<?php screen_icon(); ?>
<?php $options = get_option($this->option_name); ?>
<div class="wrap">
	<h2>Separate Full RSS Feed Settings</h2>
	<form method="post" action="options.php">
		<?php @settings_fields('separate_full_rss_feed-group'); ?>
		<?php @do_settings_fields('separate_full_rss_feed-group'); ?>
		

		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="<?php echo $this->option_name?>[NumOfPosts]">Number of posts to show (0 for all)</label></th>
				<td><input type="text" name="<?php echo $this->option_name?>[NumOfPosts]" id="<?php echo $this->option_name?>[NumOfPosts]" value="<?php echo $options['NumOfPosts']; ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="<?php echo $this->option_name?>[FeedSlug]">Feed Slug to be used (not implemented yet)</label></th>
				<td><input type="text" name="<?php echo $this->option_name?>[FeedSlug]" id="<?php echo $this->option_name?>[FeedSlug]" value="<?php echo $options['FeedSlug']; ?>" /></td>
			</tr>
		</table>
		
		<?php @submit_button(); ?>
		<p>Click <a href="/?feed=<?php echo $options['FeedSlug']; ?>" target="_blank">here.</a> to show the feed</p>
		
	</form>
</div>