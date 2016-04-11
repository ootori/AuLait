<?php
namespace AuLait\Test;

use AuLait\Security;
use AuLait\Session;
use AuLait\DI;
use AuLait\DependencyInjection;

/**
 * @runTestsInSeparateProcesses
 */
class SecurityTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $di = new DependencyInjection();
        $di->set(
            'session',
            function () use ($di) {
                return new Session();
            }
        );
        DI::setDefault($di);
    }

    public function testCheckCsrfTokenSuccess()
    {
        $di = DI::getDefault();

        $security = new Security($di);
        $token = $security->getCsrfToken();

        $this->assertNotEmpty($token);
        $this->assertTrue($security->checkCsrfToken($token));
    }

    public function testRegenerateCsrfToken()
    {
        $di = DI::getDefault();

        $security = new Security($di);
        $token = $security->getCsrfToken();
        $newToken = $security->getCsrfToken(true);
        $this->assertNotEquals($token, $newToken);

        $currentToken = $security->getCsrfToken();
        $this->assertEquals($newToken, $currentToken);
    }

    public function testGenerateRandomBinaryNumber()
    {
        $length = 64;

        $di = DI::getDefault();
        $security = new Security($di);
        $token = $security->generateRandomNumber($length);

        // 長さの一致
        $this->assertEquals($length, strlen($token));

        // バイナリか？（urlエンコードして長さ違うか・・・妥当なのかは不明）
        $this->assertGreaterThan($length, strlen(urlencode($token)));

        // 違う数が振られているか
        $newToken = $security->generateRandomNumber($length);
        $this->assertNotEquals($token, $newToken);

    }

    public function testGenerateRandomTextNumber()
    {
        $length = 32;

        $di = DI::getDefault();
        $security = new Security($di);
        $token = $security->generateRandomNumber($length, false);

        // 長さの一致＆６２進数か
        $matching = preg_match("/\\A[A-Za-z0-9]+\\z/", $token);
        $this->assertEquals(1, $matching);

        // 違う数が振られているか
        $newToken = $security->generateRandomNumber($length, false);
        $this->assertNotEquals($token, $newToken);


    }


}
