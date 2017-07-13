<?php
/**
 * 票数数据源类
 * 
 * - 读者可以尝试根据$teamId进行分表
 * 
 * @author dogstar <chanzonghuang@gmail.com> 20150517
 */

class Model_Vote extends PhalApi_Model_NotORM {

    protected function getTableName($id = NULL) {
        return 'vote';
    }

    public function vote($teamId) {
        $row = $this->getORM()
            ->select('vote_num')
            ->where('team_id', $teamId)
            ->fetchRow();

        $data = array(
            'team_id' => $teamId,
            'vote_num' => 1,
        );

        if (empty($row)) {
            $this->getORM()->insert($data);
        } else {
            $data['vote_num'] += $row['vote_num'];
            $this->getORM()
                ->where('team_id', $teamId)
                ->update($data);
        }

        return $data['vote_num'];
    }
}
