<div class="wrap">
	<?php
		$args = array(
			'wpautop' => false,
			'textarea_rows' => 10,
		);

		$defaults = array(
			'general' => array(
				'full-width' => false,
				'auto-height' => false,
				'slides-width' => '720',
				'slides-height' => '400',
				'animation-speed' => '1000',
				'animation-interval' => '3000',
			),
			'slides' => array(
				'image' => '',
				'caption' => '',
				'links-to' => '',
			),
		);

		if ( isset( $_POST['settings-nonce'] ) && wp_verify_nonce( $_POST['settings-nonce'], 'settings-action' ) ) {
			$settings = $_POST['settings'];
			$settings['general']['full-width'] = isset( $_POST['settings']['general']['full-width'] );
			$settings['general']['auto-height'] = isset( $_POST['settings']['general']['auto-height'] );

			//for ( $i = 0; $i < count( $settings['slides']['caption'] ); $i++ ) {
			//	$settings['slides']['caption'][ $i ] = stripslashes( $settings['slides']['caption'][ $i ] );
			//}

			update_option( SIMPLE_SLIDER_OPTIONS, $settings );
			echo '<div class="updated"><p>Settings updated</p></div>';
		}

		$settings = wp_parse_args( get_option( SIMPLE_SLIDER_OPTIONS, array() ), $defaults );
	?>
	<h1>Simple jQuery Slider settings</h1>
	<form id="settings" class="form-table" action="<?php echo admin_url( 'admin.php?page=simple-jquery-slider' ); ?>" method="post">
		<?php wp_nonce_field( 'settings-action', 'settings-nonce' ); ?>
		<h3>General settings</h3>
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="settings-full-width">Full Width Slides?</label>
				</th>
				<td>
					<input type="checkbox" id="settings-full-width" name="settings[general][full-width]" value="1" <?php checked( true, $settings['general']['full-width'] ); ?> />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="settings-auto-height">Auto Height Slides?</label>
				</th>
				<td>
					<input type="checkbox" id="settings-auto-height" name="settings[general][auto-height]" value="1" <?php checked( true, $settings['general']['auto-height'] ); ?> />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="settings-slides-width">Slides Width</label>
				</th>
				<td>
					<input type="text" class="small-text" id="settings-slides-width" name="settings[general][slides-width]"
						   value="<?php echo $settings['general']['slides-width']; ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="settings-slides-height">Slides Height</label>
				</th>
				<td>
					<input type="text" class="small-text" id="settings-slides-height" name="settings[general][slides-height]"
						   value="<?php echo $settings['general']['slides-height']; ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="settings-animation-speed">Animation Speed</label>
				</th>
				<td>
					<input type="text" class="small-text" id="settings-animation-speed" name="settings[general][animation-speed]"
						   value="<?php echo $settings['general']['animation-speed']; ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="settings-animation-interval">Animation Interval</label>
				</th>
				<td>
					<input type="text" class="small-text" id="settings-animation-interval" name="settings[general][animation-interval]"
						   value="<?php echo $settings['general']['animation-interval']; ?>" />
				</td>
			</tr>
		</table>
		<h3>Slides</h3>
		<div id="slides-data">
			<?php for ( $i = 0; $i < count( $settings['slides']['image'] ); $i++ ): ?>
			<?php $slide = $settings['slides']; ?>
			<div class="slide-item">
				<a href="javascript:void(0);" class="remove-slide">
					<span class="dashicons dashicons-dismiss"></span>
				</a>
				<div class="slide-image">
					<h4>Slide Image</h4>
					<input type="hidden" name="settings[slides][image][]" class="upload-input" value="<?php echo $slide['image'][ $i ]; ?>" />
					<input type="button" class="upload-button button-primary <?php echo empty( $slide['image'][ $i ] ) ? 'add-state' : 'remove-state'; ?>" value="<?php echo empty( $slide['image'][ $i ] ) ? 'Choose image' : 'Remove'; ?>" />
					<div class="upload-thumb"><?php echo wp_get_attachment_image( $slide['image'][ $i ], 'large' ); ?></div>
				</div>
				<h4>Caption</h4>
				<textarea class="regular-text" name="settings[slides][caption][]" rows="10"><?php echo $settings['slides']['caption'][ $i ]; ?></textarea>
				<?php
					//$args['textarea_name'] = 'settings[slides][caption][]';
					//wp_editor( stripslashes( $settings['slides']['caption'][ $i ] ), "slides-caption-$i", $args );
				?>
				<div class="slide-text-column">
					<h4>Link</h4>
					<input type="text" class="regular-text" name="settings[slides][link][]" value="<?php echo $slide['link'][ $i ]; ?>" />
				</div>
				<div class="slide-text-column">
					<h4>Link Text</h4>
					<input type="text" class="regular-text" name="settings[slides][link-text][]" value="<?php echo $slide['link-text'][ $i ]; ?>" />
				</div>
			</div>
			<?php endfor; ?>
		</div>
		<div class="clearfix"></div>
		<div id="add-slide-icon">
			<a href="javascript:void(0);" class="add-slide">
				<span class="dashicons dashicons-plus-alt"></span>
			</a>
		</div>
		<input type="submit" class="button-primary" value="Save Settings" />
	</form>
</div>
