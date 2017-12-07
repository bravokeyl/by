<?php
/**
 *Template Name: Frontpage
 */

get_header(); ?>
	<div id="bk-home-subhead" class="bk-home-subhead">
			<div class="container">
					<div class="row text-center">
							<div class="col-md-12 col-sm-12 col-xs-12">
								 Empower your employee to consistently collaborate in a social, fun &amp; easy way towards company goals &amp; values
							</div>
					</div>
			</div>
	</div>
	<div id="content" class="site-content">
		<main id="main" class="site-main" role="main">
            <?php

            do_action( 'sumerian_frontpage_before_section_parts' );

			if ( ! has_action( 'sumerian_frontpage_section_parts' ) ) {

				$sections = apply_filters( 'sumerian_frontpage_sections_order', array(
                    'about', 'features', 'services', 'sales', 'gallery', 'career', 'team', 'contact'
                ) );

				foreach ( $sections as $section ){
                    sumerian_load_section( $section );
				}

			} else {
				do_action( 'sumerian_frontpage_section_parts' );
			}

            do_action( 'sumerian_frontpage_after_section_parts' );

			?>
		</main><!-- #main -->
	</div><!-- #content -->

<?php get_footer(); ?>
