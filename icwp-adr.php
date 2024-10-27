<?php
/*
Plugin Name: Article Directory Redux
Plugin URI: http://icwp.io/home
Description: An update to the original Article Directory plugin, but for WordPress 3.9 compatibility.
Version: 1.0.2
Author: iControlWP
Author URI: http://www.icontrolwp.com/
*/

class ICWP_ArticleDirectoryRedux {

	const PluginVersion = '1.0.2';

	/**
	 * @var string
	 */
	const PluginTextDomain				= 'article-directory-redux';
	/**
	 * @var string
	 */
	const OptionStorageKey				= 'icwp_article_directory_redux';
	/**
	 * @var string
	 */
	const OptionStorageKey_Old			= 'article_directory';

	/**
	 * @var ICWP_ArticleDirectoryRedux
	 */
	protected static $oInstance = NULL;
	/**
	 * @var string
	 */
	protected $sPluginRootFile;
	/**
	 * @var string
	 */
	protected $sPluginFile;
	/**
	 * @var array
	 */
	protected $aOptions;

	/**
	 * @return ICWP_ArticleDirectoryRedux
	 */
	public static function & GetInstance() {
		if ( is_null( self::$oInstance ) ) {
			self::$oInstance = new ICWP_ArticleDirectoryRedux();
		}
		return self::$oInstance;
	}

	public function run() {
		$this->sPluginRootFile = __FILE__; //ensure all relative paths etc. are setup.
		$this->sPluginFile	= plugin_basename( $this->sPluginRootFile );

		$this->loadOptions();

		if ( strstr( $_SERVER['REQUEST_URI'], 'icwp-adr.php' ) && isset($_GET['ver']) ) {
			echo $this->artdir_get_version();
		}

		add_action( 'wp_loaded', array( $this, 'onWpLoaded' ) );
		add_action( 'admin_init', array( $this, 'artdir_textdomain' ) );
		add_filter( 'pre_kses', array( $this, 'artdir_plugin_description' ) );
		add_action( 'admin_menu', array( $this, 'artdir_options_page' ) );

		if ( $this->isOption('kinderloss', 1) ) {
			add_filter( 'posts_where', array( $this, 'kinderloss_where' ) );
		}

		if ( $this->isOption('show_article_code', 1) ) {
			add_filter( 'the_content', array( $this, 'artdir_get_article_code' ) );
		}

		if ( $this->isOption( 'author_interface', 1 ) ) {
			add_action('init', array( $this, 'artdir_restrict_admin_area' ) );
		}

		add_action( 'update_option', array($this, 'duplicateOptionStoreOnUpdate'), 10, 3 );
		add_action( 'delete_option', array($this, 'duplicateOptionStoreOnDelete') );

		register_deactivation_hook( __FILE__, array( $this, 'artdir_uninstall' ) );
	}

	/**
	 * The primary storage is the original 'article_directory'.  But we're duplicating this in case they deactivate
	 * the old plugin and it deletes their
	 */
	protected function loadOptions() {
		if ( !empty( $this->aOptions ) ) {
			return;
		}

		$this->aOptions = get_option( self::OptionStorageKey_Old );
		if ( !$this->aOptions ) {
			$this->aOptions = get_option( self::OptionStorageKey, $this->aOptions );
		}

		// if it's still empty, set the defaults
		if ( empty($this->aOptions) ) {
			$this->aOptions = $this->artdir_default_options();
			$this->storeOptions();
		}

		if ( is_admin() ) {
			delete_option('article_directory_redux'); //cleanup - this is the old key which is now replaced.
		}
	}

	/**
	 * TODO: This is hooked for future use - we'll come back around and use the new storage value later.
	 *
	 * @param $sOptionKey
	 * @param $mOldValue
	 * @param $mNewValue
	 */
	public function duplicateOptionStoreOnUpdate( $sOptionKey, $mOldValue, $mNewValue ) {
		if ( $sOptionKey == self::OptionStorageKey_Old ) {
			update_option( self::OptionStorageKey, $mNewValue );
		}
	}

