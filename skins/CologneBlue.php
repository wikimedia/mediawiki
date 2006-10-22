<?php
/**
 * See skin.txt
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/**
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */
class SkinCologneBlue extends Skin {

	private $searchboxes = '';
	// How many search boxes have we made?  Avoid duplicate id's.

	function getStylesheet() {
		return 'common/cologneblue.css';
	}
	function getSkinName() {
		return "cologneblue";
	}

	function doBeforeContent() {

		$s = "";
		$qb = $this->qbSetting();
		$mainPageObj = Title::newMainPage();

		$s .= "\n<div id='content'>\n<div id='topbar'>" .
		  "<table width='100%' border='0' cellspacing='0' cellpadding='8'><tr>";

		$s .= "<td class='top' align='left' valign='middle' nowrap='nowrap'>";
		$s .= "<a href=\"" . $mainPageObj->escapeLocalURL() . "\">";
		$s .= "<span id='sitetitle'>" . wfMsg( "sitetitle" ) . "</span></a>";

		$s .= "</td><td class='top' align='right' valign='bottom' width='100%'>";
		$s .= $this->sysLinks();
		$s .= "</td></tr><tr><td valign='top'>";

		$s .= "<font size='-1'><span id='sitesub'>";
		$s .= htmlspecialchars( wfMsg( "sitesubtitle" ) ) . "</span></font>";
		$s .= "</td><td align='right'>" ;

		$s .= "<font size='-1'><span id='langlinks'>" ;
		$s .= str_replace ( "<br />" , "" , $this->otherLanguages() );
		$cat = $this->getCategoryLinks();
		if( $cat ) $s .= "<br />$cat\n";
		$s .= "<br />" . $this->pageTitleLinks();
		$s .= "</span></font>";

		$s .= "</td></tr></table>\n";

		$s .= "\n</div>\n<div id='article'>";

		$notice = wfGetSiteNotice();
		if( $notice ) {
			$s .= "\n<div id='siteNotice'>$notice</div>\n";
		}
		$s .= $this->pageTitle();
		$s .= $this->pageSubtitle() . "\n";
		return $s;
	}

	function doAfterContent()
	{
		global $wgOut;

		$s = "\n</div><br clear='all' />\n";

		$s .= "\n<div id='footer'>";
		$s .= "<table width='98%' border='0' cellspacing='0'><tr>";

		$qb = $this->qbSetting();
		if ( 1 == $qb || 3 == $qb ) { # Left
			$s .= $this->getQuickbarCompensator();
		}
		$s .= "<td class='bottom' align='center' valign='top'>";

		$s .= $this->bottomLinks();
		$s .= "\n<br />" . $this->makeKnownLink( wfMsgForContent( "mainpage" ) ) . " | "
		  . $this->aboutLink() . " | "
		  . $this->searchForm( wfMsg( "qbfind" ) );

		$s .= "\n<br />" . $this->pageStats();

		$s .= "</td>";
		if ( 2 == $qb ) { # Right
			$s .= $this->getQuickbarCompensator();
		}
		$s .= "</tr></table>\n</div>\n</div>\n";

		if ( 0 != $qb ) { $s .= $this->quickBar(); }
		return $s;
	}

	function doGetUserStyles() {
		global $wgOut;
		$s = parent::doGetUserStyles();
		$qb = $this->qbSetting();

		if ( 2 == $qb ) { # Right
			$s .= "#quickbar { position: absolute; right: 4px; }\n" .
			  "#article { margin-left: 4px; margin-right: 148px; }\n";
		} else if ( 1 == $qb ) {
			$s .= "#quickbar { position: absolute; left: 4px; }\n" .
			  "#article { margin-left: 148px; margin-right: 4px; }\n";
		} else if ( 3 == $qb ) { # Floating left
			$s .= "#quickbar { position:absolute; left:4px } \n" .
			  "#topbar { margin-left: 148px }\n" .
			  "#article { margin-left:148px; margin-right: 4px; } \n" .
			  "body>#quickbar { position:fixed; left:4px; top:4px; overflow:auto ;bottom:4px;} \n"; # Hides from IE
		} else if ( 4 == $qb ) { # Floating right
			$s .= "#quickbar { position: fixed; right: 4px; } \n" .
			  "#topbar { margin-right: 148px }\n" .
			  "#article { margin-right: 148px; margin-left: 4px; } \n" .
			  "body>#quickbar { position: fixed; right: 4px; top: 4px; overflow: auto ;bottom:4px;} \n"; # Hides from IE
		}
		return $s;
	}

