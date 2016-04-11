<?php
namespace AuLait\Test;

use AuLait\View;

class ViewTest extends \PHPUnit_Framework_TestCase
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
