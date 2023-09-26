<?php
/**
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

namespace MediaWiki\Skin;

use MediaWiki\Context\ContextSource;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Html\Html;
use MediaWiki\Language\Language;
use MediaWiki\Language\LanguageCode;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\OutputPage;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\ResourceLoader as RL;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Specials\SpecialUserRights;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\WikiMap\WikiMap;
use UploadBase;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @defgroup Skins Skins
 */

/**
 * The base class for all skins.
 *
 * See docs/Skin.md for more information.
 *
 * @stable to extend
 * @ingroup Skins
 */
abstract class Skin extends ContextSource {
	use ProtectedHookAccessorTrait;

	/**
	 * @var array link options used in Skin::makeLink. Can be set by skin option `link`.
	 */
	private $defaultLinkOptions;

	/**
	 * @var string|null
	 */
	protected $skinname = null;

	/**
	 * @var array Skin options passed into constructor
	 */
	protected $options = [];
	/** @var Title|null */
	protected $mRelevantTitle = null;

	/**
	 * @var UserIdentity|null|false
	 */
	private $mRelevantUser = false;

	/** The current major version of the skin specification. */
	protected const VERSION_MAJOR = 1;

	/** @var array|null Cache for getLanguages() */
	private $languageLinks;

	/** @var array|null Cache for buildSidebar() */
	private $sidebar;

	/**
	 * @var SkinComponentRegistry Initialised in constructor.
	 */
	private $componentRegistry = null;

	/**
	 * Get the current major version of Skin. This is used to manage changes
	 * to underlying data and for providing support for older and new versions of code.
	 *
	 * @since 1.36
	 * @return int
	 */
	public static function getVersion() {
		return self::VERSION_MAJOR;
	}

	/**
	 * @internal use in Skin.php, SkinTemplate.php or SkinMustache.php
	 * @param string $name
	 * @return SkinComponent
	 */
	final protected function getComponent( string $name ): SkinComponent {
		return $this->componentRegistry->getComponent( $name );
	}

	/**
	 * @stable to extend. Subclasses may extend this method to add additional
	 * template data.
	 * @internal this method should never be called outside Skin and its subclasses
	 * as it can be computationally expensive and typically has side effects on the Skin
	 * instance, through execution of hooks.
	 *
	 * The data keys should be valid English words. Compound words should
	 * be hyphenated except if they are normally written as one word. Each
	 * key should be prefixed with a type hint, this may be enforced by the
	 * class PHPUnit test.
	 *
	 * Plain strings are prefixed with 'html-', plain arrays with 'array-'
	 * and complex array data with 'data-'. 'is-' and 'has-' prefixes can
	 * be used for boolean variables.
	 * Messages are prefixed with 'msg-', followed by their message key.
	 * All messages specified in the skin option 'messages' will be available
	 * under 'msg-MESSAGE-NAME'.
	 *
	 * @return array Data for a mustache template
	 */
	public function getTemplateData() {
		$title = $this->getTitle();
		$out = $this->getOutput();
		$user = $this->getUser();
		$isMainPage = $title->isMainPage();
		$blankedHeading = false;
		// Heading can only be blanked on "views". It should
		// still show on action=edit, diff pages and action=history
		$isHeadingOverridable = $this->getContext()->getActionName() === 'view' &&
			!$this->getRequest()->getRawVal( 'diff' );

		if ( $isMainPage && $isHeadingOverridable ) {
			// Special casing for the main page to allow more freedom to editors, to
			// design their home page differently. This came up in T290480.
			// The parameter for logged in users is optional and may
			// or may not be used.
			$titleMsg = $user->isAnon() ?
				$this->msg( 'mainpage-title' ) :
				$this->msg( 'mainpage-title-loggedin', $user->getName() );

			// T298715: Use content language rather than user language so that
			// the custom page heading is shown to all users, not just those that have
			// their interface set to the site content language.
			//
			// T331095: Avoid Message::inContentLanuguage and, just like Parser,
			// pick the language variant based on the current URL and/or user
			// preference if their variant relates to the content language.
			$forceUIMsgAsContentMsg = $this->getConfig()
				->get( MainConfigNames::ForceUIMsgAsContentMsg );
			if ( !in_array( $titleMsg->getKey(), (array)$forceUIMsgAsContentMsg ) ) {
				$services = MediaWikiServices::getInstance();
				$contLangVariant = $services->getLanguageConverterFactory()
					->getLanguageConverter( $services->getContentLanguage() )
					->getPreferredVariant();
				$titleMsg->inLanguage( $contLangVariant );
			}
			$titleMsg->setInterfaceMessageFlag( true );
			$blankedHeading = $titleMsg->isBlank();
			if ( !$titleMsg->isDisabled() ) {
				$htmlTitle = $titleMsg->parse();
			} else {
				$htmlTitle = $out->getPageTitle();
			}
		} else {
			$htmlTitle = $out->getPageTitle();
		}

		$data = [
			// raw HTML
			'html-title-heading' => Html::rawElement(
				'h1',
				[
					'id' => 'firstHeading',
					'class' => 'firstHeading mw-first-heading',
					'style' => $blankedHeading ? 'display: none' : null
				] + $this->getUserLanguageAttributes(),
				$htmlTitle
			),
			'html-title' => $htmlTitle ?: null,
			// Boolean values
			'is-title-blank' => $blankedHeading, // @since 1.38
			'is-anon' => $user->isAnon(),
			'is-article' => $out->isArticle(),
			'is-mainpage' => $isMainPage,
			'is-specialpage' => $title->isSpecialPage(),
			'canonical-url' => $this->getCanonicalUrl(),
		];

		$components = $this->componentRegistry->getComponents();
		foreach ( $components as $componentName => $component ) {
			$data['data-' . $componentName] = $component->getTemplateData();
		}
		return $data;
	}

	/**
	 * Normalize a skin preference value to a form that can be loaded.
	 *
	 * If a skin can't be found, it will fall back to the configured default ($wgDefaultSkin), or the
	 * hardcoded default ($wgFallbackSkin) if the default skin is unavailable too.
	 *
	 * @param string $key 'monobook', 'vector', etc.
	 * @return string
	 */
	public static function normalizeKey( string $key ) {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$defaultSkin = $config->get( MainConfigNames::DefaultSkin );
		$fallbackSkin = $config->get( MainConfigNames::FallbackSkin );
		$skinFactory = MediaWikiServices::getInstance()->getSkinFactory();
		$skinNames = $skinFactory->getInstalledSkins();

		// Make keys lowercase for case-insensitive matching.
		$skinNames = array_change_key_case( $skinNames, CASE_LOWER );
		$key = strtolower( $key );
		$defaultSkin = strtolower( $defaultSkin );
		$fallbackSkin = strtolower( $fallbackSkin );

		if ( $key == '' || $key == 'default' ) {
			// Don't return the default immediately;
			// in a misconfiguration we need to fall back.
			$key = $defaultSkin;
		}

		if ( isset( $skinNames[$key] ) ) {
			return $key;
		}

		// Older versions of the software used a numeric setting
		// in the user preferences.
		$fallback = [
			0 => $defaultSkin,
			2 => 'cologneblue'
		];

		if ( isset( $fallback[$key] ) ) {
			// @phan-suppress-next-line PhanTypeMismatchDimFetch False positive
			$key = $fallback[$key];
		}

		if ( isset( $skinNames[$key] ) ) {
			return $key;
		} elseif ( isset( $skinNames[$defaultSkin] ) ) {
			return $defaultSkin;
		} else {
			return $fallbackSkin;
		}
	}

	/**
	 * @since 1.31
	 * @param string|array|null $options Options for the skin can be an array (since 1.35).
	 *  When a string is passed, it's the skinname.
	 *  When an array is passed:
	 *
	 *  - `name`: Required. Internal skin name, generally in lowercase to comply with conventions
	 *     for interface message keys and CSS class names which embed this value.
	 *
	 *  - `format`: Enable rendering of skin as json or html.
	 *
	 *     Since: MW 1.43
	 *     Default: `html`
	 *
	 *  - `styles`: ResourceLoader style modules to load on all pages. Default: `[]`
	 *
	 *  - `scripts`: ResourceLoader script modules to load on all pages. Default: `[]`
	 *
	 *  - `toc`: Whether a table of contents is included in the main article content
	 *     area. If your skin has place a table of contents elsewhere (for example, the sidebar),
	 *     set this to `false`.
	 *
	 *     See ParserOutput::getText() for the implementation logic.
	 *
	 *     Default: `true`
	 *
	 *  - `bodyClasses`: An array of extra class names to add to the HTML `<body>` element.
	 *     Default: `[]`
	 *
	 *  - `clientPrefEnabled`: Enable support for mw.user.clientPrefs.
	 *     This instructs OutputPage and ResourceLoader\ClientHtml to include an inline script
	 *     in web responses for unregistered users to switch HTML classes as needed.
	 *
	 *     Since: MW 1.41
	 *     Default: `false`
	 *
	 *  - `wrapSiteNotice`: Enable support for standard site notice wrapper.
	 *     This instructs the Skin to wrap banners in div#siteNotice.
	 *
	 *     Since: MW 1.42
	 *     Default: `false`
	 *
	 *  - `responsive`: Whether the skin supports responsive behaviour and wants a viewport meta
	 *     tag to be added to the HTML head. Note, users can disable this feature via a user
	 *     preference.
	 *
	 *     Default: `false`
	 *
	 *  - `supportsMwHeading`: Whether the skin supports new HTML markup for headings, which uses
	 *     `<div class="mw-heading">` tags (https://www.mediawiki.org/wiki/Heading_HTML_changes).
	 *     If false, MediaWiki will output the legacy markup instead.
	 *
	 *     Since: MW 1.43
	 *     Default: `false` (will become `true` in and then will be removed in the future)
	 *
	 *  - `link`: An array of link option overriddes. See Skin::makeLink for the available options.
	 *
	 *     Default: `[]`
	 *
	 *  - `tempUserBanner`: Whether to display a banner on page views by in temporary user sessions.
	 *     This will prepend SkinComponentTempUserBanner to the `<body>` above everything else.
	 *     See also MediaWiki\MainConfigSchema::AutoCreateTempUser and User::isTemp.
	 *
	 *     Default: `false`
	 *
	 *  - `menus`: Which menus the skin supports, to allow features like SpecialWatchlist
	 *     to render their own navigation in the skins that don't support certain menus.
	 *     For any key in the array, the skin is promising to render an element e.g. the
	 *     presence of `associated-pages` means the skin will render a menu
	 *     compatible with mw.util.addPortletLink which has the ID p-associated-pages.
	 *
	 *     Default: `['namespaces', 'views', 'actions', 'variants']`
	 *
	 *     Opt-in menus:
	 *     - `associated-pages`
	 *     - `notifications`
	 *     - `user-interface-preferences`
	 *     - `user-page`
	 *     - `user-menu`
	 */
	public function __construct( $options = null ) {
		if ( is_string( $options ) ) {
			$this->skinname = $options;
		} elseif ( $options ) {
			$name = $options['name'] ?? null;

			if ( !$name ) {
				throw new SkinException( 'Skin name must be specified' );
			}

			// Defaults are set in Skin::getOptions()
			$this->options = $options;
			$this->skinname = $name;
		}
		$this->defaultLinkOptions = $this->getOptions()['link'];
		$this->componentRegistry = new SkinComponentRegistry(
			new SkinComponentRegistryContext( $this )
		);
	}

