<?php
# See skin.doc

# These are the INTERNAL names, which get mapped
# directly to class names.  For display purposes, the
# Language class has internationalized names
#
/* private */ $wgValidSkinNames = array(
	"Standard", "Nostalgia", "CologneBlue" #, "Smarty", "Montparnasse"
);

include_once( "RecentChange.php" );

# For some odd PHP bug, this function can't be part of a class
function getCategories ()
{
  global $wgOut , $wgTitle , $wgUseCategoryMagic , $wgUser ;
  if ( !isset ( $wgUseCategoryMagic ) || !$wgUseCategoryMagic ) return "" ;
  if ( count ( $wgOut->mCategoryLinks ) == 0 ) return "" ;
  if ( !$wgOut->isArticle() ) return "" ;
  $sk = $wgUser->getSkin() ;
  $s = "" ;
  $s .=  "\n<br>\n";
  $s .= $sk->makeKnownLink ( "Special:Categories" , "Categories" , "article=".$wgTitle->getDBkey() ) ;
  $t = implode ( " | " , $wgOut->mCategoryLinks ) ;
  if ( $t != "" ) $s .= " : " ;
  $s .= $t ;
  return $s ;
}

class RCCacheEntry extends RecentChange
{
	var $secureName, $link;
	var $curlink , $lastlink , $usertalklink , $versionlink ;
	var $userlink, $timestamp, $watched;

	function newFromParent( $rc )
	{
		$rc2 = new RCCacheEntry;
		$rc2->mAttribs = $rc->mAttribs;
		$rc2->mExtra = $rc->mExtra;
		return $rc2;
	}
} ;

class Skin {

	/* private */ var $lastdate, $lastline;
	var $linktrail ; # linktrail regexp
	var $rc_cache ; # Cache for Enhanced Recent Changes
	var $rcCacheIndex ; # Recent Changes Cache Counter for visibility toggle
	var $rcMoveIndex;

	function Skin()
	{
		$this->linktrail = wfMsg("linktrail");
	}

	function getSkinNames()
	{
		global $wgValidSkinNames;
		return $wgValidSkinNames;
	}

	function getStylesheet()
	{
		return "wikistandard.css";
	}

	function qbSetting()
	{
		global $wgOut, $wgUser;

		if ( $wgOut->isQuickbarSuppressed() ) { return 0; }
		$q = $wgUser->getOption( "quickbar" );
		if ( "" == $q ) { $q = 0; }
		return $q;
	}

	function initPage( &$out )
	{
		global $wgStyleSheetPath;
		$fname = "Skin::initPage";
		wfProfileIn( $fname );
		
		$out->addLink( "shortcut icon", "", "/favicon.ico" );
		
		wfProfileOut( $fname );
	}
	
	function outputPage( &$out ) {
		global $wgDebugComments;
		
		wfProfileIn( "Skin::outputPage" );
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

		$out->out( $out->mBodytext );

		$out->out( $this->afterContent() );
		
		wfProfileClose();
		$out->out( $out->reportTime() );

		$out->out( "\n</body></html>" );
	}

	function getHeadScripts() {
		global $wgStyleSheetPath;
		$r = "<script type=\"text/javascript\" src=\"{$wgStyleSheetPath}/wikibits.js\"></script>\n";
		return $r;
	}

	function getUserStyles()
	{
		global $wgOut, $wgStyleSheetPath;
		if( $wgOut->isPrintable() ) {
			$sheet = "wikiprintable.css";
		} else {
			$sheet = $this->getStylesheet();
		}
		$s = "<style type='text/css'><!--\n";
		$s .= "@import url(\"$wgStyleSheetPath/$sheet\");\n";
		$s .= "/*/*/\n"; # <-- Hide the styles from Netscape 4 without hiding them from IE/Mac
		$s .= $this->doGetUserStyles();
		$s .= "/* */\n";
		$s .= "//--></style>\n";
		return $s;
	}

	function doGetUserStyles()
	{
		global $wgUser;

		$s = "";
		if ( 1 == $wgUser->getOption( "underline" ) ) {
			# Don't override browser settings
		} else {
			# CHECK MERGE @@@
			# Force no underline
			$s .= "a.stub, a.new, a.internal, a.external { " .
			  "text-decoration: none; }\n";
		}
		if ( 1 == $wgUser->getOption( "highlightbroken" ) ) {
			$s .= "a.new, #quickbar a.new { color: #CC2200; }\n";
		}
		if ( 1 == $wgUser->getOption( "justify" ) ) {
			$s .= "#article { text-align: justify; }\n";
		}
		return $s;
	}

	function getBodyOptions()
	{
		global $wgUser, $wgTitle, $wgNamespaceBackgrounds, $wgOut, $oldid, $redirect, $diff,$action;

		if ( 0 != $wgTitle->getNamespace() ) {
			$a = array( "bgcolor" => "#ffffec" );
		}
		else $a = array( "bgcolor" => "#FFFFFF" );
		if($wgOut->isArticle() && $wgUser->getOption("editondblclick")
			&& 
			(!$wgTitle->isProtected() || $wgUser->isSysop())
			
			) {
			$n = $wgTitle->getPrefixedURL();
			$t = wfMsg( "editthispage" );
			$oid = $red = "";
			if ( $redirect ) { $red = "&redirect={$redirect}"; }
			if ( $oldid && ! isset( $diff ) ) {
				$oid = "&oldid={$oldid}";
			}
			$s = wfLocalUrlE($n,"action=edit{$oid}{$red}");
			$s = "document.location = \"" .$s ."\";";
			$a += array ("ondblclick" => $s);

		}
		$a['onload'] = $wgOut->getOnloadHandler();
		return $a;
	}

	function getExternalLinkAttributes( $link, $text )
	{
		global $wgUser, $wgOut, $wgLang;

		$link = urldecode( $link );
		$link = $wgLang->checkTitleEncoding( $link );
		$link = str_replace( "_", " ", $link );
		$link = wfEscapeHTML( $link );

		if ( $wgOut->isPrintable() ) { $r = " class='printable'"; }
		else { $r = " class='external'"; }

		if ( 1 == $wgUser->getOption( "hover" ) ) {
			$r .= " title=\"{$link}\"";
		}
		return $r;
	}

	function getInternalLinkAttributes( $link, $text, $broken = false )
	{
		global $wgUser, $wgOut;

		$link = urldecode( $link );
		$link = str_replace( "_", " ", $link );
		$link = wfEscapeHTML( $link );

		if ( $wgOut->isPrintable() ) { 
			$r = " class='printable'"; 
		} else if ( $broken == "stub" ) { 
			$r = " class='stub'"; 
		} else if ( $broken == "yes" ) { 
			$r = " class='new'"; 
		} else { 
			$r = " class='internal'"; 
		}

		if ( 1 == $wgUser->getOption( "hover" ) ) {
			$r .= " title=\"{$link}\"";
		}
		return $r;
	}
	
	function getInternalLinkAttributesObj( &$nt, $text, $broken = false )
	{
		global $wgUser, $wgOut;

		if ( $wgOut->isPrintable() ) { 
			$r = " class='printable'"; 
		} else if ( $broken == "stub" ) { 
			$r = " class='stub'"; 
		} else if ( $broken == "yes" ) { 
			$r = " class='new'"; 
		} else { 
			$r = " class='internal'"; 
		}

		if ( 1 == $wgUser->getOption( "hover" ) ) {
			$r .= ' title ="' . $nt->getEscapedText() . '"';
		}
		return $r;
	}		
	
	function getLogo()
	{
		global $wgLogo;
		return $wgLogo;
	}

	# This will be called immediately after the <body> tag.  Split into
	# two functions to make it easier to subclass.
	#
	function beforeContent()
	{
		global $wgUser, $wgOut, $wgSiteNotice;

		if ( $wgOut->isPrintable() ) {
			$s = $this->pageTitle() . $this->pageSubtitle() . "\n";
			$s .= "\n<div class='bodytext'>";
			return $s;
		}
		if( $wgSiteNotice ) {
			$note = "\n<div id='notice' style='font-weight: bold; color: red; text-align: center'>$wgSiteNotice</div>\n";
		} else {
			$note = "";
		}
		return $this->doBeforeContent() . $note;
	}

	function doBeforeContent()
	{
		global $wgUser, $wgOut, $wgTitle, $wgLang;
		$fname = "Skin::doBeforeContent";
		wfProfileIn( $fname );

		$s = "";
		$qb = $this->qbSetting();

		if( $langlinks = $this->otherLanguages() ) {
			$rows = 2;
			$borderhack = "";
		} else {
			$rows = 1;
			$langlinks = false;
			$borderhack = "class='top'";
		}

		$s .= "\n<div id='content'>\n<div id='topbar'>" .
		  "<table width='98%' border=0 cellspacing=0><tr>";

		$shove = ($qb != 0);
		$left = ($qb == 1 || $qb == 3);
		if($wgLang->isRTL()) $left = !$left;
		
		if ( !$shove ) {
			$s .= "<td class='top' align=left valign=top rowspan='{$rows}'>" .
			  $this->logoText() . "</td>";
		} elseif( $left ) {
			$s .= $this->getQuickbarCompensator( $rows );
		}
		$l = $wgLang->isRTL() ? "right" : "left";
		$s .= "<td {$borderhack} align='$l' valign='top'>";

		$s .= $this->topLinks() ;
		$s .= "<p class='subtitle'>" . $this->pageTitleLinks();

		$r = $wgLang->isRTL() ? "left" : "right";
		$s .= "</td>\n<td {$borderhack} valign='top' align='$r' nowrap>";
		$s .= $this->nameAndLogin();
		$s .= "\n<br>" . $this->searchForm() . "</td>";

		if ( $langlinks ) {
			$s .= "</tr>\n<tr><td class='top' colspan=\"2\">$langlinks</td>";
		}

		if ( $shove && !$left ) { # Right
			$s .= $this->getQuickbarCompensator( $rows );
		}
		$s .= "</tr></table>\n</div>\n";
		$s .= "\n<div id='article'>";

		$s .= $this->pageTitle();
		$s .= $this->pageSubtitle() ;
		$s .= getCategories(); // For some odd reason, zhis can't be a function of the object
		$s .= "\n<p>";
		wfProfileOut( $fname );
		return $s;
	}

	function getQuickbarCompensator( $rows = 1 )
	{
		return "<td width='152' rowspan='{$rows}'>&nbsp;</td>";
	}

