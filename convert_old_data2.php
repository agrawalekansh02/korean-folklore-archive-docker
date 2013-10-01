#!/usr/bin/php
<?php
set_time_limit(1800);
include_once('lib.php');

$query = "alter table context modify context_event_name varchar (50)";
mysql_query($query);

$query = "alter table context modify context_event_type varchar (200)";
mysql_query($query);
?>