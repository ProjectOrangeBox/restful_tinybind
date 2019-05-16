	<div class="row">
		<div class="col-md-6">
			<h3><i class="fa fa-th"></i> {page.titles} <small id="table-search-field-count"></small></h3>
		</div>
		<div class="col-md-6">
			<div class="pull-right">
				<div class="form-group has-feedback" style="display:inline-block">
					<input type="text" id="bound-table-search-field" class="form-control input-sm" style="width:222px;" placeholder="search">
					<i class="fa fa-search form-control-feedback"></i>
				</div>
				<a class="btn btn-default btn-sm" rv-on-click="events.goto | wrap '/'"><i class="fa fa-link"></i> Goto Robots</a>
				<a class="btn btn-default btn-sm js-new" rv-on-click="events.create | wrap page.path"><i class="fa fa-magic"></i> New {page.title}</a>
			</div>
		</div>
	</div>

	<div class="row">
		<table class="table table-sticky-header bound-table-search table-sort table-hover">
			<thead>
				<tr class="panel-default">
					<th class="panel-heading text-center">Id</th>
					<th class="panel-heading">Value</th>
					<th class="panel-heading text-center nosort">Actions</th>
				</tr>
			</thead>
			<tbody>
			<tr rv-each-record="model" rv-data-id="record.id">
				<td class="text-center" rv-text="record.id"></td>
				<td rv-text="record.value"></td>
				<td class="text-center actions">
					<a rv-on-click="events.edit | wrap page.path record.id"><i class="fa fa-edit fa-lg"></i></a>
					<a rv-on-click="events.delete | wrap page.path record.id"><i class="fa fa-trash fa-lg"></i></a>
				</td>
			</tr>
			</tbody>
		</table>
	</div>