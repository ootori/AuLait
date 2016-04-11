<?php
namespace AuLait\Validator;

class StringLength extends Base
{
    /**
     * @param string $value
     * @return array
     */
    public function validate($value)
    {
        $messages = [];

        $length = mb_strlen($value);

        if ($length == 0) {
            return $messages;
        }

        if ($length < $this->options['min']){
            $messages[] = '短すぎます';
        }

        if ($length > $this->options['max']){
            $messages[] = '長すぎます';
        }
        return $messages;
    }
}

