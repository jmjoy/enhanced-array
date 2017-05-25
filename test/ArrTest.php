<?php

use functional\Arr;

class ArrTest extends PHPUnit_Framework_TestCase {

    public function testCall() {
        $arr = Arr::from(array(1, 2, 3))->map('intval');
        $this->assertInstanceOf('\\functional\\ArrayObject', $arr);
    }

    public function testCallStatic() {
        $arr = Arr::map(array(1, 2, 3), 'intval');
        $this->assertInstanceOf('\\functional\\ArrayObject', $arr);
        $this->assertEquals((array) $arr, array(1, 2, 3));
    }

}