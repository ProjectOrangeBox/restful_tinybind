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

<?=pear::end() ?>

<? pear::section('page_css') ?>
<? pear::parent() ?>
<link href="/assets/backorder/backorder.min.css" rel="stylesheet" type="text/css" />
<? pear::end() ?>
