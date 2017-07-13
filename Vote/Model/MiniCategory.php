<?php
/**
 * Created by PhpStorm.
 * User: weifeng
 * Date: 2017/6/30
 * Time: 16:37
 */
require_once '/home/ubuntu/miniProj/Vote/Model/qcloudapi-sdk-php-master/src/QcloudApi/QcloudApi.php';
class Model_MiniCategory extends PhalApi_Model_NotORM
{
    private $remaintime=604800;

    protected function getTableName($id = null)
    {
        return 'Category';
    }

    public function insertCategorys($UserID,$content){
        $config = array('SecretId'       => 'AKIDGK8gy5cZv1rSouwLo34q6D5h3A1diHH1',
            'SecretKey'      => 'g8LJ4dx2sTq44qNfiW6RXO1QaVBcppa5',
            'RequestMethod'  => 'POST',
            'DefaultRegion'  => 'gz');
        $wenzhi = QcloudApi::load(QcloudApi::MODULE_WENZHI, $config);
        $package = array(
            "content"=>$content,
            "title"=>$content,
        );

        $a = $wenzhi->TextClassify($package);
        if ($a === false) {
            $error = $wenzhi->getError();
            echo "Error code:" . $error->getCode() . ".\n";
            echo "message:" . $error->getMessage() . ".\n";
            echo "ext:" . var_export($error->getExt(), true) . ".\n";
        }

        $res=json_decode($wenzhi->getLastResponse(),true);

        $maxval=-1;
        $maxclassnum=0;

        foreach($res['classes'] as $value){
            if((float)$value['conf']>(float)$maxval){
                $maxval=$value['conf'];
                $maxclassnum=$value['class_num'];
            }
        }
        if($maxclassnum===0)//未知分类时，不插入表
            return;

        $data=array("UserID"=>$UserID,"categoryID"=>$maxclassnum,"tc_create_time"=>date("Y-m-d h:i:s"));
        $this->getORM()->insert($data);
    }

    public function getMatchCategorys($UserID_A,$UserID_B)
    {
        $catogorys_A_f=$this->getORM()
            ->select('categoryID')
            ->where('UserID',$UserID_A)
            ->order('tc_create_time DESC')
            ->fetchAll();
        $catogorys_A=array();
        foreach ($catogorys_A_f as $value){
            if(strtotime (date("Y-m-d h:i:s"))-strtotime($value['tc_create_time'])<$this->remainntime) {
                $catogorys_A[] = $value['categoryID'];
            }
        }

        $catogorys_B_f=$this->getORM()
            ->select('categoryID')
            ->where('UserID',$UserID_B)
            ->order('tc_create_time DESC')
            ->fetchAll();
        $catogorys_B=array();
        foreach ($catogorys_B_f as $value){
            if(strtotime (date("Y-m-d h:i:s"))-strtotime($value['tc_create_time'])<$this->remaintime) {
                $catogorys_B[] = $value['categoryID'];
            }
        }
        if(count($catogorys_A)==0||count($catogorys_B)==0) return 0.0;
        $arra=array_intersect($catogorys_A,$catogorys_B);
        $arrb=array_intersect($catogorys_B,$catogorys_A);

        $result=(float)(count($arra)+count($arrb))/(count($catogorys_A)+count($catogorys_B))*1.0;
        return $result;

    }







}






