<?php

# Splitting edit page/HTML interface from Article...
# The actual database and text munging is still in Article,
# but it should get easier to call those from alternate
# interfaces.

class EditPage {
	var $mArticle;
	var $mTitle;
	
	function EditPage( $article ) {
		$this->mArticle =& $article;
		global $wgTitle;
		$this->mTitle =& $wgTitle;
	}

	# This is the function that gets called for "action=edit".

	function edit()
	{
		global $wgOut, $wgUser, $wgWhitelistEdit;
		global $wpTextbox1, $wpSummary, $wpSave, $wpPreview;
		global $wpMinoredit, $wpEdittime, $wpTextbox2;
		// this is not an article
		$wgOut->setArticleFlag(false);

		$fields = array( "wpTextbox1", "wpSummary", "wpTextbox2" );
		wfCleanFormFields( $fields );

		if ( ! $this->mTitle->userCanEdit() ) {
			$wgOut->readOnlyPage( $this->mArticle->getContent(), true );
			return;
		}
		if ( $wgUser->isBlocked() ) {
			$this->blockedIPpage();
			return;
		}
		if ( !$wgUser->getID() && $wgWhitelistEdit ) {
			$this->userNotLoggedInPage();
			return;
		}
		if ( wfReadOnly() ) {
			if( isset( $wpSave ) or isset( $wpPreview ) ) {
				$this->editForm( "preview" );
			} else {
				$wgOut->readOnlyPage( $this->mArticle->getContent() );
			}
			return;
		}
		if ( $_SERVER['REQUEST_METHOD'] != "POST" ) unset( $wpSave );
		if ( isset( $wpSave ) ) {
			$this->editForm( "save" );
		} else if ( isset( $wpPreview ) ) {
			$this->editForm( "preview" );
		} else { # First time through
			$this->editForm( "initial" );
		}
	}

	# Since there is only one text field on the edit form,
	# pressing <enter> will cause the form to be submitted, but
	# the submit button value won't appear in the query, so we
	# Fake it here before going back to edit().  This is kind of
	# ugly, but it helps some old URLs to still work.

	function submit()
	{
		global $wpSave, $wpPreview;
		if ( ! isset( $wpPreview ) ) { $wpSave = 1; }

		$this->edit();
	}

	# The edit form is self-submitting, so that when things like
	# preview and edit conflicts occur, we get the same form back
	# with the extra stuff added.  Only when the final submission
	# is made and all is well do we actually save and redirect to
	# the newly-edited page.

