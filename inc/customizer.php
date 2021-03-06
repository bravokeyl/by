<?php

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function sumerian_customize_register( $wp_customize ) {


	// Load custom controls.
	require get_template_directory() . '/inc/customizer-controls.php';

	// Remove default sections.

	// Custom WP default control & settings.
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	/**
	 * Hook to add other customize
	 */
	do_action( 'sumerian_customize_before_register', $wp_customize );


	$pages  =  get_pages();
	$option_pages = array();
	$option_pages[0] = esc_html__( 'Select page', 'sumerian' );
	foreach( $pages as $p ){
		$option_pages[ $p->ID ] = $p->post_title;
	}

	$users = get_users( array(
		'orderby'      => 'display_name',
		'order'        => 'ASC',
		'number'       => '',
	) );

	$option_users[0] = esc_html__( 'Select member', 'sumerian' );
	foreach( $users as $user ){
		$option_users[ $user->ID ] = $user->display_name;
	}

	/*------------------------------------------------------------------------*/
    /*  Site Identity.
    /*------------------------------------------------------------------------*/

        $is_old_logo = get_theme_mod( 'sumerian_site_image_logo' );

        $wp_customize->add_setting( 'sumerian_hide_sitetitle',
            array(
                'sanitize_callback' => 'sumerian_sanitize_checkbox',
                'default'           => $is_old_logo ? 1: 0,
            )
        );
        $wp_customize->add_control(
            'sumerian_hide_sitetitle',
            array(
                'label' 		=> esc_html__('Hide site title', 'sumerian'),
                'section' 		=> 'title_tagline',
                'type'          => 'checkbox',
            )
        );

        $wp_customize->add_setting( 'sumerian_hide_tagline',
            array(
                'sanitize_callback' => 'sumerian_sanitize_checkbox',
                'default'           => $is_old_logo ? 1: 0,
            )
        );
        $wp_customize->add_control(
            'sumerian_hide_tagline',
            array(
                'label' 		=> esc_html__('Hide site tagline', 'sumerian'),
                'section' 		=> 'title_tagline',
                'type'          => 'checkbox',

            )
        );

    // Retina Logo
    $wp_customize->add_setting( 'sumerian_retina_logo',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
            'transport'			=> 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'sumerian_retina_logo',
            array(
                'label'       => esc_html__('Retina Logo', 'sumerian'),
                'section'     => 'title_tagline',
            )
        )
    );


    // Logo Width
    $wp_customize->add_setting( 'sumerian_logo_height',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
            'transport'			=> 'postMessage'
        )
    );
    $wp_customize->add_control(
        'sumerian_logo_height',
        array(
            'label'       => esc_html__('Logo Height In Pixel', 'sumerian'),
            'section'     => 'title_tagline',
        )

    );

	/*------------------------------------------------------------------------*/
    /*  Site Options
    /*------------------------------------------------------------------------*/
		$wp_customize->add_panel( 'sumerian_options',
			array(
				'priority'       => 5,
			    'capability'     => 'edit_theme_options',
			    'theme_supports' => '',
			    'title'          => esc_html__( 'Theme Options', 'sumerian' ),
			    'description'    => '',
			)
		);

		/* Global Settings
		----------------------------------------------------------------------*/
		$wp_customize->add_section( 'sumerian_global_settings' ,
			array(
				'priority'    => 3,
				'title'       => esc_html__( 'Global', 'sumerian' ),
				'description' => '',
				'panel'       => 'sumerian_options',
			)
		);

            // Sidebar settings
            $wp_customize->add_setting( 'sumerian_layout',
                array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'default'           => 'right-sidebar',
                    //'transport'			=> 'postMessage'
                )
            );
            $wp_customize->add_control( 'sumerian_layout',
                array(
                    'type'        => 'select',
                    'label'       => esc_html__('Site Layout', 'sumerian'),
                    'description'       => esc_html__('Site Layout, apply for all pages, exclude home page and custom page templates.', 'sumerian'),
                    'section'     => 'sumerian_global_settings',
                    'choices' => array(
                        'right-sidebar' => esc_html__('Right sidebar', 'sumerian'),
                        'left-sidebar' => esc_html__('Left sidebar', 'sumerian'),
                        'no-sidebar' => esc_html__('No sidebar', 'sumerian'),
                    )
                )
            );


			// Disable Animation
			$wp_customize->add_setting( 'sumerian_animation_disable',
				array(
					'sanitize_callback' => 'sumerian_sanitize_checkbox',
					'default'           => '',
				)
			);
			$wp_customize->add_control( 'sumerian_animation_disable',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Disable animation effect?', 'sumerian'),
					'section'     => 'sumerian_global_settings',
					'description' => esc_html__('Check this box to disable all element animation when scroll.', 'sumerian')
				)
			);

			// Disable Animation
			$wp_customize->add_setting( 'sumerian_btt_disable',
				array(
					'sanitize_callback' => 'sumerian_sanitize_checkbox',
					'default'           => '',
					'transport'			=> 'postMessage'
				)
			);
			$wp_customize->add_control( 'sumerian_btt_disable',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide footer back to top?', 'sumerian'),
					'section'     => 'sumerian_global_settings',
					'description' => esc_html__('Check this box to hide footer back to top button.', 'sumerian')
				)
			);

            // Disable Google Font
            $wp_customize->add_setting( 'sumerian_disable_g_font',
                array(
                    'sanitize_callback' => 'sumerian_sanitize_checkbox',
                    'default'           => '',
                    'transport'			=> 'postMessage'
                )
            );
            $wp_customize->add_control( 'sumerian_disable_g_font',
                array(
                    'type'        => 'checkbox',
                    'label'       => esc_html__('Disable Google Fonts', 'sumerian'),
                    'section'     => 'sumerian_global_settings',
                    'description' => esc_html__('Check this if you want to disable default google fonts in theme.', 'sumerian')
                )
            );


		/* Colors
		----------------------------------------------------------------------*/
		$wp_customize->add_section( 'sumerian_colors_settings' ,
			array(
				'priority'    => 4,
				'title'       => esc_html__( 'Site Colors', 'sumerian' ),
				'description' => '',
				'panel'       => 'sumerian_options',
			)
		);
			// Primary Color
			$wp_customize->add_setting( 'sumerian_primary_color', array('sanitize_callback' => 'sanitize_hex_color_no_hash', 'sanitize_js_callback' => 'maybe_hash_hex_color', 'default' => '#13b5ea' ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sumerian_primary_color',
				array(
					'label'       => esc_html__( 'Primary Color', 'sumerian' ),
					'section'     => 'sumerian_colors_settings',
					'description' => '',
					'priority'    => 1
				)
			));


		/* Header
		----------------------------------------------------------------------*/
		$wp_customize->add_section( 'sumerian_header_settings' ,
			array(
				'priority'    => 5,
				'title'       => esc_html__( 'Header', 'sumerian' ),
				'description' => '',
				'panel'       => 'sumerian_options',
			)
		);

        // Header width
        $wp_customize->add_setting( 'sumerian_header_width',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => 'contained',
                'transport' => 'postMessage',
            )
        );

        $wp_customize->add_control( 'sumerian_header_width',
            array(
                'type'        => 'select',
                'label'       => esc_html__('Header Width', 'sumerian'),
                'section'     => 'sumerian_header_settings',
                'choices' => array(
                    'full-width' => esc_html__('Full Width', 'sumerian'),
                    'contained' => esc_html__('Contained', 'sumerian')
                )
            )
        );

        // Header Layout
        $wp_customize->add_setting( 'sumerian_header_position',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => 'top',
                'transport' => 'postMessage',
                'active_callback' => 'sumerian_showon_frontpage'
            )
        );

        $wp_customize->add_control( 'sumerian_header_position',
            array(
                'type'        => 'select',
                'label'       => esc_html__('Header Position', 'sumerian'),
                'section'     => 'sumerian_header_settings',
                'choices' => array(
                    'top' => esc_html__('Top', 'sumerian'),
                    'below_hero' => esc_html__('Below Hero Slider', 'sumerian')
                )
            )
        );

        // Disable Sticky Header
        $wp_customize->add_setting( 'sumerian_sticky_header_disable',
            array(
                'sanitize_callback' => 'sumerian_sanitize_checkbox',
                'default'           => '',
                'transport' => 'postMessage',
            )
        );
        $wp_customize->add_control( 'sumerian_sticky_header_disable',
            array(
                'type'        => 'checkbox',
                'label'       => esc_html__('Disable Sticky Header?', 'sumerian'),
                'section'     => 'sumerian_header_settings',
                'description' => esc_html__('Check this box to disable sticky header when scroll.', 'sumerian')
            )
        );



		// Vertical align menu
		$wp_customize->add_setting( 'sumerian_vertical_align_menu',
			array(
				'sanitize_callback' => 'sumerian_sanitize_checkbox',
				'default'           => '',
			)
		);
		$wp_customize->add_control( 'sumerian_vertical_align_menu',
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__('Center vertical align for menu', 'sumerian'),
				'section'     => 'sumerian_header_settings',
				'description' => esc_html__('If you use logo and your logo is too tall, check this box to auto vertical align menu.', 'sumerian')
			)
		);

		// Scroll to top when click to logo
		$wp_customize->add_setting( 'sumerian_header_scroll_logo',
			array(
				'sanitize_callback' => 'sumerian_sanitize_checkbox',
				'default'           => 0,
				'active_callback'   => ''
			)
		);
		$wp_customize->add_control( 'sumerian_header_scroll_logo',
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__('Scroll to top when click to the site logo or site title, only apply on front page.', 'sumerian'),
				'section'     => 'sumerian_header_settings',
			)
		);

		// Header BG Color
		$wp_customize->add_setting( 'sumerian_header_bg_color',
			array(
				'sanitize_callback' => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'default' => ''
			) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sumerian_header_bg_color',
			array(
				'label'       => esc_html__( 'Background Color', 'sumerian' ),
				'section'     => 'sumerian_header_settings',
				'description' => '',
			)
		));


		// Site Title Color
		$wp_customize->add_setting( 'sumerian_logo_text_color',
			array(
				'sanitize_callback' => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'default' => ''
			) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sumerian_logo_text_color',
			array(
				'label'       => esc_html__( 'Site Title Color', 'sumerian' ),
				'section'     => 'sumerian_header_settings',
				'description' => esc_html__( 'Only set if you don\'t use an image logo.', 'sumerian' ),
			)
		));

		// Header Menu Color
		$wp_customize->add_setting( 'sumerian_menu_color',
			array(
				'sanitize_callback' => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'default' => ''
			) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sumerian_menu_color',
			array(
				'label'       => esc_html__( 'Menu Link Color', 'sumerian' ),
				'section'     => 'sumerian_header_settings',
				'description' => '',
			)
		));

		// Header Menu Hover Color
		$wp_customize->add_setting( 'sumerian_menu_hover_color',
			array(
				'sanitize_callback' => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'default' => ''
			) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sumerian_menu_hover_color',
			array(
				'label'       => esc_html__( 'Menu Link Hover/Active Color', 'sumerian' ),
				'section'     => 'sumerian_header_settings',
				'description' => '',

			)
		));

		// Header Menu Hover BG Color
		$wp_customize->add_setting( 'sumerian_menu_hover_bg_color',
			array(
				'sanitize_callback' => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'default' => ''
			) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sumerian_menu_hover_bg_color',
			array(
				'label'       => esc_html__( 'Menu Link Hover/Active BG Color', 'sumerian' ),
				'section'     => 'sumerian_header_settings',
				'description' => '',
			)
		));

		// Responsive Mobile button color
		$wp_customize->add_setting( 'sumerian_menu_toggle_button_color',
			array(
				'sanitize_callback' => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'default' => ''
			) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sumerian_menu_toggle_button_color',
			array(
				'label'       => esc_html__( 'Responsive Menu Button Color', 'sumerian' ),
				'section'     => 'sumerian_header_settings',
				'description' => '',
			)
		));


        // Header Transparent
        $wp_customize->add_setting( 'sumerian_header_transparent',
            array(
                'sanitize_callback' => 'sumerian_sanitize_checkbox',
                'default'           => '',
                'active_callback'   => 'sumerian_showon_frontpage',
                'transport' => 'postMessage',
            )
        );
        $wp_customize->add_control( 'sumerian_header_transparent',
            array(
                'type'        => 'checkbox',
                'label'       => esc_html__('Header Transparent', 'sumerian'),
                'section'     => 'sumerian_header_settings',
                'description' => esc_html__('Apply for front page template only.', 'sumerian')
            )
        );

        // Transparent Logo
        $wp_customize->add_setting( 'sumerian_transparent_logo',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '',
                'transport'			=> 'postMessage'
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Image_Control(
                $wp_customize,
                'sumerian_transparent_logo',
                array(
                    'label'       => esc_html__('Transparent Logo', 'sumerian'),
                    'section'     => 'sumerian_header_settings',
                    'description' => esc_html__('Only apply when transparent header option is checked.', 'sumerian')
                )
            )
        );

        // Transparent Retina Logo
        $wp_customize->add_setting( 'sumerian_transparent_retina_logo',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '',
                'transport'			=> 'postMessage'
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Image_Control(
                $wp_customize,
                'sumerian_transparent_retina_logo',
                array(
                    'label'       => esc_html__('Transparent Retina Logo', 'sumerian'),
                    'description' => esc_html__('Only apply when transparent header option is checked.', 'sumerian'),
                    'section'     => 'sumerian_header_settings',
                )
            )
        );

        $wp_customize->add_setting( 'sumerian_transparent_site_title_c',
            array(
                'sanitize_callback' => 'sanitize_hex_color',
                'default' => ''
            ) );
        $wp_customize->add_control( new WP_Customize_Color_Control(
            $wp_customize,
            'sumerian_transparent_site_title_c',
            array(
                'label'       => esc_html__( 'Transparent Site Title Color', 'sumerian' ),
                'section'     => 'sumerian_header_settings',
                'description' => '',
            )
        ));

        $wp_customize->add_setting( 'sumerian_transparent_tag_title_c',
            array(
                'sanitize_callback' => 'sanitize_hex_color',
                'default' => ''
            )
        );
        $wp_customize->add_control( new WP_Customize_Color_Control(
            $wp_customize,
            'sumerian_transparent_tag_title_c',
            array(
                'label'       => esc_html__( 'Transparent Tagline Color', 'sumerian' ),
                'section'     => 'sumerian_header_settings',
                'description' => '',
            )
        ));


    /* Navigation Settings
    ----------------------------------------------------------------------*/
    $wp_customize->add_section( 'sumerian_nav' ,
        array(
            'priority'    => null,
            'title'       => esc_html__( 'Navigation', 'sumerian' ),
            'description' => '',
            'panel'       => 'sumerian_options',
        )
    );
    $wp_customize->add_setting( 'sumerian_menu_item_padding',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
            'transport' => 'postMessage'
        )
    );
    $wp_customize->add_control( 'sumerian_menu_item_padding',
        array(
            'label'       => esc_html__('Menu Item Padding', 'sumerian'),
            'description' => esc_html__('Padding left and right for Navigation items (pixels).', 'sumerian'),
            'section'     => 'sumerian_nav',
        )
    );

    /* Page Settings
   ----------------------------------------------------------------------*/
    $wp_customize->add_section( 'sumerian_page' ,
        array(
            'priority'    => null,
            'title'       => esc_html__( 'Page Title Area', 'sumerian' ),
            'description' => '',
            'panel'       => 'sumerian_options',
        )
    );

    // Disable the page title bar
    $wp_customize->add_setting( 'sumerian_page_title_bar_disable',
        array(
            'sanitize_callback' => 'sumerian_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'sumerian_page_title_bar_disable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Disable Page Title bar?', 'sumerian'),
            'section'     => 'sumerian_page',
            'description' => esc_html__('Check this box to disable the page title bar on all pages.', 'sumerian')
        )
    );

    $wp_customize->add_setting( 'sumerian_page_cover_pd_top',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
            'transport' => 'postMessage'
        )
    );
    $wp_customize->add_control( 'sumerian_page_cover_pd_top',
        array(
            'label'       => esc_html__('Padding Top', 'sumerian'),
            'description'       => esc_html__('The page cover padding top in percent (%).', 'sumerian'),
            'section'     => 'sumerian_page',
        )
    );

    $wp_customize->add_setting( 'sumerian_page_cover_pd_bottom',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
            'transport' => 'postMessage'
        )
    );
    $wp_customize->add_control( 'sumerian_page_cover_pd_bottom',
        array(
            'label'       => esc_html__('Padding Bottom', 'sumerian'),
            'description'       => esc_html__('The page cover padding bottom in percent (%).', 'sumerian'),
            'section'     => 'sumerian_page',
        )
    );

    $wp_customize->add_setting( 'sumerian_page_cover_color',
        array(
            'sanitize_callback' => 'sumerian_sanitize_color_alpha',
            'default'           => null,
            'transport' => 'postMessage'
        )
    );
    $wp_customize->add_control( new sumerian_Alpha_Color_Control(
            $wp_customize,
            'sumerian_page_cover_color',
            array(
                'label' 		=> esc_html__('Color', 'sumerian'),
                'section' 		=> 'sumerian_page',
            )
        )
    );

    // Overlay color
    $wp_customize->add_setting( 'sumerian_page_cover_overlay',
        array(
            'sanitize_callback' => 'sumerian_sanitize_color_alpha',
            'default'           => 'rgba(0,0,0,.3)',
            'transport' => 'postMessage'
        )
    );
    $wp_customize->add_control( new sumerian_Alpha_Color_Control(
            $wp_customize,
            'sumerian_page_cover_overlay',
            array(
                'label' 		=> esc_html__('Background Overlay Color', 'sumerian'),
                'section' 		=> 'sumerian_page',
            )
        )
    );

    $wp_customize->add_setting( 'sumerian_page_cover_align',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'center',
            'transport' => 'postMessage'
        )
    );
    $wp_customize->add_control( 'sumerian_page_cover_align',
        array(
            'label'       => esc_html__('Content Align', 'sumerian'),
            'section'     => 'sumerian_page',
            'type'        => 'select',
            'choices'     => array(
                'center' => esc_html__('Center', 'sumerian'),
                'left' => esc_html__('Left', 'sumerian'),
                'right' => esc_html__('Right', 'sumerian'),
            ),
        )
    );




    /* Single Settings
    ----------------------------------------------------------------------*/
    $wp_customize->add_section( 'sumerian_single' ,
        array(
            'priority'    => null,
            'title'       => esc_html__( 'Single Post', 'sumerian' ),
            'description' => '',
            'panel'       => 'sumerian_options',
        )
    );

    $wp_customize->add_setting( 'single_thumbnail',
        array(
            'sanitize_callback' => 'sumerian_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'single_thumbnail',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Show single post thumbnail', 'sumerian'),
            'section'     => 'sumerian_single',
            'description' => esc_html__('Check this box to show post thumbnail on single post.', 'sumerian')
        )
    );

    $wp_customize->add_setting( 'single_meta',
        array(
            'sanitize_callback' => 'sumerian_sanitize_checkbox',
            'default'           => '1',
        )
    );
    $wp_customize->add_control( 'single_meta',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Show single post meta', 'sumerian'),
            'section'     => 'sumerian_single',
            'description' => esc_html__('Check this box to show single post meta such as post date, author, category,...', 'sumerian')
        )
    );

    $wp_customize->add_setting( 'single_thumbnail',
        array(
            'sanitize_callback' => 'sumerian_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'single_thumbnail',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Show single post thumbnail', 'sumerian'),
            'section'     => 'sumerian_single',
            'description' => esc_html__('Check this box to show featured image on single post.', 'sumerian')
        )
    );

		/* Footer top Social Settings
		----------------------------------------------------------------------*/
		$wp_customize->add_section( 'onepres_footer_top' ,
			array(
				'title'       => esc_html__( 'Footer Socials', 'sumerian' ),
				'description' => '',
				'panel'       => 'sumerian_options',
			)
		);

			// Disable Social
			$wp_customize->add_setting( 'sumerian_social_disable',
				array(
					'sanitize_callback' => 'sumerian_sanitize_checkbox',
					'default'           => '1',
                    'transport'         => 'postMessage',
				)
			);
			$wp_customize->add_control( 'sumerian_social_disable',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide Footer Social?', 'sumerian'),
					'section'     => 'onepres_footer_top',
					'description' => esc_html__('Check this box to hide footer social section.', 'sumerian')
				)
			);

			$wp_customize->add_setting( 'sumerian_social_footer_guide',
				array(
					'sanitize_callback' => 'sumerian_sanitize_text'
				)
			);
			$wp_customize->add_control( new sumerian_Misc_Control( $wp_customize, 'sumerian_social_footer_guide',
				array(
					'section'     => 'onepres_footer_top',
					'type'        => 'custom_message',
					'description' => esc_html__( 'These social profiles setting below will display at the footer of your site.', 'sumerian' )
				)
			));

			// Footer Social Title
			$wp_customize->add_setting( 'sumerian_social_footer_title',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => esc_html__( 'Keep Updated', 'sumerian' ),
					'transport'			=> 'postMessage',
				)
			);
			$wp_customize->add_control( 'sumerian_social_footer_title',
				array(
					'label'       => esc_html__('Social Footer Title', 'sumerian'),
					'section'     => 'onepres_footer_top',
					'description' => ''
				)
			);

           // Socials
            $wp_customize->add_setting(
                'sumerian_social_profiles',
                array(
                    //'default' => '',
                    'sanitize_callback' => 'sumerian_sanitize_repeatable_data_field',
                    'transport' => 'postMessage', // refresh or postMessage
            ) );

            $wp_customize->add_control(
                new sumerian_Customize_Repeatable_Control(
                    $wp_customize,
                    'sumerian_social_profiles',
                    array(
                        'label' 		=> esc_html__('Socials', 'sumerian'),
                        'description'   => '',
                        'section'       => 'onepres_footer_top',
                        'live_title_id' => 'network', // apply for unput text and textarea only
                        'title_format'  => esc_html__('[live_title]', 'sumerian'), // [live_title]
                        'max_item'      => 5, // Maximum item can add
                        'limited_msg' 	=> wp_kses_post( __( 'Upgrade to <a target="_blank" href="https://www.famethemes.com/plugins/sumerian-plus/?utm_source=theme_customizer&utm_medium=text_link&utm_campaign=sumerian_customizer#get-started">sumerian Plus</a> to be able to add more items and unlock other premium features!', 'sumerian' ) ),
                        'fields'    => array(
                            'network'  => array(
                                'title' => esc_html__('Social network', 'sumerian'),
                                'type'  =>'text',
                            ),
                            'icon'  => array(
                                'title' => esc_html__('Icon', 'sumerian'),
                                'type'  =>'icon',
                            ),
                            'link'  => array(
                                'title' => esc_html__('URL', 'sumerian'),
                                'type'  =>'text',
                            ),
                        ),

                    )
                )
            );


		/* Newsletter Settings
		----------------------------------------------------------------------*/

			// Disable Newsletter
			$wp_customize->add_setting( 'sumerian_newsletter_disable',
				array(
					'sanitize_callback' => 'sumerian_sanitize_checkbox',
					'default'           => '1',
                    'transport'         => 'postMessage',
				)
			);
			$wp_customize->add_control( 'sumerian_newsletter_disable',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide Footer Newsletter?', 'sumerian'),
					'section'     => 'onepres_footer_top',
					'description' => esc_html__('Check this box to hide footer newsletter form.', 'sumerian')
				)
			);

			// Mailchimp Form Title
			$wp_customize->add_setting( 'sumerian_newsletter_title',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => esc_html__( 'Join our Newsletter', 'sumerian' ),
                    'transport'         => 'postMessage', // refresh or postMessage
				)
			);
			$wp_customize->add_control( 'sumerian_newsletter_title',
				array(
					'label'       => esc_html__('Newsletter Form Title', 'sumerian'),
					'section'     => 'onepres_footer_top',
					'description' => ''
				)
			);

			// Mailchimp action url
			$wp_customize->add_setting( 'sumerian_newsletter_mailchimp',
				array(
					'sanitize_callback' => 'esc_url',
					'default'           => '',
                    'transport'         => 'postMessage', // refresh or postMessage
				)
			);
			$wp_customize->add_control( 'sumerian_newsletter_mailchimp',
				array(
					'label'       => esc_html__('MailChimp Action URL', 'sumerian'),
					'section'     => 'onepres_footer_top',
					'description' => __( 'The newsletter form use MailChimp, please follow <a target="_blank" href="http://goo.gl/uRVIst">this guide</a> to know how to get MailChimp Action URL. Example <i>//famethemes.us8.list-manage.com/subscribe/post?u=521c400d049a59a4b9c0550c2&amp;id=83187e0006</i>', 'sumerian' )
				)
			);

            // Footer BG Color
            $wp_customize->add_setting( 'sumerian_footer_bg', array(
                'sanitize_callback' => 'sanitize_hex_color_no_hash',
                'sanitize_js_callback' => 'maybe_hash_hex_color',
                'default' => '',
                'transport' => 'postMessage'
            ) );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sumerian_footer_bg',
                array(
                    'label'       => esc_html__( 'Background', 'sumerian' ),
                    'section'     => 'onepres_footer_top',
                    'description' => '',
                )
            ));


            $wp_customize->add_setting( 'sumerian_footer_top_color', array(
                'sanitize_callback' => 'sanitize_hex_color',
                'default' => '',
                'transport' => 'postMessage'
            ) );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sumerian_footer_top_color',
                array(
                    'label'       => esc_html__( 'Text Color', 'sumerian' ),
                    'section'     => 'onepres_footer_top',
                    'description' => '',
                )
            ));





    /* Footer Widgets Settings
    ----------------------------------------------------------------------*/
    $wp_customize->add_section( 'sumerian_footer' ,
        array(
            'priority'    => null,
            'title'       => esc_html__( 'Footer Widgets', 'sumerian' ),
            'description' => '',
            'panel'       => 'sumerian_options',
        )
    );

    $wp_customize->add_setting( 'footer_layout',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
            'transport' => 'postMessage',
        )
    );

    $wp_customize->add_control( 'footer_layout',
        array(
            'type'        => 'select',
            'label'       => esc_html__('Layout', 'sumerian'),
            'section'     => 'sumerian_footer',
            'default' => '0',
            'description' => esc_html__('Number footer columns to display.', 'sumerian'),
            'choices' => array(
                '4' => 4,
                '3' => 3,
                '2' => 2,
                '1' => 1,
                '0' => esc_html__('Disable footer widgets', 'sumerian'),
            )
        )
    );

    for ( $i = 1; $i<=4; $i ++ ) {
        $df = 12;
        if ( $i > 1 ) {
            $_n = 12/$i;
            $df = array();
            for ( $j = 0; $j < $i; $j++ ) {
                $df[ $j ] = $_n;
            }
            $df = join( '+', $df );
        }
        $wp_customize->add_setting('footer_custom_'.$i.'_columns',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default' => $df,
                'transport' => 'postMessage',
            )
        );
        $wp_customize->add_control('footer_custom_'.$i.'_columns',
            array(
                'label' => $i == 1 ? __('Custom footer 1 column width', 'sumerian') : sprintf( __('Custom footer %s columns width', 'sumerian'), $i ),
                'section' => 'sumerian_footer',
                'description' => esc_html__('Enter int numbers and sum of them must smaller or equal 12, separated by "+"', 'sumerian'),
            )
        );
    }

    // sumerian_sanitize_color_alpha
        $wp_customize->add_setting( 'footer_widgets_color',
            array(
                'sanitize_callback' => 'sanitize_hex_color',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new WP_Customize_Color_Control(
                $wp_customize,
                'footer_widgets_color',
                array(
                    'label' 		=> esc_html__('Text Color', 'sumerian'),
                    'section' 		=> 'sumerian_footer',
                )
            )
        );

        $wp_customize->add_setting( 'footer_widgets_bg_color',
            array(
                'sanitize_callback' => 'sanitize_hex_color',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new WP_Customize_Color_Control(
                $wp_customize,
                'footer_widgets_bg_color',
                array(
                    'label' 		=> esc_html__('Background Color', 'sumerian'),
                    'section' 		=> 'sumerian_footer',
                )
            )
        );

        // Footer Heading color
        $wp_customize->add_setting( 'footer_widgets_title_color',
            array(
                'sanitize_callback' => 'sanitize_hex_color',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new WP_Customize_Color_Control(
                $wp_customize,
                'footer_widgets_title_color',
                array(
                    'label' 		=> esc_html__('Widget Title Color', 'sumerian'),
                    'section' 		=> 'sumerian_footer',
                )
            )
        );


        $wp_customize->add_setting( 'footer_widgets_link_color',
            array(
                'sanitize_callback' => 'sanitize_hex_color',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new WP_Customize_Color_Control(
                $wp_customize,
                'footer_widgets_link_color',
                array(
                    'label' 		=> esc_html__('Link Color', 'sumerian'),
                    'section' 		=> 'sumerian_footer',
                )
            )
        );

        $wp_customize->add_setting( 'footer_widgets_link_hover_color',
            array(
                'sanitize_callback' => 'sanitize_hex_color',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new WP_Customize_Color_Control(
                $wp_customize,
                'footer_widgets_link_hover_color',
                array(
                    'label' 		=> esc_html__('Link Hover Color', 'sumerian'),
                    'section' 		=> 'sumerian_footer',
                )
            )
        );



    /* Footer Copyright Settings
    ----------------------------------------------------------------------*/
    $wp_customize->add_section( 'sumerian_footer_copyright' ,
        array(
            'priority'    => null,
            'title'       => esc_html__( 'Footer Copyright', 'sumerian' ),
            'description' => '',
            'panel'       => 'sumerian_options',
        )
    );

    // Footer Widgets Color
    $wp_customize->add_setting( 'sumerian_footer_info_bg', array(
        'sanitize_callback' => 'sanitize_hex_color',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
        'default' => '',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sumerian_footer_info_bg',
        array(
            'label'       => esc_html__( 'Background', 'sumerian' ),
            'section'     => 'sumerian_footer_copyright',
            'description' => '',
        )
    ));

    // Footer Widgets Color
    $wp_customize->add_setting( 'sumerian_footer_c_color', array(
        'sanitize_callback' => 'sanitize_hex_color',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
        'default' => '',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sumerian_footer_c_color',
        array(
            'label'       => esc_html__( 'Text Color', 'sumerian' ),
            'section'     => 'sumerian_footer_copyright',
            'description' => '',
        )
    ));

    $wp_customize->add_setting( 'sumerian_footer_c_link_color', array(
        'sanitize_callback' => 'sanitize_hex_color',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
        'default' => '',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sumerian_footer_c_link_color',
        array(
            'label'       => esc_html__( 'Link Color', 'sumerian' ),
            'section'     => 'sumerian_footer_copyright',
            'description' => '',
        )
    ));

    $wp_customize->add_setting( 'sumerian_footer_c_link_hover_color', array(
        'sanitize_callback' => 'sanitize_hex_color',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
        'default' => '',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sumerian_footer_c_link_hover_color',
        array(
            'label'       => esc_html__( 'Link Hover Color', 'sumerian' ),
            'section'     => 'sumerian_footer_copyright',
            'description' => '',
        )
    ));


    if ( ! function_exists( 'wp_get_custom_css' ) ) {  // Back-compat for WordPress < 4.7.

                /* Custom CSS Settings
                ----------------------------------------------------------------------*/
                $wp_customize->add_section(
                    'sumerian_custom_code',
                    array(
                        'title' => __('Custom CSS', 'sumerian'),
                        'panel' => 'sumerian_options',
                    )
                );


                $wp_customize->add_setting(
                    'sumerian_custom_css',
                    array(
                        'default' => '',
                        'sanitize_callback' => 'sumerian_sanitize_css',
                        'type' => 'option',
                    )
                );

                $wp_customize->add_control(
                    'sumerian_custom_css',
                    array(
                        'label' => __('Custom CSS', 'sumerian'),
                        'section' => 'sumerian_custom_code',
                        'type' => 'textarea'
                    )
                );
            } else {
                $wp_customize->get_section( 'custom_css' )->priority = 994;
            }


	/*------------------------------------------------------------------------*/
    /*  Section: Hero
    /*------------------------------------------------------------------------*/

	$wp_customize->add_panel( 'sumerian_hero_panel' ,
		array(
			'priority'        => 130,
			'title'           => esc_html__( 'Section: Hero', 'sumerian' ),
			'description'     => '',
			'active_callback' => 'sumerian_showon_frontpage'
		)
	);

		// Hero settings
		$wp_customize->add_section( 'sumerian_hero_settings' ,
			array(
				'priority'    => 3,
				'title'       => esc_html__( 'Hero Settings', 'sumerian' ),
				'description' => '',
				'panel'       => 'sumerian_hero_panel',
			)
		);

			// Show section
			$wp_customize->add_setting( 'sumerian_hero_disable',
				array(
					'sanitize_callback' => 'sumerian_sanitize_checkbox',
					'default'           => '',
				)
			);
			$wp_customize->add_control( 'sumerian_hero_disable',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide this section?', 'sumerian'),
					'section'     => 'sumerian_hero_settings',
					'description' => esc_html__('Check this box to hide this section.', 'sumerian'),
				)
			);
			// Section ID
			$wp_customize->add_setting( 'sumerian_hero_id',
				array(
					'sanitize_callback' => 'sumerian_sanitize_text',
					'default'           => esc_html__('hero', 'sumerian'),
				)
			);
			$wp_customize->add_control( 'sumerian_hero_id',
				array(
					'label' 		=> esc_html__('Section ID:', 'sumerian'),
					'section' 		=> 'sumerian_hero_settings',
					'description'   => esc_html__( 'The section id, we will use this for link anchor.', 'sumerian' )
				)
			);

			// Show hero full screen
			$wp_customize->add_setting( 'sumerian_hero_fullscreen',
				array(
					'sanitize_callback' => 'sumerian_sanitize_checkbox',
					'default'           => '',
				)
			);
			$wp_customize->add_control( 'sumerian_hero_fullscreen',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Make hero section full screen', 'sumerian'),
					'section'     => 'sumerian_hero_settings',
					'description' => esc_html__('Check this box to make hero section full screen.', 'sumerian'),
				)
			);

            // Show hero full screen
            $wp_customize->add_setting( 'sumerian_hero_disable_preload',
                array(
                    'sanitize_callback' => 'sumerian_sanitize_checkbox',
                    'default'           => '',
                )
            );
            $wp_customize->add_control( 'sumerian_hero_disable_preload',
                array(
                    'type'        => 'checkbox',
                    'label'       => esc_html__('Disable Preload Icon', 'sumerian'),
                    'section'     => 'sumerian_hero_settings',
                )
            );

			// Hero content padding top
			$wp_customize->add_setting( 'sumerian_hero_pdtop',
				array(
					'sanitize_callback' => 'sumerian_sanitize_text',
					'default'           => esc_html__('10', 'sumerian'),
				)
			);
			$wp_customize->add_control( 'sumerian_hero_pdtop',
				array(
					'label'           => esc_html__('Padding Top:', 'sumerian'),
					'section'         => 'sumerian_hero_settings',
					'description'     => esc_html__( 'The hero content padding top in percent (%).', 'sumerian' ),
					'active_callback' => 'sumerian_hero_fullscreen_callback'
				)
			);

			// Hero content padding bottom
			$wp_customize->add_setting( 'sumerian_hero_pdbotom',
				array(
					'sanitize_callback' => 'sumerian_sanitize_text',
					'default'           => esc_html__('10', 'sumerian'),
				)
			);
			$wp_customize->add_control( 'sumerian_hero_pdbotom',
				array(
					'label'           => esc_html__('Padding Bottom:', 'sumerian'),
					'section'         => 'sumerian_hero_settings',
					'description'     => esc_html__( 'The hero content padding bottom in percent (%).', 'sumerian' ),
					'active_callback' => 'sumerian_hero_fullscreen_callback'
				)
			);


            /* Hero options
            ----------------------------------------------------------------------*/

            $wp_customize->add_setting(
                'sumerian_hero_option_animation',
                array(
                    'default'              => 'flipInX',
                    'sanitize_callback'    => 'sanitize_text_field',
                )
            );

            /**
             * @see https://github.com/daneden/animate.css
             */

            $animations_css = 'bounce flash pulse rubberBand shake headShake swing tada wobble jello bounceIn bounceInDown bounceInLeft bounceInRight bounceInUp bounceOut bounceOutDown bounceOutLeft bounceOutRight bounceOutUp fadeIn fadeInDown fadeInDownBig fadeInLeft fadeInLeftBig fadeInRight fadeInRightBig fadeInUp fadeInUpBig fadeOut fadeOutDown fadeOutDownBig fadeOutLeft fadeOutLeftBig fadeOutRight fadeOutRightBig fadeOutUp fadeOutUpBig flipInX flipInY flipOutX flipOutY lightSpeedIn lightSpeedOut rotateIn rotateInDownLeft rotateInDownRight rotateInUpLeft rotateInUpRight rotateOut rotateOutDownLeft rotateOutDownRight rotateOutUpLeft rotateOutUpRight hinge rollIn rollOut zoomIn zoomInDown zoomInLeft zoomInRight zoomInUp zoomOut zoomOutDown zoomOutLeft zoomOutRight zoomOutUp slideInDown slideInLeft slideInRight slideInUp slideOutDown slideOutLeft slideOutRight slideOutUp';

            $animations_css = explode( ' ', $animations_css );
            $animations = array();
            foreach ( $animations_css as $v ) {
                $v =  trim( $v );
                if ( $v ){
                    $animations[ $v ]= $v;
                }

            }

            $wp_customize->add_control(
                'sumerian_hero_option_animation',
                array(
                    'label'    => __( 'Text animation', 'sumerian' ),
                    'section'  => 'sumerian_hero_settings',
                    'type'     => 'select',
                    'choices' => $animations,
                )
            );


            $wp_customize->add_setting(
                'sumerian_hero_option_speed',
                array(
                    'default'              => '5000',
                    'sanitize_callback'    => 'sanitize_text_field',
                )
            );

            $wp_customize->add_control(
                'sumerian_hero_option_speed',
                array(
                    'label'    => __( 'Text animation speed', 'sumerian' ),
                    'description' => esc_html__( 'The delay between the changing of each phrase in milliseconds.', 'sumerian' ),
                    'section'  => 'sumerian_hero_settings',
                )
            );


        $wp_customize->add_setting(
            'sumerian_hero_slider_fade',
            array(
                'default'              => '750',
                'sanitize_callback'    => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'sumerian_hero_slider_fade',
            array(
                'label'    => __( 'Slider animation speed', 'sumerian' ),
                'description' => esc_html__( 'This is the speed at which the image will fade in. Integers in milliseconds are accepted.', 'sumerian' ),
                'section'  => 'sumerian_hero_settings',
            )
        );

        $wp_customize->add_setting(
            'sumerian_hero_slider_duration',
            array(
                'default'              => '5000',
                'sanitize_callback'    => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'sumerian_hero_slider_duration',
            array(
                'label'    => __( 'Slider duration speed', 'sumerian' ),
                'description' => esc_html__( 'The amount of time in between slides, expressed as the number of milliseconds.', 'sumerian' ),
                'section'  => 'sumerian_hero_settings',
            )
        );



		$wp_customize->add_section( 'sumerian_hero_images' ,
			array(
				'priority'    => 6,
				'title'       => esc_html__( 'Hero Background Media', 'sumerian' ),
				'description' => '',
				'panel'       => 'sumerian_hero_panel',
			)
		);

			$wp_customize->add_setting(
				'sumerian_hero_images',
				array(
					'sanitize_callback' => 'sumerian_sanitize_repeatable_data_field',
					'transport' => 'refresh', // refresh or postMessage
					'default' => json_encode( array(
						array(
							'image'=> array(
								'url' => get_template_directory_uri().'/assets/images/hero5.jpg',
								'id' => ''
							)
						)
					) )
				) );

			$wp_customize->add_control(
				new sumerian_Customize_Repeatable_Control(
					$wp_customize,
					'sumerian_hero_images',
					array(
						'label'     => esc_html__('Background Images', 'sumerian'),
						'description'   => '',
						'priority'     => 40,
						'section'       => 'sumerian_hero_images',
						'title_format'  => esc_html__( 'Background', 'sumerian'), // [live_title]
						'max_item'      => 2, // Maximum item can add

						'fields'    => array(
							'image' => array(
								'title' => esc_html__('Background Image', 'sumerian'),
								'type'  =>'media',
								'default' => array(
									'url' => get_template_directory_uri().'/assets/images/hero5.jpg',
									'id' => ''
								)
							),

						),

					)
				)
			);

			// Overlay color
			$wp_customize->add_setting( 'sumerian_hero_overlay_color',
				array(
					'sanitize_callback' => 'sumerian_sanitize_color_alpha',
					'default'           => 'rgba(0,0,0,.3)',
					//'transport' => 'refresh', // refresh or postMessage
				)
			);
			$wp_customize->add_control( new sumerian_Alpha_Color_Control(
					$wp_customize,
					'sumerian_hero_overlay_color',
					array(
						'label' 		=> esc_html__('Background Overlay Color', 'sumerian'),
						'section' 		=> 'sumerian_hero_images',
						'priority'      => 130,
					)
				)
			);


            // Parallax
            $wp_customize->add_setting( 'sumerian_hero_parallax',
                array(
                    'sanitize_callback' => 'sumerian_sanitize_checkbox',
                    'default'           => 0,
                    'transport' => 'refresh', // refresh or postMessage
                )
            );
            $wp_customize->add_control(
                'sumerian_hero_parallax',
                array(
                    'label' 		=> esc_html__('Enable parallax effect (apply for first BG image only)', 'sumerian'),
                    'section' 		=> 'sumerian_hero_images',
                    'type' 		   => 'checkbox',
                    'priority'      => 50,
                    'description' => '',
                )
            );

			// Background Video
			$wp_customize->add_setting( 'sumerian_hero_videobackground_upsell',
				array(
					'sanitize_callback' => 'sumerian_sanitize_text',
				)
			);
			$wp_customize->add_control( new sumerian_Misc_Control( $wp_customize, 'sumerian_hero_videobackground_upsell',
				array(
					'section'     => 'sumerian_hero_images',
					'type'        => 'custom_message',
					'description' => wp_kses_post( __( 'Want to add <strong>background video</strong> for hero section? Upgrade to <a target="_blank" href="https://www.famethemes.com/plugins/sumerian-plus/?utm_source=theme_customizer&utm_medium=text_link&utm_campaign=sumerian_customizer#get-started">sumerian Plus</a> version.', 'sumerian' ) ),
					'priority'    => 131,
				)
			));



		$wp_customize->add_section( 'sumerian_hero_content_layout1' ,
			array(
				'priority'    => 9,
				'title'       => esc_html__( 'Hero Content Layout', 'sumerian' ),
				'description' => '',
				'panel'       => 'sumerian_hero_panel',

			)
		);

			// Hero Layout
			$wp_customize->add_setting( 'sumerian_hero_layout',
				array(
					'sanitize_callback' => 'sumerian_sanitize_text',
					'default'           => '1',
				)
			);
			$wp_customize->add_control( 'sumerian_hero_layout',
				array(
					'label' 		=> esc_html__('Display Layout', 'sumerian'),
					'section' 		=> 'sumerian_hero_content_layout1',
					'description'   => '',
					'type'          => 'select',
					'choices'       => array(
						'1' => esc_html__('Layout 1', 'sumerian' ),
						'2' => esc_html__('Layout 2', 'sumerian' ),
					),
				)
			);
			// For Hero layout ------------------------

				// Large Text
				$wp_customize->add_setting( 'sumerian_hcl1_largetext',
					array(
						'sanitize_callback' => 'sumerian_sanitize_text',
						'mod' 				=> 'html',
						'default'           => wp_kses_post( __( 'A SOCIAL EMPLOYEE ENGAGEMENT PORTAL', 'sumerian') ),
					)
				);
				$wp_customize->add_control( new sumerian_Editor_Custom_Control(
					$wp_customize,
					'sumerian_hcl1_largetext',
					array(
						'label' 		=> esc_html__('Large Text', 'sumerian'),
						'section' 		=> 'sumerian_hero_content_layout1',
						'description'   => esc_html__('', 'sumerian'),
					)
				));


                $wp_customize->add_setting( 'sumerian_hcl1_r_color',
                    array(
                        'sanitize_callback' => 'sanitize_hex_color',
                        'default'           => null,
                    )
                );
                $wp_customize->add_control( new WP_Customize_Color_Control(
                        $wp_customize,
                        'sumerian_hcl1_r_color',
                        array(
                            'label' 		=> esc_html__('Rotating Text Color', 'sumerian'),
                            'section' 		=> 'sumerian_hero_content_layout1'
                        )
                    )
                );
                $wp_customize->add_setting( 'sumerian_hcl1_r_bg_color',
                    array(
                        'sanitize_callback' => 'sanitize_hex_color',
                        'default'           => null,
                    )
                );
                $wp_customize->add_control( new WP_Customize_Color_Control(
                        $wp_customize,
                        'sumerian_hcl1_r_bg_color',
                        array(
                            'label' 		=> esc_html__('Rotating Text Background', 'sumerian'),
                            'section' 		=> 'sumerian_hero_content_layout1'
                        )
                    )
                );

				// Small Text
				$wp_customize->add_setting( 'sumerian_hcl1_smalltext',
					array(
						'sanitize_callback' => 'sumerian_sanitize_text',
						'default'			=> wp_kses_post('', 'sumerian'),
					)
				);
				$wp_customize->add_control( new sumerian_Editor_Custom_Control(
					$wp_customize,
					'sumerian_hcl1_smalltext',
					array(
						'label' 		=> esc_html__('Small Text', 'sumerian'),
						'section' 		=> 'sumerian_hero_content_layout1',
						'mod' 				=> 'html',
						'description'   => esc_html__('You can use text rotate slider in this textarea too.', 'sumerian'),
					)
				));

				// Button #1 Text
				$wp_customize->add_setting( 'sumerian_hcl1_btn1_text',
					array(
						'sanitize_callback' => 'sumerian_sanitize_text',
						'default'           => esc_html__('About Us', 'sumerian'),
					)
				);
				$wp_customize->add_control( 'sumerian_hcl1_btn1_text',
					array(
						'label' 		=> esc_html__('Button #1 Text', 'sumerian'),
						'section' 		=> 'sumerian_hero_content_layout1'
					)
				);

				// Button #1 Link
				$wp_customize->add_setting( 'sumerian_hcl1_btn1_link',
					array(
						'sanitize_callback' => 'esc_url',
						'default'           => esc_url( home_url( '/' )).esc_html__('#about', 'sumerian'),
					)
				);
				$wp_customize->add_control( 'sumerian_hcl1_btn1_link',
					array(
						'label' 		=> esc_html__('Button #1 Link', 'sumerian'),
						'section' 		=> 'sumerian_hero_content_layout1'
					)
				);
                // Button #1 Style
				$wp_customize->add_setting( 'sumerian_hcl1_btn1_style',
					array(
						'sanitize_callback' => 'sumerian_sanitize_text',
						'default'           => 'btn-theme-primary',
					)
				);
				$wp_customize->add_control( 'sumerian_hcl1_btn1_style',
					array(
						'label' 		=> esc_html__('Button #1 style', 'sumerian'),
						'section' 		=> 'sumerian_hero_content_layout1',
                        'type'          => 'select',
                        'choices' => array(
                                'btn-theme-primary' => esc_html__('Button Primary', 'sumerian'),
                                'btn-secondary-outline' => esc_html__('Button Secondary', 'sumerian'),
                                'btn-default' => esc_html__('Button', 'sumerian'),
                                'btn-primary' => esc_html__('Primary', 'sumerian'),
                                'btn-success' => esc_html__('Success', 'sumerian'),
                                'btn-info' => esc_html__('Info', 'sumerian'),
                                'btn-warning' => esc_html__('Warning', 'sumerian'),
                                'btn-danger' => esc_html__('Danger', 'sumerian'),
                        )
					)
				);

				// Button #2 Text
				$wp_customize->add_setting( 'sumerian_hcl1_btn2_text',
					array(
						'sanitize_callback' => 'sumerian_sanitize_text',
						'default'           => esc_html__('Get Started', 'sumerian'),
					)
				);
				$wp_customize->add_control( 'sumerian_hcl1_btn2_text',
					array(
						'label' 		=> esc_html__('Button #2 Text', 'sumerian'),
						'section' 		=> 'sumerian_hero_content_layout1'
					)
				);

				// Button #2 Link
				$wp_customize->add_setting( 'sumerian_hcl1_btn2_link',
					array(
						'sanitize_callback' => 'esc_url',
						'default'           => esc_url( home_url( '/' )).esc_html__('#contact', 'sumerian'),
					)
				);
				$wp_customize->add_control( 'sumerian_hcl1_btn2_link',
					array(
						'label' 		=> esc_html__('Button #2 Link', 'sumerian'),
						'section' 		=> 'sumerian_hero_content_layout1'
					)
				);

                // Button #1 Style
                $wp_customize->add_setting( 'sumerian_hcl1_btn2_style',
                    array(
                        'sanitize_callback' => 'sumerian_sanitize_text',
                        'default'           => 'btn-secondary-outline',
                    )
                );
                $wp_customize->add_control( 'sumerian_hcl1_btn2_style',
                    array(
                        'label' 		=> esc_html__('Button #2 style', 'sumerian'),
                        'section' 		=> 'sumerian_hero_content_layout1',
                        'type'          => 'select',
                        'choices' => array(
                            'btn-theme-primary' => esc_html__('Button Primary', 'sumerian'),
                            'btn-secondary-outline' => esc_html__('Button Secondary', 'sumerian'),
                            'btn-default' => esc_html__('Button', 'sumerian'),
                            'btn-primary' => esc_html__('Primary', 'sumerian'),
                            'btn-success' => esc_html__('Success', 'sumerian'),
                            'btn-info' => esc_html__('Info', 'sumerian'),
                            'btn-warning' => esc_html__('Warning', 'sumerian'),
                            'btn-danger' => esc_html__('Danger', 'sumerian'),
                        )
                    )
                );


				/* Layout 2 ---- */

				// Layout 22 content text
				$wp_customize->add_setting( 'sumerian_hcl2_content',
					array(
						'sanitize_callback' => 'sumerian_sanitize_text',
						'mod' 				=> 'html',
						'default'           =>  wp_kses_post( '<h1>Business Website'."\n".'Made Simple.</h1>'."\n".'We provide creative solutions to clients around the world,'."\n".'creating things that get attention and meaningful.'."\n\n".'<a class="btn btn-secondary-outline btn-lg" href="#">Get Started</a>' ),
					)
				);
				$wp_customize->add_control( new sumerian_Editor_Custom_Control(
					$wp_customize,
					'sumerian_hcl2_content',
					array(
						'label' 		=> esc_html__('Content Text', 'sumerian'),
						'section' 		=> 'sumerian_hero_content_layout1',
						'description'   => '',
					)
				));

				// Layout 2 image
				$wp_customize->add_setting( 'sumerian_hcl2_image',
					array(
						'sanitize_callback' => 'sumerian_sanitize_text',
						'mod' 				=> 'html',
						'default'           =>  get_template_directory_uri().'/assets/images/sumerian_responsive.png',
					)
				);
				$wp_customize->add_control( new WP_Customize_Image_Control(
					$wp_customize,
					'sumerian_hcl2_image',
					array(
						'label' 		=> esc_html__('Image', 'sumerian'),
						'section' 		=> 'sumerian_hero_content_layout1',
						'description'   => '',
					)
				));


			// END For Hero layout ------------------------

	/*------------------------------------------------------------------------*/
	/*  Section: Sales
	/*------------------------------------------------------------------------*/
	$wp_customize->add_panel( 'sumerian_sales' ,
		array(
			'priority'        => 180,
			'title'           => esc_html__( 'Section: Sales', 'sumerian' ),
			'description'     => '',
			'active_callback' => 'sumerian_showon_frontpage'
		)
	);

    $wp_customize->add_section( 'sumerian_sales_settings' ,
        array(
            'priority'    => 3,
            'title'       => esc_html__( 'Section Settings', 'sumerian' ),
            'description' => '',
            'panel'       => 'sumerian_sales',
        )
    );

    // Show Content
    $wp_customize->add_setting( 'sumerian_sales_disable',
        array(
            'sanitize_callback' => 'sumerian_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'sumerian_sales_disable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Hide this section?', 'sumerian'),
            'section'     => 'sumerian_sales_settings',
            'description' => esc_html__('Check this box to hide this section.', 'sumerian'),
        )
    );

    // Section ID
    $wp_customize->add_setting( 'sumerian_sales_id',
        array(
            'sanitize_callback' => 'sumerian_sanitize_text',
            'default'           => 'sales',
        )
    );
    $wp_customize->add_control( 'sumerian_sales_id',
        array(
            'label' 		=> esc_html__('Section ID:', 'sumerian'),
            'section' 		=> 'sumerian_sales_settings',
            'description'   => esc_html__('The section id, we will use this for link anchor.', 'sumerian' )
        )
    );

    // Title
    $wp_customize->add_setting( 'sumerian_sales_title',
        array(
            'sanitize_callback' => 'sumerian_sanitize_text',
            'default'           => '',
        )
    );

    $wp_customize->add_control(  'sumerian_sales_title',
        array(
            'label'     	=>  esc_html__('Section heading', 'sumerian'),
            'section' 		=> 'sumerian_sales_settings',
            'description'   => '',
        )
    );

		// Content

    $wp_customize->add_setting( 'sumerian_sales_desc',
        array(
            'sanitize_callback' => 'sumerian_sanitize_text',
            'default'           => '',
        )
    );
		$wp_customize->add_control( new sumerian_Editor_Custom_Control(
        $wp_customize,
        'sumerian_sales_desc',
        array(
            'label'     	=>  esc_html__('Section Content', 'sumerian'),
            'section' 		=> 'sumerian_sales_settings',
            'description'   => '',
        )
    ));

    // Parallax image
    $wp_customize->add_setting( 'sumerian_sales_image',
        array(
            'sanitize_callback' => 'esc_url_raw',
            'default'           => '',
        )
    );
    $wp_customize->add_control( new WP_Customize_Image_Control(
        $wp_customize,
        'sumerian_sales_image',
        array(
            'label' 		=> esc_html__('Background image', 'sumerian'),
            'section' 		=> 'sumerian_sales_settings',
        )
    ));


	/*------------------------------------------------------------------------*/
	/*  Section: Clients
    /*------------------------------------------------------------------------*/
	$wp_customize->add_panel( 'sumerian_clients' ,
		array(
			'priority'        => 190,
			'title'           => esc_html__( 'Section: Clients', 'sumerian' ),
			'description'     => '',
			'active_callback' => 'sumerian_showon_frontpage'
		)
	);

	$wp_customize->add_section( 'sumerian_clients_settings' ,
		array(
			'priority'    => 3,
			'title'       => esc_html__( 'Section Settings', 'sumerian' ),
			'description' => '',
			'panel'       => 'sumerian_clients',
		)
	);

	// Show Content
	$wp_customize->add_setting( 'sumerian_clients_disable',
		array(
			'sanitize_callback' => 'sumerian_sanitize_checkbox',
			'default'           => 1,
		)
	);
	$wp_customize->add_control( 'sumerian_clients_disable',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__('Hide this section?', 'sumerian'),
			'section'     => 'sumerian_clients_settings',
			'description' => esc_html__('Check this box to hide this section.', 'sumerian'),
		)
	);

	// Section ID
	$wp_customize->add_setting( 'sumerian_clients_id',
		array(
			'sanitize_callback' => 'sumerian_sanitize_text',
			'default'           => esc_html__('clients', 'sumerian'),
		)
	);
	$wp_customize->add_control( 'sumerian_clients_id',
		array(
			'label'     => esc_html__('Section ID:', 'sumerian'),
			'section' 		=> 'sumerian_clients_settings',
			'description'   => esc_html__( 'The section id, we will use this for link anchor.', 'sumerian' )
		)
	);

	// Title
	$wp_customize->add_setting( 'sumerian_clients_title',
		array(
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => esc_html__('Gallery', 'sumerian'),
		)
	);
	$wp_customize->add_control( 'sumerian_clients_title',
		array(
			'label'     => esc_html__('Section Title', 'sumerian'),
			'section' 		=> 'sumerian_clients_settings',
			'description'   => '',
		)
	);

	// Sub Title
	$wp_customize->add_setting( 'sumerian_clients_subtitle',
		array(
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => esc_html__('Section subtitle', 'sumerian'),
		)
	);
	$wp_customize->add_control( 'sumerian_clients_subtitle',
		array(
			'label'     => esc_html__('Section Subtitle', 'sumerian'),
			'section' 		=> 'sumerian_clients_settings',
			'description'   => '',
		)
	);

	// Description
	$wp_customize->add_setting( 'sumerian_clients_desc',
		array(
			'sanitize_callback' => 'sumerian_sanitize_text',
			'default'           => '',
		)
	);
	$wp_customize->add_control( new sumerian_Editor_Custom_Control(
		$wp_customize,
		'sumerian_clients_desc',
		array(
			'label' 		=> esc_html__('Section Description', 'sumerian'),
			'section' 		=> 'sumerian_clients_settings',
			'description'   => '',
		)
	));

	$wp_customize->add_section( 'sumerian_clients_content' ,
		array(
			'priority'    => 6,
			'title'       => esc_html__( 'Section Content', 'sumerian' ),
			'description' => '',
			'panel'       => 'sumerian_clients',
		)
	);

	// Features content
	$wp_customize->add_setting(
			'sumerian_clients_logos',
			array(
					//'default' => '',
					'sanitize_callback' => 'sumerian_sanitize_repeatable_data_field',
					'transport' => 'refresh', // refresh or postMessage
			) );

	$wp_customize->add_control(
			new sumerian_Customize_Repeatable_Control(
					$wp_customize,
					'sumerian_clients_logos',
					array(
							'label' 		=> esc_html__('Features content', 'sumerian'),
							'description'   => '',
							'section'       => 'sumerian_clients_content',
							'live_title_id' => 'title', // apply for unput text and textarea only
							'title_format'  => esc_html__('[live_title]', 'sumerian'), // [live_title]
							'max_item'      => 20, // Maximum item can add
							'limited_msg' 	=> wp_kses_post( __( 'Hola!', 'sumerian' ) ),
							'fields'    => array(
								'title1'  => array(
										'title' => esc_html__('Title', 'sumerian'),
										'type'  =>'text',
								),
								'logo1'  => array(
									'title' => esc_html__('Logo 1', 'sumerian'),
									'type'  =>'media',
									'required' => array( 'icon_type', '=', 'image' ),
								),
								'title2'  => array(
										'title' => esc_html__('Title', 'sumerian'),
										'type'  =>'text',
								),
								'logo2'  => array(
									'title' => esc_html__('Logo 2', 'sumerian'),
									'type'  =>'media',
									'required' => array( 'icon_type', '=', 'image' ),
								),
							),
					)
			)
	);

		/*------------------------------------------------------------------------*/
		/*  Section: Career
		/*------------------------------------------------------------------------*/
		$wp_customize->add_panel( 'sumerian_career' ,
		  array(
		    'priority'        => 180,
		    'title'           => esc_html__( 'Section: Career', 'sumerian' ),
		    'description'     => '',
		    'active_callback' => 'sumerian_showon_frontpage'
		  )
		);

		  $wp_customize->add_section( 'sumerian_career_settings' ,
		      array(
		          'priority'    => 3,
		          'title'       => esc_html__( 'Section Settings', 'sumerian' ),
		          'description' => '',
		          'panel'       => 'sumerian_career',
		      )
		  );

		  // Show Content
		  $wp_customize->add_setting( 'sumerian_career_disable',
		      array(
		          'sanitize_callback' => 'sumerian_sanitize_checkbox',
		          'default'           => '',
		      )
		  );
		  $wp_customize->add_control( 'sumerian_career_disable',
		      array(
		          'type'        => 'checkbox',
		          'label'       => esc_html__('Hide this section?', 'sumerian'),
		          'section'     => 'sumerian_career_settings',
		          'description' => esc_html__('Check this box to hide this section.', 'sumerian'),
		      )
		  );

		  // Section ID
		  $wp_customize->add_setting( 'sumerian_career_id',
		      array(
		          'sanitize_callback' => 'sumerian_sanitize_text',
		          'default'           => 'career',
		      )
		  );
		  $wp_customize->add_control( 'sumerian_career_id',
		      array(
		          'label' 		=> esc_html__('Section ID:', 'sumerian'),
		          'section' 		=> 'sumerian_career_settings',
		          'description'   => esc_html__('The section id, we will use this for link anchor.', 'sumerian' )
		      )
		  );

		  // Title
		  $wp_customize->add_setting( 'sumerian_career_title',
		      array(
		          'sanitize_callback' => 'sumerian_sanitize_text',
		          'default'           => '',
		      )
		  );

		  $wp_customize->add_control(  'sumerian_career_title',
		      array(
		          'label'     	=>  esc_html__('Section heading', 'sumerian'),
		          'section' 		=> 'sumerian_career_settings',
		          'description'   => '',
		      )
		  );

		  // Content

		  $wp_customize->add_setting( 'sumerian_career_desc',
		      array(
		          'sanitize_callback' => 'sumerian_sanitize_text',
		          'default'           => '',
		      )
		  );
		  $wp_customize->add_control( new sumerian_Editor_Custom_Control(
		      $wp_customize,
		      'sumerian_career_desc',
		      array(
		          'label'     	=>  esc_html__('Section Content', 'sumerian'),
		          'section' 		=> 'sumerian_career_settings',
		          'description'   => '',
		      )
		  ));

		  // Parallax image
		  $wp_customize->add_setting( 'sumerian_career_image',
		      array(
		          'sanitize_callback' => 'esc_url_raw',
		          'default'           => '',
		      )
		  );
		  $wp_customize->add_control( new WP_Customize_Image_Control(
		      $wp_customize,
		      'sumerian_career_image',
		      array(
		          'label' 		=> esc_html__('Background image', 'sumerian'),
		          'section' 		=> 'sumerian_career_settings',
		      )
		  ));


	/*------------------------------------------------------------------------*/
    /*  Section: About
    /*------------------------------------------------------------------------*/
    $wp_customize->add_panel( 'sumerian_about' ,
		array(
			'priority'        => 160,
			'title'           => esc_html__( 'Section: About', 'sumerian' ),
			'description'     => '',
			'active_callback' => 'sumerian_showon_frontpage'
		)
	);

	$wp_customize->add_section( 'sumerian_about_settings' ,
		array(
			'priority'    => 3,
			'title'       => esc_html__( 'Section Settings', 'sumerian' ),
			'description' => '',
			'panel'       => 'sumerian_about',
		)
	);

		// Show Content
		$wp_customize->add_setting( 'sumerian_about_disable',
			array(
				'sanitize_callback' => 'sumerian_sanitize_checkbox',
				'default'           => '',
			)
		);
		$wp_customize->add_control( 'sumerian_about_disable',
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__('Hide this section?', 'sumerian'),
				'section'     => 'sumerian_about_settings',
				'description' => esc_html__('Check this box to hide this section.', 'sumerian'),
			)
		);

		// Section ID
		$wp_customize->add_setting( 'sumerian_about_id',
			array(
				'sanitize_callback' => 'sumerian_sanitize_text',
				'default'           => esc_html__('about', 'sumerian'),
			)
		);
		$wp_customize->add_control( 'sumerian_about_id',
			array(
				'label' 		=> esc_html__('Section ID:', 'sumerian'),
				'section' 		=> 'sumerian_about_settings',
				'description'   => esc_html__( 'The section id, we will use this for link anchor.', 'sumerian' )
			)
		);

		// Title
		$wp_customize->add_setting( 'sumerian_about_title',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('About Us', 'sumerian'),
			)
		);
		$wp_customize->add_control( 'sumerian_about_title',
			array(
				'label' 		=> esc_html__('Section Title', 'sumerian'),
				'section' 		=> 'sumerian_about_settings',
				'description'   => '',
			)
		);

		// Sub Title
		$wp_customize->add_setting( 'sumerian_about_subtitle',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('Section subtitle', 'sumerian'),
			)
		);
		$wp_customize->add_control( 'sumerian_about_subtitle',
			array(
				'label' 		=> esc_html__('Section Subtitle', 'sumerian'),
				'section' 		=> 'sumerian_about_settings',
				'description'   => '',
			)
		);

		// Description
		$wp_customize->add_setting( 'sumerian_about_desc',
			array(
				'sanitize_callback' => 'sumerian_sanitize_text',
				'default'           => '',
			)
		);
		$wp_customize->add_control( new sumerian_Editor_Custom_Control(
			$wp_customize,
			'sumerian_about_desc',
			array(
				'label' 		=> esc_html__('Section Description', 'sumerian'),
				'section' 		=> 'sumerian_about_settings',
				'description'   => '',
			)
		));

		if ( class_exists( 'sumerian_Plus' ) ) {
            // About column
            $wp_customize->add_setting('sumerian_about_layout',
                array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'default' => 3,
                )
            );

            $wp_customize->add_control('sumerian_about_layout',
                array(
                    'label' => esc_html__('Layout Settings', 'sumerian'),
                    'section' => 'sumerian_about_settings',
                    'description' => '',
                    'type' => 'select',
                    'choices' => array(
                        4 => esc_html__('4 Columns', 'sumerian'),
                        3 => esc_html__('3 Columns', 'sumerian'),
                        2 => esc_html__('2 Columns', 'sumerian'),
                        1 => esc_html__('1 Column', 'sumerian'),
                    ),
                )
            );
        }



	$wp_customize->add_section( 'sumerian_about_content' ,
		array(
			'priority'    => 6,
			'title'       => esc_html__( 'Section Content', 'sumerian' ),
			'description' => '',
			'panel'       => 'sumerian_about',
		)
	);

		// Order & Stlying
		$wp_customize->add_setting(
			'sumerian_about_boxes',
			array(
				//'default' => '',
				'sanitize_callback' => 'sumerian_sanitize_repeatable_data_field',
				'transport' => 'refresh', // refresh or postMessage
			) );


			$wp_customize->add_control(
				new sumerian_Customize_Repeatable_Control(
					$wp_customize,
					'sumerian_about_boxes',
					array(
						'label' 		=> esc_html__('About content page', 'sumerian'),
						'description'   => '',
						'section'       => 'sumerian_about_content',
						'live_title_id' => 'content_page', // apply for unput text and textarea only
						'title_format'  => esc_html__('[live_title]', 'sumerian'), // [live_title]
						'max_item'      => 3, // Maximum item can add
                        'limited_msg' 	=> wp_kses_post( __('Upgrade to <a target="_blank" href="https://www.famethemes.com/plugins/sumerian-plus/?utm_source=theme_customizer&utm_medium=text_link&utm_campaign=sumerian_customizer#get-started">sumerian Plus</a> to be able to add more items and unlock other premium features!', 'sumerian' ) ),
						'fields'    => array(
							'content_page'  => array(
								'title' => esc_html__('Select a page', 'sumerian'),
								'type'  =>'select',
								'options' => $option_pages
							),
							'hide_title'  => array(
								'title' => esc_html__('Hide item title', 'sumerian'),
								'type'  =>'checkbox',
							),
							'enable_link'  => array(
								'title' => esc_html__('Link to single page', 'sumerian'),
								'type'  =>'checkbox',
							),
						),

					)
				)
			);

            // About content source
            $wp_customize->add_setting( 'sumerian_about_content_source',
                array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'default'           => 'content',
                )
            );

            $wp_customize->add_control( 'sumerian_about_content_source',
                array(
                    'label' 		=> esc_html__('Item content source', 'sumerian'),
                    'section' 		=> 'sumerian_about_content',
                    'description'   => '',
                    'type'          => 'select',
                    'choices'       => array(
                        'content' => esc_html__( 'Full Page Content', 'sumerian' ),
                        'excerpt' => esc_html__( 'Page Excerpt', 'sumerian' ),
                    ),
                )
            );




    /*------------------------------------------------------------------------*/
    /*  Section: Features
    /*------------------------------------------------------------------------*/
    $wp_customize->add_panel( 'sumerian_features' ,
        array(
            'priority'        => 150,
            'title'           => esc_html__( 'Section: Features', 'sumerian' ),
            'description'     => '',
            'active_callback' => 'sumerian_showon_frontpage'
        )
    );

    $wp_customize->add_section( 'sumerian_features_settings' ,
        array(
            'priority'    => 3,
            'title'       => esc_html__( 'Section Settings', 'sumerian' ),
            'description' => '',
            'panel'       => 'sumerian_features',
        )
    );

    // Show Content
    $wp_customize->add_setting( 'sumerian_features_disable',
        array(
            'sanitize_callback' => 'sumerian_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'sumerian_features_disable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Hide this section?', 'sumerian'),
            'section'     => 'sumerian_features_settings',
            'description' => esc_html__('Check this box to hide this section.', 'sumerian'),
        )
    );

    // Section ID
    $wp_customize->add_setting( 'sumerian_features_id',
        array(
            'sanitize_callback' => 'sumerian_sanitize_text',
            'default'           => esc_html__('features', 'sumerian'),
        )
    );
    $wp_customize->add_control( 'sumerian_features_id',
        array(
            'label' 		=> esc_html__('Section ID:', 'sumerian'),
            'section' 		=> 'sumerian_features_settings',
            'description'   => esc_html__( 'The section id, we will use this for link anchor.', 'sumerian' )
        )
    );

    // Title
    $wp_customize->add_setting( 'sumerian_features_title',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('Features', 'sumerian'),
        )
    );
    $wp_customize->add_control( 'sumerian_features_title',
        array(
            'label' 		=> esc_html__('Section Title', 'sumerian'),
            'section' 		=> 'sumerian_features_settings',
            'description'   => '',
        )
    );

    // Sub Title
    $wp_customize->add_setting( 'sumerian_features_subtitle',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('Section subtitle', 'sumerian'),
        )
    );
    $wp_customize->add_control( 'sumerian_features_subtitle',
        array(
            'label' 		=> esc_html__('Section Subtitle', 'sumerian'),
            'section' 		=> 'sumerian_features_settings',
            'description'   => '',
        )
    );

    // Description
    $wp_customize->add_setting( 'sumerian_features_desc',
        array(
            'sanitize_callback' => 'sumerian_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control( new sumerian_Editor_Custom_Control(
        $wp_customize,
        'sumerian_features_desc',
        array(
            'label' 		=> esc_html__('Section Description', 'sumerian'),
            'section' 		=> 'sumerian_features_settings',
            'description'   => '',
        )
    ));

    // Features layout
    $wp_customize->add_setting( 'sumerian_features_layout',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '3',
        )
    );

    $wp_customize->add_control( 'sumerian_features_layout',
        array(
            'label' 		=> esc_html__('Features Layout Setting', 'sumerian'),
            'section' 		=> 'sumerian_features_settings',
            'description'   => '',
            'type'          => 'select',
            'choices'       => array(
                '3' => esc_html__( '4 Columns', 'sumerian' ),
                '4' => esc_html__( '3 Columns', 'sumerian' ),
                '6' => esc_html__( '2 Columns', 'sumerian' ),
            ),
        )
    );


    $wp_customize->add_section( 'sumerian_features_content' ,
        array(
            'priority'    => 6,
            'title'       => esc_html__( 'Section Content', 'sumerian' ),
            'description' => '',
            'panel'       => 'sumerian_features',
        )
    );

    // Features content
    $wp_customize->add_setting(
        'sumerian_features_boxes',
        array(
            //'default' => '',
            'sanitize_callback' => 'sumerian_sanitize_repeatable_data_field',
            'transport' => 'refresh', // refresh or postMessage
        ) );

    $wp_customize->add_control(
        new sumerian_Customize_Repeatable_Control(
            $wp_customize,
            'sumerian_features_boxes',
            array(
                'label' 		=> esc_html__('Features content', 'sumerian'),
                'description'   => '',
                'section'       => 'sumerian_features_content',
                'live_title_id' => 'title', // apply for unput text and textarea only
                'title_format'  => esc_html__('[live_title]', 'sumerian'), // [live_title]
                'max_item'      => 20, // Maximum item can add
                'limited_msg' 	=> wp_kses_post( __( 'Hola!', 'sumerian' ) ),
                'fields'    => array(
                    'title'  => array(
                        'title' => esc_html__('Title', 'sumerian'),
                        'type'  =>'text',
                    ),
					'icon_type'  => array(
						'title' => esc_html__('Custom icon', 'sumerian'),
						'type'  =>'select',
						'options' => array(
							'icon' => esc_html__('Icon', 'sumerian'),
							'image' => esc_html__('image', 'sumerian'),
						),
					),
                    'icon'  => array(
                        'title' => esc_html__('Icon', 'sumerian'),
                        'type'  =>'icon',
						'required' => array( 'icon_type', '=', 'icon' ),
                    ),
					'image'  => array(
						'title' => esc_html__('Image', 'sumerian'),
						'type'  =>'media',
						'required' => array( 'icon_type', '=', 'image' ),
					),
                    'desc'  => array(
                        'title' => esc_html__('Description', 'sumerian'),
                        'type'  =>'editor',
                    ),
                    'link'  => array(
                        'title' => esc_html__('Custom Link', 'sumerian'),
                        'type'  =>'text',
                    ),
                ),

            )
        )
    );

    // About content source
    $wp_customize->add_setting( 'sumerian_about_content_source',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'content',
        )
    );

    $wp_customize->add_control( 'sumerian_about_content_source',
        array(
            'label' 		=> esc_html__('Item content source', 'sumerian'),
            'section' 		=> 'sumerian_about_content',
            'description'   => '',
            'type'          => 'select',
            'choices'       => array(
                'content' => esc_html__( 'Full Page Content', 'sumerian' ),
                'excerpt' => esc_html__( 'Page Excerpt', 'sumerian' ),
            ),
        )
    );


    /*------------------------------------------------------------------------*/
    /*  Section: Services
    /*------------------------------------------------------------------------*/
    $wp_customize->add_panel( 'sumerian_services' ,
		array(
			'priority'        => 170,
			'title'           => esc_html__( 'Section: Services', 'sumerian' ),
			'description'     => '',
			'active_callback' => 'sumerian_showon_frontpage'
		)
	);

	$wp_customize->add_section( 'sumerian_service_settings' ,
		array(
			'priority'    => 3,
			'title'       => esc_html__( 'Section Settings', 'sumerian' ),
			'description' => '',
			'panel'       => 'sumerian_services',
		)
	);

		// Show Content
		$wp_customize->add_setting( 'sumerian_services_disable',
			array(
				'sanitize_callback' => 'sumerian_sanitize_checkbox',
				'default'           => '',
			)
		);
		$wp_customize->add_control( 'sumerian_services_disable',
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__('Hide this section?', 'sumerian'),
				'section'     => 'sumerian_service_settings',
				'description' => esc_html__('Check this box to hide this section.', 'sumerian'),
			)
		);

		// Section ID
		$wp_customize->add_setting( 'sumerian_services_id',
			array(
				'sanitize_callback' => 'sumerian_sanitize_text',
				'default'           => esc_html__('services', 'sumerian'),
			)
		);
		$wp_customize->add_control( 'sumerian_services_id',
			array(
				'label'     => esc_html__('Section ID:', 'sumerian'),
				'section' 		=> 'sumerian_service_settings',
				'description'   => 'The section id, we will use this for link anchor.'
			)
		);

		// Title
		$wp_customize->add_setting( 'sumerian_services_title',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('Our Services', 'sumerian'),
			)
		);
		$wp_customize->add_control( 'sumerian_services_title',
			array(
				'label'     => esc_html__('Section Title', 'sumerian'),
				'section' 		=> 'sumerian_service_settings',
				'description'   => '',
			)
		);

		// Sub Title
		$wp_customize->add_setting( 'sumerian_services_subtitle',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('Section subtitle', 'sumerian'),
			)
		);
		$wp_customize->add_control( 'sumerian_services_subtitle',
			array(
				'label'     => esc_html__('Section Subtitle', 'sumerian'),
				'section' 		=> 'sumerian_service_settings',
				'description'   => '',
			)
		);

        // Description
        $wp_customize->add_setting( 'sumerian_services_desc',
            array(
                'sanitize_callback' => 'sumerian_sanitize_text',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new sumerian_Editor_Custom_Control(
            $wp_customize,
            'sumerian_services_desc',
            array(
                'label' 		=> esc_html__('Section Description', 'sumerian'),
                'section' 		=> 'sumerian_service_settings',
                'description'   => '',
            )
        ));


        // Services layout
        $wp_customize->add_setting( 'sumerian_service_layout',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '6',
            )
        );

        $wp_customize->add_control( 'sumerian_service_layout',
            array(
                'label' 		=> esc_html__('Services Layout Setting', 'sumerian'),
                'section' 		=> 'sumerian_service_settings',
                'description'   => '',
                'type'          => 'select',
                'choices'       => array(
                    '3' => esc_html__( '4 Columns', 'sumerian' ),
                    '4' => esc_html__( '3 Columns', 'sumerian' ),
                    '6' => esc_html__( '2 Columns', 'sumerian' ),
                    '12' => esc_html__( '1 Column', 'sumerian' ),
                ),
            )
        );


	$wp_customize->add_section( 'sumerian_service_content' ,
		array(
			'priority'    => 6,
			'title'       => esc_html__( 'Section Content', 'sumerian' ),
			'description' => '',
			'panel'       => 'sumerian_services',
		)
	);

		// Section service content.
		$wp_customize->add_setting(
			'sumerian_services',
			array(
				'sanitize_callback' => 'sumerian_sanitize_repeatable_data_field',
				'transport' => 'refresh', // refresh or postMessage
			) );


		$wp_customize->add_control(
			new sumerian_Customize_Repeatable_Control(
				$wp_customize,
				'sumerian_services',
				array(
					'label'     	=> esc_html__('Service content', 'sumerian'),
					'description'   => '',
					'section'       => 'sumerian_service_content',
					'live_title_id' => 'content_page', // apply for unput text and textarea only
					'title_format'  => esc_html__('[live_title]', 'sumerian'), // [live_title]
					'max_item'      => 4, // Maximum item can add,
                    'limited_msg' 	=> wp_kses_post( __('Upgrade to <a target="_blank" href="https://www.famethemes.com/plugins/sumerian-plus/?utm_source=theme_customizer&utm_medium=text_link&utm_campaign=sumerian_customizer#get-started">sumerian Plus</a> to be able to add more items and unlock other premium features!', 'sumerian' ) ),
                    'fields'    => array(
						'icon_type'  => array(
							'title' => esc_html__('Custom icon', 'sumerian'),
							'type'  =>'select',
							'options' => array(
								'icon' => esc_html__('Icon', 'sumerian'),
								'image' => esc_html__('image', 'sumerian'),
							),
						),
						'icon'  => array(
							'title' => esc_html__('Icon', 'sumerian'),
							'type'  =>'icon',
							'required' => array( 'icon_type', '=', 'icon' ),
						),
						'image'  => array(
							'title' => esc_html__('Image', 'sumerian'),
							'type'  =>'media',
							'required' => array( 'icon_type', '=', 'image' ),
						),

						'content_page'  => array(
							'title' => esc_html__('Select a page', 'sumerian'),
							'type'  =>'select',
							'options' => $option_pages
						),
						'enable_link'  => array(
							'title' => esc_html__('Link to single page', 'sumerian'),
							'type'  =>'checkbox',
						),
					),

				)
			)
		);


        // Services icon size
        $wp_customize->add_setting( 'sumerian_service_icon_size',
            array(
                'sanitize_callback' => 'sumerian_sanitize_select',
                'default'           => '5x',
            )
        );

        $wp_customize->add_control( 'sumerian_service_icon_size',
            array(
                'label' 		=> esc_html__('Icon Size', 'sumerian'),
                'section' 		=> 'sumerian_service_content',
                'description'   => '',
                'type'          => 'select',
                'choices'       => array(
                    '5x' => esc_html__( '5x', 'sumerian' ),
                    '4x' => esc_html__( '4x', 'sumerian' ),
                    '3x' => esc_html__( '3x', 'sumerian' ),
                    '2x' => esc_html__( '2x', 'sumerian' ),
                    '1x' => esc_html__( '1x', 'sumerian' ),
                ),
            )
        );

	/*------------------------------------------------------------------------*/
    /*  Section: Contact
    /*------------------------------------------------------------------------*/
    $wp_customize->add_panel( 'sumerian_contact' ,
		array(
			'priority'        => 270,
			'title'           => esc_html__( 'Section: Contact', 'sumerian' ),
			'description'     => '',
			'active_callback' => 'sumerian_showon_frontpage'
		)
	);

	$wp_customize->add_section( 'sumerian_contact_settings' ,
		array(
			'priority'    => 3,
			'title'       => esc_html__( 'Section Settings', 'sumerian' ),
			'description' => '',
			'panel'       => 'sumerian_contact',
		)
	);

		// Show Content
		$wp_customize->add_setting( 'sumerian_contact_disable',
			array(
				'sanitize_callback' => 'sumerian_sanitize_checkbox',
				'default'           => '',
			)
		);
		$wp_customize->add_control( 'sumerian_contact_disable',
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__('Hide this section?', 'sumerian'),
				'section'     => 'sumerian_contact_settings',
				'description' => esc_html__('Check this box to hide this section.', 'sumerian'),
			)
		);

		// Section ID
		$wp_customize->add_setting( 'sumerian_contact_id',
			array(
				'sanitize_callback' => 'sumerian_sanitize_text',
				'default'           => esc_html__('contact', 'sumerian'),
			)
		);
		$wp_customize->add_control( 'sumerian_contact_id',
			array(
				'label'     => esc_html__('Section ID:', 'sumerian'),
				'section' 		=> 'sumerian_contact_settings',
				'description'   => esc_html__( 'The section id, we will use this for link anchor.', 'sumerian' )
			)
		);

		// Title
		$wp_customize->add_setting( 'sumerian_contact_title',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('Get in touch', 'sumerian'),
			)
		);
		$wp_customize->add_control( 'sumerian_contact_title',
			array(
				'label'     => esc_html__('Section Title', 'sumerian'),
				'section' 		=> 'sumerian_contact_settings',
				'description'   => '',
			)
		);

		// Sub Title
		$wp_customize->add_setting( 'sumerian_contact_subtitle',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('Section subtitle', 'sumerian'),
			)
		);
		$wp_customize->add_control( 'sumerian_contact_subtitle',
			array(
				'label'     => esc_html__('Section Subtitle', 'sumerian'),
				'section' 		=> 'sumerian_contact_settings',
				'description'   => '',
			)
		);

        // Description
        $wp_customize->add_setting( 'sumerian_contact_desc',
            array(
                'sanitize_callback' => 'sumerian_sanitize_text',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new sumerian_Editor_Custom_Control(
            $wp_customize,
            'sumerian_contact_desc',
            array(
                'label' 		=> esc_html__('Section Description', 'sumerian'),
                'section' 		=> 'sumerian_contact_settings',
                'description'   => '',
            )
        ));


	$wp_customize->add_section( 'sumerian_contact_content' ,
		array(
			'priority'    => 6,
			'title'       => esc_html__( 'Section Content', 'sumerian' ),
			'description' => '',
			'panel'       => 'sumerian_contact',
		)
	);


		// Contact Text
		$wp_customize->add_setting( 'sumerian_contact_left_title',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('CORPORATE HEADQUARTER', 'sumerian'),
			)
		);
		$wp_customize->add_control( 'sumerian_contact_left_title',
			array(
				'label'     => esc_html__('Left title', 'sumerian'),
				'section' 		=> 'sumerian_contact_content',
				'description'   => '',
			)
		);
		$wp_customize->add_setting( 'sumerian_contact_left_text',
			array(
				'sanitize_callback' => 'sumerian_sanitize_text',
				'default'           => '',
			)
		);
		$wp_customize->add_control( new sumerian_Editor_Custom_Control(
			$wp_customize,
			'sumerian_contact_left_text',
			array(
				'label'     	=> esc_html__('Contact Text', 'sumerian'),
				'section' 		=> 'sumerian_contact_content',
				'description'   => '',
			)
		));

		// hr
		$wp_customize->add_setting( 'sumerian_contact_text_hr', array( 'sanitize_callback' => 'sumerian_sanitize_text' ) );
		$wp_customize->add_control( new sumerian_Misc_Control( $wp_customize, 'sumerian_contact_text_hr',
			array(
				'section'     => 'sumerian_contact_content',
				'type'        => 'hr'
			)
		));

		$wp_customize->add_setting( 'sumerian_contact_right_title',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('GLOBAL DEVELOPMENT CENTRE', 'sumerian'),
			)
		);
		$wp_customize->add_control( 'sumerian_contact_right_title',
			array(
				'label'     => esc_html__('Right Title', 'sumerian'),
				'section' 		=> 'sumerian_contact_content',
				'description'   => '',
			)
		);

		$wp_customize->add_setting( 'sumerian_contact_right_text',
			array(
				'sanitize_callback' => 'sumerian_sanitize_text',
				'default'           => '',
			)
		);
		$wp_customize->add_control( new sumerian_Editor_Custom_Control(
			$wp_customize,
			'sumerian_contact_right_text',
			array(
				'label'     	=> esc_html__('Contact Right Text', 'sumerian'),
				'section' 		=> 'sumerian_contact_content',
				'description'   => '',
			)
		));

		do_action( 'sumerian_customize_after_register', $wp_customize );

}
add_action( 'customize_register', 'sumerian_customize_register' );
/**
 * Selective refresh
 */