	/**
	 * @return string|null Skin name
	 */
	public function getSkinName() {
		return $this->skinname;
	}

	/**
	 * Indicates if this skin is responsive.
	 * Responsive skins have skin--responsive added to <body> by OutputPage,
	 * and a viewport <meta> tag set by Skin::initPage.
	 *
	 * @since 1.36
	 * @stable to override
	 * @return bool
	 */
	public function isResponsive() {
		$isSkinResponsiveCapable = $this->getOptions()['responsive'];
		$userOptionsLookup = MediaWikiServices::getInstance()->getUserOptionsLookup();

		return $isSkinResponsiveCapable &&
			$userOptionsLookup->getBoolOption( $this->getUser(), 'skin-responsive' );
	}

	/**
	 * @stable to override
	 * @param OutputPage $out
	 */
	public function initPage( OutputPage $out ) {
		$skinMetaTags = $this->getConfig()->get( MainConfigNames::SkinMetaTags );
		$siteName = $this->getConfig()->get( MainConfigNames::Sitename );
		$this->preloadExistence();

		if ( $this->isResponsive() ) {
			$out->addMeta(
				'viewport',
				'width=device-width, initial-scale=1.0, ' .
				'user-scalable=yes, minimum-scale=0.25, maximum-scale=5.0'
			);
		} else {
			// Force the desktop experience on an iPad by resizing the mobile viewport to
			// the value of @min-width-breakpoint-desktop (1120px).
			// This is as @min-width-breakpoint-desktop-wide usually tends to optimize
			// for larger screens with max-widths and margins.
			// The initial-scale SHOULD NOT be set here as defining it will impact zoom
			// on mobile devices. To allow font-size adjustment in iOS devices (see T311795)
			// we will define a zoom in JavaScript on certain devices (see resources/src/mediawiki.page.ready/ready.js)
			$out->addMeta(
				'viewport',
				'width=1120'
			);
		}

		$tags = [
			'og:site_name' => $siteName,
			'og:title' => $out->getHTMLTitle(),
			'twitter:card' => 'summary_large_image',
			'og:type' => 'website',
		];

		// Support sharing on platforms such as Facebook and Twitter
		foreach ( $tags as $key => $value ) {
			if ( in_array( $key, $skinMetaTags ) ) {
				$out->addMeta( $key, $value );
			}
		}
	}

	/**
	 * Defines the ResourceLoader modules that should be added to the skin
	 * It is recommended that skins wishing to override call parent::getDefaultModules()
	 * and substitute out any modules they wish to change by using a key to look them up
	 *
	 * Any modules defined with the 'styles' key will be added as render blocking CSS via
	 * Output::addModuleStyles. Similarly, each key should refer to a list of modules
	 *
	 * @stable to override
	 * @return array Array of modules with helper keys for easy overriding
	 */
	public function getDefaultModules() {
		$out = $this->getOutput();
		$user = $this->getUser();

		// Modules declared in the $modules literal are loaded
		// for ALL users, on ALL pages, in ALL skins.
		// Keep this list as small as possible!
		$modules = [
			// The 'styles' key sets render-blocking style modules
			// Unlike other keys in $modules, this is an associative array
			// where each key is its own group pointing to a list of modules
			'styles' => [
				'skin' => $this->getOptions()['styles'],
				'core' => [],
				'content' => [],
				'syndicate' => [],
				'user' => []
			],
			'core' => [
				'site',
				'mediawiki.page.ready',
			],
			// modules that enhance the content in some way
			'content' => [],
			// modules relating to search functionality
			'search' => [],
			// Skins can register their own scripts
			'skin' => $this->getOptions()['scripts'],
			// modules relating to functionality relating to watching an article
			'watch' => [],
			// modules which relate to the current users preferences
			'user' => [],
			// modules relating to RSS/Atom Feeds
			'syndicate' => [],
		];

		// Preload jquery.tablesorter for mediawiki.page.ready
		if ( strpos( $out->getHTML(), 'sortable' ) !== false ) {
			$modules['content'][] = 'jquery.tablesorter';
			$modules['styles']['content'][] = 'jquery.tablesorter.styles';
		}

		// Preload jquery.makeCollapsible for mediawiki.page.ready
		if ( strpos( $out->getHTML(), 'mw-collapsible' ) !== false ) {
			$modules['content'][] = 'jquery.makeCollapsible';
			$modules['styles']['content'][] = 'jquery.makeCollapsible.styles';
		}

		// Load relevant styles on wiki pages that use mw-ui-button.
		// Since 1.26, this no longer loads unconditionally. Special pages
		// and extensions should load this via addModuleStyles() instead.
		if ( strpos( $out->getHTML(), 'mw-ui-button' ) !== false ) {
			$modules['styles']['content'][] = 'mediawiki.ui.button';
		}
		// Since 1.41, styling for mw-message-box is only required for
		// messages that appear in article content.
		// This should only be removed when a suitable alternative exists
		// e.g. https://phabricator.wikimedia.org/T363607 is resolved.
		if ( strpos( $out->getHTML(), 'mw-message-box' ) !== false ) {
			$modules['styles']['content'][] = 'mediawiki.legacy.messageBox';
		}

		$action = $this->getRequest()->getRawVal( 'action' ) ?? 'view';
		$title = $this->getTitle();
		$namespace = $title ? $title->getNamespace() : 0;
		// If the page is using Codex message box markup load Codex styles.
		// Since 1.41. Skins can unset this if they prefer to handle this via other
		// means.
		// For content, this should not be considered stable, and will likely
		// be removed when https://phabricator.wikimedia.org/T363607 is resolved.
		$containsUserGeneratedContent = strpos( $out->getHTML(), 'mw-parser-output' ) !== false;
		$containsCodexMessageBox = strpos( $out->getHTML(), 'cdx-message' ) !== false;
		if ( $containsCodexMessageBox && $containsUserGeneratedContent && $namespace !== NS_SPECIAL ) {
			$modules['styles']['content'][] = 'mediawiki.codex.messagebox.styles';
		}

		if ( $out->isTOCEnabled() ) {
			$modules['content'][] = 'mediawiki.toc';
		}

		$authority = $this->getAuthority();
		$relevantTitle = $this->getRelevantTitle();
		if ( $authority->getUser()->isRegistered()
			&& $authority->isAllowedAll( 'viewmywatchlist', 'editmywatchlist' )
			&& $relevantTitle && $relevantTitle->canExist()
		) {
			$modules['watch'][] = 'mediawiki.page.watch.ajax';
		}

		$userOptionsLookup = MediaWikiServices::getInstance()->getUserOptionsLookup();
		if ( $userOptionsLookup->getBoolOption( $user, 'editsectiononrightclick' )
			|| ( $out->isArticle() && $userOptionsLookup->getOption( $user, 'editondblclick' ) )
		) {
			$modules['user'][] = 'mediawiki.misc-authed-pref';
		}

		if ( $out->isSyndicated() ) {
			$modules['styles']['syndicate'][] = 'mediawiki.feedlink';
		}

		if ( $user->isTemp() ) {
			$modules['user'][] = 'mediawiki.tempUserBanner';
			$modules['styles']['user'][] = 'mediawiki.tempUserBanner.styles';
		}

		if ( $namespace === NS_FILE ) {
			$modules['styles']['core'][] = 'filepage'; // local Filepage.css, T31277, T356505
		}

		return $modules;
	}

	/**
	 * Preload the existence of three commonly-requested pages in a single query
	 */
	private function preloadExistence() {
		$titles = [];

		// User/talk link
		$user = $this->getUser();
		if ( $user->isRegistered() ) {
			$titles[] = $user->getUserPage();
			$titles[] = $user->getTalkPage();
		}

		// Check, if the page can hold some kind of content, otherwise do nothing
		$title = $this->getRelevantTitle();
		if ( $title && $title->canExist() && $title->canHaveTalkPage() ) {
			$namespaceInfo = MediaWikiServices::getInstance()->getNamespaceInfo();
			if ( $title->isTalkPage() ) {
				$titles[] = $namespaceInfo->getSubjectPage( $title );
			} else {
				$titles[] = $namespaceInfo->getTalkPage( $title );
			}
		}

		// Preload for self::getCategoryLinks
		$allCats = $this->getOutput()->getCategoryLinks();
		if ( isset( $allCats['normal'] ) && $allCats['normal'] !== [] ) {
			$catLink = Title::newFromText( $this->msg( 'pagecategorieslink' )->inContentLanguage()->text() );
			if ( $catLink ) {
				// If this is a special page, the LinkBatch would skip it
				$titles[] = $catLink;
			}
		}

		$this->getHookRunner()->onSkinPreloadExistence( $titles, $this );

		if ( $titles ) {
			$linkBatchFactory = MediaWikiServices::getInstance()->getLinkBatchFactory();
			$linkBatchFactory->newLinkBatch( $titles )
				->setCaller( __METHOD__ )
				->execute();
		}
	}

	/**
	 * @see self::getRelevantTitle()
	 * @param Title $t
	 */
	public function setRelevantTitle( $t ) {
		$this->mRelevantTitle = $t;
	}

	/**
	 * Return the "relevant" title.
	 * A "relevant" title is not necessarily the actual title of the page.
	 * Special pages like Special:MovePage use set the page they are acting on
	 * as their "relevant" title, this allows the skin system to display things
	 * such as content tabs which belong to that page instead of displaying
	 * a basic special page tab which has almost no meaning.
	 *
	 * @return Title|null the title is null when no relevant title was set, as this
	 *   falls back to ContextSource::getTitle
	 */
	public function getRelevantTitle() {
		return $this->mRelevantTitle ?? $this->getTitle();
	}

	/**
	 * @see self::getRelevantUser()
	 * @param UserIdentity|null $u
	 */
	public function setRelevantUser( ?UserIdentity $u ) {
		$this->mRelevantUser = $u;
	}

	/**
	 * Return the "relevant" user.
	 * A "relevant" user is similar to a relevant title. Special pages like
	 * Special:Contributions mark the user which they are relevant to so that
	 * things like the toolbox can display the information they usually are only
	 * able to display on a user's userpage and talkpage.
	 *
	 * @return UserIdentity|null Null if there's no relevant user or the viewer cannot view it.
	 */
	public function getRelevantUser(): ?UserIdentity {
		if ( $this->mRelevantUser === false ) {
			$this->mRelevantUser = null; // false indicates we never attempted to load it.
			$title = $this->getRelevantTitle();
			if ( $title->hasSubjectNamespace( NS_USER ) ) {
				$services = MediaWikiServices::getInstance();
				$rootUser = $title->getRootText();
				$userNameUtils = $services->getUserNameUtils();
				if ( $userNameUtils->isIP( $rootUser ) ) {
					$this->mRelevantUser = UserIdentityValue::newAnonymous( $rootUser );
				} else {
					$user = $services->getUserIdentityLookup()->getUserIdentityByName( $rootUser );
					$this->mRelevantUser = $user && $user->isRegistered() ? $user : null;
				}
			}
		}

		// The relevant user should only be set if it exists. However, if it exists but is hidden,
		// and the viewer cannot see hidden users, this exposes the fact that the user exists;
		// pretend like the user does not exist in such cases, by setting it to null. T120883
		if ( $this->mRelevantUser && $this->mRelevantUser->isRegistered() ) {
			$userBlock = MediaWikiServices::getInstance()
				->getBlockManager()
				->getBlock( $this->mRelevantUser, null );
			if ( $userBlock && $userBlock->getHideName() &&
				!$this->getAuthority()->isAllowed( 'hideuser' )
			) {
				$this->mRelevantUser = null;
			}
		}

		return $this->mRelevantUser;
	}

