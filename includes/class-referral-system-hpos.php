<?php
/**
 * Ensure compatibility between orders and the WooCommerce HPOS (High Performing Order Storage) feature.
 *
 * @package WooCommerce Referral System.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Automattic\WooCommerce\Utilities\OrderUtil;

if ( ! class_exists( 'Referral_System_Hpos' ) ) {

	/**
	 * Class for orders hpos feature.
	 */
	class Referral_System_Hpos {
		/**
		 * Constructor
		 */
		public function __construct() {
		}

		/**
		 * Verify the status of HPOS (High Performing Order Storage) to determine if it is enabled or not.
		 */
		public function is_hpos_enable() {
			if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Add sharing div on thank you page.
		 *
		 * @param int $order_id order_id.
		 */
		public function modify_thankyou_page( $order_id ) {
			if ( is_user_logged_in() ) {
				$referral_link = wcrs_get_current_referral_url();
				?>
				<div class="wcrs_thankyou_wrapper">
					<span>
						<?php wc_get_template( '/frontend/referral-link-content.php', array( 'referral_link' => $referral_link ), 'woocommerce-referral-system', WCRS_TEMP_DIR ); ?>
					</span>
				</div>
				<?php
			}
		}
	}

	new Referral_System_Hpos();
}
