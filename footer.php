<?php
$hide_footer = false;
if ( is_page() ){
    $hide_footer = get_post_meta( get_the_ID(), '_hide_footer', true );
}
if ( ! $hide_footer ) {
    ?>
    <footer id="colophon" class="site-footer" role="contentinfo">
        <?php
        do_action('sumerian_before_site_info');
        ?>
    </footer>
    <?php
}
do_action( 'sumerian_site_end' );
?>
</div>

<?php wp_footer(); ?>

</body>
</html>
