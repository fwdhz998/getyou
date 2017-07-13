<?php
/**
 * Created by PhpStorm.
 * User: weifeng
 * Date: 2017/6/30
 * Time: 16:23
 */

class Domain_Mini {

    public function isExists($username) {
        $model = new Model_MiniUser();
        return $model->isExists($username);
    }

    public function register($data) {
        $model = new Model_MiniUser();
        return $model->insertPersonInfo($data);
    }

    public function login($username,$password) {
        $model = new Model_MiniUser();
        return $model->login($username,$password);
    }

    public function updatePersonInfo($UserID,$keyarr) {
        $model = new Model_MiniUser();
        return $model->updatePersonInfo($UserID,$keyarr);
    }

    public function getPersonInfo($UserID) {
        $model = new Model_MiniUser();
        return $model->getPersonInfo($UserID);
    }

}
