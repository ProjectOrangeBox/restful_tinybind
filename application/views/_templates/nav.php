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
						<? if (user::can('url::/backorder/get~index')) { ?>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Backorder <span class="caret"></span></a>

								<ul class="dropdown-menu">
									<?=pear::menu_li('url::/backorder/get~index','/backorder','Backorder Mgr') ?>
									<?=pear::menu_li('url::/backorder_status/get~index','/backorder_status','Backorder Mgr Status') ?>
								</ul>
							</li>
						<? } ?>

						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Misc <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="/stock_status_check">Stock Status Check</a></li>
							</ul>
						</li>

						<? if (user::has_one_permission_of(['url::/admin/users/get~index','url::/admin/roles/get~index','url::/admin/permissions/get~index','url::/admin/permissions/get~index'])) { ?>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin <span class="caret"></span></a>
						
								<ul class="dropdown-menu">
									<?=pear::menu_li('url::/admin/users/get~index','/admin/users','Users') ?>
									<?=pear::menu_li('url::/admin/roles/get~index','/admin/roles/','Roles') ?>
									<?=pear::menu_li('url::/admin/permissions/get~index','/admin/permissions','Permissions') ?>
									<?=pear::menu_li('url::/admin/settings/get~index','/admin/settings','Settings') ?>
									<?=pear::menu_li('url::/admin/utilities/config_viewer/get~index','/admin/utilities/config-viewer','Config Viewer') ?>
								</ul>
						</li>
						<? } ?>
          </ul>

					<ul class="nav navbar-nav navbar-right">
            <li>
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<img alt="" class="img-circle" src="https://www.gravatar.com/avatar/<?=md5(strtolower(trim(user::email()))) ?>?s=32" />
								<span class="username username-hide-on-mobile"> <?=user::username() ?></span> <span class="caret"></span>
							</a>
	            <ul class="dropdown-menu">
								<li><a href="/users/edit-profile/<?=user::id() ?>"><i class="fa fa-user"></i> My Profile</a></li>
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








