<?php
/**
 * Created by PhpStorm.
 * User: weifeng
 * Date: 2017/6/30
 * Time: 16:37
 */

class Model_MiniTags extends PhalApi_Model_NotORM
{


    protected function getTableName($id = null)
    {
        return 'Tags';
    }


    public function insertTags($UserID,$tagIDs)
    {
        $res=array('code'=>0,'msg'=>0,'data'=>0);

        $tags=array();

        foreach ($tagIDs as $ch){
            for($i=0;$i<strlen($ch);$i++){
                if ($ch[$i]!='[' and $ch[$i]!=',' and $ch[$i]!=']'){
                    $tags[]=intval($ch[$i]);
                }
            }
        }

        foreach ($tags as $tagID){
            $data=array();
            $data['UserID']=$UserID;
            $data['tagID']=$tagID;
            $data['tc_create_time']=date("Y-m-d h:i:s");
            $this->getORM()->insert($data);
        }
        $res['msg']='insertTags success';
        return $res;
    }

    public function getTags($UserID)
    {
        $res=array('code'=>0,'msg'=>0,'data'=>0);
        $tags=$this->getORM()
            ->select('tagID')
            ->where('UserID',$UserID)
            ->fetchAll();
        $data=array();
        foreach ($tags as $tag){
            $data[]=$tag['tagID'];
        }
        $res['data']=$data;
        $res['msg']='getTags success';
        return $res;
    }


    public function getMatchTags($UserID_A,$UserID_B)
    {
        $tags_A_f=$this->getORM()
            ->select('tagID')
            ->where('UserID',$UserID_A)
            ->fetchAll();
        $tags_A=array();
        foreach ($tags_A_f as $value){
            $tags_A[]=$value['tagID'];
        }

        $tags_B_f=$this->getORM()
            ->select('tagID')
            ->where('UserID',$UserID_B)
            ->fetchAll();
        $tags_B=array();
        foreach ($tags_B_f as $value){
            $tags_B[]=$value['tagID'];
        }

        $len=count(array_intersect($tags_A,$tags_B));
        return ((float)$len/10)*1.0;
    }




}

