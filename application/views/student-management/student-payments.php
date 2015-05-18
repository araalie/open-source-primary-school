<h4 class="section-title"> School Fees Payment </h4>

<div class="row-fluid">
	<div class="span12">
		<h5 class="can-toggle">Find Students</h5>
		<div class="panel toggles">
			<div class="control-group">
				<div class="controls">
					<?= form_open('student_payments/find', array('class' => 'form-horizontal edit-form', 'id' => 'search_form')); ?>
					Classs : <?=form_dropdown('search_classi_id', $current_classes, valueOrBlank($search_classi_id), 'id="search_classi_id" class="span4" '); ?>
					&nbsp;&nbsp;&nbsp;St. #
					<input type="text" id='sno' name='sno' class="span1" />
					&nbsp;&nbsp;&nbsp;Name
					<input type="text" id='name' name='name' class="span1" />
					<input type="hidden" id='student_id' name='student_id'/>
					<a href="#" class="btn btn-success" id="do_search">Search</a>
					</form>
					<div id='search_results'>
						<table class="table table-striped table-bordered table-condensed zeb">
							<caption>
								Search Results
							</caption>
							<thead>
								<tr>
									<th>#</th>
									<th>Surname</th>
									<th>First Name</th>
									<th>St. No.</th>
									<th>Class</th>
									<th>Profile</th>
									<th>Choose</th>
								</tr>
							</thead>
							<tbody id="search-hits"></tbody>
						</table>
						<div class="holder"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<ul id="tab" class="nav nav-tabs">
	<li>
		<a href="#current-fees" data-toggle="tab">Payment Status</a>
	</li>
	<li class="active">
		<a href="#terminal-payments" data-toggle="tab">Term Fees</a>
	</li>
	<li>
		<a href="#annual-payments" data-toggle="tab">Annual Fees</a>
	</li>
	<li>
		<a href="#once-fees" data-toggle="tab">One Time Fees</a>
	</li>
	<li>
		<a href="#adhoc-fees" data-toggle="tab">Adhoc Fees</a>
	</li>
	<li>
		<a href="#holiday" data-toggle="tab">Holiday Fees</a>
	</li>
	<li>
		<a href="#debts" data-toggle="tab">Debts</a>
	</li>
	<li>
		<a href="#items-man" data-toggle="tab">Inventory</a>
	</li>
	<li>
		<a href="#fees-history" data-toggle="tab">Fees History</a>
	</li>
