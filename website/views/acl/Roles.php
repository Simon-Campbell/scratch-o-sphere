<?php

namespace Views\ACL;

class Roles
{
    function __construct() {
        $f3 = \Base::instance();
        $f3->set('ROLES', \ACL\RoleTable::instance()->getRoles());
        $f3->set('PERMISSIONS', \ACL\PermissionTable::instance()->getPermissions());
    }
    
    function render() {
        echo \Template::instance()->render('acl\rolestable.htm');
    }
}