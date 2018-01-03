<? pear::extends('_templates/orange_admin') ?>

<? pear::section('section_container') ?>

<div class="row">
  <div class="col-md-6"><h3><i class="fa fa-cog"></i> <?=$controller_titles ?></h3></div>
  <div class="col-md-6">
  	<div class="pull-right">
  		<?=pear::search_sort_field() ?>
			<?=pear::new_button($controller_path.'/details','New '.$controller_title) ?>
  	</div>
  </div>
</div>

<div class="row">
	<table class="table orange sortable table-hover">
			<thead>
				<tr class="panel-default">
					<th class="panel-heading">Id</th>
					<th class="panel-heading">Created On</th>
					<th class="panel-heading">Created By</th>
					<th class="panel-heading">Created Ip</th>
					<th class="panel-heading">Updated On</th>
					<th class="panel-heading">Updated By</th>
					<th class="panel-heading">Updated Ip</th>
					<th class="panel-heading">Deleted On</th>
					<th class="panel-heading">Deleted By</th>
					<th class="panel-heading">Deleted Ip</th>
					<th class="panel-heading">Is Deleted</th>
					<th class="panel-heading">Username</th>
					<th class="panel-heading">Email</th>
					<th class="panel-heading">Password</th>
					<th class="panel-heading">Dashboard Url</th>
					<th class="panel-heading">User Read Role Id</th>
					<th class="panel-heading">User Edit Role Id</th>
					<th class="panel-heading">User Delete Role Id</th>
					<th class="panel-heading">Read Role Id</th>
					<th class="panel-heading">Edit Role Id</th>
					<th class="panel-heading">Delete Role Id</th>
					<th class="panel-heading">Is Active</th>
					<th class="panel-heading">Last Login</th>
					<th class="panel-heading">Last Ip</th>

					<th class="panel-heading text-center">Actions</th>
				</tr>
			</thead>
		<tbody class="searchable">
			<? foreach ($records as $row) { ?>
			<tr>
				<td><?=e($row->id) ?></td>
				<td><?=e($row->created_on) ?></td>
				<td><?=e($row->created_by) ?></td>
				<td><?=e($row->created_ip) ?></td>
				<td><?=e($row->updated_on) ?></td>
				<td><?=e($row->updated_by) ?></td>
				<td><?=e($row->updated_ip) ?></td>
				<td><?=e($row->deleted_on) ?></td>
				<td><?=e($row->deleted_by) ?></td>
				<td><?=e($row->deleted_ip) ?></td>
				<td><?=e($row->is_deleted) ?></td>
				<td><?=e($row->username) ?></td>
				<td><?=e($row->email) ?></td>
				<td><?=e($row->password) ?></td>
				<td><?=e($row->dashboard_url) ?></td>
				<td><?=e($row->user_read_role_id) ?></td>
				<td><?=e($row->user_edit_role_id) ?></td>
				<td><?=e($row->user_delete_role_id) ?></td>
				<td><?=e($row->read_role_id) ?></td>
				<td><?=e($row->edit_role_id) ?></td>
				<td><?=e($row->delete_role_id) ?></td>
				<td><?=e($row->is_active) ?></td>
				<td><?=e($row->last_login) ?></td>
				<td><?=e($row->last_ip) ?></td>

				<td class="text-center actions">
					<?=pear::edit_button($controller_path.'/details/'.bin2hex($row->id)) ?>
					<?=pear::delete_button($controller_path,['primary_key'=>'id','id'=>$row->id]) ?>
				</td>
			</tr>
			<? } ?>
		</tbody>
	</table>
</div>

<? pear::end() ?>
