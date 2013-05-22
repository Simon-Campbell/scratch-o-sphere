<?php

class Config extends Prefab
{
    /**
     * Summary of $DB
     * @var DB\SQL
     */
    public $DB;
    
    function __construct() {
        require ('db.local.php');
        
        $this->DB = new DB\SQL(
            'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_DATABASE,
            DB_USER,
            DB_PASSWORD
        );
    }
    
    function setVisitorMask() {
        if(ACL\User::instance()->isLoggedIn())
            return;
        $total = 0;
        $routes = array(
            '/login',
            '/logout',
        );
        
        foreach($routes as $route) {
            $total += pow(2, ACL\PermissionTable::instance()->getPermissionByRoute($route)->ID);
        }
        
        $visitor = ACL\RoleTable::instance()->getRoleByName("Visitor");
        if($visitor->PERMISSIONMASK != $total) {
            ACL\RoleTable::instance()->updateRole($visitor->ID, "Visitor", $total);
        }
    }
    
    function setSuperuserMask() {       
        if(!ACL\User::instance()->isRole("Superuser"))
            return;
        
        $total = 0;
        foreach(ACL\PermissionTable::instance()->getPermissions() as $perm) {
            $total += pow(2, $perm->ID);
        }
        
        $superuser = ACL\RoleTable::instance()->getRoleByName("Superuser");
        if($superuser->PERMISSIONMASK != $total) {
            ACL\RoleTable::instance()->updateRole($superuser->ID, "Superuser", $total);
        }
    }
    
    /**
     * Summary of instance
     * @return Config
     */
    static function instance() {
		if (!Registry::exists($class=get_called_class()))
			Registry::set($class,new $class);
		return Registry::get($class);
	}
}
