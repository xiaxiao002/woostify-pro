<?php
/**
 * Elementor Product Related Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class widget.
 */
class Woostify_Product_Related extends Widget_Base {
	/**
	 * Category
	 */
	public function get_categories() {
		return array( 'woostify-product' );
	}

	/**
	 * Name
	 */
	public function get_name() {
		return 'woostify-product-related';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Product Related', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-product-related';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'woocommerce', 'shop', 'product', 'related', 'store' );
	}

	/**
	 * Controls
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'start',
			array(
				'label' => __( 'General', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'total',
			array(
				'label'   => __( 'Total Products', 'woostify-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => -1,
				'max'     => 20,
				'step'    => 1,
				'default' => 4,
			)
		);

		$this->add_control(
			'columns',
			array(
				'label'   => __( 'Columns', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 4,
				'options' => array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render
	 */
	protected function render() {
		global $product, $woocommerce_loop;
		$settings = $this->get_settings_for_display();

		if ( woostify_is_elementor_editor() ) {
			$product_id = \Woostify_Woo_Builder::init()->get_product_id();
			$product    = wc_get_product( $product_id );
		}

		if ( empty( $product ) ) {
			return;
		}

		$args = array(
			'posts_per_page' => $settings['total'],
			'orderby'        => 'rand',
		);

		$args['related_products'] = array_filter(
			array_map(
				'wc_get_product',
				wc_get_related_products(
					$product->get_id(),
					$args['posts_per_page'],
					$product->get_upsell_ids()
				)
			),
			'wc_products_array_filter_visible'
		);

		$woocommerce_loop['columns'] = $settings['columns'];
		wc_get_template( 'single-product/related.php', $args );
		woocommerce_reset_loop();
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Product_Related() );
