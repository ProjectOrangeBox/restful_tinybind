    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="<?=site_url('{dashboard}') ?>" class="navbar-brand" href="#">Q</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
						<? if (user::has_one_permission_of(['url::/backorder::index~get','url::/backorder_status/index~get','url::/backorder_call_center::index~get'])) { ?>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Backorder <span class="caret"></span></a>

								<ul class="dropdown-menu">
									<?=pear::menu_li('url::/backorder::index~get','/backorder','Backorder Mgr') ?>
									<?=pear::menu_li('url::/backorder_call_center::index~get','/backorder-call-center','Backorder Search') ?>
									<?=pear::menu_li('url::/backorder_status::index~get','/backorder-status','Backorder Mgr Status') ?>
									<?=pear::menu_li('url::/admin/backorder_buyer::index~get','/admin/backorder_buyer','Backorder Buyer Groups') ?>
								</ul>
							</li>
						<? } ?>

						<? if (user::has_one_permission_of(['url::/stock_status_check::index~get'])) { ?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Misc <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<?=pear::menu_li('url::/stock_status_check::index~get','/stock_status_check','Stock Status Check') ?>
							</ul>
						</li>
						<? } ?>

						<? if (user::has_one_permission_of(['url::/admin/users::index~get','url::/admin/roles::index~get','url::/admin/permissions::index~get','url::/admin/permissions::index~get'])) { ?>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin <span class="caret"></span></a>

								<ul class="dropdown-menu">
									<?=pear::menu_li('url::/admin/users::index~get','/admin/users','Users') ?>
									<?=pear::menu_li('url::/admin/roles::index~get','/admin/roles','Roles') ?>
									<?=pear::menu_li('url::/admin/permissions::index~get','/admin/permissions','Permissions') ?>
									<?=pear::menu_li('url::/admin/settings::index~get','/admin/settings','Settings') ?>
									<?=pear::menu_li('url::/admin/utilities/config_viewer::index~get','/admin/utilities/config-viewer','Tools') ?>
									<?=pear::menu_li('url::/admin/utilities/cache_viewer::index~get','/admin/utilities/cache-viewer','Cache Viewer') ?>
									<?=pear::menu_li('url::/admin/reports/logins_successful::index~get','/admin/reports/logins-successful','Successful Logins') ?>
									<?=pear::menu_li('url::/admin/reports/opcache::index~get','/admin/reports/opcache','OPCache') ?>
									<?=pear::menu_li('url::/admin/configure/tooltips::index~get','/admin/configure/tooltips','Tooltips') ?>
								</ul>
						</li>
						<? } ?>
          </ul>

					<ul class="nav navbar-nav navbar-right">
            <li>
								<? if (user::somebody()) { ?>
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
								<!--
								<li> <a href="app_calendar.html"> <i class="fa fa-calendar"></i> My Calendar </a> </li>
								<li> <a href="app_inbox.html"> <i class="fa fa-envelope-o"></i> My Inbox <span class="badge badge-danger"> 3 </span> </a> </li>
								<li> <a href="app_todo.html"> <i class="fa fa-rocket"></i> My Tasks <span class="badge badge-success"> 7 </span> </a> </li>
								<li class="divider"> </li>
								<li> <a href="#"> <i class="fa fa-lock"></i> Lock Screen </a> </li>
								-->
								<li><a href="<?=site_url('{logout}') ?>"><i class="fa fa-key"></i> Log Out</a></li>
	            </ul>
	          </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
