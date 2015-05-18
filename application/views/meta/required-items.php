<h4>
	Student's Required Items
</h4>
<?= form_open('required_items/edit', array('id' => 'form_edit_house', 'class' => 'well')); ?>
<fieldset>
          <legend>Create/Edit Item</legend>
	<label>
		Name of Item
	</label>
	<input type="hidden" name="item_id" name="item_id" value="<?= valueOrBlank($item_id); ?>"/>
	<input type="text" class="span3 {required:true,minlength:3}" placeholder="Name of Item" name="item_name" value="<?= valueOrBlank($item_name); ?>">
	<div class="control-group">
	<label class="control-label" for="fees_profile_id">Cost Group</label>
	<div class="controls">
	<?=form_dropdown('fees_profile_id', $fees_profiles, valueOrBlank($fees_profile_id), '  name="fees_profile_id" id="fees_profile_id" class="span3 required" '); ?>
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
<?=anchor('required_items/', 'New Item', 'class="btn btn-warning margin-right-1" title="Create a new Item"');?>
	<button type="submit" class="btn btn-primary" name="save_item">
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
				Item
			</th>
			<th>
				Costing Group
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
	if(isset($item_list) && is_array($item_list)){
		$i=1;
		foreach($item_list as $t){
		$fp='N/A';
		if($t->getFeesProfile()){
		$fp= $t->getFeesProfile()->getName();	
		}
			echo '<tr><td>'.$i.'</td><td>'.$t->getName()
			.'</td><td>'.$fp
			.'</td><td>'.$t->getDescription()
			.'</td><td>'.anchor('required_items/edit/'.$t->getId(), 'Edit <i class="small1">'.$t->getName()
			.'</i>', 'title="Edit"').'</td></tr>';
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
				required: "Please enter Name",
				minlenght: "Item Name must have at least 2 letters."
				}
			
		}
		});
	
	});
</script>