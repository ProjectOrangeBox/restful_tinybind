<? pear::extends('_templates/orange_admin') ?>
<? pear::section('section_container') ?>
<div class="row">
  <div class="col-md-6"><?=pear::title($controller_titles,'user') ?></div>
  <div class="col-md-6">
  	<div class="pull-right">
  		<?=pear::search_sort_field() ?>
			<?php if (user::can('url::/admin/users::index~post')) { ?>
				<?=pear::new_button($controller_path.'/details','New '.$controller_title) ?>
  		<? } ?>
  	</div>
  </div>
</div>
<div class="row">
	<table class="table orange sortable table-hover">
			<thead>
				<tr class="panel-default">
					<th class="panel-heading">Username</th>
					<th class="panel-heading">Email</th>
					<th class="panel-heading text-center">Active</th>
					<th class="panel-heading text-center">Actions</th>
				</tr>
			</thead>
		<tbody class="searchable">
		<?php foreach ($records as $row) { ?>
			<?php if (user::has_role($row->read_role_id)) { ?>
				<tr>
					<td><?=e($row->username) ?></td>
					<td><?=e($row->email) ?></td>
					<td class="text-center"><?=pear::fa_enum_icon($row->is_active) ?></td>
					<td class="text-center actions">
						<?php if (user::has_role($row->edit_role_id)) { ?>
							<?=pear::edit_button($controller_path.'/details/'.bin2hex($row->id)) ?>
						<? } ?>
						<?php if (user::has_role($row->delete_role_id)) { ?>
							<?=pear::delete_button($controller_path,['id'=>$row->id]) ?>
						<? } ?>
						<?php if (user::has_role(ADMIN_ROLE_ID)) { ?>
							<a href="/main/hijack/<?=bin2hex($row->id.chr(0).date('U').chr(0).md5($row->id.chr(0).date('U').chr(0).ci('config')->item('encryption_key'))) ?>"><i class="fa fa-user-secret fa-lg"></i></a>
						<? } ?>
						<?php if (user::has_role(ADMIN_ROLE_ID)) { ?>
							<a href="<?=$controller_path ?>/send_forgot/<?=bin2hex($row->id) ?>"><i class="fa fa-envelope-o fa-lg"></i></a>
						<? } ?>
					</td>
				</tr>
			<? } ?>
		<? } ?>
		</tbody>
	</table>
</div>
<? pear::end() ?>
