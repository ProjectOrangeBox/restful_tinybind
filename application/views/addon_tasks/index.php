<? pear::extends('_templates/orange_admin') ?>

<? pear::section('section_container') ?>

<div class="row">
  <div class="col-md-6"><h3><i class="fa fa-cog"></i> <?=$controller_titles ?></h3></div>
  <div class="col-md-6">
  	<div class="pull-right">
  		<?=pear::search_sort_field() ?>
			<?=pear::new_button($controller_path.'/details','{new_button_txt}') ?>
  	</div>
  </div>
</div>

<div class="row">
	<table class="table orange sortable table-hover">
			<thead>
				<tr class="panel-default">
					<th class="panel-heading"><?=pear::field_human('Addon_tasks_model','closed') ?></th>
					<th class="panel-heading"><?=pear::field_human('Addon_tasks_model','created_by') ?></th>
					<th class="panel-heading"><?=pear::field_human('Addon_tasks_model','created_ip') ?></th>
					<th class="panel-heading"><?=pear::field_human('Addon_tasks_model','created_on') ?></th>
					<th class="panel-heading"><?=pear::field_human('Addon_tasks_model','email') ?></th>
					<th class="panel-heading"><?=pear::field_human('Addon_tasks_model','id') ?></th>
					<th class="panel-heading"><?=pear::field_human('Addon_tasks_model','name') ?></th>
					<th class="panel-heading"><?=pear::field_human('Addon_tasks_model','notes') ?></th>
					<th class="panel-heading"><?=pear::field_human('Addon_tasks_model','opened') ?></th>
					<th class="panel-heading"><?=pear::field_human('Addon_tasks_model','problem') ?></th>
					<th class="panel-heading"><?=pear::field_human('Addon_tasks_model','project') ?></th>
					<th class="panel-heading"><?=pear::field_human('Addon_tasks_model','short_problem') ?></th>
					<th class="panel-heading"><?=pear::field_human('Addon_tasks_model','status') ?></th>
					<th class="panel-heading"><?=pear::field_human('Addon_tasks_model','updated_by') ?></th>
					<th class="panel-heading"><?=pear::field_human('Addon_tasks_model','updated_ip') ?></th>
					<th class="panel-heading"><?=pear::field_human('Addon_tasks_model','updated_on') ?></th>

					<th class="panel-heading text-center">Actions</th>
				</tr>
			</thead>
		<tbody class="searchable">
			<? foreach ($records as $row) { ?>
			<tr>
				<td><?=e($row->closed) ?></td>
				<td><?=e($row->created_by) ?></td>
				<td><?=e($row->created_ip) ?></td>
				<td><?=e($row->created_on) ?></td>
				<td><?=e($row->email) ?></td>
				<td><?=e($row->id) ?></td>
				<td><?=e($row->name) ?></td>
				<td><?=e($row->notes) ?></td>
				<td><?=e($row->opened) ?></td>
				<td><?=e($row->problem) ?></td>
				<td><?=e($row->project) ?></td>
				<td><?=e($row->short_problem) ?></td>
				<td><?=e($row->status) ?></td>
				<td><?=e($row->updated_by) ?></td>
				<td><?=e($row->updated_ip) ?></td>
				<td><?=e($row->updated_on) ?></td>

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
