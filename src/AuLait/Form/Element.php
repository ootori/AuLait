<?php
namespace AuLait\Form;
use AuLait\DependencyInjection;

class Element
{
    /** @var DependencyInjection */
    public $di = null;

    /** @var string 要素名 */
    public $name = '';
    protected $value = null;
    protected $form_type = 'text';
    protected $params = [];
    protected $errors = [];

    protected $validators = [];

    /**
     * @param string $name
     * @param array $params
     */
    public function __construct($name, $params = [])
    {
        $this->name = $name;
        $this->params = $params;
    }

    /**
     * @param DependencyInjection $di
     */
    public function setDI(DependencyInjection $di)
    {
        $this->di = $di;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
         return $this->value;
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $this->errors = [];

//        $this->setValue($value);

        $value = $this->getValue();

        /** @var \AuLait\Validator\Base $validator */
        foreach ($this->validators as $validator) {
            $messages = $validator->validate($value);
            if ($messages) {
                $this->errors = array_merge($this->errors, $messages);
            }
        }
        return $this->errors ? false : true;
    }

    /**
     * @param $validator
     * @return $this
     */
    public function addValidator($validator)
    {
        $this->validators[] = $validator;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * TODO: View::rendorの方は描画せずに文字列返してるのでこちらもあわせる。
     *
     * @param array $params
     */
    public function render($params = [])
    {
        $security = $this->di->share('security');

        $attr = '';
        foreach ($params as $key => $value) {
            $attr = sprintf('%s %s="%s"', $attr, $security->sanitize($key), $security->sanitize($value));
        }

        printf(
            '<input type="%s" name="%s" value="%s"%s/>',
            $security->sanitize($this->form_type),
            $security->sanitize($this->name),
            $security->sanitize($this->value),
            $attr
        );
    }
}
