<?php

namespace FPiBB\View;

class Index
{    
    function render() {
        echo \Template::instance()->render('index.htm');
    }
}