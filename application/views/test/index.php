<? pear::extends('_templates/orange_admin') ?>

<? pear::section('section_container') ?>

<?=pear::open_multipart('',['class'=>'form-horizontal','method'=>'post','data-success'=>'Record Saved|blue'],['id'=>$record['id']]) ?>

	<div class="row">
		<div class="col-md-6"><h3><i class="fa fa-cog"></i> <?=$ci_title_prefix ?> <?=$controller_title ?></h3></div>
		<div class="col-md-6"></div>
	</div>

	<hr>

	<?=pear::repeatable('name-here') ?>

	<div class="repeatable name-here">
		<? foreach ($record['repeatable'] as $rec) { ?>
			<div class="repeat-grouping">

			<!-- Text input-->
			<div class="form-group">
				<label class="col-md-3 control-label" for="textinput">First Name</label>
				<div class="col-md-9">
					<?=pear::input('firstname['.$rec['id'].']',$rec['firstname'],['class'=>'form-control input-md']) ?>
				</div>
			</div>

			<!-- Text input-->
			<div class="form-group">
				<label class="col-md-3 control-label" for="textinput">Last Name</label>
				<div class="col-md-9">
					<?=pear::input('lastname['.$rec['id'].']',$rec['lastname'],['class'=>'form-control input-md']) ?>
				</div>
			</div>
			<div class="form-group text-right"><div class="col-md-12"><a class="btn btn-default js-repeatable-remove-button btn-xs" href="#"><i class="fa fa-minus-square" aria-hidden="true"></i> '+remove_text+'</a></div></div>
		</div>

	<? } ?>

	</div>


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
