<?php
/**
 * @file The program :) , the entry point.
 */

// Loads the system processor.
require_once('./system.php');

use Miner\Board;

$board = new Board();

$input1 =
    '*...\n' .
    '..*.\n' .
    '....';

$input =
    '..*.\n' .
    '..*.\n' .
    '*..*';


$board->parseInput($input);

print '=== Mines ===' . chr(10);
print $board->toString();