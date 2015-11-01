<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Studio
 */

get_header();

?>

<?php if ( have_posts() ) : ?>

<div id="main" class="has-sidebar">
  <header class="page-header">
    <h1 class="page-title">
      <?php
        if ( is_category() ) :
          single_cat_title();

        elseif ( is_tag() ) :
          single_tag_title();

        elseif ( is_author() ) :
          /* Queue the first post, that way we know
           * what author we're dealing with (if that is the case).
          */
          the_post();
          printf( __( 'Author: %s', 'studio' ), '<span class="vcard">' . get_the_author() . '</span>' );
          /* Since we called the_post() above, we need to
           * rewind the loop back to the beginning that way
           * we can run the loop properly, in full.
           */
          rewind_posts();

        elseif ( is_day() ) :
          printf( __( 'Day: %s', 'studio' ), '<span>' . get_the_date() . '</span>' );

        elseif ( is_month() ) :
          printf( __( 'Month: %s', 'studio' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

        elseif ( is_year() ) :
          printf( __( 'Year: %s', 'studio' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

        elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
          _e( 'Asides', 'studio' );

        elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
          _e( 'Images', 'studio');

        elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
          _e( 'Videos', 'studio' );

        elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
          _e( 'Quotes', 'studio' );

        elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
          _e( 'Links', 'studio' );

        else :
          _e( 'Archives', 'studio' );

        endif;
      ?>
    </h1>
  </header><!-- .page-header -->

  <div id="posts">

    <?php /* Start the Loop */ ?>
    <?php while ( have_posts() ) : the_post(); ?>

      <?php
        get_template_part( 'content', 'archive' );
      ?>

    <?php endwhile; ?>

    <?php studio_content_nav( 'nav-below' ); ?>

  </div><!-- #posts -->

<?php else : ?>

  <?php get_template_part( 'no-results', 'archive' ); ?>

<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
