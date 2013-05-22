<?php

namespace ACL;

class User extends \Prefab
{
    public 
    $ID = -1,
    $USERNAME,
    $REALNAME,
    $EMAIL,
    $LIPADMIN,
    $INSPECTOR;
    
    private $ROLE, $dbuser;
    
    function __construct() {
        $this->ID       = isset($_COOKIE['id'])         ? $_COOKIE['id']        : -1;
        $this->USERNAME = isset($_COOKIE['username'])   ? $_COOKIE['username']  : null;
        
        $this->dbuser = new \DB\SQL\Mapper(\Config::instance()->DB, 'acl_users');
        $this->dbuser->load(array(
                '`id`=?',
                $this->ID,
        ));
        
        $this->REALNAME = $this->dbuser->realname;
        $this->EMAIL = $this->dbuser->email;
        $this->ROLE = RoleTable::instance()->getRoleById($this->dbuser->role);
        if($this->ROLE == null) {
            $this->ROLE = RoleTable::instance()->getRoleByName("Visitor");
        }
        $this->LIPADMIN = $this->dbuser->lipadmin;
        $this->INSPECTOR = $this->dbuser->inspector;
    }
    
    function isLoggedIn() {
        return $this->ID > 0;
    }
    
    function login($f3) {
        $email = $f3->get('POST.email');
        $password = $f3->get('POST.password');        
        
        if($this->isLoggedIn())
            $f3->reroute('/');
        
        $this->dbuser->load(array(
                '`username`=?',
                $email,
                '`password`=?',
                md5($password),
            )
        );

        if($this->dbuser->id == null) {
            $f3->push('ERRORS', 'Invalid login request, username/password incorrect');
            $index = new \Views\Index();
            $index->render();
            die();
        }
        
        $this->setVariable('id',        $this->ID,          $this->dbuser->id,       time() + (60 * 60 * 24 * 30));
        $this->setVariable('username',  $this->USERNAME,    $this->dbuser->username, time() + (60 * 60 * 24 * 30) );
        $this->setVariable('realname',  $this->REALNAME,    $this->dbuser->realname, time() + (60 * 60 * 24 * 30) );
        
        $this->ROLE = RoleTable::instance()->getRoleById($this->dbuser->role);
        
        $f3->reroute('/');
    }
    
    function logout($f3) {
        $this->setVariable('id',       $this->ID,       -1,     time() + (60 * 60 * 24 * 30) );
        $this->setVariable('username', $this->USERNAME, null,   time() + (60 * 60 * 24 * 30) );
        $this->setVariable('realname', $this->REALNAME, null,   time() + (60 * 60 * 24 * 30) );
        $f3->reroute('/');
    }
    
    function update($f3) {
        $realname = $f3->get('POST.realname');
        $username = $f3->get('POST.username');
        $password = $f3->get('POST.password');
        $email = $f3->get('POST.email');
        $role = $f3->get('POST.role');
        $lipadmin = $f3->get('POST.lipadmin');
        $inspector = $f3->get('POST.inspector');
        
        if(isset($realname)) $this->dbuser->realname = $realname;
        if(isset($username)) $this->dbuser->username = $username;
        if(isset($password)) $this->dbuser->password = md5($password);
        if(isset($email)) $this->dbuser->email = $email;
        if(isset($role)) $this->dbuser->role = $role;
        if(isset($lipadmin)) $this->dbuser->lipadmin = $lipadmin;
        if(isset($inspector)) $this->dbuser->inspector = $inspector;
        
        !isset($password) ? $this->dbuser->save() : $this->dbuser->insert();
        $f3->reroute('/users');
    }
    
    function delete($f3) {
        $id = $f3->get('PARAMS.id');        
        if(isset($id) && UserTable::instance()->userExists($id)) {            
            if($this->ID == $id) {
                $error = new \Views\Error(403, "Forbidden", "You attempted to delete your current user");
                $error->render();
                exit();
            }
            $this->dbuser->load(array('`id`=?',$id));
            $this->dbuser->erase();
        }
        $f3->reroute('/users');
    }
    
    private function setVariable($name, &$variable, $value, $time) {
        setcookie($name, $value, $time);
        $variable = $value;        
    }
    
    function routeCheck($f3, $reroute = true) {
        if(!$reroute) {
            return $this->ROLE->hasPermissionByRoute($f3->get('PATH'));
        }
        if($this->ROLE == null || !$this->ROLE->hasPermissionByRoute($f3->get('PATH'))) {
            $text = "You attempted to access a page you do not have permission to view (" . $f3->get('PATH') . ").";
            if(!$this->isLoggedIn())
                $text .= " Try logging in?";
            
            $error = new \Views\Error(403, "Forbidden", $text);
            $error->render();
            exit();
        }
    }
    
    function hasPermissionByRoute($route) {
        if($this->ROLE == null)
            return false;
        return $this->ROLE->hasPermissionByRoute($route);
    }
    
    function isRole($role) {
        return $this->ROLE->NAME == $role;
    }
    
    function getRoleMask() {
        return $this->ROLE->PERMISSIONMASK;
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
