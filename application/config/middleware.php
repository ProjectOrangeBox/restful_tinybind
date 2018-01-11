<?php

$middleware = [
	'/main/route404' => ['GuiMiddleware','ThemeMiddleware'],
	'/backorder/update_dates' => [],
	'/backorder/update_records' => [],
	'/admin*' => ['AdminMiddleware','GuiMiddleware','ThemeMiddleware'],
	'/backorder*' => ['AdminMiddleware','GuiMiddleware','ThemeMiddleware'],
	'/stock_status_check*' => ['AdminMiddleware','GuiMiddleware','ThemeMiddleware'],
	'/users*' => ['PublicMiddleware','GuiMiddleware','ThemeMiddleware'],
	'/login' => ['LoginMiddleware','PublicMiddleware','GuiMiddleware','ThemeMiddleware'],

	/* must be last because it's a catach all */
	'/*' => ['PublicMiddleware','GuiMiddleware'],
];
