<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Studio
 */

get_header(); ?>

<main id="main">

<?php
  get_template_part( 'no-results', 'search' );

  get_template_part( 'sidebar' );

  get_footer();
?>
