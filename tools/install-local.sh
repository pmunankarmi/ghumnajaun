#!/bin/bash
set -euo pipefail
SITE=/Users/puskar/Documents/Sites/ghumnajaun/app/public
SOURCE=/Users/puskar/Documents/ChatGPT/Ghumnajaun
SOCKET='/Users/puskar/Library/Application Support/Local/run/-DjACOZD1/mysql/mysqld.sock'
WP=/Applications/Local.app/Contents/Resources/extraResources/bin/wp-cli/wp-cli.phar
cp -R "$SOURCE/wordpress/ghumnajaun" "$SITE/wp-content/themes/"
for plugin in advanced-custom-fields-pro polylang; do
 if [ ! -d "$SITE/wp-content/plugins/$plugin" ]; then cp -R "/Users/puskar/Documents/Sites/SAB/app/public/wp-content/plugins/$plugin" "$SITE/wp-content/plugins/"; fi
done
wpcli(){ /opt/homebrew/bin/php -d "mysqli.default_socket=$SOCKET" "$WP" --path="$SITE" "$@"; }
wpcli plugin activate advanced-custom-fields-pro polylang
wpcli theme activate ghumnajaun
wpcli rewrite structure '/%postname%/'
export GJ_MEDIA_CACHE=/tmp/ghumnajaun-media
wpcli eval-file "$SOURCE/tools/finish-wordpress.php"
