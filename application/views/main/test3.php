<? pear::extends('_templates/orange_admin') ?>

<? pear::section('section_container') ?>

<?=pear::open_multipart('/main/test3',['class'=>'form-horizontal','method'=>'post','data-success'=>'Record Saved|blue']) ?>
	
	<? tf_test('id',123) ?>
	<? tf_test('firstname') ?>
	<? tf_test('lastname') ?>
	<? tf_test('age') ?>
	<? tf_test('color') ?>
	<? tf_test('food') ?>
	<? tf_test('food space') ?>

	<? tf_test('person.firstname') ?>
	<? tf_test('person.lastname') ?>
	<? tf_test('person.age') ?>
	<? tf_test('person.color') ?>
	<? tf_test('person.food') ?>

	<? tf_test('candys.firstname[]') ?>
	<? tf_test('candys.lastname[]') ?>
	<? tf_test('candys.age[]') ?>
	<? tf_test('candys.color[]') ?>
	<? tf_test('candys.food[]') ?>

	<? tf_test('candys.firstname[]') ?>
	<? tf_test('candys.lastname[]') ?>
	<? tf_test('candys.age[]') ?>
	<? tf_test('candys.color[]') ?>
	<? tf_test('candys.food[]') ?>

	<? tf_test('candys.firstname[]') ?>
	<? tf_test('candys.lastname[]') ?>
	<? tf_test('candys.age[]') ?>
	<? tf_test('candys.color[]') ?>
	<? tf_test('candys.food[]') ?>

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

<?
function tf_test($name,$value='') {
	echo '<div class="form-group">';
	echo '<label class="col-md-3 control-label" for="textinput">'.$name.'</label>';
	echo '<div class="col-md-4">';
	echo pear::input($name,$value,['class'=>'form-control input-md']);
	echo '</div>';
	echo '</div>';
}