<?php
/**
 * Contain the EditPage class
 * @package MediaWiki
 */

/**
 * Splitting edit page/HTML interface from Article...
 * The actual database and text munging is still in Article,
 * but it should get easier to call those from alternate
 * interfaces.
 *
 * @package MediaWiki
 */

class EditPage {
	var $mArticle;
	var $mTitle;
	var $mMetaData = '';
	
	# Form values
	var $save = false, $preview = false;
	var $minoredit = false, $watchthis = false;
	var $textbox1 = '', $textbox2 = '', $summary = '';
	var $edittime = '', $section = '';
	var $oldid = 0;
	
	/**
	 * @todo document
	 * @param $article
	 */
	function EditPage( $article ) {
		$this->mArticle =& $article;
		global $wgTitle;
		$this->mTitle =& $wgTitle;
	}

	/**
	 * This is the function that extracts metadata from the article body on the first view.
	 * To turn the feature on, set $wgUseMetadataEdit = true ; in LocalSettings
	 */
	function extractMetaDataFromArticle ()
	{
		global $wgUseMetadataEdit , $wgLang ;
		$this->mMetaData = "" ;
		if ( !$wgUseMetadataEdit ) return ;
		$s = "" ;
		$t = $this->mArticle->getContent ( true ) ;

		# MISSING : <nowiki> filtering

		# Categories and language links
		$t = explode ( "\n" , $t ) ;
		$catlow = strtolower ( $wgLang->getNsText ( NS_CATEGORY ) ) ;
		$cat = $ll = array() ;
		foreach ( $t AS $key => $x )
		{
			$y = trim ( strtolower ( $x ) ) ;
			while ( substr ( $y , 0 , 2 ) == "[[" )
			{
				$y = explode ( "]]" , trim ( $x ) ) ;
				$first = array_shift ( $y ) ;
				$first = explode ( ":" , $first ) ;
				$ns = array_shift ( $first ) ;
				$ns = trim ( str_replace ( "[" , "" , $ns ) ) ;
				if ( strlen ( $ns ) == 2 OR strtolower ( $ns ) == $catlow )
				{
					$add = "[[" . $ns . ":" . implode ( ":" , $first ) . "]]" ;
					if ( strtolower ( $ns ) == $catlow ) $cat[] = $add ;
					else $ll[] = $add ;
					$x = implode ( "]]" , $y ) ;
					$t[$key] = $x ;
					$y = trim ( strtolower ( $x ) ) ;
				}
			}
		}
		if ( count ( $cat ) ) $s .= implode ( " " , $cat ) . "\n" ;
		if ( count ( $ll ) ) $s .= implode ( " " , $ll ) . "\n" ;
		$t = implode ( "\n" , $t ) ;

		# Templates, but only some
		$sat = array ( "meta-template" ) ; # stand-alone-templates; must be lowercase
		$t = explode ( "{{" , $t ) ;
		$tl = array () ;
		foreach ( $t AS $key => $x )
		{
			$y = explode ( "}}" , $x , 2 ) ;
			if ( count ( $y ) == 2 )
			{
				$z = $y[0] ;
				$z = explode ( "|" , $z ) ;
				$tn = array_shift ( $z ) ;
				if ( in_array ( strtolower ( $tn ) , $sat ) )
				{
					$tl[] = "{{" . $y[0] . "}}" ;
					$t[$key] = $y[1] ;
					$y = explode ( "}}" , $y[1] , 2 ) ;
				}
				else $t[$key] = "{{" . $x ;
			}
			else if ( $key != 0 ) $t[$key] = "{{" . $x ;
			else $t[$key] = $x ;
		}
		if ( count ( $tl ) ) $s .= implode ( " " , $tl ) ;
		$t = implode ( "" , $t ) ;

		$t = str_replace ( "\n\n\n" , "\n" , $t ) ;
		$this->mArticle->mContent = $t ;
		$this->mMetaData = $s ;
	}

