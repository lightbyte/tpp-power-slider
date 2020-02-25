<?php

class TpppsManager
{

	private $paths = array();

	private $plugin_name = 'tpp-power-slider/tpp-power-slider.php';

	private static $instance;

	private function __construct()
	{
		$dir = TPPPS_PLUGIN_DIR;

		$this->setPaths(array(
			'APP_ROOT' => $dir,
			'WP_ROOT' => preg_replace( '/$\//', '', ABSPATH ),
			'APP_DIR' => basename( plugin_basename( $dir ) ),
			'APP_URI' => plugins_url('tpp-power-slider'),
			'ASSETS_DIR' => $dir . '/assets',
			'HELPERS_DIR' => $dir . '/inc/helpers',
			'SHORTCODES_DIR' => $dir . '/inc/shortcodes'
		));

		add_action( 'plugins_loaded', array(
			$this,
			'pluginsLoaded',
		), 9 );
		add_action( 'init', array(
			$this,
			'init',
		), 11 );
		$this->setPluginName( $this->path( 'APP_DIR', 'tpp-power-slider.php' ) );
		register_activation_hook( TPPPS_PLUGIN_FILE, array(
			$this,
			'activationHook',
		) );
	}

	public static function getInstance() {
		if ( ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * prevent the instance from being cloned (which would create a second instance of it)
	 */
	private function __clone() {
	}

	/**
	 * prevent from being unserialized (which would create a second instance of it)
	 */
	private function __wakeup() {
	}

	public function setPaths($paths)
	{
		$this->paths = $paths;
	}

	public function path($name, $file = '')
	{
		$path = $this->paths[ $name ] . ( strlen( $file ) > 0 ? '/' . preg_replace( '/^\//', '', $file ) : '' );
		return $path;
	}

	public function pluginName() {
		return $this->plugin_name;
	}

	public function setPluginName( $name ) {
		$this->plugin_name = $name;
	}

	public function pluginsLoaded()
	{
		// Setup locale
		load_plugin_textdomain( 'tpp-power-slider', false, $this->path( 'APP_DIR', 'locale' ) );
	}

	public function init()
	{
		require_once $this->path('HELPERS_DIR', 'helpers_factory.php');
		require_once $this->path('SHORTCODES_DIR', 'slider_shortcode.php');
		$slider = new SliderShortcode();
		$slider->init();

		require_once $this->path('SHORTCODES_DIR', 'loop_posts.php');
		$postsSlider = new PostsSliderShortcode();
		$postsSlider->init();
	}

	public function activationHook( $networkWide = false ) {
		// do_action( 'tppps_activation_hook', $networkWide );
		// if ( ! is_plugin_active( 'js_composer/js_composer.php' ) ) {
		// 	wp_die( 'Please activate Visual Composer, and try again' );
		// }
	}
}
