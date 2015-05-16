<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * actual version 1.6.4
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     5.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product->is_visible() )
	return;

// get quickview setting
$show_quickview = sp_get_option( 'quickview' );

if ( $show_quickview === 'on' )
	$quickview_class = 'quickview';
else
	$quickview_class = '';

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'last';

// add column class
switch( $woocommerce_loop['columns'] ) {
	case 1:
		$classes[] = sp_column_css( '', '', '', '12' );
		break;

	case 2:
		$classes[] = sp_column_css( '', '', '', '6' );
		break;
		
	case 3:
		$classes[] = sp_column_css( '', '', '', '4' );
		break;
		
	case 4:
		$classes[] = sp_column_css( '', '', '', '3' );
		break;
		
	case 5:
		$classes[] = sp_column_css( '', '', '', '2' );
		break;
		
	case 6:
		$classes[] = sp_column_css( '', '', '', '2' );
		break;
		
	default:
		$classes[] = sp_column_css( '', '', '', '4' );
		break;

}

// get image width
$image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $product->id ), 'shop_catalog' );

if ( $image_url ) {
	$image_width = $image_url[1];
} else {
	// get saved option
	$image_width = get_option( 'shop_catalog_image_size' );
	$image_width = $image_width['width']; 
}

// removes the default image function and load our own
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

// product views
$view_type = sp_get_product_view_type();

if ( $view_type === 'list-view' && is_woocommerce() ) {
	$classes[] = 'clearfix';
	$image_wrap_column = apply_filters( 'sp_product_view_list_image_wrap_column', sp_column_css( '', '' ,'', '4' ) );
	$content_wrap = apply_filters( 'sp_product_view_list_content_wrap', sp_column_css( '', '', '', '8' ) );
} else {
	$image_wrap_column = '';
	$content_wrap = '';
}
?>
<li <?php post_class( $classes ); ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
	<div class="image-wrap-column <?php echo esc_attr( $image_wrap_column ); ?>">
	<div class="image-wrap <?php echo esc_attr( $quickview_class ); ?>" style="max-width:<?php echo esc_attr( $image_width ); ?>px;">
	<a href="<?php the_permalink(); ?>" class="product-image-link">

		<?php
			// get user set image width/height
			$catalog_image_size = get_option( 'shop_catalog_image_size' );

			// get alternate product image settings
			$hover_status = get_post_meta( $product->id, '_sp_alternate_product_image_on_hover_status', true );

			// get the alternate image
			$show_alt_image = false;
			if ( $hover_status === 'on' ) {
				$alt_image_id = absint( get_post_meta( $product->id, '_sp_alternate_product_image_id', true ) );
				$alt_image = sp_get_image( $alt_image_id, $catalog_image_size['width'], $catalog_image_size['height'], $catalog_image_size['crop'] );
				$show_alt_image = true;
			}

			$image = sp_get_image( get_post_thumbnail_id( $product->id ), $catalog_image_size['width'], $catalog_image_size['height'], $catalog_image_size['crop'] );

			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );

			echo '<img src="' . esc_url( $image['url'] ) . '" alt="' . esc_attr( $image['alt'] ) . '" itemprop="image" class="attachment-shop_catalog wp-post-image lazyload" data-original="' . esc_url( $image['url'] ) . '" />' . PHP_EOL;

			if ( $product->is_on_sale() )
				echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . __( 'Sale!', 'sp-theme' ) . '</span>', $post, $product );
			
			if ( $show_alt_image )
				echo '<img src="' . esc_attr( $alt_image['url'] ) . '" alt="' . esc_attr( $alt_image['alt'] ) . '" itemprop="image" class="alt-product-image" />' . PHP_EOL;
		?>

		<?php
		if ( $show_quickview === 'on' )
			echo '<span class="quickview-button"><i class="icon-list" aria-hidden="true"></i> ' . __( 'Quickview', 'sp-theme' ) . '</span>' . PHP_EOL;
		?>
		<span class="price-wrap">
		<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
		?>
		</span><!--close .price-wrap-->

		<?php echo sp_woo_product_meta_action_buttons_html( $product->id ); ?>
	</a>
	
	</div><!--close .image-wrap-->
	</div><!--close .image-wrap-column-->
	
	<div class="content-wrap <?php echo esc_attr( $content_wrap ); ?>">
		<h3 class="product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		
		<?php 
		ob_start();
		woocommerce_template_loop_add_to_cart(); 
		$addtocart = ob_get_clean();
		?>
				
		<?php if ( sp_get_option( 'show_product_list_add_to_cart', 'is', 'on' ) ) { ?>
			<div class="grid-addtocart"><?php echo $addtocart; ?></div><!--close .grid-addtocart-->
		<?php } ?>

		<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
		<?php
		// check product type
		$product_type = sp_has_product_add_ons( $product->id ) ? 'addons' : $product->product_type;
		?>
		<input type="hidden" name="product_type" value="<?php echo esc_attr( $product_type ); ?>" />
		<input type="hidden" name="product_id" value="<?php echo esc_attr( $product->id ); ?>" />

		<div class="list-view-content clearfix">
			<?php woocommerce_template_single_excerpt(); ?>
			<?php echo sp_woo_product_meta_action_buttons_html( $product->id ); ?>
			
			<?php if ( sp_get_option( 'show_product_list_add_to_cart', 'is', 'on' ) ) { ?>
				<div class="add-container clearfix">
				<?php echo $addtocart; ?>
			</div><!--close .add-container-->
			<?php } ?>
		</div><!--close .list-view-content-->
	</div><!--close .content-wrap-->
</li>