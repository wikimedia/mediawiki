<?
# See skin.doc

# These are the INTERNAL names, which get mapped
# directly to class names.  For display purposes, the
# Language class has internationalized names
#
/* private */ $wgValidSkinNames = array(
	"Standard", "Nostalgia", "CologneBlue"
);

class RecentChangesClass {
	var $secureName , $displayName , $link , $namespace ;
	var $oldid , $diffid , $timestamp , $curlink , $lastlink , $usertalklink , $versionlink ;
	var $usercomment , $userlink ;
	var $isminor , $isnew , $watched , $islog ;
	} ;

class Skin {

	/* private */ var $lastdate, $lastline;

	var $rc_cache ; # Cache for Enhanced Recent Changes
	var $rccc ; # Recent Changes Cache Counter for visibility toggle


	function Skin()
	{
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

		if ( $wgOut->isQuickbarSupressed() ) { return 0; }
		$q = $wgUser->getOption( "quickbar" );
		if ( "" == $q ) { $q = 0; }
		return $q;
	}

	function initPage()
	{
		global $wgOut, $wgStyleSheetPath;
		wfProfileIn( "Skin::initPage" );

		$wgOut->addLink( "shortcut icon", "", "/favicon.ico" );
		if ( $wgOut->isPrintable() ) { $ss = "wikiprintable.css"; }
		else { $ss = $this->getStylesheet(); }
		$wgOut->addLink( "stylesheet", "", "{$wgStyleSheetPath}/{$ss}" );
		wfProfileOut();
	}

	function getHeadScripts() {
		$r = "
<SCRIPT TYPE=\"text/javascript\">
function toggleVisibility( _levelId, _otherId, _linkId) {
	var thisLevel = document.getElementById( _levelId );
	var otherLevel = document.getElementById( _otherId );
	var linkLevel = document.getElementById( _linkId );
	if ( thisLevel.style.display == 'none' ) {
		thisLevel.style.display = 'block';
		otherLevel.style.display = 'none';
		linkLevel.style.display = 'inline';
	} else {
		thisLevel.style.display = 'none';
		otherLevel.style.display = 'inline';
		linkLevel.style.display = 'none';
		}
	}
</SCRIPT>
		" ;
		return $r;
	}

	function getUserStyles()
	{
		$s = "<style type='text/css' media='screen'><!--\n";
		$s .= $this->doGetUserStyles();
		$s .= "//--></style>\n";
		return $s;
	}

