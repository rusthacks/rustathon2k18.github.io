<?php
/**
 * The template for displaying product content within loops.
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     5.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; # Exit if accessed directly
}

global $product;

# Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

?>

<li <?php post_class(); ?>>
	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
	<div class="product-thumbnail-outer">
		<a href="<?php the_permalink(); ?>">
			<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
			<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
		</a>
	</div>

	<div class="product-thumbnail-outer-inner">
		<a href="<?php the_permalink(); ?>"><?php do_action( 'woocommerce_shop_loop_item_title' ); ?></a>
		<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
	</div>
</li>