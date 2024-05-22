<?php
/**
 * The Template for displaying Social Share.
 *
 * @package codup-wc-referral-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$share_description = urlencode_deep( get_option( 'wcrs_sharing_description' ) );
if ( empty( $share_description ) ) {
	$share_description = __( 'Come and Join To Earn Rewards Now!', 'codup-wc-referral-system' );
}

?>
	<div class="cwrs_share">
		<span><?php esc_html_e( 'Share On : ', 'codup-wc-referral-system' ); ?></span>
		<span>
			<a href="mailto:?subject=Signup Now.&amp;body=
			<?php
			echo esc_html( rawurlencode( get_option( 'wcrs_sharing_description' ) ) );
			echo ' ' . esc_html( $referral_link );
			?>
			." title="Share by Email">
				<img src="<?php echo esc_html( WCRS_PLUGIN_DIR_URL . 'assets/images/email.png' ); ?>" class="social_image" alt="Email">
		</a>
		</span>
		<span>
			<!-- Facebook -->
			<a href="#" onclick="share('<?php echo esc_html( $referral_link ); ?>','<?php echo esc_html( $share_description ); ?>');" data-reflink="<?php echo esc_html( $referral_link ); ?>" data-desc="<?php echo esc_html( $share_description ); ?>">
				<img src="<?php echo esc_html( WCRS_PLUGIN_DIR_URL . 'assets/images/facebook.png' ); ?>" class="social_image" alt="Facebook" />
			</a>
		</span>
		<span>
			<!-- LinkedIn -->
			<a href="#" onclick="open_share_window('http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo esc_html( $referral_link ); ?>');" >
				<img src="<?php echo esc_html( WCRS_PLUGIN_DIR_URL . 'assets/images/linkedin.png' ); ?>" class="social_image" alt="LinkedIn" />
			</a>
		</span>	
		<span>
			<!-- Twitter -->
			<a href="#" onclick="open_share_window('https://twitter.com/share?url=<?php echo esc_html( $referral_link ); ?>&amp;text=<?php echo esc_html( $share_description ); ?>&amp;');" >
				<img src="<?php echo esc_html( WCRS_PLUGIN_DIR_URL . 'assets/images/twitter.png' ); ?>" alt="Twitter" class="social_image" />
			</a>
		</span>
		<span>
			<!-- WhatsApp -->
			<a href="#" onclick="open_share_window('https://api.whatsapp.com/send?text=<?php echo esc_html( $share_description ); ?><?php echo esc_html( $referral_link ); ?>');" >
				<img src="<?php echo esc_url( WCRS_PLUGIN_DIR_URL . 'assets/images/whatsapp.png' ); ?>" alt="WhatsApp" class="social_image" />
			</a>
		</span>
		<span>
			<!-- Telegram -->
			<a href="#" onclick="open_share_window('https://t.me/share/url?url=<?php echo esc_html( $referral_link ); ?>&text=<?php echo esc_html( $share_description ); ?>');" >
				<img src="<?php echo esc_html( WCRS_PLUGIN_DIR_URL . 'assets/images/telegram.png' ); ?>" alt="Telegram" class="social_image" />
			</a>
		</span>
			<?php do_action( 'wcrs_after_social_media_icon', $referral_link, $share_description ); ?>	
	</div>

