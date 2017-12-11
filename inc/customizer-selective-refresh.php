<?php

function sumerian_customizer_load_template( $template_names ){
    $located = '';

    $is_child =  get_stylesheet_directory() != get_template_directory() ;
    foreach ( (array) $template_names as $template_name ) {
        if (  !$template_name )
            continue;

        if ( $is_child && file_exists( get_stylesheet_directory() . '/' . $template_name ) ) {  // Child them
            $located = get_stylesheet_directory() . '/' . $template_name;
            break;

        } elseif ( defined( 'sumerian_PLUS_PATH' ) && file_exists( sumerian_PLUS_PATH  . $template_name ) ) { // Check part in the plugin
            $located = sumerian_PLUS_PATH . $template_name;
            break;
        } elseif ( file_exists( get_template_directory() . '/' . $template_name) ) { // current_theme
            $located =  get_template_directory() . '/' . $template_name;
            break;
        }
    }

    return $located;
}

/**
 * Render customizer section
 * @since 1.2.1
 *
 * @param $section_tpl
 * @param array $section
 * @return string
 */
function sumerian_get_customizer_section_content( $section_tpl, $section = array() ){
    ob_start();
    $GLOBALS['sumerian_is_selective_refresh'] = true;
    $file = sumerian_customizer_load_template( $section_tpl );
    if ( $file ) {
        include $file;
    }
    $content = ob_get_clean();
    return trim( $content );
}


/**
 * Add customizer selective refresh
 *
 * @since 1.2.1
 *
 * @param $wp_customize
 */
