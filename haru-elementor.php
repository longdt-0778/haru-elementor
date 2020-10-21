<?php
/**
 * Plugin Name: Haru Elementor
 * Description: Haru Elementor extension from HaruTheme.
 * Plugin URI:  http://harutheme.com
 * Version:     1.0.0
 * Author:      HaruTheme
 * Author URI:  http://harutheme.com
 * Text Domain: haru-elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Haru Elementor Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Haru_Elementor' ) ) {
	final class Haru_Elementor {

		/**
		 * Plugin Version
		 *
		 * @since 1.0.0
		 *
		 * @var string The plugin version.
		 */
		const VERSION = '1.0.0';

		/**
		 * Minimum Elementor Version
		 *
		 * @since 1.0.0
		 *
		 * @var string Minimum Elementor version required to run the plugin.
		 */
		const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

		/**
		 * Minimum PHP Version
		 *
		 * @since 1.0.0
		 *
		 * @var string Minimum PHP version required to run the plugin.
		 */
		const MINIMUM_PHP_VERSION = '7.0';

		/**
		 * Instance
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @static
		 *
		 * @var Haru_Elementor The single instance of the class.
		 */
		private static $_instance = null;

		/**
		 * Instance
		 *
		 * Ensures only one instance of the class is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 * @static
		 *
		 * @return Haru_Elementor An instance of the class.
		 */
		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;

		}

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function __construct() {

			add_action( 'init', [ $this, 'i18n' ] );
			add_action( 'plugins_loaded', [ $this, 'init' ] );
			add_action( 'admin_init', [ $this, 'admin_assets' ] );

		}

		/**
		 * Load Textdomain
		 *
		 * Load plugin localization files.
		 *
		 * Fired by `init` action hook.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function i18n() {

			load_plugin_textdomain( 'haru-elementor' );

		}

		/**
		 * Initialize the plugin
		 *
		 * Load the plugin only after Elementor (and other plugins) are loaded.
		 * Checks for basic plugin requirements, if one check fail don't continue,
		 * if all check have passed load the files required to run the plugin.
		 *
		 * Fired by `plugins_loaded` action hook.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function init() {

			// Check if Elementor installed and activated
			if ( ! did_action( 'elementor/loaded' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
				return;
			}

			// Check for required Elementor version
			if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
				return;
			}

			// Check for required PHP version
			if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
				return;
			}

			// Define some constant
			$this->define();

			// Include plugin files
			$this->includes();

			// Add Plugin actions
			// Add widgets categories
			add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_widget_categories' ] );

			// Register widgets
			add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );

			// Register controls
			add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );

			// Register Widget Styles
			add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );

			// Register Widget Scripts
			add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

			// Render Header & Footer
			add_filter( 'haru_render_post_builder', [ $this, 'render_post_builder'] , 10, 2 );
		}

		/**
		 * Admin notice
		 *
		 * Warning when the site doesn't have Elementor installed or activated.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function admin_notice_missing_main_plugin() {

			if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor */
				esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'haru-elementor' ),
				'<strong>' . esc_html__( 'Haru Elementor', 'haru-elementor' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'haru-elementor' ) . '</strong>'
			);

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

		}

		/**
		 * Admin notice
		 *
		 * Warning when the site doesn't have a minimum required Elementor version.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function admin_notice_minimum_elementor_version() {

			if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'haru-elementor' ),
				'<strong>' . esc_html__( 'Haru Elementor', 'haru-elementor' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'haru-elementor' ) . '</strong>',
				 self::MINIMUM_ELEMENTOR_VERSION
			);

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

		}

		/**
		 * Admin notice
		 *
		 * Warning when the site doesn't have a minimum required PHP version.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function admin_notice_minimum_php_version() {

			if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

			$message = sprintf(
				/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'haru-elementor' ),
				'<strong>' . esc_html__( 'Haru Elementor', 'haru-elementor' ) . '</strong>',
				'<strong>' . esc_html__( 'PHP', 'haru-elementor' ) . '</strong>',
				 self::MINIMUM_PHP_VERSION
			);

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

		}

		/**
		 * Define Constants
		 *
		 * Define plugin constants
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function define() {

			if ( ! defined( 'HARU_ELEMENTOR_CORE_DIR' ) ) {
	            define( 'HARU_ELEMENTOR_CORE_DIR', plugin_dir_path(__FILE__) );
	        }

	        if ( ! defined( 'HARU_ELEMENTOR_CORE_URL' ) ) {
	            define( 'HARU_ELEMENTOR_CORE_URL', plugin_dir_url( __FILE__ ) );
	        }

	        if ( ! defined( 'HARU_ELEMENTOR_CORE_FILE' ) ) {
	            define( 'HARU_ELEMENTOR_CORE_FILE', __FILE__ );
	        }

	        if( ! defined( 'HARU_ELEMENTOR_CORE_NAME' ) ) {
                define( 'HARU_ELEMENTOR_CORE_NAME', 'haru-elementor' );
            }

		}

		/**
		 * Include Files
		 *
		 * Include plugin files
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function includes() {

			require_once( 'includes/posttypes/_init.php' );

		}

		/**
		 * Add Widgets Categories
		 *
		 * Use for Haru Widgets Categories
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		function add_elementor_widget_categories( $elements_manager ) {

			$elements_manager->add_category(
				'haru-elements',
				[
					'title' => esc_html__( 'Haru Elements', 'haru-elementor' ),
					'icon' => 'fa fa-plug',
				]
			);

			$elements_manager->add_category(
				'haru-header-elements',
				[
					'title' => esc_html__( 'Haru Header Elements', 'haru-elementor' ),
					'icon' => 'fa fa-plug',
				]
			);

			$elements_manager->add_category(
				'haru-footer-elements',
				[
					'title' => esc_html__( 'Haru Footer Elements', 'haru-elementor' ),
					'icon' => 'fa fa-plug',
				]
			);

		}

		/**
		 * Init Widgets
		 *
		 * Include widgets files and register them
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function init_widgets() {

			// Include Widget files
			require_once( HARU_ELEMENTOR_CORE_DIR . '/includes/widgets/logo-widget.php' );
			require_once( HARU_ELEMENTOR_CORE_DIR . '/includes/widgets/footer-link-widget.php' );

			// Register widget
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Haru_Elementor_Logo_Widget() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Haru_Elementor_Footer_Link_Widget() );

		}

		/**
		 * Init Controls
		 *
		 * Include controls files and register them
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function init_controls() {

			// Include Control files
			require_once( HARU_ELEMENTOR_CORE_DIR . '/includes/controls/test-control.php' );

			// Register control
			// \Elementor\Plugin::$instance->controls_manager->register_control( 'control-type-', new \Test_Control() );

		}

		/**
		 * Widget Styles
		 *
		 * Include style files and register them
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function widget_styles() {

			wp_register_style( 'widget-1', plugins_url( 'assets/css/widget-1.css', __FILE__ ) );
			wp_register_style( 'widget-2', plugins_url( 'assets/css/widget-2.css', __FILE__ ) );

		}

		/**
		 * Widget Scripts
		 *
		 * Include script files and register them
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function widget_scripts() {

			wp_register_script( 'some-library', plugins_url( 'assets/js/libs/some-library.js', __FILE__ ) );
			wp_register_script( 'widget-1', plugins_url( 'assets/js/widget-1.js', __FILE__ ) );
			wp_register_script( 'widget-2', plugins_url( 'assets/js/widget-2.js', __FILE__ ), [ 'jquery', 'some-library' ] );

		}

		/**
		 * Admin Assets
		 *
		 * Include assets files for admin
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function admin_assets() {
			wp_enqueue_style( 'haru-admin', plugins_url( HARU_ELEMENTOR_CORE_NAME . 'assets/css/admin.css' ), array(), false, 'all' );
			wp_enqueue_script( 'haru-admin', plugins_url( HARU_ELEMENTOR_CORE_NAME . 'assets/js/admin.js' ), array( 'jquery' ), false, false );
		}

		/**
		 * Render Header & Footer
		 *
		 * Render Header & Footer HTML & CSS
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 */
		public function render_page_content( $post_id ) {
            if ( class_exists( 'Elementor\Core\Files\CSS\Post' ) ) {
                $css_file = new Elementor\Core\Files\CSS\Post( $post_id );
                $css_file->enqueue();
            }

            return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id );
        }

        public function render_post_builder( $html, $post_id ) {
            if ( ! empty( $post_id ) ) {
                return $this->render_page_content( $post_id );
            }

            return $html;
        }
	}
}

Haru_Elementor::instance();
