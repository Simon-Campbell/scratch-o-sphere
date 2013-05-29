<?php

namespace SOS\View;

class Scripts
{
    function __construct() {
        $f3 = \Base::instance();
        $search = $f3->get('GET.search');
        if(isset($search)) {
            $searchedScript = \SOS\Model\ScriptTable::instance()->getScript($search, \ACL\Model\User::instance()->ID);
            if($searchedScript->ID == -1) {
                $f3->set('SCRIPTS', \SOS\Model\ScriptTable::instance()->getScripts(\ACL\Model\User::instance()->ID));
                $f3->push('ERRORS', 'Script does not exist');
            }
            else {
                $f3->set('SCRIPTS', array(
                    $searchedScript
                ));
            }
        }
        else {
            $f3->set('SCRIPTS', \SOS\Model\ScriptTable::instance()->getScripts(\ACL\Model\User::instance()->ID));
        }
    }
    
    function render() {
        echo \Template::instance()->render('sos\controller\scripts.htm');
    }
}