function sumerian_customizer_partials( $wp_customize ) {

    // Abort if selective refresh is not available.
    if ( ! isset( $wp_customize->selective_refresh ) ) {
        return;
    }

    $selective_refresh_keys = array(
        // section features
        array(
            'id' => 'features',
            'selector' => '.section-features',
            'settings' => array(
                'sumerian_features_boxes',
                'sumerian_features_title',
                'sumerian_features_subtitle',
                'sumerian_features_desc',
                'sumerian_features_layout',
            ),
        ),

        // section services
        array(
            'id' => 'services',
            'selector' => '.section-services',
            'settings' => array(
                'sumerian_services',
                'sumerian_services_title',
                'sumerian_services_subtitle',
                'sumerian_services_desc',
                'sumerian_service_layout',
                'sumerian_service_icon_size',
            ),
        ),

        // section gallery
        'gallery' => array(
            'id' => 'gallery',
            'selector' => '.section-gallery',
            'settings' => array(
                'sumerian_clients_source',

                'sumerian_clients_title',
                'sumerian_clients_subtitle',
                'sumerian_clients_desc',
                'sumerian_clients_source_page',
                'sumerian_clients_layout',
                'sumerian_clients_display',
                'sumerian_g_number',
                'sumerian_g_row_height',
                'sumerian_g_col',
                'sumerian_g_readmore_link',
                'sumerian_g_readmore_text',
            ),
        ),

        // section news
        array(
            'id' => 'news',
            'selector' => '.section-news',
            'settings' => array(
                'sumerian_news_title',
                'sumerian_news_subtitle',
                'sumerian_news_desc',
                'sumerian_news_number',
                'sumerian_news_more_link',
                'sumerian_news_more_text',

                'sumerian_news_cat',
                'sumerian_news_orderby',
                'sumerian_news_order',
            ),
        ),

        // section contact
        array(
            'id' => 'contact',
            'selector' => '.section-contact',
            'settings' => array(
                'sumerian_contact_title',
                'sumerian_contact_subtitle',
                'sumerian_contact_desc',
                'sumerian_contact_cf7',
                'sumerian_contact_cf7_disable',
                'sumerian_contact_text',
                'sumerian_contact_address_title',
                'sumerian_contact_address',
                'sumerian_contact_phone',
                'sumerian_contact_email',
                'sumerian_contact_fax',
            ),
        ),

        // section counter
        array(
            'id' => 'counter',
            'selector' => '.section-counter',
            'settings' => array(
                'sumerian_counter_boxes',
                'sumerian_counter_title',
                'sumerian_counter_subtitle',
                'sumerian_counter_desc',
            ),
        ),
        // section videolightbox
        array(
            'id' => 'videolightbox',
            'selector' => '.section-videolightbox',
            'settings' => array(
                'sumerian_videolightbox_title',
                'sumerian_videolightbox_url',
            ),
        ),

        // Section about
        array(
            'id' => 'about',
            'selector' => '.section-about',
            'settings' => array(
                'sumerian_about_boxes',
                'sumerian_about_title',
                'sumerian_about_subtitle',
                'sumerian_about_desc',
                'sumerian_about_content_source',
                'sumerian_about_layout',
            ),
        ),

        // Section team
        array(
            'id' => 'team',
            'selector' => '.section-team',
            'settings' => array(
                'sumerian_team_members',
                'sumerian_team_title',
                'sumerian_team_subtitle',
                'sumerian_team_desc',
                'sumerian_team_layout',
            ),
        ),
    );

    $selective_refresh_keys = apply_filters( 'sumerian_customizer_partials_selective_refresh_keys', $selective_refresh_keys );

    foreach ( $selective_refresh_keys as $section ) {
        foreach ( $section['settings'] as $key ) {
            if ( $wp_customize->get_setting( $key ) ) {
                $wp_customize->get_setting( $key )->transport = 'postMessage';
            }
        }

        $wp_customize->selective_refresh->add_partial( 'section-'.$section['id'] , array(
            'selector' => $section['selector'],
            'settings' => $section['settings'],
            'render_callback' => 'sumerian_selective_refresh_render_section_content',
        ));
    }

    $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
    $wp_customize->get_setting( 'sumerian_hide_sitetitle' )->transport = 'postMessage';
    $wp_customize->get_setting( 'sumerian_hide_tagline' )->transport = 'postMessage';
    $wp_customize->selective_refresh->add_partial( 'header_brand', array(
        'selector' => '.site-header .site-branding',
        'settings' => array( 'blogname', 'blogdescription', 'sumerian_hide_sitetitle', 'sumerian_hide_tagline' ),
        'render_callback' => 'sumerian_site_logo',
    ) );

    // Footer social heading
    $wp_customize->selective_refresh->add_partial( 'sumerian_social_footer_title', array(
        'selector' => '.footer-social .follow-heading',
        'settings' => array( 'sumerian_social_footer_title' ),
        'render_callback' => 'sumerian_selective_refresh_social_footer_title',
    ) );
    // Footer social icons
    $wp_customize->selective_refresh->add_partial( 'sumerian_social_profiles', array(
        'selector' => '.footer-social .footer-social-icons',
        'settings' => array( 'sumerian_social_profiles' ),
        'render_callback' =>  'sumerian_get_social_profiles',
    ) );

    // Footer New letter heading
    $wp_customize->selective_refresh->add_partial( 'sumerian_newsletter_title', array(
        'selector' => '.footer-subscribe .follow-heading',
        'settings' => array( 'sumerian_newsletter_title' ),
        'render_callback' => 'sumerian_selective_refresh_newsletter_title',
    ) );

    /**
     * Footer Widgets
     * @since 2.0.0
     */
    $wp_customize->selective_refresh->add_partial( 'sumerian-footer-widgets', array(
        'selector' => '#footer-widgets',
        'settings' => array( 'footer_layout', 'footer_custom_1_columns', 'footer_custom_2_columns', 'footer_custom_3_columns', 'footer_custom_4_columns' ),
        'render_callback' => 'sumerian_footer_widgets',
        'container_inclusive' => true
    ) );

    /**
     * Header Position
     * @since 2.0.0
     */
    $wp_customize->selective_refresh->add_partial( 'sumerian-header-section', array(
        'selector' => '#header-section',
        'settings' => array( 'sumerian_header_position', 'sumerian_sticky_header_disable', 'sumerian_header_transparent', 'sumerian_header_width' ),
        'render_callback' => 'sumerian_header',
        'container_inclusive' => true
    ) );

    /**
     * Footer Connect
     * @since 2.0.0
     */
    $wp_customize->selective_refresh->add_partial( 'sumerian-footer-connect', array(
        'selector' => '.footer-connect',
        'settings' => array( 'sumerian_newsletter_disable', 'sumerian_social_disable' ),
        'render_callback' => 'sumerian_footer_connect',
        'container_inclusive' => true
    ) );

    /**
     * Selective Refresh style
     * @since 2.0.0
     */
    $css_settings = array(
        'sumerian_logo_height',

        'sumerian_transparent_site_title_c',
        'sumerian_transparent_tag_title_c',
        'sumerian_logo_height',

        'sumerian_hero_overlay_color',
        //'sumerian_hero_overlay_opacity',
        'sumerian_primary_color',
        'sumerian_menu_item_padding',

        'sumerian_page_cover_align',
        'sumerian_page_cover_color',
        'sumerian_page_cover_overlay',
        'sumerian_page_cover_pd_top',
        'sumerian_page_cover_pd_bottom',

        'sumerian_header_bg_color',
        'sumerian_menu_color',
        'sumerian_menu_hover_color',
        'sumerian_menu_hover_bg_color',
        'sumerian_menu_hover_bg_color',
        'sumerian_menu_toggle_button_color',
        'sumerian_logo_text_color',
        'sumerian_footer_info_bg',

        'sumerian_footer_bg',
        'sumerian_footer_top_color',

        'sumerian_footer_c_color',
        'sumerian_footer_c_link_color',
        'sumerian_footer_c_link_hover_color',

        'footer_widgets_color',
        'footer_widgets_bg_color',
        'footer_widgets_title_color',
        'footer_widgets_link_color',
        'footer_widgets_link_hover_color',

        'sumerian_hcl1_r_color',
        'sumerian_hcl1_r_bg_color'

    );

    foreach( $css_settings as $index => $key ) {
        if ( $wp_customize->get_setting( $key ) ) {
            $wp_customize->get_setting( $key )->transport = 'postMessage';

        } else {
            unset( $css_settings[ $index ] );
        }
    }

    $wp_customize->selective_refresh->add_partial( 'sumerian-style-live-css', array(
        'selector' => '#sumerian-style-inline-css',
        'settings' => $css_settings,
        'render_callback' => 'sumerian_custom_inline_style',
    ) );

    // Retina logo
     $wp_customize->selective_refresh->add_partial( 'sumerian_site_logo', array(
         'selector' => '.site-branding',
         'settings' => array('sumerian_retina_logo', 'sumerian_transparent_logo', 'sumerian_transparent_retina_logo' ),
         'render_callback' => 'sumerian_site_logo',
     ) );

}
add_action( 'customize_register', 'sumerian_customizer_partials', 199 );



/**
 * Selective render content
 *
 * @param $partial
 * @param array $container_context
 */
function sumerian_selective_refresh_render_section_content( $partial, $container_context = array() ) {
    $tpl = 'section-parts/'.$partial->id.'.php';
    $GLOBALS['sumerian_is_selective_refresh'] = true;
    $file = sumerian_customizer_load_template( $tpl );
    if ( $file ) {
        include $file;
    }
}

function sumerian_selective_refresh_social_footer_title(){
    return get_theme_mod( 'sumerian_social_footer_title' );
}

function sumerian_selective_refresh_newsletter_title(){
    return get_theme_mod( 'sumerian_newsletter_title' );
}
