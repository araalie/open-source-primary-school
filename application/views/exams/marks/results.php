<h4> Results Grading and Analysis </h4>
<hr/>
<div class="row-fluid">
	<div class="span6">
		<?= form_open('exam_management/edit', array('class' => 'form-horizontal edit-form')); ?>
		<label for="selected_exam">Exams this Term</label>
		<?=form_dropdown('selected_exam', $current_exams, valueOrBlank($selected_exam), 'id="selected_exam" class="span5" '); ?>
	</div>
	<div class="span4">
		<label for="selected_class">Classes</label>
		<?=form_dropdown('selected_class', $my_classes, '', 'id="selected_class" class="span3" '); ?>
		<?=form_close(); ?>
	</div>
</div>
<hr/>
<div class="row-fluid">
	<div class="span12">
		<table id="class_results"  class="table table-striped table-bordered table-condensed zeb">
			<caption></caption>
			<thead></thead>
			<tbody></tbody>
			<tfoot></tfoot>
		</table>
		<hr/>
		<table class="table" style="width:40%;">
			<tbody>
				<tr>
					<td>No. of missing marks</td><td id="missing_marks" class="marks_info"></td><td id="missing_marks_pct" class="marks_info"></td>
				</tr>
				<tr>
					<td>No. of entered marks</td><td id="entered_marks" class="marks_info"></td><td id="entered_marks_pct" class="marks_info"></td>
				</tr>			</tbody>
				<tfoot>
					<tr>
						<td colspan="3" id="seal-status"></td>
					</tr>
				</tfoot>
		</table>
	</div>
</div>

<div class="row-fluid">
	<div class="span5">
		<button id='class_report' class="btn btn-alpha">
			Download Report as MS Excel
		</button>
	</div>
	<div class="span5">
		<h4>Available Schemes Guide:</h4>
		<hr/>
		<?php
		if (isset($schemes) && is_array($schemes)) {
			$choices = '<div id="scheme-choices-list" style="position:absolute;left:-90000px;top:-2900px"><form id="scheme-selection-form">';
			echo '<dl class="collapsy">';
			foreach ($schemes as $s) {
				echo sprintf('<dt id="scheme-title-%s"><b>%s</b></dt><dd id="scheme-%s"><ul>', $s['id'], $s['name'], $s['id']);
				foreach ($s['ranges'] as $r) {
					echo sprintf('<li> %s : %2s - %3s</li>', $r['code'], $r['min'], $r['max']);
				}
				echo '</ul></dd>';
				$choices .= sprintf('<input type="radio" name="scheme-choice" class="scheme-chkbx" value="%s" /> %s</br/>', $s['id'], $s['name']);
			}
			echo '</dl>';
			$choices .= '</form></div>';
			echo $choices;
		}
		?>
	</div>
</div>

