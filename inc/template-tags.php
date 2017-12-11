<?php

function sumerian_add_retina_logo( $html ){
    $custom_logo_id = get_theme_mod( 'custom_logo' );

    $custom_logo_attr = array(
        'class'    => 'custom-logo',
        'itemprop' => 'logo',
    );
    $image_retina_url = false;
    $retina_id = false;
    $retina_url = sanitize_text_field( get_theme_mod( 'sumerian_retina_logo' ) ) ;
    if ( $retina_url ) {
        $retina_id = attachment_url_to_postid( $retina_url );
        if ( $retina_id ){
            $image_retina_url = wp_get_attachment_image_src( $retina_id, 'full' );
            if ( $image_retina_url ) {
                $custom_logo_attr['srcset'] = $image_retina_url[0].' 2x';
            }

        }
    }

    if( ! $custom_logo_id ) {
        $custom_logo_id = $retina_id;
    }

    $t_logo_html = '';

    if ( sumerian_is_transparent_header() ){
        $t_logo = sanitize_text_field( get_theme_mod( 'sumerian_transparent_logo' ) ) ;
        $t_logo_r = sanitize_text_field( get_theme_mod( 'sumerian_transparent_retina_logo' ) ) ;
        $t_logo_attr = array(
            'class'    => 'custom-logo-transparent',
            'itemprop' => 'logo',
        );

        if ( $t_logo_r ) {
            $t_logo_r = attachment_url_to_postid( $t_logo_r );
            if ( $t_logo_r ){
                $image_tr_url = wp_get_attachment_image_src( $t_logo_r, 'full' );
                if ( $image_tr_url ) {
                    $t_logo_attr['srcset'] = $image_tr_url[0].' 2x';
                }
            }
        }

        if ( $t_logo ) {
            $t_logo = attachment_url_to_postid( $t_logo );
        }
        if ( ! $t_logo ) {
            $t_logo = $t_logo_r;
        }

        if ( $t_logo ){
            $t_logo_html = wp_get_attachment_image( $t_logo, 'full', false, $t_logo_attr );
        }

    }

    // We have a logo. Logo is go.
    if ( $custom_logo_id ) {

        /*
         * If the logo alt attribute is empty, get the site title and explicitly
         * pass it to the attributes used by wp_get_attachment_image().
         */
        $image_alt = get_post_meta( $custom_logo_id, '_wp_attachment_image_alt', true );
        if ( empty( $image_alt ) ) {
            $custom_logo_attr['alt'] = get_bloginfo( 'name', 'display' );
        }

        if ( ! $t_logo_html ) {
            $class = ' no-t-logo';
        } else {
            $class = ' has-t-logo';
        }

        /*
         * If the alt attribute is not empty, there's no need to explicitly pass
         * it because wp_get_attachment_image() already adds the alt attribute.
         */
        $html = sprintf( '<a href="%1$s" class="custom-logo-link '.esc_attr( $class ).'" rel="home" itemprop="url">%2$s</a>',
            esc_url( home_url( '/' ) ),
            wp_get_attachment_image( $custom_logo_id, 'full', false, $custom_logo_attr ).$t_logo_html
        );
    }

    return $html;
}

add_filter( 'get_custom_logo', 'sumerian_add_retina_logo', 15 );


if ( ! function_exists( 'sumerian_site_logo' ) ) {
    function sumerian_site_logo(){
        $classes = array();
        $html = '' ;
        $classes['logo'] = 'no-logo-img';

        if ( function_exists( 'has_custom_logo' ) ) {
            if ( has_custom_logo()) {
                $classes['logo'] = 'has-logo-img';
                $html .= '<div class="site-logo-div">';
                $html .= get_custom_logo();
                $html .= '</div>';
            }
        }

        $hide_sitetile = get_theme_mod( 'sumerian_hide_sitetitle',  0 );
        $hide_tagline  = get_theme_mod( 'sumerian_hide_tagline', 0 );

        if ( ! $hide_sitetile ) {
            $classes['title'] = 'has-title';
            if ( is_front_page() && !is_home() ) {
                $html .= '<h1 class="site-title"><a class="site-text-logo" href="' . esc_url(home_url('/')) . '" rel="home">' . get_bloginfo('name') . '</a></h1>';
            } else {
                $html .= '<p class="site-title"><a class="site-text-logo" href="' . esc_url(home_url('/')) . '" rel="home">' . get_bloginfo('name') . '</a></p>';
            }
        }

        if ( ! $hide_tagline ) {
            $description = get_bloginfo( 'description', 'display' );
            if ( $description || is_customize_preview() ) {
                $classes['desc'] = 'has-desc';
                $html .= '<p class="site-description">'.$description.'</p>';
            }
        } else {
            $classes['desc'] = 'no-desc';
        }

        echo '<div class="site-brand-inner '.esc_attr( join( ' ', $classes ) ).'">'.$html.'</div>';
    }
}

if ( ! function_exists( 'sumerian_is_transparent_header' ) ) {
    function sumerian_is_transparent_header()
    {
        $check = false;
        if (is_front_page() && is_page_template('template-frontpage.php')) {
            if (get_theme_mod('sumerian_header_transparent')) {
                $check = true;
            }
        } elseif ( is_page() &&  has_post_thumbnail() ) {
            if ( get_theme_mod( 'sumerian_page_title_bar_disable' ) == 1  ) {
                return false;
            }
            if ( has_post_thumbnail() ){
                if (get_theme_mod('sumerian_header_transparent')) {
                    $check = true;
                }
            }
        } elseif( is_home() ){
            if ( get_theme_mod( 'sumerian_page_title_bar_disable' ) == 1  ) {
                return false;
            }

            $new_page = get_option( 'page_for_posts' );
            if ( has_post_thumbnail( $new_page ) ) {
                if ( get_theme_mod('sumerian_header_transparent')) {
                    $check = true;
                }
            }
        }

        return $check;
    }
}

