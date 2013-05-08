<?php

namespace ACL;

class Role
{
    public 
    $ID,
    $NAME,
    $PERMISSIONMASK;
    
    function __construct($data = array()) {
        $this->ID               = isset($data['id'])             ? $data['id']              : -1;
        $this->NAME             = isset($data['name'])           ? $data['name']            : null;
        $this->PERMISSIONMASK   = isset($data['permissionmask']) ? $data['permissionmask']  : 0;        
    }
    
    function hasPermissionByName($name) {
        $pow = pow(2, PermissionTable::instance()->getPermissionByName($name)->ID);
        if($this->PERMISSIONMASK & $pow || $pow == 1) {
            return true;
        }
        return false;
    }
    
    function hasPermissionByRoute($route) {
        $pow = pow(2, PermissionTable::instance()->getPermissionByRoute($route)->ID);
        if($this->PERMISSIONMASK & $pow || $pow == 1) {
            return true;
        }
        return false;
    }
    
    function hasPermissionById($id) {
        $pow = pow(2, PermissionTable::instance()->getPermissionById($id)->ID);
        if($this->PERMISSIONMASK & $pow || $pow == 1) {
            return true;
        }
        return false;
    }
}
