<div class="container">
	<div class="row-fluid">
		<div class="span12">
<h4>
Class Level School Fees Management
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
	<hr/><h5 class="title-color-1">Fees Per Class : <?=$term->getName();?> </h5><hr/>
	<?= form_open('fees_management/edit/'.$classi_id, array('id' => 'fees', 'class' => 'form-horizontal edit-form')); ?>
	
<div class="control-group">
<label class="control-label" for="classi_id">Class</label>
<div class="controls">
<?=form_dropdown('classi_id', $current_classes, valueOrBlank($classi_id), '  name="classi_id" id="classi_id" class="{required:true} span4" '); ?>
</div>
</div>

<?php

foreach($fees as $keyUp => $valueUp){
	?>
			<table class="table table-striped table-bordered table-condensed zeb">
				<caption><h3><?=$keyUp;?></h3></caption>
		<thead>
		<tr>
		<th>
		#
		</th>
		<th>Fees Type</th>
		<th>Amount (UGX)</th>
		<th>Compulsary?</th>
		<th>Student Profile</th>
		</tr>
		</thead>
		<tbody>
	<?php

$j=1;
for($a=0;$a<count($valueUp); $a++){

 $value = $valueUp[$a]; 
if(is_array($value)){
	$checked=NULL;
	echo $value['is_compulsary'].'<br/>';
	if($value['is_compulsary']==1){	
	$checked = 'checked=checked';	
	}
$input ="<input class='fee_amount digits fee-figure input-small'  name='fee_".$value['id']."' value='". $value['amount']."' />";
$chBox = "<input type='checkbox' class='input-small'  name='compulsary_fee_".$value['id']."' $checked />";

//$sum+=floatval($value['amount']);

echo  '<tr><td>'.$j.'</td><td>'.$value['name'].'</td><td>'.
$input.'</td><td>'. $chBox
.'</td><td>'.$value['profile']
.'</td></tr>';

$j++;
}
}
echo '</tbody></table>';
}

?>
	
<input type='hidden' id='feedback' name='feedback' value="<?= valueOrBlank($feedback); ?>"/>
<div id="form-errors"></div>	
	<hr/><input type="submit" class="btn btn-primary submit align-45" name="update_term_class_fees" value="Save/Update Fee Structure" />	
	</div>
	<?=form_close();?>
	</div>
	</div>
<?php
}
?>
		</div>
	</div>
</div>
<script type="text/javascript">

$(document).ready(function () {


    //auto submit
    $('#classi_id').change(function () {
        $(this).closest('form').submit();
    })
	
    $("#fees").validate({
        errorLabelContainer: $("#form-errors"),
        messages: {
            classi_id: "A class must be selected"
        }
    });

    if ($('#feedback').val() != '') {
        DisplayPopup($('#feedback').val());
        $('#feedback').val('');
    }

});

</script>