	/**
	 * This is the function that gets called for "action=edit".
	 */
	function edit() {
		global $wgOut, $wgUser, $wgWhitelistEdit, $wgRequest;
		// this is not an article
		$wgOut->setArticleFlag(false);

		$this->importFormData( $wgRequest );
		
		if( $this->live ) {
			$this->livePreview();
			return;
		}

		if ( ! $this->mTitle->userCanEdit() ) {
			$wgOut->readOnlyPage( $this->mArticle->getContent( true ), true );
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
				$this->editForm( 'preview' );
			} else {
				$wgOut->readOnlyPage( $this->mArticle->getContent( true ) );
			}
			return;
		}
		if ( $this->save ) {
			$this->editForm( 'save' );
		} else if ( $this->preview ) {
			$this->editForm( 'preview' );
		} else { # First time through
			if( $wgUser->getOption('previewonfirst') ) {
				$this->editForm( 'preview', true );
			} else {
				$this->extractMetaDataFromArticle () ;
				$this->editForm( 'initial', true );
			}
		}
	}

	/**
	 * @todo document
	 */
	function importFormData( &$request ) {
		# These fields need to be checked for encoding.
		# Also remove trailing whitespace, but don't remove _initial_
		# whitespace from the text boxes. This may be significant formatting.
		$this->textbox1 = rtrim( $request->getText( 'wpTextbox1' ) );
		$this->textbox2 = rtrim( $request->getText( 'wpTextbox2' ) );
		$this->mMetaData = rtrim( $request->getText( 'metadata' ) );
		$this->summary = trim( $request->getText( 'wpSummary' ) );

		$this->edittime = $request->getVal( 'wpEdittime' );
		if( !preg_match( '/^\d{14}$/', $this->edittime )) $this->edittime = '';

		$this->preview = $request->getCheck( 'wpPreview' );
		$this->save = $request->wasPosted() && !$this->preview;
		$this->minoredit = $request->getCheck( 'wpMinoredit' );
		$this->watchthis = $request->getCheck( 'wpWatchthis' );

		$this->oldid = $request->getInt( 'oldid' );

		# Section edit can come from either the form or a link
		$this->section = $request->getVal( 'wpSection', $request->getVal( 'section' ) );
		
		$this->live = $request->getCheck( 'live' );
	}

	/**
	 * Since there is only one text field on the edit form,
	 * pressing <enter> will cause the form to be submitted, but
	 * the submit button value won't appear in the query, so we
	 * Fake it here before going back to edit().  This is kind of
	 * ugly, but it helps some old URLs to still work.
	 */
	function submit() {
		if( !$this->preview ) $this->save = true;

		$this->edit();
	}

	/**
	 * The edit form is self-submitting, so that when things like
	 * preview and edit conflicts occur, we get the same form back
	 * with the extra stuff added.  Only when the final submission
	 * is made and all is well do we actually save and redirect to
	 * the newly-edited page.
	 *
	 * @param string $formtype Type of form either : save, initial or preview
	 * @param bool $firsttime True to load form data from db
	 */
	function editForm( $formtype, $firsttime = false ) {
		global $wgOut, $wgUser;
		global $wgLang, $wgContLang, $wgParser, $wgTitle;
		global $wgAllowAnonymousMinor;
		global $wgWhitelistEdit;
		global $wgSpamRegex, $wgFilterCallback;
		global $wgUseLatin1;

		$sk = $wgUser->getSkin();
		$isConflict = false;
		// css / js subpages of user pages get a special treatment
		$isCssJsSubpage = (Namespace::getUser() == $wgTitle->getNamespace() and preg_match("/\\.(css|js)$/", $wgTitle->getText() ));

		if(!$this->mTitle->getArticleID()) { # new article
			$wgOut->addWikiText(wfmsg('newarticletext'));
		}

		if( Namespace::isTalk( $this->mTitle->getNamespace() ) ) {
			$wgOut->addWikiText(wfmsg('talkpagetext'));
		}

		# Attempt submission here.  This will check for edit conflicts,
		# and redundantly check for locked database, blocked IPs, etc.
		# that edit() already checked just in case someone tries to sneak
		# in the back door with a hand-edited submission URL.

		if ( 'save' == $formtype ) {
			# Reintegrate metadata
			if ( $this->mMetaData != "" ) $this->textbox1 .= "\n" . $this->mMetaData ;
			$this->mMetaData = "" ;

			# Check for spam
			if ( $wgSpamRegex && preg_match( $wgSpamRegex, $this->textbox1, $matches ) ) {
				$this->spamPage ( $matches[0] );
				return;
			}
			if ( $wgFilterCallback && $wgFilterCallback( $this->mTitle, $this->textbox1, $this->section ) ) {
				# Error messages or other handling should be performed by the filter function
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

			# If article is new, insert it.
			$aid = $this->mTitle->getArticleID( GAID_FOR_UPDATE );
			if ( 0 == $aid ) {
				# Don't save a new article if it's blank.
				if ( ( '' == $this->textbox1 ) ||
				  ( wfMsg( 'newarticletext' ) == $this->textbox1 ) ) {
					$wgOut->redirect( $this->mTitle->getFullURL() );
					return;
				}
				if (wfRunHooks('ArticleSave', $this->mArticle, $wgUser, $this->textbox1, 
							   $this->summary, $this->minoredit, $this->watchthis, NULL))
				{
					$this->mArticle->insertNewArticle( $this->textbox1, $this->summary,
													   $this->minoredit, $this->watchthis );
					wfRunHooks('ArticleSaveComplete', $this->mArticle, $wgUser, $this->textbox1, 
							   $this->summary, $this->minoredit, $this->watchthis, NULL);
				}
				return;
			}

			# Article exists. Check for edit conflict.

			$this->mArticle->clear(); # Force reload of dates, etc.
			$this->mArticle->forUpdate( true ); # Lock the article

			if( ( $this->section != 'new' ) &&
				($this->mArticle->getTimestamp() != $this->edittime ) ) {
				$isConflict = true;
			}
			$userid = $wgUser->getID();

			if ( $isConflict) {
				$text = $this->mArticle->getTextOfLastEditWithSectionReplacedOrAdded(
					$this->section, $this->textbox1, $this->summary, $this->edittime);
			}
			else {
				$text = $this->mArticle->getTextOfLastEditWithSectionReplacedOrAdded(
					$this->section, $this->textbox1, $this->summary);
			}
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
						$this->section = '';
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
				
				if (wfRunHooks('ArticleSave', $this->mArticle, $wgUser, $text, $this->summary,
							   $this->minoredit, $this->watchthis, $sectionanchor))
				{
					# update the article here
					if($this->mArticle->updateArticle( $text, $this->summary, $this->minoredit,
													   $this->watchthis, '', $sectionanchor ))
					{
						wfRunHooks('ArticleSaveComplete', $this->mArticle, $wgUser, $text, $this->summary,
								   $this->minoredit, $this->watchthis, $sectionanchor);
						return;
					}
					else
					  $isConflict = true;
				}
			}
		}
		# First time through: get contents, set time for conflict
		# checking, etc.

		if ( 'initial' == $formtype || $firsttime ) {
			$this->edittime = $this->mArticle->getTimestamp();
			$this->textbox1 = $this->mArticle->getContent( true );
			$this->summary = '';
			$this->proxyCheck();
		}
		$wgOut->setRobotpolicy( 'noindex,nofollow' );

		# Enabled article-related sidebar, toplinks, etc.
		$wgOut->setArticleRelated( true );

		if ( $isConflict ) {
			$s = wfMsg( 'editconflict', $this->mTitle->getPrefixedText() );
			$wgOut->setPageTitle( $s );
			$wgOut->addHTML( wfMsg( 'explainconflict' ) );

			$this->textbox2 = $this->textbox1;
			$this->textbox1 = $this->mArticle->getContent( true );
			$this->edittime = $this->mArticle->getTimestamp();
		} else {

			if( $this->section != '' ) {
				if( $this->section == 'new' ) {
					$s = wfMsg('editingcomment', $this->mTitle->getPrefixedText() );
				} else {
					$s = wfMsg('editingsection', $this->mTitle->getPrefixedText() );
				}
				if(!$this->preview) {
					preg_match( "/^(=+)(.+)\\1/mi",
						$this->textbox1,
						$matches );
					if( !empty( $matches[2] ) ) {
						$this->summary = "/* ". trim($matches[2])." */ ";
					}
				}
			} else {
				$s = wfMsg( 'editing', $this->mTitle->getPrefixedText() );
			}
			$wgOut->setPageTitle( $s );
			if ( !$wgUseLatin1 && !$this->checkUnicodeCompliantBrowser() ) {
				$this->mArticle->setOldSubtitle();
				$wgOut->addWikiText( wfMsg( 'nonunicodebrowser') );
			}
			if ( $this->oldid ) {
				$this->mArticle->setOldSubtitle();
				$wgOut->addHTML( wfMsg( 'editingold' ) );
			}
		}

		if( wfReadOnly() ) {
			$wgOut->addHTML( '<strong>' .
			wfMsg( 'readonlywarning' ) .
			"</strong>" );
		} else if ( $isCssJsSubpage and 'preview' != $formtype) {
			$wgOut->addHTML( wfMsg( 'usercssjsyoucanpreview' ));
		}
		if( $this->mTitle->isProtected('edit') ) {
			$wgOut->addHTML( '<strong>' . wfMsg( 'protectedpagewarning' ) .
			  "</strong><br />\n" );
		}

		$kblength = (int)(strlen( $this->textbox1 ) / 1024);
		if( $kblength > 29 ) {
			$wgOut->addHTML( '<strong>' .
				wfMsg( 'longpagewarning', $wgLang->formatNum( $kblength ) )
				. '</strong>' );
		}

		$rows = $wgUser->getOption( 'rows' );
		$cols = $wgUser->getOption( 'cols' );

		$ew = $wgUser->getOption( 'editwidth' );
		if ( $ew ) $ew = " style=\"width:100%\"";
		else $ew = '';

		$q = 'action=submit';
		#if ( "no" == $redirect ) { $q .= "&redirect=no"; }
		$action = $this->mTitle->escapeLocalURL( $q );

		$summary = wfMsg('summary');
		$subject = wfMsg('subject');
		$minor   = wfMsg('minoredit');
		$watchthis = wfMsg ('watchthis');
		$save = wfMsg('savearticle');
		$prev = wfMsg('showpreview');

		$cancel = $sk->makeKnownLink( $this->mTitle->getPrefixedText(),
				wfMsg('cancel') );
		$edithelpurl = $sk->makeUrl( wfMsg( 'edithelppage' ));
		$edithelp = '<a target="helpwindow" href="'.$edithelpurl.'">'.
			htmlspecialchars( wfMsg( 'edithelp' ) ).'</a> '.
			htmlspecialchars( wfMsg( 'newwindow' ) );

		global $wgRightsText;
		$copywarn = "<div id=\"editpage-copywarn\">\n" .
			wfMsg( $wgRightsText ? 'copyrightwarning' : 'copyrightwarning2',
				'[[' . wfMsg( 'copyrightpage' ) . ']]',
				$wgRightsText ) . "\n</div>";

		if( $wgUser->getOption('showtoolbar') and !$isCssJsSubpage ) {
			# prepare toolbar for edit buttons
			$toolbar = $this->getEditToolbar();
		} else {
			$toolbar = '';
		}

		// activate checkboxes if user wants them to be always active
		if( !$this->preview ) {
			if( $wgUser->getOption( 'watchdefault' ) ) $this->watchthis = true;
			if( $wgUser->getOption( 'minordefault' ) ) $this->minoredit = true;

			// activate checkbox also if user is already watching the page,
			// require wpWatchthis to be unset so that second condition is not
			// checked unnecessarily
			if( !$this->watchthis && $this->mTitle->userIsWatching() ) $this->watchthis = true;
		}

		$minoredithtml = '';

		if ( 0 != $wgUser->getID() || $wgAllowAnonymousMinor ) {
			$minoredithtml =
				"<input tabindex='3' type='checkbox' value='1' name='wpMinoredit'".($this->minoredit?" checked='checked'":"").
				" accesskey='".wfMsg('accesskey-minoredit')."' id='wpMinoredit' />".
				"<label for='wpMinoredit' title='".wfMsg('tooltip-minoredit')."'>{$minor}</label>";
		}

		$watchhtml = '';

		if ( 0 != $wgUser->getID() ) {
			$watchhtml = "<input tabindex='4' type='checkbox' name='wpWatchthis'".($this->watchthis?" checked='checked'":"").
				" accesskey='".wfMsg('accesskey-watch')."' id='wpWatchthis'  />".
				"<label for='wpWatchthis' title='".wfMsg('tooltip-watch')."'>{$watchthis}</label>";
		}

		$checkboxhtml = $minoredithtml . $watchhtml . '<br />';

		$wgOut->addHTML( '<div id="wikiPreview">' );
		if ( 'preview' == $formtype) {
			$previewOutput = $this->getPreviewText( $isConflict, $isCssJsSubpage );
			if( $wgUser->getOption('previewontop' ) ) {
				$wgOut->addHTML( $previewOutput );
				$wgOut->addHTML( "<br style=\"clear:both;\" />\n" );
			}
		}
		$wgOut->addHTML( '</div>' );

		# if this is a comment, show a subject line at the top, which is also the edit summary.
		# Otherwise, show a summary field at the bottom
		$summarytext = htmlspecialchars( $wgContLang->recodeForEdit( $this->summary ) ); # FIXME
			if( $this->section == 'new' ) {
				$commentsubject="{$subject}: <input tabindex='1' type='text' value=\"$summarytext\" name=\"wpSummary\" maxlength='200' size='60' /><br />";
				$editsummary = '';
			} else {
				$commentsubject = '';
				$editsummary="{$summary}: <input tabindex='3' type='text' value=\"$summarytext\" name=\"wpSummary\" maxlength='200' size='60' /><br />";
			}

		if( !$this->preview ) {
		# Don't select the edit box on preview; this interferes with seeing what's going on.
			$wgOut->setOnloadHandler( 'document.editform.wpTextbox1.focus()' );
		}
		# Prepare a list of templates used by this page
		$db =& wfGetDB( DB_SLAVE );
		$page = $db->tableName( 'page' );
		$links = $db->tableName( 'links' );
		$id = $this->mTitle->getArticleID();
		$sql = "SELECT page_namespace,page_title,page_id ".
			"FROM $page,$links WHERE l_to=page_id AND l_from={$id} and page_namespace=".NS_TEMPLATE;
		$res = $db->query( $sql, "EditPage::editform" );

		if ( $db->numRows( $res ) ) {
			$templates = '<br />'. wfMsg( 'templatesused' ) . '<ul>';
			while ( $row = $db->fetchObject( $res ) ) {
				if ( $titleObj = Title::makeTitle( $row->page_namespace, $row->page_title ) ) {
					$templates .= '<li>' . $sk->makeLinkObj( $titleObj ) . '</li>';
				}
			}
			$templates .= '</ul>';
		} else {	
			$templates = '';
		}
		
		global $wgLivePreview, $wgStylePath;
		/**
		 * Live Preview lets us fetch rendered preview page content and
		 * add it to the page without refreshing the whole page.
		 * Set up the button for it; if not supported by the browser
		 * it will fall through to the normal form submission method.
		 */
		if( $wgLivePreview ) {
			$wgOut->addHTML( '<script type="text/javascript" src="' .
				htmlspecialchars( $wgStylePath . '/common/preview.js' ) .
				'"></script>' . "\n" );
			$liveAction = $wgTitle->getLocalUrl( 'action=submit&wpPreview=true&live=true' );
			$liveOnclick = 'onclick="return !livePreview('.
				'getElementById(\'wikiPreview\'),' .
				'editform.wpTextbox1.value,' .
				htmlspecialchars( '"' . $liveAction . '"' ) . ')"';
		} else {
			$liveOnclick = '';
		}
		
		global $wgUseMetadataEdit ;
		if ( $wgUseMetadataEdit )
		{
			$metadata = $this->mMetaData ;
			$metadata = htmlspecialchars( $wgContLang->recodeForEdit( $metadata ) ) ;
			$metadata = "<textarea name='metadata' rows='3' cols='{$cols}'{$ew}>{$metadata}</textarea>" ;
		}
		else $metadata = "" ;


		$wgOut->addHTML( <<<END
{$toolbar}
<form id="editform" name="editform" method="post" action="$action"
enctype="multipart/form-data">
{$commentsubject}
{$mbegin}
<textarea tabindex='1' accesskey="," name="wpTextbox1" rows='{$rows}'
cols='{$cols}'{$ew}>
END
. htmlspecialchars( $wgContLang->recodeForEdit( $this->textbox1 ) ) .
"
</textarea>
{$metadata}
<br />{$editsummary}
{$checkboxhtml}
<input tabindex='5' id='wpSave' type='submit' value=\"{$save}\" name=\"wpSave\" accesskey=\"".wfMsg('accesskey-save')."\"".
" title=\"".wfMsg('tooltip-save')."\"/>
<input tabindex='6' id='wpPreview' type='submit' $liveOnclick value=\"{$prev}\" name=\"wpPreview\" accesskey=\"".wfMsg('accesskey-preview')."\"".
" title=\"".wfMsg('tooltip-preview')."\"/>
<em>{$cancel}</em> | <em>{$edithelp}</em>{$templates}" );
		$wgOut->addWikiText( $copywarn );
		$wgOut->addHTML( "
<input type='hidden' value=\"" . htmlspecialchars( $this->section ) . "\" name=\"wpSection\" />
<input type='hidden' value=\"{$this->edittime}\" name=\"wpEdittime\" />\n" );

		if ( $isConflict ) {
			require_once( "DifferenceEngine.php" );
			$wgOut->addHTML( "<h2>" . wfMsg( "yourdiff" ) . "</h2>\n" );
			DifferenceEngine::showDiff( $this->textbox2, $this->textbox1,
			  wfMsg( "yourtext" ), wfMsg( "storedversion" ) );

			$wgOut->addHTML( "<h2>" . wfMsg( "yourtext" ) . "</h2>
<textarea tabindex=6 id='wpTextbox2' name=\"wpTextbox2\" rows='{$rows}' cols='{$cols}' wrap='virtual'>"
. htmlspecialchars( $wgContLang->recodeForEdit( $this->textbox2 ) ) .
"
</textarea>" );
		}
		$wgOut->addHTML( "</form>\n" );
		if($formtype =="preview" && !$wgUser->getOption("previewontop")) {
			$wgOut->addHTML('<div id="wikiPreview">' . $previewOutput . '</div>');
		}
	}

	function getPreviewText( $isConflict, $isCssJsSubpage ) {
		global $wgOut, $wgUser, $wgTitle, $wgParser;
		$previewhead='<h2>' . wfMsg( 'preview' ) . "</h2>\n<p><center><font color=\"#cc0000\">" .
			wfMsg( 'note' ) . wfMsg( 'previewnote' ) . "</font></center></p>\n";
		if ( $isConflict ) {
			$previewhead.='<h2>' . wfMsg( 'previewconflict' ) .
				"</h2>\n";
		}

		$parserOptions = ParserOptions::newFromUser( $wgUser );
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
			# if user want to see preview when he edit an article
			if( $wgUser->getOption('previewonfirst') and ($this->textbox1 == '')) {
				$this->textbox1 = $this->mArticle->getContent(true);
			}

			$toparse = $this->textbox1 ;
			if ( $this->mMetaData != "" ) $toparse .= "\n" . $this->mMetaData ;
			
			$parserOutput = $wgParser->parse( $this->mArticle->preSaveTransform( $toparse ) ."\n\n",
					$wgTitle, $parserOptions );		
			
			$previewHTML = $parserOutput->mText;
			$wgOut->addCategoryLinks($parserOutput->getCategoryLinks());
			$wgOut->addLanguageLinks($parserOutput->getLanguageLinks());
		}
		return $previewhead . $previewHTML;
	}
	
	/**
	 * @todo document
	 */
	function blockedIPpage() {
		global $wgOut, $wgUser, $wgContLang, $wgIP;

		$wgOut->setPageTitle( wfMsg( 'blockedtitle' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );

		$id = $wgUser->blockedBy();
		$reason = $wgUser->blockedFor();
		$ip = $wgIP;
		
		if ( is_numeric( $id ) ) {
			$name = User::whoIs( $id );
		} else {
			$name = $id;
		}
		$link = '[[' . $wgContLang->getNsText( Namespace::getUser() ) .
		  ":{$name}|{$name}]]";

		$wgOut->addWikiText( wfMsg( 'blockedtext', $link, $reason, $ip, $name ) );
		$wgOut->returnToMain( false );
	}

	/**
	 * @todo document
	 */
	function userNotLoggedInPage() {
		global $wgOut, $wgUser;

		$wgOut->setPageTitle( wfMsg( 'whitelistedittitle' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );

		$wgOut->addWikiText( wfMsg( 'whitelistedittext' ) );
		$wgOut->returnToMain( false );
	}

	/**
	 * @todo document
	 */
	function spamPage ( $match = false )
	{
		global $wgOut;
		$wgOut->setPageTitle( wfMsg( 'spamprotectiontitle' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );

		$wgOut->addWikiText( wfMsg( 'spamprotectiontext' ) );
		if ( $match ) {
			$wgOut->addWikiText( wfMsg( 'spamprotectionmatch', "<nowiki>{$match}</nowiki>" ) );
		}
		$wgOut->returnToMain( false );
	}

	/**
	 * Forks processes to scan the originating IP for an open proxy server
	 * MemCached can be used to skip IPs that have already been scanned
	 */
	function proxyCheck() {
		global $wgBlockOpenProxies, $wgProxyPorts, $wgProxyScriptPath;
		global $wgIP, $wgUseMemCached, $wgMemc, $wgDBname, $wgProxyMemcExpiry;
		
		if ( !$wgBlockOpenProxies ) {
			return;
		}
		
		# Get MemCached key
		$skip = false;
		if ( $wgUseMemCached ) {
			$mcKey = $wgDBname.':proxy:ip:'.$wgIP;
			$mcValue = $wgMemc->get( $mcKey );
			if ( $mcValue ) {
				$skip = true;
			}
		}

		# Fork the processes
		if ( !$skip ) {
			$title = Title::makeTitle( NS_SPECIAL, 'Blockme' );
			$iphash = md5( $wgIP . $wgProxyKey );
			$url = $title->getFullURL( 'ip='.$iphash );

			foreach ( $wgProxyPorts as $port ) {
				$params = implode( ' ', array(
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

	/**
	 * @access private
	 * @todo document
	 */
	function mergeChangesInto( &$text ){
		$yourtext = $this->mArticle->fetchRevisionText();
		
		$db =& wfGetDB( DB_SLAVE );
		$oldText = $this->mArticle->fetchRevisionText(
			$db->timestamp( $this->edittime ),
			'rev_timestamp' );
		
		if(wfMerge($oldText, $text, $yourtext, $result)){
			$text = $result;
			return true;
		} else {
			return false;
		}
	}


	function checkUnicodeCompliantBrowser() {
		global $wgBrowserBlackList;
		$currentbrowser = $_SERVER["HTTP_USER_AGENT"];
		foreach ( $wgBrowserBlackList as $browser ) {
			if ( preg_match($browser, $currentbrowser) ) {
				return false;
			}
		}
		return true;
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

	/**
	 * Shows a bulletin board style toolbar for common editing functions.
	 * It can be disabled in the user preferences.
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
	 * Output preview text only. This can be sucked into the edit page
	 * via JavaScript, and saves the server time rendering the skin as
	 * well as theoretically being more robust on the client (doesn't
	 * disturb the edit box's undo history, won't eat your text on
	 * failure, etc).
	 *
	 * @todo This doesn't include category or interlanguage links.
	 *       Would need to enhance it a bit, maybe wrap them in XML
	 *       or something... that might also require more skin
	 *       initialization, so check whether that's a problem.
	 */
	function livePreview() {
		global $wgOut;
		$wgOut->disable();
		header( 'Content-type: text/xml' );
		header( 'Cache-control: no-cache' );
		# FIXME
		echo $this->getPreviewText( false, false );
	}

}

?>
