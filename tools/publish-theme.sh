#!/bin/bash
set -euo pipefail
cd /Users/puskar/Documents/ChatGPT/Ghumnajaun
if ! git remote get-url origin >/dev/null 2>&1; then git remote add origin https://github.com/pmunankarmi/ghumnajaun.git; fi
if ! git rev-parse --verify HEAD >/dev/null 2>&1; then git symbolic-ref HEAD refs/heads/codex/wordpress-theme; fi
git add .gitignore .github design wordpress tools CONTACT-DELIVERY.txt
git commit -m "Audit WordPress desktop and mobile designs, social links and shared SVG logo"
git push -u origin HEAD:main
