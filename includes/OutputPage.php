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

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Session\SessionManager;
use WrappedString\WrappedString;
use WrappedString\WrappedStringList;

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
	/** @var array Should be private. Used with addMeta() which adds "<meta>" */
	protected $mMetatags = [];

	/** @var array */
	protected $mLinktags = [];

	/** @var bool */
	protected $mCanonicalUrl = false;

	/**
	 * @var array Additional stylesheets. Looks like this is for extensions.
	 *   Might be replaced by ResourceLoader.
	 */
	protected $mExtStyles = [];

	/**
	 * @var string Should be private - has getter and setter. Contains
	 *   the HTML title */
	public $mPagetitle = '';

	/**
	 * @var string Contains all of the "<body>" content. Should be private we
	 *   got set/get accessors and the append() method.
	 */
	public $mBodytext = '';

	/** @var string Stores contents of "<title>" tag */
	private $mHTMLtitle = '';

	/**
	 * @var bool Is the displayed content related to the source of the
	 *   corresponding wiki article.
	 */
	private $mIsarticle = false;

	/** @var bool Stores "article flag" toggle. */
	private $mIsArticleRelated = true;

	/**
	 * @var bool We have to set isPrintable(). Some pages should
	 * never be printed (ex: redirections).
	 */
	private $mPrintable = false;

	/**
	 * @var array Contains the page subtitle. Special pages usually have some
	 *   links here. Don't confuse with site subtitle added by skins.
	 */
	private $mSubtitle = [];

	/** @var string */
	public $mRedirect = '';

	/** @var int */
	protected $mStatusCode;

	/**
	 * @var string Used for sending cache control.
	 *   The whole caching system should probably be moved into its own class.
	 */
	protected $mLastModified = '';

	/** @var array */
	protected $mCategoryLinks = [];

	/** @var array */
	protected $mCategories = [];

	/** @var array */
	protected $mIndicators = [];

	/** @var array Array of Interwiki Prefixed (non DB key) Titles (e.g. 'fr:Test page') */
	private $mLanguageLinks = [];

	/**
	 * Used for JavaScript (predates ResourceLoader)
	 * @todo We should split JS / CSS.
	 * mScripts content is inserted as is in "<head>" by Skin. This might
	 * contain either a link to a stylesheet or inline CSS.
	 */
	private $mScripts = '';

	/** @var string Inline CSS styles. Use addInlineStyle() sparingly */
	protected $mInlineStyles = '';

	/**
	 * @var string Used by skin template.
	 * Example: $tpl->set( 'displaytitle', $out->mPageLinkTitle );
	 */
	public $mPageLinkTitle = '';

	/** @var array Array of elements in "<head>". Parser might add its own headers! */
	protected $mHeadItems = [];

	/** @var array */
	protected $mModules = [];

	/** @var array */
	protected $mModuleScripts = [];

	/** @var array */
	protected $mModuleStyles = [];

	/** @var ResourceLoader */
	protected $mResourceLoader;

	/** @var ResourceLoaderClientHtml */
	private $rlClient;

	/** @var ResourceLoaderContext */
	private $rlClientContext;

	/** @var string */
	private $rlUserModuleState;

	/** @var array */
	private $rlExemptStyleModules;

	/** @var array */
	protected $mJsConfigVars = [];

	/** @var array */
	protected $mTemplateIds = [];

	/** @var array */
	protected $mImageTimeKeys = [];

	/** @var string */
	public $mRedirectCode = '';

	protected $mFeedLinksAppendQuery = null;

	/** @var array
	 * What level of 'untrustworthiness' is allowed in CSS/JS modules loaded on this page?
	 * @see ResourceLoaderModule::$origin
	 * ResourceLoaderModule::ORIGIN_ALL is assumed unless overridden;
	 */
	protected $mAllowedModules = [
		ResourceLoaderModule::TYPE_COMBINED => ResourceLoaderModule::ORIGIN_ALL,
	];

	/** @var bool Whether output is disabled.  If this is true, the 'output' method will do nothing. */
	protected $mDoNothing = false;

	// Parser related.

	/** @var int */
	protected $mContainsNewMagic = 0;

	/**
	 * lazy initialised, use parserOptions()
	 * @var ParserOptions
	 */
	protected $mParserOptions = null;

	/**
	 * Handles the Atom / RSS links.
	 * We probably only support Atom in 2011.
	 * @see $wgAdvertisedFeedTypes
	 */
	private $mFeedLinks = [];

	// Gwicke work on squid caching? Roughly from 2003.
	protected $mEnableClientCache = true;

	/** @var bool Flag if output should only contain the body of the article. */
	private $mArticleBodyOnly = false;

	/** @var bool */
	protected $mNewSectionLink = false;

	/** @var bool */
	protected $mHideNewSectionLink = false;

	/**
	 * @var bool Comes from the parser. This was probably made to load CSS/JS
	 * only if we had "<gallery>". Used directly in CategoryPage.php.
	 * Looks like ResourceLoader can replace this.
	 */
	public $mNoGallery = false;

	/** @var string */
	private $mPageTitleActionText = '';

	/** @var int Cache stuff. Looks like mEnableClientCache */
	protected $mCdnMaxage = 0;
	/** @var int Upper limit on mCdnMaxage */
	protected $mCdnMaxageLimit = INF;

	/**
	 * @var bool Controls if anti-clickjacking / frame-breaking headers will
	 * be sent. This should be done for pages where edit actions are possible.
	 * Setters: $this->preventClickjacking() and $this->allowClickjacking().
	 */
	protected $mPreventClickjacking = true;

	/** @var int To include the variable {{REVISIONID}} */
	private $mRevisionId = null;

	/** @var string */
	private $mRevisionTimestamp = null;

	/** @var array */
	protected $mFileVersion = null;

	/**
	 * @var array An array of stylesheet filenames (relative from skins path),
	 * with options for CSS media, IE conditions, and RTL/LTR direction.
	 * For internal use; add settings in the skin via $this->addStyle()
	 *
	 * Style again! This seems like a code duplication since we already have
	 * mStyles. This is what makes Open Source amazing.
	 */
	protected $styles = [];

	private $mIndexPolicy = 'index';
	private $mFollowPolicy = 'follow';
	private $mVaryHeader = [
		'Accept-Encoding' => [ 'match=gzip' ],
	];

	/**
	 * If the current page was reached through a redirect, $mRedirectedFrom contains the Title
	 * of the redirect.
	 *
	 * @var Title
	 */
	private $mRedirectedFrom = null;

	/**
	 * Additional key => value data
	 */
	private $mProperties = [];

	/**
	 * @var string|null ResourceLoader target for load.php links. If null, will be omitted
	 */
	private $mTarget = null;

	/**
	 * @var bool Whether parser output should contain table of contents
	 */
	private $mEnableTOC = true;

	/**
	 * @var bool Whether parser output should contain section edit links
	 */
	private $mEnableSectionEditLinks = true;

	/**
	 * @var string|null The URL to send in a <link> element with rel=copyright
	 */
	private $copyrightUrl;

	/**
	 * Constructor for OutputPage. This should not be called directly.
	 * Instead a new RequestContext should be created and it will implicitly create
	 * a OutputPage tied to that context.
	 * @param IContextSource|null $context
	 */
	function __construct( IContextSource $context = null ) {
		if ( $context === null ) {
			# Extensions should use `new RequestContext` instead of `new OutputPage` now.
			wfDeprecated( __METHOD__, '1.18' );
		} else {
			$this->setContext( $context );
		}
	}

	/**
	 * Redirect to $url rather than displaying the normal page
	 *
	 * @param string $url URL
	 * @param string $responsecode HTTP status code
	 */
	public function redirect( $url, $responsecode = '302' ) {
		# Strip newlines as a paranoia check for header injection in PHP<5.1.2
		$this->mRedirect = str_replace( "\n", '', $url );
		$this->mRedirectCode = $responsecode;
	}

	/**
	 * Get the URL to redirect to, or an empty string if not redirect URL set
	 *
	 * @return string
	 */
	public function getRedirect() {
		return $this->mRedirect;
	}

	/**
	 * Set the copyright URL to send with the output.
	 * Empty string to omit, null to reset.
	 *
	 * @since 1.26
	 *
	 * @param string|null $url
	 */
	public function setCopyrightUrl( $url ) {
		$this->copyrightUrl = $url;
	}

	/**
	 * Set the HTTP status code to send with the output.
	 *
	 * @param int $statusCode
	 */
	public function setStatusCode( $statusCode ) {
		$this->mStatusCode = $statusCode;
	}

	/**
	 * Add a new "<meta>" tag
	 * To add an http-equiv meta tag, precede the name with "http:"
	 *
	 * @param string $name Tag name
	 * @param string $val Tag value
	 */
	function addMeta( $name, $val ) {
		array_push( $this->mMetatags, [ $name, $val ] );
	}

	/**
	 * Returns the current <meta> tags
	 *
	 * @since 1.25
	 * @return array
	 */
	public function getMetaTags() {
		return $this->mMetatags;
	}

	/**
	 * Add a new \<link\> tag to the page header.
	 *
	 * Note: use setCanonicalUrl() for rel=canonical.
	 *
	 * @param array $linkarr Associative array of attributes.
	 */
	function addLink( array $linkarr ) {
		array_push( $this->mLinktags, $linkarr );
	}

	/**
	 * Returns the current <link> tags
	 *
	 * @since 1.25
	 * @return array
	 */
	public function getLinkTags() {
		return $this->mLinktags;
	}

	/**
	 * Add a new \<link\> with "rel" attribute set to "meta"
	 *
	 * @param array $linkarr Associative array mapping attribute names to their
	 *                 values, both keys and values will be escaped, and the
	 *                 "rel" attribute will be automatically added
	 */
	function addMetadataLink( array $linkarr ) {
		$linkarr['rel'] = $this->getMetadataAttribute();
		$this->addLink( $linkarr );
	}

	/**
	 * Set the URL to be used for the <link rel=canonical>. This should be used
	 * in preference to addLink(), to avoid duplicate link tags.
	 * @param string $url
	 */
	function setCanonicalUrl( $url ) {
		$this->mCanonicalUrl = $url;
	}

	/**
	 * Returns the URL to be used for the <link rel=canonical> if
	 * one is set.
	 *
	 * @since 1.25
	 * @return bool|string
	 */
	public function getCanonicalUrl() {
		return $this->mCanonicalUrl;
	}

	/**
	 * Get the value of the "rel" attribute for metadata links
	 *
	 * @return string
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
	 * Internal use only. Use OutputPage::addModules() or OutputPage::addJsConfigVars()
	 * if possible.
	 *
	 * @param string $script Raw HTML
	 */
	function addScript( $script ) {
		$this->mScripts .= $script;
	}

	/**
	 * Register and add a stylesheet from an extension directory.
	 *
	 * @deprecated since 1.27 use addModuleStyles() or addStyle() instead
	 * @param string $url Path to sheet.  Provide either a full url (beginning
	 *             with 'http', etc) or a relative path from the document root
	 *             (beginning with '/').  Otherwise it behaves identically to
	 *             addStyle() and draws from the /skins folder.
	 */
	public function addExtensionStyle( $url ) {
		wfDeprecated( __METHOD__, '1.27' );
		array_push( $this->mExtStyles, $url );
	}

	/**
	 * Get all styles added by extensions
	 *
	 * @deprecated since 1.27
	 * @return array
	 */
	function getExtStyle() {
		wfDeprecated( __METHOD__, '1.27' );
		return $this->mExtStyles;
	}

	/**
	 * Add a JavaScript file out of skins/common, or a given relative path.
	 * Internal use only. Use OutputPage::addModules() if possible.
	 *
	 * @param string $file Filename in skins/common or complete on-server path
	 *              (/foo/bar.js)
	 * @param string $version Style version of the file. Defaults to $wgStyleVersion
	 */
	public function addScriptFile( $file, $version = null ) {
		// See if $file parameter is an absolute URL or begins with a slash
		if ( substr( $file, 0, 1 ) == '/' || preg_match( '#^[a-z]*://#i', $file ) ) {
			$path = $file;
		} else {
			$path = $this->getConfig()->get( 'StylePath' ) . "/common/{$file}";
		}
		if ( is_null( $version ) ) {
			$version = $this->getConfig()->get( 'StyleVersion' );
		}
		$this->addScript( Html::linkedScript( wfAppendQuery( $path, $version ) ) );
	}

	/**
	 * Add a self-contained script tag with the given contents
	 * Internal use only. Use OutputPage::addModules() if possible.
	 *
	 * @param string $script JavaScript text, no script tags
	 */
	public function addInlineScript( $script ) {
		$this->mScripts .= Html::inlineScript( $script );
	}

	/**
	 * Filter an array of modules to remove insufficiently trustworthy members, and modules
	 * which are no longer registered (eg a page is cached before an extension is disabled)
	 * @param array $modules
	 * @param string|null $position If not null, only return modules with this position
	 * @param string $type
	 * @return array
	 */
	protected function filterModules( array $modules, $position = null,
		$type = ResourceLoaderModule::TYPE_COMBINED
	) {
		$resourceLoader = $this->getResourceLoader();
		$filteredModules = [];
		foreach ( $modules as $val ) {
			$module = $resourceLoader->getModule( $val );
			if ( $module instanceof ResourceLoaderModule
				&& $module->getOrigin() <= $this->getAllowedModules( $type )
				&& ( is_null( $position ) || $module->getPosition() == $position )
				&& ( !$this->mTarget || in_array( $this->mTarget, $module->getTargets() ) )
			) {
				$filteredModules[] = $val;
			}
		}
		return $filteredModules;
	}

	/**
	 * Get the list of modules to include on this page
	 *
	 * @param bool $filter Whether to filter out insufficiently trustworthy modules
	 * @param string|null $position If not null, only return modules with this position
	 * @param string $param
	 * @return array Array of module names
	 */
	public function getModules( $filter = false, $position = null, $param = 'mModules',
		$type = ResourceLoaderModule::TYPE_COMBINED
	) {
		$modules = array_values( array_unique( $this->$param ) );
		return $filter
			? $this->filterModules( $modules, $position, $type )
			: $modules;
	}

	/**
	 * Add one or more modules recognized by ResourceLoader. Modules added
	 * through this function will be loaded by ResourceLoader when the
	 * page loads.
	 *
	 * @param string|array $modules Module name (string) or array of module names
	 */
	public function addModules( $modules ) {
		$this->mModules = array_merge( $this->mModules, (array)$modules );
	}

	/**
	 * Get the list of module JS to include on this page
	 *
	 * @param bool $filter
	 * @param string|null $position
	 * @return array Array of module names
	 */
	public function getModuleScripts( $filter = false, $position = null ) {
		return $this->getModules( $filter, $position, 'mModuleScripts',
			ResourceLoaderModule::TYPE_SCRIPTS
		);
	}

	/**
	 * Add only JS of one or more modules recognized by ResourceLoader. Module
	 * scripts added through this function will be loaded by ResourceLoader when
	 * the page loads.
	 *
	 * @param string|array $modules Module name (string) or array of module names
	 */
	public function addModuleScripts( $modules ) {
		$this->mModuleScripts = array_merge( $this->mModuleScripts, (array)$modules );
	}

	/**
	 * Get the list of module CSS to include on this page
	 *
	 * @param bool $filter
	 * @param string|null $position
	 * @return array Array of module names
	 */
	public function getModuleStyles( $filter = false, $position = null ) {
		return $this->getModules( $filter, $position, 'mModuleStyles',
			ResourceLoaderModule::TYPE_STYLES
		);
	}

	/**
	 * Add only CSS of one or more modules recognized by ResourceLoader.
	 *
	 * Module styles added through this function will be added using standard link CSS
	 * tags, rather than as a combined Javascript and CSS package. Thus, they will
	 * load when JavaScript is disabled (unless CSS also happens to be disabled).
	 *
	 * @param string|array $modules Module name (string) or array of module names
	 */
	public function addModuleStyles( $modules ) {
		$this->mModuleStyles = array_merge( $this->mModuleStyles, (array)$modules );
	}

	/**
	 * @return null|string ResourceLoader target
	 */
	public function getTarget() {
		return $this->mTarget;
	}

	/**
	 * Sets ResourceLoader target for load.php links. If null, will be omitted
	 *
	 * @param string|null $target
	 */
	public function setTarget( $target ) {
		$this->mTarget = $target;
	}

	/**
	 * Get an array of head items
	 *
	 * @return array
	 */
	function getHeadItemsArray() {
		return $this->mHeadItems;
	}

	/**
	 * Add or replace a head item to the output
	 *
	 * Whenever possible, use more specific options like ResourceLoader modules,
	 * OutputPage::addLink(), OutputPage::addMetaLink() and OutputPage::addFeedLink()
	 * Fallback options for those are: OutputPage::addStyle, OutputPage::addScript(),
	 * OutputPage::addInlineScript() and OutputPage::addInlineStyle()
	 * This would be your very LAST fallback.
	 *
	 * @param string $name Item name
	 * @param string $value Raw HTML
	 */
	public function addHeadItem( $name, $value ) {
		$this->mHeadItems[$name] = $value;
	}

	/**
	 * Add one or more head items to the output
	 *
	 * @since 1.28
	 * @param string|string[] $value Raw HTML
	 */
	public function addHeadItems( $values ) {
		$this->mHeadItems = array_merge( $this->mHeadItems, (array)$values );
	}

	/**
	 * Check if the header item $name is already set
	 *
	 * @param string $name Item name
	 * @return bool
	 */
	public function hasHeadItem( $name ) {
		return isset( $this->mHeadItems[$name] );
	}

	/**
	 * @deprecated since 1.28 Obsolete - wgUseETag experiment was removed.
	 * @param string $tag
	 */
	public function setETag( $tag ) {
	}

	/**
	 * Set whether the output should only contain the body of the article,
	 * without any skin, sidebar, etc.
	 * Used e.g. when calling with "action=render".
	 *
	 * @param bool $only Whether to output only the body of the article
	 */
	public function setArticleBodyOnly( $only ) {
		$this->mArticleBodyOnly = $only;
	}

	/**
	 * Return whether the output will contain only the body of the article
	 *
	 * @return bool
	 */
	public function getArticleBodyOnly() {
		return $this->mArticleBodyOnly;
	}

	/**
	 * Set an additional output property
	 * @since 1.21
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function setProperty( $name, $value ) {
		$this->mProperties[$name] = $value;
	}

	/**
	 * Get an additional output property
	 * @since 1.21
	 *
	 * @param string $name
	 * @return mixed Property value or null if not found
	 */
	public function getProperty( $name ) {
		if ( isset( $this->mProperties[$name] ) ) {
			return $this->mProperties[$name];
		} else {
			return null;
		}
	}

	/**
	 * checkLastModified tells the client to use the client-cached page if
	 * possible. If successful, the OutputPage is disabled so that
	 * any future call to OutputPage->output() have no effect.
	 *
	 * Side effect: sets mLastModified for Last-Modified header
	 *
	 * @param string $timestamp
	 *
	 * @return bool True if cache-ok headers was sent.
	 */
	public function checkLastModified( $timestamp ) {
		if ( !$timestamp || $timestamp == '19700101000000' ) {
			wfDebug( __METHOD__ . ": CACHE DISABLED, NO TIMESTAMP\n" );
			return false;
		}
		$config = $this->getConfig();
		if ( !$config->get( 'CachePages' ) ) {
			wfDebug( __METHOD__ . ": CACHE DISABLED\n" );
			return false;
		}

		$timestamp = wfTimestamp( TS_MW, $timestamp );
		$modifiedTimes = [
			'page' => $timestamp,
			'user' => $this->getUser()->getTouched(),
			'epoch' => $config->get( 'CacheEpoch' )
		];
		if ( $config->get( 'UseSquid' ) ) {
			// bug 44570: the core page itself may not change, but resources might
			$modifiedTimes['sepoch'] = wfTimestamp( TS_MW, time() - $config->get( 'SquidMaxage' ) );
		}
		Hooks::run( 'OutputPageCheckLastModified', [ &$modifiedTimes, $this ] );

		$maxModified = max( $modifiedTimes );
		$this->mLastModified = wfTimestamp( TS_RFC2822, $maxModified );

		$clientHeader = $this->getRequest()->getHeader( 'If-Modified-Since' );
		if ( $clientHeader === false ) {
			wfDebug( __METHOD__ . ": client did not send If-Modified-Since header", 'private' );
			return false;
		}

		# IE sends sizes after the date like this:
		# Wed, 20 Aug 2003 06:51:19 GMT; length=5202
		# this breaks strtotime().
		$clientHeader = preg_replace( '/;.*$/', '', $clientHeader );

		MediaWiki\suppressWarnings(); // E_STRICT system time bitching
		$clientHeaderTime = strtotime( $clientHeader );
		MediaWiki\restoreWarnings();
		if ( !$clientHeaderTime ) {
			wfDebug( __METHOD__
				. ": unable to parse the client's If-Modified-Since header: $clientHeader\n" );
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
			wfTimestamp( TS_ISO_8601, $clientHeaderTime ), 'private' );
		wfDebug( __METHOD__ . ": effective Last-Modified: " .
			wfTimestamp( TS_ISO_8601, $maxModified ), 'private' );
		if ( $clientHeaderTime < $maxModified ) {
			wfDebug( __METHOD__ . ": STALE, $info", 'private' );
			return false;
		}

		# Not modified
		# Give a 304 Not Modified response code and disable body output
		wfDebug( __METHOD__ . ": NOT MODIFIED, $info", 'private' );
		ini_set( 'zlib.output_compression', 0 );
		$this->getRequest()->response()->statusHeader( 304 );
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
	 * @param string $timestamp New timestamp, in a format readable by
	 *        wfTimestamp()
	 */
	public function setLastModified( $timestamp ) {
		$this->mLastModified = wfTimestamp( TS_RFC2822, $timestamp );
	}

	/**
	 * Set the robot policy for the page: <http://www.robotstxt.org/meta.html>
	 *
	 * @param string $policy The literal string to output as the contents of
	 *   the meta tag.  Will be parsed according to the spec and output in
	 *   standardized form.
	 * @return null
	 */
	public function setRobotPolicy( $policy ) {
		$policy = Article::formatRobotPolicy( $policy );

		if ( isset( $policy['index'] ) ) {
			$this->setIndexPolicy( $policy['index'] );
		}
		if ( isset( $policy['follow'] ) ) {
			$this->setFollowPolicy( $policy['follow'] );
		}
	}

	/**
	 * Set the index policy for the page, but leave the follow policy un-
	 * touched.
	 *
	 * @param string $policy Either 'index' or 'noindex'.
	 * @return null
	 */
	public function setIndexPolicy( $policy ) {
		$policy = trim( $policy );
		if ( in_array( $policy, [ 'index', 'noindex' ] ) ) {
			$this->mIndexPolicy = $policy;
		}
	}

	/**
	 * Set the follow policy for the page, but leave the index policy un-
	 * touched.
	 *
	 * @param string $policy Either 'follow' or 'nofollow'.
	 * @return null
	 */
	public function setFollowPolicy( $policy ) {
		$policy = trim( $policy );
		if ( in_array( $policy, [ 'follow', 'nofollow' ] ) ) {
			$this->mFollowPolicy = $policy;
		}
	}

	/**
	 * Set the new value of the "action text", this will be added to the
	 * "HTML title", separated from it with " - ".
	 *
	 * @param string $text New value of the "action text"
	 */
	public function setPageTitleActionText( $text ) {
		$this->mPageTitleActionText = $text;
	}

	/**
	 * Get the value of the "action text"
	 *
	 * @return string
	 */
	public function getPageTitleActionText() {
		return $this->mPageTitleActionText;
	}

	/**
	 * "HTML title" means the contents of "<title>".
	 * It is stored as plain, unescaped text and will be run through htmlspecialchars in the skin file.
	 *
	 * @param string|Message $name
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
	 * @return string
	 */
	public function getHTMLTitle() {
		return $this->mHTMLtitle;
	}

	/**
	 * Set $mRedirectedFrom, the Title of the page which redirected us to the current page.
	 *
	 * @param Title $t
	 */
	public function setRedirectedFrom( $t ) {
		$this->mRedirectedFrom = $t;
	}

	/**
	 * "Page title" means the contents of \<h1\>. It is stored as a valid HTML
	 * fragment. This function allows good tags like \<sup\> in the \<h1\> tag,
	 * but not bad tags like \<script\>. This function automatically sets
	 * \<title\> to the same content as \<h1\> but with all tags removed. Bad
	 * tags that were escaped in \<h1\> will still be escaped in \<title\>, and
	 * good tags like \<i\> will be dropped entirely.
	 *
	 * @param string|Message $name
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
		$this->setHTMLTitle(
			$this->msg( 'pagetitle' )->rawParams( Sanitizer::stripAllTags( $nameWithTags ) )
				->inContentLanguage()
		);
	}

	/**
	 * Return the "page title", i.e. the content of the \<h1\> tag.
	 *
	 * @return string
	 */
	public function getPageTitle() {
		return $this->mPagetitle;
	}

	/**
	 * Set the Title object to use
	 *
	 * @param Title $t
	 */
	public function setTitle( Title $t ) {
		$this->getContext()->setTitle( $t );
	}

	/**
	 * Replace the subtitle with $str
	 *
	 * @param string|Message $str New value of the subtitle. String should be safe HTML.
	 */
	public function setSubtitle( $str ) {
		$this->clearSubtitle();
		$this->addSubtitle( $str );
	}

	/**
	 * Add $str to the subtitle
	 *
	 * @param string|Message $str String or Message to add to the subtitle. String should be safe HTML.
	 */
	public function addSubtitle( $str ) {
		if ( $str instanceof Message ) {
			$this->mSubtitle[] = $str->setContext( $this->getContext() )->parse();
		} else {
			$this->mSubtitle[] = $str;
		}
	}

	/**
	 * Build message object for a subtitle containing a backlink to a page
	 *
	 * @param Title $title Title to link to
	 * @param array $query Array of additional parameters to include in the link
	 * @return Message
	 * @since 1.25
	 */
	public static function buildBacklinkSubtitle( Title $title, $query = [] ) {
		if ( $title->isRedirect() ) {
			$query['redirect'] = 'no';
		}
		return wfMessage( 'backlinksubtitle' )
			->rawParams( Linker::link( $title, null, [], $query ) );
	}

	/**
	 * Add a subtitle containing a backlink to a page
	 *
	 * @param Title $title Title to link to
	 * @param array $query Array of additional parameters to include in the link
	 */
	public function addBacklinkSubtitle( Title $title, $query = [] ) {
		$this->addSubtitle( self::buildBacklinkSubtitle( $title, $query ) );
	}

	/**
	 * Clear the subtitles
	 */
	public function clearSubtitle() {
		$this->mSubtitle = [];
	}

	/**
	 * Get the subtitle
	 *
	 * @return string
	 */
	public function getSubtitle() {
		return implode( "<br />\n\t\t\t\t", $this->mSubtitle );
	}

	/**
	 * Set the page as printable, i.e. it'll be displayed with all
	 * print styles included
	 */
	public function setPrintable() {
		$this->mPrintable = true;
	}

	/**
	 * Return whether the page is "printable"
	 *
	 * @return bool
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
	 * @return bool
	 */
	public function isDisabled() {
		return $this->mDoNothing;
	}

	/**
	 * Show an "add new section" link?
	 *
	 * @return bool
	 */
	public function showNewSectionLink() {
		return $this->mNewSectionLink;
	}

	/**
	 * Forcibly hide the new section link?
	 *
	 * @return bool
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
	 * @param bool $show True: add default feeds, false: remove all feeds
	 */
	public function setSyndicated( $show = true ) {
		if ( $show ) {
			$this->setFeedAppendQuery( false );
		} else {
			$this->mFeedLinks = [];
		}
	}

	/**
	 * Add default feeds to the page header
	 * This is mainly kept for backward compatibility, see OutputPage::addFeedLink()
	 * for the new version
	 * @see addFeedLink()
	 *
	 * @param string $val Query to append to feed links or false to output
	 *        default links
	 */
	public function setFeedAppendQuery( $val ) {
		$this->mFeedLinks = [];

		foreach ( $this->getConfig()->get( 'AdvertisedFeedTypes' ) as $type ) {
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
	 * @param string $format Feed type, should be a key of $wgFeedClasses
	 * @param string $href URL
	 */
	public function addFeedLink( $format, $href ) {
		if ( in_array( $format, $this->getConfig()->get( 'AdvertisedFeedTypes' ) ) ) {
			$this->mFeedLinks[$format] = $href;
		}
	}

	/**
	 * Should we output feed links for this page?
	 * @return bool
	 */
	public function isSyndicated() {
		return count( $this->mFeedLinks ) > 0;
	}

	/**
	 * Return URLs for each supported syndication format for this page.
	 * @return array Associating format keys with URLs
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
	 * @param bool $v
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
	 * @return bool
	 */
	public function isArticle() {
		return $this->mIsarticle;
	}

	/**
	 * Set whether this page is related an article on the wiki
	 * Setting false will cause the change of "article flag" toggle to false
	 *
	 * @param bool $v
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
	 * @return bool
	 */
	public function isArticleRelated() {
		return $this->mIsArticleRelated;
	}

	/**
	 * Add new language links
	 *
	 * @param array $newLinkArray Associative array mapping language code to the page
	 *                      name
	 */
	public function addLanguageLinks( array $newLinkArray ) {
		$this->mLanguageLinks += $newLinkArray;
	}

	/**
	 * Reset the language links and add new language links
	 *
	 * @param array $newLinkArray Associative array mapping language code to the page
	 *                      name
	 */
	public function setLanguageLinks( array $newLinkArray ) {
		$this->mLanguageLinks = $newLinkArray;
	}

	/**
	 * Get the list of language links
	 *
	 * @return array Array of Interwiki Prefixed (non DB key) Titles (e.g. 'fr:Test page')
	 */
	public function getLanguageLinks() {
		return $this->mLanguageLinks;
	}

	/**
	 * Add an array of categories, with names in the keys
	 *
	 * @param array $categories Mapping category name => sort key
	 */
	public function addCategoryLinks( array $categories ) {
		global $wgContLang;

		if ( !is_array( $categories ) || count( $categories ) == 0 ) {
			return;
		}

		# Add the links to a LinkBatch
		$arr = [ NS_CATEGORY => $categories ];
		$lb = new LinkBatch;
		$lb->setArray( $arr );

		# Fetch existence plus the hiddencat property
		$dbr = wfGetDB( DB_REPLICA );
		$fields = array_merge(
			LinkCache::getSelectFields(),
			[ 'page_namespace', 'page_title', 'pp_value' ]
		);

		$res = $dbr->select( [ 'page', 'page_props' ],
			$fields,
			$lb->constructSet( 'page', $dbr ),
			__METHOD__,
			[],
			[ 'page_props' => [ 'LEFT JOIN', [
				'pp_propname' => 'hiddencat',
				'pp_page = page_id'
			] ] ]
		);

		# Add the results to the link cache
		$lb->addResultToCache( LinkCache::singleton(), $res );

		# Set all the values to 'normal'.
		$categories = array_fill_keys( array_keys( $categories ), 'normal' );

		# Mark hidden categories
		foreach ( $res as $row ) {
			if ( isset( $row->pp_value ) ) {
				$categories[$row->page_title] = 'hidden';
			}
		}

		# Add the remaining categories to the skin
		if ( Hooks::run(
			'OutputPageMakeCategoryLinks',
			[ &$this, $categories, &$this->mCategoryLinks ] )
		) {
			foreach ( $categories as $category => $type ) {
				// array keys will cast numeric category names to ints, so cast back to string
				$category = (string)$category;
				$origcategory = $category;
				$title = Title::makeTitleSafe( NS_CATEGORY, $category );
				if ( !$title ) {
					continue;
				}
				$wgContLang->findVariantLink( $category, $title, true );
				if ( $category != $origcategory && array_key_exists( $category, $categories ) ) {
					continue;
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
	 * @param array $categories Mapping category name => sort key
	 */
	public function setCategoryLinks( array $categories ) {
		$this->mCategoryLinks = [];
		$this->addCategoryLinks( $categories );
	}

	/**
	 * Get the list of category links, in a 2-D array with the following format:
	 * $arr[$type][] = $link, where $type is either "normal" or "hidden" (for
	 * hidden categories) and $link a HTML fragment with a link to the category
	 * page
	 *
	 * @return array
	 */
	public function getCategoryLinks() {
		return $this->mCategoryLinks;
	}

	/**
	 * Get the list of category names this page belongs to
	 *
	 * @return array Array of strings
	 */
	public function getCategories() {
		return $this->mCategories;
	}

	/**
	 * Add an array of indicators, with their identifiers as array
	 * keys and HTML contents as values.
	 *
	 * In case of duplicate keys, existing values are overwritten.
	 *
	 * @param array $indicators
	 * @since 1.25
	 */
	public function setIndicators( array $indicators ) {
		$this->mIndicators = $indicators + $this->mIndicators;
		// Keep ordered by key
		ksort( $this->mIndicators );
	}

	/**
	 * Get the indicators associated with this page.
	 *
	 * The array will be internally ordered by item keys.
	 *
	 * @return array Keys: identifiers, values: HTML contents
	 * @since 1.25
	 */
	public function getIndicators() {
		return $this->mIndicators;
	}

	/**
	 * Adds help link with an icon via page indicators.
	 * Link target can be overridden by a local message containing a wikilink:
	 * the message key is: lowercase action or special page name + '-helppage'.
	 * @param string $to Target MediaWiki.org page title or encoded URL.
	 * @param bool $overrideBaseUrl Whether $url is a full URL, to avoid MW.o.
	 * @since 1.25
	 */
	public function addHelpLink( $to, $overrideBaseUrl = false ) {
		$this->addModuleStyles( 'mediawiki.helplink' );
		$text = $this->msg( 'helppage-top-gethelp' )->escaped();

		if ( $overrideBaseUrl ) {
			$helpUrl = $to;
		} else {
			$toUrlencoded = wfUrlencode( str_replace( ' ', '_', $to ) );
			$helpUrl = "//www.mediawiki.org/wiki/Special:MyLanguage/$toUrlencoded";
		}

		$link = Html::rawElement(
			'a',
			[
				'href' => $helpUrl,
				'target' => '_blank',
				'class' => 'mw-helplink',
			],
			$text
		);

		$this->setIndicators( [ 'mw-helplink' => $link ] );
	}

	/**
	 * Do not allow scripts which can be modified by wiki users to load on this page;
	 * only allow scripts bundled with, or generated by, the software.
	 * Site-wide styles are controlled by a config setting, since they can be
	 * used to create a custom skin/theme, but not user-specific ones.
	 *
	 * @todo this should be given a more accurate name
	 */
	public function disallowUserJs() {
		$this->reduceAllowedModules(
			ResourceLoaderModule::TYPE_SCRIPTS,
			ResourceLoaderModule::ORIGIN_CORE_INDIVIDUAL
		);

		// Site-wide styles are controlled by a config setting, see bug 71621
		// for background on why. User styles are never allowed.
		if ( $this->getConfig()->get( 'AllowSiteCSSOnRestrictedPages' ) ) {
			$styleOrigin = ResourceLoaderModule::ORIGIN_USER_SITEWIDE;
		} else {
			$styleOrigin = ResourceLoaderModule::ORIGIN_CORE_INDIVIDUAL;
		}
		$this->reduceAllowedModules(
			ResourceLoaderModule::TYPE_STYLES,
			$styleOrigin
		);
	}

	/**
	 * Show what level of JavaScript / CSS untrustworthiness is allowed on this page
	 * @see ResourceLoaderModule::$origin
	 * @param string $type ResourceLoaderModule TYPE_ constant
	 * @return int ResourceLoaderModule ORIGIN_ class constant
	 */
	public function getAllowedModules( $type ) {
		if ( $type == ResourceLoaderModule::TYPE_COMBINED ) {
			return min( array_values( $this->mAllowedModules ) );
		} else {
			return isset( $this->mAllowedModules[$type] )
				? $this->mAllowedModules[$type]
				: ResourceLoaderModule::ORIGIN_ALL;
		}
	}

	/**
	 * Limit the highest level of CSS/JS untrustworthiness allowed.
	 *
	 * If passed the same or a higher level than the current level of untrustworthiness set, the
	 * level will remain unchanged.
	 *
	 * @param string $type
	 * @param int $level ResourceLoaderModule class constant
	 */
	public function reduceAllowedModules( $type, $level ) {
		$this->mAllowedModules[$type] = min( $this->getAllowedModules( $type ), $level );
	}

	/**
	 * Prepend $text to the body HTML
	 *
	 * @param string $text HTML
	 */
	public function prependHTML( $text ) {
		$this->mBodytext = $text . $this->mBodytext;
	}

	/**
	 * Append $text to the body HTML
	 *
	 * @param string $text HTML
	 */
	public function addHTML( $text ) {
		$this->mBodytext .= $text;
	}

	/**
	 * Shortcut for adding an Html::element via addHTML.
	 *
	 * @since 1.19
	 *
	 * @param string $element
	 * @param array $attribs
	 * @param string $contents
	 */
	public function addElement( $element, array $attribs = [], $contents = '' ) {
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
	 * @return string HTML
	 */
	public function getHTML() {
		return $this->mBodytext;
	}

	/**
	 * Get/set the ParserOptions object to use for wikitext parsing
	 *
	 * @param ParserOptions|null $options Either the ParserOption to use or null to only get the
	 *   current ParserOption object
	 * @return ParserOptions
	 */
	public function parserOptions( $options = null ) {
		if ( $options !== null && !empty( $options->isBogus ) ) {
			// Someone is trying to set a bogus pre-$wgUser PO. Check if it has
			// been changed somehow, and keep it if so.
			$anonPO = ParserOptions::newFromAnon();
			$anonPO->setEditSection( false );
			if ( !$options->matches( $anonPO ) ) {
				wfLogWarning( __METHOD__ . ': Setting a changed bogus ParserOptions: ' . wfGetAllCallers( 5 ) );
				$options->isBogus = false;
			}
		}

		if ( !$this->mParserOptions ) {
			if ( !$this->getContext()->getUser()->isSafeToLoad() ) {
				// $wgUser isn't unstubbable yet, so don't try to get a
				// ParserOptions for it. And don't cache this ParserOptions
				// either.
				$po = ParserOptions::newFromAnon();
				$po->setEditSection( false );
				$po->isBogus = true;
				if ( $options !== null ) {
					$this->mParserOptions = empty( $options->isBogus ) ? $options : null;
				}
				return $po;
			}

			$this->mParserOptions = ParserOptions::newFromContext( $this->getContext() );
			$this->mParserOptions->setEditSection( false );
		}

		if ( $options !== null && !empty( $options->isBogus ) ) {
			// They're trying to restore the bogus pre-$wgUser PO. Do the right
			// thing.
			return wfSetVar( $this->mParserOptions, null, true );
		} else {
			return wfSetVar( $this->mParserOptions, $options );
		}
	}

	/**
	 * Set the revision ID which will be seen by the wiki text parser
	 * for things such as embedded {{REVISIONID}} variable use.
	 *
	 * @param int|null $revid An positive integer, or null
	 * @return mixed Previous value
	 */
	public function setRevisionId( $revid ) {
		$val = is_null( $revid ) ? null : intval( $revid );
		return wfSetVar( $this->mRevisionId, $val );
	}

	/**
	 * Get the displayed revision ID
	 *
	 * @return int
	 */
	public function getRevisionId() {
		return $this->mRevisionId;
	}

	/**
	 * Set the timestamp of the revision which will be displayed. This is used
	 * to avoid a extra DB call in Skin::lastModified().
	 *
	 * @param string|null $timestamp
	 * @return mixed Previous value
	 */
	public function setRevisionTimestamp( $timestamp ) {
		return wfSetVar( $this->mRevisionTimestamp, $timestamp );
	}

	/**
	 * Get the timestamp of displayed revision.
	 * This will be null if not filled by setRevisionTimestamp().
	 *
	 * @return string|null
	 */
	public function getRevisionTimestamp() {
		return $this->mRevisionTimestamp;
	}

	/**
	 * Set the displayed file version
	 *
	 * @param File|bool $file
	 * @return mixed Previous value
	 */
	public function setFileVersion( $file ) {
		$val = null;
		if ( $file instanceof File && $file->exists() ) {
			$val = [ 'time' => $file->getTimestamp(), 'sha1' => $file->getSha1() ];
		}
		return wfSetVar( $this->mFileVersion, $val, true );
	}

	/**
	 * Get the displayed file version
	 *
	 * @return array|null ('time' => MW timestamp, 'sha1' => sha1)
	 */
	public function getFileVersion() {
		return $this->mFileVersion;
	}

	/**
	 * Get the templates used on this page
	 *
	 * @return array (namespace => dbKey => revId)
	 * @since 1.18
	 */
	public function getTemplateIds() {
		return $this->mTemplateIds;
	}

	/**
	 * Get the files used on this page
	 *
	 * @return array (dbKey => array('time' => MW timestamp or null, 'sha1' => sha1 or ''))
	 * @since 1.18
	 */
	public function getFileSearchOptions() {
		return $this->mImageTimeKeys;
	}

	/**
	 * Convert wikitext to HTML and add it to the buffer
	 * Default assumes that the current page title will be used.
	 *
	 * @param string $text
	 * @param bool $linestart Is this the start of a line?
	 * @param bool $interface Is this text in the user interface language?
	 * @throws MWException
	 */
	public function addWikiText( $text, $linestart = true, $interface = true ) {
		$title = $this->getTitle(); // Work around E_STRICT
		if ( !$title ) {
			throw new MWException( 'Title is null' );
		}
		$this->addWikiTextTitle( $text, $title, $linestart, /*tidy*/false, $interface );
	}

	/**
	 * Add wikitext with a custom Title object
	 *
	 * @param string $text Wikitext
	 * @param Title $title
	 * @param bool $linestart Is this the start of a line?
	 */
	public function addWikiTextWithTitle( $text, &$title, $linestart = true ) {
		$this->addWikiTextTitle( $text, $title, $linestart );
	}

	/**
	 * Add wikitext with a custom Title object and tidy enabled.
	 *
	 * @param string $text Wikitext
	 * @param Title $title
	 * @param bool $linestart Is this the start of a line?
	 */
	function addWikiTextTitleTidy( $text, &$title, $linestart = true ) {
		$this->addWikiTextTitle( $text, $title, $linestart, true );
	}

	/**
	 * Add wikitext with tidy enabled
	 *
	 * @param string $text Wikitext
	 * @param bool $linestart Is this the start of a line?
	 */
	public function addWikiTextTidy( $text, $linestart = true ) {
		$title = $this->getTitle();
		$this->addWikiTextTitleTidy( $text, $title, $linestart );
	}

	/**
	 * Add wikitext with a custom Title object
	 *
	 * @param string $text Wikitext
	 * @param Title $title
	 * @param bool $linestart Is this the start of a line?
	 * @param bool $tidy Whether to use tidy
	 * @param bool $interface Whether it is an interface message
	 *   (for example disables conversion)
	 */
	public function addWikiTextTitle( $text, Title $title, $linestart,
		$tidy = false, $interface = false
	) {
		global $wgParser;

		$popts = $this->parserOptions();
		$oldTidy = $popts->setTidy( $tidy );
		$popts->setInterfaceMessage( (bool)$interface );

		$parserOutput = $wgParser->getFreshParser()->parse(
			$text, $title, $popts,
			$linestart, true, $this->mRevisionId
		);

		$popts->setTidy( $oldTidy );

		$this->addParserOutput( $parserOutput );

	}

	/**
	 * Add a ParserOutput object, but without Html.
	 *
	 * @deprecated since 1.24, use addParserOutputMetadata() instead.
	 * @param ParserOutput $parserOutput
	 */
	public function addParserOutputNoText( $parserOutput ) {
		wfDeprecated( __METHOD__, '1.24' );
		$this->addParserOutputMetadata( $parserOutput );
	}

	/**
	 * Add all metadata associated with a ParserOutput object, but without the actual HTML. This
	 * includes categories, language links, ResourceLoader modules, effects of certain magic words,
	 * and so on.
	 *
	 * @since 1.24
	 * @param ParserOutput $parserOutput
	 */
	public function addParserOutputMetadata( $parserOutput ) {
		$this->mLanguageLinks += $parserOutput->getLanguageLinks();
		$this->addCategoryLinks( $parserOutput->getCategories() );
		$this->setIndicators( $parserOutput->getIndicators() );
		$this->mNewSectionLink = $parserOutput->getNewSection();
		$this->mHideNewSectionLink = $parserOutput->getHideNewSection();

		if ( !$parserOutput->isCacheable() ) {
			$this->enableClientCache( false );
		}
		$this->mNoGallery = $parserOutput->getNoGallery();
		$this->mHeadItems = array_merge( $this->mHeadItems, $parserOutput->getHeadItems() );
		$this->addModules( $parserOutput->getModules() );
		$this->addModuleScripts( $parserOutput->getModuleScripts() );
		$this->addModuleStyles( $parserOutput->getModuleStyles() );
		$this->addJsConfigVars( $parserOutput->getJsConfigVars() );
		$this->mPreventClickjacking = $this->mPreventClickjacking
			|| $parserOutput->preventClickjacking();

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
		$parserOutputHooks = $this->getConfig()->get( 'ParserOutputHooks' );
		foreach ( $parserOutput->getOutputHooks() as $hookInfo ) {
			list( $hookName, $data ) = $hookInfo;
			if ( isset( $parserOutputHooks[$hookName] ) ) {
				call_user_func( $parserOutputHooks[$hookName], $this, $parserOutput, $data );
			}
		}

		// enable OOUI if requested via ParserOutput
		if ( $parserOutput->getEnableOOUI() ) {
			$this->enableOOUI();
		}

		// Link flags are ignored for now, but may in the future be
		// used to mark individual language links.
		$linkFlags = [];
		Hooks::run( 'LanguageLinks', [ $this->getTitle(), &$this->mLanguageLinks, &$linkFlags ] );
		Hooks::run( 'OutputPageParserOutput', [ &$this, $parserOutput ] );
	}

	/**
	 * Add the HTML and enhancements for it (like ResourceLoader modules) associated with a
	 * ParserOutput object, without any other metadata.
	 *
	 * @since 1.24
	 * @param ParserOutput $parserOutput
	 */
	public function addParserOutputContent( $parserOutput ) {
		$this->addParserOutputText( $parserOutput );

		$this->addModules( $parserOutput->getModules() );
		$this->addModuleScripts( $parserOutput->getModuleScripts() );
		$this->addModuleStyles( $parserOutput->getModuleStyles() );

		$this->addJsConfigVars( $parserOutput->getJsConfigVars() );
	}

	/**
	 * Add the HTML associated with a ParserOutput object, without any metadata.
	 *
	 * @since 1.24
	 * @param ParserOutput $parserOutput
	 */
	public function addParserOutputText( $parserOutput ) {
		$text = $parserOutput->getText();
		Hooks::run( 'OutputPageBeforeHTML', [ &$this, &$text ] );
		$this->addHTML( $text );
	}

	/**
	 * Add everything from a ParserOutput object.
	 *
	 * @param ParserOutput $parserOutput
	 */
	function addParserOutput( $parserOutput ) {
		$this->addParserOutputMetadata( $parserOutput );
		$parserOutput->setTOCEnabled( $this->mEnableTOC );

		// Touch section edit links only if not previously disabled
		if ( $parserOutput->getEditSectionTokens() ) {
			$parserOutput->setEditSectionTokens( $this->mEnableSectionEditLinks );
		}

		$this->addParserOutputText( $parserOutput );
	}

	/**
	 * Add the output of a QuickTemplate to the output buffer
	 *
	 * @param QuickTemplate $template
	 */
	public function addTemplate( &$template ) {
		$this->addHTML( $template->getHTML() );
	}

	/**
	 * Parse wikitext and return the HTML.
	 *
	 * @param string $text
	 * @param bool $linestart Is this the start of a line?
	 * @param bool $interface Use interface language ($wgLang instead of
	 *   $wgContLang) while parsing language sensitive magic words like GRAMMAR and PLURAL.
	 *   This also disables LanguageConverter.
	 * @param Language $language Target language object, will override $interface
	 * @throws MWException
	 * @return string HTML
	 */
	public function parse( $text, $linestart = true, $interface = false, $language = null ) {
		global $wgParser;

		if ( is_null( $this->getTitle() ) ) {
			throw new MWException( 'Empty $mTitle in ' . __METHOD__ );
		}

		$popts = $this->parserOptions();
		if ( $interface ) {
			$popts->setInterfaceMessage( true );
		}
		if ( $language !== null ) {
			$oldLang = $popts->setTargetLanguage( $language );
		}

		$parserOutput = $wgParser->getFreshParser()->parse(
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
	 * @param string $text
	 * @param bool $linestart Is this the start of a line?
	 * @param bool $interface Use interface language ($wgLang instead of
	 *   $wgContLang) while parsing language sensitive magic
	 *   words like GRAMMAR and PLURAL
	 * @return string HTML
	 */
	public function parseInline( $text, $linestart = true, $interface = false ) {
		$parsed = $this->parse( $text, $linestart, $interface );
		return Parser::stripOuterParagraph( $parsed );
	}

	/**
	 * @param $maxage
	 * @deprecated since 1.27 Use setCdnMaxage() instead
	 */
	public function setSquidMaxage( $maxage ) {
		$this->setCdnMaxage( $maxage );
	}

	/**
	 * Set the value of the "s-maxage" part of the "Cache-control" HTTP header
	 *
	 * @param int $maxage Maximum cache time on the CDN, in seconds.
	 */
	public function setCdnMaxage( $maxage ) {
		$this->mCdnMaxage = min( $maxage, $this->mCdnMaxageLimit );
	}

	/**
	 * Lower the value of the "s-maxage" part of the "Cache-control" HTTP header
	 *
	 * @param int $maxage Maximum cache time on the CDN, in seconds
	 * @since 1.27
	 */
	public function lowerCdnMaxage( $maxage ) {
		$this->mCdnMaxageLimit = min( $maxage, $this->mCdnMaxageLimit );
		$this->setCdnMaxage( $this->mCdnMaxage );
	}

	/**
	 * Get TTL in [$minTTL,$maxTTL] in pass it to lowerCdnMaxage()
	 *
	 * This sets and returns $minTTL if $mtime is false or null. Otherwise,
	 * the TTL is higher the older the $mtime timestamp is. Essentially, the
	 * TTL is 90% of the age of the object, subject to the min and max.
	 *
	 * @param string|integer|float|bool|null $mtime Last-Modified timestamp
	 * @param integer $minTTL Mimimum TTL in seconds [default: 1 minute]
	 * @param integer $maxTTL Maximum TTL in seconds [default: $wgSquidMaxage]
	 * @return integer TTL in seconds
	 * @since 1.28
	 */
	public function adaptCdnTTL( $mtime, $minTTL = 0, $maxTTL = 0 ) {
		$minTTL = $minTTL ?: IExpiringStore::TTL_MINUTE;
		$maxTTL = $maxTTL ?: $this->getConfig()->get( 'SquidMaxage' );

		if ( $mtime === null || $mtime === false ) {
			return $minTTL; // entity does not exist
		}

		$age = time() - wfTimestamp( TS_UNIX, $mtime );
		$adaptiveTTL = max( .9 * $age, $minTTL );
		$adaptiveTTL = min( $adaptiveTTL, $maxTTL );

		$this->lowerCdnMaxage( (int)$adaptiveTTL );

		return $adaptiveTTL;
	}

	/**
	 * Use enableClientCache(false) to force it to send nocache headers
	 *
	 * @param bool $state
	 *
	 * @return bool
	 */
	public function enableClientCache( $state ) {
		return wfSetVar( $this->mEnableClientCache, $state );
	}

	/**
	 * Get the list of cookies that will influence on the cache
	 *
	 * @return array
	 */
	function getCacheVaryCookies() {
		static $cookies;
		if ( $cookies === null ) {
			$config = $this->getConfig();
			$cookies = array_merge(
				SessionManager::singleton()->getVaryCookies(),
				[
					'forceHTTPS',
				],
				$config->get( 'CacheVaryCookies' )
			);
			Hooks::run( 'GetCacheVaryCookies', [ $this, &$cookies ] );
		}
		return $cookies;
	}

	/**
	 * Check if the request has a cache-varying cookie header
	 * If it does, it's very important that we don't allow public caching
	 *
	 * @return bool
	 */
	function haveCacheVaryCookies() {
		$request = $this->getRequest();
		foreach ( $this->getCacheVaryCookies() as $cookieName ) {
			if ( $request->getCookie( $cookieName, '', '' ) !== '' ) {
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
	 * @param string $header Header name
	 * @param string[]|null $option Options for the Key header. See
	 * https://datatracker.ietf.org/doc/draft-fielding-http-key/
	 * for the list of valid options.
	 */
	public function addVaryHeader( $header, array $option = null ) {
		if ( !array_key_exists( $header, $this->mVaryHeader ) ) {
			$this->mVaryHeader[$header] = [];
		}
		if ( !is_array( $option ) ) {
			$option = [];
		}
		$this->mVaryHeader[$header] = array_unique( array_merge( $this->mVaryHeader[$header], $option ) );
	}

	/**
	 * Return a Vary: header on which to vary caches. Based on the keys of $mVaryHeader,
	 * such as Accept-Encoding or Cookie
	 *
	 * @return string
	 */
	public function getVaryHeader() {
		// If we vary on cookies, let's make sure it's always included here too.
		if ( $this->getCacheVaryCookies() ) {
			$this->addVaryHeader( 'Cookie' );
		}

		foreach ( SessionManager::singleton()->getVaryHeaders() as $header => $options ) {
			$this->addVaryHeader( $header, $options );
		}
		return 'Vary: ' . implode( ', ', array_keys( $this->mVaryHeader ) );
	}

	/**
	 * Get a complete Key header
	 *
	 * @return string
	 */
	public function getKeyHeader() {
		$cvCookies = $this->getCacheVaryCookies();

		$cookiesOption = [];
		foreach ( $cvCookies as $cookieName ) {
			$cookiesOption[] = 'param=' . $cookieName;
		}
		$this->addVaryHeader( 'Cookie', $cookiesOption );

		foreach ( SessionManager::singleton()->getVaryHeaders() as $header => $options ) {
			$this->addVaryHeader( $header, $options );
		}

		$headers = [];
		foreach ( $this->mVaryHeader as $header => $option ) {
			$newheader = $header;
			if ( is_array( $option ) && count( $option ) > 0 ) {
				$newheader .= ';' . implode( ';', $option );
			}
			$headers[] = $newheader;
		}
		$key = 'Key: ' . implode( ',', $headers );

		return $key;
	}

	/**
	 * T23672: Add Accept-Language to Vary and Key headers
	 * if there's no 'variant' parameter existed in GET.
	 *
	 * For example:
	 *   /w/index.php?title=Main_page should always be served; but
	 *   /w/index.php?title=Main_page&variant=zh-cn should never be served.
	 */
	function addAcceptLanguage() {
		$title = $this->getTitle();
		if ( !$title instanceof Title ) {
			return;
		}

		$lang = $title->getPageLanguage();
		if ( !$this->getRequest()->getCheck( 'variant' ) && $lang->hasVariants() ) {
			$variants = $lang->getVariants();
			$aloption = [];
			foreach ( $variants as $variant ) {
				if ( $variant === $lang->getCode() ) {
					continue;
				} else {
					$aloption[] = 'substr=' . $variant;

					// IE and some other browsers use BCP 47 standards in
					// their Accept-Language header, like "zh-CN" or "zh-Hant".
					// We should handle these too.
					$variantBCP47 = wfBCP47( $variant );
					if ( $variantBCP47 !== $variant ) {
						$aloption[] = 'substr=' . $variantBCP47;
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
	 * @param bool $enable
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
	 * Get the prevent-clickjacking flag
	 *
	 * @since 1.24
	 * @return bool
	 */
	public function getPreventClickjacking() {
		return $this->mPreventClickjacking;
	}

	/**
	 * Get the X-Frame-Options header value (without the name part), or false
	 * if there isn't one. This is used by Skin to determine whether to enable
	 * JavaScript frame-breaking, for clients that don't support X-Frame-Options.
	 *
	 * @return string
	 */
	public function getFrameOptions() {
		$config = $this->getConfig();
		if ( $config->get( 'BreakFrames' ) ) {
			return 'DENY';
		} elseif ( $this->mPreventClickjacking && $config->get( 'EditPageFrameOptions' ) ) {
			return $config->get( 'EditPageFrameOptions' );
		}
		return false;
	}

	/**
	 * Send cache control HTTP headers
	 */
	public function sendCacheControl() {
		$response = $this->getRequest()->response();
		$config = $this->getConfig();

		$this->addVaryHeader( 'Cookie' );
		$this->addAcceptLanguage();

		# don't serve compressed data to clients who can't handle it
		# maintain different caches for logged-in users and non-logged in ones
		$response->header( $this->getVaryHeader() );

		if ( $config->get( 'UseKeyHeader' ) ) {
			$response->header( $this->getKeyHeader() );
		}

		if ( $this->mEnableClientCache ) {
			if (
				$config->get( 'UseSquid' ) &&
				!$response->hasCookies() &&
				!SessionManager::getGlobalSession()->isPersistent() &&
				!$this->isPrintable() &&
				$this->mCdnMaxage != 0 &&
				!$this->haveCacheVaryCookies()
			) {
				if ( $config->get( 'UseESI' ) ) {
					# We'll purge the proxy cache explicitly, but require end user agents
					# to revalidate against the proxy on each visit.
					# Surrogate-Control controls our CDN, Cache-Control downstream caches
					wfDebug( __METHOD__ . ": proxy caching with ESI; {$this->mLastModified} **", 'private' );
					# start with a shorter timeout for initial testing
					# header( 'Surrogate-Control: max-age=2678400+2678400, content="ESI/1.0"');
					$response->header( 'Surrogate-Control: max-age=' . $config->get( 'SquidMaxage' )
						. '+' . $this->mCdnMaxage . ', content="ESI/1.0"' );
					$response->header( 'Cache-Control: s-maxage=0, must-revalidate, max-age=0' );
				} else {
					# We'll purge the proxy cache for anons explicitly, but require end user agents
					# to revalidate against the proxy on each visit.
					# IMPORTANT! The CDN needs to replace the Cache-Control header with
					# Cache-Control: s-maxage=0, must-revalidate, max-age=0
					wfDebug( __METHOD__ . ": local proxy caching; {$this->mLastModified} **", 'private' );
					# start with a shorter timeout for initial testing
					# header( "Cache-Control: s-maxage=2678400, must-revalidate, max-age=0" );
					$response->header( 'Cache-Control: s-maxage=' . $this->mCdnMaxage
						. ', must-revalidate, max-age=0' );
				}
			} else {
				# We do want clients to cache if they can, but they *must* check for updates
				# on revisiting the page.
				wfDebug( __METHOD__ . ": private caching; {$this->mLastModified} **", 'private' );
				$response->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
				$response->header( "Cache-Control: private, must-revalidate, max-age=0" );
			}
			if ( $this->mLastModified ) {
				$response->header( "Last-Modified: {$this->mLastModified}" );
			}
		} else {
			wfDebug( __METHOD__ . ": no caching **", 'private' );

			# In general, the absence of a last modified header should be enough to prevent
			# the client from using its cache. We send a few other things just to make sure.
			$response->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
			$response->header( 'Cache-Control: no-cache, no-store, max-age=0, must-revalidate' );
			$response->header( 'Pragma: no-cache' );
		}
	}

	/**
	 * Finally, all the text has been munged and accumulated into
	 * the object, let's actually output it:
	 *
	 * @param bool $return Set to true to get the result as a string rather than sending it
	 * @return string|null
	 * @throws Exception
	 * @throws FatalError
	 * @throws MWException
	 */
	public function output( $return = false ) {
		global $wgContLang;

		if ( $this->mDoNothing ) {
			return $return ? '' : null;
		}

		$response = $this->getRequest()->response();
		$config = $this->getConfig();

		if ( $this->mRedirect != '' ) {
			# Standards require redirect URLs to be absolute
			$this->mRedirect = wfExpandUrl( $this->mRedirect, PROTO_CURRENT );

			$redirect = $this->mRedirect;
			$code = $this->mRedirectCode;

			if ( Hooks::run( "BeforePageRedirect", [ $this, &$redirect, &$code ] ) ) {
				if ( $code == '301' || $code == '303' ) {
					if ( !$config->get( 'DebugRedirects' ) ) {
						$response->statusHeader( $code );
					}
					$this->mLastModified = wfTimestamp( TS_RFC2822 );
				}
				if ( $config->get( 'VaryOnXFP' ) ) {
					$this->addVaryHeader( 'X-Forwarded-Proto' );
				}
				$this->sendCacheControl();

				$response->header( "Content-Type: text/html; charset=utf-8" );
				if ( $config->get( 'DebugRedirects' ) ) {
					$url = htmlspecialchars( $redirect );
					print "<html>\n<head>\n<title>Redirect</title>\n</head>\n<body>\n";
					print "<p>Location: <a href=\"$url\">$url</a></p>\n";
					print "</body>\n</html>\n";
				} else {
					$response->header( 'Location: ' . $redirect );
				}
			}

			return $return ? '' : null;
		} elseif ( $this->mStatusCode ) {
			$response->statusHeader( $this->mStatusCode );
		}

		# Buffer output; final headers may depend on later processing
		ob_start();

		$response->header( 'Content-type: ' . $config->get( 'MimeType' ) . '; charset=UTF-8' );
		$response->header( 'Content-language: ' . $wgContLang->getHtmlCode() );

		// Avoid Internet Explorer "compatibility view" in IE 8-10, so that
		// jQuery etc. can work correctly.
		$response->header( 'X-UA-Compatible: IE=Edge' );

		// Prevent framing, if requested
		$frameOptions = $this->getFrameOptions();
		if ( $frameOptions ) {
			$response->header( "X-Frame-Options: $frameOptions" );
		}

		if ( $this->mArticleBodyOnly ) {
			echo $this->mBodytext;
		} else {
			$sk = $this->getSkin();
			// add skin specific modules
			$modules = $sk->getDefaultModules();

			// Enforce various default modules for all pages and all skins
			$coreModules = [
				// Keep this list as small as possible
				'site',
				'mediawiki.page.startup',
				'mediawiki.user',
			];

			// Support for high-density display images if enabled
			if ( $config->get( 'ResponsiveImages' ) ) {
				$coreModules[] = 'mediawiki.hidpi';
			}

			$this->addModules( $coreModules );
			foreach ( $modules as $group ) {
				$this->addModules( $group );
			}
			MWDebug::addModules( $this );

			// Hook that allows last minute changes to the output page, e.g.
			// adding of CSS or Javascript by extensions.
			Hooks::run( 'BeforePageDisplay', [ &$this, &$sk ] );

			try {
				$sk->outputPage();
			} catch ( Exception $e ) {
				ob_end_clean(); // bug T129657
				throw $e;
			}
		}

		try {
			// This hook allows last minute changes to final overall output by modifying output buffer
			Hooks::run( 'AfterFinalPageOutput', [ $this ] );
		} catch ( Exception $e ) {
			ob_end_clean(); // bug T129657
			throw $e;
		}

		$this->sendCacheControl();

		if ( $return ) {
			return ob_get_clean();
		} else {
			ob_end_flush();
			return null;
		}
	}

	/**
	 * Prepare this object to display an error page; disable caching and
	 * indexing, clear the current text and redirect, set the page's title
	 * and optionally an custom HTML title (content of the "<title>" tag).
	 *
	 * @param string|Message $pageTitle Will be passed directly to setPageTitle()
	 * @param string|Message $htmlTitle Will be passed directly to setHTMLTitle();
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
	 * showErrorPage( 'titlemsg', 'pagetextmsg' );
	 * showErrorPage( 'titlemsg', 'pagetextmsg', [ 'param1', 'param2' ] );
	 * showErrorPage( 'titlemsg', $messageObject );
	 * showErrorPage( $titleMessageObject, $messageObject );
	 *
	 * @param string|Message $title Message key (string) for page title, or a Message object
	 * @param string|Message $msg Message key (string) for page text, or a Message object
	 * @param array $params Message parameters; ignored if $msg is a Message object
	 */
	public function showErrorPage( $title, $msg, $params = [] ) {
		if ( !$title instanceof Message ) {
			$title = $this->msg( $title );
		}

		$this->prepareErrorPage( $title );

		if ( $msg instanceof Message ) {
			if ( $params !== [] ) {
				trigger_error( 'Argument ignored: $params. The message parameters argument '
					. 'is discarded when the $msg argument is a Message object instead of '
					. 'a string.', E_USER_NOTICE );
			}
			$this->addHTML( $msg->parseAsBlock() );
		} else {
			$this->addWikiMsgArray( $msg, $params );
		}

		$this->returnToMain();
	}

	/**
	 * Output a standard permission error page
	 *
	 * @param array $errors Error message keys or [key, param...] arrays
	 * @param string $action Action that was denied or null if unknown
	 */
	public function showPermissionsErrorPage( array $errors, $action = null ) {
		foreach ( $errors as $key => $error ) {
			$errors[$key] = (array)$error;
		}

		// For some action (read, edit, create and upload), display a "login to do this action"
		// error if all of the following conditions are met:
		// 1. the user is not logged in
		// 2. the only error is insufficient permissions (i.e. no block or something else)
		// 3. the error can be avoided simply by logging in
		if ( in_array( $action, [ 'read', 'edit', 'createpage', 'createtalk', 'upload' ] )
			&& $this->getUser()->isAnon() && count( $errors ) == 1 && isset( $errors[0][0] )
			&& ( $errors[0][0] == 'badaccess-groups' || $errors[0][0] == 'badaccess-group0' )
			&& ( User::groupHasPermission( 'user', $action )
			|| User::groupHasPermission( 'autoconfirmed', $action ) )
		) {
			$displayReturnto = null;

			# Due to bug 32276, if a user does not have read permissions,
			# $this->getTitle() will just give Special:Badtitle, which is
			# not especially useful as a returnto parameter. Use the title
			# from the request instead, if there was one.
			$request = $this->getRequest();
			$returnto = Title::newFromText( $request->getVal( 'title', '' ) );
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

			$query = [];

			if ( $returnto ) {
				$query['returnto'] = $returnto->getPrefixedText();

				if ( !$request->wasPosted() ) {
					$returntoquery = $request->getValues();
					unset( $returntoquery['title'] );
					unset( $returntoquery['returnto'] );
					unset( $returntoquery['returntoquery'] );
					$query['returntoquery'] = wfArrayToCgi( $returntoquery );
				}
			}
			$loginLink = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Userlogin' ),
				$this->msg( 'loginreqlink' )->escaped(),
				[],
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
	 * @param mixed $version The version of MediaWiki needed to use the page
	 */
	public function versionRequired( $version ) {
		$this->prepareErrorPage( $this->msg( 'versionrequired', $version ) );

		$this->addWikiMsg( 'versionrequiredtext', $version );
		$this->returnToMain();
	}

	/**
	 * Format a list of error messages
	 *
	 * @param array $errors Array of arrays returned by Title::getUserPermissionsErrors
	 * @param string $action Action that was denied or null if unknown
	 * @return string The wikitext error-messages, formatted into a list.
	 */
	public function formatPermissionsErrorMessage( array $errors, $action = null ) {
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

			foreach ( $errors as $error ) {
				$text .= '<li>';
				$text .= call_user_func_array( [ $this, 'msg' ], $error )->plain();
				$text .= "</li>\n";
			}
			$text .= '</ul>';
		} else {
			$text .= "<div class=\"permissions-errors\">\n" .
					call_user_func_array( [ $this, 'msg' ], reset( $errors ) )->plain() .
					"\n</div>";
		}

		return $text;
	}

	/**
	 * Display a page stating that the Wiki is in read-only mode.
	 * Should only be called after wfReadOnly() has returned true.
	 *
	 * Historically, this function was used to show the source of the page that the user
	 * was trying to edit and _also_ permissions error messages. The relevant code was
	 * moved into EditPage in 1.19 (r102024 / d83c2a431c2a) and removed here in 1.25.
	 *
	 * @deprecated since 1.25; throw the exception directly
	 * @throws ReadOnlyError
	 */
	public function readOnlyPage() {
		if ( func_num_args() > 0 ) {
			throw new MWException( __METHOD__ . ' no longer accepts arguments since 1.25.' );
		}

		throw new ReadOnlyError;
	}

	/**
	 * Turn off regular page output and return an error response
	 * for when rate limiting has triggered.
	 *
	 * @deprecated since 1.25; throw the exception directly
	 */
	public function rateLimited() {
		wfDeprecated( __METHOD__, '1.25' );
		throw new ThrottledError;
	}

	/**
	 * Show a warning about replica DB lag
	 *
	 * If the lag is higher than $wgSlaveLagCritical seconds,
	 * then the warning is a bit more obvious. If the lag is
	 * lower than $wgSlaveLagWarning, then no warning is shown.
	 *
	 * @param int $lag Slave lag
	 */
	public function showLagWarning( $lag ) {
		$config = $this->getConfig();
		if ( $lag >= $config->get( 'SlaveLagWarning' ) ) {
			$lag = floor( $lag ); // floor to avoid nano seconds to display
			$message = $lag < $config->get( 'SlaveLagCritical' )
				? 'lag-warn-normal'
				: 'lag-warn-high';
			$wrap = Html::rawElement( 'div', [ 'class' => "mw-{$message}" ], "\n$1\n" );
			$this->wrapWikiMsg( "$wrap\n", [ $message, $this->getLanguage()->formatNum( $lag ) ] );
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
	 * @param Title $title Title to link
	 * @param array $query Query string parameters
	 * @param string $text Text of the link (input is not escaped)
	 * @param array $options Options array to pass to Linker
	 */
	public function addReturnTo( $title, array $query = [], $text = null, $options = [] ) {
		$link = $this->msg( 'returnto' )->rawParams(
			Linker::link( $title, $text, [], $query, $options ) )->escaped();
		$this->addHTML( "<p id=\"mw-returnto\">{$link}</p>\n" );
	}

	/**
	 * Add a "return to" link pointing to a specified title,
	 * or the title indicated in the request, or else the main page
	 *
	 * @param mixed $unused
	 * @param Title|string $returnto Title or String to return to
	 * @param string $returntoquery Query string for the return to link
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

	private function getRlClientContext() {
		if ( !$this->rlClientContext ) {
			$query = ResourceLoader::makeLoaderQuery(
				[], // modules; not relevant
				$this->getLanguage()->getCode(),
				$this->getSkin()->getSkinName(),
				$this->getUser()->isLoggedIn() ? $this->getUser()->getName() : null,
				null, // version; not relevant
				ResourceLoader::inDebugMode(),
				null, // only; not relevant
				$this->isPrintable(),
				$this->getRequest()->getBool( 'handheld' )
			);
			$this->rlClientContext = new ResourceLoaderContext(
				$this->getResourceLoader(),
				new FauxRequest( $query )
			);
		}
		return $this->rlClientContext;
	}

	/**
	 * Call this to freeze the module queue and JS config and create a formatter.
	 *
	 * Depending on the Skin, this may get lazy-initialised in either headElement() or
	 * getBottomScripts(). See SkinTemplate::prepareQuickTemplate(). Calling this too early may
	 * cause unexpected side-effects since disallowUserJs() may be called at any time to change
	 * the module filters retroactively. Skins and extension hooks may also add modules until very
	 * late in the request lifecycle.
	 *
	 * @return ResourceLoaderClientHtml
	 */
	public function getRlClient() {
		if ( !$this->rlClient ) {
			$context = $this->getRlClientContext();
			$rl = $this->getResourceLoader();
			$this->addModules( [
				'user.options',
				'user.tokens',
			] );
			$this->addModuleStyles( [
				'site.styles',
				'noscript',
				'user.styles',
				'user.cssprefs',
			] );
			$this->getSkin()->setupSkinUserCss( $this );

			// Prepare exempt modules for buildExemptModules()
			$exemptGroups = [ 'site' => [], 'noscript' => [], 'private' => [], 'user' => [] ];
			$exemptStates = [];
			$moduleStyles = $this->getModuleStyles( /*filter*/ true );

			// Preload getTitleInfo for isKnownEmpty calls below and in ResourceLoaderClientHtml
			// Separate user-specific batch for improved cache-hit ratio.
			$userBatch = [ 'user.styles', 'user' ];
			$siteBatch = array_diff( $moduleStyles, $userBatch );
			$dbr = wfGetDB( DB_REPLICA );
			ResourceLoaderWikiModule::preloadTitleInfo( $context, $dbr, $siteBatch );
			ResourceLoaderWikiModule::preloadTitleInfo( $context, $dbr, $userBatch );

			// Filter out modules handled by buildExemptModules()
			$moduleStyles = array_filter( $moduleStyles,
				function ( $name ) use ( $rl, $context, &$exemptGroups, &$exemptStates ) {
					$module = $rl->getModule( $name );
					if ( $module ) {
						if ( $name === 'user.styles' && $this->isUserCssPreview() ) {
							$exemptStates[$name] = 'ready';
							// Special case in buildExemptModules()
							return false;
						}
						$group = $module->getGroup();
						if ( isset( $exemptGroups[$group] ) ) {
							$exemptStates[$name] = 'ready';
							if ( !$module->isKnownEmpty( $context ) ) {
								// E.g. Don't output empty <styles>
								$exemptGroups[$group][] = $name;
							}
							return false;
						}
					}
					return true;
				}
			);
			$this->rlExemptStyleModules = $exemptGroups;

			$isUserModuleFiltered = !$this->filterModules( [ 'user' ] );
			// If this page filters out 'user', makeResourceLoaderLink will drop it.
			// Avoid indefinite "loading" state or untrue "ready" state (T145368).
			if ( !$isUserModuleFiltered ) {
				// Manually handled by getBottomScripts()
				$userModule = $rl->getModule( 'user' );
				$userState = $userModule->isKnownEmpty( $context ) && !$this->isUserJsPreview()
					? 'ready'
					: 'loading';
				$this->rlUserModuleState = $exemptStates['user'] = $userState;
			}

			$rlClient = new ResourceLoaderClientHtml( $context, $this->getTarget() );
			$rlClient->setConfig( $this->getJSVars() );
			$rlClient->setModules( $this->getModules( /*filter*/ true ) );
			$rlClient->setModuleStyles( $moduleStyles );
			$rlClient->setModuleScripts( $this->getModuleScripts( /*filter*/ true ) );
			$rlClient->setExemptStates( $exemptStates );
			$this->rlClient = $rlClient;
		}
		return $this->rlClient;
	}

	/**
	 * @param Skin $sk The given Skin
	 * @param bool $includeStyle Unused
	 * @return string The doctype, opening "<html>", and head element.
	 */
	public function headElement( Skin $sk, $includeStyle = true ) {
		global $wgContLang;

		$userdir = $this->getLanguage()->getDir();
		$sitedir = $wgContLang->getDir();

		$pieces = [];
		$pieces[] = Html::htmlHeader( Sanitizer::mergeAttributes(
			$this->getRlClient()->getDocumentAttributes(),
			$sk->getHtmlElementAttributes()
		) );
		$pieces[] = Html::openElement( 'head' );

		if ( $this->getHTMLTitle() == '' ) {
			$this->setHTMLTitle( $this->msg( 'pagetitle', $this->getPageTitle() )->inContentLanguage() );
		}

		if ( !Html::isXmlMimeType( $this->getConfig()->get( 'MimeType' ) ) ) {
			// Add <meta charset="UTF-8">
			// This should be before <title> since it defines the charset used by
			// text including the text inside <title>.
			// The spec recommends defining XHTML5's charset using the XML declaration
			// instead of meta.
			// Our XML declaration is output by Html::htmlHeader.
			// http://www.whatwg.org/html/semantics.html#attr-meta-http-equiv-content-type
			// http://www.whatwg.org/html/semantics.html#charset
			$pieces[] = Html::element( 'meta', [ 'charset' => 'UTF-8' ] );
		}

		$pieces[] = Html::element( 'title', null, $this->getHTMLTitle() );
		$pieces[] = $this->getRlClient()->getHeadHtml();
		$pieces[] = $this->buildExemptModules();
		$pieces = array_merge( $pieces, array_values( $this->getHeadLinksArray() ) );
		$pieces = array_merge( $pieces, array_values( $this->mHeadItems ) );
		$pieces[] = Html::closeElement( 'head' );

		$bodyClasses = [];
		$bodyClasses[] = 'mediawiki';

		# Classes for LTR/RTL directionality support
		$bodyClasses[] = $userdir;
		$bodyClasses[] = "sitedir-$sitedir";

		if ( $this->getLanguage()->capitalizeAllNouns() ) {
			# A <body> class is probably not the best way to do this . . .
			$bodyClasses[] = 'capitalize-all-nouns';
		}

		// Parser feature migration class
		// The idea is that this will eventually be removed, after the wikitext
		// which requires it is cleaned up.
		$bodyClasses[] = 'mw-hide-empty-elt';

		$bodyClasses[] = $sk->getPageClasses( $this->getTitle() );
		$bodyClasses[] = 'skin-' . Sanitizer::escapeClass( $sk->getSkinName() );
		$bodyClasses[] =
			'action-' . Sanitizer::escapeClass( Action::getActionName( $this->getContext() ) );

		$bodyAttrs = [];
		// While the implode() is not strictly needed, it's used for backwards compatibility
		// (this used to be built as a string and hooks likely still expect that).
		$bodyAttrs['class'] = implode( ' ', $bodyClasses );

		// Allow skins and extensions to add body attributes they need
		$sk->addToBodyAttributes( $this, $bodyAttrs );
		Hooks::run( 'OutputPageBodyAttributes', [ $this, $sk, &$bodyAttrs ] );

		$pieces[] = Html::openElement( 'body', $bodyAttrs );

		return self::combineWrappedStrings( $pieces );
	}

	/**
	 * Get a ResourceLoader object associated with this OutputPage
	 *
	 * @return ResourceLoader
	 */
	public function getResourceLoader() {
		if ( is_null( $this->mResourceLoader ) ) {
			$this->mResourceLoader = new ResourceLoader(
				$this->getConfig(),
				LoggerFactory::getInstance( 'resourceloader' )
			);
		}
		return $this->mResourceLoader;
	}

	/**
	 * Explicily load or embed modules on a page.
	 *
	 * @param array|string $modules One or more module names
	 * @param string $only ResourceLoaderModule TYPE_ class constant
	 * @param array $extraQuery [optional] Array with extra query parameters for the request
	 * @return string|WrappedStringList HTML
	 */
	public function makeResourceLoaderLink( $modules, $only, array $extraQuery = [] ) {
		// Apply 'target' and 'origin' filters
		$modules = $this->filterModules( (array)$modules, null, $only );

		return ResourceLoaderClientHtml::makeLoad(
			$this->getRlClientContext(),
			$modules,
			$only,
			$extraQuery
		);
	}

	/**
	 * Combine WrappedString chunks and filter out empty ones
	 *
	 * @param array $chunks
	 * @return string|WrappedStringList HTML
	 */
	protected static function combineWrappedStrings( array $chunks ) {
		// Filter out empty values
		$chunks = array_filter( $chunks, 'strlen' );
		return WrappedString::join( "\n", $chunks );
	}

	private function isUserJsPreview() {
		return $this->getConfig()->get( 'AllowUserJs' )
			&& $this->getTitle()
			&& $this->getTitle()->isJsSubpage()
			&& $this->userCanPreview();
	}

	private function isUserCssPreview() {
		return $this->getConfig()->get( 'AllowUserCss' )
			&& $this->getTitle()
			&& $this->getTitle()->isCssSubpage()
			&& $this->userCanPreview();
	}

	/**
	 * JS stuff to put at the bottom of the `<body>`. These are modules with position 'bottom',
	 * legacy scripts ($this->mScripts), and user JS.
	 *
	 * @return string|WrappedStringList HTML
	 */
	public function getBottomScripts() {
		$chunks = [];
		$chunks[] = $this->getRlClient()->getBodyHtml();

		// Legacy non-ResourceLoader scripts
		$chunks[] = $this->mScripts;

		// Exempt 'user' module
		// - May need excludepages for live preview. (T28283)
		// - Must use TYPE_COMBINED so its response is handled by mw.loader.implement() which
		//   ensures execution is scheduled after the "site" module.
		// - Don't load if module state is already resolved as "ready".
		if ( $this->rlUserModuleState === 'loading' ) {
			if ( $this->isUserJsPreview() ) {
				$chunks[] = $this->makeResourceLoaderLink( 'user', ResourceLoaderModule::TYPE_COMBINED,
					[ 'excludepage' => $this->getTitle()->getPrefixedDBkey() ]
				);
				$chunks[] = ResourceLoader::makeInlineScript(
					Xml::encodeJsCall( 'mw.loader.using', [
						[ 'user', 'site' ],
						new XmlJsCode(
							'function () {'
								. Xml::encodeJsCall( '$.globalEval', [
									$this->getRequest()->getText( 'wpTextbox1' )
								] )
								. '}'
						)
					] )
				);
				// FIXME: If the user is previewing, say, ./vector.js, his ./common.js will be loaded
				// asynchronously and may arrive *after* the inline script here. So the previewed code
				// may execute before ./common.js runs. Normally, ./common.js runs before ./vector.js.
				// Similarly, when previewing ./common.js and the user module does arrive first,
				// it will arrive without common.js and the inline script runs after.
				// Thus running common after the excluded subpage.
			} else {
				// Load normally
				$chunks[] = $this->makeResourceLoaderLink( 'user', ResourceLoaderModule::TYPE_COMBINED );
			}
		}

		return self::combineWrappedStrings( $chunks );
	}

	/**
	 * Get the javascript config vars to include on this page
	 *
	 * @return array Array of javascript config vars
	 * @since 1.23
	 */
	public function getJsConfigVars() {
		return $this->mJsConfigVars;
	}

	/**
	 * Add one or more variables to be set in mw.config in JavaScript
	 *
	 * @param string|array $keys Key or array of key/value pairs
	 * @param mixed $value [optional] Value of the configuration variable
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
	 * Do not add things here which can be evaluated in ResourceLoaderStartUpModule
	 * - in other words, page-independent/site-wide variables (without state).
	 * You will only be adding bloat to the html page and causing page caches to
	 * have to be purged on configuration changes.
	 * @return array
	 */
	public function getJSVars() {
		global $wgContLang;

		$curRevisionId = 0;
		$articleId = 0;
		$canonicalSpecialPageName = false; # bug 21115

		$title = $this->getTitle();
		$ns = $title->getNamespace();
		$canonicalNamespace = MWNamespace::exists( $ns )
			? MWNamespace::getCanonicalName( $ns )
			: $title->getNsText();

		$sk = $this->getSkin();
		// Get the relevant title so that AJAX features can use the correct page name
		// when making API requests from certain special pages (bug 34972).
		$relevantTitle = $sk->getRelevantTitle();
		$relevantUser = $sk->getRelevantUser();

		if ( $ns == NS_SPECIAL ) {
			list( $canonicalSpecialPageName, /*...*/ ) =
				SpecialPageFactory::resolveAlias( $title->getDBkey() );
		} elseif ( $this->canUseWikiPage() ) {
			$wikiPage = $this->getWikiPage();
			$curRevisionId = $wikiPage->getLatest();
			$articleId = $wikiPage->getId();
		}

		$lang = $title->getPageViewLanguage();

		// Pre-process information
		$separatorTransTable = $lang->separatorTransformTable();
		$separatorTransTable = $separatorTransTable ? $separatorTransTable : [];
		$compactSeparatorTransTable = [
			implode( "\t", array_keys( $separatorTransTable ) ),
			implode( "\t", $separatorTransTable ),
		];
		$digitTransTable = $lang->digitTransformTable();
		$digitTransTable = $digitTransTable ? $digitTransTable : [];
		$compactDigitTransTable = [
			implode( "\t", array_keys( $digitTransTable ) ),
			implode( "\t", $digitTransTable ),
		];

		$user = $this->getUser();

		$vars = [
			'wgCanonicalNamespace' => $canonicalNamespace,
			'wgCanonicalSpecialPageName' => $canonicalSpecialPageName,
			'wgNamespaceNumber' => $title->getNamespace(),
			'wgPageName' => $title->getPrefixedDBkey(),
			'wgTitle' => $title->getText(),
			'wgCurRevisionId' => $curRevisionId,
			'wgRevisionId' => (int)$this->getRevisionId(),
			'wgArticleId' => $articleId,
			'wgIsArticle' => $this->isArticle(),
			'wgIsRedirect' => $title->isRedirect(),
			'wgAction' => Action::getActionName( $this->getContext() ),
			'wgUserName' => $user->isAnon() ? null : $user->getName(),
			'wgUserGroups' => $user->getEffectiveGroups(),
			'wgCategories' => $this->getCategories(),
			'wgBreakFrames' => $this->getFrameOptions() == 'DENY',
			'wgPageContentLanguage' => $lang->getCode(),
			'wgPageContentModel' => $title->getContentModel(),
			'wgSeparatorTransformTable' => $compactSeparatorTransTable,
			'wgDigitTransformTable' => $compactDigitTransTable,
			'wgDefaultDateFormat' => $lang->getDefaultDateFormat(),
			'wgMonthNames' => $lang->getMonthNamesArray(),
			'wgMonthNamesShort' => $lang->getMonthAbbreviationsArray(),
			'wgRelevantPageName' => $relevantTitle->getPrefixedDBkey(),
			'wgRelevantArticleId' => $relevantTitle->getArticleID(),
			'wgRequestId' => WebRequest::getRequestId(),
		];

		if ( $user->isLoggedIn() ) {
			$vars['wgUserId'] = $user->getId();
			$vars['wgUserEditCount'] = $user->getEditCount();
			$userReg = $user->getRegistration();
			$vars['wgUserRegistration'] = $userReg ? wfTimestamp( TS_UNIX, $userReg ) * 1000 : null;
			// Get the revision ID of the oldest new message on the user's talk
			// page. This can be used for constructing new message alerts on
			// the client side.
			$vars['wgUserNewMsgRevisionId'] = $user->getNewMessageRevisionId();
		}

		if ( $wgContLang->hasVariants() ) {
			$vars['wgUserVariant'] = $wgContLang->getPreferredVariant();
		}
		// Same test as SkinTemplate
		$vars['wgIsProbablyEditable'] = $title->quickUserCan( 'edit', $user )
			&& ( $title->exists() || $title->quickUserCan( 'create', $user ) );

		foreach ( $title->getRestrictionTypes() as $type ) {
			$vars['wgRestriction' . ucfirst( $type )] = $title->getRestrictions( $type );
		}

		if ( $title->isMainPage() ) {
			$vars['wgIsMainPage'] = true;
		}

		if ( $this->mRedirectedFrom ) {
			$vars['wgRedirectedFrom'] = $this->mRedirectedFrom->getPrefixedDBkey();
		}

		if ( $relevantUser ) {
			$vars['wgRelevantUserName'] = $relevantUser->getName();
		}

		// Allow extensions to add their custom variables to the mw.config map.
		// Use the 'ResourceLoaderGetConfigVars' hook if the variable is not
		// page-dependant but site-wide (without state).
		// Alternatively, you may want to use OutputPage->addJsConfigVars() instead.
		Hooks::run( 'MakeGlobalVariablesScript', [ &$vars, $this ] );

		// Merge in variables from addJsConfigVars last
		return array_merge( $vars, $this->getJsConfigVars() );
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
		$request = $this->getRequest();
		if (
			$request->getVal( 'action' ) !== 'submit' ||
			!$request->getCheck( 'wpPreview' ) ||
			!$request->wasPosted()
		) {
			return false;
		}

		$user = $this->getUser();

		if ( !$user->isLoggedIn() ) {
			// Anons have predictable edit tokens
			return false;
		}
		if ( !$user->matchEditToken( $request->getVal( 'wpEditToken' ) ) ) {
			return false;
		}

		$title = $this->getTitle();
		if ( !$title->isJsSubpage() && !$title->isCssSubpage() ) {
			return false;
		}
		if ( !$title->isSubpageOf( $user->getUserPage() ) ) {
			// Don't execute another user's CSS or JS on preview (T85855)
			return false;
		}

		$errors = $title->getUserPermissionsErrors( 'edit', $user );
		if ( count( $errors ) !== 0 ) {
			return false;
		}

		return true;
	}

	/**
	 * @return array Array in format "link name or number => 'link html'".
	 */
	public function getHeadLinksArray() {
		global $wgVersion;

		$tags = [];
		$config = $this->getConfig();

		$canonicalUrl = $this->mCanonicalUrl;

		$tags['meta-generator'] = Html::element( 'meta', [
			'name' => 'generator',
			'content' => "MediaWiki $wgVersion",
		] );

		if ( $config->get( 'ReferrerPolicy' ) !== false ) {
			$tags['meta-referrer'] = Html::element( 'meta', [
				'name' => 'referrer',
				'content' => $config->get( 'ReferrerPolicy' )
			] );
		}

		$p = "{$this->mIndexPolicy},{$this->mFollowPolicy}";
		if ( $p !== 'index,follow' ) {
			// http://www.robotstxt.org/wc/meta-user.html
			// Only show if it's different from the default robots policy
			$tags['meta-robots'] = Html::element( 'meta', [
				'name' => 'robots',
				'content' => $p,
			] );
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
				[
					$a => $tag[0],
					'content' => $tag[1]
				]
			);
		}

		foreach ( $this->mLinktags as $tag ) {
			$tags[] = Html::element( 'link', $tag );
		}

		# Universal edit button
		if ( $config->get( 'UniversalEditButton' ) && $this->isArticleRelated() ) {
			$user = $this->getUser();
			if ( $this->getTitle()->quickUserCan( 'edit', $user )
				&& ( $this->getTitle()->exists() ||
					$this->getTitle()->quickUserCan( 'create', $user ) )
			) {
				// Original UniversalEditButton
				$msg = $this->msg( 'edit' )->text();
				$tags['universal-edit-button'] = Html::element( 'link', [
					'rel' => 'alternate',
					'type' => 'application/x-wiki',
					'title' => $msg,
					'href' => $this->getTitle()->getEditURL(),
				] );
				// Alternate edit link
				$tags['alternative-edit'] = Html::element( 'link', [
					'rel' => 'edit',
					'title' => $msg,
					'href' => $this->getTitle()->getEditURL(),
				] );
			}
		}

		# Generally the order of the favicon and apple-touch-icon links
		# should not matter, but Konqueror (3.5.9 at least) incorrectly
		# uses whichever one appears later in the HTML source. Make sure
		# apple-touch-icon is specified first to avoid this.
		if ( $config->get( 'AppleTouchIcon' ) !== false ) {
			$tags['apple-touch-icon'] = Html::element( 'link', [
				'rel' => 'apple-touch-icon',
				'href' => $config->get( 'AppleTouchIcon' )
			] );
		}

		if ( $config->get( 'Favicon' ) !== false ) {
			$tags['favicon'] = Html::element( 'link', [
				'rel' => 'shortcut icon',
				'href' => $config->get( 'Favicon' )
			] );
		}

		# OpenSearch description link
		$tags['opensearch'] = Html::element( 'link', [
			'rel' => 'search',
			'type' => 'application/opensearchdescription+xml',
			'href' => wfScript( 'opensearch_desc' ),
			'title' => $this->msg( 'opensearch-desc' )->inContentLanguage()->text(),
		] );

		if ( $config->get( 'EnableAPI' ) ) {
			# Real Simple Discovery link, provides auto-discovery information
			# for the MediaWiki API (and potentially additional custom API
			# support such as WordPress or Twitter-compatible APIs for a
			# blogging extension, etc)
			$tags['rsd'] = Html::element( 'link', [
				'rel' => 'EditURI',
				'type' => 'application/rsd+xml',
				// Output a protocol-relative URL here if $wgServer is protocol-relative.
				// Whether RSD accepts relative or protocol-relative URLs is completely
				// undocumented, though.
				'href' => wfExpandUrl( wfAppendQuery(
					wfScript( 'api' ),
					[ 'action' => 'rsd' ] ),
					PROTO_RELATIVE
				),
			] );
		}

		# Language variants
		if ( !$config->get( 'DisableLangConversion' ) ) {
			$lang = $this->getTitle()->getPageLanguage();
			if ( $lang->hasVariants() ) {
				$variants = $lang->getVariants();
				foreach ( $variants as $variant ) {
					$tags["variant-$variant"] = Html::element( 'link', [
						'rel' => 'alternate',
						'hreflang' => wfBCP47( $variant ),
						'href' => $this->getTitle()->getLocalURL(
							[ 'variant' => $variant ] )
						]
					);
				}
				# x-default link per https://support.google.com/webmasters/answer/189077?hl=en
				$tags["variant-x-default"] = Html::element( 'link', [
					'rel' => 'alternate',
					'hreflang' => 'x-default',
					'href' => $this->getTitle()->getLocalURL() ] );
			}
		}

		# Copyright
		if ( $this->copyrightUrl !== null ) {
			$copyright = $this->copyrightUrl;
		} else {
			$copyright = '';
			if ( $config->get( 'RightsPage' ) ) {
				$copy = Title::newFromText( $config->get( 'RightsPage' ) );

				if ( $copy ) {
					$copyright = $copy->getLocalURL();
				}
			}

			if ( !$copyright && $config->get( 'RightsUrl' ) ) {
				$copyright = $config->get( 'RightsUrl' );
			}
		}

		if ( $copyright ) {
			$tags['copyright'] = Html::element( 'link', [
				'rel' => 'copyright',
				'href' => $copyright ]
			);
		}

		# Feeds
		if ( $config->get( 'Feed' ) ) {
			$feedLinks = [];

			foreach ( $this->getSyndicationLinks() as $format => $link ) {
				# Use the page name for the title.  In principle, this could
				# lead to issues with having the same name for different feeds
				# corresponding to the same page, but we can't avoid that at
				# this low a level.

				$feedLinks[] = $this->feedLink(
					$format,
					$link,
					# Used messages: 'page-rss-feed' and 'page-atom-feed' (for an easier grep)
					$this->msg(
						"page-{$format}-feed", $this->getTitle()->getPrefixedText()
					)->text()
				);
			}

			# Recent changes feed should appear on every page (except recentchanges,
			# that would be redundant). Put it after the per-page feed to avoid
			# changing existing behavior. It's still available, probably via a
			# menu in your browser. Some sites might have a different feed they'd
			# like to promote instead of the RC feed (maybe like a "Recent New Articles"
			# or "Breaking news" one). For this, we see if $wgOverrideSiteFeed is defined.
			# If so, use it instead.
			$sitename = $config->get( 'Sitename' );
			if ( $config->get( 'OverrideSiteFeed' ) ) {
				foreach ( $config->get( 'OverrideSiteFeed' ) as $type => $feedUrl ) {
					// Note, this->feedLink escapes the url.
					$feedLinks[] = $this->feedLink(
						$type,
						$feedUrl,
						$this->msg( "site-{$type}-feed", $sitename )->text()
					);
				}
			} elseif ( !$this->getTitle()->isSpecial( 'Recentchanges' ) ) {
				$rctitle = SpecialPage::getTitleFor( 'Recentchanges' );
				foreach ( $config->get( 'AdvertisedFeedTypes' ) as $format ) {
					$feedLinks[] = $this->feedLink(
						$format,
						$rctitle->getLocalURL( [ 'feed' => $format ] ),
						# For grep: 'site-rss-feed', 'site-atom-feed'
						$this->msg( "site-{$format}-feed", $sitename )->text()
					);
				}
			}

			# Allow extensions to change the list pf feeds. This hook is primarily for changing,
			# manipulating or removing existing feed tags. If you want to add new feeds, you should
			# use OutputPage::addFeedLink() instead.
			Hooks::run( 'AfterBuildFeedLinks', [ &$feedLinks ] );

			$tags += $feedLinks;
		}

		# Canonical URL
		if ( $config->get( 'EnableCanonicalServerLink' ) ) {
			if ( $canonicalUrl !== false ) {
				$canonicalUrl = wfExpandUrl( $canonicalUrl, PROTO_CANONICAL );
			} else {
				if ( $this->isArticleRelated() ) {
					// This affects all requests where "setArticleRelated" is true. This is
					// typically all requests that show content (query title, curid, oldid, diff),
					// and all wikipage actions (edit, delete, purge, info, history etc.).
					// It does not apply to File pages and Special pages.
					// 'history' and 'info' actions address page metadata rather than the page
					// content itself, so they may not be canonicalized to the view page url.
					// TODO: this ought to be better encapsulated in the Action class.
					$action = Action::getActionName( $this->getContext() );
					if ( in_array( $action, [ 'history', 'info' ] ) ) {
						$query = "action={$action}";
					} else {
						$query = '';
					}
					$canonicalUrl = $this->getTitle()->getCanonicalURL( $query );
				} else {
					$reqUrl = $this->getRequest()->getRequestURL();
					$canonicalUrl = wfExpandUrl( $reqUrl, PROTO_CANONICAL );
				}
			}
		}
		if ( $canonicalUrl !== false ) {
			$tags[] = Html::element( 'link', [
				'rel' => 'canonical',
				'href' => $canonicalUrl
			] );
		}

		return $tags;
	}

	/**
	 * @return string HTML tag links to be put in the header.
	 * @deprecated since 1.24 Use OutputPage::headElement or if you have to,
	 *   OutputPage::getHeadLinksArray directly.
	 */
	public function getHeadLinks() {
		wfDeprecated( __METHOD__, '1.24' );
		return implode( "\n", $this->getHeadLinksArray() );
	}

	/**
	 * Generate a "<link rel/>" for a feed.
	 *
	 * @param string $type Feed type
	 * @param string $url URL to the feed
	 * @param string $text Value of the "title" attribute
	 * @return string HTML fragment
	 */
	private function feedLink( $type, $url, $text ) {
		return Html::element( 'link', [
			'rel' => 'alternate',
			'type' => "application/$type+xml",
			'title' => $text,
			'href' => $url ]
		);
	}

	/**
	 * Add a local or specified stylesheet, with the given media options.
	 * Internal use only. Use OutputPage::addModuleStyles() if possible.
	 *
	 * @param string $style URL to the file
	 * @param string $media To specify a media type, 'screen', 'printable', 'handheld' or any.
	 * @param string $condition For IE conditional comments, specifying an IE version
	 * @param string $dir Set to 'rtl' or 'ltr' for direction-specific sheets
	 */
	public function addStyle( $style, $media = '', $condition = '', $dir = '' ) {
		$options = [];
		if ( $media ) {
			$options['media'] = $media;
		}
		if ( $condition ) {
			$options['condition'] = $condition;
		}
		if ( $dir ) {
			$options['dir'] = $dir;
		}
		$this->styles[$style] = $options;
	}

	/**
	 * Adds inline CSS styles
	 * Internal use only. Use OutputPage::addModuleStyles() if possible.
	 *
	 * @param mixed $style_css Inline CSS
	 * @param string $flip Set to 'flip' to flip the CSS if needed
	 */
	public function addInlineStyle( $style_css, $flip = 'noflip' ) {
		if ( $flip === 'flip' && $this->getLanguage()->isRTL() ) {
			# If wanted, and the interface is right-to-left, flip the CSS
			$style_css = CSSJanus::transform( $style_css, true, false );
		}
		$this->mInlineStyles .= Html::inlineStyle( $style_css );
	}

	/**
	 * Build exempt modules and legacy non-ResourceLoader styles.
	 *
	 * @return string|WrappedStringList HTML
	 */
	protected function buildExemptModules() {
		global $wgContLang;

		$resourceLoader = $this->getResourceLoader();
		$chunks = [];
		// Things that go after the ResourceLoaderDynamicStyles marker
		$append = [];

		// Exempt 'user' styles module (may need 'excludepages' for live preview)
		if ( $this->isUserCssPreview() ) {
			$append[] = $this->makeResourceLoaderLink(
				'user.styles',
				ResourceLoaderModule::TYPE_STYLES,
				[ 'excludepage' => $this->getTitle()->getPrefixedDBkey() ]
			);

			// Load the previewed CSS. Janus it if needed.
			// User-supplied CSS is assumed to in the wiki's content language.
			$previewedCSS = $this->getRequest()->getText( 'wpTextbox1' );
			if ( $this->getLanguage()->getDir() !== $wgContLang->getDir() ) {
				$previewedCSS = CSSJanus::transform( $previewedCSS, true, false );
			}
			$append[] = Html::inlineStyle( $previewedCSS );
		}

		// We want site, private and user styles to override dynamically added styles from
		// general modules, but we want dynamically added styles to override statically added
		// style modules. So the order has to be:
		// - page style modules (formatted by ResourceLoaderClientHtml::getHeadHtml())
		// - dynamically loaded styles (added by mw.loader before ResourceLoaderDynamicStyles)
		// - ResourceLoaderDynamicStyles marker
		// - site/private/user styles

		// Add legacy styles added through addStyle()/addInlineStyle() here
		$chunks[] = implode( '', $this->buildCssLinksArray() ) . $this->mInlineStyles;

		$chunks[] = Html::element(
			'meta',
			[ 'name' => 'ResourceLoaderDynamicStyles', 'content' => '' ]
		);

		foreach ( $this->rlExemptStyleModules as $group => $moduleNames ) {
			$chunks[] = $this->makeResourceLoaderLink( $moduleNames,
				ResourceLoaderModule::TYPE_STYLES
			);
		}

		return self::combineWrappedStrings( array_merge( $chunks, $append ) );
	}

	/**
	 * @return array
	 */
	public function buildCssLinksArray() {
		$links = [];

		// Add any extension CSS
		foreach ( $this->mExtStyles as $url ) {
			$this->addStyle( $url );
		}
		$this->mExtStyles = [];

		foreach ( $this->styles as $file => $options ) {
			$link = $this->styleLink( $file, $options );
			if ( $link ) {
				$links[$file] = $link;
			}
		}
		return $links;
	}

	/**
	 * Generate \<link\> tags for stylesheets
	 *
	 * @param string $style URL to the file
	 * @param array $options Option, can contain 'condition', 'dir', 'media' keys
	 * @return string HTML fragment
	 */
	protected function styleLink( $style, array $options ) {
		if ( isset( $options['dir'] ) ) {
			if ( $this->getLanguage()->getDir() != $options['dir'] ) {
				return '';
			}
		}

		if ( isset( $options['media'] ) ) {
			$media = self::transformCssMedia( $options['media'] );
			if ( is_null( $media ) ) {
				return '';
			}
		} else {
			$media = 'all';
		}

		if ( substr( $style, 0, 1 ) == '/' ||
			substr( $style, 0, 5 ) == 'http:' ||
			substr( $style, 0, 6 ) == 'https:' ) {
			$url = $style;
		} else {
			$config = $this->getConfig();
			$url = $config->get( 'StylePath' ) . '/' . $style . '?' .
				$config->get( 'StyleVersion' );
		}

		$link = Html::linkedStyle( $url, $media );

		if ( isset( $options['condition'] ) ) {
			$condition = htmlspecialchars( $options['condition'] );
			$link = "<!--[if $condition]>$link<![endif]-->";
		}
		return $link;
	}

	/**
	 * Transform path to web-accessible static resource.
	 *
	 * This is used to add a validation hash as query string.
	 * This aids various behaviors:
	 *
	 * - Put long Cache-Control max-age headers on responses for improved
	 *   cache performance.
	 * - Get the correct version of a file as expected by the current page.
	 * - Instantly get the updated version of a file after deployment.
	 *
	 * Avoid using this for urls included in HTML as otherwise clients may get different
	 * versions of a resource when navigating the site depending on when the page was cached.
	 * If changes to the url propagate, this is not a problem (e.g. if the url is in
	 * an external stylesheet).
	 *
	 * @since 1.27
	 * @param Config $config
	 * @param string $path Path-absolute URL to file (from document root, must start with "/")
	 * @return string URL
	 */
	public static function transformResourcePath( Config $config, $path ) {
		global $IP;
		$remotePathPrefix = $config->get( 'ResourceBasePath' );
		if ( $remotePathPrefix === '' ) {
			// The configured base path is required to be empty string for
			// wikis in the domain root
			$remotePath = '/';
		} else {
			$remotePath = $remotePathPrefix;
		}
		if ( strpos( $path, $remotePath ) !== 0 ) {
			// Path is outside wgResourceBasePath, ignore.
			return $path;
		}
		$path = RelPath\getRelativePath( $path, $remotePath );
		return self::transformFilePath( $remotePathPrefix, $IP, $path );
	}

	/**
	 * Utility method for transformResourceFilePath().
	 *
	 * Caller is responsible for ensuring the file exists. Emits a PHP warning otherwise.
	 *
	 * @since 1.27
	 * @param string $remotePath URL path prefix that points to $localPath
	 * @param string $localPath File directory exposed at $remotePath
	 * @param string $file Path to target file relative to $localPath
	 * @return string URL
	 */
	public static function transformFilePath( $remotePathPrefix, $localPath, $file ) {
		$hash = md5_file( "$localPath/$file" );
		if ( $hash === false ) {
			wfLogWarning( __METHOD__ . ": Failed to hash $localPath/$file" );
			$hash = '';
		}
		return "$remotePathPrefix/$file?" . substr( $hash, 0, 5 );
	}

	/**
	 * Transform "media" attribute based on request parameters
	 *
	 * @param string $media Current value of the "media" attribute
	 * @return string Modified value of the "media" attribute, or null to skip
	 * this stylesheet
	 */
	public static function transformCssMedia( $media ) {
		global $wgRequest;

		// http://www.w3.org/TR/css3-mediaqueries/#syntax
		$screenMediaQueryRegex = '/^(?:only\s+)?screen\b/i';

		// Switch in on-screen display for media testing
		$switches = [
			'printable' => 'print',
			'handheld' => 'handheld',
		];
		foreach ( $switches as $switch => $targetMedia ) {
			if ( $wgRequest->getBool( $switch ) ) {
				if ( $media == $targetMedia ) {
					$media = '';
				} elseif ( preg_match( $screenMediaQueryRegex, $media ) === 1 ) {
					/* This regex will not attempt to understand a comma-separated media_query_list
					 *
					 * Example supported values for $media:
					 * 'screen', 'only screen', 'screen and (min-width: 982px)' ),
					 * Example NOT supported value for $media:
					 * '3d-glasses, screen, print and resolution > 90dpi'
					 *
					 * If it's a print request, we never want any kind of screen stylesheets
					 * If it's a handheld request (currently the only other choice with a switch),
					 * we don't want simple 'screen' but we might want screen queries that
					 * have a max-width or something, so we'll pass all others on and let the
					 * client do the query.
					 */
					if ( $targetMedia == 'print' || $media == 'screen' ) {
						return null;
					}
				}
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
	 * @param string $name
	 * @param array $args
	 */
	public function addWikiMsgArray( $name, $args ) {
		$this->addHTML( $this->msg( $name, $args )->parseAsBlock() );
	}

	/**
	 * This function takes a number of message/argument specifications, wraps them in
	 * some overall structure, and then parses the result and adds it to the output.
	 *
	 * In the $wrap, $1 is replaced with the first message, $2 with the second,
	 * and so on. The subsequent arguments may be either
	 * 1) strings, in which case they are message names, or
	 * 2) arrays, in which case, within each array, the first element is the message
	 *    name, and subsequent elements are the parameters to that message.
	 *
	 * Don't use this for messages that are not in the user's interface language.
	 *
	 * For example:
	 *
	 *    $wgOut->wrapWikiMsg( "<div class='error'>\n$1\n</div>", 'some-error' );
	 *
	 * Is equivalent to:
	 *
	 *    $wgOut->addWikiText( "<div class='error'>\n"
	 *        . wfMessage( 'some-error' )->plain() . "\n</div>" );
	 *
	 * The newline after the opening div is needed in some wikitext. See bug 19226.
	 *
	 * @param string $wrap
	 */
	public function wrapWikiMsg( $wrap /*, ...*/ ) {
		$msgSpecs = func_get_args();
		array_shift( $msgSpecs );
		$msgSpecs = array_values( $msgSpecs );
		$s = $wrap;
		foreach ( $msgSpecs as $n => $spec ) {
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
			} else {
				$args = [];
				$name = $spec;
			}
			$s = str_replace( '$' . ( $n + 1 ), $this->msg( $name, $args )->plain(), $s );
		}
		$this->addWikiText( $s );
	}

	/**
	 * Enables/disables TOC, doesn't override __NOTOC__
	 * @param bool $flag
	 * @since 1.22
	 */
	public function enableTOC( $flag = true ) {
		$this->mEnableTOC = $flag;
	}

	/**
	 * @return bool
	 * @since 1.22
	 */
	public function isTOCEnabled() {
		return $this->mEnableTOC;
	}

	/**
	 * Enables/disables section edit links, doesn't override __NOEDITSECTION__
	 * @param bool $flag
	 * @since 1.23
	 */
	public function enableSectionEditLinks( $flag = true ) {
		$this->mEnableSectionEditLinks = $flag;
	}

	/**
	 * @return bool
	 * @since 1.23
	 */
	public function sectionEditLinksEnabled() {
		return $this->mEnableSectionEditLinks;
	}

	/**
	 * Helper function to setup the PHP implementation of OOUI to use in this request.
	 *
	 * @since 1.26
	 * @param String $skinName The Skin name to determine the correct OOUI theme
	 * @param String $dir Language direction
	 */
	public static function setupOOUI( $skinName = '', $dir = 'ltr' ) {
		$themes = ExtensionRegistry::getInstance()->getAttribute( 'SkinOOUIThemes' );
		// Make keys (skin names) lowercase for case-insensitive matching.
		$themes = array_change_key_case( $themes, CASE_LOWER );
		$theme = isset( $themes[$skinName] ) ? $themes[$skinName] : 'MediaWiki';
		// For example, 'OOUI\MediaWikiTheme'.
		$themeClass = "OOUI\\{$theme}Theme";
		OOUI\Theme::setSingleton( new $themeClass() );
		OOUI\Element::setDefaultDir( $dir );
	}

	/**
	 * Add ResourceLoader module styles for OOUI and set up the PHP implementation of it for use with
	 * MediaWiki and this OutputPage instance.
	 *
	 * @since 1.25
	 */
	public function enableOOUI() {
		self::setupOOUI(
			strtolower( $this->getSkin()->getSkinName() ),
			$this->getLanguage()->getDir()
		);
		$this->addModuleStyles( [
			'oojs-ui-core.styles',
			'oojs-ui.styles.icons',
			'oojs-ui.styles.indicators',
			'oojs-ui.styles.textures',
			'mediawiki.widgets.styles',
		] );
	}
}
