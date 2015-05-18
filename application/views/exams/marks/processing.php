<h4>
Marks Entry: <span id='subject_status_note' class="alert alert-info custom-info">*</span>
</h4>
<hr/>
<div class="row-fluid">
<div class="span4">
<?= form_open('exam_management/edit', array('class' => 'form-horizontal edit-form')); ?>
<label for="selected_exam">Exams this Term</label>
<?=form_dropdown('selected_exam', $current_exams, valueOrBlank($selected_exam), 'id="selected_exam" class="span3" '); ?>
</div>
<div class="span4">
<label for="selected_class">My Classes</label>
<?=form_dropdown('selected_class', $my_classes, '', 'id="selected_class" class="span3" '); ?>
<?=form_close();?>		
</div>
<div class="span4">
<label for="selected_subject">My Subjects</label>
<?=form_dropdown('selected_subject', $my_subjects, '', 'id="selected_subject" class="span3" '); ?>
<?=form_close();?>		
</div>
</div>
<div class="row-fluid">
<div class="span8">
<div class="panel">
<h5 class="section-title2" id='marks-title'></h5>
<table class="table table-striped table-bordered table-condensed zeb">
<caption><h3 id='currentSubjectName'></h3></caption>
<thead>
<tr>
<th>#</th>
<th>Surname</th>
<th>First Name</th>
<th>Student Number</th>
<th>Marks</th>
<th>Comment</th>
</tr>
</thead>
<tbody id="this-subject-marks"></tbody>
</table>
</div>	
</div>
<div class="span4">
<div class="panel">
<h5 class="section-title2">>SUMMARY<</h5>
<table class="table table-striped table-bordered table-condensed zeb">
<thead>
<tr>
<th>Range</th>
<th>No. of Students</th>
</tr>
</thead>
<tbody id="summary-panel"></tbody>
</table>
<div id='range-chart' style="cursor:pointer;text-align: center;" title="Click to expand."></div>
<a href="#" class="btn btn-primary" id='refresh-summary'>Refresh Summary</a>
</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function () {
	
	$('body').on('blur', '.mark-input', function (e) {
		
		var val = $(this).val();
		var original = $(this).data('original');
		
		$(this).removeClass('error');
		
		if(original!=val){
			
			if(val==''){
				UpdateMarks($(this).attr('id'), true);
				}else{
				var m = parseInt(val);
				
				if(m>=0 && m<=100){
					UpdateMarks($(this).attr('id'), true);	
					}else{
					$(this).addClass('error');
				}
			}
		}
		
		});

	$('body').on('blur', '.comment-input', function (e) {
		
		var val = $(this).val();
		var original = $(this).data('original');
		
		if(original!=val){
			UpdateMarks($(this).attr('id'), false);
		}
		
		});	
	
	$('#selected_exam').change(function () {
		var examId = $('#selected_exam').val();
		
		$('#selected_class').html('');
		$('#selected_subject').html('');
		$('#currentSubjectName').html('');
		$("#this-subject-marks").html('');
		
		if(examId>0){
			LoadClasses(examId);
		}
		});
	
	$('#selected_class').change(function () {
		var classId = $('#selected_class').val();
		
		if(classId>0){
			LoadSubjects(classId);
		}
		});	
	
	$('#selected_subject').change(function () {
		LoadStudents();
		});	
		
	$('#refresh-summary').click(function () {
		var subjectId = $('#selected_subject').val();
		var classId = $('#selected_class').val();
		
		if(subjectId>0 && classId>0){
			LoadStudents();	
		}
		
		});
		
    $('#range-chart').click(function (e) {
    	
        var url = SchoolUniverse.Base+'fees_reports/term_bursary/';
        
        $(this).colorbox({
                inline: true,
                width: "96%",
                height: "96%",
                href: this
            });


    });				
	
	});

function LoadClasses(eId){
	clearUI();
	$.get(SchoolUniverse.Base + 'marks_editing/get_my_classes/'+eId,
	function (data) {
		//console.log(data);
		var output = [];
		output.push('<option value="">Select a class</option>');
		$.each(data, function(key, value)
		{
			output.push('<option value="'+ key +'">'+ value +'</option>');
			});
		
		$('#selected_class').html(output.join(''));
		}, 
	'json');	
}


