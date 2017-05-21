<?php

use functional\Exp;

class ExpTest extends PHPUnit_Framework_TestCase {

    public function testEval() {
        $add = function($a) {
            return function($b) use ($a) {
                return $a + $b;
            };
        };
        $exp = new Exp($add, 1, 2);
        $this->assertEquals(3, $exp->callMe());
        $this->assertEquals(3, Exp::call($add, 1, 2));
    }

}
