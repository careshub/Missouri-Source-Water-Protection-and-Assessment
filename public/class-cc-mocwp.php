<?php
/**
 * Community Commons MoCWP
 *
 * @package   Community_Commons_MoCWP
 * @author    dcavins
 * @license   GPL-2.0+
 * @link      http://www.communitycommons.org
 * @copyright 2017 Community Commons
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `admin/class-mocwp-admin.php`
 *
 *
 * @package Community_Commons_MoCWP
 * @author  dcavins
 */
class CC_MoCWP {

	/**
	 *
	 * The current version of the plugin.
	 *
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $version = '1.0.0';

	/**
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'cc-mocwp';

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	public function __construct() {

		$this->version = mocwp_get_plugin_version();

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return   string Plugin slug.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles_scripts() {
		if ( mocwp_is_mocwp_group() ) {
			// Styles
			// wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), array(), $this->version );

			// IE specific
			// global $wp_styles;
			// wp_enqueue_style( $this->plugin_slug . '-ie-plugin-styles', plugins_url( 'css/public-ie.css', __FILE__ ), array(), $this->version );
			// $wp_styles->add_data( $this->plugin_slug . '-ie-plugin-styles', 'conditional', 'lte IE 9' );

			// Scripts
			wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.min.js', __FILE__ ), array( 'chosen-js-script', 'jquery' ), $this->version, true );

			// Enqueue Chosen.js library.
			wp_enqueue_style( 'chosen-js-styles', 'https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css', array(), '1.4.2' );
			wp_enqueue_script( 'chosen-js-script', 'https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js', array( 'jquery' ), '1.4.2', true );
		}
	}

}
$cc_mocwp = new CC_MoCWP();
