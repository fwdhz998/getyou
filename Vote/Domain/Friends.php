<?php


class Domain_Friends {

    public function addFriendsByUid($FromID,$ToID) {
        $model = new Model_MiniFriends();
        return $model->addFriendsByUid($FromID,$ToID);
    }

    public function addFriendsByUsername($FromID,$ToUsername)
    {
        $model = new Model_MiniFriends();
        return $model->addFriendsByUsername($FromID,$ToUsername);
    }

    public function delFriends($FromID,$ToID) {
        $model = new Model_MiniFriends();
        return $model->delFriends($FromID,$ToID);
    }

    public function getFriendsInfo($FromID) {
        $model = new Model_MiniFriends();
        return $model->getFriendsInfo($FromID);
    }


}