	# This gets called immediately before the </body> tag.
	#
	function afterContent()
	{
		global $wgUser, $wgOut, $wgServer;
		global $wgTitle, $wgLang;
		
		if ( $wgOut->isPrintable() ) {
			$s = "\n</div>\n";

			$u = htmlspecialchars( $wgServer . $wgTitle->getFullURL() );
			$u = "<a href=\"$u\">$u</a>";
			$rf = wfMsg( "retrievedfrom", $u );

			if ( $wgOut->isArticle() ) {
				$lm = "<br>" . $this->lastModified();
			} else { $lm = ""; }

			$cr = wfMsg( "gnunote" );
			$s .= "<p>" . $wgLang->emphasize("{$rf}{$lm} {$cr}\n");
			return $s;
		}
		return $this->doAfterContent();
	}

	function doAfterContent()
	{
		global $wgUser, $wgOut, $wgLang;
		$fname =  "Skin::doAfterContent";
		wfProfileIn( $fname );
		wfProfileIn( "$fname-1" );

		$s = "\n</div><br clear=all>\n";
		$s .= "\n<div id='footer'>";
		$s .= "<table width='98%' border=0 cellspacing=0><tr>";
		
		wfProfileOut( "$fname-1" );
		wfProfileIn( "$fname-2" );

		$qb = $this->qbSetting();
		$shove = ($qb != 0);
		$left = ($qb == 1 || $qb == 3);
		if($wgLang->isRTL()) $left = !$left;

		if ( $shove && $left ) { # Left
			$s .= $this->getQuickbarCompensator();
		}
		wfProfileOut( "$fname-2" );
		wfProfileIn( "$fname-3" );
		$l = $wgLang->isRTL() ? "right" : "left";
		$s .= "<td class='bottom' align='$l' valign='top'>";

		$s .= $this->bottomLinks();
		$s .= "\n<br>" . $this->mainPageLink()
		  . " | " . $this->aboutLink()
		  . " | " . $this->specialLink( "recentchanges" )
		  . " | " . $this->searchForm()
		  . "<br>" . $this->pageStats();

		$s .= "</td>";
		if ( $shove && !$left ) { # Right
			$s .= $this->getQuickbarCompensator();
		}
		$s .= "</tr></table>\n</div>\n</div>\n";
		
		wfProfileOut( "$fname-3" );
		wfProfileIn( "$fname-4" );
		if ( 0 != $qb ) { $s .= $this->quickBar(); }
		wfProfileOut( "$fname-4" );
		wfProfileOut( $fname );
		return $s;
	}

	function pageTitleLinks()
	{
		global $wgOut, $wgTitle, $oldid, $action, $diff, $wgUser, $wgLang, $wgUseApproval ;

		$s = $this->printableLink();
		if ( wfMsg ( "disclaimers" ) != "-" ) $s .= " | " . $this->makeKnownLink( wfMsg( "disclaimerpage" ), wfMsg( "disclaimers" ) ) ;

		if ( $wgOut->isArticleRelated() ) {
			if ( $wgTitle->getNamespace() == Namespace::getImage() ) {
				$name = $wgTitle->getDBkey();
				$link = wfEscapeHTML( wfImageUrl( $name ) );
				$style = $this->getInternalLinkAttributes( $link, $name );
				$s .= " | <a href=\"{$link}\"{$style}>{$name}</a>";
			}
			# This will show the "Approve" link if $wgUseApproval=true;
			if ( isset ( $wgUseApproval ) && $wgUseApproval )
			{
				$t = $wgTitle->getDBkey();
				$name = "Approve this article" ; 
				$link = "http://test.wikipedia.org/w/magnus/wiki.phtml?title={$t}&action=submit&doit=1" ;
				#wfEscapeHTML( wfImageUrl( $name ) );
				$style = $this->getExternalLinkAttributes( $link, $name );
				$s .= " | <a href=\"{$link}\"{$style}>{$name}</a>" ;
			}		
		}
		if ( "history" == $action || isset( $diff ) || isset( $oldid ) ) {
			$s .= " | " . $this->makeKnownLink( $wgTitle->getPrefixedText(),
					wfMsg( "currentrev" ) );
		}

		if ( $wgUser->getNewtalk() ) {
		# do not show "You have new messages" text when we are viewing our 
		# own talk page 

			if(!(strcmp($wgTitle->getText(),$wgUser->getName()) == 0 &&
						$wgTitle->getNamespace()==Namespace::getTalk(Namespace::getUser()))) {
				$n =$wgUser->getName();
				$tl = $this->makeKnownLink( $wgLang->getNsText(
							Namespace::getTalk( Namespace::getUser() ) ) . ":{$n}",
						wfMsg("newmessageslink") );
				$s.=" | <strong>". wfMsg( "newmessages", $tl ) . "</strong>";
			}
		}
		if( $wgUser->isSysop() &&
				(($wgTitle->getArticleId() == 0) || ($action == "history")) &&
				($n = $wgTitle->isDeleted() ) ) {
			$s .= " | " . wfMsg( "thisisdeleted",
					$this->makeKnownLink(
						$wgLang->SpecialPage( "Undelete/" . $wgTitle->getPrefixedDBkey() ),
						wfMsg( "restorelink", $n ) ) );
		}
		return $s;
	}

	function printableLink()
	{
		global $wgOut, $wgTitle, $oldid, $action;

		$basequery = "";
		foreach( $_GET as $var => $val ) {
			if( $var == "title" ) continue;
			$basequery .= "&" . urlencode( $var ) . "=" . urlencode( $val );
		}
		$basequery .= "&printable=yes";
		
		# Trim the extra & if there was nothing else
		$basequery = substr( $basequery, 1 );
		
		$s = $this->makeKnownLinkObj( $wgTitle,
		  wfMsg( "printableversion" ), $basequery );
		return $s;
	}

	function pageTitle()
	{
		global $wgOut, $wgTitle, $wgUser;

		$s = "<h1 class='pagetitle'>" . $wgOut->getPageTitle() . "</h1>";
		if($wgUser->getOption("editsectiononrightclick") && $wgTitle->userCanEdit()) { $s=$this->editSectionScript(0,$s);}
		return $s;
	}

	function pageSubtitle()
	{
		global $wgOut,$wgTitle,$wgNamespacesWithSubpages;

		$sub = $wgOut->getSubtitle();
		if ( "" == $sub ) {
			global $wgExtraSubtitle;
			$sub = wfMsg( "fromwikipedia" ) . $wgExtraSubtitle;
		}
		if($wgOut->isArticle() && $wgNamespacesWithSubpages[$wgTitle->getNamespace()]) {
			$ptext=$wgTitle->getPrefixedText();
			if(preg_match("/\//",$ptext)) {
				$sub.="</p><p class='subpages'>";
				$links=explode("/",$ptext);
				$c=0;
				$growinglink="";
				foreach($links as $link) {
					$c++;
					if ($c<count($links)) {
						$growinglink .= $link;
						$getlink = $this->makeLink( $growinglink, $link );
						if(preg_match("/class='new'/i",$getlink)) { break; } # this is a hack, but it saves time
						if ($c>1) {
							$sub .= " | ";
						} else  {
							$sub .="&lt; ";
						}
						$sub .= $getlink;
						$growinglink.="/";
					}
					
				}
			}
		}
		$s = "<p class='subtitle'>{$sub}\n";
		return $s;
	}

	function nameAndLogin()
	{
		global $wgUser, $wgTitle, $wgLang, $wgShowIPinHeader, $wgIP;

		$li = $wgLang->specialPage( "Userlogin" );
		$lo = $wgLang->specialPage( "Userlogout" );

		$s = "";
		if ( 0 == $wgUser->getID() ) {
			if( $wgShowIPinHeader && isset(  $_COOKIE[ini_get("session.name")] ) ) {
				$n = $wgIP;

				$tl = $this->makeKnownLink( $wgLang->getNsText(
				  Namespace::getTalk( Namespace::getUser() ) ) . ":{$n}",
				  $wgLang->getNsText( Namespace::getTalk( 0 ) ) );
			  
				$s .= $n .  " (".$tl.")";
			} else {
				$s .= wfMsg("notloggedin");
			}
			
			$rt = $wgTitle->getPrefixedURL();
			if ( 0 == strcasecmp( urlencode( $lo ), $rt ) ) {
				$q = "";
			} else { $q = "returnto={$rt}"; }
			
			$s .= "\n<br>" . $this->makeKnownLink( $li,
			  wfMsg( "login" ), $q );
		} else {
			$n = $wgUser->getName();
			$rt = $wgTitle->getPrefixedURL();
			$tl = $this->makeKnownLink( $wgLang->getNsText(
			  Namespace::getTalk( Namespace::getUser() ) ) . ":{$n}",
			  $wgLang->getNsText( Namespace::getTalk( 0 ) ) );

			$tl = " ({$tl})"; 
			
			$s .= $this->makeKnownLink( $wgLang->getNsText(
			  Namespace::getUser() ) . ":{$n}", $n ) . "{$tl}<br>" .
			  $this->makeKnownLink( $lo, wfMsg( "logout" ),
			  "returnto={$rt}" ) . " | " .
			  $this->specialLink( "preferences" );
		}
		$s .= " | " . $this->makeKnownLink( wfMsg( "helppage" ),
		  wfMsg( "help" ) ); 

		return $s;
	}

	function searchForm()
	{
		global $search;

		$s = "<form name='search' class='inline' method=post action=\""
		  . wfLocalUrl( "" ) . "\">"
		  . "<input type=text name=\"search\" size=19 value=\""
		  . htmlspecialchars(substr($search,0,256)) . "\">\n"
		  . "<input type=submit name=\"go\" value=\"" . wfMsg ("go") . "\">&nbsp;"
		  . "<input type=submit name=\"fulltext\" value=\"" . wfMsg ("search") . "\"></form>";

		return $s;
	}

	function topLinks()
	{
		global $wgOut;
		$sep = " |\n";

		$s = $this->mainPageLink() . $sep
		  . $this->specialLink( "recentchanges" );

		if ( $wgOut->isArticleRelated() ) {
			$s .=  $sep . $this->editThisPage()
			  . $sep . $this->historyLink();
		}
		# Many people don't like this dropdown box
		#$s .= $sep . $this->specialPagesList();

		return $s;
	}

