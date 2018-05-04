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

use MediaWiki\MediaWikiServices;

/**
 * @defgroup Skins Skins
 */

/**
 * The main skin class which provides methods and properties for all other skins.
 *
 * See docs/skin.txt for more information.
 *
 * @ingroup Skins
 */
abstract class Skin extends ContextSource {
	/**
	 * @var string|null
	 */
	protected $skinname = null;

	protected $mRelevantTitle = null;
	protected $mRelevantUser = null;

	/**
	 * @var string Stylesheets set to use. Subdirectory in skins/ where various stylesheets are
	 *   located. Only needs to be set if you intend to use the getSkinStylePath() method.
	 */
	public $stylename = null;

	/**
	 * Fetch the set of available skins.
	 * @return array Associative array of strings
	 */
	static function getSkinNames() {
		return SkinFactory::getDefaultInstance()->getSkinNames();
	}

	/**
	 * Fetch the skinname messages for available skins.
	 * @return string[]
	 */
	static function getSkinNameMessages() {
		$messages = [];
		foreach ( self::getSkinNames() as $skinKey => $skinName ) {
			$messages[] = "skinname-$skinKey";
		}
		return $messages;
	}

	/**
	 * Fetch the list of user-selectable skins in regards to $wgSkipSkins.
	 * Useful for Special:Preferences and other places where you
	 * only want to show skins users _can_ use.
	 * @return string[]
	 * @since 1.23
	 */
	public static function getAllowedSkins() {
		global $wgSkipSkins;

		$allowedSkins = self::getSkinNames();

		foreach ( $wgSkipSkins as $skip ) {
			unset( $allowedSkins[$skip] );
		}

		return $allowedSkins;
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
	static function normalizeKey( $key ) {
		global $wgDefaultSkin, $wgFallbackSkin;

		$skinNames = self::getSkinNames();

		// Make keys lowercase for case-insensitive matching.
		$skinNames = array_change_key_case( $skinNames, CASE_LOWER );
		$key = strtolower( $key );
		$defaultSkin = strtolower( $wgDefaultSkin );
		$fallbackSkin = strtolower( $wgFallbackSkin );

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
	 * @param string|null $skinname
	 */
	public function __construct( $skinname = null ) {
		if ( is_string( $skinname ) ) {
			$this->skinname = $skinname;
		}
	}

	/**
	 * @return string|null Skin name
	 */
	public function getSkinName() {
		return $this->skinname;
	}

	/**
	 * @param OutputPage $out
	 */
	public function initPage( OutputPage $out ) {
		$this->preloadExistence();
	}

	/**
	 * Defines the ResourceLoader modules that should be added to the skin
	 * It is recommended that skins wishing to override call parent::getDefaultModules()
	 * and substitute out any modules they wish to change by using a key to look them up
	 *
	 * Any modules defined with the 'styles' key will be added as render blocking CSS via
	 * Output::addModuleStyles. Similarly, each key should refer to a list of modules
	 *
	 * @return array Array of modules with helper keys for easy overriding
	 */
	public function getDefaultModules() {
		$out = $this->getOutput();
		$config = $this->getConfig();
		$user = $this->getUser();

		// Modules declared in the $modules literal are loaded
		// for ALL users, on ALL pages, in ALL skins.
		// Keep this list as small as possible!
		$modules = [
			'styles' => [
				// The 'styles' key sets render-blocking style modules
				// Unlike other keys in $modules, this is an associative array
				// where each key is its own group pointing to a list of modules
				'core' => [
					'mediawiki.legacy.shared',
					'mediawiki.legacy.commonPrint',
				],
				'content' => [],
				'syndicate' => [],
			],
			'core' => [
				'site',
				'mediawiki.page.startup',
				'mediawiki.user',
			],
			// modules that enhance the content in some way
			'content' => [
				'mediawiki.page.ready',
			],
			// modules relating to search functionality
			'search' => [],
			// modules relating to functionality relating to watching an article
			'watch' => [],
			// modules which relate to the current users preferences
			'user' => [],
			// modules relating to RSS/Atom Feeds
			'syndicate' => [],
		];

		// Support for high-density display images if enabled
		if ( $config->get( 'ResponsiveImages' ) ) {
			$modules['core'][] = 'mediawiki.hidpi';
		}

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

		// Add various resources if required
		if ( $user->isLoggedIn()
			&& $user->isAllowedAll( 'writeapi', 'viewmywatchlist', 'editmywatchlist' )
			&& $this->getRelevantTitle()->canExist()
		) {
			$modules['watch'][] = 'mediawiki.page.watch.ajax';
		}

		$modules['search'][] = 'mediawiki.searchSuggest';

		if ( $user->getBoolOption( 'editsectiononrightclick' ) ) {
			$modules['user'][] = 'mediawiki.action.view.rightClickEdit';
		}

		// Crazy edit-on-double-click stuff
		if ( $out->isArticle() && $user->getOption( 'editondblclick' ) ) {
			$modules['user'][] = 'mediawiki.action.view.dblClickEdit';
		}

		if ( $out->isSyndicated() ) {
			$modules['styles']['syndicate'][] = 'mediawiki.feedlink';
		}

		return $modules;
	}

	/**
	 * Preload the existence of three commonly-requested pages in a single query
	 */
	protected function preloadExistence() {
		$titles = [];

		// User/talk link
		$user = $this->getUser();
		if ( $user->isLoggedIn() ) {
			$titles[] = $user->getUserPage();
			$titles[] = $user->getTalkPage();
		}

		// Check, if the page can hold some kind of content, otherwise do nothing
		$title = $this->getRelevantTitle();
		if ( $title->canExist() ) {
			if ( $title->isTalkPage() ) {
				$titles[] = $title->getSubjectPage();
			} else {
				$titles[] = $title->getTalkPage();
			}
		}

		// Footer links (used by SkinTemplate::prepareQuickTemplate)
		foreach ( [
			$this->footerLinkTitle( 'privacy', 'privacypage' ),
			$this->footerLinkTitle( 'aboutsite', 'aboutpage' ),
			$this->footerLinkTitle( 'disclaimers', 'disclaimerpage' ),
		] as $title ) {
			if ( $title ) {
				$titles[] = $title;
			}
		}

		Hooks::run( 'SkinPreloadExistence', [ &$titles, $this ] );

		if ( $titles ) {
			$lb = new LinkBatch( $titles );
			$lb->setCaller( __METHOD__ );
			$lb->execute();
		}
	}

	/**
	 * Get the current revision ID
	 *
	 * @return int
	 */
	public function getRevisionId() {
		return $this->getOutput()->getRevisionId();
	}

	/**
	 * Whether the revision displayed is the latest revision of the page
	 *
	 * @return bool
	 */
	public function isRevisionCurrent() {
		$revID = $this->getRevisionId();
		return $revID == 0 || $revID == $this->getTitle()->getLatestRevID();
	}

	/**
	 * Set the "relevant" title
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
	 * such as content tabs which belong to to that page instead of displaying
	 * a basic special page tab which has almost no meaning.
	 *
	 * @return Title
	 */
	public function getRelevantTitle() {
		if ( isset( $this->mRelevantTitle ) ) {
			return $this->mRelevantTitle;
		}
		return $this->getTitle();
	}

	/**
	 * Set the "relevant" user
	 * @see self::getRelevantUser()
	 * @param User $u
	 */
	public function setRelevantUser( $u ) {
		$this->mRelevantUser = $u;
	}

	/**
	 * Return the "relevant" user.
	 * A "relevant" user is similar to a relevant title. Special pages like
	 * Special:Contributions mark the user which they are relevant to so that
	 * things like the toolbox can display the information they usually are only
	 * able to display on a user's userpage and talkpage.
	 * @return User
	 */
	public function getRelevantUser() {
		if ( isset( $this->mRelevantUser ) ) {
			return $this->mRelevantUser;
		}
		$title = $this->getRelevantTitle();
		if ( $title->hasSubjectNamespace( NS_USER ) ) {
			$rootUser = $title->getRootText();
			if ( User::isIP( $rootUser ) ) {
				$this->mRelevantUser = User::newFromName( $rootUser, false );
			} else {
				$user = User::newFromName( $rootUser, false );

				if ( $user ) {
					$user->load( User::READ_NORMAL );

					if ( $user->isLoggedIn() ) {
						$this->mRelevantUser = $user;
					}
				}
			}
			return $this->mRelevantUser;
		}
		return null;
	}

	/**
	 * Outputs the HTML generated by other functions.
	 * @param OutputPage $out
	 */
	abstract function outputPage( OutputPage $out = null );

	/**
	 * @param array $data
	 * @return string
	 */
	static function makeVariablesScript( $data ) {
		if ( $data ) {
			return ResourceLoader::makeInlineScript(
				ResourceLoader::makeConfigSetScript( $data )
			);
		} else {
			return '';
		}
	}

	/**
	 * Get the query to generate a dynamic stylesheet
	 *
	 * @return array
	 */
	public static function getDynamicStylesheetQuery() {
		global $wgSquidMaxage;

		return [
				'action' => 'raw',
				'maxage' => $wgSquidMaxage,
				'usemsgcache' => 'yes',
				'ctype' => 'text/css',
				'smaxage' => $wgSquidMaxage,
			];
	}

	/**
	 * Hook point for adding style modules to OutputPage.
	 *
	 * @deprecated since 1.32 Use getDefaultModules() instead.
	 * @param OutputPage $out Legacy parameter, identical to $this->getOutput()
	 */
	public function setupSkinUserCss( OutputPage $out ) {
		// Stub.
	}

	/**
	 * TODO: document
	 * @param Title $title
	 * @return string
	 */
	function getPageClasses( $title ) {
		$numeric = 'ns-' . $title->getNamespace();

		if ( $title->isSpecialPage() ) {
			$type = 'ns-special';
			// T25315: provide a class based on the canonical special page name without subpages
			list( $canonicalName ) = SpecialPageFactory::resolveAlias( $title->getDBkey() );
			if ( $canonicalName ) {
				$type .= ' ' . Sanitizer::escapeClass( "mw-special-$canonicalName" );
			} else {
				$type .= ' mw-invalidspecialpage';
			}
		} elseif ( $title->isTalkPage() ) {
			$type = 'ns-talk';
		} else {
			$type = 'ns-subject';
		}

		$name = Sanitizer::escapeClass( 'page-' . $title->getPrefixedText() );
		$root = Sanitizer::escapeClass( 'rootpage-' . $title->getRootTitle()->getPrefixedText() );

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
	 * This will be called by OutputPage::headElement when it is creating the
	 * "<body>" tag, skins can override it if they have a need to add in any
	 * body attributes or classes of their own.
	 * @param OutputPage $out
	 * @param array &$bodyAttrs
	 */
	function addToBodyAttributes( $out, &$bodyAttrs ) {
		// does nothing by default
	}

	/**
	 * URL to the logo
	 * @return string
	 */
	function getLogo() {
		global $wgLogo;
		return $wgLogo;
	}

	/**
	 * Whether the logo should be preloaded with an HTTP link header or not
	 * @since 1.29
	 * @return bool
	 */
	public function shouldPreloadLogo() {
		return false;
	}

	/**
	 * @return string HTML
	 */
	function getCategoryLinks() {
		global $wgUseCategoryBrowser;

		$out = $this->getOutput();
		$allCats = $out->getCategoryLinks();

		if ( !count( $allCats ) ) {
			return '';
		}

		$embed = "<li>";
		$pop = "</li>";

		$s = '';
		$colon = $this->msg( 'colon-separator' )->escaped();

		if ( !empty( $allCats['normal'] ) ) {
			$t = $embed . implode( "{$pop}{$embed}", $allCats['normal'] ) . $pop;

			$msg = $this->msg( 'pagecategories' )->numParams( count( $allCats['normal'] ) )->escaped();
			$linkPage = wfMessage( 'pagecategorieslink' )->inContentLanguage()->text();
			$title = Title::newFromText( $linkPage );
			$link = $title ? Linker::link( $title, $msg ) : $msg;
			$s .= '<div id="mw-normal-catlinks" class="mw-normal-catlinks">' .
				$link . $colon . '<ul>' . $t . '</ul>' . '</div>';
		}

		# Hidden categories
		if ( isset( $allCats['hidden'] ) ) {
			if ( $this->getUser()->getBoolOption( 'showhiddencats' ) ) {
				$class = ' mw-hidden-cats-user-shown';
			} elseif ( $this->getTitle()->getNamespace() == NS_CATEGORY ) {
				$class = ' mw-hidden-cats-ns-shown';
			} else {
				$class = ' mw-hidden-cats-hidden';
			}

			$s .= "<div id=\"mw-hidden-catlinks\" class=\"mw-hidden-catlinks$class\">" .
				$this->msg( 'hidden-categories' )->numParams( count( $allCats['hidden'] ) )->escaped() .
				$colon . '<ul>' . $embed . implode( "{$pop}{$embed}", $allCats['hidden'] ) . $pop . '</ul>' .
				'</div>';
		}

		# optional 'dmoz-like' category browser. Will be shown under the list
		# of categories an article belong to
		if ( $wgUseCategoryBrowser ) {
			$s .= '<br /><hr />';

			# get a big array of the parents tree
			$parenttree = $this->getTitle()->getParentCategoryTree();
			# Skin object passed by reference cause it can not be
			# accessed under the method subfunction drawCategoryBrowser
			$tempout = explode( "\n", $this->drawCategoryBrowser( $parenttree ) );
			# Clean out bogus first entry and sort them
			unset( $tempout[0] );
			asort( $tempout );
			# Output one per line
			$s .= implode( "<br />\n", $tempout );
		}

		return $s;
	}

	/**
	 * Render the array as a series of links.
	 * @param array $tree Categories tree returned by Title::getParentCategoryTree
	 * @return string Separated by &gt;, terminate with "\n"
	 */
	function drawCategoryBrowser( $tree ) {
		$return = '';

		foreach ( $tree as $element => $parent ) {
			if ( empty( $parent ) ) {
				# element start a new list
				$return .= "\n";
			} else {
				# grab the others elements
				$return .= $this->drawCategoryBrowser( $parent ) . ' &gt; ';
			}

			# add our current element to the list
			$eltitle = Title::newFromText( $element );
			$return .= Linker::link( $eltitle, htmlspecialchars( $eltitle->getText() ) );
		}

		return $return;
	}

	/**
	 * @return string HTML
	 */
	function getCategories() {
		$out = $this->getOutput();
		$catlinks = $this->getCategoryLinks();

		// Check what we're showing
		$allCats = $out->getCategoryLinks();
		$showHidden = $this->getUser()->getBoolOption( 'showhiddencats' ) ||
						$this->getTitle()->getNamespace() == NS_CATEGORY;

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

		if ( Hooks::run( 'SkinAfterContent', [ &$data, $this ] ) ) {
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
			wfDebug( "Hook SkinAfterContent changed output processing.\n" );
		}

		return $data;
	}

	/**
	 * Generate debug data HTML for displaying at the bottom of the main content
	 * area.
	 * @return string HTML containing debug data, if enabled (otherwise empty).
	 */
	protected function generateDebugHTML() {
		return MWDebug::getHTMLDebugLog();
	}

	/**
	 * This gets called shortly before the "</body>" tag.
	 *
	 * @return string HTML-wrapped JS code to be put before "</body>"
	 */
	function bottomScripts() {
		// TODO and the suckage continues. This function is really just a wrapper around
		// OutputPage::getBottomScripts() which takes a Skin param. This should be cleaned
		// up at some point
		$bottomScriptText = $this->getOutput()->getBottomScripts();
		Hooks::run( 'SkinAfterBottomScripts', [ $this, &$bottomScriptText ] );

		return $bottomScriptText;
	}

	/**
	 * Text with the permalink to the source page,
	 * usually shown on the footer of a printed page
	 *
	 * @return string HTML text with an URL
	 */
	function printSource() {
		$oldid = $this->getRevisionId();
		if ( $oldid ) {
			$canonicalUrl = $this->getTitle()->getCanonicalURL( 'oldid=' . $oldid );
			$url = htmlspecialchars( wfExpandIRI( $canonicalUrl ) );
		} else {
			// oldid not available for non existing pages
			$url = htmlspecialchars( wfExpandIRI( $this->getTitle()->getCanonicalURL() ) );
		}

		return $this->msg( 'retrievedfrom' )
			->rawParams( '<a dir="ltr" href="' . $url . '">' . $url . '</a>' )
			->parse();
	}

	/**
	 * @return string HTML
	 */
	function getUndeleteLink() {
		$action = $this->getRequest()->getVal( 'action', 'view' );

		if ( $this->getTitle()->userCan( 'deletedhistory', $this->getUser() ) &&
			( !$this->getTitle()->exists() || $action == 'history' ) ) {
			$n = $this->getTitle()->isDeleted();

			if ( $n ) {
				if ( $this->getTitle()->quickUserCan( 'undelete', $this->getUser() ) ) {
					$msg = 'thisisdeleted';
				} else {
					$msg = 'viewdeleted';
				}

				return $this->msg( $msg )->rawParams(
					Linker::linkKnown(
						SpecialPage::getTitleFor( 'Undelete', $this->getTitle()->getPrefixedDBkey() ),
						$this->msg( 'restorelink' )->numParams( $n )->escaped() )
					)->escaped();
			}
		}

		return '';
	}

	/**
	 * @param OutputPage $out Defaults to $this->getOutput() if left as null
	 * @return string
	 */
	function subPageSubtitle( $out = null ) {
		if ( $out === null ) {
			$out = $this->getOutput();
		}
		$title = $out->getTitle();
		$subpages = '';

		if ( !Hooks::run( 'SkinSubPageSubtitle', [ &$subpages, $this, $out ] ) ) {
			return $subpages;
		}

		if ( $out->isArticle() && MWNamespace::hasSubpages( $title->getNamespace() ) ) {
			$ptext = $title->getPrefixedText();
			if ( strpos( $ptext, '/' ) !== false ) {
				$links = explode( '/', $ptext );
				array_pop( $links );
				$c = 0;
				$growinglink = '';
				$display = '';
				$lang = $this->getLanguage();

				foreach ( $links as $link ) {
					$growinglink .= $link;
					$display .= $link;
					$linkObj = Title::newFromText( $growinglink );

					if ( is_object( $linkObj ) && $linkObj->isKnown() ) {
						$getlink = Linker::linkKnown(
							$linkObj,
							htmlspecialchars( $display )
						);

						$c++;

						if ( $c > 1 ) {
							$subpages .= $lang->getDirMarkEntity() . $this->msg( 'pipe-separator' )->escaped();
						} else {
							$subpages .= '&lt; ';
						}

						$subpages .= $getlink;
						$display = '';
					} else {
						$display .= '/';
					}
					$growinglink .= '/';
				}
			}
		}

		return $subpages;
	}

	/**
	 * @return string
	 */
	function getSearchLink() {
		$searchPage = SpecialPage::getTitleFor( 'Search' );
		return $searchPage->getLocalURL();
	}

	/**
	 * @return string
	 */
	function escapeSearchLink() {
		return htmlspecialchars( $this->getSearchLink() );
	}

	/**
	 * @param string $type
	 * @return string
	 */
	function getCopyright( $type = 'detect' ) {
		global $wgRightsPage, $wgRightsUrl, $wgRightsText;

		if ( $type == 'detect' ) {
			if ( !$this->isRevisionCurrent()
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

		if ( $wgRightsPage ) {
			$title = Title::newFromText( $wgRightsPage );
			$link = Linker::linkKnown( $title, $wgRightsText );
		} elseif ( $wgRightsUrl ) {
			$link = Linker::makeExternalLink( $wgRightsUrl, $wgRightsText );
		} elseif ( $wgRightsText ) {
			$link = $wgRightsText;
		} else {
			# Give up now
			return '';
		}

		// Allow for site and per-namespace customization of copyright notice.
		// @todo Remove deprecated $forContent param from hook handlers and then remove here.
		$forContent = true;

		Hooks::run(
			'SkinCopyrightFooter',
			[ $this->getTitle(), $type, &$msg, &$link, &$forContent ]
		);

		return $this->msg( $msg )->rawParams( $link )->text();
	}

	/**
	 * @return null|string
	 */
	function getCopyrightIcon() {
		global $wgRightsUrl, $wgRightsText, $wgRightsIcon, $wgFooterIcons;

		$out = '';

		if ( $wgFooterIcons['copyright']['copyright'] ) {
			$out = $wgFooterIcons['copyright']['copyright'];
		} elseif ( $wgRightsIcon ) {
			$icon = htmlspecialchars( $wgRightsIcon );

			if ( $wgRightsUrl ) {
				$url = htmlspecialchars( $wgRightsUrl );
				$out .= '<a href="' . $url . '">';
			}

			$text = htmlspecialchars( $wgRightsText );
			$out .= "<img src=\"$icon\" alt=\"$text\" width=\"88\" height=\"31\" />";

			if ( $wgRightsUrl ) {
				$out .= '</a>';
			}
		}

		return $out;
	}

	/**
	 * Gets the powered by MediaWiki icon.
	 * @return string
	 */
	function getPoweredBy() {
		global $wgResourceBasePath;

		$url1 = htmlspecialchars(
			"$wgResourceBasePath/resources/assets/poweredby_mediawiki_88x31.png"
		);
		$url1_5 = htmlspecialchars(
			"$wgResourceBasePath/resources/assets/poweredby_mediawiki_132x47.png"
		);
		$url2 = htmlspecialchars(
			"$wgResourceBasePath/resources/assets/poweredby_mediawiki_176x62.png"
		);
		$text = '<a href="//www.mediawiki.org/"><img src="' . $url1
			. '" srcset="' . $url1_5 . ' 1.5x, ' . $url2 . ' 2x" '
			. 'height="31" width="88" alt="Powered by MediaWiki" /></a>';
		Hooks::run( 'SkinGetPoweredBy', [ &$text, $this ] );
		return $text;
	}

	/**
	 * Get the timestamp of the latest revision, formatted in user language
	 *
	 * @return string
	 */
	protected function lastModified() {
		$timestamp = $this->getOutput()->getRevisionTimestamp();

		# No cached timestamp, load it from the database
		if ( $timestamp === null ) {
			$timestamp = Revision::getTimestampFromId( $this->getTitle(), $this->getRevisionId() );
		}

		if ( $timestamp ) {
			$d = $this->getLanguage()->userDate( $timestamp, $this->getUser() );
			$t = $this->getLanguage()->userTime( $timestamp, $this->getUser() );
			$s = ' ' . $this->msg( 'lastmodifiedat', $d, $t )->parse();
		} else {
			$s = '';
		}

		if ( MediaWikiServices::getInstance()->getDBLoadBalancer()->getLaggedReplicaMode() ) {
			$s .= ' <strong>' . $this->msg( 'laggedslavemode' )->parse() . '</strong>';
		}

		return $s;
	}

	/**
	 * @param string $align
	 * @return string
	 */
	function logoText( $align = '' ) {
		if ( $align != '' ) {
			$a = " style='float: {$align};'";
		} else {
			$a = '';
		}

		$mp = $this->msg( 'mainpage' )->escaped();
		$mptitle = Title::newMainPage();
		$url = ( is_object( $mptitle ) ? htmlspecialchars( $mptitle->getLocalURL() ) : '' );

		$logourl = $this->getLogo();
		$s = "<a href='{$url}'><img{$a} src='{$logourl}' alt='[{$mp}]' /></a>";

		return $s;
	}

	/**
	 * Renders a $wgFooterIcons icon according to the method's arguments
	 * @param array $icon The icon to build the html for, see $wgFooterIcons
	 *   for the format of this array.
	 * @param bool|string $withImage Whether to use the icon's image or output
	 *   a text-only footericon.
	 * @return string HTML
	 */
	function makeFooterIcon( $icon, $withImage = 'withImage' ) {
		if ( is_string( $icon ) ) {
			$html = $icon;
		} else { // Assuming array
			$url = isset( $icon["url"] ) ? $icon["url"] : null;
			unset( $icon["url"] );
			if ( isset( $icon["src"] ) && $withImage === 'withImage' ) {
				// do this the lazy way, just pass icon data as an attribute array
				$html = Html::element( 'img', $icon );
			} else {
				$html = htmlspecialchars( $icon["alt"] );
			}
			if ( $url ) {
				global $wgExternalLinkTarget;
				$html = Html::rawElement( 'a',
					[ "href" => $url, "target" => $wgExternalLinkTarget ],
					$html );
			}
		}
		return $html;
	}

	/**
	 * Gets the link to the wiki's main page.
	 * @return string
	 */
	function mainPageLink() {
		$s = Linker::linkKnown(
			Title::newMainPage(),
			$this->msg( 'mainpage' )->escaped()
		);

		return $s;
	}

	/**
	 * Returns an HTML link for use in the footer
	 * @param string $desc The i18n message key for the link text
	 * @param string $page The i18n message key for the page to link to
	 * @return string HTML anchor
	 */
	public function footerLink( $desc, $page ) {
		$title = $this->footerLinkTitle( $desc, $page );
		if ( !$title ) {
			return '';
		}

		return Linker::linkKnown(
			$title,
			$this->msg( $desc )->escaped()
		);
	}

	/**
	 * @param string $desc
	 * @param string $page
	 * @return Title|null
	 */
	private function footerLinkTitle( $desc, $page ) {
		// If the link description has been set to "-" in the default language,
		if ( $this->msg( $desc )->inContentLanguage()->isDisabled() ) {
			// then it is disabled, for all languages.
			return null;
		}
		// Otherwise, we display the link for the user, described in their
		// language (which may or may not be the same as the default language),
		// but we make the link target be the one site-wide page.
		$title = Title::newFromText( $this->msg( $page )->inContentLanguage()->text() );

		return $title ?: null;
	}

	/**
	 * Gets the link to the wiki's privacy policy page.
	 * @return string HTML
	 */
	function privacyLink() {
		return $this->footerLink( 'privacy', 'privacypage' );
	}

	/**
	 * Gets the link to the wiki's about page.
	 * @return string HTML
	 */
	function aboutLink() {
		return $this->footerLink( 'aboutsite', 'aboutpage' );
	}

	/**
	 * Gets the link to the wiki's general disclaimers page.
	 * @return string HTML
	 */
	function disclaimerLink() {
		return $this->footerLink( 'disclaimers', 'disclaimerpage' );
	}

	/**
	 * Return URL options for the 'edit page' link.
	 * This may include an 'oldid' specifier, if the current page view is such.
	 *
	 * @return array
	 * @private
	 */
	function editUrlOptions() {
		$options = [ 'action' => 'edit' ];

		if ( !$this->isRevisionCurrent() ) {
			$options['oldid'] = intval( $this->getRevisionId() );
		}

		return $options;
	}

	/**
	 * @param User|int $id
	 * @return bool
	 */
	function showEmailUser( $id ) {
		if ( $id instanceof User ) {
			$targetUser = $id;
		} else {
			$targetUser = User::newFromId( $id );
		}

		# The sending user must have a confirmed email address and the receiving
		# user must accept emails from the sender.
		return $this->getUser()->canSendEmail()
			&& SpecialEmailUser::validateTarget( $targetUser, $this->getUser() ) === '';
	}

	/**
	 * Return a fully resolved style path url to images or styles stored in the current skins's folder.
	 * This method returns a url resolved using the configured skin style path
	 * and includes the style version inside of the url.
	 *
	 * Requires $stylename to be set, otherwise throws MWException.
	 *
	 * @param string $name The name or path of a skin resource file
	 * @return string The fully resolved style path url including styleversion
	 * @throws MWException
	 */
	function getSkinStylePath( $name ) {
		global $wgStylePath, $wgStyleVersion;

		if ( $this->stylename === null ) {
			$class = static::class;
			throw new MWException( "$class::\$stylename must be set to use getSkinStylePath()" );
		}

		return "$wgStylePath/{$this->stylename}/$name?$wgStyleVersion";
	}

	/* these are used extensively in SkinTemplate, but also some other places */

	/**
	 * @param string|string[] $urlaction
	 * @return string
	 */
	static function makeMainPageUrl( $urlaction = '' ) {
		$title = Title::newMainPage();
		self::checkTitle( $title, '' );

		return $title->getLinkURL( $urlaction );
	}

	/**
	 * Make a URL for a Special Page using the given query and protocol.
	 *
	 * If $proto is set to null, make a local URL. Otherwise, make a full
	 * URL with the protocol specified.
	 *
	 * @param string $name Name of the Special page
	 * @param string|string[] $urlaction Query to append
	 * @param string|null $proto Protocol to use or null for a local URL
	 * @return string
	 */
	static function makeSpecialUrl( $name, $urlaction = '', $proto = null ) {
		$title = SpecialPage::getSafeTitleFor( $name );
		if ( is_null( $proto ) ) {
			return $title->getLocalURL( $urlaction );
		} else {
			return $title->getFullURL( $urlaction, false, $proto );
		}
	}

	/**
	 * @param string $name
	 * @param string $subpage
	 * @param string|string[] $urlaction
	 * @return string
	 */
	static function makeSpecialUrlSubpage( $name, $subpage, $urlaction = '' ) {
		$title = SpecialPage::getSafeTitleFor( $name, $subpage );
		return $title->getLocalURL( $urlaction );
	}

	/**
	 * @param string $name
	 * @param string|string[] $urlaction
	 * @return string
	 */
	static function makeI18nUrl( $name, $urlaction = '' ) {
		$title = Title::newFromText( wfMessage( $name )->inContentLanguage()->text() );
		self::checkTitle( $title, $name );
		return $title->getLocalURL( $urlaction );
	}

	/**
	 * @param string $name
	 * @param string|string[] $urlaction
	 * @return string
	 */
	static function makeUrl( $name, $urlaction = '' ) {
		$title = Title::newFromText( $name );
		self::checkTitle( $title, $name );

		return $title->getLocalURL( $urlaction );
	}

	/**
	 * If url string starts with http, consider as external URL, else
	 * internal
	 * @param string $name
	 * @return string URL
	 */
	static function makeInternalOrExternalUrl( $name ) {
		if ( preg_match( '/^(?i:' . wfUrlProtocols() . ')/', $name ) ) {
			return $name;
		} else {
			return self::makeUrl( $name );
		}
	}

	/**
	 * this can be passed the NS number as defined in Language.php
	 * @param string $name
	 * @param string|string[] $urlaction
	 * @param int $namespace
	 * @return string
	 */
	static function makeNSUrl( $name, $urlaction = '', $namespace = NS_MAIN ) {
		$title = Title::makeTitleSafe( $namespace, $name );
		self::checkTitle( $title, $name );

		return $title->getLocalURL( $urlaction );
	}

	/**
	 * these return an array with the 'href' and boolean 'exists'
	 * @param string $name
	 * @param string|string[] $urlaction
	 * @return array
	 */
	static function makeUrlDetails( $name, $urlaction = '' ) {
		$title = Title::newFromText( $name );
		self::checkTitle( $title, $name );

		return [
			'href' => $title->getLocalURL( $urlaction ),
			'exists' => $title->isKnown(),
		];
	}

	/**
	 * Make URL details where the article exists (or at least it's convenient to think so)
	 * @param string $name Article name
	 * @param string|string[] $urlaction
	 * @return array
	 */
	static function makeKnownUrlDetails( $name, $urlaction = '' ) {
		$title = Title::newFromText( $name );
		self::checkTitle( $title, $name );

		return [
			'href' => $title->getLocalURL( $urlaction ),
			'exists' => true
		];
	}

	/**
	 * make sure we have some title to operate on
	 *
	 * @param Title &$title
	 * @param string $name
	 */
	static function checkTitle( &$title, $name ) {
		if ( !is_object( $title ) ) {
			$title = Title::newFromText( $name );
			if ( !is_object( $title ) ) {
				$title = Title::newFromText( '--error: link target missing--' );
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
	 *   - a message name (e.g. 'navigation'), the message should be HTML-escaped by the skin
	 *   - plain text, which should be HTML-escaped by the skin
	 * - content is the contents of the portlet. It is either:
	 *   - HTML text (<ul><li>...</li>...</ul>)
	 *   - array of link data in a format accepted by BaseTemplate::makeListItem()
	 *   - (for a magic string as a key, any value)
	 *
	 * Note that extensions can control the sidebar contents using the SkinBuildSidebar hook
	 * and can technically insert anything in here; skin creators are expected to handle
	 * values described above.
	 *
	 * @return array
	 */
	public function buildSidebar() {
		global $wgEnableSidebarCache, $wgSidebarCacheExpiry;

		$callback = function ( $old = null, &$ttl = null ) {
			$bar = [];
			$this->addToSidebar( $bar, 'sidebar' );
			Hooks::run( 'SkinBuildSidebar', [ $this, &$bar ] );
			if ( MessageCache::singleton()->isDisabled() ) {
				$ttl = WANObjectCache::TTL_UNCACHEABLE; // bug T133069
			}

			return $bar;
		};

		$msgCache = MessageCache::singleton();
		$wanCache = MediaWikiServices::getInstance()->getMainWANObjectCache();

		$sidebar = $wgEnableSidebarCache
			? $wanCache->getWithSetCallback(
				$wanCache->makeKey( 'sidebar', $this->getLanguage()->getCode() ),
				$wgSidebarCacheExpiry,
				$callback,
				[
					'checkKeys' => [
						// Unless there is both no exact $code override nor an i18n definition
						// in the the software, the only MediaWiki page to check is for $code.
						$msgCache->getCheckKey( $this->getLanguage()->getCode() )
					],
					'lockTSE' => 30
				]
			)
			: $callback();

		// Apply post-processing to the cached value
		Hooks::run( 'SidebarBeforeOutput', [ $this, &$sidebar ] );

		return $sidebar;
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
		$this->addToSidebarPlain( $bar, wfMessage( $message )->inContentLanguage()->plain() );
	}

	/**
	 * Add content from plain text
	 * @since 1.17
	 * @param array &$bar
	 * @param string $text
	 * @return array
	 */
	function addToSidebarPlain( &$bar, $text ) {
		$lines = explode( "\n", $text );

		$heading = '';
		$messageTitle = $this->getConfig()->get( 'EnableSidebarCache' )
			? Title::newMainPage() : $this->getTitle();

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

				if ( strpos( $line, '|' ) !== false ) { // sanity check
					$line = MessageCache::singleton()->transform( $line, false, null, $messageTitle );
					$line = array_map( 'trim', explode( '|', $line, 2 ) );
					if ( count( $line ) !== 2 ) {
						// Second sanity check, could be hit by people doing
						// funky stuff with parserfuncs... (T35321)
						continue;
					}

					$extraAttribs = [];

					$msgLink = $this->msg( $line[0] )->title( $messageTitle )->inContentLanguage();
					if ( $msgLink->exists() ) {
						$link = $msgLink->text();
						if ( $link == '-' ) {
							continue;
						}
					} else {
						$link = $line[0];
					}
					$msgText = $this->msg( $line[1] )->title( $messageTitle );
					if ( $msgText->exists() ) {
						$text = $msgText->text();
					} else {
						$text = $line[1];
					}

					if ( preg_match( '/^(?i:' . wfUrlProtocols() . ')/', $link ) ) {
						$href = $link;

						// Parser::getExternalLinkAttribs won't work here because of the Namespace things
						global $wgNoFollowLinks, $wgNoFollowDomainExceptions;
						if ( $wgNoFollowLinks && !wfMatchesDomainList( $href, $wgNoFollowDomainExceptions ) ) {
							$extraAttribs['rel'] = 'nofollow';
						}

						global $wgExternalLinkTarget;
						if ( $wgExternalLinkTarget ) {
							$extraAttribs['target'] = $wgExternalLinkTarget;
						}
					} else {
						$title = Title::newFromText( $link );

						if ( $title ) {
							$title = $title->fixSpecialName();
							$href = $title->getLinkURL();
						} else {
							$href = 'INVALID-TITLE';
						}
					}

					$bar[$heading][] = array_merge( [
						'text' => $text,
						'href' => $href,
						'id' => Sanitizer::escapeIdForAttribute( 'n-' . strtr( $line[1], ' ', '-' ) ),
						'active' => false,
					], $extraAttribs );
				} else {
					continue;
				}
			}
		}

		return $bar;
	}

	/**
	 * Gets new talk page messages for the current user and returns an
	 * appropriate alert message (or an empty string if there are no messages)
	 * @return string
	 */
	function getNewtalks() {
		$newMessagesAlert = '';
		$user = $this->getUser();
		$newtalks = $user->getNewMessageLinks();
		$out = $this->getOutput();

		// Allow extensions to disable or modify the new messages alert
		if ( !Hooks::run( 'GetNewMessagesAlert', [ &$newMessagesAlert, $newtalks, $user, $out ] ) ) {
			return '';
		}
		if ( $newMessagesAlert ) {
			return $newMessagesAlert;
		}

		if ( count( $newtalks ) == 1 && $newtalks[0]['wiki'] === wfWikiID() ) {
			$uTalkTitle = $user->getTalkPage();
			$lastSeenRev = isset( $newtalks[0]['rev'] ) ? $newtalks[0]['rev'] : null;
			$nofAuthors = 0;
			if ( $lastSeenRev !== null ) {
				$plural = true; // Default if we have a last seen revision: if unknown, use plural
				$latestRev = Revision::newFromTitle( $uTalkTitle, false, Revision::READ_NORMAL );
				if ( $latestRev !== null ) {
					// Singular if only 1 unseen revision, plural if several unseen revisions.
					$plural = $latestRev->getParentId() !== $lastSeenRev->getId();
					$nofAuthors = $uTalkTitle->countAuthorsBetween(
						$lastSeenRev, $latestRev, 10, 'include_new' );
				}
			} else {
				// Singular if no revision -> diff link will show latest change only in any case
				$plural = false;
			}
			$plural = $plural ? 999 : 1;
			// 999 signifies "more than one revision". We don't know how many, and even if we did,
			// the number of revisions or authors is not necessarily the same as the number of
			// "messages".
			$newMessagesLink = Linker::linkKnown(
				$uTalkTitle,
				$this->msg( 'newmessageslinkplural' )->params( $plural )->escaped(),
				[],
				[ 'redirect' => 'no' ]
			);

			$newMessagesDiffLink = Linker::linkKnown(
				$uTalkTitle,
				$this->msg( 'newmessagesdifflinkplural' )->params( $plural )->escaped(),
				[],
				$lastSeenRev !== null
					? [ 'oldid' => $lastSeenRev->getId(), 'diff' => 'cur' ]
					: [ 'diff' => 'cur' ]
			);

			if ( $nofAuthors >= 1 && $nofAuthors <= 10 ) {
				$newMessagesAlert = $this->msg(
					'youhavenewmessagesfromusers',
					$newMessagesLink,
					$newMessagesDiffLink
				)->numParams( $nofAuthors, $plural );
			} else {
				// $nofAuthors === 11 signifies "11 or more" ("more than 10")
				$newMessagesAlert = $this->msg(
					$nofAuthors > 10 ? 'youhavenewmessagesmanyusers' : 'youhavenewmessages',
					$newMessagesLink,
					$newMessagesDiffLink
				)->numParams( $plural );
			}
			$newMessagesAlert = $newMessagesAlert->text();
			# Disable CDN cache
			$out->setCdnMaxage( 0 );
		} elseif ( count( $newtalks ) ) {
			$sep = $this->msg( 'newtalkseparator' )->escaped();
			$msgs = [];

			foreach ( $newtalks as $newtalk ) {
				$msgs[] = Xml::element(
					'a',
					[ 'href' => $newtalk['link'] ], $newtalk['wiki']
				);
			}
			$parts = implode( $sep, $msgs );
			$newMessagesAlert = $this->msg( 'youhavenewmessagesmulti' )->rawParams( $parts )->escaped();
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
		global $wgRenderHashAppend, $wgContLang;

		$needParse = false;

		if ( $name === 'default' ) {
			// special case
			global $wgSiteNotice;
			$notice = $wgSiteNotice;
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

		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$parsed = $cache->getWithSetCallback(
			// Use the extra hash appender to let eg SSL variants separately cache
			// Key is verified with md5 hash of unparsed wikitext
			$cache->makeKey( $name, $wgRenderHashAppend, md5( $notice ) ),
			// TTL in seconds
			600,
			function () use ( $notice ) {
				return $this->getOutput()->parse( $notice );
			}
		);

		return Html::rawElement(
			'div',
			[
				'id' => 'localNotice',
				'lang' => $wgContLang->getHtmlCode(),
				'dir' => $wgContLang->getDir()
			],
			$parsed
		);
	}

	/**
	 * Get the site notice
	 *
	 * @return string HTML fragment
	 */
	function getSiteNotice() {
		$siteNotice = '';

		if ( Hooks::run( 'SiteNoticeBefore', [ &$siteNotice, $this ] ) ) {
			if ( is_object( $this->getUser() ) && $this->getUser()->isLoggedIn() ) {
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
				$siteNotice = $this->getCachedNotice( 'default' );
			}
		}

		Hooks::run( 'SiteNoticeAfter', [ &$siteNotice, $this ] );
		return $siteNotice;
	}

	/**
	 * Create a section edit link.  This supersedes editSectionLink() and
	 * editSectionLinkForOther().
	 *
	 * @param Title $nt The title being linked to (may not be the same as
	 *   the current page, if the section is included from a template)
	 * @param string $section The designation of the section being pointed to,
	 *   to be included in the link, like "&section=$section"
	 * @param string $tooltip The tooltip to use for the link: will be escaped
	 *   and wrapped in the 'editsectionhint' message
	 * @param string $lang Language code
	 * @return string HTML to use for edit link
	 */
	public function doEditSectionLink( Title $nt, $section, $tooltip = null, $lang = false ) {
		// HTML generated here should probably have userlangattributes
		// added to it for LTR text on RTL pages

		$lang = wfGetLangObj( $lang );

		$attribs = [];
		if ( !is_null( $tooltip ) ) {
			$attribs['title'] = wfMessage( 'editsectionhint' )->rawParams( $tooltip )
				->inLanguage( $lang )->text();
		}

		$links = [
			'editsection' => [
				'text' => wfMessage( 'editsection' )->inLanguage( $lang )->escaped(),
				'targetTitle' => $nt,
				'attribs' => $attribs,
				'query' => [ 'action' => 'edit', 'section' => $section ],
				'options' => [ 'noclasses', 'known' ]
			]
		];

		Hooks::run( 'SkinEditSectionLinks', [ $this, $nt, $section, $tooltip, &$links, $lang ] );

		$result = '<span class="mw-editsection"><span class="mw-editsection-bracket">[</span>';

		$linksHtml = [];
		foreach ( $links as $k => $linkDetails ) {
			$linksHtml[] = Linker::link(
				$linkDetails['targetTitle'],
				$linkDetails['text'],
				$linkDetails['attribs'],
				$linkDetails['query'],
				$linkDetails['options']
			);
		}

		$result .= implode(
			'<span class="mw-editsection-divider">'
				. wfMessage( 'pipe-separator' )->inLanguage( $lang )->escaped()
				. '</span>',
			$linksHtml
		);

		$result .= '<span class="mw-editsection-bracket">]</span></span>';
		// Deprecated, use SkinEditSectionLinks hook instead
		Hooks::run(
			'DoEditSectionLink',
			[ $this, $nt, $section, $tooltip, &$result, $lang ],
			'1.25'
		);
		return $result;
	}

}