	function editForm( $formtype )
	{
		global $wgOut, $wgUser;
		global $wpTextbox1, $wpSummary, $wpWatchthis;
		global $wpSave, $wpPreview;
		global $wpMinoredit, $wpEdittime, $wpTextbox2, $wpSection;
		global $oldid, $redirect, $section;
		global $wgLang, $wgSpamRegex;
		global $wgAllowAnonymousMinor;

		if(isset($wpSection)) { $section=$wpSection; } else { $wpSection=$section; }

		$sk = $wgUser->getSkin();
		$isConflict = false;
		$wpTextbox1 = rtrim ( $wpTextbox1 ) ; # To avoid text getting longer on each preview

		if(!$this->mTitle->getArticleID()) { # new article

			$wgOut->addWikiText(wfmsg("newarticletext"));

		}

		# Attempt submission here.  This will check for edit conflicts,
		# and redundantly check for locked database, blocked IPs, etc.
		# that edit() already checked just in case someone tries to sneak
		# in the back door with a hand-edited submission URL.

		if ( "save" == $formtype ) {
			# Check for spam
			if ( $wgSpamRegex && preg_match( $wgSpamRegex, $wpTextbox1 ) ) {
					sleep(10); # Saving the page, be with you in a sec
					$wgOut->redirect(  wfLocalUrl( $this->mTitle->getPrefixedURL() ) );
					# Bwahahaha
					return;
			}

			if ( $wgUser->isBlocked() ) {
				$this->blockedIPpage();
				return;
			}
			if ( !$wgUser->getID() && $wgWhitelistEdit ) {
				$this->userNotLoggedInPage();
				return;
			}
			if ( wfReadOnly() ) {
				$wgOut->readOnlyPage();
				return;
			}
			$wpSummary=trim($wpSummary);
			# If article is new, insert it.

			$aid = $this->mTitle->getArticleID();
			if ( 0 == $aid ) {
				# we need to strip Windoze linebreaks because some browsers
				# append them and the string comparison fails
				if ( ( "" == $wpTextbox1 ) ||
				  ( wfMsg( "newarticletext" ) == rtrim( preg_replace("/\r/","",$wpTextbox1) ) ) ) {
					$wgOut->redirect(  wfLocalUrl(
					  $this->mTitle->getPrefixedURL() ) );
					return;
				}
				$this->mArticle->insertNewArticle( $wpTextbox1, $wpSummary, $wpMinoredit, $wpWatchthis );
				return;
			}
			# Article exists. Check for edit conflict.
			# Don't check for conflict when appending a comment - this should always work

			$this->mArticle->clear(); # Force reload of dates, etc.
			if ( $section!="new" && ( $this->mArticle->getTimestamp() != $wpEdittime ) ) {
				$isConflict = true;
			}
			$u = $wgUser->getID();

			# Suppress edit conflict with self

			if ( ( 0 != $u ) && ( $this->mArticle->getUser() == $u ) ) {
				$isConflict = false;
			} else {
				# switch from section editing to normal editing in edit conflict
				if($isConflict) {
					$section="";$wpSection="";
				}

			}
			if ( ! $isConflict ) {
				# All's well: update the article here
				if($this->mArticle->updateArticle( $wpTextbox1, $wpSummary, $wpMinoredit, $wpWatchthis, $wpSection ))
					return;
				else
					$isConflict = true;
			}
		}
		# First time through: get contents, set time for conflict
		# checking, etc.

		if ( "initial" == $formtype ) {
			$wpEdittime = $this->mArticle->getTimestamp();
			$wpTextbox1 = $this->mArticle->getContent(true);
			$wpSummary = "";
			$this->proxyCheck();
		}
		$wgOut->setRobotpolicy( "noindex,nofollow" );
		
		# Enabled article-related sidebar, toplinks, etc.
		$wgOut->setArticleRelated( true );

		if ( $isConflict ) {
			$s = wfMsg( "editconflict", $this->mTitle->getPrefixedText() );
			$wgOut->setPageTitle( $s );
			$wgOut->addHTML( wfMsg( "explainconflict" ) );

			$wpTextbox2 = $wpTextbox1;
			$wpTextbox1 = $this->mArticle->getContent(true);
			$wpEdittime = $this->mArticle->getTimestamp();
		} else {
			$s = wfMsg( "editing", $this->mTitle->getPrefixedText() );

			if($section!="") {
				if($section=="new") {
					$s.=wfMsg("commentedit");
				} else {
					$s.=wfMsg("sectionedit");
				}
				if(!$wpPreview) {

					$sectitle=preg_match("/^=+(.*?)=+/mi",
				  	$wpTextbox1,
				  	$matches);
					if($matches[1]) { $wpSummary =
                                         "=". trim($matches[1])."= "; }
				}				
			}
			$wgOut->setPageTitle( $s );
			if ( $oldid ) {
				$this->mArticle->setOldSubtitle();
				$wgOut->addHTML( wfMsg( "editingold" ) );
			}
		}

		if( wfReadOnly() ) {
			$wgOut->addHTML( "<strong>" .
				wfMsg( "readonlywarning" ) .
				"</strong>" );
		}
		if( $this->mTitle->isProtected() ) {
			$wgOut->addHTML( "<strong>" . wfMsg( "protectedpagewarning" ) .
			  "</strong><br />\n" );
		}

		$kblength = (int)(strlen( $wpTextbox1 ) / 1024);
		if( $kblength > 29 ) {
			$wgOut->addHTML( "<strong>" .
				wfMsg( "longpagewarning", $kblength )
				. "</strong>" );
		}

		$rows = $wgUser->getOption( "rows" );
		$cols = $wgUser->getOption( "cols" );

		$ew = $wgUser->getOption( "editwidth" );
		if ( $ew ) $ew = " style=\"width:100%\"";
		else $ew = "" ;

		$q = "action=submit";
		if ( "no" == $redirect ) { $q .= "&redirect=no"; }
		$action = wfEscapeHTML( wfLocalUrl( $this->mTitle->getPrefixedURL(), $q ) );

		$summary = wfMsg( "summary" );
		$subject = wfMsg("subject");
		$minor = wfMsg( "minoredit" );
		$watchthis = wfMsg ("watchthis");
		$save = wfMsg( "savearticle" );
		$prev = wfMsg( "showpreview" );

		$cancel = $sk->makeKnownLink( $this->mTitle->getPrefixedURL(),
		  wfMsg( "cancel" ) );
		$edithelp = $sk->makeKnownLink( wfMsg( "edithelppage" ),
		  wfMsg( "edithelp" ) );
		$copywarn = wfMsg( "copyrightwarning", $sk->makeKnownLink(
		  wfMsg( "copyrightpage" ) ) );

		$wpTextbox1 = wfEscapeHTML( $wpTextbox1 );
		$wpTextbox2 = wfEscapeHTML( $wpTextbox2 );
		$wpSummary = wfEscapeHTML( $wpSummary );


		if($wgUser->getOption("showtoolbar")) {
			// prepare toolbar for edit buttons
			$toolbar=$sk->getEditToolbar();
		}

		// activate checkboxes if user wants them to be always active
		if (!$wpPreview && $wgUser->getOption("watchdefault")) $wpWatchthis=1;
		if (!$wpPreview && $wgUser->getOption("minordefault")) $wpMinoredit=1;

		// activate checkbox also if user is already watching the page,
		// require wpWatchthis to be unset so that second condition is not
		// checked unnecessarily
		if (!$wpWatchthis && !$wpPreview && $this->mTitle->userIsWatching()) $wpWatchthis=1;

                $minoredithtml = "";

		if ( 0 != $wgUser->getID() || $wgAllowAnonymousMinor ) {
			$minoredithtml =
			"<input tabindex=3 type=checkbox value=1 name='wpMinoredit'".($wpMinoredit?" checked":"")." id='wpMinoredit'>".
			"<label for='wpMinoredit'>{$minor}</label>";
		}
	    
	        $watchhtml = "";
	    
		if ( 0 != $wgUser->getID() ) {
		        $watchhtml = "<input tabindex=4 type=checkbox name='wpWatchthis'".($wpWatchthis?" checked":"")." id='wpWatchthis'>".
			"<label for='wpWatchthis'>{$watchthis}</label>";

		}
	    
	        $checkboxhtml= $minoredithtml . $watchhtml . "<br>";

		if ( "preview" == $formtype) {

			$previewhead="<h2>" . wfMsg( "preview" ) . "</h2>\n<p><large><center><font color=\"#cc0000\">" .
			wfMsg( "note" ) . wfMsg( "previewnote" ) . "</font></center></large><P>\n";
			if ( $isConflict ) {
				$previewhead.="<h2>" . wfMsg( "previewconflict" ) .
				  "</h2>\n";
			}
			$previewtext = wfUnescapeHTML( $wpTextbox1 );

			if($wgUser->getOption("previewontop")) {
				$wgOut->addHTML($previewhead);
				$wgOut->addWikiText( $this->mArticle->preSaveTransform( $previewtext ) ."\n\n");
			}
			$wgOut->addHTML( "<br clear=\"all\" />\n" );
		}

		# if this is a comment, show a subject line at the top, which is also the edit summary.
		# Otherwise, show a summary field at the bottom
		if($section=="new") {

			$commentsubject="{$subject}: <input tabindex=1 type=text value=\"{$wpSummary}\" name=\"wpSummary\" maxlength=200 size=60><br>";
		} else {

			$editsummary="{$summary}: <input tabindex=3 type=text value=\"{$wpSummary}\" name=\"wpSummary\" maxlength=200 size=60><br>";
		}

		if( $_GET["action"] == "edit" ) {
			# Don't select the edit box on preview; this interferes with seeing what's going on.
			$wgOut->setOnloadHandler( "document.editform.wpTextbox1.focus()" );
		}
		$wgOut->addHTML( "
{$toolbar}
<form id=\"editform\" name=\"editform\" method=\"post\" action=\"$action\"
enctype=\"application/x-www-form-urlencoded\">
{$commentsubject}
<textarea tabindex=2 name=\"wpTextbox1\" rows={$rows}
cols={$cols}{$ew} wrap=\"virtual\">" .
$wgLang->recodeForEdit( $wpTextbox1 ) .
"
</textarea>
<br>{$editsummary}
{$checkboxhtml}
<input tabindex=5 type=submit value=\"{$save}\" name=\"wpSave\" accesskey=\"s\">
<input tabindex=6 type=submit value=\"{$prev}\" name=\"wpPreview\" accesskey=\"p\">
<em>{$cancel}</em> | <em>{$edithelp}</em>
<br><br>{$copywarn}
<input type=hidden value=\"{$section}\" name=\"wpSection\">
<input type=hidden value=\"{$wpEdittime}\" name=\"wpEdittime\">\n" );

		if ( $isConflict ) {
			$wgOut->addHTML( "<h2>" . wfMsg( "yourdiff" ) . "</h2>\n" );
			DifferenceEngine::showDiff( $wpTextbox2, $wpTextbox1,
			  wfMsg( "yourtext" ), wfMsg( "storedversion" ) );

			$wgOut->addHTML( "<h2>" . wfMsg( "yourtext" ) . "</h2>
<textarea tabindex=6 name=\"wpTextbox2\" rows={$rows} cols={$cols} wrap=virtual>"
. $wgLang->recodeForEdit( $wpTextbox2 ) .
"
</textarea>" );
		}
		$wgOut->addHTML( "</form>\n" );
		if($formtype =="preview" && !$wgUser->getOption("previewontop")) {
			$wgOut->addHTML($previewhead);
			$wgOut->addWikiText( $this->mArticle->preSaveTransform( $previewtext ) );
		}

	}

	function blockedIPpage()
	{
		global $wgOut, $wgUser, $wgLang, $wgIP;

		$wgOut->setPageTitle( wfMsg( "blockedtitle" ) );
		$wgOut->setRobotpolicy( "noindex,nofollow" );
		$wgOut->setArticleRelated( false );

		$id = $wgUser->blockedBy();
		$reason = $wgUser->blockedFor();
                $ip = $wgIP;
		
                $name = User::whoIs( $id );
		$link = "[[" . $wgLang->getNsText( Namespace::getUser() ) .
		  ":{$name}|{$name}]]";

		$wgOut->addWikiText( wfMsg( "blockedtext", $link, $reason, $ip ) );
		$wgOut->returnToMain( false );
	}



	function userNotLoggedInPage()
	{
		global $wgOut, $wgUser, $wgLang;

		$wgOut->setPageTitle( wfMsg( "whitelistedittitle" ) );
		$wgOut->setRobotpolicy( "noindex,nofollow" );
		$wgOut->setArticleRelated( false );

		$wgOut->addWikiText( wfMsg( "whitelistedittext" ) );
		$wgOut->returnToMain( false );
	}

	# Forks processes to scan the originating IP for an open proxy server
	# MemCached can be used to skip IPs that have already been scanned
	function proxyCheck()
	{
		global $wgBlockOpenProxies, $wgProxyPorts, $wgProxyScriptPath;
		global $wgIP, $wgUseMemCached, $wgMemc, $wgDBname, $wgProxyMemcExpiry;
		global $wgServer, $wgOut;

		if ( !$wgBlockOpenProxies ) {
			return;
		}
		
		# Get MemCached key
		$skip = false;
		if ( $wgUseMemCached ) {
			$mcKey = "$wgDBname:proxy:ip:$wgIP";
			$mcValue = $wgMemc->get( $mcKey );
			if ( $mcValue ) {
				$skip = true;
			}
		}

		# Fork the processes
		if ( !$skip ) {
			$title = Title::makeTitle( NS_SPECIAL, "Blockme" );
			$url = wfFullUrl( $title->getPrefixedURL(), "ip=$wgIP" );
			foreach ( $wgProxyPorts as $port ) {
				$params = implode( " ", array(
				  escapeshellarg( $wgProxyScriptPath ),
				  escapeshellarg( $wgIP ),
				  escapeshellarg( $port ),
				  escapeshellarg( $url )
			  ));
			exec( "php $params &>/dev/null &" );

			}
			# Set MemCached key
			if ( $wgUseMemCached ) {
				$wgMemc->set( $mcKey, 1, $wgProxyMemcExpiry );
			}
		}
	}
}

?>
