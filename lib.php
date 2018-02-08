<?php

// if local
if ($_SERVER['SERVER_NAME']=='localhost'){
    define('SECRET','');
    define('HOST','http://localhost:8888/kfl/');
    define('DB_HOST', '');
    define('DB_USERNAME', '');
    define('DB_PASSWORD', '');
    define('DB_NAME', '');
}
else if ($_SERVER['SERVER_NAME']=='dev.kfl.humnet.ucla.edu'){
// if test
    define('SECRET','');
    define('HOST','http://dev.kfl.humnet.ucla.edu/');
    define('DB_HOST', 'localhost');
    define('DB_USERNAME', '');
    define('DB_PASSWORD', '');
    define('DB_NAME', '');
}
else if ($_SERVER['SERVER_NAME']=='kfl.humnet.ucla.edu'){
// if production
    define('SECRET','');
    define('HOST','http://kfl.humnet.ucla.edu/');
    define('DB_HOST', 'localhost');
    define('DB_USERNAME', '');
    define('DB_PASSWORD', '');
    define('DB_NAME', '');
}

define_passcode();

function get_connection() {
    $connection = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    mysqli_query($connection, "SET NAMES 'utf8'");
    return $connection;
}

$dbConn = get_connection();

include_once('user.php');

function get_token($str) {
    return md5(SECRET . $str);
}

function get_user() {
    return array(
        'collector_id' => 1,
        'ucla_id' => 'jwan123',
        'name' => 'Jun Wan',
        'is_admin' => true
    );    
}

function get_auth_sql($and = true) {
    global $user;
    $and = ($and) ? ' and' : '';
    if ($user->is_admin()) return '';
    else return "$and collector_id=" . $user->get('id');
}

function get_collector_sql($and = true) {
    global $user;
    $and = ($and) ? ' and' : '';
    return "$and collector_id=" . $user->get('id');
}

function get_record($table, $id, $collector_id=false) {
    global $user, $dbConn;
    if (!$user->is_admin()) $collector_id = $user->get('id');
    $and = ($collector_id) ? "and collector_id=$collector_id" : '';

    $sql = "select * from $table where ${table}_id = $id $and";
    $result = mysqli_query($dbConn, $sql);
    if (!$result) return 'Query was unsuccesssful';
    if ($row=mysqli_fetch_assoc($result)) return $row;
    else return array();
}

function get_records($table, $collector_id=false) {
    global $user, $dbConn;
    if (!$user->is_admin()) $collector_id = $user->get('id');
    $and = ($collector_id) ? "and collector_id=$collector_id" : '';

    $sql = "select * from $table where 1 $and";
    $result = mysqli_query($dbConn, $sql);
    if (!$result) return array();
    $data = array();
    while ($row=mysqli_fetch_assoc($result)) $data[] = $row;
    return $data;
}

function check_auth() {
    global $user;
	if (isset($_COOKIE['kfl'])){
		if (!$user->auth) { exit('Not authorized'); }
	    if (!$user->is_user()) { 
			header("Location: " . HOST . "collector"); 
			exit(); 
		}
	}
	else{
		header("Location: " . HOST . "login"); 
		exit(); 
    }
    return $user;
}

function define_passcode(){
	$passcode = file_get_contents('mini/passcode.txt', FILE_USE_INCLUDE_PATH);
	define('PASSCODE', $passcode );
}

function update_passcode($passcode){
	file_put_contents('mini/passcode.txt', $passcode);
}

function add_quarter($quarter){
    global $dbConn;

	$sql = "update quarter set is_current_quarter = 0";
	mysqli_query($dbConn, $sql);

	$sql = "insert into quarter(quarter_short_name, is_current_quarter) values (?, 1) on duplicate key update is_current_quarter = 1";
	$stmt = mysqli_prepare($dbConn, $sql);
    mysqli_stmt_bind_param($stmt,'s', $quarter);
    mysqli_stmt_execute($stmt);

	$sql2 = "select quarter_id from quarter where is_current_quarter = 1";
	$result2 = mysqli_query($dbConn, $sql2);
	$row2 = mysqli_fetch_array($result2);
	return $row2['quarter_id'];
}

/* switch user on and off admin */
function update_admin($uclalogonid, $admin=1){
    global $dbConn;

	$sql = "update collector set collector_status = '". $admin ."' where collector_sid = '". $uclalogonid . "'";
	mysqli_query($dbConn, $sql);
}

/* find by ucla logon id */
function find_collector($uclalogonid){
    global $dbConn;

	$sql = "select collector_sid from collector where lower(collector_sid) = '". $uclalogonid . "'";
	$rs = mysqli_query($dbConn, $sql);
	$rw = mysqli_fetch_array($rs);
	return $rw['collector_sid'];
}

function check_test(){
    return "hello";
}

