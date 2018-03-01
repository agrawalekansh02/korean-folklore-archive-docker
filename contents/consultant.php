<?php

$user = check_auth();

if (isset($data[0]) && !empty($data[0])) {
	$fields = get_record('consultant',$data[0]);
	if (count($fields) == 0) {
		echo 'Invalid URL';
		return false;
	}
	foreach ($fields as $k => $v) $$k = $v;
}
?>
<h3>CONSULTANT PROFILE</h3>

<table class="formStyle">
<tr> 
<td align="left" valign="top"> 
<?php
  $action = "handler/consultant/";
  if(isset($data) && !empty($data)){
    $actionArray = array_slice($data, 0, 3);
    $action .= implode("/", $actionArray);
  }
?>
<form name="form1" enctype="multipart/form-data" method="post" action="<?php echo $action; ?>">

<input name="consultant_id" type="hidden" value="<?php echo isset($consultant_id) ? $consultant_id : ''; ?>">

<table width="100%" border="0">
	<tr valign="bottom" align="left"> 
		<td width="24%" class="form_title">Last Name: <font color="#FF0000">*</font> </td>
		<td width="19%" class="form_title"> First Name: <font color="#FF0000">*</font> </td>
		<td width="57%" class="form_title"> Initial: </td>
	</tr>
	<tr valign="top" align="left"> 
		<td width="24%"> 
			<input name="consultant_last_name" type="text" id="consultant_last_name" 
					value="<?php echo isset($consultant_last_name) ? $consultant_last_name : ''; ?>" size="19" maxlength="20">
		</td>
		<td width="19%"> 
			<input name="consultant_first_name" type="text" id="consultant_first_name" 
					value="<?php echo isset($consultant_first_name) ? $consultant_first_name : ''; ?>" size="15" maxlength="20">
		</td>
		<td width="57%"> 
			<input name="consultant_initial" type="text" id="consultant_initial" 
					value="<?php echo isset($consultant_initial) ? $consultant_initial : ''; ?>" size="2" maxlength="2">
		</td>
	</tr>
	<tr valign="middle" align="left"> 
		<td width="19%" class="form_title">  Show Level: <font color="#FF0000">*</font></td>
		<td width="19%">
			<select name="consultant_show_level" size="1" class="unnamed1" id="consultant_show_level">
				<option value="name,address" 
				<?php if (isset($consultant_show_level) && strstr($consultant_show_level, "name") && strstr($consultant_show_level, "address")) echo "selected"; ?> >
					Name &amp; Address</option>
				<option value="name," 
				<?php if (isset($consultant_show_level) && strstr($consultant_show_level, "name") && !strstr($consultant_show_level, "address")) echo "selected"; ?> >
					Name only</option>
				<option value="address," 
				<?php if (isset($consultant_show_level) && !strstr($consultant_show_level, "name") && strstr($consultant_show_level, "address")) echo "selected"; ?> >
					Address only</option>
				<option 
				<?php if (!isset($consultant_show_level) || (!strstr($consultant_show_level, "name") && !strstr($consultant_show_level, "address"))) echo "selected"; ?> >
					None</option>
			</select> 
		</td>
		<td width="57%">&nbsp;</td>
	</tr>
</table>

<hr size="1" align="center">

<table width="100%" border="0">
	<tr valign="bottom" align="left"> 
		<td width="24%" class="form_title">Street Address: </td>
		<td width="19%" class="form_title"> City: </td>
		<td width="57%" class="form_title">State/Province: </td>
	</tr>
	<tr valign="top" align="left"> 
		<td width="24%"> 
			<input name="consultant_street" type="text" id="consultant_street" 
					value="<?php echo isset($consultant_street) ? $consultant_street : ''; ?>" size="19" maxlength="50">
		</td>
		<td width="19%"> 

			<input name="consultant_city" type="text" id="consultant_city" 
					value="<?php echo isset($consultant_city) ? $consultant_city : ''; ?>" size="15" maxlength="30">
		</td>
		<td width="57%"> 
			<input name="consultant_state" type="text" id="consultant_state" 
					value="<?php echo isset($consultant_state) ? $consultant_state : ''; ?>" size="3" maxlength="20">
		</td>
	</tr>
	<tr valign="middle" align="left"> 
		<td width="24%" class="form_title">Zip/Postal Code:</td>
		<td width="19%" class="form_title">Country: </td>
		<td width="57%" class="form_title">&nbsp;</td>
	</tr>
	<tr valign="middle" align="left"> 
		<td width="24%" class="unnamed1"> 
			<input name="consultant_zipcode" type="text" id="consultant_zipcode" 
					value="<?php echo isset($consultant_zipcode) ? $consultant_zipcode : ''; ?>" size="19" maxlength="20">
		</td>
		<td width="19%" class="unnamed1"> 
			<input name="consultant_country" type="text" id="consultant_country" 
				value="<?php echo isset($consultant_country) ? $consultant_country : ''; ?>" size="15" maxlength="20">
		</td>
		<td width="57%" class="unnamed1">&nbsp;</td>
	</tr>
