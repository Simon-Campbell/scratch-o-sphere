<?php

namespace ACL\Config;

class Init extends \Prefab
{
    function __construct() {
        $f3 = \Base::instance();
        $f3->push('FULLNAVBAR',
            array(
                'title'     => 'Settings <i class="icon-wrench"></i>',
                'url'       => '',
                'rlogin'    => true,
                'class'     => '',
                'nohref'    => false,
                'prefindex' => 999,
                'subitems'  => array(
                    array(
                        'title'     => 'User',
                        'url'       => '',
                        'rlogin'    => true,
                        'class'     => 'nav-header',
                        'nohref'    => true,
                    ),
                    array(
                        'title'     => 'Manage Settings',
                        'url'       => '/settings',
                        'rlogin'    => true,
                        'class'     => '',
                        'nohref'    => false,
                    ),
                    array(
                        'title'     => '',
                        'url'       => '',
                        'rlogin'    => true,
                        'class'     => 'divider',
                        'nohref'    => true,
                    ),
                    array(
                        'title'     => 'Admin',
                        'url'       => '',
                        'rlogin'    => true,
                        'class'     => 'nav-header',
                        'nohref'    => true,
                    ),
                    array(
                        'title'     => 'Manage Users',
                        'url'       => '/users',
                        'rlogin'    => true,
                        'class'     => '',
                        'nohref'    => false,
                    ),            
                    array(
                        'title'     => 'Manage Roles',
                        'url'       => '/roles',
                        'rlogin'    => true,
                        'class'     => '',
                        'nohref'    => false,
                    ),
                    array(
                        'title'     => '',
                        'url'       => '',
                        'rlogin'    => true,
                        'class'     => 'divider',
                        'nohref'    => true,
                    ),
                    array(
                        'title'     => 'Logout',
                        'url'       => '/logout',
                        'rlogin'    => true,
                        'class'     => '',
                        'nohref'    => false,
                    ),    
                ),
            ));
        
        //Set up user//
        $f3->set('MYUSER',      \ACL\Model\User::instance());
    }
}

return Init::instance();