<?php
if ( !defined( 'MEDIAWIKI' ) ) {
	die( 1 );
}

/**
 * This class should be covered by a general architecture document which does
 * not exist as of january 2011.  This is one of the Core class and should
 * be read at least once by any new developers.
 *
 * This class is used to prepare the final rendering. A skin is then
 * applied to the output parameters (links, javascript, html, categories ...).
 * 
 * Another class (fixme) handle sending the whole page to the client.
 * 
 * Some comments comes from a pairing session between Zak Greant and Ashar Voultoiz
 * in november 2010.
 *
 * @todo document
 */
class OutputPage {
	/// Should be private. Used with addMeta() which adds <meta>
	var $mMetatags = array();

	/// <meta keyworkds="stuff"> most of the time the first 10 links to an article
	var $mKeywords = array();

	var $mLinktags = array();

	/// Additional stylesheets. Looks like this is for extensions. Might be replaced by resource loader.
	var $mExtStyles = array();

	/// Should be private - has getter and setter. Contains the HTML title
	var $mPagetitle = '';

	/// Contains all of the <body> content. Should be private we got set/get accessors and the append() method.
	var $mBodytext = '';

	/**
	 * Holds the debug lines that will be outputted as comments in page source if
	 * $wgDebugComments is enabled. See also $wgShowDebug.
	 * TODO: make a getter method for this
	 */
	public $mDebugtext = ''; // TODO: we might want to replace it by wfDebug() wfDebugLog()

	/// Should be private. Stores contents of <title> tag
	var $mHTMLtitle = '';

	/// Should be private. Is the displayed content related to the source of the corresponding wiki article.
	var $mIsarticle = true;

	/**
	 * Should be private. We have to set isPrintable(). Some pages should
	 * never be printed (ex: redirections).
	 */
	var $mPrintable = false;

	/**
	 * Should be private. We have set/get/append methods.
	 *
	 * Contains the page subtitle. Special pages usually have some links here.
	 * Don't confuse with site subtitle added by skins.
	 */
	var $mSubtitle = '';

	var $mRedirect = '';
	var $mStatusCode;

	/**
	 * mLastModified and mEtag are used for sending cache control.
	 * The whole caching system should probably be moved in its own class.
	 */
	var $mLastModified = '';

	/**
	 * Should be private. No getter but used in sendCacheControl();
	 * Contains an HTTP Entity Tags (see RFC 2616 section 3.13) which is used
	 * as a unique identifier for the content. It is later used by the client
	 * to compare its cache version with the server version. Client sends
	 * headers If-Match and If-None-Match containing its local cache ETAG value.
	 *
	 * To get more information, you will have to look at HTTP1/1 protocols which
	 * is properly described in RFC 2616 : http://tools.ietf.org/html/rfc2616
	 */
	var $mETag = false;

	var $mCategoryLinks = array();
	var $mCategories = array();

	/// Should be private. Array of Interwiki Prefixed (non DB key) Titles (e.g. 'fr:Test page')
	var $mLanguageLinks = array();

	/**
	 * Should be private. Used for JavaScript (pre resource loader)
	 * We should split js / css.
	 * mScripts content is inserted as is in <head> by Skin. This might contains
	 * either a link to a stylesheet or inline css.
	 */
	var $mScripts = '';

	/**
	 * Inline CSS styles. Use addInlineStyle() sparsingly
	 */
	var $mInlineStyles = '';

	//
	var $mLinkColours;

	/**
	 * Used by skin template.
	 * Example: $tpl->set( 'displaytitle', $out->mPageLinkTitle );
	 */
	var $mPageLinkTitle = '';

	/// Array of elements in <head>. Parser might add its own headers!
	var $mHeadItems = array();

	// Next variables probably comes from the resource loader @todo FIXME
	var $mModules = array(), $mModuleScripts = array(), $mModuleStyles = array(), $mModuleMessages = array();
	var $mResourceLoader;

	/** @fixme is this still used ?*/
	var $mInlineMsg = array();

	var $mTemplateIds = array();

	# What level of 'untrustworthiness' is allowed in CSS/JS modules loaded on this page?
	# @see ResourceLoaderModule::$origin
	# ResourceLoaderModule::ORIGIN_ALL is assumed unless overridden;
	protected $mAllowedModules = array(
		ResourceLoaderModule::TYPE_COMBINED => ResourceLoaderModule::ORIGIN_ALL,
	);

	/**
	 * @EasterEgg I just love the name for this self documenting variable.
	 * @todo document
	 */
	var $mDoNothing = false;

	// Parser related.
	var $mContainsOldMagic = 0, $mContainsNewMagic = 0;

	/**
	 * Should be private. Has get/set methods properly documented.
	 * Stores "article flag" toggle.
	 */
	var $mIsArticleRelated = true;

	/// lazy initialised, use parserOptions()
	protected $mParserOptions = null;

	/**
	 * Handles the atom / rss links.
	 * We probably only support atom in 2011.
	 * Looks like a private variable.
	 * @see $wgAdvertisedFeedTypes
	 */
	var $mFeedLinks = array();

	// Gwicke work on squid caching? Roughly from 2003.
	var $mEnableClientCache = true;

	/**
	 * Flag if output should only contain the body of the article.
	 * Should be private.
	 */
	var $mArticleBodyOnly = false;

	var $mNewSectionLink = false;
	var $mHideNewSectionLink = false;

	/**
	 * Comes from the parser. This was probably made to load CSS/JS only
	 * if we had <gallery>. Used directly in CategoryPage.php
	 * Looks like resource loader can replace this.
	 */
	var $mNoGallery = false;

	// should be private.
	var $mPageTitleActionText = '';
	var $mParseWarnings = array();

	// Cache stuff. Looks like mEnableClientCache
	var $mSquidMaxage = 0;

	// @todo document
	var $mPreventClickjacking = true;

	/// should be private. To include the variable {{REVISIONID}}
	var $mRevisionId = null;

	/// Stores a Title object (of the current page).
	protected $mTitle = null;

	/**
	 * An array of stylesheet filenames (relative from skins path), with options
	 * for CSS media, IE conditions, and RTL/LTR direction.
	 * For internal use; add settings in the skin via $this->addStyle()
	 *
	 * Style again! This seems like a code duplication since we already have
	 * mStyles. This is what makes OpenSource amazing.
	 */
	var $styles = array();

	/**
	 * Whether jQuery is already handled.
	 */
	protected $mJQueryDone = false;

	private $mIndexPolicy = 'index';
	private $mFollowPolicy = 'follow';
	private $mVaryHeader = array(
		'Accept-Encoding' => array( 'list-contains=gzip' ),
		'Cookie' => null
	);

	/**
	 * Redirect to $url rather than displaying the normal page
	 *
	 * @param $url String: URL
	 * @param $responsecode String: HTTP status code
	 */
	public function redirect( $url, $responsecode = '302' ) {
		# Strip newlines as a paranoia check for header injection in PHP<5.1.2
		$this->mRedirect = str_replace( "\n", '', $url );
		$this->mRedirectCode = $responsecode;
	}

	/**
	 * Get the URL to redirect to, or an empty string if not redirect URL set
	 *
	 * @return String
	 */
	public function getRedirect() {
		return $this->mRedirect;
	}

	/**
	 * Set the HTTP status code to send with the output.
	 *
	 * @param $statusCode Integer
	 */
	public function setStatusCode( $statusCode ) {
		$this->mStatusCode = $statusCode;
	}

	/**
	 * Add a new <meta> tag
	 * To add an http-equiv meta tag, precede the name with "http:"
	 *
	 * @param $name String tag name
	 * @param $val String tag value
	 */
	function addMeta( $name, $val ) {
		array_push( $this->mMetatags, array( $name, $val ) );
	}

	/**
	 * Add a keyword or a list of keywords in the page header
	 *
	 * @param $text String or array of strings
	 */
	function addKeyword( $text ) {
		if( is_array( $text ) ) {
			$this->mKeywords = array_merge( $this->mKeywords, $text );
		} else {
			array_push( $this->mKeywords, $text );
		}
	}

	/**
	 * Add a new \<link\> tag to the page header
	 *
	 * @param $linkarr Array: associative array of attributes.
	 */
	function addLink( $linkarr ) {
		array_push( $this->mLinktags, $linkarr );
	}

	/**
	 * Add a new \<link\> with "rel" attribute set to "meta"
	 *
	 * @param $linkarr Array: associative array mapping attribute names to their
	 *                 values, both keys and values will be escaped, and the
	 *                 "rel" attribute will be automatically added
	 */
	function addMetadataLink( $linkarr ) {
		# note: buggy CC software only reads first "meta" link
		static $haveMeta = false;
		$linkarr['rel'] = $haveMeta ? 'alternate meta' : 'meta';
		$this->addLink( $linkarr );
		$haveMeta = true;
	}

	/**
	 * Add raw HTML to the list of scripts (including \<script\> tag, etc.)
	 *
	 * @param $script String: raw HTML
	 */
	function addScript( $script ) {
		$this->mScripts .= $script . "\n";
	}

	/**
	 * Register and add a stylesheet from an extension directory.
	 *
	 * @param $url String path to sheet.  Provide either a full url (beginning
	 *             with 'http', etc) or a relative path from the document root
	 *             (beginning with '/').  Otherwise it behaves identically to
	 *             addStyle() and draws from the /skins folder.
	 */
	public function addExtensionStyle( $url ) {
		array_push( $this->mExtStyles, $url );
	}

	/**
	 * Get all styles added by extensions
	 *
	 * @return Array
	 */
	function getExtStyle() {
		return $this->mExtStyles;
	}

	/**
	 * Add a JavaScript file out of skins/common, or a given relative path.
	 *
	 * @param $file String: filename in skins/common or complete on-server path
	 *              (/foo/bar.js)
	 * @param $version String: style version of the file. Defaults to $wgStyleVersion
	 */
	public function addScriptFile( $file, $version = null ) {
		global $wgStylePath, $wgStyleVersion;
		// See if $file parameter is an absolute URL or begins with a slash
		if( substr( $file, 0, 1 ) == '/' || preg_match( '#^[a-z]*://#i', $file ) ) {
			$path = $file;
		} else {
			$path = "{$wgStylePath}/common/{$file}";
		}
		if ( is_null( $version ) )
			$version = $wgStyleVersion;
		$this->addScript( Html::linkedScript( wfAppendQuery( $path, $version ) ) );
	}

	/**
	 * Add a self-contained script tag with the given contents
	 *
	 * @param $script String: JavaScript text, no <script> tags
	 */
	public function addInlineScript( $script ) {
		$this->mScripts .= Html::inlineScript( "\n$script\n" ) . "\n";
	}

	/**
	 * Get all registered JS and CSS tags for the header.
	 *
	 * @return String
	 */
	function getScript() {
		return $this->mScripts . $this->getHeadItems();
	}

	/**
	 * Filter an array of modules to remove insufficiently trustworthy members
	 * @param $modules Array
	 * @return Array
	 */
	protected function filterModules( $modules, $type = ResourceLoaderModule::TYPE_COMBINED ){
		$resourceLoader = $this->getResourceLoader();
		$filteredModules = array();
		foreach( $modules as $val ){
			$module = $resourceLoader->getModule( $val );
			if( $module->getOrigin() <= $this->getAllowedModules( $type ) ) {
				$filteredModules[] = $val;
			}
		}
		return $filteredModules;
	}

	/**
	 * Get the list of modules to include on this page
	 *
	 * @param $filter Bool whether to filter out insufficiently trustworthy modules
	 * @return Array of module names
	 */
	public function getModules( $filter = false, $param = 'mModules' ) {
		$modules = array_values( array_unique( $this->$param ) );
		return $filter
			? $this->filterModules( $modules )
			: $modules;
	}

	/**
	 * Add one or more modules recognized by the resource loader. Modules added
	 * through this function will be loaded by the resource loader when the
	 * page loads.
	 *
	 * @param $modules Mixed: module name (string) or array of module names
	 */
	public function addModules( $modules ) {
		$this->mModules = array_merge( $this->mModules, (array)$modules );
	}

	/**
	 * Get the list of module JS to include on this page
	 * @return array of module names
	 */
	public function getModuleScripts( $filter = false ) {
		return $this->getModules( $filter, 'mModuleScripts' );
	}

