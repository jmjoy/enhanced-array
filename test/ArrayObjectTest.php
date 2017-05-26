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
            array(null, array(array('name' => array('name2' => 'name3')), array('name', 'name2', 'name3'))),
            array(null, array(array('name' => array('name2' => 'name3')), array('name', 0))),
            array('22', array(array(array(11, 22)), array(0, 1), 0, 'strval')),
        );
    }

    /**
     * @dataProvider getDataProvider
     */
    public function testGet($except, $params) {
        $this->assertSame($except, call_user_func_array('\\functional\\Arr::get', $params));
    }

    public function setDataProvider() {
        return array(
            array(array(1, 2, 3), array(array(1, 2, 1), 2, 3)),
            array(
                array('name' => array('name2' => 'name4')),
                array(array('name' => array('name2' => 'name3')), array('name', 'name2'), 'name4'),
            ),
            array(
                array('name' => array('name2' => 'name3', 'name3' => 'name5')),
                array(array('name' => array('name2' => 'name3')), array('name', 'name3'), 'name5'),
            ),
            array(
                array('name' => array('name2' => 'name3', 'name3' => array('name4' => 'name5'))),
                array(array('name' => array('name2' => 'name3')), array('name', 'name3', 'name4'), 'name5'),
            ),
        );
    }

    /**
     * @dataProvider setDataProvider
     */
    public function testSet($except, $params) {
        $this->assertSame($except, call_user_func_array('\\functional\\Arr::set', $params)->toArray());
    }

    public function testHas() {
        $this->assertTrue(Arr::has(array(1, 2, 3), 0));
        $this->assertFalse(Arr::has(array('name' => 'aaa'), 'name1'));
        $this->assertTrue(Arr::has(array('a0' => array('a1' => 'a2')), array('a0', 'a1')));
        $this->assertFalse(Arr::has(array('a0' => array('a1' => 'a2')), array('a0', 'a2')));
        $this->assertFalse(Arr::has(array('a0' => array('a1' => 'a2')), array('a0', 'a1', 'a2')));
    }

    public function removeDataProvider() {
        return array(
            array(array(), array(array(0), 0)),
            array(
                array('a0' => array('a1' => 'a2')),
                array(array('a0' => array('a1' => 'a2')), 'a1'),
            ),
            array(
                array(),
                array(array('a0' => array('a1' => 'a2')), 'a0'),
            ),
            array(
                array('a0' => array()),
                array(array('a0' => array('a1' => 'a2')), array('a0', 'a1')),
            ),
            array(
                array('a0' => array('a1' => 'a2')),
                array(array('a0' => array('a1' => 'a2')), array('a0', 'a1', 'a2')),
            ),
        );
    }

    /**
     * @dataProvider removeDataProvider
     */
    public function testRemove($except, $params) {
        $this->assertEquals($except, call_user_func_array('\\functional\\Arr::remove', $params)->toArray());
    }

    public function testIn() {
        $this->assertTrue(Arr::in(array(1, 2), 2));
        $this->assertFalse(Arr::in(array('name', 'age'), 'gender'));
    }

    public function testSort() {
        $this->assertEquals(array(1, 2, 3), Arr::sort(array(1, 3, 2))->toArray());
        $this->assertEquals(array(
            array('a' => 1),
            array('a' => 2),
            array('a' => 3),
        ), Arr::sort(array(
            array('a' => 2),
            array('a' => 1),
            array('a' => 3),
        ), function($a, $b) {
            return ($a['a'] == $b['a']) ? 0 : (($a['a'] > $b['a']) ? 1 : -1);
        })->toArray());
    }

    public function testSortByKeys() {
        $this->assertEquals(array(
            array('a' => 1, 'b' => 3, 'c' => 1),
            array('a' => 1, 'b' => 2, 'c' => 2),
            array('a' => 2, 'b' => 3, 'c' => 3),
        ), Arr::sortByKeys(array(
            array('a' => 2, 'b' => 3, 'c' => 3),
            array('a' => 1, 'b' => 2, 'c' => 2),
            array('a' => 1, 'b' => 3, 'c' => 1),
        ), array(
            'a' => SORT_ASC,
            'b' => SORT_DESC,
            'c' => SORT_ASC,
        ))->toArray());
    }

}