</table>

<hr size="1" align="center">

<table width="100%" border="0">
	<tr valign="bottom" align="left"> 
		<td colspan="3" class="form_title"> 
			<table width="99%" border="0" cellspacing="0" cellpadding="0">
				<tr> 
					<td width="60%" class="form_title" >Date of Birth (yyyy/mm/dd): </td>
					<td width="40%" class="form_title" >Age:</td>
				</tr>
				<tr> 
					<td width="60%"> 
						<input name="consultant_dob" type="text" id="datepicker" 
								value="<?php echo isset($consultant_dob) ? $consultant_dob : ''; ?>" size="12" maxlength="12">
					</td>
					<td width="40%"> 
						<input name="consultant_age" type="text" id="consultant_age" 
								value="<?php echo isset($consultant_age) ? $consultant_age : ''; ?>" size="3" maxlength="3">
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr valign="top" align="left"> 
		<td width="32%" class="form_title" valign="bottom">Place of Birth:</td>
		<td width="28%" class="form_title">&nbsp;</td>
		<td width="40%" class="form_title">&nbsp;</td>
	</tr>
	<tr valign="top" align="left"> 
		<td width="32%" class="form_title">City:</td>
		<td width="28%" class="form_title">State/Province:</td>
		<td width="40%" class="form_title">Country: <font color="#FF0000">*</font></td>
	</tr>
	<tr valign="top" align="left"> 
		<td width="32%" class="form_title"> 
			<input name="consultant_birth_city" type="text" id="consultant_birth_city" 
					value="<?php echo isset($consultant_birth_city) ? $consultant_birth_city : '';?>" size="15" maxlength="30">
		</td>
		<td width="28%" class="form_title"> 
			<input name="consultant_birth_state" type="text" id="consultant_birth_state" 
					value="<?php echo isset($consultant_birth_state) ? $consultant_birth_state : ''; ?>" size="3" maxlength="20">
		</td>
		<td width="40%" class="form_title"> 
			<input name="consultant_birth_country" type="text" id="consultant_birth_country" 
					value="<?php echo isset($consultant_birth_country) ? $consultant_birth_country : ''; ?>" size="15" maxlength="20">
		</td>
	</tr>
	<tr valign="middle" align="left"> 
		<td width="32%" class="form_title">Gender: <font color="#FF0000">*</font> </td>
		<td width="28%" class="form_title">Marital status: </td>
		<td width="40%" class="form_title">Occupation: </td>
	</tr>
	<tr valign="middle" align="left"> 
		<td width="32%" class="unnamed1"> 
			<select name="consultant_gender" size="1" class="unnamed1" id="consultant_gender">
				<option value="M" <?php if (!isset($consultant_gender) || (isset($consultant_gender) && trim($consultant_gender)) == "M") echo "selected"; ?> >Male</option>
				<option value="F" <?php if (isset($consultant_gender) && trim($consultant_gender) == "F") echo "selected"; ?> >Female</option>
				<option value="O" <?php if (isset($consultant_gender) && trim($consultant_gender) == "O") echo "selected"; ?> >Other</option>
			</select>
		</td>
		<td width="28%" class="unnamed1"> 
			<input name="consultant_marital_status" type="text" id="consultant_marital_status" value="<?php echo isset($consultant_marital_status) ? $consultant_marital_status : ''; ?>" size="15" maxlength="30">
		</td>
		<td width="40%" class="unnamed1"> 
			<input name="consultant_occupation" type="text" id="consultant_occupation" value="<?php echo isset($consultant_occupation) ? $consultant_occupation : ''; ?>" size="19" maxlength="30">
		</td>
	</tr>
