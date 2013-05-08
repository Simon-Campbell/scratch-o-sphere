<?php

namespace Views;

class UsersEdit
{
    function __construct($id = -1) {
        $f3 = \Base::instance();
        $f3->set('EDITUSER', \ACL\UserTable::instance()->getUser($id));
        $f3->set('ROLES', \ACL\RoleTable::instance()->getRoles());
    }
    
    function render() {
        return \Template::instance()->render('edituser.htm');
    }
}
