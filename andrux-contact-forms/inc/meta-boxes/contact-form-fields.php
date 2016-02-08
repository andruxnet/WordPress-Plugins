<?php 
	global $post;

	$field_types = array(
		'Checkbox',
		'Color',
		'Date',
		'Email',
		'Number',
		'Select',
		'Tel', 
		'Text', 
		'Textarea', 
	);

	$fields = get_post_meta( $post->ID, __CF_FIELDS, true );
	$sections = get_post_meta( $post->ID, __CF_SECTIONS, true );
?>
<div id="contact-form-fields" class="contact-form-meta-container">
	<?php wp_nonce_field( __CF_ACTION, __CF_NONCE ); ?>
	<table id="fields-table" cellspacing="2" cellpadding="2" style="margin: 10px;">
		<thead>
			<tr>
				<td></td>
				<td>
					<strong>Section</strong>
				</td>
				<td>
					<strong>Label</strong>
				</td>
				<td>
					<strong>Field Type</strong>
				</td>
				<td>
					<strong>Req?</strong>
				</td>
				<td>
					<strong>Name</strong>
				</td>
				<td>
					<strong>Multiple Options</strong>
				</td>
				<td class="advanced-settings">
					<strong>Id</strong>
				</td>
				<td class="advanced-settings">
					<strong>Class</strong>
				</td>
				<td class="advanced-settings">
					<strong>Html Attributes</strong>
				</td>
			</tr>
		</thead>
		<tbody>
			<?php if ( empty( $fields ) ): ?>
			<?php $i = 0; ?>
			<tr row-number="<?php echo $i; ?>">
				<td>
					<a class="delete-field dashicons dashicons-dismiss" href="javascript:void(0);" title="Delete field"></a>
				</td>
				<td>
					<select name="<?php echo __CF_FIELDS; ?>[<?php echo $i; ?>][section]">
						<option value=""></option>
						<?php foreach ( $sections as $section ): ?>
						<option value="<?php echo sanitize_title( $section ); ?>"><?php echo $section; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<td>
					<input type="text" name="<?php echo __CF_FIELDS; ?>[<?php echo $i; ?>][label]" size="10" value="" />
				</td>
				<td>
					<select class="fields-box" name="<?php echo __CF_FIELDS; ?>[<?php echo $i; ?>][field-type]">
						<?php foreach ( $field_types as $field_type ): ?>
						<option value="<?php echo sanitize_title( $field_type ); ?>"><?php echo $field_type; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<td>
					<div style="text-align: center;">
						<input type="checkbox" name="<?php echo __CF_FIELDS; ?>[<?php echo $i; ?>][required]" value="1" />
					</div>
				</td>
				<td>
					<input type="text" name="<?php echo __CF_FIELDS; ?>[<?php echo $i; ?>][name]" placeholder="optional" size="10" value="" />
				</td>
				<td>
					<input type="text" name="<?php echo __CF_FIELDS; ?>[<?php echo $i; ?>][options]" placeholder="required for checkboxes" size="40" value="" />
				</td>
				<td class="advanced-settings">
					<input type="text" name="<?php echo __CF_FIELDS; ?>[<?php echo $i; ?>][id]" size="10" value="" />
				<td class="advanced-settings">
					<input type="text" name="<?php echo __CF_FIELDS; ?>[<?php echo $i; ?>][class]" size="10" value="" />
				</td>
				<td class="advanced-settings">
					<input type="text" name="<?php echo __CF_FIELDS; ?>[<?php echo $i; ?>][atts]" size="20" value="" />
				</td>
			</tr>
			<?php else: ?>
			<?php for ( $i = 0; $i < count( $fields ); $i++ ): ?>
			<tr row-number="<?php echo $i; ?>">
				<td>
					<a class="delete-field dashicons dashicons-dismiss" href="javascript:void(0);" title="Delete field"></a>
				</td>
				<td>
					<select name="<?php echo __CF_FIELDS; ?>[<?php echo $i; ?>][section]">
						<option value="default">Default</option>
						<?php foreach ( $sections as $section ): ?>
						<option value="<?php echo sanitize_title( $section ); ?>"<?php selected( $fields[ $i ]['section'], sanitize_title( $section ) ) ; ?>><?php echo $section; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<td>
					<input type="text" name="<?php echo __CF_FIELDS; ?>[<?php echo $i; ?>][label]" size="10" value="<?php echo $fields[ $i ]['label']; ?>" />
				</td>
				<td>
					<select class="fields-box" name="<?php echo __CF_FIELDS; ?>[<?php echo $i; ?>][field-type]">
						<?php foreach ( $field_types as $field_type ): ?>
						<?php $default = empty( $fields[ $i ]['field-type'] ) ? 'text' : sanitize_title( $fields[ $i ]['field-type'] ); ?>
						<option value="<?php echo sanitize_title( $field_type ); ?>"<?php selected( $default, sanitize_title( $field_type ) ); ?>><?php echo $field_type; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<td>
					<div style="text-align: center;">
						<input type="checkbox" name="<?php echo __CF_FIELDS; ?>[<?php echo $i; ?>][required]" value="1" <?php checked( isset( $fields[ $i ]['required'] ), true ); ?> />
					</div>
				</td>
				<td>
					<input type="text" name="<?php echo __CF_FIELDS; ?>[<?php echo $i; ?>][name]" size="10" placeholder="optional" value="<?php echo $fields[ $i ]['name']; ?>" />
				</td>
				<td>
					<input type="text" name="<?php echo __CF_FIELDS; ?>[<?php echo $i; ?>][options]" size="40" placeholder="required for checkboxes" value="<?php echo $fields[ $i ]['options']; ?>" />
				</td>
				<td class="advanced-settings">
					<input type="text" name="<?php echo __CF_FIELDS; ?>[<?php echo $i; ?>][id]" size="10" value="<?php echo $fields[ $i ]['id']; ?>" />
				<td class="advanced-settings">
					<input type="text" name="<?php echo __CF_FIELDS; ?>[<?php echo $i; ?>][class]" size="10" value="<?php echo $fields[ $i ]['class']; ?>" />
				</td>
				<td class="advanced-settings">
					<input type="text" name="<?php echo __CF_FIELDS; ?>[<?php echo $i; ?>][atts]" size="20" value="<?php echo stripslashes( htmlentities( $fields[ $i ]['atts'] ) ); ?>" />
				</td>
			</tr>
			<?php endfor; ?>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<td>
					<a id="add-field" class="dashicons dashicons-plus-alt" href="javascript:void(0);" title="Add field"></a>
				</td>
			</tr>
		</tfoot>
	</table>
</div>