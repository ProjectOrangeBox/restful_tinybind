<? pear::extends('_templates/orange_admin') ?>

<? pear::section('section_container') ?>

<?=pear::open_multipart($controller_path,['class'=>'form-horizontal','method'=>$form_method,'data-success'=>'Record Saved|blue'],['blog_id'=>$record->blog_id]) ?>
	<div class="row">
		<div class="col-md-6"><h3><i class="fa fa-cog"></i> <?=$ci_title_prefix ?> <?=$controller_title ?></h3></div>
	  <div class="col-md-6">
	  	<div class="pull-right">
				<?=pear::goback_button($controller_path) ?>
	  	</div>
	  </div>
	</div>

	<hr>


	<!-- integer input-->
	<div class="form-group">
		<?=pear::field_label('blogs_model','blog_id') ?>
		<div class="col-md-2">
			<?=pear::input('blog_id',$record->blog_id,['data-mask'=>'int','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>
	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('blogs_model','blog_title') ?>
		<div class="col-md-9">
			<?=pear::input('blog_title',$record->blog_title,['class'=>'form-control','autocomplete'=>'off','maxlength'=>100]) ?>
		</div>
	</div>
	<!-- textarea input-->
	<div class="form-group">
		<?=pear::field_label('blogs_model','blog_description') ?>
		<div class="col-md-9">
			<?=pear::textarea('blog_description',$record->blog_description,['cols'=>'6','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>
	<!-- float input-->
	<div class="form-group">
		<?=pear::field_label('blogs_model','blog_price') ?>
		<div class="col-md-2">
			<?=pear::input('blog_price',$record->blog_price,['data-mask'=>'float','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>
	<!-- integer input-->
	<div class="form-group">
		<?=pear::field_label('blogs_model','blog_sort_order') ?>
		<div class="col-md-2">
			<?=pear::input('blog_sort_order',$record->blog_sort_order,['data-mask'=>'int','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>
	<!-- Checkbox -->
	<div class="form-group">
		<div class="col-md-offset-3 col-md-4">
			<div class="checkbox">
				<label>
					<?=pear::checkbox('checkers', 1,($record->checkers == 1),['class'=>'js-checker']) ?> <?=pear::field_human('blogs_model','checkers') ?>
				</label>
			</div>
		</div>
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
