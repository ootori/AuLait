<?php
namespace AuLait\Test\Form;

use AuLait\DependencyInjection;
use AuLait\Form\TextArea;
use AuLait\Security;

class TextAreaTest extends \PHPUnit_Framework_TestCase
{
    static public function providerRender()
    {
        return [
            [
                '1000',
                [],
                '<textarea name="test">1000</textarea>'
            ],
            [
                'abc',
                ['class' => 'test'],
                '<textarea name="test" class="test">abc</textarea>'
            ]
        ];
    }

    /**
     * @dataProvider providerRender
     * @param $value
     * @param $params
     * @param $expected
     */
    public function testRender($value, $params, $expected)
    {
        $di = new DependencyInjection();
        $di->set('security', function () use ($di) {
            $security = new Security($di);
            return $security;
        });

        $name = 'test';
        $text = new TextArea($name);
        $text->setDI($di);
        $text->setValue($value);

        ob_start();
        $text->render($params);
        $result = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($expected, $result);
    }
}
