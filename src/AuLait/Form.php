<?php
namespace AuLait;

use AuLait\Form\Element;
use AuLait\Form\File;

class Form
{
    /** @var DependencyInjection */
    protected $di = null;

    /** @var string post|get */
    protected $method = 'post';

    /** @var Request  */
    protected $request = null;

    /** @var Form\Element[] フォーム内のinput要素 */
    protected $elements = [];

    /** @var array エラー文言 */
    protected $errors = [];

    /**
     * Form constructor.
     * @param DependencyInjection $di
     */
    public function __construct(DependencyInjection $di)
    {
        $this->di = $di;
    }

    /**
     * @param array $defaults
     */
    public function setDefaults($defaults = [])
    {
        foreach ($defaults as $key => $value) {
            if (isset($this->elements[$key])) {
                $this->elements[$key]->setValue($value);
            }
        }
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function add(Element $element)
    {
        $element->setDI($this->di);
        $this->elements[$element->name] = $element;
    }

    public function get($name)
    {
        return $this->elements[$name];
    }


    /**
     * @return bool
     */
    public function validate()
    {
        $this->errors = [];

        /** @var Request $request */
        $request = $this->di->share('request');
        $security = $this->di->share('security');
        if ($request->getMethod() == 'post') {

            // TODO: csrfはFormに持たないようにしてForm\CSRFのようなinput要素をつくってそっちで対応する
            $csrf = $this->getValue('csrf');
            if (!$csrf || !$security->checkCsrfToken($csrf)) {
                $this->addError('csrf', '無効なフォームからの送信です。');
                return false;
            }
        }

        foreach ($this->elements as $element) {
            if (!$element->validate()) {
                $this->errors[$element->name] = $element->getErrors();
            }
        }

        if ($this->errors) {
            return false;
        }
        return true;
    }

    public function addError($name, $message)
    {
        if (!isset($this->errors[$name])) {
            $this->errors[$name] = [];
        }
        $this->errors[$name][] = $message;
    }

    /**
     * @param string $name 指定要素のエラー文言（配列）を取得。未指定時は全要素のもの。
     * @return array
     */
    public function getErrors($name = null)
    {
        if ($name === null) {
            $errors = [];
            foreach ($this->errors as $messages) {
                foreach ($messages as $message) {
                    $errors[] = $message;
                }
            }
            return $errors;
        }
        return isset($this->errors[$name]) ? $this->errors[$name] : [];
    }

    public function render($name, $params = array())
    {
        $this->elements[$name]->render($params);
    }

    public function getValue($name, $default = null)
    {
        if (!isset($this->elements[$name])) {
            return $default;
        }
        $value = $this->elements[$name]->getValue();
        return  ($value == '') ? $default : $value;
    }

    public function getCsrfToken()
    {
        return $this->di->share('security')->getCsrfToken();
    }

    /**
     * 値の有無をチェックする。
     *
     * 例：<input type="submit" name="back" value="true"><input type="submit" name="forward" value="true">
     * のような状態で、どちらのボタンが押されたかを判定するのに使用する。
     *
     * @param $name
     * @return bool
     */
    function isSent($name)
    {
        $method = strtolower($_SERVER["REQUEST_METHOD"]);
        if ($method == 'post' && isset($_POST[$name])) {
            return true;
        } elseif ($method == 'get' && isset($_GET[$name])) {
            return true;
        }
        return false;
    }
}