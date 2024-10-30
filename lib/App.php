<?php
namespace cs_tmc\lib;

use cs_tmc\lib\Config\Config_Default;
use cs_tmc\lib\Handlers\Fonts_Handler;
use cs_tmc\lib\Handlers\Lock_Handler;
use cs_tmc\lib\Handlers\Subscription_Handler;
use cs_tmc\lib\Handlers\Template_Handler;
use cs_tmc\lib\Managers\WP_Customize_Image_Fake_Control;




class App {

	// PLUGIN INFO
	// --------------------

	public $version;
	public $file_path;
	public $url;
	public $path;
	public $prefix;
	public $namespace;
	public $txtdomain;

	public $tmc_site;
	public $compare_name;
	public $options;
	public $options_name;

	// PLUGIN COMPONENTS
	// --------------------

	public $helpers;
	public $actions;
	public $filters;
	public $ajax;

	// PLUGIN HANDLERS
	// --------------------

	public $template_handler;
	public $lock_handler;
	public $subscription_handler;
	public $option_pages;
	public $fonts_handler;

	// PLUGIN VARIABLES
	// --------------------




	function __construct( $file_path ) {

		$this->version = 		"1.1.3";
		$this->file_path = 		$file_path;
		$this->slug =			plugin_basename( $file_path );
		$this->url = 			plugin_dir_url( $file_path );
		$this->path = 			plugin_dir_path( $file_path );
		$this->prefix = 		'cs_tmc';				// used for trailing shit
		$this->namespace = 		'cs_tmc';				// used for first segment of namespace
		$this->txtdomain =		'cs-tmc';				// used for textdomain
		$this->compare_name =	'coming-soon-tmc';		// used for plugin duplication check 

		$this->tmc_site = 		'http://themastercut.co/';

		spl_autoload_register( array( $this, 'autoloader' ) );	// autoload classes

		//	=====================================
		//	ADMIN PAGES AND OPTIONS
		//	-------------------------------------

		include( $this->path . '/lib/External/admin-page-framework/admin-page-framework.php' );

		$this->options_name = $this->prefix( '_options' );

		$this->option_pages = new Options\Option_Pages( $this->options_name );
		$this->option_pages->init( $this );

		$this->options = $this->get_options();		// load all options into variable

		// Factory reset

		if( $this->options['factory_reset']['enabled'] == 'yes' || ! isset( $this->options['factory_reset']['enabled'] ) ){

			$this->restore_options();

		}

		// Repair differences

		$config_default = Config_Default::get_config( $this );
		$this->provide_options_compatibility( $config_default );

		//	=====================================
		//	COMPONENTS SETUP
		//	=====================================

		$this->lock_handler			= new Lock_Handler( $this );
		$this->template_handler		= new Template_Handler( $this );
		$this->subscription_handler	= new Subscription_Handler( $this );
		$this->fonts_handler        = new Fonts_Handler( $this );

		//	=====================================
		//	CORE ACTIONS SETUP
		//	=====================================

		register_activation_hook( $this->file_path, array( $this, 'install' ) );

		add_action( 'plugin_action_links_' . $this->slug, 					array( $this, 'add_plugin_action_links' ) );
		add_action( 'admin_enqueue_scripts', 								array( $this, 'load_admin_scripts' ) );
		add_action( 'admin_bar_menu', 										array( $this->lock_handler, 'add_lock_toggle' ), 500 );
		add_action( 'init', 												array( $this->lock_handler, 'init' ) );
		add_action( 'init', 												array( $this, 'add_comingsoon_templates' ), 5 );
		add_action( 'init',													array( $this->template_handler, 'process_template_activation' ), 10 );
		add_action( 'init',													array( $this->template_handler, 'process_template_change' ), 12 );
		add_action( 'template_redirect', 									array( $this, 'coming_soon_template' ) );
		add_action( 'customize_register', 									array( $this, 'setup_customizer' ) );
		add_action( 'wp_ajax_subscribe_action', 							array( $this->subscription_handler, 'subscribe_action' ) );
		add_action( 'wp_ajax_nopriv_subscribe_action', 						array( $this->subscription_handler, 'subscribe_action' ) );

		//	=====================================
		//	MICRO ACTIONS SETUP
		//	-------------------------------------


		//	=====================================
		//	AJAX ACTIONS SETUP
		//	-------------------------------------


		//	=====================================
		//	FILTERS SETUP
		//	-------------------------------------

	}

