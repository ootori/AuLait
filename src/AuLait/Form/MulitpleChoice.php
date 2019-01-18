<?php

namespace AuLait\Form;

//use AuLait\Validator\Choices;
use AuLait\DependencyInjection;
use AuLait\DI;

class MulitpleChoice extends Element
{
    protected $value = [];
    protected $form_type = 'checkbox';
    protected $options = [
        'options' => [],
//        'blank' => false,
        'message' => '選択肢にない項目が入力されました。'
    ];

    /**
     * @return bool
     */
    public function validate()
    {
        // TODO: バリデーション
        return true;
    }

    /**
     * @param array $params
     */
    public function render($params = [])
    {
        $security = $this->di->share('security');

        $attr = '';
        foreach ($params as $key => $value) {
            $attr = sprintf('%s %s="%s"', $attr, $security->sanitize($key), $security->sanitize($value));
        }

        $input_value = $this->getValue();

        foreach ($this->params['options'] as $value => $label) {
            $selected = in_array($value, $input_value) ? ' checked="checked"' : '';
            printf(
                '<input type="checkbox" name="%s[]" value="%s"%s>%s</input>',
                $security->sanitize($this->name),
                $security->sanitize($value),
                $selected,
                $security->sanitize($label)
            );
        }
    }

    /**
     * @param array $params
     */
    public function choices($params = [])
    {
        foreach ($this->params['options'] as $value => $entity) {
            $checked = in_array($value, $this->getValue());
            yield new Choice($this->name, $entity, $checked);
        }
    }
}

/**
 * Class Choice
 * @package AuLait\Form
 */
class Choice
{
    public $name = '';
    public $entity = null;
    public $checked = false;
    public $value_key = 'id';

    /**
     * @param string $name
     * @param array $params
     */
    public function __construct($name, $entity, $checked, $value_key='id')
    {
        $this->name = $name;
        $this->entity = $entity;
        $this->checked = $checked;
        $this->value_key = $value_key;
    }

    /**
     * @param array $params
     */
    public function rendor($params = [])
    {
        $security = DI::getDefault()->share('security');

        $attr = '';
        foreach ($params as $key => $value) {
            $attr = sprintf('%s %s="%s"', $attr, $security->sanitize($key), $security->sanitize($value));
        }

        if ($this->checked) {
            $attr .= ' checked="checked"';
        }
        printf(
            '<input type="checkbox" name="%s[]" value="%s"%s>',
            $security->sanitize($this->name),
            $security->sanitize($this->entity->{$this->value_key}),
            $attr
        );
    }
}