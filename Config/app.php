<?php
/**
 * 请在下面放置任何您需要的应用配置
 */

return array(

    /**
     * 应用接口层的统一参数
     */
    'apiCommonRules' => array(
        //'sign' => array('name' => 'sign', 'require' => false),

        //登录信息
        'UserID' => array(
            'name' => 'UserID', 'type' => 'int', 'default' => 0, 'require' => true,
        ),
        'token' => array(
            'name' => 'token', 'type' => 'string', 'default' => '', 'require' => true,
        ),
    ),

    'max_daily_vote_times' => 3,
);
