<h4>
	Subject Assignment for Classes
</h4>
<div class="alert alert-info custom-info">
  Note Only Acticated Classes can be assigned subjects.
</div>
<?= form_open('classes_subject_assignment', array('id' => 'edit_assignment', 'class' => 'form-vertical edit-form')); ?>
<fieldset>
<div class="row-fluid">
<div class="span4">
<h3>AVAILABLE SUBJECTS</h3>
<?=form_dropdown('subject_list', $possible_subjects, '', 'multiple="multiple"  name="subject_list" id="subject_list" class="span3 multi-long"'); ?>
<?=anchor('#', 'Add >>', 'class="btn btn-success margin-right-1 tooltips" title="Add Selected subjects to this class" id="add"');?>
</div>
<div class="span3">
<div class="control-group">
<label class="control-label" for="classi_id">Select A Class</label>
<div class="controls">
<?=form_dropdown('classi_id', $classies, valueOrBlank($classi_id), '  name="classi_id" id="classi_id" class="span3"'); ?>
</div>
</div>
<div class="control-group">
            <label class="control-label" for="attached_subjects">Attached Subjects</label>
            <div class="controls">
			<?=form_dropdown('attached_subjects[]', $attached_subjects, '', 'multiple="multiple"  name="subject_list" id="attached_subjects" class="multi-medium"'); ?>
			  <?=anchor('#', '<< Remove', 
'class="btn btn-danger  inpage-del tooltips"  title="Remove selected Subjects from this class (and therefore from the term)" id="remove"');?>
            </div>
          </div>
</div>
</div>
</fieldset>
<hr/><br/>
<div class="row-fluid">
<div class="span4">
<div class="alert alert-info custom-info">
  After Selecting Subjects, Update here
</div> </div>
<div class="span4">
<div class="align-451">
<input type="submit" class="btn btn-primary submit" name="save_subjects" value="Update Subjects" /> 
<input type='hidden' id='feedback' name='feedback' value="<?= valueOrBlank($feedback); ?>"/>
</div>
</div>
</div>
</form>
<script type="text/javascript">
$(document).ready(function () {

    var allOptions = jQuery.parseJSON(jQuery('#possible_vals').val());

    $('#possible_subjects').children().remove().end();
    for (i in allOptions) {
        $('#possible_subjects')
    }

    $('#add').click(function () {
        return !$('#subject_list option:selected').remove().appendTo('#attached_subjects');
    });
    $('#remove').click(function () {
        return !$('#attached_subjects option:selected').remove().appendTo('#subject_list');
    });
    //auto submit
    $('#classi_id').change(function () {
		$('#attached_subjects option:selected').remove();
        $(this).closest('form').submit();		
    })
	
	
	$('#edit_assignment').submit(function() {
          $('#attached_subjects option').each(function (i) {
            $(this).attr("selected", "selected");
        });
});
});
</script>
