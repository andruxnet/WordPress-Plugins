<?php
	/*
	Plugin Name: Ad Rem Search
	Plugin URI:
	Description: Plugin that performs a more relevant search and gives options to better manipulate search terms.
	Author: andruxnet
	Version: 1.0
	Author URI: http.//andrux.net
	*/

	 require_once "class-ad-rem.php";

	/* create an instance of our class */
	if ( ! $ad_rem ) {
		$ad_rem = new Ad_Rem();

		/* make sure we have our options created/deleted on activation/deactivation */
		register_activation_hook( __FILE__, array( $ad_rem, "on_activate" ) );
		register_deactivation_hook( __FILE__, array( $ad_rem, "on_deactivate" ) );
	}

?>