	/**
	 * Add only JS of one or more modules recognized by the resource loader. Module
	 * scripts added through this function will be loaded by the resource loader when
	 * the page loads.
	 *
	 * @param $modules Mixed: module name (string) or array of module names
	 */
	public function addModuleScripts( $modules ) {
		$this->mModuleScripts = array_merge( $this->mModuleScripts, (array)$modules );
	}

	/**
	 * Get the list of module CSS to include on this page
	 *
	 * @return Array of module names
	 */
	public function getModuleStyles( $filter = false ) {
		return $this->getModules( $filter, 'mModuleStyles' );
	}

	/**
	 * Add only CSS of one or more modules recognized by the resource loader. Module
	 * styles added through this function will be loaded by the resource loader when
	 * the page loads.
	 *
	 * @param $modules Mixed: module name (string) or array of module names
	 */
	public function addModuleStyles( $modules ) {
		$this->mModuleStyles = array_merge( $this->mModuleStyles, (array)$modules );
	}

	/**
	 * Get the list of module messages to include on this page
	 *
	 * @return Array of module names
	 */
	public function getModuleMessages( $filter = false ) {
		return $this->getModules( $filter, 'mModuleMessages' );
	}

	/**
	 * Add only messages of one or more modules recognized by the resource loader.
	 * Module messages added through this function will be loaded by the resource
	 * loader when the page loads.
	 *
	 * @param $modules Mixed: module name (string) or array of module names
	 */
	public function addModuleMessages( $modules ) {
		$this->mModuleMessages = array_merge( $this->mModuleMessages, (array)$modules );
	}

	/**
	 * Get all header items in a string
	 *
	 * @return String
	 */
	function getHeadItems() {
		$s = '';
		foreach ( $this->mHeadItems as $item ) {
			$s .= $item;
		}
		return $s;
	}

	/**
	 * Add or replace an header item to the output
	 *
	 * @param $name String: item name
	 * @param $value String: raw HTML
	 */
	public function addHeadItem( $name, $value ) {
		$this->mHeadItems[$name] = $value;
	}

	/**
	 * Check if the header item $name is already set
	 *
	 * @param $name String: item name
	 * @return Boolean
	 */
	public function hasHeadItem( $name ) {
		return isset( $this->mHeadItems[$name] );
	}

	/**
	 * Set the value of the ETag HTTP header, only used if $wgUseETag is true
	 *
	 * @param $tag String: value of "ETag" header
	 */
	function setETag( $tag ) {
		$this->mETag = $tag;
	}

	/**
	 * Set whether the output should only contain the body of the article,
	 * without any skin, sidebar, etc.
	 * Used e.g. when calling with "action=render".
	 *
	 * @param $only Boolean: whether to output only the body of the article
	 */
	public function setArticleBodyOnly( $only ) {
		$this->mArticleBodyOnly = $only;
	}

	/**
	 * Return whether the output will contain only the body of the article
	 *
	 * @return Boolean
	 */
	public function getArticleBodyOnly() {
		return $this->mArticleBodyOnly;
	}

