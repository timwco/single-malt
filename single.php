<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Studio
 */

get_header(); ?>

<?php $is_portfolio_post = studio_portfolio_post_is_active( $post->ID ); ?>

<div id="main" <?php if ( $is_portfolio_post ) echo 'class="portfolio-post"'; ?>>

    <?php while ( have_posts() ) : the_post();

      if ( $is_portfolio_post ) :
        get_template_part( 'content', 'portfolio' );
      else :
        get_template_part( 'content' );
      endif;

    endwhile; // end of the loop.

    studio_content_nav( 'nav-below' );

    if ( ! $is_portfolio_post )
      get_sidebar();

get_footer(); ?>
