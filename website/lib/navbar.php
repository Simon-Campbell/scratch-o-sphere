<?php

class Navbar extends Prefab
{
    private $NAVBAR = array();
    
    function __construct() {
        $this->NAVBAR = array(
            array(
                'title'     => 'Home',
                'url'       => '/',
                'rlogin'    => false,
                'class'     => '',
                'nohref'    => false,
                'subitems'  => array(),
            ),
            array(
                'title'     => 'Scripts <i class="icon-pencil"></i>',
                'url'       => '/scripts',
                'rlogin'    => true,
                'class'     => '',
                'nohref'    => false,
                'subitems'  => array(),
            ),
            array(
                'title'     => 'Settings <i class="icon-wrench"></i>',
                'url'       => '',
                'rlogin'    => true,
                'class'     => '',
                'nohref'    => false,
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
            ),
        );
        $path = Base::instance()->get('PATH');

        $newnavbar = array();
        foreach($this->NAVBAR as $title => $nav) {
            if($nav['rlogin'] && !ACL\User::instance()->isLoggedIn())
                continue;
            if(!ACL\User::instance()->hasPermissionByRoute($nav['url']))
                continue;            
            if($path == $nav['url'])
                $nav['class'] .= "active";
            
            $newsubnav = array();
            foreach($nav['subitems'] as $subtitle => $subnav) {
                if($subnav['rlogin'] && !ACL\User::instance()->isLoggedIn())
                    continue;
                if(!ACL\User::instance()->hasPermissionByRoute($subnav['url']))
                    continue;
                
                if($path == $subnav['url']) {
                    $subnav['class'] .= "active";
                    $nav['class'] .= "active";
                }
                
                $newsubnav[$subtitle] = $subnav;
            }

            $newnavbar[$title] = $nav;
            $newnavbar[$title]['subitems']  = $newsubnav;
        }        
        $this->NAVBAR = $newnavbar;
        
        $anyActiveCheck = false;
        foreach($this->NAVBAR as $nav) {
            if(stripos($nav['class'], 'active') !== false) {
                $anyActiveCheck = true;
                break;
            }
        }
        
        if(!$anyActiveCheck) {
            $checkPath = str_replace('/', '', $path);
            foreach($this->NAVBAR as $key => $nav) {
                
                if(stripos($checkPath, str_replace('/', '', $nav['url'])) !== false) {
                    $this->NAVBAR[$key]['class'] .= 'active';
                }
                
            }
        }
    }
    
    /**
     * Summary of getNavbar
     * @return array
     */
    function getNavbar() {
        return $this->NAVBAR;
    }
    
    /**
     * Summary of instance
     * @return Navbar
     */
    static function instance() {
		if (!\Registry::exists($class=get_called_class()))
			\Registry::set($class,new $class);
		return \Registry::get($class);
	}
}
