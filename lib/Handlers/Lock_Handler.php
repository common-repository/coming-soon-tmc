<?php
namespace cs_tmc\lib\Handlers;




class Lock_Handler {

	public $app;

	//	variables
	//	-------------------

	public $query_var;		// variable in GET method




	function __construct( $app ) {

		$this->app = $app;

		$this->query_var = $this->app->prefix( '_lock' );

	}

	function init() {

		$capability = $this->app->options['status']['view_capability'];

		if( current_user_can( $capability ) ){

			if( isset( $_GET[$this->query_var] ) ){

				if( $_GET[$this->query_var] == '1' ){

					$this->lock();

				}

				if( $_GET[$this->query_var] == '0' ){

					$this->unlock();

				}

				wp_safe_redirect( add_query_arg( $this->query_var, null ) );	// remove query arg by redirecting
				exit;

			}

		}

	}

	function is_locked() {

		if( $this->app->options['status']['enabled'] == 'yes' ){
			return true;
		} else {
			return false;
		}

	}

	function lock() {

		$this->is_locked = true;

		$this->app->options['status']['enabled'] = 'yes';
		update_option( $this->app->options_name, $this->app->options );

	}

	function unlock() {

		$this->is_locked = false;

		$this->app->options['status']['enabled'] = 'no';
		update_option( $this->app->options_name, $this->app->options );

	}

	//	==============================================
	//	ADMIN BAR TOGGLER
	//	==============================================

	function add_lock_toggle( $wp_admin_bar ) {

		if( current_user_can( 'manage_options' && $this->app->options['status']['toggle'] == 'yes' ) ){

			if( $this->is_locked() ){

				$label = __( 'Locked', 'cs-tmc' );
				$icon = '<span class="ab-icon dashicons-lock"></span>';
				$href = add_query_arg( $this->query_var, '0' );
				$meta = array( 'class' => 'cs-tmc-lock-toggle enabled' );

			} else {

				$label = __( 'Lock website', 'cs-tmc' );
				$icon = '<span class="ab-icon dashicons-unlock"></span>';
				$href = add_query_arg( $this->query_var, '1' );
				$meta = array( 'class' => 'cs-tmc-lock-toggle' );


			}

			$args = array(
				'id'	=> $this->app->prefix( '_lock_toggle' ),
				'title' => sprintf( '%1$s %2$s', $icon, $label ),
				'href'  => $href,
				'meta'  => $meta
			);

			$wp_admin_bar->add_node( $args );

		}

	}

}