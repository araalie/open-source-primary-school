<script src="<?=base_url() ;?>assets/js/components3/pschecker.js" type="text/javascript"></script>
<h4> Manage your user account </h4>
<hr/>
<div class="container">
	<div class="row-fluid">
		<div class="span3">
			<h6>Change Password</h6>
				<?= form_open('account/change_password', array('class' => "form-horizontal password-container", 'id'=>'change_passwd_form')); ?>
				<fieldset>
					<div class="control-group">
						<label class="control-label" for="old_passwd">Old password</label>
						<div class="controls">
							<input class="input-small " name="old_passwd" id="old_passwd" type="password" value="">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="new_passwd">New password</label>
						<div class="controls">
							<input class="input-small strong-password" name="new_passwd" id="new_passwd" type="password" value="">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="new2_passwd">New again password</label>
						<div class="controls">
							<input class="input-small strong-password" name="new2_passwd" id="new2_passwd" type="password" value="">
						</div>
					</div>
					<hr/>
					<span class="error">Password must be 8 characters long</span>
					<button class="btn" style="float:right;margin-right:5%;" id="change_passwd" disabled="disabled" >Change Password</button>
				</fieldset>
			</form>
		</div>
		<div class="span3">
			<div class="strength-indicator">
                <div class="meter">
                </div>
                Strong passwords contain 8-16 characters, do not include common words or names,
                and combine uppercase letters, lowercase letters, numbers, and symbols.
            </div>
		</div>
	</div>
		<div class="row-fluid">
		<div class="span10">
			<hr/>
			<h3>Your Latest Activity</h3>
					<table class="table table-striped table-bordered table-condensed zeb">
		<thead>
		<tr>
		<th>#</th>
		<th>Date &amp; time</th>
		<th>System Object</th>
		<th>Narrative</th>
		</tr>
		</thead>
		<tbody>
			<?php $i=1; 
			foreach($latest as $log){
				echo '<tr><td>'.$i++.'</td><td>'.$log['date_created']->format('d.M.Y h:i A')
				.'</td><td>'.$log['object_type']
				.'</td><td>'.$log['narrative']				
				.'</td></tr>';
			}
			?>
			
			</tbody>
			</table>
			</div>
			</div>
</div>
    <script type="text/javascript">
        $(document).ready(function () {
            //Demo code
            $('.password-container').pschecker({ onPasswordValidate: validatePassword, onPasswordMatch: matchPassword });

            var submitbutton = $('.submit-button');

            var errorBox = $('.error');
            errorBox.css('visibility', 'hidden');
            submitbutton.attr("disabled", "disabled");

            //this function will handle onPasswordValidate callback, which mererly checks the password against minimum length

            function validatePassword(isValid) {

                if (!isValid)
                    errorBox.css('visibility', 'visible');
                else
                    errorBox.css('visibility', 'hidden');
            }

            //this function will be called when both passwords match

            function matchPassword(isMatched) {
                if (isMatched) {
                    
                    $('#change_passwd').removeAttr("disabled");
                }
                else {
                    $('#change_passwd').attr("disabled", "disabled");
                }

            }
            
            $('#change_passwd').click(function (e) {

            	var jqxhr = $.post($("#change_passwd_form").attr('action'), $("#change_passwd_form").serialize(),

		            function (data) {
		
		                if (data.success) {
		                    
		                    $.Zebra_Dialog( 'You have successfully changed your password.<br/>You will now be logged out.<br>'+
		                    'To login again, use your new password', {
						    'type':     'information',
						    'title':    'Password Changed Successfully',
						    'buttons':  ['OK',],
						    'onClose':  function(caption) {
        		                    clearFormElements('#change_passwd_form input');
		                    		window.location.href = SchoolUniverse.Base+'account/logout';
    							}
							});

		                } else {
		                    DisplayPopup('error|Password was not changed|' + data.msg);
		                }
            		},
            		'json');
        e.preventDefault();
    });

        });

    </script>