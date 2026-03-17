<?php
namespace TMPCODER\Modules;

use Elementor\Utils;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * TMPCODER_Ajax_Search setup
 *
 * @since 3.4.6
 */

 class TMPCODER_Ajax_Search {

    public function __construct() {
        add_action('wp_ajax_tmpcoder_data_fetch' , [$this, 'data_fetch']);
        add_action('wp_ajax_nopriv_tmpcoder_data_fetch',[$this, 'data_fetch']);
    }

    public function data_fetch() {

        if ( !isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'spexo-addons')) {
            return; // Get out of here, the nonce is rotten!
        }

		$all_post_types = [];
        foreach(tmpcoder_get_custom_types_of( 'post', false ) as $key => $value ) {
            array_push($all_post_types, $key);
        }
        
        $tax_query = '';

        if ( isset($_POST['tmpcoder_category']) && sanitize_text_field(wp_unslash($_POST['tmpcoder_category'])) != 'false' && sanitize_text_field(wp_unslash($_POST['tmpcoder_category'])) != '0' && sanitize_text_field(wp_unslash($_POST['tmpcoder_category'])) != '' ) {   
            $tax_query = array(
                array(
                    'taxonomy' => (isset($_POST['tmpcoder_option_post_type']) ? sanitize_text_field(wp_unslash($_POST['tmpcoder_option_post_type'])) : ''),
                    'field'    => 'term_id',
                    'terms'    => ( isset($_POST['tmpcoder_category']) ? sanitize_text_field(wp_unslash($_POST['tmpcoder_category'])) : ''),
                ),
            );

        } else if ( isset($_POST['tmpcoder_category']) && isset($_POST['tmpcoder_query_type']) && sanitize_text_field(wp_unslash($_POST['tmpcoder_category'])) == '0' && sanitize_text_field(wp_unslash($_POST['tmpcoder_query_type'])) != 'all' ) {
            if ( !empty($_POST['tmpcoder_option_post_type']) ) {
                $tax_query = array(
                    array(
                        'taxonomy' => ( isset($_POST['tmpcoder_option_post_type']) ? sanitize_text_field(wp_unslash($_POST['tmpcoder_option_post_type'])) : '' ),
                        'field'    => 'term_id',
                        'terms'    => ( isset($_POST['tmpcoder_category']) ? sanitize_text_field(wp_unslash($_POST['tmpcoder_category'])) : '' ),
                    ),
                );
            } else { 
                // Get the string from the POST data
                $taxonomy_type_string = (isset($_POST['tmpcoder_taxonomy_type']) ? sanitize_text_field(wp_unslash($_POST['tmpcoder_taxonomy_type'])) : '');
            
                // Check if the string contains spaces
                if (strpos($taxonomy_type_string, ' ') !== false) {
                    // Split the string into an array based on spaces
                    $taxonomy_types = explode(' ', $taxonomy_type_string);
                
                    $tax_query = [
                        'relation' => 'OR'
                    ];
                    
                    foreach( $taxonomy_types as $taxonomy_type ) {
                        array_push($tax_query, [
                            'taxonomy' => $taxonomy_type,
                            'operator'    => 'EXISTS'
                        ]);
                    }
                } else {
                    // If there are no spaces, leave it as a single-item array
                    $taxonomy_types = $taxonomy_type_string;

                    $tax_query = array(
                        array(
                            'taxonomy' => (isset($_POST['tmpcoder_taxonomy_type']) ? sanitize_text_field(wp_unslash($_POST['tmpcoder_taxonomy_type'])) : ''),
                            'operator'    => 'EXISTS',
                        ),
                    );
                }
            } 
        }

        // $demo = !tmpcoder_is_availble() ? $all_post_types : array( sanitize_text_field(wp_unslash($_POST['tmpcoder_query_type'])) );
        // print_r($demo);
        // exit();

        $the_query = new \WP_Query( 
            [
                'posts_per_page' => ( isset($_POST['tmpcoder_number_of_results']) ? sanitize_text_field(wp_unslash($_POST['tmpcoder_number_of_results'])) : 0), 
                
                's' => ( isset($_POST['tmpcoder_keyword']) ? sanitize_text_field( wp_unslash($_POST['tmpcoder_keyword']) ) : '' ), 
                
                'post_type' => ( isset($_POST['tmpcoder_query_type']) && sanitize_text_field( wp_unslash($_POST['tmpcoder_query_type'])) === 'all') || !tmpcoder_is_availble() ? $all_post_types : array( sanitize_text_field(wp_unslash($_POST['tmpcoder_query_type'])) ),
                
                'offset' => (isset($_POST['tmpcoder_search_results_offset']) ? sanitize_text_field(wp_unslash($_POST['tmpcoder_search_results_offset'])) : 0),
                
                'meta_query' => isset($_POST['tmpcoder_exclude_without_thumb']) && 'yes' === sanitize_text_field(wp_unslash($_POST['tmpcoder_exclude_without_thumb'])) ? [
                    ['key' => '_thumbnail_id']
                ] : '',
                
                'tax_query' => $tax_query,
                'post_status' => 'publish'
            ]
        );

        // echo "<pre>";
        // print_r($the_query);
        // echo "</pre>";
        // exit();
        
        if( $the_query->have_posts() ) :
            $number_of_queried_posts = $the_query->found_posts;
            $post_count = 0;

                while( $the_query->have_posts() ) : $the_query->the_post();

                ob_start();
                the_post_thumbnail('medium');
                $post_thumb = ob_get_clean();
                ?>

                <li data-number-of-results ="<?php echo esc_attr($the_query->found_posts); ?>" >
                    <?php if ( isset($_POST['tmpcoder_show_ajax_thumbnail']) && 'yes' === sanitize_text_field(wp_unslash($_POST['tmpcoder_show_ajax_thumbnail'])) ) :
                        if ( has_post_thumbnail() ) :
                            echo '<a class="tmpcoder-ajax-img-wrap" target="'. esc_attr(isset($_POST['tmpcoder_ajax_search_link_target']) ? sanitize_text_field(wp_unslash($_POST['tmpcoder_ajax_search_link_target'])) : '') .'" href="'. esc_url(get_the_permalink()).'">'.  wp_kses($post_thumb, array(
                                'img' => array(
                                    'src' => array(),
                                    'alt' => array(),
                                    'class' => array(),
                                    'sizes' => array(),
                                    'srcset' => array(),
                                    'loading' => array(),
                                )
                            )) .'</a>';
                        else :
                            echo '<a class="tmpcoder-ajax-img-wrap" target="'. esc_attr(isset($_POST['tmpcoder_ajax_search_link_target']) ? sanitize_text_field(wp_unslash($_POST['tmpcoder_ajax_search_link_target'])) : '') .'" href='. esc_url( get_the_permalink() ) .'><img src='.esc_url(Utils::get_placeholder_image_src()).'></a>';
                        endif ;
                    endif ; ?>
                    <div class="tmpcoder-ajax-search-content">
                        <a target="<?php echo esc_attr(isset($_POST['tmpcoder_ajax_search_link_target']) ? sanitize_text_field(wp_unslash($_POST['tmpcoder_ajax_search_link_target'])) : '' ) ?>" class="tmpcoder-ajax-title" href="<?php echo esc_url( the_permalink() ); ?>"><?php the_title();?></a>

                        <?php if (isset($_POST['tmpcoder_show_description']) && sanitize_text_field(wp_unslash($_POST['tmpcoder_show_description'])) == 'yes' ) : ?>
                            <p class="tmpcoder-ajax-desc"><a target="<?php echo esc_attr(isset($_POST['tmpcoder_ajax_search_link_target']) ? sanitize_text_field(wp_unslash($_POST['tmpcoder_ajax_search_link_target'])) : '') ?>" href="<?php echo esc_url( the_permalink() ); ?>"><?php echo esc_html(wp_trim_words(get_the_content(), ( isset($_POST['tmpcoder_number_of_words']) ? sanitize_text_field(wp_unslash($_POST['tmpcoder_number_of_words'])) : 10 ) )); ?></a></p>
                        <?php endif; ?>

                        <?php if ( isset($_POST['tmpcoder_show_view_result_btn']) && sanitize_text_field(wp_unslash($_POST['tmpcoder_show_view_result_btn'])) ) : ?>
                            <a target="<?php echo esc_attr( isset($_POST['tmpcoder_ajax_search_link_target']) ? sanitize_text_field(wp_unslash($_POST['tmpcoder_ajax_search_link_target'])) : '') ?>" class="tmpcoder-view-result" href="<?php echo esc_url( the_permalink() ); ?>"><?php echo esc_html( isset($_POST['tmpcoder_view_result_text']) ? sanitize_text_field(wp_unslash($_POST['tmpcoder_view_result_text'])) : '' ); ?></a>
                        <?php endif; ?>
                    </div>
                </li>
                <?php 
                $post_count++;
                endwhile;

            wp_reset_postdata();
            
        else :
            if ( isset($_POST['tmpcoder_search_results_offset']) && 0 < intval($_POST['tmpcoder_search_results_offset'])) {
            } else {
                echo '<p class="tmpcoder-no-results">'. esc_html( isset($_POST['tmpcoder_no_results']) ? sanitize_text_field(wp_unslash($_POST['tmpcoder_no_results'])) : '' ) .'</p>';
            }

        endif;
        
        die();
    }
 }

 new TMPCODER_Ajax_Search();