	function sysLinks() {
		global $wgUser, $wgContLang, $wgTitle;
		$li = $wgContLang->specialPage("Userlogin");
		$lo = $wgContLang->specialPage("Userlogout");

		$rt = $wgTitle->getPrefixedURL();
		if ( 0 == strcasecmp( urlencode( $lo ), $rt ) ) {
			$q = "";
		} else {
			$q = "returnto={$rt}";
		}

		$s = "" .
		  $this->makeKnownLink( wfMsgForContent( "mainpage" ), wfMsg( "mainpage" ) )
		  . " | " .
		  $this->makeKnownLink( wfMsgForContent( "aboutpage" ), wfMsg( "about" ) )
		  . " | " .
		  $this->makeKnownLink( wfMsgForContent( "helppage" ), wfMsg( "help" ) )
		  . " | " .
		  $this->makeKnownLink( wfMsgForContent( "faqpage" ), wfMsg("faq") )
		  . " | " .
		  $this->specialLink( "specialpages" ) . " | ";

		if ( $wgUser->isLoggedIn() ) {
			$s .=  $this->makeKnownLink( $lo, wfMsg( "logout" ), $q );
		} else {
			$s .=  $this->makeKnownLink( $li, wfMsg( "login" ), $q );
		}

		/* show links to different language variants */
		global $wgDisableLangConversion;
		$variants = $wgContLang->getVariants();
		if( !$wgDisableLangConversion && sizeof( $variants ) > 1 ) {
			$actstr = '';
			foreach( $variants as $code ) {
				$varname = $wgContLang->getVariantname( $code );
				if( $varname == 'disable' )
					continue;
				$s .= ' | <a href="' . $wgTitle->getLocalUrl( 'variant=' . $code ) . '">' . $varname . '</a>';
			}
		}

		return $s;
	}

