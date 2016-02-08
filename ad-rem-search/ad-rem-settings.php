<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e( "Ad Rem Results Settings" ); ?></h2>
	<hr />
	<h3><?php _e( "General Settings" ); ?></h3>
	<form method="post" action="">
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e( "Results per page" ); ?></th>
				<td><input type="number" name="results_per_page" min="10" max="100" step="10" /></td>
			</tr>
		</table>
		<hr />
		<input type="submit" class="button-primary" value="<?php _e( "Save Changes" ); ?>" />
	</form>
</div>