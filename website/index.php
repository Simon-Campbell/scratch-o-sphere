<?php
//Timer
$start = microtime();
$start = explode(' ', $start);
$start = $start[1] + $start[0];

//Set up base classes//
$f3 = require('lib/base.php');
if(is_file('config.cfg')) $f3->config('config.cfg');
Config::instance();

//Set up hive arrays//
$f3->set('FULLNAVBAR',  array());
$f3->set('EXTRACSS',    array());
$f3->set('PAGELINKS',   array());
$f3->set('ERRORS',      array());
$f3->set('INFOS',       array());

//Set up variables//
$f3->concat('PLUGINS', 
    ';' . $f3->fixslashes(__DIR__) . '/plugins/' . 
    ';' . $f3->fixslashes(__DIR__) . '/globals/');
$f3->concat('UI', ';' . $f3->fixslashes(__DIR__) . '/plugins/');
$f3->set('PATH',        $_SERVER['PATH_INFO']);

//Set up plugins//
Config::instance()->pluginConfig();

//Set up nav//
$f3->set('NAVBAR',      FPiBB\Model\Navbar::instance()->getNavbar());

//Extra styles//
$f3->push('EXTRACSS', 'body { margin-top: 40px; }');
$f3->push('EXTRACSS', 'input[type="text"], input[type="password"], input[type="email"] { height: 30px; margin-bottom: 0px; }');

//User checks//
ACL\Model\UserTable::instance()->setSuperuserMask();
ACL\Model\UserTable::instance()->setVisitorMask();
$test = "test";


//Timer
$finish = microtime();
$finish = explode(' ', $finish);
$finish = $finish[1] + $finish[0];
$f3->set('TIMER', round(($finish - $start), 4));

//Output page//
$f3->run();
