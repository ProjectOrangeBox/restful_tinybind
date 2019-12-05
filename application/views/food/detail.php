<?php include __DIR__ . '/../includes/header.php' ?>
<?php include __DIR__ . '/../includes/nav.php' ?>
<div id="app">
	<div class="container">
		<div class="row heading">
			<div class="col-md-6">
				<h3><i class="fa fa-tags"></i><span rv-text="page.action"></span><span rv-text="page.title"></span></h3>
			</div>
			<div class="col-md-6">
				<div class="pull-right">
					<a class="btn btn-default btn-sm js-esc" rv-on-click="events.redirect | wrap '%s' page.path"><i class="fa fa-share fa-flip-horizontal" aria-hidden="true"></i> Go Back</a>
				</div>
			</div>
		</div>
		<hr />
		<form rv-method="form.method" rv-action="form.action">
			<div class="form-group" rv-if="record.id">
				<label for="id">Id</label>
				<input class="form-control" id="id" type="id" name="id" placeholder="placeholder" rv-value="record.id" />
			</div>

			<div class="form-group">
				<label for="name">First Name</label>
				<input class="form-control" id="value" type="name" name="name" placeholder="placeholder" rv-value="record.firstname" />
			</div>

			<div class="form-group">
				<label for="name">Last Name</label>
				<input class="form-control" id="value" type="name" name="name" placeholder="placeholder" rv-value="record.lastname" />
			</div>

			<div class="form-group">
				<label for="name">Price</label>
				<input class="form-control" id="value" type="name" name="name" placeholder="placeholder" rv-value="record.price" />
			</div>

			<button class="btn btn-default" type="submit" rv-on-click="events.submitRedirect">Submit</button>
		</form>
	</div>
	<?php include __DIR__ . '/../includes/footer.php' ?>