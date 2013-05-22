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
    
    function hasPermissionByRoute($route) {
        $route = rtrim($route, "/");
        if(!isset($route) || $route == "") {
            return true;
        }
        $permMaskCheck = $this->PERMISSIONMASK;
        $idCounter = 0;
        while($permMaskCheck != 0) {
            $test = decbin($permMaskCheck);
            $set = $permMaskCheck & 1;
            $permMaskCheck = $permMaskCheck >> 1;
            if($set == 1) {
                $perm = PermissionTable::instance()->getPermissionById($idCounter);
                if($perm != null) {
                    if($route == $perm->ROUTE) {
                        return true;
                    }
                    $wildcardcheck = stripos($perm->ROUTE, "*");
                    $newroute = str_replace("*", "", $perm->ROUTE);
                    if($newroute != "/") {
                        $newroute = rtrim($newroute, "/");
                    }
                    $routecheck = stripos($route, $newroute);
                    if($wildcardcheck !== false && $routecheck !== false) {
                        return true;
                    }
                }
            }
            $idCounter++;
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
