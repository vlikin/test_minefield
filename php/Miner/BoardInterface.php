<?php
/**
 * @file Contains the BoardInterface.
 */

namespace Miner;

interface BoardInterface {
    public function parseInput($input);
    public function toString();
}
