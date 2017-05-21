<?php

namespace functional\fn;

class Op {

    public static function add() {
        return function($a) {
            return function($b) use ($a) {
                return $a + $b;
            };
        };
    }
}