<?php
//Set up base classes//
$f3 = require('lib/base.php');
Config::instance();

//Set up F3 variables//
$f3->set('DEBUG', 3);
$f3->set('UI', 'ui/');
$f3->set('BASE', '');
$f3->set('EXTRACSS', array());
$f3->set('MYUSER', ACL\User::instance());
$f3->set('PAGELINKS', array());

//Extra styles//
$f3->push('EXTRACSS', "
    body {
        margin-top: 40px;
    }
");

//Do a permission check//
if(! ACL\User::instance()->ROLE->hasPermissionByRoute($f3->get('URI'))) {
    $text = "You attempted to access a page you do not have permission to view (" . $f3->get('URI') . ").";
    if(! ACL\User::instance()->isLoggedIn()) {
        $text .= " Try logging in?";
    }
    $_403 = new Views\_403($text);
    echo $_403->render();
    exit();
}

//Set up routes//
$f3->route('GET /',
	function($f3) {
        $index = new Views\Index();
        echo $index->render();
	}
);

$f3->route('POST /login',
    function($f3) {
        $test = ACL\User::instance()->login($f3->get("POST.email"), $f3->get("POST.password"));
        $f3->reroute('/');
    }
);

$f3->route('GET /logout',
    function($f3) {
        ACL\User::instance()->logout();
        $f3->reroute('/');
    }
);

$f3->route('GET /users',
    function($f3) {
        $users = new Views\Users();
        echo $users->render();
    }
);

$f3->route('GET /roles',
    function($f3) {
        $roles = new Views\Roles();
        echo $roles->render();
    }
);

$f3->route('GET /users/edit',
    function($f3) {
        $usersedit = new Views\UsersEdit();
        echo $usersedit->render();
    }
);

$f3->route('GET /users/edit/@id',
    function($f3) {
        $edituser = new Views\UsersEdit($f3->get('PARAMS.id'));
        echo $edituser->render();
    }
);

$f3->route('POST /users/edit/@id',
    function($f3) {
        echo $f3->get('PARAMS.id');
        ACL\User::instance()->update($f3->get('POST.username'), $f3->get('POST.realname'), $f3->get('POST.role'));
        $f3->reroute('/users');
    }
);

$f3->route('GET|POST /scriptinfo/@id',
    function($f3) {
        $obj = array(
            $f3->get('PARAMS.id')   => array (
                'name'  => 'TestScript',
                'url'   => '/getscript/' . $f3->get('PARAMS.id'),
                'otherdata' => 'cangohere'
            )
        );
        echo JSON::Encode($obj);
        echo '<br />';
        echo print_r($obj);
    }
);

//Simple route to update superuser perms//
$f3->route('GET /superuser',
    function($f3) {
        $total = 0;
        foreach(ACL\PermissionTable::instance()->getPermissions() as $perm) {
            $total += pow(2, $perm->ID);
        }
        ACL\RoleTable::instance()->updateRole(1, $total);
    }
);

//Output page//
$f3->run();