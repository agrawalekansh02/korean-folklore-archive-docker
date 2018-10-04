<?php 
$user = check_auth();
if (!$user->is_admin()) exit('Not authorized.');
$dbConn = get_connection();

// if submit write to file
if (isset($_POST['passcode']) && !empty($_POST['passcode'])){
	update_passcode($_POST['passcode']);
}
// if add a new quarter
$current_quarter = 0;
if (isset($_POST['quarter']) && !empty($_POST['quarter'])){
	$current_quarter = add_quarter($_POST['quarter']);
}

// add admin

$admin = '';
if (isset($_POST['admin']) && !empty($_POST['admin'])){
	$admin = find_collector(strtolower(trim($_POST['admin'])));
	if (!empty($admin)){
		update_admin($admin, 2);
	}
}

if (isset($_POST['remove_admin'])){
	update_admin($_POST['remove_admin']);
}

$q1 = mysqli_query($dbConn, "select quarter_short_name from quarter where is_current_quarter = 1");
$r1 = mysqli_fetch_assoc($q1);
$q = $r1['quarter_short_name'];

// get status
if((isset($_POST['admin'])||isset($_POST['quarter'])) && $current_quarter > 0){
	$q2 = "update collector_quarter q, collector c set q.quarter_id = $current_quarter where c.collector_id = q.collector_id and c.collector_status = 2";
	mysqli_query($dbConn, $q2);
}

$q2 = "select collector_last_name, collector_first_name, collector_sid from collector where collector_status = 2";
$r2 = mysqli_query($dbConn, $q2);

?>
<div id="container">
<div style="margin:20px auto; text-align: left;">
<form method="post" action="" id='passcode_form'>
	<p>
		<div>Please enter the new passcode below: </div>
		<div><input type="text" name="passcode" id="passcode_box" class='search_box'/></div>
		<?php
			if (isset($_POST['passcode']) && !empty($_POST['passcode'])){ ?>
				<div><font color=red>New passcode added</font>:&nbsp;<?php echo $_POST['passcode'];?></div>
			<?php
			}else{ ?>
				<div>Current passcode: <?php echo PASSCODE; ?></div>
			<?php
			}
		?>

	</p>
	<p>
		<div>Add a new quarter (format as w02, s02, f02 etc.): </div>
		<div><input type="text" name="quarter" id="quarter_box" class='search_box'/></div>
		<?php
			if (isset($_POST['quarter']) && !empty($_POST['quarter'])){?>
				<div><font color=red>New quarter added</font>:&nbsp;<?php echo $_POST['quarter'];?></div>
		<?php
			}else{ ?>
				<div>Current quarter: <?php echo $q;?></div>
			<?php
			}
		?>
	</p>
	<p>
		<div>
			Add a new admin (enter UCLA Logon ID): 
			<br>
			<span style="font-style:italic;font-size:12px;">
				Plese note that only the person who is/was a collector in the system can be added as admin.
			</span>
		</div>
		<div>
			<input type="text" name="admin" id="admin_box" class='search_box'/>
		</div>
		<?php
			if (isset($_POST['admin']) && !empty($_POST['admin']) && $admin){ ?>
				<div><font color=red>New admin added</font>:&nbsp;<?php echo $admin;?></div>
			<?php
			}else if (isset($_POST['admin']) && !empty($_POST['admin']) && !$admin){ ?>
				<div>
					<span style="color:red;font-style:italic">
						Sorry, we can not locate <?php echo $_POST['admin'];?> in the system. Please try again.
					</span>
				</div>
			<?php
			}
			?>
	</p>
	<p>
		<input type="submit" id="submit_button" value="Submit changes" class="search_button" />
	</p>
	<p>
		<br />
	</p>
	<p>
		<div>
			<strong>Current list of admin:</strong><br><br>
			<?php 
				while($rw2 = mysqli_fetch_array($r2)){
					echo $rw2['collector_last_name']. ", " . $rw2['collector_first_name'] . " (".$rw2['collector_sid'].")". 
						"&nbsp;&nbsp;<a href=\"javascript:remove_admin('".$rw2['collector_sid']."')\">Remove admin</a><br>";
				}
			?>
			<input type="hidden" name="remove_admin" id="remove_admin" value=''>
		</div>
	</p>
</form>
</div>     
</div>
<?php
mysqli_close($dbConn);
?>
<script type="text/javascript">
	function remove_admin(userId){
		$('#remove_admin').val(userId);
		$('#passcode_form').submit();
	}
</script>
