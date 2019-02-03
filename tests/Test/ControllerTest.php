<?php

namespace AuLait\Test;

use AuLait\Controller;

class ControllerTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        if (!extension_loaded('xdebug')) {
            $this->markTestSkipped('xdebug module is not installed.');
        }
    }

    /**
     * @runInSeparateProcess
     */
    public function testRedirect()
    {
        $controller = new Controller();
        $controller->redirect('http://sample.com/');
        $this->assertContains('Location: http://sample.com/', xdebug_get_headers());
    }
}
