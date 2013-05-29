<?php

namespace ACL\View;

class APIUserLogin
{
    private $json = array();
    function __construct() {
        $f3 = \Base::instance();
        
        $username = $f3->get('PARAMS.username');
        $pass = $f3->get('PARAMS.pass');
        
        $token = \ACL\Model\UserTable::instance()->getUserToken($username, $pass, true);
        if(!$token)
            $this->json['status'] = false;
        else {
            $this->json['status'] = true;
            $this->json['token'] = $token;
        }
    }
    
    function render() {
        echo \JSON::encode($this->json);
    }
}