add_action( 'sumerian_site_start', 'sumerian_site_header' );
if ( ! function_exists( 'sumerian_site_header' ) ) {
    /**
     * Display site header
     */
    function sumerian_site_header(){
        $header_width = get_theme_mod( 'sumerian_header_width', 'contained' );
        $is_disable_sticky = sanitize_text_field( get_theme_mod( 'sumerian_sticky_header_disable' ) );
        $classes = array(
            'site-header',  'header-'.$header_width,
        );

        if ( $is_disable_sticky !=  1 ) {
            $classes[] ='is-sticky no-scroll';
        } else {
            $classes[] ='no-sticky no-scroll';
        }

        $transparent = 'no-t';
        if ( sumerian_is_transparent_header() ){
            $transparent = 'is-t';
        }
        $classes[] = $transparent;

        $pos = sanitize_text_field(get_theme_mod('sumerian_header_position', 'top'));
        if ($pos == 'below_hero') {
            $classes[] = 'h-below-hero';
        } else {
            $classes[] = 'h-on-top';
        }

        ?>
        <header id="masthead" class="<?php echo esc_attr( join(' ', $classes ) ); ?>" role="banner">
            <div class="container">
                <div class="site-branding">
                <?php
                sumerian_site_logo();
                ?>
                </div>
                <div class="header-right-wrapper">
                    <a href="#0" id="nav-toggle"><?php _e('Menu', 'sumerian'); ?><span></span></a>
                    <nav id="site-navigation" class="main-navigation" role="navigation">
                        <ul class="sumerian-menu">
                            <?php wp_nav_menu(array('theme_location' => 'primary', 'container' => '', 'items_wrap' => '%3$s')); ?>
                        </ul>
                    </nav>
                    <!-- #site-navigation -->
                </div>
            </div>
        </header><!-- #masthead -->
        <?php
    }
}

if ( ! function_exists('sumerian_header' ) ) {

    function sumerian_header()
    {
        $transparent = 'no-transparent';
        $classes = array();
        if ( sumerian_is_transparent_header() ){
            $transparent = 'is-transparent';
        }
        $pos = sanitize_text_field(get_theme_mod('sumerian_header_position', 'top'));
        if ($pos == 'below_hero') {
            $transparent = 'no-transparent';
            $classes[] = 'h-below-hero';
        } else {
            $classes[] = 'h-on-top';
        }

        $classes[] = $transparent;

        echo '<div id="header-section" class="' . esc_attr( join( ' ', $classes ) ) . '">';

            do_action('sumerian_header_section_start');
            if ($pos == 'below_hero' ) {
                if ( is_page_template('template-frontpage.php') ) {
                    do_action('sumerian_header_end');
                }
            }

            $hide_header = false;
            if (is_page()) {
                $hide_header = get_post_meta(get_the_ID(), '_hide_header', true);
            }
            if (!$hide_header) {
                /**
                 * Hooked: sumerian_site_header
                 *
                 * @see sumerian_site_header
                 */
                do_action('sumerian_site_start');
            }

            if ( $pos != 'below_hero') {
                if ( is_page_template('template-frontpage.php') ) {
                    do_action('sumerian_header_end');
                }
            }

            do_action('sumerian_header_section_end');
        echo '</div>';
    }
}

if ( ! function_exists( 'sumerian_posted_on' ) ) {
    /**
     * Prints HTML with meta information for the current post-date/time and author.
     */
    function sumerian_posted_on()
    {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated hide" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf($time_string,
            esc_attr(get_the_date('c')),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date('c')),
            esc_html(get_the_modified_date())
        );

        $posted_on = sprintf(
            esc_html_x('Posted on %s', 'post date', 'sumerian'),
            '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
        );

        $byline = sprintf(
            esc_html_x('by %s', 'post author', 'sumerian'),
            '<span class="author vcard"><a  rel="author" class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
        );

        echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

    }
}

if ( ! function_exists( 'sumerian_entry_footer' ) ) {
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function sumerian_entry_footer()
    {
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list(esc_html__(', ', 'sumerian'));
            if ($categories_list && sumerian_categorized_blog()) {
                printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'sumerian') . '</span>', $categories_list); // WPCS: XSS OK.
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('', esc_html__(', ', 'sumerian'));
            if ($tags_list) {
                printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'sumerian') . '</span>', $tags_list); // WPCS: XSS OK.
            }
        }

        if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
            echo '<span class="comments-link">';
            comments_popup_link(esc_html__('Leave a comment', 'sumerian'), esc_html__('1 Comment', 'sumerian'), esc_html__('% Comments', 'sumerian'));
            echo '</span>';
        }

    }
}

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function sumerian_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'sumerian_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'sumerian_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so sumerian_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so sumerian_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in sumerian_categorized_blog.
 */
function sumerian_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'sumerian_categories' );
}
add_action( 'edit_category', 'sumerian_category_transient_flusher' );
add_action( 'save_post',     'sumerian_category_transient_flusher' );


if ( ! function_exists( 'sumerian_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own sumerian_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @return void
 */
function sumerian_comment( $comment, $args, $depth ) {
    switch ( $comment->comment_type ) :
        case 'pingback' :
        case 'trackback' :
        // Display trackbacks differently than normal comments.
    ?>
    <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
        <p><?php _e( 'Pingback:', 'sumerian' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'sumerian' ), '<span class="edit-link">', '</span>' ); ?></p>
    <?php
            break;
        default :
        // Proceed with normal comments.
        global $post;
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment clearfix">

            <?php echo get_avatar( $comment, 60 ); ?>

            <div class="comment-wrapper">

                <header class="comment-meta comment-author vcard">
                    <?php
                        printf( '<cite><b class="fn">%1$s</b> %2$s</cite>',
                            get_comment_author_link(),
                            // If current post author is also comment author, make it known visually.
                            ( $comment->user_id === $post->post_author ) ? '<span>' . __( 'Post author', 'sumerian' ) . '</span>' : ''
                        );
                        printf( '<a class="comment-time" href="%1$s"><time datetime="%2$s">%3$s</time></a>',
                            esc_url( get_comment_link( $comment->comment_ID ) ),
                            get_comment_time( 'c' ),
                            /* translators: 1: date, 2: time */
                            get_comment_date()
                        );
                        comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'sumerian' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
                        edit_comment_link( __( 'Edit', 'sumerian' ), '<span class="edit-link">', '</span>' );
                    ?>
                </header><!-- .comment-meta -->

                <?php if ( '0' == $comment->comment_approved ) : ?>
                    <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'sumerian' ); ?></p>
                <?php endif; ?>

                <div class="comment-content entry-content">
                    <?php comment_text(); ?>
                    <?php  ?>
                </div><!-- .comment-content -->

            </div><!--/comment-wrapper-->

        </article><!-- #comment-## -->
    <?php
        break;
    endswitch; // end comment_type check
}
endif;

