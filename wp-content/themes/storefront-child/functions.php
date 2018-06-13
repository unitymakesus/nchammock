<?php
// Customizing Header Hooks
function header_add_and_remove() {
  remove_action( 'storefront_header', 'storefront_product_search', 40 );
  add_action( 'storefront_header', 'storefront_product_search', 15 );

  remove_action( 'storefront_header', 'storefront_header_cart', 60 );
  add_action( 'storefront_header', 'storefront_header_cart', 25 );

  remove_action( 'storefront_header', 'storefront_primary_navigation', 50 );
  add_action( 'storefront_before_content', 'storefront_primary_navigation', 5 );

  remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
}
add_action( 'init' , 'header_add_and_remove');

// Customizing Footer Hooks
function footer_remove() {
  remove_action( 'storefront_footer', 'storefront_credit', 20 );
}
add_action( 'init' , 'footer_remove');


// Customizing Homepage Hooks
function homepage_add_and_remove() {
  remove_action( 'homepage', 'storefront_product_categories', 20 );
  remove_action( 'homepage', 'storefront_recent_products', 30 );
  remove_action( 'homepage', 'storefront_popular_products', 50 );
  remove_action( 'homepage', 'storefront_on_sale_products', 60 );
  remove_action( 'homepage', 'storefront_best_selling_products', 70 );
  remove_action( 'storefront_homepage', 'storefront_homepage_header', 10 );
}
add_action( 'init' , 'homepage_add_and_remove');

// Customizing Site Logo
function custom_logo_size() {
  add_theme_support('custom-logo', apply_filters( 'storefront_custom_logo_args', array(
    'height'      => 400,
    'width'       => 400,
    'flex-width'  => true,
  )));
}
add_action( 'after_setup_theme', 'custom_logo_size');

// Enqueuing Google Fonts
function add_google_font() {
  wp_enqueue_style( 'add_google_font', 'https://fonts.googleapis.com/css?family=Arvo:400,400i,700,700i|Khula:300,400,600,700,800', false );
}
add_action( 'wp_enqueue_scripts', 'add_google_font' );

// Renaming Product Tabs
add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {

	$tabs['description']['title'] = __( 'Features' );
	$tabs['additional_information']['title'] = __( 'Specifications' );
	return $tabs;
}

// Adjusting Featured Products
if ( ! function_exists( 'storefront_featured_products' ) ) {
	function storefront_featured_products( $args ) {
		if ( storefront_is_woocommerce_activated() ) {

			$args = apply_filters( 'storefront_featured_products_args', array(
				'limit'   => 6,
				'columns' => 3,
				'orderby' => 'date',
				'order'   => 'desc',
				'title'   => __( 'FEATURED PRODUCTS', 'storefront' ),
			) );

			$shortcode_content = storefront_do_shortcode( 'featured_products', apply_filters( 'storefront_featured_products_shortcode_args', array(
				'per_page' => intval( $args['limit'] ),
				'columns'  => intval( $args['columns'] ),
				'orderby'  => esc_attr( $args['orderby'] ),
				'order'    => esc_attr( $args['order'] ),
			) ) );

			if ( false !== strpos( $shortcode_content, 'product' ) ) {
				echo '<section class="storefront-product-section storefront-featured-products" aria-label="' . esc_attr__( 'Featured Products', 'storefront' ) . '">';
				do_action( 'storefront_homepage_before_featured_products' );
				echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';
				do_action( 'storefront_homepage_after_featured_products_title' );
				echo $shortcode_content;
				do_action( 'storefront_homepage_after_featured_products' );
				echo '</section>';

			}
		}
	}
}

// Footer Authorized Retailers Shortcode
  function auth_retailers_shortcode() { ob_start(); ?>

<div class="authorized-retailers">
  <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/cacoon.png"/>
  <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/pawleys.png"/>
  <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hatteras.png"/>
</div>

<?php
  return ob_get_clean(); }
  add_shortcode('authorize_retailers', 'auth_retailers_shortcode');
