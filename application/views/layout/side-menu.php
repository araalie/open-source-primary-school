<div class="well" id="sidebar-nav">
<?= AccessManager::getMenu(); ?>
</div>
<!--/.well -->
<script type="text/javascript">
$(document).ready(function() {
	$('h3.nav-header').accordion({
		cookieName: 'bf161de6-7e0b-42ef-a244-f8809b738238_nav',
		speed: 'slow'
		});
	
	});
</script>