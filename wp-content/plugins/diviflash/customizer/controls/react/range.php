<?php
/**
 * Range Control. Handles data passing from args to JS.
 *
 * @package DIFL\Customizer\Controls\React
 */

namespace DIFL\Customizer\Controls\React;

/**
 * Class Spacing
 *
 * @package DIFL\Customizer\Controls\React
 */
class Range extends \WP_Customize_Control {
	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'difl_range_control';
	/**
	 * Additional arguments passed to JS.
	 *
	 * @var array
	 */
	public $input_attrs = [];

	/**
	 * Send to JS.
	 */
	public function json() {
		$json                = parent::json();
		$json['input_attrs'] = $this->input_attrs;
		return $json;
	}

	/**
	 * This method overrides the default render
	 * so that nothing is rendered.
	 * Previously it would try to put an input element where the value was `esc_attr()`
	 * This would trigger notices in PHP
	 * It is not required to have a render as it is being handled by React.
	 */
	final public function render_content() {
		// this is rendered from React
	}
}
