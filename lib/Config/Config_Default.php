<?php
namespace cs_tmc\lib\Config;




class Config_Default {

	static function get_config( $app ) {

		return array(
			'template'	=> array(
				'id'					=>	'simple',
				'last_id'				=>	''
			),
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
		    'status' => array(
	            'enabled' 				=>	'no',
	            'toggle' 				=>	'yes',
	            'view_capability'		=>	'manage_options'
	        ),
	        'factory_reset'		=> array(
	        	'enabled'	=>	'no'
	        ),
			'social_services' => array(
		        'urls' => array(
	                'facebook' 		=> 'https://www.facebook.com',
	                'twitter' 		=> 'https://twitter.com',
	                'youtube' 		=> 'https://www.youtube.com',
	                'googleplus' 	=> 'https://plus.google.com/',
	                'instagram' 	=> 'https://instagram.com',
	                'linkedin' 		=> 'https://www.linkedin.com/',
	                'pinterest' 	=> 'https://www.pinterest.com/',
	                'snapchat'		=> '',			// pro
	                '_500px'		=> '',			// pro
	                'medium'		=> '',			// pro
	            ),
                'primary_color'		=> '#fff',
                'secondary_color'	=> '#000'
		    ),
		    'social_widgets'		=> array(
		    	'twitter_url'			=>	'https://twitter.com/themastercutHQ',
		    	'twitter_link_color'	=>	'#000',
		    	'twitter_bg'			=>	'#fff',
		    	'twitter_bg_opacity'	=>	'1.0'
		    ),
		    'software_stores' => array(
		        'urls' => array(
	                'appstore'		=>	'https://www.apple.com',
	                'play'			=>	'https://play.google.com/store',
	                'amazon'		=>	'',
	                'windows'		=>	''
	            )
	        ),
			'google_analytics' => array(
			        'tracking_code' => ''
		    ),
			'google_adwords' => array(	// pro
			        'conversion_tracking_code' => ''
		    ),
			'newsletter' => array(
		        'enabled' 				=> 'yes',
		        'type'					=> 'email',
		        'email' 				=> '',
		        'getresponse_key'		=> '',	// pro
		        'getresponse_campaign'	=> '',	// pro
		        'mailchimp_key'			=> '',	// pro
		        'mailchimp_list'		=> '',	// pro
		        'freshmail_key'			=> '',	// pro
		        'freshmail_secret'		=> '',	// pro
		        'freshmail_list'		=> ''	// pro
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
	        'license' => array(
	        	'key'				        =>	'',
	        	'can_access'		        =>	'',
	        	'just_updated_license'		=>	''
	        ),
	        'contact' => array(
	        	'phone_number'			=>	'+49 333 333 333',
	        	'phone_number_color'	=>	'#fff'
	        ),
            'fonts' =>  array(
                'logo_text_font'                =>  'roboto',
                'header_text_font'              =>  'roboto',
                'message_text_font'             =>  'roboto',
                'footer_note_font'              =>  'roboto',
                'button_text_font'              =>  'roboto',
                'sub_message_text_font'         =>  'roboto',
                'phone_number_font'             =>  'roboto'
            )
		);

	}

}