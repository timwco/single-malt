<?php
/**
 * @package Studio
 */
?>

<?php

  // setup 'Filed under:'
  $portfolios = studio_portfolio_page_ids( $post->ID );
  $portfolioTitles = '';
  foreach ($portfolios as $index => $portfolioID) {
    $newTitle = "<a href='" . get_permalink($portfolioID) . "'>" . get_the_title($portfolioID) . "</a>";
    if( $index > 0 ) {
      $newTitle = ", $newTitle";
    }
    $portfolioTitles .= $newTitle;
  }

  $usevideo = get_post_meta($post->ID, 'studio_portfolio_featurevideo', true);
?>


<article id="post-<?php the_ID(); ?>" <?php post_class( 'portfolio-post' . ($usevideo == 1 ? ' use-video' : '') ); ?> <?php if ( is_single() ) echo 'style="display:block";'; echo $usevideo; ?>>
  <header class="post-header">
    <?php if ( ! is_single() ) : ?>
      <div class="permalink-navigation">
        <span class="prev"><?php _e( 'Previous', 'studio' ); ?></span>
        <span class="next"><?php _e( 'Next Post', 'studio' ); ?></span>
      </div>
    <?php endif; ?>
    <h1 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

    <?php echo '<a data-featured-image="' . studio_get_featured_image() . '"></a>'; ?>
  </header><!-- .post-header -->

  <div class="post-bottom">
    <div class="post-summary post-content">
      <?php $more = 0; ?>
      <?php the_content( __( 'Continue Reading', 'studio' ) ); ?>
    </div><!-- .post-summary -->

    <?php if ( ! is_single() ) : ?>
      <div class="static-permalink">
        <a href="<?php the_permalink(); ?>"><?php _e( 'Permalink', 'studio' ); ?></a>
        <?php if($portfolios) : ?>
          <div class="filed-under">
          <?php echo _e( 'Filed under: ', 'studio' ) . $portfolioTitles; ?>
          </div>
        <?php endif; ?>
        <?php edit_post_link( __( 'Edit', 'studio' ), '<div class="edit-link-wrap">', '</div>' ); ?>
      </div>
    <?php endif; ?>

    <?php if ( is_single() ) : ?>

    <?php if ( has_tag() ) : ?>
      <footer class="post-meta">
        <span class="tags-links">
        <?php
          echo '<p><span>';
          _e( 'Tags: ', 'studio' );
          echo '</span>' . get_the_tag_list('',', ','') . '</p>';
        ?>
        </span>

      </footer><!-- .post-meta -->
    <?php endif; ?>

    <div class="post-meta-wrap clearfix">
      <div class="post-meta">
      <?php if($portfolios) {
        echo _e( 'Filed under: ', 'studio' ) . $portfolioTitles;
      } ?>
      </div>
      <?php studio_sharing_and_likes(); ?>
    </div>
    <?php
      edit_post_link( __( 'Edit', 'studio' ), '<span class="edit-link">', '</span>' );

      // If comments are open or we have at least one comment, load up the comment template
      if ( comments_open() || '0' != get_comments_number() )
        comments_template();
      endif;
    ?>


  </div><!-- .post-bottom -->

</article>

