<?php
/**
 * 活动接口类
 * 
 * @author dogstar <chanzonghuang@gmail.com> 20150517
 */

class Api_Act extends PhalApi_Api
{

    public function getRules()
    {
        return array(
            //0表示成功，1表示失败
            'register' => array(
                'UserID' => array(
                    'name' => 'UserID', 'type' => 'int', 'default' => 0, 'require' => false,
                ),
                'token' => array(
                    'name' => 'token', 'type' => 'string', 'default' => '', 'require' => false,
                ),
                'username' => array('name' => 'username', 'require' => true,'min' => 6,'max' => 20),
                'password' => array('name' => 'password', 'require' => true),
                'nickname' => array('name' => 'nickname', 'require' => false),
                'QQ' => array('name' => 'QQ', 'require' => false),
                'phone_number' => array('name' => 'phone_number', 'require' => false),
                'self_introduction' => array('name' => 'self_introduction', 'require' => false),
                'tags' => array('name' => 'tags', 'require' => false),
            ),

            //return array('code'=>0,'msg'=>0,'UserID'=>0);

            'login' => array(
                'UserID' => array(
                    'name' => 'UserID', 'type' => 'int', 'default' => 0, 'require' => false,
                ),
                'token' => array(
                    'name' => 'token', 'type' => 'string', 'default' => '', 'require' => false,
                ),
                'username' => array('name' => 'username', 'require' => true),
                'password' => array('name' => 'password', 'require' => true),
            ),
            //return array('code'=>0,'msg'=>0,'UserID'=>0,numofBubbles=>);


            'updatePersonInfo' => array(
                'UserID' => array('name' => 'UserID', 'require' => true),
                'nickname' => array('name' => 'nickname', 'require' => false),
                'QQ' => array('name' => 'QQ', 'require' => false),
                'phone_number' => array('name' => 'phone_number', 'require' => false),
                'self_introduction' => array('name' => 'self_introduction', 'require' => false),
                'tags' => array('name' => 'tags', 'require' => false),
            ),
            //return array('code'=>0,'msg'=>0,'upret'=>0);


            'getPersonInfo' => array(
                'UserID' => array('name' => 'UserID', 'require' => true),
            ),
            //return array('code'=>0,'msg'=>0,'data'=>);


            'getPersonAllvalidBubbles' => array(
                'UserID' => array('name' => 'UserID', 'require' => true),
            ),


            'insertBubbleInfo' => array(
                'UserID' => array('name' => 'UserID', 'require' => true),
                'longtitude' => array('name' => 'longtitude', 'require' => true),
                'latitude' => array('name' => 'latitude', 'require' => true),
                'bu_question' => array('name' => 'bu_question', 'require' => true),
                'bu_answer' => array('name' => 'bu_answer', 'require' => true),
                'sex' => array('name' => 'sex', 'require' => false),
            ),
            //return array('code'=>0,'msg'=>0,'BubbleID'=>0);


            'getBubbleInfobyUid' => array(
               'UserID' => array('name' => 'UserID', 'require' => true),
            ),
            //return array('code'=>0,'msg'=>0,'data'=>0);

            'getBubbleInfobyBid' => array(
                'BubbleID' => array('name' => 'BubbleID', 'require' => true),
            ),

            'getAroundInfo' => array(
                'UserID' => array('name' => 'UserID', 'require' => true),
                'distance' => array('name' => 'distance', 'require' => true),
                'longtitude' => array('name' => 'longtitude', 'require' => true),
                'latitude' => array('name' => 'latitude', 'require' => true),
            ),
            //return array('code'=>0,'msg'=>0,'BubbleCount'=>0,'data'=>0);


            'getMatchPercnt' => array(
                'UserID' => array('name' => 'UserID', 'require' => true),
                'BubbleID' => array('name' => 'BubbleID', 'require' => true),
                'Useranswer' => array('name' => 'Useranswer', 'require' => true),
            ),

            'createTags' => array(
                'content' => array('name' => 'content', 'require' => true),
            ),

            'addFriends' => array(
                'FromID' => array('name' => 'FromID', 'require' => true),
                'ToID' => array('name' => 'ToID', 'require' => true),
            ),
            //return array('code'=>0,'msg'=>0,'matchpercent'=>0,'left_to_stick'=>0);

            'addFriendsByUsername' => array(
                'FromID' => array('name' => 'FromID', 'require' => true),
                'ToUsername' => array('name' => 'ToUsername', 'require' => true),
            ),

            'getFriendsInfo' => array(
                'FromID' => array('name' => 'FromID', 'require' => true),
            ),


            'delFriends' => array(
                'FromID' => array('name' => 'FromID', 'require' => true),
                'ToID' => array('name' => 'ToID', 'require' => true),
            ),


        );
    }

