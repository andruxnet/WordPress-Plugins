<?php 
	global $post;

	$defaults = array(
		'Method' => array(
			'type' => 'select',
			'value' => 'WordPress',
			'description' => 'Set this setting to WordPress to disable SMTP and use WordPress\' mailing functionality.',
			'options' => array( 'mail' => 'WordPress', 'smtp' => 'SMTP' ),
			'extra-atts' => 'style="width: 140px;"'
		),
		'Content Type' => array(
			'type' => 'select',
			'value' => 'text/plain',
			'description' => 'Will the emails be sent as plain text or HTML?',
			'options' => array( 'text/plain' => 'Text', 'text/html' => 'HTML' ),
			'extra-atts' => 'style="width: 140px;"'
		),
		'From' => array(
			'type' => 'text',
			'value' => '',
			'description' => 'The reply-to email address for the emails sent.',
			'extra-atts' => ''
		),
		'From Name' => array(
			'type' => 'text',
			'value' => '',
			'description' => 'The name as it will appear in the emails sent.',
			'extra-atts' => ''
		),
		'Host' => array(
			'type' => 'text',
			'value' => '',
			'description' => 'Popular hosts are: smtp.gmail.com (Gmail), smtp.live.com (Hotmail), smtp.mail.yahoo.com (Yahoo!).',
			'extra-atts' => ''
		),
		'Encryption' => array(
			'type' => 'select',
			'value' => '',
			'description' => 'Encryption used to connect to the SMTP server.',
			'options' => array( '' => 'None', 'ssl' => 'SSL', 'tls' => 'TLS' ),
			'extra-atts' => 'style="width: 140px;"'
		),
		'Port' => array(
			'type' => 'number',
			'value' => '',
			'description' => 'Ports used by popular hosts are: 465 (Gmail), 25 (Hotmail), 465 (Yahoo!).',
			'extra-atts' => ''
		),
		'Authentication' => array(
			'type' => 'checkbox',
			'value' => false,
			'description' => 'Use SMTP authentication?',
			'extra-atts' => ''
		),
		'Email Username' => array(
			'type' => 'text',
			'value' => '',
			'description' => 'The username you use to connect to your SMTP mail inbox - usually it\'s the email address.',
			'extra-atts' => ''
		),
		'Email Password' => array(
			'type' => 'password',
			'value' => '',
			'description' => 'The password to connect to your SMTP mail inbox.',
			'extra-atts' => 'placeholder="[password not displayed]"'
		),
	);
?>
<div class="wrap">
	<h2>Email Settings</h2>
<?php
	/* verify this came from our screen and with proper authorization */
	if ( isset( $_POST['email-nonce'] ) && wp_verify_nonce( $_POST['email-nonce'], 'email-action' ) ) {
		if ( ! empty( $_POST[ __CF_EMAIL ]['Email Password']['value'] ) ) {
			$encoded_password = base64_encode( $_POST[ __CF_EMAIL ]['Email Password']['value'] );
			$_POST[ __CF_EMAIL ]['Email Password']['value'] = $encoded_password;
		}

		update_option( __CF_EMAIL_SETTINGS, $_POST[ __CF_EMAIL ] );

		echo '<div class="updated"><p>Settings updated</p></div>';
	}

	$email = get_option( __CF_EMAIL_SETTINGS, array() );
	$email = andrux_Contact_Forms::parse_args_r( $email, $defaults );
?>
	<form id="email-settings-form" method="post" action="" autocomplete="off">
		<?php wp_nonce_field( 'email-action', 'email-nonce' ); ?>
		<table class="form-table">
		<?php foreach ( $email as $label => $data ): ?>
		<?php
			$id_name = strtolower( str_replace( ' ', '-', $label ) );

			/* hide rows when the checkbox is set for SMTP settings */
			$row_styling = ''; //( $id_name == 'enable-email' || $email['Email Settings']['Enable Email'] ) ? '' : 'class="hidden-row"';
		?>
		<tr <?php echo $row_styling; ?>>
			<td style="width: 20%;">
				<label for="<?php echo $id_name; ?>"><?php echo $label; ?></label>
			</td>
			<td>
				<?php if ( $data['type'] == 'textarea' ): ?>
				<textarea name="<?php echo __CF_EMAIL; ?>[<?php echo $label; ?>][value]" id="<?php echo $id_name; ?>" <?php echo $data['extra-atts']; ?>><?php echo $data['value']; ?></textarea>
				<?php elseif ( $data['type'] == 'wp_editor' ): ?>
				<?php 
					$args = array(
						'wpautop' => false,
						'media_buttons' => false,
						'textarea_name' => __CF_EMAIL . "[{$label}][value]",
						'textarea_rows' => 10,
					);

					wp_editor( $data['value'], $id_name, $args );
				?>
				<?php elseif ( $data['type'] == 'select' ): ?>
				<select class="selectpicker" name="<?php echo __CF_EMAIL; ?>[<?php echo $label; ?>][value]" id="<?php echo $id_name; ?>" <?php echo $data['extra-atts']; ?>>
					<?php foreach ( $data['options'] as $option_value => $option_text ): ?>
					<option value="<?php echo $option_value; ?>" <?php echo selected( $data['value'], $option_value ); ?>><?php echo $option_text; ?></option>
					<?php endforeach; ?>
				</select>
				<?php elseif ( $data['type'] == 'checkbox' ): ?>
				<input type="checkbox" name="<?php echo __CF_EMAIL; ?>[<?php echo $label; ?>][value]" id="<?php echo $id_name; ?>" <?php echo $data['extra-atts']; ?> 
					   value="1" <?php checked( $data['value'], true ); ?> />
				<?php else: ?>
				<input type="<?php echo $data['type']; ?>" name="<?php echo __CF_EMAIL; ?>[<?php echo $label; ?>][value]" id="<?php echo $id_name; ?>" <?php echo $data['extra-atts']; ?> 
					   value="<?php echo $data['type'] == 'password' ? '" placeholder="[password not displayed] autocomplete="off' : $data['value']; ?>" />
				<?php endif; ?>
			</td>
			<td>
				<span class="description"><?php echo $data['description']; ?></span>
			</td>
		</tr>
		<?php endforeach; ?>
		</table>
		<input type="submit" class="button-primary" value="Save Changes" />
	</form>
</div>
