	<div class="row">
		<div class="col-md-6">
			<h3>
				<i class="fa fa-tags"></i>
				<span rv-text="page.action"></span>
				<span rv-text="page.title"></span>
			</h3>
		</div>
		<div class="col-md-6">
			<div class="pull-right">
				<a class="btn btn-default btn-sm js-esc" rv-on-click="events.goto | wrap page.path"><i class="fa fa-share fa-flip-horizontal" aria-hidden="true"></i> Go Back</a>
			</div>
		</div>
	</div>

	<hr>

	<form rv-method="form.method" rv-action="form.action">
		<div class="form-group" rv-if="model.id">
			<label for="id">Id</label>
			<input type="id" name="id" class="form-control" id="id" placeholder="" rv-value="model.id">
		</div>

		<div class="form-group">
			<label for="name">Value</label>
			<input type="name" name="name" class="form-control" id="value" placeholder="" rv-value="model.value">
		</div>

		<button type="submit" class="btn btn-default" rv-on-click="events.submit">Submit</button>
	</form>