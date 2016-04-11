<?php
namespace AuLait;

class Security
{
    /** @var DependencyInjection */
    public $di = null;

    public function __construct($di)
    {
        $this->di = $di;
    }

    /**
     *
     */
    public function sanitize($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
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
