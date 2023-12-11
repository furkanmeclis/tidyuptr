<?php

namespace App\Services;
use Exception;

class FmtReader
{
    public $content;
    public $lines;
    public $data;
    public $compiledData;
    public $namedData = [
        0 => 'ys',//y start
        1 => 'ye',// y end
        2 => 'xs',// x start
        3 => 'xe',// x end
        4 => 'undefined',//unidentified
        5 => 'level',//level
        6 => 'dataType',//data type
        7 => 'undefined',//unidentified
        8 => 'name',//name
        9 => 'empty',//empty
    ];
    public $firstLineData = [];
    public $firstLineNamedData = [
        0 => 'yl',//y length
        1 => 'xl',// x length
        2 => 'undefined',//unidentified
        3 => 'level',//level
        4 => 'dataType',//dataType
        5 => 'name',//name
        6 => 'empty',//empty
    ];
    public $resultTxt = '';
    public $resultTxtData = [];

    public array $organizedData = [];
    public array $firstLineDataOrganized = [];
    /**
     * @throws Exception
     */
    public function __construct($file = null,$resultTxt = "")
    {
        if($file == null){
            throw new Exception('Content is empty');
        }
        if(isset($file['tmp_name'])) {
            $this->content = file_get_contents($file['tmp_name']);
        } else{
            $this->content = $file;
        }
        $this->content = mb_convert_encoding($this->content, 'UTF-8', 'ISO-8859-9');
        $this->resultTxt = $resultTxt;
        $this->resultTxtData = explode("\n", $this->resultTxt);
        return $this;
    }

    /**
     * @throws Exception
     */
    public function lines()
    {
        try{
            $lines = explode("\n", str_replace("<br>", "\n", nl2br($this->content)));
            $this->firstLineData = array_filter(explode('=',$lines[0]));
            array_shift($lines);
            $this->lines = $lines;
            foreach ($lines as $line) {
                if (trim($line) == '') {
                    continue;
                }
                $cacheLine = explode('=',$line);
                $this->data[] = $cacheLine;
            }
            return $this;
        } catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function compileLineData()
    {
        try{
            //first line data
            foreach ($this->firstLineData as $key => $value) {
                $this->firstLineDataOrganized[$this->firstLineNamedData[$key]] = $value;
            }
            foreach ($this->data as $key => $value) {
                foreach ($value as $k => $v) {
                    if(isset($this->namedData[$k])) {
                        $this->compiledData[$key + 1][$this->namedData[$k]] = $v;
                    }
                }
                $this->compiledData[$key+1]['xLength'] = intval($this->compiledData[$key+1]['xe']) - intval($this->compiledData[$key+1]['xs']) + 1;
                $this->compiledData[$key+1]['yLength'] = intval($this->compiledData[$key+1]['ye']) - intval($this->compiledData[$key+1]['ys']) + 1;
                if($this->compiledData[$key+1]['level'] == 'D'){
                    $this->compiledData[$key+1]['length'] = intval($this->compiledData[$key+1]['xe']) - intval($this->compiledData[$key+1]['xs']) + 1;
                }else{
                    $this->compiledData[$key+1]['length'] = intval($this->compiledData[$key+1]['ye']) - intval($this->compiledData[$key+1]['ys'])+1;
                }
            }
            return $this;
        } catch (Exception $e){
            throw new Exception("Tanımlanamayan FMT Dosyası Lütfen Sadece Sekonic İçin Fmt Dosyası Yükleyiniz.". $e->getMessage().$e->getLine());
        }
    }
    public function parseLine($line)
    {
        if(trim($line) == ''){
            return false;
        }

        $lineData = [];
        $cacheLine = str_replace([" ","\r"],[".",""],$line);
        $compiledLength = 0;
        foreach($this->compiledData as $key => $information){
            $data = substr($cacheLine,$compiledLength,$information['length']);
            $compiledLength += $information['length'];
           if($information['name'] == "ders"){
               $lineData["lessons"][] = $data;
           }else{
               $lineData[$information['name']] = $data;
           }

        }

        $this->organizedData[] = $lineData;
        return $lineData;
    }
    public function parse()
    {
        foreach($this->resultTxtData as $line){
            $this->parseLine($line);
        }
        return $this;
    }

    /**
     * @throws Exception
     */
    public function getData()
    {
        try{
            $this->lines()->compileLineData()->parse();
            return $this->organizedData;
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function getFmtDetails(): array
    {
        $this->lines()->compileLineData();
        return [
            "paper" => $this->firstLineDataOrganized,
            "areas" => $this->compiledData
        ];
    }



}