</table>

<hr size="1" align="center">

<table width="100%" border="0">
	<tr valign="bottom" align="left"> 
		<td width="30%" class="form_title">Educational Level : </td>
		<td width="35%" class="form_title">&nbsp; </td>
		<td width="35%" class="form_title">&nbsp; </td>
	</tr>
	<tr valign="middle" align="left"> 
		<td width="30%" class="unnamed1"> 
			<input type="radio" name="consultant_edu_level" value="No Schooling" 
			<?php if (isset($consultant_edu_level) && trim($consultant_edu_level) == "No Schooling") echo "checked"; ?> >
			No Schooling
		</td>
		<td width="35%" class="unnamed1"> 
			<input type="radio" name="consultant_edu_level" value="Elementary School" 
			<?php if (isset($consultant_edu_level) && trim($consultant_edu_level) == "Elementary School") echo "checked"; ?> >
			Elementary School
		</td>
		<td width="35%" class="unnamed1"> 
			<input type="radio" name="consultant_edu_level" value="Middle School"
			<?php if (isset($consultant_edu_level) && trim($consultant_edu_level) == "Middle School") echo "checked"; ?> >
			Middle School
		</td>
	</tr>
	<tr valign="middle" align="left"> 
		<td width="30%" class="unnamed1"> 
			<input type="radio" name="consultant_edu_level" value="High School"
			<?php if (isset($consultant_edu_level) && trim($consultant_edu_level) == "High School") echo "checked"; ?> >
			High School
		</td>
		<td width="35%" class="unnamed1"> 
			<input type="radio" name="consultant_edu_level" value="College"
			<?php if (isset($consultant_edu_level) && trim($consultant_edu_level) == "College") echo "checked"; ?> >
			College
		</td>
		<td width="35%" class="unnamed1"> 
			<input type="radio" name="consultant_edu_level" value="Professional Degree" 
			<?php if (isset($consultant_edu_level) && trim($consultant_edu_level) == "Professional Degree") echo "checked"; ?> >
			Professional Degree 
		</td>  
	</tr>
	<tr valign="middle" align="left"> 
		<td width="30%" class="unnamed1">
			<input type="radio" name="consultant_edu_level" value="Graduate Degree"
			<?php if (isset($consultant_edu_level) && trim($consultant_edu_level) == "Graduate Degree") echo "checked"; ?> >
			Graduate Degree
		</td>
		<td width="35%" class="unnamed1">&nbsp;</td>
		<td width="35%" class="unnamed1">&nbsp;</td>
	</tr>
</table>

<hr size="1" align="center">

<table width="100%" border="0">
	<tr valign="bottom" align="left"> 
		<td width="30%" class="form_title">Income Level: </td>
		<td width="35%" class="form_title">&nbsp; </td>
		<td width="35%" class="form_title">&nbsp; </td>
	</tr>
	<tr valign="middle" align="left"> 
		<td width="30%" class="unnamed1"> 
			<input type="radio" name="consultant_income_level" value="10000" 
			<?php if (isset($consultant_income_level) && trim($consultant_income_level) == "10000") echo "checked";?> >
			0 - 10,000
		</td>
		<td width="35%" class="unnamed1"> 
			<input type="radio" name="consultant_income_level" value="35000" 
			<?php if (isset($consultant_income_level) && trim($consultant_income_level) == "35000") echo "checked";?> >
			10,001 - 35,000
		</td>
		<td width="35%" class="unnamed1"> 
			<input type="radio" name="consultant_income_level" value="50000" 
			<?php if (isset($consultant_income_level) && trim($consultant_income_level) == "50000") echo "checked";?> >
			35,001 - 50,000
		</td>
	</tr>
	<tr valign="middle" align="left"> 
		<td width="30%" class="unnamed1"> 
			<input type="radio" name="consultant_income_level" value="100000" 
			<?php if (isset($consultant_income_level) && trim($consultant_income_level) == "100000") echo "checked";?> >
			50,001 - 100,000
		</td>
		<td width="35%" class="unnamed1">
			<input type="radio" name="consultant_income_level" value="100001" 
			<?php if (isset($consultant_income_level) && trim($consultant_income_level) == "100001") echo "checked";?> >
			100,000+
		</td>
		<td width="35%" class="unnamed1"><input type="radio" name="consultant_income_level" value="-1" 
			<?php if (isset($consultant_income_level) && trim($consultant_income_level) == "-1") echo "checked";?> >
			Decline to State
		</td>
	</tr>
