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
- Languages: English and Nepali; add linked translations through Polylang's + buttons. Existing content has an initial Nepali translation; theme updates never invoke translation services or overwrite editorial copy.

Editable ACF text appears in English/नेपाली pairs, including nested guide rows, SEO and homepage settings. Both sets of ACF values synchronize when either linked translation is saved; removed repeater rows are also removed from its counterpart. Native titles, page bodies and excerpts remain independent Polylang translations. Images and other shared ACF values travel with the bilingual bundle. On Nepali routes, empty Nepali fields fall back to English. Existing linked records are synchronized on their next save; no bulk content overwrite runs on theme updates.

The November 2026 Nepali migration covers 57 published guides, four articles, eight pages, two draft samples, 51 taxonomy terms, menus, homepage settings, and 1,472 nonempty paired ACF values. Google Translate supplied the first pass. A subsequent full dictionary review revised 780 entries for natural Nepali, consistent place names and respectful instructions. Linked paragraphs have separate reviewed templates so links can appear in natural Nepali sentence order. Storage keys and other technical identifiers retain their exact English values. Prices, schedules and factual claims were translated without re-verification.

`tools/review-nepali.php BEFORE_DICTIONARY CONTENT_SNAPSHOT` previews editorial database changes. Append `apply` to update recognized generated text, paired fields, terms and media descriptions while preserving separate edits. Both input files are private working snapshots; database backups precede content edits. Reviewed paragraph templates live in `tools/data/ne-editorial-paragraphs.json` and are copied into the theme by the design build.

`tools/export-nepali.php` creates a private working snapshot and ACF schema in `/tmp`. `tools/translate-nepali.py` builds a resumable dictionary in `tools/data/ne-translations.json`; it sends visible text to Google Translate and preserves HTML, URLs and technical fields. After taking a database backup, explicitly run `tools/populate-nepali.php` with WP-CLI to fill empty Nepali values and create linked records. `tools/verify-nepali.php` checks coverage, synchronization and preservation against the working snapshot. These are manual migration tools, never theme hooks. Preserve reviewed dictionary entries when adding future translations.

Nepali pages use locally hosted Noto Sans Devanagari (400–700), with script-appropriate line height and heading spacing; English retains DM Sans and Instrument Serif. The font's OFL license is bundled in `assets/fonts/`. Static and enhanced interface labels use the same dictionary. Internal routing/filter keys and form option values remain stable; Nepali search includes translated destination names. The design build copies the dictionary into the theme for releases.

Short values and units stay in a single field. Gutenberg and block widgets are disabled. ACF logo and Customizer logo reference the same attachment ID in both directions.

The theme owns its destination registration and fields. Changing to a different theme hides those editing screens; database records remain intact.

## GitHub automatic updates

Repository: https://github.com/pmunankarmi/ghumnajaun

A push affecting the theme or `design/` on `main` runs `.github/workflows/theme-release.yml`. The workflow rebuilds the original design assets, lints PHP, packages only the theme, assigns an increasing `1.1.BUILD` version, and publishes a GitHub release containing `ghumnajaun.zip` and a public `ghumnajaun-update.json` manifest.

The active theme checks the repository's latest public release manifest about every five minutes, avoiding GitHub's anonymous API rate limit. It offers and installs newer versions through WordPress's native theme upgrader. The admin displays the installed version and last update status. An open administrator screen also triggers background checks without an Update button. Public-site requests trigger normal WP-Cron.

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
3. Run `node tools/build-design-runtime.cjs` to copy the latest original CSS/layouts and build the WordPress-connected interaction script.
4. Run `python3 tools/cache-photos.py` to cache referenced originals in `/tmp/ghumnajaun-media`. The optional panorama helper downloads its smaller image.
5. Install the `wordpress/ghumnajaun` directory in wp-content/themes and activate it.
6. Run `wp eval-file /absolute/path/to/tools/finish-wordpress.php` using the site's PHP/MySQL connection.

The project-specific `tools/install-local.sh` and `tools/wp-local.sh` use this machine's Local paths. Imported destination/page records are reused, preserving later editorial edits. `finish-wordpress.php` fills missing media and initializes languages/menus; it is a setup tool, not an update hook.

## Public API

`/wp-json/ghumnajaun/v1/destinations`, `/destinations/ID`, `/search?q=bhaktapur`, `/filters`.

List/search supports `page`, capped `per_page` (maximum 50), `city`, `type`, `category`, `lang` and `q`; list headers contain totals. Only published destinations are returned. The native WordPress REST editor endpoints retain standard authorization.

## Local macOS compatibility

An observed PHP-FPM crash in the macOS asynchronous IPv6 resolver is avoided by using IPv4 for WordPress HTTP requests only when `WP_ENVIRONMENT_TYPE=local` and PHP runs on Darwin. HTTPS certificate verification remains enabled. Production transport is unchanged.

## Original HTML design fidelity

`design/styles.css` is the canonical visual stylesheet. The build copies it byte-for-byte into the theme. The original HTML section markup is kept in `views/` (not `templates/`, so WordPress recognizes a classic theme). `assets/design.js` is generated from the original prototype interactions and reads published WordPress/ACF data. Server-rendered content is present before JavaScript runs.

CSS and JavaScript URLs include their SHA-256 content hashes, so any deployed change creates a fresh asset URL. Run `node tools/build-design-runtime.cjs` after changes to the original design; GitHub Actions runs it automatically for releases. Code changes do not re-import or overwrite admin content.

The starting-point selector, city cards, outing chips, banner slider, destination gallery/popups, sticky guide navigation, consent controls and original contact-form layout are retained. Source-only sample restaurant records remain drafts.

The managed updater scopes its VCS exception to this theme's explicit release-install operation. A Git repository in an ancestor such as the user's home directory therefore does not prevent the requested theme updates; other native WordPress update checks retain their VCS behavior.

## Full design audit

The 19 November 2026 audit covers the 57 published guides, four articles, and nine main page/archive routes at 1440 px and 390 px. Two prototype restaurant samples remain drafts, so Explore intentionally has two fewer cards. The WordPress toolbar is accommodated separately from the public layout.

Photo Credits uses the original `.photo-credits` wrapper and 75 unique source entries, with thumbnails, author and license links. About/Contact and policy markup bypass automatic paragraph insertion when already structured HTML. The supplied Privacy Policy replaces the unused default draft; the default Sample Page is in Trash.

Guide fields retain source visit-card markup, optional video, feature image, transport references, meal stages and links, separate accommodation rows, and nearby stops. Article bodies retain chapter, destination-link and source sections. Curated city-card destinations are editable on each City term. Initial values and one-time repair fixtures are in `tools/data/`; `tools/migrate-design-audit.php` saves the previous values in private metadata and does not repeat an already audited record.

Site settings contains YouTube, TikTok, Instagram and Facebook URLs. Footer icons use those settings. The owner's supplied light SVG is shared by ACF and Customizer. A separate dark-mode attachment uses the white-and-peach palette. `tools/install-requested-logo.php` permits its validated SVG only during this one-time import; it does not enable general SVG uploads.

For a new development installation, run the initial importer, the one-time audit-content/guide migration scripts, and the requested-logo installer after copying the theme. Normal GitHub releases do not run these content migrations.

## Light and dark logo options

Site settings provides separate Light mode logo and Dark mode logo image pickers. The light logo remains synchronized with the WordPress Customizer Site Logo; the dark logo is an independent attachment. Both the header and footer switch automatically with the theme setting and the visitor's system color preference. The default dark SVG uses white and peach, matching the supplied reference.
