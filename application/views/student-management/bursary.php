<h4 class="section-title">
Bursary Management
</h4>
<div class="row-fluid">
<div class="span12">
<h5 class="can-toggle">Search for Students</h5>
<div class="panel toggles">
<div class="control-group">
<div class="controls">
<?= form_open('student_payments/find', array('class' => 'form-horizontal edit-form', 'id'=>'search_form')); ?>
Classs : <?=form_dropdown('search_classi_id', $current_classes, valueOrBlank($search_classi_id), 'id="search_classi_id" class="span4" '); ?>
 &nbsp;&nbsp;&nbsp;St. # <input type="text" id='sno' name='sno' class="span1" />
 &nbsp;&nbsp;&nbsp;Name <input type="text" id='name' name='name' class="span1" />
<a href="#" class="btn btn-success" id="do_search">Search</a>
</form>
<div id='search_results'>
<table class="table table-striped table-bordered table-condensed zeb">
<caption>Search Results</caption>
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
<input  type="hidden" id="active-student-id" name='active_student_id' />
<div class="holder"></div>
</div>
</div>
</div>
</div>
</div>
</div>

<div class="row-fluid">
<div class="span4">
	<div class="panel">	
	<h3>GIVE BURSARY : <span id="student-name" class="mark1"></span></h3>
	<form class="horizontal" id="bursary-form">
		<div>
			<input type="hidden" id="active-student-id" name="student_id" />
			<label>Value in UGX</label><input type="text" class="small-input numeric bursary-input" name='bursary_amount' id='bursary_amount' />
		</div>
		<div>
			<label>Comments</label><textarea name='bursary_comments' class="bursary-input"></textarea>
		</div>
		<div style="padding-left: 60%">
			<button id="save-bursary" class="btn btn-alpha">Save</button>
		</div>
	</form>
	</div>
	</div>
<div class="span8">
	<div class="panel">
	<H3>CURRENT TERM BURSARY AWARDS</H3>
	<table class="table table-striped table-bordered table-condensed zeb">
<thead>
<tr>
<th>Given By</th>
<th>Date</th>
<th>Amount</th>
<th>Cancel</th>
</tr>
</thead>
<tbody id="this-term-bursary"></tbody>
<tfoot>
<tr><td colspan="3"><button data-url="#" class="btn btn-primary" id="fees-pdf" >Get Fees Report</button></td></tr>
</tfoot>
</table>
	</div>
	</div>
</div>	

<script type="text/javascript">
$(document).ready(function () {


		$('#fees-pdf').click(function(e) {
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
		
    $('#do_search').click(function () {

        var jqxhr = $.post(
        $("#search_form").attr('action'), $("#search_form").serialize(), function (data) {

            var html = $("#hitsTmpl").render(data);
            $("#search-hits").html('');
            $("#search-hits").html(html);

            $("div.holder").jPages({
                containerID: "search-hits",
                perPage: 5,
                startPage: 1,
                startRange: 1,
                midRange: 5,
                endRange: 1
            });

        }, 'json');
    });
    
    
    $('#save-bursary').click(function (e) {
    	e.preventDefault();    	
    	
    	var currentSid= $('#active-student-id').val();
    	
    	if(currentSid==undefined || currentSid==''){
    		$.sticky('Select a student first');
    		return;
    	}
    	
    	if($('#bursary_amount').val()>0){
    		
    		var inputs =formToObj("#bursary-form");
    		
    		inputs.student_id = currentSid;
    		
    	    var jqxhr = $.post(
			    SchoolUniverse.Base + 'bursaries/post/', inputs 
			    , function (data) {

		        if (data) {
		            if (data.success) {
		            	
		                DisplayPopup('info|Bursary Registered|Bursary has been registered successfully.');
		                
		                clearFormElements('.bursary-input');
		                getBursaries(currentSid);
		
		            } else {
		                DisplayPopup('error|Bursary not posted|' + data.msg);
		            }
		        }
		    }, 'json');
		    
		}
    });
    //select student
        //live
    $('body').on('click', 'a.select-student', function (e) {
        var cur = $(this).data();
        clearFormElements('.bursary-input');
        $('#active-student-id').val(cur.studentId);
        $('#student-name').html(cur.fullname);
        getBursaries(cur.studentId);
        e.preventDefault();
    });
    
    
        $('body').on('click', 'a.del-posting', function (e) {

        e.preventDefault();
        var info = $(this).data();

        new $.Zebra_Dialog('Do you want to delete <b>' + info.amount + " UGX</b> from <b>" + $('#student-name').text() + '</b> bursary?' 
        + '<br/><br/>Click yes to delete.', {
            'type': 'warning',
            'title': 'Delete Bursary Award?',
            'buttons': [{
                caption: 'Yes',
                callback: function () {
                    DeleteBursary(info.bursaryId);
                }
            },
            {
                caption: 'Cancel'
            }]
        });

    });

});


function getBursaries(studentId){
    $.get(SchoolUniverse.Base + 'bursaries/get/' + studentId, function (data) {
        var html = $("#bursaryTmpl").render(data);
        $("#this-term-bursary").html('');
        $("#this-term-bursary").html(html);
        
        $('#fees-pdf').data('url', SchoolUniverse.Base + 'fees_reports/index/' + studentId);
        
        
    }, 'json');	
}


function DeleteBursary(id) {
    var jqxhr = $.post(
    SchoolUniverse.Base + 'bursaries/delete/', {
        bursary_id: id
    }, function (data) {

        if (data) {
            if (data.success) {
                DisplayPopup('info|Entry Deleted|This bursary has been deleted successfully.');
				
				var currentSid= $('#active-student-id').val();
				
                getBursaries(currentSid);

            } else {
                DisplayPopup('error|Entry Not Deleted|' + data.msg);
            }
        }
    }, 'json');
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
		<td><a href="#" class="btn btn-primary select-student" data-student-id='{{:id}}'
		data-fullname="{{:surname}}, {{:first_name}}" data-class-id="{{:class_id}}"
		 data-fees-profile-id="{{:fees_profile_id}}">Select</a></td>
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