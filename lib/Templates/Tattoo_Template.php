<?php
namespace cs_tmc\lib\Templates;




class Tattoo_Template extends Classes\Template {

	function display() {
		?>

		<!doctype html>
		<html <?php language_attributes(); ?> class="no-js">
			<head>
				<meta charset="<?php bloginfo('charset'); ?>">
				<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' : '; } ?><?php bloginfo('name'); ?></title>

				<link href="//www.google-analytics.com" rel="dns-prefetch">
				<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
				<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
				<link href="https://fonts.googleapis.com/css?family=Roboto:400,700,900&amp;subset=latin-ext" rel="stylesheet">

                <?php $this->print_fonts_embed_code(); ?>

                <script
				  src="https://code.jquery.com/jquery-3.2.1.min.js"
				  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
				  crossorigin="anonymous"></script>

				<?php $this->print_subscribe_javascript();?>

				<?php $this->print_favicon();?>

				<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<meta name="description" content="<?php bloginfo('description'); ?>">

				<style>
					
					body,
					html {
						min-height: 100%;
					}

					.background-video {
						position: fixed;
  						top: 0; right: 0; bottom: 0; left: 0;
  						z-index: 1;
  						pointer-events: none;
					}

					.background-video iframe {
						height: 56.25vw;
						width: 180vh;
						left: 50%;
						top: 50%;
						min-height: 100%;
						min-width: 100%;
						transform: translate(-50%, -50%);
						position: absolute;
					}

					.main-div {
						position: relative;
						z-index: 10;
					}

					.header_text {
                        font-family: <?php $this->print_font_family( $this->app->options['fonts']['header_text_font'] );?>;
						font-size: 55px;
						font-weight: 900;
					}

					.message_text {
                        font-family: <?php $this->print_font_family( $this->app->options['fonts']['message_text_font'] );?>;
						font-size: 18px;
					}

					.logo_image {
						display: inline-block;
						max-width: 100%;
						height: auto;
					}

					.logo_text {
                        font-family: <?php $this->print_font_family( $this->app->options['fonts']['logo_text_font'] );?>;
						font-weight: 600;
						font-size: 1.87em;
						margin: 1em 0;
					}

					.subscription_form {
						padding: 10px;
						background-color: white;
						border-radius: 5px;
					}

					.subscription_form .form-control {
						padding: 5px 20px;
						font-size: 1em;
						border-color: transparent;
					}

					.subscription_form .btn {
                        font-family: <?php $this->print_font_family( $this->app->options['fonts']['button_text_font'] );?>;
						padding-left: 25px;
						padding-right: 25px;
						padding-top: 0;
						padding-bottom: 0;
						background-color: black;
						border-color: transparent;
						border-radius: 5px !important;
					}

					.subscription_form:not(.done) .thank-you-message {
						display: none;
					}

					.subscription_form .thank-you-message {
                        font-family: <?php $this->print_font_family( $this->app->options['fonts']['sub_message_text_font'] );?>;
						display: block;
						margin-top: 1em;
						font-size: 0.8em;
						letter-spacing: 0.15em;
						background-color: black;
						color: white;
						padding: 0.8em 1.2em;
						position: relative;
					}

					.social_buttons {
						list-style: none;
						padding: 0;
						margin: 0;
					}

					.social_buttons li {
						margin: 0;
						padding: 5px;
						display: inline-block;
					}

					.social_buttons__link {
						display: inline-block;
						color: <?php echo $this->get_social_color_primary();?>;
						background-color: <?php echo $this->get_social_color_secondary();?>;
						border-radius: 5px;
						text-align: center;
						line-height: 30px;
						width: 30px;
						border-style: solid;
						border-width: 1px;
						border-color: <?php echo $this->get_social_color_secondary();?>;
						transition: all 500ms;
					}

					.social_buttons__link:hover {
						background: <?php echo $this->get_social_color_primary();?>;
						color: <?php echo $this->get_social_color_secondary();?>;
						border-color: <?php echo $this->get_social_color_secondary();?>;
					}

					.social_buttons__link.facebook > i:before {
						content: '\f09a';
					}

					.social_buttons__link.twitter > i:before {
						content: '\f099';
					}

					.social_buttons__link.youtube > i:before {
						content: '\f16a';
					}

					.social_buttons__link.vimeo > i:before {
						content: '\f27d';
					}

					.social_buttons__link.googleplus > i:before {
						content: '\f2b3';
					}

					.social_buttons__link.instagram > i:before {
						content: '\f16d';
					}

					.social_buttons__link.linkedin > i:before {
						content: '\f0e1';
					}

					.social_buttons__link.pinterest > i:before {
						content: '\f231';
					}

					.social_buttons__link.snapchat > i:before {
						content: '\f2ab';
					}

					.social_buttons__link._500px > i:before {
						content: '\f26e';
					}

					.social_buttons__link.medium > i:before {
						content: '\f23a';
					}

					.footer_note {
                        font-family: <?php $this->print_font_family( $this->app->options['fonts']['footer_note_font'] );?>;
						font-size: 16px;
					}

                    .phone_number {
                        font-family: <?php $this->print_font_family( $this->app->options['fonts']['phone_number_font'] );?>;
                        color:<?php $this->print_phone_number_color();?>;
                        font-size: 1.6em;
                        font-weight:900;
                    }



					.twitter_widget_timeline {
						position: relative;
					}

					.twitter_widget_timeline:after {
						position: absolute;
						top: 0;
						right: 0;
						bottom: 0;
						left: 0;
						content: '';
						display: block;
						background-color: <?php $this->print_twitter_widget_bg();?>;
						opacity: <?php $this->print_twitter_widget_bg_opacity();?>;
						border-radius: 10px;
					}

					.twitter_widget_timeline .twitter-timeline-wrapper {
						position: relative;
						z-index: 10;
					}




					.software_stores {
						
					}

					.software_stores .software_stores__link {
						display: inline-block;
						margin: 10px;
					}

				</style>

			</head>
			<?php $this->print_body_tag();?>

				<?php $this->print_conversion_tracking();?>
				<?php $this->print_background_video();?>

				<div class="main-div">

					<div class="container">

						<div class="row mt-4">
							
							<div class="col-12 col-md-6 text-left">

								<?php $this->print_logo();?>

							</div>
							<div class="col-12 col-md-6 text-left text-md-right">

								<span class="phone_number">
                                    <?php $this->print_phone_number();?>
                                </span>


							</div>

						</div>

						<div class="row" style="margin-top:10vw;">

							<div class="col-12 col-md-7 push-md-5">

								<div class="text-right mr-0 ml-auto" style="max-width:500px;">
									<?php $this->print_header_text();?>
								</div>

								<div class="text-right mx-auto m-4">
									<?php $this->print_message_text();?>
								</div>

								<div class="py-4">
									<?php $this->print_subscription_form();?>
								</div>

							</div>

							<div class="col-12 col-md-5 pull-md-7">
								
								<?php $this->print_twitter_widget();?> 

							</div>

						</div>

						<div class="my-4 text-center text-md-right">
							
							<?php $this->print_store_links();?>

						</div>

						<div class="py-4 mt-2">
								<div class="clearfix text-center text-md-left" style="margin-top:10%;">
									<div>
										<?php $this->print_social_buttons();?>
									</div>
									<div class="mt-2">
										<?php $this->print_footer_note();?>
									</div>
								</div>
							</div>

						</div>

				</div>

				<?php wp_footer(); ?>

				<?php $this->print_analitics();?>

			</body>
		</html>

		<?php

	}

}

?>