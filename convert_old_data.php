#!/usr/bin/php
<?php
set_time_limit(1800);
include_once('lib.php');

// change context table column context_time from time to varchar
$query = "alter table context modify context_time varchar(50)";
mysqli_query($dbConn, $query);

// convert the times
$query = "update context set context_time = 'morning' 
where SUBSTRING( context_time, 1, 2 ) >= 5
AND SUBSTRING( context_time, 1, 2 ) <12";
mysqli_query($dbConn, $query);

$query = "update context set context_time = 'afternoon' 
where SUBSTRING( context_time, 1, 2 ) >=12
AND SUBSTRING( context_time, 1, 2 ) <18";
mysqli_query($dbConn, $query);

$query = "update context set context_time = 'evening' 
where SUBSTRING( context_time, 1, 2 ) >=18
AND SUBSTRING( context_time, 1, 2 ) <21";
mysqli_query($dbConn, $query);

$query = "update context set context_time = 'night' 
where (SUBSTRING( context_time, 1, 2 ) >=21
AND SUBSTRING( context_time, 1, 2 ) < 24) or 
(substring(context_time, 1, 2) > 0 and SUBSTRING( context_time, 1, 2 )<5) or 
substring(context_time, 1, 2) = '00'";
mysqli_query($dbConn, $query);

// map the old context event type to new ones
/*
Family Celebration, Holiday, Anecdote -> Other Celebration
Tatoo, Piercing -> Body Art or Adornment
Folk Art, Design -> Folk Art or Craft
Fairy Tale, Myth, Legend, Riddle, Joke, Proverb, Other Story -> Storytelling
Slang, Other Folk Speech, Rhyme, Gesture ->Folk Speech/Gesture
Clothing ->Costume/Clothing
*/
/* BELOW CAN ONLY RUN ONCE */
$d = 297;

$query = "update context set context_event_type = replace(context_event_type, 'Family Celebration', 'Other Celebration') where context_id >= $d";
mysqli_query($dbConn, $query);

$query = "update context set context_event_type = replace(context_event_type, 'Holiday', 'Other Celebration') where context_id >= $d";
mysqli_query($dbConn, $query);

$query = "update context set context_event_type = replace(context_event_type, 'Anecdote', 'Other Celebration') where context_id >= $d";
mysqli_query($dbConn, $query);

$query = "update context set context_event_type = replace(context_event_type, 'Tatoo', 'Body Art or Adornment') where context_id >= $d";
mysqli_query($dbConn, $query);

$query = "update context set context_event_type = replace(context_event_type, 'Piercing', 'Body Art or Adornment') where context_id >= $d";
mysqli_query($dbConn, $query);

$query = "update context set context_event_type = replace(context_event_type, 'Folk Art', 'Folk Art or Craft') where context_id >= $d";
mysqli_query($dbConn, $query);


$query = "update context set context_event_type = replace(context_event_type, 'Design', 'Folk Art or Craft') where context_id >= $d";
mysqli_query($dbConn, $query);


$query = "update context set context_event_type = replace(context_event_type, 'Fairy Tale', 'Storytelling') where context_id >= $d";
mysqli_query($dbConn, $query);

$query = "update context set context_event_type = replace(context_event_type, 'Myth', 'Storytelling') where context_id >= $d";
mysqli_query($dbConn, $query);

$query = "update context set context_event_type = replace(context_event_type, 'Legend', 'Storytelling') where context_id >= $d";
mysqli_query($dbConn, $query);

$query = "update context set context_event_type = replace(context_event_type, 'Riddle', 'Storytelling') where context_id >= $d";
mysqli_query($dbConn, $query);


$query = "update context set context_event_type = replace(context_event_type, 'Joke', 'Storytelling') where context_id >= $d";
mysqli_query($dbConn, $query);


$query = "update context set context_event_type = replace(context_event_type, 'Proverb', 'Storytelling') where context_id >= $d";
mysqli_query($dbConn, $query);


$query = "update context set context_event_type = replace(context_event_type, 'Other Story', 'Storytelling') where context_id >= $d";
mysqli_query($dbConn, $query);


$query = "update context set context_event_type = replace(context_event_type, 'Slang', 'Folk Speech/Gesture') where context_id >= $d";
mysqli_query($dbConn, $query);


$query = "update context set context_event_type = replace(context_event_type, 'Other Folk Speech', 'Folk Speech/Gesture') where context_id >= $d";
mysqli_query($dbConn, $query);


$query = "update context set context_event_type = replace(context_event_type, 'Rhyme', 'Folk Speech/Gesture') where context_id >= $d";
mysqli_query($dbConn, $query);


$query = "update context set context_event_type = replace(context_event_type, 'Gesture', 'Folk Speech/Gesture') where context_id >= $d";
mysqli_query($dbConn, $query);


$query = "update context set context_event_type = replace(context_event_type, 'Clothing', 'Costume/Clothing') where context_id >= $d";
mysqli_query($dbConn, $query);



// make the data unique

$query = "select context_id, context_event_type from context";
$result = mysqli_query($dbConn, $query);
while ($row = mysqli_fetch_array($result)){
	echo "<br>".$row['context_event_type'];
	$array = explode(',', $row['context_event_type']);
	$new = implode(',', array_unique($array));
	$new_query = "update context set context_event_type = '". $new. "' where context_id = ". $row['context_id'];
	echo " " . $new_query;
	mysqli_query($dbConn, $new_query);
}

?>