<?php

namespace Views\ACL;

class Users
{
    function __construct() {
        $f3 = \Base::instance();
        $f3->set('USERS', \ACL\UserTable::instance()->getUsers());
    }
    
    function render() {
        echo \Template::instance()->render('acl\userstable.htm');
    }
}