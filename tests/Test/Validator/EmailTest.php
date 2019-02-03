<?php
namespace AuLait\Test\Form;

use AuLait\Validator\Email;

class EmailTest extends \PHPUnit\Framework\TestCase
{

    static public function providerValidator()
    {
        return [
            [
                null,
                []
            ],
            [
                'abc',
                ['有効なメールアドレスを入力してください。']
            ],
            [
                'abc@example.com',
                []
            ],

        ];
    }

    /**
     * @dataProvider providerValidator
     * @throws \Exception
     */
    public function testValidator($value, $expected)
    {
        $email = new Email();
        $this->assertEquals($expected, $email->validate($value));
    }
}