</ul>
<div class="tab-content">
	<div class="row-fluid tab-pane active" id="current-fees">
		<div class="span6">
			<div class="panel">
				<h5 class="section-title2">Fees Overview: <span class="name-display active-student-name"></span></h5>

				<table class="table table-striped table-bordered table-condensed zeb">
					<caption>
						BURSARIES
					</caption>
					<thead>
						<tr>
							<th>Given By</th>
							<th>Date</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tbody id="this-term-bursary-summary"></tbody>
				</table>

			</div>
		</div>
	</div>
	<div class="row-fluid tab-pane" id="terminal-payments">
		<div class="span5">
			<div class="panel">
				<h5 class="section-title2">Term Fees: <span class="name-display active-student-name" id='active-student-name'></span></h5>
				<?= form_open('student_payments/post_fees_terminal', array('class' => 'form-horizontal edit-form', 'id' => 'new_fees_form')); ?>
				<table class="table table-striped table-bordered table-condensed zeb">
					<caption>
						<input  type="hidden" id="active-student-id" name='active_student_id' />
					</caption>
					<thead>
						<tr>
							<th>FEES</th>
							<th>EXPECTED</th>
							<th>PAID</th>
						</tr>
					</thead>
					<tbody id="new-fees-table"></tbody>
					<tfoot id='fees-entry-footer'></tfoot>
				</table>
				<?= form_close(); ?>
			</div>
		</div>
		<div class="span7">
			<div class="panel">
				<h5 class="section-title2">Current Term : <span class="name-display active-student-name"></span></h5>
				<table class="table table-striped table-bordered table-condensed zeb">
					<thead>
						<tr>
							<th>FEE</th>
							<th>Date</th>
							<th>Slip No.</th>
							<th>Amount</th>
							<th>Cancel</th>
						</tr>
					</thead>
					<tbody id="this-term-payments"></tbody>
				</table>
				<table class="table table-striped table-bordered table-condensed zeb">
					<caption>
						BURSARIES
					</caption>
					<thead>
						<tr>
							<th>Given By</th>
							<th>Date</th>
							<th>Amount</th>
							<th>Cancel</th>
						</tr>
					</thead>
					<tbody id="this-term-bursary"></tbody>
				</table>
				<button data-url="#" class="btn btn-primary" id="fees-pdf" >
					Get Fees Report
				</button>
				<input type="hidden" id='terminal-total-paid'/>
				<input type="hidden" id='total-bursary'/>
				<input type="hidden" id='terminal-total-class-fees'/>
				<input type="hidden" id='annual-total-paid'/>
				<input type="hidden" id='annual-total-class-fees'/>
				<br/>
				<h5 class="section-title2">Compulsary Amount Owed : <span class="name-display" id="fees-bal"></span></h5>
			</div>
		</div>
	</div>
	<div class="row-fluid tab-pane" id="annual-payments">
		<div class="span5">
			<div class="panel">
				<h5 class="section-title2">Annual Fees: <span class="name-display active-student-name"></span></h5>
				<?= form_open('student_payments/post_fees_annual', array('class' => 'form-horizontal edit-form', 'id' => 'new_fees_form_annual')); ?>
				<table class="table table-striped table-bordered table-condensed zeb">
					<caption>
						<input  type="hidden" id="annual-active-student-id" name='annual_active_student_id' />
					</caption>
					<thead>
						<tr>
							<th>FEES</th>
							<th>EXPECTED</th>
							<th>PAID</th>
						</tr>
					</thead>
					<tbody id="new-annual-fees-table"></tbody>
					<tfoot id='annual-fees-entry-footer'></tfoot>
				</table>
				<?= form_close(); ?>
			</div>
		</div>
		<div class="span7">
			<div class="panel">
				<h5 class="section-title2">This Year : <span class="name-display active-student-name"></span></h5>
				<table class="table table-striped table-bordered table-condensed zeb">
					<thead>
						<tr>
							<th>FEE</th>
							<th>Date</th>
							<th>Slip No.</th>
							<th>Amount</th>
							<th>Cancel</th>
						</tr>
					</thead>
					<tbody id="this-year-payments"></tbody>
				</table>
				<input type="hidden" id='annual-total-class-fees'/>
				<br/>
				<h5 class="section-title2">Compulsary Amount Owed : <span class="name-display" id="annual-fees-bal"></span></h5>
			</div>
		</div>
	</div>
	<div class="row-fluid tab-pane" id="once-fees">
				<div class="span5">
			<div class="panel">
				<h5 class="section-title2">One Time Fees: <span class="name-display active-student-name"></span></h5>
				<?= form_open('student_payments/post_fees_one_time', array('class' => 'form-horizontal edit-form', 'id' => 'new_fees_form_once')); ?>
				<table class="table table-striped table-bordered table-condensed zeb">
					<caption>
						<input  type="hidden" id="once-active-student-id" name='once_active_student_id' />
					</caption>
					<thead>
						<tr>
							<th>FEES</th>
							<th>EXPECTED</th>
							<th>PAID</th>
						</tr>
					</thead>
					<tbody id="new-once-fees-table"></tbody>
					<tfoot id='once-fees-entry-footer'></tfoot>
				</table>
				<?= form_close(); ?>
			</div>
		</div>
		<div class="span7">
			<div class="panel">
				<h5 class="section-title2">Paid Once : <span class="name-display active-student-name"></span></h5>
				<table class="table table-striped table-bordered table-condensed zeb">
					<thead>
						<tr>
							<th>FEE</th>
							<th>Date</th>
							<th>Slip No.</th>
							<th>Amount</th>
							<th>Cancel</th>
						</tr>
					</thead>
					<tbody id="this-once-payments"></tbody>
				</table>
				<input type="hidden" id='once-total-class-fees'/>
				<br/>
				<h5 class="section-title2">Compulsary Amount Owed : <span class="name-display" id="once-fees-bal"></span></h5>
			</div>
		</div>

	</div>
	<div class="row-fluid tab-pane" id="adhoc-fees">
		<div class="span5">
			<div class="panel">
				<h5 class="section-title2">Adhoc Term Fees: <span class="name-display active-student-name" id='active-student-name'></span></h5>
				<?= form_open('student_payments/post_fees_adhoc', array('class' => 'form-horizontal edit-form', 'id' => 'new_fees_form_adhoc')); ?>
				<table class="table table-striped table-bordered table-condensed zeb">
					<caption>
						<input  type="hidden" id="adhoc-active-student-id" name='adhoc_active_student_id' />
					</caption>
					<thead>
						<tr>
							<th>FEES</th>
							<th>EXPECTED</th>
							<th>PAID</th>
						</tr>
					</thead>
					<tbody id="new-adhoc-fees-table"></tbody>
					<tfoot id='adhoc-fees-entry-footer'></tfoot>
				</table>
				<?= form_close(); ?>
			</div>
		</div>
		<div class="span7">
			<div class="panel">
				<h5 class="section-title2">Current Ad hocs : <span class="name-display active-student-name"></span></h5>
				<table class="table table-striped table-bordered table-condensed zeb">
					<thead>
						<tr>
							<th>FEE</th>
							<th>Date</th>
							<th>Slip No.</th>
							<th>Amount</th>
							<th>Cancel</th>
						</tr>
					</thead>
					<tbody id="this-term-adhoc-payments"></tbody>
				</table>
				<h5 class="section-title2">Compulsary Amount Owed : <span class="name-display" id="adhoc-fees-bal"></span></h5>
			</div>
		</div>
	</div>
	<div class="row-fluid tab-pane" id="holiday">
		<div class="span5">
			<div class="panel">
				<h5 class="section-title2">Holiday Fees: <span class="name-display active-student-name" id='holiday-active-student-name'></span></h5>
				<?= form_open('student_payments/post_fees_holiday', array('class' => 'form-horizontal edit-form', 'id' => 'new_fees_form_holiday')); ?>
				<table class="table table-striped table-bordered table-condensed zeb">
					<caption>
						<input  type="hidden" id="holiday-active-student-id" name='holiday_active_student_id' />
					</caption>
					<thead>
						<tr>
							<th>FEES</th>
							<th>EXPECTED</th>
							<th>PAID</th>
						</tr>
					</thead>
					<tbody id="new-holiday-fees-table"></tbody>
					<tfoot id='holiday-fees-entry-footer'></tfoot>
				</table>
				<?= form_close(); ?>
			</div>
		</div>
		<div class="span7">
			<div class="panel">
				<h5 class="section-title2">Current Holiday Payments : <span class="name-display active-student-name"></span></h5>
				<table class="table table-striped table-bordered table-condensed zeb">
					<thead>
						<tr>
							<th>FEE</th>
							<th>Date</th>
							<th>Slip No.</th>
							<th>Amount</th>
							<th>Cancel</th>
						</tr>
					</thead>
					<tbody id="this-term-holiday-payments"></tbody>
				</table>
				<h5 class="section-title2">Compulsary Amount Owed : <span class="name-display" id="holiday-fees-bal"></span></h5>
			</div>
		</div>
	</div>	
	<div class="row-fluid tab-pane" id="debts">
		<div class="span10">Debts</div>
	</div>	
	<div class="row-fluid tab-pane" id='items-man'>
		<div class="span9">
			<div class="panel">
				<h5 class="section-title2">INVENTORY : <span class="name-display active-student-name"></span></h5>
				<table class="table table-striped table-bordered table-condensed zeb">
					<thead>
						<tr>
							<th>Item</th>
							<th colspan="3" class="gen-center">STUDENT</th>
							<th colspan="5" class="gen-center">SCHOOL</th>
						</tr>
						<tr>
							<th>Item</th>
							<th>Brought</th>
							<th>No. To Register</th>
							<th>Cancel</th>
							<th>Cost + Transport</th>
							<th>No. to Buy</th>
							<th>Bought</th>
							<th>Total Charge</th>
							<th>Cancel</th>
							<th>Paid</th>
							<th>Select</th>
						</tr>
					</thead>
					<tbody id="this-term-procurables"></tbody>
					<tfoot>
						<tr>
							<td colspan="3">
							<button data-url="#" class="btn btn-primary" id="report-inventory" >
								Get Inventory Report
							</button></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="span3">
			<div class="panel">
				<h4>Pay For Requirements</h4>
				<form id="pay_requirements_form">
					<ul id="requirements-payments">

					</ul>
				</form>
			</div>
		</div>
	</div>
	<div class="row-fluid tab-pane" id='fees-history'>
		<div class="span12">
			<div class="panel">
				<h5 class="section-title2">Fees History</h5>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		
		
		if(window.location.hash) {
	      	var hash = window.location.hash.substring(1); 
	      
	      	$('#student_id').val(hash);
	      	lookUpStudents(hash);
	      	$('#student_id').val('');
  		}

		$('#tab').tab('show');

		$('#fees-pdf, #report-inventory').click(function(e) {
			var url = $(this).data('url');
			if (url != '#') {
				$(this).colorbox({
					iframe : true,
					width : "96%",
					height : "96%",
					href : url
				});
			}

		});

		$('#do_search').click(function() {
			lookUpStudents();
		});

		//live
		$('body').on('click', 'a.select-student', function(e) {

			var cur = $(this).data();
			$('#active-student-id').data('current-student', cur);
			$('#active-student-id').val(cur.studentId);
			$('#annual-active-student-id').val(cur.studentId);
			$('#once-active-student-id').val(cur.studentId);
			$('#adhoc-active-student-id').val(cur.studentId);
			$('#holiday-active-student-id').val(cur.studentId);
			
			SetUpStudentFeesInterface(cur);
		});

		//terminal
		$('body').on('click', '#post-fees-terminal', function(e) {

			if ($("#new_fees_form").valid()) {

				var jqxhr = $.post($("#new_fees_form").attr('action'), $("#new_fees_form").serialize(), function(data) {

					if (data.success) {
						UpdateFeesDisplay(data);
						clearFormElements('.fees-payment');
						clearFormElements('.payment-info');
					} else {
						DisplayPopup('error|The fees have not been posted.|' + data.msg);
					}

				}, 'json');
			}
			e.preventDefault();
		});

		//annual
		$('body').on('click', '#post-fees-annual', function(e) {

			if ($("#new_fees_form_annual").valid()) {

				var jqxhr = $.post($("#new_fees_form_annual").attr('action'), $("#new_fees_form_annual").serialize(), function(data) {

					if (data.success) {
						
						UpdateAnnualFeesDisplay(data);
						
						clearFormElements('.fees-payment');
						clearFormElements('.payment-info');
						
					} else {
						DisplayPopup('error|The fees have not been posted.|' + data.msg);
					}

				}, 'json');
			}
			e.preventDefault();
		});

		//once
		$('body').on('click', '#post-fees-once', function(e) {

			if ($("#new_fees_form_once").valid()) {

				var jqxhr = $.post($("#new_fees_form_once").attr('action'), $("#new_fees_form_once").serialize(), function(data) {

					if (data.success) {

						UpdateOnceFeesDisplay(data);

						clearFormElements('.fees-payment');
						clearFormElements('.payment-info');

					} else {
						DisplayPopup('error|The fees have not been posted.|' + data.msg);
					}

				}, 'json');
			}
			e.preventDefault();
		});


		//adhoc
		$('body').on('click', '#post-fees-adhoc', function(e) {

			if ($("#new_fees_form_adhoc").valid()) {

				var jqxhr = $.post($("#new_fees_form_adhoc").attr('action'), $("#new_fees_form_adhoc").serialize(), function(data) {

					if (data.success) {

						UpdateAdhocFeesDisplay(data);

						clearFormElements('.fees-payment');
						clearFormElements('.payment-info');

					} else {
						DisplayPopup('error|The fees have not been posted.|' + data.msg);
					}

				}, 'json');
			}
			e.preventDefault();
		});
				

		//holiday
		$('body').on('click', '#post-fees-holiday', function(e) {

			if ($("#new_fees_form_holiday").valid()) {

				var jqxhr = $.post($("#new_fees_form_holiday").attr('action'), $("#new_fees_form_holiday").serialize(), function(data) {

					if (data.success) {

						UpdateHolidayFeesDisplay(data);

						clearFormElements('.fees-payment');
						clearFormElements('.payment-info');

					} else {
						DisplayPopup('error|The fees have not been posted.|' + data.msg);
					}

				}, 'json');
			}
			e.preventDefault();
		});
						
		$('body').on('click', '#btn-pay-requirement', function(e) {

			if ($("#pay_requirements_form").valid()) {

				var jqxhr = $.post(SchoolUniverse.Base + 'student_payments/pay_for_inventory/', formToObj('#pay_requirements_form'), function(data) {

					if (data.success) {
						var st = GetActiveStudent();
						UpdateProcurablesDisplay(st);
					} else {
						DisplayPopup('error|The fees have not been posted.|' + data.msg);
					}

				}, 'json');
			}

			e.preventDefault();
		});

		$('body').on('click', 'a.del-posting', function(e) {

			e.preventDefault();
			var info = $(this).data();

			new $.Zebra_Dialog('Do you want to delete <b>' + info.amount + " UGX</b> recorded on <b>" + $('#active-student-name').text() + '</b> Account' + '<br/><br/>Click yes to delete.', {
				'type' : 'warning',
				'title' : 'Delete Payment?',
				'buttons' : [{
					caption : 'Yes',
					callback : function() {
						DeletePosting(info.txnId);
					}
				}, {
					caption : 'Cancel'
				}]
			});

		});

		$('body').on('click', 'a.remove-brought', function(e) {

			e.preventDefault();

			var element = $(this);
			var diselement = $("#display_brought_" + element.data('rid'));

			if (parseInt(diselement.text()) > 0) {

				var info = element.data();

				var jqxhr = $.post(SchoolUniverse.Base + 'student_payments/delete_from_inventory/', info, function(data) {

					if (data) {
						if (data.success) {

							diselement.text('');

						} else {
							DisplayPopup('error|Deletion not done|' + data.msg);
						}
					}
				}, 'json');
			}
		});

		$('body').on('blur', 'input.student-item-brought', function(e) {

			var element = $(this);
			var newVal = element.val();
			var cur = element.data();
			if (newVal > 0 && cur.brought != newVal) {
				cur.new_brought = newVal;

				var jqxhr = $.post(SchoolUniverse.Base + 'student_payments/update_inventory/', cur, function(data) {

					if (data) {
						if (data.success) {

							element.val('');
							$("#display_brought_" + element.data('rid')).text(newVal);

						} else {
							DisplayPopup('error|Update not made|' + data.msg);
						}
					}
				}, 'json');
			}

		});

		$('body').on('blur', 'input.school-item-bought', function(e) {

			var element = $(this);
			var newVal = element.val();
			var cur = element.data();
			if (newVal > 0 && cur.bought != newVal) {
				cur.new_bought = newVal;

				var jqxhr = $.post(SchoolUniverse.Base + 'student_payments/update_charge_inventory/', cur, function(data) {

					if (data) {
						if (data.success) {

							/*element.val('');
							 $("#display_sch_bought_"+element.data('rid')).text(newVal);

							 var priceEl = $("#display_total_cost_"+element.data('rid'));
							 var pData = priceEl.data();

							 priceEl.text(newVal*pData.price+pData.transport);
							 */

							var info = GetActiveStudent();

							UpdateProcurablesDisplay(info);

						} else {
							DisplayPopup('error|Update not made|' + data.msg);
						}
					}
				}, 'json');
			}

		});

	});
	
	function lookUpStudents(studentId){
			var jqxhr = $.post($("#search_form").attr('action'), $("#search_form").serialize(), function(data) {

				var html = $("#hitsTmpl").render(data);
				$("#search-hits").html('');
				$("#search-hits").html(html);

				$("div.holder").jPages({
					containerID : "search-hits",
					perPage : 5,
					startPage : 1,
					startRange : 1,
					midRange : 5,
					endRange : 1
				});
				
				if(studentId){
					var hit = $('a.select-student').eq(0)
					
					var cur = hit.data();
					$('#active-student-id').data('current-student', cur);
					$('#active-student-id').val(cur.studentId);
					$('#annual-active-student-id').val(cur.studentId);
					$('#once-active-student-id').val(cur.studentId);
					$('#adhoc-active-student-id').val(cur.studentId);
					$('#holiday-active-student-id').val(cur.studentId);
					SetUpStudentFeesInterface(cur);
				}

			}, 'json');		
	}

	function DeletePosting(id) {
		var jqxhr = $.post(SchoolUniverse.Base + 'student_payments/delete_transaction/', {
			txnId : id
		}, function(data) {

			if (data) {
				if (data.success) {
					DisplayPopup('info|Entry Deleted|This record has been deleted successfully.');
					var cur = $('#active-student-id').data('current-student');

					SetUpStudentFeesInterface(cur);

				} else {
					DisplayPopup('error|Entry Not Deleted|' + data.msg);
				}
			}
		}, 'json');
	}

	function SetUpStudentFeesInterface(data) {

		if (data.classId == '') {
			DisplayPopup('error|Student Has No Class|This student has no class assigned and thus fees cannot be registered yet.' + '<br/>== <b>' + data.fullname + '</b> ==' + '<br />Notify the class teacher or System Administrator.');
			return;
		}

		if (data.feesProfileId == '') {
			DisplayPopup('error|Student Has No Fees Profile|This student is neither in boarding nor a day scholar.' + '<br/>== <b>' + data.fullname + '</b> ==' + '<br />Notify the Bursar or System Administrator.');
			return;
		}

		SetupEditFees(data);
		GetTotalFees(data.classId, data.feesProfileId, '#total-class-amount');
	}

	function SetupEditFees(info) {

		$('#active-student-id').val(info.studentId);
		$('#annual-active-student-id').val(info.studentId);
		$('#once-active-student-id').val(info.studentId);
		$('#adhoc-student-id').val(info.studentId);
		$('#holiday-student-id').val(info.studentId);
		$('.active-student-name').html(info.fullname);

		//terminal
		$.get(SchoolUniverse.Base + 'student_payments/get_term_fees_structure/' + info.classId + '/' + info.feesProfileId + '/TERMINAL/', 
		function(data) {

			UpdateFeesDisplay(info);
			UpdateProcurablesDisplay(info);

			var html = $("#freshpaymentTmpl").render(data);
			$("#new-fees-table").html('');
			$("#new-fees-table").html(html);

			$("#fees-entry-footer").html('');
			$("#fees-entry-footer").html(AppUniverse.terminalFeesEntryFooter);

			DatePickerActivate();
			
			$("#new_fees_form").validate({
				errorLabelContainer : $("#form-errors"),
				messages : {}
			});
		}, 'json');

		//annual
		$.get(SchoolUniverse.Base + 'student_payments/get_term_fees_structure/' + info.classId + '/' + info.feesProfileId + '/ANNUAL/', function(data) {

			var html = $("#freshpaymentTmpl").render(data);
			$("#new-annual-fees-table").html('');
			$("#new-annual-fees-table").html(html);

			$("#annual-fees-entry-footer").html('');
			$("#annual-fees-entry-footer").html(AppUniverse.annualFeesEntryFooter);

						DatePickerActivate();
						
			$("#new_fees_form_annual").validate({
				errorLabelContainer : $("#form-errors-annual"),
				messages : {}
			});
		}, 'json');
		
		
		//once
		$.get(SchoolUniverse.Base + 'student_payments/get_term_fees_structure/' + info.classId + '/' + info.feesProfileId + '/ONCE/', function(data) {

			var html = $("#freshpaymentTmpl").render(data);
			$("#new-once-fees-table").html('');
			$("#new-once-fees-table").html(html);

			$("#once-fees-entry-footer").html('');
			$("#once-fees-entry-footer").html(AppUniverse.onceFeesEntryFooter);

			DatePickerActivate();
			
			$("#new_fees_form_once").validate({
				errorLabelContainer : $("#form-errors-once"),
				messages : {}
			});
		}, 'json');
		

		//adhoc
		$.get(SchoolUniverse.Base + 'student_payments/get_term_fees_structure/' + info.classId + '/' + info.feesProfileId + '/AD HOC/', function(data) {

			var html = $("#freshpaymentTmpl").render(data);
			$("#new-adhoc-fees-table").html('');
			$("#new-adhoc-fees-table").html(html);

			$("#adhoc-fees-entry-footer").html('');
			$("#adhoc-fees-entry-footer").html(AppUniverse.adhocFeesEntryFooter);
		
					DatePickerActivate();
			$("#new_fees_form_adhoc").validate({
				errorLabelContainer : $("#form-errors-adhoc"),
				messages : {}
			});
		}, 'json');		


		//holiday
		$.get(SchoolUniverse.Base + 'student_payments/get_term_fees_structure/' + info.classId + '/' + info.feesProfileId + '/HOLIDAY/', function(data) {

			var html = $("#freshpaymentTmpl").render(data);
			$("#new-holiday-fees-table").html('');
			$("#new-holiday-fees-table").html(html);

			$("#holiday-fees-entry-footer").html('');
			$("#holiday-fees-entry-footer").html(AppUniverse.holidayFeesEntryFooter);

			DatePickerActivate();

			$("#new_fees_form_holiday").validate({
				errorLabelContainer : $("#form-errors-holiday"),
				messages : {}
			});
		}, 'json');		
	}

	function DatePickerActivate()
	{
			jQuery('.date-done').datepicker({format: 'dd-M-yyyy'});
	}
	
	function GetTotalFees(classId, feesProfileId) {

		//terminal
		$.get(SchoolUniverse.Base + 'student_payments/get_term_fees/' + classId + '/' + feesProfileId + '/TERMINAL/', function(data) {
			$('#terminal-total-class-fees').val(data);
		});

		//annual
		$.get(SchoolUniverse.Base + 'student_payments/get_term_fees/' + classId + '/' + feesProfileId + '/ANNUAL/', function(data) {
			$('#annual-total-class-fees').val(data);
		});
	}

	function UpdateFeesDisplay(info) {

		$.get(SchoolUniverse.Base + 'student_payments/get_students_fees/' + info.studentId, function(data) {

			var html = $("#currentTerminalPaymentsTmpl").render(data);
			$("#this-term-payments").html('');
			$("#this-term-payments").html(html);
			var paid = 0;
			for ( i = 0; i < data.length; i++) {
				if (data[i].is_compulsary == 1)
					paid += parseFloat(data[i].amount);
			}

			$('#terminal-total-paid').val(paid);

			$('#fees-bal').html(number_format(parseInt(parseInt($('#total-fees').data('amount'))) + paid));

			$('#fees-pdf').data('url', SchoolUniverse.Base + 'fees_reports/index/' + info.studentId);

			getBursaries(info.studentId);
			UpdateAnnualFeesDisplay(info);
			UpdateOnceFeesDisplay(info);
			UpdateAdhocFeesDisplay(info);
			UpdateHolidayFeesDisplay(info);
			
		}, 
		'json');

	}

	function UpdateAnnualFeesDisplay(info) {

		$.get(SchoolUniverse.Base + 'student_payments/get_students_fees_breakdown_current_year/' + info.studentId, function(data) {

			var html = $("#currentAnnualPaymentsTmpl").render(data);
			$("#this-year-payments").html('');
			$("#this-year-payments").html(html);

		}, 'json');

	}

	function UpdateOnceFeesDisplay(info) {

		$.get(SchoolUniverse.Base + 'student_payments/get_students_fees_breakdown_once/' + info.studentId, function(data) {

			var html = $("#oncePaymentsTmpl").render(data);
			$("#this-once-payments").html('');
			$("#this-once-payments").html(html);

		}, 'json');

	}

	function UpdateAdhocFeesDisplay(info) {

		$.get(SchoolUniverse.Base + 'student_payments/get_students_fees_breakdown_adhoc/' + info.studentId, function(data) {

			var html = $("#currentAdhocPaymentsTmpl").render(data);
			$("#this-term-adhoc-payments").html('');
			$("#this-term-adhoc-payments").html(html);

		}, 'json');

	}


	function UpdateHolidayFeesDisplay(info) {

		$.get(SchoolUniverse.Base + 'student_payments/get_students_fees_breakdown_holiday/' + info.studentId, function(data) {

			var html = $("#currentHolidayPaymentsTmpl").render(data);
			$("#this-term-holiday-payments").html('');
			$("#this-term-holiday-payments").html(html);

		}, 'json');

	}
			
	function UpdateProcurablesDisplay(info) {

		$.get(SchoolUniverse.Base + 'student_payments/term_procurable_items/' + info.studentId, function(data) {
			var html = $("#inventoryTmpl").render(data);
			$("#this-term-procurables").html('');
			$("#this-term-procurables").html(html);
			CreateInventoryPayArea();
			$('#report-inventory').data('url', SchoolUniverse.Base + 'reports_inventory/student/' + info.studentId);
		}, 'json');
	}

	function CreateInventoryPayArea() {

		var vessel = $('#requirements-payments');
		vessel.empty();

		$('input:checkbox.requirement_pay_select').live('change', function() {

			vessel.empty();
			var all = $('input:checkbox:checked.requirement_pay_select');

			var total = 0;
			var ids = '';
			all.each(function(i, a) {
				var data = $(a).data();
				ids += data.rid + '|';
				vessel.append('<li>' + number_format(data.totalCharge) + '</li>');
				total += data.totalCharge;
			});

			if (total == 0) {
				return;
			}
			var hid = '<input type="hidden" name="pay_for_items" name="pay_for_items" value="' + ids + '" />';

			vessel.append('<li><hr/>TOTAL: <b>' + number_format(total) + hid + '</b><hr/></li>');
			vessel.append('<li>Bank Slip No.: <input type="text" name="requirements_pay_slip" id="requirements_pay_slip" class="{required:true} default-width" /></li>');
			vessel.append('<li>Date Paid: <input readonly type="text" name="requirements_pay_date" id="requirements_pay_date" class="{required:true} default-width date-done" /></li>');
			vessel.append('<li><button id="btn-pay-requirement" class="btn btn-primary">Save</button></li>');

			/*$.Zebra_DatePicker('#requirements_pay_date', {
				format : 'd-M-Y',
				direction : false
			});*/
		});
	}

	function getBursaries(studentId) {
		$.get(SchoolUniverse.Base + 'bursaries/get/' + studentId, function(data) {

			var html = $("#bursaryTmpl").render(data);
			$("#this-term-bursary").html('');
			$("#this-term-bursary").html(html);

			//update summary
			var html = $("#bursarySummaryTmpl").render(data);
			$("#this-term-bursary-summary").html('');
			$("#this-term-bursary-summary").html(html);

			var award = 0;
			for ( i = 0; i < data.length; i++) {
				award += parseFloat(data[i].amount);
			}

			$('#total-bursary').val(award);

			var paid = parseFloat($('#terminal-total-paid').val());

			var classTotal = parseFloat($('#terminal-total-class-fees').val());

			$('#fees-bal').html(number_format(parseFloat(classTotal - award + paid)));

		}, 'json');
	}

	function GetActiveStudent() {
		return $('#active-student-id').data('current-student');
	}

