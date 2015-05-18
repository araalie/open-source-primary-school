<h4 class="section-title">
Assign Students to Classes
</h4>
<div class="panel">
<h5 class="section-title2">Find Students</h5>
<?= form_open('student_class_assignment', array('class' => 'form-horizontal edit-form')); ?>
<div class="container">
<div class="row-fluid">
<div class="span4">
<div class="control-group">
<label class="control-label" for="search_classi_id">By Class</label>
<div class="controls">
<?=form_dropdown('search_classi_id', $previous_classes, valueOrBlank($search_classi_id), 'id="search_classi_id" class="span12" '); ?>
</div>
</div>
</div>
<div class="span4">
<div class="control-group">
<label class="control-label" for="year_start">By Year Enrolled </label>
<div class="controls">
<?=form_dropdown('year_enrolled', $years_enrollable, valueOrBlank($year_enrolled), '  name="year_enrolled" id="year_enrolled" class="span10" '); ?>
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
<h5 class="section-title2">Assign to Class</h5>
<?= form_open('student_class_assignment/', array('id' => 'class_assignment', 'class' => 'form-horizontal edit-form')); ?>

<div class="control-group">
<label class="control-label" for="classi_id">Class</label>
<div class="controls">
<?=form_dropdown('classi_id', $current_classes, valueOrBlank($classi_id), '  name="classi_id" id="classi_id" class="span3" '); ?>
 <input type="submit" class="btn btn-primary submit" name="assign_to_class" value="Assign To class" /> 
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
	
	$currentClass='Not Available';
	
	$now = new DateTime();
	
	$age = $s->getDateOfBirth()->diff($now)->format('%y');

	if($s->getClassInstance()){
		$currentClass =  $s->getClassInstance()->getName();
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

