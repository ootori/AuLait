<?php
namespace AuLait\Test\Mathematics;

use AuLait\Mathematics\CorrelationCoefficient;

class CorrelationCoefficientTest extends \PHPUnit_Framework_TestCase
{
    static public function providerSuccessPattern()
    {
        return [
            [
                [1.0, 0.5, 0.0],
                [1.0, 0.5, 0.0],
                1.0,
            ],
            [
                [1.0, 0.5, 0.0],
                [0.0, 0.5, 1.0],
                -1.0,
            ],
            [
                [1.0, 0.5, 0.0],
                [1.0, 0.5, 1.0],
                0.0
            ]
        ];
    }

    /**
     * @dataProvider providerSuccessPattern
     * @param $vector1
     * @param $vector2
     * @param $expected
     */
    public function testSuccessPattern($vector1, $vector2, $expected)
    {
        $function = new CorrelationCoefficient();

        $result = $function->execute($vector1, $vector2);

        $this->assertEquals($expected, $result);
    }

    static public function providerFailedPattern()
    {
        return [
            [ // �x�N�g���̒����Ⴂ
                [1.0, 0.5, 0.0],
                [1.0, 0.5]
            ],
            [ // �S���O�B���w�I�ɂO���������̂��E�E�E�P���������̂��E�E�E
                [0.0, 0.0, 0.0],
                [0.0, 0.0, 0.0]
            ]
        ];
    }

    /**
     * @dataProvider providerFailedPattern
     * @param $vector1
     * @param $vector2
     */
    public function testFailedPattern($vector1, $vector2)
    {
        $function = new CorrelationCoefficient();

        $result = $function->execute($vector1, $vector2);

        $this->assertFalse($result);
    }
}
