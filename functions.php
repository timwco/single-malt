<?php
/**
 * Studio functions and definitions
 *
 * @package Studio
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
  $content_width = 700; /* pixels */

if ( ! function_exists( 'studio_setup' ) ) :
  /**
   * Sets up theme defaults and registers support for various WordPress features.
   *
   * Note that this function is hooked into the after_setup_theme hook, which runs
   * before the init hook. The init hook is too late for some features, such as indicating
   * support post thumbnails.
   */
  function studio_setup() {

    load_theme_textdomain( 'studio', get_template_directory() . '/languages' );

    add_theme_support( 'automatic-feed-links' );

    /**
     * Enable support for Post Thumbnails on posts and pages
     *
     * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
     */
    add_theme_support( 'post-thumbnails' );

    if ( function_exists( 'add_image_size' ) ) {
      add_image_size( 'portfolio', 600, 9999 );
    }

    /**
     * This theme uses wp_nav_menu() in one location.
     */
    register_nav_menus( array(
      'primary' => __( 'Primary Menu', 'studio' ),
    ) );

    add_theme_support( 'custom-background', apply_filters( 'studio_custom_background_args', array(
      'default-color' => 'ffffff',
      'default-image' => '',
   ) ) );
  }
endif; // studio_setup
add_action( 'after_setup_theme', 'studio_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function studio_widgets_init() {
  register_sidebar( array(
    'name'          => __( 'Sidebar', 'studio' ),
    'id'            => 'sidebar-1',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h1 class="widget-title">',
    'after_title'   => '</h1>',
  ) );
}
add_action( 'widgets_init', 'studio_widgets_init' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function studio_remove_share() {
  if ( function_exists( 'sharing_display' ) ) {
    remove_filter( 'the_content', 'sharing_display',19 );
    remove_filter( 'the_excerpt', 'sharing_display',19 );
  }
  if ( class_exists( 'Jetpack_Likes' ) ) {
    remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
  }
}
add_action( 'loop_start', 'studio_remove_share' );

/**
 * Enqueue scripts and styles
 */
function studio_scripts() {

  wp_register_script( 'modernizr', get_template_directory_uri() . '/javascripts/modernizr.custom.84485.js', array(), '', false );
  wp_enqueue_script( 'modernizr' );

  wp_enqueue_script( 'studio-navigation', get_template_directory_uri() . '/javascripts/navigation.js', array(), '20120206', true );

  wp_enqueue_script( 'studio-skip-link-focus-fix', get_template_directory_uri() . '/javascripts/skip-link-focus-fix.js', array(), '20130115', true );

  wp_enqueue_script( 'studio-fit-text', get_template_directory_uri() . '/javascripts/FitText.js', array(), '20140420', true );

  wp_enqueue_script( 'studio-pxu', get_template_directory_uri() . '/javascripts/_pxu.js', array( 'jquery' ), '', true );

  wp_register_script( 'scripts', get_template_directory_uri() . '/javascripts/theme.js', array( 'jquery' ), '', true );
  wp_enqueue_script( 'scripts' );

  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }

  if ( is_singular() && wp_attachment_is_image() ) {
    wp_enqueue_script( 'studio-keyboard-image-navigation', get_template_directory_uri() . '/javascripts/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
  }

  wp_enqueue_style( 'studio-lato' );
}
add_action( 'wp_enqueue_scripts', 'studio_scripts' );

/**
 * Portfolio
 */
require get_template_directory() . '/inc/portfolio.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( file_exists( get_template_directory() . '/inc/jetpack.php' ) ) {
  require get_template_directory() . '/inc/jetpack.php';
}

// Add full sized image shortcode
function full_image_shortcode_init($atts, $content = null) {
  $return_string = '<div class="full-image">'.$content.'</div>';
  return $return_string;
}
add_shortcode('full-image', 'full_image_shortcode_init');

// Add Code Short Code
function code_block_shortcode_init($atts, $content = null) {
  $pattern = "/<p[^>]*><\\/p[^>]*>/";
  $stripped_content = preg_replace($pattern, '', $content);
  $return_string = '<pre>'.$stripped_content.'</pre>';
  return $return_string;
}
add_shortcode('code', 'code_block_shortcode_init');

function studio_new_excerpt_more( $more ) {
  return '...';
}
add_filter('excerpt_more', 'studio_new_excerpt_more');

function studio_custom_excerpt_length( $length ) {
        return 22;
}
add_filter( 'excerpt_length', 'studio_custom_excerpt_length', 999 );

/**
 * Register Google Fonts
 */
function studio_google_fonts() {
  $protocol = is_ssl() ? 'https' : 'http';

  /* httptranslators: If there are characters in your language that are not supported
    by any of the following fonts, translate this to 'off'. Do not translate into your own language. */

  if ( 'off' !== _x( 'on', 'Lato font: on or off', 'studio' ) ) {
    wp_register_style( 'studio-lato', "$protocol://fonts.googleapis.com/css?family=Lato:300,400,700,400italic,700italic,900" );
  }

  if ( 'off' !== _x( 'on', 'Libre Baskerville font: on or off', 'studio' ) ) {
    wp_register_style( 'studio-libre-baskerville', "$protocol://fonts.googleapis.com/css?family=Libre+Baskerville" );
  }

  wp_enqueue_style( 'studio-lato' );
  wp_enqueue_style( 'studio-libre-baskerville' );
}
add_action( 'init', 'studio_google_fonts' );
