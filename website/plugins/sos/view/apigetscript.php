<?php

namespace SOS\View;

class APIGetScript
{
    private $json = array();
    function __construct() {
        $f3 = \Base::instance();
        
        $token = $f3->get('PARAMS.token');
        $id = $f3->get('PARAMS.id');
        
        $validtoken = \ACL\Model\UserTable::instance()->checkUserToken($token);
        
        if($validtoken !== false) {
            $this->json['status'] = true;
            if($id == null)
                $this->json['scripts'] = \SOS\Model\ScriptTable::instance()->getScripts($validtoken);
            else
                $this->json['scripts'] = array(\SOS\Model\ScriptTable::instance()->getScript($id, $validtoken));
        }
        else
            $this->json['status'] = false;
    }
    
    function render() {
        echo \JSON::encode($this->json);
    }
}
