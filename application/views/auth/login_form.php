<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login-name',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
	'class'=>'span8'
);
if ($login_by_username AND $login_by_email) {
	$login_label = 'Email or login';
} else if ($login_by_username) {
	$login_label = 'Login';
} else {
	$login_label = 'Email';
}
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
	'class'=>'span8'
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0',
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
?>
<?=form_open('account/login'); ?>
<table>
	<tr>
		<td colspan="3" id='screen-logo'></td>
	</tr>
	<tr>
		<td><?php echo form_label($login_label, $login['id']); ?></td>
		<td><?php echo form_input($login); ?></td>
		<td style="color: red;"> </td>
	</tr>
	<tr>
		<td><?php echo form_label('Password', $password['id']); ?></td>
		<td><?php echo form_password($password); ?></td>
		<td style="color: red;"> </td>
	</tr>
	<tr>
		<td colspan="3" style="padding-left:50px;">
			<?php echo form_submit('submit', 'Login', "style='float: right !important;margin-right: 80px;' class='btn-primary'"); ?>
			<?php echo form_close(); ?>
		</td>
	</tr>
	<tr>
		<td colspan="3"  style="color: red; text-align: right;">
			<?php if(isset($errors[$login['name']])){
				echo 'Invalid Login';
			}else if(isset($errors[$password['name']])) { echo 'Invalid login';} ?>
		</td>
	</tr>
</table>
