<?php
namespace AuLait\Test;
use AuLait\DateTime;
use AuLait\Exception\DateTimeException;

class DateTimeTest extends \PHPUnit\Framework\TestCase
{

    /**
     * 現在時刻取得のテスト
     */
    public function testTime()
    {
        $expected = time();
        $dateTime = new DateTime();
        $result = $dateTime->time();
        $this->assertGreaterThanOrEqual($expected, $result);
    }

    /**
     * 時刻変更のテスト
     */
    public function testTiming()
    {
        $timing = '2016-04-01 00:00:00';
        $expected = $timing;
        $dateTime = new DateTime();
        $dateTime->setTiming($timing);
        $this->assertEquals($expected, $dateTime->date('Y-m-d H:i:s'));
    }

    /**
     * @expectedException \AuLait\Exception\DateTimeException
     * @expectedExceptionCode　\AuLait\Exception\DateTimeException::CODE_ILLEGAL_TIMING_FORMAT
     */
    public function testIllegalFormat()
    {
        $dateTime = new DateTime();
        $dateTime->setTiming("illegal format!");
    }
}
