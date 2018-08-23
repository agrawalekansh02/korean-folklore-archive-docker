<?php 
$cms->title = 'UCLA Korean Folklore Archive';
$user = check_auth(); 
?>
<div style="margin-left:520px" class="small-label">
	<form id="role_form" action="handler/collector/<?php echo $user->get('id');?>/role" method="post">
	<?php
	$user = check_auth();
	if ($user->get('sid')== 'tango'|| $user->get('sid')=='jwan123'){
		$status_array = array('0'=>"regular user", '1'=>"admin");
		echo "You login as " . $status_array[$user->get('status')-1];
		$new_status = !($user->get('status')-1)+1;
		echo ". <a href='javascript:$(\"#collector_status\").val(".$new_status.");submitform(\"role_form\");'>Click to switch to ".  $status_array[!($user->get('status')-1)]."</a>";
	}
	?>
	<input type="hidden" id="collector_status" name="collector_status" value="">
	</form>
</div>
<div style="margin-top:20px">
<p class="display-text">
The Korean / Korean American Online Folklore Archive is a growing on-line archive of contemporary popular and traditional culture of Korea. The archive is based entirely on student collections in two upper division Asian Languages and Cultures courses (Korean M183 and Korean 187) at UCLA, serving between seventy and one hundred undergraduate students per academic year. In each class, a significant  portion of the coursework is comprised of individual, original fieldwork among the populations of Korean heritage in Southern California. Students input their fieldwork through a web interface and the results of their fieldwork is stored in a database residing on a server maintained by the Center for Digital Humanities. Currently, there are over 4000 records in the archive.
</p>
<p class="display-text">
The majority of the folklore collected in the archive is either in English or in Korean. Korean language recordings, and Korean words, are transcribed using the McCune-Reischauer transcription method. Instructions on transcribing according to the McCune-Reischauer system can be found <a href = 'http://roman.cs.pusan.ac.kr/input_eng.aspx' target=_blank>here</a>.
</p>

</div>