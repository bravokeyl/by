<?php
$id       = get_theme_mod( 'sumerian_sales_id', 'sales' );
$disable  = get_theme_mod( 'sumerian_sales_disable' ) == 1 ? true : false;
$heading  = get_theme_mod( 'sumerian_sales_title' );
$desc    = get_theme_mod( 'sumerian_sales_desc' );
if ( sumerian_is_selective_refresh() ) {
    $disable = false;
}
if ( ( ! $disable &&  $heading )  || sumerian_is_selective_refresh() ) {

    $image    = get_theme_mod( 'sumerian_sales_image' );
    if ( ! sumerian_is_selective_refresh() ){  ?>
    <section id="<?php if ($id != '') echo esc_attr( $id ); ?>" <?php do_action('sumerian_section_atts', 'sales'); ?>
         class="<?php echo esc_attr(apply_filters('sumerian_section_class', 'section-sales section-padding section-padding-larger onepage-section', 'sales')); ?>"
      style="<?php
      if($image){?>
        background-image: url('<?php echo esc_url( $image ); ?>');
      <?php } ?>"
    >

    <?php } ?>

        <?php do_action('sumerian_section_before_inner', 'sales'); ?>
        <div class="<?php echo esc_attr( apply_filters( 'sumerian_section_container_class', 'container', 'sales' ) ); ?>">
            <?php if ( $heading ) { ?>
            <h2 class="sales-heading"><?php echo do_shortcode( wp_kses_post( $heading ) ); ?></h2>
            <?php } ?>
            <?php if ( $desc ) { ?>
            <div class="sales-content"><?php echo do_shortcode( wp_kses_post( $desc ) ); ?></div>
            <?php } ?>
        </div>
        <?php do_action('sumerian_section_after_inner', 'sales'); ?>

        <?php if ( ! sumerian_is_selective_refresh() ) { ?>

    </section>
        <?php
    }?>

<?php
}
