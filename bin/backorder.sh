#!/bin/bash

ROOT="`dirname $0`"
ROOT="`dirname $ROOT`"

echo $ROOT

/usr/bin/flock -n /tmp/backorder_update_records.lockfile /usr/bin/php "$ROOT/public/index.php" cli/backorder/records
/usr/bin/flock -n /tmp/backorder_update_dates.lockfile /usr/bin/php "$ROOT/public/index.php" cli/backorder/dates
/usr/bin/flock -n /tmp/backorder_syncroles.lockfile /usr/bin/php "$ROOT/public/index.php" cli/backorder/syncroles
