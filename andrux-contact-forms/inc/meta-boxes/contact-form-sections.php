<?php 
	global $post;

	$sections = get_post_meta( $post->ID, __CF_SECTIONS, true );
?>
<div id="contact-form-sections" class="contact-form-meta-container">
	<?php wp_nonce_field( __CF_ACTION, __CF_NONCE ); ?>
	<table id="sections-table" cellspacing="2" cellpadding="2">
		<thead>
			<tr>
				<td></td>
				<td>
					<strong>Name</strong>
				</td>
			</tr>
		</thead>
		<tbody>
			<?php if ( empty( $sections ) ): ?>
			<tr>
				<td>
					<a class="delete-section dashicons dashicons-dismiss" href="javascript:void(0);" title="Delete section"></a>
				</td>
				<td>
					<input type="text" name="<?php echo __CF_SECTIONS; ?>[]" class="regular-text" value="" />
				</td>
			</tr>
			<?php else: ?>
			<?php foreach ( $sections as $section ): ?>
			<tr>
				<td>
					<a class="delete-section dashicons dashicons-dismiss" href="javascript:void(0);" title="Delete section"></a>
				</td>
				<td>
					<input type="text" name="<?php echo __CF_SECTIONS; ?>[]" class="regular-text" value="<?php echo stripslashes( $section ); ?>" />
				</td>
			</tr>
			<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<td>
					<a id="add-section" class="dashicons dashicons-plus-alt" href="javascript:void(0);" title="Add section"></a>
				</td>
			</tr>
		</tfoot>
	</table>
</div>