<? pear::extends('_templates/orange_admin') ?>

<? pear::section('section_container') ?>

<div class="row">
  <div class="col-md-6"><?=pear::title('Dashboard','th') ?></div>
  <div class="col-md-6"></div>
</div>

<div class="row">

	<? pear::include('/main/index_block',['','/backorder-call-center','Backorder<br>Search']) ?>

	<? pear::include('/main/index_block',['url::/admin/backorder::index~get','/admin/backorder','Backorder<br>Manager','user-circle-o']) ?>

	<? pear::include('/main/index_block',['url::/admin/backorder_buyer::index~get','/admin/backorder_buyer','Backorder<br>Buyer Groups','user-circle-o']) ?>

</div>

<? pear::end() ?>

<? pear::section('page_body_class') ?><? pear::parent() ?> dashboard<? pear::end() ?>

<? pear::section('page_style') ?>
.dashboard a.btn.btn-lg.btn-block {
	background-color: #0b6854;
	color: white;
	transition:all 0.3s ease;
  opacity:0.9;
  color: white;
}
.dashboard a.btn.btn-lg.btn-block:hover {
  -webkit-transform: scale(1.05);
  -ms-transform: scale(1.05);
  transform: scale(1.02);
  opacity:1;
  color: #fcd05c;
}
<? pear::end() ?>
