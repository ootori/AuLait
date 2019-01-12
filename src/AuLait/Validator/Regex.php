<?php
namespace AuLait\Validator;

use AuLait\Exception;

class Regex extends Base
{
    protected $options = [
        'regex' => null,
        'required' => false,
        'message' => '不正な文字列です。'
    ];

    /**
     * @param string $value
     * @return array
     * @throws \Exception
     */
    public function validate($value)
    {
        if (!isset($this->options['regex'])) {
            throw new Exception('Not found regex');
        }

        $messages = [];

        if ($value == '' && !$this->options['required']) {
            return [];
        }
        if (!preg_match($this->options['regex'], $value)) {
            $messages[] = $this->options['message'];
        }

        return $messages;
    }
}