if ( ! function_exists( 'sumerian_hex_to_rgba' ) ) {
    /**
     * Convert hex color to rgba color
     *
     * @since 1.1.5
     *
     * @param $color
     * @param int $alpha
     * @return bool|string
     */
    function sumerian_hex_to_rgba( $color, $alpha = 1)
    {
        $color = str_replace('#', '', $color);
        if ('' === $color) {
            return '';
        }

        if ( strpos(trim($color), 'rgb') !== false ) {
            return $color;
        }

        // 3 or 6 hex digits, or the empty string.
        if (preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', '#' . $color)) {
            // convert to rgb
            $colour = $color;
            if (strlen($colour) == 6) {
                list($r, $g, $b) = array($colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
            } elseif (strlen($colour) == 3) {
                list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
            } else {
                return false;
            }
            $r = hexdec($r);
            $g = hexdec($g);
            $b = hexdec($b);
            return 'rgba(' . join(',', array('r' => $r, 'g' => $g, 'b' => $b, 'a' => $alpha ) ) . ')';
        }

        return false;

    }
}



if ( ! function_exists( 'sumerian_custom_inline_style' ) ) {
    /**
     * Add custom css to header
     *
     * @change 1.1.5
     */
	function sumerian_custom_inline_style( ) {

	        $logo_height= absint( get_theme_mod( 'sumerian_logo_height' ) );

            /**
             *  Custom hero section css
             */
            $hero_bg_color = sanitize_hex_color( get_theme_mod( 'sumerian_hero_overlay_color', '#000000' ) );

            // Deprecate form v 1.1.5
            $hero_bg_color = sumerian_hex_to_rgba( $hero_bg_color, floatval( get_theme_mod( 'sumerian_hero_overlay_opacity' , .3 ) ) );

            ob_start();
            if ( $logo_height > 0 ) {
                echo ".site-logo-div img{ height: {$logo_height}px; width: auto; }";
            }

            $t_site_name_color = sanitize_hex_color( get_theme_mod( 'sumerian_transparent_site_title_c' ) );
            if ( $t_site_name_color ) {
                echo " .is-transparent .site-header.no-scroll .site-title, .is-transparent .site-header.no-scroll .site-title .site-text-logo { color: {$t_site_name_color}; }";
            }
            $t_tagline_color = sanitize_hex_color( get_theme_mod( 'sumerian_transparent_tag_title_c' ) );
            if ( $t_tagline_color ) {
                echo " .is-transparent .site-header.no-scroll .site-description { color: {$t_tagline_color}; }";
            }

            ?>
            #main .video-section section.hero-slideshow-wrapper {
                background: transparent;
            }
            .hero-slideshow-wrapper:after {
                position: absolute;
                top: 0px;
                left: 0px;
                width: 100%;
                height: 100%;
                background-color: <?php echo $hero_bg_color; ?>;
                display: block;
                content: "";
            }
            .body-desktop .parallax-hero .hero-slideshow-wrapper:after {
                display: none !important;
            }
            #parallax-hero > .parallax-bg::before {
                background-color: <?php echo $hero_bg_color; ?>;
                opacity: 1;
            }
            .body-desktop .parallax-hero .hero-slideshow-wrapper:after {
                display: none !important;
            }

            <?php
            /**
             * Theme Color
             */
            $primary = sanitize_hex_color_no_hash( get_theme_mod( 'sumerian_primary_color' ) );
            if ( $primary != '' ) { ?>
                a, .screen-reader-text:hover, .screen-reader-text:active, .screen-reader-text:focus, .header-social a, .sumerian-menu a:hover,
                .sumerian-menu ul li a:hover, .sumerian-menu li.sumerian-current-item > a, .sumerian-menu ul li.current-menu-item > a, .sumerian-menu > li a.menu-actived,
                .sumerian-menu.sumerian-menu-mobile li.sumerian-current-item > a, .site-footer a, .site-footer .footer-social a:hover, .site-footer .btt a:hover,
                .highlight, #comments .comment .comment-wrapper .comment-meta .comment-time:hover, #comments .comment .comment-wrapper .comment-meta .comment-reply-link:hover, #comments .comment .comment-wrapper .comment-meta .comment-edit-link:hover,
                .btn-theme-primary-outline, .sidebar .widget a:hover, .section-services .service-item .service-image i, .counter_item .counter__number,
                .team-member .member-thumb .member-profile a:hover, .icon-background-default
                {
                    color: #<?php echo $primary; ?>;
                }
                input[type="reset"], input[type="submit"], input[type="submit"], .nav-links a:hover, .btn-theme-primary, .btn-theme-primary-outline:hover, .section-testimonials .card-theme-primary,
				.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce button.button.alt
                {
                    background: #<?php echo $primary; ?>;
                }
                .btn-theme-primary-outline, .btn-theme-primary-outline:hover, .pricing__item:hover, .section-testimonials .card-theme-primary, .entry-content blockquote
                {
                    border-color : #<?php echo $primary; ?>;
                }
                <?php
                if ( class_exists( 'WooCommerce' ) ) { ?>
                    .woocommerce #respond input#submit.alt,
                    .woocommerce a.button.alt,
                    .woocommerce button.button.alt,
                    .woocommerce input.button.alt {
                        background-color: #<?php echo $primary; ?>;
                    }
                    .woocommerce #respond input#submit.alt:hover,
                    .woocommerce a.button.alt:hover,
                    .woocommerce button.button.alt:hover,
                    .woocommerce input.button.alt:hover {
                        background-color: #<?php echo $primary; ?>;
                    }
                <?php }
            } // End $primary

            $menu_padding = get_theme_mod( 'sumerian_menu_item_padding' );
            if ( $menu_padding ) {
                $menu_padding = absint( $menu_padding );
                echo ".sumerian-menu a{ padding-left: {$menu_padding}px; padding-right: {$menu_padding}px;  }";
            }

            $cover_align = sanitize_text_field( get_theme_mod( 'sumerian_page_cover_align' ) );
            switch( $cover_align ) {
                case  'left':
                case  'right':
                    echo ".page-header.page--cover{ text-align: {$cover_align}; }";
                    break;
            }


            $cover_color = sumerian_sanitize_color_alpha( get_theme_mod( 'sumerian_page_cover_color' ) );
            if ( $cover_color ) {
                echo " .page-header.page--cover .entry-title { color: {$cover_color}; }";
            }

            $cover_overlay = sumerian_sanitize_color_alpha( get_theme_mod( 'sumerian_page_cover_overlay' ) );
            if ( $cover_overlay ) {
                echo ".page-header.page--cover:before { background: {$cover_overlay}; }";
            }
            $cover_pd_top = absint( get_theme_mod( 'sumerian_page_cover_pd_top' ) );
            if ( $cover_pd_top > 0 ) {
                echo ".page-header.page--cover { padding-top: {$cover_pd_top}%; }";
            }
            $cover_pd_bottom = absint( get_theme_mod( 'sumerian_page_cover_pd_bottom' ) );
            if ( $cover_pd_bottom > 0 ) {
                echo ".page-header.page--cover { padding-bottom: {$cover_pd_bottom}%; }";
            }

            /**
             * Header background
             */
            $header_bg_color = sanitize_hex_color_no_hash( get_theme_mod( 'sumerian_header_bg_color' ) );
            if ( $header_bg_color ) {
                ?>
                .site-header, .is-transparent .site-header {
                    background: #<?php echo $header_bg_color; ?>;
                    border-bottom: 0px none;
                }
                <?php
            } // END $header_bg_color

            /**
             * Menu color
             */
            $menu_color = sanitize_hex_color_no_hash( get_theme_mod( 'sumerian_menu_color' ) );
            if ( $menu_color ) {
                ?>
                .sumerian-menu > li > a {
                    color: #<?php echo $menu_color; ?>;
                }
                <?php
            } // END $menu_color

            /**
             * Menu hover color
             */
            $menu_hover_color = sanitize_hex_color_no_hash( get_theme_mod( 'sumerian_menu_hover_color' ) );
            if ( $menu_hover_color ) {
                ?>
                .sumerian-menu > li > a:hover,
                .sumerian-menu > li.sumerian-current-item > a{
                    color: #<?php echo $menu_hover_color; ?>;
					-webkit-transition: all 0.5s ease-in-out;
					-moz-transition: all 0.5s ease-in-out;
					-o-transition: all 0.5s ease-in-out;
					transition: all 0.5s ease-in-out;
                }
            <?php
            } // END $menu_hover_color

            /**
             * Menu hover background color
             */
            $menu_hover_bg = sanitize_hex_color_no_hash( get_theme_mod( 'sumerian_menu_hover_bg_color' ) );
            if ( $menu_hover_bg ) {
                ?>
				@media screen and (min-width: 1140px) {
	                .sumerian-menu > li:last-child > a {
	                    padding-right: 17px;
	                }
	                .sumerian-menu > li > a:hover,
	                .sumerian-menu > li.sumerian-current-item > a
	                {
	                    background: #<?php echo $menu_hover_bg; ?>;
						-webkit-transition: all 0.5s ease-in-out;
						-moz-transition: all 0.5s ease-in-out;
						-o-transition: all 0.5s ease-in-out;
						transition: all 0.5s ease-in-out;
	                }
				}
            <?php
            } // END $menu_hover_bg

			/**
             * Reponsive Mobie button color
             */
            $menu_button_color = sanitize_hex_color_no_hash( get_theme_mod( 'sumerian_menu_toggle_button_color' ) );
            if ( $menu_button_color ) {
                ?>
				#nav-toggle span, #nav-toggle span::before, #nav-toggle span::after,
				#nav-toggle.nav-is-visible span::before, #nav-toggle.nav-is-visible span::after {
					background: #<?php echo $menu_button_color; ?>;
				}
            <?php
            }

			/**
             * Site Title
             */
            $sumerian_logo_text_color = sanitize_hex_color_no_hash( get_theme_mod( 'sumerian_logo_text_color' ) );
            if ( $sumerian_logo_text_color ) {
                ?>
				.site-branding .site-title, .site-branding .site-text-logo {
					color: #<?php echo $sumerian_logo_text_color; ?>;
				}
            <?php
            }


            $r_text = sanitize_hex_color( get_theme_mod( 'sumerian_hcl1_r_color' ) );//
            $r_bg_text = sanitize_hex_color( get_theme_mod( 'sumerian_hcl1_r_bg_color' ) );//
            if ( $r_text ) {
                ?>
                .hero-content-style1 .morphext {
                    color: <?php echo $r_text; ?>;
                }
                <?php
            }
            if ( $r_bg_text ) {
                ?>
                .hero-content-style1 .morphext {
                    background: <?php echo $r_bg_text; ?>;
                    padding: 0px 20px;
                    text-shadow: none;
                    border-radius: 3px;
                }
                <?php
            }




            $sumerian_footer_bg = sanitize_hex_color_no_hash( get_theme_mod( 'sumerian_footer_bg' ) );
            $footer_top_text = sanitize_hex_color( get_theme_mod( 'sumerian_footer_top_color' ) );
            if ( $sumerian_footer_bg ) {
                ?>
                .site-footer {
                    background-color: #<?php echo $sumerian_footer_bg; ?>;
                }
                .site-footer .footer-connect .follow-heading, .site-footer .footer-social a {
                    color: <?php echo ( $footer_top_text ) ? $footer_top_text: 'rgba(255, 255, 255, 0.9)'; ?>;
                }
                <?php
            } elseif ( $footer_top_text ) {
                ?>
                .site-footer .footer-connect .follow-heading, .site-footer .footer-social a {
                    color: <?php echo $footer_top_text; ?>;
                }
                <?php
            }

            $sumerian_footer_info_bg = sanitize_hex_color_no_hash( get_theme_mod( 'sumerian_footer_info_bg' ) );
            $c_color = sanitize_hex_color( get_theme_mod( 'sumerian_footer_c_color' ) );
            $c_link_color = sanitize_hex_color( get_theme_mod( 'sumerian_footer_c_link_color' ) );
            $c_link_hover_color = sanitize_hex_color( get_theme_mod( 'sumerian_footer_c_link_hover_color' ) );
            if ( $sumerian_footer_info_bg ) {
                ?>
                .site-footer .site-info, .site-footer .btt a{
                    background-color: #<?php echo $sumerian_footer_info_bg; ?>;

                }
                <?php if ( $c_color ) { ?>
                    .site-footer .site-info {
                        color: <?php echo $c_color ?>;
                    }
                    .site-footer .btt a, .site-footer .site-info a {
                        color: <?php echo $c_color ?>;
                    }
                    <?php
                } else {
                    ?>
                    .site-footer .site-info {
                        color: rgba(255, 255, 255, 0.7);
                    }
                    .site-footer .btt a, .site-footer .site-info a {
                        color: rgba(255, 255, 255, 0.9);
                    }
                    <?php
                }

            } elseif( $c_color ) {
                ?>
                .site-footer .site-info {
                    color: <?php echo $c_color ?>;
                }

                <?php
            }
            if ( $c_link_color ) {
                ?>
                .site-footer .btt a, .site-footer .site-info a {
                    color: <?php echo $c_link_color ?>;
                }
                <?php
            }
            if ( $c_link_hover_color ) {
                ?>
                .site-footer .btt a:hover, .site-footer .site-info a:hover {
                    color: <?php echo $c_link_hover_color ?>;
                }
                <?php
            }

            $footer_widgets_color = sanitize_hex_color( get_theme_mod( 'footer_widgets_color' ) );
            $footer_widgets_bg_color = sanitize_hex_color( get_theme_mod( 'footer_widgets_bg_color' ) );
            $footer_widgets_title_color = sanitize_hex_color( get_theme_mod( 'footer_widgets_title_color' ) );
            $footer_widgets_link_color = sanitize_hex_color( get_theme_mod( 'footer_widgets_link_color' ) );
            $footer_widgets_link_hover_color = sanitize_hex_color( get_theme_mod( 'footer_widgets_link_hover_color' ) );

            ?>
            #footer-widgets {
                <?php
                if ( $footer_widgets_color ) {
                    echo "color: {$footer_widgets_color};";
                }
                if ( $footer_widgets_bg_color ) {
                    echo "background-color: {$footer_widgets_bg_color};";
                }
                ?>
            }
            <?php
            if ( $footer_widgets_title_color ) {
                echo "#footer-widgets .widget-title{ color: {$footer_widgets_title_color}; }";
            }

            if ( $footer_widgets_link_color ) {
                echo "#footer-widgets .sidebar .widget a{ color: {$footer_widgets_link_color}; }";
            }

            if ( $footer_widgets_link_hover_color ) {
                echo "#footer-widgets .sidebar .widget a:hover{ color: {$footer_widgets_link_hover_color}; }";
            }

            $gallery_spacing = absint( get_theme_mod( 'sumerian_g_spacing', 20 ) );

            ?>
            .gallery-carousel .g-item{
                padding: 0px <?php echo intval( $gallery_spacing / 2 ); ?>px;
            }
            .gallery-carousel {
                margin-left: -<?php echo intval( $gallery_spacing / 2 ); ?>px;
                margin-right: -<?php echo intval( $gallery_spacing / 2 ); ?>px;
            }
            .gallery-grid .g-item, .gallery-masonry .g-item .inner {
                padding: <?php echo intval( $gallery_spacing / 2 ); ?>px;
            }
            .gallery-grid, .gallery-masonry {
                margin: -<?php echo intval( $gallery_spacing / 2 ); ?>px;
            }
        <?php

        $css = ob_get_clean();

        if ( trim( $css ) == "" ) {
            return ;
        }
        $css = preg_replace(
            array(
                // Remove comment(s)
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
                // Remove unused white-space(s)
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
            ),
            array(
                '$1',
                '$1$2$3$4$5$6$7',
            ),
            $css
        );


        if ( ! function_exists( 'wp_get_custom_css' ) ) {  // Back-compat for WordPress < 4.7.
            $custom = get_option('sumerian_custom_css');
            if ($custom) {
                $css .= "\n/* --- Begin custom CSS --- */\n" . $custom . "\n/* --- End custom CSS --- */\n";
            }
        }

       return apply_filters( 'sumerian_custom_css', $css ) ;
	}

}