	/**
	 * TODO: This is hooked for future use - we'll come back around and use the new storage value later.
	 *
	 * @param $sOptionKey
	 */
	public function duplicateOptionStoreOnDelete( $sOptionKey ) {
		if ( $sOptionKey == self::OptionStorageKey_Old ) {
			$mOptionData = get_option( self::OptionStorageKey_Old );
			update_option( self::OptionStorageKey, $mOptionData );
		}
	}

	/**
	 * @param $sKey
	 * @param string $mComparedValue
	 * @param boolean $fStrict
	 * @return string
	 */
	protected function isOption( $sKey, $mComparedValue, $fStrict = false ) {
		return $fStrict? ($this->getOption( $sKey ) === $mComparedValue) : ($this->getOption( $sKey ) == $mComparedValue);
	}

	/**
	 * @param $sKey
	 * @param string $mDefault
	 * @return string
	 */
	protected function getOption( $sKey, $mDefault = '' ) {
		$this->loadOptions();
		return isset( $this->aOptions[$sKey] )? $this->aOptions[$sKey] : $mDefault;
	}

	/**
	 */
	protected function storeOptions() {
		$this->loadOptions();
		update_option( self::OptionStorageKey_Old, $this->aOptions );
	}

	protected function getAuthorPanelPageId() {
		$nId = $this->getOption('author_panel_id');
		return empty( $nId )? -1 : $nId;
	}

	public function onWpLoaded() {
		if (  is_page( $this->getAuthorPanelPageId() ) || ( $this->isOption('show_article_code', 1) && is_single() ) ) {
			add_action( 'wp_head', array( $this, 'artdir_jquery' ), 8 );
		}
	}

	/**
	 * @return string
	 */
	public function artdir_get_version() {
		return self::PluginVersion;
	}

	public function artdir_textdomain() {
		load_plugin_textdomain( self::PluginTextDomain, false, dirname($this->sPluginFile) . '/languages/' );
		register_setting('article_directory', 'article_directory', array( $this, 'artdir_validate' ) );
	}

	public function artdir_plugin_description($string) {
		if (trim($string) == 'Displays the structured list of categories (like in article directory), which can be easily customized with CSS. Also allows authors to publish articles and change their profile bypassing the admin interface.')
			$string = __('Displays the structured list of categories (like in article directory), which can be easily customized with CSS. Also allows authors to publish articles and change their profile bypassing the admin interface. See the demo at <a href="http://articlesss.com/demo/">articlesss.com</a>. <strong>Attention!</strong> If you deactivate the plugin its settings will be removed from the database.', 'article-directory-redux');
		return $string;
	}

	public function artdir_validate($input) {
		$def_options = $this->artdir_default_options();
		$input['exclude_cats']          = (preg_match("/^(\d+,)*\d+$/", $input['exclude_cats']) ? $input['exclude_cats'] : $def_options['exclude_cats']);
		$input['show_parent_count']     = ($input['show_parent_count'] == 1 ? 1 : 0);
		$input['show_child_count']      = ($input['show_child_count'] == 1 ? 1 : 0);
		$input['hide_empty']            = ($input['hide_empty'] == 1 ? 1 : 0);
		$input['desc_for_parent_title'] = ($input['desc_for_parent_title'] == 1 ? 1 : 0);
		$input['desc_for_child_title']  = ($input['desc_for_child_title'] == 1 ? 1 : 0);
		$input['child_hierarchical']    = ($input['child_hierarchical'] == 1 ? 1 : 0);
		$input['column_count']          = (is_numeric($input['column_count']) && $input['column_count'] > 0 ? $input['column_count'] : $def_options['column_count']);
		$input['sort_by']               = ($input['sort_by'] == 1 ? 1 : 0);
		$input['sort_direction']        = ($input['sort_direction'] == 1 ? 1 : 0);
		$input['no_child_alert']        = ($input['no_child_alert'] == 1 ? 1 : 0);
		$input['show_child']            = ($input['show_child'] == 1 ? 1 : 0);
		$input['maximum_child']         = (is_numeric($input['maximum_child']) && $input['maximum_child'] > 0 ? $input['maximum_child'] : $def_options['maximum_child']);
		$input['author_interface']      = ($input['author_interface'] == 1 ? 1 : 0);
		$input['author_panel_id']       = (!empty($input['author_panel_id']) && $input['author_panel_id'] > 0 && is_numeric($input['author_panel_id']) ? $input['author_panel_id'] : '');
		$input['article_status']        = ($input['article_status'] == 1 ? 1 : 0);
		$input['minimum_symbols']       = (!empty($input['minimum_symbols']) && $input['minimum_symbols'] > 0 && is_numeric($input['minimum_symbols']) ? $input['minimum_symbols'] : $def_options['minimum_symbols']);
		$input['maximum_links']         = (!empty($input['maximum_links']) && $input['maximum_links'] >= 0 && is_numeric($input['maximum_links']) ? $input['maximum_links'] : $def_options['maximum_links']);
		$input['show_editor']           = ($input['show_editor'] == 1 ? 1 : 0);
		$input['default_editor']        = ($input['default_editor'] == 'tinymce' ? 'tinymce' : $def_options['default_editor']);
		$input['sel_only_one_cat']      = ($input['sel_only_one_cat'] == 1 ? 1 : 0);
		$input['show_tags']             = ($input['show_tags'] == 1 ? 1 : 0);
		$input['allow_new_tags']        = ($input['allow_new_tags'] == 1 ? 1 : 0);
		$input['publish_terms_text']    = (!empty($input['publish_terms_text']) ? $input['publish_terms_text'] : '');
		$input['kinderloss']            = ($input['kinderloss'] == 1 ? 1 : 0);
		$input['show_article_code']     = ($input['show_article_code'] == 1 ? 1 : 0);
		if (isset($_POST['artdirReset'])) 	{
			$input = $this->artdir_default_options();
		}
		return $input;
	}

