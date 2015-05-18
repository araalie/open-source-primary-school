<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>
			<?php
			if (isset($page_title)) {echo $page_title . ' - School Management System';
			} else
				echo 'School Management System';
 ?>
		</title>
		<link rel="stylesheet" href="<?=base_url(); ?>assets/css/bootstrap.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?=base_url(); ?>assets/css/bootstrap-responsive.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?=base_url(); ?>assets/css/skin1/style.css?v=4.2D5m_(e" type="text/css" media="screen" />
		<script src="<?=base_url(); ?>assets/js/jquery-1.7.2.min.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components/jquery.cookie.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components/jquery.accordion.min.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components/zebra_tooltips.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components/bootstrap.min.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components/bootstrap-tab.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components/bootstrap-datepicker.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components/bootstrap-dropdown.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components/zebra_datepicker.js?v=1.6.1" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components/zebra_dialog.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components/jquery.metadata.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components/jquery.validate.min.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components2/jsrender.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components2/jquery.observable.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components2/jquery.views.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components2/jpages.min.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components2/jquery.colorbox-min.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components2/jshashtable.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components2/gips.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components3/sticky.min.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components3/sprintf-0.7-beta1.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/components3/collapsible-dl.js" type="text/javascript"></script>
		<script src="<?=base_url(); ?>assets/js/custom.js?V=u7.7ayu0h" type="text/javascript"></script>
<script  type="text/javascript">
	var SchoolUniverse = {
	Base: "<?=base_url(); ?>",
	Assets: "<?=base_url(); ?>assets/"};
</script>
	</head>
	<body>
	<div id="top-band">
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span9">
					<div id="header">
						<div id="logo">
						</div>
						<div id="desc">
							<h3>
								<?=anchor('/', GetAppVariable('school_name'), 'title="' . GetAppVariable('school_name') . ' [HOME]" ');

								$term = Utilities::getCurrentTerm();
								if (is_null($term)) {
									echo '<br/>NOT SET';
									//WriteLog( LogAction::Error,'term', -1, 'No Current term set!' );
								} else {
									echo '<br/>' . $term -> getName();
								}
				?>
							</h3>
						</div>
					</div>
				</div>
				<div class="span3">
					<div id="exit-me"><?=anchor('account/logout', $this -> session -> userdata('username'), 'title="Log Out" id="exit-session"'); ?></div>
				</div>
			</div>
			</div>
			</div>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span2">
					<?php $this -> load -> view('layout/side-menu'); ?>
				</div>
				<!--/span-->
				<div class="span10">
					<div class="main-content">
						<?php
						if (isset($page)) { $this -> load -> view($page);
						} else { $this -> load -> view('layout/welcome');
						}
					?>
					</div>
				</div>
				<!--/span-->
			</div>
			<!--/row-->
			<hr>
			<footer>
				<div class="row-fluid">
					<div class="span12">
					<h3 id='school-motto'>**<?= GetAppVariable('school_motto') ?>**</h3>
						<p id='foot'>						
							<?= GetAppVariable('school_name') . ' &copy; ' . date('Y'); ?>
							<br />
								<?= GetAppVariable('school_address') ?>
						</p>
					</div>
				</div>
			</footer>
		</div>
		<!--/.fluid-container-->
		<script type="text/javascript">
			$(document).ready(function() {
				$('.collapsy').collapsible_dl();
			});
		</script>
<div id="loading">Loading</div>
	</body>
</html>