if ( function_exists( 'wp_update_custom_css_post' ) ) {
    // Migrate any existing theme CSS to the core option added in WordPress 4.7.
    $css = get_option( 'sumerian_custom_css' );
    if ( $css ) {
        $core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
        $return = wp_update_custom_css_post( $core_css ."\n". $css );
        if ( ! is_wp_error( $return ) ) {
            // Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
            delete_option( 'sumerian_custom_css' );
        }
    }
} else {
    // Back-compat for WordPress < 4.7.
}

if ( ! function_exists( 'sumerian_get_section_about_data' ) ) {
    /**
     * Get About data
     *
     * @return array
     */
    function sumerian_get_section_about_data()
    {
        $boxes = get_theme_mod('sumerian_about_boxes');
        if (is_string($boxes)) {
            $boxes = json_decode($boxes, true);
        }
        $page_ids = array();
        if (!empty($boxes) && is_array($boxes)) {
            foreach ($boxes as $k => $v) {
                if (isset ($v['content_page'])) {
                    $v['content_page'] = absint($v['content_page']);
                    if ($v['content_page'] > 0) {
                        $page_ids[] = wp_parse_args($v, array('enable_link' => 0, 'hide_title' => 0));
                    }
                }
            }
        }
        $page_ids = array_filter( $page_ids );

        return $page_ids;
    }
}

