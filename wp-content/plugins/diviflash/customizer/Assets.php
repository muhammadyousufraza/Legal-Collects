<?php

namespace DIFL\Customizer;

class Assets {
	public function __construct() {
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue_customizer_style' ] );
		add_action('wp_enqueue_scripts', [$this,'enqueue_customizer_style'], 999);
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue_customizer_components' ] );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue_customizer_controls' ] );
	}

	public function enqueue_customizer_style() {
		$plugin_path  = plugin_dir_path( __DIR__ );
		$plugin_url   = plugin_dir_url( __DIR__ );
		$path         = 'admin/customizer/css/';
		$handle       = 'difl-customizer';

		wp_enqueue_style(
			$handle.'-loaders',
			$plugin_url . $path . 'loaders.min.css'
		);

		wp_enqueue_style(
			$handle,
			$plugin_url . $path . 'customizer-style.css',
			['difl-customizer-components']
		);
	}

	public function enqueue_customizer_controls() {
		$plugin_path  = plugin_dir_path( __DIR__ );
		$plugin_url   = plugin_dir_url( __DIR__ );
		$path         = 'admin/customizer/';
		$handle       = 'difl-customizer-controls';
		$dependencies = include_once $plugin_path . $path . 'controls.asset.php';


		wp_enqueue_script(
			$handle,
			$plugin_url . $path . 'controls.js',
			$dependencies['dependencies'],
			$dependencies['version'], true );
	}

	public function enqueue_customizer_components() {
		$plugin_path  = plugin_dir_path( __DIR__ );
		$plugin_url   = plugin_dir_url( __DIR__ );
		$path         = 'admin/customizer/';
		$handle       = 'difl-customizer-components';
		$dependencies = include_once $plugin_path . $path . 'components.asset.php';
		wp_enqueue_style(
			$handle,
			$plugin_url . $path . 'style-components.css',
			[ 'wp-components' ],
			$dependencies['version'] );
	}
}

new Assets();