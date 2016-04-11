<?php
namespace AuLait\Form;

class CheckBox extends Element
{
    protected $form_type = 'checkbox';

    public function render($params = [])
    {
        $security = $this->di->share('security');

        $attr = '';
        foreach ($params as $key => $value) {
            $attr = sprintf('%s %s="%s"', $attr, $security->sanitize($key), $security->sanitize($value));
        }

        // TODO: 0が入力されてきたときはどうしよう？
        if ($this->value) {
            $attr .= ' checked="checked"';
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