function LoadSubjects(cId){
	
	clearUI();
	$.get(SchoolUniverse.Base + 'marks_editing/get_my_subjects/'+cId,
	function (data) {
		//console.log(data);
		var output = [];
		output.push('<option value="">Select a subject</option>');
		$.each(data, function(key, value)
		{
			output.push('<option value="'+ key +'">'+ value +'</option>');
			});
		
		$('#selected_subject').html(output.join(''));
		
		}, 
	'json');	
}

function LoadStudents(){	
	clearUI();
	$('#currentSubjectName').html(
		$('#selected_subject :selected').text()
		.replace(/\]\ \-\ \[/g,' \\ ')
		.replace('[','').replace(']','')
		);
	
	$.post(SchoolUniverse.Base + 'marks_editing/get_my_student_marks/', 
	
	{subject: $('#selected_subject').val(),exam:$('#selected_exam').val()},
	
	function (data) {
		
		if (data && data.success) {
			
			$('#selected_subject').blur();
			
			var i=1;
			var studentList = [];
			$.each(data.marks, function(key,value){
				var tmp = value;
				tmp.serial = i++;
				studentList.push(tmp);     
				})
				
			var html = $("#maxEditorTmpl").render(studentList);
			$("#this-subject-marks").html('');
			$("#this-subject-marks").html(html);

			var stats = [];
			$.each(data.stats, function(key,value){
				var tmp = {};
				tmp.key = key;
				tmp.value = value;
				stats.push(tmp);     
				})			
				
			var htmlSummary = $("#summaryTmpl").render(stats);
			$("#summary-panel").html('');
			$("#summary-panel").html(htmlSummary);
			
			//chart
			var img = '<img alt="Ranges" class="img-scaled" src="'+data.chart+'" />';
			$("#range-chart").html(img);
			
			if(data.subject_status=='ARCHIVED'){
				$('.mark-input, .comment-input').attr('readonly','readonly');
				$('#subject_status_note').text('THE MARKS ARE NOW ARCHIVED AND CANNOT BE EDITED ANYMORE');
			}else{
				$('#subject_status_note').text('THE MARKS CAN STILL BE EDITED');
			}
			
			} else {
			DisplayPopup('error|No students|Contact the Admin');
		}
		
		
		}, 'json');
}

function UpdateMarks(id, isMarks){
	
	var studentIdName = id;
	
	if(!isMarks){
		studentIdName = id.replace('_comment','');
	}
	
	var marks_data ={
		exam: $('#'+studentIdName+'_exam').val(),
		marks: $('#'+studentIdName).val(),
		subject: $('#'+studentIdName+'_subject').val(),
		comment: $('#'+studentIdName+'_comment').val(),
		student: $('#'+studentIdName+'_student').val()
		}
	
	$('#'+studentIdName).attr('readonly', 'readonly');
	
	$.post(SchoolUniverse.Base + 'marks_editing/post_marks/',
	marks_data,	
	function (data) {
		
		if (data && data.success) {
			$('#'+studentIdName).addClass('success');
			$('#'+studentIdName).data('original', marks_data.marks);
			$('#'+studentIdName).removeAttr('readonly');
			} else {
			DisplayPopup('error|Marks not saved|' + data.msg);
		}
		
		
		}, 'json');
	
}


function clearUI(){
	$('#currentSubjectName').html('');
	$("#this-subject-marks").html('');
	$("#summary-panel").html('');
	$("#range-chart").html('');
	$('#subject_status_note').html('');
	
}
</script>

<script id="maxEditorTmpl" type="text/x-jsrender">
<tr>
<td>{{:serial}}
<input type="hidden" value="{{:exam}}" id="student_{{:student_id}}_exam" class="student_{{:student_id}}" />
<input type="hidden" value="{{:subject}}" id="student_{{:student_id}}_subject" class="student_{{:student_id}}" />
<input type="hidden" value="{{:student_id}}" id="student_{{:student_id}}_student" />
</td>
<td>{{:surname}}</td>
<td>{{:first_name}}</td>
<td>{{:student_number}}</td>
<td><input type='text' class='mark-input student_{{:student_id}}' maxlength="3" id="student_{{:student_id}}" data-original="{{:marks}}" 
 value="{{:marks}}" /></td>
<td><textarea rows="1" class='span1 student_{{:student_id}} comment-input' id="student_{{:student_id}}_comment" data-original="{{:comments}}">{{:comments}}</textarea></td>
</tr>
</script>
<script id="summaryTmpl" type="text/x-jsrender">
	<tr><td>{{:key}}</td><td>{{:value}}</td></tr>
</script>
