<?php
/**
 * @defgroup Skins Skins
 */

if ( ! defined( 'MEDIAWIKI' ) )
	die( 1 );

/**
 * The main skin class that provide methods and properties for all other skins.
 * This base class is also the "Standard" skin.
 *
 * See docs/skin.txt for more information.
 *
 * @ingroup Skins
 */
class Skin extends Linker {
	/**#@+
	 * @private
	 */
	var $mWatchLinkNum = 0; // Appended to end of watch link id's
	// How many search boxes have we made?  Avoid duplicate id's.
	protected $searchboxes = '';
	/**#@-*/
	protected $mRevisionId; // The revision ID we're looking at, null if not applicable.
	protected $skinname = 'standard' ;

	/** Constructor, call parent constructor */
	function Skin() { parent::__construct(); }

	/**
	 * Fetch the set of available skins.
	 * @return array of strings
	 * @static
	 */
	static function getSkinNames() {
		global $wgValidSkinNames;
		static $skinsInitialised = false;
		if ( !$skinsInitialised ) {
			# Get a list of available skins
			# Build using the regular expression '^(.*).php$'
			# Array keys are all lower case, array value keep the case used by filename
			#
			wfProfileIn( __METHOD__ . '-init' );
			global $wgStyleDirectory;
			$skinDir = dir( $wgStyleDirectory );

			# while code from www.php.net
			while (false !== ($file = $skinDir->read())) {
				// Skip non-PHP files, hidden files, and '.dep' includes
				$matches = array();
				if(preg_match('/^([^.]*)\.php$/',$file, $matches)) {
					$aSkin = $matches[1];
					$wgValidSkinNames[strtolower($aSkin)] = $aSkin;
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
	 * @param string $key
	 * @return string
	 * @static
	 */
	static function normalizeKey( $key ) {
		global $wgDefaultSkin;
		$skinNames = Skin::getSkinNames();

		if( $key == '' ) {
			// Don't return the default immediately;
			// in a misconfiguration we need to fall back.
			$key = $wgDefaultSkin;
		}

		if( isset( $skinNames[$key] ) ) {
			return $key;
		}

		// Older versions of the software used a numeric setting
		// in the user preferences.
		$fallback = array(
			0 => $wgDefaultSkin,
			1 => 'nostalgia',
			2 => 'cologneblue' );

		if( isset( $fallback[$key] ) ){
			$key = $fallback[$key];
		}

		if( isset( $skinNames[$key] ) ) {
			return $key;
		} else {
			return 'monobook';
		}
	}

	/**
	 * Factory method for loading a skin of a given type
	 * @param string $key 'monobook', 'standard', etc
	 * @return Skin
	 * @static
	 */
	static function &newFromKey( $key ) {
		global $wgStyleDirectory;

		$key = Skin::normalizeKey( $key );

		$skinNames = Skin::getSkinNames();
		$skinName = $skinNames[$key];
		$className = 'Skin'.ucfirst($key);

		# Grab the skin class and initialise it.
		if ( !class_exists( $className ) ) {
			// Preload base classes to work around APC/PHP5 bug
			$deps = "{$wgStyleDirectory}/{$skinName}.deps.php";
			if( file_exists( $deps ) ) include_once( $deps );
			require_once( "{$wgStyleDirectory}/{$skinName}.php" );

			# Check if we got if not failback to default skin
			if( !class_exists( $className ) ) {
				# DO NOT die if the class isn't found. This breaks maintenance
				# scripts and can cause a user account to be unrecoverable
				# except by SQL manipulation if a previously valid skin name
				# is no longer valid.
				wfDebug( "Skin class does not exist: $className\n" );
				$className = 'SkinMonobook';
				require_once( "{$wgStyleDirectory}/MonoBook.php" );
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

	function qbSetting() {
		global $wgOut, $wgUser;

		if ( $wgOut->isQuickbarSuppressed() ) { return 0; }
		$q = $wgUser->getOption( 'quickbar', 0 );
		return $q;
	}

	function initPage( OutputPage $out ) {
		global $wgFavicon, $wgAppleTouchIcon;

		wfProfileIn( __METHOD__ );

		# Generally the order of the favicon and apple-touch-icon links
		# should not matter, but Konqueror (3.5.9 at least) incorrectly
		# uses whichever one appears later in the HTML source.  Make sure
		# apple-touch-icon is specified first to avoid this.
		if( false !== $wgAppleTouchIcon ) {
			$out->addLink( array( 'rel' => 'apple-touch-icon', 'href' => $wgAppleTouchIcon ) );
		}

		if( false !== $wgFavicon ) {
			$out->addLink( array( 'rel' => 'shortcut icon', 'href' => $wgFavicon ) );
		}

		# OpenSearch description link
		$out->addLink( array(
			'rel' => 'search',
			'type' => 'application/opensearchdescription+xml',
			'href' => wfScript( 'opensearch_desc' ),
			'title' => wfMsgForContent( 'opensearch-desc' ),
		));

		$this->addMetadataLinks($out);

		$this->mRevisionId = $out->mRevisionId;

		$this->preloadExistence();

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Preload the existence of three commonly-requested pages in a single query
	 */
	function preloadExistence() {
		global $wgUser, $wgTitle;

		// User/talk link
		$titles = array( $wgUser->getUserPage(), $wgUser->getTalkPage() );

		// Other tab link
		if ( $wgTitle->getNamespace() == NS_SPECIAL ) {
			// nothing
		} elseif ( $wgTitle->isTalkPage() ) {
			$titles[] = $wgTitle->getSubjectPage();
		} else {
			$titles[] = $wgTitle->getTalkPage();
		}

		$lb = new LinkBatch( $titles );
		$lb->execute();
	}

	function addMetadataLinks( OutputPage $out ) {
		global $wgTitle, $wgEnableDublinCoreRdf, $wgEnableCreativeCommonsRdf;
		global $wgRightsPage, $wgRightsUrl;

		if( $out->isArticleRelated() ) {
			# note: buggy CC software only reads first "meta" link
			if( $wgEnableCreativeCommonsRdf ) {
				$out->addMetadataLink( array(
					'title' => 'Creative Commons',
					'type' => 'application/rdf+xml',
					'href' => $wgTitle->getLocalURL( 'action=creativecommons') ) );
			}
			if( $wgEnableDublinCoreRdf ) {
				$out->addMetadataLink( array(
					'title' => 'Dublin Core',
					'type' => 'application/rdf+xml',
					'href' => $wgTitle->getLocalURL( 'action=dublincore' ) ) );
			}
		}
		$copyright = '';
		if( $wgRightsPage ) {
			$copy = Title::newFromText( $wgRightsPage );
			if( $copy ) {
				$copyright = $copy->getLocalURL();
			}
		}
		if( !$copyright && $wgRightsUrl ) {
			$copyright = $wgRightsUrl;
		}
		if( $copyright ) {
			$out->addLink( array(
				'rel' => 'copyright',
				'href' => $copyright ) );
		}
	}

	function setMembers(){
		global $wgTitle, $wgUser;
		$this->mTitle = $wgTitle;
		$this->mUser = $wgUser;
		$this->userpage = $wgUser->getUserPage()->getPrefixedText();
		$this->usercss = false;
	}

	function outputPage( OutputPage $out ) {
		global $wgDebugComments;
		wfProfileIn( __METHOD__ );

		$this->setMembers();
		$this->initPage( $out );

		// See self::afterContentHook() for documentation
		$afterContent = $this->afterContentHook();

		$out->out( $out->headElement( $this ) );

		$out->out( "\n<body" );
		$ops = $this->getBodyOptions();
		foreach ( $ops as $name => $val ) {
			$out->out( " $name='$val'" );
		}
		$out->out( ">\n" );
		if ( $wgDebugComments ) {
			$out->out( "<!-- Wiki debugging output:\n" .
			  $out->mDebugtext . "-->\n" );
		}

		$out->out( $this->beforeContent() );

		$out->out( $out->mBodytext . "\n" );

		$out->out( $this->afterContent() );
		
		$out->out( $afterContent );

		$out->out( $this->bottomScripts() );

		$out->out( wfReportTime() );

		$out->out( "\n</body></html>" );
		wfProfileOut( __METHOD__ );
	}

	static function makeVariablesScript( $data ) {
		global $wgJsMimeType;

		$r = array( "<script type= \"$wgJsMimeType\">/*<![CDATA[*/" );
		foreach ( $data as $name => $value ) {
			$encValue = Xml::encodeJsVar( $value );
			$r[] = "var $name = $encValue;";
		}
		$r[] = "/*]]>*/</script>\n";

		return implode( "\n\t\t", $r );
	}

	/**
	 * Make a <script> tag containing global variables
	 * @param array $data Associative array containing one element:
	 *     skinname => the skin name
	 * The odd calling convention is for backwards compatibility
	 */
	static function makeGlobalVariablesScript( $data ) {
		global $wgScript, $wgStylePath, $wgUser;
		global $wgArticlePath, $wgScriptPath, $wgServer, $wgContLang, $wgLang;
		global $wgTitle, $wgCanonicalNamespaceNames, $wgOut, $wgArticle;
		global $wgBreakFrames, $wgRequest, $wgVariantArticlePath, $wgActionPaths;
		global $wgUseAjax, $wgAjaxWatch;
		global $wgVersion, $wgEnableAPI, $wgEnableWriteAPI;
		global $wgRestrictionTypes, $wgLivePreview;
		global $wgMWSuggestTemplate, $wgDBname, $wgEnableMWSuggest;

		$ns = $wgTitle->getNamespace();
		$nsname = isset( $wgCanonicalNamespaceNames[ $ns ] ) ? $wgCanonicalNamespaceNames[ $ns ] : $wgTitle->getNsText();

		$vars = array(
			'skin' => $data['skinname'],
			'stylepath' => $wgStylePath,
			'wgArticlePath' => $wgArticlePath,
			'wgScriptPath' => $wgScriptPath,
			'wgScript' => $wgScript,
			'wgVariantArticlePath' => $wgVariantArticlePath,
			'wgActionPaths' => (object)$wgActionPaths,
			'wgServer' => $wgServer,
			'wgCanonicalNamespace' => $nsname,
			'wgCanonicalSpecialPageName' => SpecialPage::resolveAlias( $wgTitle->getDBkey() ),
			'wgNamespaceNumber' => $wgTitle->getNamespace(),
			'wgPageName' => $wgTitle->getPrefixedDBKey(),
			'wgTitle' => $wgTitle->getText(),
			'wgAction' => $wgRequest->getText( 'action', 'view' ),
			'wgArticleId' => $wgTitle->getArticleId(),
			'wgIsArticle' => $wgOut->isArticle(),
			'wgUserName' => $wgUser->isAnon() ? NULL : $wgUser->getName(),
			'wgUserGroups' => $wgUser->isAnon() ? NULL : $wgUser->getEffectiveGroups(),
			'wgUserLanguage' => $wgLang->getCode(),
			'wgContentLanguage' => $wgContLang->getCode(),
			'wgBreakFrames' => $wgBreakFrames,
			'wgCurRevisionId' => isset( $wgArticle ) ? $wgArticle->getLatest() : 0,
			'wgVersion' => $wgVersion,
			'wgEnableAPI' => $wgEnableAPI,
			'wgEnableWriteAPI' => $wgEnableWriteAPI,
		);
		
		if( $wgUseAjax && $wgEnableMWSuggest && !$wgUser->getOption( 'disablesuggest', false )){
			$vars['wgMWSuggestTemplate'] = SearchEngine::getMWSuggestTemplate();
			$vars['wgDBname'] = $wgDBname;
			$vars['wgSearchNamespaces'] = SearchEngine::userNamespaces( $wgUser );
			$vars['wgMWSuggestMessages'] = array( wfMsg('search-mwsuggest-enabled'), wfMsg('search-mwsuggest-disabled'));
		}

		foreach( $wgRestrictionTypes as $type )
			$vars['wgRestriction' . ucfirst( $type )] = $wgTitle->getRestrictions( $type );

		if ( $wgLivePreview && $wgUser->getOption( 'uselivepreview' ) ) {
			$vars['wgLivepreviewMessageLoading'] = wfMsg( 'livepreview-loading' );
			$vars['wgLivepreviewMessageReady']   = wfMsg( 'livepreview-ready' );
			$vars['wgLivepreviewMessageFailed']  = wfMsg( 'livepreview-failed' );
			$vars['wgLivepreviewMessageError']   = wfMsg( 'livepreview-error' );
		}

		if($wgUseAjax && $wgAjaxWatch && $wgUser->isLoggedIn() ) {
			$msgs = (object)array();
			foreach ( array( 'watch', 'unwatch', 'watching', 'unwatching' ) as $msgName ) {
				$msgs->{$msgName . 'Msg'} = wfMsg( $msgName );
			}
			$vars['wgAjaxWatch'] = $msgs;
		}

		wfRunHooks('MakeGlobalVariablesScript', array(&$vars));

		return self::makeVariablesScript( $vars );
	}

	function getHeadScripts( $allowUserJs ) {
		global $wgStylePath, $wgUser, $wgJsMimeType, $wgStyleVersion;

		$vars = self::makeGlobalVariablesScript( array( 'skinname' => $this->getSkinName() ) );

		$r = array( "<script type=\"{$wgJsMimeType}\" src=\"{$wgStylePath}/common/wikibits.js?$wgStyleVersion\"></script>" );
		global $wgUseSiteJs;
		if ($wgUseSiteJs) {
			$jsCache = $wgUser->isLoggedIn() ? '&smaxage=0' : '';
			$r[] = "<script type=\"$wgJsMimeType\" src=\"".
				htmlspecialchars(self::makeUrl('-',
					"action=raw$jsCache&gen=js&useskin=" .
					urlencode( $this->getSkinName() ) ) ) .
				"\"><!-- site js --></script>";
		}
		if( $allowUserJs && $wgUser->isLoggedIn() ) {
			$userpage = $wgUser->getUserPage();
			$userjs = htmlspecialchars( self::makeUrl(
				$userpage->getPrefixedText().'/'.$this->getSkinName().'.js',
				'action=raw&ctype='.$wgJsMimeType));
			$r[] = '<script type="'.$wgJsMimeType.'" src="'.$userjs."\"></script>";
		}
		return $vars . "\t\t" . implode ( "\n\t\t", $r );
	}

	/**
	 * To make it harder for someone to slip a user a fake
	 * user-JavaScript or user-CSS preview, a random token
	 * is associated with the login session. If it's not
	 * passed back with the preview request, we won't render
	 * the code.
	 *
	 * @param string $action
	 * @return bool
	 * @private
	 */
	function userCanPreview( $action ) {
		global $wgTitle, $wgRequest, $wgUser;

		if( $action != 'submit' )
			return false;
		if( !$wgRequest->wasPosted() )
			return false;
		if( !$wgTitle->userCanEditCssJsSubpage() )
			return false;
		return $wgUser->matchEditToken(
			$wgRequest->getVal( 'wpEditToken' ) );
	}

	/**
	 * generated JavaScript action=raw&gen=js
	 * This returns MediaWiki:Common.js and MediaWiki:[Skinname].js concate-
	 * nated together.  For some bizarre reason, it does *not* return any
	 * custom user JS from subpages.  Huh?
	 *
	 * There's absolutely no reason to have separate Monobook/Common JSes.
	 * Any JS that cares can just check the skin variable generated at the
	 * top.  For now Monobook.js will be maintained, but it should be consi-
	 * dered deprecated.
	 *
	 * @return string
	 */
	public function generateUserJs() {
		global $wgStylePath;

		wfProfileIn( __METHOD__ );

		$s = "/* generated javascript */\n";
		$s .= "var skin = '" . Xml::escapeJsString( $this->getSkinName() ) . "';\n";
		$s .= "var stylepath = '" . Xml::escapeJsString( $wgStylePath ) . "';";
		$s .= "\n\n/* MediaWiki:Common.js */\n";
		$commonJs = wfMsgForContent('common.js');
		if ( !wfEmptyMsg ( 'common.js', $commonJs ) ) {
			$s .= $commonJs;
		}

		$s .= "\n\n/* MediaWiki:".ucfirst( $this->getSkinName() ).".js */\n";
		// avoid inclusion of non defined user JavaScript (with custom skins only)
		// by checking for default message content
		$msgKey = ucfirst( $this->getSkinName() ).'.js';
		$userJS = wfMsgForContent($msgKey);
		if ( !wfEmptyMsg( $msgKey, $userJS ) ) {
			$s .= $userJS;
		}

		wfProfileOut( __METHOD__ );
		return $s;
	}

	/**
	 * generate user stylesheet for action=raw&gen=css
	 */
	public function generateUserStylesheet() {
		wfProfileIn( __METHOD__ );
		$s = "/* generated user stylesheet */\n" .
			$this->reallyGenerateUserStylesheet();
		wfProfileOut( __METHOD__ );
		return $s;
	}
	
	/**
	 * Split for easier subclassing in SkinSimple, SkinStandard and SkinCologneBlue
	 */
	protected function reallyGenerateUserStylesheet(){
		global $wgUser;
		$s = '';
		if (($undopt = $wgUser->getOption("underline")) < 2) {
			$underline = $undopt ? 'underline' : 'none';
			$s .= "a { text-decoration: $underline; }\n";
		}
		if( $wgUser->getOption( 'highlightbroken' ) ) {
			$s .= "a.new, #quickbar a.new { color: #CC2200; }\n";
		} else {
			$s .= <<<END
a.new, #quickbar a.new,
a.stub, #quickbar a.stub {
	color: inherit;
}
a.new:after, #quickbar a.new:after {
	content: "?";
	color: #CC2200;
}
a.stub:after, #quickbar a.stub:after {
	content: "!";
	color: #772233;
}
END;
		}
		if( $wgUser->getOption( 'justify' ) ) {
			$s .= "#article, #bodyContent, #mw_content { text-align: justify; }\n";
		}
		if( !$wgUser->getOption( 'showtoc' ) ) {
			$s .= "#toc { display: none; }\n";
		}
		if( !$wgUser->getOption( 'editsection' ) ) {
			$s .= ".editsection { display: none; }\n";
		}
		return $s;
	}

	/**
	 * @private
	 */
	function setupUserCss( OutputPage $out ) {
		global $wgRequest, $wgContLang, $wgUser;
		global $wgAllowUserCss, $wgUseSiteCss, $wgSquidMaxage, $wgStylePath;

		wfProfileIn( __METHOD__ );

		$this->setupSkinUserCss( $out );

		$siteargs = array(
			'action' => 'raw',
			'maxage' => $wgSquidMaxage,
		);

		// Add any extension CSS
		foreach( $out->getExtStyle() as $tag ) {
			$out->addStyle( $tag['href'] );
		}

		// If we use the site's dynamic CSS, throw that in, too
		// Per-site custom styles
		if( $wgUseSiteCss ) {
			global $wgHandheldStyle;
			$query = wfArrayToCGI( array(
				'usemsgcache' => 'yes',
				'ctype' => 'text/css',
				'smaxage' => $wgSquidMaxage
			) + $siteargs );
			# Site settings must override extension css! (bug 15025)
			$out->addStyle( self::makeNSUrl( 'Common.css', $query, NS_MEDIAWIKI ) );
			$out->addStyle( self::makeNSUrl( 'Print.css', $query, NS_MEDIAWIKI ), 'print' );
			if( $wgHandheldStyle ) {
				$out->addStyle( self::makeNSUrl( 'Handheld.css', $query, NS_MEDIAWIKI ), 'handheld' );
			}
			$out->addStyle( self::makeNSUrl( $this->getSkinName() . '.css', $query, NS_MEDIAWIKI ) );
		}

		if( $wgUser->isLoggedIn() ) {
			// Ensure that logged-in users' generated CSS isn't clobbered
			// by anons' publicly cacheable generated CSS.
			$siteargs['smaxage'] = '0';
			$siteargs['ts'] = $wgUser->mTouched;
		}
		// Per-user styles based on preferences
		$siteargs['gen'] = 'css';
		if( ( $us = $wgRequest->getVal( 'useskin', '' ) ) !== '' ) {
			$siteargs['useskin'] = $us;
		}
		$out->addStyle( self::makeUrl( '-', wfArrayToCGI( $siteargs ) ) );

		// Per-user custom style pages
		if( $wgAllowUserCss && $wgUser->isLoggedIn() ) {
			$action = $wgRequest->getVal('action');
			# If we're previewing the CSS page, use it
			if( $this->mTitle->isCssSubpage() && $this->userCanPreview( $action ) ) {
				$previewCss = $wgRequest->getText('wpTextbox1');
				// @FIXME: properly escape the cdata!
				$this->usercss = "/*<![CDATA[*/\n" . $previewCss . "/*]]>*/";
			} else {
				$out->addStyle( self::makeUrl($this->userpage . '/' . $this->getSkinName() .'.css',
					'action=raw&ctype=text/css' ) );
			}
		}

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Add skin specific stylesheets
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ) {
		$out->addStyle( 'common/shared.css' );
		$out->addStyle( 'common/oldshared.css' );
		$out->addStyle( $this->getStylesheet() );
		$out->addStyle( 'common/common_rtl.css', '', '', 'rtl' );
	}

	function getBodyOptions() {
		global $wgUser, $wgTitle, $wgOut, $wgRequest, $wgContLang;

		extract( $wgRequest->getValues( 'oldid', 'redirect', 'diff' ) );

		if ( 0 != $wgTitle->getNamespace() ) {
			$a = array( 'bgcolor' => '#ffffec' );
		}
		else $a = array( 'bgcolor' => '#FFFFFF' );
		if($wgOut->isArticle() && $wgUser->getOption('editondblclick') &&
		  $wgTitle->quickUserCan( 'edit' ) ) {
			$s = $wgTitle->getFullURL( $this->editUrlOptions() );
			$s = 'document.location = "' .wfEscapeJSString( $s ) .'";';
			$a += array ('ondblclick' => $s);

		}
		$a['onload'] = $wgOut->getOnloadHandler();
		$a['class'] =
			'mediawiki' .
			' '.( $wgContLang->isRTL() ? "rtl" : "ltr" ).
			' '.$this->getPageClasses( $wgTitle ) .
			' skin-'. Sanitizer::escapeClass( $this->getSkinName( ) );
		return $a;
	}
	
	function getPageClasses( $title ) {
		$numeric = 'ns-'.$title->getNamespace();
		if( $title->getNamespace() == NS_SPECIAL ) {
			$type = "ns-special";
		} elseif( $title->isTalkPage() ) {
			$type = "ns-talk";
		} else {
			$type = "ns-subject";
		}
		$name = Sanitizer::escapeClass( 'page-'.$title->getPrefixedText() );
		return "$numeric $type $name";
	}

	/**
	 * URL to the logo
	 */
	function getLogo() {
		global $wgLogo;
		return $wgLogo;
	}

	/**
	 * This will be called immediately after the <body> tag.  Split into
	 * two functions to make it easier to subclass.
	 */
	function beforeContent() {
		return $this->doBeforeContent();
	}

	function doBeforeContent() {
		global $wgContLang;
		$fname = 'Skin::doBeforeContent';
		wfProfileIn( $fname );

		$s = '';
		$qb = $this->qbSetting();

		if( $langlinks = $this->otherLanguages() ) {
			$rows = 2;
			$borderhack = '';
		} else {
			$rows = 1;
			$langlinks = false;
			$borderhack = 'class="top"';
		}

		$s .= "\n<div id='content'>\n<div id='topbar'>\n" .
		  "<table border='0' cellspacing='0' width='98%'>\n<tr>\n";

		$shove = ( $qb != 0 );
		$left = ( $qb == 1 || $qb == 3 );
		if( $wgContLang->isRTL() ) $left = !$left;

		if( !$shove ) {
			$s .= "<td class='top' align='left' valign='top' rowspan='{$rows}'>\n" .
			  $this->logoText() . '</td>';
		} elseif( $left ) {
			$s .= $this->getQuickbarCompensator( $rows );
		}
		$l = $wgContLang->isRTL() ? 'right' : 'left';
		$s .= "<td {$borderhack} align='$l' valign='top'>\n";

		$s .= $this->topLinks() ;
		$s .= "<p class='subtitle'>" . $this->pageTitleLinks() . "</p>\n";

		$r = $wgContLang->isRTL() ? "left" : "right";
		$s .= "</td>\n<td {$borderhack} valign='top' align='$r' nowrap='nowrap'>";
		$s .= $this->nameAndLogin();
		$s .= "\n<br />" . $this->searchForm() . "</td>";

		if ( $langlinks ) {
			$s .= "</tr>\n<tr>\n<td class='top' colspan=\"2\">$langlinks</td>\n";
		}

		if ( $shove && !$left ) { # Right
			$s .= $this->getQuickbarCompensator( $rows );
		}
		$s .= "</tr>\n</table>\n</div>\n";
		$s .= "\n<div id='article'>\n";

		$notice = wfGetSiteNotice();
		if( $notice ) {
			$s .= "\n<div id='siteNotice'>$notice</div>\n";
		}
		$s .= $this->pageTitle();
		$s .= $this->pageSubtitle() ;
		$s .= $this->getCategories();
		wfProfileOut( $fname );
		return $s;
	}


	function getCategoryLinks() {
		global $wgOut, $wgTitle, $wgUseCategoryBrowser;
		global $wgContLang, $wgUser;

		if( count( $wgOut->mCategoryLinks ) == 0 ) return '';

		# Separator
		$sep = wfMsgHtml( 'catseparator' );

		// Use Unicode bidi embedding override characters,
		// to make sure links don't smash each other up in ugly ways.
		$dir = $wgContLang->isRTL() ? 'rtl' : 'ltr';
		$embed = "<span dir='$dir'>";
		$pop = '</span>';

		$allCats = $wgOut->getCategoryLinks();
		$s = '';
		$colon = wfMsgExt( 'colon-separator', 'escapenoentities' );
		if ( !empty( $allCats['normal'] ) ) {
			$t = $embed . implode ( "{$pop} {$sep} {$embed}" , $allCats['normal'] ) . $pop;

			$msg = wfMsgExt( 'pagecategories', array( 'parsemag', 'escapenoentities' ), count( $allCats['normal'] ) );
			$s .= '<div id="mw-normal-catlinks">' .
				$this->link( Title::newFromText( wfMsgForContent('pagecategorieslink') ), $msg )
				. $colon . $t . '</div>';
		}

		# Hidden categories
		if ( isset( $allCats['hidden'] ) ) {
			if ( $wgUser->getBoolOption( 'showhiddencats' ) ) {
				$class ='mw-hidden-cats-user-shown';
			} elseif ( $wgTitle->getNamespace() == NS_CATEGORY ) {
				$class = 'mw-hidden-cats-ns-shown';
			} else {
				$class = 'mw-hidden-cats-hidden';
			}
			$s .= "<div id=\"mw-hidden-catlinks\" class=\"$class\">" .
				wfMsgExt( 'hidden-categories', array( 'parsemag', 'escapenoentities' ), count( $allCats['hidden'] ) ) .
				$colon . $embed . implode( "$pop $sep $embed", $allCats['hidden'] ) . $pop .
				"</div>";
		}

		# optional 'dmoz-like' category browser. Will be shown under the list
		# of categories an article belong to
		if( $wgUseCategoryBrowser ){
			$s .= '<br /><hr />';

			# get a big array of the parents tree
			$parenttree = $wgTitle->getParentCategoryTree();
			# Skin object passed by reference cause it can not be
			# accessed under the method subfunction drawCategoryBrowser
			$tempout = explode("\n", Skin::drawCategoryBrowser($parenttree, $this) );
			# Clean out bogus first entry and sort them
			unset($tempout[0]);
			asort($tempout);
			# Output one per line
			$s .= implode("<br />\n", $tempout);
		}

		return $s;
	}

	/** Render the array as a serie of links.
	 * @param $tree Array: categories tree returned by Title::getParentCategoryTree
	 * @param &skin Object: skin passed by reference
	 * @return String separated by &gt;, terminate with "\n"
	 */
	function drawCategoryBrowser( $tree, &$skin ){
		$return = '';
		foreach ($tree as $element => $parent) {
			if (empty($parent)) {
				# element start a new list
				$return .= "\n";
			} else {
				# grab the others elements
				$return .= Skin::drawCategoryBrowser($parent, $skin) . ' &gt; ';
			}
			# add our current element to the list
			$eltitle = Title::newFromText($element);
			$return .=  $skin->link( $eltitle, $eltitle->getText() ) ;
		}
		return $return;
	}

	function getCategories() {
		$catlinks=$this->getCategoryLinks();

		$classes = 'catlinks';

		if( strpos( $catlinks, '<div id="mw-normal-catlinks">' ) === false &&
			strpos( $catlinks, '<div id="mw-hidden-catlinks" class="mw-hidden-cats-hidden">' ) !== false ) {
			$classes .= ' catlinks-allhidden';
		}

		if( !empty( $catlinks ) ){
			return "<div id='catlinks' class='$classes'>{$catlinks}</div>";
		}
	}

	function getQuickbarCompensator( $rows = 1 ) {
		return "<td width='152' rowspan='{$rows}'>&nbsp;</td>";
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
		$data = "";

		if( wfRunHooks( 'SkinAfterContent', array( &$data ) ) ){
			// adding just some spaces shouldn't toggle the output
			// of the whole <div/>, so we use trim() here
			if( trim( $data ) != '' ){
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
	 * This gets called shortly before the </body> tag.
	 * @return String HTML to be put before </body>
	 */
	function afterContent() {
		$printfooter = "<div class=\"printfooter\">\n" . $this->printFooter() . "</div>\n";
		return $printfooter . $this->doAfterContent();
	}

	/**
	 * This gets called shortly before the </body> tag.
	 * @return String HTML-wrapped JS code to be put before </body>
	 */
	function bottomScripts() {
		global $wgJsMimeType;
		$bottomScriptText = "\n\t\t<script type=\"$wgJsMimeType\">if (window.runOnloadHook) runOnloadHook();</script>\n";
		wfRunHooks( 'SkinAfterBottomScripts', array( $this, &$bottomScriptText ) );
		return $bottomScriptText;
	}

	/** @return string Retrievied from HTML text */
	function printSource() {
		global $wgTitle;
		$url = htmlspecialchars( $wgTitle->getFullURL() );
		return wfMsg( 'retrievedfrom', '<a href="'.$url.'">'.$url.'</a>' );
	}

	function printFooter() {
		return "<p>" .  $this->printSource() .
			"</p>\n\n<p>" . $this->pageStats() . "</p>\n";
	}

	/** overloaded by derived classes */
	function doAfterContent() { return "</div></div>"; }

	function pageTitleLinks() {
		global $wgOut, $wgTitle, $wgUser, $wgRequest;

		$oldid = $wgRequest->getVal( 'oldid' );
		$diff = $wgRequest->getVal( 'diff' );
		$action = $wgRequest->getText( 'action' );

		$s = $this->printableLink();
		$disclaimer = $this->disclaimerLink(); # may be empty
		if( $disclaimer ) {
			$s .= ' | ' . $disclaimer;
		}
		$privacy = $this->privacyLink(); # may be empty too
		if( $privacy ) {
			$s .= ' | ' . $privacy;
		}

		if ( $wgOut->isArticleRelated() ) {
			if ( $wgTitle->getNamespace() == NS_IMAGE ) {
				$name = $wgTitle->getDBkey();
				$image = wfFindFile( $wgTitle );
				if( $image ) {
					$link = htmlspecialchars( $image->getURL() );
					$style = $this->getInternalLinkAttributes( $link, $name );
					$s .= " | <a href=\"{$link}\"{$style}>{$name}</a>";
				}
			}
		}
		if ( 'history' == $action || isset( $diff ) || isset( $oldid ) ) {
			$s .= ' | ' . $this->makeKnownLinkObj( $wgTitle,
					wfMsg( 'currentrev' ) );
		}

		if ( $wgUser->getNewtalk() ) {
			# do not show "You have new messages" text when we are viewing our
			# own talk page
			if( !$wgTitle->equals( $wgUser->getTalkPage() ) ) {
				$tl = $this->makeKnownLinkObj( $wgUser->getTalkPage(), wfMsgHtml( 'newmessageslink' ), 'redirect=no' );
				$dl = $this->makeKnownLinkObj( $wgUser->getTalkPage(), wfMsgHtml( 'newmessagesdifflink' ), 'diff=cur' );
				$s.= ' | <strong>'. wfMsg( 'youhavenewmessages', $tl, $dl ) . '</strong>';
				# disable caching
				$wgOut->setSquidMaxage(0);
				$wgOut->enableClientCache(false);
			}
		}

		$undelete = $this->getUndeleteLink();
		if( !empty( $undelete ) ) {
			$s .= ' | '.$undelete;
		}
		return $s;
	}

	function getUndeleteLink() {
		global $wgUser, $wgTitle, $wgContLang, $wgLang, $action;
		if(	$wgUser->isAllowed( 'deletedhistory' ) &&
			(($wgTitle->getArticleId() == 0) || ($action == "history")) &&
			($n = $wgTitle->isDeleted() ) )
		{
			if ( $wgUser->isAllowed( 'undelete' ) ) {
				$msg = 'thisisdeleted';
			} else {
				$msg = 'viewdeleted';
			}
			return wfMsg( $msg,
				$this->makeKnownLinkObj(
					SpecialPage::getTitleFor( 'Undelete', $wgTitle->getPrefixedDBkey() ),
					wfMsgExt( 'restorelink', array( 'parsemag', 'escape' ), $wgLang->formatNum( $n ) ) ) );
		}
		return '';
	}

	function printableLink() {
		global $wgOut, $wgFeedClasses, $wgRequest;

		$printurl = $wgRequest->escapeAppendQuery( 'printable=yes' );

		$s = "<a href=\"$printurl\">" . wfMsg( 'printableversion' ) . '</a>';
		if( $wgOut->isSyndicated() ) {
			foreach( $wgFeedClasses as $format => $class ) {
				$feedurl = $wgRequest->escapeAppendQuery( "feed=$format" );
				$s .= " | <a href=\"$feedurl\">{$format}</a>";
			}
		}
		return $s;
	}

	function pageTitle() {
		global $wgOut;
		$s = '<h1 class="pagetitle">' . htmlspecialchars( $wgOut->getPageTitle() ) . '</h1>';
		return $s;
	}

	function pageSubtitle() {
		global $wgOut;

		$sub = $wgOut->getSubtitle();
		if ( '' == $sub ) {
			global $wgExtraSubtitle;
			$sub = wfMsg( 'tagline' ) . $wgExtraSubtitle;
		}
		$subpages = $this->subPageSubtitle();
		$sub .= !empty($subpages)?"</p><p class='subpages'>$subpages":'';
		$s = "<p class='subtitle'>{$sub}</p>\n";
		return $s;
	}

	function subPageSubtitle() {
		$subpages = '';
		if(!wfRunHooks('SkinSubPageSubtitle', array(&$subpages)))
			return $subpages;

		global $wgOut, $wgTitle;
		if($wgOut->isArticle() && MWNamespace::hasSubpages( $wgTitle->getNamespace() )) {
			$ptext=$wgTitle->getPrefixedText();
			if(preg_match('/\//',$ptext)) {
				$links = explode('/',$ptext);
				array_pop( $links );
				$c = 0;
				$growinglink = '';
				$display = '';
				foreach($links as $link) {
					$growinglink .= $link;
					$display .= $link;
					$linkObj = Title::newFromText( $growinglink );
					if( is_object( $linkObj ) && $linkObj->exists() ){
						$getlink = $this->makeKnownLinkObj( $linkObj, htmlspecialchars( $display ) );
						$c++;
						if ($c>1) {
							$subpages .= ' | ';
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

	function nameAndLogin() {
		global $wgUser, $wgTitle, $wgLang, $wgContLang;

		$logoutPage = $wgContLang->specialPage( 'Userlogout' );

		$ret = '';
		if ( $wgUser->isAnon() ) {
			if( $this->showIPinHeader() ) {
				$name = wfGetIP();

				$talkLink = $this->link( $wgUser->getTalkPage(),
					$wgLang->getNsText( NS_TALK ) );

				$ret .= "$name ($talkLink)";
			} else {
				$ret .= wfMsg( 'notloggedin' );
			}

			$returnTo = $wgTitle->getPrefixedDBkey();
			$query = array();
			if ( $logoutPage != $returnTo ) {
				$query['returnto'] = $returnTo;
			}

			$loginlink = $wgUser->isAllowed( 'createaccount' )
				? 'nav-login-createaccount'
				: 'login';
			$ret .= "\n<br />" . $this->link(
				SpecialPage::getTitleFor( 'Userlogin' ),
				wfMsg( $loginlink ), array(), $query
			);
		} else {
			$returnTo = $wgTitle->getPrefixedDBkey();
			$talkLink = $this->link( $wgUser->getTalkPage(),
				$wgLang->getNsText( NS_TALK ) );

			$ret .= $this->link( $wgUser->getUserPage(),
				htmlspecialchars( $wgUser->getName() ) );
			$ret .= " ($talkLink)<br />";
			$ret .= $this->link(
				SpecialPage::getTitleFor( 'Userlogout' ), wfMsg( 'logout' ),
				array(), array( 'returnto' => $returnTo )
			);
			$ret .= ' | ' . $this->specialLink( 'preferences' );
		}
		$ret .= ' | ' . $this->link(
			Title::newFromText( wfMsgForContent( 'helppage' ) ),
			wfMsg( 'help' )
		);

		return $ret;
	}

	function getSearchLink() {
		$searchPage = SpecialPage::getTitleFor( 'Search' );
		return $searchPage->getLocalURL();
	}

	function escapeSearchLink() {
		return htmlspecialchars( $this->getSearchLink() );
	}

	function searchForm() {
		global $wgRequest;
		$search = $wgRequest->getText( 'search' );

		$s = '<form id="searchform'.$this->searchboxes.'" name="search" class="inline" method="post" action="'
		  . $this->escapeSearchLink() . "\">\n"
		  . '<input type="text" id="searchInput'.$this->searchboxes.'" name="search" size="19" value="'
		  . htmlspecialchars(substr($search,0,256)) . "\" />\n"
		  . '<input type="submit" name="go" value="' . wfMsg ('searcharticle') . '" />&nbsp;'
		  . '<input type="submit" name="fulltext" value="' . wfMsg ('searchbutton') . "\" />\n</form>";

		// Ensure unique id's for search boxes made after the first
		$this->searchboxes = $this->searchboxes == '' ? 2 : $this->searchboxes + 1;
		
		return $s;
	}

	function topLinks() {
		global $wgOut;
		$sep = " |\n";

		$s = $this->mainPageLink() . $sep
		  . $this->specialLink( 'recentchanges' );

		if ( $wgOut->isArticleRelated() ) {
			$s .=  $sep . $this->editThisPage()
			  . $sep . $this->historyLink();
		}
		# Many people don't like this dropdown box
		#$s .= $sep . $this->specialPagesList();

		$s .= $this->variantLinks();

		$s .= $this->extensionTabLinks();

		return $s;
	}

	/**
	 * Compatibility for extensions adding functionality through tabs.
	 * Eventually these old skins should be replaced with SkinTemplate-based
	 * versions, sigh...
	 * @return string
	 */
	function extensionTabLinks() {
		$tabs = array();
		$s = '';
		wfRunHooks( 'SkinTemplateTabs', array( $this, &$tabs ) );
		foreach( $tabs as $tab ) {
			$s .= ' | ' . Xml::element( 'a',
				array( 'href' => $tab['href'] ),
				$tab['text'] );
		}
		return $s;
	}

	/**
	 * Language/charset variant links for classic-style skins
	 * @return string
	 */
	function variantLinks() {
		$s = '';
		/* show links to different language variants */
		global $wgDisableLangConversion, $wgContLang, $wgTitle;
		$variants = $wgContLang->getVariants();
		if( !$wgDisableLangConversion && sizeof( $variants ) > 1 ) {
			foreach( $variants as $code ) {
				$varname = $wgContLang->getVariantname( $code );
				if( $varname == 'disable' )
					continue;
				$s .= ' | <a href="' . $wgTitle->escapeLocalUrl( 'variant=' . $code ) . '">' . htmlspecialchars( $varname ) . '</a>';
			}
		}
		return $s;
	}

	function bottomLinks() {
		global $wgOut, $wgUser, $wgTitle, $wgUseTrackbacks;
		$sep = " |\n";

		$s = '';
		if ( $wgOut->isArticleRelated() ) {
			$s .= '<strong>' . $this->editThisPage() . '</strong>';
			if ( $wgUser->isLoggedIn() ) {
				$s .= $sep . $this->watchThisPage();
			}
			$s .= $sep . $this->talkLink()
			  . $sep . $this->historyLink()
			  . $sep . $this->whatLinksHere()
			  . $sep . $this->watchPageLinksLink();

			if ($wgUseTrackbacks)
				$s .= $sep . $this->trackbackLink();

			if ( $wgTitle->getNamespace() == NS_USER
			    || $wgTitle->getNamespace() == NS_USER_TALK )

			{
				$id=User::idFromName($wgTitle->getText());
				$ip=User::isIP($wgTitle->getText());

				if($id || $ip) { # both anons and non-anons have contri list
					$s .= $sep . $this->userContribsLink();
				}
				if( $this->showEmailUser( $id ) ) {
					$s .= $sep . $this->emailUserLink();
				}
			}
			if ( $wgTitle->getArticleId() ) {
				$s .= "\n<br />";
				if($wgUser->isAllowed('delete')) { $s .= $this->deleteThisPage(); }
				if($wgUser->isAllowed('protect')) { $s .= $sep . $this->protectThisPage(); }
				if($wgUser->isAllowed('move')) { $s .= $sep . $this->moveThisPage(); }
			}
			$s .= "<br />\n" . $this->otherLanguages();
		}
		return $s;
	}

	function pageStats() {
		global $wgOut, $wgLang, $wgArticle, $wgRequest, $wgUser;
		global $wgDisableCounters, $wgMaxCredits, $wgShowCreditsIfMax, $wgTitle, $wgPageShowWatchingUsers;

		$oldid = $wgRequest->getVal( 'oldid' );
		$diff = $wgRequest->getVal( 'diff' );
		if ( ! $wgOut->isArticle() ) { return ''; }
		if ( isset( $oldid ) || isset( $diff ) ) { return ''; }
		if ( 0 == $wgArticle->getID() ) { return ''; }

		$s = '';
		if ( !$wgDisableCounters ) {
			$count = $wgLang->formatNum( $wgArticle->getCount() );
			if ( $count ) {
				$s = wfMsgExt( 'viewcount', array( 'parseinline' ), $count );
			}
		}

		if( $wgMaxCredits != 0 ){
			$s .= ' ' . Credits::getCredits( $wgArticle, $wgMaxCredits, $wgShowCreditsIfMax );
		} else {
			$s .= $this->lastModified();
		}

		if( $wgPageShowWatchingUsers && $wgUser->getOption( 'shownumberswatching' ) ) {
			$dbr = wfGetDB( DB_SLAVE );
			$watchlist = $dbr->tableName( 'watchlist' );
			$sql = "SELECT COUNT(*) AS n FROM $watchlist
				WHERE wl_title='" . $dbr->strencode($wgTitle->getDBkey()) .
				"' AND  wl_namespace=" . $wgTitle->getNamespace() ;
			$res = $dbr->query( $sql, 'Skin::pageStats');
			$x = $dbr->fetchObject( $res );

			$s .= ' ' . wfMsgExt( 'number_of_watching_users_pageview',
				array( 'parseinline' ), $wgLang->formatNum($x->n)
			);
		}

		return $s . ' ' .  $this->getCopyright();
	}

	function getCopyright( $type = 'detect' ) {
		global $wgRightsPage, $wgRightsUrl, $wgRightsText, $wgRequest;

		if ( $type == 'detect' ) {
			$oldid = $wgRequest->getVal( 'oldid' );
			$diff = $wgRequest->getVal( 'diff' );

			if ( !is_null( $oldid ) && is_null( $diff ) && wfMsgForContent( 'history_copyright' ) !== '-' ) {
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
		if( $wgRightsPage ) {
			$link = $this->makeKnownLink( $wgRightsPage, $wgRightsText );
		} elseif( $wgRightsUrl ) {
			$link = $this->makeExternalLink( $wgRightsUrl, $wgRightsText );
		} elseif( $wgRightsText ) {
			$link = $wgRightsText;
		} else {
			# Give up now
			return $out;
		}
		$out .= wfMsgForContent( $msg, $link );
		return $out;
	}

	function getCopyrightIcon() {
		global $wgRightsUrl, $wgRightsText, $wgRightsIcon, $wgCopyrightIcon;
		$out = '';
		if ( isset( $wgCopyrightIcon ) && $wgCopyrightIcon ) {
			$out = $wgCopyrightIcon;
		} else if ( $wgRightsIcon ) {
			$icon = htmlspecialchars( $wgRightsIcon );
			if ( $wgRightsUrl ) {
				$url = htmlspecialchars( $wgRightsUrl );
				$out .= '<a href="'.$url.'">';
			}
			$text = htmlspecialchars( $wgRightsText );
			$out .= "<img src=\"$icon\" alt='$text' />";
			if ( $wgRightsUrl ) {
				$out .= '</a>';
			}
		}
		return $out;
	}

	function getPoweredBy() {
		global $wgStylePath;
		$url = htmlspecialchars( "$wgStylePath/common/images/poweredby_mediawiki_88x31.png" );
		$img = '<a href="http://www.mediawiki.org/"><img src="'.$url.'" alt="Powered by MediaWiki" /></a>';
		return $img;
	}

	function lastModified() {
		global $wgLang, $wgArticle;
		if( $this->mRevisionId ) {
			$timestamp = Revision::getTimestampFromId( $this->mRevisionId, $wgArticle->getId() );
		} else {
			$timestamp = $wgArticle->getTimestamp();
		}
		if ( $timestamp ) {
			$d = $wgLang->date( $timestamp, true );
			$t = $wgLang->time( $timestamp, true );
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
		if ( '' != $align ) { $a = " align='{$align}'"; }
		else { $a = ''; }

		$mp = wfMsg( 'mainpage' );
		$mptitle = Title::newMainPage();
		$url = ( is_object($mptitle) ? $mptitle->escapeLocalURL() : '' );

		$logourl = $this->getLogo();
		$s = "<a href='{$url}'><img{$a} src='{$logourl}' alt='[{$mp}]' /></a>";
		return $s;
	}

	/**
	 * show a drop-down box of special pages
	 */
	function specialPagesList() {
		global $wgUser, $wgContLang, $wgServer, $wgRedirectScript;
		$pages = array_merge( SpecialPage::getRegularPages(), SpecialPage::getRestrictedPages() );
		foreach ( $pages as $name => $page ) {
			$pages[$name] = $page->getDescription();
		}

		$go = wfMsg( 'go' );
		$sp = wfMsg( 'specialpages' );
		$spp = $wgContLang->specialPage( 'Specialpages' );

		$s = '<form id="specialpages" method="get" ' .
		  'action="' . htmlspecialchars( "{$wgServer}{$wgRedirectScript}" ) . "\">\n";
		$s .= "<select name=\"wpDropdown\">\n";
		$s .= "<option value=\"{$spp}\">{$sp}</option>\n";


		foreach ( $pages as $name => $desc ) {
			$p = $wgContLang->specialPage( $name );
			$s .= "<option value=\"{$p}\">{$desc}</option>\n";
		}
		$s .= "</select>\n";
		$s .= "<input type='submit' value=\"{$go}\" name='redirect' />\n";
		$s .= "</form>\n";
		return $s;
	}

	function mainPageLink() {
		$s = $this->makeKnownLinkObj( Title::newMainPage(), wfMsg( 'mainpage' ) );
		return $s;
	}

	function copyrightLink() {
		$s = $this->makeKnownLink( wfMsgForContent( 'copyrightpage' ),
		  wfMsg( 'copyrightpagename' ) );
		return $s;
	}

	private function footerLink ( $desc, $page ) {
		// if the link description has been set to "-" in the default language,
		if ( wfMsgForContent( $desc )  == '-') {
			// then it is disabled, for all languages.
			return '';
		} else {
			// Otherwise, we display the link for the user, described in their
			// language (which may or may not be the same as the default language),
			// but we make the link target be the one site-wide page.
			return $this->makeKnownLink( wfMsgForContent( $page ),
				wfMsgExt( $desc, array( 'parsemag', 'escapenoentities' ) ) );
		}
	}

	function privacyLink() {
		return $this->footerLink( 'privacy', 'privacypage' );
	}

	function aboutLink() {
		return $this->footerLink( 'aboutsite', 'aboutpage' );
	}

	function disclaimerLink() {
		return $this->footerLink( 'disclaimers', 'disclaimerpage' );
	}

	function editThisPage() {
		global $wgOut, $wgTitle;

		if ( !$wgOut->isArticleRelated() ) {
			$s = wfMsg( 'protectedpage' );
		} else {
			if( $wgTitle->quickUserCan( 'edit' ) && $wgTitle->exists() ) {
				$t = wfMsg( 'editthispage' );
			} elseif( $wgTitle->quickUserCan( 'create' ) && !$wgTitle->exists() ) {
				$t = wfMsg( 'create-this-page' );
			} else {
				$t = wfMsg( 'viewsource' );
			}

			$s = $this->makeKnownLinkObj( $wgTitle, $t, $this->editUrlOptions() );
		}
		return $s;
	}

	/**
	 * Return URL options for the 'edit page' link.
	 * This may include an 'oldid' specifier, if the current page view is such.
	 *
	 * @return string
	 * @private
	 */
	function editUrlOptions() {
		global $wgArticle;

		if( $this->mRevisionId && ! $wgArticle->isCurrent() ) {
			return "action=edit&oldid=" . intval( $this->mRevisionId );
		} else {
			return "action=edit";
		}
	}

	function deleteThisPage() {
		global $wgUser, $wgTitle, $wgRequest;

		$diff = $wgRequest->getVal( 'diff' );
		if ( $wgTitle->getArticleId() && ( ! $diff ) && $wgUser->isAllowed('delete') ) {
			$t = wfMsg( 'deletethispage' );

			$s = $this->makeKnownLinkObj( $wgTitle, $t, 'action=delete' );
		} else {
			$s = '';
		}
		return $s;
	}

	function protectThisPage() {
		global $wgUser, $wgTitle, $wgRequest;

		$diff = $wgRequest->getVal( 'diff' );
		if ( $wgTitle->getArticleId() && ( ! $diff ) && $wgUser->isAllowed('protect') ) {
			if ( $wgTitle->isProtected() ) {
				$t = wfMsg( 'unprotectthispage' );
				$q = 'action=unprotect';
			} else {
				$t = wfMsg( 'protectthispage' );
				$q = 'action=protect';
			}
			$s = $this->makeKnownLinkObj( $wgTitle, $t, $q );
		} else {
			$s = '';
		}
		return $s;
	}

	function watchThisPage() {
		global $wgOut, $wgTitle;
		++$this->mWatchLinkNum;

		if ( $wgOut->isArticleRelated() ) {
			if ( $wgTitle->userIsWatching() ) {
				$t = wfMsg( 'unwatchthispage' );
				$q = 'action=unwatch';
				$id = "mw-unwatch-link".$this->mWatchLinkNum;
			} else {
				$t = wfMsg( 'watchthispage' );
				$q = 'action=watch';
				$id = 'mw-watch-link'.$this->mWatchLinkNum;
			}
			$s = $this->makeKnownLinkObj( $wgTitle, $t, $q, '', '', " id=\"$id\"" );
		} else {
			$s = wfMsg( 'notanarticle' );
		}
		return $s;
	}

	function moveThisPage() {
		global $wgTitle;

		if ( $wgTitle->quickUserCan( 'move' ) ) {
			return $this->makeKnownLinkObj( SpecialPage::getTitleFor( 'Movepage' ),
			  wfMsg( 'movethispage' ), 'target=' . $wgTitle->getPrefixedURL() );
		} else {
			// no message if page is protected - would be redundant
			return '';
		}
	}

	function historyLink() {
		global $wgTitle;

		return $this->makeKnownLinkObj( $wgTitle,
		  wfMsg( 'history' ), 'action=history' );
	}

	function whatLinksHere() {
		global $wgTitle;

		return $this->makeKnownLinkObj(
			SpecialPage::getTitleFor( 'Whatlinkshere', $wgTitle->getPrefixedDBkey() ),
			wfMsg( 'whatlinkshere' ) );
	}

	function userContribsLink() {
		global $wgTitle;

		return $this->makeKnownLinkObj(
			SpecialPage::getTitleFor( 'Contributions', $wgTitle->getDBkey() ),
			wfMsg( 'contributions' ) );
	}

	function showEmailUser( $id ) {
		global $wgEnableEmail, $wgEnableUserEmail, $wgUser;
		return $wgEnableEmail &&
		       $wgEnableUserEmail &&
		       $wgUser->isLoggedIn() && # show only to signed in users
		       0 != $id; # we can only email to non-anons ..
#		       '' != $id->getEmail() && # who must have an email address stored ..
#		       0 != $id->getEmailauthenticationtimestamp() && # .. which is authenticated
#		       1 != $wgUser->getOption('disablemail'); # and not disabled
	}

	function emailUserLink() {
		global $wgTitle;

		return $this->makeKnownLinkObj(
			SpecialPage::getTitleFor( 'Emailuser', $wgTitle->getDBkey() ),
			wfMsg( 'emailuser' ) );
	}

	function watchPageLinksLink() {
		global $wgOut, $wgTitle;

		if ( ! $wgOut->isArticleRelated() ) {
			return '(' . wfMsg( 'notanarticle' ) . ')';
		} else {
			return $this->makeKnownLinkObj(
				SpecialPage::getTitleFor( 'Recentchangeslinked', $wgTitle->getPrefixedDBkey() ),
				wfMsg( 'recentchangeslinked' ) );
		}
	}

	function trackbackLink() {
		global $wgTitle;

		return "<a href=\"" . $wgTitle->trackbackURL() . "\">"
			. wfMsg('trackbacklink') . "</a>";
	}

	function otherLanguages() {
		global $wgOut, $wgContLang, $wgHideInterlanguageLinks;

		if ( $wgHideInterlanguageLinks ) {
			return '';
		}

		$a = $wgOut->getLanguageLinks();
		if ( 0 == count( $a ) ) {
			return '';
		}

		$s = wfMsg( 'otherlanguages' ) . ': ';
		$first = true;
		if($wgContLang->isRTL()) $s .= '<span dir="LTR">';
		foreach( $a as $l ) {
			if ( ! $first ) { $s .= ' | '; }
			$first = false;

			$nt = Title::newFromText( $l );
			$url = $nt->escapeFullURL();
			$text = $wgContLang->getLanguageName( $nt->getInterwiki() );

			if ( '' == $text ) { $text = $l; }
			$style = $this->getExternalLinkAttributes( $l, $text );
			$s .= "<a href=\"{$url}\"{$style}>{$text}</a>";
		}
		if($wgContLang->isRTL()) $s .= '</span>';
		return $s;
	}

	function bugReportsLink() {
		$s = $this->makeKnownLink( wfMsgForContent( 'bugreportspage' ),
		  wfMsg( 'bugreports' ) );
		return $s;
	}

	function talkLink() {
		global $wgTitle;

		if ( NS_SPECIAL == $wgTitle->getNamespace() ) {
			# No discussion links for special pages
			return '';
		}

		$linkOptions = array();

		if( $wgTitle->isTalkPage() ) {
			$link = $wgTitle->getSubjectPage();
			switch( $link->getNamespace() ) {
				case NS_MAIN:
					$text = wfMsg( 'articlepage' );
					break;
				case NS_USER:
					$text = wfMsg( 'userpage' );
					break;
				case NS_PROJECT:
					$text = wfMsg( 'projectpage' );
					break;
				case NS_IMAGE:
					$text = wfMsg( 'imagepage' );
					# Make link known if image exists, even if the desc. page doesn't.
					if( wfFindFile( $link ) )
						$linkOptions[] = 'known';
					break;
				case NS_MEDIAWIKI:
					$text = wfMsg( 'mediawikipage' );
					break;
				case NS_TEMPLATE:
					$text = wfMsg( 'templatepage' );
					break;
				case NS_HELP:
					$text = wfMsg( 'viewhelppage' );
					break;
				case NS_CATEGORY:
					$text = wfMsg( 'categorypage' );
					break;
				default:
					$text = wfMsg( 'articlepage' );
			}
		} else {
			$link = $wgTitle->getTalkPage();
			$text = wfMsg( 'talkpage' );
		}

		$s = $this->link( $link, $text, array(), array(), $linkOptions );

		return $s;
	}

	function commentLink() {
		global $wgTitle, $wgOut;

		if ( $wgTitle->getNamespace() == NS_SPECIAL ) {
			return '';
		}

		# __NEWSECTIONLINK___ changes behaviour here
		# If it's present, the link points to this page, otherwise
		# it points to the talk page
		if( $wgTitle->isTalkPage() ) {
			$title = $wgTitle;
		} elseif( $wgOut->showNewSectionLink() ) {
			$title = $wgTitle;
		} else {
			$title = $wgTitle->getTalkPage();
		}

		return $this->makeKnownLinkObj( $title, wfMsg( 'postcomment' ), 'action=edit&section=new' );
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

	# If url string starts with http, consider as external URL, else
	# internal
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
			'exists' => $title->getArticleID() != 0 ? true : false
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
		if( !is_object( $title ) ) {
			$title = Title::newFromText( $name );
			if( !is_object( $title ) ) {
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
		global $wgLang;
		wfProfileIn( __METHOD__ );

		$key = wfMemcKey( 'sidebar', $wgLang->getCode() );

		if ( $wgEnableSidebarCache ) {
			$cachedsidebar = $parserMemc->get( $key );
			if ( $cachedsidebar ) {
				wfProfileOut( __METHOD__ );
				return $cachedsidebar;
			}
		}

		$bar = array();
		$lines = explode( "\n", wfMsgForContent( 'sidebar' ) );
		$heading = '';
		foreach ($lines as $line) {
			if (strpos($line, '*') !== 0)
				continue;
			if (strpos($line, '**') !== 0) {
				$line = trim($line, '* ');
				$heading = $line;
				if( !array_key_exists($heading, $bar) ) $bar[$heading] = array();
			} else {
				if (strpos($line, '|') !== false) { // sanity check
					$line = array_map('trim', explode( '|' , trim($line, '* '), 2 ) );
					$link = wfMsgForContent( $line[0] );
					if ($link == '-')
						continue;
					if (wfEmptyMsg($line[1], $text = wfMsg($line[1])))
						$text = $line[1];
					if (wfEmptyMsg($line[0], $link))
						$link = $line[0];

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
						'id' => 'n-' . strtr($line[1], ' ', '-'),
						'active' => false
					);
				} else { continue; }
			}
		}
		wfRunHooks('SkinBuildSidebar', array($this, &$bar));
		if ( $wgEnableSidebarCache ) $parserMemc->set( $key, $bar, $wgSidebarCacheExpiry );
		wfProfileOut( __METHOD__ );
		return $bar;
	}
}
