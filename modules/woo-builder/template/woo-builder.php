<?php
/**
 * Woobuilder Template
 *
 * @package Woostify Pro
 */

// Header.
require_once WOOSTIFY_PRO_PATH . 'modules/woo-builder/template/header.php';

// Do not display content on single Woo Builder post type.
if ( ! woostify_is_elementor_editor() && is_singular( 'woo_builder' ) ) {
	require_once WOOSTIFY_PRO_PATH . 'modules/woo-builder/template/footer.php';
	return;
}

// For Woo builder.
$woo_builder = new Woostify_Woo_Builder();
$cart_page   = $woo_builder->get_template_id( 'woostify_cart_page' );
$cart_empty  = $woo_builder->get_template_id( 'woostify_cart_empty' );
$args        = array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 1,
	'fields'         => 'ids',
);

$products = get_posts( $args );

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();

		if ( $cart_page && ! empty( $products ) ) {
			WC()->cart->add_to_cart( $products[0] );
		} elseif ( $cart_empty ) {
			WC()->cart->empty_cart();
		}

		the_content();
	}
}

// Footer.
require_once WOOSTIFY_PRO_PATH . 'modules/woo-builder/template/footer.php';
