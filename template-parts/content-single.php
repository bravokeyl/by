<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        <?php if ( get_theme_mod( 'single_meta', 1 ) ) { ?>
		<div class="entry-meta">
			<?php sumerian_posted_on(); ?>
		</div><!-- .entry-meta -->
        <?php } ?>
	</header><!-- .entry-header -->

    <?php if ( get_theme_mod( 'single_thumbnail', 0 ) && has_post_thumbnail() ) { ?>
        <div class="entry-thumbnail">
            <?php
            $layout = sumerian_get_layout();
            $size = 'large';
            the_post_thumbnail( $size );
            ?>
        </div><!-- .entry-footer -->
    <?php } ?>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'sumerian' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
    <?php if ( get_theme_mod( 'single_meta', 1 ) ) { ?>
	<footer class="entry-footer">
		<?php sumerian_entry_footer(); ?>
	</footer><!-- .entry-footer -->
    <?php } ?>
</article><!-- #post-## -->
