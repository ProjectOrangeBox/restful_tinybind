<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">Q</a>
		</div>
		
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
			<?=ci('nav_library')->build_bootstrap_nav(config('nav.left'),config('nav.bootstrap nav')) ?>
			</ul>
		
			<ul class="nav navbar-nav navbar-right">
				
				<? if (user::has_permission('url::/orange_user_msgs::index~get')) { ?>
				<li>
					<a href="/admin/msgs"><i class="fa fa-envelope"></i> <span class="badge"><?=pear::user_messages(user::id()) ?></span></a>
				</li>
				<? } ?>
				
				<? if (user::logged_in()) { ?>
					<?=ci('nav_library')->build_bootstrap_nav(config('nav.right protected'),config('nav.bootstrap nav icons'),false) ?>
				<? } else { ?>
					<?=ci('nav_library')->build_bootstrap_nav(config('nav.right public'),config('nav.bootstrap nav icons'),false) ?>
				<? } ?>
			
			</ul>
		</div>

	</div>
</nav>
