<?php 
$user = check_auth();
if (!$user->is_admin()) exit('Not authorized.');

$cms->script[] = "$(function(){ $('.tablesorter').tablesorter(); });";
$cms->js[] = "js/jquery.tablesorter.js";
$cms->css[] = "css/blue/style.css";

$result = mysql_query("select t1.*, t3.quarter_short_name from collector t1, collector_quarter t2, quarter t3 where t1.collector_id = t2.collector_id and t2.quarter_id = t3.quarter_id and t1.collector_status > 0 order by t1.collector_first_name, t1.collector_last_name, t1.collector_sid");
$data = array();
while ($row=mysql_fetch_assoc($result)){
	$data[] = $row;
}
?>
<h2>COLLECTORS</h2>
<form name="form1" id="admin_form" enctype="multipart/form-data" method="post" action="handler/collector/<?php echo $collector_id;?>//archive">
<table id="content_table" class="tablesorter">
<!--class="formStyle"-->
<thead>
<tr> 
	<th class="form_title">UCLA ID</th>
	<th class="form_title">Name</th>
	<th class="form_title">Quarter</th>
	<th class="form_title">
		<!-- <a href="javascript:submitform('admin_form');" class="archive-link">
		Archive selected items
		</a> -->
		<input type="button" name="archive_button" value="Archive" onClick="javascript:submitform('admin_form');">
	</th>
</tr>
</thead>
<tbody>
<?php 
foreach ($data as $row)
{
$collector_id = $row['collector_id'];
$collector_first_name = $row['collector_first_name'];
$collector_last_name = $row['collector_last_name'];
?>
<tr valign="middle" align="left"> 
	<td width="20%"><?php echo $row['collector_sid']; ?></td>
	<td width="50%" class="unnamed1"><a href="dashboard/<?php  echo $collector_id; ?>" target="_parent"><?php  echo "$collector_first_name $collector_last_name"; ?></a></td>
	<td width="5%"><?php echo strtoupper($row['quarter_short_name']); ?></td>
	<td>
	<!-- prevent archive admin -->
	<?php
		if ($row['collector_status']<2){
	?>
	<input type=checkbox name="n<?php echo $collector_id;?>" value=0>
	<?php
	}
	else{
		print "Admin";
	}
	?>
	</td>

</tr>
<?php } ?>
</tbody>
</table>
<input type="hidden" name="passcode" value="<?php echo PASSCODE;?>">
</form>
