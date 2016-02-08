<div class="wrap">
	<?php
		if ( wp_verify_nonce( $_POST['eptc-menu-nonce'], 'eptc-menu' ) ) {
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

		/*$hooks = $this->hooks_list();
		sort( $hooks['Action'] );
		sort( $hooks['Filter'] );*/
		//add_pages_page($page_title, $menu_title, $capability, $menu_slug)
	?>
    <?php screen_icon( 'options-general' ); ?><h2>Menu Tabs</h2>
	<form method="post" action="">
		<?php wp_nonce_field( 'eptc-menu', 'eptc-menu-nonce' ); ?>
		<h3>Menu Management</h3>
		<table class="form-table">
			<tr class="required">
				<th scope="row"><label for="menu-parent">Parent Menu</label></th>
				<td>
					<select name="menu-parent" id="menu-parent" class="args-data">
						<option value="">Select one</option>
						<?php global $menu; ?>
						<?php foreach ( $menu as $i => $item ): ?>
						<?php if ( ! empty( $item[0] ) ): ?>
						<option value="<?php echo $item[2]; ?>"><?php echo $item[0]; ?></option>
						<?php endif; ?>
						<?php endforeach; ?>
					</select>
					<p class="description">Parent menu tab for the new menu item.</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="menu-page-title">Page Title</label></th>
				<td>
					<input type="text" name="menu-page-title" id="menu-page-title" class="regular-text args-data" value="" />
					<p class="description">The page title in the browser's title bar.</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="menu-tab-title">Menu Title</label></th>
				<td>
					<input type="text" name="menu-tab-title" id="menu-tab-title" class="regular-text args-data" value="" />
					<p class="description description-wide">The title for this menu tab.</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="menu-capability">Capability</label></th>
				<td>
					<input type="text" name="menu-capability" id="menu-capability" class="regular-text args-data" value="" />
					<p class="description">Required capability to be able to see this menu tab.</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="menu-slug">Menu Slug</label></th>
				<td>
					<input type="text" name="menu-slug" id="menu-slug" class="regular-text args-data" value="" />
					<p class="description">Menu slug for this menu tab.</p>
				</td>
			</tr>
			<tr>
				<th></th>
				<td>
					<input type="button" class="button add-menu-tab" value="Add Menu Tab" />
				</td>
			</tr>
		</table>
		<div>
			<table class="menu-tabs-list">
				<tr>
					<th>Tag</th>
					<th>Callback</th>
					<th>Priority</th>
					<th>Args</th>
				</tr>
				<?php //$action_hooks = (array)$new_plugin['action-hooks']; ?>
				<?php //foreach ( $action_hooks as $k => $hook_info ): ?>
				<tr>
					<td><?php //echo $hook_info['tag']; ?></td>
					<td><?php //echo $hook_info['callback']; ?></td>
					<td><?php //echo $hook_info['priority']; ?></td>
					<td><?php //echo $hook_info['args']; ?></td>
				</tr>
				<?php //endforeach; ?>
			</table>
		</div>
		<input type="submit" class="button-primary" value="Save Data" />
	</form>
</div>