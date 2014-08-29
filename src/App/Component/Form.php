<?php

namespace App\Component;

use Bono\App;
use Norm\Norm;

class Form {

    protected $data;

    public static function create($collection = ''){
        return new static($collection);
    }

    public function __construct($collection = '') {
        if ($collection === '') {
            $collection = App::getInstance()->controller->clazz;
        }

        if (is_string($collection)) {
            $this->collection = Norm::factory($collection);
        }

        $this->data = &$_POST;
        $this->fields = $this->collection->schema();
        // var_dump($this->fields);


    }

    public function formatPlain($value, $entry = null)
    {
        return $value;
    }

    public function formatInput($key, $value = ':empty:') {
        if ($value === ':empty:') {
            $value = $this->data($key);
        }

        //fixme : handle for string value with html character

        if($this->fields[$key] instanceof \Norm\Schema\String){
            if(is_array($value)){
                $value = implode(',', $value);
            }
            $value = htmlentities($value);
        }

        return $this->fields[$key]->formatInput($value, $this->data);
    }

    public function readonly($key, $value = ':empty:'){
        $field = $this->fields[$key];
        $field['readonly'] = true;

        return $this->formatInput($key,$value);

    }

    public function formatReadonly($value, $entry = null)
    {
        // if($value instanceof \Norm\Type\NormArray)
        return "<span class=\"field\">".($value ?: '&nbsp;')."</span>";
    }

    public function cell($key, $value){
        return $this->fields[$key]->cell(htmlentities($value));
    }

    public function formatHidden($key, $value = ':empty:'){

        if ($value === ':empty:') {
            $value = $this->data($key);
        }

        return '<input type="hidden" name="'.$key.'" value="'.$value.'">';
    }

    public function data($key) {
        return @$this->data[$key];
    }

    public function of(&$data) {
        $this->data = &$data;
        return $this;
    }


}