	public function artdir_default_options() {
		$def_options['exclude_cats'] = 0;
		$def_options['show_parent_count'] = 1;
		$def_options['show_child_count'] = 1;
		$def_options['hide_empty'] = 0;
		$def_options['desc_for_parent_title'] = 1;
		$def_options['desc_for_child_title'] = 1;
		$def_options['child_hierarchical'] = 1;
		$def_options['column_count'] = 3;
		$def_options['sort_by'] = 0;
		$def_options['sort_direction'] = 0;
		$def_options['no_child_alert'] = 1;
		$def_options['show_child'] = 1;
		$def_options['maximum_child'] = 0;
		$def_options['author_interface'] = 0;
		$def_options['author_panel_id'] = '';
		$def_options['article_status'] = 0;
		$def_options['minimum_symbols'] = 700;
		$def_options['maximum_links'] = 3;
		$def_options['show_editor'] = 1;
		$def_options['default_editor'] = 'html';
		$def_options['sel_only_one_cat'] = 1;
		$def_options['show_tags'] = 0;
		$def_options['allow_new_tags'] = 0;
		$def_options['publish_terms_text'] = '';
		$def_options['kinderloss'] = 1;
		$def_options['show_article_code'] = 0;
		return $def_options;
	}

	public function artdir_options_page() {
		add_options_page('Article Directory', 'Article Directory Redux', 8, __FILE__, array( $this, 'artdir_options' ) );
	}

	public function artdir_options() {
		$aOptions = $this->aOptions;
		$sVersion = $this->artdir_get_version();
		include( dirname(__FILE__).'/adr-options-page-view.php' );
	}

	public function printArticleDirectory( $echo = true ) {
		include( dirname(__FILE__).'/adr-article-directory-page-view.php' );
	}

	public function printAuthorPanel() {
		$aOptions = $this->aOptions;
		include( dirname(__FILE__).'/author-panel.php' );
	}

	public function printArticleDirectoryAuthForm() {
		include( dirname(__FILE__).'/adr-directory-authorization-form-view.php' );
	}

	public function declareDuplicateFunctions() {

		if ( !function_exists('article_directory') ) {
			function article_directory( $echo = true ) {
				$oAD = ICWP_ArticleDirectoryRedux::GetInstance();
				$oAD->printArticleDirectory( $echo );
			}
		}

		if ( !function_exists('article_directory_author_panel') ) {
			function article_directory_author_panel() {
				$oAD = ICWP_ArticleDirectoryRedux::GetInstance();
				$oAD->printAuthorPanel();
			}
		}

		if ( !function_exists('article_directory_authorization_form') ) {
			function article_directory_authorization_form() {
				$oAD = ICWP_ArticleDirectoryRedux::GetInstance();
				$oAD->printArticleDirectoryAuthForm();
			}
		}
	}

