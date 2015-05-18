<h4>
Academic Year Definition
</h4>
<?= form_open('academic_years/edit', array('id' => 'edit_year', 'class' => 'form-horizontal edit-form')); ?>
<fieldset>
<legend>Year Detail</legend>

<div class="control-group">
<input type="hidden" name="year_id" id="year_id"  value="<?= valueOrBlank($year_id); ?>" />
<label class="control-label" for="year_name">Name of Year</label>
<div class="controls">
<?=form_dropdown('year_name', $academic_years, valueOrBlank($year_name), $readonly.'  name="year_name" id="year_name" class="{required:true} span2" '); ?>
</div>
</div>

<div class="control-group">
<label class="control-label" for="date_start">Start Date</label>
<div class="controls">
<input class="{required:true} span2" type="text" name="date_start" id="date_start" value="<?= valueOrBlank($date_start); ?>">
</div>
</div>

<div class="control-group">
<label class="control-label" for="date_end">End Date</label>
<div class="controls">
<input class="{required:true} span2 " type="text" name="date_end" id="date_end" value="<?= valueOrBlank($date_end); ?>">
</div>
</div>

<div class="control-group">
<label class="control-label">
Remarks/Notes
</label>
<div class="controls">
<textarea class="input-medium" rows="3"  name='remarks'><?= valueOrBlank($remarks); ?></textarea>
</div>
</div>
<input type='hidden' id='feedback' name='feedback' value="<?= valueOrBlank($feedback); ?>"/>

<div id="form-errors"></div>
</fieldset>
<?=anchor('academic_years/', 'Register New Year', 'class="btn btn-warning margin-right-1" title="Register a new year"');?>
<input type="submit" class="btn btn-primary submit" name="save_year" value="Save/Update Year" /> 
</form>
<br/>
<table class="table table-striped table-bordered table-condensed zeb">
<thead>
<tr>
<th>
#
</th>
<th>
Year
</th>
<th>
Start
</th>
<th>
End
</th>
<th>
Status
</th>
<th>
Edit
</th>
</tr>
</thead>
<tbody>
<?

if(isset($year_list)){
	$i=1;
	foreach($year_list as $y){
		echo '<tr><td>'.$i.'</td><td>'.$y->getName().'</td><td>'.$y->getDateBegan()->format('Y-M-d')
			.'</td><td>'
			.$y->getDateEnded()->format('Y-M-d')
			.'</td><td>'
			.$y->getAcademicYearStatus()->getName()
			.'</td><td>'
			.anchor('academic_years/edit/'.$y->getId(), 
			'Edit <i class="small1"> '.$y->getName().'</i>', 'title="Edit '.$y->getName().'"').'</td></tr>';
		$i++;
	}
}

?>
</tbody>
</table>
<script type="text/javascript">

$(document).ready(function () {
		
		$('#date_start').Zebra_DatePicker({format: 'd-M-Y' });
		$('#date_end').Zebra_DatePicker({format: 'd-M-Y'});
	
	$("#edit_year").validate({
		errorLabelContainer: $("#form-errors"),
		messages: {
			year_name: {
				required: "Please enter a unique name for this academic year",
				minlenght: "The name must have at least 2 letters."
				},
			date_start:"Date of start of this year is required",
			date_end:"Date of closure of the year is required"
			
		}
		});
	
	if($('#feedback').val()!=''){
		DisplayPopup($('#feedback').val());	
		$('#feedback').val('');
	}
	

	});
</script>
