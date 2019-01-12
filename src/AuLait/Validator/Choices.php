<?php

namespace AuLait\Validator;

/**
 * Class Choices
 * @package AuLait\Validator
 */
class Choices extends Base
{
    protected $options = [
        'choices' => null,
        'message' => '選択肢にない項目が入力されました。'
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

        if (!in_array($value, $this->options['choices'])) {
            $messages[] = $this->options['message'];
        }

        return $messages;
    }
}

