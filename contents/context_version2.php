<?php

$user = check_auth();

$cms->js[] = "js/jquery-latest.js";

if (isset($data[0])) {
    $fields = get_record('context',$data[0]);
    foreach ($fields as $k => $v) $$k = $v;
}

$group1 = 0;
$group2 = 0;
$group3 = 0;
$group4 = 0;
if (strstr($context_event_type, "Birthday") || strstr($context_event_type, "Seasonal/Holiday") || strstr($context_event_type, "Wedding") 
	|| strstr($context_event_type, "Funeral") || strstr($context_event_type, "Graduation") || strstr($context_event_type, "Other Celebration")){
		$group1 = 1;
}
if (strstr($context_event_type, "Oral History") || strstr($context_event_type, "Storytelling") || strstr($context_event_type, "Folk Speech/Gesture") ||
	strstr($context_event_type, "Drama") || strstr($context_event_type, "Song") || strstr($context_event_type, "Dance") || strstr($context_event_type, "Other Performance")){
		$group2 = 1;
}
if (strstr($context_event_type, "Architecture") || strstr($context_event_type, "Costume/Clothing") || strstr($context_event_type, "Body Art or Adornment") ||
	strstr($context_event_type, "Folk Art or Craft") || strstr($context_event_type, "Foodways") || strstr($context_event_type, "Other Material Culture")){
		$group3 = 1;
}

if (strstr($context_event_type, "General Observation")){
		$group4 = 1;
}

?>


