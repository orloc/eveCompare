<?php


namespace EveCompare\Service;

use League\Csv\Reader;

class EveTypeExtractor {

    protected $data;

    public function __construct(){
        $this->data = null;
    }

    public function readFile($path){
        $reader = Reader::createFromPath($path);

        $this->data = $reader->fetchAll();
    }

    public function asJson(){
        $this->checkData();

        return json_encode($this->data, true);
    }

    public function asArray(){
        $this->checkData();

        return $this->data;
    }

    protected function checkData(){
        if ($this->data === null){
            throw new \Exception('You must read a data source first.');
        }
    }
}