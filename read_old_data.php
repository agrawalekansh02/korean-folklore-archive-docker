#!/usr/bin/php
<?php
set_time_limit(1800);
include_once('lib.php');

//clean up
$col = 68;
$cons = 328;
$conte = 297;
$d = 427;

$query = "delete from collector where collector_id >= $col";
mysqli_query($dbConn, $query);
$query = "delete from consultant where consultant_id >= $cons";
mysqli_query($dbConn, $query);
$query = "delete from context where context_id >= $conte";
mysqli_query($dbConn, $query);
$query = "delete from data where data_id >= $d";
mysqli_query($dbConn, $query);
$query = "delete from collector_quarter where collector_id >= 68";
mysqli_query($dbConn, $query);

//reset index
$query = "alter table collector auto_increment = $col";
mysqli_query($dbConn, $query);
$query = "alter table consultant auto_increment = $cons";
mysqli_query($dbConn, $query);
$query = "alter table context auto_increment = $conte";
mysqli_query($dbConn, $query);
$query = "alter table data auto_increment = $d";
mysqli_query($dbConn, $query);


// get quarter id from quarter table
$query_q = "select quarter_id, quarter_short_name, is_current_quarter from quarter";
$result_q = mysqli_query($dbConn, $query_q);
$quarter_array = array();
while($row_q = mysqli_fetch_array($result_q)){
	$quarter_array[$row_q['quarter_short_name']] = $row_q['quarter_id'];
}
//reset auto-increment value
$f1 = array('w10', 'w08', 'w07', 's06', 'w06', 'f05_berkeley', 's04', 'w03');

