<?php
/**
 * Created by PhpStorm.
 * User: weifeng
 * Date: 2017/6/30
 * Time: 16:37
 */
class Model_MiniFriends extends PhalApi_Model_NotORM
{

    protected function getTableName($id = null)
    {
        return 'Friends';
    }

    public function isExists($FromID,$ToID){
        $ca=$this->getORM()
            ->where('FromID', $FromID)
            ->where('ToID',$ToID)
            ->count('frID');
        $cb=$this->getORM()
            ->where('FromID', $ToID)
            ->where('ToID',$FromID)
            ->count('frID');
        return $ca>0||$cb>0;
    }


    public function addFriendsByUid($FromID,$ToID)
    {
        $res=array('code'=>0,'msg'=>0,'frID'=>0);
        if($this->isExists($FromID,$ToID)){
            $res['code']=1;
            $res['msg']='The friendship exists,please change ';
            return $res;
        }

        $data=array(
            array('FromID'=>$FromID,'ToID'=>$ToID),
            array('FromID'=>$ToID,'ToID'=>$FromID),
        );

        $friends = $this->getORM();
        $ret=$friends->insert_multi($data);
        if($res){
            $res['msg']='insertFriendsInfo success';
            $res['frID']=$ret;
            return $res;
        }
        else {
            $res['code']=1;
            $res['msg']='insertFriendsInfo failure';
            return $res;
        }
    }

    public function addFriendsByUsername($FromID,$ToUsername)
    {
        $usermodel= new Model_MiniUser();
        if(!$usermodel->isExists($ToUsername)){
            $res=array('code'=>0,'msg'=>0,'frID'=>0);
            $res['code']=1;
            $res['msg']='no such Username,add friends failed';
            return $res;
        }
        $ToID=$usermodel->getORM()
            ->select('UserID')
            ->where('username', $ToUsername)
            ->fetchOne()['UserID'];
        return $this->addFriendsByUid($FromID,$ToID);
    }


    public function delFriends($FromID,$ToID) {
        $res=array('code'=>0,'msg'=>0,'ret'=>0);

        $a=$this->getORM()
            ->where('FromID', $FromID)
            ->where('ToID', $ToID)
            ->delete();

        $b=$this->getORM()
            ->where('FromID', $ToID)
            ->where('ToID', $FromID)
            ->delete();

        if($a&&$b){
            $res['ret']=array($a,$b);
            $res['msg']='delFriends success';
            return $res;
        }
        else{
            $res['code']=1;
            $res['msg']='no such friends';
            return $res;
        }
    }



    public function getFriendsInfo($FromID)
    {
        $res=array('code'=>0,'msg'=>0,'FromID'=>0,'numoffriends'=>0,'friendlist'=>NULL);

        $rows=$this->getORM()
            ->select('FromID,ToID')
            ->where('FromID', $FromID)
            ->or('ToID',$FromID)
            ->fetchAll();

        $friends=array();
        if($rows){
            foreach ($rows as $row){

                if(!array_key_exists($row['FromID'],$friends)){
                    $friends[$row['FromID']]=1;

                 }
                 if(!array_key_exists($row['ToID'],$friends)){
                    $friends[$row['ToID']]=1;
                 }
            }
            $usermodel=new Model_MiniUser();

            $friendlist=array();
            foreach ($friends as $key=>$value){
                if((int)$key===(int)$FromID) continue;

                $username=$usermodel->getORM()
                                    ->select('username')
                                    ->where('UserID',$key)
                                    ->fetch()['username'];
                $friendlist[]=array(
                    'UserID'=>$key,
                    'username'=>$username,
                );
            }
            $res['numoffriends']=count($friendlist);
            $res['friendlist']=$friendlist;
            $res['FromID']=$FromID;
            $res['msg']='getFriendsInfo success';

            return $res;
        }
        else {
            $res['code']=0;
            $res['msg']='he/she  has no friend now;';
            return  $res;
        }
    }




}

