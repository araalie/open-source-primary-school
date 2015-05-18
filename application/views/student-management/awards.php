<h4 class="section-title"> BURSARY AWARDS </h4>
<div class="row-fluid">
	<div class="span12">
		<ul id="tab" class="nav nav-tabs">
			<li class="active">
				<a href="#this-term" data-toggle="tab">Bursaries for this Term</a>
			</li>
			<li>
				<a href="#bursary-history" data-toggle="tab">Bursary History</a>
			</li>
		</ul>
	</div>
</div>
<div class="tab-content">
	<div class="row-fluid tab-pane active" id="this-term">
		<div class="span10">
			<div class="panel">
				<table class="table table-striped table-bordered table-condensed zeb">
					<thead>
						<tr>
							<th>#</th>
							<th>Surname</th>
							<th>First Name</th>
							<th>St. No.</th>
							<th>Class</th>
							<th>Profile</th>
							<th>Amount</th>
							<th>% of Fees</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1;
						$total=0;
						foreach($term_awards as $a){
						 	$total+=floatval($a['amount']);
						 	echo '<tr><td>'.$i++.'</td><td>'.$a['surname']
						 	.'</td><td>'.$a['first_name'].'</td><td>'.$a['student_number'].'</td><td>'
							.Utilities::getShortClassName($a['class']).'</td><td>'. $a['fees_profile'].'</td><td class="money-right">'
							.number_format($a['amount']).'</td><td>'.number_format($a['pct'],1).'%</td></tr>';
						 }
						 echo '<tr><td colspan="6"><b>TOTAL</b></td><td class="money-right">'.number_format($total).'</td><td>-</td></tr>';
						 ;?>
					</tbody>
					<tfoot>
						<tr><td><button data-url="#" class="btn btn-primary" id="term-bursary-pdf" >Get this Report</button></td></tr>
					</tfoot>
				</table>
			</div>
		</div>

	</div>
	<div class="row-fluid tab-pane active" id="bursary-history">
		<div class="span8">
			<div class="panel"></div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function () {

    $('#tab').tab('show');

    $('#term-bursary-pdf').click(function (e) {
        var url = SchoolUniverse.Base+'fees_reports/term_bursary/';
            $(this).colorbox({
                iframe: true,
                width: "96%",
                height: "96%",
                href: url
            });


    });

});
</script>
