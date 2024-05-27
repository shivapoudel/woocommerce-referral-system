<?php
/**
 * Contact Us Box on settings page.
 *
 * @package codup/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WCRS_Contact_Us' ) ) {

	/**
	 * Class Codup_Contact_Us
	 */
	class WCRS_Contact_Us {

		/**
		 * Function Construct
		 */
		public function __construct() {

			if ( isset( $_GET['page'] ) && isset( $_GET['tab'] ) ) {
				if ( 'wc-settings' == $_GET['page'] && 'codup-wc-referral-system' == $_GET['tab'] ) {

					add_action( 'admin_notices', array( $this, 'wcrs_contact_us_support_side' ), 999999 );
					add_action( 'admin_footer', array( $this, 'wcrs_contact_and_rate_us_bottom' ) );
					add_action( 'admin_notices', array( $this, 'wcrs_support_and_rating_admin_notice' ) );
				}
			}

			add_action( 'wp_ajax_wcrs_get_support', array( $this, 'wcrs_get_support' ) );
			add_action( 'wp_ajax_wcrs_give_rating', array( $this, 'wcrs_give_rating' ) );
			add_action( 'wp_ajax_wcrs_remind_later', array( $this, 'wcrs_remind_later' ) );

			add_filter( 'plugin_row_meta', array( $this, 'wcrs_support_and_faq_links' ), 10, 4 );
		}

		/**
		 * Function wcrs support and faq links
		 *
		 * @param array  $links_array Links Array.
		 * @param string $plugin_file_name Plugin File.
		 * @param array  $plugin_data Plugin Data.
		 * @param string $status Status.
		 */
		public function wcrs_support_and_faq_links( $links_array, $plugin_file_name, $plugin_data, $status ) {

			if ( 'woocommerce-referral-system/woocommerce-referral-system.php' == $plugin_file_name || 'codup-wc-referral-system/woocommerce-referral-system.php' == $plugin_file_name ) {

				$links_array[] = __( 'Having trouble in configuration? ', 'codup-wc-referral-system' ) . '<a href="http://ecommerce.codup.io/support/tickets/new" target="_blank">' . __( 'Get Support', 'codup-wc-referral-system' ) . '</a>';

			}
			return $links_array;
		}

		/**
		 * Function generate settings link.
		 *
		 * @param array $links_array Links Array.
		 */
		public function wcrs_settings_link( $links_array ) {

			array_unshift( $links_array, '<a href="' . site_url() . '/wp-admin/admin.php?page=wc-settings&tab=settings_tabs">Settings</a>' );
			return $links_array;
		}
		public function wcrs_contact_and_rate_us_bottom() {
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			// Define the main plugin file path using WCRS_PLUGIN_DIR.
			$main_plugin_file = WCRS_PLUGIN_DIR . '/woocommerce-referral-system.php';

			// Get data of the main plugin file.
			$plugin_data = get_plugin_data( $main_plugin_file );
			$plugin_name = $plugin_data['Name']; // Extract the name of the plugin.

			?>
			<div style="padding: 10%;"> 
				<h3> If you like <strong> <?php echo esc_html( $plugin_name ); ?> </strong> </strong>, 
					<a href="https://woocommerce.com/products/referral-system-for-woocommerce/" target="_blank" style="color: black; text-decoration: underline;">
						please leave us a &#9733;&#9733;&#9733;&#9733;&#9733; rating
					</a>. A huge thank you in advance! 
				</h3>
			</div>
			<?php
		}

		/**
		 * Function wcrs contact us form.
		 */
		public function wcrs_contact_us_support_side() {
			?>
			<div class="notice notice-success codup-contact-us" style="position: absolute; right: 20px; top:40%; z-index: -1;">
				<h3>
					Support Hub 
				</h3>
				<p>
					<?php echo wp_kses_post( __( 'Show us some love! Rate us by clicking here', 'codup-wc-referral-system' ) ); ?><a href="https://woocommerce.com/products/referral-system-for-woocommerce/"><?php echo wp_kses_post( __( '   Give Rating   ', 'codup-wc-referral-system' ) ); ?></a>
				</p>
				<p>
					<?php echo wp_kses_post( __( 'Having trouble in configuration?', 'codup-wc-referral-system' ) ); ?><a href="http://ecommerce.codup.io/support/tickets/new"><?php echo wp_kses_post( __( '   Contact us   ', 'codup-wc-referral-system' ) ); ?></a><?php echo wp_kses_post( __( 'for support.', 'codup-wc-referral-system' ) ); ?>
				</p>
				<p>
					<?php echo wp_kses_post( __( 'Or email your query at', 'codup-wc-referral-system' ) ); ?> <a href="mailto:woosupport@codup.co"> <?php echo wp_kses_post( __( 'woosupport@codup.co', 'codup-wc-referral-system' ) ); ?> </a>
				</p>        
			</div>
			<?php
		}



		public function wcrs_support_and_rating_admin_notice() {
			?>
			<div id="my-custom-notice" class="notice notice-success is-dismissible">
				<p><?php esc_html_e( "Thanks for choosing the Referral System plugin! We're one click away if you want to reach out for support and feedback.", 'codup-wc-referral-system' ); ?></p>
				<p>
					<a href="http://ecommerce.codup.io/support/tickets/new" class="button button-primary" id="get-support" target="_blank"><?php esc_html_e( 'Get Support', 'codup-wc-referral-system' ); ?></a>
					<a href="https://woocommerce.com/products/referral-system-for-woocommerce/" class="button button-secondary" id="give-rating" target="_blank"><?php esc_html_e( 'Give Rating', 'codup-wc-referral-system' ); ?></a>
					<a href="#" class="button button-secondary" id="remind-later"><?php esc_html_e( 'Remind Me Later', 'codup-wc-referral-system' ); ?></a>
				</p>
			</div>
			<?php
		}


		public function wcrs_get_support() {
			$current_user_ = wp_get_current_user();
			if ( ! ( $current_user_ instanceof WP_User ) ) {
				return;
			}
			$email      = $current_user_->user_email;
			$first_name = $current_user_->user_firstname;
			$last_name  = $current_user_->user_lastname;
			$site_name  = site_url();
			if ( ! class_exists( 'Mixpanel' ) ) {
				require_once WCRS_PLUGIN_DIR . '/vendor/autoload.php';
			}
			$mp = Mixpanel::getInstance( '8bf28f655698e789b860967e26a60737' );
			$mp->track(
				'Get Support Top',
				array(
					'label'       => 'Get Support Top',
					'distinct_id' => $email,
					'Website'     => $site_name,
				)
			);

			// Create/update a profile for user
			$mp->people->set(
				$email,
				array(
					'$first_name' => $first_name,
					'$last_name'  => $last_name,
					'$email'      => $email,
					'Website'     => $site_name,
				)
			);
		}

		public function wcrs_give_rating() {
			$current_user_ = wp_get_current_user();
			if ( ! ( $current_user_ instanceof WP_User ) ) {
				return;
			}
			$email      = $current_user_->user_email;
			$first_name = $current_user_->user_firstname;
			$last_name  = $current_user_->user_lastname;
			$site_name  = site_url();
			if ( ! class_exists( 'Mixpanel' ) ) {
				require_once WCRS_PLUGIN_DIR . '/vendor/autoload.php';
			}
			$mp = Mixpanel::getInstance( '8bf28f655698e789b860967e26a60737' );
			$mp->track(
				'Give Rating Top',
				array(
					'label'       => 'Give Rating Top',
					'distinct_id' => $email,
					'Website'     => $site_name,
				)
			);

			// Create/update a profile for user
			$mp->people->set(
				$email,
				array(
					'$first_name' => $first_name,
					'$last_name'  => $last_name,
					'$email'      => $email,
					'Website'     => $site_name,
				)
			);
		}


		public function wcrs_remind_later() {
			$current_user_ = wp_get_current_user();
			if ( ! ( $current_user_ instanceof WP_User ) ) {
				return;
			}
			$email      = $current_user_->user_email;
			$first_name = $current_user_->user_firstname;
			$last_name  = $current_user_->user_lastname;
			$site_name  = site_url();
			if ( ! class_exists( 'Mixpanel' ) ) {
				require_once WCRS_PLUGIN_DIR . '/vendor/autoload.php';
			}
			$mp = Mixpanel::getInstance( '8bf28f655698e789b860967e26a60737' );
			$mp->track(
				'Remind Me Later',
				array(
					'label'       => 'Remind Me Later',
					'distinct_id' => $email,
					'Website'     => $site_name,
				)
			);

			// Create/update a profile for user
			$mp->people->set(
				$email,
				array(
					'$first_name' => $first_name,
					'$last_name'  => $last_name,
					'$email'      => $email,
					'Website'     => $site_name,
				)
			);
		}
	}

}
new WCRS_Contact_Us();
