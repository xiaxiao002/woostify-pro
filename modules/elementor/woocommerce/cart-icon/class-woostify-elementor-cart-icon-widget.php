<?php
/**
 * Elementor Cart Icon Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for woostify elementor Cart icon widget.
 */
class Woostify_Elementor_Cart_Icon_Widget extends Widget_Base {
	/**
	 * Category
	 */
	public function get_categories() {
		return array( 'woostify-theme', 'woocommerce-elements-single' );
	}

	/**
	 * Name
	 */
	public function get_name() {
		return 'woostify-cart-icon';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Cart Icon', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-cart';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'woocommerce', 'shop', 'store', 'cart' );
	}

	/**
	 * General
	 */
	public function general() {
		$this->start_controls_section(
			'general',
			array(
				'label' => __( 'General', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Alignment.
		$this->add_responsive_control(
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
				'selectors' => array(
					'{{WRAPPER}} .woostify-cart-icon-widget' => 'text-align: {{VALUE}};',
				),
			)
		);

		// Padding.
		$this->add_responsive_control(
			'padding',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Padding', 'woostify-pro' ),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .shopping-bag-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * General Settings
	 */
	public function general_settings() {
		$this->start_controls_section(
			'general_settings',
			array(
				'label' => __( 'General', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		// Show Subtotal.
		$this->add_control(
			'show_subtotal',
			array(
				'label'        => __( 'Show Subtotal', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'woostify-pro' ),
				'label_off'    => __( 'Hide', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		// Position.
		$this->add_control(
			'subtotal_position',
			array(
				'label'     => __( 'Subtotal Position', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'before',
				'options'   => array(
					'before' => __( 'Before', 'woostify-pro' ),
					'after'  => __( 'After', 'woostify-pro' ),
				),
				'condition' => array(
					'show_subtotal' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Cart icon
	 */
	public function cart_icon() {
		$this->start_controls_section(
			'cart',
			array(
				'label' => __( 'Icon', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'type',
			array(
				'label'   => __( 'Icon', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'theme',
				'options' => array(
					'theme' => __( 'Use Theme Icon', 'woostify-pro' ),
					'icon'  => __( 'Use Custom Icon', 'woostify-pro' ),
					'image' => __( 'Use Image', 'woostify-pro' ),
				),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'     => __( 'Choose Icon', 'woostify-pro' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-shopping-cart',
					'library' => 'solid',
				),
				'condition' => array(
					'type' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Icon Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .shopping-bag-button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .custom-svg-icon svg path' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'type' => array( 'icon', 'theme' ),
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'icon_size',
			array(
				'label'      => __( 'Icon Size', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 10,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .shopping-bag-button'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .custom-svg-icon'       => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .woostify-svg-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'type' => array( 'icon', 'theme' ),
				),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'     => __( 'Choose Image', 'woostify-pro' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'type' => 'image',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Cart items count
	 */
	public function cart_items_count() {
		$this->start_controls_section(
			'count',
			array(
				'label' => __( 'Items Count', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'count_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .shop-cart-count' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'count_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .shop-cart-count' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'count_dimension',
			array(
				'label'      => __( 'Dimension', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 10,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .shop-cart-count' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'count_size',
			array(
				'label'      => __( 'Size', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 10,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .shop-cart-count' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'count_border_radius',
			array(
				'label'      => __( 'Border Radius', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 10,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .shop-cart-count' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'position',
			array(
				'label'     => __( 'Position', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'top-right',
				'separator' => 'before',
				'options'   => array(
					'top-left'     => __( 'Top Left', 'woostify-pro' ),
					'top-right'    => __( 'Top Right', 'woostify-pro' ),
					'center'       => __( 'Center', 'woostify-pro' ),
					'bottom-left'  => __( 'Bottom Left', 'woostify-pro' ),
					'bottom-right' => __( 'Bottom Right', 'woostify-pro' ),
				),
			)
		);

		$this->add_control(
			'position_x_left',
			array(
				'label'      => __( 'X Axis', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .shop-cart-count' => 'left: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'position' => array( 'top-left', 'center', 'bottom-left' ),
				),
			)
		);

		$this->add_control(
			'position_x_right',
			array(
				'label'      => __( 'X Axis', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .shop-cart-count' => 'right: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'position' => array( 'top-right', 'bottom-right' ),
				),
			)
		);

		$this->add_control(
			'position_y_top',
			array(
				'label'      => __( 'Y Axis', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .shop-cart-count' => 'top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'position' => array( 'top-left', 'top-right', 'center' ),
				),
			)
		);

		$this->add_control(
			'position_y_bottom',
			array(
				'label'      => __( 'Y Axis', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .shop-cart-count' => 'bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'position' => array( 'bottom-left', 'bottom-right' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Subtotal
	 */
	public function subtotal() {
		$this->start_controls_section(
			'subtotal',
			array(
				'label'     => __( 'Subtotal', 'woostify-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_subtotal' => 'yes',
				),
			)
		);

		$this->add_control(
			'subtotal_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-header-total-price' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'subtotal_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-header-total-price' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'content_typography',
				'label'     => __( 'Typography', 'woostify-pro' ),
				'selector'  => '{{WRAPPER}} .woostify-header-total-price',
				'separator' => 'after',
			)
		);

		// Padding.
		$this->add_responsive_control(
			'subtotal_padding',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Padding', 'woostify-pro' ),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-header-total-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin.
		$this->add_responsive_control(
			'subtotal_margin',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Margin', 'woostify-pro' ),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-header-total-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Controls
	 */
	protected function register_controls() { // phpcs:ignore
		$this->general_settings();
		$this->general();
		$this->cart_icon();
		$this->cart_items_count();
		$this->subtotal();
	}

	/**
	 * Render
	 */
	public function render() {
		if ( null === WC()->cart ) {
			return;
		}

		$options = woostify_options( false );

		$count                    = WC()->cart->get_cart_contents_count();
		$settings                 = $this->get_settings_for_display();
		$shopping_cart_icon_class = ( 'theme' === $settings['type'] ) ? apply_filters( 'woostify_header_shop_bag_icon_class', 'cart-icon-rotate' ) : '';
		$icon                     = ( 'theme' === $settings['type'] ) ? apply_filters( 'woostify_header_shop_bag_icon', 'shopping-cart' ) : '';
		if ( 'icon' === $settings['type'] && ! empty( $settings['icon']['value'] ) ) {
			if ( is_array( $settings['icon']['value'] ) ) {
				$icon = 'custom-svg-icon';
			} else {
				$icon = $settings['icon']['value'];
			}
		}

		$cart_count_class = array();

		if ( ! empty( $options['header_shop_hide_zero_value_cart_count'] ) ) {
			$cart_count_class[] = 'hide-zero-val';
		}
		if ( $count < 1 ) {
			$cart_count_class[] = 'hide';
		}
		?>
		<div class="woostify-cart-icon-widget <?php echo $settings['show_subtotal'] ? 'woostify-d-flex woostify-align-center' : ''; ?>">
			<?php if ( $settings['show_subtotal'] && 'before' === $settings['subtotal_position'] ) { ?>
				<div class="woostify-header-total-price">
					<?php echo WC()->cart->get_total(); //phpcs:ignore ?>
				</div>
			<?php } ?>
			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="shopping-bag-button icon-<?php echo esc_attr( $settings['position'] ); ?> <?php echo esc_attr( $shopping_cart_icon_class ); ?>">
				<?php
				if ( 'icon' === $settings['type'] && ! empty( $settings['icon']['value'] ) ) {
					Icons_Manager::render_icon( $settings['icon'] );
				} elseif ( 'image' === $settings['type'] ) {
					$img_id  = 'image' === $settings['type'] ? $settings['image']['id'] : $settings['icon']['value']['id'];
					$img_url = 'image' === $settings['type'] ? $settings['image']['url'] : $settings['icon']['value']['url'];
					$img_alt = woostify_image_alt( $img_id, __( 'Cart Icon', 'woostify-pro' ) );
					?>
						<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>">
					<?php
				} elseif ( 'theme' === $settings['type'] ) {
					echo woostify_fetch_svg_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
				<span class="shop-cart-count <?php echo esc_attr( implode( ' ', $cart_count_class ) ); ?>"><?php echo esc_html( $count ); ?></span>
			</a>
			<?php if ( $settings['show_subtotal'] && 'after' === $settings['subtotal_position'] ) { ?>
				<div class="woostify-header-total-price">
					<?php echo WC()->cart->get_total(); //phpcs:ignore ?>
				</div>
			<?php } ?>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Cart_Icon_Widget() );
