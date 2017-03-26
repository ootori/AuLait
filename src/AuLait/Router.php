<?php
namespace AuLait;
use AuLait\Exception\RouterException;

class Router
{
    protected $patterns = [];

    protected $controller = '';
    protected $action = '';
    protected $params = [];

    protected $default_scheme = 'https';
    protected $default_server_name = '127.0.0.1';

    /**
     * @param string $name
     * @param string $pattern
     * @param array $options
     */
    public function add($name, $pattern, $options = [])
    {
        $this->patterns[$name] =[
            'pattern' => $pattern,
            'options' => $options
        ];
    }

    /**
     * @param string $default_scheme
     */
    public function setDefaultScheme($default_scheme)
    {
        $this->default_scheme = $default_scheme;
    }

    /**
     * @param string $server_name
     */
    public function setDefaultServerName($server_name)
    {
        $this->default_server_name = $server_name;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function handle($path)
    {
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
                function ($matches) use($options) {
                    return  '(?<' .$matches[2] .'>[^/]+)';
                },
                $setting['pattern']
            );

            $re = '#\A'.$re.'\z#';
            if(preg_match($re, $path, $matches)){
                $matching = true;
                $this->controller = $options['controller'];
                $this->params = $matches;
                break;
            }
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
     */
    public function generate($name, $parameters = [])
    {
        if (isset($this->patterns[$name])) {
            $pattern = $this->patterns[$name];
        } else {
            throw new RouterException("undefined routing '$name' ");
        }

        $re = preg_replace_callback(
            '#({(\w+)})#',
            function ($matches) use(&$parameters) {
                $value = '';
                if (isset($parameters[$matches[2]])) {
                    $value = $parameters[$matches[2]];
                    unset($parameters[$matches[2]]);
                }
                return  $value;
            },
            $pattern['pattern']
        );

        if ($parameters) {
            $query = '?'.http_build_query($parameters);
        } else {
            $query = '';
        }

        return $this->default_scheme. '://'. $this->default_server_name. $re. $query;
    }
}
