<?php
/**
 * Genoapay main class.
 *
 * Class Genoapay
 *
 * @package Genoapay
 */

if ( ! class_exists('LatitudePay') ) {

	/**
	 * Main LatitudePay Class
	 *
	 * @class LatitudePay
	 * @version 1.0
	 */
	class LatitudePay {

		/**
		 * Initiated class
		 *
		 * @var boolean
		 */
		private static $initiated = false;

		/**
		 * Include required files
		 */
		public static function init() {

			if ( ! self::$initiated ) {
				self::init_hooks();
			}
		}

		/**
		 * Initializes WordPress hooks
		 */
		private static function init_hooks() {
			self::$initiated = true;

			add_action( 'woocommerce_single_product_summary', array( 'LatitudePay', 'woocommerce_template_genoapay_details' ), 11 );
			add_action( 'wp_enqueue_scripts', array('LatitudePay', 'genoapay_scripts' ) );
		}

		/**
		 * Genoapay payment details on single product summary
		 */
		public static function woocommerce_template_genoapay_details() {
			global $product;
			$genoapay_gateway = new WooCommerce_Gateway_Genoapay();
			if ( $genoapay_gateway->validate_currency() ) :
			?>
				<div class="genoapay-product-payment-details">
					<div class="genoapay-message">Or 10 Interest free payments from <b>$<?php echo number_format( (float) self::round_up( $product->get_price() / 10, 2), 2, '.', ''); ?></b>

                        <?php if ($product->get_price() < $genoapay_gateway->getMinimumAmount()) { ?>
							on orders over $<?php echo number_format($genoapay_gateway->getMinimumAmount()); ?>
                        <?php } ?>

						with <img src="<?php echo GENOAPAY_PLUGIN_URL . 'assets/images/latitudepay_100.png';?>" alt="LatitudePay logo" itemprop="logo">
						<a href="https://latitudepay.com?src=woo" target="_blank"><i>Learn more</i></a>
					</div>
				</div>
			<?php
			endif;
		}

		/**
		 * Always round up to 2 decimal places
		 * @param  Integer $value    
		 * @param  Integer $precision
		 * @return string
		 */
		public static function round_up ( $value, $precision ) { 
		    $pow = pow ( 10, $precision ); 
		    return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow; 
		}

		/**
		 * Enqueue Scripts
		 */
		public static function genoapay_scripts() {
			wp_enqueue_style( 'genoapay-style', GENOAPAY_PLUGIN_URL . 'assets/css/latitudepay.css', false, GENOAPAY_VERSION );
		}

	}
}// End if().
