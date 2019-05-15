<?php
namespace App;

use App\Helper\SysEmail;
use PhalApi\Helper\TestRunner;

/**
 * PhpUnderControl_App\Helper\User_Test
 *
 * 针对 ./src/app/Api/User.php App\Api\User 类的PHPUnit单元测试
 *
 * @author ZhangZijing <i@pluvet.com> 2019-5-15
 */
class PhpUnderControl_AppHelperSysEmail_Test extends \PHPUnit_Framework_TestCase
{
    public $appApiUser;

    protected function setUp()
    {
        parent::setUp();

        $this->appApiUser = new User();
    }

    protected function tearDown()
    { }


    /**
     * @group testGetRules
     */
    public function testGetRules()
    {
        $rs = $this->appApiUser->getRules();
        $this->assertTrue(is_array($rs));
    }
}