require get_template_directory() . '/inc/customizer-selective-refresh.php';


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function sumerian_customize_preview_js() {
    wp_enqueue_script( 'sumerian_customizer_liveview', get_template_directory_uri() . '/assets/js/customizer-liveview.js', array( 'customize-preview', 'customize-selective-refresh' ), false, true );
}
add_action( 'customize_preview_init', 'sumerian_customize_preview_js', 65 );



add_action( 'customize_controls_enqueue_scripts', 'opneress_customize_js_settings' );
function opneress_customize_js_settings(){
    if ( ! function_exists( 'sumerian_get_recommended_actions' ) ) {
        return;
    }

    $actions = sumerian_get_recommended_actions();
    $number_action = $actions['number_notice'];

    wp_localize_script( 'customize-controls', 'sumerian_customizer_settings', array(
        'number_action' => $number_action,
        'is_plus_activated' => class_exists( 'sumerian_Plus' ) ? 'y' : 'n',
        'action_url' => admin_url( 'themes.php?page=ft_sumerian&tab=recommended_actions' ),
    ) );
}

/**
 * Customizer Icon picker
 */
function sumerian_customize_controls_enqueue_scripts(){
    wp_localize_script( 'customize-controls', 'C_Icon_Picker',
        apply_filters( 'c_icon_picker_js_setup',
            array(
                'search'    => esc_html__( 'Search', 'sumerian' ),
                'fonts' => array(
                    'font-awesome' => array(
                        // Name of icon
                        'name' => esc_html__( 'Font Awesome', 'sumerian' ),
                        // prefix class example for font-awesome fa-fa-{name}
                        'prefix' => 'fa',
                        // font url
                        'url' => esc_url( add_query_arg( array( 'ver'=> '4.7.0' ), get_template_directory_uri() .'/assets/css/font-awesome.min.css' ) ),
                        // Icon class name, separated by |
                        'icons' => 'fa-glass|fa-music|fa-search|fa-envelope-o|fa-heart|fa-star|fa-star-o|fa-user|fa-film|fa-th-large|fa-th|fa-th-list|fa-check|fa-times|fa-search-plus|fa-search-minus|fa-power-off|fa-signal|fa-cog|fa-trash-o|fa-home|fa-file-o|fa-clock-o|fa-road|fa-download|fa-arrow-circle-o-down|fa-arrow-circle-o-up|fa-inbox|fa-play-circle-o|fa-repeat|fa-refresh|fa-list-alt|fa-lock|fa-flag|fa-headphones|fa-volume-off|fa-volume-down|fa-volume-up|fa-qrcode|fa-barcode|fa-tag|fa-tags|fa-book|fa-bookmark|fa-print|fa-camera|fa-font|fa-bold|fa-italic|fa-text-height|fa-text-width|fa-align-left|fa-align-center|fa-align-right|fa-align-justify|fa-list|fa-outdent|fa-indent|fa-video-camera|fa-picture-o|fa-pencil|fa-map-marker|fa-adjust|fa-tint|fa-pencil-square-o|fa-share-square-o|fa-check-square-o|fa-arrows|fa-step-backward|fa-fast-backward|fa-backward|fa-play|fa-pause|fa-stop|fa-forward|fa-fast-forward|fa-step-forward|fa-eject|fa-chevron-left|fa-chevron-right|fa-plus-circle|fa-minus-circle|fa-times-circle|fa-check-circle|fa-question-circle|fa-info-circle|fa-crosshairs|fa-times-circle-o|fa-check-circle-o|fa-ban|fa-arrow-left|fa-arrow-right|fa-arrow-up|fa-arrow-down|fa-share|fa-expand|fa-compress|fa-plus|fa-minus|fa-asterisk|fa-exclamation-circle|fa-gift|fa-leaf|fa-fire|fa-eye|fa-eye-slash|fa-exclamation-triangle|fa-plane|fa-calendar|fa-random|fa-comment|fa-magnet|fa-chevron-up|fa-chevron-down|fa-retweet|fa-shopping-cart|fa-folder|fa-folder-open|fa-arrows-v|fa-arrows-h|fa-bar-chart|fa-twitter-square|fa-facebook-square|fa-camera-retro|fa-key|fa-cogs|fa-comments|fa-thumbs-o-up|fa-thumbs-o-down|fa-star-half|fa-heart-o|fa-sign-out|fa-linkedin-square|fa-thumb-tack|fa-external-link|fa-sign-in|fa-trophy|fa-github-square|fa-upload|fa-lemon-o|fa-phone|fa-square-o|fa-bookmark-o|fa-phone-square|fa-twitter|fa-facebook|fa-github|fa-unlock|fa-credit-card|fa-rss|fa-hdd-o|fa-bullhorn|fa-bell|fa-certificate|fa-hand-o-right|fa-hand-o-left|fa-hand-o-up|fa-hand-o-down|fa-arrow-circle-left|fa-arrow-circle-right|fa-arrow-circle-up|fa-arrow-circle-down|fa-globe|fa-wrench|fa-tasks|fa-filter|fa-briefcase|fa-arrows-alt|fa-users|fa-link|fa-cloud|fa-flask|fa-scissors|fa-files-o|fa-paperclip|fa-floppy-o|fa-square|fa-bars|fa-list-ul|fa-list-ol|fa-strikethrough|fa-underline|fa-table|fa-magic|fa-truck|fa-pinterest|fa-pinterest-square|fa-google-plus-square|fa-google-plus|fa-money|fa-caret-down|fa-caret-up|fa-caret-left|fa-caret-right|fa-columns|fa-sort|fa-sort-desc|fa-sort-asc|fa-envelope|fa-linkedin|fa-undo|fa-gavel|fa-tachometer|fa-comment-o|fa-comments-o|fa-bolt|fa-sitemap|fa-umbrella|fa-clipboard|fa-lightbulb-o|fa-exchange|fa-cloud-download|fa-cloud-upload|fa-user-md|fa-stethoscope|fa-suitcase|fa-bell-o|fa-coffee|fa-cutlery|fa-file-text-o|fa-building-o|fa-hospital-o|fa-ambulance|fa-medkit|fa-fighter-jet|fa-beer|fa-h-square|fa-plus-square|fa-angle-double-left|fa-angle-double-right|fa-angle-double-up|fa-angle-double-down|fa-angle-left|fa-angle-right|fa-angle-up|fa-angle-down|fa-desktop|fa-laptop|fa-tablet|fa-mobile|fa-circle-o|fa-quote-left|fa-quote-right|fa-spinner|fa-circle|fa-reply|fa-github-alt|fa-folder-o|fa-folder-open-o|fa-smile-o|fa-frown-o|fa-meh-o|fa-gamepad|fa-keyboard-o|fa-flag-o|fa-flag-checkered|fa-terminal|fa-code|fa-reply-all|fa-star-half-o|fa-location-arrow|fa-crop|fa-code-fork|fa-chain-broken|fa-question|fa-info|fa-exclamation|fa-superscript|fa-subscript|fa-eraser|fa-puzzle-piece|fa-microphone|fa-microphone-slash|fa-shield|fa-calendar-o|fa-fire-extinguisher|fa-rocket|fa-maxcdn|fa-chevron-circle-left|fa-chevron-circle-right|fa-chevron-circle-up|fa-chevron-circle-down|fa-html5|fa-css3|fa-anchor|fa-unlock-alt|fa-bullseye|fa-ellipsis-h|fa-ellipsis-v|fa-rss-square|fa-play-circle|fa-ticket|fa-minus-square|fa-minus-square-o|fa-level-up|fa-level-down|fa-check-square|fa-pencil-square|fa-external-link-square|fa-share-square|fa-compass|fa-caret-square-o-down|fa-caret-square-o-up|fa-caret-square-o-right|fa-eur|fa-gbp|fa-usd|fa-inr|fa-jpy|fa-rub|fa-krw|fa-btc|fa-file|fa-file-text|fa-sort-alpha-asc|fa-sort-alpha-desc|fa-sort-amount-asc|fa-sort-amount-desc|fa-sort-numeric-asc|fa-sort-numeric-desc|fa-thumbs-up|fa-thumbs-down|fa-youtube-square|fa-youtube|fa-xing|fa-xing-square|fa-youtube-play|fa-dropbox|fa-stack-overflow|fa-instagram|fa-flickr|fa-adn|fa-bitbucket|fa-bitbucket-square|fa-tumblr|fa-tumblr-square|fa-long-arrow-down|fa-long-arrow-up|fa-long-arrow-left|fa-long-arrow-right|fa-apple|fa-windows|fa-android|fa-linux|fa-dribbble|fa-skype|fa-foursquare|fa-trello|fa-female|fa-male|fa-gratipay|fa-sun-o|fa-moon-o|fa-archive|fa-bug|fa-vk|fa-weibo|fa-renren|fa-pagelines|fa-stack-exchange|fa-arrow-circle-o-right|fa-arrow-circle-o-left|fa-caret-square-o-left|fa-dot-circle-o|fa-wheelchair|fa-vimeo-square|fa-try|fa-plus-square-o|fa-space-shuttle|fa-slack|fa-envelope-square|fa-wordpress|fa-openid|fa-university|fa-graduation-cap|fa-yahoo|fa-google|fa-reddit|fa-reddit-square|fa-stumbleupon-circle|fa-stumbleupon|fa-delicious|fa-digg|fa-pied-piper-pp|fa-pied-piper-alt|fa-drupal|fa-joomla|fa-language|fa-fax|fa-building|fa-child|fa-paw|fa-spoon|fa-cube|fa-cubes|fa-behance|fa-behance-square|fa-steam|fa-steam-square|fa-recycle|fa-car|fa-taxi|fa-tree|fa-spotify|fa-deviantart|fa-soundcloud|fa-database|fa-file-pdf-o|fa-file-word-o|fa-file-excel-o|fa-file-powerpoint-o|fa-file-image-o|fa-file-archive-o|fa-file-audio-o|fa-file-video-o|fa-file-code-o|fa-vine|fa-codepen|fa-jsfiddle|fa-life-ring|fa-circle-o-notch|fa-rebel|fa-empire|fa-git-square|fa-git|fa-hacker-news|fa-tencent-weibo|fa-qq|fa-weixin|fa-paper-plane|fa-paper-plane-o|fa-history|fa-circle-thin|fa-header|fa-paragraph|fa-sliders|fa-share-alt|fa-share-alt-square|fa-bomb|fa-futbol-o|fa-tty|fa-binoculars|fa-plug|fa-slideshare|fa-twitch|fa-yelp|fa-newspaper-o|fa-wifi|fa-calculator|fa-paypal|fa-google-wallet|fa-cc-visa|fa-cc-mastercard|fa-cc-discover|fa-cc-amex|fa-cc-paypal|fa-cc-stripe|fa-bell-slash|fa-bell-slash-o|fa-trash|fa-copyright|fa-at|fa-eyedropper|fa-paint-brush|fa-birthday-cake|fa-area-chart|fa-pie-chart|fa-line-chart|fa-lastfm|fa-lastfm-square|fa-toggle-off|fa-toggle-on|fa-bicycle|fa-bus|fa-ioxhost|fa-angellist|fa-cc|fa-ils|fa-meanpath|fa-buysellads|fa-connectdevelop|fa-dashcube|fa-forumbee|fa-leanpub|fa-sellsy|fa-shirtsinbulk|fa-simplybuilt|fa-skyatlas|fa-cart-plus|fa-cart-arrow-down|fa-diamond|fa-ship|fa-user-secret|fa-motorcycle|fa-street-view|fa-heartbeat|fa-venus|fa-mars|fa-mercury|fa-transgender|fa-transgender-alt|fa-venus-double|fa-mars-double|fa-venus-mars|fa-mars-stroke|fa-mars-stroke-v|fa-mars-stroke-h|fa-neuter|fa-genderless|fa-facebook-official|fa-pinterest-p|fa-whatsapp|fa-server|fa-user-plus|fa-user-times|fa-bed|fa-viacoin|fa-train|fa-subway|fa-medium|fa-y-combinator|fa-optin-monster|fa-opencart|fa-expeditedssl|fa-battery-full|fa-battery-three-quarters|fa-battery-half|fa-battery-quarter|fa-battery-empty|fa-mouse-pointer|fa-i-cursor|fa-object-group|fa-object-ungroup|fa-sticky-note|fa-sticky-note-o|fa-cc-jcb|fa-cc-diners-club|fa-clone|fa-balance-scale|fa-hourglass-o|fa-hourglass-start|fa-hourglass-half|fa-hourglass-end|fa-hourglass|fa-hand-rock-o|fa-hand-paper-o|fa-hand-scissors-o|fa-hand-lizard-o|fa-hand-spock-o|fa-hand-pointer-o|fa-hand-peace-o|fa-trademark|fa-registered|fa-creative-commons|fa-gg|fa-gg-circle|fa-tripadvisor|fa-odnoklassniki|fa-odnoklassniki-square|fa-get-pocket|fa-wikipedia-w|fa-safari|fa-chrome|fa-firefox|fa-opera|fa-internet-explorer|fa-television|fa-contao|fa-500px|fa-amazon|fa-calendar-plus-o|fa-calendar-minus-o|fa-calendar-times-o|fa-calendar-check-o|fa-industry|fa-map-pin|fa-map-signs|fa-map-o|fa-map|fa-commenting|fa-commenting-o|fa-houzz|fa-vimeo|fa-black-tie|fa-fonticons|fa-reddit-alien|fa-edge|fa-credit-card-alt|fa-codiepie|fa-modx|fa-fort-awesome|fa-usb|fa-product-hunt|fa-mixcloud|fa-scribd|fa-pause-circle|fa-pause-circle-o|fa-stop-circle|fa-stop-circle-o|fa-shopping-bag|fa-shopping-basket|fa-hashtag|fa-bluetooth|fa-bluetooth-b|fa-percent|fa-gitlab|fa-wpbeginner|fa-wpforms|fa-envira|fa-universal-access|fa-wheelchair-alt|fa-question-circle-o|fa-blind|fa-audio-description|fa-volume-control-phone|fa-braille|fa-assistive-listening-systems|fa-american-sign-language-interpreting|fa-deaf|fa-glide|fa-glide-g|fa-sign-language|fa-low-vision|fa-viadeo|fa-viadeo-square|fa-snapchat|fa-snapchat-ghost|fa-snapchat-square|fa-pied-piper|fa-first-order|fa-yoast|fa-themeisle|fa-google-plus-official|fa-font-awesome|fa-handshake-o|fa-envelope-open|fa-envelope-open-o|fa-linode|fa-address-book|fa-address-book-o|fa-address-card|fa-address-card-o|fa-user-circle|fa-user-circle-o|fa-user-o|fa-id-badge|fa-id-card|fa-id-card-o|fa-quora|fa-free-code-camp|fa-telegram|fa-thermometer-full|fa-thermometer-three-quarters|fa-thermometer-half|fa-thermometer-quarter|fa-thermometer-empty|fa-shower|fa-bath|fa-podcast|fa-window-maximize|fa-window-minimize|fa-window-restore|fa-window-close|fa-window-close-o|fa-bandcamp|fa-grav|fa-etsy|fa-imdb|fa-ravelry|fa-eercast|fa-microchip|fa-snowflake-o|fa-superpowers|fa-wpexplorer|fa-meetup'

                        ),
                )

            )
        )
    );
}

add_action( 'customize_controls_enqueue_scripts', 'sumerian_customize_controls_enqueue_scripts' );
