# Working on Ghumna Jaun

Ghumna Jaun is a native classic WordPress theme. WordPress, the existing ACF Pro fields and Polylang supply its editable content. It uses the classic editor and classic widgets.

## Where to edit

| Change | Source |
| --- | --- |
| Header, branding and site controls | `header.php` |
| Main navigation and search dialog | `template-parts/navigation.php`, `template-parts/search-dialog.php` |
| Homepage | `front-page.php`, `template-parts/home.php` |
| Destination archive and filters | `archive-destination.php`, `template-parts/explore.php` |
| Destination guide | `single-destination.php`, `template-parts/destination.php` |
| Individual guide sections | `template-parts/destination/` |
| Blog index and category archives | `home.php`, `category.php`, `template-parts/blog.php` |
| Blog article | `single.php`, `template-parts/post.php` |
| Editorial pages and contact form placement | `page.php` |
| FAQ disclosures | `page-faq.php` |
| Footer | `footer.php` |
| Guide fields and site options | `includes/fields.php` |
| WordPress content passed to interactive controls | `includes/site-data.php` |
| Shared presentation helpers | `includes/templates.php` |
| Article links and formatting | `includes/content-formatting.php`, `includes/internal-links.php` |
| Language handling | `includes/languages.php` |
| Responsive images | `includes/performance.php` |
| Browser interactions | `assets/theme.js` |

`functions.php` loads these features in dependency order. Keep field keys, public function names, IDs and `data-*` attributes stable when making routine edits.

## Native templates and content

WordPress selects the root PHP template through its normal template hierarchy. Shared sections load with `get_template_part()`. The server renders the page content; JavaScript adds search suggestions, filters, sliders, photo dialogs, section tracking and optional embeds.

There is no runtime `views/` or `data/` directory. Destinations, page bodies, articles, photos, categories and settings come from the database. Historical import fixtures remain outside the theme in `tools/data/legacy-theme/`; releases do not import or overwrite content.

Native `.po` and `.mo` translation catalogs live in `languages/`. The browser dictionary is generated from the same reviewed Nepali labels. English and Nepali content retains the existing linked Polylang workflow.

## Styles and assets

Edit `assets/styles.css` for the main design, `assets/wordpress.css` for WordPress integration, `assets/nepali.css` for Nepali typography and `assets/fonts.css` for local font declarations. Preserve cascade order unless you intend to change which rule wins.

Run from the repository root after editing stylesheet or translation sources:

```sh
node tools/build-design-runtime.cjs
```

This rebuilds `assets/site.css`, the gettext catalogs and the browser translation dictionary. It does not generate PHP templates or overwrite `assets/theme.js`. The repository's `design/` folder is a historical prototype, not a runtime dependency or the source of theme templates.

## Editing rules

Keep short labels in single-line fields and long descriptions in multiline fields. Store values with their units, such as `45°` or `125 ml`. Both logo controls reference the same light-mode attachment; the dark-mode logo remains a separate existing option. Classic editing, guarded shared slugs and linked translations are configured in theme code. No additional WordPress plugins are introduced.

## Checks

Use the existing PHP formatter with the repository configuration, check PHP syntax and run `git diff --check`. The formatting configuration follows WordPress presentation conventions but is not a full WordPress Coding Standards certification.

Before publishing, check desktop and mobile pages in both languages, including search, filters, origin changes, photo dialogs, theme switching, map/video consent, article categories and contact validation. Never send a real contact message as an automated test.
