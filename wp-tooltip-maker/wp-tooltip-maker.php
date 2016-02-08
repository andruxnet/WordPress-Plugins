<?php
/*
Plugin Name: WP Tooltip Maker
Description: Based on <a href="http://wordpress.org/extend/plugins/wp-image-tooltip/">WP Image Tooltip</a>. Create an image tooltip using a shortcut in the edit screen.
Version: 1.0
Author: andruxnet
Author URI: http://andrux.net/
License: GPLv2
*/

class WP_Tooltip_Maker
{
	private $plugin_url;

	public function __construct() {
		$this->plugin_url = WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) );

		add_action( 'init', array( $this, 'add_cluetip' ) );
		add_action( 'wp_ajax_select_image', array( $this, 'select_image' ) );

		add_filter( 'mce_external_plugins', array( $this, 'mce_cluetip' ) );
		add_filter( 'mce_buttons', array( $this, 'mce_buttons' ) );
	}

	/**
	 * add cluetip scripts and styles
	 */
	public function add_cluetip() {
		wp_enqueue_script( 'cluetip-js', $this->plugin_url . '/js/jquery.cluetip.js', array( 'jquery' ) );
		wp_enqueue_script( 'core-js', $this->plugin_url . '/js/core.js', array( 'jquery' ) );

		wp_enqueue_style( 'cluetip-css', $this->plugin_url . '/css/jquery.cluetip.css' );
	}

	/**
	 * MCE external plugins filter to add a button
	 *
	 * @param array $plugins
	 * @return array
	 */
	public function mce_cluetip( $plugins ) {
		$plugins['cluetip'] = plugins_url( 'tinymce.js', __FILE__ );

		return $plugins;
	}

	/**
	 * MCE buttons filter to show tooltip button
	 *
	 * @param array $buttons
	 * @return array
	 */
	public function mce_buttons( $buttons ) {
		array_push( $buttons, 'separator', 'cluetip' );

		return $buttons;
	}

	public function select_image() {
?>
			<title>Select an image</title>
			<style>
				#tooltip-attributes input, #tooltip-attributes label { font-size: 12px; }
				#submit-image-url { margin-top: 10px; padding: 3px; width: 100px; font-size: 14px; }
			</style>
			<table id="tooltip-attributes">
				<tr>
					<td><label for="title">Title</label></td>
					<td><input type="text" id="title" size="40" /></td>
				</tr>
				<tr>
					<td><label for="image-url">URL</label></td>
					<td><input type="text" id="image-url" size="40" /></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="button" id="submit-image-url" value="Add Tooltip" /></td>
				</tr>
			</table>
			<script type="text/javascript" src="/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
			<script>
				document.getElementById( 'title' ).focus();
				document.getElementById( 'submit-image-url' ).onclick = function(){
					var title = document.getElementById( 'title' ).value;
					var imageUrl = document.getElementById( 'image-url' ).value;
					var sel = window.parent.tinyMCE.activeEditor.selection.getContent();

					tinyMCEPopup.execCommand( 'mceInsertContent', 0, '<a class="tooltip" href="' + imageUrl + '" title="' + title + '">' + sel + '</a>' );
					tinyMCEPopup.close();
				};
			</script>
<?php
		die();
	}
}

return new WP_Tooltip_Maker;