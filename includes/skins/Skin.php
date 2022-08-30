<?php
/**
 * Base class for all skins.
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
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\ResourceLoader as RL;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Skin\SkinComponent;
use MediaWiki\Skin\SkinComponentRegistry;
use MediaWiki\Skin\SkinComponentRegistryContext;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\WrappedStringList;

/**
 * @defgroup Skins Skins
 */

/**
 * The main skin class which provides methods and properties for all other skins.
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
	private $defaultLinkOptions = [];

	/**
	 * @var string|null
	 */
	protected $skinname = null;

	/**
	 * @var array Skin options passed into constructor
	 */
	protected $options = [];
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
	 * Subclasses may extend this method to add additional
	 * template data.
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

			// Treat as config and get from content language
			$titleMsg->inContentLanguage();
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
			'html-title' => $htmlTitle,
			// Boolean values
			'is-title-blank' => $blankedHeading, // @since 1.38
			'is-anon' => $user->isAnon(),
			'is-article' => $out->isArticle(),
			'is-mainpage' => $isMainPage,
			'is-specialpage' => $title->isSpecialPage(),
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
	public static function normalizeKey( $key ) {
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
	 *  - `name`: Internal skin name, generally in lowercase to comply with conventions
	 *     for interface message keys and CSS class names which embed this value.
	 *  - `scripts`: An array of ResourceLoader script modules.
	 *  - `styles`: An array of ResourceLoader style modules to load on all pages.
	 *  - `responsive`: Whether the skin supports responsive behaviour and wants a viewport meta
	 *     tag to be added to the HTML head. Note, users can disable this feature via a user
	 *     preference.
	 *  - `link`: An array of link options that will be used in Skin::makeLink calls.
	 *  - `toc`: Whether a table of contents is included in the main article content
	 *     area.  It defaults to `true`, but if your skins wants to place a table of
	 *     contents elsewhere (for example, in a sidebar), set `toc` to `false`.
	 */
	public function __construct( $options = null ) {
		if ( is_string( $options ) ) {
			$this->skinname = $options;
		} elseif ( $options ) {
			$name = $options['name'] ?? null;

			if ( !$name ) {
				throw new SkinException( 'Skin name must be specified' );
			}

			if ( isset( $options['link'] ) ) {
				$this->defaultLinkOptions = $options['link'];
			}
			// Defaults are set in Skin::getOptions()
			$this->options = $options;
			$this->skinname = $name;
		}
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
		$isSkinResponsiveCapable = $this->options['responsive'] ?? false;
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
		$this->preloadExistence();

		if ( $this->isResponsive() ) {
			$out->addMeta(
				'viewport',
				'width=device-width, initial-scale=1.0, ' .
				'user-scalable=yes, minimum-scale=0.25, maximum-scale=5.0'
			);
		} else {
			// Width is based on the value of @width-breakpoint-desktop
			// This is as @width-breakpoint-desktop-wide usually tends to optimize
			// for larger screens with max-widths and margins.
			// The initial-scale SHOULD NOT be set here as defining it will impact zoom
			// on mobile devices. To allow font-size adjustment in iOS devices (see T311795)
			// we will define a zoom in JavaScript on certain devices (see resources/src/mediawiki.page.ready/ready.js)
			$out->addMeta(
				'viewport',
				'width=1000'
			);
		}

		$tags = [
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
				'skin' => $this->options['styles'] ?? [],
				'core' => [],
				'content' => [],
				'syndicate' => [],
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
			'skin' => $this->options['scripts'] ?? [],
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

		// Deprecated since 1.26: Unconditional loading of mediawiki.ui.button
		// on every page is deprecated. Express a dependency instead.
		if ( strpos( $out->getHTML(), 'mw-ui-button' ) !== false ) {
			$modules['styles']['content'][] = 'mediawiki.ui.button';
		}

		if ( $out->isTOCEnabled() ) {
			$modules['content'][] = 'mediawiki.toc';
		}

		$authority = $this->getAuthority();
		if ( $authority->getUser()->isRegistered()
			&& $authority->isAllowedAll( 'writeapi', 'viewmywatchlist', 'editmywatchlist' )
			&& $this->getRelevantTitle()->canExist()
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
		if ( $title->canExist() && $title->canHaveTalkPage() ) {
			$namespaceInfo = MediaWikiServices::getInstance()->getNamespaceInfo();
			if ( $title->isTalkPage() ) {
				$titles[] = $namespaceInfo->getSubjectPage( $title );
			} else {
				$titles[] = $namespaceInfo->getTalkPage( $title );
			}
		}

		$this->getHookRunner()->onSkinPreloadExistence( $titles, $this );

		if ( $titles ) {
			$linkBatchFactory = MediaWikiServices::getInstance()->getLinkBatchFactory();
			$lb = $linkBatchFactory->newLinkBatch( $titles );
			$lb->setCaller( __METHOD__ );
			$lb->execute();
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
				->getUserBlock( $this->mRelevantUser, null, true );
			if ( $userBlock && $userBlock->getHideName() &&
				!$this->getAuthority()->isAllowed( 'hideuser' )
			) {
				$this->mRelevantUser = null;
			}
		}

		return $this->mRelevantUser;
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
			list( $canonicalName ) = $services->getSpecialPageFactory()->resolveAlias( $title->getDBkey() );
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
	 * This gets called shortly before the "</body>" tag.
	 * @deprecated since 1.37
	 * @param bool $triggerWarnings introduced in 1.39 whether to trigger deprecation notice.
	 * @return string|WrappedStringList HTML containing scripts to put before `</body>`
	 */
	public function bottomScripts( $triggerWarnings = true ) {
		if ( $triggerWarnings ) {
			wfDeprecated( __METHOD__, '1.37' );
		}
		return $this->getOutput()->getBottomScripts();
	}

	/**
	 * Text with the permalink to the source page,
	 * usually shown on the footer of a printed page
	 *
	 * @stable to override
	 * @return string HTML text with an URL
	 */
	public function printSource() {
		$title = $this->getTitle();
		$oldid = $this->getOutput()->getRevisionId();
		if ( $oldid ) {
			$canonicalUrl = $title->getCanonicalURL( 'oldid=' . $oldid );
			$url = htmlspecialchars( wfExpandIRI( $canonicalUrl ) );
		} else {
			// oldid not available for non existing pages
			$url = htmlspecialchars( wfExpandIRI( $title->getCanonicalURL() ) );
		}

		return $this->msg( 'retrievedfrom' )
			->rawParams( '<a dir="ltr" href="' . $url . '">' . $url . '</a>' )
			->parse();
	}

	/**
	 * @return string HTML
	 */
	public function getUndeleteLink() {
		$action = $this->getRequest()->getRawVal( 'action', 'view' );
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
						$subpages .= $lang->getDirMarkEntity() . $this->msg( 'pipe-separator' )->escaped();
					} else {
						$subpages .= '&lt; ';
					}

					$subpages .= $getlink;
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
	 * @param string $type
	 * @return string
	 */
	public function getCopyright( $type = 'detect' ) {
		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		if ( $type == 'detect' ) {
			if ( !$this->getOutput()->isRevisionCurrent()
				&& !$this->msg( 'history_copyright' )->inContentLanguage()->isDisabled()
			) {
				$type = 'history';
			} else {
				$type = 'normal';
			}
		}

		if ( $type == 'history' ) {
			$msg = 'history_copyright';
		} else {
			$msg = 'copyright';
		}

		$config = $this->getConfig();

		if ( $config->get( MainConfigNames::RightsPage ) ) {
			$title = Title::newFromText( $config->get( MainConfigNames::RightsPage ) );
			$link = $linkRenderer->makeKnownLink( $title,
				new HtmlArmor( $config->get( MainConfigNames::RightsText ) ?: $title->getText() )
			);
		} elseif ( $config->get( MainConfigNames::RightsUrl ) ) {
			$link = Linker::makeExternalLink( $config->get( MainConfigNames::RightsUrl ),
				$config->get( MainConfigNames::RightsText ) );
		} elseif ( $config->get( MainConfigNames::RightsText ) ) {
			$link = $config->get( MainConfigNames::RightsText );
		} else {
			# Give up now
			return '';
		}

		// Allow for site and per-namespace customization of copyright notice.
		$this->getHookRunner()->onSkinCopyrightFooter( $this->getTitle(), $type, $msg, $link );

		return $this->msg( $msg )->rawParams( $link )->text();
	}

	/**
	 * Get the timestamp of the latest revision, formatted in user language
	 *
	 * @return string
	 */
	protected function lastModified() {
		$timestamp = $this->getOutput()->getRevisionTimestamp();
		$user = $this->getUser();
		$language = $this->getLanguage();

		# No cached timestamp, load it from the database
		if ( $timestamp === null ) {
			$revId = $this->getOutput()->getRevisionId();
			if ( $revId !== null ) {
				$timestamp = MediaWikiServices::getInstance()
					->getRevisionLookup()
					->getTimestampFromId( $revId );
			}
		}

		if ( $timestamp ) {
			$d = $language->userDate( $timestamp, $user );
			$t = $language->userTime( $timestamp, $user );
			$s = ' ' . $this->msg( 'lastmodifiedat', $d, $t )->parse();
		} else {
			$s = '';
		}

		if ( MediaWikiServices::getInstance()->getDBLoadBalancer()->getLaggedReplicaMode() ) {
			$s .= ' <strong>' . $this->msg( 'laggedreplicamode' )->parse() . '</strong>';
		}

		return $s;
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

		$logourl = RL\SkinModule::getAvailableLogos( $this->getConfig() )[ '1x' ];
		return "<a href='{$url}'><img{$a} src='{$logourl}' alt='[{$mp}]' /></a>";
	}

	/**
	 * Renders a $wgFooterIcons icon according to the method's arguments
	 * @param array $icon The icon to build the html for, see $wgFooterIcons
	 *   for the format of this array.
	 * @param bool|string $withImage Whether to use the icon's image or output
	 *   a text-only footericon.
	 * @return string HTML
	 */
	public function makeFooterIcon( $icon, $withImage = 'withImage' ) {
		if ( is_string( $icon ) ) {
			$html = $icon;
		} else { // Assuming array
			$url = $icon['url'] ?? null;
			unset( $icon['url'] );
			if ( isset( $icon['src'] ) && $withImage === 'withImage' ) {
				// Lazy-load footer icons, since they're not part of the printed view.
				$icon['loading'] = 'lazy';
				// do this the lazy way, just pass icon data as an attribute array
				$html = Html::element( 'img', $icon );
			} else {
				$html = htmlspecialchars( $icon['alt'] ?? '' );
			}
			if ( $url ) {
				$html = Html::rawElement( 'a', [
						'href' => $url,
						'target' => $this->getConfig()->get( MainConfigNames::ExternalLinkTarget ),
					],
					$html );
			}
		}
		return $html;
	}

	/**
	 * Given a pair of message keys for link and text label,
	 * return an HTML link for use in the footer.
	 *
	 * @param string $desc The i18n message key for the link text.
	 * 		The content of this message will be the visible text label.
	 * 		If this is set to nonexisting message key or the message is
	 * 		disabled, the link will not be generated, empty string will
	 * 		be returned in the stead.
	 * @param string $page The i18n message key for the page to link to.
	 * 		The content of this message will be the destination page for
	 * 		the footer link. Given a message key 'Privacypage' with content
	 * 		'Project:Privacy policy', the link will lead to the wiki page with
	 * 		the title of the content.
	 *
	 * @return string HTML anchor
	 */
	public function footerLink( $desc, $page ) {
		$title = $this->footerLinkTitle( $desc, $page );
		if ( !$title ) {
			return '';
		}

		// Similar to Skin::addToSidebarPlain
		// Optimization: Avoid LinkRenderer here as it requires extra DB info
		// to add unneeded classes even for makeKnownLink (T313462).
		return Html::element( 'a',
			[ 'href' => $title->fixSpecialName()->getLinkURL() ],
			$this->msg( $desc )->text()
		);
	}

	/**
	 * @param string $desc
	 * @param string $page
	 * @return Title|null
	 */
	private function footerLinkTitle( $desc, $page ) {
		// If the link description has been disabled in the default language,
		if ( $this->msg( $desc )->inContentLanguage()->isDisabled() ) {
			// then it is disabled, for all languages.
			return null;
		}
		// Otherwise, we display the link for the user, described in their
		// language (which may or may not be the same as the default language),
		// but we make the link target be the one site-wide page.
		return Title::newFromText( $this->msg( $page )->inContentLanguage()->text() );
	}

	/**
	 * Gets the link to the wiki's privacy policy, about page, and disclaimer page
	 *
	 * @internal For use by SkinTemplate
	 * @return string[] Map of (key => HTML) for 'privacy', 'about', 'disclaimer'
	 */
	public function getSiteFooterLinks() {
		return [
			'privacy' => $this->footerLink( 'privacy', 'privacypage' ),
			'about' => $this->footerLink( 'aboutsite', 'aboutpage' ),
			'disclaimer' => $this->footerLink( 'disclaimers', 'disclaimerpage' )
		];
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
		return $this->getUser()->canSendEmail()
			&& SpecialEmailUser::validateTarget( $targetUser, $this->getUser() ) === '';
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
	 * @param string $name Name of the Special page
	 * @param string|array $urlaction Query to append
	 * @param string|null $proto Protocol to use or null for a local URL
	 * @return string
	 */
	public static function makeSpecialUrl( $name, $urlaction = '', $proto = null ) {
		$title = SpecialPage::getSafeTitleFor( $name );
		if ( $proto === null ) {
			return $title->getLocalURL( $urlaction );
		} else {
			return $title->getFullURL( $urlaction, false, $proto );
		}
	}

	/**
	 * @param string $name
	 * @param string|bool $subpage false for no subpage
	 * @param string|array $urlaction
	 * @return string
	 */
	public static function makeSpecialUrlSubpage( $name, $subpage, $urlaction = '' ) {
		$title = SpecialPage::getSafeTitleFor( $name, $subpage );
		return $title->getLocalURL( $urlaction );
	}

	/**
	 * If url string starts with http, consider as external URL, else
	 * internal
	 * @param string $name
	 * @return string URL
	 */
	public static function makeInternalOrExternalUrl( $name ) {
		if ( preg_match( '/^(?i:' . wfUrlProtocols() . ')/', $name ) ) {
			return $name;
		} else {
			$title = $name instanceof Title ? $name : Title::newFromText( $name );
			return $title ? $title->getLocalURL() : '';
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
			$hookContainer = MediaWikiServices::getInstance()->getHookContainer();

			$userLang = $this->getLanguage();
			$languageLinks = [];
			$langNameUtils = MediaWikiServices::getInstance()->getLanguageNameUtils();

			foreach ( $this->getOutput()->getLanguageLinks() as $languageLinkText ) {
				$class = 'interlanguage-link interwiki-' . explode( ':', $languageLinkText, 2 )[0];

				$languageLinkTitle = Title::newFromText( $languageLinkText );
				if ( !$languageLinkTitle ) {
					continue;
				}

				$ilInterwikiCode =
					$this->mapInterwikiToLanguage( $languageLinkTitle->getInterwiki() );

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
				$languageLink = [
					'href' => $languageLinkTitle->getFullURL(),
					'text' => $ilLangName,
					'title' => $ilTitle,
					'class' => $class,
					'link-class' => 'interlanguage-link-target',
					'lang' => $ilInterwikiCodeBCP47,
					'hreflang' => $ilInterwikiCodeBCP47,
				];
				$hookContainer->run( 'SkinTemplateGetLanguageLink',
					[ &$languageLink, $languageLinkTitle, $this->getTitle(), $this->getOutput() ],
					[] );
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
			$nav_urls['upload'] = [ 'href' => self::makeSpecialUrl( 'Upload' ) ];
		} else {
			$nav_urls['upload'] = false;
		}
		$nav_urls['specialpages'] = [ 'href' => self::makeSpecialUrl( 'Specialpages' ) ];

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
				'href' => self::makeSpecialUrlSubpage( 'Contributions', $rootUser ),
				'tooltip-params' => [ $rootUser ],
			];

			$nav_urls['log'] = [
				'href' => self::makeSpecialUrlSubpage( 'Log', $rootUser )
			];

			if ( $this->getAuthority()->isAllowed( 'block' ) ) {
				// Check if the user is already blocked
				$userBlock = MediaWikiServices::getInstance()
				->getBlockManager()
				->getUserBlock( $user, null, true );
				if ( $userBlock ) {
					$nav_urls['changeblockip'] = [
						'href' => self::makeSpecialUrlSubpage( 'Block', $rootUser )
					];
					$nav_urls['unblockip'] = [
						'href' => self::makeSpecialUrlSubpage( 'Unblock', $rootUser )
					];
				} else {
					$nav_urls['blockip'] = [
						'text' => $this->msg( 'blockip', $rootUser )->text(),
						'href' => self::makeSpecialUrlSubpage( 'Block', $rootUser )
					];
				}
			}

			if ( $this->showEmailUser( $user ) ) {
				$nav_urls['emailuser'] = [
					'text' => $this->msg( 'tool-link-emailuser', $rootUser )->text(),
					'href' => self::makeSpecialUrlSubpage( 'Emailuser', $rootUser ),
					'tooltip-params' => [ $rootUser ],
				];
			}

			if ( $user->isRegistered() ) {
				if ( $this->getUser()->isRegistered() &&
				$this->getConfig()->get( MainConfigNames::EnableSpecialMute ) ) {
					$nav_urls['mute'] = [
						'text' => $this->msg( 'mute-preferences' )->text(),
						'href' => self::makeSpecialUrlSubpage( 'Mute', $rootUser )
					];
				}

				$sur = new UserrightsPage;
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
					'text' => $this->msg(
						$canChange ? 'tool-link-userrights' : 'tool-link-userrights-readonly',
						$rootUser
					)->text(),
					'href' => self::makeSpecialUrlSubpage( 'Userrights', ...$linkArgs )
				];
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
	 * Build an array that represents the sidebar(s), the navigation bar among them.
	 *
	 * BaseTemplate::getSidebar can be used to simplify the format and id generation in new skins.
	 *
	 * The format of the returned array is [ heading => content, ... ], where:
	 * - heading is the heading of a navigation portlet. It is either:
	 *   - magic string to be handled by the skins ('SEARCH' / 'LANGUAGES' / 'TOOLBOX' / ...)
	 *     - Note that 'SEARCH' unlike others is not supported out-of-the-box by the skins.
	 *     - For it to work, a skin must add custom support for it.
	 *   - a message name (e.g. 'navigation'), the message should be HTML-escaped by the skin
	 *   - plain text, which should be HTML-escaped by the skin
	 * - content is the contents of the portlet. This must be array of link data in a format
	 * 		accepted by self::makeListItem().
	 *   - (for a magic string as a key, any value)
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
					$wanCache->makeKey( 'sidebar', $languageCode ),
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

			$sidebar['TOOLBOX'] = $this->makeToolbox(
				$this->buildNavUrls(),
				$this->buildFeedUrls()
			);
			$sidebar['LANGUAGES'] = $this->getLanguages();
			// Apply post-processing to the cached value
			$this->getHookRunner()->onSidebarBeforeOutput( $this, $sidebar );
			$this->sidebar = $sidebar;
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
		$messageCache = MediaWikiServices::getInstance()->getMessageCache();

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
					$line = $messageCache->transform( $line, false, null, $messageTitle );
					$line = array_map( 'trim', explode( '|', $line, 2 ) );
					if ( count( $line ) !== 2 ) {
						// Second check, could be hit by people doing
						// funky stuff with parserfuncs... (T35321)
						continue;
					}

					$extraAttribs = [];

					$msgLink = $this->msg( $line[0] )->page( $messageTitle )->inContentLanguage();
					if ( $msgLink->exists() ) {
						$link = $msgLink->text();
						if ( $link == '-' ) {
							continue;
						}
					} else {
						$link = $line[0];
					}
					$msgText = $this->msg( $line[1] )->page( $messageTitle );
					if ( $msgText->exists() ) {
						$text = $msgText->text();
					} else {
						$text = $line[1];
					}

					if ( preg_match( '/^(?i:' . wfUrlProtocols() . ')/', $link ) ) {
						$href = $link;

						// Parser::getExternalLinkAttribs won't work here because of the Namespace things
						if ( $config->get( MainConfigNames::NoFollowLinks ) &&
							!wfMatchesDomainList( $href,
								$config->get( MainConfigNames::NoFollowDomainExceptions ) )
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

					$id = strtr( $line[1], ' ', '-' );
					$bar[$heading][] = array_merge( [
						'text' => $text,
						'href' => $href,
						'icon' => $this->getSidebarIcon( $id ),
						'id' => Sanitizer::escapeIdForAttribute( 'n-' . $id ),
						'active' => false,
					], $extraAttribs );
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
			case 'help':
				return 'help';
			default:
				return null;
		}
	}

	/**
	 * Gets new talk page messages for the current user and returns an
	 * appropriate alert message (or an empty string if there are no messages)
	 * @return string
	 */
	public function getNewtalks() {
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
					RevisionLookup::READ_NORMAL
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
				$this->msg( 'newmessageslinkplural' )->params( $plural )->text(),
				[],
				$uTalkTitle->isRedirect() ? [ 'redirect' => 'no' ] : []
			);

			$newMessagesDiffLink = $linkRenderer->makeKnownLink(
				$uTalkTitle,
				$this->msg( 'newmessagesdifflinkplural' )->params( $plural )->text(),
				[],
				$lastSeenRev !== null
					? [ 'oldid' => $lastSeenRev->getId(), 'diff' => 'cur' ]
					: [ 'diff' => 'cur' ]
			);

			if ( $numAuthors >= 1 && $numAuthors <= 10 ) {
				$newMessagesAlert = $this->msg(
					'youhavenewmessagesfromusers',
					$newMessagesLink,
					$newMessagesDiffLink
				)->numParams( $numAuthors, $plural );
			} else {
				// $numAuthors === 11 signifies "11 or more" ("more than 10")
				$newMessagesAlert = $this->msg(
					$numAuthors > 10 ? 'youhavenewmessagesmanyusers' : 'youhavenewmessages',
					$newMessagesLink,
					$newMessagesDiffLink
				)->numParams( $plural );
			}
			$newMessagesAlert = $newMessagesAlert->text();
			// Disable CDN cache
			$out->setCdnMaxage( 0 );
		}

		return $newMessagesAlert;
	}

	/**
	 * Get a cached notice
	 *
	 * @param string $name Message name, or 'default' for $wgSiteNotice
	 * @return string|bool HTML fragment, or false to indicate that the caller
	 *   should fall back to the next notice in its sequence
	 */
	private function getCachedNotice( $name ) {
		$config = $this->getConfig();

		if ( $name === 'default' ) {
			// special case
			$notice = $config->get( MainConfigNames::SiteNotice );
			if ( empty( $notice ) ) {
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
				$siteNotice = Html::rawElement( 'div', [ 'id' => 'localNotice' ], $siteNotice );
			}
		}

		$this->getHookRunner()->onSiteNoticeAfter( $siteNotice, $this );
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
		$attribs['title'] = $this->msg( 'editsectionhint' )->rawParams( $sectionTitle )
			->inLanguage( $lang )->text();

		$links = [
			'editsection' => [
				'text' => $this->msg( 'editsection' )->inLanguage( $lang )->text(),
				'targetTitle' => $nt,
				'attribs' => $attribs,
				'query' => [ 'action' => 'edit', 'section' => $section ]
			]
		];

		$this->getHookRunner()->onSkinEditSectionLinks( $this, $nt, $section, $sectionTitle, $links, $lang );

		$result = Html::openElement( 'span', [ 'class' => 'mw-editsection' ] );
		$result .= Html::rawElement( 'span', [ 'class' => 'mw-editsection-bracket' ], '[' );

		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		$linksHtml = [];
		foreach ( $links as $k => $linkDetails ) {
			$linksHtml[] = $linkRenderer->makeKnownLink(
				$linkDetails['targetTitle'],
				$linkDetails['text'],
				$linkDetails['attribs'],
				$linkDetails['query']
			);
		}

		$result .= implode(
			Html::rawElement(
				'span',
				[ 'class' => 'mw-editsection-divider' ],
				$this->msg( 'pipe-separator' )->inLanguage( $lang )->escaped()
			),
			$linksHtml
		);

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
			'emailuser', 'mute', 'userrights', 'upload', 'specialpages' ] as $special
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
	 * @param array $indicators return value of OutputPage::getIndicators
	 * @return array
	 */
	protected function getIndicatorsData( $indicators ) {
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
		$html = $item['html'] ?? null;
		if ( $html ) {
			return $html;
		}
		$text = $item['text'] ?? $this->msg( $item['msg'] ?? $key )->text();

		$html = htmlspecialchars( $text );
		$isLink = isset( $item['href'] ) || isset( $options['link-fallback'] );

		if ( $html !== '' && isset( $options['text-wrapper'] ) ) {
			$wrapper = $options['text-wrapper'];
			if ( isset( $wrapper['tag'] ) ) {
				$wrapper = [ $wrapper ];
			}
			while ( count( $wrapper ) > 0 ) {
				$element = array_pop( $wrapper );
				'@phan-var array $element';

				$attrs = $element['attributes'] ?? [];
				// Apply title attribute to the outermost wrapper if there is
				// no link wrapper. No need for an accesskey.
				if ( count( $wrapper ) === 0 && !$isLink ) {
					$this->applyLinkTitleAttribs(
						$item,
						false,
						$attrs
					);
				}
				$html = Html::rawElement( $element['tag'],  $attrs, $html );
			}
		}

		if ( $isLink ) {
			$attrs = $item;
			foreach ( [ 'single-id', 'text', 'msg', 'tooltiponly', 'context', 'primary',
						// These fields provide context for skins to modify classes and should not be outputted to skin
						'icon', 'button',
						'tooltip-params', 'exists', 'link-html' ] as $k ) {
				unset( $attrs[$k] );
			}

			if ( isset( $attrs['data'] ) ) {
				foreach ( $attrs['data'] as $key => $value ) {
					$attrs[ 'data-' . $key ] = $value;
				}
				unset( $attrs[ 'data' ] );
			}
			$this->applyLinkTitleAttribs( $item, true, $attrs );
			if ( isset( $options['link-class'] ) ) {
				$attrs['class'] = $this->addClassToClassList( $attrs['class'] ?? [], $options['link-class'] );
			}

			if ( isset( $item['link-html'] ) ) {
				$html = $item['link-html'] . ' ' . $html;
			}

			$html = Html::rawElement( isset( $attrs['href'] )
				? 'a'
				: $options['link-fallback'], $attrs, $html );
		}

		return $html;
	}

	/**
	 * Helper for makeLink(). Add tooltip and accesskey attributes to $attrs
	 * according to the input item array.
	 *
	 * @param array $item
	 * @param bool $allowAccessKey
	 * @param array &$attrs
	 */
	private function applyLinkTitleAttribs( $item, $allowAccessKey, &$attrs ) {
		$tooltipId = $item['single-id'] ?? $item['id'] ?? null;
		if ( $tooltipId === null ) {
			return;
		}
		$tooltipParams = $item['tooltip-params'] ?? [];
		$tooltipOption = isset( $item['exists'] ) && $item['exists'] === false ? 'nonexisting' : null;

		if ( !$allowAccessKey || !empty( $item['tooltiponly'] ) ) {
			$title = Linker::titleAttrib( $tooltipId, $tooltipOption, $tooltipParams );
			if ( $title !== false ) {
				$attrs['title'] = $title;
			}
		} else {
			$tip = Linker::tooltipAndAccesskeyAttribs(
				$tooltipId,
				$tooltipParams,
				$tooltipOption
			);
			if ( isset( $tip['title'] ) && $tip['title'] !== false ) {
				$attrs['title'] = $tip['title'];
			}
			if ( isset( $tip['accesskey'] ) && $tip['accesskey'] !== false ) {
				$attrs['accesskey'] = $tip['accesskey'];
			}
		}
	}

	/**
	 * Generates a list item for a navigation, portlet, portal, sidebar... list
	 *
	 * @since 1.35
	 * @param string $key Usually a key from the list you are generating this link from.
	 * @param array $item Array of list item data containing some of a specific set of keys.
	 * The "id", "class" and "itemtitle" keys will be used as attributes for the list item,
	 * if "active" contains a value of true a "active" class will also be appended to class.
	 * The "class" key currently accepts both a string and an array of classes, but this will be
	 * changed to only accept an array in the future.
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
		// In case this is still set from SkinTemplate, we don't want it to appear in
		// the HTML output (normally removed in SkinTemplate::buildContentActionUrls())
		unset( $item['redundant'] );

		if ( isset( $item['links'] ) ) {
			$links = [];
			foreach ( $item['links'] as $link ) {
				// Note: links will have identical label unless 'msg' is set on $link
				$links[] = $this->makeLink( $key, $link, $options );
			}
			$html = implode( ' ', $links );
		} else {
			$link = $item;
			// These keys are used by makeListItem and shouldn't be passed on to the link
			foreach ( [ 'id', 'class', 'active', 'tag', 'itemtitle' ] as $k ) {
				unset( $link[$k] );
			}
			if ( isset( $item['id'] ) && !isset( $item['single-id'] ) ) {
				// The id goes on the <li> not on the <a> for single links
				// but makeSidebarLink still needs to know what id to use when
				// generating tooltips and accesskeys.
				$link['single-id'] = $item['id'];
			}
			if ( isset( $link['link-class'] ) ) {
				// link-class should be set on the <a> itself,
				// so pass it in as 'class'
				$link['class'] = $link['link-class'];
				unset( $link['link-class'] );
			}
			$html = $this->makeLink( $key, $link, $options );
		}

		$attrs = [];
		foreach ( [ 'id', 'class' ] as $attr ) {
			if ( isset( $item[$attr] ) ) {
				$attrs[$attr] = $item[$attr];
			}
		}
		$attrs['class'] = $this->addClassToClassList( $attrs['class'] ?? [], 'mw-list-item' );

		if ( isset( $item['active'] ) && $item['active'] ) {
			// In the future, this should accept an array of classes, not a string
			$attrs['class'] = $this->addClassToClassList( $attrs['class'], 'active' );
		}
		if ( isset( $item['itemtitle'] ) ) {
			$attrs['title'] = $item['itemtitle'];
		}
		return Html::rawElement( $options['tag'] ?? 'li', $attrs, $html );
	}

	/**
	 * Adds a class to the existing class value, supporting it as a string
	 * or array.
	 *
	 * @param string|array $class to update.
	 * @param string $newClass to add.
	 * @return string|array classes.
	 */
	private function addClassToClassList( $class, string $newClass ) {
		if ( is_array( $class ) ) {
			$class[] = $newClass;
		} else {
			$class .= ' ' . $newClass;
			$class = trim( $class );
		}
		return $class;
	}

	/**
	 * @since 1.35
	 * @param array $attrs (optional) will be passed to tooltipAndAccesskeyAttribs
	 *  and decorate the resulting input
	 * @deprecated 1.39 use $this->getTemplateData()['data-search-box'] instead.
	 * @return string of HTML input
	 */
	public function makeSearchInput( $attrs = [] ) {
		wfDeprecated( __METHOD__,
			'[1.39] use $this->getTemplateData()["data-search-box"] or SkinTemplate::makeSearchInput'
		);

		// It's possible that getTemplateData might be calling
		// Skin::makeSearchInput. To avoid infinite recursion create a
		// new instance of the search component here.
		$searchBox = $this->getComponent( 'search-box' );
		$data = $searchBox->getTemplateData();

		return Html::element( 'input',
			$data[ 'array-input-attributes' ] + $attrs
		);
	}

	/**
	 * @param string $mode representing the type of button wanted
	 *  either `go`, `fulltext` or `image`
	 * @param array $attrs (optional)
	 * @throws MWException if bad value of $mode passed in
	 * @deprecated 1.39 use $this->getTemplateData()['data-search-box'] instead.
	 *   Note: When removing this function please merge SkinTemplate::makeSearchButtonInternal
	 *   with SkinTemplate::makeSearchButton.
	 * @return string of HTML button
	 */
	public function makeSearchButton( $mode, $attrs = [] ) {
		wfDeprecated( __METHOD__,
			'[1.39] use $this->getTemplateData()["data-search-box"] or SkinTemplate::makeSearchButton'
		);

		// It's possible that getTemplateData might be calling
		// Skin::makeSearchInput. To avoid infinite recursion create a
		// new instance of the search component here.
		$searchBox = $this->getComponent( 'search-box' );
		$data = $searchBox->getTemplateData();

		return SkinTemplate::makeSearchButtonInternal(
			$mode, $data, $attrs
		);
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
	 * @return string HTML
	 */
	final public function prepareSubtitle() {
		$out = $this->getOutput();
		$subpagestr = $this->subPageSubtitleInternal();
		if ( $subpagestr !== '' ) {
			$subpagestr = Html::rawElement( 'span', [ 'class' => 'subpages' ], $subpagestr );
		}
		return $subpagestr . $out->getSubtitle();
	}

	/**
	 * Get template representation of the footer containing
	 * site footer links as well as standard footer links.
	 *
	 * All values are resolved and can be added to by the
	 * SkinAddFooterLinks hook.
	 *
	 * @since 1.35
	 * @internal
	 * @return array
	 */
	protected function getFooterLinks(): array {
		$out = $this->getOutput();
		$title = $out->getTitle();
		$titleExists = $title->exists();
		$config = $this->getConfig();
		$maxCredits = $config->get( MainConfigNames::MaxCredits );
		$showCreditsIfMax = $config->get( MainConfigNames::ShowCreditsIfMax );
		$useCredits = $titleExists
			&& $out->isArticle()
			&& $out->isRevisionCurrent()
			&& $maxCredits !== 0;

		/** @var CreditsAction $action */
		if ( $useCredits ) {
			$article = Article::newFromWikiPage( $this->getWikiPage(), $this );
			$action = Action::factory( 'credits', $article, $this );
		}

		'@phan-var CreditsAction $action';
		$data = [
			'info' => [
				'lastmod' => !$useCredits ? $this->lastModified() : null,
				'numberofwatchingusers' => null,
				'credits' => $useCredits ?
					$action->getCredits( $maxCredits, $showCreditsIfMax ) : null,
				'copyright' => $titleExists &&
					$out->showsCopyright() ? $this->getCopyright() : null,
			],
			'places' => $this->getSiteFooterLinks(),
		];
		foreach ( $data as $key => $existingItems ) {
			$newItems = [];
			$this->getHookRunner()->onSkinAddFooterLinks( $this, $key, $newItems );
			$data[$key] = $existingItems + $newItems;
		}
		return $data;
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
	private function getUserLanguageAttributes() {
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
		return $undelete === '' ? null : '<span class="subpages">' . $undelete . '</span>';
	}

	/**
	 * @return string
	 * @deprecated since 1.39
	 */
	final protected function getAction(): string {
		wfDeprecated( __METHOD__, '1.39' );
		return $this->getContext()->getActionName();
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
		# An ID that includes the actual body text; without categories, contentSub, ...
		$realBodyAttribs = [
			'id' => 'mw-content-text',
			'class' => [
				'mw-body-content',
			],
		];

		# Add a mw-content-ltr/rtl class to be able to style based on text
		# direction when the content is different from the UI language (only
		# when viewing)
		# Most information on special pages and file pages is in user language,
		# rather than content language, so those will not get this
		if ( $this->getContext()->getActionName() === 'view' &&
			( !$title->inNamespaces( NS_SPECIAL, NS_FILE ) || $title->isRedirect() ) ) {
			$pageLang = $title->getPageViewLanguage();
			$realBodyAttribs['lang'] = $pageLang->getHtmlCode();
			$realBodyAttribs['dir'] = $pageLang->getDir();
			$realBodyAttribs['class'][] = 'mw-content-' . $pageLang->getDir();
		}

		return Html::rawElement( 'div', $realBodyAttribs, $html );
	}

	/**
	 * @deprecated since 1.38 Use SpecialPage::newSearchPage instead.
	 */
	public function getSearchPageTitle(): Title {
		wfDeprecated( __METHOD__, '1.38 Use SpecialPage::newSearchPage' );
		return SpecialPage::newSearchPage( $this->getUser() );
	}

	/**
	 * @deprecated since 1.38 to change the search page title change the value of the
	 *  preference 'search-special-page' instead.
	 */
	public function setSearchPageTitle( Title $title ) {
		wfDeprecated( __METHOD__, '1.38 Use SpecialPage::newSearchPage' );
		$userOptionsManager = MediaWikiServices::getInstance()->getUserOptionsManager();
		$user = $this->getUser();
		$currentTitle = $userOptionsManager->getOption( $user, 'search-special-page' );
		$newTitle = $title->getText();
		if ( $currentTitle !== $newTitle ) {
			$userOptionsManager->setOption( $user, 'search-special-page', $newTitle );
		}
	}

	/**
	 * Returns skin options
	 * Recommended to use SkinFactory::getSkinOptions instead
	 *
	 * @internal
	 * @return array Skin options passed into constructor
	 */
	final public function getOptions(): array {
		return $this->options + [
			// Whether the table of contents will be inserted on page views
			// See ParserOutput::getText() for the implementation logic
			'toc' => true,
			// An array of classes to be added to the skin body tag.
			'bodyClasses' => [],
			// For SkinTemplate based skins, whether the skin is in charge of generating
			// the <html>, <head> and <body> tags. For SkinMustache this is always true and
			// ignored.
			'bodyOnly' => false,
			'menus' => [
				// Modern keys
				'user-interface-preferences',
				'user-page',
				'user-menu',
				'notifications',
				// Legacy keys that are enabled by default for backwards compatibility
				'namespaces',
				'views',
				'actions',
				'variants',
				// Opt-in menus
				// * 'associated-pages'
			]
		];
	}

	/**
	 * Does the skin support the named menu?
	 *
	 * @since 1.39
	 * @param string $menu See Skin::getOptions for menu names.
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
}
