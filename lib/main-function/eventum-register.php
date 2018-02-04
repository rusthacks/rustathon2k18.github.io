<?php 
/*-------------------------------------------*
 *      Themeum Widget Registration
 *------------------------------------------*/

if(!function_exists('thmtheme_widdget_init')):

    function thmtheme_widdget_init()
    {

        register_sidebar(array( 'name'          => esc_html__( 'Sidebar', 'eventum' ),
                                'id'            => 'sidebar',
                                'description'   => esc_html__( 'Widgets in this area will be shown on Sidebar.', 'eventum' ),
                                'before_title'  => '<div class="themeum-title"><h3 class="widget_title">',
                                'after_title'   => '</h3></div>',
                                'before_widget' => '<div id="%1$s" class="widget %2$s" >',
                                'after_widget'  => '</div>'
                    )
        );
        global $woocommerce;
        if($woocommerce) {
            register_sidebar(array(
                'name'          => __( 'Shop', 'eventum' ),
                'id'            => 'shop',
                'description'   => __( 'Widgets in this area will be shown on Shop Sidebar.', 'eventum' ),
                'before_title'  => '<div class="themeum-title"><h3 class="widget_title">',
                'after_title'   => '</h3></div>',
                'before_widget' => '<div id="%1$s" class="widget %2$s" >',
                'after_widget'  => '</div>'
                )
            );
        }  

    }
    
    add_action('widgets_init','thmtheme_widdget_init');

endif;




/*-------------------------------------------*
 *      Themeum Style
 *------------------------------------------*/

if(!function_exists('themeum_style')):

    function themeum_style(){
        global $themeum_options;

        wp_enqueue_style('thm-style',get_stylesheet_uri());
        wp_enqueue_script('bootstrap',THMJS.'bootstrap.min.js',array(),false,true);
        wp_enqueue_script('jquery.countdown',THMJS.'jquery.countdown.min.js',array(),false,true);
        wp_enqueue_script('jquery.magnific-popup.min',THMJS.'jquery.magnific-popup.min.js',array(),false,true);
        wp_enqueue_script('jquery.masonry.pkgd.min',THMJS.'masonry.pkgd.min.js',array(),false,true);
        wp_enqueue_script('loopcounter',THMJS.'loopcounter.js',array(),false,true);
        if( isset($themeum_options['onepage-eg']) && $themeum_options['onepage-eg'] ) {
            wp_enqueue_script('jquery.nav',THMJS.'jquery.nav.js',array(),false,true);
            wp_enqueue_script('main-onepage',THMJS.'main-onepage.js',array(),false,true);
        }
        // Google Map API key
        if( $themeum_options['map-api-key'] ){
          wp_enqueue_script( 'googlemap', 'https://maps.google.com/maps/api/js?key='.$themeum_options["map-api-key"], array(), '',false,true );
        }
        // end
        wp_enqueue_script('google-map',THMJS.'gmaps.js',array(),false,true);
        wp_enqueue_script('queryloader2',THMJS.'queryloader2.min.js',array(),false,true);
    
        wp_enqueue_media();
       

        if( isset($themeum_options['custom-preset-en']) && $themeum_options['custom-preset-en']==0 ) {
            wp_enqueue_style( 'themeum-preset', get_template_directory_uri(). '/css/presets/preset' . $themeum_options['preset'] . '.css', array(),false,'all' );       
        }else {
            wp_enqueue_style('quick-preset',get_template_directory_uri().'/quick-preset.php',array(),false,'all');
        }
        wp_enqueue_style('quick-preset',get_template_directory_uri().'/quick-preset.php',array(),false,'all');
        wp_enqueue_style('quick-style',get_template_directory_uri().'/quick-style.php',array(),false,'all');

        wp_enqueue_script('main',THMJS.'main.js',array(),false,true);

    }

    add_action('wp_enqueue_scripts','themeum_style');

endif;




if(!function_exists('themeum_admin_style')):

    function themeum_admin_style(){
        wp_register_script('thmpostmeta', get_template_directory_uri() .'/js/admin/post-meta.js');
        wp_enqueue_script('thmpostmeta');
    }

    add_action('admin_enqueue_scripts','themeum_admin_style');

endif;


/*-------------------------------------------------------
*           Include the TGM Plugin Activation class
*-------------------------------------------------------*/

require_once( get_template_directory()  . '/lib/class-tgm-plugin-activation.php');

add_action( 'tgmpa_register', 'themeum_plugins_include');

if(!function_exists('themeum_plugins_include')):

    function themeum_plugins_include()
    {
        $plugins = array(
                array(
                    'name'                  => 'Slider Revolution',
                    'slug'                  => 'revslider',
                    'source'                => 'http://demo.themeum.com/wordpress/plugins/revslider.zip',
                    'required'              => true,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => '',
                ),          
                array(
                    'name'                  => 'WPBakery Visual Composer',
                    'slug'                  => 'js_composer',
                    'source'                => 'http://demo.themeum.com/wordpress/plugins/js_composer.zip',
                    'required'              => false,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => '',
                ),                               
                array(
                    'name'                  => 'Group Meta Box',
                    'slug'                  => 'meta-box-group',
                    'source'                => get_stylesheet_directory() . '/lib/plugins/meta-box-group.zip',
                    'required'              => false,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => '',
                ),
                array(
                    'name'                  => 'Themeum Eventum',
                    'slug'                  => 'themeum-eventum',
                    'source'                => get_stylesheet_directory() . '/lib/plugins/themeum-eventum.zip',
                    'required'              => false,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => '',
                ), 
                array(
                    'name'                  => 'Woocoomerce',
                    'slug'                  => 'woocommerce',
                    'required'              => false,
                ),                               
                array(
                    'name'                  => 'MailChimp for WordPress',
                    'slug'                  => 'mailchimp-for-wp',
                    'required'              => false,
                ),                                 
                array(
                    'name'                  => 'Widget Importer Exporter',
                    'slug'                  => 'widget-importer-exporter',
                    'required'              => false,
                ),
                array(
                    'name'                  => 'Contact Form 7',
                    'slug'                  => 'contact-form-7', 
                    'required'              => false,
                ),

            );
    $config = array(
        'id'           => 'eventum',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.

    );

    tgmpa( $plugins, $config );

    }

endif;