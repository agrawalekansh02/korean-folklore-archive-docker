<?php

// if local
if ($_SERVER['SERVER_NAME']=='localhost'){
	define('SECRET','O6oj.Ae32#$kWA');
	define('HOST','http://localhost/');
	define_passcode();
	mysql_connect('localhost', 'root', '');
	mysql_query("SET NAMES 'utf8'");
	mysql_select_db('kfl');
}
else if ($_SERVER['SERVER_NAME']=='kfltest.beta.cdh.ucla.edu'){
// if test
	define('SECRET','O6oj.Ae32#$kWA');
	define('HOST','http://kfltest.beta.cdh.ucla.edu/');
	define_passcode();
	mysql_connect('localhost','kfl','%=t%pm7HVxc8v5X');
	mysql_query("SET NAMES 'utf8'");
	mysql_select_db('kfltest');
    function get_connection () {
        return new MySQLi(
            'localhost',
            'kfl',
            '%=t%pm7HVxc8v5X',
            'kfltest'
        );
    }

}
else if ($_SERVER['SERVER_NAME']=='kfl.humnet.ucla.edu'){
// if production
	define('SECRET','O6oj.Ae32#$kWA');
	define('HOST','http://kfl.humnet.ucla.edu/');
	define_passcode();
	mysql_connect('localhost','kfl','%=t%pm7HVxc8v5X');
	mysql_query("SET NAMES 'utf8'");
	mysql_select_db('kfl');
    function get_connection () {
        return new MySQLi(
            'localhost',
            'kfl',
            '%=t%pm7HVxc8v5X',
            'kfltest'
        );
    }

}
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
    global $user;
    if (!$user->is_admin()) $collector_id = $user->get('id');
    $and = ($collector_id) ? "and collector_id=$collector_id" : '';

    $sql = "select * from $table where ${table}_id = $id $and";
    $result = mysql_query($sql);
    if (!$result) return 'Query was unsuccesssful';
    if ($row=mysql_fetch_assoc($result)) return $row;
    else return array();
}

function get_records($table, $collector_id=false) {
    global $user;
    if (!$user->is_admin()) $collector_id = $user->get('id');
    $and = ($collector_id) ? "and collector_id=$collector_id" : '';

    $sql = "select * from $table where 1 $and";
    $result = mysql_query($sql);
    if (!$result) return array();
    $data = array();
    while ($row=mysql_fetch_assoc($result)) $data[] = $row;
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
		header("Location: " . HOST."login"); 
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
	$sql = "update quarter set is_current_quarter = 0";
	mysql_query($sql);
	$sql = "insert into quarter(quarter_short_name, is_current_quarter) values ('".$quarter."', 1) on duplicate key update is_current_quarter = 1";
	mysql_query($sql);
	$sql2 = "select quarter_id from quarter where is_current_quarter = 1";
	$result2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($result2);
	return $row2['quarter_id'];
}

/* switch user on and off admin */
function update_admin($uclalogonid, $admin=1){
	$sql = "update collector set collector_status = '". $admin ."' where collector_sid = '". $uclalogonid . "'";
	mysql_query($sql);
}

/* find by ucla logon id */
function find_collector($uclalogonid){
	$sql = "select collector_sid from collector where lower(collector_sid) = '". $uclalogonid . "'";
	$rs = mysql_query($sql);
	$rw = mysql_fetch_array($rs);
	return $rw['collector_sid'];
}
?>
