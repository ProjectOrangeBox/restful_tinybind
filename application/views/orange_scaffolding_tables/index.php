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
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_tables_model','controller_limit_to') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_tables_model','controller_name') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_tables_model','controller_order_by') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_tables_model','controller_title') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_tables_model','controller_titles') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_tables_model','fa_icon') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_tables_model','insert_requires') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_tables_model','new_button_txt') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_tables_model','primary_autogen') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_tables_model','primary_key') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_tables_model','table') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_tables_model','update_requires') ?></th>

					<th class="panel-heading text-center">Actions</th>
				</tr>
			</thead>
		<tbody class="searchable">
			<? foreach ($records as $row) { ?>
			<tr>
				<td><?=e($row->controller_limit_to) ?></td>
				<td><?=e($row->controller_name) ?></td>
				<td><?=e($row->controller_order_by) ?></td>
				<td><?=e($row->controller_title) ?></td>
				<td><?=e($row->controller_titles) ?></td>
				<td><?=e($row->fa_icon) ?></td>
				<td><?=e($row->insert_requires) ?></td>
				<td><?=e($row->new_button_txt) ?></td>
				<td><?=e($row->primary_autogen) ?></td>
				<td><?=e($row->primary_key) ?></td>
				<td><?=e($row->table) ?></td>
				<td><?=e($row->update_requires) ?></td>

				<td class="text-center actions">
					<?=pear::edit_button($controller_path.'/details/'.bin2hex($row->table)) ?>
					<?=pear::delete_button($controller_path,['primary_key'=>'table','id'=>$row->table]) ?>
				</td>
			</tr>
			<? } ?>
		</tbody>
	</table>
</div>

<? pear::end() ?>
