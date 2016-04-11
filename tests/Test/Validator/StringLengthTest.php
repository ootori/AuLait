<?php
namespace AuLait\Test\Form;

use AuLait\Validator\StringLength;

class StringLengthTest extends \PHPUnit_Framework_TestCase
{
    static public function providerValidator()
    {
        return [
            [
                '',
                []
            ],
            [
                '0',
                ['短すぎます']
            ],
            [
                'あ',
                ['短すぎます']
            ],
            [
                '0123456789',
                []
            ],
            [
                'あいうえおかきくけこ',
                []
            ],
            [
                '01234567890',
                ['長すぎます']
            ],
            [
                'あいうえおかきくけこさ',
                ['長すぎます']
            ],

        ];
    }

    /**
     * @dataProvider providerValidator
     * @throws \Exception
     */
    public function testValidator($value, $expected)
    {
        $required = new StringLength(['min' => 3, 'max' => 10]);
        $this->assertEquals($expected, $required->validate($value));
    }
}