	function autoloader( $class_name ) {

		if( strpos( $class_name, $this->namespace ) !== false ) {	//	if namespace prefix occure in class name

			$class_path = str_replace( $this->namespace, '', $class_name );	// remove namespace prefix
			$class_path = ltrim( $class_path, '\\' );	// remove leading namespace prefix

			$class_path = $this->path . str_replace('\\', '/', $class_path) . '.php';

			if( file_exists( $class_path ) ) {

				require_once( $class_path );

			}

		}

	}

	//	==============================================
	//	INSTALL
	//	==============================================

	function install() {

		//	==========================================
		//	Check for plugin duplication
		//	------------------------------------------

		$plugins = get_option ( 'active_plugins', array () );

	    foreach ( $plugins as $plugin ) {

	    	if( strpos ( $plugin, $this->compare_name ) !== false ){

	    		wp_die( __( 'Whooops! Looks like you want to activate two Coming Soon TMC instances at once! <br/>Please disable the second one.', 'cm-tmc' ) );

	    	}

	    }

	    //	==========================================
	    //	Install default config
	    //	------------------------------------------

	    if( empty( $this->options ) ){

	    	$this->restore_options();
	    	
	    }
	    

	}

	//	==========================================
	//	TEMPLATE REPLACE BEHAVIOR
	//	==========================================

	function coming_soon_template() {

		redirect_canonical();

		if( isset( $_GET[ $this->prefix( '_preview' ) ] ) ){		// is preview

			$this->refresh_options();

			add_action('wp_print_styles', array( $this, 'remove_all_styles' ), 100);
			add_action('wp_print_styles', array( $this, 'remove_all_scripts' ), 100);

			$this->template_handler->get_current_template()->display();
			exit;

		}

		// admin paths
		$admin_paths = array(
			'async-upload.php',
			'upgrade.php',
			'wp-login.php',
			'wp-admin.php',
			'/uploads/',
			'wp-admin',
			'wp-login',
			'login',
			'wp-cron',
			'feed',
			'async-upload.php',
			'upgrade.php',
			'/plugins/',
			'xmlrpc.php'
		);

		foreach( $admin_paths as $path ){

			if( strpos( $_SERVER['REQUEST_URI'], $path ) === false ){			// path not in current address

				if(	$this->lock_handler->is_locked() ){							// is custom page ENABLED

					if(
						! is_super_admin()										// not super admin
						&& ! ( defined('DOING_AJAX') && DOING_AJAX )			// not ajax call
						&& ! in_array( $GLOBALS['pagenow'], $admin_paths )		// not on admin pages
					){

						add_action('wp_print_styles', array( $this, 'remove_all_styles' ), 100);
						add_action('wp_print_styles', array( $this, 'remove_all_scripts' ), 100);

						$this->template_handler->get_current_template()->display();
						exit;

					}
				}

			}

		}


	}

	//	=====================================
	//	TEMPLATES
	//	=====================================

