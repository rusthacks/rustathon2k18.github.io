
<?php
/**
* The template for displaying product content in the single-product.php template
*
* Override this template by copying it to yourtheme/woocommerce/content-single-product.php
*
* @author 		WooThemes
* @package 		WooCommerce/Templates
* @version     	5.0.0
*/

	if ( ! defined( 'ABSPATH' ) ) {
		exit; # Exit if accessed directly
	}
?>

<?php
	do_action( 'woocommerce_before_single_product' );
	if ( post_password_required() ) {
		echo get_the_password_form();
		return;
	}
?>

<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="row">
		<div class="col-md-5">
			<div class="single-product-image">
				<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
			</div>
		</div>

		<div class="col-md-7">
			<div class="single-product-details">
				<?php do_action( 'woocommerce_single_product_summary' ); ?>
			</div>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
<?php do_action( 'woocommerce_after_single_product_summary' ); ?>