<h2>Dashboard</h2>
<?php
$this->load->library('Chartingservice');
$cSvc = new Chartingservice();

$class_state_graph = $cSvc->classStatistics();
?>
<div class="row-fluid">
<div class="span6">
<img src="<?= $class_state_graph; ?>" alt="Class Statistics" />
</div>
</div>