<?php
/**
 * 活动接口类
 * 
 * @author dogstar <chanzonghuang@gmail.com> 20150517
 */
/**
 * Created by PhpStorm.
 * User: weifeng
 * Date: 2017/6/30
 * Time: 16:37
 */
class Api_Act extends PhalApi_Api
{

    public function getRules()
    {
        /*接口参数说明：除register,login两个接口外，其余所有接口的请求均需要附带UserID和token*/
        return array(
            /*用户注册接口*/
            'register' => array(
                'UserID' => array(
                    'name' => 'UserID', 'type' => 'int', 'default' => 0, 'require' => false,
                ),
                'token' => array(
                    'name' => 'token', 'type' => 'string', 'default' => '', 'require' => false,
                ),
                'username' => array('name' => 'username', 'require' => true,'min' => 6,'max' => 20),
                'password' => array('name' => 'password', 'require' => true,'min' => 6,'max' => 20),
                'nickname' => array('name' => 'nickname', 'require' => false),
                'QQ' => array('name' => 'QQ', 'require' => false),
                'phone_number' => array('name' => 'phone_number', 'require' => false),
                'self_introduction' => array('name' => 'self_introduction', 'require' => false),
                'tags' => array('name' => 'tags', 'require' => false),
            ),
            /*用户登录接口*/
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




            /*获取标签接口*/
            'getTags' => array(

            ),
            /*插入标签接口*/
            'insertTags' => array(
                'tagIDs' => array('name' => 'tagIDs', 'require' => true,'type' =>'array'),
            ),





            /*更新用户参数接口*/
            'updatePersonInfo' => array(
                'UserID' => array('name' => 'UserID', 'require' => true),
                'nickname' => array('name' => 'nickname', 'require' => false),
                'QQ' => array('name' => 'QQ', 'require' => false),
                'phone_number' => array('name' => 'phone_number', 'require' => false),
                'self_introduction' => array('name' => 'self_introduction', 'require' => false),
                'tags' => array('name' => 'tags', 'require' => false),
            ),
            /*获取用户个人信息接口*/
            'getPersonInfo' => array(
                'UserID' => array('name' => 'UserID', 'require' => true),
            ),
            /*获取用户个人所有的有效泡泡接口*/
            'getPersonAllvalidBubbles' => array(
                'UserID' => array('name' => 'UserID', 'require' => true),
            ),
            /*插入泡泡接口*/
            'insertBubbleInfo' => array(
                'UserID' => array('name' => 'UserID', 'require' => true),
                'longtitude' => array('name' => 'longtitude', 'require' => true),
                'latitude' => array('name' => 'latitude', 'require' => true),
                'bu_question' => array('name' => 'bu_question', 'require' => true),
                'bu_answer' => array('name' => 'bu_answer', 'require' => true),
                'sex' => array('name' => 'sex', 'require' => false),
            ),
            /*由Uid获取泡泡信息接口*/
            'getBubbleInfobyUid' => array(
               'UserID' => array('name' => 'UserID', 'require' => true),
            ),
            /*由Bid获取泡泡信息接口*/
            'getBubbleInfobyBid' => array(
                'BubbleID' => array('name' => 'BubbleID', 'require' => true),
            ),
            /*获取周遭泡泡信息接口*/
            'getAroundInfo' => array(
                'UserID' => array('name' => 'UserID', 'require' => true),
                'distance' => array('name' => 'distance', 'require' => true),
                'longtitude' => array('name' => 'longtitude', 'require' => true),
                'latitude' => array('name' => 'latitude', 'require' => true),
            ),






            /*获取问题-答案匹配信息接口*/
            'getMatchPercnt' => array(
                'UserID' => array('name' => 'UserID', 'require' => true),
                'BubbleID' => array('name' => 'BubbleID', 'require' => true),
                'Useranswer' => array('name' => 'Useranswer', 'require' => true),
            ),
            /*添加好友接口*/
            'addFriendsByUid' => array(
                'FromID' => array('name' => 'FromID', 'require' => true),
                'ToID' => array('name' => 'ToID', 'require' => true),
            ),
            /*添加好友接口*/
            'addFriendsByUsername' => array(
                'FromID' => array('name' => 'FromID', 'require' => true),
                'ToUsername' => array('name' => 'ToUsername', 'require' => true),
            ),
            /*获取好友接口*/
            'getFriendsInfo' => array(
                'FromID' => array('name' => 'FromID', 'require' => true),
            ),
            /*删除好友接口*/
            'delFriends' => array(
                'FromID' => array('name' => 'FromID', 'require' => true),
                'ToID' => array('name' => 'ToID', 'require' => true),
            ),
        );
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

    public function getTags()
    {
        Di()->userLite->check(true);
        $domain = new Domain_Bubble();
        return $domain->getTags($this->UserID);
    }

    public function insertTags()
    {
        Di()->userLite->check(true);
        $domain = new Domain_Bubble();
        return $domain->insertTags($this->UserID,$this->tagIDs);
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

    public function addFriendsByUid()
    {
        Di()->userLite->check(true);
        $domain = new Domain_Friends();
        return $domain->addFriendsByUid($this->FromID, $this->ToID);
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
