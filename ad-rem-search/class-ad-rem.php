<?php
	/**
	 * Encapsulates the logic of the plugin
	 *
	 * @author Andres Olvera <aolvera@andrux.net>
	 */
	class Ad_Rem
	{
		private $plugin_path;

		/**
		 * Constructor will only load menu tab(s)
		 */
		public function __construct() {
			$this->plugin_path = plugin_dir_url( __FILE__ );

			add_action( "admin_menu", array( $this, "load_option_tabs" ) );
			add_action( "init", array( $this, "load_js" ) );
		}

		/**
		 * Load tabs to show admin screen
		 *
		 * @author Andres Olvera <aolvera@andrux.net>
		 *
		 * @return void
		 */
		public function load_option_tabs() {
			add_options_page("Ad Rem Settings", "Ad Rem Settings", "manage_options",
								"ad-rem-settings", array( $this, "load_admin_screen" ) );
		}

		/**
		 * Load the admin screen to setup defaults and options for the searches
		 *
		 * @author Andres Olvera <aolvera@andrux.net>
		 *
		 * @return void
		 */
		public function load_admin_screen() {
			require_once "ad-rem-settings.php";
		}

		/**
		 * Load Javascript required to intercept WP search and do our own
		 *
		 * @author Andres Olvera <aolvera@andrux.net>
		 *
		 * @return void
		 */
		public function load_js() {
			/* first enqueue our css file before we forget about it :P */
			wp_enqueue_style( "style", $this->plugin_path . "css/style.css" );

			wp_enqueue_script( "hijack-search", $this->plugin_path . "js/hijack-search.js", array( "jquery" ) );
			wp_localize_script( "hijack-search", "ajax", array( "ajaxurl" => admin_url( "admin-ajax.php" ) ) );

			add_action( "wp_ajax_nopriv_hijack_search", array( $this, "hijack_search" ) );
			add_action( "wp_ajax_hijack_search", array( $this, "hijack_search" ) );
		}

		/**
		 * Show a customized results page after doing a WP search
		 *
		 * @author Andres Olvera <aolvera@andrux.net>
		 *
		 * @return void
		 */
		public function hijack_search() {
			require_once "ad-rem-results.php";

			die();
		}

		/**
		 * Create our own options inside wp_options table and fulltext indexes
		 *
		 * @author Andres Olvera <aolvera@andrux.net>
		 *
		 * @return void
		 */
		public function on_activate() {
			$general_settings = array(
				"results_per_page" => "10",
				"category_weight"  => "10",
				"title_weight"     => "10",
				"content_weight"   => "1",
			);

			$results_settings = array(
				"include_static"      => "on",
				"include_attachments" => "off",
				"show_relevance"      => "on",
				"show_category"       => "on",
				"show_author"         => "on",
			);

			$advanced_settings = array(
				"exclude_ids"  => array(),
				"exclude_cats" => array(),
			);

			add_option( "adrem_settings_general", $general_settings );
			add_option( "adrem_settings_results", $results_settings );
			add_option( "adrem_settings_advanced", $advanced_settings );

			/* create new FULLTEXT indexes */
			global $wpdb;
			$wpdb->query( "ALTER TABLE $wpdb->posts ENGINE = MYISAM" );
			$wpdb->query( "ALTER TABLE $wpdb->posts ADD FULLTEXT adrem_search (post_title, post_content)" );
			$wpdb->query( "ALTER TABLE $wpdb->posts ADD FULLTEXT adrem_title (post_title)" );
			$wpdb->query( "ALTER TABLE $wpdb->posts ADD FULLTEXT adrem_content (post_content)" );
		}

		/**
		 * Delete our options from wp_options table
		 *
		 * @author Andres Olvera <aolvera@andrux.net>
		 *
		 * @return void
		 */
		public function on_deactivate() {
			delete_option( "adrem_settings_general" );
			delete_option( "adrem_settings_results" );
			delete_option( "adrem_settings_advanced" );
		}

		/**
		 * Retrieve an option item from the wp_options table
		 *
		 * @author Andres Olvera <aolvera@andrux.net>
		 *
		 * @param string $option_item Item name inside the options array
		 *
		 * @return string The value associated with the item name
		 */
		public static function get_option( $option_item ) {
			$settings_array = array(
				"adrem_settings_general",
				"adrem_settings_results",
				"adrem_settings_advanced",
			);

			foreach ( $settings_array as $settings_item ) {
				$options = get_option( $settings_item );

				if ( array_key_exists( $option_item, $options ) ) {
					return $options[ $option_item ];
				}
			}
		}

		/**
		 * Set an option item on the wp_options table
		 *
		 * @author Andres Olvera <aolvera@andrux.net>
		 *
		 * @param string $option_item Item name inside the options array
		 * @param string $new_value New value to update the options item with
		 *
		 * @return void
		 */
		public static function set_option( $option_item, $new_value ) {
			$settings_array = array(
				"adrem_settings_general",
				"adrem_settings_results",
				"adrem_settings_advanced",
			);

			foreach ( $settings_array as $settings_item ) {
				$options = get_option( $settings_item );

				if ( array_key_exists( $option_item, $options ) ) {
					$options[ $option_item ] = $new_value;
					update_option( $settings_item, $options );
				}
			}
		}

	}

?>
