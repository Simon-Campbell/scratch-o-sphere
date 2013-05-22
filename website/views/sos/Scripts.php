<?php

namespace Views\SOS;

class Scripts
{
    function __construct() {
        $f3 = \Base::instance();
        $search = $f3->get('GET.search');
        if(isset($search)) {
            $searchedScript = \SOS\ScriptTable::instance()->getScript($search);
            if($searchedScript->ID == -1) {
                $f3->set('SCRIPTS', \SOS\ScriptTable::instance()->getScripts());
                $f3->push('ERRORS', 'Script does not exist');
            }
            else {
                $f3->set('SCRIPTS', array(
                    $searchedScript
                ));
            }
        }
        else {
            $f3->set('SCRIPTS', \SOS\ScriptTable::instance()->getScripts());
        }
    }
    
    function render() {
        echo \Template::instance()->render('sos\scripts.htm');
    }
}