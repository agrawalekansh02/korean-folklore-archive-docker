<?php
include_once('lib.php');

if (!isset($_GET['token'])) {
	header('Location: http://admin.cdh.ucla.edu/cdhit/jlogin.php?site=' . HOST . 'login.php');
	exit();
}

$md5 = substr($_GET['token'], 0, 32);
$ts = substr($_GET['token'], 32);
$tc = time();
if (abs($tc - $ts) > 60) exit("Token has expired.");

$valid_md5 = md5($_GET['email'] . SECRET . $ts);

//if ($md5 != $valid_md5) exit('Token is invalid.');



$eppn = preg_replace('/@.+/','',$_GET['eppn']);
$cookie = get_token($eppn) . $eppn;
setcookie('kfl', $cookie, time() + 3600*12,'/');
header("Location:" . HOST . 'dashboard.php');
 
