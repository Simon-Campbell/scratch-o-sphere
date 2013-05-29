<?php

namespace ACL\Model;

class PermissionTable extends \Prefab
{    
    /**
     * Summary of getPermissionByRoute
     * @param $route The route of the permission
     * @return Permission
     */
    function getPermissionByRoute($route) {
        
        $permission = \Base::instance()->set('result', \Config::instance()->DB->exec(
            "SELECT * FROM `acl_permissions` 
            WHERE 
            `route`=:route",
            array(
                ':route' => $route,
            )
        ));
        
        return (sizeof($permission) == 1) ?   new Permission($permission[0])    : null;
    }
    
    /**
     * Summary of getPermissionById
     * @param $id The id of the permission
     * @return Permission
     */
    function getPermissionById($id) {
        $permission = \Base::instance()->set('result', \Config::instance()->DB->exec(
            "SELECT * FROM `acl_permissions` 
            WHERE 
            `id`=:id",
            array(
                ':id' => $id,
            )
        ));
        
        return (sizeof($permission) == 1) ?   new Permission($permission[0])    : null;
    }
    
    /**
     * Summary of getPermissions
     * @return array
     */
    function getPermissions() {
        $permissions = array();
        foreach(\Base::instance()->set('result', \Config::instance()->DB->exec(
            "SELECT * FROM `acl_permissions`"
        )) as $permission) {
            $permissions[] = new Permission($permission);
        }
        return $permissions;
    }
    
    /**
     * Summary of addPermission
     * @param $data The values to store
     * @return boolean
     */
    function addPermission($data) {
        throw new \Exception("Not implemented exception");
    }
    
    /**
     * Summary of deletePermission
     * @param $where The id of the user to delete
     * @return boolean
     */
    function deletePermission($id) {
        throw new \Exception("Not implemented exception");
    }
    
    
    /**
     * Summary of instance
     * @return PermissionTable
     */
    static function instance() {
		if (!\Registry::exists($class=get_called_class()))
			\Registry::set($class,new $class);
		return \Registry::get($class);
	}
}
