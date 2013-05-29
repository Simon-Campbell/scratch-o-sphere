<?php

namespace ACL\Model;

class Permission
{
    public
    $ID,
    $NAME,
    $ROUTE;
    
    function __construct($data = array()) {        
        $this->ID       = isset($data['id'])    ? $data['id']      : 0;
        $this->NAME     = isset($data['name'])  ? $data['name']    : null;
        $this->ROUTE    = isset($data['route']) ? $data['route']   : null;     
    }
}
