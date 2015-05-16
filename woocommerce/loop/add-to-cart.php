<?php
/**
 * Loop Add to Cart
 * actual version 2.1.0
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     5.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;

echo apply_filters( 'woocommerce_loop_add_to_cart_link',
	sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button %s product_type_%s"><i class="icon-plus" aria-hidden="true"></i> %s</a>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( $product->id ),
		esc_attr( $product->get_sku() ),
		$product->is_purchasable() && ! sp_has_product_add_ons( $product->id ) && $product->product_type !== 'variable' ? 'add_to_cart_button' : '',
		esc_attr( $product->product_type ),
		$product->add_to_cart_text()
	),
$product );
