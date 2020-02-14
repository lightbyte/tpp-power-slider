<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! function_exists( 'tppps_main' ) ) {
    function tppps_main()
    {
        global $tppps_manager;
        return $tppps_manager::getInstance();
    }
}

if ( ! function_exists( 'tppps_path' ) ) {
    function tppps_path($name, $file = '')
    {
        return tppps_main()->path($name, $file);
    }
}
