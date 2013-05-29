<?php

namespace FPiBB\Config;

class Init extends \Prefab
{
    function __construct() {
        $f3 = \Base::instance();
        $f3->push('FULLNAVBAR',
            array(
                'title'     => 'Home',
                'url'       => '/',
                'rlogin'    => false,
                'class'     => '',
                'nohref'    => false,
                'prefindex' => -1,
                'subitems'  => array(),
            ));
    }
}

return Init::instance();