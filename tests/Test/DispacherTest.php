<?php
namespace AuLait\Test;

use AuLait\Dispatcher;

class DispatcherTest extends \PHPUnit\Framework\TestCase
{

    public function testDispatcher()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->setControllerName('Dummy');
        $dispatcher->setActionName('get');
        $dispatcher->setParams(
            [
                'param2' => 'b',
                'param1' => 'a'
            ]
        );
        ob_start();
        $dispatcher->dispatch();
        $result = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('a, b, c', $result);
    }

    /**
     * 対象のクラスがない場合
     *
     * @expectedException \AuLait\Exception\DispatcherException
     * @expectedExceptionCode　\AuLait\Exception\DispatcherException::CODE_CLASS_NOT_FOUND
     */
    public function testDispatcherFailed()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->setControllerName('NotExistClass');
        $dispatcher->dispatch();
    }
}