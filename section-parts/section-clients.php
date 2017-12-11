<?php
$id       = get_theme_mod( 'sumerian_clients_id', esc_html__('gallery', 'sumerian') );
$disable  = get_theme_mod( 'sumerian_clients_disable', 1 ) ==  1 ? true : false;
$title    = get_theme_mod( 'sumerian_clients_title', esc_html__('Gallery', 'sumerian' ));
$subtitle = get_theme_mod( 'sumerian_clients_subtitle', esc_html__('Section subtitle', 'sumerian' ));
$desc     = get_theme_mod( 'sumerian_clients_desc' );
$clients_data     = get_theme_mod( 'sumerian_clients_logos' );

if ( sumerian_is_selective_refresh() ) {
    $disable = false;
}
$layout = get_theme_mod( 'sumerian_clients_layout', 'default' );

?>
<?php if ( ! $disable ) { ?>
    <?php if ( ! sumerian_is_selective_refresh() ){ ?>
        <section id="<?php echo esc_attr( $id ); ?>" <?php do_action('sumerian_section_atts', 'gallery'); ?>
        class="<?php echo esc_attr(apply_filters('sumerian_section_class', 'section-gallery '.( ( $title || $subtitle || $desc ) ? 'section-padding' : '' ).' section-meta onepage-section', 'gallery' )); ?>">
    <?php } ?>
    <?php do_action('sumerian_section_before_inner', 'clients'); ?>

    <div class="g-layout-<?php echo esc_attr( $layout ); ?> <?php echo esc_attr( apply_filters( 'sumerian_section_container_class', 'container', 'clients' ) ); ?>">
        <?php if ( $title || $subtitle || $desc ){ ?>
            <div class="section-title-area">
                <?php if ($subtitle != '') echo '<h5 class="section-subtitle">' . esc_html($subtitle) . '</h5>'; ?>
                <?php if ($title != '') echo '<h2 class="section-title">' . esc_html($title) . '</h2>'; ?>
                <?php if ( $desc ) {
                    echo '<div class="section-desc">' . apply_filters( 'sumerian_the_content', wp_kses_post( $desc ) ) . '</div>';
                } ?>
            </div>
        <?php } ?>
        <div class="clients-content">
          <?php
          if (is_string($clients_data)) {
              $clients_data = json_decode($clients_data, true);
          }
          if (!empty($clients_data) && is_array($clients_data)) {
            foreach ($clients_data as $k => $v) {
              $logo1  = (isset($v['logo1'])  && !empty($v['logo1'])) ?  $v["logo1"]['url']: "";
              $logo2  = (isset($v['logo2'])  && !empty($v['logo2'])) ?  $v["logo2"]['url']: "";
              $title1 = (isset($v['title1']) && !empty($v['title1'])) ? $v["title1"]: "";
              $title2 = (isset($v['title2']) && !empty($v['title2'])) ? $v["title2"]: "";
              ?>
              <div class="col-md-3 col-sm-6 col-xs-6 col-xxs-12">
                  <div class="bk-clients-figure to-animate fadeInUp">
                      <figure>
                          <img style="margin:0 auto;" src="<?php echo $logo1;?>" alt="<?php echo $title1;?>" class="img-responsive">
                      </figure>
                      <figure style="margin-bottom:0px">
                          <img style="margin:0 auto;" src="<?php echo $logo2;?>" alt="<?php echo $title2;?>" class="img-responsive">
                      </figure>
                  </div>
              </div>
            <?php }
          }
          ?>
        </div>
    </div>
    <?php do_action('sumerian_section_after_inner', 'gallery'); ?>
    <?php if ( ! sumerian_is_selective_refresh() ){ ?>
        </section>
    <?php } ?>
<?php }
