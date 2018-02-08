<?php 
$user = check_auth();
if (!$user->is_admin()) exit('Not authorized.');

$dbConn = get_connection();

$cms->script[] = "$(function(){ $('.tablesorter').tablesorter(); });";
$cms->js[] = "js/jquery.tablesorter.js";
$cms->css[] = "css/blue/style.css";

$sql = "SELECT 
			c.collector_id,
			c.collector_last_name,
			c.collector_first_name,
			c.collector_sid,
			c.collector_status, 
			q.quarter_short_name 
		FROM collector AS c
        JOIN collector_quarter AS cq
        	ON c.collector_id = cq.collector_id
            AND c.collector_status = 0 
        JOIN quarter AS q 
        	ON  cq.quarter_id = q.quarter_id 
		ORDER BY c.collector_first_name, c.collector_last_name, c.collector_sid";

$result = mysqli_query($dbConn, $sql);
$data = array();
while ($row=mysqli_fetch_assoc($result)) $data[] = $row;

?>
<h2>ARCHIVED COLLECTORS</h2>
<form name="form1" id="archive_form" enctype="multipart/form-data" method="post" action="handler/collector/<?php echo isset($collector_id) ? $collector_id : "";?>//activate">
<table class="tablesorter">
<thead>
<tr> 
	<th class="form_title">UCLA ID</th>
	<th class="form_title">Name</th>
	<th class="form_title">Quarter</th>
	<th class="form_title">
		<input type="button" name="activate_button" value="Activate selected items" onClick="javascript:submitform('archive_form');">
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
	<input type=checkbox name="n<?php echo $collector_id;?>" value=1>
	</td>

</tr>
<?php } ?>
</tbody>
</table>
<input type="hidden" name="passcode" value="<?php echo PASSCODE;?>">
</form>
