<?php
# See skin.doc

class SkinCologneBlue extends Skin {

	function initPage()
	{
		global $wgOut, $wgStyleSheetPath;
	}

	function getStylesheet()
	{
		return "cologneblue.css";
	}

	function doBeforeContent()
	{
		global $wgUser, $wgOut, $wgTitle;

		$s = "";
		$qb = $this->qbSetting();

		$s .= "\n<div id='content'>\n<div id='topbar'>" .
		  "<table width='100%' border=0 cellspacing=0 cellpadding=8><tr>";

		$s .= "<td class='top' align=left valign=middle nowrap>";
		$s .= "<a href=\"" . wfLocalUrlE( urlencode( wfMsg( "mainpage" ) ) ) . "\">";
		$s .= "<span id='sitetitle'>" . wfMsg( "sitetitle" ) . "</span></a>";

		$s .= "</td><td class='top' align=right valign=bottom width='100%'>";
		$s .= $this->sysLinks();
		$s .= "</td></tr><tr><td valign=top>";

		$s .= "<font size='-1'><span id='sitesub'>";
		$s .= wfMsg( "sitesubtitle" ) . "</span></font>";
		$s .= "</td><td align=right>" ;

		$s .= "<font size='-1'><span id='langlinks'>" ;
		$s .= str_replace ( "<br>" , "" , $this->otherLanguages() ) ;
		$s .= "<br>" . $this->pageTitleLinks();
		$s .= "</span></font>";

		$s .= "</td></tr></table>\n";

		$s .= "\n</div>\n<div id='article'>";

		$s .= $this->pageTitle();
		$s .= $this->pageSubtitle() . "\n<p>";
		return $s;
	}

	function doAfterContent()
	{
		global $wgUser, $wgOut;

		$s = "\n</div><br clear=all>\n";

		$s .= "\n<div id='footer'>";
		$s .= "<table width='98%' border=0 cellspacing=0><tr>";

		$qb = $this->qbSetting();
		if ( 1 == $qb || 3 == $qb ) { # Left
			$s .= $this->getQuickbarCompensator();
		}
		$s .= "<td class='bottom' align=center valign=top>";

		$s .= $this->bottomLinks();
		$s .= "\n<br>" . $this->makeKnownLink( wfMsg( "mainpage" ),
		  wfMsg( "mainpage" ) ) . " | "
		  . $this->aboutLink() . " | "
		  . $this->searchForm( wfMsg( "qbfind" ) );

		$s .= "\n<br>" . $this->pageStats();

		$s .= "</td>";
		if ( 2 == $qb ) { # Right
			$s .= $this->getQuickbarCompensator();
		}
		$s .= "</tr></table>\n</div>\n</div>\n";

		if ( 0 != $qb ) { $s .= $this->quickBar(); }
		return $s;
	}
	function doGetUserStyles()
	{
		global $wgUser, $wgOut, $wgStyleSheetPath;

		$s = parent::doGetUserStyles();
		$qb = $this->qbSetting();

		if ( 2 == $qb ) { # Right
			$s .= "#quickbar { position: absolute; right: 4px; }\n" .
			  "#article { margin-left: 4px; margin-right: 148px; }\n";
		} else if ( 1 == $qb || 3 == $qb ) {
			$s .= "#quickbar { position: absolute; left: 4px; }\n" .
			  "#article { margin-left: 148px; margin-right: 4px; }\n";
		}
		return $s;
	}
	function sysLinks()
	{
		global $wgUser, $wgLang, $wgTitle;
		$li = $wgLang->specialPage("Userlogin");
		$lo = $wgLang->specialPage("Userlogout");

		$rt = $wgTitle->getPrefixedURL();
		if ( 0 == strcasecmp( urlencode( $lo ), $rt ) ) {
			$q = "";
		} else { 
			$q = "returnto={$rt}"; 
		}
		
		$s .= "\n<br>" . $this->makeKnownLink( $li,
		  wfMsg( "login" ), $q );

		$s = "" .
		  $this->makeKnownLink( wfMsg( "mainpage" ), wfMsg( "mainpage" ) )
		  . " | " .
		  $this->makeKnownLink( wfMsg( "aboutpage" ), wfMsg( "about" ) )
		  . " | " .
		  $this->makeKnownLink( wfMsg( "helppage" ), wfMsg( "help" ) )
		  . " | " .
		  $this->makeKnownLink( wfMsg( "faqpage" ), wfMsg("faq") )
		  . " | " .
		  $this->specialLink( "specialpages" ) . " | ";

		if ( $wgUser->getID() )
		{
			$s .=  $this->makeKnownLink( $lo, wfMsg( "logout" ), $q );
		}
		else
		{
			$s .=  $this->makeKnownLink( $li, wfMsg( "login" ), $q );
		}

		return $s;
	}

