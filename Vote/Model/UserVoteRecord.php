<?php
/**
 * 用户投票纪录数据源类
 * 
 * - 使用缓存纪录当天的投票情况
 * - 不落地
 * 
 * @author dogstar <chanzonghuang@gmail.com> 20150517
 */

class Model_UserVoteRecord {

    const EXPIRE_ONE_DAY = 86400;

    public function isCanVoteToday($userId) {
        $key = $this->formatKey($userId);

        $dailyVoteTimes = DI()->cache->get($key);

        return $dailyVoteTimes < DI()->config->get('app.max_daily_vote_times') ?  true: false;
    }

    public function addTodayVoteTimes($userId, $times = 1) {
        $key = $this->formatKey($userId);

        $todayTimes = DI()->cache->get($key);
        $todayTimes = intval($todayTimes);

        DI()->cache->set($key, $todayTimes + $times, self::EXPIRE_ONE_DAY);
    }

    protected function formatKey($userId) {
        return 'user_daily_vote_' . $userId . date('Ymd', $_SERVER['REQUEST_TIME']);
    }
}
