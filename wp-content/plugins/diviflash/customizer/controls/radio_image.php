<?php
/**
 * Radio image controls.
 *
 * Note, the `$choices` array is slightly different than normal and should be in the form of
 * `array(
 *  $value => array( 'url' => $image_url, 'label' => $text_label ),
 *  $value => array( 'url' => $image_url, 'label' => $text_label ),
 * )`
 *
 * @package    DIFL\Customizer\Controls
 */

namespace DIFL\Customizer\Controls;

/**
 * Class Radio_Image
 */
class Radio_Image extends \WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'radio-image';

	/**
	 * Flag to tell that this control is a tab
	 *
	 * @since 1.1.72
	 * @var   bool
	 */
	public $is_tab = false;

	/**
	 * Flag to tell that this control is a sub-tab in a tab
	 *
	 * @since 1.1.72
	 * @var   bool
	 */
	public $is_subtab = false;

	/**
	 * Controls in tabs.
	 *
	 * @since 1.1.72
	 * @var   array
	 */
	public $controls;

	/**
	 * Control data (tabs, names, icons, images)
	 *
	 * @since 1.1.72
	 * @var   array
	 */
	public $choices;

	/**
	 * Radio_Image constructor.
	 *
	 * @param \WP_Customize_Manager $manager Customizer manager object.
	 * @param string                $id      Control id.
	 * @param array                 $args    Control arguments.
	 */
	public function __construct( \WP_Customize_Manager $manager, $id, array $args = array() ) {
		parent::__construct( $manager, $id, apply_filters( $id . '_filter_args', $args ) );

		if ( ! empty( $args['is_tab'] ) && $args['is_tab'] === true ) {
			$this->is_tab = $args['is_tab'];
			if ( ! empty( $args['is_subtab'] ) && $args['is_subtab'] === true ) {
				$this->is_subtab = $args['is_subtab'];
			}

			if ( ! empty( $this->choices ) ) {
				foreach ( $this->choices as $value => $args ) {
					$this->controls[ $value ] = $args['controls'];
				}
			}
		}

	}

	/**
	 * Loads the jQuery UI Button script and custom scripts/styles.
	 *
	 * @return void
	 * @since  1.0.0
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_script( 'jquery-ui-button' );
	}

	/**
	 * Add custom JSON parameters to use in the JS template.
	 *
	 * @return array
	 * @since  1.0.0
	 * @access public
	 */
	public function json() {
		$json = parent::json();

		$json['is_tab']    = $this->is_tab;
		$json['is_subtab'] = $this->is_subtab;
		if ( $json['is_tab'] === true ) {
			$json['controls'] = $this->controls;
		}
		// We need to make sure we have the correct image URL.
		$json['choices'] = $this->choices;
		$json['width']   = 100;
		if ( ! empty( $this->choices ) ) {
			$json['width'] = number_format( 100 / count( $this->choices ), 2 );
		}
		$json['id']    = $this->id;
		$json['link']  = $this->get_link();
		$json['value'] = $this->value();

		return $json;
	}

	/**
	 * Underscore JS template to handle the control's output.
	 *
	 * @return void
	 * @since  1.0.0
	 * @access public
	 */
	public function content_template() {
		?>
		<#
		if ( ! data.choices ) {
		return;
		}
		#>
		<# if( !data.is_tab) {#>
		<# if ( data.label ) { #>
		<span class="customize-control-title">{{ data.label }}</span>
		<# } #>

		<# if ( data.description ) { #>
		<span class="description customize-control-description">{{{ data.description }}}</span> <?php // phpcs:ignore WordPressVIPMinimum.Security.Mustache.OutputNotation ?>
		<# } #>
		<#}#>


		<div class="buttonset <# if( data.is_tab) {#>customizer-tab <#}#> <# if( data.is_subtab) {#>customizer-subtab <#}#>">
			<# for ( key in data.choices ) { #>

			<input <# if( data.is_tab) {#>data-controls="{{data.controls[key]}}"<#}#> type="radio" value="{{ key }}"
			name="_customize-{{ data.type }}-{{ data.id }}" id="{{ data.id }}-{{ key }}" <# if ( key
			=== data.value && ( !data.is_tab || data.is_subtab) ) { #> checked="checked" <# } #>
			{{{ data.link }}} /> <?php // phpcs:ignore WordPressVIPMinimum.Security.Mustache.OutputNotation ?>
			<label for="{{ data.id }}-{{ key }}" style="width:{{data.width}}%">
				<# if( !data.is_tab) {#>
				<span class="screen-reader-text">{{ data.choices[ key ]['label'] }}</span>
				<img src="{{ data.choices[ key ]['url'] }}" alt="{{ data.choices[ key ]['label'] }}"/>
				<# } else { #>
				<# if( data.choices[ key ]['icon'] ){ #>
				<i class="fa fa-{{ data.choices[ key ]['icon'] }}"></i>
				<# }
				if( data.choices[ key ]['url'] ){
				#>
				<img src="{{ data.choices[ key ]['url'] }}" alt="{{ data.choices[ key ]['label'] }}"/>
				<# }
				if(data.choices[ key ]['label']){ #>
				<span class="tab-label">{{ data.choices[ key ]['label'] }}</span>
				<# } #>
				<# } #>
			</label>
			<# } #>

		</div>
		<?php
	}
}
