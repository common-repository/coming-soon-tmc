<?php
namespace cs_tmc\lib\Templates\Classes;




use cs_tmc\lib\App;

class Template {

    /**
     * @var App
     */
	public $app;

	public $id;
	public $input_id;
	public $screenshot_url;
	public $name;
	public $is_active;
	public $template_options;



	/**
	 * @param App $app - application object
	 * @param array $args - some necessary options
	 * @param array $template_options - options, that will override actual plugin options
	 */
	function __construct( $app, $args = null, $template_options = null ) {

		$this->app = $app;
		$this->template_options = $template_options;

		$reflection = new \ReflectionClass( $this );	// Reflection of class for naming purposes

		$args_default = array(
			'id'				=>	$reflection->getShortName(),
			'input_id'			=>	null,
			'screenshot_url'	=>	$this->app->url . '/lib/Templates/Screenshots/placeholder-300x200.png',
			'name'				=>	__( 'Template', $this->app->txtdomain ),
			'is_active'			=>	true,
		);

		$args = array_merge( $args_default, $args );	// merge defaults and custom values

		if( $args['input_id'] === null ){

			$args['input_id'] = $this->app->prefix( '_template_' . $args['id'] );

		}

		foreach ( $args as $key => $value ) {	// assign array values to class properties
			
			if( property_exists( $this, $key ) ){

				$this->$key = $value;

			}

		}

	}

	function get_template_options() {

		return $this->template_options;

	}

	function display() {

		//	# ----------------------
		//	Overwrite this method
		//	# ----------------------

	}

	function print_favicon() {

		if( ! empty( $this->app->options['content']['favicon'] ) ){

			printf( '<link rel="shortcut icon" type="image/png" href="%1$s"/>', $this->app->options['content']['favicon'] );

		}

	}

	function print_fonts_embed_code() {

        //  -----------------------------------
        //  Initialize fonts
        //  -----------------------------------

        $this->app->fonts_handler->initFontsQueue( $this->app->options['fonts'] );

        //  -----------------------------------
        //  Print embed code
        //  -----------------------------------

	    echo "<!-- START --- Automatic font embedder -->" . PHP_EOL;
	    echo $this->app->fonts_handler->getEmbedHtml();
        echo "<!-- END --- Automatic font embedder -->" . PHP_EOL;

    }

    function print_font_family( $fontSlug ) {

	    echo $this->app->fonts_handler->getFontFamily( $fontSlug );

    }

	function print_header_text() {

		printf( 
			'<h1 class="header_text" style="color:%2$s;">%1$s</h1>',
			$this->app->options['content']['header_text'],
			$this->app->options['content']['header_text_color']
		);

	}

	function print_message_text() {

		printf(
			'<span class="message_text" style="color:%2$s;">%1$s</span>',
			$this->app->options['content']['message_text'],
			$this->app->options['content']['message_text_color']
		);

	}

	function print_footer_note() {

		printf(
			'<span class="footer_note" style="color:%2$s;">%1$s</span>',
			$this->app->options['content']['footer_note'],
			$this->app->options['content']['footer_note_color']
		);

	}

	function print_logo() {

		if( ! empty( $this->app->options['content']['logo_image'] ) ){

			printf( '<img class="logo_image" src="%1$s">', $this->app->options['content']['logo_image'] );

		} else if( ! empty( $this->app->options['content']['logo_text'] ) ){

			printf(
				'<a class="logo_text" style="color:%2$s;">%1$s</a>',
				$this->app->options['content']['logo_text'], 
				$this->app->options['content']['logo_text_color']
			);

		}

	}

	function print_phone_number() {

		printf( $this->app->options['contact']['phone_number'] );

	}

	function print_phone_number_color() {

		printf( $this->app->options['contact']['phone_number_color'] );

	}

	function print_social_buttons() {

		printf( '<ul class="social_buttons">' );

		foreach( $this->app->options['social_services']['urls'] as $key => $value ){

			if( ! empty( $value ) ){

				printf( '<li><a class="social_buttons__link %1$s" href="%2$s"><i class="fa"></i></a></li>', $key, $value );

			}

		}

		printf( '</ul>' );

	}

	function print_twitter_widget() {

		if( ! empty( $this->app->options['social_widgets']['twitter_url'] ) ){

			printf( '<div class="twitter_widget_timeline"><div class="twitter-timeline-wrapper"><a class="twitter-timeline" data-height="320" data-chrome="noheader nofooter noborders transparent noscrollbar" href="%1$s" data-link-color="%2$s">Twitter</a></div></div> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>', $this->app->options['social_widgets']['twitter_url'], $this->app->options['social_widgets']['twitter_link_color'] );

		}

	}

	function print_twitter_widget_bg() {

		printf( $this->app->options['social_widgets']['twitter_bg'] );

	}

