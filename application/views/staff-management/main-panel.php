<h4>
Manage Staff
</h4>
<?= form_open('staff_management/edit', array('id' => 'edit_staff', 'class' => 'form-horizontal edit-form')); ?>
<fieldset>
<legend>Create/Edit Staff Member</legend>
<ul id="tab" class="nav nav-tabs">
<li class="active"><a href="#home" data-toggle="tab">Personal Details</a></li>
<li><a href="#profile" data-toggle="tab">Employment Details</a></li>
<li><a href="#dropdown" data-toggle="tab">Address &amp; Other Info</a></li>
</ul>
<div id="myTabContent" class="tab-content">
<div class="tab-pane fade in active" id="home">
<div class="control-group">
<input type="hidden" name="staff_id" id="staff_id"  value="<?= valueOrBlank($staff_id); ?>" />
<label class="control-label" for="staff_title">Title &nbsp; </label>
<div class="controls">
<?=form_dropdown('staff_title', $title_list, valueOrBlank($staff_title_id), '  id="staff_title" class="{required:true} input-xlarge span2" '); ?>
</div>
</div>
<div class="control-group">
<label class="control-label" for="firstname">First Name</label>
<div class="controls">
<input class="{required:true,minlength:2} input-xlarge span2" name="first_name" type="text" placeholder="First name" value="<?= valueOrBlank($first_name); ?>">
</div>
</div>
<div class="control-group">
<label class="control-label" for="surname">Surname</label>
<div class="controls">
<input class="{required:true,minlength:2} input-xlarge span2" name="surname" type="text" placeholder="surname" value="<?= valueOrBlank($surname); ?>">
</div>
</div>
<div class="control-group">
<label class="control-label" for="other_names">Other Names</label>
<div class="controls">
<input class="input-xlarge span2" name="other_names" type="text" placeholder="Other names" value="<?= valueOrBlank($other_names); ?>">
</div>
</div>
<div class="control-group">
<label class="control-label" for="dob">Date of Birth</label>
<div class="controls">
<input class="span2" type="text" name="dob" id="dob" value="<?= valueOrBlank($dob); ?>">
</div>
</div>
<div class="control-group">
<label class="control-label" for="staff_gender">Gender &nbsp; </label>
<div class="controls">
<?=form_dropdown('staff_gender', $genders, valueOrBlank($staff_gender), 
'  id="staff_gender" class="{required:true} input-xlarge span2" '); ?>
</div>
</div>
</div>
<div class="tab-pane fade" id="profile">
<div class="control-group">
<label class="control-label" for="staff_job_title">Job/Position </label>
<div class="controls">
<?=form_dropdown('staff_job_title', $job_title_list, valueOrBlank($job_title_id), '  id="staff_job_title" class="input-xlarge span2" '); ?>
</div>
</div>
<div class="control-group">
<label class="control-label" for="year_start"> Year of Joined </label>
<div class="controls">
<?=form_dropdown('year_start', $years, valueOrBlank($year_start), '  name="year_start" class="span2" '); ?>
</div>
</div>
<div class="control-group">
<label class="control-label" for="year_end"> Year of Left </label>
<div class="controls">
<?=form_dropdown('year_end', $years, valueOrBlank($year_end), '  id="year_end" class="span2" '); ?>
</div>
</div>
</div>
<div class="tab-pane fade" id="dropdown">
<div class="row-fluid">
<div class="span4">
<div class="control-group">
<label class="control-label" for="telephone1">Telephone #1</label>
<div class="controls">
<input class="input-xlarge span2" name="telephone1" type="text" placeholder="Enter Telephone 1" value="<?= valueOrBlank($tel1); ?>">
</div>
</div>
<div class="control-group">
<label class="control-label" for="telephone2">Telephone #2</label>
<div class="controls">
<input class="input-xlarge span2" name="telephone2" type="text" placeholder="Enter Telephone 2" value="<?= valueOrBlank($tel2); ?>">
</div>
</div>
<div class="control-group">
<label class="control-label" for="email">Email</label>
<div class="controls">
<input class="input-xlarge span2" name="staff_email" type="text" placeholder="Email Address"  value="<?= valueOrBlank($email); ?>" style="text-transform:none;" >
</div>
</div>	  
</div>
<div class="span4">
<div class="control-group">
<label class="control-label">
Address
</label>
<div class="controls">
<textarea class="input-medium" rows="3"  name='staff_address'><?= valueOrBlank($staff_address); ?></textarea>
</div>
</div>
<div class="control-group">
<label class="control-label">
Remarks
</label>
<div class="controls">
<textarea class="input-medium" rows="3"  name='staff_remarks'><?= valueOrBlank($staff_remarks); ?></textarea>
</div>
</div>
</div>
</div>


</div>
</div>
<div id="form-errors"></div>
</fieldset>
<?=anchor('staff_management/', 'Register New Staff Member', 'class="btn btn-warning margin-right-1" title="Register a new member of staff"');?>
<input type="submit" class="btn btn-primary submit" name="save_staff" value="Save/Update Member" /> 
</form>
<br/>
<table class="table table-striped table-bordered table-condensed zeb">
<thead>
<tr>
<th>
#
</th>
<th>
Title
</th>
<th>
Surname
</th>
<th>
First Name
</th>
<th>
Edit
</th>
</tr>
</thead>
<tbody>
<?
if(isset($staffs)){
	$i=1;
	foreach($staffs as $s){
	$title='';
	if($s->getTitle()){
		$title = $s->getTitle()->getName();
	}
		echo '<tr><td>'.$i.'</td><td>'.$title.'</td><td>'.$s->getSurname().'</td><td>'.$s->getFirstName()
			.'</td><td>'
			.anchor('staff_management/edit/'.$s->getId(), 'Edit <i class="small1">'.$s->getTitle()->getName().'. '.$s->getSurname().'</i>', 'title="Edit '.$s->getTitle()->getName().'. '.$s->getSurname().'"').'</td></tr>';
		$i++;
	}
}

?>
</tbody>
</table>
<script type="text/javascript">
$(document).ready(function () {
	$('#tab').tab('show');
	
	$.Zebra_DatePicker('#dob', {
		format: 'd-M-Y',
		direction: false
		});
	
	$("#edit_staff").validate({
		errorLabelContainer: $("#form-errors")
		});
	
	if($('#feedback').val()!=''){
		DisplayPopup($('#feedback').val());	
		$('#feedback').val('');
	}
	
	});
</script>
