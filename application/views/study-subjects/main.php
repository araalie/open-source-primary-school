<h4>
	Manage Study Subjects
</h4>
<?= form_open('subjects/edit', array('id' => 'form_edit_subject', 'class' => 'well')); ?>
<fieldset>
          <legend>Create/Edit Subject</legend>
	<label>
		Name of Subject
	</label>
	<input type="hidden" name="subject_id" name="subject_id" value="<?= valueOrBlank($subject_id); ?>"/>
	<input type="text" class="span3 {required:true,minlength:3}" placeholder="Enter name of Subject" name="subject_name" value="<?= valueOrBlank($subject_name); ?>">
	<div class="control-group">
		<label class="control-label" for="textarea">
			Description of Subject
		</label>
		<div class="controls">
			<textarea class="input-xlarge" id="textarea" rows="3"  name='subject_description'><?= valueOrBlank($subject_description); ?></textarea>
		</div>
	</div>
<div id="form-errors"></div>
</fieldset>
	<?=anchor('subjects/', 'Add New Subject', 'class="btn btn-warning margin-right-1" title="Create a new Subject"');?>
	<button type="submit" class="btn" name="save_subject">
		Save
	</button>

</form>
<br/>
<table class="table table-striped table-bordered table-condensed">
	<thead>
		<tr>
			<th>
				#
			</th>
			<th>
				Name
			</th>
			<th>
				Description
			</th>
			<th>
				Edit
			</th>
		</tr>
	</thead>
	<tbody>
	<?
	if(isset($subject_list) && is_array($subject_list)){
		$i=1;
		foreach($subject_list as $s){
			echo '<tr><td>'.$i.'</td><td>'.$s->getName().'</td><td>'.$s->getDescription()
			.'</td><td>'.anchor('subjects/edit/'.$s->getId(), 'Edit <i class="small1">'.$s->getName().'</i>', 'title="Edit"').'</td></tr>';
			$i++;
		}
	}
	
	?>
	</tbody>
</table>
<script type="text/javascript">

$(document).ready(function () {
	
	$("#form_edit_subject").validate({
		errorLabelContainer: $("#form-errors"),
		messages: {
			subject_name:{
				required: "Please enter Name",
				minlenght: "Subject must have at least 2 letters."
				}
			
		}
		});
	
	});
</script>