	/**
	 * checkLastModified tells the client to use the client-cached page if
	 * possible. If sucessful, the OutputPage is disabled so that
	 * any future call to OutputPage->output() have no effect.
	 *
	 * Side effect: sets mLastModified for Last-Modified header
	 *
	 * @return Boolean: true iff cache-ok headers was sent.
	 */
	public function checkLastModified( $timestamp ) {
		global $wgCachePages, $wgCacheEpoch, $wgUser, $wgRequest;

		if ( !$timestamp || $timestamp == '19700101000000' ) {
			wfDebug( __METHOD__ . ": CACHE DISABLED, NO TIMESTAMP\n" );
			return false;
		}
		if( !$wgCachePages ) {
			wfDebug( __METHOD__ . ": CACHE DISABLED\n", false );
			return false;
		}
		if( $wgUser->getOption( 'nocache' ) ) {
			wfDebug( __METHOD__ . ": USER DISABLED CACHE\n", false );
			return false;
		}

		$timestamp = wfTimestamp( TS_MW, $timestamp );
		$modifiedTimes = array(
			'page' => $timestamp,
			'user' => $wgUser->getTouched(),
			'epoch' => $wgCacheEpoch
		);
		wfRunHooks( 'OutputPageCheckLastModified', array( &$modifiedTimes ) );

		$maxModified = max( $modifiedTimes );
		$this->mLastModified = wfTimestamp( TS_RFC2822, $maxModified );

		if( empty( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) {
			wfDebug( __METHOD__ . ": client did not send If-Modified-Since header\n", false );
			return false;
		}

		# Make debug info
		$info = '';
		foreach ( $modifiedTimes as $name => $value ) {
			if ( $info !== '' ) {
				$info .= ', ';
			}
			$info .= "$name=" . wfTimestamp( TS_ISO_8601, $value );
		}

		# IE sends sizes after the date like this:
		# Wed, 20 Aug 2003 06:51:19 GMT; length=5202
		# this breaks strtotime().
		$clientHeader = preg_replace( '/;.*$/', '', $_SERVER["HTTP_IF_MODIFIED_SINCE"] );

		wfSuppressWarnings(); // E_STRICT system time bitching
		$clientHeaderTime = strtotime( $clientHeader );
		wfRestoreWarnings();
		if ( !$clientHeaderTime ) {
			wfDebug( __METHOD__ . ": unable to parse the client's If-Modified-Since header: $clientHeader\n" );
			return false;
		}
		$clientHeaderTime = wfTimestamp( TS_MW, $clientHeaderTime );

		wfDebug( __METHOD__ . ": client sent If-Modified-Since: " .
			wfTimestamp( TS_ISO_8601, $clientHeaderTime ) . "\n", false );
		wfDebug( __METHOD__ . ": effective Last-Modified: " .
			wfTimestamp( TS_ISO_8601, $maxModified ) . "\n", false );
		if( $clientHeaderTime < $maxModified ) {
			wfDebug( __METHOD__ . ": STALE, $info\n", false );
			return false;
		}

		# Not modified
		# Give a 304 response code and disable body output
		wfDebug( __METHOD__ . ": NOT MODIFIED, $info\n", false );
		ini_set( 'zlib.output_compression', 0 );
		$wgRequest->response()->header( "HTTP/1.1 304 Not Modified" );
		$this->sendCacheControl();
		$this->disable();

		// Don't output a compressed blob when using ob_gzhandler;
		// it's technically against HTTP spec and seems to confuse
		// Firefox when the response gets split over two packets.
		wfClearOutputBuffers();

		return true;
	}

	/**
	 * Override the last modified timestamp
	 *
	 * @param $timestamp String: new timestamp, in a format readable by
	 *        wfTimestamp()
	 */
	public function setLastModified( $timestamp ) {
		$this->mLastModified = wfTimestamp( TS_RFC2822, $timestamp );
	}

	/**
	 * Set the robot policy for the page: <http://www.robotstxt.org/meta.html>
	 *
	 * @param $policy String: the literal string to output as the contents of
	 *   the meta tag.  Will be parsed according to the spec and output in
	 *   standardized form.
	 * @return null
	 */
	public function setRobotPolicy( $policy ) {
		$policy = Article::formatRobotPolicy( $policy );

		if( isset( $policy['index'] ) ) {
			$this->setIndexPolicy( $policy['index'] );
		}
		if( isset( $policy['follow'] ) ) {
			$this->setFollowPolicy( $policy['follow'] );
		}
	}

	/**
	 * Set the index policy for the page, but leave the follow policy un-
	 * touched.
	 *
	 * @param $policy string Either 'index' or 'noindex'.
	 * @return null
	 */
	public function setIndexPolicy( $policy ) {
		$policy = trim( $policy );
		if( in_array( $policy, array( 'index', 'noindex' ) ) ) {
			$this->mIndexPolicy = $policy;
		}
	}

	/**
	 * Set the follow policy for the page, but leave the index policy un-
	 * touched.
	 *
	 * @param $policy String: either 'follow' or 'nofollow'.
	 * @return null
	 */
	public function setFollowPolicy( $policy ) {
		$policy = trim( $policy );
		if( in_array( $policy, array( 'follow', 'nofollow' ) ) ) {
			$this->mFollowPolicy = $policy;
		}
	}

	/**
	 * Set the new value of the "action text", this will be added to the
	 * "HTML title", separated from it with " - ".
	 *
	 * @param $text String: new value of the "action text"
	 */
	public function setPageTitleActionText( $text ) {
		$this->mPageTitleActionText = $text;
	}

	/**
	 * Get the value of the "action text"
	 *
	 * @return String
	 */
	public function getPageTitleActionText() {
		if ( isset( $this->mPageTitleActionText ) ) {
			return $this->mPageTitleActionText;
		}
	}

	/**
	 * "HTML title" means the contents of <title>.
	 * It is stored as plain, unescaped text and will be run through htmlspecialchars in the skin file.
	 */
	public function setHTMLTitle( $name ) {
		$this->mHTMLtitle = $name;
	}

	/**
	 * Return the "HTML title", i.e. the content of the <title> tag.
	 *
	 * @return String
	 */
	public function getHTMLTitle() {
		return $this->mHTMLtitle;
	}

	/**
	 * "Page title" means the contents of \<h1\>. It is stored as a valid HTML fragment.
	 * This function allows good tags like \<sup\> in the \<h1\> tag, but not bad tags like \<script\>.
	 * This function automatically sets \<title\> to the same content as \<h1\> but with all tags removed.
	 * Bad tags that were escaped in \<h1\> will still be escaped in \<title\>, and good tags like \<i\> will be dropped entirely.
	 */
	public function setPageTitle( $name ) {
		# change "<script>foo&bar</script>" to "&lt;script&gt;foo&amp;bar&lt;/script&gt;"
		# but leave "<i>foobar</i>" alone
		$nameWithTags = Sanitizer::normalizeCharReferences( Sanitizer::removeHTMLtags( $name ) );
		$this->mPagetitle = $nameWithTags;

		# change "<i>foo&amp;bar</i>" to "foo&bar"
		$this->setHTMLTitle( wfMsg( 'pagetitle', Sanitizer::stripAllTags( $nameWithTags ) ) );
	}

	/**
	 * Return the "page title", i.e. the content of the \<h1\> tag.
	 *
	 * @return String
	 */
	public function getPageTitle() {
		return $this->mPagetitle;
	}

	/**
	 * Set the Title object to use
	 *
	 * @param $t Title object
	 */
	public function setTitle( $t ) {
		$this->mTitle = $t;
	}

	/**
	 * Get the Title object used in this instance
	 *
	 * @return Title
	 */
	public function getTitle() {
		if ( $this->mTitle instanceof Title ) {
			return $this->mTitle;
		} else {
			wfDebug( __METHOD__ . " called and \$mTitle is null. Return \$wgTitle for sanity\n" );
			global $wgTitle;
			return $wgTitle;
		}
	}

	/**
	 * Replace the subtile with $str
	 *
	 * @param $str String: new value of the subtitle
	 */
	public function setSubtitle( $str ) {
		$this->mSubtitle = /*$this->parse(*/ $str /*)*/; // @bug 2514
	}

	/**
	 * Add $str to the subtitle
	 *
	 * @param $str String to add to the subtitle
	 */
	public function appendSubtitle( $str ) {
		$this->mSubtitle .= /*$this->parse(*/ $str /*)*/; // @bug 2514
	}

	/**
	 * Get the subtitle
	 *
	 * @return String
	 */
	public function getSubtitle() {
		return $this->mSubtitle;
	}

	/**
	 * Set the page as printable, i.e. it'll be displayed with with all
	 * print styles included
	 */
	public function setPrintable() {
		$this->mPrintable = true;
	}

	/**
	 * Return whether the page is "printable"
	 *
	 * @return Boolean
	 */
	public function isPrintable() {
		return $this->mPrintable;
	}

	/**
	 * Disable output completely, i.e. calling output() will have no effect
	 */
	public function disable() {
		$this->mDoNothing = true;
	}

	/**
	 * Return whether the output will be completely disabled
	 *
	 * @return Boolean
	 */
	public function isDisabled() {
		return $this->mDoNothing;
	}

	/**
	 * Show an "add new section" link?
	 *
	 * @return Boolean
	 */
	public function showNewSectionLink() {
		return $this->mNewSectionLink;
	}

	/**
	 * Forcibly hide the new section link?
	 *
	 * @return Boolean
	 */
	public function forceHideNewSectionLink() {
		return $this->mHideNewSectionLink;
	}

	/**
	 * Add or remove feed links in the page header
	 * This is mainly kept for backward compatibility, see OutputPage::addFeedLink()
	 * for the new version
	 * @see addFeedLink()
	 *
	 * @param $show Boolean: true: add default feeds, false: remove all feeds
	 */
	public function setSyndicated( $show = true ) {
		if ( $show ) {
			$this->setFeedAppendQuery( false );
		} else {
			$this->mFeedLinks = array();
		}
	}

	/**
	 * Add default feeds to the page header
	 * This is mainly kept for backward compatibility, see OutputPage::addFeedLink()
	 * for the new version
	 * @see addFeedLink()
	 *
	 * @param $val String: query to append to feed links or false to output
	 *        default links
	 */
	public function setFeedAppendQuery( $val ) {
		global $wgAdvertisedFeedTypes;

		$this->mFeedLinks = array();

		foreach ( $wgAdvertisedFeedTypes as $type ) {
			$query = "feed=$type";
			if ( is_string( $val ) ) {
				$query .= '&' . $val;
			}
			$this->mFeedLinks[$type] = $this->getTitle()->getLocalURL( $query );
		}
	}

	/**
	 * Add a feed link to the page header
	 *
	 * @param $format String: feed type, should be a key of $wgFeedClasses
	 * @param $href String: URL
	 */
	public function addFeedLink( $format, $href ) {
		global $wgAdvertisedFeedTypes;

		if ( in_array( $format, $wgAdvertisedFeedTypes ) ) {
			$this->mFeedLinks[$format] = $href;
		}
	}

	/**
	 * Should we output feed links for this page?
	 * @return Boolean
	 */
	public function isSyndicated() {
		return count( $this->mFeedLinks ) > 0;
	}

	/**
	 * Return URLs for each supported syndication format for this page.
	 * @return array associating format keys with URLs
	 */
	public function getSyndicationLinks() {
		return $this->mFeedLinks;
	}

	/**
	 * Will currently always return null
	 *
	 * @return null
	 */
	public function getFeedAppendQuery() {
		return $this->mFeedLinksAppendQuery;
	}

	/**
	 * Set whether the displayed content is related to the source of the
	 * corresponding article on the wiki
	 * Setting true will cause the change "article related" toggle to true
	 *
	 * @param $v Boolean
	 */
	public function setArticleFlag( $v ) {
		$this->mIsarticle = $v;
		if ( $v ) {
			$this->mIsArticleRelated = $v;
		}
	}

	/**
	 * Return whether the content displayed page is related to the source of
	 * the corresponding article on the wiki
	 *
	 * @return Boolean
	 */
	public function isArticle() {
		return $this->mIsarticle;
	}

	/**
	 * Set whether this page is related an article on the wiki
	 * Setting false will cause the change of "article flag" toggle to false
	 *
	 * @param $v Boolean
	 */
	public function setArticleRelated( $v ) {
		$this->mIsArticleRelated = $v;
		if ( !$v ) {
			$this->mIsarticle = false;
		}
	}

	/**
	 * Return whether this page is related an article on the wiki
	 *
	 * @return Boolean
	 */
	public function isArticleRelated() {
		return $this->mIsArticleRelated;
	}

	/**
	 * Add new language links
	 *
	 * @param $newLinkArray Associative array mapping language code to the page
	 *                      name
	 */
	public function addLanguageLinks( $newLinkArray ) {
		$this->mLanguageLinks += $newLinkArray;
	}

	/**
	 * Reset the language links and add new language links
	 *
	 * @param $newLinkArray Associative array mapping language code to the page
	 *                      name
	 */
	public function setLanguageLinks( $newLinkArray ) {
		$this->mLanguageLinks = $newLinkArray;
	}

	/**
	 * Get the list of language links
	 *
	 * @return Array of Interwiki Prefixed (non DB key) Titles (e.g. 'fr:Test page')
	 */
	public function getLanguageLinks() {
		return $this->mLanguageLinks;
	}

	/**
	 * Add an array of categories, with names in the keys
	 *
	 * @param $categories Array mapping category name => sort key
	 */
	public function addCategoryLinks( $categories ) {
		global $wgUser, $wgContLang;

		if ( !is_array( $categories ) || count( $categories ) == 0 ) {
			return;
		}

		# Add the links to a LinkBatch
		$arr = array( NS_CATEGORY => $categories );
		$lb = new LinkBatch;
		$lb->setArray( $arr );

		# Fetch existence plus the hiddencat property
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( array( 'page', 'page_props' ),
			array( 'page_id', 'page_namespace', 'page_title', 'page_len', 'page_is_redirect', 'page_latest', 'pp_value' ),
			$lb->constructSet( 'page', $dbr ),
			__METHOD__,
			array(),
			array( 'page_props' => array( 'LEFT JOIN', array( 'pp_propname' => 'hiddencat', 'pp_page = page_id' ) ) )
		);

		# Add the results to the link cache
		$lb->addResultToCache( LinkCache::singleton(), $res );

		# Set all the values to 'normal'. This can be done with array_fill_keys in PHP 5.2.0+
		$categories = array_combine(
			array_keys( $categories ),
			array_fill( 0, count( $categories ), 'normal' )
		);

		# Mark hidden categories
		foreach ( $res as $row ) {
			if ( isset( $row->pp_value ) ) {
				$categories[$row->page_title] = 'hidden';
			}
		}

		# Add the remaining categories to the skin
		if ( wfRunHooks( 'OutputPageMakeCategoryLinks', array( &$this, $categories, &$this->mCategoryLinks ) ) ) {
			$sk = $wgUser->getSkin();
			foreach ( $categories as $category => $type ) {
				$origcategory = $category;
				$title = Title::makeTitleSafe( NS_CATEGORY, $category );
				$wgContLang->findVariantLink( $category, $title, true );
				if ( $category != $origcategory ) {
					if ( array_key_exists( $category, $categories ) ) {
						continue;
					}
				}
				$text = $wgContLang->convertHtml( $title->getText() );
				$this->mCategories[] = $title->getText();
				$this->mCategoryLinks[$type][] = $sk->link( $title, $text );
			}
		}
	}

	/**
	 * Reset the category links (but not the category list) and add $categories
	 *
	 * @param $categories Array mapping category name => sort key
	 */
	public function setCategoryLinks( $categories ) {
		$this->mCategoryLinks = array();
		$this->addCategoryLinks( $categories );
	}

	/**
	 * Get the list of category links, in a 2-D array with the following format:
	 * $arr[$type][] = $link, where $type is either "normal" or "hidden" (for
	 * hidden categories) and $link a HTML fragment with a link to the category
	 * page
	 *
	 * @return Array
	 */
	public function getCategoryLinks() {
		return $this->mCategoryLinks;
	}

	/**
	 * Get the list of category names this page belongs to
	 *
	 * @return Array of strings
	 */
	public function getCategories() {
		return $this->mCategories;
	}

	/**
	 * Do not allow scripts which can be modified by wiki users to load on this page;
	 * only allow scripts bundled with, or generated by, the software.
	 */
	public function disallowUserJs() {
		$this->reduceAllowedModules(
			ResourceLoaderModule::TYPE_SCRIPTS,
			ResourceLoaderModule::ORIGIN_CORE_INDIVIDUAL
		);
	}

	/**
	 * Return whether user JavaScript is allowed for this page
	 * @deprecated @since 1.18 Load modules with ResourceLoader, and origin and
	 *     trustworthiness is identified and enforced automagically. 
	 * @return Boolean
	 */
	public function isUserJsAllowed() {
		return $this->getAllowedModules( ResourceLoaderModule::TYPE_SCRIPTS ) >= ResourceLoaderModule::ORIGIN_USER_INDIVIDUAL;
	}

	/**
	 * Show what level of JavaScript / CSS untrustworthiness is allowed on this page
	 * @see ResourceLoaderModule::$origin
	 * @param $type String ResourceLoaderModule TYPE_ constant
	 * @return Int ResourceLoaderModule ORIGIN_ class constant
	 */
	public function getAllowedModules( $type ){
		if( $type == ResourceLoaderModule::TYPE_COMBINED ){
			return min( array_values( $this->mAllowedModules ) );
		} else {
			return isset( $this->mAllowedModules[$type] )
				? $this->mAllowedModules[$type]
				: ResourceLoaderModule::ORIGIN_ALL;
		}
	}

	/**
	 * Set the highest level of CSS/JS untrustworthiness allowed
	 * @param  $type String ResourceLoaderModule TYPE_ constant
	 * @param  $level Int ResourceLoaderModule class constant
	 */
	public function setAllowedModules( $type, $level ){
		$this->mAllowedModules[$type] = $level;
	}

	/**
	 * As for setAllowedModules(), but don't inadvertantly make the page more accessible
	 * @param  $type String
	 * @param  $level Int ResourceLoaderModule class constant
	 */
	public function reduceAllowedModules( $type, $level ){
		$this->mAllowedModules[$type] = min( $this->getAllowedModules($type), $level );
	}

	/**
	 * Prepend $text to the body HTML
	 *
	 * @param $text String: HTML
	 */
	public function prependHTML( $text ) {
		$this->mBodytext = $text . $this->mBodytext;
	}

	/**
	 * Append $text to the body HTML
	 *
	 * @param $text String: HTML
	 */
	public function addHTML( $text ) {
		$this->mBodytext .= $text;
	}

	/**
	 * Clear the body HTML
	 */
	public function clearHTML() {
		$this->mBodytext = '';
	}

	/**
	 * Get the body HTML
	 *
	 * @return String: HTML
	 */
	public function getHTML() {
		return $this->mBodytext;
	}

	/**
	 * Add $text to the debug output
	 *
	 * @param $text String: debug text
	 */
	public function debug( $text ) {
		$this->mDebugtext .= $text;
	}

	/**
	 * Get/set the ParserOptions object to use for wikitext parsing
	 *
	 * @param $options either the ParserOption to use or null to only get the
	 *                 current ParserOption object
	 * @return current ParserOption object
	 */
	public function parserOptions( $options = null ) {
		if ( !$this->mParserOptions ) {
			$this->mParserOptions = new ParserOptions;
		}
		return wfSetVar( $this->mParserOptions, $options );
	}

	/**
	 * Set the revision ID which will be seen by the wiki text parser
	 * for things such as embedded {{REVISIONID}} variable use.
	 *
	 * @param $revid Mixed: an positive integer, or null
	 * @return Mixed: previous value
	 */
	public function setRevisionId( $revid ) {
		$val = is_null( $revid ) ? null : intval( $revid );
		return wfSetVar( $this->mRevisionId, $val );
	}

	/**
	 * Get the current revision ID
	 *
	 * @return Integer
	 */
	public function getRevisionId() {
		return $this->mRevisionId;
	}

	/**
	 * Convert wikitext to HTML and add it to the buffer
	 * Default assumes that the current page title will be used.
	 *
	 * @param $text String
	 * @param $linestart Boolean: is this the start of a line?
	 */
	public function addWikiText( $text, $linestart = true ) {
		$title = $this->getTitle(); // Work arround E_STRICT
		$this->addWikiTextTitle( $text, $title, $linestart );
	}

	/**
	 * Add wikitext with a custom Title object
	 *
	 * @param $text String: wikitext
	 * @param $title Title object
	 * @param $linestart Boolean: is this the start of a line?
	 */
	public function addWikiTextWithTitle( $text, &$title, $linestart = true ) {
		$this->addWikiTextTitle( $text, $title, $linestart );
	}

	/**
	 * Add wikitext with a custom Title object and
	 *
	 * @param $text String: wikitext
	 * @param $title Title object
	 * @param $linestart Boolean: is this the start of a line?
	 */
	function addWikiTextTitleTidy( $text, &$title, $linestart = true ) {
		$this->addWikiTextTitle( $text, $title, $linestart, true );
	}

	/**
	 * Add wikitext with tidy enabled
	 *
	 * @param $text String: wikitext
	 * @param $linestart Boolean: is this the start of a line?
	 */
	public function addWikiTextTidy( $text, $linestart = true ) {
		$title = $this->getTitle();
		$this->addWikiTextTitleTidy( $text, $title, $linestart );
	}

	/**
	 * Add wikitext with a custom Title object
	 *
	 * @param $text String: wikitext
	 * @param $title Title object
	 * @param $linestart Boolean: is this the start of a line?
	 * @param $tidy Boolean: whether to use tidy
	 */
	public function addWikiTextTitle( $text, &$title, $linestart, $tidy = false ) {
		global $wgParser;

		wfProfileIn( __METHOD__ );

		wfIncrStats( 'pcache_not_possible' );

		$popts = $this->parserOptions();
		$oldTidy = $popts->setTidy( $tidy );

		$parserOutput = $wgParser->parse(
			$text, $title, $popts,
			$linestart, true, $this->mRevisionId
		);

		$popts->setTidy( $oldTidy );

		$this->addParserOutput( $parserOutput );

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Add a ParserOutput object, but without Html
	 *
	 * @param $parserOutput ParserOutput object
	 */
	public function addParserOutputNoText( &$parserOutput ) {
		$this->mLanguageLinks += $parserOutput->getLanguageLinks();
		$this->addCategoryLinks( $parserOutput->getCategories() );
		$this->mNewSectionLink = $parserOutput->getNewSection();
		$this->mHideNewSectionLink = $parserOutput->getHideNewSection();

		$this->mParseWarnings = $parserOutput->getWarnings();
		if ( !$parserOutput->isCacheable() ) {
			$this->enableClientCache( false );
		}
		$this->mNoGallery = $parserOutput->getNoGallery();
		$this->mHeadItems = array_merge( $this->mHeadItems, $parserOutput->getHeadItems() );
		$this->addModules( $parserOutput->getModules() );
		// Versioning...
		foreach ( (array)$parserOutput->mTemplateIds as $ns => $dbks ) {
			if ( isset( $this->mTemplateIds[$ns] ) ) {
				$this->mTemplateIds[$ns] = $dbks + $this->mTemplateIds[$ns];
			} else {
				$this->mTemplateIds[$ns] = $dbks;
			}
		}

		// Hooks registered in the object
		global $wgParserOutputHooks;
		foreach ( $parserOutput->getOutputHooks() as $hookInfo ) {
			list( $hookName, $data ) = $hookInfo;
			if ( isset( $wgParserOutputHooks[$hookName] ) ) {
				call_user_func( $wgParserOutputHooks[$hookName], $this, $parserOutput, $data );
			}
		}

		wfRunHooks( 'OutputPageParserOutput', array( &$this, $parserOutput ) );
	}

	/**
	 * Add a ParserOutput object
	 *
	 * @param $parserOutput ParserOutput
	 */
	function addParserOutput( &$parserOutput ) {
		$this->addParserOutputNoText( $parserOutput );
		$text = $parserOutput->getText();
		wfRunHooks( 'OutputPageBeforeHTML', array( &$this, &$text ) );
		$this->addHTML( $text );
	}


	/**
	 * Add the output of a QuickTemplate to the output buffer
	 *
	 * @param $template QuickTemplate
	 */
	public function addTemplate( &$template ) {
		ob_start();
		$template->execute();
		$this->addHTML( ob_get_contents() );
		ob_end_clean();
	}

	/**
	 * Parse wikitext and return the HTML.
	 *
	 * @param $text String
	 * @param $linestart Boolean: is this the start of a line?
	 * @param $interface Boolean: use interface language ($wgLang instead of
	 *                   $wgContLang) while parsing language sensitive magic
	 *                   words like GRAMMAR and PLURAL
	 * @param $language  Language object: target language object, will override
	 *                   $interface
	 * @return String: HTML
	 */
	public function parse( $text, $linestart = true, $interface = false, $language = null ) {
		// Check one for one common cause for parser state resetting
		$callers = wfGetAllCallers( 10 );
		if ( strpos( $callers, 'Parser::extensionSubstitution' ) !== false ) {
			throw new MWException( "wfMsg* function with parsing cannot be used " .
				"inside a tag hook. Should use parser->recursiveTagParse() instead" );
		}

		global $wgParser;

		if( is_null( $this->getTitle() ) ) {
			throw new MWException( 'Empty $mTitle in ' . __METHOD__ );
		}

		$popts = $this->parserOptions();
		if ( $interface ) {
			$popts->setInterfaceMessage( true );
		}
		if ( $language !== null ) {
			$oldLang = $popts->setTargetLanguage( $language );
		}

		$parserOutput = $wgParser->parse(
			$text, $this->getTitle(), $popts,
			$linestart, true, $this->mRevisionId
		);

		if ( $interface ) {
			$popts->setInterfaceMessage( false );
		}
		if ( $language !== null ) {
			$popts->setTargetLanguage( $oldLang );
		}

		return $parserOutput->getText();
	}

	/**
	 * Parse wikitext, strip paragraphs, and return the HTML.
	 *
	 * @param $text String
	 * @param $linestart Boolean: is this the start of a line?
	 * @param $interface Boolean: use interface language ($wgLang instead of
	 *                   $wgContLang) while parsing language sensitive magic
	 *                   words like GRAMMAR and PLURAL
	 * @return String: HTML
	 */
	public function parseInline( $text, $linestart = true, $interface = false ) {
		$parsed = $this->parse( $text, $linestart, $interface );

		$m = array();
		if ( preg_match( '/^<p>(.*)\n?<\/p>\n?/sU', $parsed, $m ) ) {
			$parsed = $m[1];
		}

		return $parsed;
	}

	/**
	 * Set the value of the "s-maxage" part of the "Cache-control" HTTP header
	 *
	 * @param $maxage Integer: maximum cache time on the Squid, in seconds.
	 */
	public function setSquidMaxage( $maxage ) {
		$this->mSquidMaxage = $maxage;
	}

	/**
	 * Use enableClientCache(false) to force it to send nocache headers
	 *
	 * @param $state ??
	 */
	public function enableClientCache( $state ) {
		return wfSetVar( $this->mEnableClientCache, $state );
	}

	/**
	 * Get the list of cookies that will influence on the cache
	 *
	 * @return Array
	 */
	function getCacheVaryCookies() {
		global $wgCookiePrefix, $wgCacheVaryCookies;
		static $cookies;
		if ( $cookies === null ) {
			$cookies = array_merge(
				array(
					"{$wgCookiePrefix}Token",
					"{$wgCookiePrefix}LoggedOut",
					session_name()
				),
				$wgCacheVaryCookies
			);
			wfRunHooks( 'GetCacheVaryCookies', array( $this, &$cookies ) );
		}
		return $cookies;
	}

	/**
	 * Return whether this page is not cacheable because "useskin" or "uselang"
	 * URL parameters were passed.
	 *
	 * @return Boolean
	 */
	function uncacheableBecauseRequestVars() {
		global $wgRequest;
		return $wgRequest->getText( 'useskin', false ) === false
			&& $wgRequest->getText( 'uselang', false ) === false;
	}

	/**
	 * Check if the request has a cache-varying cookie header
	 * If it does, it's very important that we don't allow public caching
	 *
	 * @return Boolean
	 */
	function haveCacheVaryCookies() {
		global $wgRequest;
		$cookieHeader = $wgRequest->getHeader( 'cookie' );
		if ( $cookieHeader === false ) {
			return false;
		}
		$cvCookies = $this->getCacheVaryCookies();
		foreach ( $cvCookies as $cookieName ) {
			# Check for a simple string match, like the way squid does it
			if ( strpos( $cookieHeader, $cookieName ) !== false ) {
				wfDebug( __METHOD__ . ": found $cookieName\n" );
				return true;
			}
		}
		wfDebug( __METHOD__ . ": no cache-varying cookies found\n" );
		return false;
	}

	/**
	 * Add an HTTP header that will influence on the cache
	 *
	 * @param $header String: header name
	 * @param $option Array|null
	 * @fixme Document the $option parameter; it appears to be for
	 *        X-Vary-Options but what format is acceptable?
	 */
	public function addVaryHeader( $header, $option = null ) {
		if ( !array_key_exists( $header, $this->mVaryHeader ) ) {
			$this->mVaryHeader[$header] = (array)$option;
		} elseif( is_array( $option ) ) {
			if( is_array( $this->mVaryHeader[$header] ) ) {
				$this->mVaryHeader[$header] = array_merge( $this->mVaryHeader[$header], $option );
			} else {
				$this->mVaryHeader[$header] = $option;
			}
		}
		$this->mVaryHeader[$header] = array_unique( $this->mVaryHeader[$header] );
	}

	/**
	 * Get a complete X-Vary-Options header
	 *
	 * @return String
	 */
	public function getXVO() {
		$cvCookies = $this->getCacheVaryCookies();

		$cookiesOption = array();
		foreach ( $cvCookies as $cookieName ) {
			$cookiesOption[] = 'string-contains=' . $cookieName;
		}
		$this->addVaryHeader( 'Cookie', $cookiesOption );

		$headers = array();
		foreach( $this->mVaryHeader as $header => $option ) {
			$newheader = $header;
			if( is_array( $option ) ) {
				$newheader .= ';' . implode( ';', $option );
			}
			$headers[] = $newheader;
		}
		$xvo = 'X-Vary-Options: ' . implode( ',', $headers );

		return $xvo;
	}

	/**
	 * bug 21672: Add Accept-Language to Vary and XVO headers
	 * if there's no 'variant' parameter existed in GET.
	 *
	 * For example:
	 *   /w/index.php?title=Main_page should always be served; but
	 *   /w/index.php?title=Main_page&variant=zh-cn should never be served.
	 */
	function addAcceptLanguage() {
		global $wgRequest, $wgContLang;
		if( !$wgRequest->getCheck( 'variant' ) && $wgContLang->hasVariants() ) {
			$variants = $wgContLang->getVariants();
			$aloption = array();
			foreach ( $variants as $variant ) {
				if( $variant === $wgContLang->getCode() ) {
					continue;
				} else {
					$aloption[] = 'string-contains=' . $variant;

					// IE and some other browsers use another form of language code
					// in their Accept-Language header, like "zh-CN" or "zh-TW".
					// We should handle these too.
					$ievariant = explode( '-', $variant );
					if ( count( $ievariant ) == 2 ) {
						$ievariant[1] = strtoupper( $ievariant[1] );
						$ievariant = implode( '-', $ievariant );
						$aloption[] = 'string-contains=' . $ievariant;
					}
				}
			}
			$this->addVaryHeader( 'Accept-Language', $aloption );
		}
	}

	/**
	 * Set a flag which will cause an X-Frame-Options header appropriate for 
	 * edit pages to be sent. The header value is controlled by 
	 * $wgEditPageFrameOptions.
	 *
	 * This is the default for special pages. If you display a CSRF-protected 
	 * form on an ordinary view page, then you need to call this function.
	 */
	public function preventClickjacking( $enable = true ) {
		$this->mPreventClickjacking = $enable;
	}

	/**
	 * Turn off frame-breaking. Alias for $this->preventClickjacking(false).
	 * This can be called from pages which do not contain any CSRF-protected
	 * HTML form.
	 */
	public function allowClickjacking() {
		$this->mPreventClickjacking = false;
	}

	/**
	 * Get the X-Frame-Options header value (without the name part), or false 
	 * if there isn't one. This is used by Skin to determine whether to enable 
	 * JavaScript frame-breaking, for clients that don't support X-Frame-Options.
	 */
	public function getFrameOptions() {
		global $wgBreakFrames, $wgEditPageFrameOptions;
		if ( $wgBreakFrames ) {
			return 'DENY';
		} elseif ( $this->mPreventClickjacking && $wgEditPageFrameOptions ) {
			return $wgEditPageFrameOptions;
		}
	}

	/**
	 * Send cache control HTTP headers
	 */
	public function sendCacheControl() {
		global $wgUseSquid, $wgUseESI, $wgUseETag, $wgSquidMaxage, $wgRequest, $wgUseXVO;

		$response = $wgRequest->response();
		if ( $wgUseETag && $this->mETag ) {
			$response->header( "ETag: $this->mETag" );
		}

		$this->addAcceptLanguage();

		# don't serve compressed data to clients who can't handle it
		# maintain different caches for logged-in users and non-logged in ones
		$response->header( 'Vary: ' . join( ', ', array_keys( $this->mVaryHeader ) ) );

		if ( $wgUseXVO ) {
			# Add an X-Vary-Options header for Squid with Wikimedia patches
			$response->header( $this->getXVO() );
		}

		if( !$this->uncacheableBecauseRequestVars() && $this->mEnableClientCache ) {
			if(
				$wgUseSquid && session_id() == '' && !$this->isPrintable() &&
				$this->mSquidMaxage != 0 && !$this->haveCacheVaryCookies()
			)
			{
				if ( $wgUseESI ) {
					# We'll purge the proxy cache explicitly, but require end user agents
					# to revalidate against the proxy on each visit.
					# Surrogate-Control controls our Squid, Cache-Control downstream caches
					wfDebug( __METHOD__ . ": proxy caching with ESI; {$this->mLastModified} **\n", false );
					# start with a shorter timeout for initial testing
					# header( 'Surrogate-Control: max-age=2678400+2678400, content="ESI/1.0"');
					$response->header( 'Surrogate-Control: max-age='.$wgSquidMaxage.'+'.$this->mSquidMaxage.', content="ESI/1.0"');
					$response->header( 'Cache-Control: s-maxage=0, must-revalidate, max-age=0' );
				} else {
					# We'll purge the proxy cache for anons explicitly, but require end user agents
					# to revalidate against the proxy on each visit.
					# IMPORTANT! The Squid needs to replace the Cache-Control header with
					# Cache-Control: s-maxage=0, must-revalidate, max-age=0
					wfDebug( __METHOD__ . ": local proxy caching; {$this->mLastModified} **\n", false );
					# start with a shorter timeout for initial testing
					# header( "Cache-Control: s-maxage=2678400, must-revalidate, max-age=0" );
					$response->header( 'Cache-Control: s-maxage='.$this->mSquidMaxage.', must-revalidate, max-age=0' );
				}
			} else {
				# We do want clients to cache if they can, but they *must* check for updates
				# on revisiting the page.
				wfDebug( __METHOD__ . ": private caching; {$this->mLastModified} **\n", false );
				$response->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
				$response->header( "Cache-Control: private, must-revalidate, max-age=0" );
			}
			if($this->mLastModified) {
				$response->header( "Last-Modified: {$this->mLastModified}" );
			}
		} else {
			wfDebug( __METHOD__ . ": no caching **\n", false );

			# In general, the absence of a last modified header should be enough to prevent
			# the client from using its cache. We send a few other things just to make sure.
			$response->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
			$response->header( 'Cache-Control: no-cache, no-store, max-age=0, must-revalidate' );
			$response->header( 'Pragma: no-cache' );
		}
	}

	/**
	 * Get the message associed with the HTTP response code $code
	 *
	 * @param $code Integer: status code
	 * @return String or null: message or null if $code is not in the list of
	 *         messages
	 */
	public static function getStatusMessage( $code ) {
		static $statusMessage = array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			102 => 'Processing',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			207 => 'Multi-Status',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Large',
			415 => 'Unsupported Media Type',
			416 => 'Request Range Not Satisfiable',
			417 => 'Expectation Failed',
			422 => 'Unprocessable Entity',
			423 => 'Locked',
			424 => 'Failed Dependency',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported',
			507 => 'Insufficient Storage'
		);
		return isset( $statusMessage[$code] ) ? $statusMessage[$code] : null;
	}

	/**
	 * Finally, all the text has been munged and accumulated into
	 * the object, let's actually output it:
	 */
	public function output() {
		global $wgUser, $wgOutputEncoding, $wgRequest;
		global $wgLanguageCode, $wgDebugRedirects, $wgMimeType;
		global $wgUseAjax, $wgAjaxWatch;
		global $wgEnableMWSuggest, $wgUniversalEditButton;

		if( $this->mDoNothing ) {
			return;
		}
		wfProfileIn( __METHOD__ );
		if ( $this->mRedirect != '' ) {
			# Standards require redirect URLs to be absolute
			$this->mRedirect = wfExpandUrl( $this->mRedirect );
			if( $this->mRedirectCode == '301' || $this->mRedirectCode == '303' ) {
				if( !$wgDebugRedirects ) {
					$message = self::getStatusMessage( $this->mRedirectCode );
					$wgRequest->response()->header( "HTTP/1.1 {$this->mRedirectCode} $message" );
				}
				$this->mLastModified = wfTimestamp( TS_RFC2822 );
			}
			$this->sendCacheControl();

			$wgRequest->response()->header( "Content-Type: text/html; charset=utf-8" );
			if( $wgDebugRedirects ) {
				$url = htmlspecialchars( $this->mRedirect );
				print "<html>\n<head>\n<title>Redirect</title>\n</head>\n<body>\n";
				print "<p>Location: <a href=\"$url\">$url</a></p>\n";
				print "</body>\n</html>\n";
			} else {
				$wgRequest->response()->header( 'Location: ' . $this->mRedirect );
			}
			wfProfileOut( __METHOD__ );
			return;
		} elseif ( $this->mStatusCode ) {
			$message = self::getStatusMessage( $this->mStatusCode );
			if ( $message ) {
				$wgRequest->response()->header( 'HTTP/1.1 ' . $this->mStatusCode . ' ' . $message );
			}
		}

		$sk = $wgUser->getSkin();

		// Add base resources
		$this->addModules( array( 'mediawiki.legacy.wikibits', 'mediawiki.util' ) );

		// Add various resources if required
		if ( $wgUseAjax ) {
			$this->addModules( 'mediawiki.legacy.ajax' );

			wfRunHooks( 'AjaxAddScript', array( &$this ) );

			if( $wgAjaxWatch && $wgUser->isLoggedIn() ) {
				$this->addModules( 'mediawiki.action.watch.ajax' );
			}

			if ( $wgEnableMWSuggest && !$wgUser->getOption( 'disablesuggest', false ) ) {
				$this->addModules( 'mediawiki.legacy.mwsuggest' );
			}
		}

		if( $wgUser->getBoolOption( 'editsectiononrightclick' ) ) {
			$this->addModules( 'mediawiki.action.view.rightClickEdit' );
		}

		if( $wgUniversalEditButton ) {
			if( $this->isArticleRelated() && $this->getTitle() && $this->getTitle()->quickUserCan( 'edit' )
				&& ( $this->getTitle()->exists() || $this->getTitle()->quickUserCan( 'create' ) ) ) {
				// Original UniversalEditButton
				$msg = wfMsg( 'edit' );
				$this->addLink( array(
					'rel' => 'alternate',
					'type' => 'application/x-wiki',
					'title' => $msg,
					'href' => $this->getTitle()->getLocalURL( 'action=edit' )
				) );
				// Alternate edit link
				$this->addLink( array(
					'rel' => 'edit',
					'title' => $msg,
					'href' => $this->getTitle()->getLocalURL( 'action=edit' )
				) );
			}
		}


		# Buffer output; final headers may depend on later processing
		ob_start();

		$wgRequest->response()->header( "Content-type: $wgMimeType; charset={$wgOutputEncoding}" );
		$wgRequest->response()->header( 'Content-language: ' . $wgLanguageCode );

		// Prevent framing, if requested
		$frameOptions = $this->getFrameOptions();
		if ( $frameOptions ) {
			$wgRequest->response()->header( "X-Frame-Options: $frameOptions" );
		}

		if ( $this->mArticleBodyOnly ) {
			$this->out( $this->mBodytext );
		} else {
			// Hook that allows last minute changes to the output page, e.g.
			// adding of CSS or Javascript by extensions.
			wfRunHooks( 'BeforePageDisplay', array( &$this, &$sk ) );

			wfProfileIn( 'Output-skin' );
			$sk->outputPage( $this );
			wfProfileOut( 'Output-skin' );
		}

		$this->sendCacheControl();
		ob_end_flush();
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Actually output something with print(). Performs an iconv to the
	 * output encoding, if needed.
	 *
	 * @param $ins String: the string to output
	 */
	public function out( $ins ) {
		global $wgInputEncoding, $wgOutputEncoding, $wgContLang;
		if ( 0 == strcmp( $wgInputEncoding, $wgOutputEncoding ) ) {
			$outs = $ins;
		} else {
			$outs = $wgContLang->iconv( $wgInputEncoding, $wgOutputEncoding, $ins );
			if ( false === $outs ) {
				$outs = $ins;
			}
		}
		print $outs;
	}

	/**
	 * Produce a "user is blocked" page.
	 *
	 * @param $return Boolean: whether to have a "return to $wgTitle" message or not.
	 * @return nothing
	 */
	function blockedPage( $return = true ) {
		global $wgUser, $wgContLang, $wgLang;

		$this->setPageTitle( wfMsg( 'blockedtitle' ) );
		$this->setRobotPolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );

		$name = User::whoIs( $wgUser->blockedBy() );
		$reason = $wgUser->blockedFor();
		if( $reason == '' ) {
			$reason = wfMsg( 'blockednoreason' );
		}
		$blockTimestamp = $wgLang->timeanddate(
			wfTimestamp( TS_MW, $wgUser->mBlock->mTimestamp ), true
		);
		$ip = wfGetIP();

		$link = '[[' . $wgContLang->getNsText( NS_USER ) . ":{$name}|{$name}]]";

		$blockid = $wgUser->mBlock->mId;

		$blockExpiry = $wgUser->mBlock->mExpiry;
		if ( $blockExpiry == 'infinity' ) {
			// Entry in database (table ipblocks) is 'infinity' but 'ipboptions' uses 'infinite' or 'indefinite'
			// Search for localization in 'ipboptions'
			$scBlockExpiryOptions = wfMsg( 'ipboptions' );
			foreach ( explode( ',', $scBlockExpiryOptions ) as $option ) {
				if ( strpos( $option, ':' ) === false ) {
					continue;
				}
				list( $show, $value ) = explode( ':', $option );
				if ( $value == 'infinite' || $value == 'indefinite' ) {
					$blockExpiry = $show;
					break;
				}
			}
		} else {
			$blockExpiry = $wgLang->timeanddate(
				wfTimestamp( TS_MW, $blockExpiry ),
				true
			);
		}

		if ( $wgUser->mBlock->mAuto ) {
			$msg = 'autoblockedtext';
		} else {
			$msg = 'blockedtext';
		}

		/* $ip returns who *is* being blocked, $intended contains who was meant to be blocked.
		 * This could be a username, an IP range, or a single IP. */
		$intended = $wgUser->mBlock->mAddress;

		$this->addWikiMsg(
			$msg, $link, $reason, $ip, $name, $blockid, $blockExpiry,
			$intended, $blockTimestamp
		);

		# Don't auto-return to special pages
		if( $return ) {
			$return = $this->getTitle()->getNamespace() > -1 ? $this->getTitle() : null;
			$this->returnToMain( null, $return );
		}
	}

	/**
	 * Output a standard error page
	 *
	 * @param $title String: message key for page title
	 * @param $msg String: message key for page text
	 * @param $params Array: message parameters
	 */
	public function showErrorPage( $title, $msg, $params = array() ) {
		if ( $this->getTitle() ) {
			$this->mDebugtext .= 'Original title: ' . $this->getTitle()->getPrefixedText() . "\n";
		}
		$this->setPageTitle( wfMsg( $title ) );
		$this->setHTMLTitle( wfMsg( 'errorpagetitle' ) );
		$this->setRobotPolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );
		$this->enableClientCache( false );
		$this->mRedirect = '';
		$this->mBodytext = '';

		$this->addWikiMsgArray( $msg, $params );

		$this->returnToMain();
	}

