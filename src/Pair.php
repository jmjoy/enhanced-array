<?php

namespace functional;

/**
 * Left-Right Pair
 */
class Pair {

    public $left;

    public $right;

    public function __construct($left, $right) {
        $this->left = $left;
        $this->right = $right;
    }

}