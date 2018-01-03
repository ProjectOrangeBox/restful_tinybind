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


		<!-- integer input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Id</label>
		<div class="col-md-2">
			<?=pear::input($name,$record->id,['data-mask'=>'int','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

		<!-- date time picker input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Created On</label>
		<div class="col-md-2">
			<?=pear::date_time_picker($name,$record->created_on,['class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

		<!-- integer input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Created By</label>
		<div class="col-md-2">
			<?=pear::input($name,$record->created_by,['data-mask'=>'int','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

		<!-- text input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Created Ip</label>
		<div class="col-md-2">
			<?=pear::input($name,$record->created_ip,['class'=>'form-control','autocomplete'=>'off','maxlength'=>16]) ?>
		</div>
	</div>

		<!-- date time picker input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Updated On</label>
		<div class="col-md-2">
			<?=pear::date_time_picker($name,$record->updated_on,['class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

		<!-- integer input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Updated By</label>
		<div class="col-md-2">
			<?=pear::input($name,$record->updated_by,['data-mask'=>'int','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

		<!-- text input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Updated Ip</label>
		<div class="col-md-2">
			<?=pear::input($name,$record->updated_ip,['class'=>'form-control','autocomplete'=>'off','maxlength'=>16]) ?>
		</div>
	</div>

		<!-- date time picker input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Deleted On</label>
		<div class="col-md-2">
			<?=pear::date_time_picker($name,$record->deleted_on,['class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

		<!-- integer input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Deleted By</label>
		<div class="col-md-2">
			<?=pear::input($name,$record->deleted_by,['data-mask'=>'int','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

		<!-- text input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Deleted Ip</label>
		<div class="col-md-2">
			<?=pear::input($name,$record->deleted_ip,['class'=>'form-control','autocomplete'=>'off','maxlength'=>16]) ?>
		</div>
	</div>

		<!-- checkbox input-->
	<div class="form-group">
		<label class="col-md-3 control-label">&nbsp;</label>
		<div class="col-md-9">
			<?=pear::checkbox($name, 1,($record->is_deleted == 1),['class'=>'js-checker']) ?> Is Deleted
		</div>
	</div>
		<!-- text input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Username</label>
		<div class="col-md-7">
			<?=pear::input($name,$record->username,['class'=>'form-control','autocomplete'=>'off','maxlength'=>64]) ?>
		</div>
	</div>

		<!-- text input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Email</label>
		<div class="col-md-9">
			<?=pear::input($name,$record->email,['class'=>'form-control','autocomplete'=>'off','maxlength'=>255]) ?>
		</div>
	</div>

		<!-- text input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Password</label>
		<div class="col-md-9">
			<?=pear::input($name,$record->password,['class'=>'form-control','autocomplete'=>'off','maxlength'=>255]) ?>
		</div>
	</div>

		<!-- text input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Dashboard Url</label>
		<div class="col-md-9">
			<?=pear::input($name,$record->dashboard_url,['class'=>'form-control','autocomplete'=>'off','maxlength'=>255]) ?>
		</div>
	</div>

		<!-- integer input-->
	<div class="form-group">
		<label class="col-md-3 control-label">User Read Role Id</label>
		<div class="col-md-2">
			<?=pear::input($name,$record->user_read_role_id,['data-mask'=>'int','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

		<!-- integer input-->
	<div class="form-group">
		<label class="col-md-3 control-label">User Edit Role Id</label>
		<div class="col-md-2">
			<?=pear::input($name,$record->user_edit_role_id,['data-mask'=>'int','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

		<!-- integer input-->
	<div class="form-group">
		<label class="col-md-3 control-label">User Delete Role Id</label>
		<div class="col-md-2">
			<?=pear::input($name,$record->user_delete_role_id,['data-mask'=>'int','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

		<!-- integer input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Read Role Id</label>
		<div class="col-md-2">
			<?=pear::input($name,$record->read_role_id,['data-mask'=>'int','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

		<!-- integer input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Edit Role Id</label>
		<div class="col-md-2">
			<?=pear::input($name,$record->edit_role_id,['data-mask'=>'int','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

		<!-- integer input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Delete Role Id</label>
		<div class="col-md-2">
			<?=pear::input($name,$record->delete_role_id,['data-mask'=>'int','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

		<!-- checkbox input-->
	<div class="form-group">
		<label class="col-md-3 control-label">&nbsp;</label>
		<div class="col-md-9">
			<?=pear::checkbox($name, 1,($record->is_active == 1),['class'=>'js-checker']) ?> Is Active
		</div>
	</div>
		<!-- date time picker input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Last Login</label>
		<div class="col-md-2">
			<?=pear::date_time_picker($name,$record->last_login,['class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>

		<!-- text input-->
	<div class="form-group">
		<label class="col-md-3 control-label">Last Ip</label>
		<div class="col-md-2">
			<?=pear::input($name,$record->last_ip,['class'=>'form-control','autocomplete'=>'off','maxlength'=>16]) ?>
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
