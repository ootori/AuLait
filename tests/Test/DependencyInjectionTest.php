<?php
namespace AuLait\Test;

use AuLait\Exception\DependencyInjectionException;
use AuLait\DependencyInjection;
use AuLait\Router;

class DependencyInjectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * DIのルールをセットできるか
     */
    public function testSet()
    {
        $di = new DependencyInjection();
        $result = $di->set(
            'router',
            function () {
                return new Router('index', '/');
            }
        );
        $this->assertEquals('AuLait\DependencyInjection', get_class($result));
    }

    /**
     * @expectedException \AuLait\Exception\DependencyInjectionException
     * @expectedExceptionCode　\AuLait\Exception\DateTimeException::CODE_INVALID_PARAMETER
     */
    public function testSetFaild()
    {
        $di = new DependencyInjection();
        $result = $di->set('router', 'not callable.');
    }

    /**
     * @depends testSet
     */
    public function testGet()
    {
        $di = new DependencyInjection();
        $di->set(
            'router',
            function () {
                return new Router('index', '/');
            }
        );
        $result1 = $di->get('router');
        $result2 = $di->get('router');
        $this->assertNotEquals(spl_object_hash($result1), spl_object_hash($result2));
    }

    /**
     * @expectedException \AuLait\Exception\DependencyInjectionException
     * @expectedExceptionCode　\AuLait\Exception\DateTimeException::CODE_NO_REGISTER_FACTORY
     */
    public function testFailed()
    {
        $di = new DependencyInjection();
        $di->get('no registered factory name');
    }


    /**
     * @depends testGet
     */
    public function testShare()
    {
        $di = new DependencyInjection();
        $di->set(
            'router',
            function () {
                return new Router('index', '/');
            }
        );
        $result1 = $di->share('router');
        $result2 = $di->share('router');
        $this->assertEquals(spl_object_hash($result1), spl_object_hash($result2));
    }


}