</script>

<script id="hitsTmpl" type="text/x-jsrender">
	<tr>
	<td>{{:index}}</td>
	<td>{{:surname}}</td>
	<td>{{:first_name}}</td>
	<td>{{:student_number}}</td>
	<td>{{:class}}</td>
	<td>{{:fees_profile}}</td>
	<td><a href="#{{:id}}" class="btn btn-primary select-student" data-student-id='{{:id}}'
	data-fullname="{{:surname}}, {{:first_name}}" data-class-id="{{:class_id}}" id='sid_{{:id}}'
	data-fees-profile-id="{{:fees_profile_id}}">Select</a></td>
	</tr>
</script>

<script id="freshpaymentTmpl" type="text/x-jsrender">
	<tr>
	<td>{{:name}}</td>
	<td class="money-right">{{>~format(amount, "money")}}</td>
	<td class="compulsary-{{:is_compulsary}}"><input type='text' class='input-small fees-payment number' name="fee_post_{{:id}}" /></td>
	</tr>
</script>

<script id="inventoryTmpl" type="text/x-jsrender">
	<tr>
	<td>{{:item_name}}</td>
	<td class="bold-center1" id="display_brought_{{:id}}">{{:number_brought_by_student}}</td>
	<td><input type='text' class='tiny1 student-item-brought numeric' name="brought_{{:id}}" data-rid="{{:id}}" data-brought="{{:number_brought_by_student}}" /></td>
	<td><a href="#" class="del-link remove-brought tipsy " data-op='remove-brought' data-rid="{{:id}}" title="Remove {{:number_brought_by_student}} units of {{:item_name}}"></a></td>
	<td>{{>~format(price, "money")}} ({{>~format(transport_cost, "money")}})</td>
	<td><input type='text' class='tiny1 school-item-bought numeric' name="school_bought_{{:id}}" data-rid="{{:id}}"  data-bought="{{:number_bought_by_school}}" /></td>
	<td class="bold-center1" id="display_sch_bought_{{:id}}" >{{:number_bought_by_school}}</td>
	<td id="display_total_cost_{{:id}}" data-price="{{:price}}" data-brought="{{:number_brought_by_student}}" data-transport="{{:transport_cost}}">
	{{>~format(total_charge, "money")}}
	</td>
	<td><a href="#" class="del-link" data-op='remove-charges' data-rid="{{:id}}"></a></td>
	<td>{{>~bool2Str(was_paid,number_brought_by_student)}}</td>
	<td><input class="requirement_pay_select" data-total-charge="{{:total_charge}}"  data-rid="{{:id}}" type="checkbox" {{>~checkBox(was_paid,number_bought_by_school)}}/></td>
	</tr>
