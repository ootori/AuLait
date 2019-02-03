<?php
namespace AuLait\Test;

use AuLait\DependencyInjection;
use AuLait\View;
use AuLait\View\Helper;

class ViewTest extends \PHPUnit\Framework\TestCase
{
    static public function providerDisplay()
    {
        return [
            [
                null,
                'Hello World!'
            ],
            [
                'layout',
                '<html><head></head><body>Hello World!</body></html>'
            ]
        ];
    }

    public function testHelper()
    {
        $expected = 'test';

        $di = new DependencyInjection();
        $helper = new Helper($di);
        $helper->addMethod(
            'test',
            function(){
                return 'test';
            }
        );
        $view = new View();
        $view->setHelper($helper);

        $result = $view->helper->test();
        $this->assertEquals($expected, $result);
    }

    /**
     * @expectedException \AuLait\Exception\ViewException
     * @expectedExceptionCode　\AuLait\Exception\ViewException::CODE_USE_RESERVED_WORD
     */
    public function testAssignWithReservedWord()
    {
        $view = new View();
        $view->assign('this', 'Hello World!');
    }

    /**
     * @dataProvider providerDisplay
     */
    public function testDisplay($layout, $expected)
    {
        $view = new View();
        $view->setPath('./fixture/');
        $view->setLayout($layout);
        $view->assign('text', 'Hello World!');

        ob_start();
        $view->display('template');
        $result = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($expected, $result);
    }
}
