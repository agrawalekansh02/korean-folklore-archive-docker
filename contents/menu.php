<?php global $user; ?>

<div id="menu">
<ul class="menu">
<li> <a href="">Home</a></li>
<li> <a href="map">Search</a></li>
<?php if ($user->is_admin()) { ?>
<li> <a href="admin">Admin</a></li>
<li> <a href="passcode">Change Passcode</a></li>
<li> <a href="reports">Reports</a></li>
<?php } 
	if ($user->is_user()) {
?>
<li> <a href="dashboard">Dashboard</a></li>
<li> <a href="consultant">Add Consultant</a></li>
<li> <a href="context">Add Context</a></li>
<li> <a href="data">Add Field Data</a></li>
<li> <a href="archive">Archive</a></li>
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
