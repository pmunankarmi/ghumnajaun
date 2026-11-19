#!/bin/bash
set -euo pipefail
exec /opt/homebrew/bin/php -d error_reporting=24575 -d 'mysqli.default_socket=/Users/puskar/Library/Application Support/Local/run/-DjACOZD1/mysql/mysqld.sock' /Applications/Local.app/Contents/Resources/extraResources/bin/wp-cli/wp-cli.phar --path=/Users/puskar/Documents/Sites/ghumnajaun/app/public "$@"
