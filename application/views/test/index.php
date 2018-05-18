<? pear::extends('_templates/orange_admin') ?>

<? pear::section('section_container') ?>

<?=pear::repeatable() ?>

<?=pear::open_multipart('',['class'=>'form-horizontal','method'=>'post','data-success'=>'Record Saved|blue'],['id'=>$id]) ?>

	<div class="row">
		<div class="col-md-6"><h3><i class="fa fa-cog"></i> <?=$ci_title_prefix ?> <?=$controller_title ?></h3></div>
		<div class="col-md-6"></div>
	</div>

	<hr>

	<p>
		<?=pear::repeatable_add_button('repeatable-add-button','repeatable-template') ?>
	</p>
	
	<?=pear::sortable('sortable') ?>
	<div id="sortable" class="list-group">
	<? foreach ($repeatable as $record) { ?>
		<?=pear::include('test/repeatable',['parent_id'=>$id,'id'=>$record['id'],'firstname'=>$record['firstname'],'lastname'=>$record['lastname'],'checkers'=>$record['checkers']]) ?>
	<? } ?>
	</div>
	
	<?=pear::repeatable_start_template('repeatable-template','.repeatable-add-button','.repeatable-remove-button','.repeatable-group',8,1,'#sortable') ?>
		<?=pear::include('test/repeatable',['parent_id'=>$id]) ?>
	<?=pear::repeatable_end_template('repeatable-append') ?>

	<!-- Submit Button -->
	<div class="form-group">
		<div class="col-md-12">
			<div class="pull-right">
				<?=pear::button(null,'Save',['class'=>'js-button-submit keymaster-s btn btn-primary']) ?>
			</div>
		</div>
	</div>

<?=pear::close() ?>

<? pear::end() ?>

