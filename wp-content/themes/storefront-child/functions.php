<!-- Changing Header Hooks -->
<?php function header_add_and_remove() {
    remove_action( 'storefront_header', 'storefront_product_search', 40 );
    add_action( 'storefront_header', 'storefront_product_search', 15 );

    remove_action( 'storefront_header', 'storefront_header_cart', 60 );
    add_action( 'storefront_header', 'storefront_header_cart', 25 );

    remove_action( 'storefront_header', 'storefront_primary_navigation', 50 );
    add_action( 'storefront_before_content', 'storefront_primary_navigation', 5 );

    remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
  }

  add_action( 'init' , 'header_add_and_remove');
?>


<!-- Customizing site logo -->
<?php function custom_logo_size() {
    add_theme_support('custom-logo', apply_filters( 'storefront_custom_logo_args', array(
      'height'      => 400,
      'width'       => 400,
      'flex-width'  => true,
    )));
  }
  add_action( 'after_setup_theme', 'custom_logo_size');
?>


<!-- Enqueuing Google Fonts -->
<?php function add_google_font() {
    wp_enqueue_style( 'add_google_font', 'https://fonts.googleapis.com/css?family=Arvo:400,400i,700,700i|Khula:300,400,600,700,800', false );
  }
  add_action( 'wp_enqueue_scripts', 'add_google_font' );
?>


<!-- Customizing Homepage -->
<?php function homepage_add_and_remove() {
    remove_action( 'homepage', 'storefront_product_categories', 20 );
    remove_action( 'homepage', 'storefront_recent_products', 30 );
    remove_action( 'homepage', 'storefront_popular_products', 50 );
    remove_action( 'homepage', 'storefront_on_sale_products', 60 );
    remove_action( 'homepage', 'storefront_best_selling_products', 70 );

    remove_action( 'storefront_homepage', 'storefront_homepage_header', 10 );
  }

  add_action( 'init' , 'homepage_add_and_remove');
?>

<!-- Adjusting Featured Products -->
<?php
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

			/**
			 * Only display the section if the shortcode returns products
			 */
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
?>