	function bottomLinks()
	{ 
		global $wgOut, $wgUser, $wgTitle;
		$sep = " |\n";

		$s = "";
		if ( $wgOut->isArticleRelated() ) {
			$s .= "<strong>" . $this->editThisPage() . "</strong>";
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
			if ( $wgUser->isSysop() && $wgTitle->getArticleId() ) {
				$s .= "\n<br>" . $this->deleteThisPage() .
				$sep . $this->protectThisPage() .
				$sep . $this->moveThisPage();
			}
			$s .= "<br>\n" . $this->otherLanguages();
		}
		return $s;
	}

	function pageStats()
	{
		global $wgOut, $wgLang, $wgArticle;
		global $oldid, $diff, $wgDisableCounters;

		if ( ! $wgOut->isArticle() ) { return ""; }
		if ( isset( $oldid ) || isset( $diff ) ) { return ""; }
		if ( 0 == $wgArticle->getID() ) { return ""; }

		if ( $wgDisableCounters ) {
			$s = "";
		} else {
			$count = $wgArticle->getCount();
			$s = wfMsg( "viewcount", $count );
		}
		$s .= $this->lastModified();
		$s .= " " . wfMsg( "gnunote" );
		return "<span id='pagestats'>{$s}</span>";
	}

	function lastModified()
	{
		global $wgLang, $wgArticle;

		$d = $wgLang->timeanddate( $wgArticle->getTimestamp(), true );
		$s = " " . wfMsg( "lastmodified", $d );
		return $s;
	}

	function logoText( $align = "" )
	{
		if ( "" != $align ) { $a = " align='{$align}'"; }
		else { $a = ""; }

		$mp = wfMsg( "mainpage" );
		$s = "<a href=\"" . wfLocalUrlE( urlencode( str_replace(' ','_',$mp) ) )
		  . "\"><img{$a} border=0 src=\""
		  . $this->getLogo() . "\" alt=\"" . "[{$mp}]\"></a>";
		return $s;
	}

	function quickBar()
	{
		global $wgOut, $wgTitle, $wgUser, $action, $wgLang;
		global $wpPreview, $wgDisableUploads, $wgRemoteUploads;
	    
		$fname =  "Skin::quickBar";
		wfProfileIn( $fname );

		$tns=$wgTitle->getNamespace();

		$s = "\n<div id='quickbar'>";
		$s .= "\n" . $this->logoText() . "\n<hr class='sep'>";

		$sep = "\n<br>";
		$s .= $this->mainPageLink()
		  . $sep . $this->specialLink( "recentchanges" )
		  . $sep . $this->specialLink( "randompage" );
		if ($wgUser->getID()) { 
		$s.= $sep . $this->specialLink( "watchlist" ) ; 
		$s .= $sep .$this->makeKnownLink( $wgLang->specialPage( "Contributions" ),
		  wfMsg( "mycontris" ), "target=" . wfUrlencode($wgUser->getName() ) );		
		
		}
		// only show watchlist link if logged in
		if ( wfMsg ( "currentevents" ) != "-" ) $s .= $sep . $this->makeKnownLink( wfMsg( "currentevents" ), "" ) ;
		$s .= "\n<br><hr class='sep'>";
		$articleExists = $wgTitle->getArticleId();
		if ( $wgOut->isArticle() || $action =="edit" || $action =="history" || $wpPreview) {				
			if($wgOut->isArticle()) {
				$s .= "<strong>" . $this->editThisPage() . "</strong>";
			} else { # backlink to the article in edit or history mode
				if($articleExists){ # no backlink if no article
					switch($tns) {
						case 0:
						$text = wfMsg("articlepage");
						break;
						case 1:
						$text = wfMsg("viewtalkpage");
						break;
						case 2:
						$text = wfMsg("userpage");				
						break;
						case 3:
						$text = wfMsg("viewtalkpage");
						break;	
						case 4: 
						$text = wfMsg("wikipediapage");
						break;
						case 5:				
						$text = wfMsg("viewtalkpage");
						break;
						case 6:
						$text = wfMsg("imagepage");
						break;
						case 7:
						$text = wfMsg("viewtalkpage");
						break;
						default:
						$text= wfMsg("articlepage");
					}
				
					$link = $wgTitle->getText();
					if ($nstext = $wgLang->getNsText($tns) ) { # add namespace if necessary
						$link = $nstext . ":" . $link ;
					}			

					$s .= $this->makeLink( $link, $text );			
				} elseif( $wgTitle->getNamespace() != Namespace::getSpecial() ) {
					# we just throw in a "New page" text to tell the user that he's in edit mode,
					# and to avoid messing with the separator that is prepended to the next item
					$s .= "<strong>" . wfMsg("newpage") . "</strong>";
				}
			
			}
			

			if( $tns%2 && $action!="edit" && !$wpPreview) {
				$s.="<br>".$this->makeKnownLink($wgTitle->getPrefixedText(),wfMsg("postcomment"),"action=edit&section=new");
			}

			/*
			watching could cause problems in edit mode:
			if user edits article, then loads "watch this article" in background and then saves
			article with "Watch this article" checkbox disabled, the article is transparently
			unwatched. Therefore we do not show the "Watch this page" link in edit mode
			*/			
			if ( 0 != $wgUser->getID() && $articleExists) {
				if($action!="edit" && $action != "submit" ) 
				{
					$s .= $sep . $this->watchThisPage(); 
				}
				if ( $wgTitle->userCanEdit() ) 
					$s .= $sep . $this->moveThisPage();
			}
			if ( $wgUser->isSysop() and $articleExists ) {
				$s .= $sep . $this->deleteThisPage() .
				$sep . $this->protectThisPage();
			}
			$s .= $sep . $this->talkLink();
			if ($articleExists && $action !="history") { 
				$s .= $sep . $this->historyLink();
			}
			$s.=$sep . $this->whatLinksHere();
			
			if($wgOut->isArticleRelated()) {
				$s .= $sep . $this->watchPageLinksLink();
			}

			if ( Namespace::getUser() == $wgTitle->getNamespace() 
			|| $wgTitle->getNamespace() == Namespace::getTalk(Namespace::getUser())
			) {
			
				$id=User::idFromName($wgTitle->getText());
				$ip=User::isIP($wgTitle->getText());
				
				if($id||$ip) {
					$s .= $sep . $this->userContribsLink();
				}
				if ( 0 != $wgUser->getID() ) {
					if($id) { # can only email real users
						$s .= $sep . $this->emailUserLink(); 
					}
				}
			}
			$s .= "\n<br><hr class='sep'>";
		} 
		
		if ( 0 != $wgUser->getID() && ( !$wgDisableUploads || $wgRemoteUploads ) ) {
			$s .= $this->specialLink( "upload" ) . $sep;
		}
		$s .= $this->specialLink( "specialpages" )
		  . $sep . $this->bugReportsLink();
		
		global $wgSiteSupportPage;
		if( $wgSiteSupportPage ) {
			$s .= "\n<br><a href=\"" . htmlspecialchars( $wgSiteSupportPage ) .
			  "\" class=\"internal\">" . wfMsg( "sitesupport" ) . "</a>";
		}
	
		$s .= "\n<br></div>\n";
		wfProfileOut( $fname );
		return $s;
	}

	function specialPagesList()
	{
		global $wgUser, $wgOut, $wgLang, $wgServer, $wgRedirectScript;
		$a = array();

		$validSP = $wgLang->getValidSpecialPages();

		foreach ( $validSP as $name => $desc ) {
			if ( "" == $desc ) { continue; }
			$a[$name] = $desc;
		}
		if ( $wgUser->isSysop() )
		{ 
			$sysopSP = $wgLang->getSysopSpecialPages();

			foreach ( $sysopSP as $name => $desc ) {
				if ( "" == $desc ) { continue; }
				$a[$name] = $desc ;
			}
		}
		if ( $wgUser->isDeveloper() )
		{ 
			$devSP = $wgLang->getDeveloperSpecialPages();

			foreach ( $devSP as $name => $desc ) {
				if ( "" == $desc ) { continue; }
				$a[$name] = $desc ;
			}
		}
		$go = wfMsg( "go" );
		$sp = wfMsg( "specialpages" );
		$spp = $wgLang->specialPage( "Specialpages" );

		$s = "<form id=\"specialpages\" method=\"get\" class=\"inline\" " .
		  "action=\"{$wgServer}{$wgRedirectScript}\">\n";
		$s .= "<select name=\"wpDropdown\">\n";
		$s .= "<option value=\"{$spp}\">{$sp}</option>\n";

		foreach ( $a as $name => $desc ) {
			$p = $wgLang->specialPage( $name );
			$s .= "<option value=\"{$p}\">{$desc}</option>\n";
		}
		$s .= "</select>\n";
		$s .= "<input type=submit value=\"{$go}\" name=redirect>\n";
		$s .= "</form>\n";
		return $s;
	}

	function mainPageLink()
	{
		$mp = wfMsg( "mainpage" );
		$s = $this->makeKnownLink( $mp, $mp );
		return $s;
	}

	function copyrightLink()
	{
		$s = $this->makeKnownLink( wfMsg( "copyrightpage" ),
		  wfMsg( "copyrightpagename" ) );
		return $s;
	}

	function aboutLink()
	{
		$s = $this->makeKnownLink( wfMsg( "aboutpage" ),
		  wfMsg( "aboutwikipedia" ) );
		return $s;
	}


      function disclaimerLink()
	{
		$s = $this->makeKnownLink( wfMsg( "disclaimerpage" ),
		  wfMsg( "disclaimers" ) );
		return $s;
	}

	function editThisPage()
	{
		global $wgOut, $wgTitle, $oldid, $redirect, $diff;

		if ( ! $wgOut->isArticleRelated() ) {
			$s = wfMsg( "protectedpage" );
		} else {
			$n = $wgTitle->getPrefixedText();
			if ( $wgTitle->userCanEdit() ) {
				$t = wfMsg( "editthispage" );
			} else {
				#$t = wfMsg( "protectedpage" );
				$t = wfMsg( "viewsource" );
			}
			$oid = $red = "";

			if ( $redirect ) { $red = "&redirect={$redirect}"; }
			if ( $oldid && ! isset( $diff ) ) {
				$oid = "&oldid={$oldid}";
			}
			$s = $this->makeKnownLink( $n, $t, "action=edit{$oid}{$red}" );
		}
		return $s;
	}

