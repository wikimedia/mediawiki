<?php
/**
 * @defgroup Skins Skins
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 1 );
}

/**
 * The main skin class that provide methods and properties for all other skins.
 * This base class is also the "Standard" skin.
 *
 * See docs/skin.txt for more information.
 *
 * @ingroup Skins
 */
abstract class Skin {
	/**#@+
	 * @private
	 */
	var $mWatchLinkNum = 0; // Appended to end of watch link id's
	/**#@-*/
	protected $mRevisionId; // The revision ID we're looking at, null if not applicable.
	protected $skinname = 'standard';
	protected $mRelevantTitle = null;
	protected $mRelevantUser = null;

	/**
	 * Fetch the set of available skins.
	 * @return array of strings
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

		if ( $key == '' ) {
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
		} else if ( isset( $skinNames[$wgDefaultSkin] ) ) {
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
				// Preload base classes to work around APC/PHP5 bug
				$deps = "{$wgStyleDirectory}/{$skinName}.deps.php";
				if ( file_exists( $deps ) ) {
					include_once( $deps );
				}
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
		$skin = new $className;
		return $skin;
	}

	/** @return string path to the skin stylesheet */
	function getStylesheet() {
		return 'common/wikistandard.css';
	}

	/** @return string skin name */
	public function getSkinName() {
		return $this->skinname;
	}

