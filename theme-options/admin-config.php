<?php

/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */

if (!class_exists('Redux_Framework_sample_config')) {

    class Redux_Framework_sample_config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if (  true == Redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            //add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 3);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css, $changed_values) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r($changed_values); // Values that have changed since the last save
            echo "</pre>";
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            /*
              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'redux-framework-demo'),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path   = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url    = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns        = array();

            if (is_dir($sample_patterns_path)) :

                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();

                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode('.', $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[]  = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;

            ob_start();

            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'redux-framework-demo'), $this->theme->display('Name'));
            
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo esc_url(wp_customize_url()); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview','eventum'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_html_e('Current theme preview','eventum'); ?>" />
                <?php endif; ?>

                <h4><?php echo esc_html($this->theme->display('Name')); ?></h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'redux-framework-demo'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'redux-framework-demo'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'redux-framework-demo') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>', __('http://codex.wordpress.org/Child_Themes', 'redux-framework-demo'), $this->theme->parent()->display('Name'));
            }
            ?>

                </div>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            // ACTUAL DECLARATION OF SECTIONS





            /**********************************
            ********* General Setting ***********
            ***********************************/
            $this->sections[] = array(
                'title'     => __('General Setting', 'Home Setting'),
                'icon'      => 'el-icon-bookmark',
                'icon_class' => 'el-icon-large',
                'fields'    => array(


                    array(
                        'id'        => 'preloader_en',
                        'type'      => 'switch',
                        'title'     => __('Preloader Enable', 'eventum'),
                        'subtitle' => __('Enable or disable Preloader', 'eventum'),
                        'default'   => true,
                    ), 

                    array(
                        'id'        => 'preloader_color',
                        'type'      => 'color',
                        'title'     => __('Preloader Color', 'eventum'),
                        'subtitle'  => __('Pick a color for the Preloader (default: #4bb463).', 'eventum'),
                        'default'   => '#4bb463',
                        'validate'  => 'color',
                        'transparent'   =>true,
                    ),
                    array(
                        'id'        => 'preloader_bg_color',
                        'type'      => 'color',
                        'title'     => __('Preloader Background Color', 'eventum'),
                        'subtitle'  => __('Pick a Background color for the Preloader (default: #fff).', 'eventum'),
                        'default'   => '#fff',
                        'validate'  => 'color',
                        'transparent'   =>true,
                    ),

                    array(
                        'id'        => 'onepage-eg',
                        'type'      => 'switch',
                        'title'     => __('Onepage', 'eventum'),
                        'subtitle' => __('Enable or disable Onepage', 'eventum'),
                        'default'   => false,
                    ),

                    array(
                        'id'        => 'header-fixed',
                        'type'      => 'switch',
                        'title'     => __('Sticky Header', 'eventum'),
                        'subtitle' => __('Enable or disable sicky Header', 'eventum'),
                        'default'   => false,
                    ),

                    array(
                        'id'        => 'cart_open',
                        'type'      => 'switch',
                        'title'     => __('Cart Icon Enable', 'eventum'),
                        'subtitle' => __('Enable or disable Cart Icon', 'eventum'),
                        'default'   => true,
                    ),

                                                                               
                    array(
                        'id'        => 'map-api-key',
                        'type'      => 'text',
                        'title'     => __('Google Map API Key', 'eventum'),
                        'subtitle' => __('Put here Google Map API Key', 'eventum'),
                        'default'   => '',
                    ), 

                    array(
                        'id'        => 'custom-css',
                        'type'      => 'ace_editor',
                        'mode'      => 'css',
                        'title'     => __('Custom CSS', 'eventum'),
                        'subtitle' => __('Add some custom CSS', 'eventum'),
                        'default'   => '',
                    ),

                    array(
                        'id'        => 'custom_js',
                        'type'      => 'ace_editor',
                        'mode'      => 'javascript',
                        'title'     => __('Custom JS', 'eventum'),
                        'subtitle' => __('Add some custom CSS', 'eventum'),
                        'default'   => '',
                    ),

                    array(
                        'id'        => 'google-analytics',
                        'type'      => 'textarea',
                        'title'     => __('Google Analytics Code', 'eventum'),
                        'subtitle'  => __('Paste Your Google Analytics Code Here. This code will added to the footer', 'eventum'),                                            
                    ), 

                )
            );

            /**********************************
            ********* Header Setting ***********
            ***********************************/
            $this->sections[] = array(
                'title'     => __('Header', 'Home Setting'),
                'icon'      => 'el-icon-bookmark',
                'icon_class' => 'el-icon-large',
                'fields'    => array(  

                    array(
                        'id'        => 'header-padding-top',
                        'type'      => 'text',
                        'title'     => __('Header Top Padding', 'eventum'),
                        'subtitle' => __('Enter custom header top padding', 'eventum'),
                        'default'   => '0',

                    ),  

                    array(
                        'id'        => 'header-padding-bottom',
                        'type'      => 'text',
                        'title'     => __('Header Bottom Padding', 'eventum'),
                        'subtitle' => __('Enter custom header bottom padding', 'eventum'),
                        'default'   => '0',
                    ),     

                    array(
                        'id'        => 'header-height',
                        'type'      => 'text',
                        'title'     => __('Header Height ex. 60', 'eventum'),
                        'subtitle' => __('Enter custom header Height', 'eventum'),
                        'default'   => '60',
                    ), 

                    array(
                        'id'        => 'header-margin-bottom',
                        'type'      => 'text',
                        'title'     => __('Header Margin Bottom', 'eventum'),
                        'subtitle' => __('Enter custom header bottom margin', 'eventum'),
                        'default'   => '0',
                    ),                                                          

                )
            );



            /**********************************
            ********* Menu Setting ************
            ***********************************/
            $this->sections[] = array(
                'title'     => esc_html__('Menu Settings', 'Home Setting'),
                'icon'      => 'el-align-justify',
                'icon_class' => 'el-icon-large',
                'fields'    => array(

                    array(
                        'id'        => 'header-bg',
                        'type'      => 'color',
                        'title'     => __('Menu Background Color', 'eventum'),
                        'subtitle'  => __('Pick a background color for the Menu (default: #29333f).', 'eventum'),
                        'default'   => '#29333f',
                        'validate'  => 'color',
                        'transparent'   =>false,
                    ),

                    array(
                        'id'        => 'header-font-color',
                        'type'      => 'color',
                        'title'     => __('Menu Font Color', 'eventum'),
                        'subtitle'  => __('Pick a Font color for the Menu (default: #fff).', 'eventum'),
                        'default'   => '#fff',
                        'validate'  => 'color',
                        'transparent'   =>false,
                    ),

                    array(
                        'id'        => 'menu-color',
                        'type'      => 'color',
                        'title'     => __('Menu Hover Color', 'eventum'),
                        'subtitle'  => __('Pick a Menu Hover color (default: #4bb463).', 'eventum'),
                        'default'   => '#4bb463',
                        'validate'  => 'color',
                        'transparent'   =>false,
                        'required'  => array('custom-preset-en', "=", 1),
                    ),

                    array(
                        'id'        => 'ticket-menu-bg-color',
                        'type'      => 'color',
                        'title'     => __('Ticket Background Color', 'eventum'),
                        'subtitle'  => __('Pick a Ticket background color for the menu (default: #FF8A00).', 'eventum'),
                        'default'   => '#FF8A00',
                        'validate'  => 'color',
                        'transparent'   =>false,
                    ),

                    array(
                        'id'        => 'ticket-menu-bg-hover-color',
                        'type'      => 'color',
                        'title'     => __('Ticket Background Hover Color', 'eventum'),
                        'subtitle'  => __('Pick a Ticket background Hover color for the menu (default: #D67400).', 'eventum'),
                        'default'   => '#D67400',
                        'validate'  => 'color',
                        'transparent'   =>false,
                    ),

                    # sub menu
                    array(
                        'id'        => 'submenu-bg',
                        'type'      => 'color',
                        'title'     => __('Sub Menu Background Color', 'eventum'),
                        'subtitle'  => __('Pick a background color for the Subenu (default: #29333f).', 'eventum'),
                        'default'   => '#29333f',
                        'validate'  => 'color',
                        'transparent'   =>false,
                    ),
                    array(
                        'id'        => 'submenu-text-color',
                        'type'      => 'color',
                        'title'     => __('Sub Menu Text Color', 'eventum'),
                        'subtitle'  => __('Pick a Text color for the Submenu (default: #fff).', 'eventum'),
                        'default'   => '#fff',
                        'validate'  => 'color',
                        'transparent'   =>false,
                    ),
                    array(
                        'id'        => 'submenu-hover-bg',
                        'type'      => 'color',
                        'title'     => __('Sub Menu Background Hover Color', 'eventum'),
                        'subtitle'  => __('Pick a background Hover color for the Submenu (default: #4bb463).', 'eventum'),
                        'default'   => '#4bb463',
                        'validate'  => 'color',
                        'transparent'   =>false,
                    ),
                    array(
                        'id'        => 'submenu-text-hover-color',
                        'type'      => 'color',
                        'title'     => __('Sub Menu Text Hover Color', 'eventum'),
                        'subtitle'  => __('Pick a Text Hover color for the Submenu (default: #fff).', 'eventum'),
                        'default'   => '#fff',
                        'validate'  => 'color',
                        'transparent'   =>false,
                    ),






                                                                                                                                                                        
                )
            );
               
                

            /**********************************
            **** Sub Header Color  *****
            ***********************************/
            
            $this->sections[] = array(
                'title'     => __('Sub Header', 'eventum'),
                'icon'      => 'sub-header-icon',
                'icon_class' => 'el-icon-compass',
                'fields'    => array(

                    array(
                        'id'        => 'subheader-section',
                        'type'      => 'switch',
                        'title'     => __('Enable Subheader Section', 'eventum'),
                        'subtitle'  => __('Enable or disable Subheader', 'eventum'),
                        'default'   => true,
                    ),

                    array(
                        'id'        => 'subheader_beardcam',
                        'type'      => 'switch',
                        'title'     => __('Enable Beardcam', 'eventum'),
                        'subtitle'  => __('Enable or disable', 'eventum'),
                        'default'   => true,
                    ),

                    array(
                        'id'        =>'subheader_banner_img',
                        'url'       => false,
                        'type'      => 'media', 
                        'title'     => __('Banner Image', 'eventum'),
                        'default'   => array( 'url' => get_template_directory_uri() .'/images/banner-bg.png' ),
                        'subtitle'  => __('Upload Banner Image', 'eventum'),
                    ),


                    array(
                        'id'        => 'subheader-bg',
                        'type'      => 'background',
                        'output'    => array('.sub-title'),
                        'title'     => __('Body Background', 'eventum'),
                        'subtitle'  => __('You can set Background color or images or patterns for site body tag', 'eventum'),
                        'default'   => '#fff',
                        'transparent'   =>false,
                    ), 

                    array(
                        'id'        => 'subheader-padding-top',
                        'type'      => 'text',
                        'title'     => __('Subheader Padding Top', 'eventum'),
                        'subtitle'  => __('Enter Subheader Padding. (Padding Top)', 'eventum'),
                        'default'   => '100',
                    ), 
                    array(
                        'id'        => 'subheader-padding-bottom',
                        'type'      => 'text',
                        'title'     => __('Subheader Padding Bottom', 'eventum'),
                        'subtitle'  => __('Enter Subheader Padding. (Padding Bottom)', 'eventum'),
                        'default'   => '90',
                    ),

                    array(
                        'id'        => 'subheader-title-color',
                        'type'      => 'color',
                        'title'     => __('Sub Header Title Color', 'eventum'),
                        'subtitle'  => __('Pick a text color for the Sub header (default: #000).', 'eventum'),
                        'default'   => '#000',
                        'validate'  => 'color',
                        'transparent'   =>false,
                    ),

                    array(
                        'id'        => 'header-font-size',
                        'type'      => 'text',
                        'title'     => __('Sub Title Font Size ex. 48', 'eventum'),
                        'subtitle' => __('Enter custom subtitle font size', 'eventum'),
                        'default'   => '48',
                    ),  

                    #breadcrumb 

                    array(
                        'id'        => 'breadcrumb-text-color',
                        'type'      => 'color',
                        'title'     => __('Breadcrumb Text Color', 'eventum'),
                        'subtitle'  => __('Pick a text sizr for the breadcrumb (default: #000).', 'eventum'),
                        'default'   => '#000',
                        'validate'  => 'color',
                        'transparent'   =>false,
                    ),

                    array(
                        'id'        => 'breadcrumb-font-size',
                        'type'      => 'text',
                        'title'     => __('Breadcrumb Font Size ex. 16', 'eventum'),
                        'subtitle' => __('Enter custom breadcrumb font size', 'eventum'),
                        'default'   => '16',
                    ),

                    array(
                        'id'        => 'subheader-margin-bottom',
                        'type'      => 'text',
                        'title'     => __('Subheader Margin Bottom ex. 30', 'eventum'),
                        'subtitle' => __('Enter custom Subheader Margin Bottom', 'eventum'),
                        'default'   => '30',
                    ),

                ));
                        

            /**********************************
            ********* Logo & Favicon ***********
            ***********************************/

            $this->sections[] = array(
                'title'     => __('All Logo & favicon', 'eventum'),
                'icon'      => 'el-icon-leaf',
                'icon_class' => 'el-icon-large',
                'fields'    => array(

                    array( 
                        'id'        => 'favicon', 
                        'type'      => 'media',
                        'desc'      => 'upload favicon image',
                        'title'      => 'Favicon',
                        'subtitle' => __('Upload favicon image', 'eventum'),
                        'default' => array( 'url' => get_template_directory_uri() .'/images/favicon.ico' ), 
                    ),                                        

                    array(
                        'id'=>'logo',
                        'url'=> false,
                        'type' => 'media', 
                        'title' => __('Logo', 'eventum'),
                        'default' => array( 'url' => get_template_directory_uri() .'/images/logo.png' ),
                        'subtitle' => __('Upload your custom site logo.', 'eventum'),
                    ),

                    array(
                        'id'        => 'logo-width',
                        'type'      => 'text',
                        'title'     => esc_html__('Logo Widtht', 'eventum'),
                        'subtitle' => esc_html__('Logo width', 'eventum'),
                        'default'   => '134',
                    ), 

                    array(
                        'id'        => 'logo-height',
                        'type'      => 'text',
                        'title'     => esc_html__('Logo Height', 'eventum'),
                        'subtitle' => esc_html__('Logo height', 'eventum'),
                        'default'   => '35',
                    ),

                    array(
                        'id'=>'footer-logo',
                        'url'=> false,
                        'type' => 'media', 
                        'title' => __('Footer Logo', 'eventum'),
                        'default' => array( 'url' => get_template_directory_uri() .'/images/footer-logo.png' ),
                        'subtitle' => __('Upload your custom footer logo.', 'eventum'),
                    ),

                    array(
                        'id'        => 'logo-text-en',
                        'type'      => 'switch',
                        'title'     => __('Text Type Logo', 'eventum'),
                        'subtitle' => __('Enable or disable text type logo', 'eventum'),
                        'default'   => false,
                    ),

                    array(
                        'id'        => 'logo-text',
                        'type'      => 'text',
                        'title'     => __('Logo Text', 'eventum'),
                        'subtitle' => __('Use your Custom logo text Ex. Eventum', 'eventum'),
                        'default'   => 'Eventum',
                        'required'  => array('logo-text-en', "=", 1),

                    ), 

                    array( 
                        'id'        => 'errorpage', 
                        'type'      => 'media',
                        'desc'      => 'upload 404 Page Background',
                        'title'      => '404 Page Background',
                        'subtitle' => __('Upload 404 Page Background', 'eventum'),
                        'default' => array( 'url' => get_template_directory_uri() .'/images/404-bg.png' ), 
                    ),   

                    array( 
                        'id'        => 'comingsoon', 
                        'type'      => 'media',
                        'desc'      => 'Upload Coming Soon Page Background',
                        'title'      => 'Coming Soon Page Background',
                        'subtitle' => __('Upload Coming Soon Page Background', 'eventum'),
                        'default' => array( 'url' => get_template_directory_uri() .'/images/countdown-bg.jpg' ), 
                    ),                                                         

                )
            );


            /**********************************
            ********* Layout & Styling ***********
            ***********************************/

            $this->sections[] = array(
                'icon' => 'el-icon-brush',
                'icon_class' => 'el-icon-large',
                'title'     => __('Layout & Styling', 'eventum'),
                'fields'    => array(

                   array(
                        'id'       => 'boxfull-en',
                        'type'     => 'select',
                        'title'    => esc_html__('Select Layout', 'eventum'), 
                        'subtitle' => esc_html__('Select BoxWidth of FullWidth', 'eventum'),
                        // Must provide key => value pairs for select options
                        'options'  => array(
                            'boxwidth' => 'BoxWidth',
                            'fullwidth' => 'FullWidth'
                        ),
                        'default'  => 'fullwidth',
                    ), 

                    array(
                        'id'        => 'box-background',
                        'type'      => 'background',
                        'output'    => array('body'),
                        'title'     => __('Body Background', 'eventum'),
                        'subtitle'  => __('You can set Background color or images or patterns for site body tag', 'eventum'),
                        'default'   => '#fff',
                        'transparent'   =>false,
                    ), 


                    array(
                        'id'        => 'preset',
                        'type'      => 'image_select',
                        'compiler'  => true,
                        'title'     => __('Preset Layout', 'eventum'),
                        'subtitle'  => __('select any preset', 'eventum'),
                        'options'   => array(
                            '1' => array('alt' => 'Preset 1',       'img' => ReduxFramework::$_url . 'assets/img/presets/preset1.png'),
                            '2' => array('alt' => 'Preset 2',       'img' => ReduxFramework::$_url . 'assets/img/presets/preset2.png'),
                            '3' => array('alt' => 'Preset 3',       'img' => ReduxFramework::$_url . 'assets/img/presets/preset3.png'),
                            '4' => array('alt' => 'Preset 4',       'img' => ReduxFramework::$_url . 'assets/img/presets/preset4.png'),
                            ),
                        'default'   => '1'
                    ),  
                    

                    array(
                        'id'        => 'custom-preset-en',
                        'type'      => 'switch',
                        'title'     => __('Select Custom Color', 'eventum'),
                        'subtitle' => __('You can use unlimited color', 'eventum'),
                        'default'   => true,
                        
                    ),

                     array(
                        'id'        => 'link-color',
                        'type'      => 'color',
                        'title'     => __('Link Color', 'eventum'),
                        'subtitle'  => __('Pick a link color (default: #4bb463).', 'eventum'),
                        'default'   => '#4bb463',
                        'validate'  => 'color',
                        'transparent'   =>false,
                        'required'  => array('custom-preset-en', "=", 1),
                    ),

                     array(
                        'id'        => 'hover-color',
                        'type'      => 'color',
                        'title'     => __('Hover Color', 'eventum'),
                        'subtitle'  => __('Pick a hover color (default: #3c904f).', 'eventum'),
                        'default'   => '#3c904f',
                        'validate'  => 'color',
                        'transparent'   =>false,
                        'required'  => array('custom-preset-en', "=", 1),
                    ), 
                )
            );

            /**********************************
            ********* Typography ***********
            ***********************************/

            $this->sections[] = array(
                'icon'      => 'el-icon-font',
                'icon_class' => 'el-icon-large',                
                'title'     => __('Typography', 'eventum'),
                'fields'    => array(

                    array(
                        'id'            => 'body-font',
                        'type'          => 'typography',
                        'title'         => __('Body Font', 'eventum'),
                        'compiler'      => false,  // Use if you want to hook in your own CSS compiler
                        'google'        => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'   => false,    // Select a backup non-google font in addition to a google font
                        'font-style'    => true, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'       => true, // Only appears if google is true and subsets not set to false
                        //'font-size'     => ture,
                        // 'text-align'    => false,
                        'line-height'   => false,
                        'word-spacing'  => false,  // Defaults to false
                        'letter-spacing'=> false,  // Defaults to false
                        'color'         => true,
                        'preview'       => true, // Disable the previewer
                        'all_styles'    => true,    // Enable all Google Font style/weight variations to be added to the page
                        'output'        =>array('body'),
                        'units'         => 'px', // Defaults to px
                        'subtitle'      => __('Select your website Body Font', 'eventum'),
                        'default'       => array(
                            'color'         => '#333',
                            'font-weight'    => '300',
                            'font-family'   => 'Roboto',
                            'google'        => true,
                            'font-size'     => '16px'),
                    ), 

                    array(
                        'id'            => 'menu-font',
                        'type'          => 'typography',
                        'title'         => __('Menu Font', 'eventum'),
                        'compiler'      => false,  // Use if you want to hook in your own CSS compiler
                        'google'        => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'   => false,    // Select a backup non-google font in addition to a google font
                        'font-style'    => true, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'       => true, // Only appears if google is true and subsets not set to false
                        'font-size'     => true,
                        // 'text-align'    => false,
                        'line-height'   => false,
                        'word-spacing'  => false,  // Defaults to false
                        'letter-spacing'=> false,  // Defaults to false
                        'color'         => false,
                        'preview'       => true, // Disable the previewer
                        'all_styles'    => true,    // Enable all Google Font style/weight variations to be added to the page
                        'output'        =>array('#main-menu .nav>li>a, #main-menu ul.sub-menu li > a'),
                        'units'         => 'px', // Defaults to px
                        'subtitle'      => __('Select your website Menu Font', 'eventum'),
                        'default'       => array(
                            'font-weight'    => '500',
                            'font-family'   => 'Roboto',
                            'google'        => true,
                            'font-size'     => '14px'),
                    ),

                    array(
                        'id'            => 'headings-font_h1',
                        'type'          => 'typography',
                        'title'         => __('Headings Font h1', 'eventum'),
                        'compiler'      => false,  // Use if you want to hook in your own CSS compiler
                        'google'        => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'   => false,    // Select a backup non-google font in addition to a google font
                        'font-style'    => true, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'       => true, // Only appears if google is true and subsets not set to false
                        'font-size'     => true,
                        // 'text-align'    => false,
                        'line-height'   => false,
                        'word-spacing'  => false,  // Defaults to false
                        'letter-spacing'=> false,  // Defaults to false
                        'color'         => true,
                        'preview'       => true, // Disable the previewer
                        'all_styles'    => true,    // Enable all Google Font style/weight variations to be added to the page
                        'output'        =>array('h1'),
                        'units'         => 'px', // Defaults to px
                        'subtitle'      => __('Select your website Headings Font', 'eventum'),
                        'default'       => array(
                            'color'         => '#000',
                            'font-weight'    => '700',
                            'font-family'   => 'Roboto',
                            'google'        => true,
                            'font-size'     => '42px'),
                    ),                      

                    array(
                        'id'            => 'headings-font_h2',
                        'type'          => 'typography',
                        'title'         => __('Headings Font h2', 'eventum'),
                        'compiler'      => false,  // Use if you want to hook in your own CSS compiler
                        'google'        => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'   => false,    // Select a backup non-google font in addition to a google font
                        'font-style'    => true, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'       => true, // Only appears if google is true and subsets not set to false
                        'font-size'     => true,
                        // 'text-align'    => false,
                        'line-height'   => false,
                        'word-spacing'  => false,  // Defaults to false
                        'letter-spacing'=> false,  // Defaults to false
                        'color'         => true,
                        'preview'       => true, // Disable the previewer
                        'all_styles'    => true,    // Enable all Google Font style/weight variations to be added to the page
                        'output'        =>array('h2'),
                        'units'         => 'px', // Defaults to px
                        'subtitle'      => __('Select your website Headings Font', 'eventum'),
                        'default'       => array(
                            'color'         => '#000',
                            'font-weight'    => '700',
                            'font-family'   => 'Roboto',
                            'google'        => true,
                            'font-size'     => '36px'),
                    ),                      

                    array(
                        'id'            => 'headings-font_h3',
                        'type'          => 'typography',
                        'title'         => __('Headings Font h3', 'eventum'),
                        'compiler'      => false,  // Use if you want to hook in your own CSS compiler
                        'google'        => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'   => false,    // Select a backup non-google font in addition to a google font
                        'font-style'    => true, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'       => true, // Only appears if google is true and subsets not set to false
                        'font-size'     => true,
                        // 'text-align'    => false,
                        'line-height'   => false,
                        'word-spacing'  => false,  // Defaults to false
                        'letter-spacing'=> false,  // Defaults to false
                        'color'         => true,
                        'preview'       => true, // Disable the previewer
                        'all_styles'    => true,    // Enable all Google Font style/weight variations to be added to the page
                        'output'        =>array('h3'),
                        'units'         => 'px', // Defaults to px
                        'subtitle'      => __('Select your website Headings Font', 'eventum'),
                        'default'       => array(
                            'color'         => '#000',
                            'font-weight'    => '700',
                            'font-family'   => 'Roboto',
                            'google'        => true,
                            'font-size'     => '24px'),
                    ),                     

                    array(
                        'id'            => 'headings-font_h4',
                        'type'          => 'typography',
                        'title'         => __('Headings Font h4', 'eventum'),
                        'compiler'      => false,  // Use if you want to hook in your own CSS compiler
                        'google'        => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'   => false,    // Select a backup non-google font in addition to a google font
                        'font-style'    => true, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'       => true, // Only appears if google is true and subsets not set to false
                        'font-size'     => true,
                        // 'text-align'    => false,
                        'line-height'   => false,
                        'word-spacing'  => false,  // Defaults to false
                        'letter-spacing'=> false,  // Defaults to false
                        'color'         => true,
                        'preview'       => true, // Disable the previewer
                        'all_styles'    => true,    // Enable all Google Font style/weight variations to be added to the page
                        'output'        =>array('h4'),
                        'units'         => 'px', // Defaults to px
                        'subtitle'      => __('Select your website Headings Font', 'eventum'),
                        'default'       => array(
                            'color'         => '#000',
                            'font-weight'    => '700',
                            'font-family'   => 'Roboto',
                            'google'        => true,
                            'font-size'     => '20px'),
                    ),                      

                    array(
                        'id'            => 'headings-font_h5',
                        'type'          => 'typography',
                        'title'         => __('Headings Font h5', 'eventum'),
                        'compiler'      => false,  // Use if you want to hook in your own CSS compiler
                        'google'        => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'   => false,    // Select a backup non-google font in addition to a google font
                        'font-style'    => true, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'       => true, // Only appears if google is true and subsets not set to false
                        'font-size'     => true,
                        // 'text-align'    => false,
                        'line-height'   => false,
                        'word-spacing'  => false,  // Defaults to false
                        'letter-spacing'=> false,  // Defaults to false
                        'color'         => true,
                        'preview'       => true, // Disable the previewer
                        'all_styles'    => true,    // Enable all Google Font style/weight variations to be added to the page
                        'output'        =>array('h5'),
                        'units'         => 'px', // Defaults to px
                        'subtitle'      => __('Select your website Headings Font', 'eventum'),
                        'default'       => array(
                            'color'         => '#000',
                            'font-weight'    => '700',
                            'font-family'   => 'Roboto',
                            'google'        => true,
                            'font-size'     => '18px'),
                    ),    

                )
            );



            /**********************************
            ********* Coming Soon  ***********
            ***********************************/

            $this->sections[] = array(
                'icon'      => 'el-icon-time',
                'icon_class' => 'el-icon-large',                  
                'title'     => __('Coming Soon', 'eventum'),
                'fields'    => array(

                    array(
                        'id'        => 'comingsoon-en',
                        'type'      => 'switch',
                        'title'     => __('Enable Coming Soon', 'eventum'),
                        'subtitle'  => __('Enable or disable coming soon mode', 'eventum'),
                        'default'   => false,
                    ),

                    array(
                        'id'        => 'comingsoon-date',
                        'type'      => 'date',
                        'title'     => __('Coming Soon date', 'eventum'),
                        'subtitle' => __('Coming Soon Date', 'eventum'),
                        'default'   => __('08/30/2017', 'eventum')
                        
                    ),

                    array(
                        'id'        => 'comingsoon-title',
                        'type'      => 'text',
                        'title'     => __('Title', 'eventum'),
                        'subtitle' => __('Coming Soon Title', 'eventum'),
                        'default'   => __('A Minty Surprize is Coming Your Way!', 'eventum')
                    ),

                    array(
                        'id'        => 'comingsoon-message-desc',
                        'type'      => 'text',
                        'title'     => __('Description', 'eventum'),
                        'subtitle' => __('Coming Soon Description', 'eventum'),
                        'default'   => __('We’re working hard and our estimated time before launch:', 'eventum')
                    ),

        


                )
            );


            /**********************************
            ********* Blog  ***********
            ***********************************/

            $this->sections[] = array(
                'icon'      => 'el-icon-edit',
                'icon_class' => 'el-icon-large',                  
                'title'     => __('Blog', 'eventum'),
                'fields'    => array(

                    array(
                        'id'        => 'blog-social',
                        'type'      => 'switch',
                        'title'     => __('Blog Single Page Social Share', 'shapebootstrap'),
                        'subtitle'  => __('Enable or disable blog social share for single page', 'shapebootstrap'),
                        'default'   => true,
                    ),                     

                    array(
                        'id'        => 'blog-comment',
                        'type'      => 'switch',
                        'title'     => __('Post Comment', 'eventum'),
                        'subtitle'  => __('Enable or disable post comment', 'eventum'),
                        'default'   => true,
                    ),                 

                    array(
                        'id'        => 'blog-author',
                        'type'      => 'switch',
                        'title'     => __('Blog Author', 'eventum'),
                        'subtitle'  => __('Enable Blog Author ex. Admin', 'eventum'),
                        'default'   => true,
                    ),

                    array(
                        'id'        => 'blog-date',
                        'type'      => 'switch',
                        'title'     => __('Blog Date', 'eventum'),
                        'subtitle'  => __('Enable Blog Date ', 'eventum'),
                        'default'   => true,
                    ),

                    array(
                        'id'        => 'blog-category',
                        'type'      => 'switch',
                        'title'     => __('Blog Category', 'eventum'),
                        'subtitle'  => __('Enable or disable blog category', 'eventum'),
                        'default'   => true,
                    ), 


                    array(
                        'id'        => 'blog-tag',
                        'type'      => 'switch',
                        'title'     => __('Blog Tag', 'eventum'),
                        'subtitle'  => __('Enable Blog Tag ', 'eventum'),
                        'default'   => false,
                    ),  

                    array(
                        'id'        => 'blog-edit-en',
                        'type'      => 'switch',
                        'title'     => __('Post Edit', 'eventum'),
                        'subtitle'  => __('Enable or disable post edit', 'eventum'),
                        'default'   => false,
                    ),                                        
                    
                    array(
                        'id'        => 'blog-single-comment-en',
                        'type'      => 'switch',
                        'title'     => __('Single Post Comment', 'eventum'),
                        'subtitle'  => __('Enable Single post comment ', 'eventum'),
                        'default'   => true,
                    ),

                    array(
                        'id'        => 'post-nav-en',
                        'type'      => 'switch',
                        'title'     => __('Post navigation', 'eventum'),
                        'subtitle'  => __('Enable Post navigation ', 'eventum'),
                        'default'   => true,
                    ),

                    array(
                        'id'        => 'blog-continue-en',
                        'type'      => 'switch',
                        'title'     => __('Blog Readmore', 'eventum'),
                        'subtitle'  => __('Enable Blog Readmore', 'eventum'),
                        'default'   => true,
                    ),

                    array(
                        'id'        => 'blog-continue',
                        'type'      => 'text',
                        'title'     => __('Continue Reading', 'eventum'),
                        'subtitle' => __('Continue Reading', 'eventum'),
                        'default'   => __('Continue Reading', 'eventum'),
                        'required'  => array('blog-continue-en', "=", 1),
                    ),  

                )
            );




            /**********************************
            ********* Social Media Link ***********
            ***********************************/

            $this->sections[] = array(
                'icon'      => 'el-icon-asterisk',
                'icon_class' => 'el-icon-large', 
                'title'     => __('Social Media', 'eventum'),
                'fields'    => array(
                 

                    array(
                        'id'        => 'wp-facebook',
                        'type'      => 'text',
                        'title'     => __('Add Facebook URL', 'eventum'),
                    ),
                    array(
                        'id'        => 'wp-twitter',
                        'type'      => 'text',
                        'title'     => __('Add Twitter URL', 'eventum'),
                    ),
                    array(
                        'id'        => 'wp-google-plus',
                        'type'      => 'text',
                        'title'     => __('Add Google Plus URL', 'eventum'),
                    ),
                    array(
                        'id'        => 'wp-pinterest',
                        'type'      => 'text',
                        'title'     => __('Add Pinterest URL', 'eventum'),
                    ),
                    array(
                        'id'        => 'wp-youtube',
                        'type'      => 'text',
                        'title'     => __('Add Youtube URL', 'eventum'),
                    ),
                    array(
                        'id'        => 'wp-linkedin',
                        'type'      => 'text',
                        'title'     => __('Add Linkedin URL', 'eventum'),
                    ),
                    array(
                        'id'        => 'wp-dribbble',
                        'type'      => 'text',
                        'title'     => __('Add Dribbble URL', 'eventum'),
                    ),
                    array(
                        'id'        => 'wp-behance',
                        'type'      => 'text',
                        'title'     => __('Add Behance URL', 'eventum'),
                    ), 
                    array(
                        'id'        => 'wp-flickr',
                        'type'      => 'text',
                        'title'     => __('Add Flickr URL', 'eventum'),
                    ), 
                    array(
                        'id'        => 'wp-vk',
                        'type'      => 'text',
                        'title'     => __('Add vk URL', 'eventum'),
                    ),  
                    array(
                        'id'        => 'wp-skype',
                        'type'      => 'text',
                        'title'     => __('Add skype URL', 'eventum'),
                    ),
                    array(
                        'id'        => 'wp-instagram',
                        'type'      => 'text',
                        'title'     => __('Add Instagram URL', 'eventum'),
                    ),


                )
            );



            /**********************************
            ********* Footer ***********
            ***********************************/

            $this->sections[] = array(
                'icon'      => 'el-icon-bookmark',
                'icon_class' => 'el-icon-large', 
                'title'     => __('Footer', 'eventum'),
                'fields'    => array(
                 

                    array(
                        'id'        => 'copyright-en',
                        'type'      => 'switch',
                        'title'     => __('Copyright', 'eventum'),
                        'subtitle'  => __('Enable Copyright Text', 'eventum'),
                        'default'   => true,
                    ),

                    array(
                        'id'        => 'footer-bg-color',
                        'type'      => 'color',
                        'title'     => __('Footer Background Color', 'eventum'),
                        'subtitle'  => __('Pick a background color for the footer (default: #343d47).', 'eventum'),
                        'default'   => '#343d47',
                        'validate'  => 'color',
                        'transparent'   =>false,
                    ),  

                    array(
                        'id'        => 'footer-text-color',
                        'type'      => 'color',
                        'title'     => __('Footer Text Color', 'eventum'),
                        'subtitle'  => __('Pick a Text color for the footer (default: #999ea3).', 'eventum'),
                        'default'   => '#999ea3',
                        'validate'  => 'color',
                        'transparent'   =>false,
                    ),

                    array( 
                        'id'        => 'header_padding', 
                        'type'      => 'spacing',
                        'mode'      => 'padding',
                        'units'     => array('em', 'px'),
                        'output'    => array('footer'),
                        'title'     => esc_html__('Footer Padding','eventum'),
                        'subtitle'  => esc_html__('Footer Padding Top &amp; Bottom', 'eventum'),
                        'left'      => false,
                        'right'     => false,
                        'default'            => array(
                            'padding-top'     => '70', 
                            'padding-bottom'  => '70', 
                            'units'          => 'px', 
                        ),
                    ),                    

                    array(
                        'id'        => 'copyright-text',
                        'type'      => 'editor',
                        'title'     => __('Copyright Text', 'eventum'),
                        'subtitle'  => __('Add Copyright Text', 'eventum'),
                        'default'   => __('© 2015 Your Company. All Rights Reserved. Designed By <a href="http://www.themeum.com" target="_blank">THEMEUM</a>', 'eventum'),
                        'required'  => array('copyright-en', "=", 1),
                        
                    ),

                )
            );


            /**********************************
            ********* Import / Export ***********
            ***********************************/

            $this->sections[] = array(
                'title'     => __('Import / Export', 'eventum'),
                'desc'      => __('Import and Export your Theme Options settings from file, text or URL.', 'eventum'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your Redux options',
                        'full_width'    => false,
                    ),
                ),
            ); 

        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', 'redux-framework-demo'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', 'redux-framework-demo'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo');
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'themeum_options',            // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'      => $theme->get('Name'),     // Name that appears at the top of your panel
                'display_version'   => $theme->get('Version'),  // Version that appears at the top of your panel
                'menu_type'         => 'menu',                  //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => true,                    // Show the sections below the admin menu item or not
                'menu_title'        => __('Theme Options', 'eventum'),
                'page_title'        => __('Theme Options', 'eventum'),
                
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => '', // Must be defined to add google fonts to the typography module
                
                'async_typography'  => false,                    // Use a asynchronous font on the front end or font string
                'admin_bar'         => true,                    // Show the panel pages on the admin bar
                'global_variable'   => '',                      // Set a different name for your global variable other than the opt_name
                'dev_mode'          => false,                    // Show the time the page took to load, etc
                'customizer'        => true,                    // Enable basic customizer support
                //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                // OPTIONAL -> Give you extra features
                'page_priority'     => null,                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'       => 'themes.php',            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'  => 'manage_options',        // Permissions needed to access the options panel.
                'menu_icon'         => '',                      // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => 'icon-themes',           // Icon displayed in the admin panel next to your menu_title
                'page_slug'         => '_options',              // Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '',                      // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => true,                   // Shows the Import/Export panel when not used as a field.
                
                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.
                
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'              => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'           => false, // REMOVE

                // HINTS
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'slide',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );
         }

    }
    
    global $reduxConfig;
    $reduxConfig = new Redux_Framework_sample_config();
}

/**
  Custom function for the callback referenced above
 */
if (!function_exists('redux_my_custom_field')):
    function redux_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('redux_validate_callback_function')):
    function redux_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';

        /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;
