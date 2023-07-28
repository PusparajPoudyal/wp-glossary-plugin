<?php
/*
Plugin Name: My Glossary Plugin
Description: A comprehensive glossary plugin with search functionality.
Version: 1.0.0
Author: Your Name
*/

// Register the glossary term custom post type
// Register the glossary term custom post type
function my_glossary_register_post_type()
{
    $labels = array(
        'name'               => 'Glossary Terms',
        'singular_name'      => 'Glossary Term',
        'menu_name'          => 'Glossary',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Glossary Term',
        'edit_item'          => 'Edit Glossary Term',
        'new_item'           => 'New Glossary Term',
        'view_item'          => 'View Glossary Term',
        'search_items'       => 'Search Glossary Terms',
        'not_found'          => 'No glossary terms found',
        'not_found_in_trash' => 'No glossary terms found in Trash',
        'parent_item_colon'  => 'Parent Glossary Term:'
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'glossary-term'),
        'capability_type'     => 'post',
        'has_archive'         => false,
        'hierarchical'        => false,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-book-alt',
        'supports'            => array('title', 'editor'),
        'show_in_admin_bar'   => true, // Add "View" button in admin bar
        'publicly_queryable'  => true, // Enable public query
        'show_in_rest'        => true, // Enable Gutenberg editor
    );

    register_post_type('glossary-term', $args);
}
add_action('init', 'my_glossary_register_post_type');


// Display glossary terms on the front end
function my_glossary_display_terms()
{
    $args = array(
        'post_type'      => 'glossary-term',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC'
    );

    $terms = new WP_Query($args);

    ob_start();

    echo '<div class="glossary-main-container">';
    echo '<div class="glossary-search">';
    echo '<input type="text" id="glossary-search-input" placeholder="Search Glossary..." />';
    echo '</div>';

    if ($terms->have_posts()) {
        $current_letter = '';
        echo '<div class="glossary-terms-container">';
        echo '<ul class="glossary-terms-list">';

        while ($terms->have_posts()) {
            $terms->the_post();
            $title = get_the_title();
            $first_letter = strtoupper(substr($title, 0, 1));

            if ($current_letter !== $first_letter) {
                if ($current_letter !== '') {
                    echo '</ul>';
                    echo '</div>';
                }

                if ($first_letter !== '') {
                    echo '<div class="index-item">';
                    echo '<div class="gloss-letter">' . $first_letter . '</div>';
                    echo '<ul class="glossary-terms-sublist">';
                }

                $current_letter = $first_letter;
            }

            echo '<li><a href="' . get_permalink() . '">' . $title . '</a></li>';
        }

        echo '</ul>';
        echo '</div>';
        echo '</div>';

        wp_reset_postdata();
    } else {
        echo '<p class="no-terms-message">No glossary terms found.</p>';
    }

    echo '</div>';

    return ob_get_clean();
}

// Enqueue scripts and styles
function my_glossary_enqueue_scripts()
{
    wp_enqueue_script('my-glossary-scripts', plugin_dir_url(__FILE__) . 'js/scripts.js', array('jquery'), '1.0.0', true);
    wp_enqueue_style('my-glossary-styles', plugin_dir_url(__FILE__) . 'css/styles.css', array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'my_glossary_enqueue_scripts');

// Shortcode for displaying glossary terms
function my_glossary_terms_shortcode()
{
    ob_start();
    echo my_glossary_display_terms();
    return ob_get_clean();
}
add_shortcode('glossary_terms', 'my_glossary_terms_shortcode');
