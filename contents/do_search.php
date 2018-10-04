<?php
global $user;
if (!$user->auth) {exit('Not authorized');}

$dbConn = get_connection();

//if we got something through $_POST
if (isset($_POST['search'])) {
	// here you would normally include some database connection
	// never trust what user wrote! We must ALWAYS sanitize user input
	$word = mysqli_real_escape_string($dbConn, $_POST['search']);
	$word = htmlentities($word);
	// build your search query to the database
	$sql = "SELECT distinct t1.collector_id, t1.collector_last_name, t1.collector_first_name FROM collector t1, data t2 WHERE t1.collector_id = t2.collector_id and lower(t1.collector_last_name) LIKE '%" . strtolower($word) . "%' or lower(t1.collector_first_name) like '%". strtolower($word). "%'";
	 // get results
	$res=mysqli_query($dbConn, $sql);
	$end_result = '';

	while($row = mysqli_fetch_assoc($res)){
		$end_result     .= '<li>' . '<a href='.HOST.'dashboard/'.$row['collector_id'].'>'.
	 $row['collector_last_name'].", " . $row['collector_first_name'] . '</a></li>';   
	}
	if ($end_result == ''){
		echo "<div id='d123'>No result</div>";
	}
	else{
		echo "<div id='d123'><ol>$end_result</ol></div>";
	}
}
mysqli_close($dbConn);