	/**
	 * Compute the sidebar
	 * @access private
	 */
	function quickBar()
	{
		global $wgOut, $wgTitle, $wgUser, $wgLang, $wgContLang, $wgEnableUploads;

		$tns=$wgTitle->getNamespace();

		$s = "\n<div id='quickbar'>";

		$sep = "<br />";
		$s .= $this->menuHead( "qbfind" );
		$s .= $this->searchForm();

		$s .= $this->menuHead( "qbbrowse" );

		# Use the first heading from the Monobook sidebar as the "browse" section
		$bar = $this->buildSidebar();
		$browseLinks = reset( $bar );

		foreach ( $browseLinks as $link ) {
			if ( $link['text'] != '-' ) {
				$s .= "<a href=\"{$link['href']}\">" .
					htmlspecialchars( $link['text'] ) . '</a>' . $sep;
			}
		}

		if ( $wgOut->isArticle() ) {
			$s .= $this->menuHead( "qbedit" );
			$s .= "<strong>" . $this->editThisPage() . "</strong>";

			$s .= $sep . $this->makeKnownLink( wfMsgForContent( "edithelppage" ), wfMsg( "edithelp" ) );

			if( $wgUser->isLoggedIn() ) {
				$s .= $sep . $this->moveThisPage();
			}
			if ( $wgUser->isAllowed('delete') ) {
				$dtp = $this->deleteThisPage();
				if ( "" != $dtp ) {
					$s .= $sep . $dtp;
				}
			}
			if ( $wgUser->isAllowed('protect') ) {
				$ptp = $this->protectThisPage();
				if ( "" != $ptp ) {
					$s .= $sep . $ptp;
				}
			}
			$s .= $sep;

			$s .= $this->menuHead( "qbpageoptions" );
			$s .= $this->talkLink()
			  . $sep . $this->commentLink()
			  . $sep . $this->printableLink();
			if ( $wgUser->isLoggedIn() ) {
				$s .= $sep . $this->watchThisPage();
			}

			$s .= $sep;

			$s .= $this->menuHead("qbpageinfo")
			  . $this->historyLink()
			  . $sep . $this->whatLinksHere()
			  . $sep . $this->watchPageLinksLink();

			if( $tns == NS_USER || $tns == NS_USER_TALK ) {
				$id=User::idFromName($wgTitle->getText());
				if ($id != 0) {
					$s .= $sep . $this->userContribsLink();
					if( $this->showEmailUser( $id ) ) {
						$s .= $sep . $this->emailUserLink();
					}
				}
			}
			$s .= $sep;
		}

		$s .= $this->menuHead( "qbmyoptions" );
		if ( $wgUser->isLoggedIn() ) {
			$name = $wgUser->getName();
			$tl = $this->makeKnownLinkObj( $wgUser->getTalkPage(),
				wfMsg( 'mytalk' ) );
			if ( $wgUser->getNewtalk() ) {
				$tl .= " *";
			}

			$s .= $this->makeKnownLinkObj( $wgUser->getUserPage(),
				wfMsg( "mypage" ) )
			  . $sep . $tl
			  . $sep . $this->specialLink( "watchlist" )
			  . $sep . $this->makeKnownLinkObj( Title::makeTitle( NS_SPECIAL, "Contributions" ),
			  	wfMsg( "mycontris" ), "target=" . wfUrlencode($wgUser->getName() ) )
		  	  . $sep . $this->specialLink( "preferences" )
		  	  . $sep . $this->specialLink( "userlogout" );
		} else {
			$s .= $this->specialLink( "userlogin" );
		}

		$s .= $this->menuHead( "qbspecialpages" )
		  . $this->specialLink( "newpages" )
		  . $sep . $this->specialLink( "imagelist" )
		  . $sep . $this->specialLink( "statistics" )
		  . $sep . $this->bugReportsLink();
		if ( $wgUser->isLoggedIn() && $wgEnableUploads ) {
			$s .= $sep . $this->specialLink( "upload" );
		}
		global $wgSiteSupportPage;
		if( $wgSiteSupportPage) {
			$s .= $sep."<a href=\"".htmlspecialchars($wgSiteSupportPage)."\" class =\"internal\">"
			      .wfMsg( "sitesupport" )."</a>";
		}

		$s .= $sep . $this->makeKnownLinkObj(
			Title::makeTitle( NS_SPECIAL, 'Specialpages' ),
			wfMsg( 'moredotdotdot' ) );

		$s .= $sep . "\n</div>\n";
		return $s;
	}

	function menuHead( $key )
	{
		$s = "\n<h6>" . wfMsg( $key ) . "</h6>";
		return $s;
	}

	function searchForm( $label = "" )
	{
		global $wgRequest;

		$search = $wgRequest->getText( 'search' );
		$action = $this->escapeSearchLink();
		$s = "<form id=\"searchform{$this->searchboxes}\" method=\"get\" class=\"inline\" action=\"$action\">";
		if ( "" != $label ) { $s .= "{$label}: "; }

		$s .= "<input type='text' id=\"searchInput{$this->searchboxes}\" name=\"search\" size=\"14\" value=\""
		  . htmlspecialchars(substr($search,0,256)) . "\" />"
		  . "<br /><input type='submit' id=\"searchGoButton{$this->searchboxes}\" name=\"go\" value=\"" . htmlspecialchars( wfMsg( "searcharticle" ) ) . "\" /> <input type='submit' id=\"mw-searchButton{$this->searchboxes}\" name=\"fulltext\" value=\"" . htmlspecialchars( wfMsg( "search" ) ) . "\" /></form>";

		// Ensure unique id's for search boxes made after the first
		$this->searchboxes = $this->searchboxes == '' ? 2 : $this->searchboxes + 1;

		return $s;
	}
}

?>
