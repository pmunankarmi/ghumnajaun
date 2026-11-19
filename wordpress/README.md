# Ghumna Jaun WordPress theme

This implementation supersedes the earlier backend-plugin handoff. All project functionality lives in the classic `ghumnajaun` theme, as requested. ACF Pro and Polylang are dependencies, not custom project plugins.

## Local installation

- Local site: Ghumna Jaun
- URL: http://ghumna-jaun.local
- Admin: http://ghumna-jaun.local/wp-admin/
- Installation: `/Users/puskar/Documents/Sites/ghumnajaun`
- Theme source: `wordpress/ghumnajaun/`
- Admin username: `pmunankarmi` (password was entered by the owner in Local)
- Local search indexing is disabled. Enable it only when launching a public production installation.

## Editing

- Destinations: native titles, descriptions, excerpts, featured images, city/type/outing taxonomies, ACF galleries, stops, history, visit facts, transport, facilities, itinerary, sources and related destinations.
- Pages: About, Contact and policy copy use the classic editor.
- Posts: native blog editing.
- Site settings: homepage headline, introduction, cover, featured destinations, contact recipient and logo.
- Appearance → Menus: primary and footer navigation.
- Media: alt text, author, source URL and license. Photo Credits reads these records.
- Languages: English and Nepali; add linked translations through Polylang's + buttons. No machine translations are generated.

Short values and units stay in a single field. Gutenberg and block widgets are disabled. ACF logo and Customizer logo reference the same attachment ID in both directions.

The theme owns its destination registration and fields. Changing to a different theme hides those editing screens; database records remain intact.

## GitHub automatic updates

Repository: https://github.com/pmunankarmi/ghumnajaun

A push affecting `wordpress/ghumnajaun/` on `main` runs `.github/workflows/theme-release.yml`. The workflow lints PHP, packages only the theme, assigns an increasing `1.1.BUILD` version, and publishes a GitHub release containing `ghumnajaun.zip`.

The active theme checks the repository's latest release about every five minutes. It offers and installs newer versions through WordPress's native theme upgrader. The admin displays the installed version and last update status. An open administrator screen also triggers background checks without an Update button. Public-site requests trigger normal WP-Cron.

This requires GitHub Actions, a public repository/release, outbound HTTPS, a running Local site and writable theme files. It is polling, not instant push delivery. A closed Local app or idle site with no admin tab cannot perform work. For unattended production operation, configure a host scheduler to run WordPress cron at least every five minutes.

Only theme files are updated. Pages, guides, ACF values, options, translations and uploads stay in the database/media directory. Import scripts are deliberately outside the theme and never run during updates. Edit code through GitHub; local edits to installed theme files are replaced by the next update.

Do not put ACF Pro code, license keys, wp-config.php, database dumps or credentials in the public repository. The local installer reuses the owner's existing ACF Pro and Polylang installations. The ACF license remains the owner's responsibility.

## SEO

Server-rendered content, descriptive permalinks, native XML sitemaps, canonical URLs, configurable SEO title/description, Open Graph, Twitter card metadata, TouristAttraction and BlogPosting JSON-LD, native image sizes and alt text. Theme metadata defers to Yoast, Rank Math or AIOSEO when detected. Polylang manages language URL/hreflang behavior. Search/filter results are noindex. Local remains noindex.

Imported prices, hours and transport notes are editorial source content; no current verification date is invented.

## Contact

The WordPress form uses admin-ajax, a nonce, validation, honeypot, hashed-IP rate limiting and wp_mail. It retains text when delivery fails. Local captures mail through its mail service. Production needs a working mail transport and appropriate domain DNS; no live external message is sent by setup or verification.

`CONTACT-DELIVERY.txt` describes the separate historical prototype endpoint; it is not the WordPress handler.

## Reproducible migration

From this repository:

1. Create a clean Local site and activate ACF Pro and Polylang.
2. Run `node tools/extract-content.cjs` if prototype data changed.
3. Run `python3 tools/cache-photos.py` to cache referenced originals in `/tmp/ghumnajaun-media`. The optional panorama helper downloads its smaller image.
4. Install the `wordpress/ghumnajaun` directory in wp-content/themes and activate it.
5. Run `wp eval-file /absolute/path/to/tools/finish-wordpress.php` using the site's PHP/MySQL connection.

The project-specific `tools/install-local.sh` and `tools/wp-local.sh` use this machine's Local paths. Imported destination/page records are reused, preserving later editorial edits. `finish-wordpress.php` fills missing media and initializes languages/menus; it is a setup tool, not an update hook.

## Public API

`/wp-json/ghumnajaun/v1/destinations`, `/destinations/ID`, `/search?q=bhaktapur`, `/filters`.

List/search supports `page`, capped `per_page` (maximum 50), `city`, `type`, `category`, `lang` and `q`; list headers contain totals. Only published destinations are returned. The native WordPress REST editor endpoints retain standard authorization.

## Local macOS compatibility

An observed PHP-FPM crash in the macOS asynchronous IPv6 resolver is avoided by using IPv4 for WordPress HTTP requests only when `WP_ENVIRONMENT_TYPE=local` and PHP runs on Darwin. HTTPS certificate verification remains enabled. Production transport is unchanged.
