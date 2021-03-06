<h4 class="section-title">
Assign Students to Fees Profiles
</h4>
<div class="panel">
<h5 class="section-title2">Find Students</h5>
<?= form_open('student_fees_profile_management', array('class' => 'form-horizontal edit-form')); ?>
<div class="container">
<div class="row-fluid">
<div class="span4">
<div class="control-group">
<label class="control-label" for="search_fees_profile_id">By Class</label>
<div class="controls">
<?=form_dropdown('search_classi_id', $current_classes, valueOrBlank($search_classi_id), 'id="search_classi_id" class="span3" '); ?>
</div>
</div>
</div>
<div class="span4">
<div class="control-group">
<label class="control-label" for="year_start">By Year Enrolled </label>
<div class="controls">
<?=form_dropdown('year_enrolled', $years_enrollable, valueOrBlank($year_enrolled), '  name="year_enrolled" id="year_enrolled" class="span2" '); ?>
</div>
</div>
</div>
<div class="span4">
<input type="submit" class="btn btn-success submit" name="find_students" value="Search" /> 
</div>
</div>
</div>
<?=form_close();?>
</div>
<input type="hidden" name="hsearch_class_id"  value="<?= valueOrBlank($search_classi_id); ?>" />
<input type="hidden" name="hsearch_year" value="<?= valueOrBlank($search_year); ?>" />
<div class="panel">
<h5 class="section-title2">Assign to Fees Profile</h5>
<?= form_open('student_fees_profile_management/', array('id' => 'class_assignment', 'class' => 'form-horizontal edit-form')); ?>

<div class="control-group">
<label class="control-label" for="fees_profile_id">Class</label>
<div class="controls">
<?=form_dropdown('fees_profile_id', $fees_profiles, valueOrBlank($fees_profile_id), '  name="fees_profile_id" id="fees_profile_id" class="span3" '); ?>
 <input type="submit" class="btn btn-primary submit" name="assign_to_profile" value="Assign To Fees Profile" /> 
</div>
</div>

<!--table-->
<br/>
<table class="table table-striped table-bordered table-condensed zeb">
<thead>
<tr>
<th>
All: <input type="checkbox" id="select-all" />
</th>
<th>
Surname
</th>
<th>
First Name
</th>
<th>
Gender
</th>
<th>
Age (Yrs)
</th>
<th>
Unique ID
</th>
<th>
En. Year
</th>
<th>
Current Class
</th>
<th>
Profile
</th>
<th>
Edit
</th>
</tr>
</thead>
<tbody>
<?
//var_dump($students);
if(isset($students)){
	$i=1;
	foreach($students as $s){
	
	$currentClass='N/A';
	$prof='N/A';
	
	$now = new DateTime();
	
	$age = $s->getDateOfBirth()->diff($now)->format('%y');

	if($s->getClassInstance()){
		$currentClass =  Utilities::getShortClassName($s->getClassInstance()->getName());
	}
	
	if($s->getFeesProfile()){
		$prof =  $s->getFeesProfile()->getName();
	}
	
	$chkBox = '<input name="student-choices[]" class="student-choices" type="checkbox" value="'.$s->getId().'" />';
		echo '<tr><td>'.str_pad($i,3,"0",STR_PAD_LEFT).'. '
		.$chkBox.'</td><td>'
		.$s->getSurname().'</td><td>'
		.$s->getFirstName().'</td><td>'
			.$s->getGender()
			.'</td><td>'
			.$age
			.'</td><td>'
			.$s->getStudentNumber()
			.'</td><td>'
			.$s->getYearEnrolled()
			.'</td><td>'
			.$currentClass
			.'</td><td>'
			.$prof
			.'</td><td>'
			.anchor('student_management/edit/'.$s->getId(), 'Edit').'</td></tr>';
		$i++;
	}
}

?>
</tbody>
</table>
<!--end of table-->
</form>
</div>
<input type='hidden' id='feedback' name='feedback' value="<?= valueOrBlank($feedback); ?>"/>
<script type="text/javascript">
$(document).ready(function () {
	$('#select-all').click(function () {

        if (this.checked == false) {
            $('.student-choices:checked').attr('checked', false);
        }
        else {
            $('.student-choices:not(:checked)').attr('checked', true);
        }
    });	
	
	
		if($('#feedback').val()!=''){
		DisplayPopup($('#feedback').val());	
		$('#feedback').val('');
	}
	
});
</script>