</table>

<hr size="1" align="center">

<table width="100%" border="0">
	<tr valign="bottom" align="left"> 
		<td width="26%" class="form_title">Heritage: <font color="#FF0000">*</font> </td>
		<td width="24%" class="form_title">&nbsp; </td>
		<td width="22%" class="form_title">&nbsp;</td>
		<td width="28%" class="form_title">&nbsp; </td>
	</tr>
	<tr valign="middle" align="left"> 
		<td width="26%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_korean" 
					value="Korean" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Korean")) echo "checked";?> > Korean
		</td>
		<td width="24%" class="unnamed1">  
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_japanese" 
					value="Japanese" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Japanese")) echo "checked"; ?> > Japanese
		</td>
		<td width="22%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_chinese" 
					value="Chinese" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Chinese")) echo "checked"; ?> > Chinese
		</td>
		<td width="28%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_vietnamese" 
					value="Vietnamese" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Vietnamese")) echo "checked"; ?> > Vietnamese 
		</td>
	</tr>
	<tr valign="middle" align="left"> 
		<td width="26%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_khmer" 
					value="Khmer" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Khmer")) echo "checked"; ?> > Khmer 
		</td>
		<td width="24%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_lao" 
					value="Lao" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Lao")) echo "checked"; ?> > Lao 
		</td>
		<td width="22%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_thai" 
					value="Thai"<?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Thai")) echo "checked"; ?> > Thai 
		</td>
		<td width="28%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_philippino" 
					value="Philippino" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Philippino")) echo "checked"; ?> > Philippino 
		</td>
	</tr>
	<tr valign="middle" align="left"> 
		<td width="26%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_taiwanese" 
					value="Taiwanese"<?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Taiwanese")) echo "checked"; ?> > Taiwanese
		</td>
		<td width="24%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_burmese" 
					value="Burmese" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Burmese")) echo "checked"; ?> > Burmese 
		</td>
		<td width="22%" class="unnamed1"> <input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_malay" 
					value="Malay" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Malay")) echo "checked"; ?> > Malay 
		</td>
		<td width="28%" class="unnamed1"> <input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_indonesian" 
					value="Indonesian" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Indonesian")) echo "checked"; ?> > Indonesian 
		</td>
	</tr>
	<tr valign="middle" align="left"> 
		<td width="26%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_native_american" 
					value="Native American" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Native American")) echo "checked"; ?> > Native American
		</td>
		<td width="24%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_latino" 
					value="Latino" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Latino")) echo "checked"; ?> > Latino
		</td>
		<td width="22%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_european" 
					value="European" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "European")) echo "checked"; ?> > European
		</td>
		<td width="28%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_african_american" 
					value="African American" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "African American")) echo "checked"; ?> > African American
		</td>
	</tr>
	<tr valign="middle" align="left"> 
		<td width="26%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_south_asian" 
					value="South Asian" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "South Asian")) echo "checked"; ?> > South Asian
		</td>
		<td width="24%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_arabic" 
					value="Arabic" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Arabic")) echo "checked"; ?> > Arabic
		</td>
		<td width="22%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_north_african" 
					value="North African" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "North African")) echo "checked"; ?> > North African
		</td>
		<td width="28%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_central_african" 
					value="Central African" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Central African")) echo "checked"; ?> > Central African
		</td>
	</tr>
	<tr valign="middle" align="left"> 
		<td width="26%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_south_african" 
					value="South African" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "South African")) echo "checked"; ?> > South African
		</td>
		<td width="24%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_pacific_islander" 
					value="Pacific Islander" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Pacific Islander")) echo "checked"; ?> > Pacific Islander
		</td>
		<td width="22%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_central_asian" 
					value="Central Asian" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Central Asian")) echo "checked"; ?> > Central Asian
		</td>
		<td width="28%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_central_american" 
					value="Central American" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "Central American")) echo "checked"; ?> > Central American
		</td>
	</tr>
	<tr valign="middle" align="left"> 
		<td width="26%" class="unnamed1"> 
			<input name="consultant_heritage[]" type="checkbox" id="consultant_heritage_south_american" 
					value="South American" <?php if (isset($consultant_heritage) && strstr($consultant_heritage, "South American")) echo "checked"; ?> > South American
		</td>
		<td width="24%" class="unnamed1">&nbsp;</td>
		<td width="22%" class="unnamed1">&nbsp;</td>
		<td width="28%" class="unnamed1">&nbsp;</td>
	</tr>
	<tr valign="middle" align="left"> 
		<td colspan="2" class="form_title">Languages spoken: <font color="#FF0000">*</font></td>
		<td width="24%" class="form_title">&nbsp;</td>
		<td width="21%" class="form_title">&nbsp;</td>
	</tr>
	<tr valign="middle" align="left"> 
		<td colspan="2" class="unnamed1"> <input name="consultant_language" type="text" id="consultant_language" 
			value="<?php echo isset($consultant_language) ? $consultant_language : ''; ?>" size="40" maxlength="100"> 
		</td>
		<td width="24%" class="unnamed1">&nbsp;</td>
		<td width="21%" class="unnamed1">&nbsp;</td>
	</tr>
