<h4>
Examinations Management
</h4>
<hr/><br/>

<div class="row-fluid">
<div class="span5">
<div class="box">
<h4 class="box-header">Create Exam</h4>
<div class="box-content">
<?= form_open('exam_management/edit', array('class' => 'form-horizontal edit-form')); ?>
<input type="hidden" name="exam_id"  value="<?= valueOrBlank($exam_id); ?>" />
<div class="control-group">
<label>Name</label>
<div>
<input class="{required:true,minlength:3} input-xlarge span3" name="exam_name" type="text" placeholder="Exam Name"  
value="<?= valueOrBlank($exam_name); ?>">
</div>
</div>
<div class="control-group">
<label  for="description">Remarks</label>
<div>
<textarea name="description" rows="2"></textarea>
</div>
</div>
<?php 
if(isset($classes) && is_array($classes)){
$i=1;
echo '<table class="table table-striped table-bordered table-condensed zeb">'
	 .'<caption>Select Classes Taking this exam</caption>'
	 .'<thead><tr><th>#</th><th></th><th>Class</th></tr></thead>';
	foreach($classes as $k => $v){
		$chkBox = '<tr><td>'.$i.'</td><td><input name="class-choices[]" class="class-choices" type="checkbox" value="'.$k.'" /></td><td>'
		.$v.'</td></tr>';
		echo $chkBox;
		$i++;
	}
echo '</table>';
}

?>
<input type='hidden' id='feedback' name='feedback' value="<?= valueOrBlank($feedback); ?>"/>
<?=anchor('exam_management/', 'Create New Scheme', 'class="btn btn-warning margin-right-1" title="New Exam"');?>
<input type="submit" class="btn btn-primary submit" name="save_exam" value="Save/Update Exam" /> 
<?=form_close();?>
</div>
</div>
</div>
<div class="span6">
<div class="box">
<h4 class="box-header">All Exams</h4>
<div class="box-content">
<?php
if(isset($exams) && is_array($exams)){
echo '<ol>';
	foreach($exams as $s){
		echo '<li><b>'.$s['name'].'</b><ul>';
		foreach($s['classes'] as $r){
			echo '<li>'.$r['name'];
		}
		echo '</ul><hr/></li>';
	}
echo '</ol>';	
}

?>
</div>
</div>
</div>
</div>
<script type="text/javascript">
$.validator.setDefaults({
    ignore: ''
});

$(document).ready(function () {
    $(".edit-form").validate();
	
		if($('#feedback').val()!=''){
		DisplayPopup($('#feedback').val());	
		$('#feedback').val('');
	}

});



</script>
