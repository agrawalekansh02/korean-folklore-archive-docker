<?php global $user; ?>

<div id="menu">
<ul class="menu">
<li> <a href="">Home</a></li>
<?php if ($user->is_admin()) { ?>
<li> <a href="admin.php">Admin</a></li>
<li> <a href="passcode.php">Change Passcode</a></li>
<?php } 
	if ($user->is_user()) {
?>
<li> <a href="dashboard.php">Dashboard</a></li>
<li> <a href="consultant.php">Add Consultant</a></li>
<li> <a href="context.php">Add Context</a></li>
<li> <a href="data.php">Add Field Data</a></li>
<li> <a href="archive.php">Archive</a></li>
<!-- <li> <a href="search.php">Search</a></li>
 -->
<?php } ?>
<?php if (!$user->auth) { ?>
<li> <a href="login.php">Login</a></li>
<?php } else { ?>
<li> <a href="logout.php">Logout</a></li>
<?php } ?>
</ul>
</ul>
</div>
