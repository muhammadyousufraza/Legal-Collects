<?php

namespace DIFL\Customizer\Extensions;

use DIFL\Customizer\Base_Customizer;
use DIFL\Customizer\Types\Control;
use DIFL\Customizer\Types\Section;

class Back_To_Top extends Base_Customizer {
	const SECTION = 'difl_back_to_top_';

	const UNIT = [ 'px', 'em', 'rem' ];

	public function init() {
		parent::init();
		add_filter( 'difl_reconcile_customizer_preview', [ $this, 'handle_divi_btt_option' ] );
		add_action( 'wp_head', [ $this, 'live_refresh_scripts' ] );
	}

	/**
	 * Handle divi btt enable if disable by chance
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function handle_divi_btt_option( $data ) {
		if ( ! array_key_exists( 'difl_back_to_top_enable_difl_btt', $data ) || 'on' === et_get_option( 'divi_back_to_top' ) ) {
			return $data;
		}

		if ( array_key_exists( 'difl_back_to_top_enable_difl_btt', $data ) ) {
			et_update_option( 'divi_back_to_top', 'on' );
		}

		return $data;

	}

	public function live_refresh_scripts() {
		if ( ! is_customize_preview() ) {
			return;
		}
		?>
        <script type="text/javascript">
			jQuery(document).ready(function () {

				const target = jQuery('#scroll-to-top');
				// Choose Side
				wp.customize('difl_back_to_top_side', function (value) {
					value.bind(function (newval) {
						if (newval === 'right') {
							target.removeClass('scroll-to-top-left').addClass('scroll-to-top-right');
						}
						if (newval === 'left') {
							target.removeClass('scroll-to-top-right').addClass('scroll-to-top-left');
						}
					});
				});
				// Label
				wp.customize('difl_back_to_top_label', function (value) {
					value.bind(function (newval) {
						var hasLabel = jQuery('.scroll-to-top-label').length > 0;
						if (hasLabel) {
							jQuery('.scroll-to-top-label').text(newval);
						} else {
							jQuery('.scroll-to-top').append('<p class="scroll-to-top-label">' + newval + '</p>');
						}
					});
				});
			});
        </script>
		<?php
	}

	public function add_controls() {
		$this->add_sections();
		$this->add_content_controls();
		$this->add_style_controls();
	}

	private function add_sections() {

		$this->add_section(
			new Section(
				self::SECTION,
				[
					'priority' => 80,
					'title'    => esc_html__( 'Back To Top Button', 'divi_flash' ),
					'panel'    => 'difl_advanced_genaral',
				]
			)
		);

	}

	private function add_content_controls() {
		$this->add_control(
			new Control(
				self::SECTION . 'enable_difl_btt',
				[
					'sanitize_callback' => 'difl_sanitize_checkbox',
					'default'           => false,
					'transport'         => $this->selective_refresh,
				],
				[
					'label'    => esc_html__( 'Enable Back To Top', 'divi_flash' ),
					'section'  => self::SECTION,
					'type'     => 'difl_toggle_control',
					'priority' => 8,
				],
				'DIFL\Customizer\Controls\Checkbox'
			)
		);

		$this->add_control(
			new Control(
				self::SECTION . 'general',
				[
					'sanitize_callback' => 'sanitize_text_field',
				],
				[
					'label'            => esc_html__( 'Content', 'divi_flash' ),
					'section'          => self::SECTION,
					'priority'         => 9,
					'class'            => 'scroll-to-top-general',
					'accordion'        => true,
					'expanded'         => true,
					'controls_to_wrap' => 17,
					'active_callback'  => [ $this, 'is_extension_enabled' ],
				],
				'DIFL\Customizer\Controls\Heading'
			)
		);

		/*
		 * Label
		 */
		$this->add_control(
			new Control(
				self::SECTION . 'label',
				[
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => $this->selective_refresh,
				],
				[
					'priority'        => 10,
					'section'         => self::SECTION,
					'label'           => esc_html__( 'Label', 'divi_flash' ),
					'type'            => 'text',
					'active_callback' => [ $this, 'is_extension_enabled' ],
				]
			)
		);

