<?php
namespace AuLait;

class Security
{
    /** @var DependencyInjection */
    public $di = null;
    public $sanitize_options = ENT_QUOTES | ENT_HTML5;

    public function __construct($di)
    {
        $this->di = $di;
    }

    /**
     *
     */
    public function sanitize($string)
    {
        return htmlspecialchars($string ?? "", $this->sanitize_options, 'UTF-8');
    }

    public function getCsrfToken($regenerate = false)
    {
        /** @var Session $session */
        $session = $this->di->share('session');

        $token = $session->get('csrf');
        if (!$token || $regenerate) {
            $token = $this->generateRandomNumber(32, false);
            $session->set('csrf', $token);
        }

        return $token;
    }

    public function checkCsrfToken($requestToken)
    {
        $token = $this->getCsrfToken();
        return ($token == $requestToken);
    }

    /**
     * ランダムな文字列を返す。$binaryがfalseの場合は62進数の文字列で返す。
     *
     * @param int $length
     * @param bool|true $binary
     * @return string
     */
    function generateRandomNumber($length = 32, $binary = true)
    {
        if ($binary) {
            return file_get_contents('/dev/urandom', false, null, 0, $length);
        }
        do {
            $str = file_get_contents('/dev/urandom', false, null, 0, $length * 1.5);
            $random_number = substr(str_replace(['+', '=', '/'], '', base64_encode($str)), 0, $length);
        } while (strlen($random_number) != $length);

        return $random_number;
    }
}
