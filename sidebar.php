<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Studio
 */
?>

<?php if ( is_dynamic_sidebar() ) { ?>
  <div id="sidebar" class="clearfix">
    <?php do_action( 'before_sidebar' ); ?>
    <?php dynamic_sidebar(); ?>
  </div>
<?php } ?>
