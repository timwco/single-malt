<?php

/**
 * Set up portfolio admin UI.
 */
function studio_post_meta_box_setup() {
  add_action( 'add_meta_boxes', 'studio_add_post_meta_box' );
  add_action( 'save_post', 'studio_save_post_meta_box' );
}
add_action( 'load-post.php', 'studio_post_meta_box_setup' );
add_action( 'load-post-new.php', 'studio_post_meta_box_setup' );


/**
 * Add portfolio meta box to post edit page.
 */
function studio_add_post_meta_box() {
  $pages = studio_portfolio_pages();

  if ( ! empty( $pages ) ) {
    add_meta_box( 'studio-portfolios', __( 'Portfolios', 'studio' ), 'studio_post_meta_box', 'post', 'side', 'low' );
  }
}


/**
 * Display portfolio meta box.
 *
 * This prints out a form on the edit post page, allowing the post to be added to
 * portfolio pages. The form also allows the usevideo flag to be set for the post.
 */
function studio_post_meta_box() {
  global $post;

  wp_nonce_field( basename( __FILE__ ), 'studio_portfolios_nonce' );

  $pages = studio_portfolio_pages();

  echo '<ul>';

  foreach ( $pages as $page ) {
    $active = studio_portfolio_has_post( $page->ID, $post->ID );
    $checked = ( $active )
      ? ' checked'
      : '';

    echo '<li><label for="studio-portfolio-'.$page->ID.'">';
    echo '<input type="checkbox" name="studio_portfolios[]" value="'.$page->ID.'" id="studio-portfolio-'.$page->ID.'"'.$checked.'>';
    echo $page->post_title;
    echo '</label></li>';
  }

  echo '</ul>';

  $usevideo = studio_portfolio_usevideo( $post->ID );
  $checked = ( $usevideo )
    ? ' checked'
    : '';

  echo '<hr><label>';
  echo '<input type="checkbox" name="studio_portfolio_usevideo" value="1"'.$checked.'>';
  echo __( 'Use first video as featured media', 'studio' );
  echo '</label>';
}


/**
 * Update portfolio information when a post is saved.
 */
function studio_save_post_meta_box( $post_id ) {
  // Bail if we're doing an auto save
  if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

  // Verify the nonce
  $nonce = $_POST['studio_portfolios_nonce'];

  if ( ! isset( $nonce ) || ! wp_verify_nonce( $nonce, basename( __FILE__ ) ) ) {
    return $post_id;
  }

  // Remove post from all portfolio pages
  $page_ids = studio_portfolio_page_ids( $post_id );

  foreach ( $page_ids as $page_id ) {
    studio_portfolio_remove_post( $page_id, $post_id );
  }

  // Add post to portfolio pages
  $portfolio_page_ids = isset( $_POST['studio_portfolios'] )
    ? $_POST['studio_portfolios']
    : array();

  foreach ( $portfolio_page_ids as $page_id ) {
    studio_portfolio_add_post( $page_id, $post_id );
  }

  // Set usevideo
  $usevideo = isset( $_POST['studio_portfolio_usevideo'] )
    ? $_POST['studio_portfolio_usevideo']
    : 0;
  studio_portfolio_set_usevideo( $post_id, $usevideo );
}


/**
 * Returns if the usevideo flag is set on the given post.
 *
 * @param   $post_id
 *          The post ID.
 *
 * @return  bool
 */
function studio_portfolio_usevideo( $post_id ) {
  return get_post_meta( $post_id, 'studio_portfolio_featurevideo', true ) == 1;
}


/**
 * Sets the usevideo flag on a given post.
 *
 * @param   $post_id
 *          The post ID.
 *
 * @param   bool $usevideo
 *          True to set the usevideo flag, false to delete it.
 */
function studio_portfolio_set_usevideo( $post_id, $usevideo ) {
  if ( $usevideo ) {
    update_post_meta( $post_id, 'studio_portfolio_featurevideo', 1, true );
  } else {
    delete_post_meta( $post_id, 'studio_portfolio_featurevideo' );
  }
}


/**
 * Returns if the post is linked to a portfolio page.
 *
 * @param   $post_id
 *          The post ID.
 *
 * @return  bool
 */
function studio_portfolio_post_is_active( $post_id ) {
  return get_post_meta( $post_id, 'studio_portfolio_active', true ) == 1;
}


/**
 * Get all pages marked as portfolio pages.
 *
 * @param   $post_id optional
 *          Only return portfolio pages which have this post.
 *
 * @return  array
 *          An array of WP_Post objects that have been set as portfolio pages.
 */
