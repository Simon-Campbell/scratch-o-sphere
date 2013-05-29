<?php

namespace SOS\Config;

class Init extends \Prefab
{
    function __construct() {
        $f3 = \Base::instance();
        $f3->push('FULLNAVBAR',
            array(
                'title'     => 'Scripts',
                'url'       => '/scripts',
                'rlogin'    => true,
                'class'     => '',
                'nohref'    => false,
                'prefindex' => 3,
                'subitems'  => array(),
            ));
    }
}

return Init::instance();