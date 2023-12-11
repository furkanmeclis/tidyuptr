<?php

namespace App\Services;

class OpticHelper
{
    public $areas = [];
    public $data = [];

    public function __construct($areas = [])
    {
        $this->areas = $areas;
    }

    public function parseArea($area)
    {
        if(!empty($area)){
            //identity_number
            if($area['length'] == 11){
                if(trim($area['dataType']) == "0123456789"){
                    $area['type'] = "identity_number";
                    $area['name'] = "tc_no";
                    $this->data['identity_number'] = $area;
                }
            }
            //name
            if($area['length'] > 15 && $area['length'] < 30){
                if(strpos(trim($area['dataType']),"K")){
                    $area['type'] = "name";
                    $area['name'] = "ad_soyad";
                    $this->data['name'] = $area;
                }
            }
            //booklet number
            if($area['length'] == 1){
                if(str_replace(" ","",trim($area['dataType'])) == "AB" || str_replace(" ","",trim($area['dataType'])) == "ABC" || str_replace(" ","",trim($area['dataType'])) == "ABCD" || str_replace(" ","",trim($area['dataType'])) == "ABCDE"){
                    $area['type'] = "kitapcik";
                    $area['name'] = "kitapcik";
                    $this->data['kitapcik'] = $area;
                }
            }
            //lessons
            if($area['length'] > 25){
                if(str_replace(" ","",trim($area['dataType'])) == "ABCD" || str_replace(" ","",trim($area['dataType'])) == "ABCDE"){
                    $area['type'] = "lesson";
                    $area['name'] = "ders";
                    $this->data['lessons'][] = $area;
                }
            }
        }
    }

    public function get()
    {
        foreach ($this->areas as $area) {
            $this->parseArea($area);
        }
        return $this->data;
    }

    public function getTransactionArray()
    {
        $data = $this->get();
        $transactionArray = [];
        if(isset($data['identity_number'])){
            $transactionArray[] = $data['identity_number'];
        }
        if(isset($data['kitapcik'])){
            $transactionArray[] = $data['kitapcik'];
        }
        if(isset($data['lessons'])){
            foreach ($data['lessons'] as $lesson){
                $transactionArray[] = $lesson;
            }
        }
        if(isset($data['name'])){
            $transactionArray[] = $data['name'];
        }
        return $transactionArray;
    }

}
