<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_filter( 'vc_gitem_template_attribute_tpp_power_slider','vc_gitem_template_attribute_tpp_power_slider', 10, 2 );
function vc_gitem_template_attribute_tpp_power_slider( $value, $data ) {
    extract( array_merge( array(
        'post' => null,
        'data' => '',
    ), $data ) );
    $atts_extended = array();
    parse_str( $data, $atts_extended );
    $atts = $atts_extended['atts'];
    // write all your widget code in here using queries etc

    $class = '';
    $vertTitle = '';
    if ($atts_extended['show_vertical_title']){
        $vertTitle = get_field('titulo_vertical', $post->ID);
        $class = 'vert-title';
        $vertTitle = explode("\n", $vertTitle);
        $vertTitle = array_map('tpp_wrap_items_with_span_tag', $vertTitle);
        $vertTitle = join('', $vertTitle);
    }

    $heading = '';
    if ($atts_extended['heading_field'] != ''){
        $heading = get_field($atts_extended['heading_field'], $post->ID);
    }
    $title = get_the_title($post->ID);
    $link = get_permalink($post->ID);
    $external_link = get_field('enlace_al_articulo', $post->ID);
    $excerpt = $post->post_excerpt;
    $terms = get_the_category($post->ID);
    $category = '';
    if ($atts_extended['show_category'] == 'yes' && count($terms) > 0){
        $category = $terms[0]->name;
    }

    $html = '';
    $html .= '<div class="grid-article '.$class.'">';
    $html .= '<div class="grid-article-category"><span>'.$category.'</span></div>';
    if ($vertTitle != ''){
        $html .= '<div class="grid-article-vertical">'.$vertTitle.'</div>';
    }
    $html .= '<div class="grid-article-wrapper">';
    if ($heading != ''){
        $html .= '<div class="grid-article-number">'.$heading.'</div>';
    }
    $html .= '<h2 class="grid-article-title">'.$title.'</h2>';
    $html .= '<div class="grid-article-excerpt">'.$excerpt.'</div>';
    $html .= '<div class="grid-article-actions"><a href="'.$link.'" title="'.$title.'">VER MÁS</a></div>';
    // $html .= '<div class="grid-article-actions"><a href="'.$external_link.'" title="'.$title.'" target="_blank" nofollow>VER MÁS</a></div>';
    $html .= '</div></div>';

    return $html;
}

add_filter( 'vc_grid_item_shortcodes', 'tppps_power_slider_shortcodes' );
function tppps_power_slider_shortcodes( $shortcodes ) {
    $shortcodes['vc_tpp_power_slider'] = array(
        'name' => __( 'Custome grid article', 'tpp-power-slider' ),
        'base' => 'vc_tpp_power_slider',
        'icon' => tppps_path('APP_URI', '/assets/img/logo.png'),
        'category' => __( 'Content', 'tpp-power-slider' ),
        'description' => __( 'Displays a slider with custom design', 'tpp-power-slider' ),
        'post_type' => Vc_Grid_Item_Editor::postType(),
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __('Post type', 'tpp-power-slider'),
                'param_name' => 'post_type',
                'std' => ''
            ),
            array(
                'type' => 'textarea',
                'heading' => __('Item HTML structure', 'tpp-power-slider'),
                'param_name' => 'item_html_structure',
                'std' => '',
                'description' => __( 'HTML of each item: use {{TITLE}}, {{META:FIELD}}, {{URL}}, {{EXCERPT}} to get post info.', 'js_composer' ),
            )
        )
    );
    return $shortcodes;
}

add_shortcode( 'vc_tpp_power_slider', 'tpp_power_slider_render' );
function tpp_power_slider_render($atts){
    $atts = vc_map_get_attributes( 'tpp_power_slider', $atts );


    return '{{ tpp_power_slider:' . http_build_query( (array) $atts ) . ' }}';
}
