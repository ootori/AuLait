<?php
namespace AuLait\View;
use AuLait\DependencyInjection;

class Helper
{
    /** @var DependencyInjection */
    protected $di = null;

    /** @var array  */
    protected $methods = [];

    /**
     * @param DependencyInjection $di
     */
    public function __construct(DependencyInjection $di)
    {
        $this->di = $di;
    }

    /**
     * @param string $value
     * @return string
     */
    public function sanitize($value)
    {
        // TODO: securityなかったら自分でセットしたほうがよいかも？
        return $this->di->share('security')->sanitize($value);
    }

    /**
     * @param string $name
     * @param array $params
     * @param bool $sanitize
     * @return string
     */
    public function route($name, array $params = [], $sanitize = true)
    {
        $url = $this->di->share('router')->generate($name, $params);
        if ($sanitize) {
            $url = $this->sanitize($url);
        }
        return $url;
    }

    /**
     * @param array $messages
     * @return string
     */
    public function error($messages)
    {
        // TODO: HTMLがここに書いてあるのは微妙な感じがする
        $error = '';
        foreach ($messages as $message) {
            $error .= sprintf(
                '<span class="error-messsage">%s</span>',
                $this->di->share('security')->sanitize($message)
            );
        }

        return $error;
    }

    public function addMethod($key, $callable)
    {
        $this->methods[$key] = $callable;
    }

    public function __call($name , array $arguments )
    {
        return $this->methods[$name]($arguments);
    }
}
