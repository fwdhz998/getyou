<?php
/**
 * Created by PhpStorm.
 * User: weifeng
 * Date: 2017/6/30
 * Time: 16:37
 */
class Model_MiniUser extends PhalApi_Model_NotORM
{

    protected function getTableName($id = null)
    {
        return 'User';
    }
    //0标识成功，1标识失败。
    public function insertPersonInfo($keyarr=array())
    {
        $res=array('code'=>0,'msg'=>0,'UserID'=>0);

        $data=array();
        $keys=array('username','password','nickname','QQ','phone_number','self_introduction');
        foreach($keys as $key){
            $data[$key]=$keyarr[$key];
        }

        if($this->isExists($keyarr['username'])){
            $res['code']=1;
            $res['msg']='the User has been registered,please change another one';
            return $res;
        }
        $user=$this->getORM();
        $user->insert($data);
        $id = $user->insert_id();
            if($id){
                $res['UserID']=$id;
                $res['msg']='register success';
                return $res;
            }
            else{
                $res['code']=1;
                $res['msg']='insertPersonInfo failue';
                return $res;
            }
    }


    public function isExists($username)//此处可以判别任何字段
    {

        $num = $this->getORM()
            ->where('username', $username)
            ->count('UserID');

        return $num > 0 ? true : false;

    }

    public function login($username,$password)
    {
        $res=array('code'=>0,'msg'=>0,'UserID'=>0,'token'=>0,'username'=>0,'numofBubbles'=>0);
        $rows = $this->getORM()
            ->select('UserID,username')
            ->where('username', $username)
            ->where('password', $password)
            ->fetch();
        if($rows['UserID']){

            $token = Domain_User_User_Session::generate($rows['UserID']);
            $res['token']=$token;

            $res['username']=$rows['username'];
            $res['UserID']=$rows['UserID'];
            $res['msg']='login success';
            $model= new Model_MiniBubble();
            $res['numofBubbles']=$model->getPersonAllvalidBubbles($rows['UserID'])['bubbleCount'];
            return $res;
        }
        else{
            $res['code']=1;
            $res['msg']='pleass enter the right account';
            return $res;
        }

    }

    public function getPersonInfo($UserID)
    {

        $res=array('code'=>0,'msg'=>0,'data'=>0);

        $rows=$this->getORM()
            ->select('nickname,QQ,phone_number,self_introduction')
            ->where('UserID', $UserID)
            ->fetch();

        $tagmodel=new Model_MiniTags();
        $tag_all=array();
        $tags=$tagmodel->getORM()
                ->select('tagID')
                ->where('UserID',$UserID)
                ->fetchAll();
        foreach ($tags as $tag){
            $tag_all[]=$tag['tagID'];
        }


        $data=array(
            'nickname'=>$rows['nickname'],
            'QQ'=>$rows['QQ'],
            'phone_number'=>$rows['phone_number'],
            'self_introduction'=>$rows['self_introduction'],
            'tags'=>$tag_all,
        );


        if(count($rows)){
            $res['data']=$data;
            $res['msg']='showPersonInfo success';
            return $res;
        }
        else{
            $res['code']=1;
            $res['msg']='showPersonInfo failue';
            return $res;
        }

    }

    public function updatePersonInfo($UserID,$keyarr)
    {
        $res=array('code'=>0,'msg'=>0,'upret'=>0);
        $ret=$this->getORM()
            ->where('UserID',$UserID)
            ->update($keyarr);

        if($ret){
            $res['upret']=$ret;
            $res['msg']='updatePersonInfo success';
            return $res;
        }
        else{
            $res['code']=1;
            $res['msg']='updatePersonInfo failue';
            return $res;
        }
    }




}

