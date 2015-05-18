<h4>
	Manage Titles (for addressing Staff and Parents)
</h4>
<?= form_open('people_titles/edit', array('id' => 'form_edit_ppl_titles', 'class' => 'well')); ?>
<fieldset>
          <legend>Create/Edit Title</legend>
	<label>
		Title Abbreviation
	</label>
	<input type="hidden" name="title_id" name="title_id" value="<?= valueOrBlank($title_id); ?>"/>
	<input type="text" class="span1" placeholder="Enter title abbreviation" name="title_name" value="<?= valueOrBlank($title_name); ?>">
	<div class="control-group">
		<label class="control-label" for="textarea">
			Remarks
		</label>
		<div class="controls">
			<textarea class="input-xlarge" id="textarea" rows="3"  name='title_description'><?= valueOrBlank($title_description); ?></textarea>
		</div>
	</div>
	<?=anchor('people_titles/', 'New Title', 'class="btn btn-warning margin-right-1" title="Create a new Title"');?>
	<button type="submit" class="btn btn-primary" name="save_subject">
		Save
	</button>
	</fieldset>
</form>
<br/>
<table class="table table-striped table-bordered table-condensed">
	<thead>
		<tr>
			<th>
				#
			</th>
			<th>
				Abbreviation
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
	if(isset($title_list) && is_array($title_list)){
		$i=1;
		foreach($title_list as $t){
			echo '<tr><td>'.$i.'</td><td>'.$t->getName().'</td><td>'.$t->getDescription()
			.'</td><td>'.anchor('people_titles/edit/'.$t->getId(), 'Edit <i class="small1">'.$t->getName().'</i>', 'title="Edit"').'</td></tr>';
			$i++;
		}
	}
	
	?>
	</tbody>
</table>
