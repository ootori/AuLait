<?php
namespace AuLait\Validator;

class Csrf extends Base
{
    protected $options = [
        'value' => null,
        'message' => '文字列が等しくありません。'
    ];

    /**
     * @param string $value
     * @return array
     * @throws \Exception
     */
    public function validate($value)
    {
        if (!isset($this->options['value'])) {
            throw new \Exception('比較対象の文字列がセットされていません。');
        }

        $messages = [];
        if ($this->options['value'] != $value) {
            $messages[] = $this->options['message'];
        }

        return $messages;
    }
}

