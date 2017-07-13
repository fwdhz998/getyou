<?php
/**
 * 团队数据源类
 * 
 * - 使用了关联查询进行排行榜的展示
 * 
 * @author dogstar <chanzonghuang@gmail.com> 20150517
 */

class Model_Team extends PhalApi_Model_NotORM {

    protected function getTableName($id = null) {
        return 'team';
    }

    public function isExists($teamName) {
        $num = $this->getORM()
            ->where('team_name', $teamName)
            ->count('id');

        return $num > 0 ? true : false;
    }

    public function showList() {
        $sql = 'SELECT t.id, t.team_name, v.vote_num '
            . 'FROM phalapi_team AS t LEFT JOIN phalapi_vote AS v '
            . 'ON t.id = v.team_id '
            . 'ORDER BY v.vote_num DESC';
        $rows = $this->getORM()->queryAll($sql, array());

        foreach ($rows as &$rowRef) {
            $rowRef['id'] = intval($rowRef['id']);
            $rowRef['vote_num'] = intval($rowRef['vote_num']);
        }

        return $rows;
    }

    public function isJoinIn($teamId) {
        $num = $this->getORM()
            ->where('id', $teamId)
            ->count('id');

        return $num > 0 ? true : false;
    }
}
