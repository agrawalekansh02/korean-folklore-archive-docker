<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once (__DIR__.'/../box-jwt-php/bootstrap/autoload.php');
require_once (__DIR__.'/../box-jwt-php/helpers/helpers.php');

use Box\Auth\BoxJWTAuth;
use Box\BoxClient;
use Box\Config\BoxConstants;
use Box\Models\Request\BoxFileRequest;

global $user;
if (!$user->auth) {exit('Not authorized');}

$dbConn = get_connection();

function error_message() {
	echo "An error occured while saving this form. Your progress has not been saved.";
	exit;
}

function get_set_sql($f) {

	$dbConn_2 = get_connection();

	foreach ($f as $k => $v) {

		$value = mysqli_real_escape_string($dbConn_2, trim($v));

		if($value == NULL){
			$sql_str[] = "$k = NULL";
		}else{
			$sql_str[] = "$k = '".$value."'";
		}
		
	}
	mysqli_close($dbConn_2);
	return implode(', ', $sql_str);
}

function get_file() {

	//add box upload info
	$boxJwt     = new BoxJWTAuth();
	$boxConfig  = $boxJwt->getBoxConfig();
	$adminToken = $boxJwt->adminToken();
	$boxClient  = new BoxClient($boxConfig, $adminToken->access_token);

	$user = get_user();
	$data = false;
	foreach ($_FILES as $name => $data) {
		if ($data['size'] > 10 && $data['size'] < 1048576*16) {

			//upload file to temp folder on server
			$tempDir = sys_get_temp_dir();

			//new file name
			$origFileName = $data['name'];
			$info = pathinfo($origFileName);
		    // get the filename without the extension
		    $fileBasename =  basename($origFileName,'.'.$info['extension']);
		    // get the extension without the image name
		    $extArray = explode('.', $origFileName);
    		$fileExt = end($extArray);
    		// id and time
			$idtime = $user['collector_id'] . substr(time(), -6);

			$finalBoxName = $fileBasename . '_' . $idtime . '.' . $fileExt;

			$filePath = $tempDir."/".$finalBoxName;
			$data['files'] = $finalBoxName;

			if(!move_uploaded_file($data['tmp_name'], $filePath)){
				error_message();
			}

			//if moved to temp folder... upload to box
			try{
				$parentId = BoxConstants::BOX_ROOT_FOLDER_ID;

				$fileRequest = new BoxFileRequest(['name' => $finalBoxName, 'parent' => ['id' => $parentId]]);
				$res         = $boxClient->filesManager->uploadFile($fileRequest, $filePath);
				$uploadedFileObject = json_decode($res->getBody());

				$data['boxid']   = $uploadedFileObject->entries[0]->id;
				
				//delete file from tempDir
				unlink($filePath);
			}
			catch(Exception $e) {
				//delete file from tempDir
				unlink($filePath);
				error_message();
			}
			
			// only one file can be uploaded at a time. 
			break;
		}
	}

	return $data;
}

function get_old_file($table, $id){

	$dbConn_2 = get_connection();

	//only applies to consultant and data tables
	if($table != 'consultant' && $table != 'data'){
		return NULL;
	}

	$sql = "SELECT ${table}_box_file_id AS old_box_file_id FROM $table where ${table}_id=$id ". get_auth_sql();
	$result = mysqli_query($dbConn_2, $sql);

	$old_box_file_id = NULL;
	if ($row = mysqli_fetch_assoc($result)){
	    $old_box_file_id = $row['old_box_file_id'];
	}
	mysqli_close($dbConn_2);

	return $old_box_file_id;
}

function delete_box_file($old_box_file_id){
	//add box upload info
	$boxJwt     = new BoxJWTAuth();
	$boxConfig  = $boxJwt->getBoxConfig();
	$adminToken = $boxJwt->adminToken();
	$boxClient  = new BoxClient($boxConfig, $adminToken->access_token);

	// delete file
	try{
		$res = $boxClient->filesManager->deleteFile($old_box_file_id);
	}
	catch(Exception $e){
		// do nothing
	}
}

function process_consultant($f, $id=false) {
	if (!$id) unset($f['consultant_id']);
	if (($fd = get_file()) && !(empty($fd['name']))) {
		$f['consultant_file_name'] = $fd['name'];
		$f['consultant_file_type'] = $fd['type'];
		$f['consultant_file_size'] = $fd['size'];
		$f['consultant_consent_form'] = $fd['files'];
		$f['consultant_box_file_id'] = $fd['boxid'];
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
	if (($fd = get_file()) && !(empty($fd['name']))) {
		$f['data_file_name'] = $fd['name'];
		$f['data_file_type'] = $fd['type'];
		$f['data_file_size'] = $fd['size'];
		$f['data_file'] = $fd['files'];
		$f['data_box_file_id'] = $fd['boxid'];
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
	$dbConn_2 = get_connection();
	$columns = array();
	$query = "show columns from $table";
	$result = mysqli_query($dbConn_2, $query);
	while ($row = mysqli_fetch_array($result)){
		$columns[] = $row['Field'];
	}
	mysqli_close($dbConn_2);
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

$valid_tablenames = array('consultant', 'context', 'collector', 'data');
if(!in_array($table, $valid_tablenames)){
	header("Location: ".HOST."dashboard");
	exit();
}

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
		else{
			$v = '';
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
	if(!mysqli_query($dbConn, $sql)) {
		error_message();
	}
}
else if (!$id && $action == "activate"){
	$sql = "update $table set ${table}_status = 1 where ${table}_id in (". get_set_group_sql($sql_set) . ")" . get_auth_sql();
	if(!mysqli_query($dbConn, $sql)) {
		error_message();
	}
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

	if ($table == 'consultant' || $table == 'context' || $table == 'data'){
		$sql_set[$table.'_quarter_created'] = get_current_quarter();
	}

	$f = preprocess_sqlset($table,$sql_set);
	$sql = "insert into $table set " . get_set_sql($f);

	if(!mysqli_query($dbConn, $sql)) {
		error_message();
	}
	$id = mysqli_insert_id($dbConn);
	// insert the quarter
	if ($table == 'collector'){
		$sql = "insert into collector_quarter select ". $id . ", quarter_id from quarter where is_current_quarter = 1";
		if(!mysqli_query($dbConn, $sql)) {
			error_message();
		}
	}
} 
else if (!empty($sql_set)) {
	
	$new_box_file_id = isset($sql_set[$table.'_box_file_id']) ? $sql_set[$table.'_box_file_id'] : NULL;
	$old_box_file_id = get_old_file($table, $id);

	$f = preprocess_sqlset($table,$sql_set);
	$sql = "update $table set ".get_set_sql($f)." where ${table}_id=$id ";

	if ($action != "role"){
		$sql = $sql . get_auth_sql();
	}
	
	if(!mysqli_query($dbConn, $sql)) {
		error_message();
	}

	if($old_box_file_id != NULL && $new_box_file_id != NULL){
		delete_box_file($old_box_file_id);
	}
}
else{
	$old_box_file_id = get_old_file($table, $id);

	$sql = "delete from $table where ${table}_id=$id " . get_auth_sql();
	if(!mysqli_query($dbConn, $sql)) {
		error_message();
	}

	if($old_box_file_id != NULL){
		delete_box_file($old_box_file_id);
	}
}

mysqli_close($dbConn);

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
