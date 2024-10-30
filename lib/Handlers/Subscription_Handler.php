<?php
namespace cs_tmc\lib\Handlers;

use cs_tmc\lib\External\GetResponse\GetResponse;
use cs_tmc\lib\External\MailChimp\MailChimp;
use cs_tmc\lib\External\FreshMail\FreshMail;




class Subscription_Handler {

	private $app;




	function __construct( $app ){

		$this->app = $app;

	}

	function subscribe_action() {

		$email = sanitize_text_field( $_POST['email'] );

		if( is_email( $email ) ){

			switch( $this->app->options['newsletter']['type'] ){

				case 'email':			// EMAIL

					$html = sprintf( '<body><h2>%1$s</h2><p>%2$s</p></body>', $email, __( 'This email address has been leaved for further notification about your site status.', $this->app->txtdomain ) );

					$to 		= $this->get_admin_email();
					$subject 	= __( 'Coming Soon TMC - subscription request', $this->app->txtdomain );
					$body 		= $html;
					$headers 	= array( 'Content-Type: text/html; charset=UTF-8' );
					 
					wp_mail( $to, $subject, $body, $headers );

					break;

				case 'mailchimp':		// MAILCHIMP

					$api_key 	= $this->app->options['newsletter']['mailchimp_key'];
					$list_id 	= $this->app->options['newsletter']['mailchimp_list'];

					if( ! empty( $api_key ) && ! empty( $list_id ) ){

						$MailChimp = new MailChimp( $api_key );

						$response = $MailChimp->post("lists/$list_id/members", array(
							'email_address' => $email,
							'status'        => 'subscribed',
						) );

						wp_die();

					} else {

						wp_die( __( 'Wrong MailChimp configuration', $this->app->txtdomain ) );

					}

					break;

				case 'getresponse':		// GETRESPONSE

					$api_key 	= $this->app->options['newsletter']['getresponse_key'];
					$campaign 	= $this->app->options['newsletter']['getresponse_campaign'];

					if( ! empty( $api_key ) && ! empty( $campaign ) ){

						$GetResponse = new GetResponse( $api_key );

						$response = $GetResponse->addContact(array(
						    'email'             => $email,
						    'campaign'          => array( 'campaignId' => $campaign )
						) );

						wp_die();

					} else {

						wp_die( __( 'Wrong GetResponse configuration', $this->app->txtdomain ) );

					}

					break;

				case 'freshmail':		// FRESHMAIL

					$api_key 		= $this->app->options['newsletter']['freshmail_key'];
					$api_secret 	= $this->app->options['newsletter']['freshmail_secret'];
					$list_key 		= $this->app->options['newsletter']['freshmail_list'];

					if( ! empty( $api_key ) && ! empty( $api_secret ) && ! empty( $list_key ) ){

						$FreshMail = new FreshMail;

						$FreshMail->setApiKey( $api_key );
						$FreshMail->setApiSecret( $api_secret );

						$response = $FreshMail->doRequest('subscriber/add', array(
							'email' => $email,
						    'list'  => $list_key,
						    'state'   => 2,
						    'confirm' => 1
						) );

						wp_die();

					} else {

						wp_die( __( 'Wrong GetResponse configuration', $this->app->txtdomain ) );

					}

					break;

			}

		} else {

			wp_die( 'This is not email', $this->app->txtdomain );

		}

	}

	function get_admin_email() {

		if( ! empty( $this->app->options['newsletter']['email'] ) ){

			return $this->app->options['newsletter']['email'];

		} else {

			return get_option( 'admin_email' );

		}

	}

}