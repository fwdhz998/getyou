<?php
/**
 * Created by PhpStorm.
 * User: weifeng
 * Date: 2017/6/30
 * Time: 16:37
 */
require_once '/home/ubuntu/miniProj/Vote/Model/qcloudapi-sdk-php-master/src/QcloudApi/QcloudApi.php';
require_once '/home/ubuntu/henrygao/AipNlp.php';

const APP_ID = '9841462';
const API_KEY = 'BlG29W9z2xm0Db7wcUsacbA2';
const SECRET_KEY = 'lTelwMqELoMdpMWG9G75rbLTtVrBGhM5';


class Model_Minimatch extends PhalApi_Model_NotORM
{
    private $max_to_stick=3;

    protected function getTableName($id = null)
    {
        return 'match';
    }

    public function  isMatchValid($UserID,$BubbleID){
        $row=$this->getORM()
            ->where('UserID', $UserID)
            ->where('BubbleID', $BubbleID)
            ->count('left_to_stick');
        $row=(int)$row;
        if($row===0)
            return true;
        $left=$this->getORM()
            ->select('left_to_stick,isExist')
            ->where('UserID', $UserID)
            ->where('BubbleID', $BubbleID)
            ->fetch();
        $lefttostick=(int)$left['left_to_stick'];
        $isExist=(int)$left['isExist'];
        if($lefttostick===0||$isExist===0)
            return false;
        return true;
    }


    public function  getMatchPercnt($UserID,$BubbleID,$Useranswer){

        //第一次回答问题时，插入表。
        $row=$this->getORM()
            ->where('UserID', $UserID)
            ->where('BubbleID', $BubbleID)
            ->count('left_to_stick');
        if((int)$row===0){
            $insdata=array(
                'UserID'=>$UserID,
                'BubbleID'=>$BubbleID,
                'left_to_stick'=>$this->max_to_stick,
            );


            $this->getORM()->insert($insdata);
        }


        $left=$this->getORM()
            ->select('left_to_stick')
            ->where('UserID', $UserID)
            ->where('BubbleID', $BubbleID)
            ->fetch();
        $lefttostick=$left['left_to_stick'];
        $res=array('code'=>0,'msg'=>0,'matchpercent'=>0,'ToUserID'=>0,'ToUsername'=>0,'left_to_stick'=>0);
        //已经达到最大戳破次数
        if((int)$lefttostick===0){
            $res['code']=1;
            $res['msg']='No more than '.$this->max_to_stick.' times to try';
            return $res;
        }

        $this->getORM()
            ->where('UserID', $UserID)
            ->where('BubbleID', $BubbleID)
            ->update(array('left_to_stick'=>$lefttostick-1));




        $model= new Model_MiniBubble();
        $r=$model->getORM()
            ->select('bu_question,bu_answer,UserID')
            ->where('BubbleID', $BubbleID)
            ->fetchOne();



        $usermodel=new Model_MiniUser();
        $ToUsername=$usermodel->getORM()
            ->select('username')
            ->where('UserID', $r['UserID'])
            ->fetchOne()['username'];

        $bu_question=$r['bu_question'];
        $bu_answer=$r['bu_answer'];


        $res['ToUsername']=$ToUsername;
        $res['ToUserID']=$r['UserID'];
        $res['left_to_stick']=$lefttostick-1;

        $catmodel= new Model_MiniCategory();
        $catmodel->insertCategorys($UserID,$Useranswer);

        $matchpercent=$this->match($bu_question,$bu_answer,$Useranswer);
        $res['matchpercent']=$matchpercent;
        /*
        if($matchpercent<0.1){
            $res['msg']='看来你不够懂ta喔~';
        }else if($matchpercent<0.4)
            $res['msg']='接近ta的想法了~再想想看~';
        else if($matchpercent<0.5)
            $res['msg']='只差一丢丢就get到ta了~再想想看~';
        else
            $res['msg']='你已经get到ta了';
        return $res;*/

       if($matchpercent===-1){
           $res['msg']='看来你不够懂ta喔~';
       }else if($matchpercent===0)
           $res['msg']='接近ta的想法了~再想想看~';
       else if($matchpercent===1) {
           $res['msg'] = '你已经get到ta了';
           $this->getORM()
               ->where('UserID', $UserID)
               ->where('BubbleID', $BubbleID)
               ->update(array('isExist'=>0));
       }
        /*
       if($matchpercent===0||$matchpercent===1){
           $this->getORM()
               ->where('UserID', $UserID)
               ->where('BubbleID', $BubbleID)
               ->update(array('isExist'=>0));
       }*/

       return $res;

    }

