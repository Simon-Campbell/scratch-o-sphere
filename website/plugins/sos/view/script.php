<?php

namespace SOS\View;

class Script
{
    
    function __construct() {
        $f3 = \Base::instance();
        $paramId = $f3->get('PARAMS.id');
        $id = isset($paramId) ? $paramId : -1;
        
        $script = \SOS\Model\ScriptTable::instance()->getScript($id, \ACL\Model\User::instance()->ID);
        
        if($script->ID == -1) {
            $f3->push('INFOS', 'Creating a new Script');
        }
        
        $f3->set('SCRIPT', $script);
    }
    
    function render() {
        echo \Template::instance()->render('sos\controller\script.htm');
    }
}