#!/bin/bash
# This script sets up a CRON job to run cron.php every 24 hours.

# This script should set up a CRON job to run cron.php every 24 hours.
# You need to implement the CRON setup logic here.

(crontab -l 2>/dev/null; echo "0 0 * * * php $(pwd)/src/cron.php") | crontab -

echo "CRON job set up to run cron.php every 24 hours."
