<?php
namespace cs_tmc\lib\Managers;




use cs_tmc\lib\App;
use WP_Customize_Manager;

class WP_Customize_Description_Control extends \WP_Customize_Control {

    /**
     * @var string
     */
    public $type = 'cs_tmc_description';

    /**
     * @var App
     */
    public $app;

    /**
     * WP_Customize_Description_Control constructor.
     *
     * @param App $app
     * @param WP_Customize_Manager $manager
     * @param string $id
     * @param array $args
     */
    public function __construct( $app ,WP_Customize_Manager $manager, $id, array $args = array() ) {

        parent::__construct($manager, $id, $args);

        $this->app = $app;

    }

    public function render_content() {

        printf( '<div class="no-widget-areas-rendered-notice">%1$s</div>', 'Notice' );

    }

}