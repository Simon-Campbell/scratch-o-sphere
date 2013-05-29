<?php

namespace ACL\View;

class Roles
{
    function __construct() {
        $f3 = \Base::instance();
        $f3->set('ROLES', \ACL\Model\RoleTable::instance()->getRoles());
        $f3->set('PERMISSIONS', \ACL\Model\PermissionTable::instance()->getPermissions());
    }
    
    function render() {
        echo \Template::instance()->render('acl\controller\rolestable.htm');
    }
}