	/**
	 * Outputs the HTML for the page.
	 * @internal Only to be called by OutputPage.
	 */
	final public function outputPageFinal( OutputPage $out ) {
		// generate body
		ob_start();
		$this->outputPage();
		$html = ob_get_contents();
		ob_end_clean();

		// T259955: OutputPage::headElement must be called last
		// as it calls OutputPage::getRlClient, which freezes the ResourceLoader
		// modules queue for the current page load.
		// Since Skins can add ResourceLoader modules via OutputPage::addModule
		// and OutputPage::addModuleStyles changing this order can lead to
		// bugs.
		$head = $out->headElement( $this );
		$tail = $out->tailElement( $this );

		echo $head . $html . $tail;
	}

	/**
	 * Outputs the HTML generated by other functions.
	 */
	abstract public function outputPage();

	/**
	 * TODO: document
	 * @param Title $title
	 * @return string
	 */
	public function getPageClasses( $title ) {
		$services = MediaWikiServices::getInstance();
		$ns = $title->getNamespace();
		$numeric = 'ns-' . $ns;

		if ( $title->isSpecialPage() ) {
			$type = 'ns-special';
			// T25315: provide a class based on the canonical special page name without subpages
			[ $canonicalName ] = $services->getSpecialPageFactory()->resolveAlias( $title->getDBkey() );
			if ( $canonicalName ) {
				$type .= ' ' . Sanitizer::escapeClass( "mw-special-$canonicalName" );
			} else {
				$type .= ' mw-invalidspecialpage';
			}
		} else {
			if ( $title->isTalkPage() ) {
				$type = 'ns-talk';
			} else {
				$type = 'ns-subject';
			}
			// T208315: add HTML class when the user can edit the page
			if ( $this->getAuthority()->probablyCan( 'edit', $title ) ) {
				$type .= ' mw-editable';
			}
		}

		$titleFormatter = $services->getTitleFormatter();
		$name = Sanitizer::escapeClass( 'page-' . $titleFormatter->getPrefixedText( $title ) );
		$root = Sanitizer::escapeClass( 'rootpage-' . $titleFormatter->formatTitle( $ns, $title->getRootText() ) );
		// Add a static class that is not subject to translation to allow extensions/skins/global code to target main
		// pages reliably (T363281)
		if ( $title->isMainPage() ) {
			$name .= ' page-Main_Page';
		}

		return "$numeric $type $name $root";
	}

	/**
	 * Return values for <html> element
	 * @return array Array of associative name-to-value elements for <html> element
	 */
	public function getHtmlElementAttributes() {
		$lang = $this->getLanguage();
		return [
			'lang' => $lang->getHtmlCode(),
			'dir' => $lang->getDir(),
			'class' => 'client-nojs',
		];
	}

	/**
	 * @return string HTML
	 */
	public function getCategoryLinks() {
		$out = $this->getOutput();
		$allCats = $out->getCategoryLinks();
		$title = $this->getTitle();
		$services = MediaWikiServices::getInstance();
		$linkRenderer = $services->getLinkRenderer();

		if ( $allCats === [] ) {
			return '';
		}

		$embed = "<li>";
		$pop = "</li>";

		$s = '';
		$colon = $this->msg( 'colon-separator' )->escaped();

		if ( !empty( $allCats['normal'] ) ) {
			$t = $embed . implode( $pop . $embed, $allCats['normal'] ) . $pop;

			$msg = $this->msg( 'pagecategories' )->numParams( count( $allCats['normal'] ) );
			$linkPage = $this->msg( 'pagecategorieslink' )->inContentLanguage()->text();
			$pageCategoriesLinkTitle = Title::newFromText( $linkPage );
			if ( $pageCategoriesLinkTitle ) {
				$link = $linkRenderer->makeLink( $pageCategoriesLinkTitle, $msg->text() );
			} else {
				$link = $msg->escaped();
			}
			$s .= Html::rawElement(
				'div',
				[ 'id' => 'mw-normal-catlinks', 'class' => 'mw-normal-catlinks' ],
				$link . $colon . Html::rawElement( 'ul', [], $t )
			);
		}

		# Hidden categories
		if ( isset( $allCats['hidden'] ) ) {
			$userOptionsLookup = $services->getUserOptionsLookup();

			if ( $userOptionsLookup->getBoolOption( $this->getUser(), 'showhiddencats' ) ) {
				$class = ' mw-hidden-cats-user-shown';
			} elseif ( $title->inNamespace( NS_CATEGORY ) ) {
				$class = ' mw-hidden-cats-ns-shown';
			} else {
				$class = ' mw-hidden-cats-hidden';
			}

			$s .= Html::rawElement(
				'div',
				[ 'id' => 'mw-hidden-catlinks', 'class' => "mw-hidden-catlinks$class" ],
				$this->msg( 'hidden-categories' )->numParams( count( $allCats['hidden'] ) )->escaped() .
					$colon .
					Html::rawElement(
						'ul',
						[],
						$embed . implode( $pop . $embed, $allCats['hidden'] ) . $pop
					)
			);
		}

		return $s;
	}

	/**
	 * @return string HTML
	 */
	public function getCategories() {
		$userOptionsLookup = MediaWikiServices::getInstance()->getUserOptionsLookup();
		$showHiddenCats = $userOptionsLookup->getBoolOption( $this->getUser(), 'showhiddencats' );

		$catlinks = $this->getCategoryLinks();
		// Check what we're showing
		$allCats = $this->getOutput()->getCategoryLinks();
		$showHidden = $showHiddenCats || $this->getTitle()->inNamespace( NS_CATEGORY );

		$classes = [ 'catlinks' ];
		if ( empty( $allCats['normal'] ) && !( !empty( $allCats['hidden'] ) && $showHidden ) ) {
			$classes[] = 'catlinks-allhidden';
		}

		return Html::rawElement(
			'div',
			[ 'id' => 'catlinks', 'class' => $classes, 'data-mw' => 'interface' ],
			$catlinks
		);
	}

	/**
	 * This runs a hook to allow extensions placing their stuff after content
	 * and article metadata (e.g. categories).
	 * Note: This function has nothing to do with afterContent().
	 *
	 * This hook is placed here in order to allow using the same hook for all
	 * skins, both the SkinTemplate based ones and the older ones, which directly
	 * use this class to get their data.
	 *
	 * The output of this function gets processed in SkinTemplate::outputPage() for
	 * the SkinTemplate based skins, all other skins should directly echo it.
	 *
	 * @return string Empty by default, if not changed by any hook function.
	 */
	protected function afterContentHook() {
		$data = '';

		if ( $this->getHookRunner()->onSkinAfterContent( $data, $this ) ) {
			// adding just some spaces shouldn't toggle the output
			// of the whole <div/>, so we use trim() here
			if ( trim( $data ) != '' ) {
				// Doing this here instead of in the skins to
				// ensure that the div has the same ID in all
				// skins
				$data = "<div id='mw-data-after-content'>\n" .
					"\t$data\n" .
					"</div>\n";
			}
		} else {
			wfDebug( "Hook SkinAfterContent changed output processing." );
		}

		return $data;
	}

	/**
	 * Get the canonical URL (permalink) for the page including oldid if present.
	 *
	 * @return string
	 */
	private function getCanonicalUrl() {
		$title = $this->getTitle();
		$oldid = $this->getOutput()->getRevisionId();
		if ( $oldid ) {
			return $title->getCanonicalURL( 'oldid=' . $oldid );
		} else {
			// oldid not available for non existing pages
			return $title->getCanonicalURL();
		}
	}

	/**
	 * Text with the permalink to the source page,
	 * usually shown on the footer of a printed page
	 *
	 * @stable to override
	 * @return string HTML text with an URL
	 */
	public function printSource() {
		$urlUtils = MediaWikiServices::getInstance()->getUrlUtils();
		$url = htmlspecialchars( $urlUtils->expandIRI( $this->getCanonicalUrl() ) ?? '' );

		return $this->msg( 'retrievedfrom' )
			->rawParams( '<a dir="ltr" href="' . $url . '">' . $url . '</a>' )
			->parse();
	}

	/**
	 * @return string HTML
	 */
	public function getUndeleteLink() {
		$action = $this->getRequest()->getRawVal( 'action' ) ?? 'view';
		$title = $this->getTitle();
		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();

		if ( ( !$title->exists() || $action == 'history' ) &&
			$this->getAuthority()->probablyCan( 'deletedhistory', $title )
		) {
			$n = $title->getDeletedEditsCount();

			if ( $n ) {
				if ( $this->getAuthority()->probablyCan( 'undelete', $title ) ) {
					$msg = 'thisisdeleted';
				} else {
					$msg = 'viewdeleted';
				}

				$subtitle = $this->msg( $msg )->rawParams(
					$linkRenderer->makeKnownLink(
						SpecialPage::getTitleFor( 'Undelete', $title->getPrefixedDBkey() ),
						$this->msg( 'restorelink' )->numParams( $n )->text() )
					)->escaped();

				$links = [];
				// Add link to page logs, unless we're on the history page (which
				// already has one)
				if ( $action !== 'history' ) {
					$links[] = $linkRenderer->makeKnownLink(
						SpecialPage::getTitleFor( 'Log' ),
						$this->msg( 'viewpagelogs-lowercase' )->text(),
						[],
						[ 'page' => $title->getPrefixedText() ]
					);
				}

				// Allow extensions to add more links
				$this->getHookRunner()->onUndeletePageToolLinks(
					$this->getContext(), $linkRenderer, $links );

				if ( $links ) {
					$subtitle .= ''
						. $this->msg( 'word-separator' )->escaped()
						. $this->msg( 'parentheses' )
							->rawParams( $this->getLanguage()->pipeList( $links ) )
							->escaped();
				}

				return Html::rawElement( 'div', [ 'class' => 'mw-undelete-subtitle' ], $subtitle );
			}
		}

		return '';
	}

