<?php


class Domain_Friends {

    public function addFriends($FromID,$ToID) {
        $model = new Model_MiniFriends();
        return $model->addFriends($FromID,$ToID);
    }

    public function delFriends($FromID,$ToID) {
        $model = new Model_MiniFriends();
        return $model->delFriends($FromID,$ToID);
    }

    public function getFriendsInfo($FromID) {
        $model = new Model_MiniFriends();
        return $model->getFriendsInfo($FromID);
    }

    public function addFriendsByUsername($FromID,$ToUsername)
    {
        $model = new Model_MiniFriends();
        return $model->addFriendsByUsername($FromID,$ToUsername);
    }

}
