<?php

namespace Views\SOS;

class Script
{
    
    function __construct() {
        $f3 = \Base::instance();
        $paramId = $f3->get('PARAMS.id');
        $id = isset($paramId) ? $paramId : -1;
        
        $script = \SOS\ScriptTable::instance()->getScript($id);
        
        if($script->ID == -1) {
            $f3->push('INFOS', 'Creating a new Script');
        }
        
        $f3->set('SCRIPT', $script);
    }
    
    function render() {
        echo \Template::instance()->render('sos\script.htm');
    }
}