<?php

# Splitting edit page/HTML interface from Article...
# The actual database and text munging is still in Article,
# but it should get easier to call those from alternate
# interfaces.

class EditPage {
	var $mArticle;
	var $mTitle;
	
	# Form values
	var $save = false, $preview = false;
	var $minoredit = false, $watchthis = false;
	var $textbox1 = "", $textbox2 = "", $summary = "";
	var $edittime = "", $section = "";
	var $oldid = 0;
	
	function EditPage( $article ) {
		$this->mArticle =& $article;
		global $wgTitle;
		$this->mTitle =& $wgTitle;
	}

	# This is the function that gets called for "action=edit".

	function edit()
	{
		global $wgOut, $wgUser, $wgWhitelistEdit, $wgRequest;
		// this is not an article
		$wgOut->setArticleFlag(false);

		$this->importFormData( $wgRequest );

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
			if( $this->save || $this->preview ) {
				$this->editForm( "preview" );
			} else {
				$wgOut->readOnlyPage( $this->mArticle->getContent() );
			}
			return;
		}
		if ( $this->save ) {
			$this->editForm( "save" );
		} else if ( $this->preview ) {
			$this->editForm( "preview" );
		} else { # First time through
			$this->editForm( "initial" );
		}
	}

	function importFormData( &$request ) {
		# These fields need to be checked for encoding.
		# Also remove trailing whitespace, but don't remove _initial_
		# whitespace from the text boxes. This may be significant formatting.
		$this->textbox1 = rtrim( $request->getText( "wpTextbox1" ) );
		$this->textbox2 = rtrim( $request->getText( "wpTextbox2" ) );
		$this->summary = trim( $request->getText( "wpSummary" ) );

		$this->edittime = $request->getVal( 'wpEdittime' );
		if( !preg_match( '/^\d{14}$/', $this->edittime ) ) $this->edittime = "";

		$this->preview = $request->getCheck( 'wpPreview' );
		$this->save = $request->wasPosted() && !$this->preview;
		$this->minoredit = $request->getCheck( 'wpMinoredit' );
		$this->watchthis = $request->getCheck( 'wpWatchthis' );

		$this->oldid = $request->getInt( 'oldid' );

		# Section edit can come from either the form or a link
		$this->section = $request->getVal( 'wpSection', $request->getVal( 'section' ) );
	}

	# Since there is only one text field on the edit form,
	# pressing <enter> will cause the form to be submitted, but
	# the submit button value won't appear in the query, so we
	# Fake it here before going back to edit().  This is kind of
	# ugly, but it helps some old URLs to still work.

	function submit()
	{
		if( !$this->preview ) $this->save = true;

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
		global $wgLang, $wgParser, $wgTitle;
	    global $wgAllowAnonymousMinor;
	    global $wgWhitelistEdit;

		$sk = $wgUser->getSkin();
		$isConflict = false;

		if(!$this->mTitle->getArticleID()) { # new article
			$wgOut->addWikiText(wfmsg("newarticletext"));
		}

		if( Namespace::isTalk( $this->mTitle->getNamespace() ) ) {
			$wgOut->addWikiText(wfmsg("talkpagetext"));
		}

		# Attempt submission here.  This will check for edit conflicts,
		# and redundantly check for locked database, blocked IPs, etc.
		# that edit() already checked just in case someone tries to sneak
		# in the back door with a hand-edited submission URL.

		if ( "save" == $formtype ) {
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

			# If article is new, insert it.
			$aid = $this->mTitle->getArticleID();
			if ( 0 == $aid ) {
				# Don't save a new article if it's blank.
				if ( ( "" == $this->textbox1 ) ||
				  ( wfMsg( "newarticletext" ) == $this->textbox1 ) ) {
					$wgOut->redirect( $this->mTitle->getFullURL() );
					return;
				}
				$this->mArticle->insertNewArticle( $this->textbox1, $this->summary, $this->minoredit, $this->watchthis );
				return;
			}

			# Article exists. Check for edit conflict.

			$this->mArticle->clear(); # Force reload of dates, etc.

			if( ( $this->section != "new" ) &&
				($this->mArticle->getTimestamp() != $this->edittime ) ) {
				$isConflict = true;
			}
			$userid = $wgUser->getID();

			$text = $this->mArticle->getTextOfLastEditWithSectionReplacedOrAdded(
				$this->section, $this->textbox1, $this->summary);
			# Suppress edit conflict with self

			if ( ( 0 != $userid ) && ( $this->mArticle->getUser() == $userid ) ) {
				$isConflict = false;
			} else {
				# switch from section editing to normal editing in edit conflict
				if($isConflict) {
                    # Attempt merge
					if( $this->mergeChangesInto( $text ) ){
						// Successful merge! Maybe we should tell the user the good news?
						$isConflict = false;
					} else {
						$this->section = "";
						$this->textbox1 = $text;
					}
				}
			}
			if ( ! $isConflict ) {
				# All's well: update the article here
				if($this->mArticle->updateArticle( $text, $this->summary, $this->minoredit, $this->watchthis ))
					return;
				else
					$isConflict = true;
			}
		}
		# First time through: get contents, set time for conflict
		# checking, etc.

		if ( "initial" == $formtype ) {
			$this->edittime = $this->mArticle->getTimestamp();
			$this->textbox1 = $this->mArticle->getContent(true);
			$this->summary = "";
			$this->proxyCheck();
		}
		$wgOut->setRobotpolicy( "noindex,nofollow" );

		# Enabled article-related sidebar, toplinks, etc.
		$wgOut->setArticleRelated( true );

		if ( $isConflict ) {
			$s = wfMsg( "editconflict", $this->mTitle->getPrefixedText() );
			$wgOut->setPageTitle( $s );
			$wgOut->addHTML( wfMsg( "explainconflict" ) );

			$this->textbox2 = $this->textbox1;
			$this->textbox1 = $this->mArticle->getContent(true);
			$this->edittime = $this->mArticle->getTimestamp();
		} else {
			$s = wfMsg( "editing", $this->mTitle->getPrefixedText() );

			if( $this->section != "" ) {
				if( $this->section == "new" ) {
					$s.=wfMsg("commentedit");
				} else {
					$s.=wfMsg("sectionedit");
				}
				if(!$this->preview) {
					$sectitle=preg_match("/^=+(.*?)=+/mi",
				  	$this->textbox1,
				  	$matches);
					if( !empty( $matches[1] ) ) {
						$this->summary = "/* ". trim($matches[1])." */ ";
					}
				}
			}
			$wgOut->setPageTitle( $s );
			if ( $this->oldid ) {
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

		$kblength = (int)(strlen( $this->textbox1 ) / 1024);
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
		#if ( "no" == $redirect ) { $q .= "&redirect=no"; }
		$action = $this->mTitle->escapeLocalURL( $q );

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

		if( $wgUser->getOption("showtoolbar") ) {
			# prepare toolbar for edit buttons
			$toolbar = $sk->getEditToolbar();
		} else {
			$toolbar = "";
		}

		// activate checkboxes if user wants them to be always active
		if( !$this->preview ) {
			if( $wgUser->getOption( "watchdefault" ) ) $this->watchthis = true;
			if( $wgUser->getOption( "minordefault" ) ) $this->minoredit = true;

			// activate checkbox also if user is already watching the page,
			// require wpWatchthis to be unset so that second condition is not
			// checked unnecessarily
			if( !$this->watchthis && $this->mTitle->userIsWatching() ) $this->watchthis = true;
		}

		$minoredithtml = "";

		if ( 0 != $wgUser->getID() || $wgAllowAnonymousMinor ) {
			$minoredithtml =
			"<input tabindex='3' type='checkbox' value='1' name='wpMinoredit'".($this->minoredit?" checked='checked'":"").
			" accesskey='".wfMsg('accesskey-minoredit')."' id='wpMinoredit' />".
			"<label for='wpMinoredit' title='".wfMsg('tooltip-minoredit')."'>{$minor}</label>";
		}

		$watchhtml = "";

		if ( 0 != $wgUser->getID() ) {
			$watchhtml = "<input tabindex='4' type='checkbox' name='wpWatchthis'".($this->watchthis?" checked='checked'":"").
			" accesskey='".wfMsg('accesskey-watch')."' id='wpWatchthis'  />".
			"<label for='wpWatchthis' title='".wfMsg('tooltip-watch')."'>{$watchthis}</label>";
		}

		$checkboxhtml = $minoredithtml . $watchhtml . "<br />";

		if ( "preview" == $formtype) {
			$previewhead="<h2>" . wfMsg( "preview" ) . "</h2>\n<p><large><center><font color=\"#cc0000\">" .
			wfMsg( "note" ) . wfMsg( "previewnote" ) . "</font></center></large></p>\n";
			if ( $isConflict ) {
				$previewhead.="<h2>" . wfMsg( "previewconflict" ) .
				  "</h2>\n";
			}
			$previewtext = wfUnescapeHTML( $this->textbox1 );

			$parserOptions = ParserOptions::newFromUser( $wgUser );
			$parserOptions->setUseCategoryMagic( false );
			$parserOptions->setEditSection( false );
			$parserOptions->setEditSectionOnRightClick( false );
			$parserOutput = $wgParser->parse( $this->mArticle->preSaveTransform( $previewtext ) ."\n\n",
				$wgTitle, $parserOptions );
			$previewHTML = $parserOutput->mText;

			if($wgUser->getOption("previewontop")) {
				$wgOut->addHTML($previewhead);
				$wgOut->addHTML($previewHTML);
			}
			$wgOut->addHTML( "<br clear=\"all\" />\n" );
		}

		# if this is a comment, show a subject line at the top, which is also the edit summary.
		# Otherwise, show a summary field at the bottom
		$summarytext = htmlspecialchars( $wgLang->recodeForEdit( $this->summary ) ); # FIXME
		if( $this->section == "new" ) {
			$commentsubject="{$subject}: <input tabindex='1' type='text' value=\"$summarytext\" name=\"wpSummary\" maxlength='200' size='60' /><br />";
			$editsummary = "";
		} else {
			$commentsubject = "";
			$editsummary="{$summary}: <input tabindex='3' type='text' value=\"$summarytext\" name=\"wpSummary\" maxlength='200' size='60' /><br />";
		}

		if( !$this->preview ) {
			# Don't select the edit box on preview; this interferes with seeing what's going on.
			$wgOut->setOnloadHandler( "document.editform.wpTextbox1.focus()" );
		}
		$wgOut->addHTML( "
{$toolbar}
<form id=\"editform\" name=\"editform\" method=\"post\" action=\"$action\"
enctype=\"application/x-www-form-urlencoded\">
{$commentsubject}
<div class=\"tawrapper\"><textarea tabindex='1' accesskey=\",\" name=\"wpTextbox1\" rows='{$rows}'
cols='{$cols}'{$ew}>" .
htmlspecialchars( $wgLang->recodeForEdit( $this->textbox1 ) ) .
"
</textarea></div>
<br />{$editsummary}
{$checkboxhtml}
<input tabindex='5' type='submit' value=\"{$save}\" name=\"wpSave\" accesskey=\"".wfMsg('accesskey-save')."\"".
" title=\"".wfMsg('tooltip-save')."\"/>
<input tabindex='6' type='submit' value=\"{$prev}\" name=\"wpPreview\" accesskey=\"".wfMsg('accesskey-preview')."\"".
" title=\"".wfMsg('tooltip-preview')."\"/>
<em>{$cancel}</em> | <em>{$edithelp}</em>
<br /><br />{$copywarn}
<input type='hidden' value=\"" . htmlspecialchars( $this->section ) . "\" name=\"wpSection\" />
<input type='hidden' value=\"{$this->edittime}\" name=\"wpEdittime\" />\n" );

		if ( $isConflict ) {
			$wgOut->addHTML( "<h2>" . wfMsg( "yourdiff" ) . "</h2>\n" );
			DifferenceEngine::showDiff( $this->textbox2, $this->textbox1,
			  wfMsg( "yourtext" ), wfMsg( "storedversion" ) );

			$wgOut->addHTML( "<h2>" . wfMsg( "yourtext" ) . "</h2>
<div class=\"tawrapper\"><textarea tabindex=6 name=\"wpTextbox2\" rows='{$rows}' cols='{$cols}' wrap='virtual'>"
. htmlspecialchars( $wgLang->recodeForEdit( $this->textbox2 ) ) .
"
</textarea></div>" );
		}
		$wgOut->addHTML( "</form>\n" );
		if($formtype =="preview" && !$wgUser->getOption("previewontop")) {
			$wgOut->addHTML($previewhead);
			$wgOut->addHTML($previewHTML);
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

		$wgOut->addWikiText( wfMsg( "blockedtext", $link, $reason, $ip, $name ) );
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
			$iphash = md5( $wgIP . $wgProxyKey );
			$url = $title->getFullURL( "ip=$iphash" );

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

	/* private */ function mergeChangesInto( &$text ){
		$oldDate = $this->edittime;
		$res = wfQuery("SELECT cur_text FROM cur WHERE cur_id=" .
			$this->mTitle->getArticleID() . " FOR UPDATE", DB_WRITE);
		$obj = wfFetchObject($res);

		$yourtext = $obj->cur_text;
		$ns = $this->mTitle->getNamespace();
		$title = wfStrencode( $this->mTitle->getDBkey() );
		$res = wfQuery("SELECT old_text FROM old WHERE old_namespace = $ns AND ".
		  "old_title = '{$title}' AND old_timestamp = '{$oldDate}'", DB_WRITE);
		$obj = wfFetchObject($res);
		if(wfMerge($obj->old_text, $text, $yourtext, $result)){
			$text = $result;
			return true;
		} else {
			return false;
		}
	}
}

?>