	/**
	 * @return string
	 */
	private function subPageSubtitleInternal() {
		$services = MediaWikiServices::getInstance();
		$linkRenderer = $services->getLinkRenderer();
		$out = $this->getOutput();
		$title = $out->getTitle();
		$subpages = '';

		if ( !$this->getHookRunner()->onSkinSubPageSubtitle( $subpages, $this, $out ) ) {
			return $subpages;
		}

		$hasSubpages = $services->getNamespaceInfo()->hasSubpages( $title->getNamespace() );
		if ( !$out->isArticle() || !$hasSubpages ) {
			return $subpages;
		}

		$ptext = $title->getPrefixedText();
		if ( strpos( $ptext, '/' ) !== false ) {
			$links = explode( '/', $ptext );
			array_pop( $links );
			$count = 0;
			$growingLink = '';
			$display = '';
			$lang = $this->getLanguage();

			foreach ( $links as $link ) {
				$growingLink .= $link;
				$display .= $link;
				$linkObj = Title::newFromText( $growingLink );

				if ( $linkObj && $linkObj->isKnown() ) {
					$getlink = $linkRenderer->makeKnownLink( $linkObj, $display );

					$count++;

					if ( $count > 1 ) {
						$subpages .= $this->msg( 'pipe-separator' )->escaped();
					} else {
						$subpages .= '&lt; ';
					}

					$subpages .= Html::rawElement( 'bdi', [ 'dir' => $lang->getDir() ], $getlink );
					$display = '';
				} else {
					$display .= '/';
				}
				$growingLink .= '/';
			}
		}

		return $subpages;
	}

	/**
	 * Helper function for mapping template data for use in legacy function
	 *
	 * @param string $dataKey
	 * @param string $name
	 * @return string
	 */
	private function getFooterTemplateDataItem( string $dataKey, string $name ) {
		$footerData = $this->getComponent( 'footer' )->getTemplateData();
		$items = $footerData[ $dataKey ]['array-items'] ?? [];
		foreach ( $items as $item ) {
			if ( $item['name'] === $name ) {
				return $item['html'];
			}
		}
		return '';
	}

	final public function getCopyright(): string {
		return $this->getFooterTemplateDataItem( 'data-info', 'copyright' );
	}

	/**
	 * @param string $align
	 * @return string
	 */
	public function logoText( $align = '' ) {
		if ( $align != '' ) {
			$a = " style='float: {$align};'";
		} else {
			$a = '';
		}

		$mp = $this->msg( 'mainpage' )->escaped();
		$url = htmlspecialchars( Title::newMainPage()->getLocalURL() );

		$logourl = RL\SkinModule::getAvailableLogos(
			$this->getConfig(),
			$this->getLanguage()->getCode()
		)[ '1x' ];
		return "<a href='{$url}'><img{$a} src='{$logourl}' alt='[{$mp}]' /></a>";
	}

	/**
	 * Get template representation of the footer.
	 *
	 * Stable to use since 1.40 but should not be overridden.
	 *
	 * @since 1.35
	 * @internal for use inside SkinComponentRegistryContext
	 * @return array
	 */
	final public function getFooterIcons() {
		return SkinComponentFooter::getFooterIconsData(
			$this->getConfig()
		);
	}

	/**
	 * Renders a $wgFooterIcons icon according to the method's arguments
	 *
	 * Stable to use since 1.40 but should not be overridden.
	 *
	 * @param array $icon The icon to build the html for, see $wgFooterIcons
	 *   for the format of this array.
	 * @param bool|string $withImage Whether to use the icon's image or output
	 *   a text-only footericon.
	 * @return string HTML
	 */
	final public function makeFooterIcon( $icon, $withImage = 'withImage' ) {
		return SkinComponentFooter::makeFooterIconHTML(
			$this->getConfig(), $icon, $withImage
		);
	}

	/**
	 * Return URL options for the 'edit page' link.
	 * This may include an 'oldid' specifier, if the current page view is such.
	 *
	 * @return array
	 * @internal
	 */
	public function editUrlOptions() {
		$options = [ 'action' => 'edit' ];
		$out = $this->getOutput();

		if ( !$out->isRevisionCurrent() ) {
			$options['oldid'] = intval( $out->getRevisionId() );
		}

		return $options;
	}

	/**
	 * @param UserIdentity|int $id
	 * @return bool
	 */
	public function showEmailUser( $id ) {
		if ( $id instanceof UserIdentity ) {
			$targetUser = User::newFromIdentity( $id );
		} else {
			$targetUser = User::newFromId( $id );
		}

		# The sending user must have a confirmed email address and the receiving
		# user must accept emails from the sender.
		$emailUser = MediaWikiServices::getInstance()->getEmailUserFactory()
			->newEmailUser( $this->getUser() );

		return $emailUser->canSend()->isOK()
			&& $emailUser->validateTarget( $targetUser )->isOK();
	}

	/* these are used extensively in SkinTemplate, but also some other places */

	/**
	 * @param string|array $urlaction
	 * @return string
	 */
	public static function makeMainPageUrl( $urlaction = '' ) {
		$title = Title::newMainPage();

		return $title->getLinkURL( $urlaction );
	}

	/**
	 * Make a URL for a Special Page using the given query and protocol.
	 *
	 * If $proto is set to null, make a local URL. Otherwise, make a full
	 * URL with the protocol specified.
	 *
	 * @deprecated since 1.39 - Moved to SkinComponentUtils::makeSpecialUrl
	 * @param string $name Name of the Special page
	 * @param string|array $urlaction Query to append
	 * @param string|null $proto Protocol to use or null for a local URL
	 * @return string
	 */
	public static function makeSpecialUrl( $name, $urlaction = '', $proto = null ) {
		wfDeprecated( __METHOD__, '1.39' );
		return SkinComponentUtils::makeSpecialUrl( $name, $urlaction, $proto );
	}

	/**
	 * @deprecated since 1.39 - Moved to SkinComponentUtils::makeSpecialUrlSubpage
	 * @param string $name
	 * @param string|bool $subpage false for no subpage
	 * @param string|array $urlaction
	 * @return string
	 */
	public static function makeSpecialUrlSubpage( $name, $subpage, $urlaction = '' ) {
		wfDeprecated( __METHOD__, '1.39' );
		return SkinComponentUtils::makeSpecialUrlSubpage( $name, $subpage, $urlaction );
	}

	/**
	 * If url string starts with http, consider as external URL, else
	 * internal
	 * @param string $name
	 * @return string URL
	 */
	public static function makeInternalOrExternalUrl( $name ) {
		$protocols = MediaWikiServices::getInstance()->getUrlUtils()->validProtocols();

		if ( preg_match( '/^(?i:' . $protocols . ')/', $name ) ) {
			return $name;
		} else {
			$title = $name instanceof Title ? $name : Title::newFromText( $name );
			return $title ? $title->getLinkURL() : '';
		}
	}

	/**
	 * these return an array with the 'href' and boolean 'exists'
	 * @param string|Title $name
	 * @param string|array $urlaction
	 * @return array
	 */
	protected static function makeUrlDetails( $name, $urlaction = '' ) {
		$title = $name instanceof Title ? $name : Title::newFromText( $name );
		return [
			'href' => $title ? $title->getLocalURL( $urlaction ) : '',
			'exists' => $title && $title->isKnown(),
		];
	}

	/**
	 * Make URL details where the article exists (or at least it's convenient to think so)
	 * @param string|Title $name Article name
	 * @param string|array $urlaction
	 * @return array
	 */
	protected static function makeKnownUrlDetails( $name, $urlaction = '' ) {
		$title = $name instanceof Title ? $name : Title::newFromText( $name );
		return [
			'href' => $title ? $title->getLocalURL( $urlaction ) : '',
			'exists' => (bool)$title,
		];
	}

	/**
	 * Allows correcting the language of interlanguage links which, mostly due to
	 * legacy reasons, do not always match the standards compliant language tag.
	 *
	 * @param string $code
	 * @return string
	 * @since 1.35
	 */
	public function mapInterwikiToLanguage( $code ) {
		$map = $this->getConfig()->get( MainConfigNames::InterlanguageLinkCodeMap );
		return $map[ $code ] ?? $code;
	}

	/**
	 * Generates array of language links for the current page.
	 * This may includes items added to this section by the SidebarBeforeOutput hook
	 * (which may not necessarily be language links)
	 *
	 * @since 1.35
	 * @return array
	 */
	public function getLanguages() {
		if ( $this->getConfig()->get( MainConfigNames::HideInterlanguageLinks ) ) {
			return [];
		}
		if ( $this->languageLinks === null ) {
			$hookRunner = $this->getHookRunner();

			$userLang = $this->getLanguage();
			$languageLinks = [];
			$langNameUtils = MediaWikiServices::getInstance()->getLanguageNameUtils();

			foreach ( $this->getOutput()->getLanguageLinks() as $languageLinkText ) {
				[ $prefix, $title ] = explode( ':', $languageLinkText, 2 );
				$class = 'interlanguage-link interwiki-' . $prefix;

				[ $title, $frag ] = array_pad( explode( '#', $title, 2 ), 2, '' );
				$languageLinkTitle = TitleValue::tryNew( NS_MAIN, $title, $frag, $prefix );
				if ( $languageLinkTitle === null ) {
					continue;
				}
				$ilInterwikiCode = $this->mapInterwikiToLanguage( $prefix );

				$ilLangName = $langNameUtils->getLanguageName( $ilInterwikiCode );

				if ( strval( $ilLangName ) === '' ) {
					$ilDisplayTextMsg = $this->msg( "interlanguage-link-$ilInterwikiCode" );
					if ( !$ilDisplayTextMsg->isDisabled() ) {
						// Use custom MW message for the display text
						$ilLangName = $ilDisplayTextMsg->text();
					} else {
						// Last resort: fallback to the language link target
						$ilLangName = $languageLinkText;
					}
				} else {
					// Use the language autonym as display text
					$ilLangName = $this->getLanguage()->ucfirst( $ilLangName );
				}

				// CLDR extension or similar is required to localize the language name;
				// otherwise we'll end up with the autonym again.
				$ilLangLocalName =
					$langNameUtils->getLanguageName( $ilInterwikiCode, $userLang->getCode() );

				$languageLinkTitleText = $languageLinkTitle->getText();
				if ( $ilLangLocalName === '' ) {
					$ilFriendlySiteName =
						$this->msg( "interlanguage-link-sitename-$ilInterwikiCode" );
					if ( !$ilFriendlySiteName->isDisabled() ) {
						if ( $languageLinkTitleText === '' ) {
							$ilTitle =
								$this->msg( 'interlanguage-link-title-nonlangonly',
									$ilFriendlySiteName->text() )->text();
						} else {
							$ilTitle =
								$this->msg( 'interlanguage-link-title-nonlang',
									$languageLinkTitleText, $ilFriendlySiteName->text() )->text();
						}
					} else {
						// we have nothing friendly to put in the title, so fall back to
						// displaying the interlanguage link itself in the title text
						// (similar to what is done in page content)
						$ilTitle = $languageLinkTitle->getInterwiki() . ":$languageLinkTitleText";
					}
				} elseif ( $languageLinkTitleText === '' ) {
					$ilTitle =
						$this->msg( 'interlanguage-link-title-langonly', $ilLangLocalName )->text();
				} else {
					$ilTitle =
						$this->msg( 'interlanguage-link-title', $languageLinkTitleText,
							$ilLangLocalName )->text();
				}

				$ilInterwikiCodeBCP47 = LanguageCode::bcp47( $ilInterwikiCode );
				// A TitleValue is sufficient above this point, but we need
				// a full Title for ::getFullURL() and the hook invocation
				$languageLinkFullTitle = Title::newFromLinkTarget( $languageLinkTitle );
				$languageLink = [
					'href' => $languageLinkFullTitle->getFullURL(),
					'text' => $ilLangName,
					'title' => $ilTitle,
					'class' => $class,
					'link-class' => 'interlanguage-link-target',
					'lang' => $ilInterwikiCodeBCP47,
					'hreflang' => $ilInterwikiCodeBCP47,
					'data-title' => $languageLinkTitleText,
					'data-language-autonym' => $ilLangName,
					'data-language-local-name' => $ilLangLocalName,
				];
				$hookRunner->onSkinTemplateGetLanguageLink(
					$languageLink, $languageLinkFullTitle, $this->getTitle(), $this->getOutput()
				);
				$languageLinks[] = $languageLink;
			}
			$this->languageLinks = $languageLinks;
		}

		return $this->languageLinks;
	}

