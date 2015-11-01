<?php
/**
 * The Header for our theme.
 *
 *
 * @package Studio
 */
?><!DOCTYPE html>
<!--[if IE 9 ]><html <?php language_attributes(); ?> class="ie9 lt-ie10 no-js"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php wp_title( '|', true, 'right' ); ?></title>
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
  <link rel="icon" href="<?php bloginfo('template_directory'); ?>/favicon.ico" type="image/x-icon">
  <link href='https://fonts.googleapis.com/css?family=Nunito:300' rel='stylesheet' type='text/css'>

  <?php wp_head(); ?>
</head>
<?php $has_sidebar = 'has-sidebar';
if ( studio_portfolio_post_is_active( $post->ID ) && is_single() || is_page_template( 'portfolio.php' ) || ! is_dynamic_sidebar() ) :
  $has_sidebar = '';
endif ?>

<body <?php body_class( $has_sidebar ); ?>>

<div id="page">
  <?php do_action( 'before' ); ?>
  <?php $header_image = get_theme_mod( 'custom_sidebar_background' ); ?>
  <?php $sidebar_opacity = get_theme_mod( 'sidebar_opacity' ); ?>
  <?php $logo_image = get_theme_mod( 'custom_logo_image' ); ?>
  <?php $display_tagline = get_theme_mod('tagline_toggle'); ?>


  <header class="site-header <?php if( ! empty( $header_image ) ) { echo 'inverted'; } ?>" style="background-image:url('<?php echo esc_attr( $header_image ); ?>');">
    <div class="site-header-dimmer <?php echo esc_attr( $sidebar_opacity ); ?>" <?php if( empty( $header_image ) ) { echo 'style="background-color:transparent;"'; } ?>>
      <div class="site-branding">
        <div class="navigation-toggle-wrap">
          <div class="navigation-toggle">
            <div class="navigation-icon"></div>
          </div>
        </div>
        <h1 class="site-title">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
            <?php if( ! empty( $logo_image ) ) { ?>
              <img class="logo-image" src="<?php echo esc_attr( $logo_image ) ?>" alt="<?php bloginfo( 'name' ) ?>">
            <?php } else { bloginfo( 'name' ); } ?>
          </a>
        </h1>
        <?php if( $display_tagline ) { ?>
          <h2 class="site-description <?php if( ! empty( $logo_image ) ) echo 'centered'?>"><?php bloginfo( 'description' ); ?></h2>
        <?php } ?>
      </div><!-- .site-branding -->

      <nav class="primary-navigation expanded">
        <?php wp_nav_menu( array(
            'depth' => 3,
            'theme_location' => 'primary',
        ) ); ?>
      </nav>

      <?php $social_icons = array(
        'bandcamp',
        'behance',
        'delicious',
        'deviantart',
        'digg',
        'dribbble',
        'etsy',
        'facebook',
        'flickr',
        'foursquare',
        'github',
        'google-plus',
        'instagram',
        'lastfm',
        'linkedin',
        'myspace',
        'pinboard',
        'pinterest',
        'rdio',
        'soundcloud',
        'spotify',
        'steam',
        'stumbleupon',
        'svpply',
        'twitter',
        'vimeo',
        'youtube',
        );

        $iconCount = 0;
        if ( get_theme_mod( 'skype' ) ) { $iconCount++; }
        foreach ( $social_icons as $icon ) {
          if ( get_theme_mod( $icon ) ) { $iconCount++; }
        }

        if ($iconCount > 0 || get_theme_mod( 'email' ) != '' || get_theme_mod( 'phone' ) != '') : ?>

        <div class="contact">

          <?php if($iconCount > 0) { ?>
          <div class="social-icons clearfix">
            <?php if ( get_theme_mod( 'skype' ) ) : ?>
              <a class="skype" href="<?php echo esc_url( '"skype:" . get_theme_mod("skype")' ); ?>?userinfo" target="_blank"><?php _e( 'Skype', 'studio' ); ?></a>
            <?php endif; foreach ( $social_icons as $icon ) {
            if ( get_theme_mod( $icon ) ) : ?>
              <a class="<?php echo esc_html( $icon ); ?>" href="<?php echo esc_url( get_theme_mod( $icon ) ); ?>" target="_blank"><?php echo esc_html( $icon ); ?></a>
            <?php endif; } ?>
          </div>
          <?php } ?>

          <?php if ( get_theme_mod( 'email' ) != '' ) : ?>
            <div class="contact-field email">
              <span class="contact-field-label"><?php _e( 'Email:', 'studio' ); ?></span> <a class="email-link <?php if( ! empty( $header_image ) ) { echo 'inverted'; } ?>" href="mailto:<?php echo sanitize_email( get_theme_mod('email') ); ?>"><?php echo esc_html( get_theme_mod('email') ); ?></a>
            </div>
          <?php endif; ?>

          <?php if ( get_theme_mod( 'phone' ) != '' ) : ?>
            <div class="contact-field phone">
              <span class="contact-field-label"><?php _e( 'Phone:', 'studio' ); ?></span> <?php echo esc_html( get_theme_mod('phone') ); ?>
            </div>
          <?php endif; ?>

          </div>

          <?php endif; ?>

    </div><!-- .site-header-dimmer -->
  </header>

  <div id="content" class="site-content <?php if ( studio_portfolio_post_is_active( $post->ID ) && is_single() ) : ?>portfolio<?php endif; ?>">
