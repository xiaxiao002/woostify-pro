<?php
/**
 * Elementor Product Short Description Widget
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
class Woostify_Product_Short_Description extends Widget_Base {
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
		return 'woostify-product-description';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Product Short Description', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-product-description';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'woocommerce', 'shop', 'product', 'description', 'store' );
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
			'color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-product-details__short-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typo',
				'selector' => '{{WRAPPER}} .woocommerce-product-details__short-description',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'woostify-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render
	 */
	public function render() {
		global $product, $post;
		if ( woostify_is_elementor_editor() ) {
			$product_id = \Woostify_Woo_Builder::init()->get_product_id();
			$product    = wc_get_product( $product_id );

			$post = get_post( $product_id ); // phpcs:ignore
			if ( ! $post ) {
				return;
			}
		}

		if ( empty( $product ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		wc_get_template( 'single-product/short-description.php' );
		wp_reset_postdata();
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Product_Short_Description() );