if ( ! function_exists( 'sumerian_get_section_counter_data' ) ) {
    /**
     * Get counter data
     *
     * @return array
     */
    function sumerian_get_section_counter_data()
    {
        $boxes = get_theme_mod('sumerian_counter_boxes');
        if (is_string($boxes)) {
            $boxes = json_decode($boxes, true);
        }
        if (empty($boxes) || !is_array($boxes)) {
            $boxes = array();
        }
        return $boxes;
    }
}

if ( ! function_exists( 'sumerian_get_section_services_data' ) ) {
    /**
     * Get services data
     * @return array
     */
    function sumerian_get_section_services_data()
    {
        $services = get_theme_mod('sumerian_services');
        if (is_string($services)) {
            $services = json_decode($services, true);
        }
        $page_ids = array();
        if (!empty($services) && is_array($services)) {
            foreach ($services as $k => $v) {
                if (isset ($v['content_page'])) {
                    $v['content_page'] = absint($v['content_page']);
                    if ($v['content_page'] > 0) {
                        $page_ids[] = wp_parse_args($v, array(
                            'icon_type' => 'icon',
                            'image' => '',
                            'icon' => 'gg',
                            'enable_link' => 0
                        ));
                    }
                }
            }
        }
        // if still empty data then get some page for demo
        return $page_ids;
    }
}

