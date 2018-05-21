<?php
namespace AuLait;

use AuLait\Exception\RouterException;

class Router
{
    protected $patterns = [];

    protected $controller = '';
    protected $params = [];

    protected $default_route = true;

    /**
     * @param string $name
     * @param string $pattern
     * @param array $options
     */
    public function add($name, $pattern, $options = [])
    {
        $this->patterns[$name] = [
            'pattern' => $pattern,
            'options' => $options
        ];
    }

    /**
     * @param string $path
     * @return bool
     */
    public function handle($path)
    {
        $this->controller = '';

        // TODO: urlの前方一致（もしくはグルーピング）機能がほしい
        $matching = false;
        foreach ($this->patterns as $name => $setting) {

            $options = $setting['options'];

            // 完全一致
            if (isset($options['equal']) && $options['equal'] && $setting['pattern'] === $path) {
                $matching = true;
                $this->controller = $options['controller'];
                $this->params = [];
                break;
            }

            // 正規表現での一致 (ex: /article/{id})
            $re = preg_replace_callback(
                '#({(\w+)})#',
                function ($matches) use ($options) {
                    return '(?<' . $matches[2] . '>[^/]+)';
                },
                $setting['pattern']
            );

            $re = '#\A' . $re . '\z#';
            if (preg_match($re, $path, $matches)) {
                $matching = true;
                $this->controller = $options['controller'];
                $this->params = $matches;
                break;
            }
        }

        // 一致してない場合にデフォルトルートを利用
        if ($this->controller === "" && $this->default_route) {
            $matching = true;
            // TODO: 「\\Controller」を除去する
            $this->controller = "\\Controllers" . implode("\\", array_map("ucfirst", explode('/', $path)));
            $this->params = $matches;
        }
        return $matching;
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        // TODO: 許可アクションの種別をホワイトリストチェック
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $name
     * @param array $parameters
     * @return string
     * @throws RouterException
     */
    public function generate($name, $parameters = [])
    {
        if (isset($this->patterns[$name])) {
            $pattern = $this->patterns[$name];
        } else {
            if ($this->default_route) {
                // 一致してない場合にデフォルトルートを利用
                // ex: $name = user_login => /user/login
                $path = '/' . str_replace('_', '/', $name);
                $pattern = [
                    'pattern' => $path,
                    'option' => []
                ];
            } else {
                throw new RouterException("undefined routing '$name' ");
            }
        }

        $re = preg_replace_callback(
            '#({(\w+)})#',
            function ($matches) use (&$parameters) {
                $value = '';
                if (isset($parameters[$matches[2]])) {
                    $value = $parameters[$matches[2]];
                    unset($parameters[$matches[2]]);
                }
                return $value;
            },
            $pattern['pattern']
        );

        if ($parameters) {
            $query = '?' . http_build_query($parameters);
        } else {
            $query = '';
        }

//        return $this->default_scheme. '://'. $this->default_server_name. $re. $query;
        return $re . $query;
    }
}
