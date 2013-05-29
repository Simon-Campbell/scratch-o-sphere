<?php

namespace ACL\Model;

class UserTable extends \Prefab
{    
    /**
     * Summary of getUser
     * @param $id id of the user
     * @return array
     */
    function getUser($id) {
        $user = \Base::instance()->set('result', \Config::instance()->DB->exec(
            "SELECT * FROM `acl_users` 
            WHERE 
            `id`=:id",
            array(
                ':id' => $id,
            )
        ));
        
        return (sizeof($user) == 1) ? $user[0]  : 
            array(
                'id'        => null,
                'username'  => null,
                'realname'  => null,
                'role'      => null,
                'email'     => null,
                'api_token' => null
            );
    }
    
    function checkUserToken($token) {
        $user = \Base::instance()->set('result', \Config::instance()->DB->exec(
            "SELECT * FROM `acl_users` 
            WHERE 
            `api_token`=:token",
            array(
                ':token' => $token,
            )
        ));
        
        return (sizeof($user) == 1) ? $user[0]['id'] : false;
    }
    
    function getUserToken($username, $pass, $new = false) {
        $user = \Base::instance()->set('result', \Config::instance()->DB->exec(
            "SELECT * FROM `acl_users` 
            WHERE 
            `username`=:user AND `password`=:pass",
            array(
                ':user' => $username,
                ':pass' => md5($pass),
            )
        ));
        
        $user = (sizeof($user) == 1) ? $user[0]  : 
            array(
                'id'        => null,
                'username'  => null,
                'realname'  => null,
                'role'      => null,
                'email'     => null,
                'api_token' => null
            );

        if($user['id'] == null) {
            return false;
        }
        
        $token = $user['api_token'];
        if($new) {
            $token = md5($username . $pass . time());
            \Config::instance()->DB->exec(
                "UPDATE `acl_users` 
                SET `api_token`=:token 
                WHERE `id`=:id",
                array(
                    ':token' => $token,
                    ':id' => $user['id'],
                )
            );
        }        
        return $token;
    }
    
    
    
    /**
     * Summary of getUsers
     * @return array
     */
    function getUsers() {
        return \Base::instance()->set('result', \Config::instance()->DB->exec(
            "SELECT * FROM `acl_users`"
        ));
    }
    
    /**
     * Summary of userExists
     * @param $id id of the user
     * @return boolean
     */
    function userExists($id) {
        $user = \Base::instance()->set('result', \Config::instance()->DB->exec(
            "SELECT * FROM `acl_users` 
            WHERE 
            `id`=:id",
            array(
                ':id' => $id,
            )
        ));
        
        return (sizeof($user) == 1);
    }
    
    /**
     * Summary of addUser
     * @param $data The values to store
     * @return boolean
     */
    function addUser($data) {
        throw new \Exception("Not implemented exception");
    }
    
    /**
     * Summary of deleteUser
     * @param $where The id of the user to delete
     * @return boolean
     */
    function deleteUser($id) {
        throw new \Exception("Not implemented exception");
    }    
    
    function setVisitorMask() {
        if(User::instance()->isLoggedIn())
            return;
        $total = 0;
        $routes = array(
            '/login',
        );
        
        foreach($routes as $route) {
            $total += pow(2, PermissionTable::instance()->getPermissionByRoute($route)->ID);
        }
        
        $visitor = RoleTable::instance()->getRoleByName("Visitor");
        if($visitor->PERMISSIONMASK != $total) {
            RoleTable::instance()->updateRole($visitor->ID, "Visitor", $total);
        }
    }
    
    function setSuperuserMask() {       
        if(!User::instance()->isRole("Superuser"))
            return;
        
        $total = 0;
        foreach(PermissionTable::instance()->getPermissions() as $perm) {
            $total += pow(2, $perm->ID);
        }
        
        $superuser = RoleTable::instance()->getRoleByName("Superuser");
        if($superuser->PERMISSIONMASK != $total) {
            RoleTable::instance()->updateRole($superuser->ID, "Superuser", $total);
        }
    }
    
    /**
     * Summary of instance
     * @return UserTable
     */
    static function instance() {
		if (!\Registry::exists($class=get_called_class()))
			\Registry::set($class,new $class);
		return \Registry::get($class);
	}
}
