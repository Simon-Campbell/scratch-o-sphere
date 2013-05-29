<?php

namespace ACL\Model;

class RoleTable extends \Prefab
{
    /**
     * Summary of getRoleById
     * @param $id The id of the role
     * @return Role
     */
    function getRoleById($id) {
        $role = \Base::instance()->set('result', \Config::instance()->DB->exec(
            "SELECT * FROM `acl_roles` 
            WHERE 
            `id`=:id",
            array(
                ':id' => $id,
            )
        ));
        
        return (sizeof($role) == 1) ?   new Role($role[0])    : null;
    }
    
    /**
     * Summary of getRoleByName
     * @param $name The name of the role
     * @return Role
     */
    function getRoleByName($name) {
        $role = \Base::instance()->set('result', \Config::instance()->DB->exec(
            "SELECT * FROM `acl_roles` 
            WHERE 
            `name`=:name",
            array(
                ':name' => $name,
            )
        ));
        
        return (sizeof($role) == 1) ?   new Role($role[0])    : null;
    }
    
    /**
     * Summary of getRoles
     * @return array
     */
    function getRoles() {
        $roles = array();
        foreach(\Base::instance()->set('result', \Config::instance()->DB->exec(
            "SELECT * FROM `acl_roles`"
        )) as $role) {
            $roles[] = new Role($role);
        }
        return $roles;
    }
    
    /**
     * Summary of updateRole
     * @param $data the values to 
     */
    function updateRole($id, $name, $permissionmask) {
        $role = new \DB\SQL\Mapper(\Config::instance()->DB, 'acl_roles');
        $role->load(array('`id`=?', $id));
        $role->name = $name;
        $role->permissionmask = $permissionmask;
        isset($id) ? $role->save() : $role->insert();
    }
    
    function update($f3) {
        $id = $f3->get("PARAMS.id");
        isset($id) ? $id : null;        
        $name = $f3->get("POST.name");
        $permissions = $f3->get("POST.permissions");
        $permissionmask = 0;
        foreach($permissions as $perm) {
            $permissionmask += pow(2, $perm);
        }
        $this->updateRole($id, $name, $permissionmask);
        $f3->reroute("/roles");
    }
    
    /**
     * Summary of addRole
     * @param $data The values to store
     * @return boolean
     */
    function addRole($data) {
        throw new \Exception("Not implemented exception");
    }
    
    /**
     * Summary of deleteRole
     * @param $where The id of the user to delete
     * @return boolean
     */
    function deleteRole($id) {
        throw new \Exception("Not implemented exception");
    }
    
    /**
     * Summary of instance
     * @return RoleTable
     */
    static function instance() {
		if (!\Registry::exists($class=get_called_class()))
			\Registry::set($class,new $class);
		return \Registry::get($class);
	}
}
