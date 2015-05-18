<h4>
	Houses In the School
</h4>
<?= form_open('student_houses/edit', array('id' => 'form_edit_house', 'class' => 'well')); ?>
<fieldset>
          <legend>Create/Edit House</legend>
	<label>
		House Name
	</label>
	<input type="hidden" name="house_id" name="house_id" value="<?= valueOrBlank($house_id); ?>"/>
	<input type="text" class="span2 {required:true,minlength:3}" placeholder="Enter House Name" name="house_name" value="<?= valueOrBlank($house_name); ?>">
	<div class="control-group">
		<label class="control-label" for="textarea">
			Description
		</label>
		<div class="controls">
			<textarea class="input-xlarge" id="textarea" rows="3"  name='title_description'><?= valueOrBlank($house_description); ?></textarea>
		</div>
	</div>
	<div id="form-errors"></div>
	</fieldset>
<?=anchor('student_houses/', 'New House', 'class="btn btn-warning margin-right-1" title="Create a new House"');?>
	<button type="submit" class="btn btn-primary" name="save_house">
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
				House
			</th>
			<th class="central">
				No. of  Student Members
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
	if(isset($house_list) && is_array($house_list)){
		$i=1;
		foreach($house_list as $t){
			echo '<tr><td>'.$i.'</td><td>'.$t['name']
			.'</td><td class="central">'.$t['students_in_house']
			.'</td><td>'.$t['description']
			.'</td><td>'.anchor('student_houses/edit/'.$t['id'], 'Edit <i class="small1">'.$t['name'].' House</i>', 'title="Edit"').'</td></tr>';
			$i++;
		}
	}
	
	?>
	</tbody>
</table>
<script type="text/javascript">

$(document).ready(function () {
	
	$("#form_edit_house").validate({
		errorLabelContainer: $("#form-errors"),
		messages: {
			house_name:{
				required: "Please enter student's First Names",
				minlenght: "First Name must have at least 2 letters."
				}
			
		}
		});
	
	});
</script>