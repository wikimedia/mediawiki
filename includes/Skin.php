<?php

/**
 *
 * @package MediaWiki
 * @subpackage Skins
 */

/**
 * This is not a valid entry point, perform no further processing unless MEDIAWIKI is defined
 */
if( defined( "MEDIAWIKI" ) ) {

# See skin.txt
require_once( 'Linker.php' );
require_once( 'Image.php' );

# Get a list of all skins available in /skins/
# Build using the regular expression '^(.*).php$'
# Array keys are all lower case, array value keep the case used by filename
#

$skinDir = dir($IP.'/skins');

# while code from www.php.net
while (false !== ($file = $skinDir->read())) {
	if(preg_match('/^([^.].*)\.php$/',$file, $matches)) {
		$aSkin = $matches[1];
		$wgValidSkinNames[strtolower($aSkin)] = $aSkin;
	}
}
$skinDir->close();
unset($matches);

require_once( 'RecentChange.php' );

/**
 * @todo document
 * @package MediaWiki
 */
class RCCacheEntry extends RecentChange
{
	var $secureName, $link;
	var $curlink , $difflink, $lastlink , $usertalklink , $versionlink ;
	var $userlink, $timestamp, $watched;

	function newFromParent( $rc )
	{
		$rc2 = new RCCacheEntry;
		$rc2->mAttribs = $rc->mAttribs;
		$rc2->mExtra = $rc->mExtra;
		return $rc2;
	}
} ;


/**
 * The main skin class that provide methods and properties for all other skins
 * including PHPTal skins.
 * This base class is also the "Standard" skin.
 * @package MediaWiki
 */
class Skin extends Linker {
	/**#@+
	 * @access private
	 */
	var $lastdate, $lastline;
	var $rc_cache ; # Cache for Enhanced Recent Changes
	var $rcCacheIndex ; # Recent Changes Cache Counter for visibility toggle
	var $rcMoveIndex;
	/**#@-*/

	/** Constructor, call parent constructor */
	function Skin() { parent::Linker(); }

	function getSkinNames() {
		global $wgValidSkinNames;
		return $wgValidSkinNames;
	}

	/** @return string path to the skin stylesheet */
	function getStylesheet() { return 'common/wikistandard.css'; }

	/** @return string skin name */
	function getSkinName() {
		return 'standard';
	}

	function qbSetting() {
		global $wgOut, $wgUser;

		if ( $wgOut->isQuickbarSuppressed() ) { return 0; }
		$q = $wgUser->getOption( 'quickbar' );
		if ( '' == $q ) { $q = 0; }
		return $q;
	}

	function initPage( &$out ) {
		$fname = 'Skin::initPage';
		wfProfileIn( $fname );

		$out->addLink( array( 'rel' => 'shortcut icon', 'href' => '/favicon.ico' ) );

		$this->addMetadataLinks($out);

		wfProfileOut( $fname );
	}

	function addMetadataLinks( &$out ) {
		global $wgTitle, $wgEnableDublinCoreRdf, $wgEnableCreativeCommonsRdf, $wgRdfMimeType, $action;
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

	function outputPage( &$out ) {
		global $wgDebugComments;

		wfProfileIn( 'Skin::outputPage' );
		$this->initPage( $out );

		$out->out( $out->headElement() );

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

		wfProfileClose();
		$out->out( $out->reportTime() );

		$out->out( "\n</body></html>" );
	}

	function getHeadScripts() {
		global $wgStylePath, $wgUser, $wgContLang, $wgAllowUserJs, $wgJsMimeType;
		$r = "<script type=\"{$wgJsMimeType}\" src=\"{$wgStylePath}/common/wikibits.js\"></script>\n";
		if( $wgAllowUserJs && $wgUser->isLoggedIn() ) {
			$userpage = $wgUser->getUserPage();
			$userjs = htmlspecialchars( $this->makeUrl(
				$userpage->getPrefixedText().'/'.$this->getSkinName().'.js',
				'action=raw&ctype='.$wgJsMimeType));
			$r .= '<script type="'.$wgJsMimeType.'" src="'.$userjs."\"></script>\n";
		}
		return $r;
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
	 * @access private
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

	# get the user/site-specific stylesheet, SkinPHPTal called from RawPage.php (settings are cached that way)
	function getUserStylesheet() {
		global $wgOut, $wgStylePath, $wgContLang, $wgUser, $wgRequest, $wgTitle, $wgAllowUserCss;
		$sheet = $this->getStylesheet();
		$action = $wgRequest->getText('action');
		$s = "@import \"$wgStylePath/$sheet\";\n";
		if($wgContLang->isRTL()) $s .= "@import \"$wgStylePath/common/common_rtl.css\";\n";
		if( $wgAllowUserCss && $wgUser->isLoggedIn() ) { # logged in
			if($wgTitle->isCssSubpage() && $this->userCanPreview( $action ) ) {
				$s .= $wgRequest->getText('wpTextbox1');
			} else {
				$userpage = $wgUser->getUserPage();
				$s.= '@import "'.$this->makeUrl(
					$userpage->getPrefixedText().'/'.$this->getSkinName().'.css',
					'action=raw&ctype=text/css').'";'."\n";
			}
		}
		$s .= $this->doGetUserStyles();
		return $s."\n";
	}

	/**
	 * placeholder, returns generated js in monobook
	 */
	function getUserJs() { return; }

	/**
	 * Return html code that include User stylesheets
	 */
	function getUserStyles() {
		global $wgOut, $wgStylePath, $wgLang;
		$s = "<style type='text/css'>\n";
		$s .= "/*/*/ /*<![CDATA[*/\n"; # <-- Hide the styles from Netscape 4 without hiding them from IE/Mac
		$s .= $this->getUserStylesheet();
		$s .= "/*]]>*/ /* */\n";
		$s .= "</style>\n";
		return $s;
	}

	/**
	 * Some styles that are set by user through the user settings interface.
	 */
	function doGetUserStyles() {
		global $wgUser, $wgContLang;

		$csspage = $wgContLang->getNsText( NS_MEDIAWIKI ) . ':' . $this->getSkinName() . '.css';
		$s = '@import "'.$this->makeUrl($csspage, 'action=raw&ctype=text/css')."\";\n";

		return $s . $this->reallyDoGetUserStyles();
	}

	function reallyDoGetUserStyles() {
		global $wgUser;
		$s = '';
		$underline = $wgUser->getOption( "underline" ) ? 'underline' : 'none';
		$s .= "a { text-decoration: $underline; }\n";
		if( $wgUser->getOption( 'highlightbroken' ) ) {
			$s .= "a.new, #quickbar a.new { color: #CC2200; }\n";
		} else {
			$s .= <<<END
a.new, #quickbar a.new,
a.stub, #quickbar a.stub {
	color: inherit;
	text-decoration: inherit;
}
a.new:after, #quickbar a.new:after {
	content: "?";
	color: #CC2200;
	text-decoration: $underline;
}
a.stub:after, #quickbar a.stub:after {
	content: "!";
	color: #772233;
	text-decoration: $underline;
}
END;
		}
		if( $wgUser->getOption( 'justify' ) ) {
			$s .= "#article, #bodyContent { text-align: justify; }\n";
		}
		if( !$wgUser->getOption( 'showtoc' ) ) {
			$s .= "#toc { display: none; }\n";
		}
		return $s;
	}

	function getBodyOptions() {
		global $wgUser, $wgTitle, $wgNamespaceBackgrounds, $wgOut, $wgRequest;

		extract( $wgRequest->getValues( 'oldid', 'redirect', 'diff' ) );

		if ( 0 != $wgTitle->getNamespace() ) {
			$a = array( 'bgcolor' => '#ffffec' );
		}
		else $a = array( 'bgcolor' => '#FFFFFF' );
		if($wgOut->isArticle() && $wgUser->getOption('editondblclick') &&
		  (!$wgTitle->isProtected() || $wgUser->isAllowed('protect')) ) {
			$t = wfMsg( 'editthispage' );
			$oid = $red = '';
			if ( !empty($redirect) && $redirect == 'no' ) {
				$red = "&redirect={$redirect}";
			}
			if ( !empty($oldid) && ! isset( $diff ) ) {
				$oid = "&oldid=" . IntVal( $oldid );
			}
			$s = $wgTitle->getFullURL( "action=edit{$oid}{$red}" );
			$s = 'document.location = "' .$s .'";';
			$a += array ('ondblclick' => $s);

		}
		$a['onload'] = $wgOut->getOnloadHandler();
		if( $wgUser->getOption( 'editsectiononrightclick' ) ) {
			if( $a['onload'] != '' ) {
				$a['onload'] .= ';';
			}
			$a['onload'] .= 'setupRightClickEdit()';
		}
		return $a;
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
		global $wgOut, $wgTitle, $wgContLang;
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

		$shove = ($qb != 0);
		$left = ($qb == 1 || $qb == 3);
		if($wgContLang->isRTL()) $left = !$left;

		if ( !$shove ) {
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


	function getCategoryLinks () {
		global $wgOut, $wgTitle, $wgParser;
		global $wgUseCategoryMagic, $wgUseCategoryBrowser, $wgLang;

		if( !$wgUseCategoryMagic ) return '' ;
		if( count( $wgOut->mCategoryLinks ) == 0 ) return '';

		# Taken out so that they will be displayed in previews -- TS
		#if( !$wgOut->isArticle() ) return '';

		$t = implode ( ' | ' , $wgOut->mCategoryLinks ) ;
		$s = $this->makeKnownLinkObj( Title::makeTitle( NS_SPECIAL, 'Categories' ),
			wfMsg( 'categories' ), 'article=' . urlencode( $wgTitle->getPrefixedDBkey() ) )
			. ': ' . $t;

		# optional 'dmoz-like' category browser. Will be shown under the list
		# of categories an article belong to
		if($wgUseCategoryBrowser) {
			$s .= '<br /><hr />';

			# get a big array of the parents tree
			$parenttree = $wgTitle->getParentCategoryTree();

			# Skin object passed by reference cause it can not be
			# accessed under the method subfunction walkThrough.
			$s .= Skin::drawCategoryBrowser($parenttree, $this);
		}

		return $s;
	}

	# Render the array as a serie of links
	function drawCategoryBrowser ($tree, &$skin) {
		$return = '';
		foreach ($tree as $element => $parent) {
			if (empty($parent)) {
				# element start a new list
				$return .= '<br />';
			} else {
				# grab the others elements
				$return .= Skin::drawCategoryBrowser($parent, $skin);
			}
			# add our current element to the list
			$eltitle = Title::NewFromText($element);
			if (!empty($parent)) $return .= ' &gt; ';
			$return .=  $skin->makeLinkObj( $eltitle, $eltitle->getText() ) ;
		}
		return $return;
	}

	function getCategories() {
		$catlinks=$this->getCategoryLinks();
		if(!empty($catlinks)) {
			return "<p class='catlinks'>{$catlinks}</p>";
		}
	}

	function getQuickbarCompensator( $rows = 1 ) {
		return "<td width='152' rowspan='{$rows}'>&nbsp;</td>";
	}

	/**
	 * This gets called immediately before the </body> tag.
	 * @return string HTML to be put after </body> ???
	 */
	function afterContent() {
		$printfooter = "<div class=\"printfooter\">\n" . $this->printFooter() . "</div>\n";
		return $printfooter . $this->doAfterContent();
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
	function doAfterContent() { }

	function pageTitleLinks() {
		global $wgOut, $wgTitle, $wgUser, $wgContLang, $wgUseApproval, $wgRequest;

		extract( $wgRequest->getValues( 'oldid', 'diff' ) );
		$action = $wgRequest->getText( 'action' );

		$s = $this->printableLink();
		$disclaimer = $this->disclaimerLink(); # may be empty
		if( $disclaimer ) {
			$s .= ' | ' . $disclaimer;
		}

		if ( $wgOut->isArticleRelated() ) {
			if ( $wgTitle->getNamespace() == NS_IMAGE ) {
				$name = $wgTitle->getDBkey();
				$image = new Image( $wgTitle );
				if( $image->exists() ) {
					$link = htmlspecialchars( $image->getURL() );
					$style = $this->getInternalLinkAttributes( $link, $name );
					$s .= " | <a href=\"{$link}\"{$style}>{$name}</a>";
				}
			}
			# This will show the "Approve" link if $wgUseApproval=true;
			if ( isset ( $wgUseApproval ) && $wgUseApproval )
			{
				$t = $wgTitle->getDBkey();
				$name = 'Approve this article' ;
				$link = "http://test.wikipedia.org/w/magnus/wiki.phtml?title={$t}&action=submit&doit=1" ;
				#htmlspecialchars( wfImageUrl( $name ) );
				$style = $this->getExternalLinkAttributes( $link, $name );
				$s .= " | <a href=\"{$link}\"{$style}>{$name}</a>" ;
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
				$tl = $this->makeKnownLinkObj( $wgUser->getTalkPage(),
						wfMsg('newmessageslink') );
				$s.= ' | <strong>'. wfMsg( 'newmessages', $tl ) . '</strong>';
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
		global $wgUser, $wgTitle, $wgContLang, $action;
		if( $wgUser->isAllowed('rollback') &&
			(($wgTitle->getArticleId() == 0) || ($action == "history")) &&
			($n = $wgTitle->isDeleted() ) ) {
			return wfMsg( 'thisisdeleted',
				$this->makeKnownLink(
					$wgContLang->SpecialPage( 'Undelete/' . $wgTitle->getPrefixedDBkey() ),
					wfMsg( 'restorelink', $n ) ) );
		}
		return '';
	}

	function printableLink() {
		global $wgOut, $wgFeedClasses, $wgRequest;

		$baseurl = $_SERVER['REQUEST_URI'];
		if( strpos( '?', $baseurl ) == false ) {
			$baseurl .= '?';
		} else {
			$baseurl .= '&';
		}
		$baseurl = htmlspecialchars( $baseurl );
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
		global $wgOut, $wgTitle, $wgUser;

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
		global $wgOut,$wgTitle,$wgNamespacesWithSubpages;
		$subpages = '';
		if($wgOut->isArticle() && !empty($wgNamespacesWithSubpages[$wgTitle->getNamespace()])) {
			$ptext=$wgTitle->getPrefixedText();
			if(preg_match('/\//',$ptext)) {
				$links = explode('/',$ptext);
				$c = 0;
				$growinglink = '';
				foreach($links as $link) {
					$c++;
					if ($c<count($links)) {
						$growinglink .= $link;
						$getlink = $this->makeLink( $growinglink, $link );
						if(preg_match('/class="new"/i',$getlink)) { break; } # this is a hack, but it saves time
						if ($c>1) {
							$subpages .= ' | ';
						} else  {
							$subpages .= '&lt; ';
						}
						$subpages .= $getlink;
						$growinglink .= '/';
					}
				}
			}
		}
		return $subpages;
	}

	function nameAndLogin() {
		global $wgUser, $wgTitle, $wgLang, $wgContLang, $wgShowIPinHeader, $wgIP;

		$li = $wgContLang->specialPage( 'Userlogin' );
		$lo = $wgContLang->specialPage( 'Userlogout' );

		$s = '';
		if ( $wgUser->isAnon() ) {
			if( $wgShowIPinHeader && isset( $_COOKIE[ini_get('session.name')] ) ) {
				$n = $wgIP;

				$tl = $this->makeKnownLinkObj( $wgUser->getTalkPage(),
				  $wgContLang->getNsText( NS_TALK ) );

				$s .= $n . ' ('.$tl.')';
			} else {
				$s .= wfMsg('notloggedin');
			}

			$rt = $wgTitle->getPrefixedURL();
			if ( 0 == strcasecmp( urlencode( $lo ), $rt ) ) {
				$q = '';
			} else { $q = "returnto={$rt}"; }

			$s .= "\n<br />" . $this->makeKnownLinkObj(
				Title::makeTitle( NS_SPECIAL, 'Userlogin' ),
				wfMsg( 'login' ), $q );
		} else {
			$n = $wgUser->getName();
			$rt = $wgTitle->getPrefixedURL();
			$tl = $this->makeKnownLinkObj( $wgUser->getTalkPage(),
			  $wgContLang->getNsText( NS_TALK ) );

			$tl = " ({$tl})";

			$s .= $this->makeKnownLinkObj( $wgUser->getUserPage(),
			  $n ) . "{$tl}<br />" .
			  $this->makeKnownLinkObj( Title::makeTitle( NS_SPECIAL, 'Userlogout' ), wfMsg( 'logout' ),
			  "returnto={$rt}" ) . ' | ' .
			  $this->specialLink( 'preferences' );
		}
		$s .= ' | ' . $this->makeKnownLink( wfMsgForContent( 'helppage' ),
		  wfMsg( 'help' ) );

		return $s;
	}

	function getSearchLink() {
		$searchPage =& Title::makeTitle( NS_SPECIAL, 'Search' );
		return $searchPage->getLocalURL();
	}

	function escapeSearchLink() {
		return htmlspecialchars( $this->getSearchLink() );
	}

	function searchForm() {
		global $wgRequest;
		$search = $wgRequest->getText( 'search' );

		$s = '<form name="search" class="inline" method="post" action="'
		  . $this->escapeSearchLink() . "\">\n"
		  . '<input type="text" name="search" size="19" value="'
		  . htmlspecialchars(substr($search,0,256)) . "\" />\n"
		  . '<input type="submit" name="go" value="' . wfMsg ('go') . '" />&nbsp;'
		  . '<input type="submit" name="fulltext" value="' . wfMsg ('search') . "\" />\n</form>";

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

		/* show links to different language variants */
		global $wgDisableLangConversion, $wgContLang, $wgTitle;
		$variants = $wgContLang->getVariants();
		if( !$wgDisableLangConversion && sizeof( $variants ) > 1 ) {
			foreach( $variants as $code ) {
				$varname = $wgContLang->getVariantname( $code );
				if( $varname == 'disable' )
					continue;
				$s .= ' | <a href="' . $wgTitle->getLocalUrl( 'variant=' . $code ) . '">' . $varname . '</a>';
			}
		}

		return $s;
	}

	function bottomLinks() {
		global $wgOut, $wgUser, $wgTitle;
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

		extract( $wgRequest->getValues( 'oldid', 'diff' ) );
		if ( ! $wgOut->isArticle() ) { return ''; }
		if ( isset( $oldid ) || isset( $diff ) ) { return ''; }
		if ( 0 == $wgArticle->getID() ) { return ''; }

		$s = '';
		if ( !$wgDisableCounters ) {
			$count = $wgLang->formatNum( $wgArticle->getCount() );
			if ( $count ) {
				$s = wfMsg( 'viewcount', $count );
			}
		}

	        if (isset($wgMaxCredits) && $wgMaxCredits != 0) {
		    require_once('Credits.php');
		    $s .= ' ' . getCredits($wgArticle, $wgMaxCredits, $wgShowCreditsIfMax);
		} else {
		    $s .= $this->lastModified();
		}

		if ($wgPageShowWatchingUsers && $wgUser->getOption( 'shownumberswatching' )) {
			$dbr =& wfGetDB( DB_SLAVE );
			extract( $dbr->tableNames( 'watchlist' ) );
			$sql = "SELECT COUNT(*) AS n FROM $watchlist
				WHERE wl_title='" . $dbr->strencode($wgTitle->getDBKey()) .
				"' AND  wl_namespace=" . $wgTitle->getNamespace() ;
			$res = $dbr->query( $sql, 'Skin::pageStats');
			$x = $dbr->fetchObject( $res );
			$s .= ' ' . wfMsg('number_of_watching_users_pageview', $x->n );
		}

		return $s . ' ' .  $this->getCopyright();
	}

	function getCopyright() {
		global $wgRightsPage, $wgRightsUrl, $wgRightsText, $wgRequest;


		$oldid = $wgRequest->getVal( 'oldid' );
		$diff = $wgRequest->getVal( 'diff' );

		if ( !is_null( $oldid ) && is_null( $diff ) && wfMsgForContent( 'history_copyright' ) !== '-' ) {
			$msg = 'history_copyright';
		} else {
			$msg = 'copyright';
		}

		$out = '';
		if( $wgRightsPage ) {
			$link = $this->makeKnownLink( $wgRightsPage, $wgRightsText );
		} elseif( $wgRightsUrl ) {
			$link = $this->makeExternalLink( $wgRightsUrl, $wgRightsText );
		} else {
			# Give up now
			return $out;
		}
		$out .= wfMsgForContent( $msg, $link );
		return $out;
	}

	function getCopyrightIcon() {
		global $wgRightsPage, $wgRightsUrl, $wgRightsText, $wgRightsIcon, $wgCopyrightIcon;
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
		$img = '<a href="http://www.mediawiki.org/"><img src="'.$url.'" alt="MediaWiki" /></a>';
		return $img;
	}

	function lastModified() {
		global $wgLang, $wgArticle, $wgLoadBalancer;

		$timestamp = $wgArticle->getTimestamp();
		if ( $timestamp ) {
			$d = $wgLang->timeanddate( $timestamp, true );
			$s = ' ' . wfMsg( 'lastmodified', $d );
		} else {
			$s = '';
		}
		if ( $wgLoadBalancer->getLaggedSlaveMode() ) {
			$s .= ' <strong>' . wfMsg( 'laggedslavemode' ) . '</strong>';
		}
		return $s;
	}

	function logoText( $align = '' ) {
		if ( '' != $align ) { $a = " align='{$align}'"; }
		else { $a = ''; }

		$mp = wfMsg( 'mainpage' );
		$titleObj = Title::newFromText( $mp );
		if ( is_object( $titleObj ) ) {
			$url = $titleObj->escapeLocalURL();
		} else {
			$url = '';
		}

		$logourl = $this->getLogo();
		$s = "<a href='{$url}'><img{$a} src='{$logourl}' alt='[{$mp}]' /></a>";
		return $s;
	}

	/**
	 * show a drop-down box of special pages
	 * @TODO crash bug913. Need to be rewrote completly.
	 */
	function specialPagesList() {
		global $wgUser, $wgOut, $wgContLang, $wgServer, $wgRedirectScript, $wgAvailableRights;
		require_once('SpecialPage.php');
		$a = array();
		$pages = SpecialPage::getPages();

		// special pages without access restriction
		foreach ( $pages[''] as $name => $page ) {
			$a[$name] = $page->getDescription();
		}

		// Other special pages that are restricted.
		// Copied from SpecialSpecialpages.php
		foreach($wgAvailableRights as $right) {
			if( $wgUser->isAllowed($right) ) {
				/** Add all pages for this right */
				if(isset($pages[$right])) {
					foreach($pages[$right] as $name => $page) {
					$a[$name] = $page->getDescription();
					}
				}
			}
		}

		$go = wfMsg( 'go' );
		$sp = wfMsg( 'specialpages' );
		$spp = $wgContLang->specialPage( 'Specialpages' );

		$s = '<form id="specialpages" method="get" class="inline" ' .
		  'action="' . htmlspecialchars( "{$wgServer}{$wgRedirectScript}" ) . "\">\n";
		$s .= "<select name=\"wpDropdown\">\n";
		$s .= "<option value=\"{$spp}\">{$sp}</option>\n";


		foreach ( $a as $name => $desc ) {
			$p = $wgContLang->specialPage( $name );
			$s .= "<option value=\"{$p}\">{$desc}</option>\n";
		}
		$s .= "</select>\n";
		$s .= "<input type='submit' value=\"{$go}\" name='redirect' />\n";
		$s .= "</form>\n";
		return $s;
	}

	function mainPageLink() {
		$mp = wfMsgForContent( 'mainpage' );
		$mptxt = wfMsg( 'mainpage');
		$s = $this->makeKnownLink( $mp, $mptxt );
		return $s;
	}

	function copyrightLink() {
		$s = $this->makeKnownLink( wfMsgForContent( 'copyrightpage' ),
		  wfMsg( 'copyrightpagename' ) );
		return $s;
	}

	function aboutLink() {
		$s = $this->makeKnownLink( wfMsgForContent( 'aboutpage' ),
		  wfMsg( 'aboutsite' ) );
		return $s;
	}


	function disclaimerLink() {
		$disclaimers = wfMsg( 'disclaimers' );
		if ($disclaimers == '-') {
			return '';
		} else {
			return $this->makeKnownLink( wfMsgForContent( 'disclaimerpage' ),
			                             $disclaimers );
		}
	}

	function editThisPage() {
		global $wgOut, $wgTitle, $wgRequest;

		$oldid = $wgRequest->getVal( 'oldid' );
		$diff = $wgRequest->getVal( 'diff' );
		$redirect = $wgRequest->getVal( 'redirect' );

		if ( ! $wgOut->isArticleRelated() ) {
			$s = wfMsg( 'protectedpage' );
		} else {
			if ( $wgTitle->userCanEdit() ) {
				$t = wfMsg( 'editthispage' );
			} else {
				$t = wfMsg( 'viewsource' );
			}
			$oid = $red = '';

			if ( !is_null( $redirect ) ) { $red = "&redirect={$redirect}"; }
			if ( $oldid && ! isset( $diff ) ) {
				$oid = '&oldid='.$oldid;
			}
			$s = $this->makeKnownLinkObj( $wgTitle, $t, "action=edit{$oid}{$red}" );
		}
		return $s;
	}

	function deleteThisPage() {
		global $wgUser, $wgOut, $wgTitle, $wgRequest;

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
		global $wgUser, $wgOut, $wgTitle, $wgRequest;

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
		global $wgUser, $wgOut, $wgTitle;

		if ( $wgOut->isArticleRelated() ) {
			if ( $wgTitle->userIsWatching() ) {
				$t = wfMsg( 'unwatchthispage' );
				$q = 'action=unwatch';
			} else {
				$t = wfMsg( 'watchthispage' );
				$q = 'action=watch';
			}
			$s = $this->makeKnownLinkObj( $wgTitle, $t, $q );
		} else {
			$s = wfMsg( 'notanarticle' );
		}
		return $s;
	}

	function moveThisPage() {
		global $wgTitle;

		if ( $wgTitle->userCanMove() ) {
			return $this->makeKnownLinkObj( Title::makeTitle( NS_SPECIAL, 'Movepage' ),
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

		return $this->makeKnownLinkObj( Title::makeTitle( NS_SPECIAL, 'Whatlinkshere' ),
		  wfMsg( 'whatlinkshere' ), 'target=' . $wgTitle->getPrefixedURL() );
	}

	function userContribsLink() {
		global $wgTitle;

		return $this->makeKnownLinkObj( Title::makeTitle( NS_SPECIAL, 'Contributions' ),
		  wfMsg( 'contributions' ), 'target=' . $wgTitle->getPartialURL() );
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

		return $this->makeKnownLinkObj( Title::makeTitle( NS_SPECIAL, 'Emailuser' ),
		  wfMsg( 'emailuser' ), 'target=' . $wgTitle->getPartialURL() );
	}

	function watchPageLinksLink() {
		global $wgOut, $wgTitle;

		if ( ! $wgOut->isArticleRelated() ) {
			return '(' . wfMsg( 'notanarticle' ) . ')';
		} else {
			return $this->makeKnownLinkObj( Title::makeTitle( NS_SPECIAL,
			  'Recentchangeslinked' ), wfMsg( 'recentchangeslinked' ),
			  'target=' . $wgTitle->getPrefixedURL() );
		}
	}

	function otherLanguages() {
		global $wgOut, $wgContLang, $wgTitle, $wgHideInterlanguageLinks;

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

	function dateLink() {
		global $wgLinkCache;
		$t1 = Title::newFromText( gmdate( 'F j' ) );
		$t2 = Title::newFromText( gmdate( 'Y' ) );

		$wgLinkCache->suspend();
		$id = $t1->getArticleID();
		$wgLinkCache->resume();

		if ( 0 == $id ) {
			$s = $this->makeBrokenLink( $t1->getText() );
		} else {
			$s = $this->makeKnownLink( $t1->getText() );
		}
		$s .= ', ';

		$wgLinkCache->suspend();
		$id = $t2->getArticleID();
		$wgLinkCache->resume();

		if ( 0 == $id ) {
			$s .= $this->makeBrokenLink( $t2->getText() );
		} else {
			$s .= $this->makeKnownLink( $t2->getText() );
		}
		return $s;
	}

	function talkLink() {
		global $wgTitle, $wgLinkCache;

		if ( NS_SPECIAL == $wgTitle->getNamespace() ) {
			# No discussion links for special pages
			return '';
		}

		if( $wgTitle->isTalkPage() ) {
			$link = $wgTitle->getSubjectPage();
			switch( $link->getNamespace() ) {
			case NS_MAIN:
				$text = wfMsg('articlepage');
				break;
			case NS_USER:
				$text = wfMsg('userpage');
				break;
			case NS_PROJECT:
				$text = wfMsg('wikipediapage');
				break;
			case NS_IMAGE:
				$text = wfMsg('imagepage');
				break;
			default:
				$text= wfMsg('articlepage');
			}
		} else {
			$link = $wgTitle->getTalkPage();
			$text = wfMsg( 'talkpage' );
		}

		$wgLinkCache->suspend();
		$s = $this->makeLinkObj( $link, $text );
		$wgLinkCache->resume();

		return $s;
	}

	function commentLink() {
		global $wgContLang, $wgTitle, $wgLinkCache;

		if ( $wgTitle->getNamespace() == NS_SPECIAL ) {
			return '';
		}
		return $this->makeKnownLinkObj( $wgTitle->getTalkPage(),
			wfMsg( 'postcomment' ), 'action=edit&section=new' );
	}

	/* these are used extensively in SkinPHPTal, but also some other places */
	/*static*/ function makeSpecialUrl( $name, $urlaction='' ) {
		$title = Title::makeTitle( NS_SPECIAL, $name );
		return $title->getLocalURL( $urlaction );
	}

	/*static*/ function makeI18nUrl ( $name, $urlaction='' ) {
		$title = Title::newFromText( wfMsgForContent($name) );
		$this->checkTitle($title, $name);
		return $title->getLocalURL( $urlaction );
	}

	/*static*/ function makeUrl ( $name, $urlaction='' ) {
		$title = Title::newFromText( $name );
		$this->checkTitle($title, $name);
		return $title->getLocalURL( $urlaction );
	}

	# If url string starts with http, consider as external URL, else
	# internal
	/*static*/ function makeInternalOrExternalUrl( $name ) {
		if ( strncmp( $name, 'http', 4 ) == 0 ) {
			return $name;
		} else {
			return $this->makeUrl( $name );
		}
	}

	# this can be passed the NS number as defined in Language.php
	/*static*/ function makeNSUrl( $name, $urlaction='', $namespace=NS_MAIN ) {
		$title = Title::makeTitleSafe( $namespace, $name );
		$this->checkTitle($title, $name);
		return $title->getLocalURL( $urlaction );
	}

	/* these return an array with the 'href' and boolean 'exists' */
	/*static*/ function makeUrlDetails ( $name, $urlaction='' ) {
		$title = Title::newFromText( $name );
		$this->checkTitle($title, $name);
		return array(
			'href' => $title->getLocalURL( $urlaction ),
			'exists' => $title->getArticleID() != 0?true:false
		);
	}

	# make sure we have some title to operate on
	/*static*/ function checkTitle ( &$title, &$name ) {
		if(!is_object($title)) {
			$title = Title::newFromText( $name );
			if(!is_object($title)) {
				$title = Title::newFromText( '--error: link target missing--' );
			}
		}
	}

	/**
	 * Build an array that represents the sidebar(s), the navigation bar among them
	 *
	 * @return array
	 * @access private
	 */
	function buildSidebar() {
		$fname = 'SkinTemplate::buildSidebar';
		wfProfileIn( $fname );

		$bar = array();
		$lines = explode( "\n", wfMsgForContent( 'sidebar' ) );
		foreach ($lines as $line) {
			if (strpos($line, '*') !== 0)
				continue;
			if (strpos($line, '**') !== 0) {
				$line = trim($line, '* ');
				$heading = $line;
			} else {
				if (strpos($line, '|') !== false) { // sanity check
					$line = explode( '|' , trim($line, '* '), 2 );
					$link = wfMsgForContent( $line[0] );
					if ($link == '-')
						continue;
					$bar[$heading][] = array(
						'text' => wfMsg( $line[1] ),
						'href' => $this->makeInternalOrExternalUrl( $link ),
						'id' => 'n-' . $line[1],
					);
				} else { continue; }
			}
		}

		wfProfileOut( $fname );
		return $bar;
	}
}

}
?>