	/**
	 * Build array of common navigation links.
	 * Assumes thispage property has been set before execution.
	 * @since 1.35
	 * @return array
	 */
	protected function buildNavUrls() {
		$out = $this->getOutput();
		$title = $this->getTitle();
		$thispage = $title->getPrefixedDBkey();
		$uploadNavigationUrl = $this->getConfig()->get( MainConfigNames::UploadNavigationUrl );

		$nav_urls = [];
		$nav_urls['mainpage'] = [ 'href' => self::makeMainPageUrl() ];
		if ( $uploadNavigationUrl ) {
			$nav_urls['upload'] = [ 'href' => $uploadNavigationUrl ];
		} elseif ( UploadBase::isEnabled() && UploadBase::isAllowed( $this->getAuthority() ) === true ) {
			$nav_urls['upload'] = [ 'href' => SkinComponentUtils::makeSpecialUrl( 'Upload' ) ];
		} else {
			$nav_urls['upload'] = false;
		}

		$nav_urls['print'] = false;
		$nav_urls['permalink'] = false;
		$nav_urls['info'] = false;
		$nav_urls['whatlinkshere'] = false;
		$nav_urls['recentchangeslinked'] = false;
		$nav_urls['contributions'] = false;
		$nav_urls['log'] = false;
		$nav_urls['blockip'] = false;
		$nav_urls['changeblockip'] = false;
		$nav_urls['unblockip'] = false;
		$nav_urls['mute'] = false;
		$nav_urls['emailuser'] = false;
		$nav_urls['userrights'] = false;

		// A print stylesheet is attached to all pages, but nobody ever
		// figures that out. :)  Add a link...
		if ( !$out->isPrintable() && ( $out->isArticle() || $title->isSpecialPage() ) ) {
			$nav_urls['print'] = [
				'text' => $this->msg( 'printableversion' )->text(),
				'href' => 'javascript:print();'
			];
		}

		if ( $out->isArticle() ) {
			// Also add a "permalink" while we're at it
			$revid = $out->getRevisionId();
			if ( $revid ) {
				$nav_urls['permalink'] = [
					'icon' => 'link',
					'text' => $this->msg( 'permalink' )->text(),
					'href' => $title->getLocalURL( "oldid=$revid" )
				];
			}
		}

		if ( $out->isArticleRelated() ) {
			$nav_urls['whatlinkshere'] = [
				'href' => SpecialPage::getTitleFor( 'Whatlinkshere', $thispage )->getLocalURL()
			];

			$nav_urls['info'] = [
				'icon' => 'infoFilled',
				'text' => $this->msg( 'pageinfo-toolboxlink' )->text(),
				'href' => $title->getLocalURL( "action=info" )
			];

			if ( $title->exists() || $title->inNamespace( NS_CATEGORY ) ) {
				$nav_urls['recentchangeslinked'] = [
					'href' => SpecialPage::getTitleFor( 'Recentchangeslinked', $thispage )->getLocalURL()
				];
			}
		}

		$user = $this->getRelevantUser();

		if ( $user ) {
			$rootUser = $user->getName();

			$nav_urls['contributions'] = [
				'text' => $this->msg( 'tool-link-contributions', $rootUser )->text(),
				'href' => SkinComponentUtils::makeSpecialUrlSubpage( 'Contributions', $rootUser ),
				'tooltip-params' => [ $rootUser ],
			];

			$nav_urls['log'] = [
				'icon' => 'listBullet',
				'href' => SkinComponentUtils::makeSpecialUrlSubpage( 'Log', $rootUser )
			];

			if ( $this->getAuthority()->isAllowed( 'block' ) ) {
				// Check if the user is already blocked
				$userBlock = MediaWikiServices::getInstance()
					->getBlockManager()
					->getBlock( $user, null );
				if ( $userBlock ) {
					$useCodex = $this->getConfig()->get( MainConfigNames::UseCodexSpecialBlock );
					$nav_urls[ $useCodex ? 'block-manage-blocks' : 'changeblockip' ] = [
						'icon' => 'block',
						'href' => SkinComponentUtils::makeSpecialUrlSubpage( 'Block', $rootUser )
					];
					if ( !$useCodex ) {
						$nav_urls['unblockip'] = [
							'icon' => 'unBlock',
							'href' => SkinComponentUtils::makeSpecialUrlSubpage( 'Unblock', $rootUser )
						];
					}
				} else {
					$nav_urls['blockip'] = [
						'icon' => 'block',
						'text' => $this->msg( 'blockip', $rootUser )->text(),
						'href' => SkinComponentUtils::makeSpecialUrlSubpage( 'Block', $rootUser )
					];
				}
			}

			if ( $this->showEmailUser( $user ) ) {
				$nav_urls['emailuser'] = [
					'text' => $this->msg( 'tool-link-emailuser', $rootUser )->text(),
					'href' => SkinComponentUtils::makeSpecialUrlSubpage( 'Emailuser', $rootUser ),
					'tooltip-params' => [ $rootUser ],
				];
			}

			if ( $user->isRegistered() ) {
				if ( $this->getConfig()->get( MainConfigNames::EnableSpecialMute ) &&
					$this->getUser()->isNamed()
				) {
					$nav_urls['mute'] = [
						'text' => $this->msg( 'mute-preferences' )->text(),
						'href' => SkinComponentUtils::makeSpecialUrlSubpage( 'Mute', $rootUser )
					];
				}

				// Don't show links to Special:UserRights for temporary accounts (as they cannot have groups)
				$userNameUtils = MediaWikiServices::getInstance()->getUserNameUtils();
				if ( !$userNameUtils->isTemp( $user->getName() ) ) {
					$sur = new SpecialUserRights;
					$sur->setContext( $this->getContext() );
					$canChange = $sur->userCanChangeRights( $user );
					$delimiter = $this->getConfig()->get(
						MainConfigNames::UserrightsInterwikiDelimiter );
					if ( str_contains( $rootUser, $delimiter ) ) {
						// Username contains interwiki delimiter, link it via the
						// #{userid} syntax. (T260222)
						$linkArgs = [ false, [ 'user' => '#' . $user->getId() ] ];
					} else {
						$linkArgs = [ $rootUser ];
					}
					$nav_urls['userrights'] = [
						'icon' => 'userGroup',
						'text' => $this->msg(
							$canChange ? 'tool-link-userrights' : 'tool-link-userrights-readonly',
							$rootUser
						)->text(),
						'href' => SkinComponentUtils::makeSpecialUrlSubpage( 'Userrights', ...$linkArgs )
					];
				}
			}
		}

		return $nav_urls;
	}

	/**
	 * Build data structure representing syndication links.
	 * @since 1.35
	 * @return array
	 */
	final protected function buildFeedUrls() {
		$feeds = [];
		$out = $this->getOutput();
		if ( $out->isSyndicated() ) {
			foreach ( $out->getSyndicationLinks() as $format => $link ) {
				$feeds[$format] = [
					// Messages: feed-atom, feed-rss
					'text' => $this->msg( "feed-$format" )->text(),
					'href' => $link
				];
			}
		}
		return $feeds;
	}

	/**
	 * Append link to SpecialPages into navigation sidebar if it doesn't already exist
	 *
	 * Created to help migrate sidebars after the SpecialPages link was removed from the toolbar.
	 *
	 * @since 1.44
	 * @deprecated since 1.44 - will be hard deprecated in 1.45
	 */
	private function appendSpecialPagesLinkIfAbsent() {
		if ( $this->sidebar === null ) {
			return;
		}

		$isSpecialPagesPresent = false;
		foreach ( $this->sidebar as $bar ) {
			if ( in_array( 'n-specialpages', array_column( $bar, 'id' ) ) ) {
				$isSpecialPagesPresent = true;
				break;
			}
		}
		if ( !$isSpecialPagesPresent ) {
			$item = $this->createSidebarItem( 'specialpages-url', 'specialpages' );
			if ( $item !== null ) {
				wfDeprecated( __METHOD__, '1.44' );
				$this->sidebar['navigation'][] = $item;
			}
		}
	}

	/**
	 * Build an array that represents the sidebar(s), the navigation bar among them.
	 *
	 * BaseTemplate::getSidebar can be used to simplify the format and id generation in new skins.
	 *
	 * The format of the returned array is [ heading => content, ... ], where:
	 * - heading is the heading of a navigation portlet. It is either:
	 *   - magic string to be handled by the skins ('SEARCH' / 'LANGUAGES' / 'TOOLBOX' / ...)
	 *     (Note that 'SEARCH' unlike others is not supported out-of-the-box by the skins.
	 *     For it to work, a skin must add custom support for it.)
	 *   - a message name (e.g. 'navigation'), the message should be HTML-escaped by the skin
	 *   - plain text, which should be HTML-escaped by the skin
	 * - content is the contents of the portlet.
	 *   - For keys that aren't magic strings, this is an array of link data, where the
	 *     array items are arrays in the format expected by the $item parameter of
	 *     {@link self::makeListItem()}.
	 *   - For magic strings, the format varies. For LANGUAGES and TOOLBOX it is the same as above;
	 *     for SEARCH the value will be ignored.
	 *
	 * Note that extensions can control the sidebar contents using the SkinBuildSidebar hook
	 * and can technically insert anything in here; skin creators are expected to handle
	 * values described above.
	 *
	 * @return array
	 */
	public function buildSidebar() {
		if ( $this->sidebar === null ) {
			$services = MediaWikiServices::getInstance();
			$callback = function ( $old = null, &$ttl = null ) {
				$bar = [];
				$this->addToSidebar( $bar, 'sidebar' );

				// This hook may vary its behaviour by skin.
				$this->getHookRunner()->onSkinBuildSidebar( $this, $bar );
				$msgCache = MediaWikiServices::getInstance()->getMessageCache();
				if ( $msgCache->isDisabled() ) {
					// Don't cache the fallback if DB query failed. T133069
					$ttl = WANObjectCache::TTL_UNCACHEABLE;
				}

				return $bar;
			};

			$msgCache = $services->getMessageCache();
			$wanCache = $services->getMainWANObjectCache();
			$config = $this->getConfig();
			$languageCode = $this->getLanguage()->getCode();

			$sidebar = $config->get( MainConfigNames::EnableSidebarCache )
				? $wanCache->getWithSetCallback(
					$wanCache->makeKey( 'sidebar', $languageCode, $this->getSkinName() ?? '' ),
					$config->get( MainConfigNames::SidebarCacheExpiry ),
					$callback,
					[
						'checkKeys' => [
							// Unless there is both no exact $code override nor an i18n definition
							// in the software, the only MediaWiki page to check is for $code.
							$msgCache->getCheckKey( $languageCode )
						],
						'lockTSE' => 30
					]
				)
				: $callback();

			$sidebar['TOOLBOX'] = array_merge(
				$this->makeToolbox(
					$this->buildNavUrls(),
					$this->buildFeedUrls()
				), $sidebar['TOOLBOX'] ?? []
			);

			$sidebar['LANGUAGES'] = $this->getLanguages();
			// Apply post-processing to the cached value
			$this->getHookRunner()->onSidebarBeforeOutput( $this, $sidebar );

			$this->sidebar = $sidebar;

			$this->appendSpecialPagesLinkIfAbsent();
		}

		return $this->sidebar;
	}

