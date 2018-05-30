<!-- Text input-->
<fieldset class="repeatable-group list-group-item">

	<div class="form-group">
		<label class="col-md-3 control-label" for="textinput">First Name</label>
		<div class="col-md-9">
			<input type="text" class="form-control" name="repeatable|firstname][]" value="<?=$firstname ?>">
			<input type="hidden" name="repeatable|id[]" value="<?=$id ?>">
			<input type="hidden" name="repeatable|parent_id[]" value="<?=$parent_id ?>">
		</div>
	</div>

	<!-- Text input-->
	<div class="form-group">
		<label class="col-md-3 control-label" for="textinput">Last Name</label>
		<div class="col-md-9">
			<input type="text" class="form-control" name="repeatable|lastname][]" value="<?=$lastname ?>">
		</div>
	</div>

	<!-- Checkbox -->
	<div class="form-group">
		<div class="col-md-offset-3 col-md-4">
			<div class="checkbox">
				<label>
					<?=pear::checker('repeatable|dinner[]',0,($checkers == 1)) ?> Dinner
				</label>
			</div>
		</div>
	</div>


	<div class="form-group text-right">
		<div class="col-md-12">
			<?=pear::repeatable_remove_button('repeatable-remove-button','repeatable-template') ?>
		</div>
	</div>

</fieldset>