	//thanks to "Kinderlose" plugin - http://guff.szub.net/kinderlose
	public function kinderloss_where( $where ) {
		if ( is_category() ) {
			global $wp_query;
			$where = preg_replace('/.term_id IN \(\'(.*)\'\)/', '.term_id IN (\'' . $wp_query->query_vars['cat'] . '\') AND post_type = \'post\' AND post_status = \'publish\'', $where);
		}

		return $where;
	}

	public function artdir_get_article_code($text) {
		$rn = "\r\n\r\n";
		$get_article_code = '
<script type="text/javascript">
(function($) {
$(function() {
	$("#getArticleCode").css({opacity: 0}).hide();
	$("#getArticleSource").toggle(
		function() { $("#getArticleCode").animate({opacity: 1}, 300).show(); return false; },
		function() { $("#getArticleCode").animate({opacity: 0}).hide();	return false;	}
	);
	$("#htmlVersion").text("<h1>' . get_the_title() . '</h1>" + "\r\n" + $("#artdirPost").html() + "<p>' . __('Source') . ': <a href=\"' . get_permalink() . '\">' . get_permalink() . '</a></p>");
	$("#textVersion").text("' . get_the_title() . '" + "\r\n\r\n" + $("#artdirPost").text() + "\r\n" + "' . __('Source') . ': ' . get_permalink() . '");
	$("#getArticleCode textarea, #getArticleCode input").click(function() { $(this).select() });
})
})(jQuery)
</script>
<p><a href="#" id="getArticleSource">'.__('Article Source', 'article-directory-redux').'</a></p>
<div id="getArticleCode" style="display:none">
	<label>' . __('HTML Version', 'article-directory-redux') . ':</label>
	<textarea id="htmlVersion" rows="15" cols="50"></textarea>
	<label>' . __('Text Version', 'article-directory-redux') . ':</label>
	<textarea id="textVersion" rows="15" cols="50"></textarea>
	<label>' . __('Article Url', 'article-directory-redux') . ':</label>
	<input type="text" value="' . get_permalink() . '" />
</div>
		';
		if (is_single()) {
			return '<div id="artdirPost">' . $text . '</div>' . $get_article_code;
		} else {
			return $text;
		}
	}

	public function artdir_jquery() {
		wp_deregister_script('jquery');
		wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"), false, '1.4.2');
		wp_enqueue_script('jquery');
	}

	public function artdir_restrict_admin_area() {
		if (strpos($_SERVER['SCRIPT_NAME'], 'wp-admin')) {
			if ( is_user_logged_in() && !current_user_can('level_7') ) {
				wp_redirect( get_permalink( $this->aOptions['author_panel_id'] ) );
				?>
				<!DOCTYPE html>
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta charset="utf-8" />
					<title><?php _e('WordPress &rsaquo; Error'); ?></title>
				</head>
				<body style="background:#F9F9F9">
				<div style="background:#FFF;color:#333;font:12px/18px 'Lucida Grande',Verdana,Arial,'Bitstream Vera Sans',sans-serif;margin:50px auto;width:700px;padding:18px 32px;-moz-border-radius:11px;-webkit-border-radius:11px;border-radius:11px;border:1px solid #DFDFDF">
					<h1 style="font-size: 14px"><?php _e('Error', 'article-directory-redux'); ?></h1>
					<p><?php _e('Unfortunately, you can not get into the author panel, because the site admin does not <a href="http://articlesss.com/article-directory-wordpress-plugin/#installation" target="_blank">set it up</a>.', 'article-directory-redux') ?></p>
					<p>&raquo; <a href="<?php echo wp_logout_url(get_bloginfo('wpurl')); ?>"><?php _e('Log out') ?></a></p>
				</div>
				</body>
				</html>
				<?php
				die();
			}
		}
	}

	public function artdir_uninstall() {
//		delete_option('article_directory');
	}

}

$oICWP_ADR = ICWP_ArticleDirectoryRedux::GetInstance();
$oICWP_ADR->run();

add_action( 'plugins_loaded', array( $oICWP_ADR, 'declareDuplicateFunctions') );
