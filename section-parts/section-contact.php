<?php
$sumerian_contact_id            = get_theme_mod( 'sumerian_contact_id', esc_html__('contact', 'sumerian') );
$sumerian_contact_disable       = get_theme_mod( 'sumerian_contact_disable' ) == 1 ?  true : false;
$sumerian_contact_title         = get_theme_mod( 'sumerian_contact_title', esc_html__('Get in touch', 'sumerian' ));
$sumerian_contact_subtitle      = get_theme_mod( 'sumerian_contact_subtitle', esc_html__('Section subtitle', 'sumerian' ));
$sumerian_contact_left_title     = get_theme_mod( 'sumerian_contact_left_title' );
$sumerian_contact_right_title     = get_theme_mod( 'sumerian_contact_right_title' );
$sumerian_contact_left_text     = get_theme_mod( 'sumerian_contact_left_text', esc_html__('CORPORATE HEADQUARTER', 'sumerian') );
$sumerian_contact_right_text     = get_theme_mod( 'sumerian_contact_right_text' );

if ( sumerian_is_selective_refresh() ) {
    $sumerian_contact_disable = false;
}

if ( $sumerian_contact_left_text || $sumerian_contact_title ) {
    $desc = wp_kses_post( get_theme_mod( 'sumerian_contact_desc' ) );
    ?>
    <?php if (!$sumerian_contact_disable) : ?>
        <?php if ( ! sumerian_is_selective_refresh() ){ ?>
        <section id="<?php if ($sumerian_contact_id != '') { echo esc_attr( $sumerian_contact_id ); }; ?>" <?php do_action('sumerian_section_atts', 'counter'); ?>
                 class="<?php echo esc_attr(apply_filters('sumerian_section_class', 'section-contact section-padding  section-meta onepage-section', 'contact')); ?>">
        <?php } ?>
            <?php do_action('sumerian_section_before_inner', 'contact'); ?>
            <div class="<?php echo esc_attr( apply_filters( 'sumerian_section_container_class', 'container', 'contact' ) ); ?>">
                <?php if ( $sumerian_contact_title || $sumerian_contact_subtitle || $desc ){ ?>
                <div class="section-title-area">
                    <?php if ($sumerian_contact_subtitle != '') echo '<h5 class="section-subtitle">' . esc_html($sumerian_contact_subtitle) . '</h5>'; ?>
                    <?php if ($sumerian_contact_title != '') echo '<h2 class="section-title">' . esc_html($sumerian_contact_title) . '</h2>'; ?>
                    <?php if ( $desc ) {
                        echo '<div class="section-desc">' . apply_filters( 'sumerian_the_content', $desc ) . '</div>';
                    } ?>
                </div>
                <?php } ?>
                <div class="row">
                    <div class="col-sm-6  wow slideInUp">
                      <div class="bk-contact-col">
                        <?php
                        if($sumerian_contact_left_title != '') {
                          echo "<h4>".esc_html($sumerian_contact_left_title)."</h4>";
                        }
                        if ($sumerian_contact_left_text != '') { ?>
                          <div class="bk-contact-col-text">
                          <?php  echo apply_filters( 'sumerian_the_content', wp_kses_post( $sumerian_contact_left_text ) ); ?>
                          </div>
                      <?php  }
                        ?>
                      </div>
                    </div>
                    <div class="col-sm-6 wow slideInUp">
                      <div class="bk-contact-col">
                        <?php
                        if($sumerian_contact_right_title != '') {
                          echo "<h4>".esc_html($sumerian_contact_right_title)."</h4>";
                        }
                        if ($sumerian_contact_right_text != '') {?>
                          <div class="bk-contact-col-text">
                          <?php  echo apply_filters( 'sumerian_the_content', wp_kses_post( $sumerian_contact_right_text ) ); ?>
                          </div>
                        <?php }
                        ?>
                      </div>
                    </div>
                </div>
            </div>
            <?php do_action('sumerian_section_after_inner', 'contact'); ?>
        <?php if ( ! sumerian_is_selective_refresh() ){ ?>
        </section>
        <?php } ?>
    <?php endif;
}
