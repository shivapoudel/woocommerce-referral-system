<?php
/**
 * Mixpanel alerts and data tracking .
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Referral_System_Mixpanel' ) ) {

	/**
	 * Mixpanel data tracking class.
	 */
	class Referral_System_Mixpanel {

		/**
		 * Constructor.
		 */
		public function __construct() {

			add_action( 'admin_init', array( $this, 'wcrs_mixpanel_tracking_alert' ) );
			add_action( 'wp_ajax_wcrs_mixpanel_alert_ajax_function', array( $this, 'wcrs_mixpanel_alert_ajax_function' ) );
		}

		/**
		 * Trigger mixpenal alert box on plugin activation.
		 */
		public function wcrs_mixpanel_tracking_alert() {

			$alert = get_option( 'wwcrs_mixpanel_tracking_alert_show' );
			if ( ! $alert ) {
				$nonce = wp_create_nonce( 'wcrs_mixpanel_alert_nonce' );
				echo "<script>var wcrs_nonce = '" . esc_js( $nonce ) . "';</script>";
				?>
				<!-- The Modal -->
				<div id="wcrs-myModal" class="wcrs-modal">
					<!-- Modal content -->
					<div class="wcrs-modal-content"> 
						<div class="content-div ">
							<h1 style="text-align: center;"> WooCommerce Referral System </h1>
							</br>
							<p>
								<?php
								$url         = 'https://mixpanel.codupstaging.com/privacy-policy-mixpanel.txt';
								$textContent = file_get_contents( $url );
								echo nl2br( esc_html( $textContent ) );
								?>
							</p>
							<div class="button-div ">
								<button id="wcrs-deny"><span> Deny </span></button>
								<button id="wcrs-allow">Allow</button>
								<div class="button-content-div "> 
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
		}

		/**
		 * Ajax call back function on Alertbox Deny and Allow.
		 */
		public function wcrs_mixpanel_alert_ajax_function() {
			// Verify the nonce
			if ( isset( $_POST['nonce'] ) ) {
				$nonce = sanitize_text_field( $_POST['nonce'] );
				if ( wp_verify_nonce( $nonce, 'wcrs_mixpanel_alert_nonce' ) ) {
					if ( isset( $_POST['value'] ) ) {
						$value = intval( $_POST['value'] );
						update_option( 'wwcrs_mixpanel_tracking_alert_show', 'yes' );
						if ( '1' == $value ) {
							update_option( 'wcrs_user_choice_box', true );
							$this->wcrs_allow();
						} else {
							update_option( 'wcrs_user_choice_box', false );
							$this->wcrs_deny();
						}
					} else {
						echo 'Error: No value received';
					}
				}
			} else {
				echo 'Error: Nonce verification failed';
			}
			wp_die();
		}

		/**
		 * Function to run when user Deny to data tracking.
		 */
		public function wcrs_deny() {
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
					'Denied Tracking',
					array(
						'label'       => 'Denied Tracking',
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

		/**
		 * Function to run when user Allow to data tracking.
		 */
		public function wcrs_allow() {
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
					'Allowed Tracking',
					array(
						'label'       => 'Allowed Tracking',
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

$wcrs_loader = new Referral_System_Mixpanel();
