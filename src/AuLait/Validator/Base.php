<?php
namespace AuLait\Validator;
abstract class Base
{
    protected $options = [];

    /**
     * @param array $options
     */
    public function __construct($options=[])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @param string $value
     * @return array
     */
    abstract public function validate($value);

}
