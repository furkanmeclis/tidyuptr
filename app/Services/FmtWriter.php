<?php

namespace App\Services;

class FmtWriter
{
    public $paper;
    public $areas;
    public $firstLineNamedData = [
        'yl' => 0,
        'xl' => 1,
        'undefined' => 2,
        'level' => 3,
        'dataType' => 4,
        'name' => 6,
    ];
    public $namedData = [
        'ys' => 0,
        'ye' => 1,
        'xs' => 2,
        'xe' => 3,
        'undefined' => 4,
        'level' => 5,
        'dataType' => 6,
        'unidentified' => 7,
        'name'  => 8,
        'empty' => 9,
    ];
    public $firstFmt = '';
    public $first = [];
    public $fmt = '';
    public $data = [];
    public function __construct($paper, $areas)
    {
        $this->paper = $paper;
        $this->areas = $areas;
        foreach ($paper as $key => $value) {
            $this->first[$this->firstLineNamedData[$key]] = $value;
        }
        foreach ($areas as $key => $value) {
            foreach ($value as $k => $v) {
                $this->data[$key][$this->namedData[$k]] = $value;
            }
        }
    }

    public function getFmt()
    {
        $fmtArray = [];
        foreach ($this->first as $value) {
            $this->firstFmt .= $value . "=";
        }
        foreach ($this->data as $key => $value) {
            foreach ($value as $subArray) {
                $dataString = implode('=', $subArray) . "=";
                $fmtArray[$key][$dataString] = true;
            }
        }

        foreach ($fmtArray as $key => $value) {
            $dataString = implode("\n", array_keys($value));
            $this->fmt .= $dataString . "\n";
        }
        return $this->firstFmt . "\n" . $this->fmt;
    }


}
