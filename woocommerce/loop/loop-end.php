<?php
/**
 * Product Loop End
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     5.0.0
 */
?>
</div>
<?php
global $woocommerce_loop, $themeum_increment;

if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 3 );

if(isset( $themeum_increment )){
	if( $themeum_increment != 1 ){
		echo '</div>'; //row
	}
}