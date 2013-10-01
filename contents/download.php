<?php
global $user;
if (!$user->is_user()) exit('Invalid user');
if (count($data) != 2) exit('Invalid url');

$table = $data[0];
$id = $data[1];

$result = mysql_query("select * from $table where ${table}_id=$id" . get_auth_sql());
if (!($row = mysql_fetch_assoc($result))) exit("Invalid $table");


// To accommodate old data used file name
if ($table == 'consultant'){
	$fname = 'file'.$row['consultant_consent_form'].$row['consultant_file_name'];
	$fpath = is_readable('files/'.$row['consultant_consent_form'])? $row['consultant_consent_form']:$fname;
	
}
else if ($table == 'data'){
	$fname = $row['data_file_name'];
	$fpath = is_readable('files/'.$row['data_file'])? $row['data_file']: $row['data_file_name'];
}

$fpath = 'files/' . $fpath;
if (!is_readable($fpath)) exit("File ".getcwd()."/$fpath is not readable");

header('Content-type: "' . $row[$table . '_file_type'] . '"');
//header('Content-Disposition: attachment; filename="'.$row[$table . '_file_name'].'"');
header('Content-Disposition: attachment; filename="'.$fname.'"');

$handle = fopen($fpath, "rb");
while (!feof($handle)) {
echo fread($handle, 8192);
}
fclose($handle);
exit(0);
?>