	function print_twitter_widget_bg_opacity() {

		printf( $this->app->options['social_widgets']['twitter_bg_opacity'] );

	}

	function print_store_links() {

		printf( '<div class="software_stores">' );

		foreach( $this->app->options['software_stores']['urls'] as $key => $value ){

			if( ! empty( $value ) ){

				switch( $key ){

					case 'appstore':
						$img_url = $this->app->url( '/assets/img/store_badges/appstore.png' );
						break;

					case 'play':
						$img_url = $this->app->url( '/assets/img/store_badges/play.png' );
						break;

					case 'amazon':
						$img_url = $this->app->url( '/assets/img/store_badges/amazon.png' );
						break;

					case 'windows':
						$img_url = $this->app->url( '/assets/img/store_badges/windows.png' );
						break;

				}

				printf( '<a class="software_stores__link img-fluid" target="_blank" href="%2$s"><img src="%2$s"></a>', $value, $img_url );

			}

		}

		printf( '</div>' );

	}

	function print_analitics() {

		printf( $this->app->options['google_analytics']['tracking_code'] );

	}

	function print_conversion_tracking() {

		printf( $this->app->options['google_adwords']['conversion_tracking_code'] );

	}

	function print_subscription_form() {

		printf( '<form class="subscription_form" method="post" action="%1$s">', admin_url( 'admin-ajax.php' ) );
		printf( '<div class="input-group">' );
		printf( '<input type="text" name="email" class="form-control" placeholder="%1$s">', $this->app->options['subscription']['field_text'] );
		printf( '<span class="input-group-btn">' );
		printf(
			'<input type="submit" name="submit" value="%1$s" class="btn btn-primary" style="color:%2$s; background-color:%3$s;">',
			$this->app->options['subscription']['button_text'],
			$this->app->options['subscription']['button_text_color'],
			$this->app->options['subscription']['button_background']
		);
		printf( '</span>' );
		printf( '</div>' );
		printf(
			'<div class="thank-you-message" style="color:%2$s; background-color:%3$s;">%1$s</div>',
			$this->app->options['subscription']['message_text'],
			$this->app->options['subscription']['message_text_color'],
			$this->app->options['subscription']['message_background']
		);
		printf( '</form>' );

	}

	function print_subscribe_javascript() {
		?>

		<script>
			jQuery(document).ready(function($) {

				$('.subscription_form').on('submit', function(e){

					e.preventDefault();

					var form = $(this);

					if( ! form.find('[type="submit"]').prop('disabled') ){

						form.find('[type="submit"]').prop('disabled', true);

						var email 	= form.find('[name="email"]').val();
						var url 	= form.attr('action');

						var data = {
							'action': 	'subscribe_action',
							'email': 	email
						};

						$.post( url, data, function(response) {
							
							form.addClass( 'done' );
							console.log(response);

						});

					}

				 });
				
			});
		</script>

		<?php
	}

	function print_body_tag() {

		$options = $this->app->options['background'];

		$attributes = array(
			'class'		=>	array(),
			'style'		=>	array()
		);

		$attributes['style'][] = sprintf( 'background-color:%s;', $options['color'] );

		if( ! empty( $options['image'] ) ){

			$attributes['style'][] = sprintf( 'background-image:url(%s);', $options['image'] );
			$attributes['style'][] = sprintf( 'background-size:%s;', $options['size'] );
			$attributes['style'][] = sprintf( 'background-attachment:%s;', $options['attachment'] );
			$attributes['style'][] = sprintf( 'background-repeat:%s;', $options['repeat'] );
			$attributes['style'][] = sprintf( 'background-position:%s;', $options['position'] );

		}


		$body_tag = '<body ';

		foreach( $attributes as $attr => $values ){
			
			$body_tag .= sprintf( '%1$s="%2$s" ', $attr, implode( ' ', $values ) );

		}

		$body_tag .= '>';

		echo $body_tag;

	}

	function print_background_video() {

		if( ! empty( $this->app->options['background']['yt_url'] ) ){

			$parts = parse_url( $this->app->options['background']['yt_url'] );
			$query = '';

			parse_str( $parts['query'], $query );	// parse parameters to $query

			if( isset( $query['v'] ) && ! empty( $query['v'] ) ){

				printf(	'<div class="background-video">' );
				printf( '<iframe id="background-video-ytplayer" src="https://www.youtube.com/embed/%1$s?controls=0&showinfo=0&rel=0&autoplay=1&loop=1&iv_load_policy=3&playlist=%1$s" frameborder="0" allowfullscreen></iframe>', $query['v'] );
  				printf( '</div>' );

			}


		}

	}

	//	==========================================
	//	SIMPLE COLORS GETTERS
	//	------------------------------------------

	function get_social_color_primary() {

		return $this->app->options['social_services']['primary_color'];

	}

	function get_social_color_secondary() {

		return $this->app->options['social_services']['secondary_color'];
		
	}

}