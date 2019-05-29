<?php

namespace AuLait\Validator;

class NaturalNumber extends Base
{
    protected $options = [
        'permit_zero' => false,
        'message' => '%d以上の整数を入力してください'
    ];


    /**
     * @param string $value
     * @return array
     */
    public function validate($value)
    {
        $messages = [];
        if ($value === null || $value === '') {
            return $messages;
        }

        if ($this->options['permit_zero']) {
            $regex = '#\A(0|[1-9][0-9]*)\z#';
        } else {
            $regex = '#\A[1-9][0-9]*\z#';
        }

        if (!preg_match($regex, $value)) {
            $messages[] = sprintf(
                $this->options['message'],
                $this->options['permit_zero'] ? 0 : 1
            );
        }

        return $messages;
    }
}

