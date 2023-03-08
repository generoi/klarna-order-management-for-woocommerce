<?php
/**
 * Class for the KOM settings.
 *
 * @package WC_Klarna_Order_Management
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to add settings to the Klarna Add-ons page.
 */
class WC_Klarna_Order_Management_Settings {
	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_filter( 'wc_gateway_klarna_payments_settings', array( $this, 'extend_settings' ) );
		add_filter( 'kco_wc_gateway_settings', array( $this, 'extend_settings' ) );
	}

	public function extend_settings( $settings ) {
		$settings['kom'] = array(
			'title' => 'Klarna Order Management',
			'type'  => 'title',
		);

		$settings['kom_auto_capture'] = array(
			'title'   => 'On order completion',
			'type'    => 'checkbox',
			'default' => 'yes',
			'label'   => __( 'Activate Klarna order automatically when WooCommerce order is marked complete.', 'klarna-order-management-for-woocommerce' ),
		);

		$settings['kom_auto_cancel'] = array(
			'title'   => 'On order cancel',
			'type'    => 'checkbox',
			'default' => 'yes',
			'label'   => __( 'Cancel Klarna order automatically when WooCommerce order is marked canceled.', 'klarna-order-management-for-woocommerce' ),
		);

		$settings['kom_auto_update'] = array(
			'title'   => 'On order update',
			'type'    => 'checkbox',
			'default' => 'yes',
			'label'   => __( 'Update Klarna order automatically when WooCommerce order is updated.', 'klarna-order-management-for-woocommerce' ),
		);

		$settings['kom_auto_order_sync'] = array(
			'title'   => 'On order creation ( manual )',
			'type'    => 'checkbox',
			'default' => 'yes',
			'label'   => __( 'Gets the customer information from Klarna when creating a manual admin order and adding a Klarna order id as a transaction id.', 'klarna-order-management-for-woocommerce' ),
		);

		$settings['kom_force_full_capture'] = array(
			'title'   => 'Force capture full order',
			'type'    => 'checkbox',
			'default' => 'no',
			'label'   => __( 'Force capture full order. Useful if the Klarna order has been updated by an ERP system.', 'klarna-order-management-for-woocommerce' ),
		);

		$settings['kom_debug_log'] = array(
			'title'   => 'Debug log',
			'type'    => 'checkbox',
			'default' => 'yes',
			'label'   => __( 'Enable the debug log.', 'klarna-order-management-for-woocommerce' ),
		);

		return $settings;
	}

	public function get_settings( $order_id ) {
		if ( empty( $order_id ) ) {
			/* If "kom_settings" is not available, use default values. */
			return get_option(
				'kom_settings',
				array_map(
					function( $setting ) {
						return $setting['default'];
					},
					$this->extend_settings( array() )
				)
			);
		}

		$order          = wc_get_order( $order_id );
		$payment_method = $order->get_payment_method();

		if ( 'kco' === $payment_method ) {
			return get_option( 'kco_wc_gateway_settings' );
		} elseif ( 'klarna_payments' === $payment_method ) {
			return get_option( 'woocommerce_klarna_payments_settings' );
		} else {
			return get_option( 'kom_settings' );
		}
	}
}
