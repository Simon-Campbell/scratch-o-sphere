<?php

namespace ACL\View;

class RolesEdit
{
    function __construct() {
        $f3 = \Base::instance();
        $paramId = $f3->get('PARAMS.id');
        $id = isset($paramId) ? $paramId : -1;
        
        $role = \ACL\Model\RoleTable::instance()->getRoleById($id);
        if(!isset($role)) {            
            $visitor = \ACL\Model\RoleTable::instance()->getRoleByName("Visitor");
            $role = new \ACL\Model\Role(array('permissionmask' => $visitor->PERMISSIONMASK));
        }
        $f3->set('EDITROLE', $role);
        $f3->set('PERMISSIONS', \ACL\Model\PermissionTable::instance()->getPermissions());
    }
    
    function render() {
        echo \Template::instance()->render('acl\controller\editrole.htm');
    }
}
