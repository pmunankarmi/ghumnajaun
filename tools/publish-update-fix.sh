#!/bin/bash
set -euo pipefail
cd /Users/puskar/Documents/ChatGPT/Ghumnajaun
bash tools/sync-local-theme.sh
git add wordpress/ghumnajaun/includes/updates.php .github/workflows/theme-release.yml wordpress/README.md tools/publish-update-fix.sh
git commit -m "Use public release manifest for reliable automatic theme updates"
git push origin HEAD:main
