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

# See skin.doc
require_once( 'Image.php' );

# Get a list of all skins available in /skins/
# Build using the regular expression '^(.*).php$'
# Array keys are all lower case, array value keep the case used by filename
#

$skinDir = dir($IP.'/skins');

# while code from www.php.net
while (false !== ($file = $skinDir->read())) {
	if(preg_match('/^(.*).php$/',$file, $matches)) {
		$aSkin = $matches[1];
	$wgValidSkinNames[strtolower($aSkin)] = $aSkin;
	}
}
$skinDir->close();
unset($matches);

require_once( 'RecentChange.php' );

global $wgLinkHolders;
$wgLinkHolders = array(
	'namespaces' => array(),
	'dbkeys' => array(),
	'queries' => array(),
	'texts' => array(),
	'titles' => array()
);
global $wgInterwikiLinkHolders;
$wgInterwikiLinkHolders = array();

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
class Skin {
	/**#@+
	 * @access private
	 */
	var $lastdate, $lastline;
	var $linktrail ; # linktrail regexp
	var $rc_cache ; # Cache for Enhanced Recent Changes
	var $rcCacheIndex ; # Recent Changes Cache Counter for visibility toggle
	var $rcMoveIndex;
	var $postParseLinkColour = false;
	/**#@-*/

	function Skin() {
		$this->linktrail = wfMsg('linktrail');
	}

	function getSkinNames() {
		global $wgValidSkinNames;
		return $wgValidSkinNames;
	}

	function getStylesheet() {
		return 'common/wikistandard.css';
	}

	function getSkinName() {
		return 'standard';
	}

	/**
	 * Get/set accessor for delayed link colouring
	 */
	function postParseLinkColour( $setting = NULL ) {
		return wfSetVar( $this->postParseLinkColour, $setting );
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
		global $wgStylePath, $wgUser, $wgContLang, $wgAllowUserJs;
		$r = "<script type=\"text/javascript\" src=\"{$wgStylePath}/common/wikibits.js\"></script>\n";
		if( $wgAllowUserJs && $wgUser->getID() != 0 ) { # logged in
			$userpage = $wgContLang->getNsText( Namespace::getUser() ) . ":" . $wgUser->getName();
			$userjs = htmlspecialchars($this->makeUrl($userpage.'/'.$this->getSkinName().'.js', 'action=raw&ctype=text/javascript'));
			$r .= '<script type="text/javascript" src="'.$userjs."\"></script>\n";
		}
		return $r;
	}

