<?php
namespace AuLait\Test\Form;

use AuLait\DependencyInjection;
use AuLait\Form\CheckBox;
use AuLait\Security;

class CheckBoxTest extends \PHPUnit_Framework_TestCase
{
    static public function providerRender()
    {
        return [
            [
                '1',
                [],
                '<input type="checkbox" name="test" value="1" checked="checked"/>'
            ],
            [
                'abc',
                ['class' => 'test'],
                '<input type="checkbox" name="test" value="abc" class="test" checked="checked"/>'
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
        $text = new CheckBox($name);
        $text->setDI($di);
        $text->setValue($value);

        ob_start();
        $text->render($params); //TODO: viewの方はrenderではprintしないので名前的におかしい。値返すようにする。
        $result = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($expected, $result);
    }
}