<h3>CONTEXT</h3>
      <table class="formStyle">
        <tr> 
          <td align="left" valign="top"> 
            <form name="form1" method="post" action="handler/context/<?php if (isset($data[0])) echo $data[0]; ?>/<?php echo $data[1];?>">
              <input name="context_id" type="hidden" value="<?php echo $context_id; ?>">
              <table width="100%" border="0" height="37">
                <tr valign="bottom" align="left"> 
                  <td width="59%" height="7" class="form_title"><label>Event or Interview Title:</label> <font color="#FF0000">*</font><b> </b></td>
                  <td width="41%" height="7" class="form_title">&nbsp;</td>
                </tr>
                <tr valign="top" align="left"> 
                  <td width="59%" height="2"> 
                    <input name="context_event_name" type="text" value="<?php echo $context_event_name; ?>"  size="50" maxlength="50">
                  </td>
                  <td width="41%" height="2">&nbsp; </td>
                </tr>
              </table>
              <hr size="1" align="center">                                          
              <table width="100%" border="0" height="100">

                <tr valign="middle" align="left"> 
                  <td colspan="6" height="26" class="form_title" valign="top"><label>Type 
                    of event or expression:</label><b class="unnamed1"> (Check all that 
                    apply)</b> <font color="#FF0000">*</font></td>
                  <td width="104" height="26" class="unnamed1">&nbsp; </td>
                </tr>
				<tr>
				<td colspan="7" class="form_title">
				<input type="radio" name="event_type_category" value="Celebration"
				onClick="javascript:check_category(1);" <?php if ($group1) echo " checked";?>>
				a. Celebration
				</td>
				</tr>
                <tr valign="bottom" align="left" class="unnamed1"> 
				  <td nowrap>
                    <input type="checkbox" class="group1" name="context_event_type[]" value="Birthday" 
					<?php if (strstr($context_event_type, "Birthday")) echo " checked"; if (!$group1) echo " disabled";?>>
                    Birthday
				  </td>
				  <td nowrap> 
                    <input type="checkbox" class="group1" name="context_event_type[]" value="Seasonal/Holiday" 
					<?php if (strstr($context_event_type, "Seasonal/Holiday")) echo "checked"; if (!$group1) echo " disabled";?>>
                   Seasonal/Holiday
				  </td>
                  <td nowrap> 
                    <input type="checkbox" class="group1" name="context_event_type[]" value="Wedding" 
					<?php if ((strstr($context_event_type, "Wedding"))) echo "checked";if (!$group1) echo " disabled"; ?>>
                    Wedding</td>
                  <td nowrap> 
                    <input type="checkbox" class="group1" name="context_event_type[]" value="Funeral" 
					<?php if ((strstr($context_event_type, "Funeral"))) echo "checked";if (!$group1) echo " disabled"; ?>>
                    Funeral </td>
                  <td nowrap> 
                    <input type="checkbox" class="group1" name="context_event_type[]" value="Graduation" 
					<?php if ((strstr($context_event_type, "Graduation"))) echo "checked"; if (!$group1) echo " disabled";?>>
                    Graduation</td>
                  <td nowrap> 
                    <input type="checkbox" class="group1" name="context_event_type[]" value="Other Celebration" 
					<?php if ((strstr($context_event_type, "Other Celebration"))) echo "checked"; if (!$group1) echo " disabled";?>>
                    Other Celebration</td>
                  <td></td>
				</tr>
				<tr height=10></tr>
				<tr>
				<td colspan="7" class="form_title">
				<input type="radio" name="event_type_category" value="Performance" 
				onClick="javascript:check_category(2);" <?php if ($group2) echo " checked";?>>
				b. Performance</td>
				</tr>

                <tr valign="bottom" align="left" class="unnamed1"> 
                  <td nowrap> 
                    <input type="checkbox" class="group2" name="context_event_type[]" value="Oral History" 
					<?php if (strstr($context_event_type, "Oral History")) echo "checked"; if (!$group2) echo " disabled"; ?>>
                    Oral History</td>

				  <td nowrap> 
                    <input type="checkbox" class="group2" name="context_event_type[]" value="Storytelling" 
					<?php if (strstr($context_event_type, "Storytelling")) echo "checked"; if (!$group2) echo " disabled";?>>
                    Storytelling</td>

				 <td nowrap> 
                    <input type="checkbox" class="group2" name="context_event_type[]" value="Folk Speech/Gesture" 
					<?php if (strstr($context_event_type, "Folk Speech/Gesture")) echo "checked"; if (!$group2) echo " disabled";?>>
                   Folk Speech/Gesture</td>


				 <td nowrap> 
                    <input type="checkbox" class="group2" name="context_event_type[]" value="Drama" 
					<?php if (strstr($context_event_type, "Drama")) echo "checked"; if (!$group2) echo " disabled";?>>
                    Drama</td>

				<td nowrap> 
                    <input type="checkbox" class="group2" name="context_event_type[]" value="Song" 
					<?php if (strstr($context_event_type, "Song")) echo "checked";if (!$group2) echo " disabled"; ?>>
                    Song</td>

				 <td nowrap> 
                    <input type="checkbox" class="group2" name="context_event_type[]" value="Dance" 
					<?php if (strstr($context_event_type, "Dance")) echo "checked";if (!$group2) echo " disabled"; ?>>
                    Dance</td>

				<td nowrap>
					<input type="checkbox" class="group2" name="context_event_type[]" value="Other Performance" 
					<?php if (strstr($context_event_type, "Other Performance")) echo "checked"; if (!$group2) echo " disabled";?>>
                    Other Performance
				</td>
				</tr>

				<tr height=10></tr>

				<tr>
				<td colspan="7" class="form_title">
				<input type="radio" name="event_type_category" value="Material Culture" 
				onClick="javascript:check_category(3);" <?php if ($group3) echo " checked";?>>
				c. Material Culture</td>
				</tr>

                <tr valign="bottom" align="left" class="unnamed1"> 
				<td nowrap> 
				<input type="checkbox" class="group3" name="context_event_type[]" value="Architecture" 
				<?php if (strstr($context_event_type, "Architecture")) echo "checked"; if (!$group3) echo " disabled";?>>
                    Architecture</td>
				<td nowrap> 
                    <input type="checkbox" class="group3" name="context_event_type[]" value="Costume/Clothing" 
					<?php if (strstr($context_event_type, "Costume/Clothing")) echo "checked"; if (!$group3) echo " disabled";?>>
                    Costume/Clothing</td>
				 <td nowrap> 
                    <input type="checkbox" class="group3" name="context_event_type[]" value="Body Art or Adornment" 
					<?php if (strstr($context_event_type, "Body Art or Adornment")) echo "checked"; if (!$group3) echo " disabled";?>>
                    Body Art or Adornment
					</td>
				 <td nowrap> 
                    <input type="checkbox" class="group3" name="context_event_type[]" value="Folk Art or Craft" 
					<?php if (strstr($context_event_type, "Folk Art or Craft")) echo "checked"; if (!$group3) echo " disabled";?>>
                    Folk Art or Craft</td>
				<td nowrap> 
                    <input type="checkbox" class="group3" name="context_event_type[]" value="Foodways" 
					<?php if (strstr($context_event_type, "Foodways")) echo "checked"; if (!$group3) echo " disabled";?>>
                    Cooking</td>
				<td nowrap> 
                    <input type="checkbox" class="group3" name="context_event_type[]" value="Other Material Culture" 
					<?php if (strstr($context_event_type, "Other Material Culture")) echo "checked";if (!$group3) echo " disabled"; ?>>
                    Other Material Culture</td>
				<td>
				</td>

				</tr>

				<tr height=10></tr>

				<tr>
				<td colspan="7" class="form_title">
				<input type="radio" name="event_type_category" value="General Observation" 
				onClick="javascript:check_category(4);" <?php if ($group4) echo " checked";?>>
				d. General Observation
				<input type="hidden" id="general_observation" name="context_event_type[]" value="">
				</td>
				</tr>
              </table>
              <hr size="1" align="center">
              <table width="100%" border="0" height="0">
                <tr valign="bottom" align="left"> 
                  <td height="16" class="form_title" width="167"><label>Time of collection: 
                    <font color="#FF0000">*</font> </label> </td>
                  <td height="16" class="form_title">Date of collection (yyyy/mm/dd): 
                    <font color="#FF0000">*</font></td>
                </tr>
                <tr valign="middle" align="left"> 
                  <td width=60%> 
				   <input type="radio" name="context_time" value="morning" <?php if (trim($context_time) == "morning") echo "checked"; ?> >
                    Morning &nbsp;&nbsp;
                    <input type="radio" name="context_time" value="afternoon" <?php if (trim($context_time) == "afternoon") echo "checked"; ?> >
                    Afternoon&nbsp;&nbsp;
                    <input type="radio" name="context_time" value="evening" <?php if (trim($context_time) == "evening") echo "checked"; ?> >
                    Evening&nbsp;&nbsp;
                    <input type="radio" name="context_time" value="night" <?php if (trim($context_time) == "night") echo "checked"; ?> >
                    Night&nbsp;&nbsp;
                  </td>
                  <td class="unnamed1"> 
                    <input type="text" name="context_date" id="datepicker" value="<?php if (!empty($context_date)) echo $context_date; ?>" size="12" maxlength="12">
                  </td>
                </tr>
              </table>
              <hr size="1" align="center">
              <table width="100%" border="0">
                <tr valign="bottom" align="left"> 
                  <td colspan="2" height="8" class="form_title"> Weather on the 
                    day of collection: <font color="#FF0000">*</font></td>
                  <td height="8" class="form_title" colspan="2">&nbsp; </td>
                </tr>
                <tr valign="middle" align="left"> 
                  <td width="25%" height="1" class="unnamed1"> 
                    <input type="radio" name="context_weather" value="Sunny" <?php if (trim($context_weather) == "Sunny") echo "checked"; ?> >
                    Sunny </td>
                  <td width="25%" height="1" class="unnamed1"> 
                    <input type="radio" name="context_weather" value="Overcast" <?php if (trim($context_weather) == "Overcast") echo "checked"; ?> >
                    Overcast</td>
                  <td width="25%" height="1" class="unnamed1"> 
                    <input type="radio" name="context_weather" value="Raining" <?php if (trim($context_weather) == "Raining") echo "checked"; ?> >
                    Raining</td>
                  <td width="25%" height="1" class="unnamed1"> 
                    <input type="radio" name="context_weather" value="Snowing" <?php if (trim($context_weather) == "Snowing") echo "checked"; ?> >
                    Snowing</td>
                </tr>
              </table>
              <hr size="1" align="center">
              <table width="100%" border="0" height="121">
                <tr valign="bottom" align="left"> 
                  <td colspan="2" height="16" class="form_title"><b>Language(s) 
                    of collection: <font color="#FF0000">*</font></b> </td>
                  <td width="100" height="16" class="form_title">&nbsp;</td>
                  <td width="127" height="16" class="form_title">&nbsp; </td>
                </tr>
                <tr valign="middle" align="left"> 
                  <td width="104" height="13" class="unnamed1"> 
                    <input type="checkbox" name="context_language[]" value="Korean" <?php if ((strstr($context_language, "Korean"))) echo "checked"; ?> >
                    Korean</td>
                  <td width="107" height="13" class="unnamed1"> 
                     
                      <input type="checkbox" name="context_language[]" value="Japanese" <?php if ((strstr($context_language, "Japanese"))) echo "checked"; ?> >
                      Japanese
                  </td>
                  <td width="100" height="13" class="unnamed1"> 
                    <input type="checkbox" name="context_language[]" value="Chinese" <?php if ((strstr($context_language, "Chinese"))) echo "checked"; ?> >
                    Chinese</td>
                  <td width="127" height="13" class="unnamed1"> 
                    <input type="checkbox" name="context_language[]" value="English" <?php if ((strstr($context_language, "English"))) echo "checked"; ?> >
                    English</td>
                </tr>
                <tr valign="middle" align="left"> 
                  <td width="104" height="5" class="unnamed1"> 
                    <input type="checkbox" name="context_language[]" value="Portugese" <?php if ((strstr($context_language, "Portugese"))) echo "checked"; ?> >
                    Portuguese</td>
                  <td width="107" height="5" class="unnamed1"> 
                    <input type="checkbox" name="context_language[]" value="Other" <?php if ((strstr($context_language, "Other"))) echo "checked"; ?> >
                    Other</td>
                  <td width="100" height="5" class="unnamed1">&nbsp; </td>
                  <td width="127" height="5" class="unnamed1">&nbsp; </td>
                </tr>
                <tr valign="bottom" align="left"> 
                  <td colspan="2" height="2" class="form_title">If other, please 
                    specify:</td>
                  <td width="100" height="2" class="form_title">&nbsp;</td>
                  <td width="127" height="2" class="form_title">&nbsp;</td>
                </tr>
                <tr valign="bottom" align="left"> 
                  <td colspan="2" height="12" class="form_title"> 
                    <input type="text" name="context_other_language" value="<?php echo $context_other_language; ?>" size="30">
                  </td>
                  <td width="100" height="12" class="form_title">&nbsp;</td>
                  <td width="127" height="12" class="form_title">&nbsp;</td>
                </tr>
              </table>
              <hr size="1" align="center">

              <table width="100%" border="0" height="20">
                <tr valign="bottom" align="left"> 
                  <td colspan="2" height="8" class="form_title"> Place of collection: 
                    <font color="#FF0000">*</font></td>
                  <td height="8" class="form_title" colspan="2">&nbsp; </td>
                </tr>

                <tr valign="middle" align="left"> 
                  <td width="30%" height="16" class="unnamed1"> 
                    <input type="radio" name="context_place" value="Business" <?php if (trim($context_place) == "Business") echo "checked"; ?> >
                    Business</td>
                  <td width="35%" height="16" class="unnamed1"> 
                     
                      <input type="radio" name="context_place" value="Residence" <?php if (trim($context_place) == "Residence") echo "checked"; ?> >
                      Residence
                  </td>
                  <td width="35%" height="16" class="unnamed1"> 
                    <input type="radio" name="context_place" value="Public Place" <?php if (trim($context_place) == "Public Place") echo "checked"; ?> >
                    Public Place</td>
                </tr>

              </table>
              <hr size="1" align="center">
              
              <table width="100%" border="0">
                <tr valign="bottom" align="left"> 
                  <td colspan="2" height="16" class="form_title"> Address of Collection:</td>
                  <td height="16">&nbsp; </td>
                </tr>
                <tr valign="bottom" align="left"> 
                  <td width="30%" height="8" class="form_title">Street Address: 
                  </td>
                  <td width="35%" height="8" class="form_title"><b>City:</b> </td>
                  <td width="35%" height="8" class="form_title"><b>State/Province: </b></td>
                </tr>
                <tr valign="top" align="left"> 
                  <td width="30%" height="13"> 
                    <input type="text" name="context_street" value="<?php echo $context_street; ?>" size="19" maxlength="50">
                  </td>
                  <td width="35%" height="13"> 
                     
                      <input name="context_city" type="text" value="<?php echo $context_city; ?>" size="15" maxlength="30">
                    
                  </td>
                  <td width="35%" height="13"> 
                    <input type="text" name="context_state" value="<?php echo $context_state; ?>" size="3" maxlength="20">
                  </td>
                </tr>
                <tr valign="middle" align="left"> 
                  <td width="30%" height="5" class="form_title">Zipcode/Postal Code: </td>
                  <td width="35%" height="5" class="form_title"><b>Country:</b> 
                  </td>
                  <td width="35%" height="5" class="form_title">&nbsp;</td>
                </tr>
                <tr valign="middle" align="left"> 
                  <td width="30%" height="2" class="unnamed1"> 
                    <input type="text" name="context_zipcode" value="<?php echo $context_zipcode; ?>" size="19" maxlength="20">
                  </td>
                  <td width="35%" height="2" class="unnamed1"> 
                    <input type="text" name="context_country" value="<?php echo $context_country; ?>" size="15" maxlength="20">
                  </td>
                  <td width="35%" height="2" class="unnamed1">&nbsp;</td>
                </tr>
              </table>
              <hr size="1" align="center">
              
              <table width="100%" border="0">
                <tr valign="bottom" align="left"> 
                  <td colspan="3" height="8" class="form_title"><b>If the address 
                      of collection is unknown, </b><b> please enter GPS Info 
                      of Collection: </b> 
                    </td>
                </tr>
                <tr valign="middle" align="left"> 
                  <td width="29%" height="8" class="form_title" valign="bottom">Latitude: 
                  </td>
                  <td width="36%" height="8" valign="bottom"> 
                    &nbsp;</td>
                  <td width="35%" height="8" class="unnamed1">&nbsp; </td>
                </tr>
                <tr valign="middle" align="left"> 
                  <td width="29%" height="9" class="unnamed1">Direction:</td>
                  <td width="36%" height="9" class="unnamed1">Degrees [0,180): 
                  </td>
                  <td width="35%" height="9" class="unnamed1">Minutes.Seconds:</td>
                </tr>
                <tr valign="middle" align="left"> 
                  <td width="29%" height="0" class="unnamed1"> 
                    <select name="context_latitude_direction" size="1">
                      <option value="*">Please Select</option>
                      <option value="N" <?php if (trim($context_latitude_direction) == "N") echo "selected"; ?> >N</option>
                      <option value="S" <?php if (trim($context_latitude_direction) == "S") echo "selected"; ?> >S</option>
                    </select>
                  </td>
                  <td width="36%" height="-4" class="unnamed1">
                    <input type="text" name="context_latitude_degree" value="<?php echo $context_latitude_degree; ?>" size="15" maxlength="3">
                  </td>
                  <td width="35%" height="-4" class="unnamed1">
                    <input type="text" name="context_latitude_minsec" value="<?php echo $context_latitude_minsec; ?>" size="15" maxlength="6">
                  </td>
                </tr>
                <tr valign="bottom" align="left"> 
                  <td width="29%" height="8" class="form_title">Longitude: </td>
                  <td width="36%" height="8" class="unnamed1">&nbsp;</td>
                  <td width="35%" height="8" class="unnamed1">&nbsp;</td>
                </tr>
                <tr valign="middle" align="left"> 
                  <td width="29%" height="1" class="unnamed1">Direction:</td>
                  <td width="36%" height="-1" class="unnamed1">Degrees [0,180):</td>
                  <td width="35%" height="-1" class="unnamed1">Minutes.Seconds:</td>
                </tr>
                <tr valign="middle" align="left"> 
                  <td width="29%" height="1" class="unnamed1"> 
                    <select name="context_longitude_direction" size="1">
                      <option value="*">Please Select</option>
                      <option value="E" <?php if (trim($context_longitude_direction) == "E") echo "selected"; ?> >E</option>
                      <option value="W" <?php if (trim($context_longitude_direction) == "W") echo "selected"; ?> >W</option>
                    </select>
                  </td>
                  <td width="36%" height="1" class="unnamed1">
                    <input type="text" name="context_longitude_degree" value="<?php echo $context_longitude_degree; ?>" size="15" maxlength="3">
                  </td>
                  <td width="35%" height="1" class="unnamed1">
                    <input type="text" name="context_longitude_minsec" value="<?php echo $context_longitude_minsec; ?>" size="15" maxlength="6">
                  </td>
                </tr>
              </table>
              <hr size="1" align="center">

              <table width="100%" border="0" height="105">
                <tr valign="bottom" align="left"> 
                  <td colspan="2" height="7" class="form_title"><b>Consultants present: </b></td>
                  <td width="41%" height="7" class="form_title">&nbsp;</td>
                </tr>
                <tr valign="middle" align="left">
                <?php
                    $count = 0;
                    if (isset($collector_id)) $result = get_records('consultant', $collector_id);
                    else $result = get_records('consultant', $user->get('id'));

                    $consultants = (isset($context_consultants)) ? explode(',', $context_consultants) : array();
                    foreach ($result as $row)
                    {
                        $count++;
                ?>
                    <td height="2" width="82">
                    <input type="checkbox" name="context_consultants[]" value="<?php echo $row['consultant_id']; ?>" <?php if (in_array($row['consultant_id'], $consultants)) echo 'checked'; ?>><?php echo $row['consultant_first_name'],' ',$row['consultant_last_name']; ?>
                    </td>
                <?php
                    if (!($count%3)) echo '</tr><tr>';
                    }
                ?>
                </tr>
                <tr valign="top" align="left"> 
                  <td colspan="2" height="10" valign="bottom"><span class="form_title">Number 
                    of others present:</span> </td>
                  <td width="41%" height="10">&nbsp;</td>
                </tr>
                <tr valign="middle" align="left"> 
                  <td width="32%" height="25" class="unnamed1"> 
                    <input type="text" name="context_otherpresent_num" value="<?php echo $context_otherpresent_num; ?>" size="8" maxlength="5">
                  </td>
                  <td width="27%" height="25">&nbsp; </td>
                  <td width="41%" height="25">&nbsp;</td>
                </tr>
                <tr valign="middle" align="left"> 
                  <td colspan="2" height="10" class="unnamed1"><span class="form_title">Age 
                    of others present:</span> (Check all that apply)</td>
                  <td width="41%" height="-3">&nbsp;</td>
                </tr>
                <tr valign="middle" align="left"> 
                  <td width="32%" height="10" class="unnamed1"> 
                    <input type="checkbox" name="context_otherpresent_age[]" value="Younger" <?php if ((strstr($context_otherpresent_age, "Younger"))) echo "checked";?>>
                    Younger</td>
                  <td width="27%" height="-1" class="unnamed1"> 
                    <input type="checkbox" name="context_otherpresent_age[]" value="Same Age" <?php if ((strstr($context_otherpresent_age, "Same Age"))) echo "checked";?>>
                    Same-age-as</td>
                  <td width="41%" height="-1" class="unnamed1"> 
                    <input type="checkbox" name="context_otherpresent_age[]" value="Older" <?php if ((strstr($context_otherpresent_age, "Older"))) echo "checked";?>>
                    Older than main consultant</td>
                </tr>
              </table>
              <hr size="1" align="center">

              <table width="100%" border="0" height="30">              
                <tr valign="bottom" align="left"> 
                  <td colspan="2" height="16" class="form_title"><b>Method of 
                    collection :</b></td>
                  <td width="100" height="16" class="form_title">&nbsp;</td>
                  <td width="127" height="16" class="form_title">&nbsp; </td>
                </tr>

                <tr valign="middle" align="left"> 
                  <td width="104" height="13" class="unnamed1"> 
                    <input type="checkbox" name="context_media[]" value="Tape Recorder" <?php if ((strstr($context_media, "Tape Recorder"))) echo "checked"; ?> >
                    Tape-Recorder</td>
                  <td width="107" height="13" class="unnamed1"> 
                     
                      <input type="checkbox" name="context_media[]" value="Video Camera" <?php if ((strstr($context_media, "Video Camera"))) echo "checked"; ?> >
                      Video Camera
                  </td>
                  <td width="100" height="13" class="unnamed1"> 
                    <input type="checkbox" name="context_media[]" value="Still Camera" <?php if ((strstr($context_media, "Still Camera"))) echo "checked"; ?> >
                    Still Camera</td>
                  <td width="127" height="13" class="unnamed1"> 
                    <input type="checkbox" name="context_media[]" value="Notes" <?php if ((strstr($context_media, "Notes"))) echo "checked"; ?> >
                    Notes </td>
                </tr>
              </table>
              <hr size="1" align="center">
              
              <table width="100%" border="0">
                <tr valign="bottom" align="left"> 
                  <td width="30%" height="16" class="form_title" valign="top">Context 
                    Description: <font color="#FF0000">*</font></td>
                  <td colspan="2" height="80" class="form_title" rowspan="2"> 
                    
                      <textarea name="context_description" wrap="VIRTUAL" cols="50" rows="10" class="unnamed1"><?php echo $context_description; ?></textarea>
                    
                  </td>
                </tr>
                <tr valign="middle" align="left"> 
                  <td width="30%" height="30" class="unnamed1">&nbsp; </td>
                </tr>
                <tr valign="middle" align="left"> 
                  <td height="5" class="unnamed1">&nbsp; </td>
                  <td height="5" class="unnamed1">&nbsp; </td>
                  <td height="5" class="unnamed1">&nbsp; </td>
                </tr>
              </table>
              <hr size="1" align="center">
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr> 
                  <td class="unnamed1" style="text-align: center;"> 
                    <input type="submit" class="unnamed1 btnSubmit">&nbsp;&nbsp;&nbsp;
                    <a href="dashboard/<?php echo $data[1];?>">Cancel</a>&nbsp;&nbsp;&nbsp;
                  </td>
                </tr>
              </table>
              </form>
          </td>
        </tr>
      </table>