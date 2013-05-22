<?php

namespace ACL;

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
                'lipadmin'  => null,
                'inspector' => null,
            );
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
