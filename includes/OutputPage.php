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

use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageRecord;
use MediaWiki\Page\PageReference;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\ResourceLoader as RL;
use MediaWiki\ResourceLoader\ResourceLoader;
use MediaWiki\Session\SessionManager;
use Wikimedia\AtEase\AtEase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\RelPath;
use Wikimedia\WrappedString;
use Wikimedia\WrappedStringList;

/**
 * This is one of the Core classes and should
 * be read at least once by any new developers. Also documented at
 * https://www.mediawiki.org/wiki/Manual:Architectural_modules/OutputPage
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
	use ProtectedHookAccessorTrait;

	/** @var string[][] Should be private. Used with addMeta() which adds "<meta>" */
	protected $mMetatags = [];

	/** @var array */
	protected $mLinktags = [];

	/** @var string|bool */
	protected $mCanonicalUrl = false;

	/**
	 * @var string The contents of <h1>
	 */
	private $mPageTitle = '';

	/**
	 * @var string The displayed title of the page. Different from page title
	 * if overridden by display title magic word or hooks. Can contain safe
	 * HTML. Different from page title which may contain messages such as
	 * "Editing X" which is displayed in h1. This can be used for other places
	 * where the page name is referred on the page.
	 */
	private $displayTitle;

	/** @var bool See OutputPage::couldBePublicCached. */
	private $cacheIsFinal = false;

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
	private $mIsArticle = false;

	/** @var bool Stores "article flag" toggle. */
	private $mIsArticleRelated = true;

	/** @var bool Is the content subject to copyright */
	private $mHasCopyright = false;

	/**
	 * @var bool We have to set isPrintable(). Some pages should
	 * never be printed (ex: redirections).
	 */
	private $mPrintable = false;

	/**
	 * @var array sections from ParserOutput
	 */
	private $mSections = [];

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

	/**
	 * @var string[][]
	 * @deprecated since 1.38; will be made private (T301020)
	 */
	protected $mCategoryLinks = [];

	/**
	 * @var string[][]
	 * @deprecated since 1.38, will be made private (T301020)
	 */
	protected $mCategories = [
		'hidden' => [],
		'normal' => [],
	];

	/**
	 * @var string[]
	 * @deprecated since 1.38; will be made private (T301020)
	 */
	protected $mIndicators = [];

	/**
	 * @var array Array of Interwiki Prefixed (non DB key) Titles (e.g. 'fr:Test page')
	 */
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

	/**
	 * Additional <html> classes; This should be rarely modified; prefer mAdditionalBodyClasses.
	 * @var array
	 */
	protected $mAdditionalHtmlClasses = [];

	/**
	 * @var array Array of elements in "<head>". Parser might add its own headers!
	 * @deprecated since 1.38; will be made private (T301020)
	 */
	protected $mHeadItems = [];

	/** @var array Additional <body> classes; there are also <body> classes from other sources */
	protected $mAdditionalBodyClasses = [];

	/**
	 * @var array
	 * @deprecated since 1.38; will be made private (T301020)
	 */
	protected $mModules = [];

	/**
	 * @var array
	 * @deprecated since 1.38; will be made private (T301020)
	 */
	protected $mModuleStyles = [];

	/** @var ResourceLoader */
	protected $mResourceLoader;

	/** @var RL\ClientHtml */
	private $rlClient;

	/** @var RL\Context */
	private $rlClientContext;

	/** @var array */
	private $rlExemptStyleModules;

	/**
	 * @var array
	 * @deprecated since 1.38; will be made private (T301020)
	 */
	protected $mJsConfigVars = [];

	/**
	 * @var array
	 * @deprecated since 1.38; will be made private (T301020)
	 */
	protected $mTemplateIds = [];

	/** @var array */
	protected $mImageTimeKeys = [];

	/** @var string */
	public $mRedirectCode = '';

	protected $mFeedLinksAppendQuery = null;

	/** @var array
	 * What level of 'untrustworthiness' is allowed in CSS/JS modules loaded on this page?
	 * @see RL\Module::$origin
	 * RL\Module::ORIGIN_ALL is assumed unless overridden;
	 */
	protected $mAllowedModules = [
		RL\Module::TYPE_COMBINED => RL\Module::ORIGIN_ALL,
	];

	/** @var bool Whether output is disabled.  If this is true, the 'output' method will do nothing. */
	protected $mDoNothing = false;

	// Parser related.

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

	/**
	 * @var bool Set to false to send no-cache headers, disabling
	 * client-side caching. (This variable should really be named
	 * in the opposite sense; see ::disableClientCache().)
	 * @deprecated since 1.38; will be made private (T301020)
	 */
	protected $mEnableClientCache = true;

	/** @var bool Flag if output should only contain the body of the article. */
	private $mArticleBodyOnly = false;

	/**
	 * @var bool
	 * @deprecated since 1.38; will be made private (T301020)
	 */
	protected $mNewSectionLink = false;

	/**
	 * @var bool
	 * @deprecated since 1.38; will be made private (T301020)
	 */
	protected $mHideNewSectionLink = false;

	/**
	 * @var bool Comes from the parser. This was probably made to load CSS/JS
	 * only if we had "<gallery>". Used directly in CategoryPage.php.
	 * Looks like ResourceLoader can replace this.
	 * @deprecated since 1.38; will be made private (T301020)
	 */
	public $mNoGallery = false;

	/** @var int Cache stuff. Looks like mEnableClientCache */
	protected $mCdnMaxage = 0;
	/** @var int Upper limit on mCdnMaxage */
	protected $mCdnMaxageLimit = INF;

	/**
	 * @var bool Controls if anti-clickjacking / frame-breaking headers will
	 * be sent. This should be done for pages where edit actions are possible.
	 * Setter: $this->setPreventClickjacking()
	 */
	protected $mPreventClickjacking = true;

	/** @var int|null To include the variable {{REVISIONID}} */
	private $mRevisionId = null;

	/** @var bool|null */
	private $mRevisionIsCurrent = null;

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

	/** @var array */
	private $mRobotsOptions = [];

	/**
	 * @var array Headers that cause the cache to vary.  Key is header name,
	 * value should always be null.  (Value was an array of options for
	 * the `Key` header, which was deprecated in 1.32 and removed in 1.34.)
	 */
	private $mVaryHeader = [
		'Accept-Encoding' => null,
	];

	/**
	 * If the current page was reached through a redirect, $mRedirectedFrom contains the title
	 * of the redirect.
	 *
	 * @var PageReference
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
	 * @var bool Whether parser output contains a table of contents
	 */
	private $mEnableTOC = false;

	/**
	 * @var string|null The URL to send in a <link> element with rel=license
	 */
	private $copyrightUrl;

	/** @var array Profiling data */
	private $limitReportJSData = [];

	/** @var array Map Title to Content */
	private $contentOverrides = [];

	/** @var callable[] */
	private $contentOverrideCallbacks = [];

	/**
	 * Link: header contents
	 */
	private $mLinkHeader = [];

	/**
	 * @var ContentSecurityPolicy
	 */
	private $CSP;

	/**
	 * @var array A cache of the names of the cookies that will influence the cache
	 */
	private static $cacheVaryCookies = null;

	/**
	 * Constructor for OutputPage. This should not be called directly.
	 * Instead a new RequestContext should be created and it will implicitly create
	 * a OutputPage tied to that context.
	 * @param IContextSource $context
	 */
	public function __construct( IContextSource $context ) {
		$this->setContext( $context );
		$this->CSP = new ContentSecurityPolicy(
			$context->getRequest()->response(),
			$context->getConfig(),
			$this->getHookContainer()
		);
	}

	/**
	 * Redirect to $url rather than displaying the normal page
	 *
	 * @param string $url
	 * @param string|int $responsecode HTTP status code
	 */
	public function redirect( $url, $responsecode = '302' ) {
		# Strip newlines as a paranoia check for header injection in PHP<5.1.2
		$this->mRedirect = str_replace( "\n", '', $url );
		$this->mRedirectCode = (string)$responsecode;
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
	 * @param string $name Name of the meta tag
	 * @param string $val Value of the meta tag
	 */
	public function addMeta( $name, $val ) {
		$this->mMetatags[] = [ $name, $val ];
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
	public function addLink( array $linkarr ) {
		$this->mLinktags[] = $linkarr;
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
	 * Set the URL to be used for the <link rel=canonical>. This should be used
	 * in preference to addLink(), to avoid duplicate link tags.
	 * @param string $url
	 */
	public function setCanonicalUrl( $url ) {
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
	 * Add raw HTML to the list of scripts (including \<script\> tag, etc.)
	 * Internal use only. Use OutputPage::addModules() or OutputPage::addJsConfigVars()
	 * if possible.
	 *
	 * @param string $script Raw HTML
	 */
	public function addScript( $script ) {
		$this->mScripts .= $script;
	}

	/**
	 * Add a JavaScript file to be loaded as `<script>` on this page.
	 *
	 * Internal use only. Use OutputPage::addModules() if possible.
	 *
	 * @param string $file URL to file (absolute path, protocol-relative, or full url)
	 * @param string|null $unused Previously used to change the cache-busting query parameter
	 */
	public function addScriptFile( $file, $unused = null ) {
		$this->addScript( Html::linkedScript( $file, $this->CSP->getNonce() ) );
	}

	/**
	 * Add a self-contained script tag with the given contents
	 * Internal use only. Use OutputPage::addModules() if possible.
	 *
	 * @param string $script JavaScript text, no script tags
	 */
	public function addInlineScript( $script ) {
		$this->mScripts .= Html::inlineScript( "\n$script\n", $this->CSP->getNonce() ) . "\n";
	}

	/**
	 * Filter an array of modules to remove insufficiently trustworthy members, and modules
	 * which are no longer registered (eg a page is cached before an extension is disabled)
	 * @param string[] $modules
	 * @param string|null $position Unused
	 * @param string $type
	 * @return string[]
	 */
	protected function filterModules( array $modules, $position = null,
		$type = RL\Module::TYPE_COMBINED
	) {
		$resourceLoader = $this->getResourceLoader();
		$filteredModules = [];
		foreach ( $modules as $val ) {
			$module = $resourceLoader->getModule( $val );
			if ( $module instanceof RL\Module
				&& $module->getOrigin() <= $this->getAllowedModules( $type )
			) {
				if ( $this->mTarget && !in_array( $this->mTarget, $module->getTargets() ) ) {
					$this->warnModuleTargetFilter( $module->getName() );
					continue;
				}
				$filteredModules[] = $val;
			}
		}
		return $filteredModules;
	}

	private function warnModuleTargetFilter( $moduleName ) {
		static $warnings = [];
		if ( isset( $warnings[$this->mTarget][$moduleName] ) ) {
			return;
		}
		$warnings[$this->mTarget][$moduleName] = true;
		$this->getResourceLoader()->getLogger()->debug(
			'Module "{module}" not loadable on target "{target}".',
			[
				'module' => $moduleName,
				'target' => $this->mTarget,
			]
		);
	}

	/**
	 * Get the list of modules to include on this page
	 *
	 * @param bool $filter Whether to filter out insufficiently trustworthy modules
	 * @param string|null $position Unused
	 * @param string $param
	 * @param string $type
	 * @return string[] Array of module names
	 */
	public function getModules( $filter = false, $position = null, $param = 'mModules',
		$type = RL\Module::TYPE_COMBINED
	) {
		$modules = array_values( array_unique( $this->$param ) );
		return $filter
			? $this->filterModules( $modules, null, $type )
			: $modules;
	}

	/**
	 * Load one or more ResourceLoader modules on this page.
	 *
	 * @param string|array $modules Module name (string) or array of module names
	 */
	public function addModules( $modules ) {
		$this->mModules = array_merge( $this->mModules, (array)$modules );
	}

	/**
	 * Get the list of style-only modules to load on this page.
	 *
	 * @param bool $filter
	 * @param string|null $position Unused
	 * @return string[] Array of module names
	 */
	public function getModuleStyles( $filter = false, $position = null ) {
		return $this->getModules( $filter, null, 'mModuleStyles',
			RL\Module::TYPE_STYLES
		);
	}

	/**
	 * Load the styles of one or more style-only ResourceLoader modules on this page.
	 *
	 * Module styles added through this function will be loaded as a stylesheet,
	 * using a standard `<link rel=stylesheet>` HTML tag, rather than as a combined
	 * Javascript and CSS package. Thus, they will even load when JavaScript is disabled.
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
	 * Force the given Content object for the given page, for things like page preview.
	 * @see self::addContentOverrideCallback()
	 * @since 1.32
	 * @param LinkTarget|PageReference $target
	 * @param Content $content
	 */
	public function addContentOverride( $target, Content $content ) {
		if ( !$this->contentOverrides ) {
			// Register a callback for $this->contentOverrides on the first call
			$this->addContentOverrideCallback( function ( $target ) {
				$key = $target->getNamespace() . ':' . $target->getDBkey();
				return $this->contentOverrides[$key] ?? null;
			} );
		}

		$key = $target->getNamespace() . ':' . $target->getDBkey();
		$this->contentOverrides[$key] = $content;
	}

	/**
	 * Add a callback for mapping from a Title to a Content object, for things
	 * like page preview.
	 * @see RL\Context::getContentOverrideCallback()
	 * @since 1.32
	 * @param callable $callback
	 */
	public function addContentOverrideCallback( callable $callback ) {
		$this->contentOverrideCallbacks[] = $callback;
	}

	/**
	 * Add a class to the <html> element. This should rarely be used.
	 * Instead use OutputPage::addBodyClasses() if possible.
	 *
	 * @unstable Experimental since 1.35. Prefer OutputPage::addBodyClasses()
	 * @param string|string[] $classes One or more classes to add
	 */
	public function addHtmlClasses( $classes ) {
		$this->mAdditionalHtmlClasses = array_merge( $this->mAdditionalHtmlClasses, (array)$classes );
	}

	/**
	 * Get an array of head items
	 *
	 * @return array
	 */
	public function getHeadItemsArray() {
		return $this->mHeadItems;
	}

	/**
	 * Add or replace a head item to the output
	 *
	 * Whenever possible, use more specific options like ResourceLoader modules,
	 * OutputPage::addLink(), OutputPage::addMeta() and OutputPage::addFeedLink()
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
	 * @param string|string[] $values Raw HTML
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
	 * Add a class to the <body> element
	 *
	 * @since 1.30
	 * @param string|string[] $classes One or more classes to add
	 */
	public function addBodyClasses( $classes ) {
		$this->mAdditionalBodyClasses = array_merge( $this->mAdditionalBodyClasses, (array)$classes );
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
		return $this->mProperties[$name] ?? null;
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
			wfDebug( __METHOD__ . ": CACHE DISABLED, NO TIMESTAMP" );
			return false;
		}
		$config = $this->getConfig();
		if ( !$config->get( MainConfigNames::CachePages ) ) {
			wfDebug( __METHOD__ . ": CACHE DISABLED" );
			return false;
		}

		$timestamp = wfTimestamp( TS_MW, $timestamp );
		$modifiedTimes = [
			'page' => $timestamp,
			'user' => $this->getUser()->getTouched(),
			'epoch' => $config->get( MainConfigNames::CacheEpoch )
		];
		if ( $config->get( MainConfigNames::UseCdn ) ) {
			$modifiedTimes['sepoch'] = wfTimestamp( TS_MW, $this->getCdnCacheEpoch(
				time(),
				$config->get( MainConfigNames::CdnMaxAge )
			) );
		}
		$this->getHookRunner()->onOutputPageCheckLastModified( $modifiedTimes, $this );

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

		// E_STRICT system time warnings
		AtEase::suppressWarnings();
		$clientHeaderTime = strtotime( $clientHeader );
		AtEase::restoreWarnings();
		if ( !$clientHeaderTime ) {
			wfDebug( __METHOD__
				. ": unable to parse the client's If-Modified-Since header: $clientHeader" );
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
		// @phan-suppress-next-line PhanTypeMismatchArgumentInternal Scalar okay with php8.1
		ini_set( 'zlib.output_compression', 0 );
		$this->getRequest()->response()->statusHeader( 304 );
		$this->sendCacheControl();
		$this->disable();

		// Don't output a compressed blob when using ob_gzhandler;
		// it's technically against HTTP spec and seems to confuse
		// Firefox when the response gets split over two packets.
		wfResetOutputBuffers( false );

		return true;
	}

	/**
	 * @param int $reqTime Time of request (eg. now)
	 * @param int $maxAge Cache TTL in seconds
	 * @return int Timestamp
	 */
	private function getCdnCacheEpoch( $reqTime, $maxAge ) {
		// Ensure Last-Modified is never more than $wgCdnMaxAge in the past,
		// because even if the wiki page content hasn't changed since, static
		// resources may have changed (skin HTML, interface messages, urls, etc.)
		// and must roll-over in a timely manner (T46570)
		return $reqTime - $maxAge;
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
	 * Get the current robot policy for the page as a string in the form
	 * <index policy>,<follow policy>.
	 *
	 * @return string
	 */
	public function getRobotPolicy() {
		return "{$this->mIndexPolicy},{$this->mFollowPolicy}";
	}

	/**
	 * Format an array of robots options as a string of directives.
	 *
	 * @return string The robots policy options.
	 */
	private function formatRobotsOptions(): string {
		$options = $this->mRobotsOptions;
		// Check if options array has any non-integer keys.
		if ( count( array_filter( array_keys( $options ), 'is_string' ) ) > 0 ) {
			// Robots meta tags can have directives that are single strings or
			// have parameters that should be formatted like <directive>:<setting>.
			// If the options keys are strings, format them accordingly.
			// https://developers.google.com/search/docs/advanced/robots/robots_meta_tag
			array_walk( $options, static function ( &$value, $key ) {
				$value = is_string( $key ) ? "{$key}:{$value}" : "{$value}";
			} );
		}
		return implode( ',', $options );
	}

	/**
	 * Set the robots policy with options for the page.
	 *
	 * @since 1.38
	 * @param array $options An array of key-value pairs or a string
	 *   to populate the robots meta tag content attribute as a string.
	 */
	public function setRobotsOptions( array $options = [] ): void {
		$this->mRobotsOptions = array_merge( $this->mRobotsOptions, $options );
	}

	/**
	 * Get the robots policy content attribute for the page
	 * as a string in the form <index policy>,<follow policy>,<options>.
	 *
	 * @return string
	 */
	private function getRobotsContent(): string {
		$robotOptionString = $this->formatRobotsOptions();
		$robotArgs = ( $this->mIndexPolicy === 'index' &&
			$this->mFollowPolicy === 'follow' ) ?
			[] :
			[
				$this->mIndexPolicy,
				$this->mFollowPolicy,
			];
		if ( $robotOptionString ) {
			$robotArgs[] = $robotOptionString;
		}
		return implode( ',', $robotArgs );
	}

	/**
	 * Set the index policy for the page, but leave the follow policy un-
	 * touched.
	 *
	 * @param string $policy Either 'index' or 'noindex'.
	 */
	public function setIndexPolicy( $policy ) {
		$policy = trim( $policy );
		if ( in_array( $policy, [ 'index', 'noindex' ] ) ) {
			$this->mIndexPolicy = $policy;
		}
	}

	/**
	 * Get the current index policy for the page as a string.
	 *
	 * @return string
	 */
	public function getIndexPolicy() {
		return $this->mIndexPolicy;
	}

	/**
	 * Set the follow policy for the page, but leave the index policy un-
	 * touched.
	 *
	 * @param string $policy Either 'follow' or 'nofollow'.
	 */
	public function setFollowPolicy( $policy ) {
		$policy = trim( $policy );
		if ( in_array( $policy, [ 'follow', 'nofollow' ] ) ) {
			$this->mFollowPolicy = $policy;
		}
	}

	/**
	 * Get the current follow policy for the page as a string.
	 *
	 * @return string
	 */
	public function getFollowPolicy() {
		return $this->mFollowPolicy;
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
	 * Set $mRedirectedFrom, the page which redirected us to the current page.
	 *
	 * @param PageReference $t
	 */
	public function setRedirectedFrom( PageReference $t ) {
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
	 * @param-taint $name tainted
	 * Phan-taint-check gets very confused by $name being either a string or a Message
	 */
	public function setPageTitle( $name ) {
		if ( $name instanceof Message ) {
			$name = $name->setContext( $this->getContext() )->text();
		}

		# change "<script>foo&bar</script>" to "&lt;script&gt;foo&amp;bar&lt;/script&gt;"
		# but leave "<i>foobar</i>" alone
		$nameWithTags = Sanitizer::removeSomeTags( $name );
		$this->mPageTitle = $nameWithTags;

		# change "<i>foo&amp;bar</i>" to "foo&bar"
		$this->setHTMLTitle(
			$this->msg( 'pagetitle' )->plaintextParams( Sanitizer::stripAllTags( $nameWithTags ) )
				->inContentLanguage()
		);
	}

	/**
	 * Return the "page title", i.e. the content of the \<h1\> tag.
	 *
	 * @return string
	 */
	public function getPageTitle() {
		return $this->mPageTitle;
	}

	/**
	 * Same as page title but only contains name of the page, not any other text.
	 *
	 * @since 1.32
	 * @param string $html Page title text.
	 * @see OutputPage::setPageTitle
	 */
	public function setDisplayTitle( $html ) {
		$this->displayTitle = $html;
	}

	/**
	 * Returns page display title.
	 *
	 * Performs some normalization, but this not as strict the magic word.
	 *
	 * @since 1.32
	 * @return string HTML
	 */
	public function getDisplayTitle() {
		$html = $this->displayTitle;
		if ( $html === null ) {
			return htmlspecialchars( $this->getTitle()->getPrefixedText(), ENT_NOQUOTES );
		}

		return Sanitizer::removeSomeTags( $html );
	}

	/**
	 * Returns page display title without namespace prefix if possible.
	 *
	 * This method is unreliable and best avoided. (T314399)
	 *
	 * @since 1.32
	 * @return string HTML
	 */
	public function getUnprefixedDisplayTitle() {
		$service = MediaWikiServices::getInstance();
		$languageConverter = $service->getLanguageConverterFactory()
			->getLanguageConverter( $service->getContentLanguage() );
		$text = $this->getDisplayTitle();

		// Create a regexp with matching groups as placeholders for the namespace, separator and main text
		$pageTitleRegexp = "/^" . str_replace(
			preg_quote( '(.+?)', '/' ),
			'(.+?)',
			preg_quote( Parser::formatPageTitle( '(.+?)', '(.+?)', '(.+?)' ), '/' )
		) . "$/";
		$matches = [];
		if ( preg_match( $pageTitleRegexp, $text, $matches ) ) {
			// The regexp above could be manipulated by malicious user input,
			// sanitize the result just in case
			return Sanitizer::removeSomeTags( $matches[3] );
		}

		$nsPrefix = $languageConverter->convertNamespace(
			$this->getTitle()->getNamespace()
		) . ':';
		$prefix = preg_quote( $nsPrefix, '/' );

		return preg_replace( "/^$prefix/i", '', $text );
	}

	/**
	 * Set the Title object to use
	 *
	 * @param PageReference $t
	 */
	public function setTitle( PageReference $t ) {
		$t = Title::castFromPageReference( $t );

		// @phan-suppress-next-next-line PhanUndeclaredMethod
		// @fixme Not all implementations of IContextSource have this method!
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
	 * @since 1.25
	 * @param PageReference $page Title to link to
	 * @param array $query Array of additional parameters to include in the link
	 * @return Message
	 */
	public static function buildBacklinkSubtitle( PageReference $page, $query = [] ) {
		if ( $page instanceof PageRecord || $page instanceof Title ) {
			// Callers will typically have a PageRecord
			if ( $page->isRedirect() ) {
				$query['redirect'] = 'no';
			}
		} elseif ( $page->getNamespace() !== NS_SPECIAL ) {
			// We don't know whether it's a redirect, so add the parameter, just to be sure.
			$query['redirect'] = 'no';
		}

		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		return wfMessage( 'backlinksubtitle' )
			->rawParams( $linkRenderer->makeLink( $page, null, [], $query ) );
	}

	/**
	 * Add a subtitle containing a backlink to a page
	 *
	 * @param PageReference $title Title to link to
	 * @param array $query Array of additional parameters to include in the link
	 */
	public function addBacklinkSubtitle( PageReference $title, $query = [] ) {
		$this->addSubtitle( self::buildBacklinkSubtitle( $title, $query ) );
	}

	/**
	 * Clear the subtitles
	 */
	public function clearSubtitle() {
		$this->mSubtitle = [];
	}

	/**
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
	 * Return effective list of advertised feed types
	 * @see addFeedLink()
	 *
	 * @return string[] Array of feed type names ( 'rss', 'atom' )
	 */
	protected function getAdvertisedFeedTypes() {
		if ( $this->getConfig()->get( MainConfigNames::Feed ) ) {
			return $this->getConfig()->get( MainConfigNames::AdvertisedFeedTypes );
		} else {
			return [];
		}
	}

	/**
	 * Add default feeds to the page header
	 * This is mainly kept for backward compatibility, see OutputPage::addFeedLink()
	 * for the new version
	 * @see addFeedLink()
	 *
	 * @param string|false $val Query to append to feed links or false to output
	 *        default links
	 */
	public function setFeedAppendQuery( $val ) {
		$this->mFeedLinks = [];

		foreach ( $this->getAdvertisedFeedTypes() as $type ) {
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
		if ( in_array( $format, $this->getAdvertisedFeedTypes() ) ) {
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
	 * @param bool $newVal
	 */
	public function setArticleFlag( $newVal ) {
		$this->mIsArticle = $newVal;
		if ( $newVal ) {
			$this->mIsArticleRelated = $newVal;
		}
	}

	/**
	 * Return whether the content displayed page is related to the source of
	 * the corresponding article on the wiki
	 *
	 * @return bool
	 */
	public function isArticle() {
		return $this->mIsArticle;
	}

	/**
	 * Set whether this page is related an article on the wiki
	 * Setting false will cause the change of "article flag" toggle to false
	 *
	 * @param bool $newVal
	 */
	public function setArticleRelated( $newVal ) {
		$this->mIsArticleRelated = $newVal;
		if ( !$newVal ) {
			$this->mIsArticle = false;
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
	 * Set whether the standard copyright should be shown for the current page.
	 *
	 * @param bool $hasCopyright
	 */
	public function setCopyright( $hasCopyright ) {
		$this->mHasCopyright = $hasCopyright;
	}

	/**
	 * Return whether the standard copyright should be shown for the current page.
	 * By default, it is true for all articles but other pages
	 * can signal it by using setCopyright( true ).
	 *
	 * Used by SkinTemplate to decided whether to show the copyright.
	 *
	 * @return bool
	 */
	public function showsCopyright() {
		return $this->isArticle() || $this->mHasCopyright;
	}

	/**
	 * Add new language links
	 *
	 * @param string[] $newLinkArray Array of interwiki-prefixed (non DB key) titles
	 *                               (e.g. 'fr:Test page')
	 */
	public function addLanguageLinks( array $newLinkArray ) {
		$this->mLanguageLinks = array_merge( $this->mLanguageLinks, $newLinkArray );
	}

	/**
	 * Reset the language links and add new language links
	 *
	 * @param string[] $newLinkArray Array of interwiki-prefixed (non DB key) titles
	 *                               (e.g. 'fr:Test page')
	 */
	public function setLanguageLinks( array $newLinkArray ) {
		$this->mLanguageLinks = $newLinkArray;
	}

	/**
	 * Get the list of language links
	 *
	 * @return string[] Array of interwiki-prefixed (non DB key) titles (e.g. 'fr:Test page')
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
		if ( !$categories ) {
			return;
		}

		$res = $this->addCategoryLinksToLBAndGetResult( $categories );

		# Set all the values to 'normal'.
		$categories = array_fill_keys( array_keys( $categories ), 'normal' );
		$pageData = [];

		# Mark hidden categories
		foreach ( $res as $row ) {
			if ( isset( $row->pp_value ) ) {
				$categories[$row->page_title] = 'hidden';
			}
			// Page exists, cache results
			if ( isset( $row->page_id ) ) {
				$pageData[$row->page_title] = $row;
			}
		}

		# Add the remaining categories to the skin
		if ( $this->getHookRunner()->onOutputPageMakeCategoryLinks(
			$this, $categories, $this->mCategoryLinks )
		) {
			$services = MediaWikiServices::getInstance();
			$linkRenderer = $services->getLinkRenderer();
			$languageConverter = $services->getLanguageConverterFactory()
				->getLanguageConverter( $services->getContentLanguage() );
			foreach ( $categories as $category => $type ) {
				// array keys will cast numeric category names to ints, so cast back to string
				$category = (string)$category;
				$origcategory = $category;
				if ( array_key_exists( $category, $pageData ) ) {
					$title = Title::newFromRow( $pageData[$category] );
				} else {
					$title = Title::makeTitleSafe( NS_CATEGORY, $category );
				}
				if ( !$title ) {
					continue;
				}
				$languageConverter->findVariantLink( $category, $title, true );

				if ( $category != $origcategory && array_key_exists( $category, $categories ) ) {
					continue;
				}
				$text = $languageConverter->convertHtml( $title->getText() );
				$this->mCategories[$type][] = $title->getText();
				$this->mCategoryLinks[$type][] = $linkRenderer->makeLink( $title, new HtmlArmor( $text ) );
			}
		}
	}

	/**
	 * @param array $categories
	 * @return bool|IResultWrapper
	 */
	protected function addCategoryLinksToLBAndGetResult( array $categories ) {
		# Add the links to a LinkBatch
		$arr = [ NS_CATEGORY => $categories ];
		$linkBatchFactory = MediaWikiServices::getInstance()->getLinkBatchFactory();
		$lb = $linkBatchFactory->newLinkBatch();
		$lb->setArray( $arr );

		# Fetch existence plus the hiddencat property
		$dbr = wfGetDB( DB_REPLICA );
		$fields = array_merge(
			LinkCache::getSelectFields(),
			[ 'pp_value' ]
		);

		$res = $dbr->newSelectQueryBuilder()
			->select( $fields )
			->from( 'page' )
			->leftJoin( 'page_props', null, [
				'pp_propname' => 'hiddencat',
				'pp_page = page_id',
			] )
			->where( $lb->constructSet( 'page', $dbr ) )
			->caller( __METHOD__ )
			->fetchResultSet();

		# Add the results to the link cache
		$linkCache = MediaWikiServices::getInstance()->getLinkCache();
		$lb->addResultToCache( $linkCache, $res );

		return $res;
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
	 * @return string[][]
	 * @return-taint none
	 */
	public function getCategoryLinks() {
		return $this->mCategoryLinks;
	}

	/**
	 * Get the list of category names this page belongs to.
	 *
	 * @param string $type The type of categories which should be returned. Possible values:
	 *  * all: all categories of all types
	 *  * hidden: only the hidden categories
	 *  * normal: all categories, except hidden categories
	 * @return string[]
	 */
	public function getCategories( $type = 'all' ) {
		if ( $type === 'all' ) {
			$allCategories = [];
			foreach ( $this->mCategories as $categories ) {
				$allCategories = array_merge( $allCategories, $categories );
			}
			return $allCategories;
		}
		if ( !isset( $this->mCategories[$type] ) ) {
			throw new InvalidArgumentException( 'Invalid category type given: ' . $type );
		}
		return $this->mCategories[$type];
	}

	/**
	 * Add an array of indicators, with their identifiers as array
	 * keys and HTML contents as values.
	 *
	 * In case of duplicate keys, existing values are overwritten.
	 *
	 * @note External code which calls this method should ensure that
	 * any indicators sourced from parsed wikitext are wrapped with
	 * the appropriate class; see note in ::getIndicators().
	 *
	 * @param string[] $indicators
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
	 * @return string[] Keys: identifiers, values: HTML contents
	 * @since 1.25
	 */
	public function getIndicators() {
		// Note that some -- but not all -- indicators will be wrapped
		// with a class appropriate for user-generated wikitext content
		// (usually .mw-parser-output). The exceptions would be an
		// indicator added via ::addHelpLink() below, which adds content
		// which don't come from the parser and is not user-generated;
		// and any indicators added by extensions which may call
		// OutputPage::setIndicators() directly.  In the latter case the
		// caller is responsible for wrapping any parser-generated
		// indicators.
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
			$helpUrl = "https://www.mediawiki.org/wiki/Special:MyLanguage/$toUrlencoded";
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

		// See note in ::getIndicators() above -- unlike wikitext-generated
		// indicators which come from ParserOutput, this indicator will not
		// be wrapped.
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
			RL\Module::TYPE_SCRIPTS,
			RL\Module::ORIGIN_CORE_INDIVIDUAL
		);

		// Site-wide styles are controlled by a config setting, see T73621
		// for background on why. User styles are never allowed.
		if ( $this->getConfig()->get( MainConfigNames::AllowSiteCSSOnRestrictedPages ) ) {
			$styleOrigin = RL\Module::ORIGIN_USER_SITEWIDE;
		} else {
			$styleOrigin = RL\Module::ORIGIN_CORE_INDIVIDUAL;
		}
		$this->reduceAllowedModules(
			RL\Module::TYPE_STYLES,
			$styleOrigin
		);
	}

	/**
	 * Show what level of JavaScript / CSS untrustworthiness is allowed on this page
	 * @see RL\Module::$origin
	 * @param string $type RL\Module TYPE_ constant
	 * @return int Module ORIGIN_ class constant
	 */
	public function getAllowedModules( $type ) {
		if ( $type == RL\Module::TYPE_COMBINED ) {
			return min( array_values( $this->mAllowedModules ) );
		} else {
			return $this->mAllowedModules[$type] ?? RL\Module::ORIGIN_ALL;
		}
	}

	/**
	 * Limit the highest level of CSS/JS untrustworthiness allowed.
	 *
	 * If passed the same or a higher level than the current level of untrustworthiness set, the
	 * level will remain unchanged.
	 *
	 * @param string $type
	 * @param int $level RL\Module class constant
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
	 * @return ParserOptions
	 */
	public function parserOptions() {
		if ( !$this->mParserOptions ) {
			if ( !$this->getUser()->isSafeToLoad() ) {
				// Context user isn't unstubbable yet, so don't try to get a
				// ParserOptions for it. And don't cache this ParserOptions
				// either.
				$po = ParserOptions::newFromAnon();
				$po->setAllowUnsafeRawHtml( false );
				return $po;
			}

			$this->mParserOptions = ParserOptions::newFromContext( $this->getContext() );
			$this->mParserOptions->setAllowUnsafeRawHtml( false );
		}

		return $this->mParserOptions;
	}

	/**
	 * Set the revision ID which will be seen by the wiki text parser
	 * for things such as embedded {{REVISIONID}} variable use.
	 *
	 * @param int|null $revid A positive integer, or null
	 * @return mixed Previous value
	 */
	public function setRevisionId( $revid ) {
		$val = $revid === null ? null : intval( $revid );
		return wfSetVar( $this->mRevisionId, $val, true );
	}

	/**
	 * Get the displayed revision ID
	 *
	 * @return int|null
	 */
	public function getRevisionId() {
		return $this->mRevisionId;
	}

	/**
	 * Set whether the revision displayed (as set in ::setRevisionId())
	 * is the latest revision of the page.
	 *
	 * @param bool $isCurrent
	 */
	public function setRevisionIsCurrent( bool $isCurrent ): void {
		$this->mRevisionIsCurrent = $isCurrent;
	}

	/**
	 * Whether the revision displayed is the latest revision of the page
	 *
	 * @since 1.34
	 * @return bool
	 */
	public function isRevisionCurrent(): bool {
		return $this->mRevisionId == 0 || (
			$this->mRevisionIsCurrent ?? (
				$this->mRevisionId == $this->getTitle()->getLatestRevID()
			)
		);
	}

	/**
	 * Set the timestamp of the revision which will be displayed. This is used
	 * to avoid a extra DB call in Skin::lastModified().
	 *
	 * @param string|null $timestamp
	 * @return mixed Previous value
	 */
	public function setRevisionTimestamp( $timestamp ) {
		return wfSetVar( $this->mRevisionTimestamp, $timestamp, true );
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
	 * @param File|null $file
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
	 * @return array [ dbKey => [ 'time' => MW timestamp or null, 'sha1' => sha1 or '' ] ]
	 * @since 1.18
	 */
	public function getFileSearchOptions() {
		return $this->mImageTimeKeys;
	}

	/**
	 * Convert wikitext *in the user interface language* to HTML and
	 * add it to the buffer. The result will not be
	 * language-converted, as user interface messages are already
	 * localized into a specific variant.  Assumes that the current
	 * page title will be used if optional $title is not
	 * provided. Output will be tidy.
	 *
	 * @param string $text Wikitext in the user interface language
	 * @param bool $linestart Is this the start of a line? (Defaults to true)
	 * @param PageReference|null $title Optional title to use; default of `null`
	 *   means use current page title.
	 * @throws MWException if $title is not provided and OutputPage::getTitle()
	 *   is null
	 * @since 1.32
	 */
	public function addWikiTextAsInterface(
		$text, $linestart = true, PageReference $title = null
	) {
		if ( $title === null ) {
			$title = $this->getTitle();
		}
		if ( !$title ) {
			throw new MWException( 'Title is null' );
		}
		$this->addWikiTextTitleInternal( $text, $title, $linestart, /*interface*/true );
	}

	/**
	 * Convert wikitext *in the user interface language* to HTML and
	 * add it to the buffer with a `<div class="$wrapperClass">`
	 * wrapper.  The result will not be language-converted, as user
	 * interface messages as already localized into a specific
	 * variant.  The $text will be parsed in start-of-line context.
	 * Output will be tidy.
	 *
	 * @param string $wrapperClass The class attribute value for the <div>
	 *   wrapper in the output HTML
	 * @param string $text Wikitext in the user interface language
	 * @since 1.32
	 */
	public function wrapWikiTextAsInterface(
		$wrapperClass, $text
	) {
		$this->addWikiTextTitleInternal(
			$text, $this->getTitle(),
			/*linestart*/true, /*interface*/true,
			$wrapperClass
		);
	}

	/**
	 * Convert wikitext *in the page content language* to HTML and add
	 * it to the buffer.  The result with be language-converted to the
	 * user's preferred variant.  Assumes that the current page title
	 * will be used if optional $title is not provided. Output will be
	 * tidy.
	 *
	 * @param string $text Wikitext in the page content language
	 * @param bool $linestart Is this the start of a line? (Defaults to true)
	 * @param PageReference|null $title Optional title to use; default of `null`
	 *   means use current page title.
	 * @throws MWException if $title is not provided and OutputPage::getTitle()
	 *   is null
	 * @since 1.32
	 */
	public function addWikiTextAsContent(
		$text, $linestart = true, PageReference $title = null
	) {
		if ( $title === null ) {
			$title = $this->getTitle();
		}
		if ( !$title ) {
			throw new MWException( 'Title is null' );
		}
		$this->addWikiTextTitleInternal( $text, $title, $linestart, /*interface*/false );
	}

	/**
	 * Add wikitext with a custom Title object.
	 * Output is unwrapped.
	 *
	 * @param string $text Wikitext
	 * @param PageReference $title
	 * @param bool $linestart Is this the start of a line?@param
	 * @param bool $interface Whether it is an interface message
	 *   (for example disables conversion)
	 * @param string|null $wrapperClass if not empty, wraps the output in
	 *   a `<div class="$wrapperClass">`
	 */
	private function addWikiTextTitleInternal(
		$text, PageReference $title, $linestart, $interface, $wrapperClass = null
	) {
		$parserOutput = $this->parseInternal(
			$text, $title, $linestart, $interface
		);

		$this->addParserOutput( $parserOutput, [
			'enableSectionEditLinks' => false,
			'wrapperDivClass' => $wrapperClass ?? '',
		] );
	}

	/**
	 * Adds sections to OutputPage from ParserOutput
	 * @param array $sections
	 * @internal For use by Article.php
	 */
	public function setSections( array $sections ) {
		$this->mSections = $sections;
	}

	/**
	 * @internal For usage in Skin::getSectionsData() only.
	 * @return array Array of sections.
	 *   Empty if OutputPage::setSections() has not been called.
	 */
	public function getSections(): array {
		return $this->mSections;
	}

	/**
	 * Add all metadata associated with a ParserOutput object, but without the actual HTML. This
	 * includes categories, language links, ResourceLoader modules, effects of certain magic words,
	 * and so on.  It does *not* include section information.
	 *
	 * @since 1.24
	 * @param ParserOutput $parserOutput
	 */
	public function addParserOutputMetadata( ParserOutput $parserOutput ) {
		// T301020 This should eventually use the standard "merge ParserOutput"
		// function between $parserOutput and $this->metadata.
		$this->mLanguageLinks =
			array_merge( $this->mLanguageLinks, $parserOutput->getLanguageLinks() );
		$this->addCategoryLinks( $parserOutput->getCategories() );

		// Parser-generated indicators get wrapped like other parser output.
		$wrapClass = $parserOutput->getWrapperDivClass();
		$result = [];
		foreach ( $parserOutput->getIndicators() as $name => $html ) {
			if ( $html !== '' && $wrapClass !== '' ) {
				$html = Html::rawElement( 'div', [ 'class' => $wrapClass ], $html );
			}
			$result[$name] = $html;
		}
		$this->setIndicators( $result );

		// FIXME: Best practice is for OutputPage to be an accumulator, as
		// addParserOutputMetadata() may be called multiple times, but the
		// following lines overwrite any previous data.  These should
		// be migrated to an injection pattern. (T301020, T300979)
		$this->mNewSectionLink = $parserOutput->getNewSection();
		$this->mHideNewSectionLink = $parserOutput->getHideNewSection();
		$this->mNoGallery = $parserOutput->getNoGallery();

		if ( !$parserOutput->isCacheable() ) {
			$this->disableClientCache();
		}
		$this->mHeadItems = array_merge( $this->mHeadItems, $parserOutput->getHeadItems() );
		$this->addModules( $parserOutput->getModules() );
		$this->addModuleStyles( $parserOutput->getModuleStyles() );
		$this->addJsConfigVars( $parserOutput->getJsConfigVars() );
		$this->mPreventClickjacking = $this->mPreventClickjacking
			|| $parserOutput->getPreventClickjacking();
		$scriptSrcs = $parserOutput->getExtraCSPScriptSrcs();
		foreach ( $scriptSrcs as $src ) {
			$this->getCSP()->addScriptSrc( $src );
		}
		$defaultSrcs = $parserOutput->getExtraCSPDefaultSrcs();
		foreach ( $defaultSrcs as $src ) {
			$this->getCSP()->addDefaultSrc( $src );
		}
		$styleSrcs = $parserOutput->getExtraCSPStyleSrcs();
		foreach ( $styleSrcs as $src ) {
			$this->getCSP()->addStyleSrc( $src );
		}

		// If $wgImagePreconnect is true, and if the output contains
		// images, give the user-agent a hint about foreign repos from
		// which those images may be served.  See T123582.
		//
		// TODO: We don't have an easy way to know from which remote(s)
		// the image(s) will be served.  For now, we only hint the first
		// valid one.
		if ( $this->getConfig()->get( MainConfigNames::ImagePreconnect ) && count( $parserOutput->getImages() ) ) {
			$preconnect = [];
			$repoGroup = MediaWikiServices::getInstance()->getRepoGroup();
			$repoGroup->forEachForeignRepo( static function ( $repo ) use ( &$preconnect ) {
				$preconnect[] = wfParseUrl( $repo->getZoneUrl( 'thumb' ) )['host'];
			} );
			$preconnect[] = wfParseUrl( $repoGroup->getLocalRepo()->getZoneUrl( 'thumb' ) )['host'];
			foreach ( $preconnect as $host ) {
				if ( $host ) {
					$this->addLink( [ 'rel' => 'preconnect', 'href' => '//' . $host ] );
					break;
				}
			}
		}

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
		// Deprecated! See T292321; should be done in the OutputPageParserOutput
		// hook instead.
		$parserOutputHooks = $this->getConfig()->get( MainConfigNames::ParserOutputHooks );
		foreach ( $parserOutput->getOutputHooks() as $hookInfo ) {
			[ $hookName, $data ] = $hookInfo;
			if ( isset( $parserOutputHooks[$hookName] ) ) {
				$parserOutputHooks[$hookName]( $this, $parserOutput, $data );
			}
		}

		// Enable OOUI if requested via ParserOutput
		if ( $parserOutput->getEnableOOUI() ) {
			$this->enableOOUI();
		}

		// Include parser limit report
		// FIXME: This should append, rather than overwrite, or else this
		// data should be injected into the OutputPage like is done for the
		// other page-level things (like OutputPage::setSections()).
		if ( !$this->limitReportJSData ) {
			$this->limitReportJSData = $parserOutput->getLimitReportJSData();
		}

		// Link flags are ignored for now, but may in the future be
		// used to mark individual language links.
		$linkFlags = [];
		$this->getHookRunner()->onLanguageLinks( $this->getTitle(), $this->mLanguageLinks, $linkFlags );
		$this->getHookRunner()->onOutputPageParserOutput( $this, $parserOutput );

		// This check must be after 'OutputPageParserOutput' runs in addParserOutputMetadata
		// so that extensions may modify ParserOutput to toggle TOC.
		// This cannot be moved to addParserOutputText because that is not
		// called by EditPage for Preview.
		if ( $parserOutput->getTOCHTML() ) {
			$this->mEnableTOC = true;
		}
	}

	/**
	 * Add the HTML and enhancements for it (like ResourceLoader modules) associated with a
	 * ParserOutput object, without any other metadata.
	 *
	 * @since 1.24
	 * @param ParserOutput $parserOutput
	 * @param array $poOptions Options to ParserOutput::getText()
	 */
	public function addParserOutputContent( ParserOutput $parserOutput, $poOptions = [] ) {
		$this->addParserOutputText( $parserOutput, $poOptions );

		$this->addModules( $parserOutput->getModules() );
		$this->addModuleStyles( $parserOutput->getModuleStyles() );

		$this->addJsConfigVars( $parserOutput->getJsConfigVars() );
	}

	/**
	 * Add the HTML associated with a ParserOutput object, without any metadata.
	 *
	 * @since 1.24
	 * @param ParserOutput $parserOutput
	 * @param array $poOptions Options to ParserOutput::getText()
	 */
	public function addParserOutputText( ParserOutput $parserOutput, $poOptions = [] ) {
		$text = $parserOutput->getText( $poOptions );
		$this->getHookRunner()->onOutputPageBeforeHTML( $this, $text );
		$this->addHTML( $text );
	}

	/**
	 * Add everything from a ParserOutput object.
	 *
	 * @param ParserOutput $parserOutput
	 * @param array $poOptions Options to ParserOutput::getText()
	 */
	public function addParserOutput( ParserOutput $parserOutput, $poOptions = [] ) {
		$this->addParserOutputMetadata( $parserOutput );
		$this->addParserOutputText( $parserOutput, $poOptions );
	}

	/**
	 * Add the output of a QuickTemplate to the output buffer
	 *
	 * @param QuickTemplate &$template
	 */
	public function addTemplate( &$template ) {
		$this->addHTML( $template->getHTML() );
	}

	/**
	 * Parse wikitext *in the page content language* and return the HTML.
	 * The result will be language-converted to the user's preferred variant.
	 * Output will be tidy.
	 *
	 * @param string $text Wikitext in the page content language
	 * @param bool $linestart Is this the start of a line? (Defaults to true)
	 * @throws MWException
	 * @return string HTML
	 * @since 1.32
	 */
	public function parseAsContent( $text, $linestart = true ) {
		return $this->parseInternal(
			$text, $this->getTitle(), $linestart, /*interface*/false
		)->getText( [
			'enableSectionEditLinks' => false,
			'wrapperDivClass' => ''
		] );
	}

	/**
	 * Parse wikitext *in the user interface language* and return the HTML.
	 * The result will not be language-converted, as user interface messages
	 * are already localized into a specific variant.
	 * Output will be tidy.
	 *
	 * @param string $text Wikitext in the user interface language
	 * @param bool $linestart Is this the start of a line? (Defaults to true)
	 * @throws MWException
	 * @return string HTML
	 * @since 1.32
	 */
	public function parseAsInterface( $text, $linestart = true ) {
		return $this->parseInternal(
			$text, $this->getTitle(), $linestart, /*interface*/true
		)->getText( [
			'enableSectionEditLinks' => false,
			'wrapperDivClass' => ''
		] );
	}

	/**
	 * Parse wikitext *in the user interface language*, strip
	 * paragraph wrapper, and return the HTML.
	 * The result will not be language-converted, as user interface messages
	 * are already localized into a specific variant.
	 * Output will be tidy.  Outer paragraph wrapper will only be stripped
	 * if the result is a single paragraph.
	 *
	 * @param string $text Wikitext in the user interface language
	 * @param bool $linestart Is this the start of a line? (Defaults to true)
	 * @throws MWException
	 * @return string HTML
	 * @since 1.32
	 */
	public function parseInlineAsInterface( $text, $linestart = true ) {
		return Parser::stripOuterParagraph(
			$this->parseAsInterface( $text, $linestart )
		);
	}

	/**
	 * Parse wikitext and return the HTML (internal implementation helper)
	 *
	 * @param string $text
	 * @param PageReference $title The title to use
	 * @param bool $linestart Is this the start of a line?
	 * @param bool $interface Use interface language (instead of content language) while parsing
	 *   language sensitive magic words like GRAMMAR and PLURAL.  This also disables
	 *   LanguageConverter.
	 * @throws MWException
	 * @return ParserOutput
	 */
	private function parseInternal( $text, $title, $linestart, $interface ) {
		if ( $title === null ) {
			throw new MWException( 'Empty $mTitle in ' . __METHOD__ );
		}

		$popts = $this->parserOptions();

		$oldInterface = $popts->setInterfaceMessage( (bool)$interface );

		$parserOutput = MediaWikiServices::getInstance()->getParserFactory()->getInstance()
			->parse(
				$text, $title, $popts,
				$linestart, true, $this->mRevisionId
			);

		$popts->setInterfaceMessage( $oldInterface );

		return $parserOutput;
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
	 * Set the value of the "s-maxage" part of the "Cache-control" HTTP header to $maxage if that is
	 * lower than the current s-maxage.  Either way, $maxage is now an upper limit on s-maxage, so
	 * that future calls to setCdnMaxage() will no longer be able to raise the s-maxage above
	 * $maxage.
	 *
	 * @param int $maxage Maximum cache time on the CDN, in seconds
	 * @since 1.27
	 */
	public function lowerCdnMaxage( $maxage ) {
		$this->mCdnMaxageLimit = min( $maxage, $this->mCdnMaxageLimit );
		$this->setCdnMaxage( $this->mCdnMaxage );
	}

	/**
	 * Get TTL in [$minTTL,$maxTTL] and pass it to lowerCdnMaxage()
	 *
	 * This sets and returns $minTTL if $mtime is false or null. Otherwise,
	 * the TTL is higher the older the $mtime timestamp is. Essentially, the
	 * TTL is 90% of the age of the object, subject to the min and max.
	 *
	 * @param string|int|float|bool|null $mtime Last-Modified timestamp
	 * @param int $minTTL Minimum TTL in seconds [default: 1 minute]
	 * @param int $maxTTL Maximum TTL in seconds [default: $wgCdnMaxAge]
	 * @since 1.28
	 */
	public function adaptCdnTTL( $mtime, $minTTL = 0, $maxTTL = 0 ) {
		$minTTL = $minTTL ?: IExpiringStore::TTL_MINUTE;
		$maxTTL = $maxTTL ?: $this->getConfig()->get( MainConfigNames::CdnMaxAge );

		if ( $mtime === null || $mtime === false ) {
			return; // entity does not exist
		}

		$age = MWTimestamp::time() - (int)wfTimestamp( TS_UNIX, $mtime );
		$adaptiveTTL = max( 0.9 * $age, $minTTL );
		$adaptiveTTL = min( $adaptiveTTL, $maxTTL );

		$this->lowerCdnMaxage( (int)$adaptiveTTL );
	}

	/**
	 * Use enableClientCache(false) to force it to send nocache headers
	 *
	 * @param bool|null $state New value, or null to not set the value
	 *
	 * @return bool Old value
	 * @deprecated since 1.38; use disableClientCache() instead
	 * ($state is almost always `false` when this method is called)
	 */
	public function enableClientCache( $state ) {
		wfDeprecated( __METHOD__, '1.38' );
		return wfSetVar( $this->mEnableClientCache, $state );
	}

	/**
	 * Force the page to send nocache headers
	 * @since 1.38
	 */
	public function disableClientCache(): void {
		$this->mEnableClientCache = false;
	}

	/**
	 * Whether the output might become publicly cached.
	 *
	 * @since 1.34
	 * @return bool
	 */
	public function couldBePublicCached() {
		if ( !$this->cacheIsFinal ) {
			// - The entry point handles its own caching and/or doesn't use OutputPage.
			//   (such as load.php, or MediaWiki\Rest\EntryPoint).
			//
			// - Or, we haven't finished processing the main part of the request yet
			//   (e.g. Action::show, SpecialPage::execute), and the state may still
			//   change via enableClientCache().
			return true;
		}
		// e.g. various error-type pages disable all client caching
		return $this->mEnableClientCache;
	}

	/**
	 * Set the expectation that cache control will not change after this point.
	 *
	 * This should be called after the main processing logic has completed
	 * (e.g. Action::show or SpecialPage::execute), but may be called
	 * before Skin output has started (OutputPage::output).
	 *
	 * @since 1.34
	 */
	public function considerCacheSettingsFinal() {
		$this->cacheIsFinal = true;
	}

	/**
	 * Get the list of cookie names that will influence the cache
	 *
	 * @return array
	 */
	public function getCacheVaryCookies() {
		if ( self::$cacheVaryCookies === null ) {
			$config = $this->getConfig();
			self::$cacheVaryCookies = array_values( array_unique( array_merge(
				SessionManager::singleton()->getVaryCookies(),
				[
					'forceHTTPS',
				],
				$config->get( MainConfigNames::CacheVaryCookies )
			) ) );
			$this->getHookRunner()->onGetCacheVaryCookies( $this, self::$cacheVaryCookies );
		}
		return self::$cacheVaryCookies;
	}

	/**
	 * Check if the request has a cache-varying cookie header
	 * If it does, it's very important that we don't allow public caching
	 *
	 * @return bool
	 */
	public function haveCacheVaryCookies() {
		$request = $this->getRequest();
		foreach ( $this->getCacheVaryCookies() as $cookieName ) {
			if ( $request->getCookie( $cookieName, '', '' ) !== '' ) {
				wfDebug( __METHOD__ . ": found $cookieName" );
				return true;
			}
		}
		wfDebug( __METHOD__ . ": no cache-varying cookies found" );
		return false;
	}

	/**
	 * Add an HTTP header that will influence on the cache
	 *
	 * @param string $header Header name
	 * @param string[]|null $option Deprecated; formerly options for the
	 *  Key header, deprecated in 1.32 and removed in 1.34. See
	 *   https://datatracker.ietf.org/doc/draft-fielding-http-key/
	 *   for the list of formerly-valid options.
	 */
	public function addVaryHeader( $header, array $option = null ) {
		if ( $option !== null && count( $option ) > 0 ) {
			wfDeprecatedMsg(
				'The $option parameter to addVaryHeader is ignored since MediaWiki 1.34',
				'1.34' );
		}
		if ( !array_key_exists( $header, $this->mVaryHeader ) ) {
			$this->mVaryHeader[$header] = null;
		}
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
	 * Add an HTTP Link: header
	 *
	 * @param string $header Header value
	 */
	public function addLinkHeader( $header ) {
		$this->mLinkHeader[] = $header;
	}

	/**
	 * Return a Link: header. Based on the values of $mLinkHeader.
	 *
	 * @return string|false
	 */
	public function getLinkHeader() {
		if ( !$this->mLinkHeader ) {
			return false;
		}

		return 'Link: ' . implode( ',', $this->mLinkHeader );
	}

	/**
	 * T23672: Add Accept-Language to Vary header if there's no 'variant' parameter in GET.
	 *
	 * For example:
	 *   /w/index.php?title=Main_page will vary based on Accept-Language; but
	 *   /w/index.php?title=Main_page&variant=zh-cn will not.
	 */
	private function addAcceptLanguage() {
		$title = $this->getTitle();
		if ( !$title instanceof Title ) {
			return;
		}

		$languageConverter = MediaWikiServices::getInstance()->getLanguageConverterFactory()
			->getLanguageConverter( $title->getPageLanguage() );
		if ( !$this->getRequest()->getCheck( 'variant' ) && $languageConverter->hasVariants() ) {
			$this->addVaryHeader( 'Accept-Language' );
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
	 * @deprecated since 1.38, use ::setPreventClickjacking( true )
	 */
	public function preventClickjacking( $enable = true ) {
		$this->mPreventClickjacking = $enable;
	}

	/**
	 * Turn off frame-breaking. Alias for $this->preventClickjacking(false).
	 * This can be called from pages which do not contain any CSRF-protected
	 * HTML form.
	 *
	 * @deprecated since 1.38, use ::setPreventClickjacking( false )
	 */
	public function allowClickjacking() {
		$this->mPreventClickjacking = false;
	}

	/**
	 * Set the prevent-clickjacking flag.
	 *
	 * If true, will cause an X-Frame-Options header appropriate for
	 * edit pages to be sent. The header value is controlled by
	 * $wgEditPageFrameOptions.  This is the default for special
	 * pages. If you display a CSRF-protected form on an ordinary view
	 * page, then you need to call this function.
	 *
	 * Setting this flag to false will turn off frame-breaking.  This
	 * can be called from pages which do not contain any
	 * CSRF-protected HTML form.
	 *
	 * @param bool $enable If true, will cause an X-Frame-Options header
	 *  appropriate for edit pages to be sent.
	 *
	 * @since 1.38
	 */
	public function setPreventClickjacking( bool $enable ) {
		$this->mPreventClickjacking = $enable;
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
	 * @return string|false
	 */
	public function getFrameOptions() {
		$config = $this->getConfig();
		if ( $config->get( MainConfigNames::BreakFrames ) ) {
			return 'DENY';
		} elseif ( $this->mPreventClickjacking && $config->get( MainConfigNames::EditPageFrameOptions ) ) {
			return $config->get( MainConfigNames::EditPageFrameOptions );
		}
		return false;
	}

	/**
	 * Get the Origin-Trial header values. This is used to enable Chrome Origin
	 * Trials: https://github.com/GoogleChrome/OriginTrials
	 *
	 * @return array
	 */
	private function getOriginTrials() {
		$config = $this->getConfig();

		return $config->get( MainConfigNames::OriginTrials );
	}

	private function getReportTo() {
		$config = $this->getConfig();

		$expiry = $config->get( MainConfigNames::ReportToExpiry );

		if ( !$expiry ) {
			return false;
		}

		$endpoints = $config->get( MainConfigNames::ReportToEndpoints );

		if ( !$endpoints ) {
			return false;
		}

		$output = [ 'max_age' => $expiry, 'endpoints' => [] ];

		foreach ( $endpoints as $endpoint ) {
			$output['endpoints'][] = [ 'url' => $endpoint ];
		}

		return json_encode( $output, JSON_UNESCAPED_SLASHES );
	}

	private function getFeaturePolicyReportOnly() {
		$config = $this->getConfig();

		$features = $config->get( MainConfigNames::FeaturePolicyReportOnly );
		return implode( ';', $features );
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

		if ( $this->mEnableClientCache ) {
			if ( !$config->get( MainConfigNames::UseCdn ) ) {
				$privateReason = 'config';
			} elseif ( $response->hasCookies() ) {
				$privateReason = 'set-cookies';
			// The client might use methods other than cookies to appear logged-in.
			// E.g. HTTP headers, or query parameter tokens, OAuth, etc.
			} elseif ( SessionManager::getGlobalSession()->isPersistent() ) {
				$privateReason = 'session';
			} elseif ( $this->isPrintable() ) {
				$privateReason = 'printable';
			} elseif ( $this->mCdnMaxage == 0 ) {
				$privateReason = 'no-maxage';
			} elseif ( $this->haveCacheVaryCookies() ) {
				$privateReason = 'cache-vary-cookies';
			} else {
				$privateReason = false;
			}

			if ( $privateReason === false ) {
				# We'll purge the proxy cache for anons explicitly, but require end user agents
				# to revalidate against the proxy on each visit.
				# IMPORTANT! The CDN needs to replace the Cache-Control header with
				# Cache-Control: s-maxage=0, must-revalidate, max-age=0
				wfDebug( __METHOD__ .
					": local proxy caching; {$this->mLastModified} **", 'private' );
				# start with a shorter timeout for initial testing
				# header( "Cache-Control: s-maxage=2678400, must-revalidate, max-age=0" );
				$response->header( "Cache-Control: " .
					"s-maxage={$this->mCdnMaxage}, must-revalidate, max-age=0" );
			} else {
				# We do want clients to cache if they can, but they *must* check for updates
				# on revisiting the page.
				wfDebug( __METHOD__ . ": private caching ($privateReason); {$this->mLastModified} **", 'private' );

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
	 * Transfer styles and JavaScript modules from skin.
	 *
	 * @param Skin $sk to load modules for
	 */
	public function loadSkinModules( $sk ) {
		foreach ( $sk->getDefaultModules() as $group => $modules ) {
			if ( $group === 'styles' ) {
				foreach ( $modules as $key => $moduleMembers ) {
					$this->addModuleStyles( $moduleMembers );
				}
			} else {
				$this->addModules( $modules );
			}
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
			$content = '';

			if ( $this->getHookRunner()->onBeforePageRedirect( $this, $redirect, $code ) ) {
				if ( $code == '301' || $code == '303' ) {
					if ( !$config->get( MainConfigNames::DebugRedirects ) ) {
						$response->statusHeader( (int)$code );
					}
					$this->mLastModified = wfTimestamp( TS_RFC2822 );
				}
				if ( $config->get( MainConfigNames::VaryOnXFP ) ) {
					$this->addVaryHeader( 'X-Forwarded-Proto' );
				}
				$this->sendCacheControl();

				$response->header( 'Content-Type: text/html; charset=UTF-8' );
				if ( $config->get( MainConfigNames::DebugRedirects ) ) {
					$url = htmlspecialchars( $redirect );
					$content = "<!DOCTYPE html>\n<html>\n<head>\n"
						. "<title>Redirect</title>\n</head>\n<body>\n"
						. "<p>Location: <a href=\"$url\">$url</a></p>\n"
						. "</body>\n</html>\n";

					if ( !$return ) {
						print $content;
					}

				} else {
					$response->header( 'Location: ' . $redirect );
				}
			}

			return $return ? $content : null;
		} elseif ( $this->mStatusCode ) {
			$response->statusHeader( $this->mStatusCode );
		}

		# Buffer output; final headers may depend on later processing
		ob_start();

		$response->header( 'Content-type: ' . $config->get( MainConfigNames::MimeType ) . '; charset=UTF-8' );
		$response->header( 'Content-language: ' .
			MediaWikiServices::getInstance()->getContentLanguage()->getHtmlCode() );

		$linkHeader = $this->getLinkHeader();
		if ( $linkHeader ) {
			$response->header( $linkHeader );
		}

		// Prevent framing, if requested
		$frameOptions = $this->getFrameOptions();
		if ( $frameOptions ) {
			$response->header( "X-Frame-Options: $frameOptions" );
		}

		$originTrials = $this->getOriginTrials();
		foreach ( $originTrials as $originTrial ) {
			$response->header( "Origin-Trial: $originTrial", false );
		}

		$reportTo = $this->getReportTo();
		if ( $reportTo ) {
			$response->header( "Report-To: $reportTo" );
		}

		$featurePolicyReportOnly = $this->getFeaturePolicyReportOnly();
		if ( $featurePolicyReportOnly ) {
			$response->header( "Feature-Policy-Report-Only: $featurePolicyReportOnly" );
		}

		if ( $this->mArticleBodyOnly ) {
			$this->CSP->sendHeaders();
			echo $this->mBodytext;
		} else {
			// Enable safe mode if requested (T152169)
			if ( $this->getRequest()->getBool( 'safemode' ) ) {
				$this->disallowUserJs();
			}

			$sk = $this->getSkin();
			$this->loadSkinModules( $sk );

			MWDebug::addModules( $this );

			// Hook that allows last minute changes to the output page, e.g.
			// adding of CSS or Javascript by extensions, adding CSP sources.
			$this->getHookRunner()->onBeforePageDisplay( $this, $sk );

			$this->CSP->sendHeaders();

			try {
				$sk->outputPage();
			} catch ( Exception $e ) {
				ob_end_clean(); // bug T129657
				throw $e;
			}
		}

		try {
			// This hook allows last minute changes to final overall output by modifying output buffer
			$this->getHookRunner()->onAfterFinalPageOutput( $this );
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
	 * @param string|Message|false $htmlTitle Will be passed directly to setHTMLTitle();
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
		$this->disableClientCache();
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
	 * @param PageReference|LinkTarget|string|null $returnto Page to show a return link to;
	 *   defaults to the 'returnto' URL parameter
	 * @param string|null $returntoquery Query string for the return to link;
	 *   defaults to the 'returntoquery' URL parameter
	 */
	public function showErrorPage(
		$title, $msg, $params = [], $returnto = null, $returntoquery = null
	) {
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

		$this->returnToMain( null, $returnto, $returntoquery );
	}

	/**
	 * Output a standard permission error page
	 *
	 * @param array $errors Error message keys or [key, param...] arrays
	 * @param string|null $action Action that was denied or null if unknown
	 */
	public function showPermissionsErrorPage( array $errors, $action = null ) {
		$services = MediaWikiServices::getInstance();
		$permissionManager = $services->getPermissionManager();
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
			&& ( $permissionManager->groupHasPermission( 'user', $action )
				|| $permissionManager->groupHasPermission( 'autoconfirmed', $action ) )
		) {
			$displayReturnto = null;

			# Due to T34276, if a user does not have read permissions,
			# $this->getTitle() will just give Special:Badtitle, which is
			# not especially useful as a returnto parameter. Use the title
			# from the request instead, if there was one.
			$request = $this->getRequest();
			$returnto = Title::newFromText( $request->getText( 'title' ) );
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

			$title = SpecialPage::getTitleFor( 'Userlogin' );
			$linkRenderer = $services->getLinkRenderer();
			$loginUrl = $title->getLinkURL( $query, false, PROTO_RELATIVE );
			$loginLink = $linkRenderer->makeKnownLink(
				$title,
				$this->msg( 'loginreqlink' )->text(),
				[],
				$query
			);

			$this->prepareErrorPage( $this->msg( 'loginreqtitle' ) );
			$this->addHTML( $this->msg( $msg )->rawParams( $loginLink )->params( $loginUrl )->parse() );

			# Don't return to a page the user can't read otherwise
			# we'll end up in a pointless loop
			if ( $displayReturnto && $this->getAuthority()->probablyCan( 'read', $displayReturnto ) ) {
				$this->returnToMain( null, $displayReturnto );
			}
		} else {
			$this->prepareErrorPage( $this->msg( 'permissionserrors' ) );
			$this->addWikiTextAsInterface( $this->formatPermissionsErrorMessage( $errors, $action ) );
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
	 * Format permission $status obtained from Authority for display.
	 *
	 * @param PermissionStatus $status
	 * @param string|null $action that was denied or null if unknown
	 * @return string
	 */
	public function formatPermissionStatus( PermissionStatus $status, string $action = null ): string {
		if ( $status->isGood() ) {
			return '';
		}
		return $this->formatPermissionsErrorMessage( $status->toLegacyErrorArray(), $action );
	}

	/**
	 * Format a list of error messages
	 *
	 * @deprecated since 1.36. Use ::formatPermissionStatus instead
	 * @param array $errors Array of arrays returned by PermissionManager::getPermissionErrors
	 * @param string|null $action Action that was denied or null if unknown
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
				$text .= $this->msg( ...$error )->plain();
				$text .= "</li>\n";
			}
			$text .= '</ul>';
		} else {
			$text .= "<div class=\"permissions-errors\">\n" .
					$this->msg( ...reset( $errors ) )->plain() .
					"\n</div>";
		}

		return $text;
	}

	/**
	 * Show a warning about replica DB lag
	 *
	 * If the lag is higher than $wgDatabaseReplicaLagCritical seconds,
	 * then the warning is a bit more obvious. If the lag is
	 * lower than $wgDatabaseReplicaLagWarning, then no warning is shown.
	 *
	 * @param int $lag Replica lag
	 */
	public function showLagWarning( $lag ) {
		$config = $this->getConfig();
		if ( $lag >= $config->get( MainConfigNames::DatabaseReplicaLagWarning ) ) {
			$lag = floor( $lag ); // floor to avoid nano seconds to display
			$message = $lag < $config->get( MainConfigNames::DatabaseReplicaLagCritical )
				? 'lag-warn-normal'
				: 'lag-warn-high';
			// For grep: mw-lag-warn-normal, mw-lag-warn-high
			$wrap = Html::rawElement( 'div', [ 'class' => "mw-{$message}" ], "\n$1\n" );
			$this->wrapWikiMsg( "$wrap\n", [ $message, $this->getLanguage()->formatNum( $lag ) ] );
		}
	}

	/**
	 * Output an error page
	 *
	 * @note FatalError exception class provides an alternative.
	 * @param string $message Error to output. Must be escaped for HTML.
	 */
	public function showFatalError( $message ) {
		$this->prepareErrorPage( $this->msg( 'internalerror' ) );

		$this->addHTML( $message );
	}

	/**
	 * Add a "return to" link pointing to a specified title
	 *
	 * @param LinkTarget $title Title to link
	 * @param array $query Query string parameters
	 * @param string|null $text Text of the link (input is not escaped)
	 * @param array $options Options array to pass to Linker
	 */
	public function addReturnTo( $title, array $query = [], $text = null, $options = [] ) {
		$linkRenderer = MediaWikiServices::getInstance()
			->getLinkRendererFactory()->createFromLegacyOptions( $options );
		$link = $this->msg( 'returnto' )->rawParams(
			$linkRenderer->makeLink( $title, $text, [], $query ) )->escaped();
		$this->addHTML( "<p id=\"mw-returnto\">{$link}</p>\n" );
	}

	/**
	 * Add a "return to" link pointing to a specified title,
	 * or the title indicated in the request, or else the main page
	 *
	 * @param mixed|null $unused
	 * @param PageReference|LinkTarget|string|null $returnto Page to return to
	 * @param string|null $returntoquery Query string for the return to link
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
			$linkTarget = TitleValue::castPageToLinkTarget( $returnto );
		} else {
			$linkTarget = Title::newFromText( $returnto );
		}

		// We don't want people to return to external interwiki. That
		// might potentially be used as part of a phishing scheme
		if ( !is_object( $linkTarget ) || $linkTarget->isExternal() ) {
			$linkTarget = Title::newMainPage();
		}

		$this->addReturnTo( $linkTarget, wfCgiToArray( $returntoquery ) );
	}

	private function getRlClientContext() {
		if ( !$this->rlClientContext ) {
			$query = ResourceLoader::makeLoaderQuery(
				[], // modules; not relevant
				$this->getLanguage()->getCode(),
				$this->getSkin()->getSkinName(),
				$this->getUser()->isRegistered() ? $this->getUser()->getName() : null,
				null, // version; not relevant
				ResourceLoader::inDebugMode(),
				null, // only; not relevant
				$this->isPrintable()
			);
			$this->rlClientContext = new RL\Context(
				$this->getResourceLoader(),
				new FauxRequest( $query )
			);
			if ( $this->contentOverrideCallbacks ) {
				$this->rlClientContext = new RL\DerivativeContext( $this->rlClientContext );
				$this->rlClientContext->setContentOverrideCallback( function ( $title ) {
					foreach ( $this->contentOverrideCallbacks as $callback ) {
						$content = $callback( $title );
						if ( $content !== null ) {
							$text = ( $content instanceof TextContent ) ? $content->getText() : '';
							if ( strpos( $text, '</script>' ) !== false ) {
								// Proactively replace this so that we can display a message
								// to the user, instead of letting it go to Html::inlineScript(),
								// where it would be considered a server-side issue.
								$content = new JavaScriptContent(
									Xml::encodeJsCall( 'mw.log.error', [
										"Cannot preview $title due to script-closing tag."
									] )
								);
							}
							return $content;
						}
					}
					return null;
				} );
			}
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
	 * @return RL\ClientHtml
	 */
	public function getRlClient() {
		if ( !$this->rlClient ) {
			$context = $this->getRlClientContext();
			$rl = $this->getResourceLoader();
			$this->addModules( [
				'user',
				'user.options',
			] );
			$this->addModuleStyles( [
				'site.styles',
				'noscript',
				'user.styles',
			] );

			// Prepare exempt modules for buildExemptModules()
			$exemptGroups = [
				RL\Module::GROUP_SITE => [],
				RL\Module::GROUP_NOSCRIPT => [],
				RL\Module::GROUP_PRIVATE => [],
				RL\Module::GROUP_USER => []
			];
			$exemptStates = [];
			$moduleStyles = $this->getModuleStyles( /*filter*/ true );

			// Preload getTitleInfo for isKnownEmpty calls below and in RL\ClientHtml
			// Separate user-specific batch for improved cache-hit ratio.
			$userBatch = [ 'user.styles', 'user' ];
			$siteBatch = array_diff( $moduleStyles, $userBatch );
			$dbr = wfGetDB( DB_REPLICA );
			RL\WikiModule::preloadTitleInfo( $context, $dbr, $siteBatch );
			RL\WikiModule::preloadTitleInfo( $context, $dbr, $userBatch );

			// Filter out modules handled by buildExemptModules()
			$moduleStyles = array_filter( $moduleStyles,
				static function ( $name ) use ( $rl, $context, &$exemptGroups, &$exemptStates ) {
					$module = $rl->getModule( $name );
					if ( $module ) {
						$group = $module->getGroup();
						if ( $group !== null && isset( $exemptGroups[$group] ) ) {
							// The `noscript` module is excluded from the client
							// side registry, no need to set its state either.
							// But we still output it. See T291735
							if ( $group !== RL\Module::GROUP_NOSCRIPT ) {
								$exemptStates[$name] = 'ready';
							}
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

			$rlClient = new RL\ClientHtml( $context, [
				'target' => $this->getTarget(),
				'nonce' => $this->CSP->getNonce(),
				// When 'safemode', disallowUserJs(), or reduceAllowedModules() is used
				// to only restrict modules to ORIGIN_CORE (ie. disallow ORIGIN_USER), the list of
				// modules enqueued for loading on this page is filtered to just those.
				// However, to make sure we also apply the restriction to dynamic dependencies and
				// lazy-loaded modules at run-time on the client-side, pass 'safemode' down to the
				// StartupModule so that the client-side registry will not contain any restricted
				// modules either. (T152169, T185303)
				'safemode' => ( $this->getAllowedModules( RL\Module::TYPE_COMBINED )
					<= RL\Module::ORIGIN_CORE_INDIVIDUAL
				) ? '1' : null,
			] );
			$rlClient->setConfig( $this->getJSVars() );
			$rlClient->setModules( $this->getModules( /*filter*/ true ) );
			$rlClient->setModuleStyles( $moduleStyles );
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
		$config = $this->getConfig();
		$userdir = $this->getLanguage()->getDir();
		$services = MediaWikiServices::getInstance();
		$sitedir = $services->getContentLanguage()->getDir();

		$pieces = [];
		$htmlAttribs = Sanitizer::mergeAttributes( Sanitizer::mergeAttributes(
			$this->getRlClient()->getDocumentAttributes(),
			$sk->getHtmlElementAttributes()
		), [ 'class' => implode( ' ', $this->mAdditionalHtmlClasses ) ] );
		$pieces[] = Html::htmlHeader( $htmlAttribs );
		$pieces[] = Html::openElement( 'head' );

		if ( $this->getHTMLTitle() == '' ) {
			$this->setHTMLTitle( $this->msg( 'pagetitle', $this->getPageTitle() )->inContentLanguage() );
		}

		if ( !Html::isXmlMimeType( $config->get( MainConfigNames::MimeType ) ) ) {
			// Add <meta charset="UTF-8">
			// This should be before <title> since it defines the charset used by
			// text including the text inside <title>.
			// The spec recommends defining XHTML5's charset using the XML declaration
			// instead of meta.
			// Our XML declaration is output by Html::htmlHeader.
			// https://html.spec.whatwg.org/multipage/semantics.html#attr-meta-http-equiv-content-type
			// https://html.spec.whatwg.org/multipage/semantics.html#charset
			$pieces[] = Html::element( 'meta', [ 'charset' => 'UTF-8' ] );
		}

		$pieces[] = Html::element( 'title', [], $this->getHTMLTitle() );
		$pieces[] = $this->getRlClient()->getHeadHtml( $htmlAttribs['class'] ?? null );
		$pieces[] = $this->buildExemptModules();
		$pieces = array_merge( $pieces, array_values( $this->getHeadLinksArray() ) );
		$pieces = array_merge( $pieces, array_values( $this->mHeadItems ) );

		$pieces[] = Html::closeElement( 'head' );

		$skinOptions = $sk->getOptions();
		$bodyClasses = array_merge( $this->mAdditionalBodyClasses, $skinOptions['bodyClasses'] );
		$bodyClasses[] = 'mediawiki';

		# Classes for LTR/RTL directionality support
		$bodyClasses[] = $userdir;
		$bodyClasses[] = "sitedir-$sitedir";

		$underline = $services->getUserOptionsLookup()->getOption( $this->getUser(), 'underline' );
		if ( $underline < 2 ) {
			// The following classes can be used here:
			// * mw-underline-always
			// * mw-underline-never
			$bodyClasses[] = 'mw-underline-' . ( $underline ? 'always' : 'never' );
		}

		// Parser feature migration class
		// The idea is that this will eventually be removed, after the wikitext
		// which requires it is cleaned up.
		$bodyClasses[] = 'mw-hide-empty-elt';

		$bodyClasses[] = $sk->getPageClasses( $this->getTitle() );
		$bodyClasses[] = 'skin-' . Sanitizer::escapeClass( $sk->getSkinName() );
		$bodyClasses[] =
			'action-' . Sanitizer::escapeClass( $this->getContext()->getActionName() );

		if ( $sk->isResponsive() ) {
			$bodyClasses[] = 'skin--responsive';
		}

		$bodyAttrs = [];
		// While the implode() is not strictly needed, it's used for backwards compatibility
		// (this used to be built as a string and hooks likely still expect that).
		$bodyAttrs['class'] = implode( ' ', $bodyClasses );

		// Allow skins and extensions to add body attributes they need
		// Get ones from deprecated method
		if ( method_exists( $sk, 'addToBodyAttributes' ) ) {
			/** @phan-suppress-next-line PhanUndeclaredMethod */
			$sk->addToBodyAttributes( $this, $bodyAttrs );
			wfDeprecated( 'Skin::addToBodyAttributes method to add body attributes', '1.35' );
		}

		// Then run the hook, the recommended way of adding body attributes now
		$this->getHookRunner()->onOutputPageBodyAttributes( $this, $sk, $bodyAttrs );

		$pieces[] = Html::openElement( 'body', $bodyAttrs );

		return self::combineWrappedStrings( $pieces );
	}

	/**
	 * Get a ResourceLoader object associated with this OutputPage
	 *
	 * @return ResourceLoader
	 */
	public function getResourceLoader() {
		if ( $this->mResourceLoader === null ) {
			// Lazy-initialise as needed
			$this->mResourceLoader = MediaWikiServices::getInstance()->getResourceLoader();
		}
		return $this->mResourceLoader;
	}

	/**
	 * Explicitly load or embed modules on a page.
	 *
	 * @param array|string $modules One or more module names
	 * @param string $only RL\Module TYPE_ class constant
	 * @param array $extraQuery [optional] Array with extra query parameters for the request
	 * @return string|WrappedStringList HTML
	 */
	public function makeResourceLoaderLink( $modules, $only, array $extraQuery = [] ) {
		// Apply 'target' and 'origin' filters
		$modules = $this->filterModules( (array)$modules, null, $only );

		return RL\ClientHtml::makeLoad(
			$this->getRlClientContext(),
			$modules,
			$only,
			$extraQuery,
			$this->CSP->getNonce()
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

	/**
	 * JS stuff to put at the bottom of the `<body>`.
	 * These are legacy scripts ($this->mScripts), and user JS.
	 *
	 * @param string $extraHtml (only for use by this->tailElement(); will be removed in future)
	 * @return string|WrappedStringList HTML
	 */
	public function getBottomScripts( $extraHtml = '' ) {
		$chunks = [];
		$chunks[] = $this->getRlClient()->getBodyHtml();

		// Legacy non-ResourceLoader scripts
		$chunks[] = $this->mScripts;

		if ( $this->limitReportJSData ) {
			$chunks[] = ResourceLoader::makeInlineScript(
				ResourceLoader::makeConfigSetScript(
					[ 'wgPageParseReport' => $this->limitReportJSData ]
				),
				$this->CSP->getNonce()
			);
		}
		// Keep the hook appendage separate to preserve WrappedString objects.
		// This enables BaseTemplate::getTrail() to merge them where possible.
		$this->getHookRunner()->onSkinAfterBottomScripts( $this->getSkin(), $extraHtml );
		$chunks = [ self::combineWrappedStrings( $chunks ) ];
		if ( $extraHtml !== '' ) {
			$chunks[] = $extraHtml;
		}

		return WrappedString::join( "\n", $chunks );
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
	 * @param mixed|null $value [optional] Value of the configuration variable
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
	 * Do not add things here which can be evaluated in RL\StartUpModule
	 * - in other words, page-independent/site-wide variables (without state).
	 * You will only be adding bloat to the html page and causing page caches to
	 * have to be purged on configuration changes.
	 * @return array
	 */
	public function getJSVars() {
		$curRevisionId = 0;
		$articleId = 0;
		$canonicalSpecialPageName = false; # T23115
		$services = MediaWikiServices::getInstance();

		$title = $this->getTitle();
		$ns = $title->getNamespace();
		$nsInfo = $services->getNamespaceInfo();
		$canonicalNamespace = $nsInfo->exists( $ns )
			? $nsInfo->getCanonicalName( $ns )
			: $title->getNsText();

		$sk = $this->getSkin();
		// Get the relevant title so that AJAX features can use the correct page name
		// when making API requests from certain special pages (T36972).
		$relevantTitle = $sk->getRelevantTitle();

		if ( $ns === NS_SPECIAL ) {
			[ $canonicalSpecialPageName, /*...*/ ] =
				$services->getSpecialPageFactory()->
					resolveAlias( $title->getDBkey() );
		} elseif ( $this->canUseWikiPage() ) {
			$wikiPage = $this->getWikiPage();
			$curRevisionId = $wikiPage->getLatest();
			$articleId = $wikiPage->getId();
		}

		$lang = $title->getPageViewLanguage();

		// Pre-process information
		$separatorTransTable = $lang->separatorTransformTable();
		$separatorTransTable = $separatorTransTable ?: [];
		$compactSeparatorTransTable = [
			implode( "\t", array_keys( $separatorTransTable ) ),
			implode( "\t", $separatorTransTable ),
		];
		$digitTransTable = $lang->digitTransformTable();
		$digitTransTable = $digitTransTable ?: [];
		$compactDigitTransTable = [
			implode( "\t", array_keys( $digitTransTable ) ),
			implode( "\t", $digitTransTable ),
		];

		$user = $this->getUser();

		// Internal variables for MediaWiki core
		$vars = [
			// @internal For mediawiki.page.ready
			'wgBreakFrames' => $this->getFrameOptions() == 'DENY',

			// @internal For jquery.tablesorter
			'wgSeparatorTransformTable' => $compactSeparatorTransTable,
			'wgDigitTransformTable' => $compactDigitTransTable,
			'wgDefaultDateFormat' => $lang->getDefaultDateFormat(),
			'wgMonthNames' => $lang->getMonthNamesArray(),

			// @internal For debugging purposes
			'wgRequestId' => WebRequest::getRequestId(),

			// @internal For mw.loader
			'wgCSPNonce' => $this->CSP->getNonce(),
		];

		// Start of supported and stable config vars (for use by extensions/gadgets).
		$vars += [
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
			'wgAction' => $this->getContext()->getActionName(),
			'wgUserName' => $user->isAnon() ? null : $user->getName(),
			'wgUserGroups' => $services->getUserGroupManager()->getUserEffectiveGroups( $user ),
			'wgCategories' => $this->getCategories(),
			'wgPageContentLanguage' => $lang->getCode(),
			'wgPageContentModel' => $title->getContentModel(),
			'wgRelevantPageName' => $relevantTitle->getPrefixedDBkey(),
			'wgRelevantArticleId' => $relevantTitle->getArticleID(),
		];
		if ( $user->isRegistered() ) {
			$vars['wgUserId'] = $user->getId();
			$vars['wgUserEditCount'] = $user->getEditCount();
			$userReg = $user->getRegistration();
			$vars['wgUserRegistration'] = $userReg ? (int)wfTimestamp( TS_UNIX, $userReg ) * 1000 : null;
			// Get the revision ID of the oldest new message on the user's talk
			// page. This can be used for constructing new message alerts on
			// the client side.
			$userNewMsgRevId = $this->getLastSeenUserTalkRevId();
			// Only occupy precious space in the <head> when it is non-null (T53640)
			// mw.config.get returns null by default.
			if ( $userNewMsgRevId ) {
				$vars['wgUserNewMsgRevisionId'] = $userNewMsgRevId;
			}
		}
		$languageConverter = $services->getLanguageConverterFactory()
			->getLanguageConverter( $title->getPageLanguage() );
		if ( $languageConverter->hasVariants() ) {
			$vars['wgUserVariant'] = $languageConverter->getPreferredVariant();
		}
		// Same test as SkinTemplate
		$vars['wgIsProbablyEditable'] = $this->getAuthority()->probablyCan( 'edit', $title );
		$vars['wgRelevantPageIsProbablyEditable'] = $relevantTitle &&
			$this->getAuthority()->probablyCan( 'edit', $relevantTitle );
		$restrictionStore = $services->getRestrictionStore();
		foreach ( $restrictionStore->listApplicableRestrictionTypes( $title ) as $type ) {
			// Following keys are set in $vars:
			// wgRestrictionCreate, wgRestrictionEdit, wgRestrictionMove, wgRestrictionUpload
			$vars['wgRestriction' . ucfirst( $type )] = $restrictionStore->getRestrictions( $title, $type );
		}
		if ( $title->isMainPage() ) {
			$vars['wgIsMainPage'] = true;
		}

		$relevantUser = $sk->getRelevantUser();
		if ( $relevantUser ) {
			$vars['wgRelevantUserName'] = $relevantUser->getName();
		}
		// End of stable config vars

		$titleFormatter = $services->getTitleFormatter();

		if ( $this->mRedirectedFrom ) {
			// @internal For skin JS
			$vars['wgRedirectedFrom'] = $titleFormatter->getPrefixedDBkey( $this->mRedirectedFrom );
		}

		// Allow extensions to add their custom variables to the mw.config map.
		// Use the 'ResourceLoaderGetConfigVars' hook if the variable is not
		// page-dependent but site-wide (without state).
		// Alternatively, you may want to use OutputPage->addJsConfigVars() instead.
		$this->getHookRunner()->onMakeGlobalVariablesScript( $vars, $this );

		// Merge in variables from addJsConfigVars last
		return array_merge( $vars, $this->getJsConfigVars() );
	}

	/**
	 * Get the revision ID for the last user talk page revision viewed by the talk page owner.
	 *
	 * @return int|null
	 */
	private function getLastSeenUserTalkRevId() {
		$services = MediaWikiServices::getInstance();
		$user = $this->getUser();
		$userHasNewMessages = $services
			->getTalkPageNotificationManager()
			->userHasNewMessages( $user );
		if ( !$userHasNewMessages ) {
			return null;
		}

		$timestamp = $services
			->getTalkPageNotificationManager()
			->getLatestSeenMessageTimestamp( $user );

		if ( !$timestamp ) {
			return null;
		}

		$revRecord = $services->getRevisionLookup()->getRevisionByTimestamp(
			$user->getTalkPage(),
			$timestamp
		);

		if ( !$revRecord ) {
			return null;
		}

		return $revRecord->getId();
	}

	/**
	 * To make it harder for someone to slip a user a fake
	 * JavaScript or CSS preview, a random token
	 * is associated with the login session. If it's not
	 * passed back with the preview request, we won't render
	 * the code.
	 *
	 * @return bool
	 */
	public function userCanPreview() {
		$request = $this->getRequest();
		if (
			$request->getRawVal( 'action' ) !== 'submit' ||
			!$request->wasPosted()
		) {
			return false;
		}

		$user = $this->getUser();

		if ( !$user->isRegistered() ) {
			// Anons have predictable edit tokens
			return false;
		}
		if ( !$user->matchEditToken( $request->getVal( 'wpEditToken' ) ) ) {
			return false;
		}

		$title = $this->getTitle();
		if ( !$this->getAuthority()->probablyCan( 'edit', $title ) ) {
			return false;
		}

		return true;
	}

	/**
	 * @return array Array in format "link name or number => 'link html'".
	 */
	public function getHeadLinksArray() {
		$tags = [];
		$config = $this->getConfig();

		$canonicalUrl = $this->mCanonicalUrl;

		$tags['meta-generator'] = Html::element( 'meta', [
			'name' => 'generator',
			'content' => 'MediaWiki ' . MW_VERSION,
		] );

		if ( $config->get( MainConfigNames::ReferrerPolicy ) !== false ) {
			// Per https://w3c.github.io/webappsec-referrer-policy/#unknown-policy-values
			// fallbacks should come before the primary value so we need to reverse the array.
			foreach ( array_reverse( (array)$config->get( MainConfigNames::ReferrerPolicy ) ) as $i => $policy ) {
				$tags["meta-referrer-$i"] = Html::element( 'meta', [
					'name' => 'referrer',
					'content' => $policy,
				] );
			}
		}

		$p = $this->getRobotsContent();
		if ( $p ) {
			// http://www.robotstxt.org/wc/meta-user.html
			// Only show if it's different from the default robots policy
			$tags['meta-robots'] = Html::element( 'meta', [
				'name' => 'robots',
				'content' => $p,
			] );
		}

		# Browser based phonenumber detection
		if ( $config->get( MainConfigNames::BrowserFormatDetection ) !== false ) {
			$tags['meta-format-detection'] = Html::element( 'meta', [
				'name' => 'format-detection',
				'content' => $config->get( MainConfigNames::BrowserFormatDetection ),
			] );
		}

		foreach ( $this->mMetatags as $tag ) {
			if ( strncasecmp( $tag[0], 'http:', 5 ) === 0 ) {
				$a = 'http-equiv';
				$tag[0] = substr( $tag[0], 5 );
			} elseif ( strncasecmp( $tag[0], 'og:', 3 ) === 0 ) {
				$a = 'property';
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

		if ( $config->get( MainConfigNames::UniversalEditButton ) && $this->isArticleRelated() ) {
			if ( $this->getAuthority()->probablyCan( 'edit', $this->getTitle() ) ) {
				$msg = $this->msg( 'edit' )->text();
				// Use mime type per https://phabricator.wikimedia.org/T21165#6946526
				$tags['universal-edit-button'] = Html::element( 'link', [
					'rel' => 'alternate',
					'type' => 'application/x-wiki',
					'title' => $msg,
					'href' => $this->getTitle()->getEditURL(),
				] );
			}
		}

		# Generally the order of the favicon and apple-touch-icon links
		# should not matter, but Konqueror (3.5.9 at least) incorrectly
		# uses whichever one appears later in the HTML source. Make sure
		# apple-touch-icon is specified first to avoid this.
		if ( $config->get( MainConfigNames::AppleTouchIcon ) !== false ) {
			$tags['apple-touch-icon'] = Html::element( 'link', [
				'rel' => 'apple-touch-icon',
				'href' => $config->get( MainConfigNames::AppleTouchIcon )
			] );
		}

		if ( $config->get( MainConfigNames::Favicon ) !== false ) {
			$tags['favicon'] = Html::element( 'link', [
				'rel' => 'icon',
				'href' => $config->get( MainConfigNames::Favicon )
			] );
		}

		# OpenSearch description link
		$tags['opensearch'] = Html::element( 'link', [
			'rel' => 'search',
			'type' => 'application/opensearchdescription+xml',
			'href' => wfScript( 'opensearch_desc' ),
			'title' => $this->msg( 'opensearch-desc' )->inContentLanguage()->text(),
		] );

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

		# Language variants
		$services = MediaWikiServices::getInstance();
		$languageConverterFactory = $services->getLanguageConverterFactory();
		$disableLangConversion = $languageConverterFactory->isConversionDisabled();
		if ( !$disableLangConversion ) {
			$lang = $this->getTitle()->getPageLanguage();
			$languageConverter = $languageConverterFactory->getLanguageConverter( $lang );
			if ( $languageConverter->hasVariants() ) {
				$variants = $languageConverter->getVariants();
				foreach ( $variants as $variant ) {
					$tags["variant-$variant"] = Html::element( 'link', [
						'rel' => 'alternate',
						'hreflang' => LanguageCode::bcp47( $variant ),
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
			if ( $config->get( MainConfigNames::RightsPage ) ) {
				$copy = Title::newFromText( $config->get( MainConfigNames::RightsPage ) );

				if ( $copy ) {
					$copyright = $copy->getLocalURL();
				}
			}

			if ( !$copyright && $config->get( MainConfigNames::RightsUrl ) ) {
				$copyright = $config->get( MainConfigNames::RightsUrl );
			}
		}

		if ( $copyright ) {
			$tags['copyright'] = Html::element( 'link', [
				'rel' => 'license',
				'href' => $copyright ]
			);
		}

		# Feeds
		if ( $config->get( MainConfigNames::Feed ) ) {
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
			$sitename = $config->get( MainConfigNames::Sitename );
			$overrideSiteFeed = $config->get( MainConfigNames::OverrideSiteFeed );
			if ( $overrideSiteFeed ) {
				foreach ( $overrideSiteFeed as $type => $feedUrl ) {
					// Note, this->feedLink escapes the url.
					$feedLinks[] = $this->feedLink(
						$type,
						$feedUrl,
						$this->msg( "site-{$type}-feed", $sitename )->text()
					);
				}
			} elseif ( !$this->getTitle()->isSpecial( 'Recentchanges' ) ) {
				$rctitle = SpecialPage::getTitleFor( 'Recentchanges' );
				foreach ( $this->getAdvertisedFeedTypes() as $format ) {
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
			$this->getHookRunner()->onAfterBuildFeedLinks( $feedLinks );

			$tags += $feedLinks;
		}

		# Canonical URL
		if ( $config->get( MainConfigNames::EnableCanonicalServerLink ) ) {
			if ( $canonicalUrl !== false ) {
				$canonicalUrl = wfExpandUrl( $canonicalUrl, PROTO_CANONICAL );
			} elseif ( $this->isArticleRelated() ) {
				// This affects all requests where "setArticleRelated" is true. This is
				// typically all requests that show content (query title, curid, oldid, diff),
				// and all wikipage actions (edit, delete, purge, info, history etc.).
				// It does not apply to File pages and Special pages.
				//
				// 'history' and 'info' actions address page metadata rather than the page
				// content itself, so they may not be canonicalized to the view page url.
				//
				// TODO: this logic should be owned by Action subclasses.
				$action = $this->getContext()->getActionName();
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
		if ( $canonicalUrl !== false ) {
			$tags[] = Html::element( 'link', [
				'rel' => 'canonical',
				'href' => $canonicalUrl
			] );
		}

		// Allow extensions to add, remove and/or otherwise manipulate these links
		// If you want only to *add* <head> links, please use the addHeadItem()
		// (or addHeadItems() for multiple items) method instead.
		// This hook is provided as a last resort for extensions to modify these
		// links before the output is sent to client.
		$this->getHookRunner()->onOutputPageAfterGetHeadLinksArray( $tags, $this );

		return $tags;
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
		$chunks = [];

		// Requirements:
		// - Within modules provided by the software (core, skin, extensions),
		//   styles from skin stylesheets should be overridden by styles
		//   from modules dynamically loaded with JavaScript.
		// - Styles from site-specific, private, and user modules should override
		//   both of the above.
		//
		// The effective order for stylesheets must thus be:
		// 1. Page style modules, formatted server-side by RL\ClientHtml.
		// 2. Dynamically-loaded styles, inserted client-side by mw.loader.
		// 3. Styles that are site-specific, private or from the user, formatted
		//    server-side by this function.
		//
		// The 'ResourceLoaderDynamicStyles' marker helps JavaScript know where
		// point #2 is.

		// Add legacy styles added through addStyle()/addInlineStyle() here
		$chunks[] = implode( '', $this->buildCssLinksArray() ) . $this->mInlineStyles;

		// Things that go after the ResourceLoaderDynamicStyles marker
		$append = [];
		$separateReq = [ 'site.styles', 'user.styles' ];
		foreach ( $this->rlExemptStyleModules as $group => $moduleNames ) {
			if ( $moduleNames ) {
				$append[] = $this->makeResourceLoaderLink(
					array_diff( $moduleNames, $separateReq ),
					RL\Module::TYPE_STYLES
				);

				foreach ( array_intersect( $moduleNames, $separateReq ) as $name ) {
					// These require their own dedicated request in order to support "@import"
					// syntax, which is incompatible with concatenation. (T147667, T37562)
					$append[] = $this->makeResourceLoaderLink( $name,
						RL\Module::TYPE_STYLES
					);
				}
			}
		}
		if ( $append ) {
			$chunks[] = Html::element(
				'meta',
				[ 'name' => 'ResourceLoaderDynamicStyles', 'content' => '' ]
			);
			$chunks = array_merge( $chunks, $append );
		}

		return self::combineWrappedStrings( $chunks );
	}

	/**
	 * @return array
	 */
	public function buildCssLinksArray() {
		$links = [];

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
		if ( isset( $options['dir'] ) && $this->getLanguage()->getDir() != $options['dir'] ) {
			return '';
		}

		if ( isset( $options['media'] ) ) {
			$media = self::transformCssMedia( $options['media'] );
			if ( $media === null ) {
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
			// Append file hash as query parameter
			$url = self::transformResourcePath(
				$config,
				$config->get( MainConfigNames::StylePath ) . '/' . $style
			);
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
		$localDir = $config->get( MainConfigNames::BaseDirectory );
		$remotePathPrefix = $config->get( MainConfigNames::ResourceBasePath );
		if ( $remotePathPrefix === '' ) {
			// The configured base path is required to be empty string for
			// wikis in the domain root
			$remotePath = '/';
		} else {
			$remotePath = $remotePathPrefix;
		}
		if ( strpos( $path, $remotePath ) !== 0 || substr( $path, 0, 2 ) === '//' ) {
			// - Path is outside wgResourceBasePath, ignore.
			// - Path is protocol-relative. Fixes T155310. Not supported by RelPath lib.
			return $path;
		}
		// For files in resources, extensions/ or skins/, ResourceBasePath is preferred here.
		// For other misc files in $IP, we'll fallback to that as well. There is, however, a fourth
		// supported dir/path pair in the configuration (wgUploadDirectory, wgUploadPath)
		// which is not expected to be in wgResourceBasePath on CDNs. (T155146)
		$uploadPath = $config->get( MainConfigNames::UploadPath );
		if ( strpos( $path, $uploadPath ) === 0 ) {
			$localDir = $config->get( MainConfigNames::UploadDirectory );
			$remotePathPrefix = $remotePath = $uploadPath;
		}

		$path = RelPath::getRelativePath( $path, $remotePath );
		return self::transformFilePath( $remotePathPrefix, $localDir, $path );
	}

	/**
	 * Utility method for transformResourceFilePath().
	 *
	 * Caller is responsible for ensuring the file exists. Emits a PHP warning otherwise.
	 *
	 * @since 1.27
	 * @param string $remotePathPrefix URL path prefix that points to $localPath
	 * @param string $localPath File directory exposed at $remotePath
	 * @param string $file Path to target file relative to $localPath
	 * @return string URL
	 */
	public static function transformFilePath( $remotePathPrefix, $localPath, $file ) {
		// This MUST match the equivalent logic in CSSMin::remapOne()
		$localFile = "$localPath/$file";
		$url = "$remotePathPrefix/$file";
		if ( is_file( $localFile ) ) {
			$hash = md5_file( $localFile );
			if ( $hash === false ) {
				wfLogWarning( __METHOD__ . ": Failed to hash $localFile" );
				$hash = '';
			}
			$url .= '?' . substr( $hash, 0, 5 );
		}
		return $url;
	}

	/**
	 * Transform "media" attribute based on request parameters
	 *
	 * @param string $media Current value of the "media" attribute
	 * @return string|null Modified value of the "media" attribute, or null to disable
	 * this stylesheet
	 */
	public static function transformCssMedia( $media ) {
		global $wgRequest;

		if ( $wgRequest->getBool( 'printable' ) ) {
			// When browsing with printable=yes, apply "print" media styles
			// as if they are screen styles (no media, media="").
			if ( $media === 'print' ) {
				return '';
			}

			// https://www.w3.org/TR/css3-mediaqueries/#syntax
			//
			// This regex will not attempt to understand a comma-separated media_query_list
			// Example supported values for $media:
			//
			//     'screen', 'only screen', 'screen and (min-width: 982px)' ),
			//
			// Example NOT supported value for $media:
			//
			//     '3d-glasses, screen, print and resolution > 90dpi'
			//
			// If it's a "printable" request, we disable all screen stylesheets.
			$screenMediaQueryRegex = '/^(?:only\s+)?screen\b/i';
			if ( preg_match( $screenMediaQueryRegex, $media ) === 1 ) {
				return null;
			}
		}

		return $media;
	}

	/**
	 * Add a wikitext-formatted message to the output.
	 * This is equivalent to:
	 *
	 *    $wgOut->addWikiText( wfMessage( ... )->plain() )
	 *
	 * @param mixed ...$args
	 */
	public function addWikiMsg( ...$args ) {
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
	 *     $wgOut->wrapWikiMsg( "<div class='customclass'>\n$1\n</div>", 'some-msg-key' );
	 *
	 * Is equivalent to:
	 *
	 *     $wgOut->addWikiTextAsInterface( "<div class='customclass'>\n"
	 *         . wfMessage( 'some-msg-key' )->plain() . "\n</div>" );
	 *
	 * The newline after the opening div is needed in some wikitext. See T21226.
	 *
	 * @param string $wrap
	 * @param mixed ...$msgSpecs
	 */
	public function wrapWikiMsg( $wrap, ...$msgSpecs ) {
		$s = $wrap;
		foreach ( $msgSpecs as $n => $spec ) {
			if ( is_array( $spec ) ) {
				$args = $spec;
				$name = array_shift( $args );
			} else {
				$args = [];
				$name = $spec;
			}
			$s = str_replace( '$' . ( $n + 1 ), $this->msg( $name, $args )->plain(), $s );
		}
		$this->addWikiTextAsInterface( $s );
	}

	/**
	 * Whether the output has a table of contents
	 * @return bool
	 * @since 1.22
	 */
	public function isTOCEnabled() {
		return $this->mEnableTOC;
	}

	/**
	 * Helper function to setup the PHP implementation of OOUI to use in this request.
	 *
	 * @since 1.26
	 * @param string $skinName The Skin name to determine the correct OOUI theme
	 * @param string $dir Language direction
	 */
	public static function setupOOUI( $skinName = 'default', $dir = 'ltr' ) {
		$themes = RL\OOUIFileModule::getSkinThemeMap();
		$theme = $themes[$skinName] ?? $themes['default'];
		// For example, 'OOUI\WikimediaUITheme'.
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
			'oojs-ui.styles.indicators',
			'mediawiki.widgets.styles',
			'oojs-ui-core.icons',
		] );
	}

	/**
	 * Get (and set if not yet set) the CSP nonce.
	 *
	 * This value needs to be included in any <script> tags on the
	 * page.
	 *
	 * @return string|false Nonce or false to mean don't output nonce
	 * @since 1.32
	 * @deprecated Since 1.35 use getCSP()->getNonce() instead
	 */
	public function getCSPNonce() {
		return $this->CSP->getNonce();
	}

	/**
	 * Get the ContentSecurityPolicy object
	 *
	 * @since 1.35
	 * @return ContentSecurityPolicy
	 */
	public function getCSP() {
		return $this->CSP;
	}

	/**
	 * The final bits that go to the bottom of a page
	 * HTML document including the closing tags
	 *
	 * @internal
	 * @since 1.37
	 * @param Skin $skin
	 * @return string
	 */
	public function tailElement( $skin ) {
		// T257704: Temporarily run skin hook here pending
		// creation dedicated outputpage hook for this
		$extraHtml = '';
		$this->getHookRunner()->onSkinAfterBottomScripts( $skin, $extraHtml );

		$tail = [
			MWDebug::getDebugHTML( $skin ),
			$this->getBottomScripts( $extraHtml ),
			wfReportTime( $this->getCSP()->getNonce() ),
			MWDebug::getHTMLDebugLog(),
			Html::closeElement( 'body' ),
			Html::closeElement( 'html' ),
		];

		return WrappedStringList::join( "\n", $tail );
	}
}
