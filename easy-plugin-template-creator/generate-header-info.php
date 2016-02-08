<div class="wrap">
	<?php
		if ( wp_verify_nonce( $_POST['eptc-header-nonce'], 'eptc-header' ) ) {
			$new_plugin = get_option( 'eptc-plugins' );
			$new_plugin[ 'current-plugin' ] = $_POST['plugin-name'];
			$new_plugin[ $_POST['plugin-name'] ] = array(
				'plugin-description'	=> $_POST['plugin-description'],
				'version'				=> $_POST['version'],
				'plugin-uri'			=> $_POST['plugin-uri'],
				'author'				=> $_POST['author'],
				'author-uri'			=> $_POST['author-uri'],
			);

			update_option( 'eptc-plugins', $new_plugin );

			echo '<div class="updated"><p>Saved new plugin data.</p></div>';
		}

		$plugins = get_option( 'eptc-plugins' );
		$current_plugin = $plugins['current-plugin'];
		$new_plugin = $plugins[ $current_plugin ];

	?>
    <?php screen_icon( 'options-general' ); ?><h2>Header Information</h2>
	<form method="post" action="">
		<?php wp_nonce_field( 'eptc-header', 'eptc-header-nonce' ); ?>
		<table class="form-table">
			<tr class="required">
				<th scope="row"><label for="plugin-name">Plugin Name</label></th>
				<td><input type="text" name="plugin-name" id="plugin-name" class="regular-text" value="<?php echo $current_plugin; ?>" /></td>
			</tr>
			<tr class="required">
				<th scope="row"><label for="plugin-description">Description</label></th>
				<td><textarea name="plugin-description" id="plugin-description" rows="7" cols="40" class="code"><?php echo $new_plugin['plugin-description']; ?></textarea>
			</tr>
			<tr>
				<th scope="row"><label for="version">Version</label></th>
				<td><input type="text" name="version" id="version" class="regular-text" value="<?php echo $new_plugin['version']; ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="plugin-uri">Plugin URI</label></th>
				<td><input type="text" name="plugin-uri" id="plugin-uri" class="regular-text" value="<?php echo $new_plugin['plugin-uri']; ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="author">Author</label></th>
				<td><input type="text" name="author" id="author" class="regular-text" value="<?php echo $new_plugin['author']; ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="author-uri">Author URI</label></th>
				<td><input type="text" name="author-uri" id="author-uri" class="regular-text" value="<?php echo $new_plugin['author-uri']; ?>" /></td>
			</tr>
		</table>
		<input type="submit" class="button-primary" value="Save Data" />
	</form>
</div>