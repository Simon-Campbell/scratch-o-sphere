<?php

namespace ACL\View;

class UsersEdit
{
    function __construct() {
        $f3 = \Base::instance();
        $paramId = $f3->get('PARAMS.id');
        $id = isset($paramId) ? $paramId : -1;
        
        $user = \ACL\Model\UserTable::instance()->getUser($id);
        
        $f3->set('EDITUSER', $user);
        $f3->set('ROLES', \ACL\Model\RoleTable::instance()->getRoles());
        
        if(isset($user['id']))
            $f3->push('INFOS', 'Editing user "' . $user['username'] . '"');
        else
            $f3->push('INFOS', 'Adding a new user');
    }
    
    function render() {
        echo \Template::instance()->render('acl\controller\edituser.htm');
    }
}