if ( ! function_exists( 'sumerian_get_section_team_data' ) ) {
    /**
     * Get team members
     *
     * @return array
     */
    function sumerian_get_section_team_data()
    {
        $members = get_theme_mod('sumerian_team_members');
        if (is_string($members)) {
            $members = json_decode($members, true);
        }
        if (!is_array($members)) {
            $members = array();
        }
        return $members;
    }
}

if ( ! function_exists( 'sumerian_get_features_data' ) ) {
    /**
     * Get features data
     *
     * @since 1.1.4
     * @return array
     */
    function sumerian_get_features_data()
    {
        $array = get_theme_mod('sumerian_features_boxes');
        if (is_string($array)) {
            $array = json_decode($array, true);
        }
        if (!empty($array) && is_array($array)) {
            foreach ($array as $k => $v) {
                $array[$k] = wp_parse_args($v, array(
                    'icon' => 'gg',
                    'title' => '',
                    'desc' => '',
                    'link' => '',
                ));

                //Get/Set social icons
                $array[$k]['icon'] = trim($array[$k]['icon']);
                if ($array[$k]['icon'] != '' && strpos($array[$k]['icon'], 'fa') !== 0) {
                    $array[$k]['icon'] = 'fa-' . $array[$k]['icon'];
                }
            }
        }
        return $array;
    }
}

if ( ! function_exists( 'sumerian_get_social_profiles' ) ) {
    /**
     * Get social profiles
     *
     * @since 1.1.4
     * @return bool|array
     */
    function sumerian_get_social_profiles()
    {
        $array = get_theme_mod('sumerian_social_profiles');
        if (is_string($array)) {
            $array = json_decode($array, true);
        }
        $html = '';
        if (!empty($array) && is_array($array)) {
            foreach ($array as $k => $v) {
                $array[$k] = wp_parse_args($v, array(
                    'network' => '',
                    'icon' => '',
                    'link' => '',
                ));

                //Get/Set social icons
                // If icon isset
                $icons = array();
                $array[$k]['icon'] = trim($array[$k]['icon']);

                if ($array[$k]['icon'] != '' && strpos($array[$k]['icon'], 'fa') !== 0) {
                    $icons[$array[$k]['icon']] = 'fa-' . $array[$k]['icon'];
                } else {
                    $icons[$array[$k]['icon']] = $array[$k]['icon'];
                }

                $network = ($array[$k]['network']) ? sanitize_title($array[$k]['network']) : false;
                if ( $network && ! $array[$k]['icon'] ) {
                    $icons['fa-' . $network] = 'fa-' . $network;
                }

                $array[$k]['icon'] = join(' ', $icons);

            }
        }

        foreach ( (array) $array as $s) {
            if ($s['icon'] != '') {
                $html .= '<a target="_blank" href="' . $s['link'] . '" title="' . esc_attr($s['network']) . '"><i class="fa ' . esc_attr($s['icon']) . '"></i></a>';
            }
        }

        return $html;
    }
}


if ( ! function_exists( 'sumerian_get_section_gallery_data' ) ) {

    function sumerian_get_section_gallery_data()
    {

        $source = 'page'; // get_theme_mod( 'sumerian_clients_source' );
        if( has_filter( 'sumerian_get_section_gallery_data' ) ) {
            $data =  apply_filters( 'sumerian_get_section_gallery_data', false );
            return $data;
        }

        $data = array();

        switch ( $source ) {
            default:
                $page_id = get_theme_mod( 'sumerian_clients_source_page' );
                $images = '';
                if ( $page_id ) {
                    $gallery = get_post_gallery( $page_id , false );
                    if ( $gallery ) {
                        $images = $gallery['ids'];
                    }
                }

                $display_type = get_theme_mod( 'sumerian_clients_display', 'grid' );
                if ( $display_type == 'masonry' || $display_type == ' justified' ) {
                    $size = 'large';
                } else {
                    $size = 'sumerian-small';
                }

                $image_thumb_size = apply_filters( 'sumerian_clients_page_img_size', $size );

                if ( ! empty( $images ) ) {
                    $images = explode( ',', $images );
                    foreach ( $images as $post_id ) {
                        $post = get_post( $post_id );
                        if ( $post ) {
                            $img_thumb = wp_get_attachment_image_src($post_id, $image_thumb_size );
                            if ($img_thumb) {
                                $img_thumb = $img_thumb[0];
                            }

                            $img_full = wp_get_attachment_image_src( $post_id, 'full' );
                            if ($img_full) {
                                $img_full = $img_full[0];
                            }

                            if ( $img_thumb && $img_full ) {
                                $data[ $post_id ] = array(
                                    'id'        => $post_id,
                                    'thumbnail' => $img_thumb,
                                    'full'      => $img_full,
                                    'title'     => $post->post_title,
                                    'content'   => $post->post_content,
                                );
                            }
                        }
                    }
                }
            break;
        }

        return $data;

    }
}

/**
 * Generate HTML content for gallery items.
 *
 * @since 1.2.6
 *
 * @param $data
 * @param bool|true $inner
 * @return string
 */
function sumerian_clients_html( $data, $inner = true, $size = 'thumbnail' ) {
    $max_item = get_theme_mod( 'sumerian_g_number', 10 );
    $html = '';
    if ( ! is_array( $data ) ) {
        return $html;
    }
    $n = count( $data );
    if ( $max_item > $n ) {
        $max_item =  $n;
    }
    $i = 0;
    while( $i < $max_item ){
        $photo = current( $data );
        $i ++ ;
        if ( $size == 'full' ) {
            $thumb = $photo['full'];
        } else {
            $thumb = $photo['thumbnail'];
        }




        $html .= '<a href="'.esc_attr( $photo['full'] ).'" class="g-item" title="'.esc_attr( wp_strip_all_tags( $photo['title'] ) ).'">';
        if ( $inner ) {
            $html .= '<span class="inner">';
                $html .= '<span class="inner-content">';
                $html .= '<img src="'.esc_url( $thumb ).'" alt="">';
                $html .= '</span>';
            $html .= '</span>';
        } else {
            $html .= '<img src="'.esc_url( $thumb ).'" alt="">';
        }

        $html .= '</a>';

        next( $data );
    }
    reset( $data );

    return $html;
}


