<?php
namespace AuLait\Test\Validator;

use AuLait\Validator\Email;
use AuLait\Validator\NaturalNumber;

class NaturalNumberTest extends \PHPUnit\Framework\TestCase
{

    static public function providerValidator()
    {
        return [
            [
                null,
                false,
                []
            ],
            [
                '1',
                false,
                []
            ],
            [
                '-1',
                false,
                ['1以上の整数を入力してください']
            ],
            [
                '0',
                false,
                ['1以上の整数を入力してください']
            ],
            [
                '0',
                true,
                []
            ],
            [
                '-1',
                true,
                ['0以上の整数を入力してください']
            ],
        ];
    }

    /**
     * @dataProvider providerValidator
     * @throws \Exception
     */
    public function testValidator($value, $permit_zero, $expected)
    {
        $validator = new NaturalNumber(['permit_zero' => $permit_zero]);
        $this->assertEquals($expected, $validator->validate($value));
    }
}