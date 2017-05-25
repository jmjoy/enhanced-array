<?php

use functional\Arr;

class ArrObjectTest extends PHPUnit_Framework_TestCase {

    protected $testData;

    public function setUp() {
        $this->testData = array(
            'ints' => array(1, 3, 2, 5, 4, 7, 6, 6, 9, 8),
            'map' => array(
                'aaa' => 1,
                'bbb' => 4,
                'ccc' => 3,
                'trim' => 6,
            ),
            'map2' => array(
                'aaa' => 1,
                'bbb' => '2',
                'ccc' => 3,
                'trim' => 6,
            ),
            'maps' => array(
                array(
                    'aaa' => 1,
                    'bbb' => 'bbb',
                    'ccc' => 'ccc',
                    'trim' => 'ddd',
                ),
                array(
                    'aaa' => 2,
                    'bbb' => 'bbb2',
                    'ccc' => 'ccc2',
                    'trim' => 'ddd2',
                ),
            ),
        );
    }

    public function tearDown() {
        $this->testData = null;
    }

    public function testToArray() {
        $this->assertEquals(array(1, 2, 3), Arr::toArray(array(1, 2, 3)));
    }

    public function testToJson() {
        $this->assertEquals('[1,2,3]', Arr::toJson(array(1, 2, 3)));
        $this->assertEquals('{"aaa":1,"bbb":"2","ccc":3,"trim":6}', Arr::toJson($this->testData['map2']));
    }

    public function getDataProvider() {
        return array(
            array(1, array(array(2, 1, 3), 1)),
            array(null, array(array(2, 1, 3), 3)),
            array(4, array(array(2, 1, 3), 3, 4)),
            array(2, array(array(2.1, 1.1, 3.1), 0, null, 'intval')),
            array('name3', array(array('name' => array('name2' => 'name3')), array('name', 'name2'))),
        );
    }

    /**
     * @dataProvider getDataProvider
     */
    public function testGet($except, $params) {
        $this->assertSame($except, call_user_func_array('\\functional\\Arr::get', $params));
    }

}

