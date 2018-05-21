<?php
namespace AuLait\Validator;

class Csrf extends Base
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
        if (!isset($this->options['value'])) {
            throw new \Exception('正規表現がセットされていません。');
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

