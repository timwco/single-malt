<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package _s
 */
?>

      <?php $header_image = get_theme_mod( 'custom_sidebar_background' ); ?>
      <footer class="site-footer <?php if( ! empty( $header_image ) ) { echo 'inverted'; } ?>" role="contentinfo">

        <div class="site-info-wrap">
          <div class="site-info">
            <?php do_action( 'studio_credits' ); ?>
            Published with <a href="http://desk.pm">Desk</a>
          </div><!-- .site-info -->
        </div>
      </footer><!-- #colophon -->
    </div>
  </div><!-- #content -->
</div><!-- #page -->

<?php wp_footer(); ?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-49577871-1', 'timw.co');
  ga('send', 'pageview');
</script>

</body>
</html>
