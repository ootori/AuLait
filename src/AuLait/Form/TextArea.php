<?php
namespace AuLait\Form;

class TextArea extends Element
{
    protected $form_type = 'textarea';

    public function render($params=[])
    {
        $security = $this->di->share('security');

        $attr = '';
        foreach ($params as $key => $value) {
            $attr = sprintf('%s %s="%s"', $attr, $security->sanitize($key), $security->sanitize($value));
        }

        printf(
            '<textarea name="%s"%s>%s</textarea>',
            $security->sanitize($this->name),
            $attr,
            $security->sanitize($this->value)
        );
    }
}