#!/bin/bash
set -euo pipefail
if [ -f /Users/puskar/Documents/Sites/ghumnajaun/app/public/wp-content/themes/ghumnajaun/templates/index.html ]; then
 mv /Users/puskar/Documents/Sites/ghumnajaun/app/public/wp-content/themes/ghumnajaun/templates "/tmp/ghumnajaun-obsolete-templates-$(date +%s)"
fi
cp -R /Users/puskar/Documents/ChatGPT/Ghumnajaun/wordpress/ghumnajaun/. /Users/puskar/Documents/Sites/ghumnajaun/app/public/wp-content/themes/ghumnajaun/
