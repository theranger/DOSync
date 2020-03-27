<div id="dos__message"
	 class="notice notice-success settings-error hidden"></div>
<h1>DigitalOcean Spaces Sync <?php _e('Settings', 'dos'); ?></h1>

<form method="post" action="options.php">
	<?php settings_fields('dos_settings'); ?>
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><label
					for="dos_key"><?php _e('Key', 'dos'); ?></label></th>
			<td><input id="dos_key" name="dos_key" type="text"
					   class="regular-text"
					   value="<?php echo get_option('dos_key'); ?>"/></td>
		</tr>

		<tr>
			<th scope="row"><label
					for="dos_secret"><?php _e('Secret', 'dos'); ?></label></th>
			<td><input id="dos_secret" name="dos_secret" type="password"
					   class="regular-text"
					   value="<?php echo get_option('dos_secret'); ?>"/></td>
		</tr>

		<tr>
			<th scope="row"><label
					for="dos_endpoint"><?php _e('Endpoint', 'dos'); ?></label>
			</th>
			<td>
				<input id="dos_endpoint" name="dos_endpoint" type="text"
					   class="regular-text"
					   value="<?php echo get_option('dos_endpoint'); ?>"/>
				<input type="button" name="test"
					   class="button button-primary dos__test__connection"
					   value="<?php _e('Test', 'dos'); ?>"/>
			</td>
		</tr>

		<tr>
			<th scope="row"><label
					for="dos_prefix"><?php _e('Prefix path', 'dos'); ?></label>
			</th>
			<td><input id="dos_prefix" name="dos_prefix" type="text"
					   class="regular-text"
					   value="<?php echo get_option('dos_prefix'); ?>"/></td>
		</tr>
	</table>

	<?php submit_button(); ?>

</form>
