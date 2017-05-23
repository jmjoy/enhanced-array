<?php

namespace functional;

/**
 * ArrayObject Decorator
 */
class Arr {

    protected $arrayObject;

    public function __construct($array) {
        $this->arrayObject = new ArrayObject($array);
    }

    public static function from($array) {
        return new static($array);
    }

    public function __call($name, $arguments) {
        return call_user_func_array(array($this->arrayObject, $name), $arguments);
    }

    public static function __callStatic($name, $arguments) {
        $array = array_shift($arguments);
        return call_user_func_array(array(static::from($array), $name), $arguments);
    }

}