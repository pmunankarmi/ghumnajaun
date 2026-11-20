<?php
/** Social icons. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;
?>
<ul class="social-icons" aria-label="<?php esc_attr_e( 'Social media', 'ghumnajaun' ); ?>">
	<?php $url = gj_option( 'social_youtube' );

if ( $url ) : ?>
	<li>
		<a
			class="social-icon"
			aria-label="YouTube"
			title="YouTube"
			href="<?php echo esc_url( $url ); ?>"
			target="_blank"
			rel="noopener noreferrer"
		>
			<svg aria-hidden="true" viewbox="0 0 24 24">
				<path
					d="M22 7.4a2.8 2.8 0 0 0-2-2C18.2 5 12 5 12 5s-6.2 0-8 .4a2.8 2.8 0 0 0-2 2A29 29 0 0 0 2 12a29 29 0 0 0 .4 4.6 2.8 2.8 0 0 0 2 2C6.2 19 12 19 12 19s6.2 0 8-.4a2.8 2.8 0 0 0 2-2 29 29 0 0 0 .4-4.6 29 29 0 0 0-.4-4.6ZM10 15.2v-6.4l5.5 3.2-5.5 3.2Z"
					fill-rule="evenodd"
				></path>
			</svg>
		</a>
	</li>
	<?php endif;
$url = gj_option( 'social_tiktok' );

if ( $url ) : ?>
	<li>
		<a
			class="social-icon"
			aria-label="TikTok"
			title="TikTok"
			href="<?php echo esc_url( $url ); ?>"
			target="_blank"
			rel="noopener noreferrer"
		>
			<svg aria-hidden="true" viewbox="0 0 24 24">
				<path
					d="M14 2h3.2c.3 2.4 1.7 4 4 4.3v3.2a8.8 8.8 0 0 1-4-1.3v7.5a6.3 6.3 0 1 1-6.3-6.3c.4 0 .8 0 1.1.1v3.3a3.1 3.1 0 1 0 2 2.9V2Z"
				></path>
			</svg>
		</a>
	</li>
	<?php endif;
$url = gj_option( 'social_instagram' );

if ( $url ) : ?>
	<li>
		<a
			class="social-icon"
			aria-label="Instagram"
			title="Instagram"
			href="<?php echo esc_url( $url ); ?>"
			target="_blank"
			rel="noopener noreferrer"
		>
			<svg aria-hidden="true" viewbox="0 0 24 24">
				<g fill="none" stroke="currentColor" stroke-width="1.9">
					<rect height="18" rx="5" width="18" x="3" y="3"></rect>
					<circle cx="12" cy="12" r="4"></circle>
				</g>
				<circle cx="17.5" cy="6.7" r="1.15"></circle>
			</svg>
		</a>
	</li>
	<?php endif;
$url = gj_option( 'social_facebook' );

if ( $url ) : ?>
	<li>
		<a
			class="social-icon"
			aria-label="Facebook"
			title="Facebook"
			href="<?php echo esc_url( $url ); ?>"
			target="_blank"
			rel="noopener noreferrer"
		>
			<svg aria-hidden="true" viewbox="0 0 24 24">
				<path
					d="M14 22v-9h3l.5-3.5H14V7.3c0-1 .3-1.7 1.8-1.7h1.9V2.4a24 24 0 0 0-2.8-.2c-2.8 0-4.8 1.7-4.8 4.9v2.4H7V13h3.1v9H14Z"
				></path>
			</svg>
		</a>
	</li>
	<?php endif; ?>
</ul>
