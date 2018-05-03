<? pear::extends('_templates/orange_admin') ?>

<? pear::section('section_container') ?>

<?=pear::open_multipart($controller_path,['class'=>'form-horizontal','method'=>$form_method,'data-success'=>'Record Saved|blue'],['ip'=>$record->ip]) ?>
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
		<?=pear::field_label('firestick_log_model','ip') ?>
		<div class="col-md-2">
			<?=pear::input('ip',$record->ip,['data-mask'=>'int','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>
	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('firestick_log_model','page') ?>
		<div class="col-md-9">
			<?=pear::input('page',$record->page,['class'=>'form-control','autocomplete'=>'off','maxlength'=>255]) ?>
		</div>
	</div>
	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('firestick_log_model','user_agent') ?>
		<div class="col-md-9">
			<?=pear::input('user_agent',$record->user_agent,['class'=>'form-control','autocomplete'=>'off','maxlength'=>255]) ?>
		</div>
	</div>
	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('firestick_log_model','referrer') ?>
		<div class="col-md-9">
			<?=pear::input('referrer',$record->referrer,['class'=>'form-control','autocomplete'=>'off','maxlength'=>255]) ?>
		</div>
	</div>
	<!-- time picker input-->
	<div class="form-group">
		<?=pear::field_label('firestick_log_model','logged') ?>
		<div class="col-md-2">
			<?=pear::time_picker('logged',$record->logged,['class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>
	<!-- integer input-->
	<div class="form-group">
		<?=pear::field_label('firestick_log_model','memory') ?>
		<div class="col-md-2">
			<?=pear::input('memory',$record->memory,['data-mask'=>'int','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>
	<!-- text input-->
	<div class="form-group">
		<?=pear::field_label('firestick_log_model','memory_mb') ?>
		<div class="col-md-9">
			<?=pear::input('memory_mb',$record->memory_mb,['class'=>'form-control','autocomplete'=>'off','maxlength'=>10]) ?>
		</div>
	</div>
	<!-- float input-->
	<div class="form-group">
		<?=pear::field_label('firestick_log_model','render_elapsed') ?>
		<div class="col-md-2">
			<?=pear::input('render_elapsed',$record->render_elapsed,['data-mask'=>'float','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>
	<!-- float input-->
	<div class="form-group">
		<?=pear::field_label('firestick_log_model','ci_elapsed') ?>
		<div class="col-md-2">
			<?=pear::input('ci_elapsed',$record->ci_elapsed,['data-mask'=>'float','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>
	<!-- float input-->
	<div class="form-group">
		<?=pear::field_label('firestick_log_model','controller_elapsed') ?>
		<div class="col-md-2">
			<?=pear::input('controller_elapsed',$record->controller_elapsed,['data-mask'=>'float','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>
	<!-- float input-->
	<div class="form-group">
		<?=pear::field_label('firestick_log_model','mysql_elapsed') ?>
		<div class="col-md-2">
			<?=pear::input('mysql_elapsed',$record->mysql_elapsed,['data-mask'=>'float','class'=>'form-control','autocomplete'=>'off']) ?>
		</div>
	</div>
	<!-- Checkbox -->
	<div class="form-group">
		<div class="col-md-offset-3 col-md-4">
			<div class="checkbox">
				<label>
					<?=pear::checkbox('mysql_count_queries', 1,($record->mysql_count_queries == 1),['class'=>'js-checker']) ?> <?=pear::field_human('firestick_log_model','mysql_count_queries') ?>
				</label>
			</div>
		</div>
	</div>
	<!-- textarea input-->
	<div class="form-group">
		<?=pear::field_label('firestick_log_model','mysql_queries') ?>
		<div class="col-md-9">
			<?=pear::textarea('mysql_queries',$record->mysql_queries,['cols'=>'6','class'=>'form-control','autocomplete'=>'off']) ?>
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
