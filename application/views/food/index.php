<?php include __DIR__ . '/../includes/header.php' ?>
<?php include __DIR__ . '/../includes/nav.php' ?>
<div id="app">
	<div class="container">
		<div class="row heading">
			<div class="col-md-6">
				<h3>
					<i class="fa fa-th"></i><span rv-text="page.titles"></span><small id="table-search-field-count"></small>
				</h3>
			</div>
			<div class="col-md-6">
				<div class="pull-right">
					<div class="form-group has-feedback" style="display:inline-block">
						<input class="form-control input-sm" id="bound-table-search-field" type="text" style="width:222px;" placeholder="search" />
						<i class="fa fa-search form-control-feedback"></i>
					</div>
					<a class="btn btn-default btn-sm js-new" rv-on-click="events.redirect | args '%s/create' page.path">
						<i class="fa fa-magic"></i> New {page.title}
					</a>
				</div>
			</div>
		</div>
		<div class="row">
			<table class="table table-sticky-header bound-table-search table-sort table-hover">
				<thead>
					<tr class="panel-default">
						<th class="panel-heading text-center">Id</th>
						<th class="panel-heading">First Name</th>
						<th class="panel-heading">Last Name</th>
						<th class="text-right panel-heading">Price</th>
						<th class="panel-heading text-center nosort">Actions</th>
					</tr>
				</thead>
				<tbody>
					<tr rv-each-record="records" rv-data-id="record.id">
						<td class="text-center" rv-text="record.id"></td>
						<td rv-text="record.firstname"></td>
						<td rv-text="record.lastname"></td>
						<td class="text-right" rv-text="record.price"></td>
						<td class="text-center actions">
							<a rv-on-click="events.redirect | args '%s/edit/%s' page.path record.id"><i class="fa fa-edit fa-lg"></i></a>
							<a rv-on-click="events.delete | args page.path record.id"><i class="fa fa-trash fa-lg"></i></a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include __DIR__ . '/../includes/footer.php' ?>