<?php 

class Blogs_model extends Database_model {
	protected $table = 'blogs';
	protected $skip_rules = true;
	protected $primary_key = 'blog_id';
} /* end class */