foreach ($f1 as $k=>$f){
	$collectors = new SimpleXMLElement("collector_".$f.".xml", NULL, TRUE);
	$consultants = new SimpleXMLElement("consultant_".$f.".xml", NULL, TRUE);
	$contexts = new SimpleXMLElement("context_".$f.".xml", NULL, TRUE);
	$contexts_consultants = new SimpleXMLElement("context_consultant_".$f.".xml", NULL, TRUE);
	$data = new SimpleXMLElement("data_".$f.".xml", NULL, TRUE);
	$quarter = $quarter_array[substr($f,0,3)];

	// wrap in transaction so that have the right last insert id
	mysqli_query($dbConn, "START TRANSACTION");
	// insert into data table
	foreach($data->records->row as $row){
		$sql_data = "insert ignore data values (null, ";
		// start from 2nd column
		$col = 0;
		foreach($row->column as $r){
			if ($col > 0){
				$sql_data .="'".addslashes($r)."',";
			}
			$col++;
		}
		$sql_data = substr($sql_data, 0, -1) . ")";
		mysqli_query($dbConn, $sql_data);
		echo $sql_data. "<br>";
	}
	mysqli_query($dbConn, "COMMIT");

	mysqli_query($dbConn, "START TRANSACTION");
	// insert into collector table
	$collector_ids_array = array();// array to store old collotors
	foreach($collectors->records->row as $row){
		$sql_collector = "insert ignore collector values (null, ";
		// start from 2nd column
		$col = 0;
		$sid = '';
		foreach($row->column as $r){
			if ($col > 0){
				$sql_collector .="'".addslashes($r)."',";
				if ($col == 4){
					$sid = $r; // used to search for existing sid
				}
			}
			$col++;
		}
		$sql_collector .= "0)";
		mysqli_query($dbConn, $sql_collector);

		if (mysqli_insert_id()>0){
			$new_collector_id = mysqli_insert_id();
		}
		else{ // map to existing record to column 4
			$query_tmp1 = "select collector_id from collector where collector_sid = '". $sid . "'";
			$result_tmp1 = mysqli_query($query_tmp1);
			$row_tmp1 = mysqli_fetch_array($result_tmp1);
			$new_collector_id = $row_tmp1['collector_id'];
		}
		$collector_ids_array['w'.(string)$row->column[0]]=$new_collector_id;

		// insert into collector_quarter table
		$query_cq = "insert ignore collector_quarter values (".$new_collector_id. ", ". $quarter.")";
		mysqli_query($query_cq);

		echo $sql_collector . "<br>";
		echo $query_cq . "<br>";
	}
	mysqli_query($dbConn, "COMMIT");

	mysqli_query("START TRANSACTION");
	// insert into consultant table
	$consultant_ids_array = array();// array to store old consultants
	foreach($consultants->records->row as $row){
		$sql_consultant = "insert ignore consultant values (null, ";
		$col = 0;
		foreach($row->column as $r){
			if ($col == 1){
				$sql_consultant .=array_key_exists('w'.$r, $collector_ids_array)?"'".$collector_ids_array['w'.$r]."',":null;
			}
			else if ($col > 1){
				$sql_consultant .="'".addslashes($r)."',";
			}
			$col++;
		}
		$sql_consultant = substr($sql_consultant, 0, -1) . ")";
		mysqli_query($dbConn, $sql_consultant);
		echo $sql_consultant . "<br>";
		$consultant_ids_array['w'.(string)$row->column[0]]=mysqli_insert_id($dbConn);
	}
	mysqli_query($dbConn, "COMMIT");


	// retrive consultant context table
	$contexts_consultants_ids_array = array();
	foreach($contexts_consultants->records->row as $row){
		$f = 0;
		$key = 0;
		$value = 0;
		foreach($row->column as $r){
			if ($f==0){
				$key = $r;
				$f = 1;
			}
			else{
				$value = $r;
				$f = 0;
			}
		}
		$contexts_consultants_ids_array['cc'.(string)$key]=$value;
	}

	mysqli_query($dbConn, "START TRANSACTION");
	// insert into context table
	$context_ids_array = array();// array to store old consultants
	foreach($contexts->records->row as $row){
		$sql_context = "insert ignore context values (null, ";
		$col = 0;
		$cc = 0;
		foreach($row->column as $r){
			if ($col == 1){
				$sql_context .=array_key_exists('w'.$r, $collector_ids_array)?"'".$collector_ids_array['w'.$r]."',":null;
			}
			else if ($col > 1){
				$sql_context .="'".addslashes($r)."',";
			}
			else{
				$cc = $r; // first column->context_id
			}
			$col++;
		}
		$sql_context .= array_key_exists('cc'.$cc, $contexts_consultants_ids_array)?"'".$consultant_ids_array['w'.$contexts_consultants_ids_array['cc'.$cc]]."')":"null)";
		mysqli_query($dbConn, $sql_context);
		echo $sql_context . "<br>";
		$context_ids_array['w'.(string)$row->column[0]]=mysqli_insert_id($dbConn);
	}
	mysqli_query($dbConn, "COMMIT");

	echo " done with importing table " . $f . "<br> next need to update data table";

	// update data table
	$query = "select data_id, collector_id, consultant_id, context_id from data where data_id >= $d";
	$result = mysqli_query($dbConn, $query);
	while($row = mysqli_fetch_array($result)){
		$collector_id = array_key_exists('w'.$row['collector_id'], $collector_ids_array)?$collector_ids_array['w'.$row['collector_id']]:null;
		$consultant_id = array_key_exists('w'.$row['consultant_id'], $consultant_ids_array)?$consultant_ids_array['w'.$row['consultant_id']]:null;
		$context_id = array_key_exists('w'.$row['context_id'], $context_ids_array)?$context_ids_array['w'.$row['context_id']]:null;
		$q1= "update data set collector_id = '".$collector_id . "', consultant_id = '" . $consultant_id . "', context_id = '" . $context_id . "' where data_id = " . $row['data_id'];
		mysqli_query($dbConn, $q1);
		echo $q1 . "<br>";
	}
	echo " done with update data table for $f <br> next retrieve the new index for table";

	$query = "SELECT AUTO_INCREMENT FROM information_schema.tables WHERE table_name =  'data'";
	$result = mysqli_query($dbConn, $query);
	$row = mysqli_fetch_array($result);
	$d = $row['AUTO_INCREMENT'];

	echo " Done with updating new index for table data and it is ready for next update for data table indexes";
}

echo "all done";
?>