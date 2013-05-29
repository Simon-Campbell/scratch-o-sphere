<?php

namespace FPiBB\Model;

class SqlValue
{
    public 
    $NAME,
    $VALUE;
    
    function __construct($name, $value) {
        $this->NAME = $name;
        $this->VALUE = $value;
    }
    
    static function implodeNameValue($values) {
        $ret = array();
        foreach($values as $v) {
            $ret[] = $v->NAME . " = " . $v->VALUE;
        }
        return $ret;
    }
}
