<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function tppps_posts_slider_wrap_items_with_span_tag($item){
    return '<span class="br-xl">'.$item.'</span>';
}

class PostsSliderShortcode
{
    private $has_active_slide = false;

    public function init()
    {
        add_shortcode('tppps-posts-slider', array($this, 'shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    }

    function shortcode($atts = [], $content = null, $tag = '')
    {
        // normalize attribute keys, lowercase
        $atts = array_change_key_case((array)$atts, CASE_LOWER);

        // override default attributes with user attributes
        $tpp_atts = shortcode_atts([
            'post_type' => 'post',
            'order_by' => 'post_date',
            'order' => 'DESC',
            'id'    => '',
            'class' => '',
            'data' => ''
        ], $atts, $tag);

        $shortcode = '[tppps-slider id="'.$tpp_atts['id'].'" class="r-collapsible '.$tpp_atts['class'].'" data="'.$tpp_atts['data'].'"]';

        $query = new WP_Query(array(
            'post_type' => $tpp_atts['post_type'],
            'order_by' => 'post_date',
            'order' => 'DESC',
            'nopaging' => true,
            'posts_per_page' => -1
        ));
        while ( $query->have_posts() ) {
            $query->the_post();
            global $post;
            $shortcode .= '[tppps-item]';
            //image
            $shortcode .= get_the_post_thumbnail($post, 'full');
            //content
            $shortcode .= '<div class="r-content">';
            //name
            $name = get_field('nombre', $post);
            if ($name == ''){
                $name = get_the_title($post);
            }
            $name = explode("\n", $name);
            $name = array_map('tppps_posts_slider_wrap_items_with_span_tag', $name);
            $name = join('', $name);
            $shortcode .= '<div class="r-name">'.$name.'</div>';
            //title
            $shortcode .= '<div class="r-title">'.get_field('puesto_de_trabajo', $post).'</div>';
            //excerpt
            $excerpt = get_the_excerpt($post);
            $shortcode .= '<div class="r-text">'.$excerpt.'</div>';
            $shortcode .= '<div class="r-text-full d-none">'.strip_tags($post->post_content, '<br> <strong> <i> <b> <u> <a> <p>').'</div>';
            //read more button
            $more_class = '';
            if (str_word_count($excerpt) < 20){
                $more_class = 'class="d-none"';
            }
            $shortcode .= ' <div class="r-more"><a href="javascript:;" '.$more_class.'>'.__('Ver más','tpp-power-slider').'</a></div>';
            //date
            $shortcode .= ' <div class="r-date">'.get_the_date('d/m/Y').'</div>';
            //close content
            $shortcode .= '</div>';
            $shortcode .= '[/tppps-item]';
        }

        $shortcode .= '[/tppps-slider]';

        // start output
        $o = do_shortcode($shortcode);

        // return output
        return $o;
    }
    public function enqueue_styles()
    {
        //wp_enqueue_script('tppps-script', tppps_path('APP_URI', 'assets/js/all.min.js'), array('jquery', 'jquery-migrate'));
    }
}
