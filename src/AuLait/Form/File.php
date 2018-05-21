<?php
namespace AuLait\Form;

class File extends Element
{
    protected $form_type = 'file';

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
            '<input type="%s" name="%s" %s/>',
            $security->sanitize($this->form_type),
            $security->sanitize($this->name),
            $attr
        );
    }
}
