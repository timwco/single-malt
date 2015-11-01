<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Studio
 */

if ( ! function_exists( 'studio_sharing_and_likes' ) ) :
  function studio_sharing_and_likes() { ?>
    <div class="sharedaddy-wrap">
      <div class="share-button"></div>
      <div class="like-button"></div>
    <?php if ( function_exists( 'sharing_display' ) ) { ?>
        <div class="share-wrap">
          <?php sharing_display( '', true ); ?>
          <span class="arrow"></span>
        </div>
    <?php }

    if ( class_exists( 'Jetpack_Likes' ) ) {
      $custom_likes = new Jetpack_Likes; ?>
      <div class="likes-wrap">
        <?php echo $custom_likes->post_likes( '' ); ?>
        <span class="arrow"></span>
      </div>
    <?php } ?>
    </div>
<?php }
endif;

if ( ! function_exists( 'studio_get_featured_image' ) ) :
function studio_get_featured_image() {
  global $post;
  $featured_image = '';
  if ( wp_get_attachment_url( get_post_thumbnail_id() ) ) {
    $featured_image = wp_get_attachment_url( get_post_thumbnail_id() );
  } else {
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    if ( $output ) {
      $featured_image = $matches [1] [0];
    }
  }
  return $featured_image;
}
endif;

if ( ! function_exists( 'studio_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function studio_content_nav( $nav_id ) {
  global $wp_query, $post;

  // Don't print empty markup on single pages if there's nowhere to navigate.
  if ( is_single() ) {
    $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
    $next = get_adjacent_post( false, '', false );

    if ( ! $next && ! $previous )
      return;
  }

  // Don't print empty markup in archives if there's only one page.
  if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
    return;

  ?>
  <?php if ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
    <nav role="navigation" class="navigation">

      <?php if ( get_next_posts_link() ) : ?>
      <div class="nav-previous"><?php next_posts_link( __( 'Older posts', 'studio' ) ); ?></div>
      <?php endif; ?>

      <?php if ( get_previous_posts_link() && get_next_posts_link() ) : ?>
      <div class="divider"> / </div>
      <?php endif; ?>

      <?php if ( get_previous_posts_link() ) : ?>
      <div class="nav-next"><?php previous_posts_link( __( 'Newer posts', 'studio' ) ); ?></div>
      <?php endif; ?>

    </nav><!-- #<?php echo esc_html( $nav_id ); ?> -->
  <?php endif; ?>

  <?php
}
endif; // studio_content_nav

if ( ! function_exists( 'studio_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function studio_comment( $comment, $args, $depth ) {
  $GLOBALS['comment'] = $comment;

  if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

  <li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
    <div class="comment-body">
      <?php _e( 'Pingback:', 'studio' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'studio' ), '<span class="edit-link">', '</span>' ); ?>
    </div>

  <?php else : ?>

  <li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
    <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
      <footer class="comment-meta">
        <div class="comment-author vcard">
          <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
          <?php printf( __( '%s <span class="says">says:</span>', 'studio' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
        </div><!-- .comment-author -->

        <div class="comment-metadata">
          <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
            <time datetime="<?php comment_time( 'c' ); ?>">
              <?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'studio' ), get_comment_date(), get_comment_time() ); ?>
            </time>
          </a>
          <?php edit_comment_link( __( 'Edit', 'studio' ), '<span class="edit-link">', '</span>' ); ?>
        </div><!-- .comment-metadata -->

        <?php if ( '0' == $comment->comment_approved ) : ?>
        <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'studio' ); ?></p>
        <?php endif; ?>
      </footer><!-- .comment-meta -->

      <div class="comment-content">
        <?php comment_text(); ?>
      </div><!-- .comment-content -->

      <div class="reply">
        <?php comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
      </div><!-- .reply -->
    </article><!-- .comment-body -->

  <?php
  endif;
}
endif; // ends check for studio_comment()

if ( ! function_exists( 'studio_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function studio_posted_on() {
  $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
  /* translators: used between list items, there is a space after the comma */
  $categories_list = get_the_category_list( __( ', ', 'studio' ) );

  if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) )
    $time_string = '<time class="updated" datetime="%3$s">%4$s</time>';

  $time_string = sprintf( $time_string,
    esc_attr( get_the_modified_date( 'c' ) ),
    esc_html( get_the_modified_date() ),
    esc_attr( get_the_date( 'c' ) ),
    esc_html( get_the_date() )
  );

  printf( __( '<span class="posted-on">Posted on %1$s</span><span class="byline"> by %2$s</span>', 'studio' ),
    sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
      esc_url( get_permalink() ),
      esc_attr( get_the_time() ),
      $time_string
    ),

    sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
      esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
      esc_attr( sprintf( __( 'View all posts by %s', 'studio' ), get_the_author() ) ),
      esc_html( get_the_author() )
        )
    );
    echo '<span class="cat-links"> in ' . $categories_list . '</span>';
}
endif;

/**
 * Returns true if a blog has more than 1 category
 */
function studio_categorized_blog() {
  if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
    // Create an array of all the categories that are attached to posts
    $all_the_cool_cats = get_categories( array(
      'hide_empty' => 1,
    ) );

    // Count the number of categories that are attached to the posts
    $all_the_cool_cats = count( $all_the_cool_cats );

    set_transient( 'all_the_cool_cats', $all_the_cool_cats );
  }

  if ( '1' != $all_the_cool_cats ) {
    // This blog has more than 1 category so studio_categorized_blog should return true
    return true;
  } else {
    // This blog has only 1 category so studio_categorized_blog should return false
    return false;
  }
}

/**
 * Flush out the transients used in studio_categorized_blog
 */
function studio_category_transient_flusher() {
  // Like, beat it. Dig?
  delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'studio_category_transient_flusher' );
add_action( 'save_post',   'studio_category_transient_flusher' );
