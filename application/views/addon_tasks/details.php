<? pear::extends('_templates/orange_admin') ?>

<? pear::section('section_container') ?>

<?=pear::open_multipart($controller_path,['class'=>'form-horizontal','method'=>$form_method,'data-success'=>'Record Saved|blue'],['id'=>$record->id]) ?>
	<div class="row">
		<div class="col-md-6"><h3><i class="fa fa-cog"></i> <?=$ci_title_prefix ?> <?=$controller_title ?></h3></div>
	  <div class="col-md-6">
	  	<div class="pull-right">
				<?=pear::goback_button($controller_path) ?>
	  	</div>
	  </div>
	</div>

	<hr>

	<!-- date picker input-->
	<div class="form-group">
		<?=pear::field_label('Addon_tasks_model','closed') ?>
		<div class="col-md-2">
			<?=pear::date_picker('closed',$record->closed,['class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

	<!-- integer input-->
	<div class="form-group">
		<?=pear::field_label('Addon_tasks_model','created_by') ?>
		<div class="col-md-2">
			<?=pear::input('created_by',$record->created_by,['data-mask'=>'int','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Addon_tasks_model','created_ip') ?>
		<div class="col-md-2">
			<?=pear::input('created_ip',$record->created_ip,['class'=>'form-control','autocomplete'=>'off','maxlength'=>15]) ?>
		</div>
	</div>

	<!-- date time picker input-->
	<div class="form-group">
		<?=pear::field_label('{utable}_model','{name}') ?>
		<div class="col-md-2">
			<?=pear::date_time_picker('{name}',$record->{name},['class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Addon_tasks_model','email') ?>
		<div class="col-md-9">
			<?=pear::input('email',$record->email,['class'=>'form-control','autocomplete'=>'off','maxlength'=>128]) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Addon_tasks_model','id') ?>
		<div class="col-md-2">
			<?=pear::input('id',$record->id,['class'=>'form-control','autocomplete'=>'off','maxlength'=>20]) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Addon_tasks_model','name') ?>
		<div class="col-md-9">
			<?=pear::input('name',$record->name,['class'=>'form-control','autocomplete'=>'off','maxlength'=>128]) ?>
		</div>
	</div>

	<!-- textarea input-->
	<div class="form-group">
		<?=pear::field_label('Addon_tasks_model','notes') ?>
		<div class="col-md-9">
			<?=pear::textarea('notes',$record->notes,['cols'=>'6','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

	<!-- date picker input-->
	<div class="form-group">
		<?=pear::field_label('Addon_tasks_model','opened') ?>
		<div class="col-md-2">
			<?=pear::date_picker('opened',$record->opened,['class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

	<!-- textarea input-->
	<div class="form-group">
		<?=pear::field_label('Addon_tasks_model','problem') ?>
		<div class="col-md-9">
			<?=pear::textarea('problem',$record->problem,['cols'=>'6','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Addon_tasks_model','project') ?>
		<div class="col-md-9">
			<?=pear::input('project',$record->project,['class'=>'form-control','autocomplete'=>'off','maxlength'=>128]) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Addon_tasks_model','short_problem') ?>
		<div class="col-md-9">
			<?=pear::input('short_problem',$record->short_problem,['class'=>'form-control','autocomplete'=>'off','maxlength'=>255]) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Addon_tasks_model','status') ?>
		<div class="col-md-9">
			<?=pear::input('status',$record->status,['class'=>'form-control','autocomplete'=>'off','maxlength'=>128]) ?>
		</div>
	</div>

	<!-- integer input-->
	<div class="form-group">
		<?=pear::field_label('Addon_tasks_model','updated_by') ?>
		<div class="col-md-2">
			<?=pear::input('updated_by',$record->updated_by,['data-mask'=>'int','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('Addon_tasks_model','updated_ip') ?>
		<div class="col-md-2">
			<?=pear::input('updated_ip',$record->updated_ip,['class'=>'form-control','autocomplete'=>'off','maxlength'=>15]) ?>
		</div>
	</div>

	<!-- date time picker input-->
	<div class="form-group">
		<?=pear::field_label('{utable}_model','{name}') ?>
		<div class="col-md-2">
			<?=pear::date_time_picker('{name}',$record->{name},['class'=>'form-control','autocomplete'=>'off']) ?>
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
