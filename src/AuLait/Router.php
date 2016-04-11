<?php
namespace AuLait;
class Router
{
    protected $patterns = [];

    protected $controller = null;
    protected $action = null;
    protected $params = null;
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

    public function setDefaultScheme($default_scheme)
    {
        $this->default_scheme = $default_scheme;
    }

    public function setDefaultServerName($server_name)
    {
        $this->default_server_name = $server_name;
    }

    /**
     * @param $path
     * @return bool
     */
    public function handle($path)
    {
        // TODO: 完全一致で問題ない場合は正規表現使わない方が高速なのでそういう処理も入れたい。
        // TODO: urlの前方一致（もしくはグルーピング）機能がほしい
        $matching = false;
        foreach ($this->patterns as $name => $setting) {
            $options = $setting['options'];
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

    public function getControllerName()
    {
        return $this->controller;
    }
    public function getActionName()
    {
        // TODO: 許可アクションの種別をホワイトリストチェック
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getParams()
    {
        return $this->params;
    }

    public function generate($name, $parameters = [])
    {
        $pattern = $this->patterns[$name];
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
