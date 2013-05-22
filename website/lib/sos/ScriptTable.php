<?php

namespace SOS;

class ScriptTable extends \Prefab
{
    /**
     * Summary of getScript
     * @param $script The script id
     * @return Script
     */
    function getScript($script) {
        $script = \Base::instance()->set('result', \Config::instance()->DB->exec(
            "SELECT * FROM `sos_scripts` 
            WHERE 
            `id`=:id",
            array(
                ':id' => $script,
            )
        ));
        return (sizeof($script) == 1) ?   new Script($script[0])    : new Script();
    }
    
    /**
     * Summary of getScripts
     * @return array
     */
    function getScripts() {
        $scripts = array();
        foreach(\Base::instance()->set('result', \Config::instance()->DB->exec(
            "SELECT * FROM `sos_scripts`"
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
