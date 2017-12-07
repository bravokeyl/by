<?php
$id         = get_theme_mod( 'sumerian_hero_id', esc_html__('hero', 'sumerian') );
$disable    =  sanitize_text_field( get_theme_mod( 'sumerian_hero_disable' ) ) == 1 ? true : false ;
$fullscreen = sanitize_text_field( get_theme_mod( 'sumerian_hero_fullscreen' ) );
$pdtop      = floatval( get_theme_mod( 'sumerian_hero_pdtop', '10' ) );
$pdbottom   = floatval( get_theme_mod( 'sumerian_hero_pdbotom', '10' ) );

if ( sumerian_is_selective_refresh() ) {
    $disable = false;
}

$hero_content_style = '';
if ( $fullscreen != '1' ) {
	$hero_content_style = ' style="padding-top: '. $pdtop .'%; padding-bottom: '. $pdbottom .'%;"';
}

$_images = get_theme_mod('sumerian_hero_images');
if (is_string($_images)) {
	$_images = json_decode($_images, true);
}

if ( empty( $_images ) || !is_array( $_images ) ) {
    $_images = array();
}

$images = array();

foreach ( $_images as $m ) {
	$m  = wp_parse_args( $m, array('image' => '' ) );
	$_u = sumerian_get_media_url( $m['image'] );
	if ( $_u ) {
		$images[] = $_u;
	}
}

if ( empty( $images ) ){
	$images = array( get_template_directory_uri().'/assets/images/home-bg.jpg' );
}

$is_parallax =  get_theme_mod( 'sumerian_hero_parallax' ) == 1 && ! empty( $images ) ;

if ( $is_parallax ) {
    echo '<div id="parallax-hero" class="parallax-hero parallax-window" >';
    echo '<div class="parallax-bg" style="background-image: url('.esc_url( $images[0]).');" data-stellar-ratio="0.1" data-stellar-offset-parent="true"></div>';
}

?>
<?php if ( ! $disable && ! empty ( $images ) ) : ?>
	<section  id="<?php if ( $id != '' ){ echo esc_attr( $id ); } ?>" <?php if ( ! empty ( $images) && ! $is_parallax ) { ?> data-images="<?php echo esc_attr( json_encode( $images ) ); ?>"<?php } ?>
             class="hero-slideshow-wrapper <?php echo ( $fullscreen == 1 ) ? 'hero-slideshow-fullscreen' : 'hero-slideshow-normal'; ?>">

        <?php if ( ! get_theme_mod( 'sumerian_hero_disable_preload', false ) ) { ?>
            <div class="slider-spinner">
                <div class="double-bounce1"></div>
                <div class="double-bounce2"></div>
            </div>
        <?php } ?>

        <?php
		$layout = get_theme_mod( 'sumerian_hero_layout', 1 );
		switch( $layout ) {
			case 2:
				$hcl2_content =  get_theme_mod( 'sumerian_hcl2_content', wp_kses_post( '<h1>Business Website'."\n".'Made Simple.</h1>'."\n".'We provide creative solutions to clients around the world,'."\n".'creating things that get attention and meaningful.'."\n\n".'<a class="btn btn-secondary-outline btn-lg" href="#">Get Started</a>' ) );
				$hcl2_image   =  get_theme_mod( 'sumerian_hcl2_image', get_template_directory_uri().'/assets/images/sumerian_responsive.png' );
				?>
				<div class="container"<?php echo $hero_content_style; ?>>
					<div class="row hero__content hero-content-style<?php echo esc_attr( $layout ); ?>">
						<div class="col-md-12 col-lg-6">
							<?php if ( $hcl2_content ) { echo '<div class="hcl2-content">'.apply_filters( 'the_content', do_shortcode( wp_kses_post( $hcl2_content ) ) ).'</div>' ; }; ?>
						</div>
						<div class="col-md-12 col-lg-6">
							<?php if ( $hcl2_image ) { echo '<img class="hcl2-image" src="'.esc_url( $hcl2_image ).'" alt="">' ; }; ?>
						</div>
					</div>
				</div>
				<?php
			break;
			default:
				$hcl1_largetext  = get_theme_mod( 'sumerian_hcl1_largetext', wp_kses_post('A social employee engagement portal', 'sumerian' ));
				?>
				<div class="container"<?php echo $hero_content_style; ?>>
					<div class="hero__content hero-content-style<?php echo esc_attr( $layout ); ?>">
						<?php if ($hcl1_largetext != '') echo '<h2 class="hero-large-text">' . wp_kses_post($hcl1_largetext) . '</h2>'; ?>
					</div>
				</div>
				<?php
		}

	?>
	</section>
<?php endif;

if ( $is_parallax ) {

    echo '</div>'; // end parallax
}
