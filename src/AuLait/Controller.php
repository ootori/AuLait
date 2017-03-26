<?php
namespace AuLait;

/**
 * Class Controller
 * @package AuLait
 *
 * @property \AuLait\DependencyInjection $di
 * @property \AuLait\Request $request
 * @property \AuLait\Router $router
 * @property \AuLait\Security $security
 * @property \AuLait\Session $session
 * @property \AuLait\View $view
 */
class Controller
{
    /**
     *
     */
    public function initialize() {}

    /**
     * @return bool
     */
    public function before() {
        return true;
    }

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

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        if ($key == 'di') {
            return DI::getDefault();
        }
        return DI::getDefault()->share($key);
    }
}
