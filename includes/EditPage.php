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
			$wgOut->readOnlyPage( $this->mArticle->getContent( true ), true );
			return;
		}
		if ( $wgUser->isBlocked() ) {
			$wgOut->blockedIPpage();
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
				$wgOut->readOnlyPage( $this->mArticle->getContent( true ) );
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
		if( !preg_match( '/^\d{14}$/', $this->edittime )) $this->edittime = "";

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
		global $wgSpamRegex, $wgFilterCallback;

		$sk = $wgUser->getSkin();
		$isConflict = false;
		// css / js subpages of user pages get a special treatment
		$isCssJsSubpage = (Namespace::getUser() == $wgTitle->getNamespace() and preg_match("/\\.(css|js)$/", $wgTitle->getText() ));

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
			# Check for spam
			if ( $wgSpamRegex && preg_match( $wgSpamRegex, $this->textbox1, $matches ) ) {
				$this->spamPage( $matches[0] );
				return;
			}
			if ( $wgFilterCallback && $wgFilterCallback( $this->mTitle, $this->textbox1, $this->section ) ) {
				# Error messages or other handling should be performed by the filter function
				return;
			}
			if ( $wgUser->isBlocked() ) {
				$wgOut->blockedIPpage();
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
				# All's well
				$sectionanchor = '';
				if( $this->section == 'new' ) {
					if( $this->summary != '' ) {
						$sectionanchor = $this->sectionAnchor( $this->summary );
					}
				} elseif( $this->section != '' ) {
					# Try to get a section anchor from the section source, redirect to edited section if header found
					# XXX: might be better to integrate this into Article::getTextOfLastEditWithSectionReplacedOrAdded
					# for duplicate heading checking and maybe parsing
					$hasmatch = preg_match( "/^ *([=]{1,6})(.*?)(\\1) *\\n/i", $this->textbox1, $matches );
					# we can't deal with anchors, includes, html etc in the header for now, 
					# headline would need to be parsed to improve this
					#if($hasmatch and strlen($matches[2]) > 0 and !preg_match( "/[\\['{<>]/", $matches[2])) {
					if($hasmatch and strlen($matches[2]) > 0) {
						$sectionanchor = $this->sectionAnchor( $matches[2] );
					}
				}
	
				# update the article here
				if($this->mArticle->updateArticle( $text, $this->summary, $this->minoredit, $this->watchthis, '', $sectionanchor ))
					return;
				else
					$isConflict = true;
			}
		}
		# First time through: get contents, set time for conflict
		# checking, etc.

		if ( "initial" == $formtype ) {
			$this->edittime = $this->mArticle->getTimestamp();
			$this->textbox1 = $this->mArticle->getContent( true );
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
			$this->textbox1 = $this->mArticle->getContent( true );
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
		} else if ( $isCssJsSubpage and "preview" != $formtype) {
			$wgOut->addHTML( wfMsg( "usercssjsyoucanpreview" ));
		}
		if( $this->mTitle->isProtected() ) {
			$wgOut->addHTML( "<strong>" . wfMsg( "protectedpagewarning" ) .
			  "</strong><br />\n" );
		}

		$kblength = (int)(strlen( $this->textbox1 ) / 1024);
		if( $kblength > 29 ) {
			$wgOut->addHTML( "<strong>" .
				wfMsg( "longpagewarning", $wgLang->formatNum( $kblength ) )
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

		$cancel = $sk->makeKnownLink( $this->mTitle->getPrefixedText(),
		  wfMsg( "cancel" ) );
		$edithelpurl = $sk->makeUrl( wfMsg( 'edithelppage' ));
		$edithelp = '<a target="helpwindow" href="'.$edithelpurl.'">'.
			htmlspecialchars( wfMsg( 'edithelp' ) ).'</a> '.
			htmlspecialchars( wfMsg( 'newwindow' ) );
		$copywarn = wfMsg( "copyrightwarning", $sk->makeKnownLink(
		  wfMsg( "copyrightpage" ) ) );

		if( $wgUser->getOption("showtoolbar") and !$isCssJsSubpage ) {
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
			$previewhead="<h2>" . wfMsg( "preview" ) . "</h2>\n<p><center><font color=\"#cc0000\">" .
			wfMsg( "note" ) . wfMsg( "previewnote" ) . "</font></center></p>\n";
			if ( $isConflict ) {
				$previewhead.="<h2>" . wfMsg( "previewconflict" ) .
				  "</h2>\n";
			}

			$parserOptions = ParserOptions::newFromUser( $wgUser );
			$parserOptions->setUseCategoryMagic( false );
			$parserOptions->setEditSection( false );
			$parserOptions->setEditSectionOnRightClick( false );
			# don't parse user css/js, show message about preview
			# XXX: stupid php bug won't let us use $wgTitle->isCssJsSubpage() here
			if ( $isCssJsSubpage ) {
				if(preg_match("/\\.css$/", $wgTitle->getText() ) ) {
					$previewtext = wfMsg('usercsspreview');
				} else if(preg_match("/\\.js$/", $wgTitle->getText() ) ) {
					$previewtext = wfMsg('userjspreview');
				}
				$parserOutput = $wgParser->parse( $previewtext , $wgTitle, $parserOptions );
				$wgOut->addHTML( $parserOutput->mText );
			} else {
				$parserOutput = $wgParser->parse( $this->mArticle->preSaveTransform( $this->textbox1 ) ."\n\n",
				$wgTitle, $parserOptions );
				$previewHTML = $parserOutput->mText;

				if($wgUser->getOption("previewontop")) {
					$wgOut->addHTML($previewhead);
					$wgOut->addHTML($previewHTML);
				}
				$wgOut->addCategoryLinks($parserOutput->getCategoryLinks());
				$wgOut->addLanguageLinks($parserOutput->getLanguageLinks());
				$wgOut->addHTML( "<br style=\"clear:both;\" />\n" );
			}
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
enctype=\"multipart/form-data\">
{$commentsubject}
<textarea tabindex='1' accesskey=\",\" name=\"wpTextbox1\" rows='{$rows}'
cols='{$cols}'{$ew}>" .
htmlspecialchars( $wgLang->recodeForEdit( $this->textbox1 ) ) .
"
</textarea>
<br />{$editsummary}
{$checkboxhtml}
<input tabindex='5' id='wpSave' type='submit' value=\"{$save}\" name=\"wpSave\" accesskey=\"".wfMsg('accesskey-save')."\"".
" title=\"".wfMsg('tooltip-save')."\"/>
<input tabindex='6' id='wpPreview' type='submit' value=\"{$prev}\" name=\"wpPreview\" accesskey=\"".wfMsg('accesskey-preview')."\"".
" title=\"".wfMsg('tooltip-preview')."\"/>
<em>{$cancel}</em> | <em>{$edithelp}</em>
<br /><div id=\"editpage-copywarn\">{$copywarn}</div>
<input type='hidden' value=\"" . htmlspecialchars( $this->section ) . "\" name=\"wpSection\" />
<input type='hidden' value=\"{$this->edittime}\" name=\"wpEdittime\" />\n" );

		if ( $isConflict ) {
			$wgOut->addHTML( "<h2>" . wfMsg( "yourdiff" ) . "</h2>\n" );
			DifferenceEngine::showDiff( $this->textbox2, $this->textbox1,
			  wfMsg( "yourtext" ), wfMsg( "storedversion" ) );

			$wgOut->addHTML( "<h2>" . wfMsg( "yourtext" ) . "</h2>
<textarea tabindex=6 id='wpTextbox2' name=\"wpTextbox2\" rows='{$rows}' cols='{$cols}' wrap='virtual'>"
. htmlspecialchars( $wgLang->recodeForEdit( $this->textbox2 ) ) .
"
</textarea>" );
		}
		$wgOut->addHTML( "</form>\n" );
		if($formtype =="preview" && !$wgUser->getOption("previewontop")) {
			$wgOut->addHTML($previewhead);
			$wgOut->addHTML($previewHTML);
		}

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

	function spamPage( $match )
	{
		global $wgOut;
		$wgOut->setPageTitle( wfMsg( "spamprotectiontitle" ) );
		$wgOut->setRobotpolicy( "noindex,nofollow" );
		$wgOut->setArticleRelated( false );

		$wgOut->addWikiText( wfMsg( "spamprotectiontext" ) . "\n\n" . $match );
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
		global $wgIsPg;
		$oldDate = $this->edittime;
		$res = wfQuery("SELECT cur_text FROM cur WHERE cur_id=" .
			$this->mTitle->getArticleID() . " FOR UPDATE", DB_WRITE);
		$obj = wfFetchObject($res);

		$yourtext = $obj->cur_text;
		$ns = $this->mTitle->getNamespace();
		$title = wfStrencode( $this->mTitle->getDBkey() );
		$oldtable=$wgIsPg?'"old"':'old';
		$res = wfQuery("SELECT old_text,old_flags FROM $oldtable WHERE old_namespace = $ns AND ".
		  "old_title = '{$title}' AND old_timestamp = '{$oldDate}'", DB_WRITE);
		$obj = wfFetchObject($res);
		$oldText = Article::getRevisionText( $obj );
		
		if(wfMerge($oldText, $text, $yourtext, $result)){
			$text = $result;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Format an anchor fragment as it would appear for a given section name
	 * @param string $text
	 * @return string
	 * @access private
	 */
	function sectionAnchor( $text ) {
		global $wgInputEncoding;
		$headline = do_html_entity_decode( $text, ENT_COMPAT, $wgInputEncoding );
		# strip out HTML 
		$headline = preg_replace( '/<.*?' . '>/', '', $headline );
		$headline = trim( $headline );
		$sectionanchor = '#' . urlencode( str_replace( ' ', '_', $headline ) );
		$replacearray = array(
			'%3A' => ':',
			'%' => '.'
		);
		return str_replace(
			array_keys( $replacearray ),
			array_values( $replacearray ),
			$sectionanchor );
	}
}

?>
