<? pear::extends('_templates/orange_admin') ?>

<? pear::section('section_container') ?>

<div class="row">
  <div class="col-md-6"><?=pear::title('Dashboard','th') ?></div>
  <div class="col-md-6"></div>
</div>

<div class="row">

	<? pear::include('/main/index_block',['','/backorder-call-center','Backorder<br>Search']) ?>
	<? pear::include('/main/index_block',['','/dropships','Dropship<br>Search']) ?>
	<? pear::include('/main/index_block',['','/backorder_k1','Backorder<br>K1s']) ?>

</div>

<div class="row">
	<?php if (user::can('url::/admin/tasks::index~get')) { ?>
		<div class="alert alert-danger" role="alert">
			If you have any problems, changes, ideas, etc... please submit them with the "<i class="fa fa-bug"></i> New Request" button below.
		</div>
	<?php } ?>

	<? pear::include('/main/index_block',['url::/admin/backorder::index~get','/admin/backorder','Backorder<br>MGR','industry','265476']) ?>

	<? pear::include('/main/index_block',['url::/admin/backorderimport::index~get','/admin/backorderimport','Backorder<br>Import','upload','265476']) ?>

	<? pear::include('/main/index_block',['url::/admin/backorder_buyer::index~get','/admin/backorder_buyer','Backorder<br>Buyer Groups','list','265476']) ?>

	<? pear::include('/main/index_block',['url::/admin/dropships::index~get','/admin/dropships','Drop Ship<br>MGR','industry','487626']) ?>

	<? pear::include('/main/index_block',['url::/admin/tasks::index~get','/admin/tasks/details','New<br>Request','bug','cc2f0c']) ?>

	<? pear::include('/main/index_block',['url::/admin/backorder_find_bo::index~get','/admin/backorder_find_bo','Find<br>BOs','industry','265476']) ?>

	<? pear::include('/main/index_block',['url::/admin/backorder_item_lookup::index~get','/admin/backorder_item_lookup','Backorder<br>Item Lookup','industry','265476']) ?>

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
