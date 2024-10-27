<?php

$error = false;
if ( empty($aOptions['author_panel_id']) && $aOptions['author_interface'] == '0' ) {
	echo '<div id="message" class="error"><p><strong style="color:#C00">' .  __('Attention', 'article-directory-redux') . '!</strong> ' .  __('The option "<em><strong>ID of author panel page</strong></em>" must be filled obligatory. Otherwise, the authors are unable to add articles.', 'article-directory-redux') . '</p></div>';
}
if ( empty($aOptions['author_panel_id']) ) {
	$error = ' style="border: 1px solid #C00; background: #FFEBE8;"';
}
?>

<div class="wrap">

<h2><?php _e('Article Directory Options', 'article-directory-redux'); ?></h2>

<form method="post" action="options.php">
<?php settings_fields( 'article_directory' ); ?>

<div id="poststuff" class="ui-sortable">

<p><input type="submit" class="button-primary" value="<?php _e('Update Options', 'article-directory-redux') ?>" style="font-weight:bold;" /><br><br></p>

<div class="postbox">

	<h3><?php _e('Categories List Options', 'article-directory-redux'); ?></h3>

	<div class="inside">

		<table class="form-table">

			<tr valign="top">
				<td scope="row" colspan="3"><strong style="color: #090"><span style="color: #F00">(!)</span> <?php _e('This options is only for the list of categories, which displays on the home page (or another page, where you have inserted the <code>article_directory()</code> function).', 'article-directory-redux'); ?></strong></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label for="column_count"><?php _e('The number of columns for parent categories list', 'article-directory-redux'); ?>:</label></td>
				<td>
					<input name="article_directory[column_count]" type="text" id="column_count" value="<?php echo $aOptions['column_count']; ?>" size="4" maxlength="2" />
				</td>
				<td></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label><?php _e('Sort the parent categories list', 'article-directory-redux'); ?>:</label></td>
				<td>
					<select name="article_directory[sort_by]">
						<option value="0"<?php selected('0', $aOptions['sort_by']); ?>><?php _e('By name', 'article-directory-redux'); ?></option>
						<option value="1"<?php selected('1', $aOptions['sort_by']); ?>><?php _e('By your choice', 'article-directory-redux'); ?></option>
					</select>
				</td>
				<td><?php _e('For sorting by your choice you need to install <a href="http://wordpress.org/extend/plugins/my-category-order/">My Category Order</a> plugin.', 'article-directory-redux'); ?></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label><?php _e('Sort direction of parent categories', 'article-directory-redux'); ?>:</label></td>
				<td>
					<select name="article_directory[sort_direction]">
						<option value="1"<?php selected('1', $aOptions['sort_direction']); ?>><?php _e('From top to down', 'article-directory-redux'); ?></option>
						<option value="0"<?php selected('0', $aOptions['sort_direction']); ?>><?php _e('From left to right', 'article-directory-redux'); ?></option>
					</select>
				</td>
				<td><?php _e('At sorting "From left to right" the list is built more rationally.', 'article-directory-redux'); ?></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label><?php _e('Show the number of articles in parent categories', 'article-directory-redux'); ?>:</label></td>
				<td>
					<select name="article_directory[show_parent_count]">
						<option value="1"<?php selected('1', $aOptions['show_parent_count']); ?>><?php _e('Yes', 'article-directory-redux'); ?></option>
						<option value="0"<?php selected('0', $aOptions['show_parent_count']); ?>><?php _e('No', 'article-directory-redux'); ?></option>
					</select>
				</td>
				<td></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label><?php _e('Show description in the title of parent categories', 'article-directory-redux'); ?>:</label></td>
				<td>
					<select name="article_directory[desc_for_parent_title]">
						<option value="1"<?php selected('1', $aOptions['desc_for_parent_title']); ?>><?php _e('Yes', 'article-directory-redux'); ?></option>
						<option value="0"<?php selected('0', $aOptions['desc_for_parent_title']); ?>><?php _e('No', 'article-directory-redux'); ?></option>
					</select>
				</td>
				<td></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label><?php _e('Show the "No categories", if category don\'t contain subcategories', 'article-directory-redux'); ?>:</label></td>
				<td>
					<select name="article_directory[no_child_alert]">
						<option value="1"<?php selected('1', $aOptions['no_child_alert']); ?>><?php _e('Yes', 'article-directory-redux'); ?></option>
						<option value="0"<?php selected('0', $aOptions['no_child_alert']); ?>><?php _e('No', 'article-directory-redux'); ?></option>
					</select>
				</td>
				<td></td>
			</tr>

			<tr>
				<td style="padding: 0">&nbsp;</td>
			</tr>

			<tr valign="top">
				<td scope="row"><label><?php _e('Show the child categories', 'article-directory-redux'); ?>:</label></td>
				<td>
					<select name="article_directory[show_child]">
						<option value="1"<?php selected('1', $aOptions['show_child']); ?>><?php _e('Yes', 'article-directory-redux'); ?></option>
						<option value="0"<?php selected('0', $aOptions['show_child']); ?>><?php _e('No', 'article-directory-redux'); ?></option>
					</select>
				</td>
				<td></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label><?php _e('Show the number of articles in child categories', 'article-directory-redux'); ?>:</label></td>
				<td>
					<select name="article_directory[show_child_count]">
						<option value="1"<?php selected('1', $aOptions['show_child_count']); ?>><?php _e('Yes', 'article-directory-redux'); ?></option>
						<option value="0"<?php selected('0', $aOptions['show_child_count']); ?>><?php _e('No', 'article-directory-redux'); ?></option>
					</select>
				</td>
				<td></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label for="maximum_child"><?php _e('The number of child categories to show', 'article-directory-redux'); ?>:</label></td>
				<td>
					<input name="article_directory[maximum_child]" type="text" id="maximum_child" value="<?php echo $aOptions['maximum_child']; ?>" size="4" maxlength="2" />
				</td>
				<td><?php _e('<code>0</code> - all child categories will be displayed. If the number other than zero, level 3 child categories not shown.<br /> Specify <code>99</code>, if you not want to show subcategories of 3rd and above level.', 'article-directory-redux'); ?></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label><?php _e('Show description in the title of child categories', 'article-directory-redux'); ?>:</label></td>
				<td>
					<select name="article_directory[desc_for_child_title]">
						<option value="1"<?php selected('1', $aOptions['desc_for_child_title']); ?>><?php _e('Yes', 'article-directory-redux'); ?></option>
						<option value="0"<?php selected('0', $aOptions['desc_for_child_title']); ?>><?php _e('No', 'article-directory-redux'); ?></option>
					</select>
				</td>
				<td></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label><?php _e('Use hierarchy for child categories', 'article-directory-redux'); ?>:</label></td>
				<td>
					<select name="article_directory[child_hierarchical]">
						<option value="1"<?php selected('1', $aOptions['child_hierarchical']); ?>><?php _e('Yes', 'article-directory-redux'); ?></option>
						<option value="0"<?php selected('0', $aOptions['child_hierarchical']); ?>><?php _e('No', 'article-directory-redux'); ?></option>
					</select>
				</td>
				<td></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label><?php _e('Hide empty categories', 'article-directory-redux'); ?>:</label></td>
				<td>
					<select name="article_directory[hide_empty]">
						<option value="1"<?php selected('1', $aOptions['hide_empty']); ?>><?php _e('Yes', 'article-directory-redux'); ?></option>
						<option value="0"<?php selected('0', $aOptions['hide_empty']); ?>><?php _e('No', 'article-directory-redux'); ?></option>
					</select>
				</td>
				<td></td>
			</tr>

			<tr>
				<td style="padding: 0">&nbsp;</td>
			</tr>

			<tr valign="top">
				<td scope="row" style="width: 360px"><label for="exclude_cats"><?php _e('Comma separated IDs of categories, which should be excluded', 'article-directory-redux'); ?>:</label></td>
				<td width="130">
					<input name="article_directory[exclude_cats]" type="text" id="exclude_cats" value="<?php echo $aOptions['exclude_cats']; ?>" size="15" />
				</td>
				<td><?php _e('Еxample: <code>1,3,7</code>. <code>0</code> - all categories will be displayed.', 'article-directory-redux'); ?></td>
			</tr>

		</table>

	</div><!-- .inside -->

