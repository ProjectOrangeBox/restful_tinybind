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
			<?=ci('nav_library')->build_bootstrap_nav(config('nav.left'),config('nav.bootstrap nav')) ?>
			<ul class="nav navbar-nav navbar-right">
				<? if (user::has_permission('url::/orange_user_msgs::index~get')) { ?>
				<li>
					<a href="/admin/msgs"><i class="fa fa-envelope"></i> <span class="badge"><?=pear::user_messages(user::id()) ?></span></a>
				</li>
				<? } ?>
				<li>
				<? if (user::logged_in()) { ?>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<img alt="" class="img-circle" src="https://www.gravatar.com/avatar/<?=md5(strtolower(trim(user::email()))) ?>?s=32" />
						<span class="username username-hide-on-mobile"> <?=user::username() ?></span> <span class="caret"></span>
					</a>
				<? } else { ?>
					<a href="<?=site_url('{login}') ?>">
						<i class="fa fa-sign-in"></i> Login
					</a>
				<? } ?>
				<ul class="dropdown-menu">
					<? if (user::has_one_permission_of('menu::edit profile')) { ?>
						<li><a href="/users/edit-profile/<?=user::id() ?>"><i class="fa fa-user"></i> My Profile</a></li>
					<? } ?>
					<? foreach (user::roles() as $role) { ?>
						<li class="disabled"><a tabindex="-1" href="#"><i class="fa fa-users"></i> <?=$role ?></a></li>
					<? } ?>
					<li role="separator" class="divider"></li>
					<? if (user::logged_in()) { ?>
						<li><a href="<?=site_url('{logout}') ?>"><i class="fa fa-key"></i> Log Out</a></li>
					<? } ?>
				</ul>
				</li>
				</ul>
		</div>
	</div>
</nav>
