<?php

namespace Views;

class Error
{
    function __construct($code, $title = "", $text = "") {
        $f3 = \Base::instance();
        $f3->set('ERROR.code', $code);
        $f3->set('ERROR.title', $title);
        $f3->set('ERROR.text', $text);
    }
    
    function render() {
        echo \Template::instance()->render('error.htm');
    }
}