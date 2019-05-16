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
			<label for="name">Name</label>
			<input type="name" name="name" class="form-control" id="name" placeholder="" rv-value="model.name">
		</div>

		<div class="form-group">
			<label for="type">Type</label>
			<input type="type" name="type" class="form-control" id="type" placeholder="" rv-value="model.type">
		</div>

		<div class="form-group">
			<label for="year">Year</label>
			<input type="year" name="year" class="form-control" id="" placeholder="" rv-value="model.year">
		</div>

		<div class="form-group">
			<label for="checkbox">Enabled</label>
			<input type="checkbox" name="enabled" value="1" rv-intcheck="model.enabled">
		</div>

		<div class="radio">
			<label>
				<input type="radio" name="options" value="11" rv-checked="model.options">
				Option one is this and that&mdash;be sure to include why it's great
			</label>
		</div>
		<div class="radio">
			<label>
				<input type="radio" name="options" value="22" rv-checked="model.options">
				Option two can be something else and selecting it will deselect option one
			</label>
		</div>
		<div class="radio">
			<label>
				<input type="radio" name="options" value="33" rv-checked="model.options">
				Option three is disabled
			</label>
		</div>

		<div class="form-group">
			<label>Select</label>
			<select name="select" class="form-control" rv-value="model.select">
				<option rv-each-foo="form.select" rv-value="foo.id">{ foo.value }</option>
			</select>
		</div>

		<button type="submit" class="btn btn-default" rv-on-click="events.submit">Submit</button>
	</form>