		$this->add_control(
			new Control(
				self::SECTION . 'label_font_size',
				[
					'sanitize_callback' => 'difl_sanitize_range_value',
					'default'           => '{ "mobile": "16", "tablet": "16", "desktop": "16" }',
					'transport'         => $this->selective_refresh,
				],
				[
					'label'                 => esc_html__( 'Font Size', 'divi_flash' ),
					'section'               => self::SECTION,
					'media_query'           => true,
					'step'                  => 1,
					'input_attr'            => [
						'mobile'  => [
							'min'     => 10,
							'max'     => 100,
							'default' => 16,
						],
						'tablet'  => [
							'min'     => 10,
							'max'     => 100,
							'default' => 16,
						],
						'desktop' => [
							'min'     => 10,
							'max'     => 100,
							'default' => 16,
						],
					],
					'input_attrs'           => [
						'step'       => 1,
						'min'        => 1,
						'max'        => 100,
						'defaultVal' => [
							'mobile'  => 16,
							'tablet'  => 16,
							'desktop' => 16,
							'suffix'  => [
								'mobile'  => 'px',
								'tablet'  => 'px',
								'desktop' => 'px',
							],
						],
						'units'      => self::UNIT,
					],
					'priority'              => 10,
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'     => [
							'vars'       => '--size',
							'selector'   => '.scroll-to-top-icon, .scroll-to-top-image',
							'responsive' => true,
							'suffix'     => 'px',
						],
						'responsive' => true,
						'template'   => 'body .scroll-to-top.icon .scroll-to-top-icon, body .scroll-to-top.image .scroll-to-top-image {
							width: {{value}}px;
							height: {{value}}px;
						}',
					],
					'active_callback'       => [ $this, 'label_has_value' ],
				],
				class_exists( 'DIFL\Customizer\Controls\React\Responsive_Range', false ) ? 'DIFL\Customizer\Controls\React\Responsive_Range' : 'DIFL\Customizer\Controls\Range'
			)
		);

		/**
		 * Label Color
		 */
		$color_controls = [
			self::SECTION . 'label_font_color'       => [
				'priority'              => 10,
				'label'                 => esc_html__( 'Label Color', 'divi_flash' ),
				'default'               => 'var(--difl--icon--color)',
				'live_refresh_css_prop' => [
					'cssVar'   => [
						'vars'     => '--color',
						'selector' => '.scroll-to-top',
					],
					'template' => '
					body .scroll-to-top {
						color: {{value}};
					}',
				],
			],
			self::SECTION . 'label_font_hover_color' => [
				'priority'              => 10,
				'label'                 => esc_html__( 'Label Hover Color', 'divi_flash' ),
				'default'               => 'var(--difl--brand--color)',
				'live_refresh_css_prop' => [
					'cssVar'   => [
						'vars'     => '--hovercolor',
						'selector' => '.scroll-to-top:hover',
					],
					'template' => '
					body .scroll-to-top:hover {
						color: {{value}};
					}',
				],
			],
		];

		/**
		 * Color controls for label
		 */
		foreach ( $color_controls as $control_id => $control_properties ) {
			$this->add_control(
				new Control(
					$control_id,
					[
						'sanitize_callback' => 'difl_sanitize_colors',
						'default'           => array_key_exists( 'default', $control_properties ) ? $control_properties['default'] : '',
						'transport'         => $this->selective_refresh,
					],
					[
						'label'                 => $control_properties['label'],
						'section'               => self::SECTION,
						'priority'              => $control_properties['priority'],
						'input_attrs'           => isset( $control_properties['input_attrs'] ) ? $control_properties['input_attrs'] : [],
						'live_refresh_selector' => true,
						'live_refresh_css_prop' => $control_properties['live_refresh_css_prop'],
						'active_callback'       => [ $this, 'label_has_value' ],
					],
					'\DIFL\Customizer\Controls\React\Color'
				)
			);
		}


		/**
		 * Scroll to top Media
		 */
		$this->add_control(
			new Control(
				self::SECTION . 'type',
				[
					'default'           => 'icon',
					'sanitize_callback' => [ $this, 'sanitize_scroll_to_top_type' ],
				],
				[
					'label'           => esc_html__( 'Media', 'divi_flash' ),
					'section'         => self::SECTION,
					'priority'        => 11,
					'type'            => 'select',
					'choices'         => [
						'icon'  => esc_html__( 'Icon', 'divi_flash' ),
						'image' => esc_html__( 'Image', 'divi_flash' ),
						'none'  => esc_html__( 'None', 'divi_flash' ),
					],
					'active_callback' => [ $this, 'is_extension_enabled' ],
				]
			)
		);

		$this->add_control(
			new Control(
				self::SECTION . 'icon',
				[
					'default'   => false,
					'transport' => $this->selective_refresh,
				],
				[
					'label'           => esc_html__( 'Select Icon', 'divi_flash' ),
					'section'         => self::SECTION,
					'type'            => 'icon_picker',
					'priority'        => 12,
					'active_callback' => [ $this, 'is_icon_type_control' ],
				],
				'DIFL\Customizer\Controls\Divi_Icon'
			)
		);

		/**
		 * Image button
		 */
		$this->add_control(
			new Control(
				self::SECTION . 'image',
				[
				],
				[
					'label'           => esc_html__( 'Image Uploader', 'divi_flash' ),
					'section'         => self::SECTION,
					'priority'        => 13,
					'active_callback' => [ $this, 'is_image_type_control' ],
					'flex_height'     => true,
					'flex_width'      => true,
				],
				'\WP_Customize_Upload_Control'
			)
		);

		/**
		 * Icon size
		 */
		$this->add_control(
			new Control(
				self::SECTION . 'icon_size',
				[
					'sanitize_callback' => 'difl_sanitize_range_value',
					'default'           => '{ "mobile": "16", "tablet": "16", "desktop": "16" }',
					'transport'         => $this->selective_refresh,
				],
				[
					'label'                 => esc_html__( 'Icon Size', 'divi_flash' ),
					'section'               => self::SECTION,
					'media_query'           => true,
					'step'                  => 1,
					'input_attr'            => [
						'mobile'  => [
							'min'     => 10,
							'max'     => 100,
							'default' => 16,
						],
						'tablet'  => [
							'min'     => 10,
							'max'     => 100,
							'default' => 16,
						],
						'desktop' => [
							'min'     => 10,
							'max'     => 100,
							'default' => 16,
						],
					],
					'input_attrs'           => [
						'step'       => 1,
						'min'        => 1,
						'max'        => 100,
						'defaultVal' => [
							'mobile'  => 16,
							'tablet'  => 16,
							'desktop' => 16,
							'suffix'  => [
								'mobile'  => 'px',
								'tablet'  => 'px',
								'desktop' => 'px',
							],
						],
						'units'      => self::UNIT,
					],
					'priority'              => 14,
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'     => [
							'vars'       => '--size',
							'selector'   => '.scroll-to-top-icon, .scroll-to-top-image',
							'responsive' => true,
							'suffix'     => 'px',
						],
						'responsive' => true,
						'template'   => 'body .scroll-to-top.icon .scroll-to-top-icon, body .scroll-to-top.image .scroll-to-top-image {
							width: {{value}}px;
							height: {{value}}px;
						}',
					],
					'active_callback'       => [ $this, 'is_icon_type_control' ],
				],
				class_exists( 'DIFL\Customizer\Controls\React\Responsive_Range', false ) ? 'DIFL\Customizer\Controls\React\Responsive_Range' : 'DIFL\Customizer\Controls\Range'
			)
		);

		/**
		 * Image size
		 */
		$this->add_control(
			new Control(
				self::SECTION . 'image_size',
				[
					'sanitize_callback' => 'difl_sanitize_range_value',
					'default'           => '{ "mobile": "16", "tablet": "16", "desktop": "16" }',
					'transport'         => $this->selective_refresh,
				],
				[
					'label'                 => esc_html__( 'Image Size', 'divi_flash' ),
					'section'               => self::SECTION,
					'media_query'           => true,
					'step'                  => 1,
					'input_attr'            => [
						'mobile'  => [
							'min'     => 10,
							'max'     => 100,
							'default' => 16,
						],
						'tablet'  => [
							'min'     => 10,
							'max'     => 100,
							'default' => 16,
						],
						'desktop' => [
							'min'     => 10,
							'max'     => 100,
							'default' => 16,
						],
					],
					'input_attrs'           => [
						'step'       => 1,
						'min'        => 1,
						'max'        => 100,
						'defaultVal' => [
							'mobile'  => 16,
							'tablet'  => 16,
							'desktop' => 16,
							'suffix'  => [
								'mobile'  => 'px',
								'tablet'  => 'px',
								'desktop' => 'px',
							],
						],
						'units'      => self::UNIT,
					],
					'priority'              => 15,
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'     => [
							'vars'       => '--size',
							'selector'   => '.scroll-to-top-icon, .scroll-to-top-image',
							'responsive' => true,
							'suffix'     => 'px',
						],
						'responsive' => true,
						'template'   => 'body .scroll-to-top.icon .scroll-to-top-icon, body .scroll-to-top.image .scroll-to-top-image {
							width: {{value}}px;
							height: {{value}}px;
						}',
					],
					'active_callback'       => [ $this, 'is_image_type_control' ],
				],
				class_exists( 'DIFL\Customizer\Controls\React\Responsive_Range', false ) ? 'DIFL\Customizer\Controls\React\Responsive_Range' : 'DIFL\Customizer\Controls\Range'
			)
		);

		/**
		 * Icon Color
		 */
		$color_controls = [
			self::SECTION . 'icon_color'       => [
				'priority'              => 16,
				'label'                 => esc_html__( 'Icon Color', 'divi_flash' ),
				'default'               => 'var(--difl--icon--color)',
				'live_refresh_css_prop' => [
					'cssVar'   => [
						'vars'     => '--color',
						'selector' => '.scroll-to-top',
					],
					'template' => '
					body .scroll-to-top {
						color: {{value}};
					}',
				],
			],
			self::SECTION . 'icon_hover_color' => [
				'priority'              => 17,
				'label'                 => esc_html__( 'Icon Hover Color', 'divi_flash' ),
				'default'               => 'var(--difl--brand--color)',
				'live_refresh_css_prop' => [
					'cssVar'   => [
						'vars'     => '--hovercolor',
						'selector' => '.scroll-to-top:hover',
					],
					'template' => '
					body .scroll-to-top:hover {
						color: {{value}};
					}',
				],
			],
		];

		/**
		 * Color controls
		 */
		foreach ( $color_controls as $control_id => $control_properties ) {
			$this->add_control(
				new Control(
					$control_id,
					[
						'sanitize_callback' => 'difl_sanitize_colors',
						'default'           => array_key_exists( 'default', $control_properties ) ? $control_properties['default'] : '',
						'transport'         => $this->selective_refresh,
					],
					[
						'label'                 => $control_properties['label'],
						'section'               => self::SECTION,
						'priority'              => $control_properties['priority'],
						'input_attrs'           => isset( $control_properties['input_attrs'] ) ? $control_properties['input_attrs'] : [],
						'live_refresh_selector' => true,
						'live_refresh_css_prop' => $control_properties['live_refresh_css_prop'],
						'active_callback'       => [ $this, 'is_icon_type_control' ],
					],
					'\DIFL\Customizer\Controls\React\Color'
				)
			);
		}

		/**
		 * Position
		 */
		$this->add_control(
			new Control(
				self::SECTION . 'position',
				[
					'default'           => 'right',
					'sanitize_callback' => [ $this, 'sanitize_scroll_to_top_side' ],
					'transport'         => $this->selective_refresh,
				],
				[
					'label'           => esc_html__( 'Position', 'divi_flash' ),
					'section'         => self::SECTION,
					'priority'        => 18,
					'type'            => 'select',
					'choices'         => [
						'left'  => esc_html__( 'Left', 'divi_flash' ),
						'right' => esc_html__( 'Right', 'divi_flash' ),
					],
					'active_callback' => [ $this, 'is_extension_enabled' ],
				]
			)
		);

		/**
		 * Alignment
		 */
		$this->add_control(
			new Control(
				self::SECTION . 'alignment',
				[
					'default'           => 'horizontally',
					'sanitize_callback' => [ $this, 'sanitize_scroll_to_top_alignemnt' ],
					'transport'         => 'refresh',
				],
				[
					'label'           => esc_html__( 'Alignment', 'divi_flash' ),
					'section'         => self::SECTION,
					'priority'        => 19,
					'type'            => 'select',
					'choices'         => [
						'horizontally' => esc_html__( 'Horizontally', 'divi_flash' ),
						'vertically'   => esc_html__( 'Vertically', 'divi_flash' ),
					],
					'active_callback' => [ $this, 'is_extension_enabled' ],
				]
			)
		);


		/**
		 * Side Offset
		 */
		$this->add_control(
			new Control(
				self::SECTION . 'offset',
				[
					'sanitize_callback' => 'absint',
					'default'           => 10,
				],
				[
					'label'           => esc_html__( 'Side Offset (PX)', 'divi_flash' ),
					'description'     => esc_html__( 'Gap from page side', 'divi_flash' ),
					'section'         => self::SECTION,
					'step'            => 1,
					'input_attr'      => [
						'min'     => 0,
						'max'     => 1000,
						'default' => 10,
					],
					'input_attrs'     => [
						'min'        => 0,
						'max'        => 1000,
						'defaultVal' => 10,
					],
					'priority'        => 20,
					'active_callback' => [ $this, 'is_extension_enabled' ],
				],
				class_exists( 'DIFL\Customizer\Controls\React\Range' ) ? 'DIFL\Customizer\Controls\React\Range' : 'DIFL\Customizer\Controls\Range'
			)
		);

		/**
		 * Bottom Offset
		 */
		$this->add_control(
			new Control(
				self::SECTION . 'bottom_offset',
				[
					'sanitize_callback' => 'absint',
					'default'           => 30,
				],
				[
					'label'           => esc_html__( 'Bottom Offset (PX)', 'divi_flash' ),
					'description'     => esc_html__( 'Gap from page bottom', 'divi_flash' ),
					'section'         => self::SECTION,
					'step'            => 1,
					'input_attr'      => [
						'min'     => 0,
						'max'     => 400,
						'default' => 30,
					],
					'input_attrs'     => [
						'min'        => 0,
						'max'        => 400,
						'defaultVal' => 30,
					],
					'priority'        => 21,
					'active_callback' => [ $this, 'is_extension_enabled' ],
				],
				class_exists( 'DIFL\Customizer\Controls\React\Range' ) ? 'DIFL\Customizer\Controls\React\Range' : 'DIFL\Customizer\Controls\Range'
			)
		);

		$this->add_control(
			new Control(
				self::SECTION . 'hover_animation',
				[
					'default'   => 'zoomin',
					'transport' => $this->selective_refresh,
				],
				[
					'label'           => esc_html__( 'Hover Animation', 'divi_flash' ),
					'section'         => self::SECTION,
					'priority'        => 22,
					'type'            => 'select',
					'choices'         => [
						'none'     => esc_html__( 'None', 'divi_flash' ),
						'moveup'   => esc_html__( 'Move Up', 'divi_flash' ),
						'movedown' => esc_html__( 'Move Down', 'divi_flash' ),
						'zoomin'   => esc_html__( 'Zoom In', 'divi_flash' ),
						'zoomout'  => esc_html__( 'Zoom Out', 'divi_flash' ),
					],
					'active_callback' => [ $this, 'is_extension_enabled' ],
				]
			)
		);

		/**
		 * Hide on mobile
		 */
		$this->add_control(
			new Control(
				self::SECTION . 'on_mobile',
				[
					'sanitize_callback' => 'difl_sanitize_checkbox',
					'default'           => false,
					'transport'         => $this->selective_refresh,
				],
				[
					'label'           => esc_html__( 'Hide on small devices', 'divi_flash' ),
					'section'         => self::SECTION,
					'type'            => 'difl_toggle_control',
					'priority'        => 23,
					'active_callback' => [ $this, 'is_extension_enabled' ],
				],
				'DIFL\Customizer\Controls\Checkbox'
			)
		);

	}

	private function add_style_controls() {

		$this->add_control(
			new Control(
				self::SECTION . 'style',
				[
					'sanitize_callback' => 'sanitize_text_field',
				],
				[
					'label'            => esc_html__( 'Design', 'divi_flash' ),
					'section'          => self::SECTION,
					'priority'         => 26,
					'class'            => 'scroll-to-top-accordion',
					'accordion'        => true,
					'expanded'         => false,
					'controls_to_wrap' => 7,
					'active_callback'  => [ $this, 'is_extension_enabled' ],
				],
				'DIFL\Customizer\Controls\Heading'
			)
		);

		$this->add_control(
			new Control(
				self::SECTION . 'font_family',
				[
					'transport'         => $this->selective_refresh,
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => 'Arial, Helvetica, sans-serif',
				],
				[
					'settings'              => [
						'default'  => 'difl_body_font_family',
						'variants' => 'difl_body_font_family_variants',
					],
					'label'                 => esc_html__( 'Body', 'divi_flash' ),
					'section'               => self::SECTION,
					'priority'              => 28,
					'type'                  => 'difl_font_family_control',
					'live_refresh_selector' => apply_filters( 'difl_body_font_family_selectors', 'body, .site-title' ),
					'live_refresh_css_prop' => [
						'cssVar' => [
							'vars'     => '--bodyfontfamily',
							'selector' => 'body',
							'fallback' => 'Arial, Helvetica, sans-serif',
							'suffix'   => ', var(--difl-fallback-ff)',
						],
					],
				],
				'\DIFL\Customizer\Controls\React\Font_Family'
			)
		);

		$color_controls = [
			self::SECTION . 'background_color'       => [
				'priority'              => 32,
				'label'                 => esc_html__( 'Background Color', 'divi_flash' ),
				'default'               => 'var(--difl--icon--color)',
				'input_attrs'           => [
					'allow_gradient' => true,
				],
				'live_refresh_css_prop' => [
					'cssVar'   => [
						'vars'     => '--bgcolor',
						'selector' => '.scroll-to-top',
					],
					'template' => '
					body .scroll-to-top {
						background: {{value}};
					}',
					'fallback' => '#ffffff',
				],
			],
			self::SECTION . 'background_hover_color' => [
				'priority'              => 34,
				'label'                 => esc_html__( 'Background Hover Color', 'divi_flash' ),
				'default'               => 'var(--difl--icon--color)',
				'input_attrs'           => [
					'allow_gradient' => true,
				],
				'live_refresh_css_prop' => [
					'cssVar'   => [
						'vars'     => '--hoverbgcolor',
						'selector' => '.scroll-to-top:hover',
					],
					'template' => '
					body .scroll-to-top:hover {
						background: {{value}};
					}',
					'fallback' => '#ffffff',
				],
			],
		];

		/**
		 * Color controls
		 */
		foreach ( $color_controls as $control_id => $control_properties ) {
			$this->add_control(
				new Control(
					$control_id,
					[
						'sanitize_callback' => 'difl_sanitize_colors',
						'default'           => array_key_exists( 'default', $control_properties ) ? $control_properties['default'] : '',
						'transport'         => $this->selective_refresh,
					],
					[
						'label'                 => $control_properties['label'],
						'section'               => self::SECTION,
						'priority'              => $control_properties['priority'],
						'input_attrs'           => isset( $control_properties['input_attrs'] ) ? $control_properties['input_attrs'] : [],
						'live_refresh_selector' => true,
						'live_refresh_css_prop' => $control_properties['live_refresh_css_prop'],
						'active_callback'       => [ $this, 'is_extension_enabled' ],
					],
					'\DIFL\Customizer\Controls\React\Color'
				)
			);
		}
		/**
		 * Space Between
		 */
		$this->add_control(
			new Control(
				self::SECTION . 'space_between',
				[
					'sanitize_callback' => 'absint',
					'default'           => 5,
				],
				[
					'label'           => esc_html__( 'Space Between (PX)', 'divi_flash' ),
					'description'     => esc_html__( 'Gap between icon and label', 'divi_flash' ),
					'section'         => self::SECTION,
					'step'            => 1,
					'input_attr'      => [
						'min'     => 0,
						'max'     => 200,
						'default' => 5,
					],
					'input_attrs'     => [
						'min'        => 0,
						'max'        => 200,
						'defaultVal' => 5,
					],
					'priority'        => 36,
					'active_callback' => [ $this, 'is_extension_enabled' ],
				],
				class_exists( 'DIFL\Customizer\Controls\React\Range' ) ? 'DIFL\Customizer\Controls\React\Range' : 'DIFL\Customizer\Controls\Range'
			)
		);


		$default_space_values = [
			'desktop'      => [
				'top'    => 8,
				'right'  => 10,
				'bottom' => 8,
				'left'   => 10,
			],
			'tablet'       => [
				'top'    => 8,
				'right'  => 10,
				'bottom' => 8,
				'left'   => 10,
			],
			'mobile'       => [
				'top'    => 8,
				'right'  => 10,
				'bottom' => 8,
				'left'   => 10,
			],
			'desktop-unit' => 'px',
			'tablet-unit'  => 'px',
			'mobile-unit'  => 'px',
		];

		$this->add_control(
			new Control(
				self::SECTION . 'margin',
				[
					'default'   => $default_space_values,
					'transport' => $this->selective_refresh,
				],
				[
					'label'                 => __( 'Margin', 'divi_flash' ),
					'section'               => self::SECTION,
					'input_attrs'           => [
						'units' => self::UNIT,
					],
					'default'               => $default_space_values,
					'priority'              => 38,
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'      => [
							'vars'       => '--margin',
							'selector'   => '#scroll-to-top',
							'responsive' => true,
						],
						'responsive'  => true,
						'directional' => true,
						'template'    =>
							'#scroll-to-top {
							padding-top: {{value.top}};
							padding-right: {{value.right}};
							padding-bottom: {{value.bottom}};
							padding-left: {{value.left}};
						}',
					],
					'active_callback'       => [ $this, 'is_extension_enabled' ],
				],
				'\DIFL\Customizer\Controls\React\Spacing'
			)
		);
		$this->add_control(
			new Control(
				self::SECTION . 'padding',
				[
					'default'   => $default_space_values,
					'transport' => $this->selective_refresh,
				],
				[
					'label'                 => __( 'Padding', 'divi_flash' ),
					'section'               => self::SECTION,
					'input_attrs'           => [
						'units' => self::UNIT,
					],
					'default'               => $default_space_values,
					'priority'              => 40,
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'      => [
							'vars'       => '--padding',
							'selector'   => '#scroll-to-top',
							'responsive' => true,
						],
						'responsive'  => true,
						'directional' => true,
						'template'    =>
							'#scroll-to-top {
							padding-top: {{value.top}};
							padding-right: {{value.right}};
							padding-bottom: {{value.bottom}};
							padding-left: {{value.left}};
						}',
					],
					'active_callback'       => [ $this, 'is_extension_enabled' ],
				],
				'\DIFL\Customizer\Controls\React\Spacing'
			)
		);
		/**
		 * Button border radius
		 */
		$this->add_control(
			new Control(
				self::SECTION . 'border_radius',
				[
					'default'   => $default_space_values,
					'transport' => $this->selective_refresh,
				],
				[
					'label'                 => __( 'Border Radius', 'divi_flash' ),
					'section'               => self::SECTION,
					'input_attrs'           => [
						'units' => self::UNIT,
					],
					'default'               => $default_space_values,
					'priority'              => 42,
					'live_refresh_selector' => true,
					'live_refresh_css_prop' => [
						'cssVar'      => [
							'vars'       => '--margin',
							'selector'   => '#scroll-to-top',
							'responsive' => true,
						],
						'responsive'  => true,
						'directional' => true,
						'template'    =>
							'#scroll-to-top {
							padding-top: {{value.top}};
							padding-right: {{value.right}};
							padding-bottom: {{value.bottom}};
							padding-left: {{value.left}};
						}',
					],
					'active_callback'       => [ $this, 'is_extension_enabled' ],
				],
				'\DIFL\Customizer\Controls\React\Spacing'
			)
		);
	}

	public function is_image_type_control() {
		if ( ! $this->is_extension_enabled() ) {
			return false;
		}

		return get_theme_mod( self::SECTION . 'type', 'none' ) === 'image';
	}

	public function is_icon_type_control() {
		if ( ! $this->is_extension_enabled() ) {
			return false;
		}

		return get_theme_mod( self::SECTION . 'type', 'none' ) === 'icon';
	}

	public function sanitize_scroll_to_top_type( $value ) {
		$allowed_values = [ 'icon', 'image', 'none' ];
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'icon';
		}

		return esc_html( $value );
	}

	public function sanitize_scroll_to_top_side( $value ) {
		$allowed_values = [ 'left', 'right' ];
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'right';
		}

		return esc_html( $value );
	}

	public function sanitize_scroll_to_top_alignemnt( $value ) {
		$allowed_values = [ 'horizontally', 'vertically' ];
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'horizontally';
		}

		return esc_html( $value );
	}

	public static function is_divi_btt_enabled() {
		if ( ! function_exists( 'et_get_option' ) ) {
			return false;
		}

		return et_get_option( 'divi_back_to_top', false ) === 'on';
	}

	public static function is_extension_enabled() {
		if ( ! self::is_divi_btt_enabled() ) {
			return false;
		}

		return get_theme_mod( self::SECTION . 'enable_difl_btt', false );
	}

	public static function label_has_value() {
		if ( ! self::is_divi_btt_enabled() || ! self::is_extension_enabled() ) {
			return false;
		}

		return get_theme_mod( self::SECTION . 'label', '' ) !== '';
	}
}
