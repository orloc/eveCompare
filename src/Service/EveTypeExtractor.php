<?php


namespace EveCompare\Service;

use League\Csv\Reader;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class EveTypeExtractor {

    protected $data;

    protected $path;

    public function __construct($path){
        $this->data = null;
        $this->path = $path;
    }

    public function readFile(){
        $this->validatePath();

        $reader = Reader::createFromPath($this->path);
        $this->data = $reader
            ->addFilter($this->filterMinerals());

        return $this;
    }

    public function asJson(){
        $this->checkData();

        return json_encode($this->data, true);
    }

    public function asArray(){
        $this->checkData();

        return $this->data->fetchAll();
    }

    protected function filterMinerals(){
        return function($val, $row){
            var_dump($val);
        };

    }

    protected function checkData(){
        if ($this->data === null){
            throw new \Exception('You must read a data source first.');
        }
    }

    protected function validatePath(){
        if (!file_exists($this->path) || !is_readable($this->path)){
            throw new FileNotFoundException('File not found or not readable');
        }

    }
}