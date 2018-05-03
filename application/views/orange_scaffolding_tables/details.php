<? pear::extends('_templates/orange_admin') ?>

<? pear::section('section_container') ?>

<?=pear::open_multipart($controller_path,['class'=>'form-horizontal','method'=>$form_method,'data-success'=>'Record Saved|blue'],['table'=>$record->table]) ?>
	<div class="row">
		<div class="col-md-6"><h3><i class="fa fa-cog"></i> <?=$ci_title_prefix ?> <?=$controller_title ?></h3></div>
	  <div class="col-md-6">
	  	<div class="pull-right">
				<?=pear::goback_button($controller_path) ?>
	  	</div>
	  </div>
	</div>

	<hr>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_tables_model','controller_limit_to') ?>
		<div class="col-md-9">
			<?=pear::input('controller_limit_to',$record->controller_limit_to,['class'=>'form-control','autocomplete'=>'off','maxlength'=>128]) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_tables_model','controller_name') ?>
		<div class="col-md-9">
			<?=pear::input('controller_name',$record->controller_name,['class'=>'form-control','autocomplete'=>'off','maxlength'=>128]) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_tables_model','controller_order_by') ?>
		<div class="col-md-9">
			<?=pear::input('controller_order_by',$record->controller_order_by,['class'=>'form-control','autocomplete'=>'off','maxlength'=>128]) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_tables_model','controller_title') ?>
		<div class="col-md-4">
			<?=pear::input('controller_title',$record->controller_title,['class'=>'form-control','autocomplete'=>'off','maxlength'=>32]) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_tables_model','controller_titles') ?>
		<div class="col-md-4">
			<?=pear::input('controller_titles',$record->controller_titles,['class'=>'form-control','autocomplete'=>'off','maxlength'=>32]) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_tables_model','fa_icon') ?>
		<div class="col-md-4">
			<?=pear::input('fa_icon',$record->fa_icon,['class'=>'form-control','autocomplete'=>'off','maxlength'=>32]) ?>
		</div>
	</div>

	<!-- textarea input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_tables_model','insert_requires') ?>
		<div class="col-md-9">
			<?=pear::textarea('insert_requires',$record->insert_requires,['cols'=>'6','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_tables_model','new_button_txt') ?>
		<div class="col-md-4">
			<?=pear::input('new_button_txt',$record->new_button_txt,['class'=>'form-control','autocomplete'=>'off','maxlength'=>32]) ?>
		</div>
	</div>

	<!-- Checkbox -->
	<div class="form-group">
		<div class="col-md-offset-3 col-md-4">
			<div class="checkbox">
				<label>
					<?=pear::checkbox('primary_autogen', 1,($record->primary_autogen == 1),['class'=>'js-checker']) ?> <?=pear::field_human('Orange_scaffolding_tables_model','primary_autogen') ?>
				</label>
			</div>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_tables_model','primary_key') ?>
		<div class="col-md-9">
			<?=pear::input('primary_key',$record->primary_key,['class'=>'form-control','autocomplete'=>'off','maxlength'=>128]) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_tables_model','table') ?>
		<div class="col-md-9">
			<?=pear::input('table',$record->table,['class'=>'form-control','autocomplete'=>'off','maxlength'=>128]) ?>
		</div>
	</div>

	<!-- textarea input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_tables_model','update_requires') ?>
		<div class="col-md-9">
			<?=pear::textarea('update_requires',$record->update_requires,['cols'=>'6','class'=>'form-control','autocomplete'=>'off']) ?>
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
