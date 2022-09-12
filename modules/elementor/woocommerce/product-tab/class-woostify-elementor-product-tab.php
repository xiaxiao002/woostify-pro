<?php
/**
 * Elementor Product Tab Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

/**
 * Class woostify elementor product tab widget.
 */
class Woostify_Elementor_Product_Tab extends Widget_Base {
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
		return 'woostify-new-product-tab';
	}

	/**
	 * Title
	 */
	public function get_title() {
		return __( 'Woostify - Product Tab', 'woostify-pro' );
	}

	/**
	 * Icon
	 */
	public function get_icon() {
		return 'eicon-product-tabs';
	}

	/**
	 * Controls
	 */
	protected function register_controls() { // phpcs:ignore
		$this->general();
		$this->filter();
		$this->product();
		$this->section_icons_style();
		$this->section_sale_flash();
		$this->arrows();
		$this->dots();
	}

	/**
	 * General
	 */
	private function general() {
		$this->start_controls_section(
			'general',
			array(
				'label' => esc_html__( 'General', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => array(
					'grid'     => __( 'Grid', 'woostify-pro' ),
					'carousel' => __( 'Carousel', 'woostify-pro' ),
				),
			)
		);

		$this->add_control(
			'control_arrows',
			array(
				'label'        => __( 'Show Arrows', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'condition'    => array(
					'layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'control_dots',
			array(
				'label'        => __( 'Show Dots', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'separator'    => 'after',
				'condition'    => array(
					'layout' => 'carousel',
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'woostify-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'List Title', 'woostify-pro' ),
			)
		);

		// Source.
		$repeater->add_control(
			'source',
			array(
				'label'   => esc_html__( 'Source', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'latest',
				'options' => array(
					'sale'     => esc_html__( 'Sale', 'woostify-pro' ),
					'featured' => esc_html__( 'Featured', 'woostify-pro' ),
					'latest'   => esc_html__( 'Latest Products', 'woostify-pro' ),
					'select'   => esc_html__( 'Manual Selection', 'woostify-pro' ),
				),
			)
		);

		// Include manual products.
		$repeater->add_control(
			'include_products',
			array(
				'label'     => esc_html__( 'Select Products', 'woostify-pro' ),
				'type'      => 'autocomplete',
				'condition' => array(
					'source' => 'select',
				),
				'query'     => array(
					'type' => 'post_type',
					'name' => 'product',
				),
			)
		);

		// TAB START.
		$repeater->start_controls_tabs(
			'query_tabs',
			array(
				'condition' => array(
					'source' => array( 'sale', 'featured', 'latest' ),
				),
			)
		);
		$repeater->start_controls_tab(
			'query_include',
			array(
				'label' => __( 'Include', 'woostify-pro' ),
			)
		);

		// Include products categories.
		$repeater->add_control(
			'include_terms',
			array(
				'label' => esc_html__( 'Terms', 'woostify-pro' ),
				'type'  => 'autocomplete',
				'query' => array(
					'type' => 'term',
					'name' => 'wc_term',
				),
			)
		);

		$repeater->end_controls_tab();
		$repeater->start_controls_tab(
			'query_exclude',
			array(
				'label' => __( 'Exclude', 'woostify-pro' ),
			)
		);

		// Exclude terms.
		$repeater->add_control(
			'exclude_terms',
			array(
				'label' => esc_html__( 'Terms', 'woostify-pro' ),
				'type'  => 'autocomplete',
				'query' => array(
					'type' => 'term',
					'name' => 'wc_term',
				),
			)
		);

		// Exclude products.
		$repeater->add_control(
			'exclude_products',
			array(
				'label' => esc_html__( 'Products', 'woostify-pro' ),
				'type'  => 'autocomplete',
				'query' => array(
					'type' => 'post_type',
					'name' => 'product',
				),
			)
		);

		// TAB END.
		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();

		// Posts per page.
		$repeater->add_control(
			'count',
			array(
				'label'     => esc_html__( 'Total Products', 'woostify-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 6,
				'min'       => 1,
				'max'       => 100,
				'step'      => 1,
				'separator' => 'before',
			)
		);

		// Columns.
		$repeater->add_responsive_control(
			'col',
			array(
				'type'           => Controls_Manager::SELECT,
				'label'          => esc_html__( 'Columns', 'woostify-pro' ),
				'default'        => 4,
				'tablet_default' => 3,
				'mobile_default' => 2,
				'options'        => array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
				),
			)
		);

		// Order by.
		$repeater->add_control(
			'order_by',
			array(
				'label'   => esc_html__( 'Order By', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'id',
				'options' => array(
					'id'         => esc_html__( 'ID', 'woostify-pro' ),
					'title'      => esc_html__( 'Title', 'woostify-pro' ),
					'price'      => esc_html__( 'Price', 'woostify-pro' ),
					'rating'     => esc_html__( 'Rating', 'woostify-pro' ),
					'popularity' => esc_html__( 'Popularity', 'woostify-pro' ),
					'date'       => esc_html__( 'Date', 'woostify-pro' ),
					'menu_order' => esc_html__( 'Menu Order', 'woostify-pro' ),
					'rand'       => esc_html__( 'Random', 'woostify-pro' ),
				),
			)
		);

		// Order.
		$repeater->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ASC',
				'options' => array(
					'ASC'  => esc_html__( 'ASC', 'woostify-pro' ),
					'DESC' => esc_html__( 'DESC', 'woostify-pro' ),
				),
			)
		);

		$this->add_control(
			'list',
			array(
				'label'       => __( 'Product Tab', 'woostify-pro' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'title' => __( 'Featured', 'woostify-pro' ),
						'data'  => 'featured',
					),
					array(
						'title' => __( 'Best Sellers', 'woostify-pro' ),
						'data'  => 'best-sell',
					),
				),
				'title_field' => '{{{ title }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Product
	 */
	private function product() {

		$this->start_controls_section(
			'product',
			array(
				'label' => __( 'Product', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'product_title',
			array(
				'label' => __( 'Title', 'woostify-pro' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_style_title_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woocommerce-loop-product__title a',
			)
		);

		// Title Spacing.
		$this->add_responsive_control(
			'title_spacing',
			array(
				'label'           => __( 'Spacing', 'woostify-pro' ),
				'type'            => Controls_Manager::SLIDER,
				'range'           => array(
					'px' => array(
						'max' => 200,
					),
				),
				'devices'         => array(
					'desktop',
					'tablet',
					'mobile',
				),
				'desktop_default' => array(
					'size' => 0,
					'unit' => 'px',
				),
				'tablet_default'  => array(
					'size' => 0,
					'unit' => 'px',
				),
				'mobile_default'  => array(
					'size' => 0,
					'unit' => 'px',
				),
				'selectors'       => array(
					'{{WRAPPER}} .woostify-products-tab-content .woocommerce-loop-product__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'product_style_tabs' );

		// Normal.
		$this->start_controls_tab(
			'product_style_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'product_style_title_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-loop-product__title a ' => 'color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'product_style_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'product_style_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-loop-product__title a:hover ' => 'color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Price.
		$this->add_control(
			'product_price',
			array(
				'label'     => __( 'Price', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Color Price.
		$this->add_control(
			'product_price_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .price ins span, {{WRAPPER}} .woostify-products-tab-content .price span' => 'color: {{VALUE}};',
				),
			)
		);

		// Price Typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_price_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-content .price ins span, {{WRAPPER}} .woostify-products-tab-content .price span',
			)
		);

		// Regular Price.
		$this->add_control(
			'product_regular_price',
			array(
				'label'     => __( 'Sale Price', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Color Regular Price.
		$this->add_control(
			'product_regular_price_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .price del span ' => 'color: {{VALUE}};',
				),
			)
		);

		// Regular Price Typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_regular_price_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-content .price del span',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Tabs
	 */
	private function filter() {

		$this->start_controls_section(
			'filter',
			array(
				'label' => __( 'Filter', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'filter_tab',
			array(
				'label' => __( 'Tab', 'woostify-pro' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		// Alignment.
		$this->add_control(
			'alignment',
			array(
				'type'      => Controls_Manager::CHOOSE,
				'label'     => esc_html__( 'Alignment', 'woostify-pro' ),
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'woostify-pro' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'woostify-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'woostify-pro' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-widget .woostify-products-tab-head' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'heading_margin',
			array(
				'label'              => __( 'Margin', 'woostify-pro' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px', 'em' ),
				'allowed_dimensions' => array( 'top', 'bottom' ),
				'selectors'          => array(
					'{{WRAPPER}} .woostify-products-tab-head' => 'margin: {{TOP}}{{UNIT}} 0px {{BOTTOM}}{{UNIT}} 0px;',
				),
			)
		);

		$this->add_responsive_control(
			'heading_item_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-products-tab-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'heading_border',
				'label'    => __( 'Border', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-head',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typo',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-btn',
			)
		);

		$this->add_control(
			'filter_list',
			array(
				'label'     => __( 'Tab List', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'list_border',
				'label'    => __( 'Border', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-btn',
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'style_tabs' );

		// Normal.
		$this->start_controls_tab(
			'style_tabs_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'tabs_title_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-btn' => 'color: {{VALUE}};',
				),
			)
		);

		// Background.
		$this->add_control(
			'tabs_background_color',
			array(
				'label'     => __( 'Background', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-btn' => 'background: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'style_tabs_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'style_title_hover_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-btn:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover Background.
		$this->add_control(
			'tabs_background_hover_color',
			array(
				'label'     => __( 'Background', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-btn:hover' => 'background: {{VALUE}};',
				),
			)
		);

		// Hover border.
		$this->add_control(
			'style_title_hover_border',
			array(
				'label'     => __( 'Border', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-btn:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'style_tabs_active',
			array(
				'label' => __( 'Active', 'woostify-pro' ),
			)
		);

		// Active color.
		$this->add_control(
			'style_title_active_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-btn.active' => 'color: {{VALUE}};',
				),
			)
		);

		// Active Background.
		$this->add_control(
			'tabs_background_active_color',
			array(
				'label'     => __( 'Background', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-btn.active' => 'background: {{VALUE}};',
				),
			)
		);

		// Active border.
		$this->add_control(
			'style_title_active_border',
			array(
				'label'     => __( 'Border', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-btn.active' => 'border-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Arrows
	 */
	private function arrows() {
		$this->start_controls_section(
			'arrows',
			array(
				'label'     => __( 'Arrows', 'woostify-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'layout'         => 'carousel',
					'control_arrows' => 'yes',
				),
			)
		);

		// Position.
		$this->add_control(
			'arrows_position',
			array(
				'label'   => __( 'Position', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top-right',
				'options' => array(
					'top-right' => __( 'Top Right', 'woostify-pro' ),
					'center'    => __( 'Center Center', 'woostify-pro' ),
				),
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'arrow_control_tabs' );
		// Normal.
		$this->start_controls_tab(
			'arrow_control_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'arrow_control_text_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-product-tab-arrows-container span' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'arrow_control_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-product-tab-arrows-container span' => 'background-color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'arrow_control_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'arrow_control_hover_text_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-product-tab-arrows-container span:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_control(
			'arrow_control_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-product-tab-arrows-container span:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Size.
		$this->add_responsive_control(
			'arrows_size',
			array(
				'label'      => __( 'Size', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'separator'  => 'before',
				'range'      => array(
					'px' => array(
						'max' => 200,
					),
					'%'  => array(
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-product-tab-arrows-container span' => 'min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Radius.
		$this->add_responsive_control(
			'arrows_radius',
			array(
				'label'      => __( 'Border Radius', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'max' => 200,
					),
					'%'  => array(
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-product-tab-arrows-container span' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Vertical position.
		$this->add_responsive_control(
			'arrows_vertical_position',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Vertical Position', 'woostify-pro' ),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => -250,
						'max'  => 250,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-product-tab-arrows-container [data-controls="prev"]' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .woostify-product-tab-arrows-container [data-controls="next"]' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Margin.
		$this->add_responsive_control(
			'arrows_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-product-tab-arrows-container span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Dots
	 */
	protected function dots() {
		$this->start_controls_section(
			'dots',
			array(
				'label'     => __( 'Dots', 'woostify-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'layout'       => 'carousel',
					'control_dots' => 'yes',
				),
			)
		);

		// Dots size.
		$this->add_responsive_control(
			'dots_size',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Size', 'woostify-pro' ),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .tns-nav [data-nav]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Dots border radius.
		$this->add_responsive_control(
			'dots_border',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Border Radius', 'woostify-pro' ),
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .tns-nav [data-nav]' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Dots position.
		$this->add_responsive_control(
			'dots_position',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Vertical Position', 'woostify-pro' ),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => -150,
						'max'  => 150,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 30,
				),
				'selectors'  => array(
					'{{WRAPPER}} .tns-nav' => 'bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Dots background color.
		$this->add_control(
			'dots_bg_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Background Color', 'woostify-pro' ),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .tns-nav [data-nav]' => 'background-color: {{VALUE}}',
				),
			)
		);

		// Dot current background color.
		$this->add_control(
			'dots_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Active Dot', 'woostify-pro' ),
				'selectors' => array(
					'{{WRAPPER}} .tns-nav [data-nav].tns-nav-active' => 'background-color: {{VALUE}}',
				),
			)
		);

		// Dots alignment.
		$this->add_responsive_control(
			'dots_alignment',
			array(
				'type'           => Controls_Manager::CHOOSE,
				'label'          => esc_html__( 'Alignment', 'woostify-pro' ),
				'options'        => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'woostify-pro' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'woostify-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'woostify-pro' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'        => 'center',
				'tablet_default' => 'center',
				'mobile_default' => 'center',
				'selectors'      => array(
					'{{WRAPPER}} .tns-nav' => 'text-align: {{VALUE}};',
				),
				'separator'      => 'before',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Sale Flash
	 */
	private function section_sale_flash() {
		$this->start_controls_section(
			'section_sale_flash',
			array(
				'label' => __( 'Sale Flash', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Color.
		$this->add_control(
			'product_sale_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .woostify-tag-on-sale' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_control(
			'product_sale_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .woostify-tag-on-sale' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_sale_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-content .woostify-tag-on-sale',
			)
		);

		// Border Sale radius.
		$this->add_responsive_control(
			'product_sale_border_radius',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => __( 'Border Radius', 'woostify-pro' ),
				'size_units' => array(
					'px',
					'em',
				),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-products-tab-content .woostify-tag-on-sale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Sale Width.
		$this->add_control(
			'sale_width',
			array(
				'label'     => __( 'Width', 'woostify-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .woostify-tag-on-sale' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Sale Height.
		$this->add_control(
			'sale_height',
			array(
				'label'     => __( 'Height', 'woostify-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .woostify-tag-on-sale' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Button Style.
	 */
	private function section_icons_style() {
		$this->start_controls_section(
			'section_button',
			array(
				'label' => __( 'Button', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Button.
		$this->add_control(
			'product_button',
			array(
				'label' => __( 'Add To Cart', 'woostify-pro' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		// Button Typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_button_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-content .button',
			)
		);

		// Border.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'product_button_border',
				'label'    => __( 'Border', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-content .button',
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'product_button_tabs' );

		// Normal.
		$this->start_controls_tab(
			'product_button_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'product_button_text_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .button' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'product_button_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .button' => 'background-color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'product_button_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'product_hover_text_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_control(
			'product_button_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Hover border color.
		$this->add_control(
			'product_button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Button Spacing.
		$this->add_responsive_control(
			'button_spacing',
			array(
				'label'           => __( 'Spacing', 'woostify-pro' ),
				'type'            => Controls_Manager::SLIDER,
				'range'           => array(
					'px' => array(
						'max' => 200,
					),
				),
				'devices'         => array(
					'desktop',
					'tablet',
					'mobile',
				),
				'desktop_default' => array(
					'size' => 0,
					'unit' => 'px',
				),
				'tablet_default'  => array(
					'size' => 0,
					'unit' => 'px',
				),
				'mobile_default'  => array(
					'size' => 0,
					'unit' => 'px',
				),
				'selectors'       => array(
					'{{WRAPPER}} .woostify-products-tab-content .button' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icons_quickview',
			array(
				'label'     => __( 'Quick View', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_style_quickview_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-content .product-quick-view-btn:before',
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'product_quickview_tabs' );

		// Normal.
		$this->start_controls_tab(
			'product_quickview_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'product_quickview_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .product-quick-view-btn:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woostify-products-tab-content .product-quick-view-btn' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'product_quickview_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .product-quick-view-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'product_quickview_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'product_hover_quickview_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .quick-view-with-text:hover.product-quick-view-btn:before,
					{{WRAPPER}} .woostify-products-tab-content .product-quick-view-btn:hover,
					{{WRAPPER}} .woostify-products-tab-content .quick-view-with-icon:hover.product-quick-view-btn:before' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_control(
			'product_quickview_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .product-quick-view-btn:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'icons_wishlist',
			array(
				'label'     => __( 'Wishlist', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_style_wishlist_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-content .tinvwl_add_to_wishlist_button:before',
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'product_wishlist_tabs' );

		// Normal.
		$this->start_controls_tab(
			'product_wishlist_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'product_wishlist_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .tinvwl_add_to_wishlist_button:before' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'product_wishlist_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .tinvwl_add_to_wishlist_button' => 'background-color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'product_wishlist_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'product_hover_wishlist_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .tinvwl-position-after:hover.tinvwl_add_to_wishlist_button:before' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_control(
			'product_wishlist_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .tinvwl_add_to_wishlist_button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Product tab render
	 */
	public function product_tab_render() {
		$settings = $this->get_settings_for_display();
		$list     = $settings['list'];
		$response = array();

		foreach ( $list as $k ) {
			$args = array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => $k['count'],
				'order'          => $k['order'],
			);

			switch ( $k['order_by'] ) {
				case 'price':
					$args['orderby']  = 'meta_value_num';
					$args['meta_key'] = '_price'; // phpcs:ignore
					break;
				case 'rating':
					$args['orderby']  = 'meta_value_num';
					$args['meta_key'] = '_wc_average_rating'; // phpcs:ignore
					break;
				case 'popularity':
					$args['orderby']  = 'meta_value_num';
					$args['meta_key'] = 'total_sales'; // phpcs:ignore
					break;
				default:
					$args['orderby'] = $k['order_by'];
					break;
			}

			switch ( $k['source'] ) {
				case 'sale':
					$post__in = wc_get_product_ids_on_sale();
					if ( ! empty( $post__in ) ) {
						$args['post__in'] = $post__in;
					}
					break;
				case 'featured':
					$product_visibility_term_ids = wc_get_product_visibility_term_ids();
					if ( ! empty( $product_visibility_term_ids['featured'] ) ) {
						$args['tax_query'][] = array(
							'taxonomy' => 'product_visibility',
							'field'    => 'term_taxonomy_id',
							'terms'    => array( $product_visibility_term_ids['featured'] ),
						);
					}
					break;
				case 'select':
					if ( ! empty( $k['include_products'] ) ) {
						$args['post__in'] = $k['include_products'];
					}
					break;
			}

			// Not apply for 'select' query select product.
			if ( in_array( $k['source'], array( 'sale', 'featured', 'latest' ), true ) ) {
				// Products.
				if ( ! empty( $k['exclude_products'] ) ) {
					$args['post__not_in'] = empty( $args['post__in'] ) ? $k['exclude_products'] : array_diff( $args['post__in'], $k['exclude_products'] );
				}

				// Terms.
				global $wp_taxonomies;
				$in_term_id = empty( $k['include_terms'] ) ? array() : $k['include_terms'];
				$ex_term_id = empty( $k['exclude_terms'] ) ? array() : $k['exclude_terms'];

				$include_term_ids = array_diff( $in_term_id, $ex_term_id );
				$exclude_term_ids = empty( $in_term_id ) && ! empty( $ex_term_id ) ? $ex_term_id : array();

				if ( ! empty( $include_term_ids ) ) {
					foreach ( $include_term_ids as $in ) {
						$in_term = get_term( $in );

						$args['tax_query'][] = array(
							'taxonomy' => $wp_taxonomies[ $in_term->taxonomy ]->name,
							'field'    => 'term_id',
							'terms'    => $in,
						);
					}
				} elseif ( ! empty( $exclude_term_ids ) ) {
					foreach ( $exclude_term_ids as $ex ) {
						$ex_term = get_term( $ex );

						$args['tax_query'][] = array(
							'taxonomy' => $wp_taxonomies[ $ex_term->taxonomy ]->name,
							'field'    => 'term_id',
							'terms'    => $ex,
							'operator' => 'NOT IN',
						);
					}
				}
			}

			array_push( $response, $args );
		}

		return $response;
	}

	/**
	 * Render
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$list     = $settings['list'];
		if ( empty( $list ) ) {
			return;
		}

		$data        = $this->product_tab_render();
		$arrow_left  = apply_filters( 'woostify_product_tab_carousel_arrow_left_icon', 'angle-left' );
		$arrow_right = apply_filters( 'woostify_product_tab_carousel_arrow_right_icon', 'angle-right' );

		$list_first     = array_shift( $list );
		$data_first     = array_shift( $data );
		$columns        = isset( $list_first['col'] ) ? $list_first['col'] : 4;
		$columns_tablet = isset( $list_first['col_tablet'] ) ? $list_first['col_tablet'] : 3;
		$columns_mobile = isset( $list_first['col_mobile'] ) ? $list_first['col_mobile'] : 2;

		// Detect first slider init.
		$has_slider = ! empty( $data_first['posts_per_page'] ) && intval( $data_first['posts_per_page'] ) > intval( $list_first['col'] ) ? 'has-slider' : '';
		?>

		<div class="woostify-products-tab-widget" data-layout="<?php echo esc_attr( $settings['layout'] ); ?>-layout">
			<div class="woostify-products-tab-head">
				<div class="woostify-products-tab-head-buttons">
					<span class="woostify-products-tab-btn ready active" data-id="<?php echo esc_attr( $list_first['_id'] ); ?>"><?php echo esc_html( $list_first['title'] ); ?></span>

					<?php
					if ( ! empty( $list ) ) {
						foreach ( $list as $v ) {
							?>
							<span class="woostify-products-tab-btn" data-id="<?php echo esc_attr( $v['_id'] ); ?>"><?php echo esc_html( $v['title'] ); ?></span>
							<?php
						}
					}
					?>
				</div>

				<?php if ( 'carousel' === $settings['layout'] && 'yes' === $settings['control_arrows'] && 'top-right' === $settings['arrows_position'] ) { ?>
					<div class="woostify-product-tab-carousel-arrows">
						<div class="woostify-product-tab-arrows-container active <?php echo esc_attr( $has_slider ? '' : 'hidden' ); ?>" data-id="<?php echo esc_attr( $list_first['_id'] ); ?>">
							<?php
							echo woostify_fetch_svg_icon( $arrow_left ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo woostify_fetch_svg_icon( $arrow_right ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</div>

						<?php
						if ( ! empty( $list ) ) {
							foreach ( $list as $v ) {
								?>
								<div class="woostify-product-tab-arrows-container" data-id="<?php echo esc_attr( $v['_id'] ); ?>">
								<?php
								echo woostify_fetch_svg_icon( $arrow_left ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo woostify_fetch_svg_icon( $arrow_right ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
								</div>
								<?php
							}
						}
						?>
					</div>
				<?php } ?>
			</div>

			<div class="woostify-products-tab-body">
				<div class="woostify-products-tab-content active <?php echo esc_attr( $has_slider ); ?>"
					data-columns="<?php echo esc_attr( $columns ); ?>"
					data-columns-tablet="<?php echo esc_attr( $columns_tablet ); ?>"
					data-columns-mobile="<?php echo esc_attr( $columns_mobile ); ?>"
					data-arrows="<?php echo esc_attr( $settings['control_arrows'] ); ?>"
					data-dots="<?php echo esc_attr( $settings['control_dots'] ); ?>"
					data-id="<?php echo esc_attr( $list_first['_id'] ); ?>">
					<?php
					$query = new \WP_Query( $data_first );

					$options = woostify_options( false );
					// Legacy columns.
					$product_col = array(
						'columns-' . wc_get_loop_prop( 'columns' ),
						'tablet-columns-' . $options['tablet_products_per_row'],
						'mobile-columns-' . $options['mobile_products_per_row'],
					);
					$product_col = implode( ' ', array_filter( $product_col ) );

					// Widget columns.
					$current_col = array(
						'columns-' . $columns,
						'tablet-columns-' . $columns_tablet,
						'mobile-columns-' . $columns_mobile,
					);
					$current_col = implode( ' ', array_filter( $current_col ) );

					ob_start();
					if ( $query->have_posts() ) {
						woocommerce_product_loop_start();

						while ( $query->have_posts() ) {
							$query->the_post();

							wc_get_template_part( 'content', 'product' );
						}

						// Reset loop.
						woocommerce_reset_loop();
						wp_reset_postdata();

						woocommerce_product_loop_end();
					}
					$output = ob_get_clean();
					echo str_replace( $product_col, $current_col, $output ); // phpcs:ignore
					?>
				</div>

				<?php
				if ( ! empty( $list ) ) {
					foreach ( $list as $i => $j ) {
						$cols        = isset( $j['col'] ) ? $j['col'] : 4;
						$cols_tablet = isset( $j['col_tablet'] ) ? $j['col_tablet'] : 3;
						$cols_mobile = isset( $j['col_mobile'] ) ? $j['col_mobile'] : 2;
						?>
						<div class="woostify-products-tab-content"
							data-columns="<?php echo esc_attr( $cols ); ?>"
							data-columns-tablet="<?php echo esc_attr( $cols_tablet ); ?>"
							data-columns-mobile="<?php echo esc_attr( $cols_mobile ); ?>"
							data-arrows="<?php echo esc_attr( $settings['control_arrows'] ); ?>"
							data-dots="<?php echo esc_attr( $settings['control_dots'] ); ?>"
							data-id="<?php echo esc_attr( $j['_id'] ); ?>"
							data-query='<?php echo wp_json_encode( $data[ $i ] ); ?>'></div>
						<?php
					}
				}
				?>
			</div>

			<?php if ( 'carousel' === $settings['layout'] && 'yes' === $settings['control_arrows'] && 'center' === $settings['arrows_position'] ) { ?>
				<div class="woostify-product-tab-carousel-arrows">
					<div class="woostify-product-tab-arrows-container active <?php echo esc_attr( $has_slider ? '' : 'hidden' ); ?>" data-id="<?php echo esc_attr( $list_first['_id'] ); ?>">
						<?php
						echo woostify_fetch_svg_icon( $arrow_left ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo woostify_fetch_svg_icon( $arrow_right ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</div>

					<?php
					if ( ! empty( $list ) ) {
						foreach ( $list as $v ) {
							?>
							<div class="woostify-product-tab-arrows-container" data-id="<?php echo esc_attr( $v['_id'] ); ?>">
							<?php
							echo woostify_fetch_svg_icon( $arrow_left ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo woostify_fetch_svg_icon( $arrow_right ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
							</div>
							<?php
						}
					}
					?>
				</div>
			<?php } ?>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Product_Tab() );
