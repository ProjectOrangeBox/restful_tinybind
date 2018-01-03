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
					<th class="panel-heading"><?=pear::field_human('blogs_model','blog_id') ?></th>
					<th class="panel-heading"><?=pear::field_human('blogs_model','blog_title') ?></th>
					<th class="panel-heading"><?=pear::field_human('blogs_model','blog_description') ?></th>
					<th class="panel-heading"><?=pear::field_human('blogs_model','blog_price') ?></th>
					<th class="panel-heading"><?=pear::field_human('blogs_model','blog_sort_order') ?></th>
					<th class="panel-heading"><?=pear::field_human('blogs_model','checkers') ?></th>

					<th class="panel-heading text-center">Actions</th>
				</tr>
			</thead>
		<tbody class="searchable">
			<? foreach ($records as $row) { ?>
			<tr>
				<td><?=e($row->blog_id) ?></td>
				<td><?=e($row->blog_title) ?></td>
				<td><?=e($row->blog_description) ?></td>
				<td><?=money_format('$%i',$row->blog_price) ?></td>
				<td><?=e($row->blog_sort_order) ?></td>
				<td><?=e($row->checkers) ?></td>

				<td class="text-center actions">
					<?=pear::edit_button($controller_path.'/details/'.bin2hex($row->blog_id)) ?>
					<?=pear::delete_button($controller_path,['primary_key'=>'blog_id','id'=>$row->blog_id]) ?>
				</td>
			</tr>
			<? } ?>
		</tbody>
	</table>
</div>

<? pear::end() ?>
