<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Studio
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function studio_jetpack_setup() {
  add_theme_support( 'infinite-scroll', array(
    'container' => 'posts',
    'footer' => true
  ) );
}
add_action( 'after_setup_theme', 'studio_jetpack_setup' );
