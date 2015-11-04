<?php


namespace EveCompare\Service;

use League\Csv\Reader;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class EveTypeExtractor {

    protected $data;

    protected $path;

    protected $white_list;

    public function __construct($path, array $white_list = []){
        $this->data = null;
        $this->path = $path;

        $this->white_list = array_flip($white_list);
    }

    public function readFile(){
        $this->validatePath();

        $reader = Reader::createFromPath($this->path);
        $this->data = $reader;

        if (null !== $this->white_list){
            $reader->addFilter($this->filterIds($this->white_list));
        }

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

    protected function filterIds(array $white_list){
        return function($val) use ($white_list){
            if (isset($this->white_list[intval($val[0])])) {
                return true;
            }
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