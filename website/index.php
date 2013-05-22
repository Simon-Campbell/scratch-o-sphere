<?php
//Timer
$start = microtime();
$start = explode(' ', $start);
$start = $start[1] + $start[0];

//Set up base classes//
$f3 = require('lib/base.php');
Config::instance();
Config::instance()->setVisitorMask();
Config::instance()->setSuperuserMask();

//Set up variables//
$f3->set('PATH', $_SERVER['PATH_INFO']);
$f3->set('EXTRACSS', array());
$f3->set('MYUSER', ACL\User::instance());
$f3->set('NAVBAR', Navbar::instance()->getNavbar());
$f3->set('PAGELINKS', array());
$f3->set('ERRORS', array());
$f3->set('INFOS', array());

//Extra styles//
$f3->push('EXTRACSS', 'body { margin-top: 40px; }');
$f3->push('EXTRACSS', 'input[type="text"], input[type="password"], input[type="email"] { height: 30px; margin-bottom: 0px; }');

//Set up routes//
$f3->config('config.cfg');

$test = "test";

//Do a permission check on the user//
ACL\User::instance()->routeCheck($f3);

//Timer
$finish = microtime();
$finish = explode(' ', $finish);
$finish = $finish[1] + $finish[0];
$f3->set('TIMER', round(($finish - $start), 4));

//Output page//
$f3->run();