function studio_portfolio_pages( $post_id = null ) {
  $pages = get_pages( array(
    'meta_key'      => '_wp_page_template',
    'meta_value'    => 'portfolio.php',
    'hierarchical'  => 0,
  ) );

  // Return all pages
  if ( is_null( $post_id ) ) {
    return ( $pages )
      ? $pages
      : array();
  }

  // Return only pages that are linked to a given post
  $matching = array();

  foreach ( $pages as $page ) {
    $page_id = $page->ID;

    if ( studio_portfolio_has_post( $page_id, $post_id ) ) {
      $matching[] = $page;
    }
  }

  return $matching;
}


/**
 * Get IDs for all pages marked as portfolio pages.
 *
 * If a post ID is given, only pages that are linked to that post are returned.
 *
 * @param   $post_id optional
 */
function studio_portfolio_page_ids( $post_id = null ) {
  $pages = studio_portfolio_pages( $post_id );
  $ids = array();

  foreach ( $pages as $page ) {
    $ids[] = $page->ID;
  }

  return $ids;
}


/**
 * Get a list of post IDs that belong to a portfolio page.
 *
 * @param   $page_id
 *          The portfolio page ID.
 *
 * @return  array
 *          An array of post IDs, empty if none.
 */
function studio_portfolio_post_ids( $page_id ) {
  $meta = get_post_meta( $page_id, 'studio_portfolio_posts', true );
  return ( is_array( $meta ) )
    ? $meta
    : array();
}


/**
 * Filter out portfolio posts from the index page.
 */
function studio_filter_portfolio_posts( $query ) {
  if ( ! $query->is_home() || ! $query->is_main_query() ) {
    return;
  }

  $query->set( 'meta_query', array(
    array(
      'key'     => 'studio_portfolio_active',
      'value'   => true,
      'compare' => 'NOT EXISTS'
    )
  ));

}
add_action( 'pre_get_posts', 'studio_filter_portfolio_posts' );


/**
 * Returns true if the portfolio page is linked to the given post.
 *
 * @param   $page_id
 *          The portfolio page ID.
 *
 * @param   $post_id
 *          The post ID.
 *
 * @return  bool
 */
function studio_portfolio_has_post( $page_id, $post_id ) {
  $post_ids = studio_portfolio_post_ids( $page_id );
  return in_array( $post_id, $post_ids );
}


/**
 * Add a post to a portfolio page.
 *
 * @param   $page_id
 *          The portfolio page ID.
 *
 * @param   $post_id
 *          The post ID.
 */
function studio_portfolio_add_post( $page_id, $post_id ) {
  $post_ids = studio_portfolio_post_ids( $page_id );
  $post_ids[] = $post_id;
  $post_ids = array_unique( $post_ids );

  update_post_meta( $page_id, 'studio_portfolio_posts', $post_ids );
  add_post_meta( $post_id, 'studio_portfolio_active', true, true );
}


/**
 * Remove a post from a portfolio page.
 *
 * @param   $page_id
 *          The portfolio page ID.
 *
 * @param   $post_id
 *          The post ID.
 */
function studio_portfolio_remove_post( $page_id, $post_id ) {
  // Remove post_id from page
  $post_ids = studio_portfolio_post_ids( $page_id );
  $key = array_search( $post_id, $post_ids );

  if ( $key !== false ) {
    unset( $post_ids[$key] );
  }

  update_post_meta( $page_id, 'studio_portfolio_posts', $post_ids );

  // Update active flag
  $page_ids = studio_portfolio_page_ids( $post_id );

  if ( empty( $page_ids ) ) {
    delete_post_meta( $post_id, 'studio_portfolio_active' );
  }
}


/**
 * Upgrade post_meta table to new portfolio implementation.
 */
function studio_portfolio_upgrade() {
  // Already upgraded
  if ( get_theme_mod( 'studio_portfolio_upgrade' ) === true ) {
    return;
  }

  // Get all posts with old meta
  $posts = get_posts( array(
    'posts_per_page'  => -1,
    'post_status'     => 'any',
    'meta_query'      => array(
      array(
        'key'         => 'studio_portfolio_ids',
        'compare'     => 'EXISTS',
      )
    )
  ) );

  // Update posts to new meta
  foreach ( $posts as $post ) {
    $meta = get_post_meta( $post->ID, 'studio_portfolio_ids' );

    if ( ! isset( $meta[0] ) ) {
      continue;
    }

    $ids = $meta[0];

    foreach ( $ids as $id ) {
      studio_portfolio_add_post( $id, $post->ID );
    }

    delete_post_meta( $post->ID, 'studio_portfolio_ids' );
  }

  // Mark that we have upgraded
  set_theme_mod( 'studio_portfolio_upgrade', true );
}
add_action( 'admin_init', 'studio_portfolio_upgrade' );
