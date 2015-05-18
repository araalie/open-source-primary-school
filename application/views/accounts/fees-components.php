<h4 class="section-title">
School Fees Components
</h4>
<?php
$term = Utilities::getCurrentTerm();
if(is_null($term)){
	?>
	<div class="alert alert-error custom-error"> An Active Term must be set before proceeding.
	<br/>
	Contact the System Administrator.
	</div>
	<?php
	}else{
	
	?>
	<div class="container-fluid">
	<div class="row-fluid">
	<div class="span8">
	<?php
	if(isset($fees_components) && is_array($fees_components)){
		
		$i=1;
		?>
		
		<table class="table table-striped table-bordered table-condensed zeb">
		<thead>
		<tr>
		<th>
		#
		</th>
		<th>Component</th>
		<th>Cost Group</th>
		<th>Frequency</th>
		<th>Edit</th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach($fees_components as $f){
			
			$costing = 'N/A';
			if($f->getFeesProfile()){
			$costing = $f->getFeesProfile()->getName();
			}
			
			$freq = 'N/A';
			if($f->getFeeFrequencyType()){
			$freq = $f->getFeeFrequencyType()->getName();
			}
			echo '<tr><td>'.$i.'</td><td>'.$f->getName()
				.'</td><td>'.$costing
				.'</td><td>'.$freq
				.'</td><td>'.anchor('fees_components/edit/'.$f->getId(),'Edit', 'title="Edit '.$f->getName().'"')
				.'</td></tr>';			
			$i++;
			}		
	}
	?>
	</tbody>		
	</table>	
	</div>
	<div class="span4">
	
	<?= form_open('fees_components/edit', array('id' => 'edit_fee_component', 'class' => 'well')); ?>
	<fieldset>
	<legend>Create/Edit Component</legend>
	<label>
	Name of Component
	</label>
	<input type="hidden" name="component_id" name="component_id" value="<?= valueOrBlank($component_id); ?>"/>
	<input type="text" class="input-large {required:true,minlength:3}" placeholder="Name of Component" name="component" 
	value="<?= valueOrBlank($component); ?>">
	<div class="control-group">
	<label class="control-label" for="fees_profile_id">Cost Group</label>
	<div class="controls">
	<?=form_dropdown('fees_profile_id', $fees_profiles, valueOrBlank($fees_profile_id), '  name="fees_profile_id" id="fees_profile_id" class="input-large required" '); ?>
	</div>
	</div>

	<div class="control-group">
	<label class="control-label" for="fees_profile_id">Frequency</label>
	<div class="controls">
	<?=form_dropdown('fees_freq_id', $fees_freqs, valueOrBlank($fees_freq_id), '  name="fees_freq_id" id="fees_freq_id" class="input-large required" '); ?>
	</div>
	</div>
		
	<div class="control-group">
	<label class="control-label" for="textarea">
	Description
	</label>
	<div class="controls">
	<textarea class="input-xlarge" id="textarea" rows="3"  name='description'><?= valueOrBlank($description); ?></textarea>
	</div>
	</div>	
	<div id="form-errors"></div>
	</fieldset>
	<?=anchor('fees_components/', 'New Item', 'class="btn btn-warning margin-right-1" title="Create a new Fees Component"');?>
	<button type="submit" class="btn btn-primary" name="save_component">
	Save
	</button>	
	</form>
	
	</div>
	</div>
	</div>
	<?php
}
?>
<script type="text/javascript">

$(document).ready(function () {
	
	$("#edit_fee_component").validate({
		errorLabelContainer: $("#form-errors")
			});
	
	if ($('#feedback').val() != '') {
		DisplayPopup($('#feedback').val());
		$('#feedback').val('');
	}
	
	});

</script>