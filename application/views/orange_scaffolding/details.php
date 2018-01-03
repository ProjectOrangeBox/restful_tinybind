<? pear::extends('_templates/orange_admin') ?>

<? pear::section('section_container') ?>

<?=pear::open_multipart($controller_path,['class'=>'form-horizontal','method'=>$form_method,'data-success'=>'Record Saved|blue'],['table_column'=>$record->table_column]) ?>
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
		<?=pear::field_label('orange_scaffolding_model','table_column') ?>
		<div class="col-md-9">
			<?=pear::input('table_column',$record->table_column,['class'=>'form-control','autocomplete'=>'off','maxlength'=>255]) ?>
		</div>
	</div>

		<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('orange_scaffolding_model','human') ?>
		<div class="col-md-9">
			<?=pear::input('human',$record->human,['class'=>'form-control','autocomplete'=>'off','maxlength'=>128]) ?>
		</div>
	</div>

		<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('orange_scaffolding_model','bootstrap_md_col') ?>
		<div class="col-md-1">
			<?=pear::input('bootstrap_md_col',$record->bootstrap_md_col,['class'=>'form-control','autocomplete'=>'off','maxlength'=>10]) ?>
		</div>
	</div>

		<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('orange_scaffolding_model','details_gui') ?>
		<div class="col-md-9">
			<?=pear::input('details_gui',$record->details_gui,['class'=>'form-control','autocomplete'=>'off','maxlength'=>128]) ?>
		</div>
	</div>

		<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('orange_scaffolding_model','index_gui') ?>
		<div class="col-md-9">
			<?=pear::input('index_gui',$record->index_gui,['class'=>'form-control','autocomplete'=>'off','maxlength'=>128]) ?>
		</div>
	</div>

		<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('orange_scaffolding_model','override_rules') ?>
		<div class="col-md-1">
			<?=pear::input('override_rules',$record->override_rules,['class'=>'form-control','autocomplete'=>'off','maxlength'=>8]) ?>
		</div>
	</div>

		<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('orange_scaffolding_model','additional_rules') ?>
		<div class="col-md-9">
			<?=pear::input('additional_rules',$record->additional_rules,['class'=>'form-control','autocomplete'=>'off','maxlength'=>255]) ?>
		</div>
	</div>

		<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('orange_scaffolding_model','show_on_index') ?>
		<div class="col-md-1">
			<?=pear::input('show_on_index',$record->show_on_index,['class'=>'form-control','autocomplete'=>'off','maxlength'=>8]) ?>
		</div>
	</div>

		<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('orange_scaffolding_model','show_on_details') ?>
		<div class="col-md-1">
			<?=pear::input('show_on_details',$record->show_on_details,['class'=>'form-control','autocomplete'=>'off','maxlength'=>8]) ?>
		</div>
	</div>

		<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('orange_scaffolding_model','related_to_tablename') ?>
		<div class="col-md-9">
			<?=pear::input('related_to_tablename',$record->related_to_tablename,['class'=>'form-control','autocomplete'=>'off','maxlength'=>255]) ?>
		</div>
	</div>

		<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('orange_scaffolding_model','related_to_display_column') ?>
		<div class="col-md-9">
			<?=pear::input('related_to_display_column',$record->related_to_display_column,['class'=>'form-control','autocomplete'=>'off','maxlength'=>255]) ?>
		</div>
	</div>


	<!-- Submit Button -->
	<div class="form-group">
		<div class="col-md-12">
			<div class="pull-right">
				<?=pear::button(null,'Save',['class'=>'js-button-submit btn btn-primary']) ?>
			</div>
		</div>
	</div>
<?=pear::close() ?>

<? pear::end() ?>