</script>

<script id="currentTerminalPaymentsTmpl" type="text/x-jsrender">
	<tr>
	<td>{{:name}}</td>
	<td>{{:date_done}}</td>
	<td>{{:pay_slip_number}}</td>
	<td class="money-right">{{>~format(-1*amount, "money")}}</td>
	<td class="gen-center"><a href="#" class="del-posting" data-txn-id='{{:tx_id}}' data-amount='{{>~format(-1*amount, "money")}}' title="Cancel this payment."></a></td>
	</tr>
</script>

<script id="currentAnnualPaymentsTmpl" type="text/x-jsrender">
	<tr>
	<td>{{:name}}</td>
	<td>{{:date_done}}</td>
	<td>{{:pay_slip_number}}</td>
	<td class="money-right">{{>~format(-1*amount, "money")}}</td>
	<td class="gen-center"><a href="#" class="del-posting" data-txn-id='{{:tx_id}}' data-amount='{{>~format(-1*amount, "money")}}' title="Cancel this payment."></a></td>
	</tr>
</script>

<script id="currentAdhocPaymentsTmpl" type="text/x-jsrender">
	<tr>
	<td>{{:name}}</td>
	<td>{{:date_done}}</td>
	<td>{{:pay_slip_number}}</td>
	<td class="money-right">{{>~format(-1*amount, "money")}}</td>
	<td class="gen-center"><a href="#" class="del-posting" data-txn-id='{{:tx_id}}' data-amount='{{>~format(-1*amount, "money")}}' title="Cancel this payment."></a></td>
	</tr>