	/**
	 * Add content from a sidebar system message
	 * Currently only used for MediaWiki:Sidebar (but may be used by Extensions)
	 *
	 * This is just a wrapper around addToSidebarPlain() for backwards compatibility
	 *
	 * @param array &$bar
	 * @param string $message
	 */
	public function addToSidebar( &$bar, $message ) {
		$this->addToSidebarPlain( $bar, $this->msg( $message )->inContentLanguage()->plain() );
	}

	/**
	 * Generates an array item for the sidebar
	 * @param string $target Target link in the form of an interface message name, a wiki page name, or an external link
	 * @param string $text Link display text in the form of an interface message name or plaintext
	 * @return array|null Null if no sidebar item should be added; the array item otherwise
	 */
	private function createSidebarItem( $target, $text ) {
		$config = $this->getConfig();
		$messageTitle = $config->get( MainConfigNames::EnableSidebarCache )
			? Title::newMainPage() : $this->getTitle();
		$services = MediaWikiServices::getInstance();
		$urlUtils = $services->getUrlUtils();

		$extraAttribs = [];

		$msgLink = $this->msg( $target )->page( $messageTitle )->inContentLanguage();
		if ( $msgLink->exists() ) {
			$link = $msgLink->text();
			// Extra check in case a message does fancy stuff with {{#if:… and such
			if ( $link === '-' ) {
				return null;
			}
		} else {
			$link = $target;
		}
		$msgText = $this->msg( $text )->page( $messageTitle );
		if ( $msgText->exists() ) {
			$parsedText = $msgText->text();
		} else {
			$parsedText = $text;
		}

		if ( preg_match( '/^(?i:' . $urlUtils->validProtocols() . ')/', $link ) ) {
			$href = $link;

			// Parser::getExternalLinkAttribs won't work here because of the Namespace things
			if ( $config->get( MainConfigNames::NoFollowLinks ) &&
				!$urlUtils->matchesDomainList(
					(string)$href,
					(array)$config->get( MainConfigNames::NoFollowDomainExceptions )
				)
			) {
				$extraAttribs['rel'] = 'nofollow';
			}

			if ( $config->get( MainConfigNames::ExternalLinkTarget ) ) {
				$extraAttribs['target'] =
					$config->get( MainConfigNames::ExternalLinkTarget );
			}
		} else {
			$title = Title::newFromText( $link );
			$href = $title ? $title->fixSpecialName()->getLinkURL() : '';
		}

		$id = strtr( $text, ' ', '-' );
		return array_merge( [
			'text' => $parsedText,
			'href' => $href,
			'icon' => $this->getSidebarIcon( $id ),
			'id' => Sanitizer::escapeIdForAttribute( 'n-' . $id ),
			'active' => false,
		], $extraAttribs );
	}

	/**
	 * Add content from plain text
	 * @since 1.17
	 * @param array &$bar
	 * @param string $text
	 * @return array
	 */
	public function addToSidebarPlain( &$bar, $text ) {
		$lines = explode( "\n", $text );

		$heading = '';
		$config = $this->getConfig();
		$messageTitle = $config->get( MainConfigNames::EnableSidebarCache )
			? Title::newMainPage() : $this->getTitle();
		$services = MediaWikiServices::getInstance();
		$messageParser = $services->getMessageParser();
		$urlUtils = $services->getUrlUtils();

		foreach ( $lines as $line ) {
			if ( strpos( $line, '*' ) !== 0 ) {
				continue;
			}
			$line = rtrim( $line, "\r" ); // for Windows compat

			if ( strpos( $line, '**' ) !== 0 ) {
				$heading = trim( $line, '* ' );
				if ( !array_key_exists( $heading, $bar ) ) {
					$bar[$heading] = [];
				}
			} else {
				$line = trim( $line, '* ' );

				if ( strpos( $line, '|' ) !== false ) {
					$line = $messageParser->transform( $line, false, null, $messageTitle );
					$line = array_map( 'trim', explode( '|', $line, 2 ) );
					if ( count( $line ) !== 2 ) {
						// Second check, could be hit by people doing
						// funky stuff with parserfuncs... (T35321)
						continue;
					}

					$item = $this->createSidebarItem( $line[0], $line[1] );
					if ( $item !== null ) {
						$bar[$heading][] = $item;
					}
				}
			}
		}

		return $bar;
	}

	/**
	 * @param string $id the id of the menu
	 * @return string|null the icon glyph name to associate with this menu
	 */
	private function getSidebarIcon( string $id ) {
		switch ( $id ) {
			case 'mainpage-description':
				return 'home';
			case 'randompage':
				return 'die';
			case 'recentchanges':
				return 'recentChanges';
			// These menu items are commonly added in MediaWiki:Sidebar. We should
			// reconsider the location of this logic in future.
			case 'help':
			case 'help-mediawiki':
				return 'help';
			default:
				return null;
		}
	}

	/**
	 * Check whether to allow new talk page notifications for the current request.
	 *
	 * The client might be reading without a session cookie from an IP that matches
	 * a previous IP editor. When clients without a session visit a page with a CDN miss,
	 * we must not embed personal notifications, as doing so might leak personal details
	 * (if Cache-Control is public), or risk an outage per T350861 (if max-age=0).
	 *
	 * From an end-user perspective, this has the added benefit of not randomly showing
	 * notifications to readers (on page views that happen to be a CDN miss) when
	 * sharing an IP with an editor. Notifying clients without a session is not reliably
	 * possible, as their requests are usually CDN hits.
	 *
	 * @see https://phabricator.wikimedia.org/T350861
	 * @return bool
	 */
	private function hideNewTalkMessagesForCurrentSession() {
		// Only show new talk page notification if there is a session,
		// (the client edited a page from this browser, or is logged-in).
		return !$this->getRequest()->getSession()->isPersistent();
	}

	/**
	 * Gets new talk page messages for the current user and returns an
	 * appropriate alert message (or an empty string if there are no messages)
	 * @return string
	 */
	public function getNewtalks() {
		if ( $this->hideNewTalkMessagesForCurrentSession() ) {
			return '';
		}

		$newMessagesAlert = '';
		$user = $this->getUser();
		$services = MediaWikiServices::getInstance();
		$linkRenderer = $services->getLinkRenderer();
		$userHasNewMessages = $services->getTalkPageNotificationManager()
			->userHasNewMessages( $user );
		$timestamp = $services->getTalkPageNotificationManager()
			->getLatestSeenMessageTimestamp( $user );
		$newtalks = !$userHasNewMessages ? [] : [
			[
			// TODO: Deprecate adding wiki and link to array and redesign GetNewMessagesAlert hook
			'wiki' => WikiMap::getCurrentWikiId(),
			'link' => $user->getTalkPage()->getLocalURL(),
			'rev' => $timestamp ? $services->getRevisionLookup()
				->getRevisionByTimestamp( $user->getTalkPage(), $timestamp ) : null
			]
		];
		$out = $this->getOutput();

		// Allow extensions to disable or modify the new messages alert
		if ( !$this->getHookRunner()->onGetNewMessagesAlert(
			$newMessagesAlert, $newtalks, $user, $out )
		) {
			return '';
		}
		if ( $newMessagesAlert ) {
			return $newMessagesAlert;
		}

		if ( $newtalks !== [] ) {
			$uTalkTitle = $user->getTalkPage();
			$lastSeenRev = $newtalks[0]['rev'];
			$numAuthors = 0;
			if ( $lastSeenRev !== null ) {
				$plural = true; // Default if we have a last seen revision: if unknown, use plural
				$revStore = $services->getRevisionStore();
				$latestRev = $revStore->getRevisionByTitle(
					$uTalkTitle,
					0,
					IDBAccessObject::READ_NORMAL
				);
				if ( $latestRev !== null ) {
					// Singular if only 1 unseen revision, plural if several unseen revisions.
					$plural = $latestRev->getParentId() !== $lastSeenRev->getId();
					$numAuthors = $revStore->countAuthorsBetween(
						$uTalkTitle->getArticleID(),
						$lastSeenRev,
						$latestRev,
						null,
						10,
						RevisionStore::INCLUDE_NEW
					);
				}
			} else {
				// Singular if no revision -> diff link will show latest change only in any case
				$plural = false;
			}
			$plural = $plural ? 999 : 1;
			// 999 signifies "more than one revision". We don't know how many, and even if we did,
			// the number of revisions or authors is not necessarily the same as the number of
			// "messages".
			$newMessagesLink = $linkRenderer->makeKnownLink(
				$uTalkTitle,
				$this->msg( 'new-messages-link-plural' )->params( $plural )->text(),
				[],
				$uTalkTitle->isRedirect() ? [ 'redirect' => 'no' ] : []
			);

			$newMessagesDiffLink = $linkRenderer->makeKnownLink(
				$uTalkTitle,
				$this->msg( 'new-messages-diff-link-plural' )->params( $plural )->text(),
				[],
				$lastSeenRev !== null
					? [ 'oldid' => $lastSeenRev->getId(), 'diff' => 'cur' ]
					: [ 'diff' => 'cur' ]
			);

			if ( $numAuthors >= 1 && $numAuthors <= 10 ) {
				$newMessagesAlert = $this->msg(
					'new-messages-from-users'
				)->rawParams(
					$newMessagesLink,
					$newMessagesDiffLink
				)->numParams(
					$numAuthors,
					$plural
				);
			} else {
				// $numAuthors === 11 signifies "11 or more" ("more than 10")
				$newMessagesAlert = $this->msg(
					$numAuthors > 10 ? 'new-messages-from-many-users' : 'new-messages'
				)->rawParams(
					$newMessagesLink,
					$newMessagesDiffLink
				)->numParams( $plural );
			}
			$newMessagesAlert = $newMessagesAlert->parse();
		}

		return $newMessagesAlert;
	}

