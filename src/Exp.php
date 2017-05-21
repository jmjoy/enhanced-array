<?php

namespace functional;

use InvalidArgumentException;

class Exp {

    protected $fn;

    protected $params;

    public static function call() {
        $args = func_get_args();
        $instance = call_user_func_array('functional\\Exp::__construct', $args);
        return $instance->toEval();
    }

    public function __construct() {
        $args = func_get_args();
        if (count($args) < 1) {
            throw new InvalidArgumentException('Fn missed.');
        }
        $this->fn = array_shift($args);
        $this->params = $args;
    }

    public function callMe() {
        $result = $this->fn;
        foreach ($this->params as $param) {
            $result = call_user_func($result, $param);
        }
        return $result;
    }

}