</table>

<hr size="1" align="center">

<table width="100%" border="0">
	<tr valign="bottom" align="left"> 
		<td colspan="2" class="form_title">Did you immigrate to the United States? <font color="#FF0000">*</font> </td>
		<td width="35%" class="form_title">&nbsp; </td>
	</tr>
	<tr valign="middle" align="left"> 
		<td width="30%" class="unnamed1"> 
			<input type="radio" name="consultant_immigration_status" value="1" 
				<?php if (isset($consultant_immigration_status) && trim($consultant_immigration_status) === "1") echo "checked"; ?> > Yes
		</td>
		<td width="35%" class="unnamed1"> 
			<input type="radio" name="consultant_immigration_status" value="0" 
			<?php if (isset($consultant_immigration_status) && trim($consultant_immigration_status) === "0") echo "checked"; ?> > No 
		</td>
		<td width="35%" class="unnamed1">&nbsp; </td>
	</tr>
	<tr valign="bottom" align="left"> 
		<td colspan="2" class="form_title">If yes, Date of Immigration (yyyy/mm/dd):</td>
	<td width="35%" class="unnamed1">&nbsp; </td>
	</tr>
	<tr valign="middle" align="left"> 
		<td colspan="2" class="unnamed1"> 
			<input name="consultant_immigration_date" type="text" class="datepicker" 
					value="<?php if (isset($consultant_immigration_date) && !empty($consultant_immigration_date)) echo $consultant_immigration_date; ?>" 
					size="12" maxlength="12">
		</td>
		<td width="35%" class="unnamed1">&nbsp;</td>
	</tr>
</table>

<hr size="1" align="center">      

<table width="100%" border="0">
	<?php 
	if (isset($consultant_consent_form)){ ?>
		<tr valign="bottom" align="left"> 
			<td colspan=3 class="form_title">
				Uploaded Consent Form: 
			</td>
		</tr>
		<tr>
			<td class=unnamed1 width=33%> <i>File Name:</i> <br><a href="download/consultant/<?php echo $consultant_id; ?>" target="_blank"><?php echo $consultant_file_name; ?></a> </td> 
			<td class=unnamed1 width=33%> <i>File Type: </i><br><?php echo $consultant_file_type; ?> </td> 
			<td class=unnamed1 width=33%> <i>File Size:</i> <br><?php echo $consultant_file_size; ?> Bytes </td>
		</tr>
	<?php 
	} 
	?>
	<tr valign="bottom" align="left"> 
		<td colspan=3 class="form_title">
			<?php 
			if (isset($consultant_consent_form)){ 
				echo "Upload a new consent form to replace the existing one:";
			}else{
				echo "Upload Consent Form: <font color=#FF0000>(required for grade)</font> :"; 
			}
			?>
		</td>
	</tr>
	<tr valign="bottom" align="left"> 
		<td width=62% height=21 class=unnamed1 colspan=3>
			<input name="consultant_file" type="file">
		</td> 
			<td width="35%" class="unnamed1">&nbsp; </td>
	</tr>
</table>  

<hr size="1" align="center">  

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr> 
		<td style="text-align: center;"> 
			<input type="submit" value="Submit" class="unnamed1 btnSubmit">&nbsp;&nbsp;&nbsp;<a href="dashboard/<?php echo isset($data[1]) ? $data[1] : "";?>">Cancel</a>&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
</table>

</form>
</td>
</tr>
</table>