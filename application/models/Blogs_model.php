<?php

class Blogs_model extends Database_model {
	protected $table = 'blogs';
	protected $primary_key = 'blog_id';
	protected $auto_generated_primary = true;
	protected $rules = [
		'blog_id' => ['field' => 'blog_id', 'label' => 'Blog Id', 'rules' => 'required|integer|max_length[10]|less_than[4294967295]|filter_int[10]'],
		'blog_title' => ['field' => 'blog_title', 'label' => 'Blog Title', 'rules' => 'required|filter_input'],
		'blog_description' => ['field' => 'blog_description', 'label' => 'Blog Description', 'rules' => 'filter_textarea'],
		'blog_price' => ['field' => 'blog_price', 'label' => 'Blog Price', 'rules' => 'if_empty[0.0]|decimal|max_length[10]|less_than[4294967295]|filter_float[10]'],
		'blog_sort_order' => ['field' => 'blog_sort_order', 'label' => 'Blog Sort Order', 'rules' => 'if_empty[0]|integer|max_length[10]|less_than[4294967295]|filter_int[10]'],
		'checkers' => ['field' => 'checkers', 'label' => 'Checkers', 'rules' => 'if_empty[0]|in_list[0,1]|filter_int[1]|max_length[1]|less_than[2]'],
	];
	protected $rule_sets = [
		'insert'=>'blog_title',
	];

} /* end Blogs_model */
