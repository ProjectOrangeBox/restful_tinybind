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

	<!-- integer input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_columns_model','bootstrap_md_col') ?>
		<div class="col-md-2">
			<?=pear::input('bootstrap_md_col',$record->bootstrap_md_col,['data-mask'=>'int','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_columns_model','column') ?>
		<div class="col-md-9">
			<?=pear::input('column',$record->column,['class'=>'form-control','autocomplete'=>'off','maxlength'=>128]) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_columns_model','details_gui_file') ?>
		<div class="col-md-7">
			<?=pear::input('details_gui_file',$record->details_gui_file,['class'=>'form-control','autocomplete'=>'off','maxlength'=>64]) ?>
		</div>
	</div>

	<!-- textarea input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_columns_model','details_gui_manual') ?>
		<div class="col-md-9">
			<?=pear::textarea('details_gui_manual',$record->details_gui_manual,['cols'=>'6','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_columns_model','human') ?>
		<div class="col-md-9">
			<?=pear::input('human',$record->human,['class'=>'form-control','autocomplete'=>'off','maxlength'=>128]) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_columns_model','index_gui_file') ?>
		<div class="col-md-7">
			<?=pear::input('index_gui_file',$record->index_gui_file,['class'=>'form-control','autocomplete'=>'off','maxlength'=>64]) ?>
		</div>
	</div>

	<!-- textarea input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_columns_model','index_gui_manual') ?>
		<div class="col-md-9">
			<?=pear::textarea('index_gui_manual',$record->index_gui_manual,['cols'=>'6','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_columns_model','related_to_display_column') ?>
		<div class="col-md-7">
			<?=pear::input('related_to_display_column',$record->related_to_display_column,['class'=>'form-control','autocomplete'=>'off','maxlength'=>64]) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_columns_model','related_to_secondary_key') ?>
		<div class="col-md-7">
			<?=pear::input('related_to_secondary_key',$record->related_to_secondary_key,['class'=>'form-control','autocomplete'=>'off','maxlength'=>64]) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_columns_model','related_to_tablename') ?>
		<div class="col-md-7">
			<?=pear::input('related_to_tablename',$record->related_to_tablename,['class'=>'form-control','autocomplete'=>'off','maxlength'=>64]) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_columns_model','rules') ?>
		<div class="col-md-9">
			<?=pear::input('rules',$record->rules,['class'=>'form-control','autocomplete'=>'off','maxlength'=>512]) ?>
		</div>
	</div>

	<!-- Checkbox -->
	<div class="form-group">
		<div class="col-md-offset-3 col-md-4">
			<div class="checkbox">
				<label>
					<?=pear::checkbox('show_on_details', 1,($record->show_on_details == 1),['class'=>'js-checker']) ?> <?=pear::field_human('Orange_scaffolding_columns_model','show_on_details') ?>
				</label>
			</div>
		</div>
	</div>

	<!-- Checkbox -->
	<div class="form-group">
		<div class="col-md-offset-3 col-md-4">
			<div class="checkbox">
				<label>
					<?=pear::checkbox('show_on_index', 1,($record->show_on_index == 1),['class'=>'js-checker']) ?> <?=pear::field_human('Orange_scaffolding_columns_model','show_on_index') ?>
				</label>
			</div>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Orange_scaffolding_columns_model','table') ?>
		<div class="col-md-9">
			<?=pear::input('table',$record->table,['class'=>'form-control','autocomplete'=>'off','maxlength'=>128]) ?>
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
