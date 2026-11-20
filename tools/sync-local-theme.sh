#!/bin/bash
set -euo pipefail
if [ -f /Users/puskar/Documents/Sites/ghumnajaun/app/public/wp-content/themes/ghumnajaun/templates/index.html ]; then
 mv /Users/puskar/Documents/Sites/ghumnajaun/app/public/wp-content/themes/ghumnajaun/templates "/tmp/ghumnajaun-obsolete-templates-$(date +%s)"
fi
gj_installed_version=$(sed -n 's/^Version: *//p' /Users/puskar/Documents/Sites/ghumnajaun/app/public/wp-content/themes/ghumnajaun/style.css)
cp -R /Users/puskar/Documents/ChatGPT/Ghumnajaun/wordpress/ghumnajaun/. /Users/puskar/Documents/Sites/ghumnajaun/app/public/wp-content/themes/ghumnajaun/

/opt/homebrew/bin/php -r '$file=$argv[1];$content=file_get_contents($file);preg_match("/^Version: *(.*)$/m",$content,$m);if(version_compare($argv[2],$m[1]??"0",">"))file_put_contents($file,preg_replace("/^Version:.*$/m","Version: ".$argv[2],$content));' /Users/puskar/Documents/Sites/ghumnajaun/app/public/wp-content/themes/ghumnajaun/style.css "$gj_installed_version"

# Keep obsolete runtime files outside the installed native theme for easy recovery.
gj_theme_dir=/Users/puskar/Documents/Sites/ghumnajaun/app/public/wp-content/themes/ghumnajaun
gj_obsolete_backup=$(mktemp -d /tmp/ghumnajaun-obsolete.XXXXXX)
for gj_obsolete in views data includes/design.php assets/design.js; do
 if [ -e "$gj_theme_dir/$gj_obsolete" ]; then
  mkdir -p "$(dirname "$gj_obsolete_backup/$gj_obsolete")"
  mv "$gj_theme_dir/$gj_obsolete" "$gj_obsolete_backup/$gj_obsolete"
 fi
done
bash /Users/puskar/Documents/ChatGPT/Ghumnajaun/tools/wp-local.sh eval 'wp_clean_themes_cache();'
