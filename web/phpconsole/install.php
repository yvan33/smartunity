<?php

include_once('phpconsole.php');

global $phpconsole;
$phpconsole = new Phpconsole();
$phpconsole->set_backtrace_depth(1);

/*
==============================================
USER'S SETTINGS
==============================================
*/


$phpconsole->add_user('Yvan', 'OCeB399CYjI4kQwrCSWndFVBUYxccNffe75Q2kvNBbo8ciMel6c1RPRKBqVRNvXi');

function phpconsole($data_sent, $user = false) {
    global $phpconsole;
    return $phpconsole->send($data_sent, $user);
}

function phpconsole_cookie($user) {
    global $phpconsole;
    $phpconsole->set_user_cookie($user);
}

function phpconsole_unset_cookie($user) {
    global $phpconsole;
    $phpconsole->unset_user_cookie($user);
}

/*
Shorthand function for lazy developers (author included)
*/

function p($data_sent, $user = false) {
    global $phpconsole;
    return $phpconsole->send($data_sent, $user);
}
