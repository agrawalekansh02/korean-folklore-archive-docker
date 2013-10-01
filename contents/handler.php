<?php
global $user;
if (!$user->auth) {exit('Not authorized');}

function get_set_sql($f) {
	foreach ($f as $k => $v) {
		$sql_str[] = "$k='".mysql_real_escape_string($v)."'";
	}
	return implode(', ', $sql_str);
}

function get_file() {
	$user = get_user();
	$data = false;
	foreach ($_FILES as $name => $data) {
		if ($data['size'] > 10 && $data['size'] < 1048576*16) {
			$id = $user['collector_id'] . substr(time(), -6);
			move_uploaded_file($data['tmp_name'], "files/" . $id);	
			$data['files'] = $id;
			// only one file can be uploaded at a time. 
			break;
		}
	}
	return $data;
}

function process_consultant($f, $id=false) {
	if (!$id) unset($f['consultant_id']);
	if ($fd = get_file()) {
		$f['consultant_file_name']	= $fd['name'];
		$f['consultant_file_type'] = $fd['type'];
		$f['consultant_file_size'] = $fd['size'];
		$f['consultant_consent_form'] = $fd['files'];
	}
	return $f;
}
function process_context($f, $id=false) {
	if (!$id) unset($f['context_id']);
	return $f;
}

function process_collector($f, $id=false) {
	global $user;
	if (!$id) {
		if ($_POST['passcode'] != PASSCODE) exit('Invalid Passcode');
		unset($f['collector_id']);
		unset($f['passcode']);
		$f['collector_sid'] = $user->auth;
		$f['collector_status'] = 1;
	}
	return $f;
}
function process_data($f, $id=false) {
	if (!$id) unset($f['data_id']);
	if ($fd = get_file()) {
		$f['data_file_name'] = $fd['name'];
		$f['data_file_type'] = $fd['type'];
		$f['data_file_size'] = $fd['size'];
		$f['data_file'] = $fd['files'];
	}
	return $f;
}

function archive_collector($f) {
	global $user;
	if ($_POST['passcode'] != PASSCODE) exit('Invalid Passcode');
	unset($f['collector_id']);
	unset($f['passcode']);
	return $f;
}

/* retrieve records for group of ids */

function get_set_group_sql($f) {
	$sql_str = array();
	foreach ($f as $k => $v) {
		$sql_str[] = substr($k, 1); 
	}
	return implode(', ', $sql_str);
}

/* retrieve columns for a table */
function get_columns($table){
	$columns = array();
	$query = "show columns from $table";
	$result = mysql_query ($query);
	while ($row = mysql_fetch_array($result)){
		$columns[] = $row['Field'];
	}
	return $columns;
}

/*
process data make sure it has valid table field, so that allow form to add additional form 
field that not suppose to be process into the table
*/
function preprocess_sqlset($table, $f){
	/* f is the post data */
	$fields = array_keys($f);
	$columns = get_columns($table);
	$temp = array_intersect($columns, $fields);
	$result = array();
	foreach ($temp as $k=>$v){
		$result[$v] = $f[$v];
	}
	return $result;
}

?>
<?php
$table = $data[0];
$id = (isset($data[1])) ? $data[1] : false; // itemid
$cid = (isset($data[2])) ? $data[2] : false;  // collectorid
$action = (isset($data[3])) ? $data[3] : false; // action
$sql_set = array();
foreach ($_POST as $k => $v) {

/* when checkbox post or alike, make sure the value is NOT empty
if contain more than one item so that it would not result in an extra comma at the end */

	if (is_array($v)){
		$v2 = array();
		foreach ($v as $k1 => $v1){
			if ($v1){
				array_push($v2, $v1);
			}
		}
		if (sizeof($v2)>0)
		{
			$v=$v2;
			$v = implode(',', $v);

		}
	}
	$sql_set[$k] = $v;
}
switch($table) {
	case 'consultant':
		$sql_set = process_consultant($sql_set, $id);
		break;
	case 'context':
		$sql_set = process_context($sql_set, $id);
		break;
	case 'collector':
		if ($action=="archive"||$action=="activate" ){
			$sql_set = archive_collector($sql_set);
		}
		else{
			$sql_set = process_collector($sql_set, $id);
		}
		break;
	case 'data':
		$sql_set = process_data($sql_set, $id);
		break;
}
if (!$id && $action == "archive"){
	$sql = "update $table set ${table}_status = 0 where ${table}_id in (". get_set_group_sql($sql_set) . ")" . get_auth_sql();
	mysql_query($sql);
}
else if (!$id && $action == "activate"){
	$sql = "update $table set ${table}_status = 1 where ${table}_id in (". get_set_group_sql($sql_set) . ")" . get_auth_sql();
	mysql_query($sql);
}
else if (!$id) {
	if ($table != 'collector'){
		if ($user->is_admin() && $cid != $user->get('id') && $action == "add"){
			$sql_set['collector_id'] = $cid;
		}
		else{
			$sql_set['collector_id'] = $user->get('id');
		}
	}
	$f = preprocess_sqlset($table,$sql_set);
	$sql = "insert into $table set " . get_set_sql($f);
	mysql_query($sql);
	$id = mysql_insert_id();
	// insert the quarter
	if ($table == 'collector'){
		$sql = "insert into collector_quarter select ". $id . ", quarter_id from quarter where is_current_quarter = 1";
		mysql_query($sql);
	}
} 
else if (!empty($sql_set)) {
	$f = preprocess_sqlset($table,$sql_set);
	$sql = "update $table set ".get_set_sql($f)." where ${table}_id=$id ";
	if ($action == "role"){
		mysql_query($sql);
	}
	else{
		mysql_query($sql. get_auth_sql());
	}
}
else{
	$sql = "delete from $table where ${table}_id=$id " . get_auth_sql();
	mysql_query($sql);
}
if ($action == "archive"){
	header("Location: ".HOST."admin");
}
else if ($action == "activate"){
	header("Location: ".HOST."archive");
}
else if ($action == "role"){
	header("Location: ".HOST);
}
else{	//admin, stay on the same page
	header("Location: ".HOST."dashboard/".$data[2]);
}
exit();
?>
<pre>
<?php
echo "\n\nLocation: ".HOST."$table/$id\n\n";
echo "\n\nGet:\n";
print_r($_GET);
echo "\n\nData:\n";
print_r($data);
echo "\n\nPost:\n";
print_r($_POST);
echo "\n\nFiles:\n";
print_r($_FILES);
?>
</pre>
