<?php
namespace cs_tmc\lib\Handlers;




use cs_tmc\lib\App;

class Template_Handler {

    /**
     * @var App
     */
	private $app;

	private $templates;		// array of all templates

	public  $default_template_id;





	function __construct( $app ) {

		$this->app = $app;

		$this->templates = array();
		$this->default_template_id = 'simple';

	}

	function get_templates() {

		return $this->templates;

	}

	function get_template( $id ) {

		if( isset( $this->templates[$id] ) ){

			return $this->templates[$id];

		} else {

			return false;

		}

	}

	function add_template( $template ) {

		$this->templates[ $template->id ] = $template;

	}

	function get_template_radio( $id ) {

		$template = $this->get_template( $id );

		if( $template ){

			$html = '';

			$html .= sprintf( '<div class="tile">' );
			$html .= sprintf( '<input type="radio" name="%1$s" value="%2$s" id="%3$s" %4$s>', $this->app->options_name . '[template][id]', $template->id, $template->input_id, checked( $template->id, $this->app->options['template']['id'], false ) );
			$html .= sprintf( '<label for="%1$s">', $template->input_id );
	        $html .= sprintf( '<div class="preview" style="background-image:url(%1$s);"></div>', $template->screenshot_url );
	        $html .= sprintf( '<div class="actions">' );

	        if( $template->is_active === true ){
	        
		        if( $this->app->options['template']['id'] == $template->id ) {

		        	$html .= sprintf( '<a class="button button-customize" href="%1$s">%2$s</a>', $this->get_customizer_link(), __( 'Customize', $this->app->txtdomain ) );

		        } else {

		        	$html .= sprintf( '<input type="submit" name="submit" value="%1$s" class="button button-primary">', __( 'Apply template', $this->app->txtdomain ) );

		        }

		    } else {

		    	$html .= sprintf( '<a class="get-it" target="_blank" href="%1$s">%2$s</a>', 'http://www.themastercut.co/?utm_source=install&utm_campaign=getnewtemplate&utm_content=comingsoon', __( 'Visit our store for more templates', $this->app->txtdomain ) );

		    }
	        
	        $html .= sprintf( '</div>' );
	        $html .= sprintf( '</label>' );
	        $html .= sprintf( '</div>' );

	        return $html;

		}

	}

	function get_customizer_link() {

		return add_query_arg(
					            array(
					                'url'                               =>  urlencode( add_query_arg(
					                                                         array( $this->app->prefix( '_preview' ) => '1' ),
					                                                         get_site_url() 
					                                                     ) ),
					                'autofocus[panel]'                  => $this->app->prefix(),
					                $this->app->prefix( '_customize' )  =>  '1'
					            ),
					            admin_url( 'customize.php' )
					        );

	}

	function get_current_template_id() {

		$template_id_from_options = $this->app->options['template']['id'];

		if( isset( $this->templates[ $template_id_from_options ] ) ){

			$template = $this->templates[ $template_id_from_options ];

			if( $template->is_active ){

				return $template->id;

			} else {

				return $this->default_template_id;

			}

		} else {

			return $this->default_template_id;

		}

	}

	function get_current_template() {

		return $this->templates[ $this->get_current_template_id() ];

	}

	function reset_current_template() {

		$this->app->options['template']['id'] = $this->default_template_id;

		$this->app->update_options();

	}

	function process_template_activation() {

		$template_id_from_options = $this->app->options['template']['id'];

		if( isset( $this->templates[ $template_id_from_options ] ) ){

			$template = $this->templates[ $template_id_from_options ];

			if( ! $template->is_active ){

				$this->reset_current_template();

			}

		} else {

			$this->reset_current_template();

		}

	}

	function process_template_change() {

		if( $this->app->options['template']['id'] != $this->app->options['template']['last_id'] ){

			$this->app->options['template']['last_id'] = $this->app->options['template']['id'];

			// 	FURTHER TEMPLATE CHANGE EVENT PROCESSING
			//	----------------------------------------

			$template = $this->get_template( $this->app->options['template']['id'] );

			$this->app->options = array_replace_recursive( $this->app->options, $template->get_template_options() );

			$this->app->update_options();

		}

	}

}