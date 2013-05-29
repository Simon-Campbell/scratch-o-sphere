<?php

abstract class DBEnsurer
{
    public $TABLES = array();
    
    function __construct() {
        foreach($this->TABLES as $table) {
            $table->ensureExists();
        }
    }
}
