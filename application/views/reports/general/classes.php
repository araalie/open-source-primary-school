<h4>
	General Class Reports : Currently Enrolled Students  
</h4>
<div class="container-fluid">
<div class="row-fluid">
<div class="span6">
	<hr/>
<div class="control-group">
<label class="control-label" for="classi_id">By Class</label>
<div class="controls">
<?=form_dropdown('classi_id', $classis, NULL, 'id="classi_id" class="span4" '); ?>
</div>
</div>
</div>
</div>
<div class="row-fluid">
<div class="span8">
<table class="table table-striped table-bordered table-condensed zeb">
<thead>
<tr>
<th>
#
</th>
<th>
Surname
</th>
<th>
First Name
</th>
<th>
Gender
</th>
<th>
Student No.
</th>
<th>
En. Year
</th>
<th>
Fees Profile
</th>
<th>
House
</th>
</tr>
</thead>
<tbody id="class-list"></tbody>
<tfoot>
</tfoot>
</table>
<h6>Class Statistics</h6>
<div id='stats'>
	
</div>
<hr/>
<button data-url="#" class="btn btn-primary" id="class-report-pdf" >Get Report</button>	
	</div>
	</div>
</div>


<script type="text/javascript">
$(document).ready(function () {
	
	$('#classi_id').change(function () {
		LoadStudents();
		$('#classi_id').blur();
		});	

    $('#class-report-pdf').click(function (e) {
        var url = $(this).data('url');
        if (url != '#') {
            $(this).colorbox({
                iframe: true,
                width: "96%",
                height: "96%",
                href: url
            });
        }

    });
    
	});


function LoadStudents(){	

var classiId = $('#classi_id').val();

	$("#class-list").html('');
	$('#class-report-pdf').data('url','#');
	$.post(SchoolUniverse.Base + 'reports_classes/get_students/',
	{class_instance_id: classiId},	
	function (data) {
		
		if (data && data.success) {
			
			var html = $("#hitsTmpl").render(data.hits);
            
            $("#class-list").html(html);
            
   			var stats='<table class="table table-striped table-bordered table-condensed zeb">';         
            $.each(data.stats, function(key, value) { 
			  stats+= '<tr><td colspan="3" style="background:#fff !important;"><b>'+ key + '</b></td></tr>';
			  stats+='<tr><td>Category</td><td>Number of Students</td><td>Percentage</td></tr>';
				$.each(value, function(k1, v1) {
				stats+='<tr><td>'+k1+'</td><td>'+v1.number+'</td><td>'+v1.pct+'%</td></tr>';				
				});  
					
			  stats+='</tr>'; 
			});

		$("#stats").html(stats);
		$('#class-report-pdf').data('url', SchoolUniverse.Base + 'reports_classes/class_list/' + classiId);
			} else {
			//DisplayPopup('error|Student Has No Account|' + data.msg);
		}
		
		
		}, 'json');
		
}
</script>

<script id="hitsTmpl" type="text/x-jsrender">
	<tr>
		<td>{{:#index+1}}</td>
		<td>{{:surname}}</td>
		<td>{{:first_name}}</td>
		<td>{{:gender}}</td>
		<td>{{:student_number}}</td>
		<td>{{:enrolled}}</td>
		<td>{{:fees_profile}}</td>
		<td>{{:house}}</td>
	</tr>
</script>

