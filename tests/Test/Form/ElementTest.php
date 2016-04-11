<?php
namespace AuLait\Test\Form;

use AuLait\DependencyInjection;
use AuLait\Form\Element;
use AuLait\Form\Text;
use AuLait\Security;
use AuLait\Validator\Required;

class ElementTest extends \PHPUnit_Framework_TestCase
{
    static public function providerGetValue()
    {
        return [
            [
                '1000',
                '1000'
            ]
        ];
    }

    /**
     * @dataProvider providerGetValue
     * @throws \Exception
     */
    public function testGetValue($value, $expected)
    {
        $name = 'test';
        $text = new Element($name);
        $text->setValue($value);
        $this->assertEquals($expected, $text->getValue());
    }

    static public function providerValidator()
    {
        return [
            [
                '1000',
                true,
                []
            ],
            [
                null,
                false,
                ['必須入力です']
            ]
        ];
    }

    /**
     * @dataProvider providerValidator
     * @throws \Exception
     */
    public function testValidator($value, $expected1, $expected2)
    {
        $name = new Text(
            'test',
            null
        );
        $name->addValidator(
            new Required()
        );
        $name->setValue($value);

        $result1 = $name->validate();
        $this->assertEquals($expected1, $result1);


        $result2 = $name->getErrors();
        $this->assertEquals($expected2, $result2);
    }


    static public function providerRender()
    {
        return [
            [
                '1000',
                [],
                '<input type="text" name="test" value="1000"/>'
            ],
            [
                'abc',
                ['class' => 'test'],
                '<input type="text" name="test" value="abc" class="test"/>'
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
        $text = new Element($name);
        $text->setDI($di);
        $text->setValue($value);

        ob_start();
        $text->render($params);
        $result = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($expected, $result);
    }
}
