<?php
set_time_limit(18000);

$user = check_auth();

if (isset($data[0])) {
    $fields = get_record('data',$data[0]);
    if (count($fields) == 0) {
        echo 'Invalid URL';
        return false;
    }
    foreach ($fields as $k => $v) ${$k} = $v;
}

?>
<h3>FIELD DATA</h3>
      <table class="formStyle">
        <tr> 
          <td align="left" valign="top" height="336"> 
            <form name="form1" enctype="multipart/form-data" method="post" action="handler/data/<?php echo $data[0]; ?>/<?php echo $data[1];?>/<?php echo $data[2];?>">
              <input name="data_id" type="hidden" value="<?php echo $data_id; ?>">
              <table width="100%" border="0" height="254">
                <tr valign="bottom" align="left"> 
                  <td colspan="2" height="10" class="form_title">Title of project: 
                    <font color="#FF0000">*</font> </td>
                  <td width="119" height="10" class="form_title"><b> </b></td>
                </tr>
                <tr valign="top" align="left"> 
                  <td colspan="2" height="27"> <input name="data_project_title" type="text" value="<?php echo $data_project_title; ?>" size="40" maxlength="50"></td>
                  <td width="243" height="27"><b> </b></td>
                </tr>
                <tr valign="middle" align="left"> 
                  <td colspan="2" height="10" class="form_title">Context: <font color="#FF0000">*</font></td>
                  <td width="243" height="10" class="form_title">Main Consultant:</td>
                </tr>
                <?php  
					$collector_id = isset($data[1])?$data[1]:$collector_id;
                    if (isset($collector_id)) $context = get_records('context', $collector_id);
                    else $context = get_records('context', $user->get('id'));

                    foreach ($context as $row)
                    {
                ?>
                <tr valign="middle" align="left" class="contextset"> 
                    <td colspan="2" height="2"> 
                        &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="context_id" value="<?php echo $row['context_id']; ?>" <?php if ($context_id == $row['context_id']) echo "checked"; ?>><?php echo $row['context_event_name']; ?>
                    </td>
                    <td height="2" width="243">
                        <select name="consultant_id">
                        <option value="-1">None</option>
                        <?php
                            $sql = 'select * from consultant where consultant_id in ('.$row['context_consultants'].')';
                            $result = mysql_query($sql);
                            while($result && $rec = mysql_fetch_assoc($result))
                            {
                        ?>
                        <option value="<?php echo $rec['consultant_id']; ?>" <?php if ( ($context_id == $row['context_id']) && ($rec['consultant_id']==$consultant_id) ) echo "selected"; ?> ><?php echo $rec['consultant_first_name'],' ',$rec['consultant_last_name']; ?></option>
                        <?php
                            }
                        ?>
                        </select> 
                    </td>
                </tr>
                <?php
                    } 
                ?>
                <tr valign="middle" align="left">
                </tr>
                <tr valign="middle" align="left"> 
                  <td width="80" height="18" class="form_title" valign="top"> 
                    Collection Media:</td>
                  <td width="119" height="18" class="form_title" valign="top"> 
                    Data Description: <font color="#FF0000">*</font></td>
                  <td width="243" height="18">&nbsp;</td>
                </tr>
                <tr valign="middle" align="left"> 
                  <td height="126" class="form_title" valign="top"> 
                  <table width="100%" border="0">
                      <tr> 
                        <td>&nbsp;</td>
                        <td> <input type="radio" name="data_type" value="Fieldnotes" <?php if (trim($data_type) == "Fieldnotes") echo "checked"; ?> >
                          Fieldnotes</td>
                      </tr>
                      <tr> 
                        <td>&nbsp;</td>
                        <td> <input type="radio" name="data_type" value="Images" <?php if (trim($data_type) == "Images") echo "checked"; ?> >
                          Images</td>
                      </tr>
                      <tr> 
                        <td>&nbsp;</td>
                        <td> <input type="radio" name="data_type" value="Video" <?php if (trim($data_type) == "Video") echo "checked"; ?> >
                          Video</td>
                      </tr>
                      <tr> 
                        <td>&nbsp;</td>
                        <td> <input type="radio" name="data_type" value="Audio" <?php if (trim($data_type) == "Audio") echo "checked"; ?> >
                          Audio</td>
                      </tr>
                    </table>
                    </td>
                  <td colspan="2" height="126" valign="top" align="left"> 
                    <textarea name="data_description" wrap="VIRTUAL" cols="50" rows="10"><?php echo $data_description; ?></textarea> 
                  </td>
                </tr>
              </table>
              <hr size="1" align="center">
				<table width="100%" border="0">
				<?php 
				if ($data_file){
				?>
				<tr valign="bottom" align="left"> 
				<td colspan=3 class="form_title">
				Uploaded Media File:
				</td>
				</tr>
				<tr>
				 <td height=1 width=33%><i> File Name: </i><br><a href="download/data/<?php echo $data_id; ?>" target="_blank"><?php echo $data_file_name; ?></a> </td> 
				 <td height=1 width=33%><i> File Type:</i> <br><?php echo $data_file_type; ?> </td> 
				 <td height=1 width=33%> <i>File Size: </i><br><?php echo format_file_size($data_file_size);?></td>
				<?php 
				} 
				?>
				</tr>
				<tr valign="bottom" align="left"> 
				<td colspan=3 class="form_title">
				<?php if ($data_file){
				?>
				Upload a new media file to replace the existing one:
				<?php
				}else{
				?>
				Upload Media File (<15MB):
				<?php
				}
				?>
				</td>
				</tr>
				<tr valign="bottom" align="left"> 
				<td colspan="3" height=1 class=unnamed1>
				<input name="upload_file" type="file">
				</td> 
				<td width="35%" class="unnamed1">&nbsp; </td>
				</tr>
				</table> 
               <hr size="1" align="center">          
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr> 
                  <td style="text-align: center;"> 
                    <input type="submit" value="Submit" class="unnamed1 btnSubmit">&nbsp;&nbsp;&nbsp;
                    <a href="dashboard/<?php echo $data[1];?>">Cancel</a>&nbsp;&nbsp;&nbsp;
                    <?php if ($action == "edit") { ?>
                    <input type="submit" name="Delete" value="Delete">&nbsp;&nbsp;&nbsp;
                    <?php } ?>
                  </td>
                </tr>
              </table>
            </form>
          </td>
        </tr>
      </table>
<script>
$(function(){
	$('.contextset select').each(function(){
		var val = $(this).val();
		if (val == -1) $(this).removeAttr('name');
		else $(this).attr('name','consultant_id');
	}); 
	$('.contextset select').change(function(){
		var val = $(this).val();
		if (val == -1) $(this).removeAttr('name');
		else $(this).attr('name','consultant_id');
	}); 
});
</script>