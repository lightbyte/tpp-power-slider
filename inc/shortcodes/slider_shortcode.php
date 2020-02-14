<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class SliderShortcode
{
    private $has_active_slide = false;

    public function init()
    {
        add_shortcode('tppps-item', array($this, 'shortcode_item'));
        add_shortcode('tppps-slider', array($this, 'shortcode_slider'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    }

    function shortcode_slider($atts = [], $content = null, $tag = '')
    {
        // normalize attribute keys, lowercase
        $atts = array_change_key_case((array)$atts, CASE_LOWER);

        // override default attributes with user attributes
        $tpp_atts = shortcode_atts([
            'id'    => '',
            'class' => '',
            'width' => ''//'720px'
        ], $atts, $tag);

        $style = '';
        if ($tpp_atts['width'] != ''){
            $style = 'style="max-width:' . $tpp_atts['width'] . '"';
        }

        $id_container = '';
        $id_carousel = '';
        if ($tpp_atts['id'] != ''){
            $id_container = 'id="'.$tpp_atts['id'].'"';
            $id_carousel = 'id="carousel-id-'.$tpp_atts['id'].'"';
        }

        // start output
        $o = '';

        $o .= '<div ' . $id_container . ' class="tpp-power-slider ' . $tpp_atts['class'] . '">';

        // enclosing tags
        if (!is_null($content)) {
            // $o .= '<div class="carousel-inner">';

            $this->has_active_slide = false;
            $o .= do_shortcode($content);

            // $o .= '</div>';
        }

        // end box
        $o .= '</div>';
        // $o .= '</div>';

        // return output
        return $o;
    }

    public function shortcode_item($atts = [], $content = null, $tag = '')
    {
        // normalize attribute keys, lowercase
        $atts = array_change_key_case((array)$atts, CASE_LOWER);

        // override default attributes with user attributes
        $tpp_atts = shortcode_atts([
            'class' => ''
        ], $atts, $tag);

        // start output
        $o = '';

        // enclosing tags
        if (!is_null($content)) {
            $o .= '<div class="slider-item">';

            $o .= do_shortcode($content);

            $o .= '</div>';
        }

        // return output
        return $o;
    }

    public function enqueue_styles()
    {
        wp_enqueue_style('tppps-styles', tppps_path('APP_URI', 'assets/css/styles.min.css'));
        wp_enqueue_script('tppps-script', tppps_path('APP_URI', 'assets/js/all.min.js'), array('jquery', 'jquery-migrate'));
    }
}