	function add_comingsoon_templates() {

		$this->template_handler->add_template(
												new Templates\Simple_Template( $this,
													array( 
	        											'id'				=>	'simple',
	        											'name'				=>	'Simple',
	        											'screenshot_url'	=>	$this->url( 'lib/Templates/Screenshots/basicv2_coming-soon-tmc.png' )
	        										),
	        										array(
	        											'background' => array(	// template
												            'color' 		=> '#29aae3',
												            'image' 		=> '',
												            'repeat' 		=> 'no-repeat',
												            'attachment' 	=> 'scroll',
												            'size'			=> 'cover',
												            'position'		=> 'center',
												            'video'			=> '',	// pro
												            'yt_url'		=> ''	// pro
												        ),
													    'content' => array(
												            'logo_image' 			=> '',
												            'logo_text' 			=> 'Company Name',
												            'logo_text_color'		=> '#ffffff',	// template
												            'header_text' 			=> 'HERE GOES YOUR INGENIOUS <span style="color:#000000;">HEADER</span>',
												            'header_text_color'		=> '#ffffff',	// template
												            'message_text' 			=> 'With a handful of pre made coming soon templates and multipurpose options you can build your temporary elegant website. You can tell the users that your web is not ready yet or you have some problems to solve.',
												            'message_text_color'	=> '#ffffff',	// template
												            'footer_note' 			=> '© All Rights Reserved',
												            'footer_note_color'		=> '#ffffff',	// template
												            'favicon' 				=> ''
											       		),
													    'subscription' => array(
												            'field_text' 			=> 'Leave us your e-mail and be the first to know',
												            'button_text' 			=> 'Submit',
												            'button_text_color' 	=> '#ffffff',	// template
												            'button_background' 	=> '#29aae3',	// template
												            'message_text' 			=> 'Thank you! We will notify you as soon as we launch our website.',
												            'message_text_color'	=> '#ffffff',	// template
												            'message_background'	=> '#29aae3'	// template
												        ),
												        'social_services' => array(
												            'primary_color'		=> '#fff',
												            'secondary_color'	=> '#000'
													    ),
													    'contact' => array(
                                                            'phone_number'          =>  '',
                                                            'phone_number_color'    =>  '#ffffff'
                                                        ),
                                                        'fonts' =>  array(
                                                            'logo_text_font'                =>  'permanent_marker',
                                                            'header_text_font'              =>  'raleway',
                                                            'message_text_font'             =>  'raleway',
                                                            'footer_note_font'              =>  'raleway',
                                                            'button_text_font'              =>  'raleway',
                                                            'sub_message_text_font'         =>  'raleway',
                                                            'phone_number_font'             =>  'raleway'
                                                        )
	        										)
	        									)
		);

		$this->template_handler->add_template( 
                                                new Templates\Simple_Template( $this,
                                                    array( 
                                                        'id'                =>  'caffee',
                                                        'name'              =>  'Caffee',
                                                        'screenshot_url'    =>  $this->url( 'lib/Templates/Screenshots/caffee_coming-soon-tmc.png' ),
                                                        'is_active'         =>  false
                                                    )
                                                )
        );

        $this->template_handler->add_template( 
                                                new Templates\Simple_Template( $this,
                                                    array( 
                                                        'id'                =>  'relax',
                                                        'name'              =>  'Relax',
                                                        'screenshot_url'    =>  $this->url( 'lib/Templates/Screenshots/relax_coming-soon-tmc.jpg' ),
                                                        'is_active'         =>  false
                                                    )
                                                )
        );

        $this->template_handler->add_template( 
                                                new Templates\Simple_Template( $this,
                                                    array( 
                                                        'id'                =>  'tattoo',
                                                        'name'              =>  'Tattoo',
                                                        'screenshot_url'    =>  $this->url( 'lib/Templates/Screenshots/tattoo_coming-soon-tmc.jpg' ),
                                                        'is_active'         =>  false
                                                    )
                                                )
        );

		$this->template_handler->add_template( 
                                                new Templates\Simple_Template( $this,
                                                    array( 
                                                        'id'                =>  'taxi',
                                                        'name'              =>  'Taxi',
                                                        'screenshot_url'    =>  $this->url( 'lib/Templates/Screenshots/taxi_coming-soon-tmc.jpg' ),
                                                        'is_active'         =>  false
                                                    )
                                                )
        );

	}

	//	==============================================
	//	ADD LINKS TO PLUGIN ROW
	//	==============================================

	function add_plugin_action_links( $links ) {

		$mylinks = array(
			'<a class="dashicons-before dashicons-admin-settings" href="options-general.php?page='. $this->prefix . '_options"> ' . __('Settings', 'rm-tmc').'</a>'
		);

		return array_merge( $links, $mylinks );
	}

	//	==============================================
	//	LOAD SCRIPTS
	//	==============================================

	function load_admin_scripts(){	// skrypty wczytywane do backendu

		$version = $this->prefix .'_'. $this->version;

		$depends = array(
			'jquery',
		);

		wp_enqueue_script( $this->prefix( '-script' ), $this->url . 'assets/js/admin.js', $depends, $version, false );
		wp_enqueue_style( $this->prefix( '-style' ), $this->url . 'assets/css/admin.css', array(  ), $version, false );

		// 	---------------------------------
		//	Wartości przekazywane do skryptów

		wp_localize_script( $this->prefix( '-script' ), 'app',
			array( 
				'url' 			=> $this->url,			// url katalogu z pluginem
				'version'		=> $this->version,
				'prefix'		=> $this->prefix
			)
		);

	}

