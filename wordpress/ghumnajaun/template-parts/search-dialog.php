<?php
/** Header search dialog. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;
?>
<dialog
	class="search-dialog"
	id="gj-search-dialog"
	aria-labelledby="gj-search-title"
>
	<div class="search-dialog-heading">
		<h2 id="gj-search-title">Search places</h2>
		<button
			class="search-close"
			id="gj-search-close"
			type="button"
			aria-label="Close search"
		>
			<svg viewBox="0 0 24 24" aria-hidden="true">
				<path d="m6 6 12 12M18 6 6 18" />
			</svg>
		</button>
	</div>
	<form
		class="header-search"
		id="gj-header-search"
		action="<?php echo esc_url( gj_home_url( '/explore/' ) ); ?>"
		method="get"
		role="search"
		aria-label="Find places"
	>
		<input type="hidden" name="from" disabled />
		<input
			id="gj-search-input"
			type="search"
			name="q"
			aria-label="Search places"
			placeholder="Place, area or activity"
			maxlength="100"
			autocomplete="off"
			autofocus
			required
		/>
		<button class="primary" type="submit">Search</button>
		<div class="header-suggestions" id="gj-search-suggestions" hidden>
			<p id="gj-search-status" role="status"></p>
			<ul id="gj-search-matches" aria-label="Matching destinations"></ul>
			<a
				class="page-link search-all"
				id="gj-search-all"
				href="<?php echo esc_url( gj_home_url( '/explore/' ) ); ?>"
				hidden
			>
				View all results
				<span aria-hidden="true">↗</span>
			</a>
		</div>
	</form>
</dialog>
