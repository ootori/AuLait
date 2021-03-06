<?php
namespace AuLait;

use AuLait\Exception\ViewException;

class View
{
    protected $path = '';
    protected $data = [];
    protected $layout = null;
    protected $reservedWords = ['this', 'body'];
    public $helper = null;

    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @param string $file
     */
    public function setLayout($file)
    {
        $this->layout = $file;
    }

    /**
     * @param  object $helper
     */
    public function setHelper($helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @throws ViewException
     */
    public function assign($key, $value)
    {
        $this->checkReservedWord($key);
        $this->data[$key] = $value;
    }

    /**
     * @param $key
     * @param $value
     * @throws ViewException
     */
    public function append($key, $value)
    {
        $this->checkReservedWord($key);
        $this->data[$key][] = $value;
    }

    /**
     * @param $key
     * @throws ViewException
     */
    protected function checkReservedWord($key)
    {
        if (in_array($key, $this->reservedWords)) {
            throw new ViewException(
                "$key is a reserved word",
                ViewException::CODE_USE_RESERVED_WORD
            );
        }
    }

    /**
     * @param $file
     * @return string
     */
    public function render($file)
    {
        set_include_path($this->path);
        extract((array)$this->data);
        ob_start();
        require $this->path . '/' . $file . '.php';
        $body = ob_get_contents();
        ob_end_clean();
        if ($this->layout) {
            require $this->path . '/' . $this->layout . '.php';
        } else {
            return $body;
        }
    }

    /**
     * @param $file
     */
    public function display($file)
    {
        echo $this->render($file);
    }


    /**
     * @param $key
     * @return DependencyInjection|mixed
     */
    public function __get($key)
    {
        if ($key == 'di') {
            return DI::getDefault();
        }
        return DI::getDefault()->share($key);
    }
}
