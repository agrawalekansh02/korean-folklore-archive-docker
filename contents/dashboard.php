
<?php

function getLink() {
	$args = func_get_args();
	$str = array();
	foreach ($args as $a) $str[] = trim(ucwords($a));
	$str = implode(' ', $str);
	if (strlen(trim($str)) == 0) return 'Empty';
	else return $str;
}


$user = check_auth();

$collector_id = $user->get('id');
if ($user->is_admin() && isset($data[0]) && $data[0] > 0) {
	$collector_id = $data[0];
}


$collector_result = mysql_query("select * from collector where collector_id=$collector_id");
if ($row=mysql_fetch_assoc($collector_result)) foreach ($row as $k => $v) $$k = $v;
else {echo 'Invalid Collector'; return false;}

$data_result_array = array();
$context_data_array = array();
$consultant_data_array = array();

$context_result = mysql_query("select * from context where collector_id=$collector_id");
$consultant_result = mysql_query("select * from consultant where collector_id=$collector_id");
$data_result = mysql_query("select * from data where collector_id=$collector_id");
while($row=mysql_fetch_assoc($data_result)){
	array_push($data_result_array, $row);
	if (isset($row['context_id'])){
		array_push($context_data_array, $row['context_id']);
	}
	if (isset($row['consultant_id'])){
		array_push($consultant_data_array, $row['consultant_id']);
	}
}

$gender_display = array("F" => "Female", "M" => "Male", "O" => "Other");
?>
<h2>DASHBOARD</h2>
<table class="formStyle" cellspacing=3px>
<tr>
	<td width="50%">
		<h3>Collector Profile</h3>
                   <strong><a href="collector/<?php echo $collector_id; ?>" target="_self"><?php  echo getLink($collector_first_name, $collector_last_name); ?></a></strong><br>
                  <a href="mailto:<?php  echo $collector_email?>"> <?php  echo $collector_email?></a><br>
					<?php  echo $collector_street, ", ", $collector_city, ", ", $collector_state, " ", $collector_zipcode, " ", $collector_country; ?><br>
					DOB: <?php  echo $collector_dob;?><br>
					Age: <?php  echo $collector_age; ?><br>
					Gender: <?php  echo $gender_display[$collector_gender]; ?><br>
					Marital Status: <?php  echo $collector_marital_status; ?><br>
					Occupation: <?php  echo $collector_occupation;?><br>
					Educational Level: <?php  echo $collector_edu_level;?><br>
					Heritage: <?php  echo $collector_heritage ?><br>
					Languages Spoken: <?php  echo $collector_language; ?><br>
	</td>
	<td width="50%">
		<h3>Contexts&nbsp&nbsp;
			<?php
			if ($user->is_admin() &&  $user->get('id')!=$collector_id){
			?>
			<a href="context//<?php echo $collector_id;?>/add" class="add-link">
			Add context
			</a>
			<?php
			}
			?></h3>
			<table>
			<?php 
			// Populate the contexts that are associated with this collector
			while($row=mysql_fetch_assoc($context_result)){
				echo "<tr>";
				echo "<td>";
				// get context_id from $context_result
				$context_id=$row['context_id'];
				$flag=0; // if there is associate data with this context, flag turn to 1
				if (in_array($context_id, $context_data_array)){
					$flag = 1;
				}
				// fetch info about that context from db
				?> <strong>
				<a href="context/<?php  echo $context_id; ?>/<?php echo $collector_id;?>" target="_self"> <?php  echo getLink($row['context_event_name']); ?> </a>
				<?php
				if ($flag){
					 echo "<font color=red>*</font>";
				}
				?>
				</strong>
				<br>
				  <t>Date: <?php  echo $row['context_date']; ?> <br>
				  <t>Time: <?php  echo $row['context_time']; ?> <br>
				  <t>Description: <?php  $subcontext = substr($row['context_description'], 0, 50); echo trim($subcontext), "..."; ?> <br>
				</td>
				<td>
				<a href="handler/context/<?php echo $context_id; if ($user->is_admin()) echo '/'.$collector_id; ?>/delete" class="delete-link" title="<?php echo $flag;?>" type="context">Delete</a> 
				</td>
			<?php 
				echo "</tr>";
			}	?>
			</table>

	</td>
</tr>
<tr>
	<td style="line-height: 1.4;">
	  <h3>Consultants&nbsp&nbsp;
			<?php
			if ($user->is_admin() &&  $user->get('id')!=$collector_id){
			?>
			<a href="consultant//<?php echo $collector_id;?>/add" class="add-link">
			Add consultant
			</a>
			<?php
			}
			?>
		</h3>
			<table>
			<?php 
			// Populate consultants that are associated with this collector	
			while($row = mysql_fetch_assoc($consultant_result)){
				echo "<tr>";
				echo "<td>";
				// take consultant id from consultant_result list
				$consultant_id=$row['consultant_id'];
				$flag=0; // if there is associate data with this context, flag turn to 1
				if (in_array($consultant_id, $consultant_data_array)){
					$flag = 1;
				}
				// create link to consultant with corresponding consultant id
			?>	<strong>
				<a href="consultant/<?php echo $consultant_id;?>/<?php echo $collector_id;?>" target="_self"><?php echo getLink($row['consultant_first_name'], $row['consultant_last_name']);?> </a>
				<?php
				if ($flag){
					 echo "<font color=red>*</font>";
				}
				?>
				</strong>
				<br/>
				</td>
				<td>
				<a href="handler/consultant/<?php echo $consultant_id; if ($user->is_admin()) echo '/'.$collector_id;?>/delete" class="delete-link" title="<?php echo $flag;?>" type="consultant">Delete</a>
				</td>
			<?php 	
			  echo "</tr>";
			}
			?>
			</table>
	</td>
	<td>
		<h3>Data&nbsp&nbsp;
			<?php
			if ($user->is_admin() &&  $user->get('id')!=$collector_id){
			?>
			<a href="data//<?php echo $collector_id;?>/add" class="add-link">
			Add field data
			</a>
			<?php
			}
			?></h3>
			<table>
			<?php 
			// Populate data that are associated with this collector
			foreach ($data_result_array as $k => $row)
			{
				echo "<tr>";
				echo "<td>";
				// take data_id from data_result array
				$data_id=$row['data_id'];
				// create link to data item with corresponding data_id
				?><strong><a href="data/<?php  echo $data_id; ?>/<?php echo $collector_id;?>" target="_self"><?php  echo getLink($row['data_project_title']); ?> </a></strong>
				<t>Description: <?php  $subdata = substr($row['data_description'], 0, 100); echo trim($subdata), "..."; ?> <br>		
				<t>Filename: <?php  echo $row['data_file_name']; ?> <br>
				<td>
				<td>
				<a href="handler/data/<?php echo $data_id; if ($user->is_admin()) echo '/'.$collector_id;?>/delete" class="delete-link" 
				title="<?php echo $row['data_project_title'];?>" type="data">Delete</a>
				</td>
			<?php 
				echo "</tr>";
			}	
			?>
			</table>
	</td>
</tr>
</table>

