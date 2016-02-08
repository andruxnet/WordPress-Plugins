<?php
/*
Plugin Name: Easy Plugin Template Creator
Version: 1.0
Description: Easily generate template code to start building a new plugin, then fill in with your own logic.
Author: andruxnet
Author URI: http://andrux.net
License: GPLv2
*/

/*  Copyright 2013  Andres Olvera  (email : aolvera@andrux.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * This class encapsulates the logic for our plugin
 *
 */
class Easy_Template_Creator
{
	private $plugin_path;
	private $plugin_url;

	public function __construct() {
		/* help variables */
		$this->plugin_path = dirname( __FILE__ );
		$this->plugin_url = WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) );

		/* action hooks */
		add_action( 'admin_menu', array( $this, 'admin_menu_tabs' ) );

		/* ajax action hooks */
		add_action( 'wp_ajax_ajax_hooks_list_rows', array( $this, 'ajax_hooks_list_rows' ) );
		add_action( 'wp_ajax_ajax_add_hook', array( $this, 'ajax_add_hook' ) );
	}

	/**
	 * add menu tabs to the admin side
	 */
	public function admin_menu_tabs() {
		add_menu_page( 'Easy Plugin Template Creator', 'Easy Plugin Template Creator', 'install_plugins', 'easy-template-creator', array( $this, 'plugin_information' ), $this->plugin_url . '/icon.png' );
		add_submenu_page( 'easy-template-creator', 'How To', 'How To', 'install_plugins', 'easy-template-creator', array( $this, 'plugin_information' ) );
		add_submenu_page( 'easy-template-creator', 'Header Info', 'Header Info', 'install_plugins', 'eptc-header', array( $this, 'generate_header_info' ) );
		add_submenu_page( 'easy-template-creator', 'Menu Tabs', 'Menu Tabs', 'install_plugins', 'eptc-menu-tabs', array( $this, 'generate_menu_tabs' ) );
		add_submenu_page( 'easy-template-creator', 'Hooks', 'Hooks', 'install_plugins', 'eptc-hooks', array( $this, 'generate_hooks' ) );

		wp_enqueue_script( 'easy-template-creator-js', $this->plugin_url . '/js/core.js' );
		wp_enqueue_style( 'easy-template-creator-css', $this->plugin_url . '/css/style.css' );
	}

	/**
	 * show some information about how to use this plugin
	 */
	public function plugin_information() {
		include_once 'plugin-information.php';
	}

	/**
	 * generate the new plugin's header information
	 */
	public function generate_header_info() {
		include_once 'generate-header-info.php';
	}

	/**
	 * generate the new plugin's action and filter hooks
	 */
	public function generate_hooks() {
		include_once 'generate-hooks.php';
	}

	/**
	 * generate the new plugin's menu tabs
	 */
	public function generate_menu_tabs() {
		include_once 'generate-menu-tabs.php';
	}

	/**
	 * return an array of action and filter hooks from the codex site
	 */
	public function hooks_list() {
		$hooks = array();
		$dom = new DOMDocument();

		foreach ( array( 'Action', 'Filter' ) as $hook_type ) {
			$url = 'http://codex.wordpress.org/Plugin_API/' . $hook_type . '_Reference';

			$ch = curl_init();

			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );

			$data = curl_exec( $ch );

			@$dom->loadHTML( $data );
			$xpath = new DOMXPath( $dom );
			$container_tag = ( $hook_type == 'Action' ) ? 'td' : 'dt';

			$matching_nodes = $xpath->query( "//" . $container_tag . "/a[contains(@href,'/" . $hook_type . "_Reference/')]" );

			foreach ( $matching_nodes as $node ) {
				if ( strstr( $node->nodeValue, ',' ) ) {
					$items = explode ( ',', $node->nodeValue );
					foreach ( $items as $item ) {
						$hooks[ $hook_type ][] = trim( $item );
					}
				}
				else
					$hooks[ $hook_type ][] = trim( $node->nodeValue );
			}

			$hooks[ $hook_type ] = array_unique( $hooks[ $hook_type ] );

			curl_close( $ch );
		}

		return $hooks;
	}

	/**
	 * load hooks through an ajax request as html table row
	 */
	public function ajax_hooks_list_rows() {
		/* grab POST data */
		$hook_type = $_POST['type'];

		$hooks = $new_plugin[ $hook_type . '-hooks'];
		foreach ( $hooks as $k => $hook_info ) {
			echo '<tr>';
			echo '<td>' . $hook_info['tag'] . '</td>';
			echo '<td>' . $hook_info['callback'] . '</td>';
			echo '<td>' . $hook_info['priority'] . '</td>';
			echo '<td>' . $hook_info['args'] . '</td>';
			echo '</tr>';
		}

		/* required to return properly to the ajax call */
		die();
	}

	/**
	 * save a new hook to the database and generate html to show in the admin page
	 */
	public function ajax_add_hook() {
		/* grab POST data */
		$tag = isset( $_POST['action-tag'] ) ? $_POST['action-tag'] : $_POST['filter-tag'];
		$callback = isset( $_POST['action-callback'] ) ? $_POST['action-callback'] : $_POST['filter-callback'];
		$priority = isset( $_POST['action-priority'] ) ? $_POST['action-priority'] : $_POST['filter-priority'];
		$accepted_args = isset( $_POST['action-accepted-args'] ) ? $_POST['action-accepted-args'] : $_POST['filter-accepted-args'];

		echo '<tr>';
		echo '<td>' . $tag . '</td>';
		echo '<td>' . $callback . '</td>';
		echo '<td>' . $priority . '</td>';
		echo '<td>' . $accepted_args . '</td>';
		echo '</tr>';

		/* required to return properly to the ajax call */
		die();
	}
}

/* initialize our object */
return new Easy_Template_Creator();

?>