	//	==============================================
	//	PREFIXER AND URL'er :)))
	//	==============================================

	function prefix( $string = null ) {

		return $this->prefix . $string;

	}

	function url( $string = null ) {

		return $this->url . $string;

	}

	//	==========================================
	//	REMOVE SCRIPTS AND STYLES
	//	------------------------------------------

	function remove_all_scripts() {

	    global $wp_scripts;
	    $wp_scripts->queue = array();

	}


	function remove_all_styles() {

	    global $wp_styles;
	    $wp_styles->queue = array();

	}

	//	==========================================
	//	OPTIONS GETTERS AND SETTERS
	//	------------------------------------------

	function update_options( $options = null ) {

		if( $options !== null ){

			$this->options = (array) $options;

		}

		update_option( $this->options_name, $this->options );

	}

    /**
     * Checks differences between given array and internal plugin options.
     * If given config has more options, they are added to plugin options array.
     *
     * @param array $config
     */
	function provide_options_compatibility( $config ) {

        $this->options = $this->array_merge_recursive_distinct_safe( $this->options, $config );

        $this->update_options();

    }

    /**
     * Merge two arrays without structure changing and overriding values
     *
     * @param array $array1 - base array
     * @param array $array2 - additional array with more keys/values
     *
     * @return array - merged array
     */
    function array_merge_recursive_distinct_safe( array &$array1, array &$array2 ){

        $merged = $array1;

        foreach( $array2 as $key => &$value ){

            // If both values are arrays
            if ( is_array( $value ) && isset( $merged[$key] ) && is_array( $merged[$key] ) ){

                $merged[$key] = $this->array_merge_recursive_distinct_safe( $merged[$key], $value );

            } else if( ! isset( $merged[$key] ) ) {

                $merged[$key] = $value;

            }

        }

        return $merged;

    }

	function restore_options() {

		$options = Config\Config_Default::get_config( $this );

		$this->update_options( $options );

	}

	function get_options() {

		return get_option( $this->options_name, array() );

	}

	function refresh_options() {

		$this->options = $this->get_options();

	}

	/*	------------------------------------
		This is heavy shit, use it wisely
		------------------------------------	*/

	function get_option( $key ) {

		$options = $this->get_options();

    	preg_match_all('/[A-Za-z0-9_\-]+/', $key, $result );		// get array of keys

    	$result = $result[0];	// get array of strings that matched full pattern

    	if( is_array( $result ) ){

    		foreach( $result as $key ) {
    			
    			if( isset( $options[$key] ) ){

    				$options = $options[$key];

    			} else {

    				return false;

    			}
    			

    		}

    		return $options;

    	}

    	return false;

	}

	//	==========================================
	//	CUSTOMIZER
	//	------------------------------------------

