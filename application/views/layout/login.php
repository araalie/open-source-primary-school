<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>
			<?php if(isset($page_title)) {echo $page_title.' - School Management System';} else echo 'School Management System'; ?>
		</title>
		<link rel="stylesheet" href="<?=base_url() ;?>assets/css/bootstrap.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?=base_url() ;?>assets/css/bootstrap-responsive.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?=base_url() ;?>assets/css/skin1/style.css?v=1h.2aqf8JKs" type="text/css" media="screen" />
		<script src="<?=base_url() ;?>assets/js/jquery-1.7.2.min.js" type="text/javascript"></script>
		<script src="<?=base_url() ;?>assets/js/components/jquery.cookie.js" type="text/javascript"></script>
		<script src="<?=base_url() ;?>assets/js/components/jquery.accordion.min.js" type="text/javascript"></script>
		<script src="<?=base_url() ;?>assets/js/components/zebra_tooltips.js" type="text/javascript"></script>
		<script src="<?=base_url() ;?>assets/js/components/bootstrap-tab.js" type="text/javascript"></script>
		<script src="<?=base_url() ;?>assets/js/components/zebra_datepicker.js" type="text/javascript"></script>
		<script src="<?=base_url() ;?>assets/js/components/zebra_dialog.js" type="text/javascript"></script>
		<script src="<?=base_url() ;?>assets/js/components/jquery.metadata.js" type="text/javascript"></script>
		<script src="<?=base_url() ;?>assets/js/components/jquery.validate.min.js" type="text/javascript"></script>
		<script src="<?=base_url() ;?>assets/js/components2/jsrender.js" type="text/javascript"></script>
		<script src="<?=base_url() ;?>assets/js/components2/jquery.observable.js" type="text/javascript"></script>
		<script src="<?=base_url() ;?>assets/js/components2/jquery.views.js" type="text/javascript"></script>
		<script src="<?=base_url() ;?>assets/js/components2/jpages.min.js" type="text/javascript"></script>
		<script src="<?=base_url() ;?>assets/js/components2/jquery.colorbox-min.js" type="text/javascript"></script>
		<script src="<?=base_url() ;?>assets/js/components2/jshashtable.js" type="text/javascript"></script>
		<script src="<?=base_url() ;?>assets/js/components2/gips.js" type="text/javascript"></script>
		<script src="<?=base_url() ;?>assets/js/custom.js" type="text/javascript"></script>
<script>
var SchoolUniverse = {
	Base: "<?php echo base_url(); ?>",
	Assets: "<?php echo base_url(); ?>assets/"
}
</script>
	</head>
	<body id="login-splash">
		<div class="container-fluid" >
			<div class="row-fluid">
				<div class="span12">
					<div id="login-wrap">
						<div id="login-box" >
							<div id="login-a">
							</div>
							<div id="login-b">
						<?php $this->load->view('auth/login_form');?>		
							</div>
						
						</div>
					</div>
				</div>
				<!--/span-->
			</div>
			<footer>
				<div class="row-fluid">
					<div class="span12">
					<h3 id='school-motto'>**<?= GetAppVariable('school_motto') ?>**</h3>
						<p id='foot'>						
							<?= GetAppVariable('school_name').' &copy; '.date('Y'); ?>
							<br />
								<?= GetAppVariable('school_address') ?>
						</p>
					</div>
				</div>
			</footer>
		</div>
		<!--/.fluid-container-->
		<script type="text/javascript">
$(document).ready(function () {
  
});
</script>
	</body>
</html>
