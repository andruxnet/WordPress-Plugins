<div class="wrap">
	<?php
		if ( wp_verify_nonce( $_POST['eptc-hooks-nonce'], 'eptc-hooks' ) ) {
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

		$hooks['Action'] = array(
							'(hookname)',
							'_admin_menu',
							'_network_admin_menu',
							'_user_admin_menu',
							'add_admin_bar_menus',
							'admin_bar_init',
							'admin_bar_menu',
							'admin_enqueue_scripts',
							'admin_footer',
							'admin_footer-(hookname)',
							'admin_head',
							'admin_head-(hookname)',
							'admin_init',
							'admin_menu',
							'admin_notices',
							'admin_print_footer_scripts',
							'admin_print_scripts',
							'admin_print_scripts-(hookname)',
							'admin_print_styles',
							'admin_print_styles-(hookname)',
							'admin_xml_ns',
							'adminmenu',
							'after_setup_theme',
							'all_admin_notices',
							'auth_cookie_malformed',
							'auth_cookie_valid',
							'auth_redirect',
							'current_screen',
							'dynamic_sidebar',
							'get_footer',
							'get_header',
							'get_search_form',
							'get_sidebar',
							'get_template_part_content',
							'in_admin_footer',
							'in_admin_header',
							'init',
							'load-(page)',
							'load_textdomain',
							'loop_end',
							'loop_start',
							'muplugins_loaded',
							'network_admin_menu',
							'parse_query',
							'parse_request',
							'plugins_loaded',
							'posts_selection',
							'pre_get_comments',
							'pre_get_posts',
							'pre_user_query',
							'register_sidebar',
							'registered_post_type',
							'registered_taxonomy',
							'restrict_manage_posts',
							'sanitize_comment_cookies',
							'send_headers',
							'set_current_user',
							'setup_theme',
							'shutdown',
							'template_redirect',
							'the_post',
							'user_admin_menu',
							'widgets_init',
							'wp',
							'wp_after_admin_bar_render',
							'wp_before_admin_bar_render',
							'wp_default_scripts',
							'wp_default_styles',
							'wp_enqueue_scripts',
							'wp_footer',
							'wp_head',
							'wp_loaded',
							'wp_meta',
							'wp_print_footer_scripts',
							'wp_print_scripts',
							'wp_print_styles',
							'wp_register_sidebar_widget',
						);

		$hooks['Filter'] = array(
							'404_template',
							'add_ping',
							'admin_user_info_links',
							'all_options',
							'allowed_redirect_hosts',
							'archive_template',
							'attachment_fields_to_edit',
							'attachment_fields_to_save',
							'attachment_icon',
							'attachment_innerHTML',
							'attachment_link',
							'attachment_max_dims',
							'attachment_template',
							'attribute_escape',
							'author_email',
							'author_feed_link',
							'author_link',
							'author_rewrite_rules',
							'author_template',
							'autosave_interval',
							'bloginfo',
							'bloginfo_rss',
							'bloginfo_url',
							'body_class',
							'bulk_actions',
							'cat_rows',
							'category_description',
							'category_feed_link',
							'category_link',
							'category_rewrite_rules',
							'category_save_pre',
							'category_template',
							'comment_author',
							'comment_author_rss',
							'comment_edit_pre',
							'comment_edit_redirect',
							'comment_email',
							'comment_excerpt',
							'comment_flood_filter',
							'comment_moderation_subject',
							'comment_moderation_text',
							'comment_notification_headers',
							'comment_notification_subject',
							'comment_notification_text',
							'comment_post_redirect',
							'comment_reply_link',
							'comment_save_pre',
							'comment_status_pre',
							'comment_text',
							'comment_text_rss',
							'comment_url',
							'comments_array',
							'comments_number',
							'comments_popup_template',
							'comments_rewrite_rules',
							'comments_template',
							'contactmethods',
							'content_edit_pre',
							'content_filtered_save_pre',
							'content_save_pre',
							'create_user_query',
							'cron_request',
							'cron_schedules',
							'custom_menu_order',
							'date_rewrite_rules',
							'date_template',
							'day_link',
							'default_content',
							'default_excerpt',
							'default_title',
							'editable_slug',
							'edited_terms',
							'excerpt_edit_pre',
							'excerpt_length',
							'excerpt_more',
							'excerpt_save_pre',
							'explain_nonce_(verb)-(noun)',
							'feed_link',
							'format_to_edit',
							'format_to_post',
							'found_posts',
							'found_posts_query',
							'get_ancestors',
							'get_attached_file',
							'get_bookmarks',
							'get_categories',
							'get_category',
							'get_comment_ID',
							'get_comment_author',
							'get_comment_author_IP',
							'get_comment_author_email',
							'get_comment_author_link',
							'get_comment_author_url',
							'get_comment_author_url_link',
							'get_comment_date',
							'get_comment_excerpt',
							'get_comment_text',
							'get_comment_time',
							'get_comment_type',
							'get_comments_number',
							'get_editable_authors',
							'get_enclosed',
							'get_meta_sql',
							'get_next_post_join',
							'get_next_post_sort',
							'get_next_post_where',
							'get_others_drafts',
							'get_pages',
							'get_previous_post_join',
							'get_previous_post_sort',
							'get_previous_post_where',
							'get_pung',
							'get_the_excerpt',
							'get_the_guid',
							'get_the_modified_date',
							'get_the_modified_time',
							'get_the_time',
							'get_to_ping',
							'get_users_drafts',
							'gettext',
							'home_template',
							'icon_dir',
							'icon_dir_uri',
							'image_save_pre',
							'jpeg_quality',
							'js_escape',
							'kubrick_header_color',
							'kubrick_header_display',
							'kubrick_header_image',
							'link_category',
							'link_description',
							'link_rating',
							'link_title',
							'list_cats',
							'list_cats_exclusions',
							'locale',
							'locale_stylesheet_uri',
							'login_body_class',
							'login_errors',
							'login_headertitle',
							'login_headerurl',
							'login_message',
							'login_redirect',
							'loginout',
							'manage_edit-${post_type}_columns',
							'manage_link-manager_columns',
							'manage_pages_columns',
							'manage_posts_columns',
							'manage_users_custom_column',
							'manage_users_sortable_columns',
							'mce_buttons',
							'mce_buttons_2',
							'mce_buttons_3',
							'mce_buttons_4',
							'mce_css',
							'mce_external_languages',
							'mce_external_plugins',
							'mce_spellchecker_languages',
							'media_upload_tabs',
							'menu_order',
							'mod_rewrite_rules',
							'month_link',
							'name_save_pre',
							'option_(option name)',
							'override_load_textdomain',
							'page_link',
							'page_rewrite_rules',
							'page_template',
							'paged_template',
							'phone_content',
							'ping_status_pre',
							'post_class',
							'post_comments_feed_link',
							'post_edit_form_tag',
							'post_limits',
							'post_link',
							'post_mime_type_pre',
							'post_rewrite_rules',
							'post_type_link',
							'postmeta_form_limit',
							'posts_distinct',
							'posts_fields',
							'posts_groupby',
							'posts_join',
							'posts_join_paged',
							'posts_orderby',
							'posts_request',
							'posts_where',
							'posts_where_paged',
							'pre_category_description',
							'pre_category_name',
							'pre_category_nicename',
							'pre_comment_approved',
							'pre_comment_author_email',
							'pre_comment_author_name',
							'pre_comment_author_url',
							'pre_comment_content',
							'pre_comment_user_agent',
							'pre_comment_user_ip',
							'pre_get_space_used',
							'pre_link_description',
							'pre_link_image',
							'pre_link_name',
							'pre_link_notes',
							'pre_link_rel',
							'pre_link_rss',
							'pre_link_target',
							'pre_link_url',
							'pre_option_(option name)',
							'pre_update_option_(option name)',
							'pre_upload_error',
							'pre_user_description',
							'pre_user_display_name',
							'pre_user_email',
							'pre_user_first_name',
							'pre_user_id',
							'pre_user_last_name',
							'pre_user_login',
							'pre_user_nicename',
							'pre_user_nickname',
							'pre_user_url',
							'prepend_attachment',
							'preprocess_comment',
							'preview_page_link',
							'preview_post_link',
							'query',
							'query_string',
							'query_vars',
							'redirect_canonical',
							'register',
							'registration_errors',
							'request',
							'rewrite_rules_array',
							'richedit_pre',
							'role_has_cap',
							'root_rewrite_rules',
							'sanitize_title',
							'sanitize_user',
							'schedule_event',
							'search_rewrite_rules',
							'search_template',
							'show_password_fields',
							'single_cat_title',
							'single_post_title',
							'single_template',
							'status_save_pre',
							'stylesheet',
							'stylesheet_directory',
							'stylesheet_directory_uri',
							'stylesheet_uri',
							'tag_link',
							'template',
							'template_directory',
							'template_directory_uri',
							'template_include',
							'terms_to_edit',
							'the_author',
							'the_author_email',
							'the_category',
							'the_category_rss',
							'the_content',
							'the_content_feed',
							'the_content_rss',
							'the_date',
							'the_editor',
							'the_editor_content',
							'the_excerpt',
							'the_excerpt_rss',
							'the_modified_date',
							'the_modified_time',
							'the_password_form',
							'the_permalink',
							'the_posts',
							'the_tags',
							'the_time',
							'the_title',
							'the_title_rss',
							'the_weekday',
							'the_weekday_date',
							'theme_root',
							'theme_root_uri',
							'thumbnail_filename',
							'tiny_mce_before_init',
							'title_edit_pre',
							'title_save_pre',
							'update_attached_file',
							'update_user_query',
							'upload_dir',
							'upload_mimes',
							'uploading_iframe_src',
							'user_can_richedit',
							'user_has_cap',
							'user_registration_email',
							'validate_username',
							'widget_archives_dropdown_args',
							'widget_categories_args',
							'widget_links_args',
							'widget_pages_args',
							'widget_tag_cloud_args',
							'widget_text',
							'widget_title',
							'wp_admin_bar_class',
							'wp_create_thumbnail',
							'wp_delete_file',
							'wp_dropdown_cats',
							'wp_dropdown_pages',
							'wp_generate_attachment_metadata',
							'wp_get_attachment_metadata',
							'wp_get_attachment_thumb_file',
							'wp_get_attachment_thumb_url',
							'wp_get_attachment_url',
							'wp_handle_upload',
							'wp_insert_post_data',
							'wp_list_categories',
							'wp_list_pages',
							'wp_list_pages_excludes',
							'wp_mail_from',
							'wp_mail_from_name',
							'wp_mime_type_icon',
							'wp_redirect',
							'wp_redirect_status',
							'wp_save_image_file',
							'wp_terms_checklist_args',
							'wp_thumbnail_creation_size_limit',
							'wp_thumbnail_max_side_length',
							'wp_title',
							'wp_update_attachment_metadata',
							'wp_upload_tabs',
							'xmlrpc_methods',
							'year_link',
						);

		$plugins = get_option( 'eptc-plugins' );
		$current_plugin = $plugins['current-plugin'];
		$new_plugin = $plugins[ $current_plugin ];

		/*$hooks = $this->hooks_list();
		sort( $hooks['Action'] );
		sort( $hooks['Filter'] );*/

	?>
    <?php screen_icon( 'options-general' ); ?><h2>Hooks</h2>
	<form method="post" action="">
		<?php wp_nonce_field( 'eptc-hooks', 'eptc-hooks-nonce' ); ?>
		<h3>Action Hooks</h3>
		<table class="form-table">
			<tr class="required">
				<th scope="row"><label for="action-tag">Tag</label></th>
				<td>
					<select name="action-tag" id="action-tag" class="hook-data">
						<option value="">Select one</option>
						<?php foreach ( $hooks['Action'] as $hook ): ?>
						<option value="<?php echo $hook; ?>"><?php echo $hook; ?></option>,
						<?php endforeach; ?>
					</select>
					<p class="description">The name of the action to which the <i>callback</i> is hooked.</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="action-callback">Callback</label></th>
				<td>
					<input type="text" name="action-callback" id="action-callback" class="regular-text hook-data" value="<?php echo $new_plugin['version']; ?>" />
					<p class="description">The name of the function you wish to be called.</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="action-priority">Priority</label></th>
				<td>
					<input type="number" name="action-priority" id="action-priority" class="small-text hook-data" min="1" value="<?php echo 10; ?>" />
					<p class="description description-wide">optional. Used to specify the order in which the functions associated with a particular action are executed (default: 10). Lower numbers correspond with earlier execution, and functions with the same priority are executed in the order in which they were added to the action.</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="action-accepted-args">Accepted Arguments</label></th>
				<td>
					<input type="number" name="action-accepted-args" id="action-accepted-args" class="small-text hook-data" min="1" value="<?php echo 1; ?>" />
					<p class="description">optional. The number of arguments the function accepts (default 1).</p>
				</td>
			</tr>
			<tr>
				<th></th>
				<td>
					<input type="button" class="button add-hook" value="Add Hook" />
				</td>
			</tr>
		</table>
		<div>
			<table class="hooks-list">
				<tr>
					<th>Tag</th>
					<th>Callback</th>
					<th>Priority</th>
					<th>Args</th>
				</tr>
				<?php $action_hooks = (array)$new_plugin['action-hooks']; ?>
				<?php foreach ( $action_hooks as $k => $hook_info ): ?>
				<tr>
					<td><?php echo $hook_info['tag']; ?></td>
					<td><?php echo $hook_info['callback']; ?></td>
					<td><?php echo $hook_info['priority']; ?></td>
					<td><?php echo $hook_info['args']; ?></td>
				</tr>
				<?php endforeach; ?>
			</table>
		</div>
		<h3>Filter Hooks</h3>
		<table class="form-table">
			<tr class="required">
				<th scope="row"><label for="filter-tag">Tag</label></th>
				<td>
					<select name="filter-tag" id="filter-tag" class="hook-data">
						<option value="">Select one</option>
						<?php foreach ( $hooks['Filter'] as $hook ): ?>
						<option value="<?php echo $hook; ?>"><?php echo $hook; ?></option>
						<?php endforeach; ?>
					</select>
					<p class="description">The name of the filter to which the <i>callback</i> is hooked.</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="filter-callback">Callback</label></th>
				<td>
					<input type="text" name="filter-callback" id="filter-callback" class="regular-text hook-data" value="<?php echo $new_plugin['version']; ?>" />
					<p class="description">The name of the function to be called when the filter is applied.</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="filter-priority">Priority</label></th>
				<td>
					<input type="number" name="filter-priority" id="filter-priority" class="small-text hook-data" min="1" value="<?php echo 10; ?>" />
					<p class="description description-wide">Used to specify the order in which the functions associated with a particular action are executed (default: 10). Lower numbers correspond with earlier execution, and functions with the same priority are executed in the order in which they were added to the action.</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="filter-accepted-args">Accepted Arguments</label></th>
				<td>
					<input type="number" name="filter-accepted-args" id="filter-accepted-args" class="small-text hook-data" min="1" value="<?php echo 1; ?>" />
					<p class="description">optional. The number of arguments the function accepts (default 1).</p>
				</td>
			</tr>
			<tr>
				<th></th>
				<td>
					<input type="button" class="button add-hook" value="Add Hook" />
				</td>
			</tr>
		</table>
		<div>
			<table class="hooks-list">
				<tr>
					<th>Tag</th>
					<th>Callback</th>
					<th>Priority</th>
					<th>Args</th>
				</tr>
				<?php $filter_hooks = (array)$new_plugin['filter-hooks']; ?>
				<?php foreach ( $filter_hooks as $k => $hook_info ): ?>
				<tr>
					<td><?php echo $hook_info['tag']; ?></td>
					<td><?php echo $hook_info['callback']; ?></td>
					<td><?php echo $hook_info['priority']; ?></td>
					<td><?php echo $hook_info['args']; ?></td>
				</tr>
				<?php endforeach; ?>
			</table>

		</div>
		<input type="submit" class="button-primary" value="Save Data" />
	</form>
</div>