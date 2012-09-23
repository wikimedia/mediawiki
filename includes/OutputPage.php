<?php
/**
 * Preparation for the final page rendering.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * This class should be covered by a general architecture document which does
 * not exist as of January 2011.  This is one of the Core classes and should
 * be read at least once by any new developers.
 *
 * This class is used to prepare the final rendering. A skin is then
 * applied to the output parameters (links, javascript, html, categories ...).
 *
 * @todo FIXME: Another class handles sending the whole page to the client.
 *
 * Some comments comes from a pairing session between Zak Greant and Antoine Musso
 * in November 2010.
 *
 * @todo document
 */
class OutputPage extends ContextSource {
	/// Should be private. Used with addMeta() which adds "<meta>"
	var $mMetatags = array();

	/// "<meta keywords='stuff'>" most of the time the first 10 links to an article
	var $mKeywords = array();

	var $mLinktags = array();

	/// Additional stylesheets. Looks like this is for extensions. Might be replaced by resource loader.
	var $mExtStyles = array();

	/// Should be private - has getter and setter. Contains the HTML title
	var $mPagetitle = '';

	/// Contains all of the "<body>" content. Should be private we got set/get accessors and the append() method.
	var $mBodytext = '';

	/**
	 * Holds the debug lines that will be output as comments in page source if
	 * $wgDebugComments is enabled. See also $wgShowDebug.
	 * @deprecated since 1.20; use MWDebug class instead.
	 */
	public $mDebugtext = '';

	/// Should be private. Stores contents of "<title>" tag
	var $mHTMLtitle = '';

	/// Should be private. Is the displayed content related to the source of the corresponding wiki article.
	var $mIsarticle = false;

	/**
	 * Should be private. Has get/set methods properly documented.
	 * Stores "article flag" toggle.
	 */
	var $mIsArticleRelated = true;

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
	private $mSubtitle = array();

	var $mRedirect = '';
	var $mStatusCode;

	/**
	 * mLastModified and mEtag are used for sending cache control.
	 * The whole caching system should probably be moved into its own class.
	 */
	var $mLastModified = '';

	/**
	 * Should be private. No getter but used in sendCacheControl();
	 * Contains an HTTP Entity Tags (see RFC 2616 section 3.13) which is used
	 * as a unique identifier for the content. It is later used by the client
	 * to compare its cached version with the server version. Client sends
	 * headers If-Match and If-None-Match containing its locally cached ETAG value.
	 *
	 * To get more information, you will have to look at HTTP/1.1 protocol which
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
	 * mScripts content is inserted as is in "<head>" by Skin. This might
	 * contains either a link to a stylesheet or inline css.
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

	/// Array of elements in "<head>". Parser might add its own headers!
	var $mHeadItems = array();

	// @todo FIXME: Next variables probably comes from the resource loader
	var $mModules = array(), $mModuleScripts = array(), $mModuleStyles = array(), $mModuleMessages = array();
	var $mResourceLoader;
	var $mJsConfigVars = array();

	/** @todo FIXME: Is this still used ?*/
	var $mInlineMsg = array();

	var $mTemplateIds = array();
	var $mImageTimeKeys = array();

	var $mRedirectCode = '';

	var $mFeedLinksAppendQuery = null;

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
	 * lazy initialised, use parserOptions()
	 * @var ParserOptions
	 */
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
	 * if we had "<gallery>". Used directly in CategoryPage.php
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
	private $mRevisionTimestamp = null;