</script>

<script id="currentHolidayPaymentsTmpl" type="text/x-jsrender">
	<tr>
	<td>{{:name}}</td>
	<td>{{:date_done}}</td>
	<td>{{:pay_slip_number}}</td>
	<td class="money-right">{{>~format(-1*amount, "money")}}</td>
	<td class="gen-center"><a href="#" class="del-posting" data-txn-id='{{:tx_id}}' data-amount='{{>~format(-1*amount, "money")}}' title="Cancel this payment."></a></td>
	</tr>
</script>

<script id="oncePaymentsTmpl" type="text/x-jsrender">
	<tr>
	<td>{{:name}}</td>
	<td>{{:date_done}}</td>
	<td>{{:pay_slip_number}}</td>
	<td class="money-right">{{>~format(-1*amount, "money")}}</td>
	<td class="gen-center"><a href="#" class="del-posting" data-txn-id='{{:tx_id}}' data-amount='{{>~format(-1*amount, "money")}}' title="Cancel this payment."></a></td>
	</tr>
</script>

<script id="terminalPaymentsSummaryTmpl" type="text/x-jsrender">
	<tr>
	<td>{{:name}}</td>
	<td>{{:date_done}}</td>
	<td>{{:pay_slip_number}}</td>
	<td class="money-right">{{>~format(-1*amount, "money")}}</td>
	</tr>
</script>

<script id="bursaryTmpl" type="text/x-jsrender">
	<tr>
	<td>{{:given_by}}</td>
	<td>{{:date_created.date}}</td>
	<td class="money-right">{{>~format(amount, "money")}}</td>
	<td class="gen-center"><a href="#" class="del-posting" data-bursary-id='{{:id}}' data-amount='{{>~format(amount, "money")}}' title="Cancel this bursary."></a></td>
	</tr>
</script>

<script id="bursarySummaryTmpl" type="text/x-jsrender">
	<tr>
	<td>{{:given_by}}</td>
	<td>{{:date_created.date}}</td>
	<td class="money-right">{{>~format(amount, "money")}}</td>
	</tr>
</script>