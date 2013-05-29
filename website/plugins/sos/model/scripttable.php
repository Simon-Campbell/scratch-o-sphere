<?php

namespace SOS\Model;

class ScriptTable extends \Prefab
{
    /**
     * Summary of getScript
     * @param $script The script id
     * @return Script
     */
    function getScript($script, $userid) {
        $script = \Base::instance()->set('result', \Config::instance()->DB->exec(
            "SELECT * FROM `sos_scripts` 
            WHERE 
            `id`=:id AND `user`=:user",            
            array(
                ':id' => $script,
                ':user' => $userid
            )
        ));
        return (sizeof($script) == 1) ?   new Script($script[0])    : new Script();
    }
    
    /**
     * Summary of getScripts
     * @return array
     */
    function getScripts($userid) {
        $scripts = array();
        foreach(\Base::instance()->set('result', \Config::instance()->DB->exec(
            "SELECT * FROM `sos_scripts` 
            WHERE `user`=:user",
            array(
                ':user' => $userid
            )
        )) as $script) {
            $scripts[] = new Script($script);
        }
        return $scripts;
    }
    
    /**
     * Summary of updateScript
     * @param $f3
     */
    function updateScript($f3) {
        $id = $f3->get("PARAMS.id");
        isset($id) ? $id : null;
        
        $script = new \DB\SQL\Mapper(\Config::instance()->DB, 'sos_scripts');
        $script->load(array('`id`=?', $id));
        
        $script->name = $f3->get("POST.name");
        $script->user = \ACL\Model\User::instance()->ID;
        $script->data = $f3->get("POST.data");
        isset($id) ? $script->save() : $script->insert();
        
        $f3->reroute("/scripts");        
    }
    
    /**
     * Summary of addScript
     * @param $data The values to store
     * @return boolean
     */
    function addScript($data) {
        throw new \Exception("Not implemented exception");
    }
    
    /**
     * Summary of deleteScript
     * @param $where The id of the user to delete
     * @return boolean
     */
    function deleteScript($id) {
        throw new \Exception("Not implemented exception");
    }
    
    /**
     * Summary of instance
     * @return ScriptTable
     */
    static function instance() {
		if (!\Registry::exists($class=get_called_class()))
			\Registry::set($class,new $class);
		return \Registry::get($class);
	}
}
