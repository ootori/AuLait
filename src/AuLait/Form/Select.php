<?php
namespace AuLait\Form;

use AuLait\Validator\Choices;

class Select extends Element
{
    protected $form_type = 'select';
    protected $params = [
        'options' => [],
        'blank' => false,
        'message' => '選択肢にない項目が入力されました。'
    ];

    /**
     * @param string $name
     * @param array $params
     */
    public function __construct($name, $params = [])
    {
        parent::__construct($name, $params);

        $choices = array_keys($this->params['options']);

        $validator = new Choices([
            'choices' => $choices,
        ]);
        $this->addValidator($validator);
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

        printf(
            '<select name="%s"%s>',
            $security->sanitize($this->name),
            $attr
        );

        $input_value = $this->getValue();

        if ($this->params['blank']) {
            printf('<option value=""></option>');
        }

        foreach ($this->params['options'] as $value => $label) {
            $selected = ($input_value == $value) ? ' selected' : '';
            printf(
                '<option value="%s"%s>%s</option>',
                $security->sanitize($value),
                $selected,
                $security->sanitize($label)
            );
        }
        printf('</select>');
    }
}
