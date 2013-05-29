<?php

namespace FPiBB\Model;

class Navbar extends \Prefab
{
    private $NAVBAR = array();
    
    function __construct() {
        $f3 = \Base::instance();
        $this->NAVBAR = $f3->get('FULLNAVBAR');
        usort($this->NAVBAR, 'Toolbox::navsort');        
        $path = \Base::instance()->get('PATH');

        $newnavbar = array();
        foreach($this->NAVBAR as $title => $nav) {
            if($nav['rlogin'] && !\ACL\Model\User::instance()->isLoggedIn())
                continue;
            if(!\ACL\Model\User::instance()->hasPermissionByRoute($nav['url']))
                continue;            
            if($path == $nav['url'])
                $nav['class'] .= "active";
            
            $newsubnav = array();
            foreach($nav['subitems'] as $subtitle => $subnav) {
                if($subnav['rlogin'] && !\ACL\Model\User::instance()->isLoggedIn())
                    continue;
                if(!\ACL\Model\User::instance()->hasPermissionByRoute($subnav['url']))
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
            foreach($this->NAVBAR as $key => $nav)                
                if(stripos($checkPath, str_replace('/', '', $nav['url'])) !== false)
                    $this->NAVBAR[$key]['class'] .= 'active';
        }
    }
    
    /**
     * @return array
     */
    function getNavbar() {
        return $this->NAVBAR;
    }
    
    /**
     * @return Navbar
     */
    static function instance() {
		if (!\Registry::exists($class=get_called_class()))
			\Registry::set($class,new $class);
		return \Registry::get($class);
	}
}