function sumerian_clients_generate( $echo = true ){

    $div = '';

    $data = sumerian_get_section_gallery_data();
    $display_type = get_theme_mod( 'sumerian_clients_display', 'grid' );
    $lightbox = get_theme_mod( 'sumerian_g_lightbox', 1 );
    $class = '';
    if ( $lightbox ) {
        $class = ' enable-lightbox ';
    }
    $col = absint( get_theme_mod( 'sumerian_g_col', 4 ) );
    if ( $col <= 0 ) {
        $col = 4;
    }
    switch( $display_type ) {
        case 'masonry':
            $html = sumerian_clients_html( $data );
            if ( $html ) {
                $div .= '<div data-col="'.$col.'" class="g-zoom-in gallery-masonry '.$class.' gallery-grid g-col-'.$col.'">';
                $div .= $html;
                $div .= '</div>';
            }
            break;
        case 'carousel':
            $html = sumerian_clients_html( $data );
            if ( $html ) {
                $div .= '<div data-col="'.$col.'" class="g-zoom-in gallery-carousel owl-theme owl-carousel owl-carousel'.$class.'">';
                $div .= $html;
                $div .= '</div>';
            }
            break;
        case 'slider':
            $html = sumerian_clients_html( $data , true , 'full' );
            if ( $html ) {
                $div .= '<div class="gallery-slider owl-theme owl-carousel owl-carousel'.$class.'">';
                $div .= $html;
                $div .= '</div>';
            }
            break;
        case 'justified':
            $html = sumerian_clients_html( $data, false );
            if ( $html ) {
                $gallery_spacing = absint( get_theme_mod( 'sumerian_g_spacing', 20 ) );
                $row_height = absint( get_theme_mod( 'sumerian_g_row_height', 120 ) );
                $div .= '<div data-row-height="'.$row_height.'" data-spacing="'.$gallery_spacing.'" class="g-zoom-in gallery-justified'.$class.'">';
                $div .= $html;
                $div .= '</div>';
            }
            break;
        default: // grid
            $html = sumerian_clients_html( $data );
            if ( $html ) {
                $div .= '<div class="gallery-grid g-zoom-in '.$class.' g-col-'.$col .'">';
                $div .= $html;
                $div .= '</div>';
            }
            break;
    }

    if ( $echo ) {
        echo $div;
    } else {
        return $div;
    }

}



if ( ! function_exists( 'sumerian_footer_site_info' ) ) {

    function sumerian_footer_site_info()
    {
       printf(esc_html__('Copyright %1$s %2$s %3$s', 'sumerian'), '&copy;', esc_attr(date('Y')), esc_attr(get_bloginfo()));
    }
}
add_action( 'sumerian_footer_site_info', 'sumerian_footer_site_info' );


/**
 * Breadcrumb NavXT Compatibility.
 */
function sumerian_breadcrumb() {
	if ( function_exists('bcn_display') ) {
        ?>
        <div class="breadcrumbs" typeof="BreadcrumbList" vocab="http://schema.org/">
            <div class="container">
                <?php bcn_display(); ?>
            </div>
        </div>
        <?php
	}
}

if ( ! function_exists( 'sumerian_is_selective_refresh' ) ) {
    function sumerian_is_selective_refresh()
    {
        return isset($GLOBALS['sumerian_is_selective_refresh']) && $GLOBALS['sumerian_is_selective_refresh'] ? true : false;
    }
}