    public function createTags()
    {
        Di()->userLite->check(true);
        $domain = new Domain_Bubble();
        return $domain->createTags($this->content);
    }





    public function register()
    {
        $rs = array('code' => 0, 'msg' => 0, 'UserID' => 'valid');
        $username = $this->username;
        $domain = new Domain_Mini();
        if ($domain->isExists($username)) {
            $rs['code'] = 1;
            $rs['msg'] = 'the username existed';
            return $rs;
        }
        $data = array(
            'username' => $this->username,
            'password' => $this->password,
            'nickname' => $this->nickname,
            'QQ' => $this->QQ,
            'phone_number' => $this->phone_number,
            'self_introduction' => $this->self_introduction,
            'tags' => $this->tags,
        );
        return $domain->register($data);
    }

    public function login()
    {
        $domain = new Domain_Mini();
        return $domain->login($this->username, $this->password);
    }

    public function updatePersonInfo()
    {
        Di()->userLite->check(true);
        $domain = new Domain_Mini();
        $data = array(
            'nickname' => $this->nickname,
            'QQ' => $this->QQ,
            'phone_number' => $this->phone_number,
            'self_introduction' => $this->self_introduction,
            'tags' => $this->tags,
        );
        return $domain->updatePersonInfo($this->UserID, $data);
    }

    public function getPersonInfo()
    {
        Di()->userLite->check(true);
        $domain = new Domain_Mini();
        return $domain->getPersonInfo($this->UserID);
    }


    public function getPersonAllvalidBubbles()
    {
        Di()->userLite->check(true);
        $domain = new Domain_Bubble();
        return $domain->getPersonAllvalidBubbles($this->UserID);
    }



    public function insertBubbleInfo()
    {
        Di()->userLite->check(true);
        $data = array(
            'UserID' => $this->UserID,
            'longtitude' => $this->longtitude,
            'latitude' => $this->latitude,
            'bu_question' => $this->bu_question,
            'bu_answer' => $this->bu_answer,
            'sex' => $this->sex,
        );

        $domain = new Domain_Bubble();

        return $domain->insertBubbleInfo($data);
    }


    public function getBubbleInfobyUid()
    {
        Di()->userLite->check(true);
        $domain = new Domain_Bubble();
        return $domain->getBubbleInfo($this->UserID);
    }

    public function getBubbleInfobyBid()
    {
        Di()->userLite->check(true);
        $domain = new Domain_Bubble();
        return $domain->getBubbleInfobyBid($this->BubbleID);
    }

    public function getAroundInfo()
    {
        Di()->userLite->check(true);
        $domain = new Domain_Bubble();
        return $domain->getAroundInfo($this->UserID, $this->distance, $this->longtitude, $this->latitude);
    }


    public function getMatchPercnt()
    {
        Di()->userLite->check(true);
        $domain = new Domain_Bubble();
        return $domain->getMatchPercnt($this->UserID, $this->BubbleID, $this->Useranswer);
    }


    public function addFriends()
    {
        Di()->userLite->check(true);
        $domain = new Domain_Friends();
        return $domain->addFriends($this->FromID, $this->ToID);
    }

    public function addFriendsByUsername()
    {
        Di()->userLite->check(true);
        $domain = new Domain_Friends();
        return $domain->addFriendsByUsername($this->FromID, $this->ToUsername);
    }




    public function delFriends()
    {
        Di()->userLite->check(true);
        $domain = new Domain_Friends();
        return $domain->delFriends($this->FromID, $this->ToID);
    }

    public function getFriendsInfo()
    {
        Di()->userLite->check(true);
        $domain = new Domain_Friends();
        return $domain->getFriendsInfo($this->FromID);
    }


}
