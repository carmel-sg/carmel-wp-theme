/**
 * Trigger AJAX request to save state when the WooCommerce notice is dismissed.
 *
 * @version 2.3.0
 *
 * @package Carmel
 * @license GPL-2.0-or-later
 */

jQuery( document ).on(
	'click', '.carmel-woocommerce-notice .notice-dismiss', function() {

		jQuery.ajax(
			{
				url: ajaxurl,
				data: {
					action: 'carmel_dismiss_woocommerce_notice'
				}
			}
		);

	}
);
