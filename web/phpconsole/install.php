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

//$phpconsole->enable_auto_recognition('your-domain.com'); // see https://app.phpconsole.com/docs/auto-recognition for details

<<<<<<< HEAD
// $phpconsole->add_user('Yvan', 'OCeB399CYjI4kQwrCSWndFVBUYxccNffe75Q2kvNBbo8ciMel6c1RPRKBqVRNvXi'); // you can add more developers, just execute another add_user()
$phpconsole->add_user('Florent', 'Bru9cB0WSX1cEGa07uhh6CewIIOwcMZDDdHPelzqHqDaMz3mRJMySVgPvr27vA2R'); // you can add more developers, just execute another add_user()
=======
$phpconsole->add_user('Yvan', 'OCeB399CYjI4kQwrCSWndFVBUYxccNffe75Q2kvNBbo8ciMel6c1RPRKBqVRNvXi'); // you can add more developers, just execute another add_user()
//$phpconsole->add_user('Florent', 'Bru9cB0WSX1cEGa07uhh6CewIIOwcMZDDdHPelzqHqDaMz3mRJMySVgPvr27vA2R'); // you can add more developers, just execute another add_user()
>>>>>>> 73faee45f6548ec61b6dfbafe95cffb32367cf24



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
