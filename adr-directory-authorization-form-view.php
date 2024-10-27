<?php
if (!current_user_can('level_0')) { ?>
	<div class="section">
		<h3><?php _e('Authorization', 'article-directory-redux'); ?></h3>
		<form name="loginform" id="authoriz" action="<?php bloginfo('wpurl'); ?>/wp-login.php" method="post">
			<div>
				<label for="login"><?php _e('Username', 'article-directory-redux'); ?>:</label>
				<input type="text" name="log" value="" id="login" />
			</div>
			<div>
				<label for="pass"><?php _e('Password'); ?>:</label>
				<input type="password" name="pwd" value="" id="pass" />
			</div>
			<div>
				<span id="remember"><label for="rememberme"><input name="rememberme" id="rememberme" type="checkbox" value="forever" /><?php _e('Remember Me'); ?></label></span>
				<input type="submit" name="submit" value="<?php _e('Log In'); ?>" id="enter" />
			</div>
			<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
			<div id="lost"><?php wp_register('', ''); ?> | <a href="<?php bloginfo('wpurl'); ?>/wp-login.php?action=lostpassword"><?php _e('Lost your password?'); ?></a></div>
		</form>
	</div><!-- .section -->
<?php } else { ?>
	<div class="section">
		<h3><?php _e('Management', 'article-directory-redux'); ?></h3>
		<ul>
			<?php if (current_user_can('level_7')) { ?>
				<li><a href="<?php bloginfo('wpurl'); ?>/wp-admin/post-new.php"><?php _e('Submit article', 'article-directory-redux'); ?></a></li>
				<li><a href="<?php bloginfo('wpurl'); ?>/wp-admin/edit.php"><?php _e('Posts'); ?></a></li>
				<li><a href="<?php bloginfo('wpurl'); ?>/wp-admin/edit-comments.php"><?php _e('Comments'); ?></a></li>
				<li><a href="<?php bloginfo('wpurl'); ?>/wp-admin/plugins.php"><?php _e('Plugins'); ?></a></li>
				<li><a href="<?php bloginfo('wpurl'); ?>/wp-admin/users.php"><?php _e('Users'); ?></a></li>
				<li><a href="<?php bloginfo('wpurl'); ?>/wp-admin/options-general.php"><?php _e('Options'); ?></a></li>
				<li><a href="<?php bloginfo('wpurl'); ?>/wp-admin/profile.php"><?php _e('Profile'); ?></a></li>
				<li><a href="<?php echo wp_logout_url($_SERVER['REQUEST_URI']); ?>"><?php _e('Log out'); ?></a></li>
			<?php } else { ?>
				<?php $options = get_option('article_directory'); ?>
				<?php if ($options['author_interface'] == '0') { ?>
					<?php
					$profile = '';
					if (get_option('permalink_structure') == '') $profile = '&profile';
					else $profile = '?profile';
					?>
					<li><a href="<?php echo get_permalink($options['author_panel_id']); ?>"><?php _e('Submit article', 'article-directory-redux'); ?></a></li>
					<li><a href="<?php echo get_permalink($options['author_panel_id']) . $profile; ?>"><?php _e('My profile', 'article-directory-redux'); ?></a></li>
				<?php } else { ?>
					<li><a href="<?php bloginfo('wpurl'); ?>/wp-admin/post-new.php"><?php _e('Submit article', 'article-directory-redux'); ?></a></li>
					<li><a href="<?php bloginfo('wpurl'); ?>/wp-admin/profile.php"><?php _e('Profile'); ?></a></li>
				<?php } ?>
				<li><a href="<?php echo wp_logout_url(get_bloginfo('wpurl')); ?>"><?php _e('Log out'); ?></a></li>
			<?php } ?>
		</ul>
	</div><!-- .section -->
<?php }