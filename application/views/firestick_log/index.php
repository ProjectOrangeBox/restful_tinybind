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
					<th class="panel-heading"><?=pear::field_human('Firestick_log_model','ci_elapsed') ?></th>
					<th class="panel-heading"><?=pear::field_human('Firestick_log_model','controller_elapsed') ?></th>
					<th class="panel-heading"><?=pear::field_human('Firestick_log_model','ip') ?></th>
					<th class="panel-heading"><?=pear::field_human('Firestick_log_model','logged') ?></th>
					<th class="panel-heading"><?=pear::field_human('Firestick_log_model','memory') ?></th>
					<th class="panel-heading"><?=pear::field_human('Firestick_log_model','memory_mb') ?></th>
					<th class="panel-heading"><?=pear::field_human('Firestick_log_model','mysql_count_queries') ?></th>
					<th class="panel-heading"><?=pear::field_human('Firestick_log_model','mysql_elapsed') ?></th>
					<th class="panel-heading"><?=pear::field_human('Firestick_log_model','mysql_queries') ?></th>
					<th class="panel-heading"><?=pear::field_human('Firestick_log_model','page') ?></th>
					<th class="panel-heading"><?=pear::field_human('Firestick_log_model','referrer') ?></th>
					<th class="panel-heading"><?=pear::field_human('Firestick_log_model','render_elapsed') ?></th>
					<th class="panel-heading"><?=pear::field_human('Firestick_log_model','user_agent') ?></th>

					<th class="panel-heading text-center">Actions</th>
				</tr>
			</thead>
		<tbody class="searchable">
			<? foreach ($records as $row) { ?>
			<tr>
				<td><?=e($row->ci_elapsed) ?></td>
				<td><?=e($row->controller_elapsed) ?></td>
				<td><?=e($row->ip) ?></td>
				<td><?=e($row->logged) ?></td>
				<td><?=e($row->memory) ?></td>
				<td><?=e($row->memory_mb) ?></td>
				<td><?=e($row->mysql_count_queries) ?></td>
				<td><?=e($row->mysql_elapsed) ?></td>
				<td><?=e($row->mysql_queries) ?></td>
				<td><?=e($row->page) ?></td>
				<td><?=e($row->referrer) ?></td>
				<td><?=e($row->render_elapsed) ?></td>
				<td><?=e($row->user_agent) ?></td>

				<td class="text-center actions">
					<?=pear::edit_button($controller_path.'/details/'.bin2hex($row->ip)) ?>
					<?=pear::delete_button($controller_path,['primary_key'=>'ip','id'=>$row->ip]) ?>
				</td>
			</tr>
			<? } ?>
		</tbody>
	</table>
</div>

<? pear::end() ?>