	var $mFileVersion = null;

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
	);

	/**
	 * If the current page was reached through a redirect, $mRedirectedFrom contains the Title
	 * of the redirect.
	 *
	 * @var Title
	 */
	private $mRedirectedFrom = null;

	/**
	 * Constructor for OutputPage. This should not be called directly.
	 * Instead a new RequestContext should be created and it will implicitly create
	 * a OutputPage tied to that context.
	 */
	function __construct( IContextSource $context = null ) {
		if ( $context === null ) {
			# Extensions should use `new RequestContext` instead of `new OutputPage` now.
			wfDeprecated( __METHOD__ );
		} else {
			$this->setContext( $context );
		}
	}

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
	 * Add a new "<meta>" tag
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
		$linkarr['rel'] = $this->getMetadataAttribute();
		$this->addLink( $linkarr );
	}

	/**
	 * Get the value of the "rel" attribute for metadata links
	 *
	 * @return String
	 */
	public function getMetadataAttribute() {
		# note: buggy CC software only reads first "meta" link
		static $haveMeta = false;
		if ( $haveMeta ) {
			return 'alternate meta';
		} else {
			$haveMeta = true;
			return 'meta';
		}
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
	 * @param $script String: JavaScript text, no "<script>" tags
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
	 * Filter an array of modules to remove insufficiently trustworthy members, and modules
	 * which are no longer registered (eg a page is cached before an extension is disabled)
	 * @param $modules Array
	 * @param $position String if not null, only return modules with this position
	 * @param $type string
	 * @return Array
	 */
	protected function filterModules( $modules, $position = null, $type = ResourceLoaderModule::TYPE_COMBINED ){
		$resourceLoader = $this->getResourceLoader();
		$filteredModules = array();
		foreach( $modules as $val ){
			$module = $resourceLoader->getModule( $val );
			if( $module instanceof ResourceLoaderModule
				&& $module->getOrigin() <= $this->getAllowedModules( $type )
				&& ( is_null( $position ) || $module->getPosition() == $position ) )
			{
				$filteredModules[] = $val;
			}
		}
		return $filteredModules;
	}

	/**
	 * Get the list of modules to include on this page
	 *
	 * @param $filter Bool whether to filter out insufficiently trustworthy modules
	 * @param $position String if not null, only return modules with this position
	 * @param $param string
	 * @return Array of module names
	 */
	public function getModules( $filter = false, $position = null, $param = 'mModules' ) {
		$modules = array_values( array_unique( $this->$param ) );
		return $filter
			? $this->filterModules( $modules, $position )
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
	 *
	 * @param $filter
	 * @param $position
	 *
	 * @return array of module names
	 */
	public function getModuleScripts( $filter = false, $position = null ) {
		return $this->getModules( $filter, $position, 'mModuleScripts' );
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
	 * @param $filter
	 * @param $position
	 *
	 * @return Array of module names
	 */
	public function getModuleStyles( $filter = false, $position = null ) {
		return $this->getModules( $filter,  $position, 'mModuleStyles' );
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
	 * @param $filter
	 * @param $position
	 *
	 * @return Array of module names
	 */
	public function getModuleMessages( $filter = false, $position = null ) {
		return $this->getModules( $filter, $position, 'mModuleMessages' );
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
	 * Get an array of head items
	 *
	 * @return Array
	 */
	function getHeadItemsArray() {
		return $this->mHeadItems;
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
	 * @param $timestamp string
	 *
	 * @return Boolean: true iff cache-ok headers was sent.
	 */
	public function checkLastModified( $timestamp ) {
		global $wgCachePages, $wgCacheEpoch;

		if ( !$timestamp || $timestamp == '19700101000000' ) {
			wfDebug( __METHOD__ . ": CACHE DISABLED, NO TIMESTAMP\n" );
			return false;
		}
		if( !$wgCachePages ) {
			wfDebug( __METHOD__ . ": CACHE DISABLED\n", false );
			return false;
		}
		if( $this->getUser()->getOption( 'nocache' ) ) {
			wfDebug( __METHOD__ . ": USER DISABLED CACHE\n", false );
			return false;
		}

		$timestamp = wfTimestamp( TS_MW, $timestamp );
		$modifiedTimes = array(
			'page' => $timestamp,
			'user' => $this->getUser()->getTouched(),
			'epoch' => $wgCacheEpoch
		);
		wfRunHooks( 'OutputPageCheckLastModified', array( &$modifiedTimes ) );

		$maxModified = max( $modifiedTimes );
		$this->mLastModified = wfTimestamp( TS_RFC2822, $maxModified );

		$clientHeader = $this->getRequest()->getHeader( 'If-Modified-Since' );
		if ( $clientHeader === false ) {
			wfDebug( __METHOD__ . ": client did not send If-Modified-Since header\n", false );
			return false;
		}

		# IE sends sizes after the date like this:
		# Wed, 20 Aug 2003 06:51:19 GMT; length=5202
		# this breaks strtotime().
		$clientHeader = preg_replace( '/;.*$/', '', $clientHeader );

		wfSuppressWarnings(); // E_STRICT system time bitching
		$clientHeaderTime = strtotime( $clientHeader );
		wfRestoreWarnings();
		if ( !$clientHeaderTime ) {
			wfDebug( __METHOD__ . ": unable to parse the client's If-Modified-Since header: $clientHeader\n" );
			return false;
		}
		$clientHeaderTime = wfTimestamp( TS_MW, $clientHeaderTime );

		# Make debug info
		$info = '';
		foreach ( $modifiedTimes as $name => $value ) {
			if ( $info !== '' ) {
				$info .= ', ';
			}
			$info .= "$name=" . wfTimestamp( TS_ISO_8601, $value );
		}

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
		$this->getRequest()->response()->header( "HTTP/1.1 304 Not Modified" );
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
	 * "HTML title" means the contents of "<title>".
	 * It is stored as plain, unescaped text and will be run through htmlspecialchars in the skin file.
	 *
	 * @param $name string
	 */
	public function setHTMLTitle( $name ) {
		if ( $name instanceof Message ) {
			$this->mHTMLtitle = $name->setContext( $this->getContext() )->text();
		} else {
			$this->mHTMLtitle = $name;
		}
	}

	/**
	 * Return the "HTML title", i.e. the content of the "<title>" tag.
	 *
	 * @return String
	 */
	public function getHTMLTitle() {
		return $this->mHTMLtitle;
	}

	/**
	 * Set $mRedirectedFrom, the Title of the page which redirected us to the current page.
	 *
	 * @param $t Title
	 */
	public function setRedirectedFrom( $t ) {
		$this->mRedirectedFrom = $t;
	}

	/**
	 * "Page title" means the contents of \<h1\>. It is stored as a valid HTML fragment.
	 * This function allows good tags like \<sup\> in the \<h1\> tag, but not bad tags like \<script\>.
	 * This function automatically sets \<title\> to the same content as \<h1\> but with all tags removed.
	 * Bad tags that were escaped in \<h1\> will still be escaped in \<title\>, and good tags like \<i\> will be dropped entirely.
	 *
	 * @param $name string|Message
	 */
	public function setPageTitle( $name ) {
		if ( $name instanceof Message ) {
			$name = $name->setContext( $this->getContext() )->text();
		}

		# change "<script>foo&bar</script>" to "&lt;script&gt;foo&amp;bar&lt;/script&gt;"
		# but leave "<i>foobar</i>" alone
		$nameWithTags = Sanitizer::normalizeCharReferences( Sanitizer::removeHTMLtags( $name ) );
		$this->mPagetitle = $nameWithTags;

		# change "<i>foo&amp;bar</i>" to "foo&bar"
		$this->setHTMLTitle( $this->msg( 'pagetitle' )->rawParams( Sanitizer::stripAllTags( $nameWithTags ) ) );
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
	public function setTitle( Title $t ) {
		$this->getContext()->setTitle( $t );
	}


	/**
	 * Replace the subtile with $str
	 *
	 * @param $str String|Message: new value of the subtitle
	 */
	public function setSubtitle( $str ) {
		$this->clearSubtitle();
		$this->addSubtitle( $str );
	}

	/**
	 * Add $str to the subtitle
	 *
	 * @deprecated in 1.19; use addSubtitle() instead
	 * @param $str String|Message to add to the subtitle
	 */
	public function appendSubtitle( $str ) {
		$this->addSubtitle( $str );
	}

	/**
	 * Add $str to the subtitle
	 *
	 * @param $str String|Message to add to the subtitle
	 */
	public function addSubtitle( $str ) {
		if ( $str instanceof Message ) {
			$this->mSubtitle[] = $str->setContext( $this->getContext() )->parse();
		} else {
			$this->mSubtitle[] = $str;
		}
	}

	/**
	 * Add a subtitle containing a backlink to a page
	 *
	 * @param $title Title to link to
	 */
	public function addBacklinkSubtitle( Title $title ) {
		$query = array();
		if ( $title->isRedirect() ) {
			$query['redirect'] = 'no';
		}
		$this->addSubtitle( $this->msg( 'backlinksubtitle' )->rawParams( Linker::link( $title, null, array(), $query ) ) );
	}

	/**
	 * Clear the subtitles
	 */
	public function clearSubtitle() {
		$this->mSubtitle = array();
	}

	/**
	 * Get the subtitle
	 *
	 * @return String
	 */
	public function getSubtitle() {
		return implode( "<br />\n\t\t\t\t", $this->mSubtitle );
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
	 * @param $newLinkArray array Associative array mapping language code to the page
	 *                      name
	 */
	public function addLanguageLinks( $newLinkArray ) {
		$this->mLanguageLinks += $newLinkArray;
	}

	/**
	 * Reset the language links and add new language links
	 *
	 * @param $newLinkArray array Associative array mapping language code to the page
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
		global $wgContLang;

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
				$this->mCategoryLinks[$type][] = Linker::link( $title, $text );
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
	 * @deprecated since 1.18 Load modules with ResourceLoader, and origin and
	 *     trustworthiness is identified and enforced automagically.
	 *     Will be removed in 1.20.
	 * @return Boolean
	 */
	public function isUserJsAllowed() {
		wfDeprecated( __METHOD__, '1.18' );
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
	 * Shortcut for adding an Html::element via addHTML.
	 *
	 * @since 1.19
	 *
	 * @param $element string
	 * @param $attribs array
	 * @param $contents string
	 */
	public function addElement( $element, $attribs = array(), $contents = '' ) {
		$this->addHTML( Html::element( $element, $attribs, $contents ) );
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
	 * Get/set the ParserOptions object to use for wikitext parsing
	 *
	 * @param $options ParserOptions|null either the ParserOption to use or null to only get the
	 *                 current ParserOption object
	 * @return ParserOptions object
	 */
	public function parserOptions( $options = null ) {
		if ( !$this->mParserOptions ) {
			$this->mParserOptions = ParserOptions::newFromContext( $this->getContext() );
			$this->mParserOptions->setEditSection( false );
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
	 * Get the displayed revision ID
	 *
	 * @return Integer
	 */
	public function getRevisionId() {
		return $this->mRevisionId;
	}

	/**
	 * Set the timestamp of the revision which will be displayed. This is used
	 * to avoid a extra DB call in Skin::lastModified().
	 *
	 * @param $timestamp Mixed: string, or null
	 * @return Mixed: previous value
	 */
	public function setRevisionTimestamp( $timestamp) {
		return wfSetVar( $this->mRevisionTimestamp, $timestamp );
	}

	/**
	 * Get the timestamp of displayed revision.
	 * This will be null if not filled by setRevisionTimestamp().
	 *
	 * @return String or null
	 */
	public function getRevisionTimestamp() {
		return $this->mRevisionTimestamp;
	}

	/**
	 * Set the displayed file version
	 *
	 * @param $file File|bool
	 * @return Mixed: previous value
	 */
	public function setFileVersion( $file ) {
		$val = null;
		if ( $file instanceof File && $file->exists() ) {
			$val = array( 'time' => $file->getTimestamp(), 'sha1' => $file->getSha1() );
		}
		return wfSetVar( $this->mFileVersion, $val, true );
	}

	/**
	 * Get the displayed file version
	 *
	 * @return Array|null ('time' => MW timestamp, 'sha1' => sha1)
	 */
	public function getFileVersion() {
		return $this->mFileVersion;
	}

	/**
	 * Get the templates used on this page
	 *
	 * @return Array (namespace => dbKey => revId)
	 * @since 1.18
	 */
	public function getTemplateIds() {
		return $this->mTemplateIds;
	}

	/**
	 * Get the files used on this page
	 *
	 * @return Array (dbKey => array('time' => MW timestamp or null, 'sha1' => sha1 or ''))
	 * @since 1.18
	 */
	public function getFileSearchOptions() {
		return $this->mImageTimeKeys;
	}

	/**
	 * Convert wikitext to HTML and add it to the buffer
	 * Default assumes that the current page title will be used.
	 *
	 * @param $text String
	 * @param $linestart Boolean: is this the start of a line?
	 * @param $interface Boolean: is this text in the user interface language?
	 */
	public function addWikiText( $text, $linestart = true, $interface = true ) {
		$title = $this->getTitle(); // Work arround E_STRICT
		$this->addWikiTextTitle( $text, $title, $linestart, /*tidy*/false, $interface );
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
	 * Add wikitext with a custom Title object and tidy enabled.
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
	 * @param $interface Boolean: whether it is an interface message
	 *								(for example disables conversion)
	 */
	public function addWikiTextTitle( $text, &$title, $linestart, $tidy = false, $interface = false ) {
		global $wgParser;

		wfProfileIn( __METHOD__ );

		$popts = $this->parserOptions();
		$oldTidy = $popts->setTidy( $tidy );
		$popts->setInterfaceMessage( (bool) $interface );

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
		$this->addModuleScripts( $parserOutput->getModuleScripts() );
		$this->addModuleStyles( $parserOutput->getModuleStyles() );
		$this->addModuleMessages( $parserOutput->getModuleMessages() );

		// Template versioning...
		foreach ( (array)$parserOutput->getTemplateIds() as $ns => $dbks ) {
			if ( isset( $this->mTemplateIds[$ns] ) ) {
				$this->mTemplateIds[$ns] = $dbks + $this->mTemplateIds[$ns];
			} else {
				$this->mTemplateIds[$ns] = $dbks;
			}
		}
		// File versioning...
		foreach ( (array)$parserOutput->getFileSearchOptions() as $dbk => $data ) {
			$this->mImageTimeKeys[$dbk] = $data;
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
	 *                   words like GRAMMAR and PLURAL. This also disables
	 *					 LanguageConverter.
	 * @param $language  Language object: target language object, will override
	 *                   $interface
	 * @return String: HTML
	 */
	public function parse( $text, $linestart = true, $interface = false, $language = null ) {
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
	 * @param $state bool
	 *
	 * @return bool
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
	 * Check if the request has a cache-varying cookie header
	 * If it does, it's very important that we don't allow public caching
	 *
	 * @return Boolean
	 */
	function haveCacheVaryCookies() {
		$cookieHeader = $this->getRequest()->getHeader( 'cookie' );
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
	 * @todo FIXME: Document the $option parameter; it appears to be for
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
		$this->mVaryHeader[$header] = array_unique( (array)$this->mVaryHeader[$header] );
	}

	/**
	 * Return a Vary: header on which to vary caches. Based on the keys of $mVaryHeader,
	 * such as Accept-Encoding or Cookie
	 *
	 * @return String
	 */
	public function getVaryHeader() {
		return 'Vary: ' . join( ', ', array_keys( $this->mVaryHeader ) );
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
			if ( is_array( $option ) && count( $option ) > 0 ) {
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
		$lang = $this->getTitle()->getPageLanguage();
		if( !$this->getRequest()->getCheck( 'variant' ) && $lang->hasVariants() ) {
			$variants = $lang->getVariants();
			$aloption = array();
			foreach ( $variants as $variant ) {
				if( $variant === $lang->getCode() ) {
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
	 *
	 * @param $enable bool
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
	 *
	 * @return string
	 */
	public function getFrameOptions() {
		global $wgBreakFrames, $wgEditPageFrameOptions;
		if ( $wgBreakFrames ) {
			return 'DENY';
		} elseif ( $this->mPreventClickjacking && $wgEditPageFrameOptions ) {
			return $wgEditPageFrameOptions;
		}
		return false;
	}

	/**
	 * Send cache control HTTP headers
	 */
	public function sendCacheControl() {
		global $wgUseSquid, $wgUseESI, $wgUseETag, $wgSquidMaxage, $wgUseXVO;

		$response = $this->getRequest()->response();
		if ( $wgUseETag && $this->mETag ) {
			$response->header( "ETag: $this->mETag" );
		}

		$this->addVaryHeader( 'Cookie' );
		$this->addAcceptLanguage();

		# don't serve compressed data to clients who can't handle it
		# maintain different caches for logged-in users and non-logged in ones
		$response->header( $this->getVaryHeader() );

		if ( $wgUseXVO ) {
			# Add an X-Vary-Options header for Squid with Wikimedia patches
			$response->header( $this->getXVO() );
		}

		if( $this->mEnableClientCache ) {
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
	 *
	 * @deprecated since 1.18 Use HttpStatus::getMessage() instead.
	 */
	public static function getStatusMessage( $code ) {
		wfDeprecated( __METHOD__ );
		return HttpStatus::getMessage( $code );
	}

	/**
	 * Finally, all the text has been munged and accumulated into
	 * the object, let's actually output it:
	 */
	public function output() {
		global $wgLanguageCode, $wgDebugRedirects, $wgMimeType, $wgVaryOnXFP;

		if( $this->mDoNothing ) {
			return;
		}

		wfProfileIn( __METHOD__ );

		$response = $this->getRequest()->response();

		if ( $this->mRedirect != '' ) {
			# Standards require redirect URLs to be absolute
			$this->mRedirect = wfExpandUrl( $this->mRedirect, PROTO_CURRENT );

			$redirect = $this->mRedirect;
			$code = $this->mRedirectCode;

			if( wfRunHooks( "BeforePageRedirect", array( $this, &$redirect, &$code ) ) ) {
				if( $code == '301' || $code == '303' ) {
					if( !$wgDebugRedirects ) {
						$message = HttpStatus::getMessage( $code );
						$response->header( "HTTP/1.1 $code $message" );
					}
					$this->mLastModified = wfTimestamp( TS_RFC2822 );
				}
				if ( $wgVaryOnXFP ) {
					$this->addVaryHeader( 'X-Forwarded-Proto' );
				}
				$this->sendCacheControl();

				$response->header( "Content-Type: text/html; charset=utf-8" );
				if( $wgDebugRedirects ) {
					$url = htmlspecialchars( $redirect );
					print "<html>\n<head>\n<title>Redirect</title>\n</head>\n<body>\n";
					print "<p>Location: <a href=\"$url\">$url</a></p>\n";
					print "</body>\n</html>\n";
				} else {
					$response->header( 'Location: ' . $redirect );
				}
			}

			wfProfileOut( __METHOD__ );
			return;
		} elseif ( $this->mStatusCode ) {
			$message = HttpStatus::getMessage( $this->mStatusCode );
			if ( $message ) {
				$response->header( 'HTTP/1.1 ' . $this->mStatusCode . ' ' . $message );
			}
		}

		# Buffer output; final headers may depend on later processing
		ob_start();

		$response->header( "Content-type: $wgMimeType; charset=UTF-8" );
		$response->header( 'Content-language: ' . $wgLanguageCode );

		// Prevent framing, if requested
		$frameOptions = $this->getFrameOptions();
		if ( $frameOptions ) {
			$response->header( "X-Frame-Options: $frameOptions" );
		}

		if ( $this->mArticleBodyOnly ) {
			$this->out( $this->mBodytext );
		} else {
			$this->addDefaultModules();

			$sk = $this->getSkin();

			// Hook that allows last minute changes to the output page, e.g.
			// adding of CSS or Javascript by extensions.
			wfRunHooks( 'BeforePageDisplay', array( &$this, &$sk ) );

			wfProfileIn( 'Output-skin' );
			$sk->outputPage();
			wfProfileOut( 'Output-skin' );
		}

		// This hook allows last minute changes to final overall output by modifying output buffer
		wfRunHooks( 'AfterFinalPageOutput', array( $this ) );

		$this->sendCacheControl();
		ob_end_flush();
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Actually output something with print().
	 *
	 * @param $ins String: the string to output
	 */
	public function out( $ins ) {
		print $ins;
	}

	/**
	 * Produce a "user is blocked" page.
	 * @deprecated since 1.18
	 */
	function blockedPage() {
		throw new UserBlockedError( $this->getUser()->mBlock );
	}

	/**
	 * Prepare this object to display an error page; disable caching and
	 * indexing, clear the current text and redirect, set the page's title
	 * and optionally an custom HTML title (content of the "<title>" tag).
	 *
	 * @param $pageTitle String|Message will be passed directly to setPageTitle()
	 * @param $htmlTitle String|Message will be passed directly to setHTMLTitle();
	 *                   optional, if not passed the "<title>" attribute will be
	 *                   based on $pageTitle
	 */
	public function prepareErrorPage( $pageTitle, $htmlTitle = false ) {
		$this->setPageTitle( $pageTitle );
		if ( $htmlTitle !== false ) {
			$this->setHTMLTitle( $htmlTitle );
		}
		$this->setRobotPolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );
		$this->enableClientCache( false );
		$this->mRedirect = '';
		$this->clearSubtitle();
		$this->clearHTML();
	}

	/**
	 * Output a standard error page
	 *
	 * showErrorPage( 'titlemsg', 'pagetextmsg', array( 'param1', 'param2' ) );
	 * showErrorPage( 'titlemsg', $messageObject );
	 * showErrorPage( $titleMessageObj, $messageObject );
	 *
	 * @param $title Mixed: message key (string) for page title, or a Message object
	 * @param $msg Mixed: message key (string) for page text, or a Message object
	 * @param $params Array: message parameters; ignored if $msg is a Message object
	 */
	public function showErrorPage( $title, $msg, $params = array() ) {
		if( !$title instanceof Message ) {
			$title = $this->msg( $title );
		}

		$this->prepareErrorPage( $title );

		if ( $msg instanceof Message ){
			$this->addHTML( $msg->parse() );
		} else {
			$this->addWikiMsgArray( $msg, $params );
		}

		$this->returnToMain();
	}

	/**
	 * Output a standard permission error page
	 *
	 * @param $errors Array: error message keys
	 * @param $action String: action that was denied or null if unknown
	 */
	public function showPermissionsErrorPage( $errors, $action = null ) {
		global $wgGroupPermissions;

		// For some action (read, edit, create and upload), display a "login to do this action"
		// error if all of the following conditions are met:
		// 1. the user is not logged in
		// 2. the only error is insufficient permissions (i.e. no block or something else)
		// 3. the error can be avoided simply by logging in
		if ( in_array( $action, array( 'read', 'edit', 'createpage', 'createtalk', 'upload' ) )
			&& $this->getUser()->isAnon() && count( $errors ) == 1 && isset( $errors[0][0] )
			&& ( $errors[0][0] == 'badaccess-groups' || $errors[0][0] == 'badaccess-group0' )
			&& ( ( isset( $wgGroupPermissions['user'][$action] ) && $wgGroupPermissions['user'][$action] )
			|| ( isset( $wgGroupPermissions['autoconfirmed'][$action] ) && $wgGroupPermissions['autoconfirmed'][$action] ) )
		) {
			$displayReturnto = null;

			# Due to bug 32276, if a user does not have read permissions,
			# $this->getTitle() will just give Special:Badtitle, which is
			# not especially useful as a returnto parameter. Use the title
			# from the request instead, if there was one.
			$request = $this->getRequest();
			$returnto = Title::newFromURL( $request->getVal( 'title', '' ) );
			if ( $action == 'edit' ) {
				$msg = 'whitelistedittext';
				$displayReturnto = $returnto;
			} elseif ( $action == 'createpage' || $action == 'createtalk' ) {
				$msg = 'nocreatetext';
			} elseif ( $action == 'upload' ) {
				$msg = 'uploadnologintext';
			} else { # Read
				$msg = 'loginreqpagetext';
				$displayReturnto = Title::newMainPage();
			}

			$query = array();

			if ( $returnto ) {
				$query['returnto'] = $returnto->getPrefixedText();

				if ( !$request->wasPosted() ) {
					$returntoquery = $request->getValues();
					unset( $returntoquery['title'] );
					unset( $returntoquery['returnto'] );
					unset( $returntoquery['returntoquery'] );
					$query['returntoquery'] = wfArrayToCGI( $returntoquery );
				}
			}
			$loginLink = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Userlogin' ),
				$this->msg( 'loginreqlink' )->escaped(),
				array(),
				$query
			);

			$this->prepareErrorPage( $this->msg( 'loginreqtitle' ) );
			$this->addHTML( $this->msg( $msg )->rawParams( $loginLink )->parse() );

			# Don't return to a page the user can't read otherwise
			# we'll end up in a pointless loop
			if ( $displayReturnto && $displayReturnto->userCan( 'read', $this->getUser() ) ) {
				$this->returnToMain( null, $displayReturnto );
			}
		} else {
			$this->prepareErrorPage( $this->msg( 'permissionserrors' ) );
			$this->addWikiText( $this->formatPermissionsErrorMessage( $errors, $action ) );
		}
	}

	/**
	 * Display an error page indicating that a given version of MediaWiki is
	 * required to use it
	 *
	 * @param $version Mixed: the version of MediaWiki needed to use the page
	 */
	public function versionRequired( $version ) {
		$this->prepareErrorPage( $this->msg( 'versionrequired', $version ) );

		$this->addWikiMsg( 'versionrequiredtext', $version );
		$this->returnToMain();
	}

	/**
	 * Display an error page noting that a given permission bit is required.
	 * @deprecated since 1.18, just throw the exception directly
	 * @param $permission String: key required
	 */
	public function permissionRequired( $permission ) {
		throw new PermissionsError( $permission );
	}

	/**
	 * Produce the stock "please login to use the wiki" page
	 *
	 * @deprecated in 1.19; throw the exception directly
	 */
	public function loginToUse() {
		throw new PermissionsError( 'read' );
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
			$text = $this->msg( 'permissionserrorstext', count( $errors ) )->plain() . "\n\n";
		} else {
			$action_desc = $this->msg( "action-$action" )->plain();
			$text = $this->msg(
				'permissionserrorstext-withaction',
				count( $errors ),
				$action_desc
			)->plain() . "\n\n";
		}

		if ( count( $errors ) > 1 ) {
			$text .= '<ul class="permissions-errors">' . "\n";

			foreach( $errors as $error ) {
				$text .= '<li>';
				$text .= call_user_func_array( array( $this, 'msg' ), $error )->plain();
				$text .= "</li>\n";
			}
			$text .= '</ul>';
		} else {
			$text .= "<div class=\"permissions-errors\">\n" .
					call_user_func_array( array( $this, 'msg' ), reset( $errors ) )->plain() .
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
				$this->setPageTitle( $this->msg( 'viewsource-title', $this->getTitle()->getPrefixedText() ) );
				$this->addBacklinkSubtitle( $this->getTitle() );
			} else {
				$this->setPageTitle( $this->msg( 'badaccess' ) );
			}
			$this->addWikiText( $this->formatPermissionsErrorMessage( $reasons, $action ) );
		} else {
			// Wiki is read only
			throw new ReadOnlyError;
		}

		// Show source, if supplied
		if( is_string( $source ) ) {
			$this->addWikiMsg( 'viewsourcetext' );

			$pageLang = $this->getTitle()->getPageLanguage();
			$params = array(
				'id'   => 'wpTextbox1',
				'name' => 'wpTextbox1',
				'cols' => $this->getUser()->getOption( 'cols' ),
				'rows' => $this->getUser()->getOption( 'rows' ),
				'readonly' => 'readonly',
				'lang' => $pageLang->getHtmlCode(),
				'dir' => $pageLang->getDir(),
			);
			$this->addHTML( Html::element( 'textarea', $params, $source ) );

			// Show templates used by this article
			$templates = Linker::formatTemplates( $this->getTitle()->getTemplateLinksFrom() );
			$this->addHTML( "<div class='templatesUsed'>
$templates
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
	 * Turn off regular page output and return an error reponse
	 * for when rate limiting has triggered.
	 */
	public function rateLimited() {
		throw new ThrottledError;
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
		global $wgSlaveLagWarning, $wgSlaveLagCritical;
		if( $lag >= $wgSlaveLagWarning ) {
			$message = $lag < $wgSlaveLagCritical
				? 'lag-warn-normal'
				: 'lag-warn-high';
			$wrap = Html::rawElement( 'div', array( 'class' => "mw-{$message}" ), "\n$1\n" );
			$this->wrapWikiMsg( "$wrap\n", array( $message, $this->getLanguage()->formatNum( $lag ) ) );
		}
	}

	public function showFatalError( $message ) {
		$this->prepareErrorPage( $this->msg( 'internalerror' ) );

		$this->addHTML( $message );
	}

	public function showUnexpectedValueError( $name, $val ) {
		$this->showFatalError( $this->msg( 'unexpected', $name, $val )->text() );
	}

	public function showFileCopyError( $old, $new ) {
		$this->showFatalError( $this->msg( 'filecopyerror', $old, $new )->text() );
	}

	public function showFileRenameError( $old, $new ) {
		$this->showFatalError( $this->msg( 'filerenameerror', $old, $new )->text() );
	}

	public function showFileDeleteError( $name ) {
		$this->showFatalError( $this->msg( 'filedeleteerror', $name )->text() );
	}

	public function showFileNotFoundError( $name ) {
		$this->showFatalError( $this->msg( 'filenotfound', $name )->text() );
	}

	/**
	 * Add a "return to" link pointing to a specified title
	 *
	 * @param $title Title to link
	 * @param $query Array query string parameters
	 * @param $text String text of the link (input is not escaped)
	 */
	public function addReturnTo( $title, $query = array(), $text = null ) {
		$this->addLink( array( 'rel' => 'next', 'href' => $title->getFullURL() ) );
		$link = $this->msg( 'returnto' )->rawParams(
			Linker::link( $title, $text, array(), $query ) )->escaped();
		$this->addHTML( "<p id=\"mw-returnto\">{$link}</p>\n" );
	}

	/**
	 * Add a "return to" link pointing to a specified title,
	 * or the title indicated in the request, or else the main page
	 *
	 * @param $unused
	 * @param $returnto Title or String to return to
	 * @param $returntoquery String: query string for the return to link
	 */
	public function returnToMain( $unused = null, $returnto = null, $returntoquery = null ) {
		if ( $returnto == null ) {
			$returnto = $this->getRequest()->getText( 'returnto' );
		}

		if ( $returntoquery == null ) {
			$returntoquery = $this->getRequest()->getText( 'returntoquery' );
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

		$this->addReturnTo( $titleObj, wfCgiToArray( $returntoquery ) );
	}

	/**
	 * @param $sk Skin The given Skin
	 * @param $includeStyle Boolean: unused
	 * @return String: The doctype, opening "<html>", and head element.
	 */
	public function headElement( Skin $sk, $includeStyle = true ) {
		global $wgContLang;

		$userdir = $this->getLanguage()->getDir();
		$sitedir = $wgContLang->getDir();

		if ( $sk->commonPrintStylesheet() ) {
			$this->addModuleStyles( 'mediawiki.legacy.wikiprintable' );
		}

		$ret = Html::htmlHeader( array( 'lang' => $this->getLanguage()->getHtmlCode(), 'dir' => $userdir, 'class' => 'client-nojs' ) );

		if ( $this->getHTMLTitle() == '' ) {
			$this->setHTMLTitle( $this->msg( 'pagetitle', $this->getPageTitle() ) );
		}

		$openHead = Html::openElement( 'head' );
		if ( $openHead ) {
			# Don't bother with the newline if $head == ''
			$ret .= "$openHead\n";
		}

		$ret .= Html::element( 'title', null, $this->getHTMLTitle() ) . "\n";

		$ret .= implode( "\n", array(
			$this->getHeadLinks( null, true ),
			$this->buildCssLinks(),
			$this->getHeadScripts(),
			$this->getHeadItems()
		) );

		$closeHead = Html::closeElement( 'head' );
		if ( $closeHead ) {
			$ret .= "$closeHead\n";
		}

		$bodyAttrs = array();

		# Classes for LTR/RTL directionality support
		$bodyAttrs['class'] = "mediawiki $userdir sitedir-$sitedir";

		if ( $this->getLanguage()->capitalizeAllNouns() ) {
			# A <body> class is probably not the best way to do this . . .
			$bodyAttrs['class'] .= ' capitalize-all-nouns';
		}
		$bodyAttrs['class'] .= ' ' . $sk->getPageClasses( $this->getTitle() );
		$bodyAttrs['class'] .= ' skin-' . Sanitizer::escapeClass( $sk->getSkinName() );
		$bodyAttrs['class'] .= ' action-' . Sanitizer::escapeClass( Action::getActionName( $this->getContext() ) );

		$sk->addToBodyAttributes( $this, $bodyAttrs ); // Allow skins to add body attributes they need
		wfRunHooks( 'OutputPageBodyAttributes', array( $this, $sk, &$bodyAttrs ) );

		$ret .= Html::openElement( 'body', $bodyAttrs ) . "\n";

		return $ret;
	}

	/**
	 * Add the default ResourceLoader modules to this object
	 */
	private function addDefaultModules() {
		global $wgIncludeLegacyJavaScript, $wgPreloadJavaScriptMwUtil, $wgUseAjax,
			$wgAjaxWatch;

		// Add base resources
		$this->addModules( array(
			'mediawiki.user',
			'mediawiki.page.startup',
			'mediawiki.page.ready',
		) );
		if ( $wgIncludeLegacyJavaScript ){
			$this->addModules( 'mediawiki.legacy.wikibits' );
		}

		if ( $wgPreloadJavaScriptMwUtil ) {
			$this->addModules( 'mediawiki.util' );
		}

		MWDebug::addModules( $this );

		// Add various resources if required
		if ( $wgUseAjax ) {
			$this->addModules( 'mediawiki.legacy.ajax' );

			wfRunHooks( 'AjaxAddScript', array( &$this ) );

			if( $wgAjaxWatch && $this->getUser()->isLoggedIn() ) {
				$this->addModules( 'mediawiki.page.watch.ajax' );
			}

			if ( !$this->getUser()->getOption( 'disablesuggest', false ) ) {
				$this->addModules( 'mediawiki.searchSuggest' );
			}
		}

		if ( $this->getUser()->getBoolOption( 'editsectiononrightclick' ) ) {
			$this->addModules( 'mediawiki.action.view.rightClickEdit' );
		}

		# Crazy edit-on-double-click stuff
		if ( $this->isArticle() && $this->getUser()->getOption( 'editondblclick' ) ) {
			$this->addModules( 'mediawiki.action.view.dblClickEdit' );
		}
	}

	/**
	 * Get a ResourceLoader object associated with this OutputPage
	 *
	 * @return ResourceLoader
	 */
	public function getResourceLoader() {
		if ( is_null( $this->mResourceLoader ) ) {
			$this->mResourceLoader = new ResourceLoader();
		}
		return $this->mResourceLoader;
	}

	/**
	 * TODO: Document
	 * @param $modules Array/string with the module name(s)
	 * @param $only String ResourceLoaderModule TYPE_ class constant
	 * @param $useESI boolean
	 * @param $extraQuery Array with extra query parameters to add to each request. array( param => value )
	 * @param $loadCall boolean If true, output an (asynchronous) mw.loader.load() call rather than a "<script src='...'>" tag
	 * @return string html "<script>" and "<style>" tags
	 */
	protected function makeResourceLoaderLink( $modules, $only, $useESI = false, array $extraQuery = array(), $loadCall = false ) {
		global $wgResourceLoaderUseESI;

		$modules = (array) $modules;

		if ( !count( $modules ) ) {
			return '';
		}

		if ( count( $modules ) > 1 ) {
			// Remove duplicate module requests
			$modules = array_unique( $modules );
			// Sort module names so requests are more uniform
			sort( $modules );

			if ( ResourceLoader::inDebugMode() ) {
				// Recursively call us for every item
				$links = '';
				foreach ( $modules as $name ) {
					$links .= $this->makeResourceLoaderLink( $name, $only, $useESI );
				}
				return $links;
			}
		}

		// Create keyed-by-group list of module objects from modules list
		$groups = array();
		$resourceLoader = $this->getResourceLoader();
		foreach ( $modules as $name ) {
			$module = $resourceLoader->getModule( $name );
			# Check that we're allowed to include this module on this page
			if ( !$module
				|| ( $module->getOrigin() > $this->getAllowedModules( ResourceLoaderModule::TYPE_SCRIPTS )
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
		foreach ( $groups as $group => $grpModules ) {
			// Special handling for user-specific groups
			$user = null;
			if ( ( $group === 'user' || $group === 'private' ) && $this->getUser()->isLoggedIn() ) {
				$user = $this->getUser()->getName();
			}

			// Create a fake request based on the one we are about to make so modules return
			// correct timestamp and emptiness data
			$query = ResourceLoader::makeLoaderQuery(
				array(), // modules; not determined yet
				$this->getLanguage()->getCode(),
				$this->getSkin()->getSkinName(),
				$user,
				null, // version; not determined yet
				ResourceLoader::inDebugMode(),
				$only === ResourceLoaderModule::TYPE_COMBINED ? null : $only,
				$this->isPrintable(),
				$this->getRequest()->getBool( 'handheld' ),
				$extraQuery
			);
			$context = new ResourceLoaderContext( $resourceLoader, new FauxRequest( $query ) );
			// Extract modules that know they're empty
			$emptyModules = array ();
			foreach ( $grpModules as $key => $module ) {
				if ( $module->isKnownEmpty( $context ) ) {
					$emptyModules[$key] = 'ready';
					unset( $grpModules[$key] );
				}
			}
			// Inline empty modules: since they're empty, just mark them as 'ready'
			if ( count( $emptyModules ) > 0 && $only !== ResourceLoaderModule::TYPE_STYLES ) {
				// If we're only getting the styles, we don't need to do anything for empty modules.
				$links .= Html::inlineScript(

						ResourceLoader::makeLoaderConditionalScript(

								ResourceLoader::makeLoaderStateScript( $emptyModules )

						)

				) . "\n";
			}

			// If there are no modules left, skip this group
			if ( count( $grpModules ) === 0 ) {
				continue;
			}

			// Inline private modules. These can't be loaded through load.php for security
			// reasons, see bug 34907. Note that these modules should be loaded from
			// getHeadScripts() before the first loader call. Otherwise other modules can't
			// properly use them as dependencies (bug 30914)
			if ( $group === 'private' ) {
				if ( $only == ResourceLoaderModule::TYPE_STYLES ) {
					$links .= Html::inlineStyle(
						$resourceLoader->makeModuleResponse( $context, $grpModules )
					);
				} else {
					$links .= Html::inlineScript(
						ResourceLoader::makeLoaderConditionalScript(
							$resourceLoader->makeModuleResponse( $context, $grpModules )
						)
					);
				}
				$links .= "\n";
				continue;
			}
			// Special handling for the user group; because users might change their stuff
			// on-wiki like user pages, or user preferences; we need to find the highest
			// timestamp of these user-changable modules so we can ensure cache misses on change
			// This should NOT be done for the site group (bug 27564) because anons get that too
			// and we shouldn't be putting timestamps in Squid-cached HTML
			$version = null;
			if ( $group === 'user' ) {
				// Get the maximum timestamp
				$timestamp = 1;
				foreach ( $grpModules as $module ) {
					$timestamp = max( $timestamp, $module->getModifiedTime( $context ) );
				}
				// Add a version parameter so cache will break when things change
				$version = wfTimestamp( TS_ISO_8601_BASIC, $timestamp );
			}

			$url = ResourceLoader::makeLoaderURL(
				array_keys( $grpModules ),
				$this->getLanguage()->getCode(),
				$this->getSkin()->getSkinName(),
				$user,
				$version,
				ResourceLoader::inDebugMode(),
				$only === ResourceLoaderModule::TYPE_COMBINED ? null : $only,
				$this->isPrintable(),
				$this->getRequest()->getBool( 'handheld' ),
				$extraQuery
			);
			if ( $useESI && $wgResourceLoaderUseESI ) {
				$esi = Xml::element( 'esi:include', array( 'src' => $url ) );
				if ( $only == ResourceLoaderModule::TYPE_STYLES ) {
					$link = Html::inlineStyle( $esi );
				} else {
					$link = Html::inlineScript( $esi );
				}
			} else {
				// Automatically select style/script elements
				if ( $only === ResourceLoaderModule::TYPE_STYLES ) {
					$link = Html::linkedStyle( $url );
				} else if ( $loadCall ) {
					$link = Html::inlineScript(
						ResourceLoader::makeLoaderConditionalScript(
							Xml::encodeJsCall( 'mw.loader.load', array( $url, 'text/javascript', true ) )
						)
					);
				} else {
					$link = Html::linkedScript( $url );
				}
			}

			if( $group == 'noscript' ){
				$links .= Html::rawElement( 'noscript', array(), $link ) . "\n";
			} else {
				$links .= $link . "\n";
			}
		}
		return $links;
	}

	/**
	 * JS stuff to put in the "<head>". This is the startup module, config
	 * vars and modules marked with position 'top'
	 *
	 * @return String: HTML fragment
	 */
	function getHeadScripts() {
		global $wgResourceLoaderExperimentalAsyncLoading;

		// Startup - this will immediately load jquery and mediawiki modules
		$scripts = $this->makeResourceLoaderLink( 'startup', ResourceLoaderModule::TYPE_SCRIPTS, true );

		// Load config before anything else
		$scripts .= Html::inlineScript(
			ResourceLoader::makeLoaderConditionalScript(
				ResourceLoader::makeConfigSetScript( $this->getJSVars() )
			)
		);

		// Load embeddable private modules before any loader links
		// This needs to be TYPE_COMBINED so these modules are properly wrapped
		// in mw.loader.implement() calls and deferred until mw.user is available
		$embedScripts = array( 'user.options', 'user.tokens' );
		$scripts .= $this->makeResourceLoaderLink( $embedScripts, ResourceLoaderModule::TYPE_COMBINED );

		// Script and Messages "only" requests marked for top inclusion
		// Messages should go first
		$scripts .= $this->makeResourceLoaderLink( $this->getModuleMessages( true, 'top' ), ResourceLoaderModule::TYPE_MESSAGES );
		$scripts .= $this->makeResourceLoaderLink( $this->getModuleScripts( true, 'top' ), ResourceLoaderModule::TYPE_SCRIPTS );

		// Modules requests - let the client calculate dependencies and batch requests as it likes
		// Only load modules that have marked themselves for loading at the top
		$modules = $this->getModules( true, 'top' );
		if ( $modules ) {
			$scripts .= Html::inlineScript(
				ResourceLoader::makeLoaderConditionalScript(
					Xml::encodeJsCall( 'mw.loader.load', array( $modules ) )
				)
			);
		}

		if ( $wgResourceLoaderExperimentalAsyncLoading ) {
			$scripts .= $this->getScriptsForBottomQueue( true );
		}

		return $scripts;
	}

	/**
	 * JS stuff to put at the 'bottom', which can either be the bottom of the "<body>"
	 * or the bottom of the "<head>" depending on $wgResourceLoaderExperimentalAsyncLoading:
	 * modules marked with position 'bottom', legacy scripts ($this->mScripts),
	 * user preferences, site JS and user JS
	 *
	 * @param $inHead boolean If true, this HTML goes into the "<head>", if false it goes into the "<body>"
	 * @return string
	 */
	function getScriptsForBottomQueue( $inHead ) {
		global $wgUseSiteJs, $wgAllowUserJs;

		// Script and Messages "only" requests marked for bottom inclusion
		// If we're in the <head>, use load() calls rather than <script src="..."> tags
		// Messages should go first
		$scripts = $this->makeResourceLoaderLink( $this->getModuleMessages( true, 'bottom' ),
			ResourceLoaderModule::TYPE_MESSAGES, /* $useESI = */ false, /* $extraQuery = */ array(),
			/* $loadCall = */ $inHead
		);
		$scripts .= $this->makeResourceLoaderLink( $this->getModuleScripts( true, 'bottom' ),
			ResourceLoaderModule::TYPE_SCRIPTS, /* $useESI = */ false, /* $extraQuery = */ array(),
			/* $loadCall = */ $inHead
		);

		// Modules requests - let the client calculate dependencies and batch requests as it likes
		// Only load modules that have marked themselves for loading at the bottom
		$modules = $this->getModules( true, 'bottom' );
		if ( $modules ) {
			$scripts .= Html::inlineScript(
				ResourceLoader::makeLoaderConditionalScript(
					Xml::encodeJsCall( 'mw.loader.load', array( $modules, null, true ) )
				)
			);
		}

		// Legacy Scripts
		$scripts .= "\n" . $this->mScripts;

		$defaultModules = array();

		// Add site JS if enabled
		if ( $wgUseSiteJs ) {
			$scripts .= $this->makeResourceLoaderLink( 'site', ResourceLoaderModule::TYPE_SCRIPTS,
				/* $useESI = */ false, /* $extraQuery = */ array(), /* $loadCall = */ $inHead
			);
			$defaultModules['site'] = 'loading';
		} else {
			// The wiki is configured to not allow a site module.
			$defaultModules['site'] = 'missing';
		}

		// Add user JS if enabled
		if ( $wgAllowUserJs ) {
			if ( $this->getUser()->isLoggedIn() ) {
				if( $this->getTitle() && $this->getTitle()->isJsSubpage() && $this->userCanPreview() ) {
					# XXX: additional security check/prompt?
					// We're on a preview of a JS subpage
					// Exclude this page from the user module in case it's in there (bug 26283)
					$scripts .= $this->makeResourceLoaderLink( 'user', ResourceLoaderModule::TYPE_SCRIPTS, false,
						array( 'excludepage' => $this->getTitle()->getPrefixedDBkey() ), $inHead
					);
					// Load the previewed JS
					$scripts .= Html::inlineScript( "\n" . $this->getRequest()->getText( 'wpTextbox1' ) . "\n" ) . "\n";
					// FIXME: If the user is previewing, say, ./vector.js, his ./common.js will be loaded
					// asynchronously and may arrive *after* the inline script here. So the previewed code
					// may execute before ./common.js runs. Normally, ./common.js runs before ./vector.js...
				} else {
					// Include the user module normally, i.e., raw to avoid it being wrapped in a closure.
					$scripts .= $this->makeResourceLoaderLink( 'user', ResourceLoaderModule::TYPE_SCRIPTS,
						/* $useESI = */ false, /* $extraQuery = */ array(), /* $loadCall = */ $inHead
					);
				}
				$defaultModules['user'] = 'loading';
			} else {
				// Non-logged-in users have no user module. Treat it as empty and 'ready' to avoid
				// blocking default gadgets that might depend on it. Although arguably default-enabled
				// gadgets should not depend on the user module, it's harmless and less error-prone to
				// handle this case.
				$defaultModules['user'] = 'ready';
			}
		} else {
			// User JS disabled
			$defaultModules['user'] = 'missing';
		}

		// Group JS is only enabled if site JS is enabled.
		if ( $wgUseSiteJs ) {
			if ( $this->getUser()->isLoggedIn() ) {
				$scripts .= $this->makeResourceLoaderLink( 'user.groups', ResourceLoaderModule::TYPE_COMBINED,
					/* $useESI = */ false, /* $extraQuery = */ array(), /* $loadCall = */ $inHead
				);
				$defaultModules['user.groups'] = 'loading';
			} else {
				// Non-logged-in users have no user.groups module. Treat it as empty and 'ready' to
				// avoid blocking gadgets that might depend upon the module.
				$defaultModules['user.groups'] = 'ready';
			}
		} else {
			// Site (and group JS) disabled
			$defaultModules['user.groups'] = 'missing';
		}

		$loaderInit = '';
		if ( $inHead ) {
			// We generate loader calls anyway, so no need to fix the client-side loader's state to 'loading'.
			foreach ( $defaultModules as $m => $state ) {
				if ( $state == 'loading' ) {
					unset( $defaultModules[$m] );
				}
			}
		}
		if ( count( $defaultModules ) > 0 ) {
			$loaderInit = Html::inlineScript(
				ResourceLoader::makeLoaderConditionalScript(
					ResourceLoader::makeLoaderStateScript( $defaultModules )
				)
			) . "\n";
		}
		return $loaderInit . $scripts;
	}

	/**
	 * JS stuff to put at the bottom of the "<body>"
	 * @return string
	 */
	function getBottomScripts() {
		global $wgResourceLoaderExperimentalAsyncLoading;
		if ( !$wgResourceLoaderExperimentalAsyncLoading ) {
			return $this->getScriptsForBottomQueue( false );
		} else {
			return '';
		}
	}

	/**
	 * Add one or more variables to be set in mw.config in JavaScript.
	 *
	 * @param $keys {String|Array} Key or array of key/value pairs.
	 * @param $value {Mixed} [optional] Value of the configuration variable.
	 */
	public function addJsConfigVars( $keys, $value = null ) {
		if ( is_array( $keys ) ) {
			foreach ( $keys as $key => $value ) {
				$this->mJsConfigVars[$key] = $value;
			}
			return;
		}

		$this->mJsConfigVars[$keys] = $value;
	}


	/**
	 * Get an array containing the variables to be set in mw.config in JavaScript.
	 *
	 * DO NOT CALL THIS FROM OUTSIDE OF THIS CLASS OR Skin::makeGlobalVariablesScript().
	 * This is only public until that function is removed. You have been warned.
	 *
	 * Do not add things here which can be evaluated in ResourceLoaderStartupScript
	 * - in other words, page-independent/site-wide variables (without state).
	 * You will only be adding bloat to the html page and causing page caches to
	 * have to be purged on configuration changes.
	 * @return array
	 */
	public function getJSVars() {
		global $wgUseAjax, $wgContLang;

		$latestRevID = 0;
		$pageID = 0;
		$canonicalName = false; # bug 21115

		$title = $this->getTitle();
		$ns = $title->getNamespace();
		$nsname = MWNamespace::exists( $ns ) ? MWNamespace::getCanonicalName( $ns ) : $title->getNsText();

		// Get the relevant title so that AJAX features can use the correct page name
		// when making API requests from certain special pages (bug 34972).
		$relevantTitle = $this->getSkin()->getRelevantTitle();

		if ( $ns == NS_SPECIAL ) {
			list( $canonicalName, /*...*/ ) = SpecialPageFactory::resolveAlias( $title->getDBkey() );
		} elseif ( $this->canUseWikiPage() ) {
			$wikiPage = $this->getWikiPage();
			$latestRevID = $wikiPage->getLatest();
			$pageID = $wikiPage->getId();
		}

		$lang = $title->getPageLanguage();

		// Pre-process information
		$separatorTransTable = $lang->separatorTransformTable();
		$separatorTransTable = $separatorTransTable ? $separatorTransTable : array();
		$compactSeparatorTransTable = array(
			implode( "\t", array_keys( $separatorTransTable ) ),
			implode( "\t", $separatorTransTable ),
		);
		$digitTransTable = $lang->digitTransformTable();
		$digitTransTable = $digitTransTable ? $digitTransTable : array();
		$compactDigitTransTable = array(
			implode( "\t", array_keys( $digitTransTable ) ),
			implode( "\t", $digitTransTable ),
		);

		$vars = array(
			'wgCanonicalNamespace' => $nsname,
			'wgCanonicalSpecialPageName' => $canonicalName,
			'wgNamespaceNumber' => $title->getNamespace(),
			'wgPageName' => $title->getPrefixedDBKey(),
			'wgTitle' => $title->getText(),
			'wgCurRevisionId' => $latestRevID,
			'wgArticleId' => $pageID,
			'wgIsArticle' => $this->isArticle(),
			'wgAction' => Action::getActionName( $this->getContext() ),
			'wgUserName' => $this->getUser()->isAnon() ? null : $this->getUser()->getName(),
			'wgUserGroups' => $this->getUser()->getEffectiveGroups(),
			'wgCategories' => $this->getCategories(),
			'wgBreakFrames' => $this->getFrameOptions() == 'DENY',
			'wgPageContentLanguage' => $lang->getCode(),
			'wgSeparatorTransformTable' => $compactSeparatorTransTable,
			'wgDigitTransformTable' => $compactDigitTransTable,
			'wgDefaultDateFormat' => $lang->getDefaultDateFormat(),
			'wgMonthNames' => $lang->getMonthNamesArray(),
			'wgMonthNamesShort' => $lang->getMonthAbbreviationsArray(),
			'wgRelevantPageName' => $relevantTitle->getPrefixedDBKey(),
		);
		if ( $wgContLang->hasVariants() ) {
			$vars['wgUserVariant'] = $wgContLang->getPreferredVariant();
 		}
		foreach ( $title->getRestrictionTypes() as $type ) {
			$vars['wgRestriction' . ucfirst( $type )] = $title->getRestrictions( $type );
		}
		if ( $title->isMainPage() ) {
			$vars['wgIsMainPage'] = true;
		}
		if ( $this->mRedirectedFrom ) {
			$vars['wgRedirectedFrom'] = $this->mRedirectedFrom->getPrefixedDBKey();
		}

		// Allow extensions to add their custom variables to the mw.config map.
		// Use the 'ResourceLoaderGetConfigVars' hook if the variable is not
		// page-dependant but site-wide (without state).
		// Alternatively, you may want to use OutputPage->addJsConfigVars() instead.
		wfRunHooks( 'MakeGlobalVariablesScript', array( &$vars, $this ) );

		// Merge in variables from addJsConfigVars last
		return array_merge( $vars, $this->mJsConfigVars );
	}

	/**
	 * To make it harder for someone to slip a user a fake
	 * user-JavaScript or user-CSS preview, a random token
	 * is associated with the login session. If it's not
	 * passed back with the preview request, we won't render
	 * the code.
	 *
	 * @return bool
	 */
	public function userCanPreview() {
		if ( $this->getRequest()->getVal( 'action' ) != 'submit'
			|| !$this->getRequest()->wasPosted()
			|| !$this->getUser()->matchEditToken(
				$this->getRequest()->getVal( 'wpEditToken' ) )
		) {
			return false;
		}
		if ( !$this->getTitle()->isJsSubpage() && !$this->getTitle()->isCssSubpage() ) {
			return false;
		}

		return !count( $this->getTitle()->getUserPermissionsErrors( 'edit', $this->getUser() ) );
	}

	/**
	 * @param $addContentType bool: Whether "<meta>" specifying content type should be returned
	 *
	 * @return array in format "link name or number => 'link html'".
	 */
	public function getHeadLinksArray( $addContentType = false ) {
		global $wgUniversalEditButton, $wgFavicon, $wgAppleTouchIcon, $wgEnableAPI,
			$wgSitename, $wgVersion, $wgHtml5, $wgMimeType,
			$wgFeed, $wgOverrideSiteFeed, $wgAdvertisedFeedTypes,
			$wgDisableLangConversion, $wgCanonicalLanguageLinks,
			$wgRightsPage, $wgRightsUrl;

		$tags = array();

		if ( $addContentType ) {
			if ( $wgHtml5 ) {
				# More succinct than <meta http-equiv=Content-Type>, has the
				# same effect
				$tags['meta-charset'] = Html::element( 'meta', array( 'charset' => 'UTF-8' ) );
			} else {
				$tags['meta-content-type'] = Html::element( 'meta', array(
					'http-equiv' => 'Content-Type',
					'content' => "$wgMimeType; charset=UTF-8"
				) );
				$tags['meta-content-style-type'] = Html::element( 'meta', array(  // bug 15835
					'http-equiv' => 'Content-Style-Type',
					'content' => 'text/css'
				) );
			}
		}

		$tags['meta-generator'] = Html::element( 'meta', array(
			'name' => 'generator',
			'content' => "MediaWiki $wgVersion",
		) );

		$p = "{$this->mIndexPolicy},{$this->mFollowPolicy}";
		if( $p !== 'index,follow' ) {
			// http://www.robotstxt.org/wc/meta-user.html
			// Only show if it's different from the default robots policy
			$tags['meta-robots'] = Html::element( 'meta', array(
				'name' => 'robots',
				'content' => $p,
			) );
		}

		if ( count( $this->mKeywords ) > 0 ) {
			$strip = array(
				"/<.*?" . ">/" => '',
				"/_/" => ' '
			);
			$tags['meta-keywords'] = Html::element( 'meta', array(
				'name' => 'keywords',
				'content' =>  preg_replace(
					array_keys( $strip ),
					array_values( $strip ),
					implode( ',', $this->mKeywords )
				)
			) );
		}

		foreach ( $this->mMetatags as $tag ) {
			if ( 0 == strcasecmp( 'http:', substr( $tag[0], 0, 5 ) ) ) {
				$a = 'http-equiv';
				$tag[0] = substr( $tag[0], 5 );
			} else {
				$a = 'name';
			}
			$tagName = "meta-{$tag[0]}";
			if ( isset( $tags[$tagName] ) ) {
				$tagName .= $tag[1];
			}
			$tags[$tagName] = Html::element( 'meta',
				array(
					$a => $tag[0],
					'content' => $tag[1]
				)
			);
		}

		foreach ( $this->mLinktags as $tag ) {
			$tags[] = Html::element( 'link', $tag );
		}

		# Universal edit button
		if ( $wgUniversalEditButton && $this->isArticleRelated() ) {
			$user = $this->getUser();
			if ( $this->getTitle()->quickUserCan( 'edit', $user )
				&& ( $this->getTitle()->exists() || $this->getTitle()->quickUserCan( 'create', $user ) ) ) {
				// Original UniversalEditButton
				$msg = $this->msg( 'edit' )->text();
				$tags['universal-edit-button'] = Html::element( 'link', array(
					'rel' => 'alternate',
					'type' => 'application/x-wiki',
					'title' => $msg,
					'href' => $this->getTitle()->getLocalURL( 'action=edit' )
				) );
				// Alternate edit link
				$tags['alternative-edit'] = Html::element( 'link', array(
					'rel' => 'edit',
					'title' => $msg,
					'href' => $this->getTitle()->getLocalURL( 'action=edit' )
				) );
			}
		}

		# Generally the order of the favicon and apple-touch-icon links
		# should not matter, but Konqueror (3.5.9 at least) incorrectly
		# uses whichever one appears later in the HTML source. Make sure
		# apple-touch-icon is specified first to avoid this.
		if ( $wgAppleTouchIcon !== false ) {
			$tags['apple-touch-icon'] = Html::element( 'link', array( 'rel' => 'apple-touch-icon', 'href' => $wgAppleTouchIcon ) );
		}

		if ( $wgFavicon !== false ) {
			$tags['favicon'] = Html::element( 'link', array( 'rel' => 'shortcut icon', 'href' => $wgFavicon ) );
		}

		# OpenSearch description link
		$tags['opensearch'] = Html::element( 'link', array(
			'rel' => 'search',
			'type' => 'application/opensearchdescription+xml',
			'href' => wfScript( 'opensearch_desc' ),
			'title' => $this->msg( 'opensearch-desc' )->inContentLanguage()->text(),
		) );

		if ( $wgEnableAPI ) {
			# Real Simple Discovery link, provides auto-discovery information
			# for the MediaWiki API (and potentially additional custom API
			# support such as WordPress or Twitter-compatible APIs for a
			# blogging extension, etc)
			$tags['rsd'] = Html::element( 'link', array(
				'rel' => 'EditURI',
				'type' => 'application/rsd+xml',
				// Output a protocol-relative URL here if $wgServer is protocol-relative
				// Whether RSD accepts relative or protocol-relative URLs is completely undocumented, though
				'href' => wfExpandUrl( wfAppendQuery( wfScript( 'api' ), array( 'action' => 'rsd' ) ), PROTO_RELATIVE ),
			) );
		}


		# Language variants
		if ( !$wgDisableLangConversion && $wgCanonicalLanguageLinks ) {
			$lang = $this->getTitle()->getPageLanguage();
			if ( $lang->hasVariants() ) {

				$urlvar = $lang->getURLVariant();

				if ( !$urlvar ) {
					$variants = $lang->getVariants();
					foreach ( $variants as $_v ) {
						$tags["variant-$_v"] = Html::element( 'link', array(
							'rel' => 'alternate',
							'hreflang' => $_v,
							'href' => $this->getTitle()->getLocalURL( array( 'variant' => $_v ) ) )
						);
					}
				} else {
					$tags['canonical'] = Html::element( 'link', array(
						'rel' => 'canonical',
						'href' => $this->getTitle()->getCanonicalUrl()
					) );
				}
			}
		}

		# Copyright
		$copyright = '';
		if ( $wgRightsPage ) {
			$copy = Title::newFromText( $wgRightsPage );

			if ( $copy ) {
				$copyright = $copy->getLocalURL();
			}
		}

		if ( !$copyright && $wgRightsUrl ) {
			$copyright = $wgRightsUrl;
		}

		if ( $copyright ) {
			$tags['copyright'] = Html::element( 'link', array(
				'rel' => 'copyright',
				'href' => $copyright )
			);
		}

		# Feeds
		if ( $wgFeed ) {
			foreach( $this->getSyndicationLinks() as $format => $link ) {
				# Use the page name for the title.  In principle, this could
				# lead to issues with having the same name for different feeds
				# corresponding to the same page, but we can't avoid that at
				# this low a level.

				$tags[] = $this->feedLink(
					$format,
					$link,
					# Used messages: 'page-rss-feed' and 'page-atom-feed' (for an easier grep)
					$this->msg( "page-{$format}-feed", $this->getTitle()->getPrefixedText() )->text()
				);
			}

			# Recent changes feed should appear on every page (except recentchanges,
			# that would be redundant). Put it after the per-page feed to avoid
			# changing existing behavior. It's still available, probably via a
			# menu in your browser. Some sites might have a different feed they'd
			# like to promote instead of the RC feed (maybe like a "Recent New Articles"
			# or "Breaking news" one). For this, we see if $wgOverrideSiteFeed is defined.
			# If so, use it instead.
			if ( $wgOverrideSiteFeed ) {
				foreach ( $wgOverrideSiteFeed as $type => $feedUrl ) {
					// Note, this->feedLink escapes the url.
					$tags[] = $this->feedLink(
						$type,
						$feedUrl,
						$this->msg( "site-{$type}-feed", $wgSitename )->text()
					);
				}
			} elseif ( !$this->getTitle()->isSpecial( 'Recentchanges' ) ) {
				$rctitle = SpecialPage::getTitleFor( 'Recentchanges' );
				foreach ( $wgAdvertisedFeedTypes as $format ) {
					$tags[] = $this->feedLink(
						$format,
						$rctitle->getLocalURL( "feed={$format}" ),
						$this->msg( "site-{$format}-feed", $wgSitename )->text() # For grep: 'site-rss-feed', 'site-atom-feed'.
					);
				}
			}
		}
		return $tags;
	}

	/**
	 * @param $unused
	 * @param $addContentType bool: Whether "<meta>" specifying content type should be returned
	 *
	 * @return string HTML tag links to be put in the header.
	 */
	public function getHeadLinks( $unused = null, $addContentType = false ) {
		return implode( "\n", $this->getHeadLinksArray( $addContentType ) );
	}

	/**
	 * Generate a "<link rel/>" for a feed.
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
	 * @param $flip String: Set to 'flip' to flip the CSS if needed
	 */
	public function addInlineStyle( $style_css, $flip = 'noflip' ) {
		if( $flip === 'flip' && $this->getLanguage()->isRTL() ) {
			# If wanted, and the interface is right-to-left, flip the CSS
			$style_css = CSSJanus::transform( $style_css, true, false );
		}
		$this->mInlineStyles .= Html::inlineStyle( $style_css );
	}

	/**
	 * Build a set of "<link>" elements for the stylesheets specified in the $this->styles array.
	 * These will be applied to various media & IE conditionals.
	 *
	 * @return string
	 */
	public function buildCssLinks() {
		global $wgUseSiteCss, $wgAllowUserCss, $wgAllowUserCssPrefs,
			$wgLang, $wgContLang;

		$this->getSkin()->setupSkinUserCss( $this );

		// Add ResourceLoader styles
		// Split the styles into four groups
		$styles = array( 'other' => array(), 'user' => array(), 'site' => array(), 'private' => array(), 'noscript' => array() );
		$otherTags = ''; // Tags to append after the normal <link> tags
		$resourceLoader = $this->getResourceLoader();

		$moduleStyles = $this->getModuleStyles();

		// Per-site custom styles
		if ( $wgUseSiteCss ) {
			$moduleStyles[] = 'site';
			$moduleStyles[] = 'noscript';
			if( $this->getUser()->isLoggedIn() ){
				$moduleStyles[] = 'user.groups';
			}
		}

		// Per-user custom styles
		if ( $wgAllowUserCss ) {
			if ( $this->getTitle()->isCssSubpage() && $this->userCanPreview() ) {
				// We're on a preview of a CSS subpage
				// Exclude this page from the user module in case it's in there (bug 26283)
				$otherTags .= $this->makeResourceLoaderLink( 'user', ResourceLoaderModule::TYPE_STYLES, false,
					array( 'excludepage' => $this->getTitle()->getPrefixedDBkey() )
				);

				// Load the previewed CSS
				// If needed, Janus it first. This is user-supplied CSS, so it's
				// assumed to be right for the content language directionality.
				$previewedCSS = $this->getRequest()->getText( 'wpTextbox1' );
				if ( $wgLang->getDir() !== $wgContLang->getDir() ) {
					$previewedCSS = CSSJanus::transform( $previewedCSS, true, false );
				}
				$otherTags .= Html::inlineStyle( $previewedCSS );
			} else {
				// Load the user styles normally
				$moduleStyles[] = 'user';
			}
		}

		// Per-user preference styles
		if ( $wgAllowUserCssPrefs ) {
			$moduleStyles[] = 'user.cssprefs';
		}

		foreach ( $moduleStyles as $name ) {
			$module = $resourceLoader->getModule( $name );
			if ( !$module ) {
				continue;
			}
			$group = $module->getGroup();
			// Modules in groups named "other" or anything different than "user", "site" or "private"
			// will be placed in the "other" group
			$styles[isset( $styles[$group] ) ? $group : 'other'][] = $name;
		}

		// We want site, private and user styles to override dynamically added styles from modules, but we want
		// dynamically added styles to override statically added styles from other modules. So the order
		// has to be other, dynamic, site, private, user
		// Add statically added styles for other modules
		$ret = $this->makeResourceLoaderLink( $styles['other'], ResourceLoaderModule::TYPE_STYLES );
		// Add normal styles added through addStyle()/addInlineStyle() here
		$ret .= implode( "\n", $this->buildCssLinksArray() ) . $this->mInlineStyles;
		// Add marker tag to mark the place where the client-side loader should inject dynamic styles
		// We use a <meta> tag with a made-up name for this because that's valid HTML
		$ret .= Html::element( 'meta', array( 'name' => 'ResourceLoaderDynamicStyles', 'content' => '' ) ) . "\n";

		// Add site, private and user styles
		// 'private' at present only contains user.options, so put that before 'user'
		// Any future private modules will likely have a similar user-specific character
		foreach ( array( 'site', 'noscript', 'private', 'user' ) as $group ) {
			$ret .= $this->makeResourceLoaderLink( $styles[$group],
					ResourceLoaderModule::TYPE_STYLES
			);
		}

		// Add stuff in $otherTags (previewed user CSS if applicable)
		$ret .= $otherTags;
		return $ret;
	}

	/**
	 * @return Array
	 */
	public function buildCssLinksArray() {
		$links = array();

		// Add any extension CSS
		foreach ( $this->mExtStyles as $url ) {
			$this->addStyle( $url );
		}
		$this->mExtStyles = array();

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
			if( $this->getLanguage()->getDir() != $options['dir'] ) {
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
	 * Add a wikitext-formatted message to the output.
	 * This is equivalent to:
	 *
	 *    $wgOut->addWikiText( wfMessage( ... )->plain() )
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
	 * @param $name string
	 * @param $args array
	 */
	public function addWikiMsgArray( $name, $args ) {
		$this->addHTML( $this->msg( $name, $args )->parseAsBlock() );
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
	 * Don't use this for messages that are not in users interface language.
	 *
	 * For example:
	 *
	 *    $wgOut->wrapWikiMsg( "<div class='error'>\n$1\n</div>", 'some-error' );
	 *
	 * Is equivalent to:
	 *
	 *    $wgOut->addWikiText( "<div class='error'>\n" . wfMessage( 'some-error' )->plain() . "\n</div>" );
	 *
	 * The newline after opening div is needed in some wikitext. See bug 19226.
	 *
	 * @param $wrap string
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
					unset( $args['options'] );
					wfDeprecated(
						'Adding "options" to ' . __METHOD__ . ' is no longer supported',
						'1.20'
					);
				}
			}  else {
				$args = array();
				$name = $spec;
			}
			$s = str_replace( '$' . ( $n + 1 ), $this->msg( $name, $args )->plain(), $s );
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
	 * @deprecated since 1.17
	 */
	public function includeJQuery( $modules = array() ) {
		return array();
	}

}
