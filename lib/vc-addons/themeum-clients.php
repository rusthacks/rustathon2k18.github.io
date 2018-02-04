<?php
add_shortcode( 'themeum_client_testimonial', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'client_image'					=> '',
		'client_text'					=> '',
		'client_name'					=> '',
		'class'						=> '',
		'color'						=> '',
		'bg'						=> '',
		'name_color'						=> '',
		), $atts));
    
    
        if($color != ''){
            $color = 'color:'.$color.';';
        }
        if($bg != ''){
            $bg = 'background-color:'.$bg.';';
        }
        if($name_color != ''){
            $name_color = 'color:'.$name_color.';';
        }

$src_image   = wp_get_attachment_image_src($client_image, 'full');


    $output = '<div class="themeum-clients '.$class.'" style="'.$bg.'">';
    	
    	$output .= '<div class="client-image">';
    		$output .= '<img src="'.$src_image[0].'" />';
    	$output .= '</div>';

    	$output .= '<div class="client-comments" style="'.$color.'">';
    		$output .= $client_text;
    	$output .= '</div>';

    	$output .= '<div class="client-name"  style="'.$name_color.'">';
    		$output .= $client_name;
    	$output .= '</div>';

	$output .= '</div>';


	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
vc_map(array(
	"name" => esc_html__("Client Testimonial", 'eventum'),
	"base" => "themeum_client_testimonial",
	'icon' => 'icon-thm-client-testimonial',
	"class" => "",
	"description" => esc_html__("Client Testimonial", 'eventum'),
	"category" => esc_html__('Themeum', 'eventum'),
	"params" => array(


		array(
			"type" => "attach_image",
			"heading" => esc_html__("Client Image", 'eventum'),
			"param_name" => "client_image",
			"value" => "",
			),	

		
		array(
			"type" => "textfield",
			"heading" => esc_html__("Client Name", 'eventum'),
			"param_name" => "client_name",
			"value" => "",
			),

		array(
			"type" => "textarea",
			"heading" => esc_html__("Client Comments", 'eventum'),
			"param_name" => "client_text",
			"value" => "",
			),

		array(
			"type" => "colorpicker",
			"heading" => esc_html__("Text Color", 'eventum'),
			"param_name" => "color",
			"value" => "",
			),
		array(
			"type" => "colorpicker",
			"heading" => esc_html__("Client Name Color", 'eventum'),
			"param_name" => "name_color",
			"value" => "",
			),
		array(
			"type" => "colorpicker",
			"heading" => esc_html__("Background", 'eventum'),
			"param_name" => "bg",
			"value" => "",
			),


		array(
			"type" => "textfield",
			"heading" => esc_html__("Add Extra CSS Class", 'eventum'),
			"param_name" => "class",
			"value" => "",
			),


		)
	));
}