	function quickBar()
	{
		global $wgOut, $wgTitle, $wgUser, $wgLang, $wgDisableUploads;

		$tns=$wgTitle->getNamespace();

		$s = "\n<div id='quickbar'>";

		$sep = "<br>";
		$s .= $this->menuHead( "qbfind" );
		$s .= $this->searchForm();

		$s .= $this->menuHead( "qbbrowse" )
		  . $this->mainPageLink()
		  . $sep . $this->specialLink( "recentchanges" )
		  . $sep . $this->specialLink( "randompage" );
		if ( wfMsg ( "currentevents" ) != "-" ) $s .= $sep . $this->makeKnownLink( wfMsg( "currentevents" ), "" ) ;
			$s .= "\n";

		if ( $wgOut->isArticle() ) {
			$s .= $this->menuHead( "qbedit" );
			$s .= "<strong>" . $this->editThisPage() . "</strong>";

			$s .= $sep . $this->makeKnownLink( wfMsg( "edithelppage" ), wfMsg( "edithelp" ) );

			if ( 0 != $wgUser->getID() ) {
				$s .= $sep . $this->moveThisPage();
			}
			if ( $wgUser->isSysop() ) {
				$dtp = $this->deleteThisPage();
				if ( "" != $dtp ) {
					$s .= $sep . $dtp;
				}
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
			if ( 0 != $wgUser->getID() ) {
				$s .= $sep . $this->watchThisPage();
			}

			$s .= $sep;

			$s .= $this->menuHead("qbpageinfo")
			  . $this->historyLink()
			  . $sep . $this->whatLinksHere()
			  . $sep . $this->watchPageLinksLink();
			  
			if ( Namespace::getUser() == $tns || Namespace::getTalk(Namespace::getUser()) == $tns ) {
				$id=User::idFromName($wgTitle->getText());
				if ($id != 0) {
					$s .= $sep . $this->userContribsLink();
					if ( 0 != $wgUser->getID() ) {
						$s .= $sep . $this->emailUserLink();
					}
				}
			}
			$s .= $sep;
		}

		$s .= $this->menuHead( "qbmyoptions" );
		if ( 0 != $wgUser->getID() ) {
			$name = $wgUser->getName();
			$tl = $this->makeKnownLink( $wgLang->getNsText(
			  Namespace::getTalk( Namespace::getUser() ) ) . ":{$name}",
			  wfMsg( "mytalk" ) );
			if ( 0 != $wgUser->getNewtalk() ) { $tl .= " *"; }

			$s .= $this->makeKnownLink( $wgLang->getNsText(
			  Namespace::getUser() ) . ":{$name}", wfMsg( "mypage" ) )
			  . $sep . $tl
			  . $sep . $this->specialLink( "watchlist" )
			  . $sep . $this->makeKnownLink( $wgLang->specialPage( "Contributions" ),
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
		if ( 0 != $wgUser->getID() && !$wgDisableUploads ) {
			$s .= $sep . $this->specialLink( "upload" );
		}

		$s .= $sep . $this->makeKnownLink( $wgLang->specialPage( "Specialpages" ), wfMsg("moredotdotdot") );

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
		global $search;
		$s = "<form id=\"search\" method=\"get\" class=\"inline\" action=\"" .
		  wfLocalUrlE( "" ) . "\">";
		if ( "" != $label ) { $s .= "{$label}: "; }

		$s .= "<input type=text name=\"search\" size=14 value=\""
		  . htmlspecialchars(substr($search,0,256)) . "\">"
		  . "<br><input type=submit name=\"go\" value=\"" . wfMsg( "go" ) . "\"> <input type=submit name=\"fulltext\" value=\"" . wfMsg( "search" ) . "\"></form>";

		return $s;
	}
}

?>
