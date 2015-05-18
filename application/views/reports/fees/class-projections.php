<h4> School Fees Report and Projections for Classes </h4>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<hr/>	
			<ul id="classes-hor">
				<?php
				$base = base_url().'reports_class_level_fees/index';
				
				foreach($active_classes as $k){
					$block_active_class='';
					
					if($k['id']==$active_klass){
						$block_active_class='aktive';
					}
					
					echo '<li><a class="'.$block_active_class.'" href="'.$base.'/'.$k['id'].'/'.$freq_name.'" title="'.$k['name'].'">'. Utilities::getShortClassName($k['name']).'</a></li>';
				}
				?>
			</ul>
			<hr/>			
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<?php
			
			$s=1;
			if(!is_null($active_students) && is_array($active_students)){
					
				$table ='<table class="table table-striped table-bordered table-condensed zeb"><thead><tr><th>#</th><th>Surname</th><th>First Name</th><th>St. #</th>'
				.sprintf('<th>Fees Profile</th><th>Compulsary Paid (%s)</th><th>Compulsary Balance (%s)</th>', Utilities::getCurrencyShortName(),Utilities::getCurrencyShortName())
				.sprintf('<th>Non-Compulsary Paid (%s)</th><th>Other Fees Owed (%s)</th>', Utilities::getCurrencyShortName(),Utilities::getCurrencyShortName());
				
				$table.='</tr></thead><tbody>';
				//$actSvc
				
				$total_expected =0;
				$total_paid =0;
				$total_balance =0;
				
				foreach($active_students as $st){
						
					$bal=0;
					$compulsary_fees =  $st['compulsary_amount_owed'];
					$compulsary_paid = floatval($st['compulsary_amount_paid']);
					
					if($compulsary_fees==NULL || intval($compulsary_fees)<1){
						$compulsary_fees = $actSvc->getCompulsaryTotalClassFees($st['class_id'],$st['fees_profile_id'], $freq_name);
					}
					
					$total_expected+=floatval($compulsary_fees);
					
					$bal = floatval($compulsary_fees) -$compulsary_paid ;
					
					$total_balance+= $bal;
					
					$total_paid+=$compulsary_paid;
					
					$table.='<tr><td>'.$s++.'</td><td>'.$st['surname'].'</td><td>'.$st['first_name'].'</td><td>';
					$table.=$st['student_number'].'</td><td>'.$st['fees_profile'].'</td><td class="tright">'.number_format($compulsary_paid).'</td><td class="tright">';
					$table.=number_format($bal).'</td><td>';
					$table.=number_format(floatval($st['other_amount_paid'])).'</td><td>';
					$table.=number_format(floatval($st['other_amount_owed'])).'</td><td>';
					
					$table.='</tr>';
				}
					
				$table.='</tbody></table>';
				
				$table2='<table class="table table-striped table-bordered table-condensed" style="width:50%"><thead>'.
				'<tr><th colspan="3" class="central">COMPULSARY FEES STATUS</th></tr>'.
				 '<tr><th class="tright">Total Exepected</th><th class="tright">Total Paid</th><th class="tright">Balance</th></tr></thead>';
				$table2.=sprintf('<tbody><tr><td class="tright">%s</td><td class="tright">%s</td><td class="tright">%s</td></tr>',
				number_format($total_expected), number_format($total_paid), number_format($total_balance));
				
				$table2.='</tbody></table>';
				
				echo '<hr/>'.$table2;
				echo $table;				
				echo '<hr/>'.$table2;
				
				if($active_klass>0){
					echo anchor('reports_class_level_fees/excel/'.$active_klass.'/'.$freq_name, 'Download as Excel', 'class="btn btn-primary"');
				}
			}
			?>
			<hr/>	
			</div>
			</div>
</div>