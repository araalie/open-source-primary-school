<h4>
Class Activation : [Per term]
</h4>
<?= form_open('class_creation/edit', array('id' => 'edit_class', 'class' => 'form-horizontal edit-form')); ?>
<fieldset>
<legend>Class Details</legend>
<div class="row-fluid">
<div class="span5">
<div class="control-group">
<input type="hidden" name="classi_id" id="class_id"  value="<?= valueOrBlank($classi_id); ?>" />
<label class="control-label" for="class_type_id">Class Type</label>
<div class="controls">
<?=form_dropdown('class_type_id', $class_types, valueOrBlank($class_type_id), $readonly.'  name="class_type_id" id="class_type_id" class="{required:true} span2" '); ?>
<input type='hidden' id='term_id' name='term_id' value="<?= valueOrBlank($term_id); ?>"/>
</div>
</div>

<div class="control-group">
<label class="control-label" for="term_id">Current Term</label>
<div class="controls">
<span class='name-display'><?= valueOrBlank($term_name); ?></span>
</div>
</div>

<div class="control-group">
<label class="control-label">
Class Remarks/Notes
</label>
<div class="controls">
<textarea class="input-medium" rows="3"  name='remarks'><?= valueOrBlank($remarks); ?></textarea>
</div>
</div>
<input type='hidden' id='feedback' name='feedback' value="<?= valueOrBlank($feedback); ?>"/>
<div id="form-errors"></div>
</div>
<div class="span5">NAME: <span class='name-display'><?= valueOrBlank($class_name); ?></span></div>
</div>
</fieldset>
<?=anchor('class_creation/', 'Activate New Class', 'class="btn btn-warning margin-right-1" title="Activate a new class"');?>
<input type="submit" class="btn btn-primary submit" name="save_classi" value="Save/Update Class" /> 
</form>
<br/>
<table class="table table-striped table-bordered table-condensed zeb">
<thead>
<tr>
<th>
#
</th>
<th>
Class Name
</th>
<th>
STATUS
</th>
<th>
Remarks/Comments
</th>
<th>
Edit
</th>
</tr>
</thead>
<tbody>
<?

if(isset($class_list)){
	$i=1;
	foreach($class_list as $c){
	
	$status='';
	
	if(!is_null($c->getClassInstanceStatus())){
		$status =$c->getClassInstanceStatus()->getName();
	}
		echo '<tr><td>'.$i.'</td><td>'.$c->getName().'</td><td>'
			.$status
			.'</td><td>'
			.$c->getDescription()
			.'</td><td>'
			.anchor('class_creation/edit/'.$c->getId(), 
			'Edit <i class="small1"> '.$c->getName().'</i>', 'title="Edit class remarks '.$c->getName().'"').'</td></tr>';
		$i++;
	}
}

?>
</tbody>
</table>
<script type="text/javascript">

$(document).ready(function () {
	
	$("#edit_class").validate({
		errorLabelContainer: $("#form-errors"),
		messages: {
			year_name: {
				required: "Please enter a unique name for this academic year",
				minlenght: "The name must have at least 2 letters."
				}
		}
		});
	
	if($('#feedback').val()!=''){
		DisplayPopup($('#feedback').val());	
		$('#feedback').val('');
	}
	

	});
</script>
