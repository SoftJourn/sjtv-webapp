#!/usr/bin/env bash

#prod
SITE=https://tv.softjourn.com
DIR=/var/www/sjtv_server/tinypulse-screen

#dev
#SITE=http://sjtv.dev
#DIR=/var/www/sjtv_server/tinypulse-screen

cd $DIR
./phantomjs screencapture.js
`which php` updateTinyPulseResult.php $SITE