<? page::extends('_templates/orange_admin') ?>

<? page::section('section_container') ?>

<div class="row">
  <div class="col-md-6"><?=html::title('Dashboard','th') ?></div>
  <div class="col-md-6"></div>
</div>

<div class="row">

	<?php if (user::can('url::/backorder/get~index')) { ?>
  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
		<a href="/backorder" class="dashboard-block"><div class="dashboard-block"><i class="fa fa-cogs fa-2x" aria-hidden="true"></i><br>Backorder Mgr</div></a>
  </div>
  <?php } ?>

  <?php if (user::can('url::/backorder_status/get~index')) { ?>
	<div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
		<a href="/stock-status-check" class="dashboard-block"><div class="dashboard-block"><i class="fa fa-cogs fa-2x" aria-hidden="true"></i><br>Stock Status Check</div></a>
  </div>
  <?php } ?>

</div>

<?=page::end() ?>

<? page::section('page_css') ?>
<? page::parent() ?>
<link href="/assets/backorder/backorder.min.css" rel="stylesheet" type="text/css" />
<? page::end() ?>
