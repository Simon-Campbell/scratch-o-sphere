<?php

namespace ACL\Config;

class Database extends \DBEnsurer
{    
    function __construct() {
        $this->TABLES[] = new \FPiBB\Model\SqlTable("acl_users", array(
            new \FPiBB\Model\SqlColumn("id", \MySqlDbType::Int32, true, true, true, true),
            new \FPiBB\Model\SqlColumn("username", \MySqlDbType::VarChar, true),
            new \FPiBB\Model\SqlColumn("password", \MySqlDbType::VarChar),
            new \FPiBB\Model\SqlColumn("realname", \MySqlDbType::VarChar),
            new \FPiBB\Model\SqlColumn("role", \MySqlDbType::Int32),
            new \FPiBB\Model\SqlColumn("email", \MySqlDbType::VarChar),
            new \FPiBB\Model\SqlColumn("api_token", \MySqlDbType::VarChar),
        ));
        
        $this->TABLES[] = new \FPiBB\Model\SqlTable("acl_roles", array(
            new \FPiBB\Model\SqlColumn("id", \MySqlDbType::Int32, true, true, true, true),
            new \FPiBB\Model\SqlColumn("name", \MySqlDbType::VarChar),
            new \FPiBB\Model\SqlColumn("permissionmask", \MySqlDbType::Int32),
        ));
        
        $this->TABLES[] = new \FPiBB\Model\SqlTable("acl_permissions", array(
            new \FPiBB\Model\SqlColumn("id", \MySqlDbType::Int32, true, true, true, true),
            new \FPiBB\Model\SqlColumn("name", \MySqlDbType::VarChar),
            new \FPiBB\Model\SqlColumn("route", \MySqlDbType::VarChar),
        ));
        parent::__construct();
    }
}

return new Database();