	function initPage( OutputPage $out ) {
		wfProfileIn( __METHOD__ );

		$this->mRevisionId = $out->mRevisionId;
		$this->preloadExistence();
		$this->setMembers();

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Preload the existence of three commonly-requested pages in a single query
	 */
	function preloadExistence() {
		$user = $this->getContext()->getUser();
		
		// User/talk link
		$titles = array( $user->getUserPage(), $user->getTalkPage() );

		// Other tab link
		if ( $this->getTitle()->getNamespace() == NS_SPECIAL ) {
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
	 * Set some local variables
	 */
	protected function setMembers() {
		$this->userpage = $this->getContext()->getUser()->getUserPage()->getPrefixedText();
		$this->usercss = false;
	}

	/**
	 * Whether the revision displayed is the latest revision of the page
	 *
	 * @return Boolean
	 */
	public function isRevisionCurrent() {
		return $this->mRevisionId == 0 || $this->mRevisionId == $this->getTitle()->getLatestRevID();
	}

	/**
	 * Set the RequestContext used in this instance
	 *
	 * @param RequestContext $context
	 */
	public function setContext( RequestContext $context ) {
		$this->mContext = $context;
	}

	/**
	 * Get the RequestContext used in this instance
	 *
	 * @return RequestContext
	 */
	public function getContext() {
		if ( !isset($this->mContext) ) {
			wfDebug( __METHOD__ . " called and \$mContext is null. Using RequestContext::getMain(); for sanity\n" );
			$this->mContext = RequestContext::getMain();
		}
		return $this->mContext;
	}

	/** Get the title
	 *
	 * @return Title
	 */
	public function getTitle() {
		return $this->getContext()->getTitle();
	}

	/** Get the user
	 *
	 * @return User
	 */
	public function getUser() {
		return $this->getContext()->getUser();
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
				$user = User::newFromName( $rootUser );
				if ( $user->isLoggedIn() ) {
					$this->mRelevantUser = $user;
				}
			}
			return $this->mRelevantUser;
		}
		return null;
	}

	/**
	 * Outputs the HTML generated by other functions.
	 * @param $out Object: instance of OutputPage
	 */
	abstract function outputPage( OutputPage $out );

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
	 * To make it harder for someone to slip a user a fake
	 * user-JavaScript or user-CSS preview, a random token
	 * is associated with the login session. If it's not
	 * passed back with the preview request, we won't render
	 * the code.
	 *
	 * @param $action String: 'edit', 'submit' etc.
	 * @return bool
	 */
	public function userCanPreview( $action ) {
		if ( $action != 'submit' ) {
			return false;
		}
		if ( !$this->getContext()->getRequest()->wasPosted() ) {
			return false;
		}
		if ( !$this->getTitle()->userCanEditCssSubpage() ) {
			return false;
		}
		if ( !$this->getTitle()->userCanEditJsSubpage() ) {
			return false;
		}

		return $this->getContext()->getUser()->matchEditToken(
			$this->getContext()->getRequest()->getVal( 'wpEditToken' ) );
	}

	/**
	 * Generated JavaScript action=raw&gen=js
	 * This returns MediaWiki:Common.js and MediaWiki:[Skinname].js concate-
	 * nated together.  For some bizarre reason, it does *not* return any
	 * custom user JS from subpages.  Huh?
	 *
	 * There's absolutely no reason to have separate Monobook/Common JSes.
	 * Any JS that cares can just check the skin variable generated at the
	 * top.  For now Monobook.js will be maintained, but it should be consi-
	 * dered deprecated.
	 *
	 * @param $skinName String: If set, overrides the skin name
	 * @return string
	 */
	public function generateUserJs( $skinName = null ) {
		
		// Stub - see ResourceLoaderSiteModule, CologneBlue, Simple and Standard skins override this
		
		return '';
	}

	/**
	 * Generate user stylesheet for action=raw&gen=css
	 */
	public function generateUserStylesheet() {
		
		// Stub - see ResourceLoaderUserModule, CologneBlue, Simple and Standard skins override this
		
		return '';
	}

	/**
	 * Split for easier subclassing in SkinSimple, SkinStandard and SkinCologneBlue
	 * Anything in here won't be generated if $wgAllowUserCssPrefs is false.
	 */
	protected function reallyGenerateUserStylesheet() {
		
		// Stub - see  ResourceLoaderUserModule, CologneBlue, Simple and Standard skins override this
		
		return '';
	}

	/**
	 * @private
	 */
	function setupUserCss( OutputPage $out ) {
		global $wgUseSiteCss, $wgAllowUserCss, $wgAllowUserCssPrefs;

		wfProfileIn( __METHOD__ );

		$this->setupSkinUserCss( $out );
		// Add any extension CSS
		foreach ( $out->getExtStyle() as $url ) {
			$out->addStyle( $url );
		}

		// Per-site custom styles
		if ( $wgUseSiteCss ) {
			$out->addModuleStyles( array( 'site', 'noscript' ) );
			if( $this->getContext()->getUser()->isLoggedIn() ){
				$out->addModuleStyles( 'user.groups' );
			}
		}

		// Per-user custom styles
		if ( $wgAllowUserCss ) {
			if ( $this->getTitle()->isCssSubpage() && $this->userCanPreview( $this->getContext()->getRequest()->getVal( 'action' ) ) ) {
				// @FIXME: properly escape the cdata!
				$out->addInlineStyle( $this->getContext()->getRequest()->getText( 'wpTextbox1' ) );
			} else {
				$out->addModuleStyles( 'user' );
			}
		}

		// Per-user preference styles
		if ( $wgAllowUserCssPrefs ) {
			$out->addModuleStyles( 'user.options' );
		}

		wfProfileOut( __METHOD__ );
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
	 * @param $out OutputPage
	 * @delete
	 */
	abstract function setupSkinUserCss( OutputPage $out );

	function getPageClasses( $title ) {
		$numeric = 'ns-' . $title->getNamespace();

		if ( $title->getNamespace() == NS_SPECIAL ) {
			$type = 'ns-special';
			// bug 23315: provide a class based on the canonical special page name without subpages
			list( $canonicalName ) = SpecialPage::resolveAliasWithSubpage( $title->getDBkey() );
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
	 * <body> tag, skins can override it if they have a need to add in any
	 * body attributes or classes of their own.
	 */
	function addToBodyAttributes( $out, &$bodyAttrs ) {
		// does nothing by default
	}

	/**
	 * URL to the logo
	 */
	function getLogo() {
		global $wgLogo;
		return $wgLogo;
	}

	/**
	 * The format without an explicit $out argument is deprecated
	 */
	function getCategoryLinks( OutputPage $out=null ) {
		global $wgUseCategoryBrowser, $wgContLang;

		if ( count( $out->mCategoryLinks ) == 0 ) {
			return '';
		}

		# Separator
		$sep = wfMsgExt( 'catseparator', array( 'parsemag', 'escapenoentities' ) );

		// Use Unicode bidi embedding override characters,
		// to make sure links don't smash each other up in ugly ways.
		$dir = $wgContLang->getDir();
		$embed = "<span dir='$dir'>";
		$pop = '</span>';

		$allCats = $out->getCategoryLinks();
		$s = '';
		$colon = wfMsgExt( 'colon-separator', 'escapenoentities' );

		if ( !empty( $allCats['normal'] ) ) {
			$t = $embed . implode( "{$pop} {$sep} {$embed}" , $allCats['normal'] ) . $pop;

			$msg = wfMsgExt( 'pagecategories', array( 'parsemag', 'escapenoentities' ), count( $allCats['normal'] ) );
			$s .= '<div id="mw-normal-catlinks">' .
				$this->link( Title::newFromText( wfMsgForContent( 'pagecategorieslink' ) ), $msg )
				. $colon . $t . '</div>';
		}

		# Hidden categories
		if ( isset( $allCats['hidden'] ) ) {
			if ( $this->getContext()->getUser()->getBoolOption( 'showhiddencats' ) ) {
				$class = 'mw-hidden-cats-user-shown';
			} elseif ( $this->getTitle()->getNamespace() == NS_CATEGORY ) {
				$class = 'mw-hidden-cats-ns-shown';
			} else {
				$class = 'mw-hidden-cats-hidden';
			}

			$s .= "<div id=\"mw-hidden-catlinks\" class=\"$class\">" .
				wfMsgExt( 'hidden-categories', array( 'parsemag', 'escapenoentities' ), count( $allCats['hidden'] ) ) .
				$colon . $embed . implode( "$pop $sep $embed", $allCats['hidden'] ) . $pop .
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
			$tempout = explode( "\n", $this->drawCategoryBrowser( $parenttree, $this ) );
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
	 * @param &skin Object: skin passed by reference
	 * @return String separated by &gt;, terminate with "\n"
	 */
	function drawCategoryBrowser( $tree, &$skin ) {
		$return = '';

		foreach ( $tree as $element => $parent ) {
			if ( empty( $parent ) ) {
				# element start a new list
				$return .= "\n";
			} else {
				# grab the others elements
				$return .= $this->drawCategoryBrowser( $parent, $skin ) . ' &gt; ';
			}

			# add our current element to the list
			$eltitle = Title::newFromText( $element );
			$return .=  $skin->link( $eltitle, $eltitle->getText() );
		}

		return $return;
	}

	/**
	 * The ->getCategories() form is deprecated, please instead use
	 * the ->getCategories( $out ) form with whatout OutputPage is on hand
	 */
	function getCategories( OutputPage $out=null ) {

		$catlinks = $this->getCategoryLinks( $out );

		$classes = 'catlinks';

		// Check what we're showing
		$allCats = $out->getCategoryLinks();
		$showHidden = $this->getContext()->getUser()->getBoolOption( 'showhiddencats' ) ||
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
	 * Returns an empty string by default, if not changed by any hook function.
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
	protected function generateDebugHTML( OutputPage $out ) {
		global $wgShowDebug;

		if ( $wgShowDebug ) {
			$listInternals = $this->formatDebugHTML( $out->mDebugtext );
			return "\n<hr />\n<strong>Debug data:</strong><ul id=\"mw-debug-html\">" .
				$listInternals . "</ul>\n";
		}

		return '';
	}

	private function formatDebugHTML( $debugText ) {
		global $wgDebugTimestamps;

		$lines = explode( "\n", $debugText );
		$curIdent = 0;
		$ret = '<li>';

		foreach ( $lines as $line ) {
			$pre = '';
			if ( $wgDebugTimestamps ) {
				$matches = array();
				if ( preg_match( '/^(\d+\.\d+\s{2})/', $line, $matches ) ) {
					$pre = $matches[1];
					$line = substr( $line, strlen( $pre ) );
				}
			}
			$display = ltrim( $line );
			$ident = strlen( $line ) - strlen( $display );
			$diff = $ident - $curIdent;

			$display = $pre . $display;
			if ( $display == '' ) {
				$display = "\xc2\xa0";
			}

			if ( !$ident && $diff < 0 && substr( $display, 0, 9 ) != 'Entering ' && substr( $display, 0, 8 ) != 'Exiting ' ) {
				$ident = $curIdent;
				$diff = 0;
				$display = '<span style="background:yellow;">' . htmlspecialchars( $display ) . '</span>';
			} else {
				$display = htmlspecialchars( $display );
			}

			if ( $diff < 0 ) {
				$ret .= str_repeat( "</li></ul>\n", -$diff ) . "</li><li>\n";
			} elseif ( $diff == 0 ) {
				$ret .= "</li><li>\n";
			} else {
				$ret .= str_repeat( "<ul><li>\n", $diff );
			}
			$ret .= "<tt>$display</tt>\n";

			$curIdent = $ident;
		}

		$ret .= str_repeat( '</li></ul>', $curIdent ) . '</li>';

		return $ret;
	}

	/**
	 * This gets called shortly before the </body> tag.
	 * @param $out OutputPage object
	 * @return String HTML-wrapped JS code to be put before </body>
	 */
	function bottomScripts( $out ) {
		$bottomScriptText = "\n" . $out->getHeadScripts( $this );
		wfRunHooks( 'SkinAfterBottomScripts', array( $this, &$bottomScriptText ) );

		return $bottomScriptText;
	}

	/** @return string Retrievied from HTML text */
	function printSource() {
		$url = htmlspecialchars( $this->getTitle()->getFullURL() );
		return wfMsg( 'retrievedfrom', '<a href="' . $url . '">' . $url . '</a>' );
	}

	function getUndeleteLink() {
		$action = $this->getContext()->getRequest()->getVal( 'action', 'view' );

		if ( $this->getContext()->getUser()->isAllowed( 'deletedhistory' ) &&
			( $this->getTitle()->getArticleId() == 0 || $action == 'history' ) ) {
			$n = $this->getTitle()->isDeleted();

			if ( $n ) {
				if ( $this->getContext()->getUser()->isAllowed( 'undelete' ) ) {
					$msg = 'thisisdeleted';
				} else {
					$msg = 'viewdeleted';
				}

				return wfMsg(
					$msg,
					$this->link(
						SpecialPage::getTitleFor( 'Undelete', $this->getTitle()->getPrefixedDBkey() ),
						wfMsgExt( 'restorelink', array( 'parsemag', 'escape' ), $this->getContext()->getLang()->formatNum( $n ) ),
						array(),
						array(),
						array( 'known', 'noclasses' )
					)
				);
			}
		}

		return '';
	}

	/**
	 * The format without an explicit $out argument is deprecated
	 */
	function subPageSubtitle( OutputPage $out=null ) {
		$out = $this->getContext()->getOutput();
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

					if ( is_object( $linkObj ) && $linkObj->exists() ) {
						$getlink = $this->link(
							$linkObj,
							htmlspecialchars( $display ),
							array(),
							array(),
							array( 'known', 'noclasses' )
						);

						$c++;

						if ( $c > 1 ) {
							$subpages .= wfMsgExt( 'pipe-separator', 'escapenoentities' );
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
	 */
	function showIPinHeader() {
		global $wgShowIPinHeader;
		return $wgShowIPinHeader && session_id() != '';
	}

	function getSearchLink() {
		$searchPage = SpecialPage::getTitleFor( 'Search' );
		return $searchPage->getLocalURL();
	}

	function escapeSearchLink() {
		return htmlspecialchars( $this->getSearchLink() );
	}

	function getCopyright( $type = 'detect' ) {
		global $wgRightsPage, $wgRightsUrl, $wgRightsText;

		if ( $type == 'detect' ) {
			$diff = $this->getContext()->getRequest()->getVal( 'diff' );

			if ( is_null( $diff ) && !$this->isRevisionCurrent() && wfMsgForContent( 'history_copyright' ) !== '-' ) {
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

		$out = '';

		if ( $wgRightsPage ) {
			$title = Title::newFromText( $wgRightsPage );
			$link = $this->linkKnown( $title, $wgRightsText );
		} elseif ( $wgRightsUrl ) {
			$link = $this->makeExternalLink( $wgRightsUrl, $wgRightsText );
		} elseif ( $wgRightsText ) {
			$link = $wgRightsText;
		} else {
			# Give up now
			return $out;
		}

		// Allow for site and per-namespace customization of copyright notice.
		$forContent = true;

		wfRunHooks( 'SkinCopyrightFooter', array( $this->getTitle(), $type, &$msg, &$link, &$forContent ) );

		if ( $forContent ) {
			$out .= wfMsgForContent( $msg, $link );
		} else {
			$out .= wfMsg( $msg, $link );
		}

		return $out;
	}

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
		$text = '<a href="http://www.mediawiki.org/"><img src="' . $url . '" height="31" width="88" alt="Powered by MediaWiki" /></a>';
		wfRunHooks( 'SkinGetPoweredBy', array( &$text, $this ) );	
		return $text;
	}

	/**
	 * Get the timestamp of the latest revision, formatted in user language
	 *
	 * @param $article Article object. Used if we're working with the current revision
	 * @return String
	 */
	protected function lastModified( $article ) {
		if ( !$this->isRevisionCurrent() ) {
			$timestamp = Revision::getTimestampFromId( $this->getTitle(), $this->mRevisionId );
		} else {
			$timestamp = $article->getTimestamp();
		}

		if ( $timestamp ) {
			$d = $this->getContext()->getLang()->date( $timestamp, true );
			$t = $this->getContext()->getLang()->time( $timestamp, true );
			$s = ' ' . wfMsg( 'lastmodifiedat', $d, $t );
		} else {
			$s = '';
		}

		if ( wfGetLB()->getLaggedSlaveMode() ) {
			$s .= ' <strong>' . wfMsg( 'laggedslavemode' ) . '</strong>';
		}

		return $s;
	}

	function logoText( $align = '' ) {
		if ( $align != '' ) {
			$a = " align='{$align}'";
		} else {
			$a = '';
		}

		$mp = wfMsg( 'mainpage' );
		$mptitle = Title::newMainPage();
		$url = ( is_object( $mptitle ) ? $mptitle->escapeLocalURL() : '' );

		$logourl = $this->getLogo();
		$s = "<a href='{$url}'><img{$a} src='{$logourl}' alt='[{$mp}]' /></a>";

		return $s;
	}

	/**
	 * Renders a $wgFooterIcons icon acording to the method's arguments
	 * @param $icon Array: The icon to build the html for, see $wgFooterIcons for the format of this array
	 * @param $withImage Boolean: Whether to use the icon's image or output a text-only footericon
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
		$s = $this->link(
			Title::newMainPage(),
			wfMsg( 'mainpage' ),
			array(),
			array(),
			array( 'known', 'noclasses' )
		);

		return $s;
	}

	public function footerLink( $desc, $page ) {
		// if the link description has been set to "-" in the default language,
		if ( wfMsgForContent( $desc )  == '-' ) {
			// then it is disabled, for all languages.
			return '';
		} else {
			// Otherwise, we display the link for the user, described in their
			// language (which may or may not be the same as the default language),
			// but we make the link target be the one site-wide page.
			$title = Title::newFromText( wfMsgForContent( $page ) );

			return $this->linkKnown(
				$title,
				wfMsgExt( $desc, array( 'parsemag', 'escapenoentities' ) )
			);
		}
	}

	/**
	 * Gets the link to the wiki's privacy policy page.
	 */
	function privacyLink() {
		return $this->footerLink( 'privacy', 'privacypage' );
	}

	/**
	 * Gets the link to the wiki's about page.
	 */
	function aboutLink() {
		return $this->footerLink( 'aboutsite', 'aboutpage' );
	}

	/**
	 * Gets the link to the wiki's general disclaimers page.
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
			$options['oldid'] = intval( $this->mRevisionId );
		}

		return $options;
	}

	function showEmailUser( $id ) {
		$targetUser = User::newFromId( $id );
		return $this->getContext()->getUser()->canSendEmail() && # the sending user must have a confirmed email address
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
	static function makeMainPageUrl( $urlaction = '' ) {
		$title = Title::newMainPage();
		self::checkTitle( $title, '' );

		return $title->getLocalURL( $urlaction );
	}

	static function makeSpecialUrl( $name, $urlaction = '' ) {
		$title = SpecialPage::getTitleFor( $name );
		return $title->getLocalURL( $urlaction );
	}

	static function makeSpecialUrlSubpage( $name, $subpage, $urlaction = '' ) {
		$title = SpecialPage::getSafeTitleFor( $name, $subpage );
		return $title->getLocalURL( $urlaction );
	}

	static function makeI18nUrl( $name, $urlaction = '' ) {
		$title = Title::newFromText( wfMsgForContent( $name ) );
		self::checkTitle( $title, $name );
		return $title->getLocalURL( $urlaction );
	}

	static function makeUrl( $name, $urlaction = '' ) {
		$title = Title::newFromText( $name );
		self::checkTitle( $title, $name );

		return $title->getLocalURL( $urlaction );
	}

	/**
	 * If url string starts with http, consider as external URL, else
	 * internal
	 */
	static function makeInternalOrExternalUrl( $name ) {
		if ( preg_match( '/^(?:' . wfUrlProtocols() . ')/', $name ) ) {
			return $name;
		} else {
			return self::makeUrl( $name );
		}
	}

	# this can be passed the NS number as defined in Language.php
	static function makeNSUrl( $name, $urlaction = '', $namespace = NS_MAIN ) {
		$title = Title::makeTitleSafe( $namespace, $name );
		self::checkTitle( $title, $name );

		return $title->getLocalURL( $urlaction );
	}

	/* these return an array with the 'href' and boolean 'exists' */
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
	 */
	static function makeKnownUrlDetails( $name, $urlaction = '' ) {
		$title = Title::newFromText( $name );
		self::checkTitle( $title, $name );

		return array(
			'href' => $title->getLocalURL( $urlaction ),
			'exists' => true
		);
	}

	# make sure we have some title to operate on
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
		global $parserMemc, $wgEnableSidebarCache, $wgSidebarCacheExpiry;
		wfProfileIn( __METHOD__ );

		$key = wfMemcKey( 'sidebar', $this->getContext()->getLang()->getCode() );

		if ( $wgEnableSidebarCache ) {
			$cachedsidebar = $parserMemc->get( $key );
			if ( $cachedsidebar ) {
				wfProfileOut( __METHOD__ );
				return $cachedsidebar;
			}
		}

		$bar = array();
		$this->addToSidebar( $bar, 'sidebar' );

		wfRunHooks( 'SkinBuildSidebar', array( $this, &$bar ) );
		if ( $wgEnableSidebarCache ) {
			$parserMemc->set( $key, $bar, $wgSidebarCacheExpiry );
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
	 * @param &$bar array
	 * @param $message String
	 */
	function addToSidebar( &$bar, $message ) {
		$this->addToSidebarPlain( $bar, wfMsgForContentNoTrans( $message ) );
	}

	/**
	 * Add content from plain text
	 * @since 1.17
	 * @param &$bar array
	 * @param $text string
	 */
	function addToSidebarPlain( &$bar, $text ) {
		$lines = explode( "\n", $text );
		$wikiBar = array(); # We need to handle the wikitext on a different variable, to avoid trying to do an array operation on text, which would be a fatal error.

		$heading = '';

		foreach ( $lines as $line ) {
			if ( strpos( $line, '*' ) !== 0 ) {
				continue;
			}

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
					$link = wfMsgForContent( $line[0] );

					if ( $link == '-' ) {
						continue;
					}

					$text = wfMsgExt( $line[1], 'parsemag' );

					if ( wfEmptyMsg( $line[1] ) ) {
						$text = $line[1];
					}

					if ( wfEmptyMsg( $line[0] ) ) {
						$link = $line[0];
					}

					if ( preg_match( '/^(?:' . wfUrlProtocols() . ')/', $link ) ) {
						$href = $link;
					} else {
						$title = Title::newFromText( $link );

						if ( $title ) {
							$title = $title->fixSpecialName();
							$href = $title->getLocalURL();
						} else {
							$href = 'INVALID-TITLE';
						}
					}

					$bar[$heading][] = array(
						'text' => $text,
						'href' => $href,
						'id' => 'n-' . strtr( $line[1], ' ', '-' ),
						'active' => false
					);
				} else if ( ( substr( $line, 0, 2 ) == '{{' ) && ( substr( $line, -2 ) == '}}' ) ) {
					global $wgParser;

					$line = substr( $line, 2, strlen( $line ) - 4 );

					$options = new ParserOptions();
					$options->setEditSection( false );
					$options->setInterfaceMessage( true );
					$wikiBar[$heading] = $wgParser->parse( wfMsgForContentNoTrans( $line ) , $this->getTitle(), $options )->getText();
				} else {
					continue;
				}
			}
		}

		if ( count( $wikiBar ) > 0 ) {
			$bar = array_merge( $bar, $wikiBar );
		}

		return $bar;
	}

	/**
	 * Should we include common/wikiprintable.css?  Skins that have their own
	 * print stylesheet should override this and return false.  (This is an
	 * ugly hack to get Monobook to play nicely with
	 * OutputPage::headElement().)
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
		$out = $this->getContext()->getOutput();

		$newtalks = $this->getContext()->getUser()->getNewMessageLinks();
		$ntl = '';

		if ( count( $newtalks ) == 1 && $newtalks[0]['wiki'] === wfWikiID() ) {
			$userTitle = $this->getUser()->getUserPage();
			$userTalkTitle = $userTitle->getTalkPage();

			if ( !$userTalkTitle->equals( $out->getTitle() ) ) {
				$newMessagesLink = $this->link(
					$userTalkTitle,
					wfMsgHtml( 'newmessageslink' ),
					array(),
					array( 'redirect' => 'no' ),
					array( 'known', 'noclasses' )
				);

				$newMessagesDiffLink = $this->link(
					$userTalkTitle,
					wfMsgHtml( 'newmessagesdifflink' ),
					array(),
					array( 'diff' => 'cur' ),
					array( 'known', 'noclasses' )
				);

				$ntl = wfMsg(
					'youhavenewmessages',
					$newMessagesLink,
					$newMessagesDiffLink
				);
				# Disable Squid cache
				$out->setSquidMaxage( 0 );
			}
		} elseif ( count( $newtalks ) ) {
			// _>" " for BC <= 1.16
			$sep = str_replace( '_', ' ', wfMsgHtml( 'newtalkseparator' ) );
			$msgs = array();

			foreach ( $newtalks as $newtalk ) {
				$msgs[] = Xml::element(
					'a',
					array( 'href' => $newtalk['link'] ), $newtalk['wiki']
				);
			}
			$parts = implode( $sep, $msgs );
			$ntl = wfMsgHtml( 'youhavenewmessagesmulti', $parts );
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
		global $wgRenderHashAppend, $parserMemc;

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
			$msg = wfMessage( $name )->inContentLanguage();
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
			$parsed = $this->getContext()->getOutput()->parse( $notice );
			$parserMemc->set( $key, array( 'html' => $parsed, 'hash' => md5( $notice ) ), 600 );
			$notice = $parsed;
		}

		$notice = '<div id="localNotice">' .$notice . '</div>';
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
			if ( is_object( $this->getContext()->getUser() ) && $this->getContext()->getUser()->isLoggedIn() ) {
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
		$attribs = array();
		if ( !is_null( $tooltip ) ) {
			# Bug 25462: undo double-escaping.
			$tooltip = Sanitizer::decodeCharReferences( $tooltip );
			$attribs['title'] = wfMsgReal( 'editsectionhint', array( $tooltip ), true, $lang );
		}
		$link = Linker::link( $nt, wfMsgExt( 'editsection', array( 'language' => $lang ) ),
			$attribs,
			array( 'action' => 'edit', 'section' => $section ),
			array( 'noclasses', 'known' )
		);

		# Run the old hook.  This takes up half of the function . . . hopefully
		# we can rid of it someday.
		$attribs = '';
		if ( $tooltip ) {
			$attribs = htmlspecialchars( wfMsgReal( 'editsectionhint', array( $tooltip ), true, $lang ) );
			$attribs = " title=\"$attribs\"";
		}
		$result = null;
		wfRunHooks( 'EditSectionLink', array( &$this, $nt, $section, $attribs, $link, &$result, $lang ) );
		if ( !is_null( $result ) ) {
			# For reverse compatibility, add the brackets *after* the hook is
			# run, and even add them to hook-provided text.  (This is the main
			# reason that the EditSectionLink hook is deprecated in favor of
			# DoEditSectionLink: it can't change the brackets or the span.)
			$result = wfMsgExt( 'editsection-brackets', array( 'escape', 'replaceafter', 'language' => $lang ), $result );
			return "<span class=\"editsection\">$result</span>";
		}

		# Add the brackets and the span, and *then* run the nice new hook, with
		# clean and non-redundant arguments.
		$result = wfMsgExt( 'editsection-brackets', array( 'escape', 'replaceafter', 'language' => $lang ), $link );
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
