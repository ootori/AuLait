<?php
namespace AuLait;
use AuLait\Exception\MailSenderException;

class MailSender
{
    /**
     * メール送信をします
     *
     * @param string $from 送り元メールアドレス
     * @param string $to 送り先メールアドレス
     * @param string $message メール本文（＋タイトル）
     * @throws MailSenderException
     */
    function send($from, $to, $message)
    {
        $message = str_replace("\r\n","\n", $message);
        list($subject, $body) = @explode("\n\n", $message, 2);
        mb_language('ja');
        $result = @mb_send_mail($to, $subject, $body, "From: $from", '-f'.$from);
        if ($result === false) {
            throw new MailSenderException('test', MailSenderException::CODE_FAILED_TO_SEND_MAIL);
        }
    }
}