	# get the user/site-specific stylesheet, SkinPHPTal called from RawPage.php (settings are cached that way)
	function getUserStylesheet() {
		global $wgOut, $wgStylePath, $wgContLang, $wgUser, $wgRequest, $wgTitle, $wgAllowUserCss;
		$sheet = $this->getStylesheet();
		$action = $wgRequest->getText('action');
		$s = "@import \"$wgStylePath/$sheet\";\n";
		if($wgContLang->isRTL()) $s .= "@import \"$wgStylePath/common/common_rtl.css\";\n";
		if( $wgAllowUserCss && $wgUser->getID() != 0 ) { # logged in
			if($wgTitle->isCssSubpage() and $action == 'submit' and  $wgTitle->userCanEditCssJsSubpage()) {
				$s .= $wgRequest->getText('wpTextbox1');
			} else {
				$userpage = $wgContLang->getNsText( Namespace::getUser() ) . ":" . $wgUser->getName();
				$s.= '@import "'.$this->makeUrl($userpage.'/'.$this->getSkinName().'.css', 'action=raw&ctype=text/css').'";'."\n";
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

		if ( 1 == $wgUser->getOption( 'underline' ) ) {
			# Don't override browser settings
		} else {
			# CHECK MERGE @@@
			# Force no underline
			$s .= "a { text-decoration: none; }\n";
		}
		if ( 1 == $wgUser->getOption( 'highlightbroken' ) ) {
			$s .= "a.new, #quickbar a.new { color: #CC2200; }\n";
		}
		if ( 1 == $wgUser->getOption( 'justify' ) ) {
			$s .= "#article { text-align: justify; }\n";
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
		return $a;
	}

	function getExternalLinkAttributes( $link, $text, $class='' ) {
		global $wgUser, $wgOut, $wgContLang;

		$same = ($link == $text);
		$link = urldecode( $link );
		$link = $wgContLang->checkTitleEncoding( $link );
		$link = str_replace( '_', ' ', $link );
		$link = htmlspecialchars( $link );

		$r = ($class != '') ? " class='$class'" : " class='external'";

		if ( !$same && $wgUser->getOption( 'hover' ) ) {
			$r .= " title=\"{$link}\"";
		}
		return $r;
	}

	function getInternalLinkAttributes( $link, $text, $broken = false ) {
		global $wgUser, $wgOut;

		$link = urldecode( $link );
		$link = str_replace( '_', ' ', $link );
		$link = htmlspecialchars( $link );

		if ( $broken == 'stub' ) {
			$r = ' class="stub"';
		} else if ( $broken == 'yes' ) {
			$r = ' class="new"';
		} else {
			$r = '';
		}

		if ( 1 == $wgUser->getOption( 'hover' ) ) {
			$r .= " title=\"{$link}\"";
		}
		return $r;
	}

	/**
	 * @param bool $broken
	 */
	function getInternalLinkAttributesObj( &$nt, $text, $broken = false ) {
		global $wgUser, $wgOut;

		if ( $broken == 'stub' ) {
			$r = ' class="stub"';
		} else if ( $broken == 'yes' ) {
			$r = ' class="new"';
		} else {
			$r = '';
		}

		if ( 1 == $wgUser->getOption( 'hover' ) ) {
			$r .= ' title="' . $nt->getEscapedText() . '"';
		}
		return $r;
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
		global $wgUser, $wgOut;

		return $this->doBeforeContent();
	}

	function doBeforeContent() {
		global $wgUser, $wgOut, $wgTitle, $wgContLang, $wgSiteNotice;
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

		if( $wgSiteNotice ) {
			$s .= "\n<div id='siteNotice'>$wgSiteNotice</div>\n";
		}
		$s .= $this->pageTitle();
		$s .= $this->pageSubtitle() ;
		$s .= $this->getCategories();
		wfProfileOut( $fname );
		return $s;
	}

	
	function getCategoryLinks () {
		global $wgOut, $wgTitle, $wgUser, $wgParser;
		global $wgUseCategoryMagic, $wgUseCategoryBrowser, $wgLang;

		if( !$wgUseCategoryMagic ) return '' ;
		if( count( $wgOut->mCategoryLinks ) == 0 ) return '';

		# Taken out so that they will be displayed in previews -- TS
		#if( !$wgOut->isArticle() ) return '';

		$t = implode ( ' | ' , $wgOut->mCategoryLinks ) ;
		$s = $this->makeKnownLink( 'Special:Categories',
			wfMsg( 'categories' ), 'article=' . urlencode( $wgTitle->getPrefixedDBkey() ) )
			. ': ' . $t;

		# optional 'dmoz-like' category browser. Will be shown under the list
		# of categories an article belong to
		if($wgUseCategoryBrowser) {
			$s .= '<br/><hr/>';

			# get a big array of the parents tree
			$parenttree = $wgTitle->getParentCategoryTree();

			# Render the array as a serie of links
			function walkThrough ($tree) {
				global $wgUser;
				$sk = $wgUser->getSkin();
				$return = '';
				foreach($tree as $element => $parent) {
					if(empty($parent)) {
						# element start a new list
						$return .= '<br />';
					} else {
						# grab the others elements
						$return .= walkThrough($parent);
					}
					# add our current element to the list
					$eltitle = Title::NewFromText($element);
					# FIXME : should be makeLink() [AV]
					$return .= $sk->makeLink($element, $eltitle->getText()).' &gt; ';
				}
				return $return;
			}

			$s .= walkThrough($parenttree);
		}

		return $s;
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

	# This gets called immediately before the </body> tag.
	#
	function afterContent() {
		global $wgUser, $wgOut, $wgServer;
		global $wgTitle, $wgLang;

		$printfooter = "<div class=\"printfooter\">\n" . $this->printFooter() . "</div>\n";
		return $printfooter . $this->doAfterContent();
	}

	function printSource() {
		global $wgTitle;
		$url = htmlspecialchars( $wgTitle->getFullURL() );
		return wfMsg( "retrievedfrom", "<a href=\"$url\">$url</a>" );
	}

	function printFooter() {
		return "<p>" .  $this->printSource() .
			"</p>\n\n<p>" . $this->pageStats() . "</p>\n";
	}

	function doAfterContent() {
	# overloaded by derived classes
	}

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
			if ( $wgTitle->getNamespace() == Namespace::getImage() ) {
				$name = $wgTitle->getDBkey();
				$link = htmlspecialchars( Image::wfImageUrl( $name ) );
				$style = $this->getInternalLinkAttributes( $link, $name );
				$s .= " | <a href=\"{$link}\"{$style}>{$name}</a>";
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
			$s .= ' | ' . $this->makeKnownLink( $wgTitle->getPrefixedText(),
					wfMsg( 'currentrev' ) );
		}

		if ( $wgUser->getNewtalk() ) {
		# do not show "You have new messages" text when we are viewing our
		# own talk page

			if(!(strcmp($wgTitle->getText(),$wgUser->getName()) == 0 &&
						$wgTitle->getNamespace()==Namespace::getTalk(Namespace::getUser()))) {
				$n =$wgUser->getName();
				$tl = $this->makeKnownLink( $wgContLang->getNsText(
							Namespace::getTalk( Namespace::getUser() ) ) . ":{$n}",
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
		if($wgUser->getOption( 'editsectiononrightclick' ) && $wgTitle->userCanEdit()) { $s=$this->editSectionScript($wgTitle, 0,$s);}
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
		if ( 0 == $wgUser->getID() ) {
			if( $wgShowIPinHeader && isset(  $_COOKIE[ini_get('session.name')] ) ) {
				$n = $wgIP;

				$tl = $this->makeKnownLink( $wgContLang->getNsText(
				  Namespace::getTalk( Namespace::getUser() ) ) . ":{$n}",
				  $wgContLang->getNsText( Namespace::getTalk( 0 ) ) );

				$s .= $n . ' ('.$tl.')';
			} else {
				$s .= wfMsg('notloggedin');
			}

			$rt = $wgTitle->getPrefixedURL();
			if ( 0 == strcasecmp( urlencode( $lo ), $rt ) ) {
				$q = '';
			} else { $q = "returnto={$rt}"; }

			$s .= "\n<br />" . $this->makeKnownLink( $li,
			  wfMsg( 'login' ), $q );
		} else {
			$n = $wgUser->getName();
			$rt = $wgTitle->getPrefixedURL();
			$tl = $this->makeKnownLink( $wgContLang->getNsText(
			  Namespace::getTalk( Namespace::getUser() ) ) . ":{$n}",
			  $wgContLang->getNsText( Namespace::getTalk( 0 ) ) );

			$tl = " ({$tl})";

			$s .= $this->makeKnownLink( $wgContLang->getNsText(
			  Namespace::getUser() ) . ":{$n}", $n ) . "{$tl}<br />" .
			  $this->makeKnownLink( $lo, wfMsg( 'logout' ),
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

		return $s;
	}

	function bottomLinks() {
		global $wgOut, $wgUser, $wgTitle;
		$sep = " |\n";

		$s = '';
		if ( $wgOut->isArticleRelated() ) {
			$s .= '<strong>' . $this->editThisPage() . '</strong>';
			if ( 0 != $wgUser->getID() ) {
				$s .= $sep . $this->watchThisPage();
			}
			$s .= $sep . $this->talkLink()
			  . $sep . $this->historyLink()
			  . $sep . $this->whatLinksHere()
			  . $sep . $this->watchPageLinksLink();

			if ( $wgTitle->getNamespace() == Namespace::getUser()
			    || $wgTitle->getNamespace() == Namespace::getTalk(Namespace::getUser()) )

			{
				$id=User::idFromName($wgTitle->getText());
				$ip=User::isIP($wgTitle->getText());

				if($id || $ip) { # both anons and non-anons have contri list
					$s .= $sep . $this->userContribsLink();
				}
				if ( 0 != $wgUser->getID() ) { # show only to signed in users
					if($id) {	# can only email non-anons
						$s .= $sep . $this->emailUserLink();
					}
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
		global $wgOut, $wgLang, $wgArticle, $wgRequest;
		global $wgDisableCounters, $wgMaxCredits, $wgShowCreditsIfMax;

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
		    require_once("Credits.php");
		    $s .= ' ' . getCredits($wgArticle, $wgMaxCredits, $wgShowCreditsIfMax);
		} else {
		    $s .= $this->lastModified();
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
		global $wgRightsPage, $wgRightsUrl, $wgRightsText, $wgRightsIcon;
		$out = '';
		if( $wgRightsIcon ) {
			$icon = htmlspecialchars( $wgRightsIcon );
			if( $wgRightsUrl ) {
				$url = htmlspecialchars( $wgRightsUrl );
				$out .= '<a href="'.$url.'">';
			}
			$text = htmlspecialchars( $wgRightsText );
			$out .= "<img src=\"$icon\" alt='$text' />";
			if( $wgRightsUrl ) {
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
		global $wgLang, $wgArticle;

		$timestamp = $wgArticle->getTimestamp();
		if ( $timestamp ) {
			$d = $wgLang->timeanddate( $wgArticle->getTimestamp(), true );
			$s = ' ' . wfMsg( 'lastmodified', $d );
		} else {
			$s = '';
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

	function specialPagesList() {
		global $wgUser, $wgOut, $wgContLang, $wgServer, $wgRedirectScript;
		require_once('SpecialPage.php');
		$a = array();
		$pages = SpecialPage::getPages();

		foreach ( $pages[''] as $name => $page ) {
			$a[$name] = $page->getDescription();
		}
		if ( $wgUser->isSysop() )
		{
			foreach ( $pages['sysop'] as $name => $page ) {
				$a[$name] = $page->getDescription();
			}
		}
		if ( $wgUser->isDeveloper() )
		{
			foreach ( $pages['developer'] as $name => $page ) {
				$a[$name] = $page->getDescription() ;
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
			return "";
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
			$n = $wgTitle->getPrefixedText();
			if ( $wgTitle->userCanEdit() ) {
				$t = wfMsg( 'editthispage' );
			} else {
				#$t = wfMsg( "protectedpage" );
				$t = wfMsg( 'viewsource' );
			}
			$oid = $red = '';

			if ( !is_null( $redirect ) ) { $red = "&redirect={$redirect}"; }
			if ( $oldid && ! isset( $diff ) ) {
				$oid = '&oldid='.$oldid;
			}
			$s = $this->makeKnownLink( $n, $t, "action=edit{$oid}{$red}" );
		}
		return $s;
	}

	function deleteThisPage() {
		global $wgUser, $wgOut, $wgTitle, $wgRequest;

		$diff = $wgRequest->getVal( 'diff' );
		if ( $wgTitle->getArticleId() && ( ! $diff ) && $wgUser->isAllowed('delete') ) {
			$n = $wgTitle->getPrefixedText();
			$t = wfMsg( 'deletethispage' );

			$s = $this->makeKnownLink( $n, $t, 'action=delete' );
		} else {
			$s = '';
		}
		return $s;
	}

	function protectThisPage() {
		global $wgUser, $wgOut, $wgTitle, $wgRequest;

		$diff = $wgRequest->getVal( 'diff' );
		if ( $wgTitle->getArticleId() && ( ! $diff ) && $wgUser->isAllowed('protect') ) {
			$n = $wgTitle->getPrefixedText();

			if ( $wgTitle->isProtected() ) {
				$t = wfMsg( 'unprotectthispage' );
				$q = 'action=unprotect';
			} else {
				$t = wfMsg( 'protectthispage' );
				$q = 'action=protect';
			}
			$s = $this->makeKnownLink( $n, $t, $q );
		} else {
			$s = '';
		}
		return $s;
	}

	function watchThisPage() {
		global $wgUser, $wgOut, $wgTitle;

		if ( $wgOut->isArticleRelated() ) {
			$n = $wgTitle->getPrefixedText();

			if ( $wgTitle->userIsWatching() ) {
				$t = wfMsg( 'unwatchthispage' );
				$q = 'action=unwatch';
			} else {
				$t = wfMsg( 'watchthispage' );
				$q = 'action=watch';
			}
			$s = $this->makeKnownLink( $n, $t, $q );
		} else {
			$s = wfMsg( 'notanarticle' );
		}
		return $s;
	}

	function moveThisPage() {
		global $wgTitle, $wgContLang;

		if ( $wgTitle->userCanEdit() ) {
			$s = $this->makeKnownLink( $wgContLang->specialPage( 'Movepage' ),
			  wfMsg( 'movethispage' ), 'target=' . $wgTitle->getPrefixedURL() );
		} // no message if page is protected - would be redundant
		return $s;
	}

	function historyLink() {
		global $wgTitle;

		$s = $this->makeKnownLink( $wgTitle->getPrefixedText(),
		  wfMsg( 'history' ), 'action=history' );
		return $s;
	}

	function whatLinksHere() {
		global $wgTitle, $wgContLang;

		$s = $this->makeKnownLink( $wgContLang->specialPage( 'Whatlinkshere' ),
		  wfMsg( 'whatlinkshere' ), 'target=' . $wgTitle->getPrefixedURL() );
		return $s;
	}

	function userContribsLink() {
		global $wgTitle, $wgContLang;

		$s = $this->makeKnownLink( $wgContLang->specialPage( 'Contributions' ),
		  wfMsg( 'contributions' ), 'target=' . $wgTitle->getPartialURL() );
		return $s;
	}

	function emailUserLink() {
		global $wgTitle, $wgContLang;

		$s = $this->makeKnownLink( $wgContLang->specialPage( 'Emailuser' ),
		  wfMsg( 'emailuser' ), 'target=' . $wgTitle->getPartialURL() );
		return $s;
	}

	function watchPageLinksLink() {
		global $wgOut, $wgTitle, $wgContLang;

		if ( ! $wgOut->isArticleRelated() ) {
			$s = '(' . wfMsg( 'notanarticle' ) . ')';
		} else {
			$s = $this->makeKnownLink( $wgContLang->specialPage(
			  'Recentchangeslinked' ), wfMsg( 'recentchangeslinked' ),
			  'target=' . $wgTitle->getPrefixedURL() );
		}
		return $s;
	}

	function otherLanguages() {
		global $wgOut, $wgContLang, $wgTitle, $wgUseNewInterlanguage;

		$a = $wgOut->getLanguageLinks();
		if ( 0 == count( $a ) ) {
			if ( !$wgUseNewInterlanguage ) return '';
			$ns = $wgContLang->getNsIndex ( $wgTitle->getNamespace () ) ;
			if ( $ns != 0 AND $ns != 1 ) return '' ;
			$pn = 'Intl' ;
			$x = 'mode=addlink&xt='.$wgTitle->getDBkey() ;
			return $this->makeKnownLink( $wgContLang->specialPage( $pn ),
				  wfMsg( 'intl' ) , $x );
			}

		if ( !$wgUseNewInterlanguage ) {
			$s = wfMsg( 'otherlanguages' ) . ': ';
		} else {
			global $wgContLanguageCode ;
			$x = 'mode=zoom&xt='.$wgTitle->getDBkey() ;
			$x .= '&xl='.$wgContLanguageCode ;
			$s =  $this->makeKnownLink( $wgContLang->specialPage( 'Intl' ),
				  wfMsg( 'otherlanguages' ) , $x ) . ': ' ;
			}

		$s = wfMsg( 'otherlanguages' ) . ': ';
		$first = true;
		if($wgContLang->isRTL()) $s .= '<span dir="LTR">';
		foreach( $a as $l ) {
			if ( ! $first ) { $s .= ' | '; }
			$first = false;

			$nt = Title::newFromText( $l );
			$url = $nt->getFullURL();
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
		global $wgContLang, $wgTitle, $wgLinkCache;

		$tns = $wgTitle->getNamespace();
		if ( -1 == $tns ) { return ''; }

		$pn = $wgTitle->getText();
		$tp = wfMsg( 'talkpage' );
		if ( Namespace::isTalk( $tns ) ) {
			$lns = Namespace::getSubject( $tns );
			switch($tns) {
				case 1:
				$text = wfMsg('articlepage');
				break;
				case 3:
				$text = wfMsg('userpage');
				break;
				case 5:
				$text = wfMsg('wikipediapage');
				break;
				case 7:
				$text = wfMsg('imagepage');
				break;
				default:
				$text= wfMsg('articlepage');
			}
		} else {

			$lns = Namespace::getTalk( $tns );
			$text=$tp;
		}
		$n = $wgContLang->getNsText( $lns );
		if ( '' == $n ) { $link = $pn; }
		else { $link = $n.':'.$pn; }

		$wgLinkCache->suspend();
		$s = $this->makeLink( $link, $text );
		$wgLinkCache->resume();

		return $s;
	}

	function commentLink() {
		global $wgContLang, $wgTitle, $wgLinkCache;

		$tns = $wgTitle->getNamespace();
		if ( -1 == $tns ) { return ''; }

		$lns = ( Namespace::isTalk( $tns ) ) ? $tns : Namespace::getTalk( $tns );

		# assert Namespace::isTalk( $lns )

		$n = $wgContLang->getNsText( $lns );
		$pn = $wgTitle->getText();

		$link = $n.':'.$pn;

		$wgLinkCache->suspend();
		$s = $this->makeKnownLink($link, wfMsg('postcomment'), 'action=edit&section=new');
		$wgLinkCache->resume();

		return $s;
	}

	/**
	 * After all the page content is transformed into HTML, it makes
	 * a final pass through here for things like table backgrounds.
	 * @todo probably deprecated [AV]
	 */
	function transformContent( $text ) {
		return $text;
	}

	/**
	 * Note: This function MUST call getArticleID() on the link,
	 * otherwise the cache won't get updated properly.  See LINKCACHE.DOC.
	 */
	function makeLink( $title, $text = '', $query = '', $trail = '' ) {
		wfProfileIn( 'Skin::makeLink' );
	 	$nt = Title::newFromText( $title );
		if ($nt) {
			$result = $this->makeLinkObj( Title::newFromText( $title ), $text, $query, $trail );
		} else {
			wfDebug( 'Invalid title passed to Skin::makeLink(): "'.$title."\"\n" );
			$result = $text == "" ? $title : $text;
		}

		wfProfileOut( 'Skin::makeLink' );
		return $result;
	}

	function makeKnownLink( $title, $text = '', $query = '', $trail = '', $prefix = '',$aprops = '') {
		$nt = Title::newFromText( $title );
		if ($nt) {
			return $this->makeKnownLinkObj( Title::newFromText( $title ), $text, $query, $trail, $prefix , $aprops );
		} else {
			wfDebug( 'Invalid title passed to Skin::makeKnownLink(): "'.$title."\"\n" );
			return $text == '' ? $title : $text;
		}
	}

	function makeBrokenLink( $title, $text = '', $query = '', $trail = '' ) {
		$nt = Title::newFromText( $title );
		if ($nt) {
			return $this->makeBrokenLinkObj( Title::newFromText( $title ), $text, $query, $trail );
		} else {
			wfDebug( 'Invalid title passed to Skin::makeBrokenLink(): "'.$title."\"\n" );
			return $text == '' ? $title : $text;
		}
	}

	function makeStubLink( $title, $text = '', $query = '', $trail = '' ) {
		$nt = Title::newFromText( $title );
		if ($nt) {
			return $this->makeStubLinkObj( Title::newFromText( $title ), $text, $query, $trail );
		} else {
			wfDebug( 'Invalid title passed to Skin::makeStubLink(): "'.$title."\"\n" );
			return $text == '' ? $title : $text;
		}
	}

	/**
	 * Pass a title object, not a title string
	 */
	function makeLinkObj( &$nt, $text= '', $query = '', $trail = '', $prefix = '' ) {
		global $wgOut, $wgUser, $wgLinkHolders;
		$fname = 'Skin::makeLinkObj';

		# Fail gracefully
		if ( ! isset($nt) ) {
			# wfDebugDieBacktrace();
			return "<!-- ERROR -->{$prefix}{$text}{$trail}";
		}

		if ( $nt->isExternal() ) {
			$u = $nt->getFullURL();
			$link = $nt->getPrefixedURL();
			if ( '' == $text ) { $text = $nt->getPrefixedText(); }
			$style = $this->getExternalLinkAttributes( $link, $text, 'extiw' );

			$inside = '';
			if ( '' != $trail ) {
				if ( preg_match( '/^([a-z]+)(.*)$$/sD', $trail, $m ) ) {
					$inside = $m[1];
					$trail = $m[2];
				}
			}
			# Assume $this->postParseLinkColour(). This prevents
			# interwiki links from being parsed as external links.
			global $wgInterwikiLinkHolders;
			$t = "<a href=\"{$u}\"{$style}>{$text}{$inside}</a>";
			$nr = array_push($wgInterwikiLinkHolders, $t);
			$retVal = '<!--IWLINK '. ($nr-1) ."-->{$trail}";
		} elseif ( 0 == $nt->getNamespace() && "" == $nt->getText() ) {
			$retVal = $this->makeKnownLinkObj( $nt, $text, $query, $trail, $prefix );
		} elseif ( ( -1 == $nt->getNamespace() ) ||
				( NS_IMAGE == $nt->getNamespace() ) ) {
			$retVal = $this->makeKnownLinkObj( $nt, $text, $query, $trail, $prefix );
		} else {
			if ( $this->postParseLinkColour() ) {
				$inside = '';
				if ( '' != $trail ) {
					if ( preg_match( $this->linktrail, $trail, $m ) ) {
						$inside = $m[1];
						$trail = $m[2];
					}
				}

				# Allows wiki to bypass using linkcache, see OutputPage::parseLinkHolders()
				$nr = array_push( $wgLinkHolders['namespaces'], $nt->getNamespace() );
				$wgLinkHolders['dbkeys'][] = $nt->getDBkey();
				$wgLinkHolders['queries'][] = $query;
				$wgLinkHolders['texts'][] = $prefix.$text.$inside;
				$wgLinkHolders['titles'][] = $nt;

				$retVal = '<!--LINK '. ($nr-1) ."-->{$trail}";
			} else {
				# Work out link colour immediately
				$aid = $nt->getArticleID() ;
				if ( 0 == $aid ) {
					$retVal = $this->makeBrokenLinkObj( $nt, $text, $query, $trail, $prefix );
				} else {
					$threshold = $wgUser->getOption('stubthreshold') ;
					if ( $threshold > 0 ) {
						$dbr =& wfGetDB( DB_SLAVE );
						$s = $dbr->selectRow( 'cur', array( 'LENGTH(cur_text) AS x', 'cur_namespace',
							'cur_is_redirect' ), array( 'cur_id' => $aid ), $fname ) ;
						if ( $s !== false ) {
							$size = $s->x;
							if ( $s->cur_is_redirect OR $s->cur_namespace != 0 ) {
								$size = $threshold*2 ; # Really big
							}
							$dbr->freeResult( $res );
						} else {
							$size = $threshold*2 ; # Really big
						}
					} else {
						$size = 1 ;
					}
					if ( $size < $threshold ) {
						$retVal = $this->makeStubLinkObj( $nt, $text, $query, $trail, $prefix );
					} else {
						$retVal = $this->makeKnownLinkObj( $nt, $text, $query, $trail, $prefix );
					}
				}
			}
		}
		return $retVal;
	}

	/**
	 * Pass a title object, not a title string
	 */
	function makeKnownLinkObj( &$nt, $text = '', $query = '', $trail = '', $prefix = '' , $aprops = '' ) {
		global $wgOut, $wgTitle, $wgInputEncoding;

		$fname = 'Skin::makeKnownLinkObj';
		wfProfileIn( $fname );

		if ( !is_object( $nt ) ) {
			return $text;
		}
		$link = $nt->getPrefixedURL();
#		if ( '' != $section && substr($section,0,1) != "#" ) {
#			$section = ''

		if ( '' == $link ) {
			$u = '';
			if ( '' == $text ) {
				$text = htmlspecialchars( $nt->getFragment() );
			}
		} else {
			$u = $nt->escapeLocalURL( $query );
		}
		if ( '' != $nt->getFragment() ) {
			$anchor = urlencode( do_html_entity_decode( str_replace(' ', '_', $nt->getFragment()), ENT_COMPAT, $wgInputEncoding ) );
			$replacearray = array(
				'%3A' => ':',
				'%' => '.'
			);
			$u .= '#' . str_replace(array_keys($replacearray),array_values($replacearray),$anchor);
		}
		if ( '' == $text ) {
			$text = htmlspecialchars( $nt->getPrefixedText() );
		}
		$style = $this->getInternalLinkAttributesObj( $nt, $text );

		$inside = '';
		if ( '' != $trail ) {
			if ( preg_match( $this->linktrail, $trail, $m ) ) {
				$inside = $m[1];
				$trail = $m[2];
			}
		}
		$r = "<a href=\"{$u}\"{$style}{$aprops}>{$prefix}{$text}{$inside}</a>{$trail}";
		wfProfileOut( $fname );
		return $r;
	}

	/**
	 * Pass a title object, not a title string
	 */
	function makeBrokenLinkObj( &$nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		global $wgOut, $wgUser;

		# Fail gracefully
		if ( ! isset($nt) ) {
			# wfDebugDieBacktrace();
			return "<!-- ERROR -->{$prefix}{$text}{$trail}";
		}

		$fname = 'Skin::makeBrokenLinkObj';
		wfProfileIn( $fname );

		if ( '' == $query ) {
			$q = 'action=edit';
		} else {
			$q = 'action=edit&'.$query;
		}
		$u = $nt->escapeLocalURL( $q );

		if ( '' == $text ) {
			$text = htmlspecialchars( $nt->getPrefixedText() );
		}
		$style = $this->getInternalLinkAttributesObj( $nt, $text, "yes" );

		$inside = '';
		if ( '' != $trail ) {
			if ( preg_match( $this->linktrail, $trail, $m ) ) {
				$inside = $m[1];
				$trail = $m[2];
			}
		}
		if ( $wgUser->getOption( 'highlightbroken' ) ) {
			$s = "<a href=\"{$u}\"{$style}>{$prefix}{$text}{$inside}</a>{$trail}";
		} else {
			$s = "{$prefix}{$text}{$inside}<a href=\"{$u}\"{$style}>?</a>{$trail}";
		}

		wfProfileOut( $fname );
		return $s;
	}

	/**
 	 * Pass a title object, not a title string
	 */
	function makeStubLinkObj( &$nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		global $wgOut, $wgUser;

		$link = $nt->getPrefixedURL();

		$u = $nt->escapeLocalURL( $query );

		if ( '' == $text ) {
			$text = htmlspecialchars( $nt->getPrefixedText() );
		}
		$style = $this->getInternalLinkAttributesObj( $nt, $text, 'stub' );

		$inside = '';
		if ( '' != $trail ) {
			if ( preg_match( $this->linktrail, $trail, $m ) ) {
				$inside = $m[1];
				$trail = $m[2];
			}
		}
		if ( $wgUser->getOption( 'highlightbroken' ) ) {
			$s = "<a href=\"{$u}\"{$style}>{$prefix}{$text}{$inside}</a>{$trail}";
		} else {
			$s = "{$prefix}{$text}{$inside}<a href=\"{$u}\"{$style}>!</a>{$trail}";
		}
		return $s;
	}

	function makeSelfLinkObj( &$nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		$u = $nt->escapeLocalURL( $query );
		if ( '' == $text ) {
			$text = htmlspecialchars( $nt->getPrefixedText() );
		}
		$inside = '';
		if ( '' != $trail ) {
			if ( preg_match( $this->linktrail, $trail, $m ) ) {
				$inside = $m[1];
				$trail = $m[2];
			}
		}
		return "<strong>{$prefix}{$text}{$inside}</strong>{$trail}";
	}

	/* these are used extensively in SkinPHPTal, but also some other places */
	/*static*/ function makeSpecialUrl( $name, $urlaction='' ) {
		$title = Title::makeTitle( NS_SPECIAL, $name );
		$this->checkTitle($title, $name);
		return $title->getLocalURL( $urlaction );
	}
	/*static*/ function makeTalkUrl ( $name, $urlaction='' ) {
		$title = Title::newFromText( $name );
		$title = $title->getTalkPage();
		$this->checkTitle($title, $name);
		return $title->getLocalURL( $urlaction );
	}
	/*static*/ function makeArticleUrl ( $name, $urlaction='' ) {
		$title = Title::newFromText( $name );
		$title= $title->getSubjectPage();
		$this->checkTitle($title, $name);
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
	/*static*/ function makeNSUrl( $name, $urlaction='', $namespace=0 ) {
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
	/*static*/ function makeTalkUrlDetails ( $name, $urlaction='' ) {
		$title = Title::newFromText( $name );
		$title = $title->getTalkPage();
		$this->checkTitle($title, $name);
		return array(
			'href' => $title->getLocalURL( $urlaction ),
			'exists' => $title->getArticleID() != 0?true:false
		);
	}
	/*static*/ function makeArticleUrlDetails ( $name, $urlaction='' ) {
		$title = Title::newFromText( $name );
		$title= $title->getSubjectPage();
		$this->checkTitle($title, $name);
		return array(
			'href' => $title->getLocalURL( $urlaction ),
			'exists' => $title->getArticleID() != 0?true:false
		);
	}
	/*static*/ function makeI18nUrlDetails ( $name, $urlaction='' ) {
		$title = Title::newFromText( wfMsgForContent($name) );
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

	function fnamePart( $url ) {
		$basename = strrchr( $url, '/' );
		if ( false === $basename ) {
			$basename = $url;
		} else {
			$basename = substr( $basename, 1 );
		}
		return htmlspecialchars( $basename );
	}

	function makeImage( $url, $alt = '' ) {
		global $wgOut;
		if ( '' == $alt ) {
			$alt = $this->fnamePart( $url );
		}
		$s = '<img src="'.$url.'" alt="'.$alt.'" />';
		return $s;
	}

	function makeImageLink( $name, $url, $alt = '' ) {
		$nt = Title::makeTitleSafe( NS_IMAGE, $name );
		return $this->makeImageLinkObj( $nt, $alt );
	}

	function makeImageLinkObj( $nt, $alt = '' ) {
		global $wgContLang, $wgUseImageResize;
		$img   = Image::newFromTitle( $nt );
		$url   = $img->getViewURL();

		$align = '';
		$prefix = $postfix = '';

		# Check if the alt text is of the form "options|alt text"
		# Options are:
		#  * thumbnail       	make a thumbnail with enlarge-icon and caption, alignment depends on lang
		#  * left		no resizing, just left align. label is used for alt= only
		#  * right		same, but right aligned
		#  * none		same, but not aligned
		#  * ___px		scale to ___ pixels width, no aligning. e.g. use in taxobox
		#  * center		center the image
		#  * framed		Keep original image size, no magnify-button.

		$part = explode( '|', $alt);

		$mwThumb  =& MagicWord::get( MAG_IMG_THUMBNAIL );
		$mwLeft   =& MagicWord::get( MAG_IMG_LEFT );
		$mwRight  =& MagicWord::get( MAG_IMG_RIGHT );
		$mwNone   =& MagicWord::get( MAG_IMG_NONE );
		$mwWidth  =& MagicWord::get( MAG_IMG_WIDTH );
		$mwCenter =& MagicWord::get( MAG_IMG_CENTER );
		$mwFramed =& MagicWord::get( MAG_IMG_FRAMED );
		$alt = '';

		$height = $framed = $thumb = false;
		$manual_thumb = "" ;

		foreach( $part as $key => $val ) {
			$val_parts = explode ( "=" , $val , 2 ) ;
			$left_part = array_shift ( $val_parts ) ;
			if ( $wgUseImageResize && ! is_null( $mwThumb->matchVariableStartToEnd($val) ) ) {
				$thumb=true;
			} elseif ( $wgUseImageResize && count ( $val_parts ) == 1 && ! is_null( $mwThumb->matchVariableStartToEnd($left_part) ) ) {
				# use manually specified thumbnail
				$thumb=true;
				$manual_thumb = array_shift ( $val_parts ) ;
			} elseif ( ! is_null( $mwRight->matchVariableStartToEnd($val) ) ) {
				# remember to set an alignment, don't render immediately
				$align = 'right';
			} elseif ( ! is_null( $mwLeft->matchVariableStartToEnd($val) ) ) {
				# remember to set an alignment, don't render immediately
				$align = 'left';
			} elseif ( ! is_null( $mwCenter->matchVariableStartToEnd($val) ) ) {
				# remember to set an alignment, don't render immediately
				$align = 'center';
			} elseif ( ! is_null( $mwNone->matchVariableStartToEnd($val) ) ) {
				# remember to set an alignment, don't render immediately
				$align = 'none';
			} elseif ( $wgUseImageResize && ! is_null( $match = $mwWidth->matchVariableStartToEnd($val) ) ) {
				# $match is the image width in pixels
				if ( preg_match( '/^([0-9]*)x([0-9]*)$/', $match, $m ) ) {
					$width = intval( $m[1] );
					$height = intval( $m[2] );
				} else {
					$width = intval($match);
				}
			} elseif ( ! is_null( $mwFramed->matchVariableStartToEnd($val) ) ) {
				$framed=true;
			} else {
				$alt = $val;
			}
		}
		if ( 'center' == $align )
		{
			$prefix  = '<div class="center">';
			$postfix = '</div>';
			$align   = 'none';
		}

		if ( $thumb || $framed ) {

			# Create a thumbnail. Alignment depends on language
			# writing direction, # right aligned for left-to-right-
			# languages ("Western languages"), left-aligned
			# for right-to-left-languages ("Semitic languages")
			#
			# If  thumbnail width has not been provided, it is set
			# here to 180 pixels
			if ( $align == '' ) {
				$align = $wgContLang->isRTL() ? 'left' : 'right';
			}
			if ( ! isset($width) ) {
				$width = 180;
			}
			return $prefix.$this->makeThumbLinkObj( $img, $alt, $align, $width, $height, $framed, $manual_thumb ).$postfix;

		} elseif ( isset($width) ) {

			# Create a resized image, without the additional thumbnail
			# features

			if (    ( ! $height === false )
			     && ( $img->getHeight() * $width / $img->getWidth() > $height ) ) {
				$width = $img->getWidth() * $height / $img->getHeight();
			}
			if ( '' == $manual_thumb ) $url = $img->createThumb( $width );
		}

		$alt = preg_replace( '/<[^>]*>/', '', $alt );
		$alt = preg_replace('/&(?!:amp;|#[Xx][0-9A-fa-f]+;|#[0-9]+;|[a-zA-Z0-9]+;)/', '&amp;', $alt);
		$alt = str_replace( array('<', '>', '"'), array('&lt;', '&gt;', '&quot;'), $alt );

		$u = $nt->escapeLocalURL();
		if ( $url == '' ) {
			$s = wfMsg( 'missingimage', $img->getName() );
			$s .= "<br>{$alt}<br>{$url}<br>\n";
		} else {
			$s = '<a href="'.$u.'" class="image" title="'.$alt.'">' .
				 '<img src="'.$url.'" alt="'.$alt.'" longdesc="'.$u.'" /></a>';
		}
		if ( '' != $align ) {
			$s = "<div class=\"float{$align}\"><span>{$s}</span></div>";
		}
		return str_replace("\n", ' ',$prefix.$s.$postfix);
	}

	/**
	 * Make HTML for a thumbnail including image, border and caption
	 * $img is an Image object
	 */
	function makeThumbLinkObj( $img, $label = '', $align = 'right', $boxwidth = 180, $boxheight=false, $framed=false , $manual_thumb = "" ) {
		global $wgStylePath, $wgContLang;
		# $image = Title::makeTitleSafe( NS_IMAGE, $name );
		$url  = $img->getViewURL();

		#$label = htmlspecialchars( $label );
		$alt = preg_replace( '/<[^>]*>/', '', $label);
		$alt = preg_replace('/&(?!:amp;|#[Xx][0-9A-fa-f]+;|#[0-9]+;|[a-zA-Z0-9]+;)/', '&amp;', $alt);
		$alt = str_replace( array('<', '>', '"'), array('&lt;', '&gt;', '&quot;'), $alt );

		$width = $height = 0;
		if ( $img->exists() )
		{
			$width  = $img->getWidth();
			$height = $img->getHeight();
		}
		if ( 0 == $width || 0 == $height )
		{
			$width = $height = 200;
		}
		if ( $boxwidth == 0 )
		{
			$boxwidth = 200;
		}
		if ( $framed )
		{
			// Use image dimensions, don't scale
			$boxwidth  = $width;
			$oboxwidth = $boxwidth + 2;
			$boxheight = $height;
			$thumbUrl  = $url;
		} else {
			$h  = intval( $height/($width/$boxwidth) );
			$oboxwidth = $boxwidth + 2;
			if ( ( ! $boxheight === false ) &&  ( $h > $boxheight ) )
			{
				$boxwidth *= $boxheight/$h;
			} else {
				$boxheight = $h;
			}
			if ( '' == $manual_thumb ) $thumbUrl = $img->createThumb( $boxwidth );
		}

		if ( $manual_thumb != '' ) # Use manually specified thumbnail
		{
			$manual_title = Title::makeTitleSafe( NS_IMAGE, $manual_thumb ); #new Title ( $manual_thumb ) ;
			$manual_img = Image::newFromTitle( $manual_title );
			$thumbUrl = $manual_img->getViewURL();
			if ( $manual_img->exists() )
			{
				$width  = $manual_img->getWidth();
				$height = $manual_img->getHeight();
				$boxwidth = $width ;
				$boxheight = $height ;
				$oboxwidth = $boxwidth + 2 ;
			}
		}

		$u = $img->getEscapeLocalURL();

		$more = htmlspecialchars( wfMsg( 'thumbnail-more' ) );
		$magnifyalign = $wgContLang->isRTL() ? 'left' : 'right';
		$textalign = $wgContLang->isRTL() ? ' style="text-align:right"' : '';

		$s = "<div class=\"thumb t{$align}\"><div style=\"width:{$oboxwidth}px;\">";
		if ( $thumbUrl == '' ) {
			$s .= wfMsg( 'missingimage', $img->getName() );
			$zoomicon = '';
		} else {
			$s .= '<a href="'.$u.'" class="internal" title="'.$alt.'">'.
				'<img src="'.$thumbUrl.'" alt="'.$alt.'" ' .
				'width="'.$boxwidth.'" height="'.$boxheight.'" ' .
				'longdesc="'.$u.'" /></a>';
			if ( $framed ) {
				$zoomicon="";
			} else {
				$zoomicon =  '<div class="magnify" style="float:'.$magnifyalign.'">'.
					'<a href="'.$u.'" class="internal" title="'.$more.'">'.
					'<img src="'.$wgStylePath.'/common/images/magnify-clip.png" ' .
					'width="15" height="11" alt="'.$more.'" /></a></div>';
			}
		}
		$s .= '  <div class="thumbcaption" '.$textalign.'>'.$zoomicon.$label."</div></div></div>";
		return str_replace("\n", ' ', $s);
	}

	function makeMediaLink( $name, $url, $alt = '' ) {
		$nt = Title::makeTitleSafe( NS_IMAGE, $name );
		return $this->makeMediaLinkObj( $nt, $alt );
	}

	function makeMediaLinkObj( $nt, $alt = '', $nourl=false ) {		
		if ( ! isset( $nt ) )
		{
			### HOTFIX. Instead of breaking, return empty string.
			$s = $alt;
		} else {
			$name = $nt->getDBKey();	
			$img   = Image::newFromTitle( $nt );
			$url = $img->getURL();
			# $nourl can be set by the parser
			# this is a hack to mask absolute URLs, so the parser doesn't
			# linkify them (it is currently not context-aware)
			# 2004-10-25
			if ($nourl) { $url=str_replace("http://","http-noparse://",$url) ; }
			if ( empty( $alt ) ) {
				$alt = preg_replace( '/\.(.+?)^/', '', $name );
			}
			$u = htmlspecialchars( $url );
			$s = "<a href=\"{$u}\" class='internal' title=\"{$alt}\">{$alt}</a>";			
		}
		return $s;
	}

	function specialLink( $name, $key = '' ) {
		global $wgContLang;

		if ( '' == $key ) { $key = strtolower( $name ); }
		$pn = $wgContLang->ucfirst( $name );
		return $this->makeKnownLink( $wgContLang->specialPage( $pn ),
		  wfMsg( $key ) );
	}

	function makeExternalLink( $url, $text, $escape = true ) {
		$style = $this->getExternalLinkAttributes( $url, $text );
		$url = htmlspecialchars( $url );
		if( $escape ) {
			$text = htmlspecialchars( $text );
		}
		return '<a href="'.$url.'"'.$style.'>'.$text.'</a>';
	}

	# Called by history lists and recent changes
	#

	# Returns text for the start of the tabular part of RC
	function beginRecentChangesList() {
		$this->rc_cache = array() ;
		$this->rcMoveIndex = 0;
		$this->rcCacheIndex = 0 ;
		$this->lastdate = '';
		$this->rclistOpen = false;
		return '';
	}

	function beginImageHistoryList() {
		$s = "\n<h2>" . wfMsg( 'imghistory' ) . "</h2>\n" .
		  "<p>" . wfMsg( 'imghistlegend' ) . "</p>\n".'<ul class="special">';
		return $s;
	}

	/**
 	 * Returns text for the end of RC
	 * If enhanced RC is in use, returns pretty much all the text
	 */
	function endRecentChangesList() {
		$s = $this->recentChangesBlock() ;
		if( $this->rclistOpen ) {
			$s .= "</ul>\n";
		}
		return $s;
	}

	/**
	 * Enhanced RC ungrouped line
	 */
	function recentChangesBlockLine ( $rcObj ) {
		global $wgStylePath, $wgContLang ;

		# Get rc_xxxx variables
		extract( $rcObj->mAttribs ) ;
		$curIdEq = 'curid='.$rc_cur_id;

		# Spacer image
		$r = '' ;

		$r .= '<img src="'.$wgStylePath.'/common/images/Arr_.png" width="12" height="12" border="0" />' ;
		$r .= '<tt>' ;

		if ( $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			$r .= '&nbsp;&nbsp;';
		} else {
			# M, N and !
			$M = wfMsg( 'minoreditletter' );
			$N = wfMsg( 'newpageletter' );

			if ( $rc_type == RC_NEW ) {
				$r .= $N ;
			} else {
				$r .= '&nbsp;' ;
			}
			if ( $rc_minor ) {
				$r .= $M ;
			} else {
				$r .= '&nbsp;' ;
			}
			if ( $rcObj->unpatrolled ) {
				$r .= '!';
			} else {
				$r .= '&nbsp;';
			}
		}

		# Timestamp
		$r .= ' '.$rcObj->timestamp.' ' ;
		$r .= '</tt>' ;

		# Article link
		$link = $rcObj->link ;
		if ( $rcObj->watched ) $link = '<strong>'.$link.'</strong>' ;
		$r .= $link ;

		# Diff
		$r .= ' (' ;
		$r .= $rcObj->difflink ;
		$r .= '; ' ;

		# Hist
		$r .= $this->makeKnownLinkObj( $rcObj->getTitle(), wfMsg( 'hist' ), $curIdEq.'&action=history' );

		# User/talk
		$r .= ') . . '.$rcObj->userlink ;
		$r .= $rcObj->usertalklink ;

		# Comment
		 if ( $rc_comment != '' && $rc_type != RC_MOVE && $rc_type != RC_MOVE_OVER_REDIRECT ) {
			$rc_comment=$this->formatComment($rc_comment, $rcObj->getTitle());
			$r .= $wgContLang->emphasize( ' ('.$rc_comment.')' );
		}

		$r .= "<br />\n" ;
		return $r ;
	}

	/**
	 * Enhanced RC group
	 */
	function recentChangesBlockGroup ( $block ) {
		global $wgStylePath, $wgContLang ;

		$r = '' ;
		$M = wfMsg( 'minoreditletter' );
		$N = wfMsg( 'newpageletter' );

		# Collate list of users
		$isnew = false ;
		$unpatrolled = false;
		$userlinks = array () ;
		foreach ( $block AS $rcObj ) {
			$oldid = $rcObj->mAttribs['rc_last_oldid'];
			if ( $rcObj->mAttribs['rc_new'] ) {
				$isnew = true ;
			}
			$u = $rcObj->userlink ;
			if ( !isset ( $userlinks[$u] ) ) {
				$userlinks[$u] = 0 ;
			}
			if ( $rcObj->unpatrolled ) {
				$unpatrolled = true;
			}
			$userlinks[$u]++ ;
		}

		# Sort the list and convert to text
		krsort ( $userlinks ) ;
		asort ( $userlinks ) ;
		$users = array () ;
		foreach ( $userlinks as $userlink => $count) {
			$text = $userlink ;
			if ( $count > 1 ) $text .= " ({$count}&times;)" ;
			array_push ( $users , $text ) ;
		}
		$users = ' <font size="-1">['.implode('; ',$users).']</font>' ;

		# Arrow
		$rci = 'RCI'.$this->rcCacheIndex ;
		$rcl = 'RCL'.$this->rcCacheIndex ;
		$rcm = 'RCM'.$this->rcCacheIndex ;
		$toggleLink = "javascript:toggleVisibility('$rci','$rcm','$rcl')" ;
		$arrowdir = $wgContLang->isRTL() ? 'l' : 'r';
		$tl  = '<span id="'.$rcm.'"><a href="'.$toggleLink.'"><img src="'.$wgStylePath.'/common/images/Arr_'.$arrowdir.'.png" width="12" height="12" alt="+" /></a></span>' ;
		$tl .= '<span id="'.$rcl.'" style="display:none"><a href="'.$toggleLink.'"><img src="'.$wgStylePath.'/common/images/Arr_d.png" width="12" height="12" alt="-" /></a></span>' ;
		$r .= $tl ;

		# Main line
		# M/N
		$r .= '<tt>' ;
		if ( $isnew ) $r .= $N ;
		else $r .= '&nbsp;' ;
		$r .= '&nbsp;' ; # Minor
		if ( $unpatrolled ) {
			$r .= "!";
		} else {
			$r .= "&nbsp;";
		}

		# Timestamp
		$r .= ' '.$block[0]->timestamp.' ' ;
		$r .= '</tt>' ;

		# Article link
		$link = $block[0]->link ;
		if ( $block[0]->watched ) $link = '<strong>'.$link.'</strong>' ;
		$r .= $link ;

		$curIdEq = 'curid=' . $block[0]->mAttribs['rc_cur_id'];
		if ( $block[0]->mAttribs['rc_type'] != RC_LOG ) {
			# Changes
			$r .= ' ('.count($block).' ' ;
			if ( $isnew ) $r .= wfMsg('changes');
			else $r .= $this->makeKnownLinkObj( $block[0]->getTitle() , wfMsg('changes') ,
				$curIdEq.'&diff=0&oldid='.$oldid ) ;
			$r .= '; ' ;

			# History
			$r .= $this->makeKnownLinkObj( $block[0]->getTitle(), wfMsg( 'history' ), $curIdEq.'&action=history' );
			$r .= ')' ;
		}

		$r .= $users ;
		$r .= "<br />\n" ;

		# Sub-entries
		$r .= '<div id="'.$rci.'" style="display:none">' ;
		foreach ( $block AS $rcObj ) {
			# Get rc_xxxx variables
			extract( $rcObj->mAttribs );

			$r .= '<img src="'.$wgStylePath.'/common/images/Arr_.png" width="12" height="12" />';
			$r .= '<tt>&nbsp; &nbsp; &nbsp; &nbsp;' ;
			if ( $rc_new ) {
				$r .= $N ;
			} else {
				$r .= '&nbsp;' ;
			}

			if ( $rc_minor ) {
				$r .= $M ;
			} else {
				$r .= '&nbsp;' ;
			}

			if ( $rcObj->unpatrolled ) {
				$r .= "!";
			} else {
				$r .= "&nbsp;";
			}

			$r .= '&nbsp;</tt>' ;

			$o = '' ;
			if ( $rc_last_oldid != 0 ) {
				$o = 'oldid='.$rc_last_oldid ;
			}
			if ( $rc_type == RC_LOG ) {
				$link = $rcObj->timestamp ;
			} else {
				$link = $this->makeKnownLinkObj( $rcObj->getTitle(), $rcObj->timestamp , "{$curIdEq}&$o" ) ;
			}
			$link = '<tt>'.$link.'</tt>' ;

			$r .= $link ;
			$r .= ' (' ;
			$r .= $rcObj->curlink ;
			$r .= '; ' ;
			$r .= $rcObj->lastlink ;
			$r .= ') . . '.$rcObj->userlink ;
			$r .= $rcObj->usertalklink ;
			if ( $rc_comment != '' ) {
				$rc_comment=$this->formatComment($rc_comment, $rcObj->getTitle());
				$r .= $wgContLang->emphasize( ' ('.$rc_comment.')' ) ;
			}
			$r .= "<br />\n" ;
		}
		$r .= "</div>\n" ;

		$this->rcCacheIndex++ ;
		return $r ;
	}

	/**
	 * If enhanced RC is in use, this function takes the previously cached
	 * RC lines, arranges them, and outputs the HTML
	 */
	function recentChangesBlock () {
		global $wgStylePath ;
		if ( count ( $this->rc_cache ) == 0 ) return '' ;
		$blockOut = '';
		foreach ( $this->rc_cache AS $secureName => $block ) {
			if ( count ( $block ) < 2 ) {
				$blockOut .= $this->recentChangesBlockLine ( array_shift ( $block ) ) ;
			} else {
				$blockOut .= $this->recentChangesBlockGroup ( $block ) ;
			}
		}

		return '<div>'.$blockOut.'</div>' ;
	}

	/**
	 * Called in a loop over all displayed RC entries
	 * Either returns the line, or caches it for later use
	 */
	function recentChangesLine( &$rc, $watched = false ) {
		global $wgUser ;
		$usenew = $wgUser->getOption( 'usenewrc' );
		if ( $usenew )
			$line = $this->recentChangesLineNew ( $rc, $watched ) ;
		else
			$line = $this->recentChangesLineOld ( $rc, $watched ) ;
		return $line ;
	}

	function recentChangesLineOld( &$rc, $watched = false ) {
		global $wgTitle, $wgLang, $wgContLang, $wgUser, $wgRCSeconds, $wgUseRCPatrol, $wgOnlySysopsCanPatrol;

		# Extract DB fields into local scope
		extract( $rc->mAttribs );
		$curIdEq = 'curid=' . $rc_cur_id;

		# Should patrol-related stuff be shown?
		$unpatrolled = $wgUseRCPatrol && $wgUser->getID() != 0 && 
		  ( !$wgOnlySysopsCanPatrol || $wgUser->isAllowed('patrol') ) && $rc_patrolled == 0;
		
		# Make date header if necessary
		$date = $wgContLang->date( $rc_timestamp, true);
		$uidate = $wgLang->date( $rc_timestamp, true);
		$s = '';
		if ( $date != $this->lastdate ) {
			if ( '' != $this->lastdate ) { $s .= "</ul>\n"; }
			$s .= "<h4>{$uidate}</h4>\n<ul class=\"special\">";
			$this->lastdate = $date;
			$this->rclistOpen = true;
		}

		$s .= '<li>';

		if ( $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			# Diff
			$s .= '(' . wfMsg( 'diff' ) . ') (';
			# Hist
			$s .= $this->makeKnownLinkObj( $rc->getMovedToTitle(), wfMsg( 'hist' ), 'action=history' ) .
				') . . ';

			# "[[x]] moved to [[y]]"
			$msg = ( $rc_type == RC_MOVE ) ? '1movedto2' : '1movedto2_redir';
			$s .= wfMsg( $msg, $this->makeKnownLinkObj( $rc->getTitle(), '', 'redirect=no' ),
				$this->makeKnownLinkObj( $rc->getMovedToTitle(), '' ) );
		} elseif( $rc_namespace == NS_SPECIAL && preg_match( '!^Log/(.*)$!', $rc_title, $matches ) ) {
			# Log updates, etc
			$logtype = $matches[1];
			$logname = LogPage::logName( $logtype );
			$s .= '(' . $this->makeKnownLinkObj( $rc->getTitle(), $logname ) . ')';
		} else {
			# Diff link
			if ( $rc_type == RC_NEW || $rc_type == RC_LOG ) {
				$diffLink = wfMsg( 'diff' );
			} else {
				if ( $unpatrolled )
					$rcidparam = "&rcid={$rc_id}";
				else
					$rcidparam = "";
				$diffLink = $this->makeKnownLinkObj( $rc->getTitle(), wfMsg( 'diff' ),
				  "{$curIdEq}&diff={$rc_this_oldid}&oldid={$rc_last_oldid}{$rcidparam}",
				  '', '', ' tabindex="'.$rc->counter.'"');
			}
			$s .= '('.$diffLink.') (';

			# History link
			$s .= $this->makeKnownLinkObj( $rc->getTitle(), wfMsg( 'hist' ), $curIdEq.'&action=history' );
			$s .= ') . . ';

			# M, N and ! (minor, new and unpatrolled)
			if ( $rc_minor ) { $s .= ' <span class="minor">'.wfMsg( "minoreditletter" ).'</span>'; }
			if ( $rc_type == RC_NEW ) { $s .= '<span class="newpage">'.wfMsg( "newpageletter" ).'</span>'; }
			if ( !$rc_patrolled ) { $s .= ' <span class="unpatrolled">!</span>'; }

			# Article link
			# If it's a new article, there is no diff link, but if it hasn't been
			# patrolled yet, we need to give users a way to do so
			if ( $unpatrolled && $rc_type == RC_NEW )
				$articleLink = $this->makeKnownLinkObj( $rc->getTitle(), '', "rcid={$rc_id}" );
			else
				$articleLink = $this->makeKnownLinkObj( $rc->getTitle(), '' );

			if ( $watched ) {
				$articleLink = '<strong>'.$articleLink.'</strong>';
			}
			$s .= ' '.$articleLink;

		}

		# Timestamp
		$s .= '; ' . $wgLang->time( $rc_timestamp, true, $wgRCSeconds ) . ' . . ';

		# User link (or contributions for unregistered users)
		if ( 0 == $rc_user ) {
			$userLink = $this->makeKnownLink( $wgContLang->specialPage( 'Contributions' ),
			$rc_user_text, 'target=' . $rc_user_text );
		} else {
			$userLink = $this->makeLink( $wgContLang->getNsText( NS_USER ) . ':'.$rc_user_text, $rc_user_text );
		}
		$s .= $userLink;

		# User talk link
		$talkname=$wgContLang->getNsText(NS_TALK); # use the shorter name
		global $wgDisableAnonTalk;
		if( 0 == $rc_user && $wgDisableAnonTalk ) {
			$userTalkLink = '';
		} else {
			$utns=$wgContLang->getNsText(NS_USER_TALK);
			$userTalkLink= $this->makeLink($utns . ':'.$rc_user_text, $talkname );
		}
		# Block link
		$blockLink='';
		if ( ( 0 == $rc_user ) && $wgUser->isAllowed('block') ) {
			$blockLink = $this->makeKnownLink( $wgContLang->specialPage(
			  'Blockip' ), wfMsg( 'blocklink' ), 'ip='.$rc_user_text );

		}
		if($blockLink) {
			if($userTalkLink) $userTalkLink .= ' | ';
			$userTalkLink .= $blockLink;
		}
		if($userTalkLink) $s.=' ('.$userTalkLink.')';

		# Add comment
		if ( '' != $rc_comment && '*' != $rc_comment && $rc_type != RC_MOVE && $rc_type != RC_MOVE_OVER_REDIRECT ) {
			$rc_comment=$this->formatComment($rc_comment,$rc->getTitle());
			$s .= $wgContLang->emphasize(' (' . $rc_comment . ')');
		}
		$s .= "</li>\n";

		return $s;
	}

	function recentChangesLineNew( &$baseRC, $watched = false ) {
		global $wgTitle, $wgLang, $wgContLang, $wgUser, $wgRCSeconds;
		global $wgUseRCPatrol, $wgOnlySysopsCanPatrol;
		
		# Create a specialised object
		$rc = RCCacheEntry::newFromParent( $baseRC ) ;

		# Extract fields from DB into the function scope (rc_xxxx variables)
		extract( $rc->mAttribs );
		$curIdEq = 'curid=' . $rc_cur_id;

		# If it's a new day, add the headline and flush the cache
		$date = $wgContLang->date( $rc_timestamp, true);
		$uidate = $wgLang->date( $rc_timestamp, true);
		$ret = '';
		if ( $date != $this->lastdate ) {
			# Process current cache
			$ret = $this->recentChangesBlock () ;
			$this->rc_cache = array() ;
			$ret .= "<h4>{$uidate}</h4>\n";
			$this->lastdate = $date;
		}
		
		# Should patrol-related stuff be shown?
		if ( $wgUseRCPatrol && $wgUser->getID() != 0 && 
		  ( !$wgOnlySysopsCanPatrol || $wgUser->isAllowed('patrol') )) {
		  	$rc->unpatrolled = !$rc_patrolled;
		} else {
			$rc->unpatrolled = false;
		}
		
		# Make article link
		if ( $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			$msg = ( $rc_type == RC_MOVE ) ? "1movedto2" : "1movedto2_redir";
			$clink = wfMsg( $msg, $this->makeKnownLinkObj( $rc->getTitle(), '', 'redirect=no' ),
			  $this->makeKnownLinkObj( $rc->getMovedToTitle(), '' ) );
		} elseif( $rc_namespace == NS_SPECIAL && preg_match( '!^Log/(.*)$!', $rc_title, $matches ) ) {
			# Log updates, etc
			$logtype = $matches[1];
			$logname = LogPage::logName( $logtype );
			$clink = '(' . $this->makeKnownLinkObj( $rc->getTitle(), $logname ) . ')';
		} elseif ( $rc->unpatrolled && $rc_type == RC_NEW ) {
			# Unpatrolled new page, give rc_id in query
			$clink = $this->makeKnownLinkObj( $rc->getTitle(), '', "rcid={$rc_id}" );
		} else {
			$clink = $this->makeKnownLinkObj( $rc->getTitle(), '' ) ;
		}

		$time = $wgContLang->time( $rc_timestamp, true, $wgRCSeconds );
		$rc->watched = $watched ;
		$rc->link = $clink ;
		$rc->timestamp = $time;

		# Make "cur" and "diff" links
		if ( ( $rc_type == RC_NEW && $rc_this_oldid == 0 ) || $rc_type == RC_LOG || $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			$curLink = wfMsg( 'cur' );
			$diffLink = wfMsg( 'diff' );
		} else {
			$query = $curIdEq.'&diff=0&oldid='.$rc_this_oldid;
			$aprops = ' tabindex="'.$baseRC->counter.'"';
			$curLink = $this->makeKnownLinkObj( $rc->getTitle(), wfMsg( 'cur' ), $query, '' ,'' , $aprops );
			$diffLink = $this->makeKnownLinkObj( $rc->getTitle(), wfMsg( 'diff'), $query, '' ,'' , $aprops );
		}

		# Make "last" link
		$titleObj = $rc->getTitle();
		if ( $rc->unpatrolled ) {
			$rcIdQuery = "&rcid={$rc_id}";
		} else {
			$rcIdQuery = '';
		}
		if ( $rc_last_oldid == 0 || $rc_type == RC_LOG || $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			$lastLink = wfMsg( 'last' );
		} else {
			$lastLink = $this->makeKnownLinkObj( $rc->getTitle(), wfMsg( 'last' ),
			  $curIdEq.'&diff='.$rc_this_oldid.'&oldid='.$rc_last_oldid . $rcIdQuery );
		}

		# Make user link (or user contributions for unregistered users)
		if ( $rc_user == 0 ) {
			$userLink = $this->makeKnownLink( $wgContLang->specialPage( 'Contributions' ),
			$rc_user_text, 'target=' . $rc_user_text );
		} else {
			$userLink = $this->makeLink( $wgContLang->getNsText(
			  Namespace::getUser() ) . ':'.$rc_user_text, $rc_user_text );
		}

		$rc->userlink = $userLink;
		$rc->lastlink = $lastLink;
		$rc->curlink  = $curLink;
		$rc->difflink = $diffLink;

		# Make user talk link
		$utns=$wgContLang->getNsText(NS_USER_TALK);
		$talkname=$wgContLang->getNsText(NS_TALK); # use the shorter name
		$userTalkLink= $this->makeLink($utns . ':'.$rc_user_text, $talkname );

		global $wgDisableAnonTalk;
		if ( ( 0 == $rc_user ) && $wgUser->isAllowed('block') ) {
			$blockLink = $this->makeKnownLink( $wgContLang->specialPage(
			  'Blockip' ), wfMsg( 'blocklink' ), 'ip='.$rc_user_text );
			if( $wgDisableAnonTalk )
				$rc->usertalklink = ' ('.$blockLink.')';
			else
				$rc->usertalklink = ' ('.$userTalkLink.' | '.$blockLink.')';
		} else {
			if( $wgDisableAnonTalk && ($rc_user == 0) )
				$rc->usertalklink = '';
			else
				$rc->usertalklink = ' ('.$userTalkLink.')';
		}

		# Put accumulated information into the cache, for later display
		# Page moves go on their own line
		$title = $rc->getTitle();
		$secureName = $title->getPrefixedDBkey();
		if ( $rc_type == RC_MOVE || $rc_type == RC_MOVE_OVER_REDIRECT ) {
			# Use an @ character to prevent collision with page names
			$this->rc_cache['@@' . ($this->rcMoveIndex++)] = array($rc);
		} else {
			if ( !isset ( $this->rc_cache[$secureName] ) ) $this->rc_cache[$secureName] = array() ;
			array_push ( $this->rc_cache[$secureName] , $rc ) ;
		}
		return $ret;
	}

	function endImageHistoryList() {
		$s = "</ul>\n";
		return $s;
	}

	/**
	 * This function is called by all recent changes variants, by the page history,
	 * and by the user contributions list. It is responsible for formatting edit
	 * comments. It escapes any HTML in the comment, but adds some CSS to format
	 * auto-generated comments (from section editing) and formats [[wikilinks]].
	 *
	 * The &$title parameter must be a title OBJECT. It is used to generate a
	 * direct link to the section in the autocomment.
	 * @author Erik Moeller <moeller@scireview.de>
	 *
	 * Note: there's not always a title to pass to this function.
	 * Since you can't set a default parameter for a reference, I've turned it
	 * temporarily to a value pass. Should be adjusted further. --brion
	 */
	function formatComment($comment, $title = NULL) {
		global $wgContLang;
		$comment = htmlspecialchars( $comment );

		# The pattern for autogen comments is / * foo * /, which makes for
		# some nasty regex.
		# We look for all comments, match any text before and after the comment,
		# add a separator where needed and format the comment itself with CSS
		while (preg_match('/(.*)\/\*\s*(.*?)\s*\*\/(.*)/', $comment,$match)) {
			$pre=$match[1];
			$auto=$match[2];
			$post=$match[3];
			$link='';
			if($title) {
				$section=$auto;

				# This is hackish but should work in most cases.
				$section=str_replace('[[','',$section);
				$section=str_replace(']]','',$section);
				$title->mFragment=$section;
				$link=$this->makeKnownLinkObj($title,wfMsg('sectionlink'));
			}
			$sep='-';
			$auto=$link.$auto;
			if($pre) { $auto = $sep.' '.$auto; }
			if($post) { $auto .= ' '.$sep; }
			$auto='<span class="autocomment">'.$auto.'</span>';
			$comment=$pre.$auto.$post;
		}

		# format regular and media links - all other wiki formatting
		# is ignored
		$medians = $wgContLang->getNsText(Namespace::getMedia()).':';
		while(preg_match('/\[\[(.*?)(\|(.*?))*\]\](.*)$/',$comment,$match)) {
			# Handle link renaming [[foo|text]] will show link as "text"
			if( "" != $match[3] ) {
				$text = $match[3];
			} else {
				$text = $match[1];
			}
			if( preg_match( '/^' . $medians . '(.*)$/i', $match[1], $submatch ) ) {
				# Media link; trail not supported.
				$linkRegexp = '/\[\[(.*?)\]\]/';
				$thelink = $this->makeMediaLink( $submatch[1], "", $text );
			} else {
				# Other kind of link
				if( preg_match( wfMsgForContent( "linktrail" ), $match[4], $submatch ) ) {
					$trail = $submatch[1];
				} else {
					$trail = "";
				}
				$linkRegexp = '/\[\[(.*?)\]\]' . preg_quote( $trail, '/' ) . '/';
				if ($match[1][0] == ':')
					$match[1] = substr($match[1], 1);
				$thelink = $this->makeLink( $match[1], $text, "", $trail );
			}
			$comment = preg_replace( $linkRegexp, $thelink, $comment, 1 );
		}
		return $comment;
	}

	function imageHistoryLine( $iscur, $timestamp, $img, $user, $usertext, $size, $description ) {
		global $wgUser, $wgLang, $wgContLang, $wgTitle;

		$datetime = $wgLang->timeanddate( $timestamp, true );
		$del = wfMsg( 'deleteimg' );
		$delall = wfMsg( 'deleteimgcompletely' );
		$cur = wfMsg( 'cur' );

		if ( $iscur ) {
			$url = Image::wfImageUrl( $img );
			$rlink = $cur;
			if ( $wgUser->isAllowed('delete') ) {
				$link = $wgTitle->escapeLocalURL( 'image=' . $wgTitle->getPartialURL() .
				  '&action=delete' );
				$style = $this->getInternalLinkAttributes( $link, $delall );

				$dlink = '<a href="'.$link.'"'.$style.'>'.$delall.'</a>';
			} else {
				$dlink = $del;
			}
		} else {
			$url = htmlspecialchars( wfImageArchiveUrl( $img ) );
			if( $wgUser->getID() != 0 && $wgTitle->userCanEdit() ) {
				$rlink = $this->makeKnownLink( $wgTitle->getPrefixedText(),
				           wfMsg( 'revertimg' ), 'action=revert&oldimage=' .
				           urlencode( $img ) );
				$dlink = $this->makeKnownLink( $wgTitle->getPrefixedText(),
				           $del, 'action=delete&oldimage=' . urlencode( $img ) );
			} else {
				# Having live active links for non-logged in users
				# means that bots and spiders crawling our site can
				# inadvertently change content. Baaaad idea.
				$rlink = wfMsg( 'revertimg' );
				$dlink = $del;
			}
		}
		if ( 0 == $user ) {
			$userlink = $usertext;
		} else {
			$userlink = $this->makeLink( $wgContLang->getNsText( Namespace::getUser() ) .
			               ':'.$usertext, $usertext );
		}
		$nbytes = wfMsg( 'nbytes', $size );
		$style = $this->getInternalLinkAttributes( $url, $datetime );

		$s = "<li> ({$dlink}) ({$rlink}) <a href=\"{$url}\"{$style}>{$datetime}</a>"
		  . " . . {$userlink} ({$nbytes})";

		if ( '' != $description && '*' != $description ) {
			$sk=$wgUser->getSkin();
			$s .= $wgContLang->emphasize(' (' . $sk->formatComment($description,$wgTitle) . ')');
		}
		$s .= "</li>\n";
		return $s;
	}

	function tocIndent($level) {
		return str_repeat( '<div class="tocindent">'."\n", $level>0 ? $level : 0 );
	}

	function tocUnindent($level) {
		return str_repeat( "</div>\n", $level>0 ? $level : 0 );
	}

	/**
	 * parameter level defines if we are on an indentation level
	 */
	function tocLine( $anchor, $tocline, $level ) {
		$link = '<a href="#'.$anchor.'">'.$tocline.'</a><br />';
		if($level) {
			return $link."\n";
		} else {
			return '<div class="tocline">'.$link."</div>\n";
		}

	}

	function tocTable($toc) {
		# note to CSS fanatics: putting this in a div does not work -- div won't auto-expand
		# try min-width & co when somebody gets a chance
		$hideline = ' <script type="text/javascript">showTocToggle("' . addslashes( wfMsg('showtoc') ) . '","' . addslashes( wfMsg('hidetoc') ) . '")</script>';
		return
		'<table border="0" id="toc"><tr id="toctitle"><td align="center">'."\n".
		'<b>'.wfMsgForContent('toc').'</b>' .
		$hideline .
		'</td></tr><tr id="tocinside"><td>'."\n".
		$toc."</td></tr></table>\n";
	}

	/**
	 * These two do not check for permissions: check $wgTitle->userCanEdit
	 * before calling them
	 */
	function editSectionScriptForOther( $title, $section, $head ) {
		$ttl = Title::newFromText( $title );
		$url = $ttl->escapeLocalURL( 'action=edit&section='.$section );
		return '<span oncontextmenu=\'document.location="'.$url.'";return false;\'>'.$head.'</span>';
	}

	function editSectionScript( $nt, $section, $head ) {
		global $wgRequest;
		if( $wgRequest->getInt( 'oldid' ) && ( $wgRequest->getVal( 'diff' ) != '0' ) ) {
			return $head;
		}
		$url = $nt->escapeLocalURL( 'action=edit&section='.$section );
		return '<span oncontextmenu=\'document.location="'.$url.'";return false;\'>'.$head.'</span>';
	}

	function editSectionLinkForOther( $title, $section ) {
		global $wgRequest;
		global $wgContLang;

		$title = Title::newFromText($title);
		$editurl = '&section='.$section;
		$url = $this->makeKnownLink($title->getPrefixedText(),wfMsg('editsection'),'action=edit'.$editurl);

		if( $wgContLang->isRTL() ) {
			$farside = 'left';
			$nearside = 'right';
		} else {
			$farside = 'right';
			$nearside = 'left';
		}
		return "<div class=\"editsection\" style=\"float:$farside;margin-$nearside:5px;\">[".$url."]</div>";

	}

	function editSectionLink( $nt, $section ) {
		global $wgRequest;
		global $wgContLang;

		if( $wgRequest->getInt( 'oldid' ) && ( $wgRequest->getVal( 'diff' ) != '0' ) ) {
			# Section edit links would be out of sync on an old page.
			# But, if we're diffing to the current page, they'll be
			# correct.
			return '';
		}

		$editurl = '&section='.$section;
		$url = $this->makeKnownLink($nt->getPrefixedText(),wfMsg('editsection'),'action=edit'.$editurl);

		if( $wgContLang->isRTL() ) {
			$farside = 'left';
			$nearside = 'right';
		} else {
			$farside = 'right';
			$nearside = 'left';
		}
		return "<div class=\"editsection\" style=\"float:$farside;margin-$nearside:5px;\">[".$url."]</div>";

	}

	/**
	 * This function is called by EditPage.php and shows a bulletin board style
	 * toolbar for common editing functions. It can be disabled in the user
	 * preferences.
	 * The necessary JavaScript code can be found in style/wikibits.js.
	 */
	function getEditToolbar() {
		global $wgStylePath, $wgLang, $wgMimeType;

		/**
		 * toolarray an array of arrays which each include the filename of
		 * the button image (without path), the opening tag, the closing tag,
		 * and optionally a sample text that is inserted between the two when no
		 * selection is highlighted.
		 * The tip text is shown when the user moves the mouse over the button.
		 *
		 * Already here are accesskeys (key), which are not used yet until someone
		 * can figure out a way to make them work in IE. However, we should make
		 * sure these keys are not defined on the edit page.
		 */
		$toolarray=array(
			array(	'image'=>'button_bold.png',
					'open'	=>	"\'\'\'",
					'close'	=>	"\'\'\'",
					'sample'=>	wfMsg('bold_sample'),
					'tip'	=>	wfMsg('bold_tip'),
					'key'	=>	'B'
				),
			array(	'image'=>'button_italic.png',
					'open'	=>	"\'\'",
					'close'	=>	"\'\'",
					'sample'=>	wfMsg('italic_sample'),
					'tip'	=>	wfMsg('italic_tip'),
					'key'	=>	'I'
				),
			array(	'image'=>'button_link.png',
					'open'	=>	'[[',
					'close'	=>	']]',
					'sample'=>	wfMsg('link_sample'),
					'tip'	=>	wfMsg('link_tip'),
					'key'	=>	'L'
				),
			array(	'image'=>'button_extlink.png',
					'open'	=>	'[',
					'close'	=>	']',
					'sample'=>	wfMsg('extlink_sample'),
					'tip'	=>	wfMsg('extlink_tip'),
					'key'	=>	'X'
				),
			array(	'image'=>'button_headline.png',
					'open'	=>	"\\n== ",
					'close'	=>	" ==\\n",
					'sample'=>	wfMsg('headline_sample'),
					'tip'	=>	wfMsg('headline_tip'),
					'key'	=>	'H'
				),
			array(	'image'=>'button_image.png',
					'open'	=>	'[['.$wgLang->getNsText(NS_IMAGE).":",
					'close'	=>	']]',
					'sample'=>	wfMsg('image_sample'),
					'tip'	=>	wfMsg('image_tip'),
					'key'	=>	'D'
				),
			array(	'image'	=>	'button_media.png',
					'open'	=>	'[['.$wgLang->getNsText(NS_MEDIA).':',
					'close'	=>	']]',
					'sample'=>	wfMsg('media_sample'),
					'tip'	=>	wfMsg('media_tip'),
					'key'	=>	'M'
				),
			array(	'image'	=>	'button_math.png',
					'open'	=>	"\\<math\\>",
					'close'	=>	"\\</math\\>",
					'sample'=>	wfMsg('math_sample'),
					'tip'	=>	wfMsg('math_tip'),
					'key'	=>	'C'
				),
			array(	'image'	=>	'button_nowiki.png',
					'open'	=>	"\\<nowiki\\>",
					'close'	=>	"\\</nowiki\\>",
					'sample'=>	wfMsg('nowiki_sample'),
					'tip'	=>	wfMsg('nowiki_tip'),
					'key'	=>	'N'
				),
			array(	'image'	=>	'button_sig.png',
					'open'	=>	'--~~~~',
					'close'	=>	'',
					'sample'=>	'',
					'tip'	=>	wfMsg('sig_tip'),
					'key'	=>	'Y'
				),
			array(	'image'	=>	'button_hr.png',
					'open'	=>	"\\n----\\n",
					'close'	=>	'',
					'sample'=>	'',
					'tip'	=>	wfMsg('hr_tip'),
					'key'	=>	'R'
				)
		);
		$toolbar ="<script type='text/javascript'>\n/*<![CDATA[*/\n";

		$toolbar.="document.writeln(\"<div id='toolbar'>\");\n";
		foreach($toolarray as $tool) {

			$image=$wgStylePath.'/common/images/'.$tool['image'];
			$open=$tool['open'];
			$close=$tool['close'];
			$sample = addslashes( $tool['sample'] );

			// Note that we use the tip both for the ALT tag and the TITLE tag of the image.
			// Older browsers show a "speedtip" type message only for ALT.
			// Ideally these should be different, realistically they
			// probably don't need to be.
			$tip = addslashes( $tool['tip'] );

			#$key = $tool["key"];

			$toolbar.="addButton('$image','$tip','$open','$close','$sample');\n";
		}

		$toolbar.="addInfobox('" . addslashes( wfMsg( "infobox" ) ) . "','" . addslashes(wfMsg("infobox_alert")) . "');\n";
		$toolbar.="document.writeln(\"</div>\");\n";

		$toolbar.="/*]]>*/\n</script>";
		return $toolbar;
	}

	/**
	 * @access public
	 */
	function suppressUrlExpansion() {
		return false;
	}
}

}
?>
