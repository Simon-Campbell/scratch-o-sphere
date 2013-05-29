<?php

namespace SOS\Config;

class Database extends \DBEnsurer
{    
    function __construct() {
        
        $this->TABLES[] = new \FPiBB\Model\SqlTable("sos_scripts", array(
            new \FPiBB\Model\SqlColumn("id", \MySqlDbType::Int32, true, true, true, true),
            new \FPiBB\Model\SqlColumn("user", \MySqlDbType::Int32),
            new \FPiBB\Model\SqlColumn("name", \MySqlDbType::VarChar),
            new \FPiBB\Model\SqlColumn("data", \MySqlDbType::Text),
        ));
        
        parent::__construct();
    }
}

return new Database();