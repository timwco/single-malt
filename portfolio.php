<?php
/*
 * Template Name: Portfolio
 * */

get_header();

global $more;
global $post;

$portfolioPagination = get_theme_mod('portfolio_pagination');
if(is_front_page()) {
  $paged = (get_query_var('page')) ? get_query_var('page') : 1;
} else {
  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
}

if( $portfolioPagination ) {
  $postsPerPage = get_theme_mod('portfolios_per_page');
} else {
  $postsPerPage = -1;
}

$direct_link = get_theme_mod( 'link_portfolio_items' );

// Build custom query to find posts filed under this portfolio page
$portfolio_post_ids = studio_portfolio_post_ids( $post->ID );

if ( empty( $portfolio_post_ids ) ) {
  $custom_query_args = array();
} else {
  $custom_query_args = array(
    'post_type' => 'post',
    'posts_per_page' => $postsPerPage,
    'ignore_sticky_posts' => 1,
    'paged' => $paged,
    'post__in' => $portfolio_post_ids,
  );
}

$custom_query = new WP_Query( $custom_query_args );

?>

<main id="main">

  <div id="gallery" <?php if($direct_link) echo 'class="direct-link"'?>>
    <ul class="clearfix">
      <?php if ( $custom_query->have_posts() ): while ( $custom_query->have_posts() ):

        $custom_query->the_post();
        $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'portfolio' );

        if ( isset( $featured_image ) ) {
          $featured_image = esc_url( $featured_image[0] );
        } else {
          $featured_image = esc_url( studio_get_featured_image() );
        } ?>

        <li <?php post_class(); ?>>
          <div class="gallery-tile" style="background-image: url('<?php echo $featured_image ?>')">
            <img src="<?php echo $featured_image; ?>" class="hidden" />
            <a class="dimmer" href="<?php echo get_permalink() ?>"><h1 class="post-title"><?php the_title(); ?></h1></a>
            <div class="gallery-tile-padder <?php echo esc_attr( get_theme_mod( 'thumbnail_aspect' ) ); ?>"></div>
          </div>
        </li>

      <?php endwhile; endif; ?>
    </ul>
  </div>

  <?php if( $portfolioPagination && $custom_query->max_num_pages > 1 ) : ?>
    <nav role="navigation" class="navigation">

      <?php
        $older = get_next_posts_link( 'Older posts', $custom_query->max_num_pages );
        $newer = get_previous_posts_link('Newer posts', $custom_query->max_num_pages );
      ?>

      <?php if ( $older ) : ?>
      <div class="nav-previous"><?php echo $older; ?></div>
      <?php endif; ?>

      <?php if ( $older && $newer ) : ?>
      <div class="divider"> / </div>
      <?php endif; ?>

      <?php if ( $newer ) : ?>
      <div class="nav-next"><?php echo $newer; ?></div>
      <?php endif; ?>

    </nav>
  <?php endif; ?>

  <div id="permalinks">

    <?php
    if ( $custom_query->have_posts() ):
      while ( $custom_query->have_posts() ): $custom_query->the_post();
        get_template_part( 'content', 'portfolio' );
      endwhile;

    else : ?>
        <p><?php _e( 'You can add items to your portfolio by making a new post and selecting portfolios in the bottom right hand corner of the page.', 'studio' ); ?></p>

    <?php endif; ?>
  </div>

<?php get_footer(); ?>
