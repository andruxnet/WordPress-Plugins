<?php 
function andrux_contact_form_shortcode( $atts ) {
	extract( shortcode_atts( 
		array(
			'title' => '',
		), $atts ) );

	$form = get_page_by_title( $title, OBJECT, '__cf-contact-form' );

	$fields = get_post_meta( $form->ID, __CF_FIELDS, true );
	$sections = get_post_meta( $form->ID, __CF_SECTIONS, true );
	$extra = get_post_meta( $form->ID, __CF_EXTRA, true );

	ob_start();
?>
	<form class="formDefault" id="contact-form" name="a" method="post" action="<?php echo get_permalink( get_the_ID() ); ?>">
		<?php wp_nonce_field( 'contact-form-action', 'contact-form-nonce' ); ?>
		<p>Fields with (<span class="alert">*</span>) are required.</p>
<?php
		if ( isset( $_POST['contact-form-nonce'] ) && wp_verify_nonce( $_POST['contact-form-nonce'], 'contact-form-action' ) ) {
			$req_check = array();
			foreach ( $_POST['__required'] as $req_label => $req_name ) {
				if ( empty( $_POST['contact-data'][ $req_name ] ) ) {
					$req_check[] = "<strong>$req_label</strong>";
				}
			}

			if ( count( $req_check ) > 0 ) {
				echo '<div class="required-fields-check">';
				echo 'Please enter information for the following required fields:<br />';
				echo '<ul>';
				foreach ( $req_check as $req_field ) {
					echo "<li>$req_field</li>";
				}
				echo '</ul>';
				echo '</div>';
			}

			if ( isset( $extra['captcha'] ) && stripos( $_POST['__captcha-string'], "[{$_POST['contact-data']['captcha']}]" ) == false ) {
				echo 'Captcha code is not correct<br />';
			}
			else {
				if ( count( $req_check ) == 0 ) {
					$email = get_post_meta( $form->ID, __CF_EMAIL, true );

					/* send email if this option is enabled */
					if ( $email['Enable Email']['value'] ) {
						$smtp_options = get_option( __CF_EMAIL_SETTINGS );

						if ( $smtp_options[ 'Method' ]['value'] == 'mail' ) {
							/* set headers to html */
							add_filter( 'wp_mail_content_type', array( 'andrux_Contact_Forms', 'set_html_content_type' ) );
						}

						/* send the email */
						$mail_response = wp_mail( $_POST['contact-data']['email'], $email['Email Subject']['value'], $email['Email Body']['value'], '' );

						if ( $smtp_options[ 'Method' ]['value'] == 'mail' ) {
							/* Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578 */
							remove_filter( 'wp_mail_content_type', array( 'andrux_Contact_Forms', 'set_html_content_type' ) );
						}

						if ( $mail_response ) {
							echo 'Your email has been sent!';
						}
						else {
							echo 'There was a problem sending the email. Please check your Email settings.';
						}
					}
					else {
						echo 'Your information was submitted correctly';
					}

					echo '<br />';
				}
			}
		}
?>
		<?php foreach ( $sections as $section ): ?>
		<?php $section = stripslashes( html_entity_decode( $section ) ); ?>
		<fieldset>
			<legend><?php echo $section; ?></legend>
			<?php $section = sanitize_title( $section ); ?>
			<?php foreach ( $fields as $key => $data ): ?>
			<?php if ( $section == $data['section'] ): ?>
			<div>
				<?php $name = empty( $data['name'] ) ? sanitize_title( "contact-data[${section}-${data['label']}]" ) : $data['name']; ?>
				<?php $id = empty( $data['id'] ) ? sanitize_title( "${section}_${data['label']}" ) : $data['id']; ?>
				<?php $class = empty( $data['class'] ) ? '' : $data['class']; ?>
				<?php $atts = empty( $data['atts'] ) ? '' : stripslashes( $data['atts'] ); ?>
				<?php $options = empty( $data['options'] ) ? array() : explode( ',', $data['options'] ); ?>
				<?php $required = isset( $data['required'] ) ? '<span class="alert">*</span>' : ''; ?>
				<?php $required_att = isset( $data['required'] ) ? 'required' : ''; ?>
				<?php if ( isset( $data['required'] ) ): ?>
				<input type="hidden" name="__required[<?php echo $data['label']; ?>]" value="<?php echo $name; ?>" />
				<?php endif; ?>
				<label for="<?php echo $id; ?>"><?php echo $required; ?> <?php echo $data['label']; ?></label>
				<?php if ( $data['field-type'] == 'checkbox' ): ?>
				<?php $class = 'checkbox'; ?>
				<div class="group">
					<?php foreach ( $options as $option ): ?>
					<p>
						<input name="contact-data[<?php echo $name; ?>][]" id="<?php echo $id; ?>" class="<?php echo $class; ?>" type="checkbox" value="<?php echo $option; ?>" <?php echo $required_att; ?> /> <?php echo $option; ?>
					</p>
					<?php endforeach; ?>
				</div>
				<?php elseif ( $data['field-type'] == 'textarea' ): ?>
				<?php $atts = 'cols="30" rows="8"'; ?>
				<textarea name="contact-data[<?php echo $name; ?>]" id="<?php echo $id; ?>" <?php echo $atts; ?> <?php echo $required_att; ?>></textarea>
				<?php else: ?>
				<input name="contact-data[<?php echo $name; ?>]" id="<?php echo $id; ?>" type="<?php echo $data['field-type']; ?>" <?php echo $atts; ?> <?php echo $required_att; ?> value="" />
				<?php if ( $data['field-type'] == 'email' ): ?>
				<input type="hidden" id="__cf-email-address" name="__cf-email-address" value="" />
				<?php if ( isset( $extra['mailing-list'] ) ): ?>
				<p class="note indent"><?php echo stripslashes( html_entity_decode( $extra['mailing-list-text'] ) ); ?></p>
				<label for="mailinglist" class="checkboxLabel">
				<input name="mailinglist[]" value="default" class="checkbox" type="checkbox" /> Add me to your mailing list.</label>
				<?php endif; ?>
				<?php endif; ?>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<?php endforeach; ?>
		</fieldset>
		<?php endforeach; ?>
		<?php if ( isset( $extra['captcha'] ) ): ?>
		<fieldset>
			<legend>Captcha</legend>
			<div class="formCaptcha">
				<p><?php echo stripslashes( html_entity_decode( $extra['captcha-text'] ) ); ?></p>
				<?php $captcha = get_captcha_string(); ?>
				<label for="captcha" class="captchaLabel"><?php echo $captcha; ?><span class="alert">*</span></label>
				<input name="contact-data[captcha]" id="captcha" value="" size="20" maxlength="40" type="text" required />
				<input type="hidden" name="__required[Captcha]" value="captcha" />
				<input type="hidden" name="__captcha-string" value="<?php echo $captcha; ?>" />
			</div>
		</fieldset>
		<?php endif; ?>

		<div class="submitContainer">
			<input name="submit" id="submit" value="Submit Form" type="submit">
		</div>

	</form>
<?php 

	return ob_get_clean();
}

/**
 * get a random string for captcha feature using a quotes API
 */
function get_captcha_string() {
	$libxml_value = libxml_use_internal_errors();
	libxml_use_internal_errors( true );

	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, 'https://andruxnet-random-famous-quotes.p.mashape.com/cat/Famous' );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'X-Mashape-Authorization: 3GRsAnFnVVWlAFqqHfYWNFrNdBH9Elxl' ) );

	$quote_data = json_decode( curl_exec( $ch ) );
	curl_close( $ch );

	libxml_use_internal_errors( $libxml_value );

	$words = explode( ' ', $quote_data->quote );
	$word = '';
	$count = 1;
	while ( strlen( $word ) < 4 ) {
		$rand = mt_rand( 0, count( $words ) - 1 );
		if ( preg_match( "/\W/", $words[ $rand ] ) == 0 ) {
			$word = $words[ $rand ];
		}
	}

	$words[ $rand ] = str_replace( $word, "[{$word}]", $words[ $rand ], $count );

	return implode( ' ', $words );
}

add_shortcode( 'andrux-contact-form', 'andrux_contact_form_shortcode' ); ?>