	/**
	 * Get a cached notice
	 *
	 * @param string $name Message name, or 'default' for $wgSiteNotice
	 * @return string|false HTML fragment, or false to indicate that the caller
	 *   should fall back to the next notice in its sequence
	 */
	private function getCachedNotice( $name ) {
		$config = $this->getConfig();

		if ( $name === 'default' ) {
			// special case
			$notice = $config->get( MainConfigNames::SiteNotice );
			if ( !$notice ) {
				return false;
			}
		} else {
			$msg = $this->msg( $name )->inContentLanguage();
			if ( $msg->isBlank() ) {
				return '';
			} elseif ( $msg->isDisabled() ) {
				return false;
			}
			$notice = $msg->plain();
		}

		$services = MediaWikiServices::getInstance();
		$cache = $services->getMainWANObjectCache();
		$parsed = $cache->getWithSetCallback(
			// Use the extra hash appender to let eg SSL variants separately cache
			// Key is verified with md5 hash of unparsed wikitext
			$cache->makeKey(
				$name, $config->get( MainConfigNames::RenderHashAppend ), md5( $notice ) ),
			// TTL in seconds
			600,
			function () use ( $notice ) {
				return $this->getOutput()->parseAsInterface( $notice );
			}
		);

		$contLang = $services->getContentLanguage();
		return Html::rawElement(
			'div',
			[
				'class' => $name,
				'lang' => $contLang->getHtmlCode(),
				'dir' => $contLang->getDir()
			],
			$parsed
		);
	}

	/**
	 * @return string HTML fragment
	 */
	public function getSiteNotice() {
		$siteNotice = '';

		if ( $this->getHookRunner()->onSiteNoticeBefore( $siteNotice, $this ) ) {
			if ( $this->getUser()->isRegistered() ) {
				$siteNotice = $this->getCachedNotice( 'sitenotice' );
			} else {
				$anonNotice = $this->getCachedNotice( 'anonnotice' );
				if ( $anonNotice === false ) {
					$siteNotice = $this->getCachedNotice( 'sitenotice' );
				} else {
					$siteNotice = $anonNotice;
				}
			}
			if ( $siteNotice === false ) {
				$siteNotice = $this->getCachedNotice( 'default' ) ?: '';
			}
			if ( $this->canUseWikiPage() ) {
				$ns = $this->getWikiPage()->getNamespace();
				$nsNotice = $this->getCachedNotice( "namespacenotice-$ns" );
				if ( $nsNotice ) {
					$siteNotice .= $nsNotice;
				}
			}
			if ( $siteNotice !== '' ) {
				$siteNotice = Html::rawElement( 'div', [ 'id' => 'localNotice', 'data-nosnippet' => '' ], $siteNotice );
			}
		}

		$this->getHookRunner()->onSiteNoticeAfter( $siteNotice, $this );
		if ( $this->getOptions()[ 'wrapSiteNotice' ] ) {
			$siteNotice = Html::rawElement( 'div', [ 'id' => 'siteNotice' ], $siteNotice );
		}
		return $siteNotice;
	}

	/**
	 * Create a section edit link.
	 *
	 * @param Title $nt The title being linked to (may not be the same as
	 *   the current page, if the section is included from a template)
	 * @param string $section The designation of the section being pointed to,
	 *   to be included in the link, like "&section=$section"
	 * @param string $sectionTitle Section title. It is used in the link tooltip, escaped and
	 *   wrapped in the 'editsectionhint' message
	 * @param Language $lang
	 * @return string HTML to use for edit link
	 */
	public function doEditSectionLink( Title $nt, $section, $sectionTitle, Language $lang ) {
		// HTML generated here should probably have userlangattributes
		// added to it for LTR text on RTL pages

		$attribs = [];
		$attribs['title'] = $this->msg( 'editsectionhint' )->plaintextParams( $sectionTitle )
			->inLanguage( $lang )->text();

		$links = [
			'editsection' => [
				'icon' => 'edit',
				'text' => $this->msg( 'editsection' )->inLanguage( $lang )->text(),
				'targetTitle' => $nt,
				'attribs' => $attribs,
				'query' => [ 'action' => 'edit', 'section' => $section ]
			]
		];

		$this->getHookRunner()->onSkinEditSectionLinks( $this, $nt, $section, $sectionTitle, $links, $lang );

		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		$newLinks = [];
		$options = $this->defaultLinkOptions + [
			'class-as-property' => true,
		];
		$ctx = $this->getContext();
		foreach ( $links as $key => $linkDetails ) {
			$targetTitle = $linkDetails['targetTitle'];
			$attrs = $linkDetails['attribs'];
			$query = $linkDetails['query'];
			unset( $linkDetails['targetTitle'] );
			unset( $linkDetails['query'] );
			unset( $linkDetails['attribs'] );
			unset( $linkDetails['options' ] );
			$component = new SkinComponentLink(
				$key, $linkDetails + [
					'href' => Title::newFromLinkTarget( $targetTitle )->getLinkURL( $query, false ),
				] + $attrs, $ctx, $options
			);
			$newLinks[] = $component->getTemplateData();
		}
		return $this->doEditSectionLinksHTML( $newLinks, $lang );
	}

	/**
	 * @stable to override by skins
	 *
	 * @param array $links
	 * @param Language $lang
	 * @return string
	 */
	protected function doEditSectionLinksHTML( array $links, Language $lang ) {
		$result = Html::openElement( 'span', [ 'class' => 'mw-editsection' ] );
		$result .= Html::rawElement( 'span', [ 'class' => 'mw-editsection-bracket' ], '[' );

		$linksHtml = [];
		foreach ( $links as $linkDetails ) {
			$linksHtml[] = $linkDetails['html'];
		}

		if ( count( $linksHtml ) === 1 ) {
			$result .= $linksHtml[0];
		} else {
			$result .= implode(
				Html::rawElement(
					'span',
					[ 'class' => 'mw-editsection-divider' ],
					$this->msg( 'pipe-separator' )->inLanguage( $lang )->escaped()
				),
				$linksHtml
			);
		}

		$result .= Html::rawElement( 'span', [ 'class' => 'mw-editsection-bracket' ], ']' );
		$result .= Html::closeElement( 'span' );
		return $result;
	}

	/**
	 * Create an array of common toolbox items from the data in the quicktemplate
	 * stored by SkinTemplate.
	 * The resulting array is built according to a format intended to be passed
	 * through makeListItem to generate the html.
	 * @param array $navUrls
	 * @param array $feedUrls
	 * @return array
	 */
	public function makeToolbox( $navUrls, $feedUrls ) {
		$toolbox = [];
		if ( $navUrls['whatlinkshere'] ?? null ) {
			$toolbox['whatlinkshere'] = $navUrls['whatlinkshere'];
			$toolbox['whatlinkshere']['id'] = 't-whatlinkshere';
			$toolbox['whatlinkshere']['icon'] = 'articleRedirect';
		}
		if ( $navUrls['recentchangeslinked'] ?? null ) {
			$toolbox['recentchangeslinked'] = $navUrls['recentchangeslinked'];
			$toolbox['recentchangeslinked']['msg'] = 'recentchangeslinked-toolbox';
			$toolbox['recentchangeslinked']['id'] = 't-recentchangeslinked';
			$toolbox['recentchangeslinked']['rel'] = 'nofollow';
		}
		if ( $feedUrls ) {
			$toolbox['feeds']['id'] = 'feedlinks';
			$toolbox['feeds']['links'] = [];
			foreach ( $feedUrls as $key => $feed ) {
				$toolbox['feeds']['links'][$key] = $feed;
				$toolbox['feeds']['links'][$key]['id'] = "feed-$key";
				$toolbox['feeds']['links'][$key]['rel'] = 'alternate';
				$toolbox['feeds']['links'][$key]['type'] = "application/{$key}+xml";
				$toolbox['feeds']['links'][$key]['class'] = 'feedlink';
			}
		}
		foreach ( [ 'contributions', 'log', 'blockip', 'changeblockip', 'unblockip',
			'block-manage-blocks', 'emailuser', 'mute', 'userrights', 'upload' ] as $special
		) {
			if ( $navUrls[$special] ?? null ) {
				$toolbox[$special] = $navUrls[$special];
				$toolbox[$special]['id'] = "t-$special";
			}
		}
		if ( $navUrls['print'] ?? null ) {
			$toolbox['print'] = $navUrls['print'];
			$toolbox['print']['id'] = 't-print';
			$toolbox['print']['rel'] = 'alternate';
			$toolbox['print']['msg'] = 'printableversion';
		}
		if ( $navUrls['permalink'] ?? null ) {
			$toolbox['permalink'] = $navUrls['permalink'];
			$toolbox['permalink']['id'] = 't-permalink';
		}
		if ( $navUrls['info'] ?? null ) {
			$toolbox['info'] = $navUrls['info'];
			$toolbox['info']['id'] = 't-info';
		}

		return $toolbox;
	}

	/**
	 * Return an array of indicator data.
	 * Can be used by subclasses but should not be extended.
	 * @param array<string,string> $indicators return value of OutputPage::getIndicators
	 * @return array<array{id: string, class: string, html: string}>
	 */
	protected function getIndicatorsData( array $indicators ): array {
		$indicatorData = [];
		foreach ( $indicators as $id => $content ) {
			$indicatorData[] = [
				'id' => Sanitizer::escapeIdForAttribute( "mw-indicator-$id" ),
				'class' => 'mw-indicator',
				'html' => $content,
			];
		}
		return $indicatorData;
	}

	/**
	 * Create an array of personal tools items from the data in the quicktemplate
	 * stored by SkinTemplate.
	 * The resulting array is built according to a format intended to be passed
	 * through makeListItem to generate the html.
	 * This is in reality the same list as already stored in personal_urls
	 * however it is reformatted so that you can just pass the individual items
	 * to makeListItem instead of hardcoding the element creation boilerplate.
	 * @since 1.35
	 * @param array $urls
	 * @param bool $applyClassesToListItems (optional) when set behaves consistently with other menus,
	 *   applying the `class` property applies to list items. When not set will move the class to child links.
	 * @return array[]
	 */
	final public function getPersonalToolsForMakeListItem( $urls, $applyClassesToListItems = false ) {
		$personal_tools = [];
		foreach ( $urls as $key => $plink ) {
			# The class on a personal_urls item is meant to go on the <a> instead
			# of the <li> so we have to use a single item "links" array instead
			# of using most of the personal_url's keys directly.
			$ptool = [
				'links' => [
					[ 'single-id' => "pt-$key" ],
				],
				'id' => "pt-$key",
				'icon' => $plink[ 'icon' ] ?? null,
			];
			if ( $applyClassesToListItems && isset( $plink['class'] ) ) {
				$ptool['class'] = $plink['class'];
			}
			if ( isset( $plink['active'] ) ) {
				$ptool['active'] = $plink['active'];
			}
			// Set class for the link to link-class, when defined.
			// This allows newer notifications content navigation to retain their classes
			// when merged back into the personal tools.
			// Doing this here allows the loop below to overwrite the class if defined directly.
			if ( isset( $plink['link-class'] ) ) {
				$ptool['links'][0]['class'] = $plink['link-class'];
			}
			$props = [
				'href',
				'text',
				'dir',
				'data',
				'exists',
				'data-mw',
				'link-html',
			];
			if ( !$applyClassesToListItems ) {
				$props[] = 'class';
			}
			foreach ( $props as $k ) {
				if ( isset( $plink[$k] ) ) {
					$ptool['links'][0][$k] = $plink[$k];
				}
			}
			$personal_tools[$key] = $ptool;
		}
		return $personal_tools;
	}

