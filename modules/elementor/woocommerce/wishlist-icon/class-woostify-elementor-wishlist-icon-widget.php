<?php
/**
 * Elementor Wishlist Icon Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class
 */
class Woostify_Elementor_Wishlist_Icon_Widget extends Widget_Base {
	/**
	 * Category
	 */
	public function get_categories() {
		return array( 'woostify-theme' );
	}

	/**
	 * Name
	 */
	public function get_name() {
		return 'woostify-wishlist-icon';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Wishlist Icon', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-heart-o';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'woocommerce', 'shop', 'store', 'wishlist' );
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
					'{{WRAPPER}} .woostify-wishlist-icon-widget' => 'text-align: {{VALUE}};',
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
					'{{WRAPPER}} .header-wishlist-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'value'   => 'far fa-heart',
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
					'{{WRAPPER}} .header-wishlist-icon' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .header-wishlist-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .custom-svg-icon'      => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .header-wishlist-icon .woostify-svg-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .wishlist-item-count' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'count_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wishlist-item-count' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .wishlist-item-count' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .wishlist-item-count' => 'font-size: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .wishlist-item-count' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'position',
			array(
				'label'     => __( 'Position', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'top-right',
				'options'   => array(
					'top-left'     => __( 'Top Left', 'woostify-pro' ),
					'top-right'    => __( 'Top Right', 'woostify-pro' ),
					'center'       => __( 'Center', 'woostify-pro' ),
					'bottom-left'  => __( 'Bottom Left', 'woostify-pro' ),
					'bottom-right' => __( 'Bottom Right', 'woostify-pro' ),
				),
				'separator' => 'before',
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
					'{{WRAPPER}} .wishlist-item-count' => 'left: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .wishlist-item-count' => 'right: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .wishlist-item-count' => 'top: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .wishlist-item-count' => 'bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'position' => array( 'bottom-left', 'bottom-right' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Controls
	 */
	protected function register_controls() { // phpcs:ignore
		$this->general();
		$this->cart_icon();
		$this->cart_items_count();
	}

	/**
	 * Render
	 */
	public function render() {
		if ( ! woostify_support_wishlist_plugin() ) {
			return;
		}

		$count    = woostify_get_wishlist_count();
		$settings = $this->get_settings_for_display();
		$icon     = ( 'theme' === $settings['type'] ) ? apply_filters( 'woostify_header_wishlist_icon', 'heart' ) : '';
		if ( 'icon' === $settings['type'] && ! empty( $settings['icon']['value'] ) ) {
			if ( is_array( $settings['icon']['value'] ) ) {
				$icon = 'custom-svg-icon';
			} else {
				$icon = $settings['icon']['value'];
			}
		}
		?>
		<div class="woostify-wishlist-icon-widget">
			<a href="<?php echo esc_url( woostify_wishlist_page_url() ); ?>" class="tools-icon header-wishlist-icon icon-<?php echo esc_attr( $settings['position'] ); ?>">
				<?php
				if ( 'icon' === $settings['type'] && ! empty( $settings['icon']['value'] ) ) {
					Icons_Manager::render_icon( $settings['icon'] );
				} elseif ( 'image' === $settings['type'] ) {
					$img_id  = 'image' === $settings['type'] ? $settings['image']['id'] : $settings['icon']['value']['id'];
					$img_url = 'image' === $settings['type'] ? $settings['image']['url'] : $settings['icon']['value']['url'];
					$img_alt = woostify_image_alt( $img_id, __( 'Wishlist Icon', 'woostify-pro' ) );
					?>
						<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>">
					<?php
				} elseif ( 'theme' === $settings['type'] ) {
					echo woostify_fetch_svg_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
				<span class="theme-item-count wishlist-item-count"><?php echo esc_html( $count ); ?></span>
			</a>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Wishlist_Icon_Widget() );
