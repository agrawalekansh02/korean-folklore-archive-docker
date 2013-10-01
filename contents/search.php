<?php 
$user = check_auth();
if (!$user->is_admin()) exit('Not authorized.');



?>
<div id="container">
<div style="margin:20px auto; text-align: center;">
<form method="post" action="do_search.php">
<input type="text" name="search" id="search_box" class='search_box'/>
<input type="submit" value="Search" class="search_button" /><br />
</form>
</div>     
<div>
<div id="searchresults">Search results :<br></div>
<div id="results" class="update">
</div>
</ul>
</div>
</div>
