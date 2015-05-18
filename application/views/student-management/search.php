<?php
$actionsTemplate = '<button class="btn btn-info dropdown-toggle" data-toggle="dropdown">Actions <span class="caret"></span></button>
                      <ul class="dropdown-menu" role="menu" aria-labelledby="drop1">
                        <li>%s</li>
                        <li>%s</li>
                      </ul>';
?>
<h4> Find Students </h4>
<hr/>
<br/>
<div class="row-fluid">
	<div class="span3">
		<div class="box">
			<h4 class="box-header">Search Criteria</h4>
			<div class="box-content">
<?= form_open('search_students/', array('id' => 'find_student', 'class' => 'form-horizontal edit-form')); ?>
<div class="control-group">
<label class="control-label control-label-90" for="search_name">Name</label>
<div class="controls controls2">
<input class="input-medium" name="search_name" id="search_name" type="text" value="<?= $search_name ?>">
</div>
</div>
<div class="control-group">
<label class="control-label control-label-90" for="sstd_num">Student No.</label>
<div class="controls controls2">
<input class="input-medium" name="sstd_num" id="sstd_num" type="text" value="<?= $sstd_num ?>">
</div>
</div>
<div class="control-group">
<label class="control-label control-label-90" for="sstudent_status">Status </label>
<div class="controls controls2">
<?=form_dropdown('sstudent_status', $states, $sstudent_status, '  name="sstudent_status" id="sstudent_status" class="input-large" style="width:100%;" '); ?>
</div>
</div>
<div>
	<input type="submit" class="btn btn-primary submit" name="search_students" value="Search" /> 
</div>
<?=form_close();?>
			</div>
		</div>
	</div>
	<div class="span9">
		<div class="box">
			<h4 class="box-header"><?=$found; ?></h4>
			<div class="box-content">
	<?php
			if(!is_null($hits)){
				?>
				<table class="table table-striped table-bordered table-condensed zeb">
					<thead>
						<tr>
							<th>#</th>
							<th>Surname</th>
							<th>First Name</th>
							<th>St. No.</th>
							<th>Action</th>
							<th>Last Class</th>
							<th>Fees Profile</th>
							<th>Status</th>
							<th>House</th>
						</tr>
					</thead>
					<tbody>
				<?php
				$i=1;
				foreach($hits as $h){
					$check = sprintf('<input type="checkbox" data-id="%s" name="found" class="found" data-names="%s" />', $h->getId(),
					$h->getSurname().' '.$h->getFirstName());
					$fprofile = 'N/A';
					$house ='N/A';
					$status = 'N/A';
					if(!is_null($h->getFeesProfile())){
						$fprofile = $h->getFeesProfile()->getName();
					}

					if(!is_null($h->getHouse())){
						$house = $h->getHouse()->getName();
					}

					if(!is_null($h->getStudentStatus())){
						$status = $h->getStudentStatus()->getName();
					}					
					echo '<tr><td>'
						.$i.' '.$check.'</td><td>'
						.$h->getSurname().'</td><td>'
						.$h->getFirstName().'</td><td>'
						.$h->getStudentNumber().'</td><td class="dropdown" style="background:#fefefe !important;text-align:center;">'
						.sprintf($actionsTemplate,anchor('enrollment/edit/'.$h->getId(), 'Edit'),anchor('masterview/'.$h->getId(),'Master View')).'</td><td>'
						.$h->getClassInstance()->getName().'</td><td>'
						.$fprofile.'</td><td>'
						.$status.'</td><td>'
						.$house
						.'</td></tr>';
					$i++;
				}
				
				?>
				</tbody>
				</table><hr />
				<a class="btn btn-primary btn-large" id="change-state" href="#"><i class="icon-edit icon-white"></i> Change Status of Selected Students</a>
				<?php
				
			}
	?>
			</div>
		</div>		
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	
	$('.dropdown-toggle').dropdown();
								
								
		$('body').on('click', '#change-student-state', function(e) {

				var jqxhr = $.post(SchoolUniverse.Base+'student_status/change', {'ids':$('#selected-values').val(), 'next_state':$('#next-state').val()}, function(data) {

					if (data.success) {

						DisplayPopup('success|Successful Change of State|' + data.msg);
					} else {
						DisplayPopup('error|State change failed.|' + data.msg);
					}

				}, 'json');
				
			e.preventDefault();
		});
										
	$('#change-state').click(function(){
					
			var selected = new Array();
			var listUI='';
			var items='';
			

  			$("input[type='checkbox'].found").each(function() {
  				if($(this).is(':checked')){
  					selected.push($(this).data());	
  				}
  			});

		if(selected.length>0){
			for(i=0;i<selected.length;i++){
				listUI+=selected[i].id+'|';
				items+='<li><b>'+selected[i].names+'</b></li>';
			}
			
			var states =  $("#sstudent_status").clone(true).html();
			console.log(states)
			items+='<li><select id="next-state">'+states+'</select></li>';
			
			var body='<ol>'+items+'<input type="hidden" id="selected-values" value="'+listUI+'" />'+'</ol>';
			var footer = '<a href="#" class="btn btn-primary" id="change-student-state" data-dismiss="modal">Change state</a>';
			var title ='<h3>Students to Change</h3>';
		}else{
			var title = '<h3>No Students Selected</h3>';
			var body = '<p>Select at least one student first.</p>';
			var footer ='';
		}
		  var tmpl = [
		  
		    '<div class="modal hide fade" tabindex="-1">',
		      '<div class="modal-header">',
		        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>',
		        title, 
		      '</div>',
		      '<div class="modal-body">',
		        body,
		      '</div>',
		      '<div class="modal-footer">',
		        '<a href="#" data-dismiss="modal" class="btn">Cancel</a>',
		        footer,
		      '</div>',
		    '</div>'
		  ].join('');
		  
		  $(tmpl).modal();
	});

			});
		</script>