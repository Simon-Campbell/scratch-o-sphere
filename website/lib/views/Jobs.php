<?php

namespace Views;

class Jobs
{
    function __construct() {
        $f3 = \Base::instance();
        $f3->set('JOBS', \ACL\UserTable::instance()->getUsers());
    }
    
    function render() {
        return \Template::instance()->render('jobs.htm');
    }
}