<?php
namespace AuLait;
use AuLait\Exception\SessionException;

/**
 * Class Session
 * @package AuLait
 */
class Session
{
    /**
     * @throws SessionException
     */
    public function __construct()
    {
        if (!session_start()) {
            throw new SessionException('Failed to start session');
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param $key
     * @return null
     */
    public function get($key)
    {
        if (!isset($_SESSION[$key])) {
            return null;
        }
        return $_SESSION[$key];
    }

    /**
     * @return string
     */
    public function getId()
    {
        return session_id();
    }

    /**
     * @throws SessionException
     */
    public function regenerateId()
    {
        if (!session_regenerate_id(true)) {
            throw new SessionException('Failed to regenerate session id');
        }
    }

    /**
     * @param $key
     */
    public function delete($key)
    {
        unset($_SESSION[$key]);
    }

    // TODO: 読んだらすぐにクローズする。(read_and_closeの設定)
    // TODO: $_SESSIONに突っ込まずにflash()で書き込みにする。
    // TODO: 最終アクセスから一定時間たっていたらセッションID振りなおしたほうがいいかもしれない。
}