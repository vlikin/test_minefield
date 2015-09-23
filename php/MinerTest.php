<?php
/**
 * @file Tests the project.
 */

require_once(dirname(__FILE__) . '/system.php');


use Miner\Board;


class MinerTest extends PHPUnit_Framework_TestCase {

    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function getPropertyValue(&$object, $propertyName) {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    protected function setUp() {
    }

    public function testProject() {
        $input =
            '*...\n' .
            '..*.\n' .
            '....';

        $board = new Board();

        // parseInput.
        $board->parseInput($input);
        $list = $board->getList();
        $rows = $this->getPropertyValue($board, 'rows');
        $cols = $this->getPropertyValue($board, 'cols');
        $this->assertEquals($cols, 4);
        $this->assertEquals($rows, 3);
        $this->assertEquals($list[0], '*');
        $this->assertEquals($list[6], '*');
        $this->assertEquals($list[11], '.');
        $this->assertEquals(count($list), 12);

        // mapArround.
        $mapArround = $this->invokeMethod($board, 'getMapArround');
        $this->assertEquals(count($mapArround), 8);
        $this->assertEquals($mapArround[0]['shift'], -5);
        $this->assertEquals($mapArround[0]['rules'][0], 't');
        $this->assertEquals($mapArround[0]['rules'][1], 'l');
        $this->assertEquals($mapArround[7]['shift'], 5);
        $this->assertEquals($mapArround[7]['rules'][0], 'r');
        $this->assertEquals($mapArround[7]['rules'][1], 'b');

        // getSideRule.
        $sideRules = $this->invokeMethod($board, 'calculateSideRules', [8]);
        $this->assertFalse($sideRules['l']);
        $this->assertFalse($sideRules['b']);
        $this->assertTrue($sideRules['t']);
        $this->assertTrue($sideRules['r']);

        // goArround.
        $minesArround = $this->invokeMethod($board, 'goArround', [5]);
        $this->assertEquals($minesArround, 2);
        $minesArround = $this->invokeMethod($board, 'goArround', [1]);
        $this->assertEquals($minesArround, 2);

        // process.
        $this->invokeMethod($board, 'process');
        $mineList = $board->getMineList();
        $this->assertEquals($mineList[1], 2);
        $this->assertEquals($mineList[5], 2);

        // _toString.
        $str = $this->invokeMethod($board, '_toString');
        $this->assertEquals($str, '*211' . chr(10) . '12*1' . chr(10) . '0111');

        // toSring.
        $str = $board->toString();
        $this->assertEquals($str, '*211' . chr(10) . '12*1' . chr(10) . '0111');

        $input =
            '..*.\n' .
            '..*.\n' .
            '*..*';
        $board->parseInput($input);
        $str = $board->toString();
        $this->assertEquals($str, '02*2' . chr(10) . '13*3' . chr(10) . '*22*');

        $input =
            '..*.*\n' .
            '..*..\n' .
            '*.*..';
        $board->parseInput($input);
        $str = $board->toString();
        $this->assertEquals($str, '02*3*' . chr(10) . '14*41' . chr(10) . '*3*20');

    }
}