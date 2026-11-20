/**
 * Build production assets for the native classic WordPress theme.
 * PHP templates and assets/theme.js are source files, never generated from HTML.
 */
const fs = require('fs');
const path = require('path');
const { execFileSync } = require('child_process');
const root = path.resolve(__dirname, '..');
const assets = path.join(root, 'wordpress/ghumnajaun/assets');

execFileSync('python3', [path.join(__dirname, 'build-translations.py')], {
	stdio: 'inherit',
});

// Preserve cascade order while removing external font imports from the bundle.
const stylesheets = [
	'fonts.css',
	'vendor/bootstrap-grid.min.css',
	'vendor/swiper-bundle.min.css',
	'vendor/aos.css',
	'styles.css',
	'wordpress.css',
	'nepali.css',
];
const css = stylesheets.map((file) => {
	const source = fs.readFileSync(path.join(assets, file), 'utf8');
	return `/* Source: ${file} */\n${source.replace(/@import\s+url\([^)]*\)\s*;/g, '')}`;
});
fs.writeFileSync(path.join(assets, 'site.css'), css.join('\n'));
console.log(
	'Built the local stylesheet bundle; native templates were left intact.',
);
