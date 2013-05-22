<?php

namespace Views;

class Index
{
    function __construct() {
    }
    
    function render() {
        echo \Template::instance()->render('index.htm');
    }
}