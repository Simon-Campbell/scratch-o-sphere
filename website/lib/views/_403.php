<?php

namespace Views;

class _403
{
    function __construct($text = "") {
        $f3 = \Base::instance();
        $f3->set('ERROR.code', 403);
        $f3->set('ERROR.title', 'Forbidden');
        $f3->set('ERROR.text', $text);
    }
    
    function render() {
        echo \Template::instance()->render('error.htm');
    }
}