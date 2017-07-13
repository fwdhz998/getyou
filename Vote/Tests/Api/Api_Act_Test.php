<?php
/**
 * PhpUnderControl_ApiAct_Test
 *
 * 针对 ../../Api/Act.php Api_Act 类的PHPUnit单元测试
 *
 * @author: dogstar 20150516
 */

require_once dirname(__FILE__) . '/../test_env.php';

if (!class_exists('Api_Act')) {
    require dirname(__FILE__) . '/../../Api/Act.php';
}

class PhpUnderControl_ApiAct_Test extends PHPUnit_Framework_TestCase
{
    public $apiAct;

    protected function setUp()
    {
        parent::setUp();

        $this->apiAct = new Api_Act();
    }

    protected function tearDown()
    {
    }


    /**
     * @group testGetRules
     */ 
    public function testGetRules()
    {
        $rs = $this->apiAct->getRules();
        $this->assertNotEmpty($rs);
        $this->assertTrue(is_array($rs));
    }

    /**
     * @group testJoinIn
     */ 
    public function testJoinIn()
    {
        //Step 1. 构建请求URL
        $url = 'service=Act.JoinIn';
        $params = array(
            'sign' => 'phalapi',
            'team_name' => 'test team name',
            'user_id' => '1',
            'token' => '193CE82D1F4588A9A168BDE6E6B83868B1464F523D16C05206F308E51EB91731',
        );

        DI()->notorm->team->where('team_name', $params['team_name'])->delete();

        //Step 2. 执行请求	
        $rs = PhalApiTestRunner::go($url, $params);
        //var_dump($rs);

        //Step 3. 验证
        $this->assertNotEmpty($rs);
        $this->assertArrayHasKey('code', $rs);
        $this->assertArrayHasKey('team_id', $rs);
        $this->assertEquals(0, $rs['code']);
        $this->assertGreaterThan(0, $rs['team_id']);

        //create again
        $rs = PhalApiTestRunner::go($url, $params);
        $this->assertEquals(1, $rs['code']);
    }

    /**
     * @group testShowList
     */ 
    public function testShowList()
    {
        //Step 1. 构建请求URL
        $url = 'service=Act.ShowList&sign=phalapi&user_id=1&token=193CE82D1F4588A9A168BDE6E6B83868B1464F523D16C05206F308E51EB91731';

        //Step 2. 执行请求	
        $rs = PhalApiTestRunner::go($url);
        //var_dump($rs);

        //Step 3. 验证
        $this->assertNotEmpty($rs);
        $this->assertArrayHasKey('code', $rs);
        $this->assertArrayHasKey('teams', $rs);
        $this->assertEquals(0, $rs['code']);

        $this->assertNotEmpty($rs['teams']);
        foreach ($rs['teams'] as $team) {
            $this->assertArrayHasKey('id', $team);
            $this->assertArrayHasKey('team_name', $team);
            $this->assertArrayHasKey('vote_num', $team);

            $this->assertGreaterThanOrEqual(0, $team['vote_num']);
        }
    }

    /**
     * @group testVote
     */ 
    public function testVote()
    {
        //Step 1. 构建请求URL
        $url = 'service=Act.Vote';
        $params = array(
            'sign' => 'phalapi',
            'team_id' => '3',
            'user_id' => '1',
            'token' => '193CE82D1F4588A9A168BDE6E6B83868B1464F523D16C05206F308E51EB91731',
        );

        DI()->cache->delete('user_daily_vote_1' . date('Ymd', $_SERVER['REQUEST_TIME']));

        //Step 2. 执行请求	
        $rs = PhalApiTestRunner::go($url, $params);
        //var_dump($rs);

        //Step 3. 验证
        $this->assertNotEmpty($rs);
        $this->assertArrayHasKey('code', $rs);
        $this->assertArrayHasKey('vote_num', $rs);
        $this->assertEquals(0, $rs['code']);
        $this->assertGreaterThan(0, $rs['vote_num']);

        //vote again and again
        $rs = PhalApiTestRunner::go($url, $params);
        $this->assertEquals(0, $rs['code']);
        $rs = PhalApiTestRunner::go($url, $params);
        $this->assertEquals(0, $rs['code']);
        $rs = PhalApiTestRunner::go($url, $params);
        $this->assertEquals(2, $rs['code']);

        //no this team
        $params['team_id'] = 404;
        $rs = PhalApiTestRunner::go($url, $params);
        $this->assertEquals(1, $rs['code']);
    }

}
