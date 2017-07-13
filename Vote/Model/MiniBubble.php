<?php
/**
 * Created by PhpStorm.
 * User: weifeng
 * Date: 2017/6/30
 * Time: 16:37
 */
class Model_MiniBubble extends PhalApi_Model_NotORM
{
    private $remainntime=259200;

    protected function getTableName($id = null)
    {
        return 'Bubble';
    }

    public function insertBubbleInfo($keyarr=array())
    {
        $res=array('code'=>0,'msg'=>0,'BubbleID'=>0);
        $data=$keyarr;
        $data['bu_create_time']=date("Y-m-d H:i:s");

        $bubble=$this->getORM();
	    $bubble->insert($data);
	    $bid=$bubble->insert_id();
        $catmodel=new Model_MiniCategory();
        $catmodel->insertCategorys($keyarr['UserID'],$keyarr['bu_question']);
        $catmodel->insertCategorys($keyarr['UserID'],$keyarr['bu_answer']);

        if($bid)
        {
            $res['msg']='  insertBubbleInfo success';
            $res['BubbleID']=$bid;
            return $res;
        }
        else{
            $res['code']=1;
            $res['msg']='insertBubbleInfo failue';
            return $res;
        }
    }


    public function getPersonAllvalidBubbles($UserID)
    {
        $res=array('code'=>0,'msg'=>0,'bubbleCount'=>0,'bubbleIDList'=>0);

        $lasttime=strtotime(date("Y-m-d h:i:s"))-$this->remainntime;
        $strtime=date("Y-m-d h:i:s",$lasttime);

        $rows=$this->getORM()
            ->select('BubbleID')
            ->where('bu_create_time > ?',$strtime)
            ->where('UserID', $UserID)
            ->fetchAll();

        if(count($rows)){
            $data=array();
            foreach ($rows as $row){
                $data[]=$row['BubbleID'];
            }
            $res['bubbleCount']=count($rows);
            $res['bubbleIDList']=$data;
            $res['msg']='success';
        }else
        {
            $res['code']=1;
            $res['msg']='no valid bubbles ';
        }
        return $res;
    }


    public function getAllcount(){
        $lasttime=strtotime(date("Y-m-d h:i:s"))-$this->remainntime;
        $strtime=date("Y-m-d h:i:s",$lasttime);

        $count=0;
        $sql='SELECT a.bu_create_time
              FROM Mini_Bubble a  
              WHERE a.bu_create_time = (
              SELECT max(bu_create_time)  
              FROM Mini_Bubble  
              WHERE a.UserID = UserID)';
        $rows = $this->getORM()->queryAll($sql, array());
        foreach ($rows as $row){
            if($row['bu_create_time']>$strtime){
                $count+=1;
            }
        }
        return $count;
    }


    public function getBubbleInfo($UserID)
    {
        $res=array('code'=>0,'msg'=>0,'data'=>0);
        $rows=$this->getORM()
            ->select(' longtitude,latitude,bu_question,sex')
            ->where('UserID', $UserID)
            ->order('bu_create_time DESC')
            ->fetch();
        if(count($rows)) {
            $res['data']=$rows;
            return $res;
        }
        else{
            $res['code']=1;
            $res['msg']='getBubbleInfo failue';
            return  $res;
        }

    }



    public function getBubbleInfobyBid($bid)
    {
        $res=array('code'=>0,'msg'=>0,'data'=>0);
        $rows=$this->getORM()
            ->select(' longtitude,latitude,bu_question,sex')
            ->where('BubbleID', $bid)
            ->fetch();
        if($rows['longtitude']) {
            $res['data']=$rows;
            return $res;
        }
        else{
            $res['code']=1;
            $res['msg']='There is no such bubble';
            return  $res;
        }

    }

    public function getDistance($lat1, $lng1, $lat2, $lng2){
        $earthRadius = 6367000; //approximate radius of earth in meters
        $lat1 = ($lat1 * pi() ) / 180;
        $lng1 = ($lng1 * pi() ) / 180;
        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return round($calculatedDistance);
    }

    public function getPosition($UserID)
    {
        $rows=$this->getORM()
            ->select(' longtitude,latitude')
            ->where('UserID', $UserID)
            ->order('bu_create_time DESC')
            ->fetchOne();

        $curlongtitude=(float)$rows['longtitude'];
        $curlatitude=(float)$rows['latitude'];
        return array(
            'curlongtitude'=>$curlongtitude,
            'curlatitude'=>$curlatitude,
        );
    }


    public function getAroundInfo($UserID,$distance,$longtitude,$latitude)
    {
        $res=array('code'=>0,'msg'=>0,'BubbleCount'=>0,'data'=>0,'cur_username'=>0);



        $curlongtitude=$longtitude;
        $curlatitude=$latitude;

        $sql='SELECT a.UserID,a.BubbleID, a.longtitude, a.latitude,a.bu_question,a.sex,a.bu_create_time
              FROM Mini_Bubble a  
              WHERE a.bu_create_time = (
              SELECT max(bu_create_time)  
              FROM Mini_Bubble  
              WHERE a.UserID = UserID)';
        $rows = $this->getORM()->queryAll($sql, array());
        $distance=(float)$distance;

        $matchmodel=new Model_Minimatch();
        $usermodel=new Model_MiniUser();
        $res['cur_username']= $usermodel->getORM()
            ->select('username')
            ->where('UserID',$UserID)
            ->fetch()['username'];

        $recommendation_userIds=array();
        
       foreach ($rows as $row){
           if($row['UserID']===$UserID) continue;
           $curdis=$this->getDistance((float)$row['longtitude'],(float)$row['latitude'],$curlongtitude,$curlatitude);
           if(($curdis-$distance)<0){// 1.近距离筛选
               if($matchmodel->isMatchValid($UserID,$row['BubbleID'])) {// 2.是否超过匹配次数筛选
                   if(strtotime (date("Y-m-d h:i:s"))-strtotime($row['bu_create_time'])<$this->remainntime) {//3. 是否过期筛选
                       $recommendation_userIds[]=$row['UserID'];
                       $username=$usermodel->getORM()
                           ->select('username')
                           ->where('UserID',$row['UserID'])
                           ->fetch()['username'];
                       $resdata[] = array(
                           'BubbleID' => $row['BubbleID'],
                           'UserID'=>$row['UserID'],
                           'username'=>$username,
                           'longtitude' => $row['longtitude'],
                           'latitude' => $row['latitude'],
                           'bu_question' => $row['bu_question'],
                           'sex' => $row['sex'],
                       );
                   }
               }
           }
       }
        $recom_ids=$matchmodel->recommendation($UserID,$recommendation_userIds);//4.两级tag综合过滤推荐算法-筛选
        $finaldata=array();
        foreach ($resdata as $value){
            if(in_array($value['UserID'],$recom_ids)){
                $finaldata[]=$value;
            }
        }
        $res['BubbleCount']=count($finaldata);//5.返回最终推荐的用户
        $res['data']=$finaldata;
        return $res;
    }


}

