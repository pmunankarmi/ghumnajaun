#!/bin/bash
set -euo pipefail
if [ -f /Users/puskar/Documents/Sites/ghumnajaun/app/public/wp-content/themes/ghumnajaun/templates/index.html ]; then
 mv /Users/puskar/Documents/Sites/ghumnajaun/app/public/wp-content/themes/ghumnajaun/templates "/tmp/ghumnajaun-obsolete-templates-$(date +%s)"
fi
gj_installed_version=$(sed -n 's/^Version: *//p' /Users/puskar/Documents/Sites/ghumnajaun/app/public/wp-content/themes/ghumnajaun/style.css)
cp -R /Users/puskar/Documents/ChatGPT/Ghumnajaun/wordpress/ghumnajaun/. /Users/puskar/Documents/Sites/ghumnajaun/app/public/wp-content/themes/ghumnajaun/

/opt/homebrew/bin/php -r '$file=$argv[1];$content=file_get_contents($file);preg_match("/^Version: *(.*)$/m",$content,$m);if(version_compare($argv[2],$m[1]??"0",">"))file_put_contents($file,preg_replace("/^Version:.*$/m","Version: ".$argv[2],$content));' /Users/puskar/Documents/Sites/ghumnajaun/app/public/wp-content/themes/ghumnajaun/style.css "$gj_installed_version"
