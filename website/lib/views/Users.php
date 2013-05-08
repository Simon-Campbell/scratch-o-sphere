<?php

namespace Views;

class Users
{
    function __construct() {
        $f3 = \Base::instance();
        $f3->set('USERS', \ACL\UserTable::instance()->getUsers());
    }
    
    function render() {
        return \Template::instance()->render('userstable.htm');
    }
}