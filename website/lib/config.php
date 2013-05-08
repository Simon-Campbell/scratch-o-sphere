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
