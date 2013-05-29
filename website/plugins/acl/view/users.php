<?php

namespace ACL\View;

class Users
{
    function __construct() {
        $f3 = \Base::instance();
        $f3->set('USERS', \ACL\Model\UserTable::instance()->getUsers());
    }
    
    function render() {
        echo \Template::instance()->render('acl\controller\userstable.htm');
    }
}