	/**
	 * Makes a link, usually used by makeListItem to generate a link for an item
	 * in a list used in navigation lists, portlets, portals, sidebars, etc...
	 *
	 * @since 1.35
	 * @param string $key Usually a key from the list you are generating this
	 * link from.
	 * @param array $item Contains some of a specific set of keys.
	 *
	 * If "html" key is present, this will be returned. All other keys will be ignored.
	 *
	 * The text of the link will be generated either from the contents of the
	 * "text" key in the $item array, if a "msg" key is present a message by
	 * that name will be used, and if neither of those are set the $key will be
	 * used as a message name.
	 *
	 * If a "href" key is not present makeLink will just output htmlescaped text.
	 * The "href", "id", "class", "rel", and "type" keys are used as attributes
	 * for the link if present.
	 *
	 * If an "id" or "single-id" (if you don't want the actual id to be output
	 * on the link) is present it will be used to generate a tooltip and
	 * accesskey for the link.
	 *
	 * The 'link-html' key can be used to prepend additional HTML inside the link HTML.
	 * For example to prepend an icon.
	 *
	 * The keys "context" and "primary" are ignored; these keys are used
	 * internally by skins and are not supposed to be included in the HTML
	 * output.
	 *
	 * If you don't want an accesskey, set $item['tooltiponly'] = true;
	 *
	 * If a "data" key is present, it must be an array, where the keys represent
	 * the data-xxx properties with their provided values. For example,
	 *     $item['data'] = [
	 *       'foo' => 1,
	 *       'bar' => 'baz',
	 *     ];
	 * will render as element properties:
	 *     data-foo='1' data-bar='baz'
	 *
	 * The "class" key currently accepts both a string and an array of classes, but this will be
	 * changed to only accept an array in the future.
	 *
	 * @param array $linkOptions Can be used to affect the output of a link.
	 * Possible options are:
	 *   - 'text-wrapper' key to specify a list of elements to wrap the text of
	 *   a link in. This should be an array of arrays containing a 'tag' and
	 *   optionally an 'attributes' key. If you only have one element you don't
	 *   need to wrap it in another array. eg: To use <a><span>...</span></a>
	 *   in all links use [ 'text-wrapper' => [ 'tag' => 'span' ] ]
	 *   for your options.
	 *   - 'link-class' key can be used to specify additional classes to apply
	 *   to all links.
	 *   - 'link-fallback' can be used to specify a tag to use instead of "<a>"
	 *   if there is no link. eg: If you specify 'link-fallback' => 'span' than
	 *   any non-link will output a "<span>" instead of just text.
	 *
	 * @return string
	 */
	final public function makeLink( $key, $item, $linkOptions = [] ) {
		$options = $linkOptions + $this->defaultLinkOptions;
		$component = new SkinComponentLink(
			$key, $item, $this->getContext(), $options
		);
		return $component->getTemplateData()[ 'html' ];
	}

	/**
	 * Generates a list item for a navigation, portlet, portal, sidebar... list
	 *
	 * @since 1.35
	 * @param string $key Usually a key from the list you are generating this link from.
	 * @param array $item Array of list item data containing some of a specific set of keys.
	 *   The "id", "class" and "itemtitle" keys will be used as attributes for the list item,
	 *   if "active" contains a value of true an "active" class will also be appended to class.
	 *   The "class" key currently accepts both a string and an array of classes, but this will be
	 *   changed to only accept an array in the future.
	 *   For further options see the $item parameter of {@link SkinComponentLink::makeLink()}.
	 * @phan-param array{id?:string,html?:string,class?:string|string[],itemtitle?:string,active?:bool} $item
	 *
	 * @param array $options
	 * @phan-param array{tag?:string} $options
	 *
	 * If you want something other than a "<li>" you can pass a tag name such as
	 * "tag" => "span" in the $options array to change the tag used.
	 * link/content data for the list item may come in one of two forms
	 * A "links" key may be used, in which case it should contain an array with
	 * a list of links to include inside the list item, see makeLink for the
	 * format of individual links array items.
	 *
	 * Otherwise the relevant keys from the list item $item array will be passed
	 * to makeLink instead. Note however that "id" and "class" are used by the
	 * list item directly so they will not be passed to makeLink
	 * (however the link will still support a tooltip and accesskey from it)
	 * If you need an id or class on a single link you should include a "links"
	 * array with just one link item inside of it. You can also set "link-class" in
	 * $item to set a class on the link itself. If you want to add a title
	 * to the list item itself, you can set "itemtitle" to the value.
	 * $options is also passed on to makeLink calls
	 *
	 * @return string
	 */
	final public function makeListItem( $key, $item, $options = [] ) {
		$component = new SkinComponentListItem(
			$key, $item, $this->getContext(), $options, $this->defaultLinkOptions
		);
		return $component->getTemplateData()[ 'html-item' ];
	}

	/**
	 * Allows extensions to hook into known portlets and add stuff to them.
	 * Unlike its BaseTemplate counterpart, this method does not wrap the html
	 * provided by the hook in a div.
	 *
	 * @param string $name
	 *
	 * @return string html
	 * @since 1.35
	 */
	public function getAfterPortlet( string $name ): string {
		$html = '';

		$this->getHookRunner()->onSkinAfterPortlet( $this, $name, $html );

		return $html;
	}

	/**
	 * Prepare the subtitle of the page for output in the skin if one has been set.
	 * @since 1.35
	 * @param bool $withContainer since 1.40, when provided the mw-content-subtitle element will be output too.
	 * @return string HTML
	 */
	final public function prepareSubtitle( bool $withContainer = true ) {
		$out = $this->getOutput();
		$subpagestr = $this->subPageSubtitleInternal();
		if ( $subpagestr !== '' ) {
			$subpagestr = Html::rawElement( 'div', [ 'class' => 'subpages' ], $subpagestr );
		}
		$html = $subpagestr . $out->getSubtitle();
		return $withContainer ? Html::rawElement( 'div', [
			'id' => 'mw-content-subtitle',
		] + $this->getUserLanguageAttributes(), $html ) : $html;
	}

	/**
	 * Returns array of config variables that should be added only to this skin
	 * for use in JavaScript.
	 * Skins can override this to add variables to the page.
	 * @since 1.38 or 1.35 if extending SkinTemplate.
	 * @return array
	 */
	protected function getJsConfigVars(): array {
		return [];
	}

	/**
	 * Get user language attribute links array
	 *
	 * @return array HTML attributes
	 */
	final protected function getUserLanguageAttributes() {
		$userLang = $this->getLanguage();
		$userLangCode = $userLang->getHtmlCode();
		$userLangDir = $userLang->getDir();
		$contLang = MediaWikiServices::getInstance()->getContentLanguage();
		if (
			$userLangCode !== $contLang->getHtmlCode() ||
			$userLangDir !== $contLang->getDir()
		) {
			return [
				'lang' => $userLangCode,
				'dir' => $userLangDir,
			];
		}
		return [];
	}

	/**
	 * Prepare user language attribute links
	 * @since 1.38 on Skin and 1.35 on classes extending SkinTemplate
	 * @return string HTML attributes
	 */
	final protected function prepareUserLanguageAttributes() {
		return Html::expandAttributes(
			$this->getUserLanguageAttributes()
		);
	}

	/**
	 * Prepare undelete link for output in page.
	 * @since 1.38 on Skin and 1.35 on classes extending SkinTemplate
	 * @return null|string HTML, or null if there is no undelete link.
	 */
	final protected function prepareUndeleteLink() {
		$undelete = $this->getUndeleteLink();
		return $undelete === '' ? null : '<div class="subpages">' . $undelete . '</div>';
	}

	/**
	 * Wrap the body text with language information and identifiable element
	 *
	 * @since 1.38 in Skin, previously was a method of SkinTemplate
	 * @param Title $title
	 * @param string $html body text
	 * @return string html
	 */
	protected function wrapHTML( $title, $html ) {
		// This wraps the "real" body content (i.e. parser output or special page).
		// On page views, elements like categories and contentSub are outside of this.
		return Html::rawElement( 'div', [
			'id' => 'mw-content-text',
			'class' => [
				'mw-body-content',
			],
		], $html );
	}

	/**
	 * Get current skin's options
	 *
	 * For documentation about supported options, refer to the Skin constructor.
	 *
	 * @internal Please call SkinFactory::getSkinOptions instead. See Skin::__construct for documentation.
	 * @return array
	 */
	final public function getOptions(): array {
		return $this->options + [
			'styles' => [],
			'scripts' => [],
			'toc' => true,
			'format' => 'html',
			'bodyClasses' => [],
			'clientPrefEnabled' => false,
			'responsive' => false,
			'supportsMwHeading' => false,
			'link' => [],
			'tempUserBanner' => false,
			'wrapSiteNotice' => false,
			'menus' => [
				// Legacy keys that are enabled by default for backwards compatibility
				'namespaces',
				'views',
				'actions',
				'variants',
				// Opt-in menus
				// * 'associated-pages'
				// * 'notifications'
				// * 'user-interface-preferences',
				// * 'user-page',
				// * 'user-menu',
			]
		];
	}

	/**
	 * Does the skin support the named menu? e.g. has it declared that it
	 * will render a menu with the given ID?
	 *
	 * @since 1.39
	 * @param string $menu See Skin::__construct for menu names.
	 * @return bool
	 */
	public function supportsMenu( string $menu ): bool {
		$options = $this->getOptions();
		return in_array( $menu, $options['menus'] );
	}

	/**
	 * Returns skin options for portlet links, used by addPortletLink
	 *
	 * @internal
	 * @param RL\Context $context
	 * @return array $linkOptions
	 *   - 'text-wrapper' key to specify a list of elements to wrap the text of
	 *   a link in. This should be an array of arrays containing a 'tag' and
	 *   optionally an 'attributes' key. If you only have one element you don't
	 *   need to wrap it in another array. eg: To use <a><span>...</span></a>
	 *   in all links use [ 'text-wrapper' => [ 'tag' => 'span' ] ]
	 *   for your options. If text-wrapper contains multiple entries they are
	 *   interpreted as going from the outer wrapper to the inner wrapper.
	 */
	public static function getPortletLinkOptions( RL\Context $context ): array {
		$skinName = $context->getSkin();
		$skinFactory = MediaWikiServices::getInstance()->getSkinFactory();
		$options = $skinFactory->getSkinOptions( $skinName );
		$portletLinkOptions = $options['link'] ?? [];
		// Normalize link options to always have this key
		$portletLinkOptions += [ 'text-wrapper' => [] ];
		// Normalize text-wrapper to always be an array of arrays
		if ( isset( $portletLinkOptions['text-wrapper']['tag'] ) ) {
			$portletLinkOptions['text-wrapper'] = [ $portletLinkOptions['text-wrapper'] ];
		}
		return $portletLinkOptions;
	}

	/**
	 * @param string $name of the portal e.g. p-personal the name is personal.
	 * @param array $items that are accepted input to Skin::makeListItem
	 *
	 * @return array data that can be passed to a Mustache template that
	 *   represents a single menu.
	 */
	final protected function getPortletData( string $name, array $items ): array {
		$portletComponent = new SkinComponentMenu(
			$name,
			$items,
			$this->getContext(),
			'',
			$this->defaultLinkOptions,
			$this->getAfterPortlet( $name )
		);
		return $portletComponent->getTemplateData();
	}
}

/** @deprecated class alias since 1.44 */
class_alias( Skin::class, 'Skin' );
