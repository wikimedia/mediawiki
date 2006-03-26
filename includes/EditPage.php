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
	var $save = false, $preview = false, $diff = false;
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
	 *  and set $wgMetadataWhitelist to the *full* title of the template whitelist
	 */
	function extractMetaDataFromArticle () {
		global $wgUseMetadataEdit , $wgMetadataWhitelist , $wgLang ;
		$this->mMetaData = '' ;
		if ( !$wgUseMetadataEdit ) return ;
		if ( $wgMetadataWhitelist == '' ) return ;
		$s = '' ;
		$t = $this->mArticle->getContent ( true ) ;

		# MISSING : <nowiki> filtering

		# Categories and language links
		$t = explode ( "\n" , $t ) ;
		$catlow = strtolower ( $wgLang->getNsText ( NS_CATEGORY ) ) ;
		$cat = $ll = array() ;
		foreach ( $t AS $key => $x )
		{
			$y = trim ( strtolower ( $x ) ) ;
			while ( substr ( $y , 0 , 2 ) == '[[' )
			{
				$y = explode ( ']]' , trim ( $x ) ) ;
				$first = array_shift ( $y ) ;
				$first = explode ( ':' , $first ) ;
				$ns = array_shift ( $first ) ;
				$ns = trim ( str_replace ( '[' , '' , $ns ) ) ;
				if ( strlen ( $ns ) == 2 OR strtolower ( $ns ) == $catlow )
				{
					$add = '[[' . $ns . ':' . implode ( ':' , $first ) . ']]' ;
					if ( strtolower ( $ns ) == $catlow ) $cat[] = $add ;
					else $ll[] = $add ;
					$x = implode ( ']]' , $y ) ;
					$t[$key] = $x ;
					$y = trim ( strtolower ( $x ) ) ;
				}
			}
		}
		if ( count ( $cat ) ) $s .= implode ( ' ' , $cat ) . "\n" ;
		if ( count ( $ll ) ) $s .= implode ( ' ' , $ll ) . "\n" ;
		$t = implode ( "\n" , $t ) ;

		# Load whitelist
		$sat = array () ; # stand-alone-templates; must be lowercase
		$wl_title = Title::newFromText ( $wgMetadataWhitelist ) ;
		$wl_article = new Article ( $wl_title ) ;
		$wl = explode ( "\n" , $wl_article->getContent(true) ) ;
		foreach ( $wl AS $x )
		{
			$isentry = false ;
			$x = trim ( $x ) ;
			while ( substr ( $x , 0 , 1 ) == '*' )
			{
				$isentry = true ;
				$x = trim ( substr ( $x , 1 ) ) ;
			}
			if ( $isentry )
			{
				$sat[] = strtolower ( $x ) ;
			}
			   
		}

		# Templates, but only some
		$t = explode ( '{{' , $t ) ;
		$tl = array () ;
		foreach ( $t AS $key => $x )
		{
			$y = explode ( '}}' , $x , 2 ) ;
			if ( count ( $y ) == 2 )
			{
				$z = $y[0] ;
				$z = explode ( '|' , $z ) ;
				$tn = array_shift ( $z ) ;
				if ( in_array ( strtolower ( $tn ) , $sat ) )
				{
					$tl[] = '{{' . $y[0] . '}}' ;
					$t[$key] = $y[1] ;
					$y = explode ( '}}' , $y[1] , 2 ) ;
				}
				else $t[$key] = '{{' . $x ;
			}
			else if ( $key != 0 ) $t[$key] = '{{' . $x ;
			else $t[$key] = $x ;
		}
		if ( count ( $tl ) ) $s .= implode ( ' ' , $tl ) ;
		$t = implode ( '' , $t ) ;

		$t = str_replace ( "\n\n\n" , "\n" , $t ) ;
		$this->mArticle->mContent = $t ;
		$this->mMetaData = $s ;
	}

	/**
	 * This is the function that gets called for "action=edit".
	 */
	function edit() {
		global $wgOut, $wgUser, $wgRequest;
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
		if ( !$this->preview && !$this->diff && $wgUser->isBlockedFrom( $this->mTitle, !$this->save ) ) {
			# When previewing, don't check blocked state - will get caught at save time.
			# Also, check when starting edition is done against slave to improve performance.
			$this->blockedIPpage();
			return;
		}
		if ( !$wgUser->isAllowed('edit') ) {
			if ( $wgUser->isAnon() ) {
				$this->userNotLoggedInPage();
				return;
			} else {
				$wgOut->readOnlyPage( $this->mArticle->getContent( true ), true );
				return;
			}
		}
		if ( wfReadOnly() ) {
			if( $this->save || $this->preview ) {
				$this->editForm( 'preview' );
			} else if ( $this->diff ) {
				$this->editForm( 'diff' );
			} else {
				$wgOut->readOnlyPage( $this->mArticle->getContent( true ) );
			}
			return;
		}
		if ( $this->save ) {
			$this->editForm( 'save' );
		} else if ( $this->preview ) {
			$this->editForm( 'preview' );
		} else if ( $this->diff ) {
			$this->editForm( 'diff' );
		} else { # First time through
			if( $this->previewOnOpen() ) {
				$this->editForm( 'preview', true );
			} else {
				$this->extractMetaDataFromArticle () ;
				$this->editForm( 'initial', true );
			}
		}
	}
	
	/**
	 * Return true if this page should be previewed when the edit form
	 * is initially opened.
	 * @return bool
	 * @access private
	 */
	function previewOnOpen() {
		global $wgUser;
		return $wgUser->getOption( 'previewonfirst' ) ||
			( $this->mTitle->getNamespace() == NS_CATEGORY &&
				!$this->mTitle->exists() );
	}

	/**
	 * @todo document
	 */
	function importFormData( &$request ) {
		if( $request->wasPosted() ) {
			# These fields need to be checked for encoding.
			# Also remove trailing whitespace, but don't remove _initial_
			# whitespace from the text boxes. This may be significant formatting.
			$this->textbox1 = $this->safeUnicodeInput( $request, 'wpTextbox1' );
			$this->textbox2 = $this->safeUnicodeInput( $request, 'wpTextbox2' );
			$this->mMetaData = rtrim( $request->getText( 'metadata'   ) );
			$this->summary   =        $request->getText( 'wpSummary'  );
	
			$this->edittime = $request->getVal( 'wpEdittime' );
			if( is_null( $this->edittime ) ) {
				# If the form is incomplete, force to preview.
				$this->preview  = true;
			} else {
				if( $this->tokenOk( $request ) ) {
					# Some browsers will not report any submit button
					# if the user hits enter in the comment box.
					# The unmarked state will be assumed to be a save,
					# if the form seems otherwise complete.
					$this->preview = $request->getCheck( 'wpPreview' );
					$this->diff = $request->getCheck( 'wpDiff' );
				} else {
					# Page might be a hack attempt posted from
					# an external site. Preview instead of saving.
					$this->preview = true;
				}
			}
			$this->save    = ! ( $this->preview OR $this->diff );
			if( !preg_match( '/^\d{14}$/', $this->edittime )) {
				$this->edittime = null;
			}
	
			$this->minoredit = $request->getCheck( 'wpMinoredit' );
			$this->watchthis = $request->getCheck( 'wpWatchthis' );
		} else {
			# Not a posted form? Start with nothing.
			$this->textbox1  = '';
			$this->textbox2  = '';
			$this->mMetaData = '';
			$this->summary   = '';
			$this->edittime  = '';
			$this->preview   = false;
			$this->save      = false;
			$this->diff	 = false;
			$this->minoredit = false;
			$this->watchthis = false;
		}

		$this->oldid = $request->getInt( 'oldid' );

		# Section edit can come from either the form or a link
		$this->section = $request->getVal( 'wpSection', $request->getVal( 'section' ) );
		
		$this->live = $request->getCheck( 'live' );
	}

	/**
	 * Make sure the form isn't faking a user's credentials.
	 *
	 * @param WebRequest $request
	 * @return bool
	 * @access private
	 */
	function tokenOk( &$request ) {
		global $wgUser;
		if( $wgUser->isAnon() ) {
			# Anonymous users may not have a session
			# open. Don't tokenize.
			return true;
		} else {
			return $wgUser->matchEditToken( $request->getVal( 'wpEditToken' ) );
		}
	}
	
	function submit() {
		$this->edit();
	}

	/**
	 * The edit form is self-submitting, so that when things like
	 * preview and edit conflicts occur, we get the same form back
	 * with the extra stuff added.  Only when the final submission
	 * is made and all is well do we actually save and redirect to
	 * the newly-edited page.
	 *
	 * @param string $formtype Type of form either : save, initial, diff or preview
	 * @param bool $firsttime True to load form data from db
	 */
	function editForm( $formtype, $firsttime = false ) {
		global $wgOut, $wgUser;
		global $wgLang, $wgContLang, $wgParser, $wgTitle;
		global $wgAllowAnonymousMinor, $wgRequest;
		global $wgSpamRegex, $wgFilterCallback;

		$sk = $wgUser->getSkin();
		$isConflict = false;
		// css / js subpages of user pages get a special treatment
		$isCssJsSubpage = $wgTitle->isCssJsSubpage();
		
		if(!$this->mTitle->getArticleID()) { # new article
			$editintro = $wgRequest->getText( 'editintro' );
			$addstandardintro=true;
			if($editintro) {
				$introtitle=Title::newFromText($editintro);
				if(isset($introtitle) && $introtitle->userCanRead()) {
					$rev=Revision::newFromTitle($introtitle);
					if($rev) {
						$wgOut->addWikiText($rev->getText());	
						$addstandardintro=false;
					}
				}
			}
			if($addstandardintro) {
				$wgOut->addWikiText(wfmsg('newarticletext'));				
			}
		}

		if( $this->mTitle->isTalkPage() ) {
			$wgOut->addWikiText(wfmsg('talkpagetext'));
		}

		# Attempt submission here.  This will check for edit conflicts,
		# and redundantly check for locked database, blocked IPs, etc.
		# that edit() already checked just in case someone tries to sneak
		# in the back door with a hand-edited submission URL.

		if ( 'save' == $formtype ) {
			# Reintegrate metadata
			if ( $this->mMetaData != '' ) $this->textbox1 .= "\n" . $this->mMetaData ;
			$this->mMetaData = '' ;

			# Check for spam
			if ( $wgSpamRegex && preg_match( $wgSpamRegex, $this->textbox1, $matches ) ) {
				$this->spamPage ( $matches[0] );
				return;
			}
			if ( $wgFilterCallback && $wgFilterCallback( $this->mTitle, $this->textbox1, $this->section ) ) {
				# Error messages or other handling should be performed by the filter function
				return;
			}
			if ( $wgUser->isBlockedFrom( $this->mTitle, false ) ) {
				# Check block state against master, thus 'false'.
				$this->blockedIPpage();
				return;
			}

			if ( !$wgUser->isAllowed('edit') ) {
				if ( $wgUser->isAnon() ) {
				$this->userNotLoggedInPage();
				return;
			}
				else {
					$wgOut->readOnlyPage();
					return;
				}
			}

			if ( wfReadOnly() ) {
				$wgOut->readOnlyPage();
				return;
			}
			if ( $wgUser->pingLimiter() ) {
				$wgOut->rateLimited();
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
				if (wfRunHooks('ArticleSave', array(&$this->mArticle, &$wgUser, &$this->textbox1,
							   &$this->summary, &$this->minoredit, &$this->watchthis, NULL)))
				{					
					
					$isComment=($this->section=='new');
					$this->mArticle->insertNewArticle( $this->textbox1, $this->summary,
													   $this->minoredit, $this->watchthis, false, $isComment);
					wfRunHooks('ArticleSaveComplete', array(&$this->mArticle, &$wgUser, $this->textbox1,
															$this->summary, $this->minoredit,
															$this->watchthis, NULL));
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
				wfDebug( "EditPage::editForm conflict! getting section '$this->section' for time '$this->edittime' (article time '" .
					$this->mArticle->getTimestamp() . "'\n" );
				$text = $this->mArticle->getTextOfLastEditWithSectionReplacedOrAdded(
					$this->section, $this->textbox1, $this->summary, $this->edittime);
			}
			else {
				wfDebug( "EditPage::editForm getting section '$this->section'\n" );
				$text = $this->mArticle->getTextOfLastEditWithSectionReplacedOrAdded(
					$this->section, $this->textbox1, $this->summary);
			}
			# Suppress edit conflict with self

			if ( ( 0 != $userid ) && ( $this->mArticle->getUser() == $userid ) ) {
				wfDebug( "Suppressing edit conflict, same user.\n" );
				$isConflict = false;
			} else {
				# switch from section editing to normal editing in edit conflict
				if($isConflict) {
					# Attempt merge
					if( $this->mergeChangesInto( $text ) ){
						// Successful merge! Maybe we should tell the user the good news?
						$isConflict = false;
						wfDebug( "Suppressing edit conflict, successful merge.\n" );
					} else {
						$this->section = '';
						$this->textbox1 = $text;
						wfDebug( "Keeping edit conflict, failed merge.\n" );
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
				
				// Save errors may fall down to the edit form, but we've now
				// merged the section into full text. Clear the section field
				// so that later submission of conflict forms won't try to
				// replace that into a duplicated mess.
				$this->textbox1 = $text;
				$this->section = '';
				
				if (wfRunHooks('ArticleSave', array(&$this->mArticle, &$wgUser, &$text,
													&$this->summary, &$this->minoredit,
													&$this->watchthis, &$sectionanchor)))
				{
					# update the article here
					if($this->mArticle->updateArticle( $text, $this->summary, $this->minoredit,
													   $this->watchthis, '', $sectionanchor ))
					{
						wfRunHooks('ArticleSaveComplete', array(&$this->mArticle, &$wgUser, $text,
																$this->summary, $this->minoredit,
																$this->watchthis, $sectionanchor));
						return;
					} else {
						$isConflict = true;
					}
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
			$wgOut->addWikiText( wfMsg( 'explainconflict' ) );

			$this->textbox2 = $this->textbox1;
			$this->textbox1 = $this->mArticle->getContent( true );
			$this->edittime = $this->mArticle->getTimestamp();
		} else {

			if( $this->section != '' ) {
				if( $this->section == 'new' ) {
					$s = wfMsg('editingcomment', $this->mTitle->getPrefixedText() );
				} else {
					$s = wfMsg('editingsection', $this->mTitle->getPrefixedText() );
					if( !$this->preview && !$this->diff ) {
						preg_match( "/^(=+)(.+)\\1/mi",
							$this->textbox1,
							$matches );
						if( !empty( $matches[2] ) ) {
							$this->summary = "/* ". trim($matches[2])." */ ";
						}
					}					
				}
			} else {
				$s = wfMsg( 'editing', $this->mTitle->getPrefixedText() );
			}
			$wgOut->setPageTitle( $s );
			if ( !$this->checkUnicodeCompliantBrowser() ) {
				$wgOut->addWikiText( wfMsg( 'nonunicodebrowser') );
			}
			if ( isset( $this->mArticle )
			     && isset( $this->mArticle->mRevision )
			     && !$this->mArticle->mRevision->isCurrent() ) {
				$this->mArticle->setOldSubtitle( $this->mArticle->mRevision->getId() );
				$wgOut->addWikiText( wfMsg( 'editingold' ) );
			}
		}

		if( wfReadOnly() ) {
			$wgOut->addWikiText( wfMsg( 'readonlywarning' ) );
		} else if ( $isCssJsSubpage and 'preview' != $formtype) {
			$wgOut->addWikiText( wfMsg( 'usercssjsyoucanpreview' ));
		}
		if( $this->mTitle->isProtected('edit') ) {
			$wgOut->addWikiText( wfMsg( 'protectedpagewarning' ) );
		}

		$kblength = (int)(strlen( $this->textbox1 ) / 1024);
		if( $kblength > 29 ) {
			$wgOut->addWikiText( wfMsg( 'longpagewarning', $wgLang->formatNum( $kblength ) ) );
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
		$diff = wfMsg('showdiff');

		$cancel = $sk->makeKnownLink( $this->mTitle->getPrefixedText(),
				wfMsg('cancel') );
		$edithelpurl = $sk->makeInternalOrExternalUrl( wfMsg( 'edithelppage' ));
		$edithelp = '<a target="helpwindow" href="'.$edithelpurl.'">'.
			htmlspecialchars( wfMsg( 'edithelp' ) ).'</a> '.
			htmlspecialchars( wfMsg( 'newwindow' ) );

		global $wgRightsText;
		$copywarn = "<div id=\"editpage-copywarn\">\n" .
			wfMsg( $wgRightsText ? 'copyrightwarning' : 'copyrightwarning2',
				'[[' . wfMsgForContent( 'copyrightpage' ) . ']]',
				$wgRightsText ) . "\n</div>";

		if( $wgUser->getOption('showtoolbar') and !$isCssJsSubpage ) {
			# prepare toolbar for edit buttons
			$toolbar = $this->getEditToolbar();
		} else {
			$toolbar = '';
		}

		// activate checkboxes if user wants them to be always active
		if( !$this->preview && !$this->diff ) {
			if( $wgUser->getOption( 'watchdefault' ) ) $this->watchthis = true;
			if( $wgUser->getOption( 'minordefault' ) ) $this->minoredit = true;

			// activate checkbox also if user is already watching the page,
			// require wpWatchthis to be unset so that second condition is not
			// checked unnecessarily
			if( !$this->watchthis && $this->mTitle->userIsWatching() ) $this->watchthis = true;
		}

		$minoredithtml = '';

		if ( $wgUser->isLoggedIn() || $wgAllowAnonymousMinor ) {
			$minoredithtml =
				"<input tabindex='3' type='checkbox' value='1' name='wpMinoredit'".($this->minoredit?" checked='checked'":"").
				" accesskey='".wfMsg('accesskey-minoredit')."' id='wpMinoredit' />".
				"<label for='wpMinoredit' title='".wfMsg('tooltip-minoredit')."'>{$minor}</label>";
		}

		$watchhtml = '';

		if ( $wgUser->isLoggedIn() ) {
			$watchhtml = "<input tabindex='4' type='checkbox' name='wpWatchthis'".($this->watchthis?" checked='checked'":"").
				" accesskey='".wfMsg('accesskey-watch')."' id='wpWatchthis'  />".
				"<label for='wpWatchthis' title='".wfMsg('tooltip-watch')."'>{$watchthis}</label>";
		}

		$checkboxhtml = $minoredithtml . $watchhtml . '<br />';

		$wgOut->addHTML( '<div id="wikiPreview">' );
		if ( 'preview' == $formtype) {
			$previewOutput = $this->getPreviewText( $isConflict, $isCssJsSubpage );
			if ( $wgUser->getOption('previewontop' ) ) {
				$wgOut->addHTML( $previewOutput );
				if($this->mTitle->getNamespace() == NS_CATEGORY) {
					$this->mArticle->closeShowCategory();
				}				
				$wgOut->addHTML( "<br style=\"clear:both;\" />\n" );
			}
		}
		$wgOut->addHTML( '</div>' );
		if ( 'diff' == $formtype ) {
			if ( $wgUser->getOption('previewontop' ) ) {
				$wgOut->addHTML( $this->getDiff() );
			}
		}


		# if this is a comment, show a subject line at the top, which is also the edit summary.
		# Otherwise, show a summary field at the bottom
		$summarytext = htmlspecialchars( $wgContLang->recodeForEdit( $this->summary ) ); # FIXME
			if( $this->section == 'new' ) {
				$commentsubject="{$subject}: <input tabindex='1' type='text' value=\"$summarytext\" name=\"wpSummary\" maxlength='200' size='60' /><br />";
				$editsummary = '';
			} else {
				$commentsubject = '';
				$editsummary="{$summary}: <input tabindex='2' type='text' value=\"$summarytext\" name=\"wpSummary\" maxlength='200' size='60' /><br />";
			}

		if( !$this->preview && !$this->diff ) {
		# Don't select the edit box on preview; this interferes with seeing what's going on.
			$wgOut->setOnloadHandler( 'document.editform.wpTextbox1.focus()' );
		}
		# Prepare a list of templates used by this page
		$templates = '';
		$articleTemplates = $this->mArticle->getUsedTemplates();
		if ( count( $articleTemplates ) > 0 ) {
			$templates = '<br />'. wfMsg( 'templatesused' ) . '<ul>';
			foreach ( $articleTemplates as $tpl ) {
				if ( $titleObj = Title::makeTitle( NS_TEMPLATE, $tpl ) ) {
					$templates .= '<li>' . $sk->makeLinkObj( $titleObj ) . '</li>';
				}
			}
			$templates .= '</ul>';
		}
		
		global $wgLivePreview, $wgStylePath;
		/**
		 * Live Preview lets us fetch rendered preview page content and
		 * add it to the page without refreshing the whole page.
		 * Set up the button for it; if not supported by the browser
		 * it will fall through to the normal form submission method.
		 */
		if( $wgLivePreview ) {
			global $wgJsMimeType;
			$wgOut->addHTML( '<script type="'.$wgJsMimeType.'" src="' .
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
			$helppage = Title::newFromText ( wfmsg("metadata_page") ) ;
			$top = str_replace ( "$1" , $helppage->getInternalURL() , wfmsg("metadata") ) ;
			$metadata = $top . "<textarea name='metadata' rows='3' cols='{$cols}'{$ew}>{$metadata}</textarea>" ;
		}
		else $metadata = "" ;

		$safemodehtml = $this->checkUnicodeCompliantBrowser()
			? ""
			: "<input type='hidden' name=\"safemode\" value='1' />\n";

		$wgOut->addHTML( <<<END
{$toolbar}
<form id="editform" name="editform" method="post" action="$action"
enctype="multipart/form-data">
{$commentsubject}
<textarea tabindex='1' accesskey="," name="wpTextbox1" rows='{$rows}'
cols='{$cols}'{$ew}>
END
. htmlspecialchars( $this->safeUnicodeOutput( $this->textbox1 ) ) .
"
</textarea>
{$metadata}
<br />{$editsummary}
{$checkboxhtml}
{$safemodehtml}
<input tabindex='5' id='wpSave' type='submit' value=\"{$save}\" name=\"wpSave\" accesskey=\"".wfMsg('accesskey-save')."\"".
" title=\"".wfMsg('tooltip-save')."\"/>
<input tabindex='6' id='wpPreview' type='submit' $liveOnclick value=\"{$prev}\" name=\"wpPreview\" accesskey=\"".wfMsg('accesskey-preview')."\"".
" title=\"".wfMsg('tooltip-preview')."\"/>
<input tabindex='7' id='wpDiff' type='submit' value=\"{$diff}\" name=\"wpDiff\" accesskey=\"".wfMsg('accesskey-diff')."\"".
" title=\"".wfMsg('tooltip-diff')."\"/>
<em>{$cancel}</em> | <em>{$edithelp}</em>{$templates}" );
		$wgOut->addWikiText( $copywarn );
		$wgOut->addHTML( "
<input type='hidden' value=\"" . htmlspecialchars( $this->section ) . "\" name=\"wpSection\" />
<input type='hidden' value=\"{$this->edittime}\" name=\"wpEdittime\" />\n" );

		if ( $wgUser->isLoggedIn() ) {
			/**
			 * To make it harder for someone to slip a user a page
			 * which submits an edit form to the wiki without their
			 * knowledge, a random token is associated with the login
			 * session. If it's not passed back with the submission,
			 * we won't save the page, or render user JavaScript and
			 * CSS previews.
			 */
			$token = htmlspecialchars( $wgUser->editToken() );
			$wgOut->addHTML( "
<input type='hidden' value=\"$token\" name=\"wpEditToken\" />\n" );
		}
		
		
		if ( $isConflict ) {
			require_once( "DifferenceEngine.php" );
			$wgOut->addWikiText( '==' . wfMsg( "yourdiff" ) . '==' );
			DifferenceEngine::showDiff( $this->textbox2, $this->textbox1,
			  wfMsg( "yourtext" ), wfMsg( "storedversion" ) );

			$wgOut->addWikiText( '==' . wfMsg( "yourtext" ) . '==' );
			$wgOut->addHTML( "<textarea tabindex=6 id='wpTextbox2' name=\"wpTextbox2\" rows='{$rows}' cols='{$cols}' wrap='virtual'>"
. htmlspecialchars( $this->safeUnicodeOutput( $this->textbox2 ) ) .
"
</textarea>" );
		}
		$wgOut->addHTML( "</form>\n" );
		if ( $formtype == 'preview' && !$wgUser->getOption( 'previewontop' ) ) {
			$wgOut->addHTML( '<div id="wikiPreview">' . $previewOutput . '</div>' );
		}
		if ( $formtype == 'diff' && !$wgUser->getOption( 'previewontop' ) ) {
			#$wgOut->addHTML( '<div id="wikiPreview">' . $difftext . '</div>' );
			$wgOut->addHTML( $this->getDiff() );
		}
	}

	/**
	 * @todo document
	 */
	function getPreviewText( $isConflict, $isCssJsSubpage ) {
		global $wgOut, $wgUser, $wgTitle, $wgParser, $wgAllowDiffPreview, $wgEnableDiffPreviewPreference;
		$previewhead = '<h2>' . htmlspecialchars( wfMsg( 'preview' ) ) . "</h2>\n" .
			"<p class='previewnote'>" . htmlspecialchars( wfMsg( 'previewnote' ) ) . "</p>\n";
		if ( $isConflict ) {
			$previewhead.='<h2>' . htmlspecialchars( wfMsg( 'previewconflict' ) ) .
				"</h2>\n";
		}

		$parserOptions = ParserOptions::newFromUser( $wgUser );
		$parserOptions->setEditSection( false );

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
			return $previewhead;
		} else {
			# if user want to see preview when he edit an article
			if( $wgUser->getOption('previewonfirst') and ($this->textbox1 == '')) {
				$this->textbox1 = $this->mArticle->getContent(true);
			}

			$toparse = $this->textbox1;
			
			# If we're adding a comment, we need to show the
			# summary as the headline
			if($this->section=="new" && $this->summary!="") {
				$toparse="== {$this->summary} ==\n\n".$toparse;
			}
			
			if ( $this->mMetaData != "" ) $toparse .= "\n" . $this->mMetaData ;
			
			$parserOutput = $wgParser->parse( $this->mArticle->preSaveTransform( $toparse ) ."\n\n",
					$wgTitle, $parserOptions );		
			
			$previewHTML = $parserOutput->mText;
			
			$wgOut->addCategoryLinks($parserOutput->getCategoryLinks());
			$wgOut->addLanguageLinks($parserOutput->getLanguageLinks());
			return $previewhead . $previewHTML;
		}
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
		$link = '[[' . $wgContLang->getNsText( NS_USER ) .
		  ":{$name}|{$name}]]";

		$wgOut->addWikiText( wfMsg( 'blockedtext', $link, $reason, $ip, $name ) );
		$wgOut->returnToMain( false );
	}

	/**
	 * @todo document
	 */
	function userNotLoggedInPage() {
		global $wgOut;

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
	function mergeChangesInto( &$editText ){
		$fname = 'EditPage::mergeChangesInto';
		wfProfileIn( $fname );

		$db =& wfGetDB( DB_MASTER );
		
		// This is the revision the editor started from
		$baseRevision = Revision::loadFromTimestamp(
			$db, $this->mArticle->mTitle, $this->edittime );
		if( is_null( $baseRevision ) ) {
			wfProfileOut( $fname );
			return false;
		}
		$baseText = $baseRevision->getText();

		// The current state, we want to merge updates into it
		$currentRevision =  Revision::loadFromTitle(
			$db, $this->mArticle->mTitle );
		if( is_null( $currentRevision ) ) {
			wfProfileOut( $fname );
			return false;
		}
		$currentText = $currentRevision->getText();
		
		if( wfMerge( $baseText, $editText, $currentText, $result ) ){
			$editText = $result;
			wfProfileOut( $fname );
			return true;
		} else {
			wfProfileOut( $fname );
			return false;
		}
	}

	/**
	 * Check if the browser is on a blacklist of user-agents known to
	 * mangle UTF-8 data on form submission. Returns true if Unicode
	 * should make it through, false if it's known to be a problem.
	 * @return bool
	 * @access private
	 */
	function checkUnicodeCompliantBrowser() {
		global $wgBrowserBlackList;
		if( empty( $_SERVER["HTTP_USER_AGENT"] ) ) {
			// No User-Agent header sent? Trust it by default...
			return true;
		}
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
		$headline = Sanitizer::decodeCharReferences( $text );
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
		global $wgStylePath, $wgContLang, $wgMimeType, $wgJsMimeType;

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
					'open'	=>	'[['.$wgContLang->getNsText(NS_IMAGE).":",
					'close'	=>	']]',
					'sample'=>	wfMsg('image_sample'),
					'tip'	=>	wfMsg('image_tip'),
					'key'	=>	'D'
				),
			array(	'image'	=>'button_media.png',
					'open'	=>	'[['.$wgContLang->getNsText(NS_MEDIA).':',
					'close'	=>	']]',
					'sample'=>	wfMsg('media_sample'),
					'tip'	=>	wfMsg('media_tip'),
					'key'	=>	'M'
				),
			array(	'image'	=>'button_math.png',
					'open'	=>	"\\<math\\>",
					'close'	=>	"\\</math\\>",
					'sample'=>	wfMsg('math_sample'),
					'tip'	=>	wfMsg('math_tip'),
					'key'	=>	'C'
				),
			array(	'image'	=>'button_nowiki.png',
					'open'	=>	"\\<nowiki\\>",
					'close'	=>	"\\</nowiki\\>",
					'sample'=>	wfMsg('nowiki_sample'),
					'tip'	=>	wfMsg('nowiki_tip'),
					'key'	=>	'N'
				),
			array(	'image'	=>'button_sig.png',
					'open'	=>	'--~~~~',
					'close'	=>	'',
					'sample'=>	'',
					'tip'	=>	wfMsg('sig_tip'),
					'key'	=>	'Y'
				),
			array(	'image'	=>'button_hr.png',
					'open'	=>	"\\n----\\n",
					'close'	=>	'',
					'sample'=>	'',
					'tip'	=>	wfMsg('hr_tip'),
					'key'	=>	'R'
				)
		);
		$toolbar ="<script type='$wgJsMimeType'>\n/*<![CDATA[*/\n";

		$toolbar.="document.writeln(\"<div id='toolbar'>\");\n";
		foreach($toolarray as $tool) {

			$image=$wgStylePath.'/common/images/'.$tool['image'];
			$open=$tool['open'];
			$close=$tool['close'];
			$sample = wfEscapeJsString( $tool['sample'] );

			// Note that we use the tip both for the ALT tag and the TITLE tag of the image.
			// Older browsers show a "speedtip" type message only for ALT.
			// Ideally these should be different, realistically they
			// probably don't need to be.
			$tip = wfEscapeJsString( $tool['tip'] );

			#$key = $tool["key"];

			$toolbar.="addButton('$image','$tip','$open','$close','$sample');\n";
		}

		$toolbar.="addInfobox('" . wfEscapeJsString( wfMsg( "infobox" ) ) .
			"','" . wfEscapeJsString( wfMsg( "infobox_alert" ) ) . "');\n";
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


	/**
	 * Get a diff between the current contents of the edit box and the
	 * version of the page we're editing from.
	 *
	 * If this is a section edit, we'll replace the section as for final
	 * save and then make a comparison.
	 *
	 * @return string HTML
	 */
	function getDiff() {
		require_once( 'DifferenceEngine.php' );
		$oldtext = $this->mArticle->fetchContent();
		$newtext = $this->mArticle->getTextOfLastEditWithSectionReplacedOrAdded(
			$this->section, $this->textbox1, $this->summary, $this->edittime );
		$oldtitle = wfMsg( 'currentrev' );
		$newtitle = wfMsg( 'yourtext' );
		if ( $oldtext != wfMsg( 'noarticletext' ) || $newtext != '' ) {
			$difftext = DifferenceEngine::getDiff( $oldtext, $newtext, $oldtitle, $newtitle );
		}
		
		return '<div id="wikiDiff">' . $difftext . '</div>';
	}

	/**
	 * Filter an input field through a Unicode de-armoring process if it
	 * came from an old browser with known broken Unicode editing issues.
	 *
	 * @param WebRequest $request
	 * @param string $field
	 * @return string
	 * @access private
	 */
	function safeUnicodeInput( $request, $field ) {
		$text = rtrim( $request->getText( $field ) );
		return $request->getBool( 'safemode' )
			? $this->unmakesafe( $text )
			: $text;
	}
	
	/**
	 * Filter an output field through a Unicode armoring process if it is
	 * going to an old browser with known broken Unicode editing issues.
	 *
	 * @param string $text
	 * @return string
	 * @access private
	 */
	function safeUnicodeOutput( $text ) {
		global $wgContLang;
		$codedText = $wgContLang->recodeForEdit( $text );
		return $this->checkUnicodeCompliantBrowser()
			? $codedText
			: $this->makesafe( $codedText );
	}
	
	/**
	 * A number of web browsers are known to corrupt non-ASCII characters
	 * in a UTF-8 text editing environment. To protect against this,
	 * detected browsers will be served an armored version of the text,
	 * with non-ASCII chars converted to numeric HTML character references.
	 *
	 * Preexisting such character references will have a 0 added to them
	 * to ensure that round-trips do not alter the original data.
	 *
	 * @param string $invalue
	 * @return string
	 * @access private
	 */
	function makesafe( $invalue ) {
		// Armor existing references for reversability.
		$invalue = strtr( $invalue, array( "&#x" => "&#x0" ) );
		
		$bytesleft = 0;
		$result = "";
		$working = 0;
		for( $i = 0; $i < strlen( $invalue ); $i++ ) {
			$bytevalue = ord( $invalue{$i} );
			if( $bytevalue <= 0x7F ) { //0xxx xxxx
				$result .= chr( $bytevalue );
				$bytesleft = 0;
			} elseif( $bytevalue <= 0xBF ) { //10xx xxxx
				$working = $working << 6;
				$working += ($bytevalue & 0x3F);
				$bytesleft--;
				if( $bytesleft <= 0 ) {
					$result .= "&#x" . strtoupper( dechex( $working ) ) . ";";
				}
			} elseif( $bytevalue <= 0xDF ) { //110x xxxx
				$working = $bytevalue & 0x1F;
				$bytesleft = 1;
			} elseif( $bytevalue <= 0xEF ) { //1110 xxxx
				$working = $bytevalue & 0x0F;
				$bytesleft = 2;
			} else { //1111 0xxx
				$working = $bytevalue & 0x07;
				$bytesleft = 3;
			}
		}
		return $result;
	}
	
	/**
	 * Reverse the previously applied transliteration of non-ASCII characters
	 * back to UTF-8. Used to protect data from corruption by broken web browsers
	 * as listed in $wgBrowserBlackList.
	 *
	 * @param string $invalue
	 * @return string
	 * @access private
	 */
	function unmakesafe( $invalue ) {
		$result = "";
		for( $i = 0; $i < strlen( $invalue ); $i++ ) {
			if( ( substr( $invalue, $i, 3 ) == "&#x" ) && ( $invalue{$i+3} != '0' ) ) {
				$i += 3;
				$hexstring = "";
				do {
					$hexstring .= $invalue{$i};
					$i++;
				} while( ctype_xdigit( $invalue{$i} ) && ( $i < strlen( $invalue ) ) );
				
				// Do some sanity checks. These aren't needed for reversability,
				// but should help keep the breakage down if the editor 
				// breaks one of the entities whilst editing.
				if ((substr($invalue,$i,1)==";") and (strlen($hexstring) <= 6)) { 
					$codepoint = hexdec($hexstring);
					$result .= codepointToUtf8( $codepoint );
				} else {
					$result .= "&#x" . $hexstring . substr( $invalue, $i, 1 );
				}
			} else {
				$result .= substr( $invalue, $i, 1 );
			}
		}
		// reverse the transform that we made for reversability reasons.
		return strtr( $result, array( "&#x0" => "&#x" ) );
	}
	

}

?>
