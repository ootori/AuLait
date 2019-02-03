<?php
namespace AuLait\Test\Form;

use AuLait\Validator\Required;

class RequiredTest extends \PHPUnit\Framework\TestCase
{
    static public function providerValidator()
    {
        return [
            [
                null,
                ['必須入力です']
            ],
            [
                "",
                ['必須入力です']
            ],
            [
                0,
                ['必須入力です']
            ],
            [
                'abc',
                []
            ]

        ];
    }

    /**
     * @dataProvider providerValidator
     * @throws \Exception
     */
    public function testValidator($value, $expected)
    {
        $required = new Required();
        $this->assertEquals($expected, $required->validate($value));
    }
}