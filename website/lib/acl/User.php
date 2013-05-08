<?php

namespace ACL;

class User extends \Prefab
{
    public 
    $ID = -1,
    $USERNAME,
    $REALNAME,
    $ROLE;
    
    private $dbuser;
    
    function __construct() {
        $this->ID       = isset($_COOKIE['id'])         ? $_COOKIE['id']        : -1;
        $this->USERNAME = isset($_COOKIE['username'])   ? $_COOKIE['username']  : null;
        
        $this->dbuser = new \DB\SQL\Mapper(\Config::instance()->DB, 'acl_users');
        $this->dbuser->load(array(
                '`id`=?',
                $this->ID,
        ));
        
        $this->REALNAME = $this->dbuser->realname;
        $this->ROLE = RoleTable::instance()->getRoleById($this->dbuser->role);
    }
    
    function isLoggedIn() {
        return $this->ID > 0;
    }
    
    function login($email, $password) {
        if($this->isLoggedIn())
            return false;
        
        $this->dbuser->load(array(
                '`username`=?',
                $email,
                '`password`=?',
                md5($password),
            )
        );

        if($this->dbuser->id == null) {
            return false;
        }
        
        $this->setVariable('id',        $this->ID,          $this->dbuser->id,       time() + (60 * 60 * 24 * 30));
        $this->setVariable('username',  $this->USERNAME,    $this->dbuser->username, time() + (60 * 60 * 24 * 30) );
        $this->setVariable('realname',  $this->REALNAME,    $this->dbuser->realname, time() + (60 * 60 * 24 * 30) );
        
        $this->ROLE = RoleTable::instance()->getRoleById($this->dbuser->role);
        
        return true;
    }
    
    function logout() {
        $this->setVariable('id',       $this->ID,       -1,     time() + (60 * 60 * 24 * 30) );
        $this->setVariable('username', $this->USERNAME, null,   time() + (60 * 60 * 24 * 30) );
        $this->setVariable('realname', $this->REALNAME, null,   time() + (60 * 60 * 24 * 30) );
        $this->ROLE = new Role();
    }
    
    function setVariable($name, &$variable, $value, $time) {
        setcookie($name, $value, $time);
        $variable = $value;        
    }
    
    function update($username, $realname, $role) {
        $this->dbuser->username = $username;
        $this->dbuser->realname = $realname;
        $this->dbuser->role = $role;
        $this->dbuser->save();
    }    
    
    /**
     * Summary of instance
     * @return User
     */
    static function instance() {
		if (!\Registry::exists($class=get_called_class()))
			\Registry::set($class,new $class);
		return \Registry::get($class);
	}
}
