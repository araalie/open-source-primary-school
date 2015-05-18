<h4>
Student Registration
</h4>
<?= form_open('enrollment/edit', array('id' => 'edit_student', 'class' => 'form-horizontal edit-form')); ?>
<fieldset>
<legend>Edit Student</legend>
<ul id="tab" class="nav nav-tabs">
<li class="active"><a href="#home" data-toggle="tab">Personal Details</a></li>
<li><a href="#profile" data-toggle="tab">Enrollment Details</a></li>
<li><a href="#page3" data-toggle="tab">Address &amp; Other Info</a></li>
<li><a href="#page4" data-toggle="tab">Parents/Guardians</a></li>
<li><a href="#entry-interview" data-toggle="tab">Entry Interview</a></li>
</ul>
<div class="tab-content">
<div class="tab-pane fade in active" id="home">
<div class="control-group">
<input type="hidden" name="student_id" id="student_id"  value="<?= valueOrBlank($student_id); ?>" />
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
<input class="input-xlarge span2" name="other_names" type="text" placeholder="Other names" value="<?= valueOrBlank($other_names); ?>" />
</div>
</div>
<div class="control-group">
<label class="control-label" for="student_gender">Gender &nbsp; </label>
<div class="controls">
<?=form_dropdown('student_gender', $genders, valueOrBlank($student_gender), 
'  id="student_gender" class="{required:true} input-xlarge span2" '); ?>
</div>
</div>
<div class="control-group">
<label class="control-label" for="dob">Date of Birth</label>
<div class="controls">
<input class="{required:true} span2" type="text" name="dob" id="dob" value="<?= valueOrBlank($dob); ?>" />
</div>
</div>
</div>
<div class="tab-pane fade" id="profile">
<div class="row-fluid">
<div class="span6">
<div class="control-group">
<label class="control-label" for="student_house">Class</label>
<div class="controls">
<?=form_dropdown('classi_id', $current_classes, valueOrBlank($classi_id), 'id="classi_id" class="{required:true} input-xlarge" '); ?>
</div>
</div>
<div class="control-group">
<label class="control-label" for="status">Fees Profile</label>
<div class="controls">
<?=form_dropdown('fees_profile', $fees_profiles, valueOrBlank($fees_profile_id), '  id="fees_profile" class="{required:true} input-xlarge " '); ?>
</div>
</div>
<div class="control-group">
<label class="control-label" for="status">Status</label>
<div class="controls">
<?=form_dropdown('student_status', $statuses, valueOrBlank($status_id), '  id="status" class="input-xlarge " '); ?>
</div>
</div>
</div>
<div class="span6">
<div class="control-group">
<label class="control-label" for="student_house">House</label>
<span class="name-display"><?= valueOrBlank($house_name); ?></span>
</div>
<div class="control-group">
<label class="control-label" for="year_start"> Year Enrolled </label>
<div class="controls">
<?=form_dropdown('year_enrolled', $years_enrollable, valueOrBlank($year_enrolled), '  name="year_enrolled" id="year_enrolled" class="{required:true} " '); ?>
</div>
</div>
<div class="control-group">
<label class="control-label" for="year_end"> Year of Left </label>
<div class="controls">
<?=form_dropdown('year_end', $years_enrollable, valueOrBlank($year_left), '  id="year_end" class="" '); ?>
</div>
</div>
</div>
</div>
</div>
<div class="tab-pane fade" id="page3">
<div class="row-fluid">
<div class="span4">
<div class="control-group">
<label class="control-label" for="telephone">Telephone</label>
<div class="controls">
<input class="input-xlarge" name="telephone" type="text" placeholder="Enter Telephone" value="<?= valueOrBlank($tel); ?>">
</div>
</div>
<div class="control-group">
<label class="control-label" for="email">Email</label>
<div class="controls">
<input class="input-xlarge" name="student_email" type="text" placeholder="Email Address"  
value="<?= valueOrBlank($email); ?>" style="text-transform:none;" >
</div>
</div>	  
</div>
<div class="span4">
<div class="control-group">
<label class="control-label">
Home Address
</label>
<div class="controls">
<textarea class="input-medium" rows="3"  name='student_address'><?= valueOrBlank($student_address); ?></textarea>
</div>
</div>
</div>
</div>
</div>
<div class="tab-pane fade" id="page4">
<p>
<a class="btn" href="#"><i class="icon-user"></i> Add Parent</a>
</p>
</div>
<div class="tab-pane fade-in active" id="entry-interview">
Results
</div>
</div>
<div id="form-errors"></div>
</fieldset>
<?=anchor('enrollment/', 'Register New Student', 'class="btn btn-warning margin-right-1" title="Register a new student"');?>
<input type="submit" class="btn btn-primary submit" name="save_student" value="Save/Update Student" /> 
</form>
<br/>
<table class="table table-striped table-bordered table-condensed zeb">
<thead>
<tr>
<th>
#
</th>
<th>
First Name
</th>
<th>
Surname
</th>
<th>
Gender
</th>
<th>
Unique ID
</th>
<th>
En. Year
</th>
<th>
House
</th>
<th>
Edit
</th>
</tr>
</thead>
<tbody>
<?php
//var_dump($students);

if(isset($students)){
	$i=1;
	
	foreach($students as $s){
	$house='N/A';
	if($s->getHouse()){
		$house = $s->getHouse()->getName();
	}
		echo '<tr><td>'.$i.'</td><td>'.$s->getSurname().'</td><td>'.$s->getFirstName()
			.'</td><td>'
			.$s->getGender()
			.'</td><td>'
			.$s->getStudentNumber()
			.'</td><td>'
			.$s->getYearEnrolled()
			.'</td><td>'
			.$house
			.'</td><td>'
			.anchor('enrollment/edit/'.$s->getId(), 
			'Edit <i class="small1"> '.$s->getFirstName().' '.$s->getSurname().'</i>', 'title="Edit '.$s->getSurname().'"').'</td></tr>';
		$i++;
	}
}

?>
</tbody>
</table>
<script type="text/javascript">
$.validator.setDefaults({ ignore: '' });
$(document).ready(function () {
	$('#tab').tab('show');
	
	$.Zebra_DatePicker('#dob', {
		format: 'd-M-Y',
		direction: false
		});
	
	$("#edit_student").validate({
		errorLabelContainer: $("#form-errors"),
		messages: {
			first_name: {
				required: "Please enter student's First Names",
				minlenght: "First Name must have at least 2 letters."
				},
			year_enrolled:"Year of enrollment is mandatory",
			student_gender:"Gender is mandatory",
			surname:"Surname is required",
			fees_profile:"Student's Fees Profile is compulsary",
			
		}
		});
	
	});
</script>
