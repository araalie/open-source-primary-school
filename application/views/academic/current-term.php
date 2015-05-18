<h4>
Current Term
</h4>
<br/><br/>
<?
if($result['success']){
	echo '<div class="alert alert-success custom-tick">'.$result['term']->getName().' is the current term.'.'</div>';
}else{
	echo '<div class="alert alert-error custom-error">'.$result['msg'].'</div>';
}
?>
<br/><br/>
<hr/>
<?
echo anchor('academic_terms/', 
			'<< &nbsp;&nbsp;Return to Term Management',
			'class="btn btn-medium btn-primary"  title="Return to the Term Management Page"');
?>