	function doGetUserStyles()
	{
		global $wgUser;

		$s = "";
		if ( 1 == $wgUser->getOption( "underline" ) ) {
			$s .= "a.stub, a.new, a.internal, a.external { " .
			  "text-decoration: underline; }\n";
		} else {
			$s .= "a.stub, a.new, a.internal, a.external { " .
			  "text-decoration: none; }\n";
		}
		if ( 1 == $wgUser->getOption( "highlightbroken" ) ) {
			$s .= "a.new { color: #CC2200; }\n" .
			  "#quickbar a.new { color: CC2200; }\n";
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
			$a = array( "bgcolor" => "#FFFFDD" );
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
		if($action=="edit") { # set focus in edit box
			$a += array("onLoad"=>"document.editform.wpTextbox1.focus()");	
		}
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

		if ( $wgOut->isPrintable() ) { $r = " class='printable'"; }
                else if ( $broken == "stub" ) { $r = " class='stub'"; }
		else if ( $broken == "yes" ) { $r = " class='new'"; }
		else { $r = " class='internal'"; }

		if ( 1 == $wgUser->getOption( "hover" ) ) {
			$r .= " title=\"{$link}\"";
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
		global $wgUser, $wgOut;

		if ( $wgOut->isPrintable() ) {
			$s = $this->pageTitle() . $this->pageSubtitle() . "\n";
			$s .= "\n<div class='bodytext'>";
			return $s;
		}
		return $this->doBeforeContent();
	}

	function doBeforeContent()
	{
		global $wgUser, $wgOut, $wgTitle;
		wfProfileIn( "Skin::doBeforeContent" );

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

		if ( 0 == $qb ) {
			$s .= "<td class='top' align=left valign=top rowspan='{$rows}'>" .
			  $this->logoText() . "</td>";
		} else if ( 1 == $qb || 3 == $qb ) { # Left
			$s .= $this->getQuickbarCompensator( $rows );
		}
		$s .= "<td {$borderhack} align=left valign=top>";

		$s .= $this->topLinks() ;
		$s .= "<p class='subtitle'>" . $this->pageTitleLinks();

		$s .= "</td>\n<td {$borderhack} valign=top align=right nowrap>";
		$s .= $this->nameAndLogin();
		$s .= "\n<br>" . $this->searchForm() . "</td>";

		if ( $langlinks ) {
			$s .= "</tr>\n<tr><td class='top' colspan=\"2\">$langlinks</td>";
		}

		if ( 2 == $qb ) { # Right
			$s .= $this->getQuickbarCompensator( $rows );
		}
		$s .= "</tr></table>\n</div>\n";
		$s .= "\n<div id='article'>";

		$s .= $this->pageTitle();
		$s .= $this->pageSubtitle() . "\n<p>";
		wfProfileOut();
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
		global $wgUser, $wgOut, $wgServer, $HTTP_SERVER_VARS;

		if ( $wgOut->isPrintable() ) {
			$s = "\n</div>\n";

			$u = $wgServer . $HTTP_SERVER_VARS['REQUEST_URI'];
			$u = preg_replace( "/[?&]printable=yes/", "", $u );
			$rf = str_replace( "$1", $u, wfMsg( "retrievedfrom" ) );

			if ( $wgOut->isArticle() ) {
				$lm = "<br>" . $this->lastModified();
			} else { $lm = ""; }

			$s .= "<p><em>{$rf}{$lm}</em>\n";
			return $s;
		}
		return $this->doAfterContent();
	}

	function doAfterContent()
	{
		global $wgUser, $wgOut;
		wfProfileIn( "Skin::doAfterContent" );

		$s = "\n</div><br clear=all>\n";

		$s .= "\n<div id='footer'>";
		$s .= "<table width='98%' border=0 cellspacing=0><tr>";

		$qb = $this->qbSetting();
		if ( 1 == $qb || 3 == $qb ) { # Left
			$s .= $this->getQuickbarCompensator();
		}
		$s .= "<td class='bottom' align=left valign=top>";

		$s .= $this->bottomLinks();
		$s .= "\n<br>" . $this->mainPageLink()
		  . " | " . $this->aboutLink()
		  . " | " . $this->specialLink( "recentchanges" )
		  . " | " . $this->searchForm()
		  . "<br>" . $this->pageStats();

		$s .= "</td>";
		if ( 2 == $qb ) { # Right
			$s .= $this->getQuickbarCompensator();
		}
		$s .= "</tr></table>\n</div>\n</div>\n";

		if ( 0 != $qb ) { $s .= $this->quickBar(); }
		wfProfileOut();
		return $s;
	}

	function pageTitleLinks()
	{
		global $wgOut, $wgTitle, $oldid, $action, $diff, $wgUser, $wgLang;

		$s = $this->printableLink();

		if ( $wgOut->isArticle() ) {
			if ( $wgTitle->getNamespace() == Namespace::getImage() ) {
				$name = $wgTitle->getDBkey();
				$link = wfEscapeHTML( wfImageUrl( $name ) );
				$style = $this->getInternalLinkAttributes( $link, $name );
				$s .= " | <a href=\"{$link}\"{$style}>{$name}</a>";
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
				$s.=" | <strong>". str_replace( "$1", $tl, wfMsg("newmessages") ) . "</strong>";
			}
		}
		return $s;
	}

	function printableLink()
	{
		global $wgOut, $wgTitle, $oldid, $action;

		if ( "history" == $action ) { $q = "action=history&"; }
		else { $q = ""; }

		$s = $this->makeKnownLink( $wgTitle->getPrefixedText(),
		  WfMsg( "printableversion" ), "{$q}printable=yes" );
		return $s;
	}

	function pageTitle()
	{
		global $wgOut, $wgTitle;

		$s = "<h1 class='pagetitle'>" . $wgOut->getPageTitle() . "</h1>";
		return $s;
	}

	function pageSubtitle()
	{
		global $wgOut,$wgTitle,$wgNamespacesWithSubpages;

		$sub = $wgOut->getSubtitle();
		if ( "" == $sub ) { $sub = wfMsg( "fromwikipedia" ); }
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
						$growinglink.=$link;
						$getlink=$this->makeLink($growinglink,$link);						
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
		global $wgUser, $wgTitle, $wgLang, $wgShowIPinHeader;

		$li = $wgLang->specialPage( "Userlogin" );
		$lo = $wgLang->specialPage( "Userlogout" );

		$s = "";
		if ( 0 == $wgUser->getID() ) {
			if( $wgShowIPinHeader ) {
				$n = getenv( "REMOTE_ADDR" );

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

		$s = "<form name='search' class='inline' method=get action=\""
		  . wfLocalUrl( "" ) . "\">"
		  . "<input type=text name=\"search\" size=19 value=\""
		  . htmlspecialchars(substr($search,0,256)) . "\">\n"
		  . "<input type=submit name=\"go\" value=\"" . wfMsg ("go") . "\">&nbsp;"
		  . "<input type=submit value=\"" . wfMsg ("search") . "\"></form>";

		return $s;
	}

	function topLinks()
	{
		global $wgOut;
		$sep = " |\n";

		$s = $this->mainPageLink() . $sep
		  . $this->specialLink( "recentchanges" );

		if ( $wgOut->isArticle() ) {
			$s .=  $sep . $this->editThisPage()
			  . $sep . $this->historyLink();
		}
		$s .= $sep . $this->specialPagesList();

		return $s;
	}

	function bottomLinks()
	{ 
		global $wgOut, $wgUser, $wgTitle;
		$sep = " |\n";

		$s = "";
		if ( $wgOut->isArticle() ) {
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
			$s = str_replace( "$1", $count, wfMsg( "viewcount" ) );
		}
		$s .= $this->lastModified();
		$s .= " " . wfMsg( "gnunote" );
		return "<span id='pagestats'>{$s}</span>";
	}

	function lastModified()
	{
		global $wgLang, $wgArticle;

		$d = $wgLang->timeanddate( $wgArticle->getTimestamp(), true );
		$s = " " . str_replace( "$1", $d, wfMsg( "lastmodified" ) );
		return $s;
	}

	function logoText( $align = "" )
	{
		if ( "" != $align ) { $a = " align='{$align}'"; }
		else { $a = ""; }

		$mp = wfMsg( "mainpage" );
		$s = "<a href=\"" . wfLocalUrlE( $mp ) . "\"><img{$a} border=0 src=\""
		  . $this->getLogo() . "\" alt=\"" . "[{$mp}]\"></a>";
		return $s;
	}

	function quickBar()
	{
		global $wgOut, $wgTitle, $wgUser, $action, $wgLang;
		global $wpPreview;
		wfProfileIn( "Skin::quickBar" );

		$s = "\n<div id='quickbar'>";
		$s .= "\n" . $this->logoText() . "\n<hr>";

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
                $s .= "\n<hr>";
		$articleExists = $wgTitle->getArticleId();
		if ( $wgOut->isArticle() || $action =="edit" || $action =="history" || $wpPreview) {
						
			if($wgOut->isArticle()) {
				$s .= "<strong>" . $this->editThisPage() . "</strong>";
			} else { # backlink to the article in edit or history mode

				if($articleExists){ # no backlink if no article
					$tns=$wgTitle->getNamespace();		
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
					$s .= $this->makeLink($link, $text );			
				} elseif( $wgTitle->getNamespace() != Namespace::getSpecial() ) {
					# we just throw in a "New page" text to tell the user that he's in edit mode,
					# and to avoid messing with the separator that is prepended to the next item
					$s .= "<strong>" . wfMsg("newpage") . "</strong>";
				}
			
			}
			
			/*
			watching could cause problems in edit mode:
			if user edits article, then loads "watch this article" in background and then saves
			article with "Watch this article" checkbox disabled, the article is transparently
			unwatched. Therefore we do not show the "Watch this page" link in edit mode
			*/			
			if ( 0 != $wgUser->getID() && $articleExists) {
				if($action!="edit" && $action!="history" &&
                                   $action != "submit" ) 
				{$s .= $sep . $this->watchThisPage(); }
				if ( $wgTitle->userCanEdit() ) $s .= $sep . $this->moveThisPage();
			}
			if ( $wgUser->isSysop() and $articleExists ) {
				$s .= $sep . $this->deleteThisPage() .
				$sep . $this->protectThisPage();
			}
			$s .= $sep . $this->talkLink();
			if ($articleExists && $action !="history") { $s .= $sep . $this->historyLink();}
			$s.=$sep . $this->whatLinksHere();
			
			if($wgOut->isArticle()) {
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
			$s .= "\n<hr>";
		} 
		
		if ( 0 != $wgUser->getID() ) {
			$s .= $this->specialLink( "upload" ) . $sep;
		}
		$s .= $this->specialLink( "specialpages" )
		  . $sep . $this->bugReportsLink();

		$s .= "\n</div>\n";
		wfProfileOut();
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

	function editThisPage()
	{
		global $wgOut, $wgTitle, $oldid, $redirect, $diff;

		if ( ! $wgOut->isArticle() || $diff ) {
			$s = wfMsg( "protectedpage" );
		} else if ( $wgTitle->userCanEdit() ) {
			$n = $wgTitle->getPrefixedText();
			$t = wfMsg( "editthispage" );
			$oid = $red = "";

			if ( $redirect ) { $red = "&redirect={$redirect}"; }
			if ( $oldid && ! isset( $diff ) ) {
				$oid = "&oldid={$oldid}";
			}
			$s = $this->makeKnownLink( $n, $t, "action=edit{$oid}{$red}" );
		} else {
			$s = wfMsg( "protectedpage" );
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
			$s = wfMsg( "error" );
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
			$s = wfMsg( "error" );
		}
		return $s;
	}

	function watchThisPage()
	{
		global $wgUser, $wgOut, $wgTitle, $diff;

		if ( $wgOut->isArticle() && ( ! $diff ) ) {
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

		if ( ! $wgOut->isArticle() ) {
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
		global $wgOut, $wgLang, $wgTitle , $wgUseNewInterlanguage ;

		$a = $wgOut->getLanguageLinks();
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

		$first = true;
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
		$t1 = Title::newFromText( date( "F j" ) );
		$t2 = Title::newFromText( date( "Y" ) );

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
	function makeLink( $title, $text= "", $query = "", $trail = "" )
	{
		global $wgOut, $wgUser;

		$nt = Title::newFromText( $title );

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
			return "<a href=\"{$u}\"{$style}>{$text}{$inside}</a>{$trail}";
		}
		if ( 0 == $nt->getNamespace() && "" == $nt->getText() ) {
			return $this->makeKnownLink( $title, $text, $query, $trail );
		}
		if ( ( -1 == $nt->getNamespace() ) ||
          ( Namespace::getImage() == $nt->getNamespace() ) ) {
			return $this->makeKnownLink( $title, $text, $query, $trail );
		}
                $aid = $nt->getArticleID() ;
                if ( 0 == $aid ) {
                        return $this->makeBrokenLink( $title, $text, $query, $trail );
                } else {
                        $threshold = $wgUser->getOption("stubthreshold") ;
                        if ( $threshold > 0 ) {
                                $res = wfQuery ( "SELECT HIGH_PRIORITY length(cur_text) AS x, cur_namespace, cur_is_redirect FROM cur WHERE cur_id='{$aid}'" ) ;

                                if ( wfNumRows( $res ) > 0 ) {
                                        $s = wfFetchObject( $res );
                                        $size = $s->x;
                                        if ( $s->cur_is_redirect OR $s->cur_namespace != 0 )
                                                $size = $threshold*2 ; # Really big
                                        wfFreeResult( $res );
                                } else $size = $threshold*2 ; # Really big
                        } else $size = 1 ;

                        if ( $size < $threshold )
                                return $this->makeStubLink( $title, $text, $query, $trail );
                        return $this->makeKnownLink( $title, $text, $query, $trail );
                }
        }

	function makeKnownLink( $title, $text = "", $query = "", $trail = "" )
	{
		global $wgOut, $wgTitle;

		$nt = Title::newFromText( $title );
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
		$style = $this->getInternalLinkAttributes( $link, $text );

		$inside = "";
		if ( "" != $trail ) {
			if ( preg_match( wfMsg("linktrail"), $trail, $m ) ) {
				$inside = $m[1];
				$trail = $m[2];
			}
		}
		$r = "<a href=\"{$u}\"{$style}>{$text}{$inside}</a>{$trail}";
		return $r;
	}

	function makeBrokenLink( $title, $text = "", $query = "", $trail = "" )
	{
		global $wgOut, $wgUser;

		$nt = Title::newFromText( $title );
		$link = $nt->getPrefixedURL();

		if ( "" == $query ) { $q = "action=edit"; }
		else { $q = "action=edit&{$query}"; }
		$u = wfLocalUrlE( $link, $q );

		if ( "" == $text ) { $text = $nt->getPrefixedText(); }
		$style = $this->getInternalLinkAttributes( $link, $text, "yes" );

		$inside = "";
		if ( "" != $trail ) {
			if ( preg_match( wfMsg("linktrail"), $trail, $m ) ) {
				$inside = $m[1];
				$trail = $m[2];
			}
		}
		if ( $wgOut->isPrintable() ||
		  ( 1 == $wgUser->getOption( "highlightbroken" ) ) ) {
			$s = "<a href=\"{$u}\"{$style}>{$text}{$inside}</a>{$trail}";
		} else {
			$s = "{$text}{$inside}<a href=\"{$u}\"{$style}>?</a>{$trail}";
		}
		return $s;
	}

        function makeStubLink( $title, $text = "", $query = "", $trail = "" )
        {
                global $wgOut, $wgUser;

                $nt = Title::newFromText( $title );
                $link = $nt->getPrefixedURL();

                $u = wfLocalUrlE( $link, $query );

                if ( "" == $text ) { $text = $nt->getPrefixedText(); }
                $style = $this->getInternalLinkAttributes( $link, $text, "stub" );

                $inside = "";
                if ( "" != $trail ) {
                        if ( preg_match( wfMsg("linktrail"), $trail, $m ) ) {
                                $inside = $m[1];
                                $trail = $m[2];
                        }
                }
                if ( $wgOut->isPrintable() ||
                  ( 1 == $wgUser->getOption( "highlightbroken" ) ) ) {
                        $s = "<a href=\"{$u}\"{$style}>{$text}{$inside}</a>{$trail}";
                } else {
                        $s = "{$text}{$inside}<a href=\"{$u}\"{$style}>!</a>{$trail}";
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

	function makeImageLink( $name, $url, $alt = "" )
	{
		global $wgOut, $wgTitle, $wgLang;

		$nt = Title::newFromText( $wgLang->getNsText(
		  Namespace::getImage() ) . ":{$name}" );
		$link = $nt->getPrefixedURL();
		if ( "" == $alt ) { $alt = $name; }

		$u = wfLocalUrlE( $link );
		$s = "<a href=\"{$u}\" class='image' title=\"{$alt}\">" .
		  "<img border=0 src=\"{$url}\" alt=\"{$alt}\"></a>";
		return $s;
	}

	function makeMediaLink( $name, $url, $alt = "" )
	{
		global $wgOut, $wgTitle;

		if ( "" == $alt ) { $alt = $name; }
		$u = wfEscapeHTML( $url );
		$s = "<a href=\"{$u}\" class='media' title=\"{$alt}\">{$alt}</a>";
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

	function beginRecentChangesList()
	{
		$rc_cache = array() ;
		$rccc = 0 ;
		$this->lastdate = "";
		return "";
	}

	function beginHistoryList()
	{
		$this->lastdate = $this->lastline = "";
		$s = "\n<p>" . wfMsg( "histlegend" ) . "\n<ul>";
		return $s;
	}

	function beginImageHistoryList()
	{
		$s = "\n<h2>" . wfMsg( "imghistory" ) . "</h2>\n" .
		  "<p>" . wfMsg( "imghistlegend" ) . "\n<ul>";
		return $s;
	}

	function endRecentChangesList()
	{
		$s = $this->recentChangesBlock() ;
		$s .= "</ul>\n";
		return $s;
	}

	function endHistoryList()
	{
		$last = wfMsg( "last" );

		$s = preg_replace( "/!OLDID![0-9]+!/", $last, $this->lastline );
		$s .= "</ul>\n";
		return $s;
	}

	function endImageHistoryList()
	{
		$s = "</ul>\n";
		return $s;
	}

	function historyLine( $ts, $u, $ut, $ns, $ttl, $oid, $c, $isminor )
	{
		global $wgLang;

		$artname = Title::makeName( $ns, $ttl );
		$last = wfMsg( "last" );
		$cur = wfMsg( "cur" );
		$cr = wfMsg( "currentrev" );

		if ( $oid && $this->lastline ) {
			$ret = preg_replace( "/!OLDID!([0-9]+)!/", $this->makeKnownLink(
			  $artname, $last, "diff=\\1&oldid={$oid}" ), $this->lastline );
		} else {
			$ret = "";
		}
		$dt = $wgLang->timeanddate( $ts, true );

		if ( $oid ) { $q = "oldid={$oid}"; }
		else { $q = ""; }
		$link = $this->makeKnownLink( $artname, $dt, $q );

		if ( 0 == $u ) {
            $ul = $this->makeKnownLink( $wgLang->specialPage( "Contributions" ),
			$ut, "target=" . $ut );
		} else { $ul = $this->makeLink( $wgLang->getNsText(
		  Namespace::getUser() ) . ":{$ut}", $ut ); }

		$s = "<li>";
		if ( $oid ) {
			$curlink = $this->makeKnownLink( $artname, $cur,
			  "diff=0&oldid={$oid}" );
		} else {
			$curlink = $cur;
		}
		$s .= "({$curlink}) (!OLDID!{$oid}!) . .";

		$M = wfMsg( "minoreditletter" );
		if ( $isminor ) { $s .= " <strong>{$M}</strong>"; }
		$s .= " {$link} . . {$ul}";

		if ( "" != $c && "*" != $c ) { $s .= " <em>(" . wfEscapeHTML($c) . ")</em>"; }
		$s .= "</li>\n";

		$this->lastline = $s;
		return $ret;
	}

	function recentChangesBlockLine ( $y ) {
		global $wgUploadPath ;

		$M = wfMsg( "minoreditletter" );
		$N = wfMsg( "newpageletter" );
		$r = "" ;
		$r .= "<img src='{$wgUploadPath}/Arr_.png' width=12 height=12 border=0>" ;
		$r .= "<tt>" ;
		if ( $y->isnew ) $r .= $N ;
		else $r .= "&nbsp;" ;
		if ( $y->isminor ) $r .= $M ;
		else $r .= "&nbsp;" ;
		$r .= " ".$y->timestamp." " ;
		$r .= "</tt>" ;
		$link = $y->link ;
		if ( $y->watched ) $link = "<strong>{$link}</strong>" ;
		$r .= $link ;

		$r .= " (" ;
		$r .= $y->curlink ;
		$r .= "; " ;
		$r .= $this->makeKnownLink( $y->secureName, wfMsg( "hist" ), "action=history" );

		$r .= ") . . ".$y->userlink ;
		$r .= $y->usertalklink ;
		if ( $y->usercomment != "" )
			$r .= " <em>(".wfEscapeHTML($y->usercomment).")</em>" ;
		$r .= "<br>\n" ;
		return $r ;
		}

	function recentChangesBlockGroup ( $y ) {
		global $wgUploadPath ;

		$r = "" ;
		$M = wfMsg( "minoreditletter" );
		$N = wfMsg( "newpageletter" );
		$isnew = false ;
		$userlinks = array () ;
		foreach ( $y AS $x ) {
			$oldid = $x->diffid ;
			if ( $x->isnew ) $isnew = true ;
			$u = $x->userlink ;
			if ( !isset ( $userlinks[$u] ) ) $userlinks[$u] = 0 ;
			$userlinks[$u]++ ;
			}

		krsort ( $userlinks ) ;
		asort ( $userlinks ) ;
		$users = array () ;
		$u = array_keys ( $userlinks ) ;
		foreach ( $u as $x ) {
			$z = $x ;
			if ( $userlinks[$x] > 1 ) $z .= " ({$userlinks[$x]}&times;)" ;
			array_push ( $users , $z ) ;
			}
		$users = " <font size='-1'>[".implode("; ",$users)."]</font>" ;

		$e = $y ;
		$e = array_shift ( $e ) ;

		# Arrow
		$rci = "RCI{$this->rccc}" ;
		$rcl = "RCL{$this->rccc}" ;
		$rcm = "RCM{$this->rccc}" ;
		$tl = "<a href='javascript:toggleVisibility(\"{$rci}\",\"{$rcm}\",\"{$rcl}\")'>" ;
		$tl .= "<span id='{$rcm}'><img src='{$wgUploadPath}/Arr_r.png' width=12 height=12 border=0></span>" ;
		$tl .= "<span id='{$rcl}' style='display:none'><img src='{$wgUploadPath}/Arr_d.png' width=12 height=12 border=0></span>" ;
		$tl .= "</a>" ;
		$r .= $tl ;

		# Main line
		$r .= "<tt>" ;
		if ( $isnew ) $r .= $N ;
		else $r .= "&nbsp;" ;
		$r .= "&nbsp;" ; # Minor
		$r .= " ".$e->timestamp." " ;
		$r .= "</tt>" ;

		$link = $e->link ;
		if ( $e->watched ) $link = "<strong>{$link}</strong>" ;
		$r .= $link ;

		if ( !$e->islog ) {
			$r .= " (".count($y)." " ;
			if ( $isnew ) $r .= wfMsg("changes");
			else $r .= $this->makeKnownLink( $e->secureName , wfMsg("changes") , "diff=0&oldid=".$oldid ) ;
			$r .= "; " ;
			$r .= $this->makeKnownLink( $e->secureName, wfMsg( "history" ), "action=history" );
			$r .= ")" ;
			}

		$r .= $users ;
		$r .= "<br>\n" ;

		# Sub-entries
		$r .= "<div id='{$rci}' style='display:none'>" ;
		foreach ( $y AS $x )
			{
			$r .= "<img src='{$wgUploadPath}/Arr_.png' width=12 height=12 border=0>";
			$r .= "<tt>&nbsp; &nbsp; &nbsp; &nbsp;" ;
			if ( $x->isnew ) $r .= $N ;
			else $r .= "&nbsp;" ;
			if ( $x->isminor ) $r .= $M ;
			else $r .= "&nbsp;" ;
			$r .= "</tt>" ;

			$o = "" ;
			if ( $x->oldid != 0 ) $o = "oldid=".$x->oldid ;
			if ( $x->islog ) $link = $x->timestamp ;
			else $link = $this->makeKnownLink( $x->secureName, $x->timestamp , $o ) ;
			$link = "<tt>{$link}</tt>" ;


			$r .= $link ;
			$r .= " (" ;
			$r .= $x->curlink ;
			$r .= "; " ;
			$r .= $x->lastlink ;
			$r .= ") . . ".$x->userlink ;
			$r .= $x->usertalklink ;
			if ( $x->usercomment != "" )
				$r .= " <em>(".wfEscapeHTML($x->usercomment).")</em>" ;
			$r .= "<br>\n" ;
			}
		$r .= "</div>\n" ;

		$this->rccc++ ;
		return $r ;
		}

	function recentChangesBlock ()
	{
		global $wgUploadPath ;
		if ( count ( $this->rc_cache ) == 0 ) return "" ;
		$k = array_keys ( $this->rc_cache ) ;
		foreach ( $k AS $x )
			{
			$y = $this->rc_cache[$x] ;
			if ( count ( $y ) < 2 ) {
				$r .= $this->recentChangesBlockLine ( array_shift ( $y ) ) ;
			} else {
				$r .= $this->recentChangesBlockGroup ( $y ) ;
				}
			}

		return "<div align=left>{$r}</div>" ;
	}

	function recentChangesLine( $ts, $u, $ut, $ns, $ttl, $c, $isminor, $isnew, $watched = false, $oldid = 0 , $diffid = 0 )
	{
		global $wgUser ;
		$usenew = $wgUser->getOption( "usenewrc" );
		if ( $usenew )
			$r = $this->recentChangesLineNew ( $ts, $u, $ut, $ns, $ttl, $c, $isminor, $isnew, $watched , $oldid , $diffid ) ;
		else
			$r = $this->recentChangesLineOld ( $ts, $u, $ut, $ns, $ttl, $c, $isminor, $isnew, $watched , $oldid , $diffid ) ;
		return $r ;
	}

	function recentChangesLineOld( $ts, $u, $ut, $ns, $ttl, $c, $isminor, $isnew, $watched = false, $oldid = 0, $diffid = 0 )
	{
		global $wgTitle, $wgLang, $wgUser;

		$d = $wgLang->date( $ts, true);
		$s = "";
		if ( $d != $this->lastdate ) {
			if ( "" != $this->lastdate ) { $s .= "</ul>\n"; }
			$s .= "<h4>{$d}</h4>\n<ul>";
			$this->lastdate = $d;
		}
		$h = $wgLang->time( $ts, true );
		$t = Title::makeName( $ns, $ttl );
		$clink = $this->makeKnownLink( $t , "" );
		$nt = Title::newFromText( $t );

		if ( $watched ) {
			$clink = "<strong>{$clink}</strong>";
		}
		$hlink = $this->makeKnownLink( $t, wfMsg( "hist" ), "action=history" );
		if ( $isnew || $nt->isLog() ) {
			$dlink = wfMsg( "diff" );
		} else {
			$dlink = $this->makeKnownLink( $t, wfMsg( "diff" ),
			  "diff={$oldid}&oldid={$diffid}" ); # Finagle's law
		}
		if ( 0 == $u ) {
        	$ul = $this->makeKnownLink( $wgLang->specialPage( "Contributions" ),
			$ut, "target=" . $ut );					
		} else { $ul = $this->makeLink( $wgLang->getNsText(
		  Namespace::getUser() ) . ":{$ut}", $ut ); }
		  
		$utns=$wgLang->getNsText(Namespace::getTalk(Namespace::getUser()));
		$talkname=$wgLang->getNsText(Namespace::getTalk(0)); # use the shorter name
		$utl= $this->makeLink($utns . ":{$ut}", $talkname );
		$cr = wfMsg( "currentrev" );

		$s .= "<li> ({$dlink}) ({$hlink}) . .";
		$M = wfMsg( "minoreditletter" );
		$N = wfMsg( "newpageletter" );
		if ( $isminor ) { $s .= " <strong>{$M}</strong>"; }
		if ( $isnew ) { $s .= "<strong>{$N}</strong>"; }
		$s .= " {$clink}; {$h} . . {$ul}";

		$blink="";
		if ( ( 0 == $u ) && $wgUser->isSysop() ) {
			$blink = $this->makeKnownLink( $wgLang->specialPage(
			  "Blockip" ), wfMsg( "blocklink" ), "ip={$ut}" );
			
		}
		if(!$blink) { 
			$utl = "({$utl})";
		} else {
			$utl = "({$utl} | {$blink})";
		}
		$s.=" {$utl}";

		if ( "" != $c && "*" != $c ) {
			$s .= " <em>(" . wfEscapeHTML( $c ) . ")</em>";
		}
		$s .= "</li>\n";

		return $s;
	}

	function recentChangesLineNew( $ts, $u, $ut, $ns, $ttl, $c, $isminor, $isnew, $watched = false, $oldid = 0 , $diffid = 0 )
	{
		global $wgTitle, $wgLang, $wgUser;

		$rc = new RecentChangesClass ;

		$d = $wgLang->date( $ts, true);
		$s = "";
		$ret = "" ;
		if ( $d != $this->lastdate ) {
			$ret = $this->recentChangesBlock () ;
			$this->rc_cache = array() ;
			$ret .= "<h4>{$d}</h4>\n";
			$this->lastdate = $d;
		}
		$h = $wgLang->time( $ts, true );
		$t = Title::makeName( $ns, $ttl );
		$clink = $this->makeKnownLink( $t, "" ) ;
		if ( $oldid == 0 ) $c2link = $clink ;
		else $c2link = $this->makeKnownLink( $t, "" , "oldid={$oldid}" );
		$nt = Title::newFromText( $t );

		$rc->timestamp = $h ;
		$rc->oldid = $oldid ;
		$rc->diffid = $diffid ;
		$rc->watched = $watched ;
		$rc->isnew = $isnew ;
		$rc->isminor = $isminor ;
		$rc->secureName = $t ;
		$rc->displayName = $nt ;
		$rc->link = $clink ;
		$rc->usercomment = $c ;
		$rc->islog = $nt->isLog() ;

		if ( ( $isnew && $oldid == 0 ) || $nt->isLog() ) {
			$dlink = wfMsg( "cur" );
		} else {
			$dlink = $this->makeKnownLink( $t, wfMsg( "cur" ),
			  "diff=0&oldid={$oldid}" );
		}

		if ( $diffid == 0 || $nt->isLog() ) {
			$plink = wfMsg( "last" );
		} else {
			$plink = $this->makeKnownLink( $t, wfMsg( "last" ),
			  "diff={$oldid}&oldid={$diffid}" );
		}

		if ( 0 == $u ) {
        	$ul = $this->makeKnownLink( $wgLang->specialPage( "Contributions" ),
			$ut, "target=" . $ut );
		} else { $ul = $this->makeLink( $wgLang->getNsText(
		  Namespace::getUser() ) . ":{$ut}", $ut ); }

		$rc->userlink = $ul ;
		$rc->lastlink = $plink ;
		$rc->curlink = $dlink ;

		$utns=$wgLang->getNsText(Namespace::getTalk(Namespace::getUser()));
		$talkname=$wgLang->getNsText(Namespace::getTalk(0)); # use the shorter name
		$utl= $this->makeLink($utns . ":{$ut}", $talkname );						

		if ( ( 0 == $u ) && $wgUser->isSysop() ) {
			$blink = $this->makeKnownLink( $wgLang->specialPage(
			  "Blockip" ), wfMsg( "blocklink" ), "ip={$ut}" );
			$rc->usertalklink= " ({$utl} | {$blink})";
		} else {
			$rc->usertalklink=" ({$utl})";
		}

		if ( !isset ( $this->rc_cache[$t] ) ) $this->rc_cache[$t] = array() ;
		array_push ( $this->rc_cache[$t] , $rc ) ;
		return $ret;
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
				$link = wfLocalUrlE( "", "image=" . $wgTitle->getURL() .
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

		$nb = str_replace( "$1", $size, wfMsg( "nbytes" ) );
		$style = $this->getInternalLinkAttributes( $url, $dt );

		$s = "<li> ({$dlink}) ({$rlink}) <a href=\"{$url}\"{$style}>{$dt}</a>"
		  . " . . {$ul} ({$nb})";

		if ( "" != $c && "*" != $c ) {
			$s .= " <em>(" . wfEscapeHTML( $c ) . ")</em>";
		}
		$s .= "</li>\n";
		return $s;
	}
}

include_once( "SkinStandard.php" );
include_once( "SkinNostalgia.php" );
include_once( "SkinCologneBlue.php" );

?>
