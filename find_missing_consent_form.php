#!/usr/bin/php
<?php
set_time_limit(1800);
include_once('lib.php');

//
$query = "select t1.data_file from data t1, collector_quarter t2 where t1.collector_id = t2.collector_id and t2.quarter_id = 10 and t1.data_file > 0";
$result = mysqli_query($dbConn, $query);
$data_array = array();
while ($row = mysqli_fetch_array($result)){
	$data_array[$row['data_file']] = 1;
}
$query = "SELECT t3.collector_first_name, t3.collector_last_name, t1.consultant_id, t1.consultant_first_name, t1.consultant_last_name, t1.consultant_consent_form
FROM consultant t1, collector_quarter t2, collector t3
WHERE t1.collector_id = t3.collector_id
AND t1.collector_id = t2.collector_id
AND t2.quarter_id =10";
$result = mysqli_query($dbConn, $query);
$db_file_array = array();
$missing_file_array = array();
while($row = mysqli_fetch_array($dbConn, $result)){
	if ($row['consultant_consent_form'] > 0){
		$db_file_array[$row['consultant_consent_form']]= array('consultant_id'=>$row['consultant_id'], 'collector_name'=>$row['collector_last_name'].",".$row['collector_first_name'], 'consultant_name'=>$row['consultant_last_name'].", ". $row['consultant_first_name']);
	}
	else{
		array_push($missing_file_array, array('consultant_id'=>$row['consultant_id'], 'collector_name'=>$row['collector_last_name'].",".$row['collector_first_name'], 'consultant_name'=>$row['consultant_last_name'].", ". $row['consultant_first_name']));
	}
}
$need_to_match_file_list = array();
$file = "kfl-files.txt";
$f = fopen($file, "r");
$file_array = array();
while ( $line = fgets($f, 1000) ) {
	$tmp_array = explode(',', $line);
	foreach($tmp_array as $k=>$v){
		$file_array[$v]=1;
	}
}

$i = 0;
foreach($db_file_array as $k=>$v){
	if ($file_array[$k]==1){
		$file_array[$k] = 0; // find matched files
		$i++;
	}
}
foreach ($data_array as $k=>$v){
	if ($file_array[$k] == 1){
		$file_array[$k] = 0;
		$i++;
	}
}
// loop through the files need to match
foreach($file_array as $k=>$v){
	if ($v==1){
		array_push($need_to_match_file_list, $k);
	}
}
echo " matching concent forms " . $i;
echo " <br />missing consent form <br />";
echo "<pre>";
print_r($missing_file_array);
echo "</pre>";
echo "<br />need to match files <br />";
echo "<pre>";
print_r($need_to_match_file_list);
echo "</pre>";
?>