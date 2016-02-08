<?php
/*
Plugin Name: Simple jQuery Slider
Version: 1.0
Description: A simple jQuery slider
Author: andruxnet
Author URI: http://andrux.net/
License: GPLv2
*/

/* let's define some constants */
define( 'SIMPLE_SLIDER_OPTIONS', 'simple-slider-options' );

/**
 * This class encapsulates the logic for the plugin
 *
 */
class Simple_JQuery_Slider
{
	private $plugin_path;
	private $plugin_url;

	public function __construct() {
		/* help variables */
		$this->plugin_path = dirname( __FILE__ );
		$this->plugin_url = plugins_url() . '/' . basename( $this->plugin_path );

		/* action hooks */
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_action( 'wp_head', array( $this, 'get_slider_settings' ) );

		/* filter hooks */

		/* ajax hooks */
		add_action( 'wp_ajax_get_thumbnail', array( $this, 'get_thumbnail' ) );
		add_action( 'wp_ajax_nopriv_get_thumbnail', array( $this, 'get_thumbnail' ) );

		/* shortcodes */
		add_shortcode( 'simple-jquery-slider', array( $this, 'slider_shortcode' ) );
	}

	/**
	 * get slider settings for the front
	 */
	public function get_slider_settings() {
		$settings = get_option( SIMPLE_SLIDER_OPTIONS, array() );

		if ( ! empty( $settings ) ) {
?>
		<script type="text/javascript">
			<?php if ( isset( $settings['general']['full-width'] ) && $settings['general']['full-width'] == true ): ?>
			var width = jQuery( window ).width();
			<?php else: ?>
			var width = <?php echo $settings['general']['slides-width']; ?>;
			<?php endif; ?>
			<?php if ( isset( $settings['general']['auto-height'] ) && $settings['general']['auto-height'] == true ): ?>
			var height = 'auto';
			<?php else: ?>
			var height = <?php echo $settings['general']['slides-height']; ?>;
			<?php endif; ?>
			var animationSpeed = <?php echo $settings['general']['animation-speed']; ?>;
			var pause = <?php echo $settings['general']['animation-interval']; ?>;
			var currentSlide = 2;
			var interval;
		</script>
<?php
		}
	}

	/**
	 * slider shortcode
	 */
	public function slider_shortcode() {
		$settings = get_option( SIMPLE_SLIDER_OPTIONS, array() );
?>
		<link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
		<div id="slider">
			<div id="slides-container">
				<div id="slides-nav">
					<div class="slider-nav-arrow nav-left">
						<div class="nav-icon">
							<span class="dashicons dashicons-arrow-left-alt2"></span>
						</div>
					</div>
					<div class="slider-nav-arrow nav-right">
						<div class="nav-icon">
							<span class="dashicons dashicons-arrow-right-alt2"></span>
						</div>
					</div>
					<div class="clearfix"></div>
					<div id="slider-dots">
						<?php for ( $i = 0; $i < count( $settings['slides']['image'] ); $i++ ): ?>
						<span id="slide-<?php echo $i + 1; ?>" class="slider-dot"></span>
						<?php endfor; ?>
					</div>
				</div>
				<ul class="slides">
					<?php $slides_html = array(); ?>
					<?php for ( $i = 0; $i < count( $settings['slides']['image'] ); $i++ ): ?>
					<?php $slide = $settings['slides']; ?>
					<?php $attachment = wp_get_attachment_image_src( $slide['image'][ $i ], 'full' ); ?>
					<?php ob_start(); ?>
					<li class="slide">
						<div class="slide-image" style="background-image: url(<?php echo $attachment[0]; ?>);"></div>
						<div class="slide-content">
							<h2 class="slide-caption"><?php echo $slide['caption'][ $i ]; ?></h2>
							<div class="slide-link">
								<a href="<?php echo $slide['link'][ $i ]; ?>">
									<span class="link-text"><?php echo $slide['link-text'][ $i ]; ?></span>
								</a>
							</div>
						</div>
					</li>
					<?php $slides_html[] = ob_get_clean(); ?>
					<?php endfor; ?>

					<?php
						array_push( $slides_html, $slides_html[0] );
						array_unshift( $slides_html, $slides_html[ count( $slides_html ) - 2 ] );
					?>

					<?php for ( $i = 0; $i < count( $slides_html ); $i++ ): ?>
					<?php echo $slides_html[ $i ]; ?>
					<?php endfor; ?>
				</ul>
			</div>
		</div>
<?php
	}

	/**
	 * load admin scripts
	 */
	public function admin_scripts() {
		wp_enqueue_media();
	}

	/**
	 * hook our settings page in the admin menu and load the needed scripts
	 */
	public function admin_menu() {
		$settings_page = add_management_page( 'Simple jQuery Slider', 'Simple jQuery Slider', 'manage_options', 'simple-jquery-slider', array( $this, 'settings_page' ) );

		add_action( 'load-' . $settings_page, array( $this, 'load_scripts' ) );
	}

	/**
	 * show our settings page
	 */
	public function settings_page() {
		include_once 'settings.php';
	}

	/**
	 * load Javascript and CSS
	 */
	public function load_scripts() {
		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style( 'simple-jquery-slider', $this->plugin_url . '/style.css' );
		wp_enqueue_script( 'simple-jquery-slider', $this->plugin_url . '/scripts.js', array( 'jquery' ) );
	}

	/**
	 * get the thumbnail for a post
	 */
	public function get_thumbnail() {
		$size = isset( $_POST['size'] ) ? $_POST['size'] : 'thumbnail';
		echo wp_get_attachment_image( $_POST['post-id'], $size );

		die();
	}
}

/* initialize our object */
return new Simple_JQuery_Slider();