    /*情感分析*/
    public function match($bu_question,$bu_answer,$Useranswer)
    {
        $ask=$this->sentimentClassfy($bu_answer);
        $answer=$this->sentimentClassfy($Useranswer);
        if($ask*$answer<0)
            return -1;//不匹配
        else if($answer>=$ask)
            return 1;//强
        else
            return 0;//弱
    }



    public  function sentimentClassfy($content){

        $API_TOKEN = "YpCHEPzX.16184.FQ0pKHDBl85q";
        $SENTIMENT_URL = 'http://api.bosonnlp.com/sentiment/analysis';
        $data = array($content);
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $SENTIMENT_URL,
            CURLOPT_HTTPHEADER => array(
                "Accept:application/json",
                "Content-Type: application/json",
                "X-Token: $API_TOKEN",
            ),
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data, JSON_UNESCAPED_UNICODE),
            CURLOPT_RETURNTRANSFER => true,
        ));

        $result = curl_exec($ch);
        //var_dump(json_decode($result));
        $res=json_decode($result);
        curl_close($ch);
        return $res[0][0]>$res[0][1]?$res[0][1]:((-1)*$res[0][1]);
    }



    /*推荐算法*/
    public function recommendation($UserID_A,$Users)
    {
       if(count($Users)<10)
           return $Users;
        $tagmodel=new Model_MiniTags();
        $catmodel=new Model_MiniCategory();
        $data=array();
        foreach ($Users as $UserID_B){
            $res_one=$tagmodel->getMatchTags($UserID_A,$UserID_B);
            $res_two=$catmodel->getMatchCategorys($UserID_A,$UserID_B);
            $data[]=array(
                "UserID"=>$UserID_B,
                "res_one"=>$res_one,
                "res_two"=>$res_two,
            );
        }
        $sum_one=0.0;
        $sum_two=0.0;
        foreach ($data as $v){
            $sum_one+=$v['res_one'];
            $sum_two+=$v['res_two'];
        }
        if($sum_one<0.00001||$sum_two<0.00001) return $Users;
        foreach ($data as $v){
            $v['res_one']=$v['res_one']/$sum_one;
            $v['res_two']=$v['res_two']/$sum_two;
        }
        usort($data,$this->sortByresult());
        $final=array_slice($data, 0, 10);
        $res=array();
        foreach ($final as $value){
            $res[]=$value['UserID'];
        }
        return $res;
    }

    public function sortByresult($a,$b){
        //return max($a['res_one'],$a['res_two'])<max($b['res_one'],$b['res_two']);
        return max((0.4*$a['res_one']),(0.6*$a['res_two']))<max((0.4*$b['res_one']),(0.6*$b['res_two']));
    }


    //Baidu API
    public function  matchwithNLP($bu_answer,$Useranswer){
        $aipNlp = new AipNlp(APP_ID, API_KEY, SECRET_KEY);
        $option = array();
        $option['model']='BOW';
        $res=$aipNlp->simnet($bu_answer, $Useranswer, $option);
        return $res['score'];
    }

    //Qcloud API
    public function getfenci($content){
        $config = array('SecretId'       => 'AKIDGK8gy5cZv1rSouwLo34q6D5h3A1diHH1',
            'SecretKey'      => 'g8LJ4dx2sTq44qNfiW6RXO1QaVBcppa5',
            'RequestMethod'  => 'POST',
            'DefaultRegion'  => 'gz');

        $wenzhi = QcloudApi::load(QcloudApi::MODULE_WENZHI, $config);
        $package = array("text"=>$content, 'code' => "2097152");
        $a = $wenzhi->LexicalAnalysis($package);
        if ($a === false) {
            $error = $wenzhi->getError();
            echo "Error code:" . $error->getCode() . ".\n";
            echo "message:" . $error->getMessage() . ".\n";
            echo "ext:" . var_export($error->getExt(), true) . ".\n";
        }
        $res=json_decode($wenzhi->getLastResponse(),true);

        $data=array();
        foreach ($res['tokens'] as $key){
            $data[]=$key['word'];
            //echo $key['word']."\n";
        }
        return $data;
    }

    //余弦距离
    public function matchBansUans($bu_question,$Useranswer){
        $data_que=$this->getfenci($bu_question);
        $data_ans=$this->getfenci($Useranswer);
        $cnt=0;
        foreach ($data_que as $key_que){
            if(in_array($key_que,$data_ans)){
                $cnt+=1;
            }
        }

        $cnt1=0;
        foreach ($data_ans as $key_ans){
            if(in_array($key_ans,$data_que)){
                $cnt1+=1;
            }
        }
        $res=(float)(min($cnt,$cnt1))/sqrt((count($data_que)*count($data_ans)))*1.0;
        return $res;
    }

    public function matchBqueUans($bu_answer,$Useranswer){
        return rand(0,100)/100;
    }


}

