<h4>
	Requirements/Items Costs For this Term : <?php $term = Utilities::getCurrentTerm(); if(!is_null($term)) { echo $term->getName();} ?>
</h4>
<hr/><br/>

<div class="row-fluid">
<div class="span8">
<?= form_open('student_requirements_management', array('id' => 'edit_item_prices', 'class' => 'form-vertical edit-form')); ?>
<fieldset>
		<table class="table table-striped table-bordered table-condensed zeb">
		<thead>
		<tr>
		<th>#</th>
		<th>Item/Requirement</th>
		<th>Profile</th>
		<th>Amount per Unit (UGX)</th>		
		<th>Transport Fee (UGX)</th>
		</tr>
		</thead>
		<tbody>
<?
$j=1;
$sum=0;
foreach($prices as $key => $value){

$input ="<input class='fee_amount digits fee-figure span2'  name='price_".$key."' value='". $value['price']."' />";
$inputTrans ="<input class='fee_amount digits fee-figure span1'  name='transport_".$key."' value='". $value['transport']."' />";

echo '<tr><td>'.$j.'</td><td>'.$value['name']
	.'</td><td>'.$value['fees_profile']
	.'</td><td>'.$input.'</td><td>'.$inputTrans.'</td></tr>';

$j++;

}

?>
	</tbody>		
	</table>
</fieldset>
<div class="align-451">
<input type="submit" class="btn btn-primary submit" name="save_prices" value="Update Costs" /> 
<input type='hidden' id='feedback' name='feedback' value="<?= valueOrBlank($feedback); ?>"/>
</div>
</div>
</div>
</form>
<script type="text/javascript">

</script>
