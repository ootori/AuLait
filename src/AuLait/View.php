<?php
namespace AuLait {

    class View
    {
        protected $path = '';
        protected $data = [];
        protected $layout = null;

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
         * @param string $key
         * @param mixed $value
         */
        public function assign($key, $value)
        {
            $this->data[$key] = $value;
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

    }
}
