<?php 
	global $post;

	$defaults = array(
		'Data Submitted Response' => array(
			'type' => 'wp_editor',
			'value' => 'Thank you for reaching out to us. We will process your request shortly.',
			'description' => 'This is the message that will be displayed when the form is submitted.',
			'extra-atts' => ''
		),
		'Enable Email' => array(
			'type' => 'checkbox',
			'value' => false,
			'description' => 'Enable sending an email when the form is submitted?',
			'extra-atts' => ''
		),
		'Email Subject' => array(
			'type' => 'text',
			'value' => 'Thank you for contacting [your company name]',
			'description' => '',
			'extra-atts' => 'style="width: 100%;"'
		),
		'Email Body' => array(
			'type' => 'wp_editor',
			'value' => 'Thank you for your interest in [your company name]. Someone from our organization will contact you regarding your communication or request.<br />Sincerely,<br />[your company name]',
			'description' => '',
			'extra-atts' => ''
		),
	);

	$submit = get_post_meta( $post->ID, __CF_EMAIL, true );
	$submit = andrux_Contact_Forms::parse_args_r( $submit, $defaults );
?>
<div id="contact-form-email" class="contact-form-meta-container">
	<?php wp_nonce_field( __CF_ACTION, __CF_NONCE ); ?>
	<table class="form-table">
	<?php foreach ( $submit as $label => $data ): ?>
	<?php if ( is_array( $data ) ): ?>
	<?php
		$id_name = strtolower( str_replace( ' ', '-', $label ) );

		/* hide rows when the checkbox is set for SMTP settings */
		$row_styling = ''; //( $id_name == 'enable-email' || $submit['Email Settings']['Enable Email'] ) ? '' : 'class="hidden-row"';
	?>
	<tr <?php echo $row_styling; ?>>
		<td style="width: 30%;">
			<label for="<?php echo $id_name; ?>"><?php echo $label; ?></label>
			<span class="description"><?php echo $data['description']; ?></span>
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
				   value="<?php echo $data['type'] == 'password' ? base64_decode( $data['value'] ) : $data['value']; ?>" />
			<?php endif; ?>
		</td>
	</tr>
	<?php endif; ?>
	<?php endforeach; ?>
	</table>
</div>
