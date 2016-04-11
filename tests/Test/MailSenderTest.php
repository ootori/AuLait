<?php
namespace AuLait\Test;
use AuLait\MailSender;
use Guzzle\Tests\Service\Command\LocationVisitor\Response\BodyVisitorTest;

class MailSenderTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testSend()
    {
        if (!getenv('TEST_MAIL_TO')) {
            $this->markTestSkipped('$_ENV["TEST_MAIL_TO"] is empty');
        }
        if (!getenv('TEST_MAIL_TO')) {
            $this->markTestSkipped('$_ENV["TEST_MAIL_FROM"] is empty');
        }

        $mail = <<<Body
Mail title

Mail body
Body;

        // TODO: いま本当にメール送ってしまうので送らないようにする（Mock作る) or
        // 送ったものがちゃんとメールボックスに入ってるか確認＆削除する。
        $mailSender = new MailSender();
        $mailSender->send(
            getenv('TEST_MAIL_FROM'),
            getenv('TEST_MAIL_TO'),
            $mail
        );
    }

    /**
     * @expectedException \AuLait\Exception\MailSenderException
     * @expectedExceptionCode　\AuLait\Exception\MailSenderException::CODE_FAILED_TO_SEND_MAIL
     */
    public function testSendFailure()
    {
        if (!getenv('TEST_MAIL_TO')) {
            $this->markTestSkipped('$_ENV["TEST_MAIL_FROM"] is empty');
        }
        $mail = <<<Body
Mail title

Mail body
Body;

        // 送り先をnullに。
        $mailSender = new MailSender();
        $mailSender->send(
            getenv('TEST_MAIL_FROM'),
            "test@test@example.com",
            $mail
        );
    }


}
