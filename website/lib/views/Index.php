<?php

namespace Views;

class Index
{
    function __construct() {
        $f3 = \Base::instance();
    }
    
    function render() {
        return \Template::instance()->render('index.htm');
    }
}