<script type="text/javascript">
	var subjectGradings = '';

	$(document).ready(function() {

		$('#selected_exam').change(function() {

			var examId = $('#selected_exam').val();

			$('#selected_class').html('');
			$('#selected_subject').html('');
			$('#currentSubjectName').html('');
			$("#this-subject-marks").html('');

			if (examId > 0) {
				LoadClasses(examId);
			}else{
				resetUI();
			}
		});

		$('#selected_class').change(function() {

			var classId = $('#selected_class').val();

			if (classId > 0) {
				LoadSubjects(classId);
			}else{
				resetUI();
			}
			
		});

		$('body').on('click', '.grading-menu', function(e) {
			selectScheme({
				caller : $(this).data()
			});
		});

		$('body').on('click', '#apply-grading', function(e) {

			var gradesPerSubject = '';

			$('.subject-info').each(function(i, v) {
				gradesPerSubject += $(v).data('subjectInstance') + '|' + $(v).data('schemeId') + '#';
			});

			applyGradingSchemes(gradesPerSubject);
		});

		$('#class_report').click(function(e) {

			var examId = $('#selected_exam').val();
			var classId = $('#selected_class').val();

			if (examId < 1 || classId < 1) {
				return;
			}

			dynamicIframe({
				url : SchoolUniverse.Base + 'reports_exams/class_exam_results/' + classId + '/' + examId
			});

		});

		$('body').on('click', '#seal-class-exam', function(e) {

			e.preventDefault();
			var info = $(this).data();

			var html = 'This will seal these results making them permanent. When sealed, marks can never be changed and grading cannot not be applied again.<hr/>';
			html += 'This process is permanent and cannot be reversed once done.<br/>';

			var puzzle1 = Math.round(1 + (499 * Math.random()) % 500);
			var puzzle2 = Math.round(1 + (499 * Math.random()) % 500);

			var puzzle = puzzle1 + puzzle2;

			html += '<b>To confirm this operation enter the answer to this challenge and then click Yes.</b><hr/>';
			html += '<b>' + puzzle1 + ' + ' + puzzle2 + ' =</b> <input type="text" id="challenge_answer" style="display:inline-block;width:30px;font-weight:bold;" />';

			new $.Zebra_Dialog(html, {
				'type' : 'warning',
				'title' : 'Seal Class Exam Permanently',
				'buttons' : [{
					caption : 'Yes',
					callback : function() {

						if (puzzle == $('#challenge_answer').val()) {
							sealClassExam();
						}else{
							DisplayPopup('error|Not Sealed|This class exam was not sealed.');
						}
					}
				}, {
					caption : 'Cancel'
				}],
				width : 480
			});

		});

	});

	function LoadClasses(eId) {

		$.get(SchoolUniverse.Base + 'exam_results/classes_in_exam/' + eId, function(data) {
			//console.log(data);
			var output = [];
			output.push('<option value="">Select a class</option>');
			$.each(data, function(key, value) {
				output.push('<option value="' + key + '">' + value + '</option>');
			});

			$('#selected_class').html(output.join(''));
		}, 'json');
	}

	function LoadSubjects() {

		var examId = $('#selected_exam').val();
		var classId = $('#selected_class').val();

		if (examId < 1 || classId < 1) {
			return;
		}

		$('#selected_class').blur();

		$.post(SchoolUniverse.Base + 'exam_results/get_class_marks/', {
			'class' : classId,
			'exam' : examId
		}, function(data) {
			if (data.success) {
				loadSubjectTitles(data);
				loadStudentDetails(data);
				loadFooterMenus(data);
			} else {
				$('#class_results thead').html('')
				$('#class_results tbody').html('')
				$('#class_results tfoot').html('')

				DisplayPopup('error|No results retrieved|' + data.msg);
			}
		}, 'json');
	}

	function loadSubjectTitles(_data) {
		var data = _data.subject_list
		var titles2 = '';
		var html = '<tr>';
		html += '<th>#</th><th>SURNAME</th><th>FIRST NAME</th><th>St. #</th>';

		if (_data.info.grading_mode=='AGGREGATE_GRADING') {
			for (var i = 0; i < data.length; i++) {
				html += sprintf('<th colspan="2" class="subject-info" id="subject-info-%s" data-subject-instance="%s">%s</th>', data[i].subject_instance, data[i].subject_instance, data[i].subject_name);
				titles2 += '<th class="central">%</th><th class="central">G</th>';
			}

			html += '</tr>'
			html += '<tr><th colspan="4">&nbsp;</th>' + titles2;
			html += '<th>Total</th><th>Aggregate</th><th>Division</th><th>Position</th></tr>';

		} else {

			for (var i = 0; i < data.length; i++) {
				html += sprintf('<th colspan="1" class="subject-info" id="subject-info-%s" data-subject-instance="%s">%s</th>', data[i].subject_instance, data[i].subject_instance, data[i].subject_name);
				titles2 += '<th class="central">%</th>';
			}

			html += '</tr>'
			html += '<tr><th colspan="4">&nbsp;</th>' + titles2;
			html += '<th>Total</th><th>Position</th></tr>';

		}

		$('#class_results thead').html(html)
	}

	function loadStudentDetails(_data) {
		var html = '';

		var data = _data.results
		var subs = _data.subject_list

		if (_data.info.grading_mode=='AGGREGATE_GRADING') {
			for (var i = 0; i < data.length; i++) {
				html += '<tr><td>' + (i + 1) + '</td><td>' + data[i]['surname'];
				html += '</td><td>' + data[i]['first_name'] + '</td><td>' + data[i]['student_number'] + '</td>';

				for (var k = 0; k < subs.length; k++) {
					var mKey = 'marks_' + subs[k].subject_instance;
					var gKey = 'grade_' + subs[k].subject_instance;
					html += '<td class="central">' + deNull(data[i][mKey]) + '</td><td class="central">' + deNull(data[i][gKey]) + '</td>';
				}

				html += '<td class="central">' + deNull(data[i]['total_marks']) + '</td><td class="central">' + deNull(data[i]['total_aggregates']) + '</td><td class="central">' + deNull(data[i]['division']) + '</td>' + '</td><td class="central"><b>' + deNull(data[i]['position']) + '</b></td>';
				html += '</tr>';
			}

		} else {

			for (var i = 0; i < data.length; i++) {
				html += '<tr><td>' + (i + 1) + '</td><td>' + data[i]['surname'];
				html += '</td><td>' + data[i]['first_name'] + '</td><td>' + data[i]['student_number'] + '</td>';

				for (var k = 0; k < subs.length; k++) {
					var mKey = 'marks_' + subs[k].subject_instance;
					html += '<td class="central">' + deNull(data[i][mKey]) + '</td>';
				}

				html += '<td class="central">' + deNull(data[i]['total_marks']) + '</td><td class="central"><b>' + deNull(data[i]['position']) + '</b></td>';
				html += '</tr>';
			}

		}

		$('#class_results tbody').html(html);
		
		var total = null2Zero(_data.marks_stats.marks_count);
		
		$('#missing_marks').html(_data.marks_stats.null_marks_count);
		$('#missing_marks_pct').html(Math.round(100*null2Zero(_data.marks_stats.null_marks_count)/total)+'%');
		
		var entered = total - null2Zero(_data.marks_stats.null_marks_count);
		
		$('#entered_marks').html(entered);
		$('#entered_marks_pct').html(Math.round(100*entered/total)+'%');
	}

	function loadFooterMenus(_data) {

		$('#class_results tfoot').html('');
		
		if (_data.info.exam_status == 'ARCHIVED') {
			return;
		}
		
		
		var total = null2Zero(_data.marks_stats.marks_count)
		
		,entered = total - null2Zero(_data.marks_stats.null_marks_count)
		
		,pctMarks = Math.round(100*entered/total);
		
		var data = _data.subject_list;

		$('#class_results tfoot').html('');
		$('#seal-status').html('');

		if (_data.info.grading_mode=='AGGREGATE_GRADING') {
			var html = '<tr><th  colspan="4"><br/>Select Grading Scheme for each subject</th>';

			var footer2 = '<tr><th  colspan="4">Grading schemes<br/><button class="btn btn-success" id="apply-grading">Apply Gradings</button></th>';
			var footer3 = '<tr><th  colspan="4">Analysis</th>';
			var footer4 ='<tr><th  colspan="14"><div class="alert alert-danger custom-error">Less than 60% of the marks are entered. This exam cannot be closed.</div></th></tr>';

			if(pctMarks>=60)
			{
				footer4='<tr><th  colspan="14"><button class="btn btn-danger" id="seal-class-exam" style="margin-left:60%">Finalize these Exam Results for this Class</button></th>';	
			}else{
				$('#seal-status').html(footer4);
			}
			 

			for (var i = 0; i < data.length; i++) {
				html += sprintf('<th colspan="2">%s<br/><span class="grading-name">*%s*</span><br/><button class="grading-menu btn btn-custom1" data-subject-instance="%s" data-subject-name="%s" style="font-size:0.9em;">Select Grading Scheme</button></th>', data[i].subject_name, data[i].grading, data[i].subject_instance, data[i].subject_name);
				footer2 += sprintf('<th colspan="2" id="grading-range-%s"></th>', data[i].subject_instance);
				footer3 += sprintf('<th colspan="2" id="analysis-%s"></th>', data[i].subject_instance);
				if (i > 8) {
					footer4 += '<th colspan="2">&nbsp;</th>';
				}

			}

			html += '</tr>';
			footer2 += '<th colspan="4">&nbsp;</th></tr>';
			footer3 += '<th colspan="4">&nbsp;</th></tr>';
			footer4 += '<th colspan="4">&nbsp;</th></tr>';

			html += footer2;
			html += footer4;
			//+footer3;

			$('#class_results tfoot').html(html)

		}else{
			var html ='<tr><th  colspan="10"><div class="alert alert-danger custom-error">Less than 60% of the marks are entered. This exam cannot be closed.</div></th></tr>';
			
			if(pctMarks>=60)
			{
			 	html = '<tr><th  colspan="10"><button class="btn btn-danger" id="seal-class-exam" style="margin-left:60%">Finalize these Exam Results for this Class</button></th></tr>';
			}else{
				$('#seal-status').html(html);
			}
			
			$('#class_results tfoot').html(html)
		}
	}

	function selectScheme(_info) {
		var info = _info;

		var choiceHtml = '<span id="cform">' + $('#scheme-choices-list').html() + '</span>';

		var dlg = new $.Zebra_Dialog(choiceHtml, {
			'type' : 'info',
			'title' : 'Select a Grading Scheme for: ' + info.caller.subjectName,
			'buttons' : [{
				caption : 'Cancel',
				callback : function() {
					dlg.forceClose();
				}
			}, {
				caption : 'Select Choice',
				callback : function() {
					var form = $('#cform').children('form');
					var chosen = form.find('input:checked').eq(0).val();
					if (chosen > 0) {
						$('#grading-range-' + info.caller.subjectInstance).html($('dt#scheme-title-' + chosen).html() + '<br/>' + $('dd#scheme-' + chosen).html());
						$('#subject-info-' + info.caller.subjectInstance).data('scheme-id', chosen);
						dlg.forceClose();
					}
				}
			}],
			'manualClose' : true
		});
	}

	function applyGradingSchemes(subjectGradingSchemeStr) {
		var examId = $('#selected_exam').val();
		var classId = $('#selected_class').val();

		$.post(SchoolUniverse.Base + 'exam_results/apply_grading/', {
			'class' : classId,
			'exam' : examId,
			'subject_gradings' : subjectGradingSchemeStr
		}, function(data) {
			if (data.success) {
				loadSubjectTitles(data);
				loadStudentDetails(data);
				loadFooterMenus(data);
			} else {
				$('#class_results thead').html('')
				$('#class_results tbody').html('')
				$('#class_results tfoot').html('')

				DisplayPopup('error|No results retrieved|' + data.msg);
			}
		}, 'json');
	}

	function sealClassExam() {

		var examId = $('#selected_exam').val();
		var classId = $('#selected_class').val();

		if (examId < 1 || classId < 1) {
			return;
		}

		$.post(SchoolUniverse.Base + 'exam_results/seal_class_exam/', {
			'class' : classId,
			'exam' : examId
		}, function(data) {
			if (data.success) {
				DisplayPopup('Exam has been successfully been finalized.');
			} else {
				DisplayPopup('error|Exam Not Finalized|' + data.msg);
			}
		}, 'json');
	}

	function deNull(val) {
		if (val == null) {
			return '-';
		} else {
			return val;
		}
	}


	function null2Zero(val) {
		if (val == null) {
			return 0;
		} else {
			return val;
		}
	}
	
		
	function resetUI()
	{
				$('#class_results thead').html('')
				$('#class_results tbody').html('')
				$('#class_results tfoot').html('')
				$('.marks_info').html('');
	}
</script>