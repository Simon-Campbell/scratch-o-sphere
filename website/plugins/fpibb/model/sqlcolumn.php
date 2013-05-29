<?php

namespace FPiBB\Model;

class SqlColumn
{
    //Required
    public
    $NAME,
    $TYPE;
    
    //Optional
    public
    $UNIQUE,
    $PRIMARY,
    $AUTOINCREMENT,
    $NOTNULL,
    $DEFAULTVALUE,
    $ENUM;
    
    //Length of the data type
    public $LENGTH;
    
    function __construct($name, $type, $unique = false, $primary = false, $ai = false, $nn = false, $dv = false, $enum = array()) {
        $this->NAME = $name;
        $this->TYPE = $type;        
        $this->UNIQUE = $unique;
        $this->PRIMARY = $primary;
        $this->AUTOINCREMENT = $ai;
        $this->NOTNULL = $nn;
        $this->DEFAULTVALUE = $dv;
        $this->ENUM = $enum;
    }
}
