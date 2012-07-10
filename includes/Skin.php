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

/**
 * @defgroup Skins Skins
 */

/**
 * The main skin class that provide methods and properties for all other skins.
 * This base class is also the "Standard" skin.
 *
 * See docs/skin.txt for more information.
 *
 * @ingroup Skins
 */
abstract class Skin extends ContextSource {
	protected $skinname = 'standard';
	protected $mRelevantTitle = null;
	protected $mRelevantUser = null;

	/**
	 * Fetch the set of available skins.
	 * @return array associative array of strings
	 */
	static function getSkinNames() {
		global $wgValidSkinNames;
		static $skinsInitialised = false;

		if ( !$skinsInitialised || !count( $wgValidSkinNames ) ) {
			# Get a list of available skins
			# Build using the regular expression '^(.*).php$'
			# Array keys are all lower case, array value keep the case used by filename
			#
			wfProfileIn( __METHOD__ . '-init' );

			global $wgStyleDirectory;

			$skinDir = dir( $wgStyleDirectory );

			# while code from www.php.net
			while ( false !== ( $file = $skinDir->read() ) ) {
				// Skip non-PHP files, hidden files, and '.dep' includes
				$matches = array();

				if ( preg_match( '/^([^.]*)\.php$/', $file, $matches ) ) {
					$aSkin = $matches[1];
					$wgValidSkinNames[strtolower( $aSkin )] = $aSkin;
				}
			}
			$skinDir->close();
			$skinsInitialised = true;
			wfProfileOut( __METHOD__ . '-init' );
		}
		return $wgValidSkinNames;
	}

 	/**
	 * Fetch the skinname messages for available skins.
	 * @return array of strings
	 */
	static function getSkinNameMessages() {
		$messages = array();
		foreach( self::getSkinNames() as $skinKey => $skinName ) {
			$messages[] = "skinname-$skinKey";
		}
		return $messages;
	}

	/**
	 * Fetch the list of usable skins in regards to $wgSkipSkins.
	 * Useful for Special:Preferences and other places where you
	 * only want to show skins users _can_ use.
	 * @return array of strings
	 */
	public static function getUsableSkins() {
		global $wgSkipSkins;

		$usableSkins = self::getSkinNames();

		foreach ( $wgSkipSkins as $skip ) {
			unset( $usableSkins[$skip] );
		}

		return $usableSkins;
	}

	/**
	 * Normalize a skin preference value to a form that can be loaded.
	 * If a skin can't be found, it will fall back to the configured
	 * default (or the old 'Classic' skin if that's broken).
	 * @param $key String: 'monobook', 'standard', etc.
	 * @return string
	 */
	static function normalizeKey( $key ) {
		global $wgDefaultSkin;

		$skinNames = Skin::getSkinNames();

		if ( $key == '' || $key == 'default' ) {
			// Don't return the default immediately;
			// in a misconfiguration we need to fall back.
			$key = $wgDefaultSkin;
		}

		if ( isset( $skinNames[$key] ) ) {
			return $key;
		}

		// Older versions of the software used a numeric setting
		// in the user preferences.
		$fallback = array(
			0 => $wgDefaultSkin,
			1 => 'nostalgia',
			2 => 'cologneblue'
		);

		if ( isset( $fallback[$key] ) ) {
			$key = $fallback[$key];
		}

		if ( isset( $skinNames[$key] ) ) {
			return $key;
		} elseif ( isset( $skinNames[$wgDefaultSkin] ) ) {
			return $wgDefaultSkin;
		} else {
			return 'vector';
		}
	}

	/**
	 * Factory method for loading a skin of a given type
	 * @param $key String: 'monobook', 'standard', etc.
	 * @return Skin
	 */
	static function &newFromKey( $key ) {
		global $wgStyleDirectory;

		$key = Skin::normalizeKey( $key );

		$skinNames = Skin::getSkinNames();
		$skinName = $skinNames[$key];
		$className = "Skin{$skinName}";

		# Grab the skin class and initialise it.
		if ( !MWInit::classExists( $className ) ) {

			if ( !defined( 'MW_COMPILED' ) ) {
				require_once( "{$wgStyleDirectory}/{$skinName}.php" );
			}

			# Check if we got if not failback to default skin
			if ( !MWInit::classExists( $className ) ) {
				# DO NOT die if the class isn't found. This breaks maintenance
				# scripts and can cause a user account to be unrecoverable
				# except by SQL manipulation if a previously valid skin name
				# is no longer valid.
				wfDebug( "Skin class does not exist: $className\n" );
				$className = 'SkinVector';
				if ( !defined( 'MW_COMPILED' ) ) {
					require_once( "{$wgStyleDirectory}/Vector.php" );
				}
			}
		}
		$skin = new $className( $key );
		return $skin;
	}

