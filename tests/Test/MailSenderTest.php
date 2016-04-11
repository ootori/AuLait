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

        // TODO: ���ܖ{���Ƀ��[�������Ă��܂��̂ő���Ȃ��悤�ɂ���iMock���) or
        // ���������̂������ƃ��[���{�b�N�X�ɓ����Ă邩�m�F���폜����B
        $mailSender = new MailSender();
        $mailSender->send(
            getenv('TEST_MAIL_FROM'),
            getenv('TEST_MAIL_TO'),
            $mail
        );
    }

    /**
     * @expectedException \AuLait\Exception\MailSenderException
     * @expectedExceptionCode�@\AuLait\Exception\MailSenderException::CODE_FAILED_TO_SEND_MAIL
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

        // ������null�ɁB
        $mailSender = new MailSender();
        $mailSender->send(
            getenv('TEST_MAIL_FROM'),
            "test@test@example.com",
            $mail
        );
    }


}
