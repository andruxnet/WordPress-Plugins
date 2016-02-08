<?php 
	global $post;

	$defaults = array(
		'mailing-list' => false,
		'captcha' => false,
		'mailing-list-text' => '',
		'captcha-text' => '',
	);

	$extra = wp_parse_args( get_post_meta( $post->ID, __CF_EXTRA, true ), $defaults );
?>
<div id="contact-form-sections" class="contact-form-meta-container">
	<?php wp_nonce_field( __CF_ACTION, __CF_NONCE ); ?>
	<p>
		<label for="mailing-list">
			<input type="checkbox" id="mailing-list" name="<?php echo __CF_EXTRA; ?>[mailing-list]" value="1"<?php checked( $extra['mailing-list'], true ); ?> /> Enable mailing list option
		</label>
		<div class="hidden-container">
			<span class="description">Mailing list disclaimer (text or HTML)</span>
			<textarea id="mailing-list-text" name="<?php echo __CF_EXTRA; ?>[mailing-list-text]" rows="5"><?php echo $extra['mailing-list-text']; ?></textarea>
		</div>
	</p>

	<p>
		<label for="captcha">
			<input type="checkbox" id="captcha" name="<?php echo __CF_EXTRA; ?>[captcha]" value="1"<?php checked( $extra['captcha'], true ); ?> /> Enable captcha
		</label>
		<div class="hidden-container">
			<span class="description">Captcha message (text or HTML)</span>
			<textarea id="captcha-text" name="<?php echo __CF_EXTRA; ?>[captcha-text]" rows="5"><?php echo $extra['captcha-text']; ?></textarea>
		</div>
	</p>
</div>