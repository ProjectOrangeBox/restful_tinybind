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

<? pear::include('scaffolding/integer',['name'=>'blog_id','type'=>'int','max_length'=>5,'default'=>NULL,'primary_key'=>1,'human'=>'Blog Id','gui'=>'integer']) ?>

<? pear::include('scaffolding/integer',['name'=>'blog_title','type'=>'varchar','max_length'=>100,'default'=>NULL,'primary_key'=>0,'human'=>'Blog Title','gui'=>'integer']) ?>

<? pear::include('scaffolding/textarea',['name'=>'blog_description','type'=>'text','max_length'=>NULL,'default'=>NULL,'primary_key'=>0,'human'=>'Blog Description','gui'=>'textarea']) ?>

<? pear::include('scaffolding/integer',['name'=>'blog_sort_order','type'=>'int','max_length'=>10,'default'=>'0','primary_key'=>0,'human'=>'Blog Sort Order','gui'=>'integer']) ?>

<? pear::include('scaffolding/float',['name'=>'blog_price','type'=>'decimal','max_length'=>10,'default'=>'0.00','primary_key'=>0,'human'=>'Blog Price','gui'=>'float']) ?>


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
