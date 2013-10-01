<?php
error_reporting(E_ALL ^ E_NOTICE);
include_once('lib.php');
include_once('mini/cms.php');

$cms->js[] = 'http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js';
$cms->js[] = "js/jquery.ui.core.js";
$cms->js[] = "js/jquery.ui.widget.js";
$cms->js[] = "js/jquery.ui.datepicker.js";
//$cms->js[] = "js/jquery-latest.js";// include the table sorter dose not show the arrow
$cms->js[] = "js/kfl.js";
$cms->js[] = "main.js";
$cms->js[] = "css/jquery.ui.all.css";
$cms->css[] = "css/layout.css";
$cms->css[] = "css/menu.css";
$content = $cms->content();
$menu = $cms->content('menu');
header('Content-Type: text/html; charset=utf-8'); 
?>
<html>
<head>
	<?php echo $cms->head(); ?> 
</head>
<body>
<?php
if ($_SERVER['SERVER_NAME']=='localhost'){
	echo "<h1>This is localhost site</h1>";
}
?>
<div id="wrapper">

	<div id="topWrapper"></div>

	<table id="mainArea">
	<tr>
		<td class="leftcolumn">
			<?php echo $menu; ?>
		</td>
		<td class="rightcolumn">
			<div id="contentArea">
				<?php echo $content; ?>
			</div>
		</td>
	</tr>
	</table>

	<div id="footer">
	<a href="http://www.universityofcalifornia.edu/">University of California</a> Copyright &copy; 2000 UC Regents</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:var helpWin = window.open('http://dev.cdh.ucla.edu/etickets/websupport.php?url=' + encodeURIComponent(window.location.toString()),'helpWin','status=0, toolbar=0, height=500, width=600, menubar=0'); if (helpWin) helpWin.moveTo((screen.width - 600)/2,(screen.height - 500)/2); void(0);">Web Support</a>
	</div>

</div>


</body>
</html>