</div><!-- .postbox -->

<div class="postbox">

	<h3><?php _e('"Submit Article" page options', 'article-directory-redux'); ?></h3>

	<div class="inside">

		<table class="form-table">

			<tr valign="top">
				<td scope="row" style="width: 360px"><label><?php _e('Interface for authors', 'article-directory-redux'); ?>:</label></td>
				<td>
					<select name="article_directory[author_interface]" id="author_interface">
						<option value="1"<?php selected('1', $aOptions['author_interface']); ?>><?php _e('WordPress admin area', 'article-directory-redux'); ?></option>
						<option value="0"<?php selected('0', $aOptions['author_interface']); ?>><?php _e('Author panel', 'article-directory-redux'); ?></option>
					</select>
				</td>
				<td></td>
			</tr>

		</table>

		<script type="text/javascript">
			(function($) {
				$(function() {
					if ( $('#author_interface').val() == '1' ) $('#author_panel_options').hide();
					$('#author_interface').change(function() { $('#author_panel_options, #message').toggle(); })
				})
			})(jQuery)
		</script>

		<table class="form-table" id="author_panel_options">

			<noscript>
				<tr valign="top">
					<td scope="row" colspan="3"><div style="margin: -10px 0 0"><strong style="color: #FF4D00"><?php _e('The following options are works only if you have selected the interface "Author panel".', 'article-directory-redux'); ?></strong></div></td>
				</tr>
			</noscript>

			<tr valign="top">
				<td scope="row" style="width: 360px"><label for="author_panel_id"><?php _e('ID of author panel page', 'article-directory-redux'); ?>:</label></td>
				<td width="160">
					<input<?php echo $error; ?> name="article_directory[author_panel_id]" type="text" id="author_panel_id" value="<?php echo $aOptions['author_panel_id']; ?>" size="5" maxlength="6" />
				</td>
				<td><?php _e('<strong>Mandatory option.</strong> More about it read in the instructions for installing the plugin.', 'article-directory-redux'); ?> <?php _e('<a href="http://articlesss.com/article-directory-wordpress-plugin/#faq5" target="_blank">How to find this ID.</a>', 'article-directory-redux'); ?></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label><?php _e('Assign the following status to submitted article', 'article-directory-redux'); ?>:</label></td>
				<td>
					<select name="article_directory[article_status]">
						<option value="1"<?php selected('1', $aOptions['article_status']); ?>><?php _e('Published'); ?></option>
						<option value="0"<?php selected('0', $aOptions['article_status']); ?>><?php _e('Pending Review'); ?></option>
					</select>
				</td>
				<td></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label for="minimum_symbols"><?php _e('The minimum number of characters allowed in article', 'article-directory-redux'); ?>:</label></td>
				<td>
					<input name="article_directory[minimum_symbols]" type="text" id="minimum_symbols" value="<?php echo $aOptions['minimum_symbols']; ?>" size="5" maxlength="4" />
				</td>
				<td></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label for="maximum_links"><?php _e('The maximum number of links allowed in article', 'article-directory-redux'); ?>:</label></td>
				<td>
					<input name="article_directory[maximum_links]" type="text" id="maximum_links" value="<?php echo $aOptions['maximum_links']; ?>" size="5" maxlength="2" />
				</td>
				<td></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label><?php _e('Show text editor', 'article-directory-redux'); ?>:</label></td>
				<td>
					<select name="article_directory[show_editor]" id="show_editor">
						<option value="1"<?php selected('1', $aOptions['show_editor']); ?>><?php _e('Yes', 'article-directory-redux'); ?></option>
						<option value="0"<?php selected('0', $aOptions['show_editor']); ?>><?php _e('No', 'article-directory-redux'); ?></option>
					</select>
				</td>
				<td></td>
			</tr>

			<script type="text/javascript">
				(function($) {
					$(function() {
						if ( $('#show_editor').val() == '0' ) $('#show_editor_options').hide();
						$('#show_editor').change(function() { $('#show_editor_options').toggle(); })
					})
				})(jQuery)
			</script>

			<tr valign="top" id="show_editor_options">
				<td scope="row"><label><?php _e('Default text editor', 'article-directory-redux'); ?>:</label></td>
				<td>
					<select name="article_directory[default_editor]">
						<option value="html"<?php selected('html', $aOptions['default_editor']); ?>><?php _e('HTML editor', 'article-directory-redux'); ?></option>
						<option value="tinymce"<?php selected('tinymce', $aOptions['default_editor']); ?>><?php _e('Visual editor', 'article-directory-redux'); ?></option>
					</select>
				</td>
				<td></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label><?php _e('Allow to choose only one category', 'article-directory-redux'); ?>:</label></td>
				<td>
					<select name="article_directory[sel_only_one_cat]">
						<option value="1"<?php selected('1', $aOptions['sel_only_one_cat']); ?>><?php _e('Yes', 'article-directory-redux'); ?></option>
						<option value="0"<?php selected('0', $aOptions['sel_only_one_cat']); ?>><?php _e('No', 'article-directory-redux'); ?></option>
					</select>
				</td>
				<td><?php _e('Recommended to publish an article in only one category for the prevention of duplicate content. This option would avoid the publication in more than one category.', 'article-directory-redux'); ?></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label><?php _e('Show list of tags', 'article-directory-redux'); ?>:</label></td>
				<td>
					<select name="article_directory[show_tags]">
						<option value="1"<?php selected('1', $aOptions['show_tags']); ?>><?php _e('Yes', 'article-directory-redux'); ?></option>
						<option value="0"<?php selected('0', $aOptions['show_tags']); ?>><?php _e('No', 'article-directory-redux'); ?></option>
					</select>
				</td>
				<td></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label><?php _e('Allow to add new tags', 'article-directory-redux'); ?>:</label></td>
				<td>
					<select name="article_directory[allow_new_tags]">
						<option value="1"<?php selected('1', $aOptions['allow_new_tags']); ?>><?php _e('Yes', 'article-directory-redux'); ?></option>
						<option value="0"<?php selected('0', $aOptions['allow_new_tags']); ?>><?php _e('No', 'article-directory-redux'); ?></option>
					</select>
				</td>
				<td></td>
			</tr>

			<tr valign="top">
				<td scope="row"><label for="publish_terms_text"><?php _e('Terms of article publication', 'article-directory-redux') ?>:</label></td>
				<td colspan="2">
					<table width="100%" style="border-collapse: collapse;">
						<tr valign="top">
							<td style="border:none; padding: 0 10px 0 0"><textarea style="font: 11px/13px Arial, Tahoma, Arial; width: 400px; height: 200px;" name="article_directory[publish_terms_text]" id="publish_terms_text"><?php echo $aOptions['publish_terms_text']; ?></textarea></td>
							<td style="border:none; padding: 0"><?php _e('The terms appear before the article submission form. You can use html tags for text formatting, for example, <code>&lt;p&gt;, &lt;ul&gt;, &lt;strong&gt;, &lt;a&gt;</code>. Leave this field blank, if you don\'t want to show the terms.', 'article-directory-redux'); ?></td>
						</tr>
					</table>
				</td>
			</tr>

		</table>

	</div><!-- .inside -->

