<?php
$id       = get_theme_mod( 'sumerian_career_id', 'career' );
$disable  = get_theme_mod( 'sumerian_career_disable' ) == 1 ? true : false;
$heading  = get_theme_mod( 'sumerian_career_title' );
$desc    = get_theme_mod( 'sumerian_career_desc' );
if ( sumerian_is_selective_refresh() ) {
    $disable = false;
}
if ( ( ! $disable &&  $heading )  || sumerian_is_selective_refresh() ) {

    $image    = get_theme_mod( 'sumerian_career_image' );
    if ( ! sumerian_is_selective_refresh() ){  ?>
    <section id="<?php if ($id != '') echo esc_attr( $id ); ?>" <?php do_action('sumerian_section_atts', 'career'); ?>
         class="<?php echo esc_attr(apply_filters('sumerian_section_class', 'section-career section-padding section-padding-larger onepage-section', 'career')); ?>"
      style="<?php
      if($image){?>
        background-image: url('<?php echo esc_url( $image ); ?>');
      <?php } ?>"
    >

    <?php } ?>
        <div class="section-overlay"></div>
        <?php do_action('sumerian_section_before_inner', 'career'); ?>
        <div class="<?php echo esc_attr( apply_filters( 'sumerian_section_container_class', 'container', 'career' ) ); ?>">
            <?php if ( $heading ) { ?>
            <h2 class="career-heading"><?php echo do_shortcode( wp_kses_post( $heading ) ); ?></h2>
            <?php } ?>
            <?php if ( $desc ) { ?>
            <div class="career-content"><?php echo do_shortcode( wp_kses_post( $desc ) ); ?></div>
            <?php } ?>
            <div class="to-animate"><a href="mailto:hrteam@beyond360.com" class="btn btn-primary">Join Us!</a></div>
        </div>
        <?php do_action('sumerian_section_after_inner', 'career'); ?>

        <?php if ( ! sumerian_is_selective_refresh() ) { ?>

    </section>
        <?php
    }?>

<?php
}
