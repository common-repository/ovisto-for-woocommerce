<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $order ) : ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p class="woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

		<p class="woocommerce-thankyou-order-failed-actions">
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My Account', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>

		<p class="woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); ?></p>

        <?php
		/** @var OvistoWoo $ovistoWoo */
		$ovistoWoo = $GLOBALS['ovisto_woo'];

		/** @var OvistoSettings $ovistoSettings */
		$ovistoSettings = $ovistoWoo->get_settings();

        //OVISTO BANNER CODE BEGIN
        if ($ovistoSettings->getIsActive()) :

            $user_id = get_current_user_id();

            $your_country = $ovistoSettings->getCountry(); ?>
            <h3 style='<?php echo $ovistoSettings->getBannerHeadingCSS();?>'><?php echo $ovistoSettings->getBannerHeading();?></h3>
            <div id="ovistoBanner" style='<?php echo $ovistoSettings->getBannerCSS();?>'>
                <script type="text/javascript">
                    var _ovtp = _ovtp || [];
                    _ovtp.push(['ovt_partner_id', '<?php echo $ovistoSettings->getPartnerID(); ?>']);
                    _ovtp.push(['cust_salutation', '']);
                    _ovtp.push(['cust_firstname', '<?php echo $order->billing_first_name; ?>']);
                    _ovtp.push(['cust_lastname', '<?php echo $order->billing_last_name; ?>']);
                    _ovtp.push(['cust_email', '<?php echo $order->billing_email; ?>']);
                    _ovtp.push(['order_datetime', '<?php echo $order->order_date; ?>']);
                    _ovtp.push(['order_id', '<?php echo $order->id; ?>']);
                    _ovtp.push(['order_amount', '<?php echo $order->get_total(); ?>']);
                    _ovtp.push(['order_curr', '<?php echo $order->order_currency; ?>']);
                    _ovtp.push(['order_coupon', '<?php echo implode(',', $order->get_used_coupons()); ?>']);
                    _ovtp.push(['payment_method', '<?php echo $order->payment_method; ?>']);
                    _ovtp.push(['birthday', '']);
                    _ovtp.push(['birthday_year', '']);
                    _ovtp.push(['phone_number', '<?php echo $order->billing_phone; ?>']);
                    _ovtp.push(['fax_number', '']);
                    _ovtp.push(['mobile_number', '']);
                    _ovtp.push(['street', '<?php echo $order->billing_address_1; ?>']);
                    _ovtp.push(['cust_city', '<?php echo $order->billing_city; ?>']);
                    _ovtp.push(['cust_zip', '<?php echo $order->billing_postcode; ?>']);
                    _ovtp.push(['house_no', '']);
                    _ovtp.push(['county', '<?php echo $order->billing_city; ?>']);
                    _ovtp.push(['country', '<?php echo $order->billing_country; ?>']);
                    _ovtp.push(['customer_status', '<?php echo $ovistoSettings->getUserData($user_id); ?>']);
                    _ovtp.push(['banner_element', '<?php echo $ovistoSettings->getTargetDiv(); ?>']);
                    _ovtp.push(['banner_hide', '<?php echo $ovistoSettings->isBannerHidden(); ?>']);

                    (function() {
                        var ovt = document.createElement('script'); ovt.type = 'text/javascript'; ovt.async = true;
                        <?php if($your_country == 'de') :?>
                            ovt.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'partner.ovisto.com.au/js/client.js';
                        <?php elseif($your_country == 'au'):?>
                            ovt.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'partner.ovisto.com.au/js/client.js';
                        <?php endif;?>
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ovt, s);
                    })();
                </script>
            </div>
            <br>
            <hr>
        <?php endif;
			//OVISTO BANNER CODE END ?>

		<ul class="woocommerce-thankyou-order-details order_details">
			<li class="order">
				<?php _e( 'Order Number:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_order_number(); ?></strong>
			</li>
			<li class="date">
				<?php _e( 'Date:', 'woocommerce' ); ?>
				<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
			</li>
			<li class="total">
				<?php _e( 'Total:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_formatted_order_total(); ?></strong>
			</li>
			<?php if ( $order->payment_method_title ) : ?>
			<li class="method">
				<?php _e( 'Payment Method:', 'woocommerce' ); ?>
				<strong><?php echo $order->payment_method_title; ?></strong>
			</li>
			<?php endif; ?>
		</ul>
		<div class="clear"></div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
	<?php do_action( 'woocommerce_thankyou', $order->id ); ?>

<?php else : ?>

	<p class="woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

<?php endif; ?>