</div><!-- .postbox -->

<div class="postbox">

	<h3><?php _e('Other Options', 'article-directory-redux'); ?></h3>

	<div class="inside">

		<table class="form-table">

			<tr valign="top">
				<td scope="row" style="width: 360px"><label><?php _e('Exclude the child categories articles from the parent categories pages', 'article-directory-redux'); ?>:</label></td>
				<td width="40">
					<select name="article_directory[kinderloss]">
						<option value="1"<?php selected('1', $aOptions['kinderloss']); ?>><?php _e('Yes', 'article-directory-redux'); ?></option>
						<option value="0"<?php selected('0', $aOptions['kinderloss']); ?>><?php _e('No', 'article-directory-redux'); ?></option>
					</select>
				</td>
				<td></td>
			</tr>

			<tr valign="top">
				<td scope="row" style="width: 360px"><label><?php _e('Show article source code', 'article-directory-redux'); ?>:</label></td>
				<td>
					<select name="article_directory[show_article_code]">
						<option value="1"<?php selected('1', $aOptions['show_article_code']); ?>><?php _e('Yes', 'article-directory-redux'); ?></option>
						<option value="0"<?php selected('0', $aOptions['show_article_code']); ?>><?php _e('No', 'article-directory-redux'); ?></option>
					</select>
				</td>
				<td><?php _e('Appears on article page.', 'article-directory-redux'); ?></td>
			</tr>

		</table>

	</div><!-- .inside -->

</div><!-- .postbox -->

<p><input type="submit" class="button-primary" value="<?php _e('Update Options', 'article-directory-redux') ?>" style="font-weight:bold;" /><br><br></p>
<p><input type="submit" name="artdirReset" class="button-primary" value=" <?php _e('Reset Defaults', 'article-directory-redux') ?> " /><br><br></p>

<div class="postbox">

	<h3><?php _e('Copyright', 'article-directory-redux'); ?></h3>

	<div class="inside">

		<p>&copy; 2008-<?php echo date('Y'); ?> <a href="<?php _e('http://dimox.net', 'article-directory-redux') ?>">Dimox</a> | <a href="<?php _e('http://articlesss.com/article-directory-wordpress-plugin/', 'article-directory-redux') ?>">Article Directory</a> | <?php _e('version', 'article-directory-redux') ?> <?php echo $sVersion; ?></p>

	</div><!-- .inside -->

</div><!-- .postbox -->

</div><!-- #poststuff -->

</form>

</div><!-- .wrap -->
