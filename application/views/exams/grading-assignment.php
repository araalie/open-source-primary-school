<h4>
Assign Grading to Exam Results By Subject
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
<th>Grade</th>
<th>Comment</th>
</tr>
</thead>
<tbody id="this-subject-marks"></tbody>
</table>
</div>	
</div>
<div class="span4">
<div>
<table class="table table-striped table-bordered table-condensed zeb">
<thead>
<tr>
<th>Range</th>
<th>No. of Students</th>
</tr>
</thead>
<tbody id="summary-panel"></tbody>
</table>
<div id='range-chart'></div>
</div>
<div class="panel" id="grading-schemes"  style="height:400px;overflow-y:scroll;">
<h6>Available Grading Schemes</h6>
<? 

if(is_array($gradings) && count($gradings)>0){
echo '<table class="table table-striped table-bordered table-condensed zeb">
<thead><tr>
<th>#</th>
<th>Grading Ranges</th>
</tr>
</thead>
<tbody>';

	foreach($gradings as $g){
		echo '<tr><td><input type="radio" name="grading-choice" value="'.$g['id'].'" /></td><td><h5>'.$g['name']
		.'</h5></td></tr><tr><td colspan="2">';
		$tmp='<ul>';
		foreach($g['ranges'] as $r){
			$tmp.='<li><b>'.$r['code'].'</b> : '
			.str_pad($r['min'], 3, " ", STR_PAD_LEFT).' - '.$r['max'].'</li>';
		}
		echo $tmp.'</ul></td><tr/>';
	}
	
echo '</tbody></table>';
}

?>
</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function () {
	
	$('body').on('click', '#load-graph', function (e) {
		var url = $('#load-graph').children().eq(0).attr('src');
		if(url!=''){
	$(this).colorbox({iframe:true, width:"80%", height:"80%", href: url});	
}
		e.preventDefault();
	});
	
	$('#grading-schemes input:radio[name=grading-choice]').click(function() {
	  
	  var grading = $('#grading-schemes input:radio[name=grading-choice]:checked').val();
	  var subject = $('#selected_subject').val();
	  var exam = $('#selected_exam').val();
  
	  if(grading>0 &&  subject>0 && exam>0){
	  	LoadStudents();
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

	var grading = $('#grading-schemes input:radio[name=grading-choice]:checked').val() || -1;
	
	clearUI();
	$('#currentSubjectName').html($('#selected_subject :selected').text()
		.replace(/\]\ \-\ \[/g,' \\ ')
		.replace('[','').replace(']',''));
	
	$.post(SchoolUniverse.Base + 'exam_grading/load_exam_results/', 
	
	{subject: $('#selected_subject').val(),exam:$('#selected_exam').val(), grading:grading},
	
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
			var img = '<a href="#" id="load-graph"><img alt="Ranges" class="img-scaled" src="'+data.chart+'" /></a>';
			$("#range-chart").html(img);
			
			} else {
			DisplayPopup('error|No students|Contact the Admin');
		}
		
		
		}, 'json');
}

function clearUI(){
	$('#currentSubjectName').html('');
	$("#this-subject-marks").html('');
	$("#summary-panel").html('');
	$("#range-chart").html('');
	
}
</script>

<script id="maxEditorTmpl" type="text/x-jsrender">
<tr>
<td>{{:serial}}
</td>
<td>{{:surname}}</td>
<td>{{:first_name}}</td>
<td>{{:student_number}}</td>
<td>{{:marks}}</td>
<td>{{:grade}}</td>
<td>{{:comment}}</td>
</tr>
</script>

<script id="summaryTmpl" type="text/x-jsrender">
	<tr><td>{{:key}}</td><td>{{:value}}</td></tr>
</script>
	