	/** @return string skin name */
	public function getSkinName() {
		return $this->skinname;
	}

	/**
	 * @param $out OutputPage
	 */
	function initPage( OutputPage $out ) {
		wfProfileIn( __METHOD__ );

		$this->preloadExistence();

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Preload the existence of three commonly-requested pages in a single query
	 */
	function preloadExistence() {
		$user = $this->getUser();

		// User/talk link
		$titles = array( $user->getUserPage(), $user->getTalkPage() );

		// Other tab link
		if ( $this->getTitle()->isSpecialPage() ) {
			// nothing
		} elseif ( $this->getTitle()->isTalkPage() ) {
			$titles[] = $this->getTitle()->getSubjectPage();
		} else {
			$titles[] = $this->getTitle()->getTalkPage();
		}

		$lb = new LinkBatch( $titles );
		$lb->setCaller( __METHOD__ );
		$lb->execute();
	}

	/**
	 * Get the current revision ID
	 *
	 * @return Integer
	 */
	public function getRevisionId() {
		return $this->getOutput()->getRevisionId();
	}

	/**
	 * Whether the revision displayed is the latest revision of the page
	 *
	 * @return Boolean
	 */
	public function isRevisionCurrent() {
		$revID = $this->getRevisionId();
		return $revID == 0 || $revID == $this->getTitle()->getLatestRevID();
	}

	/**
	 * Set the "relevant" title
	 * @see self::getRelevantTitle()
	 * @param $t Title object to use
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
		if ( isset($this->mRelevantTitle) ) {
			return $this->mRelevantTitle;
		}
		return $this->getTitle();
	}

	/**
	 * Set the "relevant" user
	 * @see self::getRelevantUser()
	 * @param $u User object to use
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
		if ( isset($this->mRelevantUser) ) {
			return $this->mRelevantUser;
		}
		$title = $this->getRelevantTitle();
		if( $title->getNamespace() == NS_USER || $title->getNamespace() == NS_USER_TALK ) {
			$rootUser = strtok( $title->getText(), '/' );
			if ( User::isIP( $rootUser ) ) {
				$this->mRelevantUser = User::newFromName( $rootUser, false );
			} else {
				$user = User::newFromName( $rootUser, false );
				if ( $user && $user->isLoggedIn() ) {
					$this->mRelevantUser = $user;
				}
			}
			return $this->mRelevantUser;
		}
		return null;
	}

	/**
	 * Outputs the HTML generated by other functions.
	 * @param $out OutputPage
	 */
	abstract function outputPage( OutputPage $out = null );

	/**
	 * @param $data array
	 * @return string
	 */
	static function makeVariablesScript( $data ) {
		if ( $data ) {
			return Html::inlineScript(
				ResourceLoader::makeLoaderConditionalScript( ResourceLoader::makeConfigSetScript( $data ) )
			);
		} else {
			return '';
		}
	}

	/**
	 * Make a "<script>" tag containing global variables
	 *
	 * @deprecated in 1.19
	 * @param $unused
	 * @return string HTML fragment
	 */
	public static function makeGlobalVariablesScript( $unused ) {
		global $wgOut;

		wfDeprecated( __METHOD__, '1.19' );

		return self::makeVariablesScript( $wgOut->getJSVars() );
	}

	/**
	 * Get the query to generate a dynamic stylesheet
	 *
	 * @return array
	 */
	public static function getDynamicStylesheetQuery() {
		global $wgSquidMaxage;

		return array(
				'action' => 'raw',
				'maxage' => $wgSquidMaxage,
				'usemsgcache' => 'yes',
				'ctype' => 'text/css',
				'smaxage' => $wgSquidMaxage,
			);
	}

	/**
	 * Add skin specific stylesheets
	 * Calling this method with an $out of anything but the same OutputPage
	 * inside ->getOutput() is deprecated. The $out arg is kept
	 * for compatibility purposes with skins.
	 * @param $out OutputPage
	 * @todo delete
	 */
	abstract function setupSkinUserCss( OutputPage $out );

	/**
	 * TODO: document
	 * @param $title Title
	 * @return String
	 */
	function getPageClasses( $title ) {
		$numeric = 'ns-' . $title->getNamespace();

		if ( $title->isSpecialPage() ) {
			$type = 'ns-special';
			// bug 23315: provide a class based on the canonical special page name without subpages
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

		return "$numeric $type $name";
	}

	/**
	 * This will be called by OutputPage::headElement when it is creating the
	 * "<body>" tag, skins can override it if they have a need to add in any
	 * body attributes or classes of their own.
	 * @param $out OutputPage
	 * @param $bodyAttrs Array
	 */
	function addToBodyAttributes( $out, &$bodyAttrs ) {
		// does nothing by default
	}

	/**
	 * URL to the logo
	 * @return String
	 */
	function getLogo() {
		global $wgLogo;
		return $wgLogo;
	}

	/**
	 * @return string
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
			$t = $embed . implode( "{$pop}{$embed}" , $allCats['normal'] ) . $pop;

			$msg = $this->msg( 'pagecategories' )->numParams( count( $allCats['normal'] ) )->escaped();
			$linkPage = wfMessage( 'pagecategorieslink' )->inContentLanguage()->text();
			$s .= '<div id="mw-normal-catlinks" class="mw-normal-catlinks">' .
				Linker::link( Title::newFromText( $linkPage ), $msg )
				. $colon . '<ul>' . $t . '</ul>' . '</div>';
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
				$colon . '<ul>' . $embed . implode( "{$pop}{$embed}" , $allCats['hidden'] ) . $pop . '</ul>' .
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
	 * Render the array as a serie of links.
	 * @param $tree Array: categories tree returned by Title::getParentCategoryTree
	 * @return String separated by &gt;, terminate with "\n"
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
			$return .=  Linker::link( $eltitle, htmlspecialchars( $eltitle->getText() ) );
		}

		return $return;
	}

	/**
	 * @return string
	 */
	function getCategories() {
		$out = $this->getOutput();

		$catlinks = $this->getCategoryLinks();

		$classes = 'catlinks';

		// Check what we're showing
		$allCats = $out->getCategoryLinks();
		$showHidden = $this->getUser()->getBoolOption( 'showhiddencats' ) ||
						$this->getTitle()->getNamespace() == NS_CATEGORY;

		if ( empty( $allCats['normal'] ) && !( !empty( $allCats['hidden'] ) && $showHidden ) ) {
			$classes .= ' catlinks-allhidden';
		}

		return "<div id='catlinks' class='$classes'>{$catlinks}</div>";
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
	 * @return String, empty by default, if not changed by any hook function.
	 */
	protected function afterContentHook() {
		$data = '';

		if ( wfRunHooks( 'SkinAfterContent', array( &$data, $this ) ) ) {
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
	 * @return String HTML containing debug data, if enabled (otherwise empty).
	 */
	protected function generateDebugHTML() {
		return MWDebug::getHTMLDebugLog();
	}

	/**
	 * This gets called shortly before the "</body>" tag.
	 *
	 * @return String HTML-wrapped JS code to be put before "</body>"
	 */
	function bottomScripts() {
		// TODO and the suckage continues. This function is really just a wrapper around
		// OutputPage::getBottomScripts() which takes a Skin param. This should be cleaned
		// up at some point
		$bottomScriptText = $this->getOutput()->getBottomScripts();
		wfRunHooks( 'SkinAfterBottomScripts', array( $this, &$bottomScriptText ) );

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
			$url = htmlspecialchars( wfExpandIRI( $this->getTitle()->getCanonicalURL( 'oldid=' . $oldid ) ) );
		} else {
			// oldid not available for non existing pages
			$url = htmlspecialchars( wfExpandIRI( $this->getTitle()->getCanonicalURL() ) );
		}
		return $this->msg( 'retrievedfrom', '<a href="' . $url . '">' . $url . '</a>' )->text();
	}

	/**
	 * @return String
	 */
	function getUndeleteLink() {
		$action = $this->getRequest()->getVal( 'action', 'view' );

		if ( $this->getUser()->isAllowed( 'deletedhistory' ) &&
			( $this->getTitle()->getArticleID() == 0 || $action == 'history' ) ) {
			$n = $this->getTitle()->isDeleted();


			if ( $n ) {
				if ( $this->getUser()->isAllowed( 'undelete' ) ) {
					$msg = 'thisisdeleted';
				} else {
					$msg = 'viewdeleted';
				}

				return $this->msg( $msg )->rawParams(
					Linker::linkKnown(
						SpecialPage::getTitleFor( 'Undelete', $this->getTitle()->getPrefixedDBkey() ),
						$this->msg( 'restorelink' )->numParams( $n )->escaped() )
					)->text();
			}
		}

		return '';
	}

	/**
	 * @return string
	 */
	function subPageSubtitle() {
		global $wgLang;
		$out = $this->getOutput();
		$subpages = '';

		if ( !wfRunHooks( 'SkinSubPageSubtitle', array( &$subpages, $this, $out ) ) ) {
			return $subpages;
		}

		if ( $out->isArticle() && MWNamespace::hasSubpages( $out->getTitle()->getNamespace() ) ) {
			$ptext = $this->getTitle()->getPrefixedText();
			if ( preg_match( '/\//', $ptext ) ) {
				$links = explode( '/', $ptext );
				array_pop( $links );
				$c = 0;
				$growinglink = '';
				$display = '';

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
							$subpages .= $wgLang->getDirMarkEntity() . $this->msg( 'pipe-separator' )->escaped();
						} else  {
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
	 * Returns true if the IP should be shown in the header
	 * @return Bool
	 */
	function showIPinHeader() {
		global $wgShowIPinHeader;
		return $wgShowIPinHeader && session_id() != '';
	}

	/**
	 * @return String
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
	 * @param $type string
	 * @return string
	 */
	function getCopyright( $type = 'detect' ) {
		global $wgRightsPage, $wgRightsUrl, $wgRightsText, $wgContLang;

		if ( $type == 'detect' ) {
			if ( !$this->isRevisionCurrent() && !$this->msg( 'history_copyright' )->inContentLanguage()->isDisabled() ) {
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
		$forContent = true;

		wfRunHooks( 'SkinCopyrightFooter', array( $this->getTitle(), $type, &$msg, &$link, &$forContent ) );

		$msgObj = $this->msg( $msg )->rawParams( $link );
		if ( $forContent ) {
			$msg = $msgObj->inContentLanguage()->text();
			if ( $this->getLanguage()->getCode() !== $wgContLang->getCode() ) {
				$msg = Html::rawElement( 'span', array( 'lang' => $wgContLang->getHtmlCode(), 'dir' => $wgContLang->getDir() ), $msg );
			}
			return $msg;
		} else {
			return $msgObj->text();
		}
	}

	/**
	 * @return null|string
	 */
	function getCopyrightIcon() {
		global $wgRightsUrl, $wgRightsText, $wgRightsIcon, $wgCopyrightIcon;

		$out = '';

		if ( isset( $wgCopyrightIcon ) && $wgCopyrightIcon ) {
			$out = $wgCopyrightIcon;
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
		global $wgStylePath;

		$url = htmlspecialchars( "$wgStylePath/common/images/poweredby_mediawiki_88x31.png" );
		$text = '<a href="//www.mediawiki.org/"><img src="' . $url . '" height="31" width="88" alt="Powered by MediaWiki" /></a>';
		wfRunHooks( 'SkinGetPoweredBy', array( &$text, $this ) );
		return $text;
	}

	/**
	 * Get the timestamp of the latest revision, formatted in user language
	 *
	 * @return String
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
			$s = ' ' . $this->msg( 'lastmodifiedat', $d, $t )->text();
		} else {
			$s = '';
		}

		if ( wfGetLB()->getLaggedSlaveMode() ) {
			$s .= ' <strong>' . $this->msg( 'laggedslavemode' )->text() . '</strong>';
		}

		return $s;
	}

	/**
	 * @param $align string
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
	 * Renders a $wgFooterIcons icon acording to the method's arguments
	 * @param $icon Array: The icon to build the html for, see $wgFooterIcons for the format of this array
	 * @param $withImage Bool|String: Whether to use the icon's image or output a text-only footericon
	 * @return String HTML
	 */
	function makeFooterIcon( $icon, $withImage = 'withImage' ) {
		if ( is_string( $icon ) ) {
			$html = $icon;
		} else { // Assuming array
			$url = isset($icon["url"]) ? $icon["url"] : null;
			unset( $icon["url"] );
			if ( isset( $icon["src"] ) && $withImage === 'withImage' ) {
				$html = Html::element( 'img', $icon ); // do this the lazy way, just pass icon data as an attribute array
			} else {
				$html = htmlspecialchars( $icon["alt"] );
			}
			if ( $url ) {
				$html = Html::rawElement( 'a', array( "href" => $url ), $html );
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
	 * @param $desc
	 * @param $page
	 * @return string
	 */
	public function footerLink( $desc, $page ) {
		// if the link description has been set to "-" in the default language,
		if ( $this->msg( $desc )->inContentLanguage()->isDisabled() ) {
			// then it is disabled, for all languages.
			return '';
		} else {
			// Otherwise, we display the link for the user, described in their
			// language (which may or may not be the same as the default language),
			// but we make the link target be the one site-wide page.
			$title = Title::newFromText( $this->msg( $page )->inContentLanguage()->text() );

			return Linker::linkKnown(
				$title,
				$this->msg( $desc )->escaped()
			);
		}
	}

	/**
	 * Gets the link to the wiki's privacy policy page.
	 * @return String HTML
	 */
	function privacyLink() {
		return $this->footerLink( 'privacy', 'privacypage' );
	}

	/**
	 * Gets the link to the wiki's about page.
	 * @return String HTML
	 */
	function aboutLink() {
		return $this->footerLink( 'aboutsite', 'aboutpage' );
	}

	/**
	 * Gets the link to the wiki's general disclaimers page.
	 * @return String HTML
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
		$options = array( 'action' => 'edit' );

		if ( !$this->isRevisionCurrent() ) {
			$options['oldid'] = intval( $this->getRevisionId() );
		}

		return $options;
	}

	/**
	 * @param $id User|int
	 * @return bool
	 */
	function showEmailUser( $id ) {
		if ( $id instanceof User ) {
			$targetUser = $id;
		} else {
			$targetUser = User::newFromId( $id );
		}
		return $this->getUser()->canSendEmail() && # the sending user must have a confirmed email address
			$targetUser->canReceiveEmail(); # the target user must have a confirmed email address and allow emails from users
	}

	/**
	 * Return a fully resolved style path url to images or styles stored in the common folder.
	 * This method returns a url resolved using the configured skin style path
	 * and includes the style version inside of the url.
	 * @param $name String: The name or path of a skin resource file
	 * @return String The fully resolved style path url including styleversion
	 */
	function getCommonStylePath( $name ) {
		global $wgStylePath, $wgStyleVersion;
		return "$wgStylePath/common/$name?$wgStyleVersion";
	}

	/**
	 * Return a fully resolved style path url to images or styles stored in the curent skins's folder.
	 * This method returns a url resolved using the configured skin style path
	 * and includes the style version inside of the url.
	 * @param $name String: The name or path of a skin resource file
	 * @return String The fully resolved style path url including styleversion
	 */
	function getSkinStylePath( $name ) {
		global $wgStylePath, $wgStyleVersion;
		return "$wgStylePath/{$this->stylename}/$name?$wgStyleVersion";
	}

	/* these are used extensively in SkinTemplate, but also some other places */

	/**
	 * @param $urlaction string
	 * @return String
	 */
	static function makeMainPageUrl( $urlaction = '' ) {
		$title = Title::newMainPage();
		self::checkTitle( $title, '' );

		return $title->getLocalURL( $urlaction );
	}

	/**
	 * Make a URL for a Special Page using the given query and protocol.
	 *
	 * If $proto is set to null, make a local URL. Otherwise, make a full
	 * URL with the protocol specified.
	 *
	 * @param $name string Name of the Special page
	 * @param $urlaction string Query to append
	 * @param $proto Protocol to use or null for a local URL
	 * @return String
	 */
	static function makeSpecialUrl( $name, $urlaction = '', $proto = null ) {
		$title = SpecialPage::getSafeTitleFor( $name );
		if( is_null( $proto ) ) {
			return $title->getLocalURL( $urlaction );
		} else {
			return $title->getFullURL( $urlaction, false, $proto );
		}
	}

	/**
	 * @param $name string
	 * @param $subpage string
	 * @param $urlaction string
	 * @return String
	 */
	static function makeSpecialUrlSubpage( $name, $subpage, $urlaction = '' ) {
		$title = SpecialPage::getSafeTitleFor( $name, $subpage );
		return $title->getLocalURL( $urlaction );
	}

	/**
	 * @param $name string
	 * @param $urlaction string
	 * @return String
	 */
	static function makeI18nUrl( $name, $urlaction = '' ) {
		$title = Title::newFromText( wfMessage( $name )->inContentLanguage()->text() );
		self::checkTitle( $title, $name );
		return $title->getLocalURL( $urlaction );
	}

	/**
	 * @param $name string
	 * @param $urlaction string
	 * @return String
	 */
	static function makeUrl( $name, $urlaction = '' ) {
		$title = Title::newFromText( $name );
		self::checkTitle( $title, $name );

		return $title->getLocalURL( $urlaction );
	}

	/**
	 * If url string starts with http, consider as external URL, else
	 * internal
	 * @param $name String
	 * @return String URL
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
	 * @param $name
	 * @param $urlaction string
	 * @param $namespace int
	 * @return String
	 */
	static function makeNSUrl( $name, $urlaction = '', $namespace = NS_MAIN ) {
		$title = Title::makeTitleSafe( $namespace, $name );
		self::checkTitle( $title, $name );

		return $title->getLocalURL( $urlaction );
	}

	/**
	 * these return an array with the 'href' and boolean 'exists'
	 * @param $name
	 * @param $urlaction string
	 * @return array
	 */
	static function makeUrlDetails( $name, $urlaction = '' ) {
		$title = Title::newFromText( $name );
		self::checkTitle( $title, $name );

		return array(
			'href' => $title->getLocalURL( $urlaction ),
			'exists' => $title->getArticleID() != 0,
		);
	}

	/**
	 * Make URL details where the article exists (or at least it's convenient to think so)
	 * @param $name String Article name
	 * @param $urlaction String
	 * @return Array
	 */
	static function makeKnownUrlDetails( $name, $urlaction = '' ) {
		$title = Title::newFromText( $name );
		self::checkTitle( $title, $name );

		return array(
			'href' => $title->getLocalURL( $urlaction ),
			'exists' => true
		);
	}

	/**
	 * make sure we have some title to operate on
	 *
	 * @param $title Title
	 * @param $name string
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
	 * Build an array that represents the sidebar(s), the navigation bar among them
	 *
	 * @return array
	 */
	function buildSidebar() {
		global $wgMemc, $wgEnableSidebarCache, $wgSidebarCacheExpiry;
		wfProfileIn( __METHOD__ );

		$key = wfMemcKey( 'sidebar', $this->getLanguage()->getCode() );

		if ( $wgEnableSidebarCache ) {
			$cachedsidebar = $wgMemc->get( $key );
			if ( $cachedsidebar ) {
				wfProfileOut( __METHOD__ );
				return $cachedsidebar;
			}
		}

		$bar = array();
		$this->addToSidebar( $bar, 'sidebar' );

		wfRunHooks( 'SkinBuildSidebar', array( $this, &$bar ) );
		if ( $wgEnableSidebarCache ) {
			$wgMemc->set( $key, $bar, $wgSidebarCacheExpiry );
		}

		wfProfileOut( __METHOD__ );
		return $bar;
	}
	/**
	 * Add content from a sidebar system message
	 * Currently only used for MediaWiki:Sidebar (but may be used by Extensions)
	 *
	 * This is just a wrapper around addToSidebarPlain() for backwards compatibility
	 *
	 * @param $bar array
	 * @param $message String
	 */
	function addToSidebar( &$bar, $message ) {
		$this->addToSidebarPlain( $bar, wfMessage( $message )->inContentLanguage()->plain() );
	}

	/**
	 * Add content from plain text
	 * @since 1.17
	 * @param $bar array
	 * @param $text string
	 * @return Array
	 */
	function addToSidebarPlain( &$bar, $text ) {
		$lines = explode( "\n", $text );

		$heading = '';

		foreach ( $lines as $line ) {
			if ( strpos( $line, '*' ) !== 0 ) {
				continue;
			}
			$line = rtrim( $line, "\r" ); // for Windows compat

			if ( strpos( $line, '**' ) !== 0 ) {
				$heading = trim( $line, '* ' );
				if ( !array_key_exists( $heading, $bar ) ) {
					$bar[$heading] = array();
				}
			} else {
				$line = trim( $line, '* ' );

				if ( strpos( $line, '|' ) !== false ) { // sanity check
					$line = MessageCache::singleton()->transform( $line, false, null, $this->getTitle() );
					$line = array_map( 'trim', explode( '|', $line, 2 ) );
					if ( count( $line ) !== 2 ) {
						// Second sanity check, could be hit by people doing
						// funky stuff with parserfuncs... (bug 33321)
						continue;
					}

					$extraAttribs = array();

					$msgLink = $this->msg( $line[0] )->inContentLanguage();
					if ( $msgLink->exists() ) {
						$link = $msgLink->text();
						if ( $link == '-' ) {
							continue;
						}
					} else {
						$link = $line[0];
					}
					$msgText = $this->msg( $line[1] );
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
						if ( $wgExternalLinkTarget) {
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

					$bar[$heading][] = array_merge( array(
						'text' => $text,
						'href' => $href,
						'id' => 'n-' . Sanitizer::escapeId( strtr( $line[1], ' ', '-' ), 'noninitial' ),
						'active' => false
					), $extraAttribs );
				} else {
					continue;
				}
			}
		}

		return $bar;
	}

	/**
	 * Should we load mediawiki.legacy.wikiprintable?  Skins that have their own
	 * print stylesheet should override this and return false.  (This is an
	 * ugly hack to get Monobook to play nicely with OutputPage::headElement().)
	 *
	 * @return bool
	 */
	public function commonPrintStylesheet() {
		return true;
	}

	/**
	 * Gets new talk page messages for the current user.
	 * @return MediaWiki message or if no new talk page messages, nothing
	 */
	function getNewtalks() {
		$out = $this->getOutput();

		$newtalks = $this->getUser()->getNewMessageLinks();
		$ntl = '';

		if ( count( $newtalks ) == 1 && $newtalks[0]['wiki'] === wfWikiID() ) {
			$uTalkTitle = $this->getUser()->getTalkPage();

			if ( !$uTalkTitle->equals( $out->getTitle() ) ) {
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
				$plural = $plural ? 2 : 1;
				// 2 signifies "more than one revision". We don't know how many, and even if we did,
				// the number of revisions or authors is not necessarily the same as the number of
				// "messages".
				$newMessagesLink = Linker::linkKnown(
					$uTalkTitle,
					$this->msg( 'newmessageslinkplural' )->params( $plural )->escaped(),
					array(),
					array( 'redirect' => 'no' )
				);

				$newMessagesDiffLink = Linker::linkKnown(
					$uTalkTitle,
					$this->msg( 'newmessagesdifflinkplural' )->params( $plural )->escaped(),
					array(),
					$lastSeenRev !== null
						? array( 'oldid' => $lastSeenRev->getId(), 'diff' => 'cur' )
						: array( 'diff' => 'cur' )
				);

				if ( $nofAuthors >= 1 && $nofAuthors <= 10 ) {
					$ntl = $this->msg(
						'youhavenewmessagesfromusers',
						$newMessagesLink,
						$newMessagesDiffLink
					)->numParams( $nofAuthors );
				} else {
					// $nofAuthors === 11 signifies "11 or more" ("more than 10")
					$ntl = $this->msg(
						$nofAuthors > 10 ? 'youhavenewmessagesmanyusers' : 'youhavenewmessages',
						$newMessagesLink,
						$newMessagesDiffLink
					);
				}
				$ntl = $ntl->text();
				# Disable Squid cache
				$out->setSquidMaxage( 0 );
			}
		} elseif ( count( $newtalks ) ) {
			// _>" " for BC <= 1.16
			$sep = str_replace( '_', ' ', $this->msg( 'newtalkseparator' )->escaped() );
			$msgs = array();

			foreach ( $newtalks as $newtalk ) {
				$msgs[] = Xml::element(
					'a',
					array( 'href' => $newtalk['link'] ), $newtalk['wiki']
				);
			}
			$parts = implode( $sep, $msgs );
			$ntl = $this->msg( 'youhavenewmessagesmulti' )->rawParams( $parts )->escaped();
			$out->setSquidMaxage( 0 );
		}

		return $ntl;
	}

	/**
	 * Get a cached notice
	 *
	 * @param $name String: message name, or 'default' for $wgSiteNotice
	 * @return String: HTML fragment
	 */
	private function getCachedNotice( $name ) {
		global $wgRenderHashAppend, $parserMemc, $wgContLang;

		wfProfileIn( __METHOD__ );

		$needParse = false;

		if( $name === 'default' ) {
			// special case
			global $wgSiteNotice;
			$notice = $wgSiteNotice;
			if( empty( $notice ) ) {
				wfProfileOut( __METHOD__ );
				return false;
			}
		} else {
			$msg = $this->msg( $name )->inContentLanguage();
			if( $msg->isDisabled() ) {
				wfProfileOut( __METHOD__ );
				return false;
			}
			$notice = $msg->plain();
		}

		// Use the extra hash appender to let eg SSL variants separately cache.
		$key = wfMemcKey( $name . $wgRenderHashAppend );
		$cachedNotice = $parserMemc->get( $key );
		if( is_array( $cachedNotice ) ) {
			if( md5( $notice ) == $cachedNotice['hash'] ) {
				$notice = $cachedNotice['html'];
			} else {
				$needParse = true;
			}
		} else {
			$needParse = true;
		}

		if ( $needParse ) {
			$parsed = $this->getOutput()->parse( $notice );
			$parserMemc->set( $key, array( 'html' => $parsed, 'hash' => md5( $notice ) ), 600 );
			$notice = $parsed;
		}

		$notice = Html::rawElement( 'div', array( 'id' => 'localNotice',
			'lang' => $wgContLang->getHtmlCode(), 'dir' => $wgContLang->getDir() ), $notice );
		wfProfileOut( __METHOD__ );
		return $notice;
	}

	/**
	 * Get a notice based on page's namespace
	 *
	 * @return String: HTML fragment
	 */
	function getNamespaceNotice() {
		wfProfileIn( __METHOD__ );

		$key = 'namespacenotice-' . $this->getTitle()->getNsText();
		$namespaceNotice = $this->getCachedNotice( $key );
		if ( $namespaceNotice && substr( $namespaceNotice, 0, 7 ) != '<p>&lt;' ) {
			$namespaceNotice = '<div id="namespacebanner">' . $namespaceNotice . '</div>';
		} else {
			$namespaceNotice = '';
		}

		wfProfileOut( __METHOD__ );
		return $namespaceNotice;
	}

	/**
	 * Get the site notice
	 *
	 * @return String: HTML fragment
	 */
	function getSiteNotice() {
		wfProfileIn( __METHOD__ );
		$siteNotice = '';

		if ( wfRunHooks( 'SiteNoticeBefore', array( &$siteNotice, $this ) ) ) {
			if ( is_object( $this->getUser() ) && $this->getUser()->isLoggedIn() ) {
				$siteNotice = $this->getCachedNotice( 'sitenotice' );
			} else {
				$anonNotice = $this->getCachedNotice( 'anonnotice' );
				if ( !$anonNotice ) {
					$siteNotice = $this->getCachedNotice( 'sitenotice' );
				} else {
					$siteNotice = $anonNotice;
				}
			}
			if ( !$siteNotice ) {
				$siteNotice = $this->getCachedNotice( 'default' );
			}
		}

		wfRunHooks( 'SiteNoticeAfter', array( &$siteNotice, $this ) );
		wfProfileOut( __METHOD__ );
		return $siteNotice;
	}

	/**
	 * Create a section edit link.  This supersedes editSectionLink() and
	 * editSectionLinkForOther().
	 *
	 * @param $nt      Title  The title being linked to (may not be the same as
	 *   $wgTitle, if the section is included from a template)
	 * @param $section string The designation of the section being pointed to,
	 *   to be included in the link, like "&section=$section"
	 * @param $tooltip string The tooltip to use for the link: will be escaped
	 *   and wrapped in the 'editsectionhint' message
	 * @param $lang    string Language code
	 * @return         string HTML to use for edit link
	 */
	public function doEditSectionLink( Title $nt, $section, $tooltip = null, $lang = false ) {
		// HTML generated here should probably have userlangattributes
		// added to it for LTR text on RTL pages

		$lang = wfGetLangObj( $lang );

		$attribs = array();
		if ( !is_null( $tooltip ) ) {
			# Bug 25462: undo double-escaping.
			$tooltip = Sanitizer::decodeCharReferences( $tooltip );
			$attribs['title'] = wfMessage( 'editsectionhint' )->rawParams( $tooltip )
				->inLanguage( $lang )->text();
		}
		$link = Linker::link( $nt, wfMessage( 'editsection' )->inLanguage( $lang )->text(),
			$attribs,
			array( 'action' => 'edit', 'section' => $section ),
			array( 'noclasses', 'known' )
		);

		# Run the old hook.  This takes up half of the function . . . hopefully
		# we can rid of it someday.
		$attribs = '';
		if ( $tooltip ) {
			$attribs = wfMessage( 'editsectionhint' )->rawParams( $tooltip )
				->inLanguage( $lang )->escaped();
			$attribs = " title=\"$attribs\"";
		}
		$result = null;
		wfRunHooks( 'EditSectionLink', array( &$this, $nt, $section, $attribs, $link, &$result, $lang ) );
		if ( !is_null( $result ) ) {
			# For reverse compatibility, add the brackets *after* the hook is
			# run, and even add them to hook-provided text.  (This is the main
			# reason that the EditSectionLink hook is deprecated in favor of
			# DoEditSectionLink: it can't change the brackets or the span.)
			$result = wfMessage( 'editsection-brackets' )->rawParams( $result )
				->inLanguage( $lang )->escaped();
			return "<span class=\"editsection\">$result</span>";
		}

		# Add the brackets and the span, and *then* run the nice new hook, with
		# clean and non-redundant arguments.
		$result = wfMessage( 'editsection-brackets' )->rawParams( $link )
			->inLanguage( $lang )->escaped();
		$result = "<span class=\"editsection\">$result</span>";

		wfRunHooks( 'DoEditSectionLink', array( $this, $nt, $section, $tooltip, &$result, $lang ) );
		return $result;
	}

	/**
	 * Use PHP's magic __call handler to intercept legacy calls to the linker
	 * for backwards compatibility.
	 *
	 * @param $fname String Name of called method
	 * @param $args Array Arguments to the method
	 * @return mixed
	 */
	function __call( $fname, $args ) {
		$realFunction = array( 'Linker', $fname );
		if ( is_callable( $realFunction ) ) {
			return call_user_func_array( $realFunction, $args );
		} else {
			$className = get_class( $this );
			throw new MWException( "Call to undefined method $className::$fname" );
		}
	}

}
