<?php

namespace Miner;

use Miner\BoardInterface;

class Board implements BoardInterface {

    private $list = [];
    private $mineList = [];
    private $rows = 0;
    private $cols = 0;
    private $mapArround = NULL;
    private $processed = FALSE;

    public function getList() {
        return $this->list;
    }

    public function getMineList() {
        return $this->mineList;
    }

    public function parseInput($input) {
        $this->list = [];
        $lines = explode('\n', $input);
        $this->rows = count($lines);
        $this->cols = strlen($lines[0]);
        foreach ($lines as $line) {
            foreach (str_split($line) as $char) {
                $this->list[] = $char;
            }
        }
        $this->mapArround = NULL;
        //$this->getMapArround();
        $this->processed = FALSE;
    }

    private function getMapArround() {
        if (!is_null($this->mapArround)) {
            return $this->mapArround;
        }

        $this->mapArround = [
            [
                'shift' => -($this->cols + 1),
                'rules' => ['t', 'l'],
            ],
            [
                'shift' => -$this->cols,
                'rules' => ['t'],
            ],
            [
                'shift' => -($this->cols - 1),
                'rules' => ['t', 'r'],
            ],
            [
                'shift' => -1,
                'rules' => ['l'],
            ],
            [
                'shift' => 1 ,
                'rules' => ['r'],
            ],
            [
                'shift' => $this->cols - 1,
                'rules' => ['l', 'b'],
            ],
            [
                'shift' => $this->cols,
                'rules' => ['b'],
            ],
            [
                'shift' => $this->cols + 1,
                'rules' => ['r', 'b'],
            ],
        ];

        return $this->mapArround;
    }

    private function calculateSideRules($pos) {
        return [
            'l' => !($pos % $this->cols == 0),
            'r' => !(($pos + 1) % $this->cols == 0),
            't' => !(floor($pos / $this->cols) == 0),
            'b' => !(ceil(($pos + 1) / $this->cols) == $this->rows)
        ];
    }

    private function goArround($pos) {
        $rules = $this->calculateSideRules($pos);
        $mapArround = $this->getMapArround();

        $mineNumber = 0;
        foreach ($mapArround as $block) {
            $toContinue = TRUE;
            foreach ($block['rules'] as $ruleKey) {
                $toContinue = $toContinue && $rules[$ruleKey];
                if (!$toContinue) {
                    break;
                }
            };
            if ($toContinue) {
                $nearIndex = $pos + $block['shift'];
                $inner = $this->list[$nearIndex];
                if ($inner == '*') {
                    $mineNumber++;
                }
            }
        }

        return $mineNumber;
    }

    private function process() {
        $this->mineList = [];
        foreach ($this->list as $pos => $inner) {
            if ($inner == '*') {
                $this->mineList[] = '*';
            }
            else {
                $mineNumber = $this->goArround($pos);
                $this->mineList[] = $mineNumber;
            }
        }
    }

    private function _toString() {
        return implode(
            chr(10),
            array_map(
                function($array) {
                    return implode('', $array);
                },
                array_chunk($this->mineList, $this->cols)
            )
        );
    }

    public function toString() {
        if (!$this->processed) {
            $this->process();
        }

        return $this->_toString();
    }

    public function __construct() {
    }

}


