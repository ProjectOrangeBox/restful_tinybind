<? pear::extends('_templates/orange_admin') ?>

<? pear::section('section_container') ?>

<div class="row">
  <div class="col-md-6"><?=pear::title('Dashboard','th') ?></div>
  <div class="col-md-6"></div>
</div>

<div class="row">

	<? pear::include('/admin/dashboard/index_block',['url::/backorder::index~get','/backorder','Backorder Mgr']) ?>

	<? pear::include('/admin/dashboard/index_block',['url::/backorder_call_center::index~get','/backorder-call-center','Backorder Search']) ?>

	<? pear::include('/admin/dashboard/index_block',['url::/stock_status_check::index~get','/stock-status-check','Stock Status Check']) ?>

</div>

<? pear::end() ?>

<? pear::section('page_style') ?>
.uri-dashboard a.btn.btn-lg.btn-block {
	background-color: #0b6854;
	color: white;
	transition:all 0.3s ease;
  opacity:0.9;
  color: white;
}
.uri-dashboard a.btn.btn-lg.btn-block:hover {
  -webkit-transform: scale(1.05);
  -ms-transform: scale(1.05);
  transform: scale(1.02);
  opacity:1;
  color: #fcd05c;
}
<? pear::end() ?>
