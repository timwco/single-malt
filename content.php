<?php
/**
 * @package Studio
 */
?>

<?php $featured_image = studio_get_featured_image(); ?>

<?php $classes = array('clearfix', 'regular-post');
if ( $featured_image )
  $classes[] = 'has-image';

if ( is_search() || is_archive() )
  $classes[] = 'archive-item'; ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

  <?php if ( $featured_image && ( is_search() || is_archive() ) ) { ?>
    <div class="featured-image-wrap" style="background-image: url('<?php echo $featured_image; ?>')"></div>
  <?php } ?>

  <header class="post-header">
    <h1 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
  </header><!-- .post-header -->

  <?php if ( is_search() || is_archive() ) : ?>
    <div class="post-summary">
    <?php
      if ( function_exists( 'sharing_display' ) )
        remove_filter( 'the_excerpt', 'sharing_display', 19 );

      the_excerpt();
    ?>

    <a href="<?php the_permalink() ?>" class="continue-link"><?php _e( 'Read more', 'studio' ); ?></a>

    <?php if ( 'post' == get_post_type() ) : ?>
    <div class="post-meta-wrap clearfix">
      <div class="post-meta">
        <?php studio_posted_on(); ?>
      </div><!-- .post-meta -->
    </div>
    <?php endif; ?>

    </div><!-- .post-summary -->
  <?php else : ?>
    <div class="post-content">

      <?php
        if ( ! is_attachment() ) :

          $featured_image = '';
          if ( wp_get_attachment_url( get_post_thumbnail_id() ) ) :
            $featured_image = wp_get_attachment_url( get_post_thumbnail_id() );
            echo '<img class="featured-image" src="' . $featured_image . '">';
          endif;

        endif;

        the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'studio' ) );
        wp_link_pages( array(
          'before' => '<div class="page-links">' . __( 'Pages:', 'studio' ),
          'after'  => '</div>',
        ) );
      ?>
    </div><!-- .post-content -->

    <?php if ( 'post' == get_post_type() && is_single() && has_tag() ) : ?>
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
      <?php if ( 'post' == get_post_type() ) : ?>
      <div class="post-meta">
        <?php studio_posted_on(); ?>
      </div><!-- .post-meta -->
      <?php endif; ?>

      <?php studio_sharing_and_likes(); ?>
    </div>

    <?php // If comments are open or we have at least one comment, load up the comment template
      if ( comments_open() || '0' != get_comments_number() )
        comments_template();
    ?>

  <?php endif; ?>
  <?php edit_post_link( __( 'Edit', 'studio' ), '<span class="edit-link">', '</span>' ); ?>
</article>

