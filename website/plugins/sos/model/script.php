<?php

namespace SOS\Model;

class Script
{
    public 
    $ID,
    $NAME,
    $DATA;
    
    function __construct($data = array()) {
        $this->ID               = isset($data['id'])            ? $data['id']           : -1;
        $this->NAME             = isset($data['name'])          ? $data['name']         : null;
        $this->DATA             = isset($data['data'])          ? $data['data']         : null;
    }
}