if ( ! function_exists( 'sumerian_footer_widgets' ) ) {
    function sumerian_footer_widgets(){
        $footer_columns = absint( get_theme_mod( 'footer_layout' , 3 ) );
        $max_cols = 12;
        $layouts = 12;
        if ( $footer_columns > 1 ){
            $default = "12";
            switch ( $footer_columns ) {
                case 4:
                    $default = '3+3+3+3';
                    break;
                case 3:
                    $default = '4+4+4';
                    break;
                case 2:
                    $default = '6+6';
                    break;
            }
            $layouts = sanitize_text_field( get_theme_mod( 'footer_custom_'.$footer_columns.'_columns', $default ) );
        }

        $layouts = explode( '+', $layouts );
        foreach ( $layouts as $k => $v ) {
            $v = absint( trim( $v ) );
            $v =  $v >= $max_cols ? $max_cols : $v;
            $layouts[ $k ] = $v;
        }

        $have_widgets = false;

        for ( $count = 0; $count < $footer_columns; $count++ ) {
            $id = 'footer-' . ( $count + 1 );
            if ( is_active_sidebar( $id ) ) {
                $have_widgets = true;
            }
        }

        if ( $footer_columns > 0 && $have_widgets ) { ?>
            <div id="footer-widgets" class="footer-widgets section-padding ">
                <div class="container">
                    <div class="row">
                        <?php
                        for ( $count = 0; $count < $footer_columns; $count++ ) {
                            $col = isset( $layouts[ $count ] ) ? $layouts[ $count ] : '';
                            $id = 'footer-' . ( $count + 1 );
                            if ( $col ) {
                                ?>
                                <div id="footer-<?php echo esc_attr( $count + 1 ) ?>" class="col-md-<?php echo esc_attr( $col ); ?> col-sm-12 footer-column widget-area sidebar" role="complementary">
                                    <?php dynamic_sidebar( $id ); ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php }  ?>
        <?php
    }
}

add_action( 'sumerian_before_site_info', 'sumerian_footer_widgets', 15 );

if ( ! function_exists( 'sumerian_display_page_title' ) ) {

    function sumerian_display_page_title(){
        if ( get_theme_mod( 'sumerian_page_title_bar_disable' ) == 1  ) {
            return;
        }

        if ( ! is_page() && ! is_home() ) {
            return;
        }

        if ( is_page(  ) ) {
            $page_id = get_the_ID();
        } else {
            $page_id = get_option( 'page_for_posts' );
        }

        $hide_page_title = get_post_meta( $page_id, '_hide_page_title', true );
        $classes= array( 'page-header' );
        $img = '';
        if ( has_post_thumbnail( $page_id ) ){
            $classes[] = 'page--cover';
            $img = get_the_post_thumbnail_url($page_id, 'full' );
        }
        ?>
        <?php if ( ! $hide_page_title ){ ?>
            <div class="<?php echo esc_attr( join(' ', $classes ) ); ?>"<?php echo ( $img ) ? ' style="background-image: url(\''.esc_url( $img ).'\')" ': ''; ?>>
                <div class="container">
                    <?php
                    echo '<h1 class="entry-title">';
                    echo get_the_title( $page_id );
                    echo '</h1>';
                    $excerpt = get_the_excerpt( $page_id );
                    if ( $excerpt ) {
                        echo '<div class="entry-tagline">'.$excerpt.'</div>';
                    }
                    ?>
                </div>
            </div>
        <?php } ?>
        <?php
    }
}

add_action( 'sumerian_page_before_content', 'sumerian_display_page_title' );

if ( ! function_exists( 'sumerian_load_section' ) ) {

    function sumerian_load_section( $section_id )
    {
        /**
         * Hook before section
         */
        do_action('sumerian_before_section_' . $section_id);
        do_action('sumerian_before_section_part', $section_id);

        get_template_part('section-parts/section', $section_id );

        /**
         * Hook after section
         */
        do_action('sumerian_after_section_part', $section_id);
        do_action('sumerian_after_section_' . $section_id);
    }
}

if ( ! function_exists('sumerian_load_hero') ) {
    function sumerian_load_hero_section(){
        if ( is_page_template('template-frontpage.php') ) {
            sumerian_load_section( 'hero' );
        }
    }
}

add_action( 'sumerian_header_end', 'sumerian_load_hero_section' );

if ( ! function_exists('sumerian_subscribe_form') ) {

    function sumerian_subscribe_form()
    {
        $sumerian_newsletter_title = wp_kses_post(get_theme_mod('sumerian_newsletter_title', __('Join our Newsletter', 'sumerian')));
        $sumerian_newsletter_mailchimp = wp_kses_post(get_theme_mod('sumerian_newsletter_mailchimp'));
        ?>
        <div class="footer-subscribe">
            <?php if ($sumerian_newsletter_title != '') echo '<h5 class="follow-heading">' . $sumerian_newsletter_title . '</h5>'; ?>
            <form novalidate="" target="_blank" class="" name="mc-embedded-subscribe-form" id="mc-embedded-subscribe-form" method="post"
                  action="<?php if ($sumerian_newsletter_mailchimp != '') {
                      echo $sumerian_newsletter_mailchimp;
                  }; ?>">
                <input type="text" placeholder="<?php esc_attr_e('Enter your e-mail address', 'sumerian'); ?>" id="mce-EMAIL" class="subs_input" name="EMAIL" value="">
                <input type="submit" class="subs-button" value="<?php esc_attr_e('Subscribe', 'sumerian'); ?>" name="subscribe">
            </form>
        </div>
        <?php
    }
}
if ( ! function_exists('sumerian_footer_social_icons' ) ) {
    function sumerian_footer_social_icons()
    {
        $sumerian_social_footer_title = wp_kses_post(get_theme_mod('sumerian_social_footer_title', __('Keep Updated', 'sumerian')));
        ?>
        <div class="footer-social">
            <?php
            if ($sumerian_social_footer_title != '') {
                echo '<h5 class="follow-heading">' . $sumerian_social_footer_title . '</h5>';
            }

            $socials = sumerian_get_social_profiles();

            echo '<div class="footer-social-icons">';
            if ($socials) {
                echo $socials;
            } else {

                $twitter = get_theme_mod('sumerian_social_twitter');
                $facebook = get_theme_mod('sumerian_social_facebook');
                $google = get_theme_mod('sumerian_social_google');
                $instagram = get_theme_mod('sumerian_social_instagram');
                $rss = get_theme_mod('sumerian_social_rss');

                if ($twitter != '') echo '<a target="_blank" href="' . esc_url($twitter) . '" title="Twitter"><i class="fa fa-twitter"></i></a>';
                if ($facebook != '') echo '<a target="_blank" href="' . esc_url($facebook) . '" title="Facebook"><i class="fa fa-facebook"></i></a>';
                if ($google != '') echo '<a target="_blank" href="' . esc_url($google) . '" title="Google Plus"><i class="fa fa-google-plus"></i></a>';
                if ($instagram != '') echo '<a target="_blank" href="' . esc_url($instagram) . '" title="Instagram"><i class="fa fa-instagram"></i></a>';
                if ($rss != '') echo '<a target="_blank" href="' . esc_url($rss) . '"><i class="fa fa-rss"></i></a>';
            }
            echo '</div>';
            ?>
        </div>
        <?php
    }
}

function sumerian_footer_connect(){

    $sumerian_newsletter_disable = sanitize_text_field(get_theme_mod('sumerian_newsletter_disable', '1'));
    $sumerian_social_disable = sanitize_text_field(get_theme_mod('sumerian_social_disable', '1'));

    if ($sumerian_newsletter_disable != '1' || $sumerian_social_disable != '1') : ?>
        <div class="footer-connect">
            <div class="container">
                <div class="row">
                    <?php
                    if ( ! $sumerian_newsletter_disable && ! $sumerian_social_disable ) {
                        if ( ! $sumerian_newsletter_disable ) : ?>
                            <div class="col-md-4 offset-md-2 col-sm-6 offset-md-0">
                                <?php sumerian_subscribe_form(); ?>
                            </div>
                        <?php endif;

                        if ( ! $sumerian_social_disable ) : ?>
                            <div class="col-md-4 col-sm-6">
                                <?php sumerian_footer_social_icons(); ?>
                            </div>
                        <?php endif;
                    } else {
                        echo ' <div class="col-md-8 offset-md-2 col-sm-12 offset-md-0">';
                        if ( ! $sumerian_newsletter_disable )  {
                            sumerian_subscribe_form();
                        } else {
                            sumerian_footer_social_icons();
                        }
                        echo  '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php endif;
}
add_action( 'sumerian_before_site_info', 'sumerian_footer_connect', 25 );
