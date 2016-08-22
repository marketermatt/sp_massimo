<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );
// enqueue accordion
wp_enqueue_script( 'jquery-ui-accordion' );

if ( ! empty( $tabs ) ) : ?>

	<div class="woocommerce-accordion">

		<?php foreach ( $tabs as $key => $tab ) : ?>

			<h3 class="accordion-title clearfix"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?><i class="icon-arrow-down"></i><i class="icon-arrow-up"></i></h3>

			<div class="accordion-content entry-content" id="tab-<?php echo $key ?>">
				<?php call_user_func( $tab['callback'], $key, $tab ) ?>
			</div><!--close .accordion-content-->

		<?php endforeach; ?>

	</div><!--close .woocommerce-accordion-->

<?php endif; ?>