<?php
namespace AuLait\Form;

class Select extends Element
{
    protected $form_type = 'select';
    protected $form_value = '1';

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
        foreach ($this->params['options'] as $value => $label) {
            $selected = ($input_value==$value) ? ' selected' : '';
            printf (
                '<option value="%s"%s>%s</option>',
                $security->sanitize($value),
                $selected,
                $security->sanitize($label)
            );
        }
        printf('</select>');
    }
}
