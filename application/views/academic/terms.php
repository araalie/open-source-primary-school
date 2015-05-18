<h4>
School Terms Definition
</h4>
<?= form_open('academic_terms/edit', array('id' => 'edit_year', 'class' => 'form-horizontal edit-form')); ?>
<fieldset>
<legend>Term Details</legend>
<div class="row-fluid">
<div class="span5">
<div class="control-group">
<input type="hidden" name="term_id" id="term_id"  value="<?= valueOrBlank($term_id); ?>" />
<label class="control-label" for="year_id">Name of Year</label>
<div class="controls">
<?=form_dropdown('year_id', $academic_years, valueOrBlank($year_id), $readonly.'  name="year_id" id="year_id" class="{required:true} span2" '); ?>
</div>
</div>

<div class="control-group">
<label class="control-label" for="term_type_id">Type of Term</label>
<div class="controls">
<?=form_dropdown('term_type_id', $term_types, valueOrBlank($term_type_id), $readonly_term.'  name="term_type_id" id="term_type_id" class="{required:true} span2" '); ?>
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
</div>
<div class="span4">NAME: <span class='name-display'><?= valueOrBlank($term_name); ?></span></div>
</div>
</fieldset>
<?=anchor('academic_terms/', 'Register New Term', 'class="btn btn-warning margin-right-1" title="Register a new term for the year"');?>
<input type="submit" class="btn btn-primary submit" name="save_term" value="Save/Update Term" /> 
</form>
<br/>
<table class="table table-striped table-bordered table-condensed zeb">
<thead>
<tr>
<th>
#
</th>
<th>
Term
</th>
<th>
Current ?
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

if(isset($term_list)){
	$i=1;
	
	$currentTerm = Utilities::getCurrentTerm(TRUE);
	
	foreach($term_list as $t){
	$current = '<a href="#" class="btn btn-medium disabled">CLOSED</a>';
	
	if($t->getTermStatus()->getName()=='NEW')
	{
		if( $t->getDateEnded() > $currentTerm->getDateBegan()){
		
		$current = anchor('academic_terms/make_current/'.$t->getId(), 
			'Make Current', 'class="btn btn-medium btn-primary"  title="Make this the current term'.$t->getName().'"');	
		}
			
	}else if($t->getTermStatus()->getName()=='IN_PROGRESS')
	{
		$current = anchor('academic_terms/#'.$t->getId(), 
			'Current', 'class="btn btn-medium btn-success"  title="'.$t->getName().'"');
	}
		echo '<tr><td>'.$i.'</td><td>'.$t->getName()
			.'</td><td>'
			.$current
			.'</td><td>'.$t->getDateBegan()->format('D, d M, Y')
			.'</td><td>'
			.$t->getDateEnded()->format('D, d M, Y')
			.'</td><td>'
			.$t->getTermStatus()->getName()
			.'</td><td>'
			.anchor('academic_terms/edit/'.$t->getId(), 
			'Edit <i class="small1"> '.$t->getName().'</i>', 'title="Edit dates and remarks for '.$t->getName().'"').'</td></tr>';
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
