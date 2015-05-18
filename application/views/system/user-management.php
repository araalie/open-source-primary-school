<h4>System User Management</h4>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<hr/>
			<?php
			
			if(!is_null($sys_users) && is_array($sys_users) && count($sys_users)>0){
				$table ='<table class="table table-striped table-bordered table-condensed zeb"><thead><tr><th>#</th><th>Surname</th><th>First Name</th><th>Username</th>'
				.'<th>Reset Password</th><th>Groups</th><th>-</th>'
				.'</tr></thead><tbody>';
				
				$j=1;
				foreach($sys_users as $u){
						
					$grps = explode("|", $u['groups']);
					
					$grpHtml=sprintf('<form id="staff_%d"><ol class="general">', $u['staff_id']);
					
					foreach($groups as $g){
							
							$checked = in_array($g, $grps)? 'checked="checked"':'';
							
							$value=$groups_flip[$g];
							$grpHtml.=sprintf('<li> %s <input type="checkbox" name="user_groups[]" value="%s" %s /></li>', $g,$g, $checked);
					}
					
					$grpHtml.=sprintf('<input type="hidden" name="staff_id" value="%d" />', $u['staff_id']);
					$grpHtml.='</ol><button class="grouped btn btn-custom1" style="margin-left:50%">Update Membership</button>';
					$grpHtml.='</form>';

					
					$data_attrs=" data-staff-id='".$u['staff_id']."' "
					." data-user-id='".$u['user_id']."' "
					." data-fullname='".$u['first_name'].' '.$u['surname']."' "
					." data-username='".$u['username']."' ";
										
					$table.='<tr id="staff_'.$u['staff_id'].'" '.$data_attrs. '><td>'.$j++.'</td><td>'
					.$u['surname'].'</td><td>'.$u['first_name']
					.'</td><td>'.$u['username']
					.'</td><td>'.sprintf('<a href="#" class="reset-passwd" %s>Reset</a>', $data_attrs)
					.'</td><td>'.$grpHtml
					.'</td></tr>';
				}
				
				$table.='</tbody></table>';
				
				echo $table;
			}
			?>
			</div>
		</div>
</div>
<script type="text/javascript">
$(document).ready(function () {
	
	$('.reset-passwd').click(function (e) {
			
			e.preventDefault();
			var info = $(this).data();
			
			ResetPassword(info);
    });
    
    
    $('body').on('click', '#reset-password', function(e) {
    	e.preventDefault();

		var passleng =$('#new_pass').val().length   	
    	var form =  $(this).closest('form');
    	
    	if(passleng<6){
    		return;
    	}
    	

				var jqxhr = $.post(SchoolUniverse.Base + 'user_accounts/reset_user_password/', 
				formToObj('#'+form.attr('id')), 
				function(data) {

					if (data.success) {
						dlg.forceClose();
						DisplayPopup('info|Success|' + data.msg);
					} else {
						DisplayPopup('error|Password was not reset|' + data.msg);
					}

				}, 'json');
  });
  
  
      $('body').on('click', '.grouped', function(e) {
      	
    	e.preventDefault();

    	var form =  $(this).closest('form');
    	
		$.post(SchoolUniverse.Base + 'user_accounts/edit_membership/',form.serialize(), 
				function(data) {

					if (data.success) {
						
						DisplayPopup('info|Success|' + data.msg);
					} else {
						DisplayPopup('error|Password was not reset|' + data.msg);
					}

				}, 'json');
  });
	
});

function ResetPassword(info){
	
	var form = sprintf('<form id="reset-password-form"><input type="hidden" name="user_id" value="%s">'
		+ '<input type="hidden" name="staff_id" value="%s">' 
		+ '<ul class="general"><li><span>Name: </span> %s </li><li><span>New Password: </span><input type="password" id="new_pass" name="new_pass" class="input-small"/></li>'
		+ '<li>*Passwords must be at least 6 characters</li>'
		+'<button class="btn btn-primary" id="reset-password" style="margin-left:40%%;">Reset Password</button></li></ul></form>',info.userId, info.staffId, info.fullname);

	dlg =new $.Zebra_Dialog(form, 
			{
				'type' : 'info',
				'title' : 'Password Reset',
				'buttons' : [{
					caption : 'Cancel',
					callback : function() {
						dlg.forceClose();
					}
				}],
				'manualClose':true
			});
			
	$('#new_pass').focus();
}

</script>