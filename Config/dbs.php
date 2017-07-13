<?php
/**
 * 分库分表的自定义数据库路由配置
 * 
 * @author: dogstar <chanzonghuang@gmail.com> 2015-02-09
 */

return array(
    /**
     * DB数据库服务器集群
     */
    'servers' => array(
        /**
        'db_demo' => array(
            'host'      => 'localhost',             //数据库域名
            'name'      => 'test',                  //数据库名字
            'user'      => 'root',                  //数据库用户名
            'password'  => '123456',	            //数据库密码
            'port'      => '3306',		            //数据库端口
        ),
         */
        'db_fw' => array(
            'host'      => 'localhost',         //数据库域名
            'name'      => 'MiniPro_db',          //数据库名字
            'user'      => 'root',                  //数据库用户名
            'password'  => 'qwert111',	            //数据库密码
            'port'      => '3306',		            //数据库端口
        ),
    ),

    /**
     * 自定义路由表
     */
    'tables' => array(
        'User' => array(
            'prefix' => 'Mini_',
            'key' => 'UserID',
            'map' => array(
                array('db' => 'db_fw'),
            ),
        ),
         'Bubble' => array(
                'prefix' => 'Mini_',
                'key' => 'BubbleID',
                'map' => array(
                    array('db' => 'db_fw'),
                ),
            ),
        'Category' => array(
            'prefix' => 'Mini_',
            'key' => 'id',
            'map' => array(
                array('db' => 'db_fw'),
            ),
        ),
        'match' => array(
            'prefix' => 'Mini_',
            'key' => 'MatchID',
            'map' => array(
                array('db' => 'db_fw'),
            ),
        ),
        'Friends' => array(
            'prefix' => 'Mini_',
            'key' => 'frID',
            'map' => array(
                array('db' => 'db_fw'),
            ),
        ),
        'Tags' => array(
            'prefix' => 'Mini_',
            'key' => 'id',
            'map' => array(
                array('db' => 'db_fw'),
            ),
        ),
        'user_session' => array(
            'prefix' => 'phalapi_',
            'key' => 'id',
            'map' => array(
                array('db' => 'db_fw'),
                array('start' => 0, 'end' => 9, 'db' => 'db_fw'),
            ),
        ),
        'user_login_qq' => array(
            'prefix' => 'phalapi_',
            'key' => 'id',
            'map' => array(
                array('db' => 'db_fw'),
                array('start' => 0, 'end' => 9, 'db' => 'db_fw'),
            ),
        ),
        'user' => array(
            'prefix' => 'phalapi_',
            'key' => 'id',
            'map' => array(
                array('db' => 'db_fw'),
            ),
        ),
    ),
);
