<? pear::extends('_templates/orange_admin') ?>

<? pear::section('section_container') ?>

<div class="row">
  <div class="col-md-6"><?=pear::title('Dashboard','th') ?></div>
  <div class="col-md-6"></div>
</div>

<div class="row">

	<? pear::include('/main/index_block',['','/backorder-call-center','Backorder<br>Search']) ?>
	<? pear::include('/main/index_block',['','/dropships','Dropship<br>Search']) ?>

</div>

<div class="row">
	<?php if (user::logged_in()) { ?>
		<div class="alert alert-danger" role="alert">
			If you have any problems, changes, ideas, etc... please submit them with the "<i class="fa fa-bug"></i> New Request" button below.
		</div>
	<?php } ?>

	<? pear::include('/main/index_block',['url::/admin/backorder::index~get','/admin/backorder','Backorder<br>MGR','industry','265476']) ?>

	<? pear::include('/main/index_block',['url::/admin/backorder_buyer::index~get','/admin/backorder_buyer','Backorder<br>Buyer Groups','list','265476']) ?>

	<? pear::include('/main/index_block',['url::/admin/dropships::index~get','/admin/dropships','Drop Ship<br>MGR','industry','487626']) ?>

	<? pear::include('/main/index_block',['url::/admin/tasks::index~get','/admin/tasks/details','New<br>Request','bug','cc2f0c']) ?>

</div>

<? pear::end() ?>

<? pear::section('page_body_class') ?><? pear::parent() ?> dashboard<? pear::end() ?>

<? pear::section('page_style') ?>
.dashboard a.btn.btn-lg.btn-block {
	transition:all 0.3s ease;
  opacity:0.9;
  color: white;
}
.dashboard a.btn.btn-lg.btn-block:hover {
  -webkit-transform: scale(1.05);
  -ms-transform: scale(1.05);
  transform: scale(1.02);
  opacity:1;
  color: #eee;
}
<? pear::end() ?>
