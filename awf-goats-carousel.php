<?php
/**
 * Plugin Name: Ardor Wood Farm Goats Carousel
 * Version: 1.0
 * Description: Creates a custom post type, called Goats, with a custom taxonomy for the goats. Collectively goats in a taxonomy build a carousel based on their shared taxonomy.
 * Plugin URI: https://github.com/leroyrosales/awf-goats-carousel
 * Author: Recspec
 * Text Domain: awf-goats-carousel
 */

if( !defined( 'ABSPATH' ) || !class_exists( 'ArdorWoodFarmGoats') ) return;

Class ArdorWoodFarmGoats {

  private static $instance = null;

  public function __construct() {

    add_action( 'init', [$this, 'initialize'], 0, 0 );
    add_shortcode( 'awf_goats', [$this, 'registers_awf_goats_shortcode'] );

  }

  public static function instance() {
    self::$instance ?? self::$instance;

    return self::$instance = new ArdorWoodFarmGoats();
  }

  public static function initialize() {
    // Bail early if called directly from functions.php or plugin file.
    if( !did_action( 'plugins_loaded' ) ) return;

    $this->register_goats_cpt();

  }

  // Register DDCE Profiles post type
  private static function register_goats_cpt() {
    register_post_type( 'awf-goats', [
      'labels' => [
        'name' => __( 'Goats', 'awf-goats' ),
        'singular_name' => __( 'Goat', 'awf-goats' ),
        'add_new_item'          => __( 'Add New Goat', 'awf-goats' ),
      ],
      'public' => false,
      'publicly_queryable' => true,
      'show_ui' => true,
      'show_in_rest' => false,
      'rest_base' => '',
      'rest_controller_class' => 'WP_REST_Posts_Controller',
      'has_archive' => false,
      'show_in_menu' => true,
      'show_in_nav_menus' => false,
      'delete_with_user' => false,
      'exclude_from_search' => false,
      'capability_type' => 'post',
      'map_meta_cap' => true,
      'hierarchical' => false,
      'rewrite' => false,
      'query_var' => true,
      'menu_position' => 20,
      'menu_icon' => 'dashicons-pets',
      'supports' => [ 'title', 'editor', 'thumbnail' ],
      'taxonomies' => [ 'category' ],
      'show_in_graphql' => false,
    ]);
  }

  // Add Shortcode
  public static function registers_awf_goats_shortcode( $atts ) {

    ob_start();

    // Attributes
    $atts = shortcode_atts(
      array(
        'category' => '',
      ),
      $atts,
      'awf_goats'
    );

    $afw_goat_loop = new WP_Query([
      'post_type'     => 'awf-goats',
      'category_name' => sanitize_text_field( $atts['category'] )
    ]);

    ?>

    <ul class="awf-goats-loop">
      <?php while ( $afw_goat_loop->have_posts() ) : $afw_goat_loop->the_post(); ?>
        <li><?php the_post_thumbnail('large'); ?></li>
      <?php endwhile; ?>
    </ul>

    <?php wp_reset_postdata();

    return ob_get_clean();
  }


} // End ArdorWoodFarmGoats class

ArdorWoodFarmGoats::instance();
