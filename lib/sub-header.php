<?php 

global $themeum_options;

$output = $subheader_beardcam = ''; 
$padding_top 	= $themeum_options['subheader-padding-top'];
$padding_bottom = $themeum_options['subheader-padding-bottom'];

$banner_image 	= $themeum_options['subheader_banner_img']['url'];

if (!empty($padding_top) || !empty($padding_bottom)) {
	$output .= 'style="padding-top: '.$padding_top.'px;padding-bottom: '.$padding_bottom.'px;"'; 				
}
?>

<?php if (!is_front_page()) { ?>

	<?php if ($themeum_options['subheader-section']): ?>
		<div class="sub-title" <?php echo $output;?> >
		    <div class="container">
		        <div class="sub-title-inner">
		            <h2>
		            	<?php
			            	if( is_home() && get_option( 'page_for_posts' ) )
			            	{
			            		echo get_the_title( get_option( 'page_for_posts' ) );
			            	}
			            	elseif (is_archive()) {
			            		global $wp_query;
			            		echo $wp_query->queried_object->name;
			            	}
			            	else
			            	{
			            		the_title();
			            	}
		            	?>
		            </h2>
		            <?php if ($themeum_options['subheader_beardcam']): ?>
		            <?php echo themeum_breadcrumbs(); ?>	
		            <?php endif ?>
		        </div>
		    </div>
		</div>
	<?php endif ?>

<?php } ?>

