<?php

namespace Views\ACL;

class RolesEdit
{
    function __construct() {
        $f3 = \Base::instance();
        $paramId = $f3->get('PARAMS.id');
        $id = isset($paramId) ? $paramId : -1;
        
        $role = \ACL\RoleTable::instance()->getRoleById($id);
        if(!isset($role)) {            
            $visitor = \ACL\RoleTable::instance()->getRoleByName("Visitor");
            $role = new \ACL\Role(array('permissionmask' => $visitor->PERMISSIONMASK));
        }
        $f3->set('EDITROLE', $role);
        $f3->set('PERMISSIONS', \ACL\PermissionTable::instance()->getPermissions());
    }
    
    function render() {
        echo \Template::instance()->render('acl\editrole.htm');
    }
}
