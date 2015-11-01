<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Studio
 */

get_header(); ?>

<main id="main" class="is-portfolio-permalink">

    <?php while ( have_posts() ) : the_post();

      get_template_part( 'content', 'portfolio' );

    endwhile; // end of the loop.

    studio_content_nav( 'nav-below' );

get_footer(); ?>
