<?php
namespace AuLait;
class Controller
{
    /**
     *
     */
    public function initialize() {}

    /**
     *
     */
    public function before() {}

    /**
     *
     */
    public function after() {}

    /**
     * リダイレクトする。
     *
     * @param $url
     */
    public function redirect($url)
    {
        // MEMO: http://blog.tokumaru.org/2015/12/phphttp.html
        header('Location: '. $url);
    }
}