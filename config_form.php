<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">

<html>
<head>
    <title></title>
</head>

<body>
    <div class="field">
        <h2>Configurations</h2>

        <div class="field">
            <div class="two columns alpha">
                <label for="srss_facebook_link"><?php echo __('Facebook Link'); ?></label>
            </div>

            <div class="inputs five columns omega">
                <p class="explanation"><?php echo __("Link to related Facebook profile (e.g. http://www.facebook.com/your_page/)"); ?></p>

                <div class="input-block">
                    <input type="text" class="textinput" name="srss_facebook_link" value="<?php echo get_option('srss_facebook_link'); ?>">
                </div>
            </div>
        </div>

        <div class="field">
            <div class="two columns alpha">
                <label for="srss_twitter_user"><?php echo __('Twitter Username'); ?></label>
            </div>

            <div class="inputs five columns omega">
                <p class="explanation"><?php echo __("Twitter username (omit the @ symbol)"); ?></p>

                <div class="input-block">
                    <input type="text" class="textinput" name="srss_twitter_user" value="<?php echo get_option('srss_twitter_user'); ?>">
                </div>
            </div>
        </div>

        <div class="field">
            <div class="two columns alpha">
                <label for="srss_youtube_user"><?php echo __('Youtube Username'); ?></label>
            </div>

            <div class="inputs five columns omega">
                <p class="explanation"><?php echo __("Youtube username"); ?></p>

                <div class="input-block">
                    <input type="text" class="textinput" name="srss_youtube_user" value="<?php echo get_option('srss_youtube_user'); ?>">
                </div>
            </div>
        </div>

        <div class="field">
            <div class="two columns alpha">
                <label for="srss_ios_id"><?php echo __('iOS App Store ID'); ?></label>
            </div>

            <div class="inputs five columns omega">
                <p class="explanation"><?php echo __("ID for related app in iOS App Store (e.g. id123456789)"); ?></p>

                <div class="input-block">
                    <input type="text" class="textinput" name="srss_ios_id" value="<?php echo get_option('srss_ios_id'); ?>">
                </div>
            </div>
        </div>

        <div class="field">
            <div class="two columns alpha">
                <label for="srss_android_id"><?php echo __('Android App ID'); ?></label>
            </div>

            <div class="inputs five columns omega">
                <p class="explanation"><?php echo __("ID for related app in Google Play app market (e.g. com.developer.your.app)"); ?></p>

                <div class="input-block">
                    <input type="text" class="textinput" name="srss_android_id" value="<?php echo get_option('srss_android_id'); ?>">
                </div>
            </div>
        </div>



        <div class="field">
            <div class="two columns alpha">
                <label for="srss_about_text"><?php echo __('About Text'); ?></label>
            </div>

            <div class="inputs five columns omega">
                <p class="explanation"><?php echo __("Enter text describing your site (used for Fieldtrip output)"); ?></p>

                <div class="input-block">
                    <textarea cols="50" rows="4" class="textinput" name="srss_about_text"><?php echo get_option('srss_about_text'); ?></textarea>
                </div>
            </div>
        </div>



        <div class="field">
            <div class="two columns alpha">
                <label for="srss_android_id"><?php echo __('Feed Image URL'); ?></label>
            </div>

            <div class="inputs five columns omega">
                <p class="explanation"><?php echo __("URL for feed image. Used for Fieldtrip output. Image must be square, e.g. http://example.com/themes/default/images/Icon_144x144.png"); ?></p>

                <div class="input-block">
                    <input type="text" class="textinput" name="srss_image_url" value="<?php echo get_option('srss_image_url'); ?>">
                </div>
            </div>
        </div>
        
        



		<div class="field">
	        <div class="two columns alpha">
	            <label for="srss_include_read_more_link"><?php echo __('Include link back to item?'); ?></label>
	        </div>
	
	        <div class="inputs five columns omega">
	            <?php echo get_view()->formCheckbox('srss_include_read_more_link', true, 
	                    array('checked'=>(boolean)get_option('srss_include_read_more_link'))); ?>
	
	            <p class="explanation"><?php echo __(
	                      'If checked, the text of each RSS item will include a link back to the original, reading "For more, view the original article."'
	                    ); ?></p>
	        </div>
		</div>


		<div class="field">
	        <div class="two columns alpha">
	            <label for="srss_include_mediastats_footer"><?php echo __('Include media stats in "Read More" link?'); ?></label>
	        </div>
	
	        <div class="inputs five columns omega">
	            <?php echo get_view()->formCheckbox('srss_include_mediastats_footer', true, 
	                    array('checked'=>(boolean)get_option('srss_include_mediastats_footer'))); ?>
	
	            <p class="explanation"><?php echo __(
	                      'If checked, the included "read more" link for each RSS item will contain details about media files for the item, e.g. "For more (including 8 images, 4 sound clips, and 1 video), view the original article."'
	                    ); ?></p>
	        </div>
		</div>

		<div class="field">
	        <div class="two columns alpha">
	            <label for="srss_include_social_footer"><?php echo __('Include social media links?'); ?></label>
	        </div>
	
	        <div class="inputs five columns omega">
	            <?php echo get_view()->formCheckbox('srss_include_social_footer', true, 
	                    array('checked'=>(boolean)get_option('srss_include_social_footer'))); ?>
	
	            <p class="explanation"><?php echo __(
	                      'If checked, the text of each RSS item will include links to configured social media profiles, e.g. "Find us on Facebook, Twitter and Youtube."'
	                    ); ?></p>
	        </div>
		</div>

		<div class="field">
	        <div class="two columns alpha">
	            <label for="srss_include_applink_footer"><?php echo __('Include app store links?'); ?></label>
	        </div>
	
	        <div class="inputs five columns omega">
	            <?php echo get_view()->formCheckbox('srss_include_applink_footer', true, 
	                    array('checked'=>(boolean)get_option('srss_include_applink_footer'))); ?>
	
	            <p class="explanation"><?php echo __(
	                      'If checked, the text of each RSS item will include links to configured app store downloads, e.g. "Download the [Site Title] app for iOS and Android."'
	                    ); ?></p>
	        </div>
		</div>

        <h2>Usage</h2>

        <p>The SuperRSS plugin adds 2 new output contexts to your site's browse views:</p>

        <ul>
            <li>RSS/Atom output is available at: <?php echo '<a target="_blank" href="'.WEB_ROOT.'/items/browse?output=srss">/items/browse?output=srss</a>';?></li>

            <li>Fieldtrip output is available at: <?php echo '<a target="_blank" href="'.WEB_ROOT.'/items/browse?output=fieldtrip">/items/browse?output=fieldtrip</a>';?></li>
        </ul><strong>Include the RSS link in theme header</strong>: currently, the RSS feed must be manually added to your site's theme header:<br>
        <br>
        <span style="display:block;padding:.25em;background:#333;color:#fafafa;font-family:monospace"><?php echo htmlentities('<link rel="alternate" type="application/rss+xml" title="New Posts: RSS" href="/items/browse?output=srss&per_page=15" />');?></span><br>
        ...best practice is to use PHP to check that the plugin is active:<br>
        <br>
        <span style="display:block;padding:.25em;background:#333;color:#fafafa;font-family:monospace"><?php echo htmlentities("
<?php
if(plugin_is_active('SuperRSS')){ 
echo '<link rel=\"alternate\" type=\"application/rss+xml\" title=\"New Posts: RSS\" href=\"'. html_escape(items_output_url('srss')) .'&per_page=15\" />';
}
?>
");?></span><br>
        <strong>Note on Fieldtrip output</strong>: the Fieldtrip output is useful for integrating your site content with Google's Fieldtrip app. Inclusion in Fieldtrip requires a contract with Google.
        <p>
    </div>
</body>
</html>