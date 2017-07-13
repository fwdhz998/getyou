<?php
/**
 * 团队领域业务类
 * 
 * @author dogstar <chanzonghuang@gmail.com> 20150517
 */

class Domain_Team {

    public function isExists($teamName) {
        $model = new Model_Team();
        return $model->isExists($teamName);
    }

    public function joinIn($teamName) {
        $model = new Model_Team();
        $data = array('team_name' => $teamName);
        return $model->insert($data);
    }

    public function showList() {
        $model = new Model_Team();
        return $model->showList();
    }

    public function isJoinIn($teamId) {
        $model = new Model_Team();
        return $model->isJoinIn($teamId);
    }

}
