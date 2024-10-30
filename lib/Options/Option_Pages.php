<?php
namespace cs_tmc\lib\Options;




use cs_tmc\lib\App;

class Option_Pages extends \cs_tmc_AdminPageFramework {

    /**
     * @var App
     */
	private $app;

    /**
     * @var string
     */
    private $optionsPageSlug;


	function init( $app ) {

		$this->app = $app;

	}

    function setUp() {

        $this->optionsPageSlug = $this->app->prefix( '_options' );  //  slug setup

        //  ----------------------------------------
	    //  Hooks setup
	    //  ----------------------------------------

        //  Add submit button to end of some tabs
        add_action( 'do_' . $this->optionsPageSlug .'_social_media',    array( $this, 'printSubmitOptionsHtml' ) );
        add_action( 'do_' . $this->optionsPageSlug .'_newsletter',      array( $this, 'printSubmitOptionsHtml' ) );
        add_action( 'do_' . $this->optionsPageSlug .'_settings',        array( $this, 'printSubmitOptionsHtml' ) );

	    //  ----------------------------------------
	    //  Standard options page setup
	    //  ----------------------------------------
        
        $this->setRootMenuPage( 'Settings' );    // where to belong

        $this->addSubMenuItem(
	        array(
	            'title'				=>	sprintf( 'Coming Soon TMC' ),
	            'page_slug'			=>	$this->optionsPageSlug,
	            'menu_title'		=>	sprintf( '<img src="%1$s" class="dashicons"> Coming Soon TMC', $this->app->url . '/assets/img/tmc_menu_icon.png' ),
	        	'show_debug_info'	=>	false
	        )
        );

        $this->setInPageTabTag( 'h2' );

        $this->addInPageTabs(
            $this->optionsPageSlug,    // target page slug
            array(
                'tab_slug'      =>  'templates',
                'title'         =>  __('Templates', $this->app->txtdomain ),
                'order'         =>  5,
            ),
            array(
                'tab_slug'      =>  'social_media',
                'title'         =>  __('Social Media', $this->app->txtdomain ),
                'order'         =>  10,
            ),
            array(
                'tab_slug'      =>  'newsletter',
                'title'         =>  __('Newsletter', $this->app->txtdomain ),
                'order'         =>  12,
            ),
            array(
                'tab_slug'      =>  'settings',
                'title'         =>  __('Settings', $this->app->txtdomain ),
                'order'         =>  20,
            )
        );

        //	==========================================
        //	FIELD SECTIONS
        //	------------------------------------------

        $this->addSettingSections(
            $this->optionsPageSlug,
            array(
                'section_id'    	=>  'status',
                'tab_slug'      	=>  'settings',
                'title'         	=>  __( 'Plugin Status', $this->app->txtdomain ),
                'order'				=> 5
            ),
            array(
                'section_id'    =>  'social_services',
                'tab_slug'      =>  'social_media',
                'title'         =>  __( 'Social Services', $this->app->txtdomain ),
                'order'             =>  8
            ),
            array(
                'section_id'    =>  'google_analytics',
                'tab_slug'      =>  'settings',
                'title'         =>  __( 'Google Analytics', $this->app->txtdomain ),
                'order'         =>  20
            ),
            array(
                'section_id'    =>  'newsletter',
                'tab_slug'      =>  'newsletter',
                'title'         =>  __( 'Newsletter', $this->app->txtdomain )
            ),
            array(
                'section_id'    =>  'factory_reset',
                'tab_slug'      =>  'settings',
                'title'         =>  __( 'Factory reset', $this->app->txtdomain ),
                'order'         =>  40
            ),
            array(
                'section_id'    =>  'template',
                'tab_slug'      =>  'templates',
                'title'         =>  __( 'Templates', $this->app->txtdomain ),
                'content'       =>  $this->display_templates()
            )
        );

        //	==========================================
        //	FIELDS
        //	------------------------------------------

        //	--------------------
        //	Status
        //	--------------------

        $this->addSettingFields(
            'status',
            array(
                'field_id'      => 'enabled',
                'type'          => 'radio',
                'title'         => __('Status', $this->app->txtdomain),
                'label'         =>  array(
                    'yes'   => __('Activated', $this->app->txtdomain),
                    'no'    => __('Deactivated', $this->app->txtdomain)
                ),
                'description'   => __( 'By default only the Administrator(s) can see the actual website.', $this->app->txtdomain ),
                'order'			=> 2
            ),
            array(
                'field_id'      => 'toggle',
                'type'          => 'radio',
                'title'         => __( 'Display toggle', $this->app->txtdomain ),
                'label'         =>  array(
                    'yes'   => __('Display', $this->app->txtdomain),
                    'no'    => __('Hide', $this->app->txtdomain)
                ),
                'description'   => __( 'If set, there will be simple lock button on admin bar.', $this->app->txtdomain ),
                'order'			=> 5
            )
        );

        //	--------------------
        //	Social services
        //	--------------------

        $this->addSettingFields(
            'social_services',
            array(
                'field_id'      =>  'urls',
                'title'         =>  'Profile URLs',
                'type'          =>  'text',
                'sortable'      =>  false,
                'description'   =>  __('Enter a URL to display the icon or leave empty to not display icons.', $this->app->txtdomain),
                'label'         =>  array(
                    'facebook'  	=>  __('Facebook', $this->app->txtdomain),
                    'twitter'   	=>  __('Twitter', $this->app->txtdomain),
                    'youtube'   	=>  __('Youtube', $this->app->txtdomain),
                    'googleplus'    =>  __('Google+', $this->app->txtdomain),
                    'instagram' 	=>  __('Instagram', $this->app->txtdomain),
                    'linkedin'  	=>  __('LinkedIn', $this->app->txtdomain),
                    'pinterest' 	=>  __('Pinterest', $this->app->txtdomain)
                ),
            )
        );

        //	--------------------
        //	Google Analitics
        //	--------------------

        $this->addSettingFields(
            'google_analytics',
            array(
                'field_id'      => 'tracking_code',
                'type'          => 'textarea',
                'title'         => __('Tracking Code', $this->app->txtdomain),
                'description'   => __('Paste your tracking code from Google Analytics panel or any other code. We put the code right after the opening &lt;body&gt;.', $this->app->txtdomain),
                'attributes'    =>  array(
                    'rows'  =>  8
                )
            )
        );

        //	--------------------
        //	Newsletter
        //	--------------------

        $this->addSettingFields(
            'newsletter',
            array(
                'field_id'      => 'enabled',
                'type'          => 'radio',
                'title'         => __('Enabled', $this->app->txtdomain),
                'label'         =>  array(
                    'yes'   => __( 'Yes' ),
                    'no'    => __( 'No' )       
                ),
                'description'   => __('Enable or disable newsletter subscribe form on the front-end.', $this->app->txtdomain),
                'order'         => 1
            ),
            array(
                'field_id'      =>  'type',
                'type'          =>  'radio',
                'title'         =>  __('Type', $this->app->txtdomain),
                'label'         =>  array(
                    'email'         =>  __( 'Send subscriptions to precised email.', $this->app->txtdomain ),
                    'mailchimp'     =>  __( 'Save in Mailchimp.', $this->app->txtdomain ),
                    'getresponse'   =>  __( 'Save in GetResponse.', $this->app->txtdomain ),
                    'freshmail'     =>  __( 'Save in FreshMail;.', $this->app->txtdomain )
                ),
                'attributes'    => array(
                    'mailchimp' => array( 
                        'disabled' => 'disabled'
                    ),
                    'getresponse' => array( 
                        'disabled' => 'disabled'
                    ),
                    'freshmail' => array( 
                        'disabled' => 'disabled'
                    ),
			    ),
                'order'         => 5
            ),
            array(
                'field_id'      =>  'email',
                'title'         =>  __( 'E-mail address', $this->app->txtdomain ),
                'type'          =>  'email',
                'attributes'        => array(
                    'class'         => 'regular-text',
                    'placeholder'   => __( 'youremail@site.com', $this->app->txtdomain )
                ),
                'description'   =>  __( 'We will send subscription notifications to this e-mail. By leaving this field empty, we will use the admin e-mail.', $this->app->txtdomain),
                'order'         => 8,
            )
        );

        //  --------------------
        //  Factory reset
        //  --------------------

        $this->addSettingFields(
            'factory_reset',
            array(
                'field_id'      => 'enabled',
                'type'          => 'radio',
                'title'         => __('Reset all options', $this->app->txtdomain),
                'label'         =>  array(
                    'yes'   => __('Activated', $this->app->txtdomain),
                    'no'    => __('Deactivated', $this->app->txtdomain)
                ),
                'description'   => __( 'If you turn on factory reset and save options, all settings will reset to default values.', $this->app->txtdomain )
            )
        );
            
    }

    function display_templates() {

        $html = null;

        $html .= sprintf( '<div class="%s">', 'cs-tmc-templates-wrapper' );

        foreach( $this->app->template_handler->get_templates() as $template ){
            
            $html .= $this->app->template_handler->get_template_radio( $template->id );

        }

        $html .= sprintf( '</div>' );

        // $html .= sprintf( print_r( $this->app->options ,true) );

        return $html;

    }

    function do_before_cs_tmc_options() {

        printf( '<div class="cs-tmc-before-page-box">' );
        printf( '<a class="logo" target="_blank" href="%s"><img src="%s"></a>', $this->app->tmc_site, $this->app->url( '/assets/img/tmc_logo_main.png' ) );
        printf( '</div>' );

    }

    /**
     * This is shared function.
     * It's used by page hooks to print submit button.
     */
    public function printSubmitOptionsHtml() {

        printf( '<div class="submit-box">' );
        submit_button();
        printf( '</div>' );

    }
    
}