	function deleteThisPage()
	{
		global $wgUser, $wgOut, $wgTitle, $diff;

		if ( $wgTitle->getArticleId() && ( ! $diff ) && $wgUser->isSysop() ) {
			$n = $wgTitle->getPrefixedText();
			$t = wfMsg( "deletethispage" );

			$s = $this->makeKnownLink( $n, $t, "action=delete" );
		} else {
			$s = "";
		}
		return $s;
	}

	function protectThisPage()
	{
		global $wgUser, $wgOut, $wgTitle, $diff;

		if ( $wgTitle->getArticleId() && ( ! $diff ) && $wgUser->isSysop() ) {
			$n = $wgTitle->getPrefixedText();

			if ( $wgTitle->isProtected() ) {
				$t = wfMsg( "unprotectthispage" );
				$q = "action=unprotect";
			} else {
				$t = wfMsg( "protectthispage" );
				$q = "action=protect";
			}
			$s = $this->makeKnownLink( $n, $t, $q );
		} else {
			$s = "";
		}
		return $s;
	}

	function watchThisPage()
	{
		global $wgUser, $wgOut, $wgTitle, $diff;

		if ( $wgOut->isArticleRelated() ) {
			$n = $wgTitle->getPrefixedText();

			if ( $wgTitle->userIsWatching() ) {
				$t = wfMsg( "unwatchthispage" );
				$q = "action=unwatch";
			} else {
				$t = wfMsg( "watchthispage" );
				$q = "action=watch";
			}
			$s = $this->makeKnownLink( $n, $t, $q );
		} else {
			$s = wfMsg( "notanarticle" );
		}
		return $s;
	}

	function moveThisPage()
	{
		global $wgTitle, $wgLang;

		if ( $wgTitle->userCanEdit() ) {
			$s = $this->makeKnownLink( $wgLang->specialPage( "Movepage" ),
			  wfMsg( "movethispage" ), "target=" . $wgTitle->getPrefixedURL() );
		} // no message if page is protected - would be redundant
		return $s;
	}

	function historyLink()
	{
		global $wgTitle;

		$s = $this->makeKnownLink( $wgTitle->getPrefixedText(),
		  wfMsg( "history" ), "action=history" );
		return $s;
	}

	function whatLinksHere()
	{
		global $wgTitle, $wgLang;

		$s = $this->makeKnownLink( $wgLang->specialPage( "Whatlinkshere" ),
		  wfMsg( "whatlinkshere" ), "target=" . $wgTitle->getPrefixedURL() );
		return $s;
	}

	function userContribsLink()
	{
		global $wgTitle, $wgLang;

		$s = $this->makeKnownLink( $wgLang->specialPage( "Contributions" ),
		  wfMsg( "contributions" ), "target=" . $wgTitle->getURL() );
		return $s;
	}

	function emailUserLink()
	{
		global $wgTitle, $wgLang;

		$s = $this->makeKnownLink( $wgLang->specialPage( "Emailuser" ),
		  wfMsg( "emailuser" ), "target=" . $wgTitle->getURL() );
		return $s;
	}

	function watchPageLinksLink()
	{
		global $wgOut, $wgTitle, $wgLang;

		if ( ! $wgOut->isArticleRelated() ) {
			$s = "(" . wfMsg( "notanarticle" ) . ")";
		} else {
			$s = $this->makeKnownLink( $wgLang->specialPage(
			  "Recentchangeslinked" ), wfMsg( "recentchangeslinked" ),
			  "target=" . $wgTitle->getPrefixedURL() );
		}
		return $s;
	}

	function otherLanguages()
	{
		global $wgOut, $wgLang, $wgTitle, $wgUseNewInterlanguage;

		$a = $wgOut->getLanguageLinks();
		# TEST THIS @@@
		if ( 0 == count( $a ) ) {
			if ( !$wgUseNewInterlanguage ) return "";
			$ns = $wgLang->getNsIndex ( $wgTitle->getNamespace () ) ;
			if ( $ns != 0 AND $ns != 1 ) return "" ;
			$pn = "Intl" ;
			$x = "mode=addlink&xt=".$wgTitle->getDBkey() ;
			return $this->makeKnownLink( $wgLang->specialPage( $pn ),
				  wfMsg( "intl" ) , $x );
			}

		if ( !$wgUseNewInterlanguage ) {
			$s = wfMsg( "otherlanguages" ) . ": ";
		} else {
			global $wgLanguageCode ;
			$x = "mode=zoom&xt=".$wgTitle->getDBkey() ;
			$x .= "&xl=".$wgLanguageCode ;
			$s =  $this->makeKnownLink( $wgLang->specialPage( "Intl" ),
				  wfMsg( "otherlanguages" ) , $x ) . ": " ;
			}

		$s = wfMsg( "otherlanguages" ) . ": ";
		$first = true;
		if($wgLang->isRTL()) $s .= "<span dir='LTR'>";
		foreach( $a as $l ) {
			if ( ! $first ) { $s .= " | "; }
			$first = false;

			$nt = Title::newFromText( $l );
			$url = $nt->getFullURL();
			$text = $wgLang->getLanguageName( $nt->getInterwiki() );

			if ( "" == $text ) { $text = $l; }
			$style = $this->getExternalLinkAttributes( $l, $text );
			$s .= "<a href=\"{$url}\"{$style}>{$text}</a>";
		}
		if($wgLang->isRTL()) $s .= "</span>";
		return $s;
	}

	function bugReportsLink()
	{
		$s = $this->makeKnownLink( wfMsg( "bugreportspage" ),
		  wfMsg( "bugreports" ) );
		return $s;
	}

