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

        $o .= '<div ' . $id_container . ' class="tpp-power-slider slider multiple-items slick-initialized slick-slider slick-dotted ' . $tpp_atts['class'] . '">';
        // $o .= '<div ' . $id_carousel . ' class="carousel slide" data-ride="carousel" data-pause="hover" '.$style.'>';

        // $o .= '<button type="button" data-role="none" class="slick-prev slick-arrow" aria-label="Previous" role="button" style="display: block;">Previous</button>';

        // enclosing tags
        if (!is_null($content)) {
            // $o .= '<div class="carousel-inner">';

            $this->has_active_slide = false;
            $o .= do_shortcode($content);

            // $o .= '</div>';
        }

        // $o .= '<button type="button" data-role="none" class="slick-next slick-arrow" aria-label="Next" role="button" style="display: block;">Next</button>';

        // $o .= '<a class="carousel-control-prev" href="#carousel-id-' . $tpp_atts['id'] . '" role="button" data-slide="prev">';
        // $o .= '<span class="carousel-control-prev-icon fa fa-caret-left" aria-hidden="true"></span>';
        // $o .= '<span class="sr-only">Previous</span>';
        // $o .= '</a>';
        // $o .= '<a class="carousel-control-next" href="#carousel-id-' . $tpp_atts['id'] . '" role="button" data-slide="next">';
        // $o .= '<span class="carousel-control-next-icon fa fa-caret-right" aria-hidden="true"></span>';
        // $o .= '<span class="sr-only">Next</span>';
        // $o .= '</a>';

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

        $active = '';
        // if (!$this->has_active_slide){
        //     $this->has_active_slide = true;
        //     $active = 'active';
        // }

        // enclosing tags
        if (!is_null($content)) {
            $o .= '<div class="slider-item '.$active.'">';

            $o .= $content;

            $o .= '</div>';
        }

        // return output
        return $o;
    }

    public function enqueue_styles()
    {
        wp_enqueue_style('tppps-styles', tppps_path('APP_URI', 'assets/css/styles.min.css'));
        wp_enqueue_script('tppps-script', tppps_path('APP_URI', 'assets/js/script.js'), array('jquery', 'jquery-migrate'));
    }
}
