<?php
/*
    Plugin Name: Coming Soon TMC
    Description: Simply yet powerful Coming Soon solution.
    Author: TheMasterCut
    Tags: coming, soon
    Version: 1.1.3
    Author: TheMasterCut
    Author URI: https://themastercut.co
    Text Domain: cs-tmc
    License: GPL-2.0+
    License URI: http://www.gnu.org/licenses/gpl-2.0.txt
    Domain Path: /languages/
*/ 

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if( ! class_exists( 'cs_tmc\lib\App' ) ){
    require_once 'lib/App.php'; // core class
}

$app = new cs_tmc\lib\App( __FILE__ );