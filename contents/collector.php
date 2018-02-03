<?php

global $user;

if (!$user->auth) { exit('Please login first.'); }

if (isset($data[0]) && !empty($data[0])) {
	$fields = get_record('collector',$data[0]);
	if (count($fields) == 0) {
		echo 'Invalid URL';
		return false;
	}
	foreach ($fields as $k => $v) $$k = $v;
}
?>


<h3>COLLECTOR PROFILE</h3>
<table class="formStyle" cellspacing=3px>
	<tr>
		<td height="600">
			<form name="form1" method="post" action="handler/collector/<?php if (isset($data[0])) echo $data[0]; ?>">
				<input name="collector_sid" type="hidden" value="<?php echo isset($collector_sid) ? $collector_sid : ""; ?>">
				<?php if (!$user->is_user()) { ?>
					<table width="100%">
						<tr>
							<td style="font-size: 18px; padding: 10px 10px; vertical-align: middle;">Passcode: <input type="passcode" name="passcode"><hr></td>
						</tr>
					</table>
				<?php 
				} ?>
				<table border="0">
					<tr>
						<td class="form_title">Last Name: <font color="#FF0000">*</font> </td>
						<td class="form_title">First Name: <font color="#FF0000">*</font> </td>
						<td class="form_title">Initial: </td>
					</tr>
					<tr>
						<td><input name="collector_last_name" type="text" value="<?php echo isset($collector_last_name) ? $collector_last_name : ""; ?>" size="20" maxlength="20"></td>
						<td><input name="collector_first_name" type="text" value="<?php  echo isset($collector_first_name) ? $collector_first_name : ""; ?>" size="20" maxlength="20"></td>
						<td><input name="collector_initial" type="text" value="<?php echo isset($collector_initial) ? $collector_initial : ""; ?>" size="3" maxlength="2"> </td>
					</tr>
				</table>

				<hr size="1" align="center">

				<table border="0">
					<tr>
						<td class="form_title">Email Address: <font color="#FF0000">*</font></td>
					</tr>
					<tr>
						<td><input name="collector_email" type="text" value="<?php echo isset($collector_email) ? $collector_email : ""; ?>" size="30" maxlength="50"></td>
					</tr>
				</table>

				<hr size="1" align="center">

				<table border="0">
					<tr>
						<td class="form_title">Street Address:</td>
						<td class="form_title">City:</td>
						<td class="form_title">State/Province:</td>
					</tr>
					<tr>
						<td> <input name="collector_street" type="text" id="collector_street" value="<?php echo isset($collector_street) ? $collector_street: ""; ?>" size="19" maxlength="50"></td>
						<td> <input name="collector_city" type="text" id="collector_city" value="<?php echo isset($collector_city) ? $collector_city : ""; ?>" size="15" maxlength="30"></td>
						<td> <input name="collector_state" type="text" id="collector_state" value="<?php echo isset($collector_state) ? $collector_state : ""; ?>" size="3" maxlength="20"></td>
					</tr>
					<tr>
						<td class="form_title">Zip/Postal Code:</td>
						<td class="form_title">Country:</td>
						<td class="form_title">&nbsp;</td>
					</tr>
					<tr>
						<td class="unnamed1">
							<input name="collector_zipcode" type="text" id="collector_zipcode" value="<?php echo isset($collector_zipcode) ? $collector_zipcode : ""; ?>" size="19" maxlength="20">
						</td>
						<td class="unnamed1">
							<input name="collector_country" type="text" id="collector_country" value="<?php echo isset($collector_country) ? $collector_country : ""; ?>" size="15" maxlength="20">
						</td>
						<td class="unnamed1">&nbsp;</td>
					</tr>
				</table>

				<hr size="1" align="center">

				<table border="0">
					<tr>
						<td class="form_title">DOB (yyyy/mm/dd): <font color="#FF0000">*</font></td>
						<td class="form_title">Age: </td>
						<td class="form_title">&nbsp;</td>
					</tr>
					<tr>
						<td>
							<input name="collector_dob" type="text" id="datepicker" value="<?php if (isset($collector_dob) && !empty($collector_dob)) echo $collector_dob; ?>" size="12" maxlength="12">
						</td>
						<td>
							<input name="collector_age" type="text" id="collector_age" value="<?php echo isset($collector_age) ? $collector_age : "" ?>" size="5" maxlength="3">
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td width="38%" height="5" class="form_title">Gender: <font color="#FF0000">*</font></td>
						<td width="25%" height="5" class="form_title">Marital status:<font color="#FF0000">*</font> </td>
						<td width="37%" height="5" class="form_title">Occupation: <font color="#FF0000">*</font></td>
					</tr>
					<tr>
						<td width="38%" height="18" class="unnamed1">
							<select name="collector_gender" size="1" class="unnamed1" id="collector_gender">
								<option value="M" <?php if (!isset($collector_gender) || trim($collector_gender) == "M") echo "selected"; ?> >Male</option>
								<option value="F" <?php if (isset($collector_gender) && trim($collector_gender) == "F") echo "selected"; ?> >Female</option>
								<option value="O" <?php if (isset($collector_gender) && trim($collector_gender) == "O") echo "selected"; ?> >Other</option>
							</select>
						</td>
						<td width="25%" height="18" class="unnamed1">
							<input name="collector_marital_status" type="text" id="collector_marital_status"
								value="<?php echo isset($collector_marital_status) ? $collector_marital_status : ""; ?>" size="15" maxlength="10">
						</td>
						<td width="37%" height="18" class="unnamed1">
							<input name="collector_occupation" type="text" id="collector_occupation"
								value="<?php echo isset($collector_occupation) ? $collector_occupation : ""; ?>" size="30" maxlength="30">
						</td>
					</tr>
				</table>

				<hr size="1" align="center">

				<table width="100%" border="0">
					<tr>
						<td width="30%" height="16" class="form_title">Educational Level : </td>
						<td width="35%" height="16" class="form_title">&nbsp; </td>
						<td width="35%" height="16" class="form_title">&nbsp; </td>
					</tr>
					<tr>
						<td class="unnamed1">
							<input type="radio" name="collector_edu_level" value="No Schooling"
								<?php if (isset($collector_edu_level) && trim($collector_edu_level) == "No Schooling") echo "checked"; ?> > No Schooling
						</td>
						<td class="unnamed1">
							<input type="radio" name="collector_edu_level" value="Elementary School"
								<?php if (isset($collector_edu_level) && trim($collector_edu_level) == "Elementary School") echo "checked"; ?> > Elementary School
						</td>
						<td class="unnamed1">
							<input type="radio" name="collector_edu_level" value="Middle School"
								<?php if (isset($collector_edu_level) && trim($collector_edu_level) == "Middle School") echo "checked"; ?> > Middle School
						</td>
					<tr>
						<td class="unnamed1">
							<input type="radio" name="collector_edu_level" value="High School"
								<?php if (isset($collector_edu_level) && trim($collector_edu_level) == "High School") echo "checked"; ?> > High School
						</td>
						<td class="unnamed1">
							<input type="radio" name="collector_edu_level" value="College"
								<?php if (isset($collector_edu_level) && trim($collector_edu_level) == "College") echo "checked"; ?> > College
						</td>
						<td class="unnamed1">
							<input type="radio" name="collector_edu_level" value="Professional Degree"
								<?php if (isset($collector_edu_level) && trim($collector_edu_level) == "Professional Degree") echo "checked"; ?> > Professional Degree
						</td>
					</tr>
					<tr>
						<td class="unnamed1">
							<input type="radio" name="collector_edu_level" value="Graduate Degree"
								<?php if (isset($collector_edu_level) && trim($collector_edu_level) == "Graduate Degree") echo "checked"; ?> > Graduate Degree
						</td>
						<td class="unnamed1">&nbsp;</td>
						<td class="unnamed1">&nbsp;</td>
					</tr>
				</table>

				<hr size="1" align="center">

				<table width="100%" border="0" height="186">
					<tr>
						<td class="form_title">Heritage: <font color="#FF0000">*</font></td>
						<td class="form_title">&nbsp;</td>
						<td class="form_title">&nbsp;</td>
						<td class="form_title">&nbsp; </td>
					</tr>
					<tr>
						<td width="26%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_korean" value="Korean" 
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Korean")) echo "checked";?> > Korean
						</td>
						<td width="24%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_japanese" value="Japanese"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Japanese")) echo "checked"; ?> > Japanese
						</td>
						<td width="22%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_chinese" value="Chinese"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Chinese")) echo "checked"; ?> > Chinese
						</td>
						<td width="28%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_vietnamese" value="Vietnamese"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Vietnamese")) echo "checked"; ?> > Vietnamese
						</td>
					</tr>
					<tr>
						<td width="26%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_khmer" value="Khmer"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Khmer")) echo "checked"; ?> > Khmer
						</td>
						<td width="24%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_lao" value="Lao"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Lao")) echo "checked"; ?> > Lao
						</td>
						<td width="22%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_thai" value="Thai"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Thai")) echo "checked"; ?> > Thai
						</td>
						<td width="28%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_philippino" value="Philippino"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Philippino")) echo "checked"; ?> > Philippino 
						</td>
					</tr>
					<tr>
						<td width="26%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_taiwanese" value="Taiwanese"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Taiwanese")) echo "checked"; ?> > Taiwanese
						</td>
						<td width="24%" height="13" class="unnamed1"> 
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_burmese" value="Burmese"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Burmese")) echo "checked"; ?> > Burmese
						</td>
						<td width="22%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_malay" value="Malay"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Malay")) echo "checked"; ?> >Malay
						</td>
						<td width="28%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_indonesian" value="Indonesian"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Indonesian")) echo "checked"; ?> > Indonesian
						</td>
					</tr>
					<tr>
						<td width="26%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_native_american" value="Native American"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Native American")) echo "checked"; ?> > Native American
						</td>
						<td width="24%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_latino" value="Latino"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Latino")) echo "checked"; ?> > Latino
						</td>
						<td width="22%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_european" value="European"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "European")) echo "checked"; ?> > European
						</td>
						<td width="28%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_african_american" value="African American" 
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "African American")) echo "checked"; ?> > African American
						</td>
					</tr>
					<tr>
						<td width="26%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_south_asian" value="South Asian"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "South Asian")) echo "checked"; ?> > South Asian
						</td>
						<td width="24%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_arabic" value="Arabic"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Arabic")) echo "checked"; ?> > Arabic
						</td>
						<td width="22%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_north_african" value="North African"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "North African")) echo "checked"; ?> > North African
						</td>
						<td width="28%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_central_african" value="Central African"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Central African")) echo "checked"; ?> > Central African
						</td>
					</tr>
					<tr>
						<td width="26%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_south_african" value="South African"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "South African")) echo "checked"; ?> > South African
						</td>
						<td width="24%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_pacific_islander" value="Pacific Islander"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Pacific Islander")) echo "checked"; ?> > Pacific Islander
						</td>
						<td width="22%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_central_asian" value="Central Asian"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Central Asian")) echo "checked"; ?> > Central Asian
						</td>
						<td width="28%" height="13" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_central_american" value="Central American"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "Central American")) echo "checked"; ?> > Central American
						</td>
					</tr>
					<tr>
						<td width="26%" height="4" class="unnamed1">
							<input name="collector_heritage[]" type="checkbox" id="collector_heritage_south_american" value="South American"
								<?php if (isset($collector_heritage) && strstr($collector_heritage, "South American")) echo "checked"; ?> > South American
						</td>
						<td width="24%" height="13" class="unnamed1">&nbsp;</td>
						<td width="22%" height="13" class="unnamed1">&nbsp;</td>
						<td width="28%" height="13" class="unnamed1">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" height="1" class="form_title">Languages spoken: <font color="#FF0000">*</font></td>
						<td width="24%" height="1" class="form_title">&nbsp;</td>
						<td width="21%" height="1" class="form_title">&nbsp;</td>
					</tr>
					<tr>
						<td height="1" colspan="2" class="unnamed1">
							<input name="collector_language" type="text" id="collector_language"
								value="<?php echo isset($collector_language) ? $collector_language : ""; ?>" size="40" maxlength="50">
						</td>
						<td width="24%" height="1" class="unnamed1">&nbsp;</td>
						<td width="21%" height="1" class="unnamed1">&nbsp;</td>
					</tr>
				</table>

				<hr size="1" align="center">

				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td class="unnamed1" style="text-align: center;">
							<input type="submit" class="unnamed1 btnSubmit" value="Submit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="dashboard/<?php echo isset($data[0]) ? $data[0] : "";?>">Cancel</a>
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
</table>