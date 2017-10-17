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
									<li><a href="/backorder">Backorder Mgr</a></li>
									<? if(user::can('url::/backorder_status/get~index')) { ?>
									<li><a href="/backorder_status">Backorder Mgr Status</a></li>
									<? } ?>
								</ul>
							</li>
						<? } ?>

						<? if (user::can(['url::/admin/users/index','url::/admin/roles/index','url::/admin/permissions/index','url::/admin/settings/index'])) { ?>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin <span class="caret"></span></a>
						
								<ul class="dropdown-menu">
									<? if (user::can('url::/admin/users/index')) { ?>
									<li><a href="<?=site_url('/admin/users') ?>">Users</a></li>
									<? } ?>
						
									<? if (user::can('url::/admin/roles/index')) { ?>
									<li><a href="<?=site_url('/admin/roles') ?>">Roles</a></li>
									<? } ?>
						
									<? if (user::can('url::/admin/permissions/index')) { ?>
									<li><a href="<?=site_url('/admin/permissions') ?>">Permissions</a></li>
									<? } ?>
									
									<? if (user::can('url::/admin/settings/index')) { ?>
									<li><a href="<?=site_url('/admin/settings') ?>">Settings</a></li>
									<li><a href="<?=site_url('/admin/utilities/config-viewer') ?>">Config Viewer</a></li>
									<? } ?>
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