	function dateLink()
	{
		global $wgLinkCache;
		$t1 = Title::newFromText( gmdate( "F j" ) );
		$t2 = Title::newFromText( gmdate( "Y" ) );

		$wgLinkCache->suspend();
		$id = $t1->getArticleID();
		$wgLinkCache->resume();

		if ( 0 == $id ) {
			$s = $this->makeBrokenLink( $t1->getText() );
		} else {
			$s = $this->makeKnownLink( $t1->getText() );
		}
		$s .= ", ";

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

	function talkLink()
	{
		global $wgLang, $wgTitle, $wgLinkCache;

		$tns = $wgTitle->getNamespace();
		if ( -1 == $tns ) { return ""; }

		$pn = $wgTitle->getText();
		$tp = wfMsg( "talkpage" );		
		if ( Namespace::isTalk( $tns ) ) {
			$lns = Namespace::getSubject( $tns );
			switch($tns) {
				case 1:
				$text = wfMsg("articlepage");
				break;
				case 3:
				$text = wfMsg("userpage");
				break;
				case 5: 
				$text = wfMsg("wikipediapage");
				break;
				case 7:
				$text = wfMsg("imagepage");
				break;
				default:
				$text= wfMsg("articlepage");
			}
		} else {
			
			$lns = Namespace::getTalk( $tns );
			$text=$tp;			
		}
		$n = $wgLang->getNsText( $lns );
		if ( "" == $n ) { $link = $pn; }
		else { $link = "{$n}:{$pn}"; }

		$wgLinkCache->suspend();
		$s = $this->makeLink( $link, $text );
		$wgLinkCache->resume();

		return $s;
	}

	function commentLink()
	{
		global $wgLang, $wgTitle, $wgLinkCache;

		$tns = $wgTitle->getNamespace();
		if ( -1 == $tns ) { return ""; }

		$lns = ( Namespace::isTalk( $tns ) ) ? $tns : Namespace::getTalk( $tns );

		# assert Namespace::isTalk( $lns )

		$n = $wgLang->getNsText( $lns );
		$pn = $wgTitle->getText();

		$link = "{$n}:{$pn}";

		$wgLinkCache->suspend();
		$s = $this->makeKnownLink($link, wfMsg("postcomment"), "action=edit&section=new");
		$wgLinkCache->resume();

		return $s;
	}

	# After all the page content is transformed into HTML, it makes
	# a final pass through here for things like table backgrounds.
	#
	function transformContent( $text )
	{
		return $text;
	}

	# Note: This function MUST call getArticleID() on the link,
	# otherwise the cache won't get updated properly.  See LINKCACHE.DOC.
	#
	function makeLink( $title, $text = "", $query = "", $trail = "" ) {
		wfProfileIn( "Skin::makeLink" );
	 	$nt = Title::newFromText( $title );
		if ($nt) {		
			$result = $this->makeLinkObj( Title::newFromText( $title ), $text, $query, $trail );
		} else {
			wfDebug( "Invalid title passed to Skin::makeLink(): \"$title\"\n" );
			$result = $text == "" ? $title : $text;
		}	
		
		wfProfileOut( "Skin::makeLink" );
		return $result;
	}

	function makeKnownLink( $title, $text = "", $query = "", $trail = "" ) {
		$nt = Title::newFromText( $title );
		if ($nt) {		
			return $this->makeKnownLinkObj( Title::newFromText( $title ), $text, $query, $trail );
		} else {
			wfDebug( "Invalid title passed to Skin::makeKnownLink(): \"$title\"\n" );
			return $text == "" ? $title : $text;
		}
	}

	function makeBrokenLink( $title, $text = "", $query = "", $trail = "" ) {
		$nt = Title::newFromText( $title );
		if ($nt) {		
			return $this->makeBrokenLinkObj( Title::newFromText( $title ), $text, $query, $trail );
		} else {
			wfDebug( "Invalid title passed to Skin::makeBrokenLink(): \"$title\"\n" );
			return $text == "" ? $title : $text;
		}
	}
	
	function makeStubLink( $title, $text = "", $query = "", $trail = "" ) {
		$nt = Title::newFromText( $title );
		if ($nt) {		
			return $this->makeStubLinkObj( Title::newFromText( $title ), $text, $query, $trail );
		} else {
			wfDebug( "Invalid title passed to Skin::makeStubLink(): \"$title\"\n" );
			return $text == "" ? $title : $text;
		}
	}

	# Pass a title object, not a title string
	function makeLinkObj( &$nt, $text= "", $query = "", $trail = "", $prefix = "" )
	{
		global $wgOut, $wgUser;
		if ( $nt->isExternal() ) {
			$u = $nt->getFullURL();
			if ( "" == $text ) { $text = $nt->getPrefixedText(); }
			$style = $this->getExternalLinkAttributes( $link, $text );

			$inside = "";
			if ( "" != $trail ) {
				if ( preg_match( "/^([a-z]+)(.*)$$/sD", $trail, $m ) ) {
					$inside = $m[1];
					$trail = $m[2];
				}
			}
			$retVal = "<a href=\"{$u}\"{$style}>{$text}{$inside}</a>{$trail}";
		} elseif ( 0 == $nt->getNamespace() && "" == $nt->getText() ) {
			$retVal = $this->makeKnownLinkObj( $nt, $text, $query, $trail, $prefix );
		} elseif ( ( -1 == $nt->getNamespace() ) ||
				( Namespace::getImage() == $nt->getNamespace() ) ) {
			$retVal = $this->makeKnownLinkObj( $nt, $text, $query, $trail, $prefix );
		} else {
			$aid = $nt->getArticleID() ;
			if ( 0 == $aid ) {
				$retVal = $this->makeBrokenLinkObj( $nt, $text, $query, $trail, $prefix );
			} else {
				$threshold = $wgUser->getOption("stubthreshold") ;
				if ( $threshold > 0 ) {
					$res = wfQuery ( "SELECT LENGTH(cur_text) AS x, cur_namespace, cur_is_redirect FROM cur WHERE cur_id='{$aid}'", DB_READ ) ;

					if ( wfNumRows( $res ) > 0 ) {
						$s = wfFetchObject( $res );
						$size = $s->x;
						if ( $s->cur_is_redirect OR $s->cur_namespace != 0 ) {
							$size = $threshold*2 ; # Really big
						}
						wfFreeResult( $res );
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
		return $retVal;
	}

	# Pass a title object, not a title string
	function makeKnownLinkObj( &$nt, $text = "", $query = "", $trail = "", $prefix = "" )
	{
		global $wgOut, $wgTitle;

		$fname = "Skin::makeKnownLinkObj";
		wfProfileIn( $fname );

		$link = $nt->getPrefixedURL();

		if ( "" == $link ) {
			$u = "";
			if ( "" == $text ) { $text = $nt->getFragment(); }
		} else {
			$u = wfLocalUrlE( $link, $query );
		}
		if ( "" != $nt->getFragment() ) {
			$u .= "#" . wfEscapeHTML( $nt->getFragment() );
		}
		if ( "" == $text ) { $text = $nt->getPrefixedText(); }
		$style = $this->getInternalLinkAttributesObj( $nt, $text );

		$inside = "";
		if ( "" != $trail ) {
			if ( preg_match( $this->linktrail, $trail, $m ) ) {
				$inside = $m[1];
				$trail = $m[2];
			}
		}
		$r = "<a href=\"{$u}\"{$style}>{$prefix}{$text}{$inside}</a>{$trail}";
		wfProfileOut( $fname );
		return $r;
	}
	
	# Pass a title object, not a title string
	function makeBrokenLinkObj( &$nt, $text = "", $query = "", $trail = "", $prefix = "" )
	{
		global $wgOut, $wgUser;
		
		$fname = "Skin::makeBrokenLinkObj";
		wfProfileIn( $fname );

		$link = $nt->getPrefixedURL();

		if ( "" == $query ) { $q = "action=edit"; }
		else { $q = "action=edit&{$query}"; }
		$u = wfLocalUrlE( $link, $q );

		if ( "" == $text ) { $text = $nt->getPrefixedText(); }
		$style = $this->getInternalLinkAttributesObj( $nt, $text, "yes" );

		$inside = "";
		if ( "" != $trail ) {
			if ( preg_match( $this->linktrail, $trail, $m ) ) {
				$inside = $m[1];
				$trail = $m[2];
			}
		}
		if ( $wgOut->isPrintable() ||
		  ( 1 == $wgUser->getOption( "highlightbroken" ) ) ) {
			$s = "<a href=\"{$u}\"{$style}>{$prefix}{$text}{$inside}</a>{$trail}";
		} else {
			$s = "{$prefix}{$text}{$inside}<a href=\"{$u}\"{$style}>?</a>{$trail}";
		}

		wfProfileOut( $fname );
		return $s;
	}
	
	# Pass a title object, not a title string
	function makeStubLinkObj( &$nt, $text = "", $query = "", $trail = "", $prefix = "" )
	{
		global $wgOut, $wgUser;

		$link = $nt->getPrefixedURL();

		$u = wfLocalUrlE( $link, $query );

		if ( "" == $text ) { $text = $nt->getPrefixedText(); }
		$style = $this->getInternalLinkAttributesObj( $nt, $text, "stub" );

		$inside = "";
		if ( "" != $trail ) {
			if ( preg_match( $this->linktrail, $trail, $m ) ) {
				$inside = $m[1];
				$trail = $m[2];
			}
		}
		if ( $wgOut->isPrintable() ||
				( 1 == $wgUser->getOption( "highlightbroken" ) ) ) {
			$s = "<a href=\"{$u}\"{$style}>{$prefix}{$text}{$inside}</a>{$trail}";
		} else {
			$s = "{$prefix}{$text}{$inside}<a href=\"{$u}\"{$style}>!</a>{$trail}";
		}
		return $s;
	}

	function fnamePart( $url )
	{
		$basename = strrchr( $url, "/" );
		if ( false === $basename ) { $basename = $url; }
		else { $basename = substr( $basename, 1 ); }
		return wfEscapeHTML( $basename );
	}

	function makeImage( $url, $alt = "" )
	{
		global $wgOut;

		if ( "" == $alt ) { $alt = $this->fnamePart( $url ); }
		$s = "<img src=\"{$url}\" alt=\"{$alt}\">";
		return $s;
	}
	
	function makeImageLink( $name, $url, $alt = "" ) {
		$nt = Title::makeTitle( Namespace::getImage(), $name );
		return $this->makeImageLinkObj( $nt, $alt );
	}

	function makeImageLinkObj( $nt, $alt = "" ) {
		global $wgLang, $wgUseImageResize;
		$link  = $nt->getPrefixedURL();
		$name  = $nt->getDBKey();
		$url   = wfImageUrl( $name );
		$align = "";
		$prefix = $postfix = "";

		if ( $wgUseImageResize ) {
			# Check if the alt text is of the form "options|alt text"
			# Options are:
			#  * thumbnail       	make a thumbnail with enlarge-icon and caption, alignment depends on lang
			#  * left		no resizing, just left align. label is used for alt= only
			#  * right		same, but right aligned
			#  * none		same, but not aligned
			#  * ___px		scale to ___ pixels width, no aligning. e.g. use in taxobox
	
			$part = explode( "|", $alt);
	
			$mwThumb  =& MagicWord::get( MAG_IMG_THUMBNAIL );
			$mwLeft   =& MagicWord::get( MAG_IMG_LEFT );
			$mwRight  =& MagicWord::get( MAG_IMG_RIGHT );
			$mwNone   =& MagicWord::get( MAG_IMG_NONE );
			$mwWidth  =& MagicWord::get( MAG_IMG_WIDTH );
			$mwCenter =& MagicWord::get( MAG_IMG_CENTER );
			$alt = $part[count($part)-1];

			$thumb=false;

			foreach( $part as $key => $val ) {
				if ( ! is_null( $mwThumb->matchVariableStartToEnd($val) ) ) {
					$thumb=true;
				} elseif ( ! is_null( $mwRight->matchVariableStartToEnd($val) ) ) {
					# remember to set an alignment, don't render immediately
					$align = "right";
				} elseif ( ! is_null( $mwLeft->matchVariableStartToEnd($val) ) ) {
					# remember to set an alignment, don't render immediately
					$align = "left";
				} elseif ( ! is_null( $mwCenter->matchVariableStartToEnd($val) ) ) {
					# remember to set an alignment, don't render immediately
					$align = "center";
				} elseif ( ! is_null( $mwNone->matchVariableStartToEnd($val) ) ) {
					# remember to set an alignment, don't render immediately
					$align = "none";
				} elseif ( ! is_null( $match = $mwWidth->matchVariableStartToEnd($val) ) ) {
					# $match is the image width in pixels
					$width = intval($match);
				}
			}
			if ( "center" == $align )
			{
				$prefix  = "<center>";
				$postfix = "</center>";
				$align   = "none";
			}
	
			if ( $thumb ) {
	
				# Create a thumbnail. Alignment depends on language
				# writing direction, # right aligned for left-to-right-
				# languages ("Western languages"), left-aligned
				# for right-to-left-languages ("Semitic languages")
				#
				# If  thumbnail width has not been provided, it is set
				# here to 180 pixels
				if ( $align == "" ) {
					$align = $wgLang->isRTL() ? "left" : "right";
				}
				if ( ! isset($width) ) {
					$width = 180;
				}
				return $prefix.$this->makeThumbLinkObj( $nt, $alt, $align, $width ).$postfix;
	
			} elseif ( isset($width) ) {
				
				# Create a resized image, without the additional thumbnail
				# features
				$url = $this->createThumb( $name, $width );
			}
		} # endif $wgUseImageResize
			
		if ( empty( $alt ) ) {
			$alt = preg_replace( '/\.(.+?)^/', '', $name );
		}
		$alt = htmlspecialchars( $alt );

		$u = wfLocalUrlE( $link );
		if ( $url == "" )
		{
			$s = str_replace( "$1", $name, wfMsg("missingimage") );
		} else {
			$s = "<a href=\"{$u}\" class='image' title=\"{$alt}\">" .
				"<img border=\"0\" src=\"{$url}\" alt=\"{$alt}\"></a>";
		}
		if ( "" != $align ) {
			$s = "<div class=\"float{$align}\">{$s}</div>";
		}
		return $prefix.$s.$postfix;
	}

	function createThumb( $name, $width ) {
		global $wgUploadDirectory;
		global $wgImageMagickConvertCommand;
		global $wgUseImageMagick;
		global $wgUseSquid, $wgInternalServer;
		$imgPath   = wfImagePath( $name );
		$thumbName = $width."px-".$icon.$name;
		$thumbPath = wfImageThumbDir( $thumbName )."/".$thumbName;
		$thumbUrl  = wfImageThumbUrl( $thumbName );

		if ( ! file_exists( $imgPath ) )
		{
			# If there is no image, there will be no thumbnail
			return "";
		}

		if (     (! file_exists( $thumbPath ) )
		||  ( filemtime($thumbPath) < filemtime($imgPath) ) ) {
			# Squid purging
			if ( $wgUseSquid ) {
				$urlArr = Array(
					$wgInternalServer.$thumbUrl
				);
				wfPurgeSquidServers($urlArr);
			}

			if ( $wgUseImageMagick ) {
				# use ImageMagick
				$cmd  =  $wgImageMagickConvertCommand .
					" -quality 85 -geometry {$width} ".
					escapeshellarg($imgPath) . " " .
					escapeshellarg($thumbPath);
				$conv = shell_exec( $cmd );
			} else {
				# Use PHP's builtin GD library functions.
				#
				# First find out what kind of file this is, and select the correct
				# input routine for this.
				list($src_width, $src_height, $src_type, $src_attr) = getimagesize( $imgPath );
				switch( $src_type ) {
					case 1: # GIF
						$src_image = imagecreatefromgif( $imgPath );
						break;
					case 2: # JPG
						$src_image = imagecreatefromjpeg( $imgPath );
						break;
					case 3: # PNG
						$src_image = imagecreatefrompng( $imgPath );
						break;
					case 15: # WBMP for WML
						$src_image = imagecreatefromwbmp( $imgPath );
						break;
					case 16: # XBM
						$src_image = imagecreatefromxbm( $imgPath );
						break;
					default:
						return "Image type not supported";
						break;
				}
				$height = floor( $src_height * ( $width/$src_width ) );
				$dst_image = imagecreatetruecolor( $width, $height );
				imagecopyresampled( $dst_image, $src_image, 
							0,0,0,0,
							$width, $height, $src_width, $src_height );
				switch( $src_type ) {
					case 1:  # GIF
					case 3:  # PNG
					case 15: # WBMP
					case 16: # XBM
						#$thumbUrl .= ".png";
						#$thumbPath .= ".png";
						imagepng( $dst_image, $thumbPath );
						break;
					case 2:  # JPEG
						#$thumbUrl .= ".jpg";
						#$thumbPath .= ".jpg";
						imageinterlace( $dst_image );
						imagejpeg( $dst_image, $thumbPath, 95 );
						break;
					default:
						break;
				}
				imagedestroy( $dst_image );
				imagedestroy( $src_image );


			}
			#
			# Check for zero-sized thumbnails. Those can be generated when 
			# no disk space is available or some other error occurs
			#
			$thumbstat = stat( $thumbPath );
			$imgstat   = stat( $imgPath );
			if( $thumbstat["size"] == 0 )
			{
				unlink( $thumbPath );
			}

		}
		return $thumbUrl;
	}

	function makeThumbLinkObj( $nt, $label = "", $align = "right", $boxwidth = 180 ) {
		global $wgStockPath, $wgLang;
		$name = $nt->getDBKey();
		$image = Title::makeTitle( Namespace::getImage(), $name );
		$link = $image->getPrefixedURL();
		$url  = wfImageUrl( $name );
		$path = wfImagePath( $name );
		
		$label = htmlspecialchars( $label );
		
		if ( file_exists( $path ) )
		{
			list($width, $height, $type, $attr) = getimagesize( $path );
		} else {
			$width = $height = 200;
		}
		$boxheight  = intval( $height/($width/$boxwidth) );
		if ( $boxwidth > $width ) {
			$boxwidth  = $width;
			$boxheight = $height;
		}
		
		$thumbUrl = $this->createThumb( $name, $boxwidth );

		$u = wfLocalUrlE( $link );

		$more = htmlspecialchars( wfMsg( "thumbnail-more" ) );
		$magnifyalign = $wgLang->isRTL() ? "left" : "right";
		$textalign = $wgLang->isRTL() ? " style=\"text-align:right\"" : "";
		
		$s = "<div class=\"thumbnail-{$align}\" style=\"width:{$boxwidth}px;\">";
		if ( $thumbUrl == "" ) {
			$s .= str_replace( "$1", $name, wfMsg("missingimage") );
		} else {
		  	$s .= "<a href=\"{$u}\" class=\"internal\" title=\"{$alt}\">" .
		  		"<img border=\"0\" src=\"{$thumbUrl}\" alt=\"{$alt}\" " .
				"  width=\"{$boxwidth}\" height=\"{$boxheight}\"></a>" .
		  		"<a href=\"{$u}\" class=\"internal\" title=\"{$more}\">" .
		    		"<img border=\"0\" src=\"{$wgStockPath}/magnify-clip.png\" " .
				"  width=\"26\" height=\"24\" align=\"{$magnifyalign}\" alt=\"{$more}\"></a>";
		}
		$s .= "<p{$textalign}>{$label}</p></div>";
		return $s;
	}

	function makeMediaLink( $name, $url, $alt = "" ) {
		$nt = Title::makeTitle( Namespace::getMedia(), $name );
		return $this->makeMediaLinkObj( $nt, $alt );
	}

	function makeMediaLinkObj( $nt, $alt = "" )
	{
		$name = $nt->getDBKey();
		$url = wfImageUrl( $name );
		if ( empty( $alt ) ) {
			$alt = preg_replace( '/\.(.+?)^/', '', $name );
		}

		$u = htmlspecialchars( $url );
		$s = "<a href=\"{$u}\" class='internal' title=\"{$alt}\">{$alt}</a>";
		return $s;
	}

	function specialLink( $name, $key = "" )
	{
		global $wgLang;

		if ( "" == $key ) { $key = strtolower( $name ); }
		$pn = $wgLang->ucfirst( $name );
		return $this->makeKnownLink( $wgLang->specialPage( $pn ),
		  wfMsg( $key ) );
	}

	# Called by history lists and recent changes
	#

	# Returns text for the start of the tabular part of RC
	function beginRecentChangesList()
	{
		$this->rc_cache = array() ;
		$this->rcMoveIndex = 0;
		$this->rcCacheIndex = 0 ;
		$this->lastdate = "";
		return "";
	}

	function beginImageHistoryList()
	{
		$s = "\n<h2>" . wfMsg( "imghistory" ) . "</h2>\n" .
		  "<p>" . wfMsg( "imghistlegend" ) . "\n<ul>";
		return $s;
	}

	# Returns text for the end of RC
	# If enhanced RC is in use, returns pretty much all the text
	function endRecentChangesList()
	{
		$s = $this->recentChangesBlock() ;
		$s .= "</ul>\n";
		return $s;
	}

	# Enhanced RC ungrouped line
	function recentChangesBlockLine ( $rcObj ) 
	{
		global $wgStockPath, $wgLang ;
		
		# Get rc_xxxx variables
		extract( $rcObj->mAttribs ) ;
		$curIdEq = "curid=$rc_cur_id";
		
		# Spacer image
		$r = "" ;
		$r .= "<img src='{$wgStockPath}/Arr_.png' width='12' height='12' border='0'>" ;		$r .= "<tt>" ;
		
		if ( $rc_type == RC_MOVE ) {
			$r .= "&nbsp;&nbsp;";
		} else {
			# M & N (minor & new)
			$M = wfMsg( "minoreditletter" );
			$N = wfMsg( "newpageletter" );
		
			if ( $rc_type == RC_NEW ) {
				$r .= $N ;
			} else {
				$r .= "&nbsp;" ;
			}
			if ( $rc_minor ) {
				$r .= $M ;
			} else {
				$r .= "&nbsp;" ;
			}
		}
		
		# Timestamp
		$r .= " ".$rcObj->timestamp." " ;
		$r .= "</tt>" ;

		# Article link
		$link = $rcObj->link ;
		if ( $rcObj->watched ) $link = "<strong>{$link}</strong>" ;
		$r .= $link ;

		# Cur
		$r .= " (" ;
		$r .= $rcObj->curlink ;
		$r .= "; " ;
		
		# Hist
		$r .= $this->makeKnownLinkObj( $rcObj->getTitle(), wfMsg( "hist" ), "{$curIdEq}&action=history" );

		# User/talk
		$r .= ") . . ".$rcObj->userlink ;
		$r .= $rcObj->usertalklink ;
		
		# Comment
		if ( $rc_comment != "" && $rc_type != RC_MOVE ) {
			$r .= $wgLang->emphasize( " (".wfEscapeHTML($rc_comment).")" );
		}
		$r .= "<br>\n" ;
		return $r ;
	}

	# Enhanced RC group
	function recentChangesBlockGroup ( $block ) 
	{
		global $wgStockPath, $wgLang ;
		
		$r = "" ;
		$M = wfMsg( "minoreditletter" );
		$N = wfMsg( "newpageletter" );
		
		# Collate list of users
		$isnew = false ;
		$userlinks = array () ;
		foreach ( $block AS $rcObj ) {
			$oldid = $rcObj->mAttribs['rc_last_oldid'];
			if ( $rcObj->mAttribs['rc_new'] ) $isnew = true ;
			$u = $rcObj->userlink ;
			if ( !isset ( $userlinks[$u] ) ) $userlinks[$u] = 0 ;
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
		$users = " <font size='-1'>[".implode("; ",$users)."]</font>" ;

		# Arrow
		$rci = "RCI{$this->rcCacheIndex}" ;
		$rcl = "RCL{$this->rcCacheIndex}" ;
		$rcm = "RCM{$this->rcCacheIndex}" ;
		$toggleLink = "javascript:toggleVisibility(\"{$rci}\",\"{$rcm}\",\"{$rcl}\")" ;
		$arrowdir = $wgLang->isRTL() ? "l" : "r";
		$tl  = "<span id='{$rcm}'><a href='$toggleLink'><img src='{$wgStockPath}/Arr_{$arrowdir}.png' width='12' height='12' border='0' /></a></span>" ;
		$tl .= "<span id='{$rcl}' style='display:none'><a href='$toggleLink'><img src='{$wgStockPath}/Arr_d.png' width='12' height='12' border='0' /></a></span>" ;
		$r .= $tl ;

		# Main line
		# M/N
		$r .= "<tt>" ;
		if ( $isnew ) $r .= $N ;
		else $r .= "&nbsp;" ;
		$r .= "&nbsp;" ; # Minor

		# Timestamp
		$r .= " ".$block[0]->timestamp." " ;
		$r .= "</tt>" ;
		
		# Article link
		$link = $block[0]->link ;
		if ( $block[0]->watched ) $link = "<strong>{$link}</strong>" ;
		$r .= $link ;
		
		$curIdEq = "curid=" . $block[0]->mAttribs['rc_cur_id'];
		if ( $block[0]->mAttribs['rc_type'] != RC_LOG ) {
			# Changes
			$r .= " (".count($block)." " ;
			if ( $isnew ) $r .= wfMsg("changes");
			else $r .= $this->makeKnownLinkObj( $block[0]->getTitle() , wfMsg("changes") , 
				"{$curIdEq}&diff=0&oldid=".$oldid ) ;
			$r .= "; " ;

			# History
			$r .= $this->makeKnownLinkObj( $block[0]->getTitle(), wfMsg( "history" ), "{$curIdEq}&action=history" );
			$r .= ")" ;
		}

		$r .= $users ;
		$r .= "<br>\n" ;

		# Sub-entries
		$r .= "<div id='{$rci}' style='display:none'>" ;
		foreach ( $block AS $rcObj ) {
			# Get rc_xxxx variables
			extract( $rcObj->mAttribs );
			
			$r .= "<img src='{$wgStockPath}/Arr_.png' width=12 height=12 border=0>";
			$r .= "<tt>&nbsp; &nbsp; &nbsp; &nbsp;" ;
			if ( $rc_new ) $r .= $N ;
			else $r .= "&nbsp;" ;
			if ( $rc_minor ) $r .= $M ;
			else $r .= "&nbsp;" ;
			$r .= "</tt>" ;

			$o = "" ;
			if ( $rc_last_oldid != 0 ) {
				$o = "oldid=".$rc_last_oldid ;
			}
			if ( $rc_type == RC_LOG ) {
				$link = $rcObj->timestamp ;
			} else {
				$link = $this->makeKnownLink( $rcObj->getTitle(), $rcObj->timestamp , "{$curIdEq}&$o" ) ;
			}
			$link = "<tt>{$link}</tt>" ;

			$r .= $link ;
			$r .= " (" ;
			$r .= $rcObj->curlink ;
			$r .= "; " ;
			$r .= $rcObj->lastlink ;
			$r .= ") . . ".$rcObj->userlink ;
			$r .= $rcObj->usertalklink ;
			if ( $rc_comment != "" ) {
				$r .= $wgLang->emphasize( " (".wfEscapeHTML($rc_comment).")" ) ;
			}
			$r .= "<br>\n" ;
		}
		$r .= "</div>\n" ;

		$this->rcCacheIndex++ ;
		return $r ;
	}

	# If enhanced RC is in use, this function takes the previously cached
	# RC lines, arranges them, and outputs the HTML
	function recentChangesBlock ()
	{
		if ( count ( $this->rc_cache ) == 0 ) return "" ;
		#$k = array_keys ( $this->rc_cache ) ;
		foreach ( $this->rc_cache AS $secureName => $block ) {
			if ( count ( $block ) < 2 ) {
				$r .= $this->recentChangesBlockLine ( array_shift ( $block ) ) ;
			} else {
				$r .= $this->recentChangesBlockGroup ( $block ) ;
			}
		}

		return "<div>{$r}</div>" ;
	}

	# Called in a loop over all displayed RC entries
	# Either returns the line, or caches it for later use
	function recentChangesLine( &$rc, $watched = false )
	{
		global $wgUser ;
		$usenew = $wgUser->getOption( "usenewrc" );
		if ( $usenew )
			$line = $this->recentChangesLineNew ( $rc, $watched ) ;
		else
			$line = $this->recentChangesLineOld ( $rc, $watched ) ;
		return $line ;
	}
	
	function recentChangesLineOld( &$rc, $watched = false )
	{
		global $wgTitle, $wgLang, $wgUser;
		
		# Extract DB fields into local scope
		extract( $rc->mAttribs );
		$curIdEq = "curid=" . $rc_cur_id;
		
		# Make date header if necessary
		$date = $wgLang->date( $rc_timestamp, true);
		$s = "";
		if ( $date != $this->lastdate ) {
			if ( "" != $this->lastdate ) { $s .= "</ul>\n"; }
			$s .= "<h4>{$date}</h4>\n<ul>";
			$this->lastdate = $date;
		}
		$s .= "<li> ";
		
		if ( $rc_type == RC_MOVE ) {
			# Diff
			$s .= "(" . wfMsg( "diff" ) . ") (";
			# Hist
			$s .= $this->makeKnownLinkObj( $rc->getMovedToTitle(), wfMsg( "hist" ), "action=history" ) .
				") . . ";
			
			# "[[x]] moved to [[y]]"

			$s .= wfMsg( "1movedto2", $this->makeKnownLinkObj( $rc->getTitle(), "", "redirect=no" ),
				$this->makeKnownLinkObj( $rc->getMovedToTitle(), "" ) );

		} else {
			# Diff link
			if ( $rc_type == RC_NEW || $rc_type == RC_LOG ) {
				$diffLink = wfMsg( "diff" );
			} else {
				$diffLink = $this->makeKnownLinkObj( $rc->getTitle(), wfMsg( "diff" ),
				  "{$curIdEq}&diff={$rc_this_oldid}&oldid={$rc_last_oldid}" );
			}
			$s .= "($diffLink) (";
			
			# History link
			$s .= $this->makeKnownLinkObj( $rc->getTitle(), wfMsg( "hist" ), "{$curIdEq}&action=history" ); 
			$s .= ") . . ";

			# M and N (minor and new)
			$M = wfMsg( "minoreditletter" );
			$N = wfMsg( "newpageletter" );
			if ( $rc_minor ) { $s .= " <strong>{$M}</strong>"; }
			if ( $rc_type == RC_NEW ) { $s .= "<strong>{$N}</strong>"; }

			# Article link
			$articleLink = $this->makeKnownLinkObj( $rc->getTitle(), "" );

			if ( $watched ) {
				$articleLink = "<strong>{$articleLink}</strong>";
			}
			$s .= " $articleLink";

		}
		
		# Timestamp
		$s .= "; " . $wgLang->time( $rc_timestamp, true ) . " . . ";
		
		# User link (or contributions for unregistered users)
		if ( 0 == $rc_user ) {
			$userLink = $this->makeKnownLink( $wgLang->specialPage( "Contributions" ),
			$rc_user_text, "target=" . $rc_user_text );					
		} else { 
			$userLink = $this->makeLink( $wgLang->getNsText( NS_USER ) . ":{$rc_user_text}", $rc_user_text ); 
		}
		$s .= $userLink;
		
		# User talk link
		$talkname=$wgLang->getNsText(NS_TALK); # use the shorter name
		global $wgDisableAnonTalk;
		if( 0 == $rc_user && $wgDisableAnonTalk ) {
			$userTalkLink = "";
		} else {
			$utns=$wgLang->getNsText(NS_USER_TALK);
			$userTalkLink= $this->makeLink($utns . ":{$rc_user_text}", $talkname );
		}
		# Block link
		$blockLink="";
		if ( ( 0 == $rc_user ) && $wgUser->isSysop() ) {
			$blockLink = $this->makeKnownLink( $wgLang->specialPage(
			  "Blockip" ), wfMsg( "blocklink" ), "ip={$rc_user_text}" );
			
		}
		if($blockLink) { 
			if($userTalkLink) $userTalkLink .= " | ";
			$userTalkLink .= $blockLink;
		}
		if($userTalkLink) $s.=" ({$userTalkLink})";

		# Add comment
		if ( "" != $rc_comment && "*" != $rc_comment && $rc_type != RC_MOVE ) {
			$s .= $wgLang->emphasize(" (" . wfEscapeHTML( $rc_comment ) . ")");
		}
		$s .= "</li>\n";

		return $s;
	}
	
#	function recentChangesLineNew( $ts, $u, $ut, $ns, $ttl, $c, $isminor, $isnew, $watched = false, $oldid = 0 , $diffid = 0 )
	function recentChangesLineNew( &$baseRC, $watched = false )
	{
		global $wgTitle, $wgLang, $wgUser;

		# Create a specialised object
		$rc = RCCacheEntry::newFromParent( $baseRC ) ;

		# Extract fields from DB into the function scope (rc_xxxx variables)
		extract( $rc->mAttribs );
		$curIdEq = "curid=" . $rc_cur_id;

		# If it's a new day, add the headline and flush the cache
		$date = $wgLang->date( $rc_timestamp, true);
		$ret = "" ;
		if ( $date != $this->lastdate ) {
			# Process current cache
			$ret = $this->recentChangesBlock () ;
			$this->rc_cache = array() ;
			$ret .= "<h4>{$date}</h4>\n";
			$this->lastdate = $date;
		}
		
		# Make article link
		if ( $rc_type == RC_MOVE ) {
			$clink = $this->makeKnownLinkObj( $rc->getTitle(), "", "redirect=no" );
			$clink .= " " . wfMsg("movedto") . " ";
			$clink .= $this->makeKnownLinkObj( $rc->getMovedToTitle(), "" );
		} else {
			$clink = $this->makeKnownLinkObj( $rc->getTitle(), "" ) ;
		}
		
		$time = $wgLang->time( $rc_timestamp, true );
		$rc->watched = $watched ;
		$rc->link = $clink ;
		$rc->timestamp = $time;
		
		# Make "cur" link
		if ( ( $rc_type == RC_NEW && $rc_this_oldid == 0 ) || $rc_type == RC_LOG || $rc_type == RC_MOVE) {
			$curLink = wfMsg( "cur" );
		} else {
			$curLink = $this->makeKnownLinkObj( $rc->getTitle(), wfMsg( "cur" ),
			  "{$curIdEq}&diff=0&oldid={$rc_this_oldid}" );
		}

		# Make "last" link
		$titleObj = $rc->getTitle();
		if ( $rc_last_oldid == 0 || $rc_type == RC_LOG || $rc_type == RC_MOVE ) {
			$lastLink = wfMsg( "last" );
		} else {
			$lastLink = $this->makeKnownLinkObj( $rc->getTitle(), wfMsg( "last" ),
			  "{$curIdEq}&diff={$rc_this_oldid}&oldid={$rc_last_oldid}" );
		}

		# Make user link (or user contributions for unregistered users)
		if ( 0 == $rc_user ) {
			$userLink = $this->makeKnownLink( $wgLang->specialPage( "Contributions" ),
			$rc_user_text, "target=" . $rc_user_text );
		} else { 
			$userLink = $this->makeLink( $wgLang->getNsText(
			  Namespace::getUser() ) . ":{$rc_user_text}", $rc_user_text ); 
		}

		$rc->userlink = $userLink ;
		$rc->lastlink = $lastLink ;
		$rc->curlink = $curLink ;

		# Make user talk link		
		$utns=$wgLang->getNsText(NS_USER_TALK);
		$talkname=$wgLang->getNsText(NS_TALK); # use the shorter name
		$userTalkLink= $this->makeLink($utns . ":{$rc_user_text}", $talkname );	
		
		global $wgDisableAnonTalk;
		if ( ( 0 == $rc_user ) && $wgUser->isSysop() ) {
			$blockLink = $this->makeKnownLink( $wgLang->specialPage(
			  "Blockip" ), wfMsg( "blocklink" ), "ip={$rc_user_text}" );
			if( $wgDisableAnonTalk )
				$rc->usertalklink = " ({$blockLink})";
			else
				$rc->usertalklink = " ({$userTalkLink} | {$blockLink})";
		} else {
			if( $wgDisableAnonTalk && ($rc_user == 0) )
				$rc->usertalklink = "";
			else
				$rc->usertalklink = " ({$userTalkLink})";
		}

		# Put accumulated information into the cache, for later display
		# Page moves go on their own line
		$title = $rc->getTitle();
		$secureName = $title->getPrefixedDBkey();
		if ( $rc_type == RC_MOVE ) {
			# Use an @ character to prevent collision with page names
			$this->rc_cache["@@" . ($this->rcMoveIndex++)] = array($rc);
		} else {
			if ( !isset ( $this->rc_cache[$secureName] ) ) $this->rc_cache[$secureName] = array() ;
			array_push ( $this->rc_cache[$secureName] , $rc ) ;
		}
		return $ret;
	}

	function endImageHistoryList()
	{
		$s = "</ul>\n";
		return $s;
	}

	function imageHistoryLine( $iscur, $ts, $img, $u, $ut, $size, $c )
	{
		global $wgUser, $wgLang, $wgTitle;

		$dt = $wgLang->timeanddate( $ts, true );
		$del = wfMsg( "deleteimg" );
		$cur = wfMsg( "cur" );

		if ( $iscur ) {
			$url = wfImageUrl( $img );
			$rlink = $cur;
			if ( $wgUser->isSysop() ) {
				$link = wfLocalUrlE( $wgTitle->getPrefixedText(), "image=" . $wgTitle->getURL() .
				  "&action=delete" );
				$style = $this->getInternalLinkAttributes( $link, $del );

				$dlink = "<a href=\"{$link}\"{$style}>{$del}</a>";
			} else {
				$dlink = $del;
			}
		} else {
			$url = wfEscapeHTML( wfImageArchiveUrl( $img ) );
			if( $wgUser->getID() != 0 ) {
				$rlink = $this->makeKnownLink( $wgTitle->getPrefixedText(),
				  wfMsg( "revertimg" ), "action=revert&oldimage=" .
				  urlencode( $img ) );
				$dlink = $this->makeKnownLink( $wgTitle->getPrefixedText(),
				  $del, "action=delete&oldimage=" . urlencode( $img ) );
			} else {
				# Having live active links for non-logged in users
				# means that bots and spiders crawling our site can
				# inadvertently change content. Baaaad idea.
				$rlink = wfMsg( "revertimg" );
				$dlink = $del;
			}
		}
		if ( 0 == $u ) { $ul = $ut; }
		else { $ul = $this->makeLink( $wgLang->getNsText(
		  Namespace::getUser() ) . ":{$ut}", $ut ); }

		$nb = wfMsg( "nbytes", $size );
		$style = $this->getInternalLinkAttributes( $url, $dt );

		$s = "<li> ({$dlink}) ({$rlink}) <a href=\"{$url}\"{$style}>{$dt}</a>"
		  . " . . {$ul} ({$nb})";

		if ( "" != $c && "*" != $c ) {
			$s .= $wgLang->emphasize(" (" . wfEscapeHTML( $c ) . ")");
		}
		$s .= "</li>\n";
		return $s;
	}

	function tocIndent($level) {

		while($level-->0) $rv.="<div style=\"margin-left:2em;\">\n";
		return $rv;

	}

	function tocUnindent($level) {
		$rv = "";
		while($level-->0) $rv.="</div>\n";
		return $rv;
	}

	// parameter level defines if we are on an indentation level
	function tocLine($anchor,$tocline,$level) {
		
		if($level) { 
		
			return "<A CLASS=\"internal\" HREF=\"#".$anchor."\">".$tocline."</A><BR>\n";
		} else { 

			return "<div style=\"margin-bottom:0px;\">\n".
			"<A CLASS=\"internal\" HREF=\"#".$anchor."\">".$tocline."</A><BR>\n".
			"</div>\n";
		}

	}

	function tocTable($toc) {
		// note to CSS fanatics: putting this in a div does not work -- div won't auto-expand
		global $printable;
		
		if (!$printable) {
			$hideline = " <script type='text/javascript'>showTocToggle(\"" . wfMsg("showtoc") . "\",\"" . wfMsg("hidetoc") . "\")</script>";
		}
		return
		"<p><table border=\"0\" id=\"toc\"><tr><td align=\"center\">\n".
		"<b>".wfMsg("toc")."</b>" .
		$hideline .
		"</td></tr><tr id='tocinside'><td>\n".
		$toc."</td></tr></table><P>\n";
	}

	# These two do not check for permissions: check $wgTitle->userCanEdit before calling them
	function editSectionScript($section,$head) {

		global $wgTitle,$wgUser,$oldid;
		if($oldid) return $head;
		$url = wfLocalUrlE(urlencode(str_replace(' ','_',$wgTitle->getPrefixedText())),"action=edit&section=$section");
		return "<span onContextMenu='document.location=\"".$url."\";return false;'>{$head}</span>";
	}

	function editSectionLink($section) {
		global $printable,$oldid;
		global $wgTitle, $wgUser, $wgLang;
		
		if( isset( $oldid ) ) return "";
		if( isset( $printable ) ) return "";
		
		$editurl = "&section={$section}";
		$url = $this->makeKnownLink($wgTitle->getPrefixedText(),wfMsg("editsection"),"action=edit".$editurl);
		
		if( $wgLang->isRTL() ) {
			$farside = "left";
			$nearside = "right";
		} else {
			$farside = "right";
			$nearside = "left";
		}
		return "<div style=\"float:$farside;margin-$nearside:5px;\"><small>[".$url."]</small></div>";

	}

	// This function is called by EditPage.php and shows a bulletin board style
	// toolbar for common editing functions. It can be disabled in the user preferences.
	// The necsesary JavaScript code can be found in style/wikibits.js.
	function getEditToolbar() {

		global $wgStockPath,$wgLang;

		// toolarray an array of arrays which each include the filename of
		// the button image (without path), the opening tag, the closing tag,
		// and optionally a sample text that is inserted between the two when no
		// selection is highlighted.
		// The tip text is shown when the user moves the mouse over the button.
		$toolarray=array(
			array(	"image"=>"button_bold.png",
				"open"=>"\'\'\'",
				"close"=>"\'\'\'",
				"sample"=>wfMsg("bold_sample"),
				"tip"=>wfMsg("bold_tip")),
			array(	"image"=>"button_italic.png",
				"open"=>"\'\'",
				"close"=>"\'\'",
				"sample"=>wfMsg("italic_sample"),
				"tip"=>wfMsg("italic_tip")),
			array(	"image"=>"button_link.png",
				"open"=>"[[",
				"close"=>"]]",
				"sample"=>wfMsg("link_sample"),
				"tip"=>wfMsg("link_tip")),
			array(	"image"=>"button_extlink.png",
				"open"=>"[",
				"close"=>"]",
				"sample"=>wfMsg("extlink_sample"),
				"tip"=>wfMsg("extlink_tip")),
			array(	"image"=>"button_headline.png",
				"open"=>"\\n== ",
				"close"=>" ==\\n",
				"sample"=>wfMsg("headline_sample"),
				"tip"=>wfMsg("headline_tip")),
			array(	"image"=>"button_image.png",
				"open"=>"[[".$wgLang->getNsText(NS_IMAGE).":",
				"close"=>"]]",
				"sample"=>wfMsg("image_sample"),
				"tip"=>wfMsg("image_tip")),
			array(	"image"=>"button_media.png",
				"open"=>"[[".$wgLang->getNsText(NS_MEDIA).":",
				"close"=>"]]",
				"sample"=>wfMsg("media_sample"),
				"tip"=>wfMsg("media_tip")),
			array(	"image"=>"button_math.png",
				"open"=>"\\<math\\>",
				"close"=>"\\</math\\>",
				"sample"=>wfMsg("math_sample"),
				"tip"=>wfMsg("math_tip")),
			array(	"image"=>"button_nowiki.png",
				"open"=>"\\<nowiki\\>",
				"close"=>"\\</nowiki\\>",
				"sample"=>wfMsg("nowiki_sample"),
				"tip"=>wfMsg("nowiki_tip")),
			array(	"image"=>"button_sig.png",
				"open"=>"--~~~~",
				"close"=>"",
				"sample"=>"",
				"tip"=>wfMsg("sig_tip")),
			array(	"image"=>"button_hr.png",
				"open"=>"\\n----\\n",
				"close"=>"",
				"sample"=>"",
				"tip"=>wfMsg("hr_tip"))
		);
		$toolbar.="<script type='text/javascript'>\n";
		$toolbar.="document.writeln(\"<div id='toolbar'>\");\n";
		foreach($toolarray as $tool) {

			$image=$wgStockPath."/".$tool["image"];
			$open=$tool["open"];
			$close=$tool["close"];
			$sample = addslashes( $tool["sample"] );

			// Note that we use the tip both for the ALT tag and the TITLE tag of the image.
			// Older browsers show a "speedtip" type message only for ALT.
			// Ideally these should be different, realistically they
			// probably don't need to be.
			$tip = addslashes( $tool["tip"] );
			$toolbar.="addButton('$image','$tip','$open','$close','$sample');\n";
		}

		$toolbar.="addInfobox('" . addslashes( wfMsg( "infobox" ) ) . "');\n";
		$toolbar.="document.writeln(\"</div>\");\n</script>";
		return $toolbar;
	}
}

include_once( "SkinStandard.php" );
include_once( "SkinNostalgia.php" );
include_once( "SkinCologneBlue.php" );

#include_once( "SkinSmarty.php" );

?>