    /**
     * @param \WP_Customize_Manager $wp_customize
     */
	function setup_customizer( $wp_customize ) {

		//	--------------------
		//	Sections and panels
		//	--------------------

		$wp_customize->add_panel( $this->prefix(), array(
		  'title' 		=> 'Coming Soon TMC',
		  'capability'	=> 'manage_options',
		  'priority' 	=> 160
		) );

		$wp_customize->add_section( $this->prefix( '_background' ), array(
		  'title' 	=>	__( 'Background', $this->txtdomain ),
		  'panel'	=>	$this->prefix()
		) );

		$wp_customize->add_section( $this->prefix( '_logo' ), array(
		  'title' 	=>	__( 'Logo', $this->txtdomain ),
		  'panel'	=>	$this->prefix()
		) );

		$wp_customize->add_section( $this->prefix( '_middle' ), array(
		  'title' 	=>	__( 'Main content', $this->txtdomain ),
		  'panel'	=>	$this->prefix()
		) );

		$wp_customize->add_section( $this->prefix( '_footer' ), array(
		  'title' 	=>	__( 'Footer', $this->txtdomain ),
		  'panel'	=>	$this->prefix()
		) );

		$wp_customize->add_section( $this->prefix( '_subscription' ), array(
		  'title' 	=>	__( 'Subscription', $this->txtdomain ),
		  'panel'	=>	$this->prefix()
		) );

		$wp_customize->add_section( $this->prefix( '_social_services' ), array(
            'title' 	        =>	__( 'Social services', $this->txtdomain ),
            'panel'	            =>	$this->prefix(),
            'description'       =>  sprintf( '<div class="cs-tmc-description">%1$s</div>', __( 'Just paste your timeline address and it\'s all set up! or leave empty to not display on front page.', $this->txtdomain ) )
        ) );

		$wp_customize->add_section( $this->prefix( '_social_widgets' ), array(
		    'title' 	        =>	__( 'Social widgets', $this->txtdomain ),
		    'panel'	            =>	$this->prefix(),
            'description'       =>  sprintf( '<div class="cs-tmc-description">%1$s</div>', __( 'Add and edit your social icons widgets in settings.', $this->txtdomain ) )
		) );

		//	--------------------
		//	Settings
		//	--------------------

		$wp_customize->add_setting( $this->options_name . '[background][color]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[background][image]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[background][size]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[background][repeat]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[background][attachment]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[background][position]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );




		$wp_customize->add_setting( $this->options_name . '[content][logo_text]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[content][logo_text_color]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[content][logo_image]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[content][header_text]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[content][header_text_color]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[content][message_text]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[content][message_text_color]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[content][footer_note]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[content][footer_note_color]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );




		$wp_customize->add_setting( $this->options_name . '[subscription][field_text]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[subscription][button_text]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[subscription][button_text_color]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[subscription][button_background]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[subscription][message_text]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[subscription][message_text_color]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[subscription][message_background]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );




		$wp_customize->add_setting( $this->options_name . '[social_services][primary_color]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[social_services][secondary_color]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );




		$wp_customize->add_setting( $this->options_name . '[social_widgets][twitter_link_color]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[social_widgets][twitter_bg]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );

		$wp_customize->add_setting( $this->options_name . '[social_widgets][twitter_bg_opacity]', array(
		  'type' => 'option',
		  'transport' => 'refresh'
		) );



        $wp_customize->add_setting( $this->options_name . '[fonts][logo_text_font]', array(
            'type' => 'option',
            'transport' => 'refresh'
        ) );

        $wp_customize->add_setting( $this->options_name . '[fonts][header_text_font]', array(
            'type' => 'option',
            'transport' => 'refresh'
        ) );

        $wp_customize->add_setting( $this->options_name . '[fonts][message_text_font]', array(
            'type' => 'option',
            'transport' => 'refresh'
        ) );

        $wp_customize->add_setting( $this->options_name . '[fonts][footer_note_font]', array(
            'type' => 'option',
            'transport' => 'refresh'
        ) );

        $wp_customize->add_setting( $this->options_name . '[fonts][button_text_font]', array(
            'type' => 'option',
            'transport' => 'refresh'
        ) );

        $wp_customize->add_setting( $this->options_name . '[fonts][sub_message_text_font]', array(
            'type' => 'option',
            'transport' => 'refresh'
        ) );

        $wp_customize->add_setting( $this->options_name . '[fonts][phone_number_font]', array(
            'type' => 'option',
            'transport' => 'refresh'
        ) );

		//	--------------------
		//	Controls
		//	--------------------

		$wp_customize->add_control( 
			new \WP_Customize_Color_Control( 
				$wp_customize, 
				$this->options_name . '[background][color]',
				array(
					'label'      	=> __('Background Color', $this->txtdomain ),
					'description'	=> __('Remember, if you choose background image, it will appear on top of background color.', $this->txtdomain ),
					'section'    	=> $this->prefix( '_background' ),
					'settings'   	=> $this->options_name . '[background][color]',
					'priority'		=> 5
				)
			) 
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Fake_Control(
				$wp_customize,
				$this->options_name . '[background][image]',
				array(
				   'label'      	=> __('Background Image', $this->txtdomain),
				   'description'	=> __('If type of background is set to "Full Image" your image will be expanded to cover the whole page.', $this->txtdomain),
				   'section'    	=> $this->prefix( '_background' ),
				   'settings'   	=> $this->options_name . '[background][image]',
				   'priority'		=> 10
				)
			)
		);

		$wp_customize->add_control(
			$this->options_name . '[background][size]',
			array(
				'label'    => __('Background size', $this->txtdomain),
				'section'  => $this->prefix( '_background' ),
				'settings' => $this->options_name . '[background][size]',
				'type'     => 'radio',
				'choices'  => array(
					'initial'   =>  __( 'Default', $this->txtdomain ),
                    'cover'     =>  __( 'Cover whole screen', $this->txtdomain ),
                    'contain'   =>  __( 'Contain inside screen', $this->txtdomain )
				),
			)
		);

		$wp_customize->add_control(
			$this->options_name . '[background][repeat]',
			array(
				'label'    => __( 'Pattern Repeat', $this->txtdomain ),
				'section'  => $this->prefix( '_background' ),
				'settings' => $this->options_name . '[background][repeat]',
				'type'     => 'radio',
				'choices'  => array(
					'no-repeat' =>  __( 'No repeat', $this->txtdomain ),
                    'repeat'    =>  __( 'Repeat', $this->txtdomain ),
                    'repeat-x'  =>  __( 'Repeat Horizontally', $this->txtdomain ),
                    'repeat-y'  =>  __( 'Repeat Vertically', $this->txtdomain )
				),
			)
		);

		$wp_customize->add_control(
			$this->options_name . '[background][attachment]',
			array(
				'label'    => __('Fixed background', $this->txtdomain),
				'section'  => $this->prefix( '_background' ),
				'settings' => $this->options_name . '[background][attachment]',
				'type'     => 'radio',
				'choices'  => array(
					'fixed'     => __( 'Yes', $this->txtdomain ),
                    'scroll'    => __( 'No', $this->txtdomain )
				),
				'description'   => __('Select Yes, if you do not want the background to scroll with the content.', $this->txtdomain)
			)
		);

		$wp_customize->add_control(
			$this->options_name . '[background][position]',
			array(
				'label'    => __('Background position', $this->txtdomain),
				'section'  => $this->prefix( '_background' ),
				'settings' => $this->options_name . '[background][position]',
				'type'     => 'radio',
				'choices'  => array(
					'center'    		=> __( 'Center', $this->txtdomain ),
                    'center top'   		=> __( 'Top', $this->txtdomain ),
                    'center bottom'    	=> __( 'Bottom', $this->txtdomain )
				),
				'description'   => __('If your image is not covering the whole screen, positioning will have an effect on display.', $this->txtdomain)
			)
		);




		$wp_customize->add_control(
			new WP_Customize_Image_Fake_Control(
				$wp_customize,
				$this->options_name . '[content][logo_image]',
				array(
				   'label'      	=> __( 'Upload a logo', $this->txtdomain ),
				   'section'    	=> $this->prefix( '_logo' ),
				   'settings'   	=> $this->options_name . '[content][logo_image]',
				   'description'	=> __( 'If image will not be set, we will use plain text instead.' )
				)
			)
		);

		$wp_customize->add_control( 
			$this->options_name . '[content][logo_text]', 
			array(
				'type' 		=> 'text',
				'section' 	=> $this->prefix( '_logo' ),
				'label' 		=> __( 'Logo text', $this->txtdomain ),
				'input_attrs' => array(
					'placeholder' => __( 'Company name', $this->txtdomain )
				)
			)
		);

        $wp_customize->add_control(
            $this->options_name . '[fonts][logo_text_font]',
            array(
                'label'    => __( 'Logo text font', $this->txtdomain ),
                'section'  => $this->prefix( '_logo' ),
                'type'     => 'select',
                'choices'  => $this->fonts_handler->getChoices(),
            )
        );

		$wp_customize->add_control( 
			new \WP_Customize_Color_Control( 
				$wp_customize, 
				$this->options_name . '[content][logo_text_color]', 
				array(
					'label'      	=> __('Logo text color', $this->txtdomain ),
					'section'    	=> $this->prefix( '_logo' ),
					'settings'   	=> $this->options_name . '[content][logo_text_color]',
				)
			) 
		);

		$wp_customize->add_control( $this->options_name . '[content][header_text]', array(
		  'type' 		=> 'text',
		  'section' 	=> $this->prefix( '_middle' ),
		  'label' 		=> __( 'Header text', $this->txtdomain ),
		  'input_attrs' => array(
		    'placeholder' => __( 'Catchy header', $this->txtdomain )
		  )
		) );

        $wp_customize->add_control(
            $this->options_name . '[fonts][header_text_font]',
            array(
                'label'    => __( 'Header text font', $this->txtdomain ),
                'section'  => $this->prefix( '_middle' ),
                'type'     => 'select',
                'choices'  => $this->fonts_handler->getChoices(),
            )
        );

		$wp_customize->add_control( 
			new \WP_Customize_Color_Control( 
				$wp_customize, 
				$this->options_name . '[content][header_text_color]', 
				array(
					'label'      	=> __('Header text color', $this->txtdomain ),
					'section'    	=> $this->prefix( '_middle' ),
					'settings'   	=> $this->options_name . '[content][header_text_color]',
				)
			) 
		);

		$wp_customize->add_control( $this->options_name . '[content][message_text]', array(
		  'type' 		=> 'textarea',
		  'section' 	=> $this->prefix( '_middle' ),
		  'label' 		=> __( 'Message text', $this->txtdomain ),
		  'input_attrs' => array(
		    'placeholder' => __( 'Tell the user what is happening', $this->txtdomain )
		  )
		) );

        $wp_customize->add_control(
            $this->options_name . '[fonts][message_text_font]',
            array(
                'label'    => __( 'Message text font', $this->txtdomain ),
                'section'  => $this->prefix( '_middle' ),
                'type'     => 'select',
                'choices'  => $this->fonts_handler->getChoices(),
            )
        );

		$wp_customize->add_control( 
			new \WP_Customize_Color_Control( 
				$wp_customize, 
				$this->options_name . '[content][message_text_color]', 
				array(
					'label'      	=> __('Message text color', $this->txtdomain ),
					'section'    	=> $this->prefix( '_middle' ),
					'settings'   	=> $this->options_name . '[content][message_text_color]',
				)
			) 
		);

		$wp_customize->add_control( $this->options_name . '[content][footer_note]', array(
		  'type' 		=> 'textarea',
		  'section' 	=> $this->prefix( '_footer' ),
		  'label' 		=> __( 'Footer note', $this->txtdomain ),
		  'input_attrs' => array(
		    'placeholder' => __( 'Company name - 2017', $this->txtdomain )
		  )
		) );

        $wp_customize->add_control(
            $this->options_name . '[fonts][footer_note_font]',
            array(
                'label'    => __( 'Footer note font', $this->txtdomain ),
                'section'  => $this->prefix( '_footer' ),
                'type'     => 'select',
                'choices'  => $this->fonts_handler->getChoices(),
            )
        );

		$wp_customize->add_control( 
			new \WP_Customize_Color_Control( 
				$wp_customize, 
				$this->options_name . '[content][footer_note_color]', 
				array(
					'label'      	=> __('Footer note color', $this->txtdomain ),
					'section'    	=> $this->prefix( '_footer' ),
					'settings'   	=> $this->options_name . '[content][footer_note_color]',
				)
			) 
		);




		$wp_customize->add_control( $this->options_name . '[subscription][field_text]', array(
		  'type' 		=> 'text',
		  'section' 	=> $this->prefix( '_subscription' ),
		  'label' 		=> __( 'Field text', $this->txtdomain ),
		  'description'	=> __( 'This shows up as placeholder', $this->txtdomain )
		) );

		$wp_customize->add_control( $this->options_name . '[subscription][button_text]', array(
		  'type' 		=> 'text',
		  'section' 	=> $this->prefix( '_subscription' ),
		  'label' 		=> __( 'Button text', $this->txtdomain ),
		) );

        $wp_customize->add_control(
            $this->options_name . '[fonts][button_text_font]',
            array(
                'label'    => __( 'Button text font', $this->txtdomain ),
                'section'  => $this->prefix( '_subscription' ),
                'type'     => 'select',
                'choices'  => $this->fonts_handler->getChoices(),
            )
        );

		$wp_customize->add_control( 
			new \WP_Customize_Color_Control( 
				$wp_customize, 
				$this->options_name . '[subscription][button_text_color]', 
				array(
					'label'      	=> __('Button text color', $this->txtdomain ),
					'section'    	=> $this->prefix( '_subscription' ),
					'settings'   	=> $this->options_name . '[subscription][button_text_color]',
				)
			) 
		);

		$wp_customize->add_control( 
			new \WP_Customize_Color_Control( 
				$wp_customize, 
				$this->options_name . '[subscription][button_background]', 
				array(
					'label'      	=> __('Button background', $this->txtdomain ),
					'section'    	=> $this->prefix( '_subscription' ),
					'settings'   	=> $this->options_name . '[subscription][button_background]',
				)
			) 
		);

		$wp_customize->add_control( $this->options_name . '[subscription][message_text]', array(
		  'type' 		=> 'textarea',
		  'section' 	=> $this->prefix( '_subscription' ),
		  'label' 		=> __( 'Message', $this->txtdomain ),
		  'description'	=> __( 'This shows up as a notice after your visitors leave you their e-mail.', $this->txtdomain )
		) );

        $wp_customize->add_control(
            $this->options_name . '[fonts][sub_message_text_font]',
            array(
                'label'    => __( 'Message font', $this->txtdomain ),
                'section'  => $this->prefix( '_subscription' ),
                'type'     => 'select',
                'choices'  => $this->fonts_handler->getChoices(),
            )
        );

		$wp_customize->add_control( 
			new \WP_Customize_Color_Control( 
				$wp_customize, 
				$this->options_name . '[subscription][message_text_color]', 
				array(
					'label'      	=> __('Message color', $this->txtdomain ),
					'section'    	=> $this->prefix( '_subscription' ),
					'settings'   	=> $this->options_name . '[subscription][message_text_color]',
				)
			) 
		);

		$wp_customize->add_control( 
			new \WP_Customize_Color_Control( 
				$wp_customize, 
				$this->options_name . '[subscription][message_background]', 
				array(
					'label'      	=> __('Message background', $this->txtdomain ),
					'section'    	=> $this->prefix( '_subscription' ),
					'settings'   	=> $this->options_name . '[subscription][message_background]',
				)
			) 
		);




		$wp_customize->add_control( 
			new \WP_Customize_Color_Control( 
				$wp_customize, 
				$this->options_name . '[social_services][primary_color]', 
				array(
					'label'      	=> __( 'Primary color', $this->txtdomain ),
					'section'    	=> $this->prefix( '_social_services' ),
					'settings'   	=> $this->options_name . '[social_services][primary_color]',
				)
			) 
		);

		$wp_customize->add_control( 
			new \WP_Customize_Color_Control( 
				$wp_customize, 
				$this->options_name . '[social_services][secondary_color]', 
				array(
					'label'      	=> __( 'Secondary color', $this->txtdomain ),
					'section'    	=> $this->prefix( '_social_services' ),
					'settings'   	=> $this->options_name . '[social_services][secondary_color]',
				)
			) 
		);





		$wp_customize->add_control( 
			new \WP_Customize_Color_Control( 
				$wp_customize, 
				$this->options_name . '[social_widgets][twitter_link_color]', 
				array(
					'label'      	=> __( 'Twitter link color', $this->txtdomain ),
					'section'    	=> $this->prefix( '_social_widgets' ),
					'settings'   	=> $this->options_name . '[social_widgets][twitter_link_color]',
				)
			) 
		);

		$wp_customize->add_control( 
			new \WP_Customize_Color_Control( 
				$wp_customize, 
				$this->options_name . '[social_widgets][twitter_bg]', 
				array(
					'label'      	=> __( 'Twitter widget background', $this->txtdomain ),
					'section'    	=> $this->prefix( '_social_widgets' ),
					'settings'   	=> $this->options_name . '[social_widgets][twitter_bg]',
				)
			) 
		);

		$wp_customize->add_control( $this->options_name . '[social_widgets][twitter_bg_opacity]', array(
		  'type' 		=> 'text',
		  'section' 	=> $this->prefix( '_social_widgets' ),
		  'label' 		=> __( 'Twitter background opacity', $this->txtdomain ),
		  'input_attrs' => array(
		    'placeholder' => '0.5'
		  ),
		  'description'	=> __( 'You can define background opacity: ( from 0.0 to 1.0 )', $this->txtdomain )
		) );

	}

}