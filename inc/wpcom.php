<?php /**
 * WordPress.com-specific functions and definitions
 * This file is centrally included from `wp-content/mu-plugins/wpcom-
 theme-compat.php`.
 *
 * @package Studio
 */


/*
 * De-queue Google fonts if custom fonts are being used instead
 */
function studio_dequeue_fonts() {
  if ( class_exists( 'TypekitData' ) && class_exists( 'CustomDesign' ) && CustomDesign::is_upgrade_active() ) {
    $customfonts = TypekitData::get( 'families' );
    if ( $customfonts && $customfonts['site-title']['id'] && $customfonts['headings']['id'] && $customfonts['body-text']['id'] ) {
      wp_dequeue_style( 'studio-lato' );
      wp_dequeue_style( 'studio-libre-baskerville' );
    }
  }
}

add_action( 'wp_enqueue_scripts', 'studio_dequeue_fonts' );

function studio_theme_colors() {
  global $themecolors;

  /**
   * Set a default theme color array for WP.com.
   * Adjust these values to match your theme
   *
   * @global array $themecolors
   */
  if ( ! isset( $themecolors ) ) :
    $themecolors = array(
        'bg' => 'ffffff',
        'border' => 'd9d9d9',
        'text' => '000000',
        'link' => '000000',
        'url' => '000000',
        );
  endif;
}
add_action( 'after_setup_theme', 'studio_theme_colors' );

/**
 * Adds support for WP.com print styles
 */
function studio_print_styles() {
  add_theme_support( 'print-style' );
}
add_action( 'after_setup_theme', 'studio_print_styles' );

// Disable sharing and likes
function studio_mute_post_flair() {
  post_flair_mute();
}
add_action( 'init', 'studio_mute_post_flair' );
