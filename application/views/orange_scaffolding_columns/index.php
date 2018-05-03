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
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_columns_model','bootstrap_md_col') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_columns_model','column') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_columns_model','details_gui_file') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_columns_model','details_gui_manual') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_columns_model','human') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_columns_model','index_gui_file') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_columns_model','index_gui_manual') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_columns_model','related_to_display_column') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_columns_model','related_to_secondary_key') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_columns_model','related_to_tablename') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_columns_model','rules') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_columns_model','show_on_details') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_columns_model','show_on_index') ?></th>
					<th class="panel-heading"><?=pear::field_human('Orange_scaffolding_columns_model','table') ?></th>

					<th class="panel-heading text-center">Actions</th>
				</tr>
			</thead>
		<tbody class="searchable">
			<? foreach ($records as $row) { ?>
			<tr>
				<td><?=e($row->bootstrap_md_col) ?></td>
				<td><?=e($row->column) ?></td>
				<td><?=e($row->details_gui_file) ?></td>
				<td><?=e($row->details_gui_manual) ?></td>
				<td><?=e($row->human) ?></td>
				<td><?=e($row->index_gui_file) ?></td>
				<td><?=e($row->index_gui_manual) ?></td>
				<td><?=e($row->related_to_display_column) ?></td>
				<td><?=e($row->related_to_secondary_key) ?></td>
				<td><?=e($row->related_to_tablename) ?></td>
				<td><?=e($row->rules) ?></td>
				<td><?=e($row->show_on_details) ?></td>
				<td><?=e($row->show_on_index) ?></td>
				<td><?=e($row->table) ?></td>

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