	/**
	 * Output a standard permission error page
	 *
	 * @param $errors Array: error message keys
	 * @param $action String: action that was denied or null if unknown
	 */
	public function showPermissionsErrorPage( $errors, $action = null ) {
		$this->mDebugtext .= 'Original title: ' .
		$this->getTitle()->getPrefixedText() . "\n";
		$this->setPageTitle( wfMsg( 'permissionserrors' ) );
		$this->setHTMLTitle( wfMsg( 'permissionserrors' ) );
		$this->setRobotPolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );
		$this->enableClientCache( false );
		$this->mRedirect = '';
		$this->mBodytext = '';
		$this->addWikiText( $this->formatPermissionsErrorMessage( $errors, $action ) );
	}

	/**
	 * Display an error page indicating that a given version of MediaWiki is
	 * required to use it
	 *
	 * @param $version Mixed: the version of MediaWiki needed to use the page
	 */
	public function versionRequired( $version ) {
		$this->setPageTitle( wfMsg( 'versionrequired', $version ) );
		$this->setHTMLTitle( wfMsg( 'versionrequired', $version ) );
		$this->setRobotPolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );
		$this->mBodytext = '';

		$this->addWikiMsg( 'versionrequiredtext', $version );
		$this->returnToMain();
	}

	/**
	 * Display an error page noting that a given permission bit is required.
	 *
	 * @param $permission String: key required
	 */
	public function permissionRequired( $permission ) {
		global $wgLang;

		$this->setPageTitle( wfMsg( 'badaccess' ) );
		$this->setHTMLTitle( wfMsg( 'errorpagetitle' ) );
		$this->setRobotPolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );
		$this->mBodytext = '';

		$groups = array_map( array( 'User', 'makeGroupLinkWiki' ),
			User::getGroupsWithPermission( $permission ) );
		if( $groups ) {
			$this->addWikiMsg(
				'badaccess-groups',
				$wgLang->commaList( $groups ),
				count( $groups )
			);
		} else {
			$this->addWikiMsg( 'badaccess-group0' );
		}
		$this->returnToMain();
	}

	/**
	 * Produce the stock "please login to use the wiki" page
	 */
	public function loginToUse() {
		global $wgUser;

		if( $wgUser->isLoggedIn() ) {
			$this->permissionRequired( 'read' );
			return;
		}

		$skin = $wgUser->getSkin();

		$this->setPageTitle( wfMsg( 'loginreqtitle' ) );
		$this->setHtmlTitle( wfMsg( 'errorpagetitle' ) );
		$this->setRobotPolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );

		$loginTitle = SpecialPage::getTitleFor( 'Userlogin' );
		$loginLink = $skin->link(
			$loginTitle,
			wfMsgHtml( 'loginreqlink' ),
			array(),
			array( 'returnto' => $this->getTitle()->getPrefixedText() ),
			array( 'known', 'noclasses' )
		);
		$this->addWikiMsgArray( 'loginreqpagetext', array( $loginLink ), array( 'replaceafter' ) );
		$this->addHTML( "\n<!--" . $this->getTitle()->getPrefixedUrl() . '-->' );

		# Don't return to the main page if the user can't read it
		# otherwise we'll end up in a pointless loop
		$mainPage = Title::newMainPage();
		if( $mainPage->userCanRead() ) {
			$this->returnToMain( null, $mainPage );
		}
	}

	/**
	 * Format a list of error messages
	 *
	 * @param $errors Array of arrays returned by Title::getUserPermissionsErrors
	 * @param $action String: action that was denied or null if unknown
	 * @return String: the wikitext error-messages, formatted into a list.
	 */
	public function formatPermissionsErrorMessage( $errors, $action = null ) {
		if ( $action == null ) {
			$text = wfMsgNoTrans( 'permissionserrorstext', count( $errors ) ) . "\n\n";
		} else {
			$action_desc = wfMsgNoTrans( "action-$action" );
			$text = wfMsgNoTrans(
				'permissionserrorstext-withaction',
				count( $errors ),
				$action_desc
			) . "\n\n";
		}

		if ( count( $errors ) > 1 ) {
			$text .= '<ul class="permissions-errors">' . "\n";

			foreach( $errors as $error ) {
				$text .= '<li>';
				$text .= call_user_func_array( 'wfMsgNoTrans', $error );
				$text .= "</li>\n";
			}
			$text .= '</ul>';
		} else {
			$text .= "<div class=\"permissions-errors\">\n" .
					call_user_func_array( 'wfMsgNoTrans', reset( $errors ) ) .
					"\n</div>";
		}

		return $text;
	}

	/**
	 * Display a page stating that the Wiki is in read-only mode,
	 * and optionally show the source of the page that the user
	 * was trying to edit.  Should only be called (for this
	 * purpose) after wfReadOnly() has returned true.
	 *
	 * For historical reasons, this function is _also_ used to
	 * show the error message when a user tries to edit a page
	 * they are not allowed to edit.  (Unless it's because they're
	 * blocked, then we show blockedPage() instead.)  In this
	 * case, the second parameter should be set to true and a list
	 * of reasons supplied as the third parameter.
	 *
	 * @todo Needs to be split into multiple functions.
	 *
	 * @param $source    String: source code to show (or null).
	 * @param $protected Boolean: is this a permissions error?
	 * @param $reasons   Array: list of reasons for this error, as returned by Title::getUserPermissionsErrors().
	 * @param $action    String: action that was denied or null if unknown
	 */
	public function readOnlyPage( $source = null, $protected = false, $reasons = array(), $action = null ) {
		global $wgUser;
		$skin = $wgUser->getSkin();

		$this->setRobotPolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );

		// If no reason is given, just supply a default "I can't let you do
		// that, Dave" message.  Should only occur if called by legacy code.
		if ( $protected && empty( $reasons ) ) {
			$reasons[] = array( 'badaccess-group0' );
		}

		if ( !empty( $reasons ) ) {
			// Permissions error
			if( $source ) {
				$this->setPageTitle( wfMsg( 'viewsource' ) );
				$this->setSubtitle(
					wfMsg( 'viewsourcefor', $skin->linkKnown( $this->getTitle() ) )
				);
			} else {
				$this->setPageTitle( wfMsg( 'badaccess' ) );
			}
			$this->addWikiText( $this->formatPermissionsErrorMessage( $reasons, $action ) );
		} else {
			// Wiki is read only
			$this->setPageTitle( wfMsg( 'readonly' ) );
			$reason = wfReadOnlyReason();
			$this->wrapWikiMsg( "<div class='mw-readonly-error'>\n$1\n</div>", array( 'readonlytext', $reason ) );
		}

		// Show source, if supplied
		if( is_string( $source ) ) {
			$this->addWikiMsg( 'viewsourcetext' );

			$params = array(
				'id'   => 'wpTextbox1',
				'name' => 'wpTextbox1',
				'cols' => $wgUser->getOption( 'cols' ),
				'rows' => $wgUser->getOption( 'rows' ),
				'readonly' => 'readonly'
			);
			$this->addHTML( Html::element( 'textarea', $params, $source ) );

			// Show templates used by this article
			$skin = $wgUser->getSkin();
			$article = new Article( $this->getTitle() );
			$this->addHTML( "<div class='templatesUsed'>
{$skin->formatTemplates( $article->getUsedTemplates() )}
</div>
" );
		}

		# If the title doesn't exist, it's fairly pointless to print a return
		# link to it.  After all, you just tried editing it and couldn't, so
		# what's there to do there?
		if( $this->getTitle()->exists() ) {
			$this->returnToMain( null, $this->getTitle() );
		}
	}

	/**
	 * Adds JS-based password security checker
	 * @param $passwordId String ID of input box containing password
	 * @param $retypeId String ID of input box containing retyped password
	 * @return none
	 */
	public function addPasswordSecurity( $passwordId, $retypeId ) {
		$data = array(
			'password' => '#' . $passwordId,
			'retype' => '#' . $retypeId,
			'messages' => array(),
		);
		foreach ( array( 'password-strength', 'password-strength-bad', 'password-strength-mediocre',
				'password-strength-acceptable', 'password-strength-good', 'password-retype', 'password-retype-mismatch'
			) as $message ) {
			$data['messages'][$message] = wfMsg( $message );
		}
		$this->addScript( Html::inlineScript( 'var passwordSecurity=' . FormatJson::encode( $data ) ) );
		$this->addModules( 'mediawiki.legacy.password' );
	}

	public function showFatalError( $message ) {
		$this->setPageTitle( wfMsg( 'internalerror' ) );
		$this->setRobotPolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );
		$this->enableClientCache( false );
		$this->mRedirect = '';
		$this->mBodytext = $message;
	}

	public function showUnexpectedValueError( $name, $val ) {
		$this->showFatalError( wfMsg( 'unexpected', $name, $val ) );
	}

	public function showFileCopyError( $old, $new ) {
		$this->showFatalError( wfMsg( 'filecopyerror', $old, $new ) );
	}

	public function showFileRenameError( $old, $new ) {
		$this->showFatalError( wfMsg( 'filerenameerror', $old, $new ) );
	}

	public function showFileDeleteError( $name ) {
		$this->showFatalError( wfMsg( 'filedeleteerror', $name ) );
	}

	public function showFileNotFoundError( $name ) {
		$this->showFatalError( wfMsg( 'filenotfound', $name ) );
	}

	/**
	 * Add a "return to" link pointing to a specified title
	 *
	 * @param $title Title to link
	 * @param $query String: query string
	 * @param $text String text of the link (input is not escaped)
	 */
	public function addReturnTo( $title, $query = array(), $text = null ) {
		global $wgUser;
		$this->addLink( array( 'rel' => 'next', 'href' => $title->getFullURL() ) );
		$link = wfMsgHtml(
			'returnto',
			$wgUser->getSkin()->link( $title, $text, array(), $query )
		);
		$this->addHTML( "<p id=\"mw-returnto\">{$link}</p>\n" );
	}

	/**
	 * Add a "return to" link pointing to a specified title,
	 * or the title indicated in the request, or else the main page
	 *
	 * @param $unused No longer used
	 * @param $returnto Title or String to return to
	 * @param $returntoquery String: query string for the return to link
	 */
	public function returnToMain( $unused = null, $returnto = null, $returntoquery = null ) {
		global $wgRequest;

		if ( $returnto == null ) {
			$returnto = $wgRequest->getText( 'returnto' );
		}

		if ( $returntoquery == null ) {
			$returntoquery = $wgRequest->getText( 'returntoquery' );
		}

		if ( $returnto === '' ) {
			$returnto = Title::newMainPage();
		}

		if ( is_object( $returnto ) ) {
			$titleObj = $returnto;
		} else {
			$titleObj = Title::newFromText( $returnto );
		}
		if ( !is_object( $titleObj ) ) {
			$titleObj = Title::newMainPage();
		}

		$this->addReturnTo( $titleObj, $returntoquery );
	}

	/**
	 * @param $sk Skin The given Skin
	 * @param $includeStyle Boolean: unused
	 * @return String: The doctype, opening <html>, and head element.
	 */
	public function headElement( Skin $sk, $includeStyle = true ) {
		global $wgOutputEncoding, $wgMimeType;
		global $wgUseTrackbacks, $wgHtml5;
		global $wgUser, $wgRequest, $wgLang;

		if ( $sk->commonPrintStylesheet() ) {
			$this->addModuleStyles( 'mediawiki.legacy.wikiprintable' );
		}
		$sk->setupUserCss( $this );

		$lang = wfUILang();
		$ret = Html::htmlHeader( array( 'lang' => $lang->getCode(), 'dir' => $lang->getDir() ) );

		if ( $this->getHTMLTitle() == '' ) {
			$this->setHTMLTitle( wfMsg( 'pagetitle', $this->getPageTitle() ) );
		}

		$openHead = Html::openElement( 'head' );
		if ( $openHead ) {
			# Don't bother with the newline if $head == ''
			$ret .= "$openHead\n";
		}

		if ( $wgHtml5 ) {
			# More succinct than <meta http-equiv=Content-Type>, has the
			# same effect
			$ret .= Html::element( 'meta', array( 'charset' => $wgOutputEncoding ) ) . "\n";
		} else {
			$this->addMeta( 'http:Content-Type', "$wgMimeType; charset=$wgOutputEncoding" );
		}

		$ret .= Html::element( 'title', null, $this->getHTMLTitle() ) . "\n";

		$ret .= implode( "\n", array(
			$this->getHeadLinks( $sk ),
			$this->buildCssLinks( $sk ),
			$this->getHeadItems()
		) );

		if ( $wgUseTrackbacks && $this->isArticleRelated() ) {
			$ret .= $this->getTitle()->trackbackRDF();
		}

		$closeHead = Html::closeElement( 'head' );
		if ( $closeHead ) {
			$ret .= "$closeHead\n";
		}

		$bodyAttrs = array();

		# Crazy edit-on-double-click stuff
		$action = $wgRequest->getVal( 'action', 'view' );

		if (
			$this->getTitle()->getNamespace() != NS_SPECIAL &&
			!in_array( $action, array( 'edit', 'submit' ) ) &&
			$wgUser->getOption( 'editondblclick' )
		)
		{
			$bodyAttrs['ondblclick'] = "document.location = '" . Xml::escapeJsString( $this->getTitle()->getEditURL() ) . "'";
		}

		# Class bloat
		$dir = wfUILang()->getDir();
		$bodyAttrs['class'] = "mediawiki $dir";

		if ( $wgLang->capitalizeAllNouns() ) {
			# A <body> class is probably not the best way to do this . . .
			$bodyAttrs['class'] .= ' capitalize-all-nouns';
		}
		$bodyAttrs['class'] .= ' ' . $sk->getPageClasses( $this->getTitle() );
		$bodyAttrs['class'] .= ' skin-' . Sanitizer::escapeClass( $sk->getSkinName() );

		$sk->addToBodyAttributes( $this, $bodyAttrs ); // Allow skins to add body attributes they need
		wfRunHooks( 'OutputPageBodyAttributes', array( $this, $sk, &$bodyAttrs ) );

		$ret .= Html::openElement( 'body', $bodyAttrs ) . "\n";

		return $ret;
	}

	/**
	 * Get a ResourceLoader object associated with this OutputPage
	 */
	public function getResourceLoader() {
		if ( is_null( $this->mResourceLoader ) ) {
			$this->mResourceLoader = new ResourceLoader();
		}
		return $this->mResourceLoader;
	}		

	/**
	 * TODO: Document
	 * @param $skin Skin
	 * @param $modules Array/string with the module name
	 * @param $only String ResourceLoaderModule TYPE_ class constant
	 * @param $useESI boolean
	 * @return string html <script> and <style> tags
	 */
	protected function makeResourceLoaderLink( Skin $skin, $modules, $only, $useESI = false ) {
		global $wgUser, $wgLang, $wgLoadScript, $wgResourceLoaderUseESI,
			$wgResourceLoaderInlinePrivateModules, $wgRequest;
		// Lazy-load ResourceLoader
		// TODO: Should this be a static function of ResourceLoader instead?
		// TODO: Divide off modules starting with "user", and add the user parameter to them
		$baseQuery = array(
			'lang' => $wgLang->getCode(),
			'debug' => ResourceLoader::inDebugMode() ? 'true' : 'false',
			'skin' => $skin->getSkinName(),
			'only' => $only,
		);
		// Propagate printable and handheld parameters if present
		if ( $this->isPrintable() ) {
			$baseQuery['printable'] = 1;
		}
		if ( $wgRequest->getBool( 'handheld' ) ) {
			$baseQuery['handheld'] = 1;
		}

		if ( !count( $modules ) ) {
			return '';
		}

		if ( count( $modules ) > 1 ) {
			// Remove duplicate module requests
			$modules = array_unique( (array) $modules );
			// Sort module names so requests are more uniform
			sort( $modules );

			if ( ResourceLoader::inDebugMode() ) {
				// Recursively call us for every item
				$links = '';
				foreach ( $modules as $name ) {
					$links .= $this->makeResourceLoaderLink( $skin, $name, $only, $useESI );
				}
				return $links;
			}
		}

		// Create keyed-by-group list of module objects from modules list
		$groups = array();
		$resourceLoader = $this->getResourceLoader();
		foreach ( (array) $modules as $name ) {
			$module = $resourceLoader->getModule( $name );
			# Check that we're allowed to include this module on this page
			if ( ( $module->getOrigin() > $this->getAllowedModules( ResourceLoaderModule::TYPE_SCRIPTS )
					&& $only == ResourceLoaderModule::TYPE_SCRIPTS )
				|| ( $module->getOrigin() > $this->getAllowedModules( ResourceLoaderModule::TYPE_STYLES )
					&& $only == ResourceLoaderModule::TYPE_STYLES )
				)
			{
				continue;
			}

			$group = $module->getGroup();
			if ( !isset( $groups[$group] ) ) {
				$groups[$group] = array();
			}
			$groups[$group][$name] = $module;
		}

		$links = '';
		foreach ( $groups as $group => $modules ) {
			$query = $baseQuery;
			// Special handling for user-specific groups
			if ( ( $group === 'user' || $group === 'private' ) && $wgUser->isLoggedIn() ) {
				$query['user'] = $wgUser->getName();
			}
			
			// Create a fake request based on the one we are about to make so modules return
			// correct timestamp and emptiness data
			$context = new ResourceLoaderContext( $resourceLoader, new FauxRequest( $query ) );
			// Drop modules that know they're empty
			foreach ( $modules as $key => $module ) {
				if ( $module->isKnownEmpty( $context ) ) {
					unset( $modules[$key] );
				}
			}
			// If there are no modules left, skip this group
			if ( $modules === array() ) {
				continue;
			}
			
			$query['modules'] = implode( '|', array_keys( $modules ) );
			
			// Support inlining of private modules if configured as such
			if ( $group === 'private' && $wgResourceLoaderInlinePrivateModules ) {
				if ( $only == ResourceLoaderModule::TYPE_STYLES ) {
					$links .= Html::inlineStyle(
						$resourceLoader->makeModuleResponse( $context, $modules )
					);
				} else {
					$links .= Html::inlineScript(
						ResourceLoader::makeLoaderConditionalScript(
							$resourceLoader->makeModuleResponse( $context, $modules )
						)
					);
				}
				continue;
			}
			// Special handling for user and site groups; because users might change their stuff
			// on-wiki like site or user pages, or user preferences; we need to find the highest
			// timestamp of these user-changable modules so we can ensure cache misses on change
			if ( $group === 'user' || $group === 'site' ) {
				// Get the maximum timestamp
				$timestamp = 1;
				foreach ( $modules as $module ) {
					$timestamp = max( $timestamp, $module->getModifiedTime( $context ) );
				}
				// Add a version parameter so cache will break when things change
				$query['version'] = wfTimestamp( TS_ISO_8601_BASIC, $timestamp );
			}
			// Make queries uniform in order
			ksort( $query );

			$url = wfAppendQuery( $wgLoadScript, $query );
			if ( $useESI && $wgResourceLoaderUseESI ) {
				$esi = Xml::element( 'esi:include', array( 'src' => $url ) );
				if ( $only == ResourceLoaderModule::TYPE_STYLES ) {
					$links .= Html::inlineStyle( $esi );
				} else {
					$links .= Html::inlineScript( $esi );
				}
			} else {
				// Automatically select style/script elements
				if ( $only === ResourceLoaderModule::TYPE_STYLES ) {
					$links .= Html::linkedStyle( wfAppendQuery( $wgLoadScript, $query ) ) . "\n";
				} else {
					$links .= Html::linkedScript( wfAppendQuery( $wgLoadScript, $query ) ) . "\n";
				}
			}
		}
		return $links;
	}

	/**
	 * Gets the global variables and mScripts; also adds userjs to the end if
	 * enabled. Despite the name, these scripts are no longer put in the
	 * <head> but at the bottom of the <body>
	 *
	 * @param $sk Skin object to use
	 * @return String: HTML fragment
	 */
	function getHeadScripts( Skin $sk ) {
		global $wgUser, $wgRequest, $wgUseSiteJs, $wgAllowUserJs;

		// Startup - this will immediately load jquery and mediawiki modules
		$scripts = $this->makeResourceLoaderLink( $sk, 'startup', ResourceLoaderModule::TYPE_SCRIPTS, true );

		// Script and Messages "only" requests
		$scripts .= $this->makeResourceLoaderLink( $sk, $this->getModuleScripts( true ), ResourceLoaderModule::TYPE_SCRIPTS );
		$scripts .= $this->makeResourceLoaderLink( $sk, $this->getModuleMessages( true ), ResourceLoaderModule::TYPE_MESSAGES );

		// Modules requests - let the client calculate dependencies and batch requests as it likes
		$loader = '';
		if ( $this->getModules( true ) ) {
			$loader = Xml::encodeJsCall( 'mw.loader.load', array( $this->getModules( true ) ) ) .
				Xml::encodeJsCall( 'mw.loader.go', array() );
		}
		
		$scripts .= Html::inlineScript(
			ResourceLoader::makeLoaderConditionalScript(
				ResourceLoader::makeConfigSetScript( $this->getJSVars() ) . $loader
			)
		);

		// Legacy Scripts
		$scripts .= "\n" . $this->mScripts;

		$userScripts = array( 'user.options' );

		// Add site JS if enabled
		if ( $wgUseSiteJs ) {
			$scripts .= $this->makeResourceLoaderLink( $sk, 'site', ResourceLoaderModule::TYPE_SCRIPTS );
			if( $wgUser->isLoggedIn() ){
				$userScripts[] = 'user.groups';
			}
		}

		// Add user JS if enabled
		if ( $wgAllowUserJs && $wgUser->isLoggedIn() ) {
			$action = $wgRequest->getVal( 'action', 'view' );
			if( $this->mTitle && $this->mTitle->isJsSubpage() && $sk->userCanPreview( $action ) ) {
				# XXX: additional security check/prompt?
				$scripts .= Html::inlineScript( "\n" . $wgRequest->getText( 'wpTextbox1' ) . "\n" ) . "\n";
			} else {
				# FIXME: this means that User:Me/Common.js doesn't load when previewing
				# User:Me/Vector.js, and vice versa (bug26283)
				$userScripts[] = 'user';
			}
		}
		$scripts .= $this->makeResourceLoaderLink( $sk, $userScripts, ResourceLoaderModule::TYPE_SCRIPTS );

		return $scripts;
	}

	/**
	 * Get an array containing global JS variables
	 * 
	 * Do not add things here which can be evaluated in
	 * ResourceLoaderStartupScript - in other words, without state.
	 * You will only be adding bloat to the page and causing page caches to
	 * have to be purged on configuration changes.
	 */
	protected function getJSVars() {
		global $wgUser, $wgRequest, $wgUseAjax, $wgEnableMWSuggest, $wgContLang;

		$title = $this->getTitle();
		$ns = $title->getNamespace();
		$nsname = MWNamespace::exists( $ns ) ? MWNamespace::getCanonicalName( $ns ) : $title->getNsText();

		$vars = array(
			'wgCanonicalNamespace' => $nsname,
			'wgCanonicalSpecialPageName' => $ns == NS_SPECIAL ?
				SpecialPage::resolveAlias( $title->getDBkey() ) : false, # bug 21115
			'wgNamespaceNumber' => $title->getNamespace(),
			'wgPageName' => $title->getPrefixedDBKey(),
			'wgTitle' => $title->getText(),
			'wgCurRevisionId' => $title->getLatestRevID(),
			'wgArticleId' => $title->getArticleId(),
			'wgIsArticle' => $this->isArticle(),
			'wgAction' => $wgRequest->getText( 'action', 'view' ),
			'wgUserName' => $wgUser->isAnon() ? null : $wgUser->getName(),
			'wgUserGroups' => $wgUser->getEffectiveGroups(),
			'wgCategories' => $this->getCategories(),
			'wgBreakFrames' => $this->getFrameOptions() == 'DENY',
		);
		if ( $wgContLang->hasVariants() ) {
			$vars['wgUserVariant'] = $wgContLang->getPreferredVariant();
		}
		foreach ( $title->getRestrictionTypes() as $type ) {
			$vars['wgRestriction' . ucfirst( $type )] = $title->getRestrictions( $type );
		}
		if ( $wgUseAjax && $wgEnableMWSuggest && !$wgUser->getOption( 'disablesuggest', false ) ) {
			$vars['wgSearchNamespaces'] = SearchEngine::userNamespaces( $wgUser );
		}
		
		// Allow extensions to add their custom variables to the global JS variables
		wfRunHooks( 'MakeGlobalVariablesScript', array( &$vars ) );
		
		return $vars;
	}

	/**
	 * Add default \<meta\> tags
	 */
	protected function addDefaultMeta() {
		global $wgVersion, $wgHtml5;

		static $called = false;
		if ( $called ) {
			# Don't run this twice
			return;
		}
		$called = true;

		if ( !$wgHtml5 ) {
			$this->addMeta( 'http:Content-Style-Type', 'text/css' ); // bug 15835
		}
		$this->addMeta( 'generator', "MediaWiki $wgVersion" );

		$p = "{$this->mIndexPolicy},{$this->mFollowPolicy}";
		if( $p !== 'index,follow' ) {
			// http://www.robotstxt.org/wc/meta-user.html
			// Only show if it's different from the default robots policy
			$this->addMeta( 'robots', $p );
		}

		if ( count( $this->mKeywords ) > 0 ) {
			$strip = array(
				"/<.*?" . ">/" => '',
				"/_/" => ' '
			);
			$this->addMeta(
				'keywords',
				preg_replace(
					array_keys( $strip ),
					array_values( $strip ),
					implode( ',', $this->mKeywords )
				)
			);
		}
	}

	/**
	 * @return string HTML tag links to be put in the header.
	 */
	public function getHeadLinks( Skin $sk ) {
		global $wgFeed;

		// Ideally this should happen earlier, somewhere. :P
		$this->addDefaultMeta();

		$tags = array();

		foreach ( $this->mMetatags as $tag ) {
			if ( 0 == strcasecmp( 'http:', substr( $tag[0], 0, 5 ) ) ) {
				$a = 'http-equiv';
				$tag[0] = substr( $tag[0], 5 );
			} else {
				$a = 'name';
			}
			$tags[] = Html::element( 'meta',
				array(
					$a => $tag[0],
					'content' => $tag[1]
				)
			);
		}
		foreach ( $this->mLinktags as $tag ) {
			$tags[] = Html::element( 'link', $tag );
		}

		if( $wgFeed ) {
			foreach( $this->getSyndicationLinks() as $format => $link ) {
				# Use the page name for the title (accessed through $wgTitle since
				# there's no other way).  In principle, this could lead to issues
				# with having the same name for different feeds corresponding to
				# the same page, but we can't avoid that at this low a level.

				$tags[] = $this->feedLink(
					$format,
					$link,
					# Used messages: 'page-rss-feed' and 'page-atom-feed' (for an easier grep)
					wfMsg( "page-{$format}-feed", $this->getTitle()->getPrefixedText() )
				);
			}

			# Recent changes feed should appear on every page (except recentchanges,
			# that would be redundant). Put it after the per-page feed to avoid
			# changing existing behavior. It's still available, probably via a
			# menu in your browser. Some sites might have a different feed they'd
			# like to promote instead of the RC feed (maybe like a "Recent New Articles"
			# or "Breaking news" one). For this, we see if $wgOverrideSiteFeed is defined.
			# If so, use it instead.

			global $wgOverrideSiteFeed, $wgSitename, $wgAdvertisedFeedTypes;
			$rctitle = SpecialPage::getTitleFor( 'Recentchanges' );

			if ( $wgOverrideSiteFeed ) {
				foreach ( $wgOverrideSiteFeed as $type => $feedUrl ) {
					$tags[] = $this->feedLink(
						$type,
						htmlspecialchars( $feedUrl ),
						wfMsg( "site-{$type}-feed", $wgSitename )
					);
				}
			} elseif ( $this->getTitle()->getPrefixedText() != $rctitle->getPrefixedText() ) {
				foreach ( $wgAdvertisedFeedTypes as $format ) {
					$tags[] = $this->feedLink(
						$format,
						$rctitle->getLocalURL( "feed={$format}" ),
						wfMsg( "site-{$format}-feed", $wgSitename ) # For grep: 'site-rss-feed', 'site-atom-feed'.
					);
				}
			}
		}
		return implode( "\n", $tags );
	}

	/**
	 * Generate a <link rel/> for a feed.
	 *
	 * @param $type String: feed type
	 * @param $url String: URL to the feed
	 * @param $text String: value of the "title" attribute
	 * @return String: HTML fragment
	 */
	private function feedLink( $type, $url, $text ) {
		return Html::element( 'link', array(
			'rel' => 'alternate',
			'type' => "application/$type+xml",
			'title' => $text,
			'href' => $url )
		);
	}

	/**
	 * Add a local or specified stylesheet, with the given media options.
	 * Meant primarily for internal use...
	 *
	 * @param $style String: URL to the file
	 * @param $media String: to specify a media type, 'screen', 'printable', 'handheld' or any.
	 * @param $condition String: for IE conditional comments, specifying an IE version
	 * @param $dir String: set to 'rtl' or 'ltr' for direction-specific sheets
	 */
	public function addStyle( $style, $media = '', $condition = '', $dir = '' ) {
		$options = array();
		// Even though we expect the media type to be lowercase, but here we
		// force it to lowercase to be safe.
		if( $media ) {
			$options['media'] = $media;
		}
		if( $condition ) {
			$options['condition'] = $condition;
		}
		if( $dir ) {
			$options['dir'] = $dir;
		}
		$this->styles[$style] = $options;
	}

	/**
	 * Adds inline CSS styles
	 * @param $style_css Mixed: inline CSS
	 */
	public function addInlineStyle( $style_css ){
		$this->mInlineStyles .= Html::inlineStyle( $style_css );
	}

	/**
	 * Build a set of <link>s for the stylesheets specified in the $this->styles array.
	 * These will be applied to various media & IE conditionals.
	 * @param $sk Skin object
	 */
	public function buildCssLinks( $sk ) {
		$ret = '';
		// Add ResourceLoader styles
		// Split the styles into four groups
		$styles = array( 'other' => array(), 'user' => array(), 'site' => array(), 'private' => array() );
		$resourceLoader = $this->getResourceLoader();
		foreach ( $this->getModuleStyles() as $name ) {
			$group = $resourceLoader->getModule( $name )->getGroup();
			// Modules in groups named "other" or anything different than "user", "site" or "private"
			// will be placed in the "other" group
			$styles[isset( $styles[$group] ) ? $group : 'other'][] = $name;
		}

		// We want site, private and user styles to override dynamically added styles from modules, but we want
		// dynamically added styles to override statically added styles from other modules. So the order
		// has to be other, dynamic, site, private, user
		// Add statically added styles for other modules
		$ret .= $this->makeResourceLoaderLink( $sk, $styles['other'], ResourceLoaderModule::TYPE_STYLES );
		// Add normal styles added through addStyle()/addInlineStyle() here
		$ret .= implode( "\n", $this->buildCssLinksArray() ) . $this->mInlineStyles;
		// Add marker tag to mark the place where the client-side loader should inject dynamic styles
		// We use a <meta> tag with a made-up name for this because that's valid HTML
		$ret .= Html::element( 'meta', array( 'name' => 'ResourceLoaderDynamicStyles', 'content' => '' ) );
		
		// Add site, private and user styles
		// 'private' at present only contains user.options, so put that before 'user'
		// Any future private modules will likely have a similar user-specific character
		foreach ( array( 'site', 'private', 'user' ) as $group ) {
			$ret .= $this->makeResourceLoaderLink( $sk, $styles[$group],
					ResourceLoaderModule::TYPE_STYLES
			);
		}
		return $ret;
	}

	public function buildCssLinksArray() {
		$links = array();
		foreach( $this->styles as $file => $options ) {
			$link = $this->styleLink( $file, $options );
			if( $link ) {
				$links[$file] = $link;
			}
		}
		return $links;
	}

	/**
	 * Generate \<link\> tags for stylesheets
	 *
	 * @param $style String: URL to the file
	 * @param $options Array: option, can contain 'condition', 'dir', 'media'
	 *                 keys
	 * @return String: HTML fragment
	 */
	protected function styleLink( $style, $options ) {
		if( isset( $options['dir'] ) ) {
			$siteDir = wfUILang()->getDir();
			if( $siteDir != $options['dir'] ) {
				return '';
			}
		}

		if( isset( $options['media'] ) ) {
			$media = self::transformCssMedia( $options['media'] );
			if( is_null( $media ) ) {
				return '';
			}
		} else {
			$media = 'all';
		}

		if( substr( $style, 0, 1 ) == '/' ||
			substr( $style, 0, 5 ) == 'http:' ||
			substr( $style, 0, 6 ) == 'https:' ) {
			$url = $style;
		} else {
			global $wgStylePath, $wgStyleVersion;
			$url = $wgStylePath . '/' . $style . '?' . $wgStyleVersion;
		}

		$link = Html::linkedStyle( $url, $media );

		if( isset( $options['condition'] ) ) {
			$condition = htmlspecialchars( $options['condition'] );
			$link = "<!--[if $condition]>$link<![endif]-->";
		}
		return $link;
	}

	/**
	 * Transform "media" attribute based on request parameters
	 *
	 * @param $media String: current value of the "media" attribute
	 * @return String: modified value of the "media" attribute
	 */
	public static function transformCssMedia( $media ) {
		global $wgRequest, $wgHandheldForIPhone;

		// Switch in on-screen display for media testing
		$switches = array(
			'printable' => 'print',
			'handheld' => 'handheld',
		);
		foreach( $switches as $switch => $targetMedia ) {
			if( $wgRequest->getBool( $switch ) ) {
				if( $media == $targetMedia ) {
					$media = '';
				} elseif( $media == 'screen' ) {
					return null;
				}
			}
		}

		// Expand longer media queries as iPhone doesn't grok 'handheld'
		if( $wgHandheldForIPhone ) {
			$mediaAliases = array(
				'screen' => 'screen and (min-device-width: 481px)',
				'handheld' => 'handheld, only screen and (max-device-width: 480px)',
			);

			if( isset( $mediaAliases[$media] ) ) {
				$media = $mediaAliases[$media];
			}
		}

		return $media;
	}

	/**
	 * Turn off regular page output and return an error reponse
	 * for when rate limiting has triggered.
	 */
	public function rateLimited() {
		$this->setPageTitle( wfMsg( 'actionthrottled' ) );
		$this->setRobotPolicy( 'noindex,follow' );
		$this->setArticleRelated( false );
		$this->enableClientCache( false );
		$this->mRedirect = '';
		$this->clearHTML();
		$this->setStatusCode( 503 );
		$this->addWikiMsg( 'actionthrottledtext' );

		$this->returnToMain( null, $this->getTitle() );
	}

	/**
	 * Show a warning about slave lag
	 *
	 * If the lag is higher than $wgSlaveLagCritical seconds,
	 * then the warning is a bit more obvious. If the lag is
	 * lower than $wgSlaveLagWarning, then no warning is shown.
	 *
	 * @param $lag Integer: slave lag
	 */
	public function showLagWarning( $lag ) {
		global $wgSlaveLagWarning, $wgSlaveLagCritical, $wgLang;
		if( $lag >= $wgSlaveLagWarning ) {
			$message = $lag < $wgSlaveLagCritical
				? 'lag-warn-normal'
				: 'lag-warn-high';
			$wrap = Html::rawElement( 'div', array( 'class' => "mw-{$message}" ), "\n$1\n" );
			$this->wrapWikiMsg( "$wrap\n", array( $message, $wgLang->formatNum( $lag ) ) );
		}
	}

	/**
	 * Add a wikitext-formatted message to the output.
	 * This is equivalent to:
	 *
	 *    $wgOut->addWikiText( wfMsgNoTrans( ... ) )
	 */
	public function addWikiMsg( /*...*/ ) {
		$args = func_get_args();
		$name = array_shift( $args );
		$this->addWikiMsgArray( $name, $args );
	}

	/**
	 * Add a wikitext-formatted message to the output.
	 * Like addWikiMsg() except the parameters are taken as an array
	 * instead of a variable argument list.
	 *
	 * $options is passed through to wfMsgExt(), see that function for details.
	 */
	public function addWikiMsgArray( $name, $args, $options = array() ) {
		$options[] = 'parse';
		$text = wfMsgExt( $name, $options, $args );
		$this->addHTML( $text );
	}

	/**
	 * This function takes a number of message/argument specifications, wraps them in
	 * some overall structure, and then parses the result and adds it to the output.
	 *
	 * In the $wrap, $1 is replaced with the first message, $2 with the second, and so
	 * on. The subsequent arguments may either be strings, in which case they are the
	 * message names, or arrays, in which case the first element is the message name,
	 * and subsequent elements are the parameters to that message.
	 *
	 * The special named parameter 'options' in a message specification array is passed
	 * through to the $options parameter of wfMsgExt().
	 *
	 * Don't use this for messages that are not in users interface language.
	 *
	 * For example:
	 *
	 *    $wgOut->wrapWikiMsg( "<div class='error'>\n$1\n</div>", 'some-error' );
	 *
	 * Is equivalent to:
	 *
	 *    $wgOut->addWikiText( "<div class='error'>\n" . wfMsgNoTrans( 'some-error' ) . "\n</div>" );
	 *
	 * The newline after opening div is needed in some wikitext. See bug 19226.
	 */
	public function wrapWikiMsg( $wrap /*, ...*/ ) {
		$msgSpecs = func_get_args();
		array_shift( $msgSpecs );
		$msgSpecs = array_values( $msgSpecs );
		$s = $wrap;
		foreach ( $msgSpecs as $n => $spec ) {
			$options = array();
			if ( is_array( $spec ) ) {
				$args = $spec;
				$name = array_shift( $args );
				if ( isset( $args['options'] ) ) {
					$options = $args['options'];
					unset( $args['options'] );
				}
			}  else {
				$args = array();
				$name = $spec;
			}
			$s = str_replace( '$' . ( $n + 1 ), wfMsgExt( $name, $options, $args ), $s );
		}
		$this->addWikiText( $s );
	}

	/**
	 * Include jQuery core. Use this to avoid loading it multiple times
	 * before we get a usable script loader.
	 *
	 * @param $modules Array: list of jQuery modules which should be loaded
	 * @return Array: the list of modules which were not loaded.
	 * @since 1.16
	 * @deprecated @since 1.17
	 */
	public function includeJQuery( $modules = array() ) {
		return array();
	}

}
