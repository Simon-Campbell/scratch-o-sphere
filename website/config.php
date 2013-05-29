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
    
    function pluginConfig() {
        $f3 = Base::instance();
        $dir = new DirectoryIterator('plugins/');
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot() && stripos($fileinfo->getFilename(), ".") === false) {
                if(!is_dir('plugins/' . $fileinfo->getFilename() . '/config/'))
                    continue;
                if(is_file($database = 'plugins/' . $fileinfo->getFilename() . '/config/database.php'))
                    require($database);
                if(is_file($config = 'plugins/' . $fileinfo->getFilename() . '/config/config.cfg'))
                    $f3->config($config);
                if(is_file($init = 'plugins/' . $fileinfo->getFilename() . '/config/init.php'))
                    require($init);
            }
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
