<?php
/**
 * Contains the EditPage class
 * @file
 */

/**
 * The edit page/HTML interface (split from Article)
 * The actual database and text munging is still in Article,
 * but it should get easier to call those from alternate
 * interfaces.
 *
 * EditPage cares about two distinct titles:
 * $wgTitle is the page that forms submit to, links point to,
 * redirects go to, etc. $this->mTitle (as well as $mArticle) is the
 * page in the database that is actually being edited. These are
 * usually the same, but they are now allowed to be different.
 */
class EditPage {
	const AS_SUCCESS_UPDATE			= 200;
	const AS_SUCCESS_NEW_ARTICLE		= 201;
	const AS_HOOK_ERROR			= 210;
	const AS_FILTERING			= 211;
	const AS_HOOK_ERROR_EXPECTED		= 212;
	const AS_BLOCKED_PAGE_FOR_USER		= 215;
	const AS_CONTENT_TOO_BIG		= 216;
	const AS_USER_CANNOT_EDIT		= 217;
	const AS_READ_ONLY_PAGE_ANON		= 218;
	const AS_READ_ONLY_PAGE_LOGGED		= 219;
	const AS_READ_ONLY_PAGE			= 220;
	const AS_RATE_LIMITED			= 221;
	const AS_ARTICLE_WAS_DELETED		= 222;
	const AS_NO_CREATE_PERMISSION		= 223;
	const AS_BLANK_ARTICLE			= 224;
	const AS_CONFLICT_DETECTED		= 225;
	const AS_SUMMARY_NEEDED			= 226;
	const AS_TEXTBOX_EMPTY			= 228;
	const AS_MAX_ARTICLE_SIZE_EXCEEDED	= 229;
	const AS_OK				= 230;
	const AS_END				= 231;
	const AS_SPAM_ERROR			= 232;
	const AS_IMAGE_REDIRECT_ANON		= 233;
	const AS_IMAGE_REDIRECT_LOGGED		= 234;

	var $mArticle;
	var $mTitle;
	var $action;
	var $mMetaData = '';
	var $isConflict = false;
	var $isCssJsSubpage = false;
	var $deletedSinceEdit = false;
	var $formtype;
	var $firsttime;
	var $lastDelete;
	var $mTokenOk = false;
	var $mTokenOkExceptSuffix = false;
	var $mTriedSave = false;
	var $tooBig = false;
	var $kblength = false;
	var $missingComment = false;
	var $missingSummary = false;
	var $allowBlankSummary = false;
	var $autoSumm = '';
	var $hookError = '';
	#var $mPreviewTemplates;
	var $mParserOutput;
	var $mBaseRevision = false;

	# Form values
	var $save = false, $preview = false, $diff = false;
	var $minoredit = false, $watchthis = false, $recreate = false;
	var $textbox1 = '', $textbox2 = '', $summary = '';
	var $edittime = '', $section = '', $starttime = '';
	var $oldid = 0, $editintro = '', $scrolltop = null;

	# Placeholders for text injection by hooks (must be HTML)
	# extensions should take care to _append_ to the present value
	public $editFormPageTop; // Before even the preview
	public $editFormTextTop;
	public $editFormTextBeforeContent;
	public $editFormTextAfterWarn;
	public $editFormTextAfterTools;
	public $editFormTextBottom;

	/* $didSave should be set to true whenever an article was succesfully altered. */
	public $didSave = false;
	public $undidRev = 0;

	public $suppressIntro = false;

	/**
	 * @todo document
	 * @param $article
	 */
	function EditPage( $article ) {
		$this->mArticle =& $article;
		$this->mTitle = $article->getTitle();
		$this->action = 'submit';

		# Placeholders for text injection by hooks (empty per default)
		$this->editFormPageTop =
		$this->editFormTextTop =
		$this->editFormTextBeforeContent =
		$this->editFormTextAfterWarn =
		$this->editFormTextAfterTools =
		$this->editFormTextBottom = "";
	}
	
	function getArticle() {
		return $this->mArticle;
	}

	/**
	 * Fetch initial editing page content.
	 * @private
	 */
	function getContent( $def_text = '' ) {
		global $wgOut, $wgRequest, $wgParser, $wgContLang, $wgMessageCache;

		wfProfileIn( __METHOD__ );
		# Get variables from query string :P
		$section = $wgRequest->getVal( 'section' );
		$preload = $wgRequest->getVal( 'preload' );
		$undoafter = $wgRequest->getVal( 'undoafter' );
		$undo = $wgRequest->getVal( 'undo' );

		$text = '';
		// For message page not locally set, use the i18n message.
		// For other non-existent articles, use preload text if any.
		if ( !$this->mTitle->exists() ) {
			if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
				# If this is a system message, get the default text.
				list( $message, $lang ) = $wgMessageCache->figureMessage( $wgContLang->lcfirst( $this->mTitle->getText() ) );
				$wgMessageCache->loadAllMessages( $lang );
				$text = wfMsgGetKey( $message, false, $lang, false );
				if( wfEmptyMsg( $message, $text ) )
					$text = '';
			} else {
				# If requested, preload some text.
				$text = $this->getPreloadedText( $preload );
			}
		// For existing pages, get text based on "undo" or section parameters.
		} else {
			$text = $this->mArticle->getContent();
			if ( $undo > 0 && $undoafter > 0 && $undo < $undoafter ) {
				# If they got undoafter and undo round the wrong way, switch them
				list( $undo, $undoafter ) = array( $undoafter, $undo );
			}
			if ( $undo > 0 && $undo > $undoafter ) {
				# Undoing a specific edit overrides section editing; section-editing
				# doesn't work with undoing.
				if ( $undoafter ) {
					$undorev = Revision::newFromId($undo);
					$oldrev = Revision::newFromId($undoafter);
				} else {
					$undorev = Revision::newFromId($undo);
					$oldrev = $undorev ? $undorev->getPrevious() : null;
				}

				# Sanity check, make sure it's the right page,
				# the revisions exist and they were not deleted.
				# Otherwise, $text will be left as-is.
				if ( !is_null( $undorev ) && !is_null( $oldrev ) &&
					$undorev->getPage() == $oldrev->getPage() &&
					$undorev->getPage() == $this->mArticle->getID() &&
					!$undorev->isDeleted( Revision::DELETED_TEXT ) &&
					!$oldrev->isDeleted( Revision::DELETED_TEXT ) ) {
					
					$undotext = $this->mArticle->getUndoText( $undorev, $oldrev );
					if ( $undotext === false ) {
						# Warn the user that something went wrong
						$this->editFormPageTop .= $wgOut->parse( '<div class="error mw-undo-failure">' . wfMsgNoTrans( 'undo-failure' ) . '</div>' );
					} else {
						$text = $undotext;
						# Inform the user of our success and set an automatic edit summary
						$this->editFormPageTop .= $wgOut->parse( '<div class="mw-undo-success">' . wfMsgNoTrans( 'undo-success' ) . '</div>' );
						$firstrev = $oldrev->getNext();
						# If we just undid one rev, use an autosummary
						if ( $firstrev->mId == $undo ) {
							$this->summary = wfMsgForContent( 'undo-summary', $undo, $undorev->getUserText() );
							$this->undidRev = $undo;
						}
						$this->formtype = 'diff';
					}
				} else {
					// Failed basic sanity checks.
					// Older revisions may have been removed since the link
					// was created, or we may simply have got bogus input.
					$this->editFormPageTop .= $wgOut->parse( '<div class="error mw-undo-norev">' . wfMsgNoTrans( 'undo-norev' ) . '</div>' );
				}
			} else if ( $section != '' ) {
				if ( $section == 'new' ) {
					$text = $this->getPreloadedText( $preload );
				} else {
					$text = $wgParser->getSection( $text, $section, $def_text );
				}
			}
		}

		wfProfileOut( __METHOD__ );
		return $text;
	}

	/**
	 * Get the contents of a page from its title and remove includeonly tags
	 *
	 * @param $preload String: the title of the page.
	 * @return string The contents of the page.
	 */
	protected function getPreloadedText( $preload ) {
		if ( $preload === '' ) {
			return '';
		} else {
			$preloadTitle = Title::newFromText( $preload );
			if ( isset( $preloadTitle ) && $preloadTitle->userCanRead() ) {
				$rev = Revision::newFromTitle($preloadTitle);
				if ( is_object( $rev ) ) {
					$text = $rev->getText();
					// TODO FIXME: AAAAAAAAAAA, this shouldn't be implementing
					// its own mini-parser! -ævar
					$text = preg_replace( '~</?includeonly>~', '', $text );
					return $text;
				} else
					return '';
			}
		}
	}

	/**
	 * This is the function that extracts metadata from the article body on the first view.
	 * To turn the feature on, set $wgUseMetadataEdit = true ; in LocalSettings
	 *  and set $wgMetadataWhitelist to the *full* title of the template whitelist
	 */
	function extractMetaDataFromArticle () {
		global $wgUseMetadataEdit, $wgMetadataWhitelist, $wgContLang;
		$this->mMetaData = '';
		if ( !$wgUseMetadataEdit ) return;
		if ( $wgMetadataWhitelist == '' ) return;
		$s = '';
		$t = $this->getContent();

		# MISSING : <nowiki> filtering

		# Categories and language links
		$t = explode ( "\n" , $t );
		$catlow = strtolower ( $wgContLang->getNsText( NS_CATEGORY ) );
		$cat = $ll = array();
		foreach ( $t AS $key => $x ) {
			$y = trim ( strtolower ( $x ) );
			while ( substr ( $y , 0 , 2 ) == '[[' ) {
				$y = explode ( ']]' , trim ( $x ) );
				$first = array_shift ( $y );
				$first = explode ( ':' , $first );
				$ns = array_shift ( $first );
				$ns = trim ( str_replace ( '[' , '' , $ns ) );
				if ( $wgContLang->getLanguageName( $ns ) || strtolower ( $ns ) == $catlow ) {
					$add = '[[' . $ns . ':' . implode ( ':' , $first ) . ']]';
					if ( strtolower ( $ns ) == $catlow ) $cat[] = $add;
					else $ll[] = $add;
					$x = implode ( ']]' , $y );
					$t[$key] = $x;
					$y = trim ( strtolower ( $x ) );
				} else {
					$x = implode ( ']]' , $y );
					$y = trim ( strtolower ( $x ) );
				}
			}
		}
		if ( count ( $cat ) ) $s .= implode ( ' ' , $cat ) . "\n";
		if ( count ( $ll ) ) $s .= implode ( ' ' , $ll ) . "\n";
		$t = implode ( "\n" , $t );

		# Load whitelist
		$sat = array () ; # stand-alone-templates; must be lowercase
		$wl_title = Title::newFromText ( $wgMetadataWhitelist );
		$wl_article = new Article ( $wl_title );
		$wl = explode ( "\n" , $wl_article->getContent() );
		foreach ( $wl AS $x ) {
			$isentry = false;
			$x = trim ( $x );
			while ( substr ( $x , 0 , 1 ) == '*' ) {
				$isentry = true;
				$x = trim ( substr ( $x , 1 ) );
			}
			if ( $isentry ) {
				$sat[] = strtolower ( $x );
			}

		}

		# Templates, but only some
		$t = explode ( '{{' , $t );
		$tl = array () ;
		foreach ( $t AS $key => $x ) {
			$y = explode ( '}}' , $x , 2 );
			if ( count ( $y ) == 2 ) {
				$z = $y[0];
				$z = explode ( '|' , $z );
				$tn = array_shift ( $z );
				if ( in_array ( strtolower ( $tn ) , $sat ) ) {
					$tl[] = '{{' . $y[0] . '}}';
					$t[$key] = $y[1];
					$y = explode ( '}}' , $y[1] , 2 );
				}
				else $t[$key] = '{{' . $x;
			}
			else if ( $key != 0 ) $t[$key] = '{{' . $x;
			else $t[$key] = $x;
		}
		if ( count ( $tl ) ) $s .= implode ( ' ' , $tl );
		$t = implode ( '' , $t );

		$t = str_replace ( "\n\n\n" , "\n" , $t );
		$this->mArticle->mContent = $t;
		$this->mMetaData = $s;
	}

	/* 
	 * Check if a page was deleted while the user was editing it, before submit.
	 * Note that we rely on the logging table, which hasn't been always there,
	 * but that doesn't matter, because this only applies to brand new
	 * deletes.
	 */
	protected function wasDeletedSinceLastEdit() {
		if ( $this->deletedSinceEdit )
			return true;
		if ( $this->mTitle->isDeleted() ) {
			$this->lastDelete = $this->getLastDelete();
			if ( $this->lastDelete ) {
				$deleteTime = wfTimestamp( TS_MW, $this->lastDelete->log_timestamp );
				if ( $deleteTime > $this->starttime ) {
					$this->deletedSinceEdit = true;
				}
			}
		}
		return $this->deletedSinceEdit;
	}

	function submit() {
		$this->edit();
	}

	/**
	 * This is the function that gets called for "action=edit". It
	 * sets up various member variables, then passes execution to
	 * another function, usually showEditForm()
	 *
	 * The edit form is self-submitting, so that when things like
	 * preview and edit conflicts occur, we get the same form back
	 * with the extra stuff added.  Only when the final submission
	 * is made and all is well do we actually save and redirect to
	 * the newly-edited page.
	 */
	function edit() {
		global $wgOut, $wgUser, $wgRequest;
		// Allow extensions to modify/prevent this form or submission
		if ( !wfRunHooks( 'AlternateEdit', array( &$this ) ) ) {
			return;
		}

		wfProfileIn( __METHOD__ );
		wfDebug( __METHOD__.": enter\n" );

		// This is not an article
		$wgOut->setArticleFlag( false );

		$this->importFormData( $wgRequest );
		$this->firsttime = false;

		if ( $this->live ) {
			$this->livePreview();
			wfProfileOut( __METHOD__ );
			return;
		}

		if ( wfReadOnly() && $this->save ) {
				// Force preview
				$this->save = false;
				$this->preview = true;
		}

		$wgOut->addScriptFile( 'edit.js' );
		$permErrors = $this->getEditPermissionErrors();
		if ( $permErrors ) {
			wfDebug( __METHOD__.": User can't edit\n" );
			$this->readOnlyPage( $this->getContent(), true, $permErrors, 'edit' );
			wfProfileOut( __METHOD__ );
			return;
		} else {
			if ( $this->save ) {
				$this->formtype = 'save';
			} else if ( $this->preview ) {
				$this->formtype = 'preview';
			} else if ( $this->diff ) {
				$this->formtype = 'diff';
			} else { # First time through
				$this->firsttime = true;
				if ( $this->previewOnOpen() ) {
					$this->formtype = 'preview';
				} else {
					$this->extractMetaDataFromArticle () ;
					$this->formtype = 'initial';
				}
			}
		}

		wfProfileIn( __METHOD__."-business-end" );

		$this->isConflict = false;
		// css / js subpages of user pages get a special treatment
		$this->isCssJsSubpage      = $this->mTitle->isCssJsSubpage();
		$this->isValidCssJsSubpage = $this->mTitle->isValidCssJsSubpage();

		# Show applicable editing introductions
		if ( $this->formtype == 'initial' || $this->firsttime )
			$this->showIntro();

		if ( $this->mTitle->isTalkPage() ) {
			$wgOut->addWikiMsg( 'talkpagetext' );
		}

		# Optional notices on a per-namespace and per-page basis
		$editnotice_ns   = 'editnotice-'.$this->mTitle->getNamespace();
		$editnotice_page = $editnotice_ns.'-'.$this->mTitle->getDBkey();
		if ( !wfEmptyMsg( $editnotice_ns, wfMsgForContent( $editnotice_ns ) ) ) {
			$wgOut->addWikiText( wfMsgForContent( $editnotice_ns )  );
		}
		if ( MWNamespace::hasSubpages( $this->mTitle->getNamespace() ) ) {
			$parts = explode( '/', $this->mTitle->getDBkey() );
			$editnotice_base = $editnotice_ns;
			while ( count( $parts ) > 0 ) {
				$editnotice_base .= '-'.array_shift( $parts );
				if ( !wfEmptyMsg( $editnotice_base, wfMsgForContent( $editnotice_base ) ) ) {
					$wgOut->addWikiText( wfMsgForContent( $editnotice_base )  );
				}
			}
		} else if ( !wfEmptyMsg( $editnotice_page, wfMsgForContent( $editnotice_page ) ) ) {
			$wgOut->addWikiText( wfMsgForContent( $editnotice_page ) );
		}

		# Attempt submission here.  This will check for edit conflicts,
		# and redundantly check for locked database, blocked IPs, etc.
		# that edit() already checked just in case someone tries to sneak
		# in the back door with a hand-edited submission URL.

		if ( 'save' == $this->formtype ) {
			if ( !$this->attemptSave() ) {
				wfProfileOut( __METHOD__."-business-end" );
				wfProfileOut( __METHOD__ );
				return;
			}
		}

		# First time through: get contents, set time for conflict
		# checking, etc.
		if ( 'initial' == $this->formtype || $this->firsttime ) {
			if ( $this->initialiseForm() === false) {
				$this->noSuchSectionPage();
				wfProfileOut( __METHOD__."-business-end" );
				wfProfileOut( __METHOD__ );
				return;
			}
			if ( !$this->mTitle->getArticleId() )
				wfRunHooks( 'EditFormPreloadText', array( &$this->textbox1, &$this->mTitle ) );
		}

		$this->showEditForm();
		wfProfileOut( __METHOD__."-business-end" );
		wfProfileOut( __METHOD__ );
	}
	
	protected function getEditPermissionErrors() {
		global $wgUser;
		$permErrors = $this->mTitle->getUserPermissionsErrors( 'edit', $wgUser );
		# Can this title be created?
		if ( !$this->mTitle->exists() ) {
			$permErrors = array_merge( $permErrors,
				wfArrayDiff2( $this->mTitle->getUserPermissionsErrors( 'create', $wgUser ), $permErrors ) );
		}
		# Ignore some permissions errors when a user is just previewing/viewing diffs
		$remove = array();
		foreach( $permErrors as $error ) {
			if ( ($this->preview || $this->diff) && 
				($error[0] == 'blockedtext' || $error[0] == 'autoblockedtext') )
			{
				$remove[] = $error;
			}
		}
		$permErrors = wfArrayDiff2( $permErrors, $remove );
		return $permErrors;
	}

	/**
	 * Show a read-only error
	 * Parameters are the same as OutputPage:readOnlyPage()
	 * Redirect to the article page if redlink=1
	 */
	function readOnlyPage( $source = null, $protected = false, $reasons = array(), $action = null ) {
		global $wgRequest, $wgOut;
		if ( $wgRequest->getBool( 'redlink' ) ) {
			// The edit page was reached via a red link.
			// Redirect to the article page and let them click the edit tab if
			// they really want a permission error.
			$wgOut->redirect( $this->mTitle->getFullUrl() );
		} else {
			$wgOut->readOnlyPage( $source, $protected, $reasons, $action );
		}
	}

	/**
	 * Should we show a preview when the edit form is first shown?
	 *
	 * @return bool
	 */
	protected function previewOnOpen() {
		global $wgRequest, $wgUser;
		if ( $wgRequest->getVal( 'preview' ) == 'yes' ) {
			// Explicit override from request
			return true;
		} elseif ( $wgRequest->getVal( 'preview' ) == 'no' ) {
			// Explicit override from request
			return false;
		} elseif ( $this->section == 'new' ) {
			// Nothing *to* preview for new sections
			return false;
		} elseif ( ( $wgRequest->getVal( 'preload' ) !== null || $this->mTitle->exists() ) && $wgUser->getOption( 'previewonfirst' ) ) {
			// Standard preference behaviour
			return true;
		} elseif ( !$this->mTitle->exists() && $this->mTitle->getNamespace() == NS_CATEGORY ) {
			// Categories are special
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @todo document
	 * @param $request
	 */
	function importFormData( &$request ) {
		global $wgLang, $wgUser;
		$fname = 'EditPage::importFormData';
		wfProfileIn( $fname );

		# Section edit can come from either the form or a link
		$this->section = $request->getVal( 'wpSection', $request->getVal( 'section' ) );

		if ( $request->wasPosted() ) {
			# These fields need to be checked for encoding.
			# Also remove trailing whitespace, but don't remove _initial_
			# whitespace from the text boxes. This may be significant formatting.
			$this->textbox1 = $this->safeUnicodeInput( $request, 'wpTextbox1' );
			$this->textbox2 = $this->safeUnicodeInput( $request, 'wpTextbox2' );
			$this->mMetaData = rtrim( $request->getText( 'metadata' ) );
			# Truncate for whole multibyte characters. +5 bytes for ellipsis
			$this->summary = $wgLang->truncate( $request->getText( 'wpSummary' ), 250, '' );

			# Remove extra headings from summaries and new sections.
			$this->summary = preg_replace('/^\s*=+\s*(.*?)\s*=+\s*$/', '$1', $this->summary);

			$this->edittime = $request->getVal( 'wpEdittime' );
			$this->starttime = $request->getVal( 'wpStarttime' );

			$this->scrolltop = $request->getIntOrNull( 'wpScrolltop' );

			if ( is_null( $this->edittime ) ) {
				# If the form is incomplete, force to preview.
				wfDebug( "$fname: Form data appears to be incomplete\n" );
				wfDebug( "POST DATA: " . var_export( $_POST, true ) . "\n" );
				$this->preview = true;
			} else {
				/* Fallback for live preview */
				$this->preview = $request->getCheck( 'wpPreview' ) || $request->getCheck( 'wpLivePreview' );
				$this->diff = $request->getCheck( 'wpDiff' );

				// Remember whether a save was requested, so we can indicate
				// if we forced preview due to session failure.
				$this->mTriedSave = !$this->preview;

				if ( $this->tokenOk( $request ) ) {
					# Some browsers will not report any submit button
					# if the user hits enter in the comment box.
					# The unmarked state will be assumed to be a save,
					# if the form seems otherwise complete.
					wfDebug( "$fname: Passed token check.\n" );
				} else if ( $this->diff ) {
					# Failed token check, but only requested "Show Changes".
					wfDebug( "$fname: Failed token check; Show Changes requested.\n" );
				} else {
					# Page might be a hack attempt posted from
					# an external site. Preview instead of saving.
					wfDebug( "$fname: Failed token check; forcing preview\n" );
					$this->preview = true;
				}
			}
			$this->save = !$this->preview && !$this->diff;
			if ( !preg_match( '/^\d{14}$/', $this->edittime )) {
				$this->edittime = null;
			}

			if ( !preg_match( '/^\d{14}$/', $this->starttime )) {
				$this->starttime = null;
			}

			$this->recreate  = $request->getCheck( 'wpRecreate' );

			$this->minoredit = $request->getCheck( 'wpMinoredit' );
			$this->watchthis = $request->getCheck( 'wpWatchthis' );

			# Don't force edit summaries when a user is editing their own user or talk page
			if ( ( $this->mTitle->mNamespace == NS_USER || $this->mTitle->mNamespace == NS_USER_TALK ) && 
				$this->mTitle->getText() == $wgUser->getName() ) 
			{
				$this->allowBlankSummary = true;
			} else {
				$this->allowBlankSummary = $request->getBool( 'wpIgnoreBlankSummary' ) || !$wgUser->getOption( 'forceeditsummary');
			}

			$this->autoSumm = $request->getText( 'wpAutoSummary' );
		} else {
			# Not a posted form? Start with nothing.
			wfDebug( "$fname: Not a posted form.\n" );
			$this->textbox1  = '';
			$this->textbox2  = '';
			$this->mMetaData = '';
			$this->summary   = '';
			$this->edittime  = '';
			$this->starttime = wfTimestampNow();
			$this->edit      = false;
			$this->preview   = false;
			$this->save      = false;
			$this->diff      = false;
			$this->minoredit = false;
			$this->watchthis = false;
			$this->recreate  = false;

			if ( $this->section == 'new' && $request->getVal( 'preloadtitle' ) ) {
				$this->summary = $request->getVal( 'preloadtitle' );
			}
			elseif ( $this->section != 'new' && $request->getVal( 'summary' ) ) {
				$this->summary = $request->getText( 'summary' );
			}
			
			if ( $request->getVal( 'minor' ) ) {
				$this->minoredit = true;
			}
		}

		$this->oldid = $request->getInt( 'oldid' );

		$this->live = $request->getCheck( 'live' );
		$this->editintro = $request->getText( 'editintro' );

		wfProfileOut( $fname );
	}

	/**
	 * Make sure the form isn't faking a user's credentials.
	 *
	 * @param $request WebRequest
	 * @return bool
	 * @private
	 */
	function tokenOk( &$request ) {
		global $wgUser;
		$token = $request->getVal( 'wpEditToken' );
		$this->mTokenOk = $wgUser->matchEditToken( $token );
		$this->mTokenOkExceptSuffix = $wgUser->matchEditTokenNoSuffix( $token );
		return $this->mTokenOk;
	}

	/**
	 * Show all applicable editing introductions
	 */
	protected function showIntro() {
		global $wgOut, $wgUser;
		if ( $this->suppressIntro ) {
			return;
		}

		$namespace = $this->mTitle->getNamespace();

		if ( $namespace == NS_MEDIAWIKI ) {
			# Show a warning if editing an interface message
			$wgOut->wrapWikiMsg( "<div class='mw-editinginterface'>\n$1</div>", 'editinginterface' );
		}

		# Show a warning message when someone creates/edits a user (talk) page but the user does not exists
		if ( $namespace == NS_USER || $namespace == NS_USER_TALK ) {
			$parts = explode( '/', $this->mTitle->getText(), 2 );
			$username = $parts[0];
			$id = User::idFromName( $username );
			$ip = User::isIP( $username );
			if ( $id == 0 && !$ip ) {
				$wgOut->wrapWikiMsg( '<div class="mw-userpage-userdoesnotexist error">$1</div>',
					array( 'userpage-userdoesnotexist', $username ) );
			}
		}
		# Try to add a custom edit intro, or use the standard one if this is not possible.
		if ( !$this->showCustomIntro() && !$this->mTitle->exists() ) {
			if ( $wgUser->isLoggedIn() ) {
				$wgOut->wrapWikiMsg( '<div class="mw-newarticletext">$1</div>', 'newarticletext' );
			} else {
				$wgOut->wrapWikiMsg( '<div class="mw-newarticletextanon">$1</div>', 'newarticletextanon' );
			}
		}
		# Give a notice if the user is editing a deleted page...
		if ( !$this->mTitle->exists() ) {
			$this->showDeletionLog( $wgOut );
		}
	}

	/**
	 * Attempt to show a custom editing introduction, if supplied
	 *
	 * @return bool
	 */
	protected function showCustomIntro() {
		if ( $this->editintro ) {
			$title = Title::newFromText( $this->editintro );
			if ( $title instanceof Title && $title->exists() && $title->userCanRead() ) {
				global $wgOut;
				$revision = Revision::newFromTitle( $title );
				$wgOut->addWikiTextTitleTidy( $revision->getText(), $this->mTitle );
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * Attempt submission (no UI)
	 * @return one of the constants describing the result
	 */
	function internalAttemptSave( &$result, $bot = false ) {
		global $wgFilterCallback, $wgUser, $wgOut, $wgParser;
		global $wgMaxArticleSize;

		$fname = 'EditPage::attemptSave';
		wfProfileIn( $fname );
		wfProfileIn( "$fname-checks" );

		if ( !wfRunHooks( 'EditPage::attemptSave', array( &$this ) ) )
		{
			wfDebug( "Hook 'EditPage::attemptSave' aborted article saving\n" );
			return self::AS_HOOK_ERROR;
		}

		# Check image redirect
		if ( $this->mTitle->getNamespace() == NS_FILE &&
			Title::newFromRedirect( $this->textbox1 ) instanceof Title &&
			!$wgUser->isAllowed( 'upload' ) ) {
				if ( $wgUser->isAnon() ) {
					return self::AS_IMAGE_REDIRECT_ANON;
				} else {
					return self::AS_IMAGE_REDIRECT_LOGGED;
				}
		}

		# Reintegrate metadata
		if ( $this->mMetaData != '' ) $this->textbox1 .= "\n" . $this->mMetaData ;
		$this->mMetaData = '' ;

		# Check for spam
		$match = self::matchSpamRegex( $this->summary );
		if ( $match === false ) {
			$match = self::matchSpamRegex( $this->textbox1 );
		}
		if ( $match !== false ) {
			$result['spam'] = $match;
			$ip = wfGetIP();
			$pdbk = $this->mTitle->getPrefixedDBkey();
			$match = str_replace( "\n", '', $match );
			wfDebugLog( 'SpamRegex', "$ip spam regex hit [[$pdbk]]: \"$match\"" );
			wfProfileOut( "$fname-checks" );
			wfProfileOut( $fname );
			return self::AS_SPAM_ERROR;
		}
		if ( $wgFilterCallback && $wgFilterCallback( $this->mTitle, $this->textbox1, $this->section, $this->hookError, $this->summary ) ) {
			# Error messages or other handling should be performed by the filter function
			wfProfileOut( "$fname-checks" );
			wfProfileOut( $fname );
			return self::AS_FILTERING;
		}
		if ( !wfRunHooks( 'EditFilter', array( $this, $this->textbox1, $this->section, &$this->hookError, $this->summary ) ) ) {
			# Error messages etc. could be handled within the hook...
			wfProfileOut( "$fname-checks" );
			wfProfileOut( $fname );
			return self::AS_HOOK_ERROR;
		} elseif ( $this->hookError != '' ) {
			# ...or the hook could be expecting us to produce an error
			wfProfileOut( "$fname-checks" );
			wfProfileOut( $fname );
			return self::AS_HOOK_ERROR_EXPECTED;
		}
		if ( $wgUser->isBlockedFrom( $this->mTitle, false ) ) {
			# Check block state against master, thus 'false'.
			wfProfileOut( "$fname-checks" );
			wfProfileOut( $fname );
			return self::AS_BLOCKED_PAGE_FOR_USER;
		}
		$this->kblength = (int)(strlen( $this->textbox1 ) / 1024);
		if ( $this->kblength > $wgMaxArticleSize ) {
			// Error will be displayed by showEditForm()
			$this->tooBig = true;
			wfProfileOut( "$fname-checks" );
			wfProfileOut( $fname );
			return self::AS_CONTENT_TOO_BIG;
		}

		if ( !$wgUser->isAllowed('edit') ) {
			if ( $wgUser->isAnon() ) {
				wfProfileOut( "$fname-checks" );
				wfProfileOut( $fname );
				return self::AS_READ_ONLY_PAGE_ANON;
			}
			else {
				wfProfileOut( "$fname-checks" );
				wfProfileOut( $fname );
				return self::AS_READ_ONLY_PAGE_LOGGED;
			}
		}

		if ( wfReadOnly() ) {
			wfProfileOut( "$fname-checks" );
			wfProfileOut( $fname );
			return self::AS_READ_ONLY_PAGE;
		}
		if ( $wgUser->pingLimiter() ) {
			wfProfileOut( "$fname-checks" );
			wfProfileOut( $fname );
			return self::AS_RATE_LIMITED;
		}

		# If the article has been deleted while editing, don't save it without
		# confirmation
		if ( $this->wasDeletedSinceLastEdit() && !$this->recreate ) {
			wfProfileOut( "$fname-checks" );
			wfProfileOut( $fname );
			return self::AS_ARTICLE_WAS_DELETED;
		}

		wfProfileOut( "$fname-checks" );

		# If article is new, insert it.
		$aid = $this->mTitle->getArticleID( GAID_FOR_UPDATE );
		if ( 0 == $aid ) {
			// Late check for create permission, just in case *PARANOIA*
			if ( !$this->mTitle->userCan( 'create' ) ) {
				wfDebug( "$fname: no create permission\n" );
				wfProfileOut( $fname );
				return self::AS_NO_CREATE_PERMISSION;
			}

			# Don't save a new article if it's blank.
			if ( '' == $this->textbox1 ) {
				wfProfileOut( $fname );
				return self::AS_BLANK_ARTICLE;
			}

			// Run post-section-merge edit filter
			if ( !wfRunHooks( 'EditFilterMerged', array( $this, $this->textbox1, &$this->hookError, $this->summary ) ) ) {
				# Error messages etc. could be handled within the hook...
				wfProfileOut( $fname );
				return self::AS_HOOK_ERROR;
			}
			
			# Handle the user preference to force summaries here. Check if it's not a redirect.
			if ( !$this->allowBlankSummary && !Title::newFromRedirect( $this->textbox1 ) ) {
				if ( md5( $this->summary ) == $this->autoSumm ) {
					$this->missingSummary = true;
					wfProfileOut( $fname );
					return self::AS_SUMMARY_NEEDED;
				}
			}

			$isComment = ( $this->section == 'new' );

			$this->mArticle->insertNewArticle( $this->textbox1, $this->summary,
				$this->minoredit, $this->watchthis, false, $isComment, $bot );

			wfProfileOut( $fname );
			return self::AS_SUCCESS_NEW_ARTICLE;
		}

		# Article exists. Check for edit conflict.

		$this->mArticle->clear(); # Force reload of dates, etc.
		$this->mArticle->forUpdate( true ); # Lock the article

		wfDebug("timestamp: {$this->mArticle->getTimestamp()}, edittime: {$this->edittime}\n");

		if ( $this->mArticle->getTimestamp() != $this->edittime ) {
			$this->isConflict = true;
			if ( $this->section == 'new' ) {
				if ( $this->mArticle->getUserText() == $wgUser->getName() &&
					$this->mArticle->getComment() == $this->summary ) {
					// Probably a duplicate submission of a new comment.
					// This can happen when squid resends a request after
					// a timeout but the first one actually went through.
					wfDebug( "EditPage::editForm duplicate new section submission; trigger edit conflict!\n" );
				} else {
					// New comment; suppress conflict.
					$this->isConflict = false;
					wfDebug( "EditPage::editForm conflict suppressed; new section\n" );
				}
			}
		}
		$userid = $wgUser->getId();
		
		# Suppress edit conflict with self, except for section edits where merging is required.
		if ( $this->isConflict && $this->section == '' && $this->userWasLastToEdit($userid,$this->edittime) ) {
			wfDebug( "EditPage::editForm Suppressing edit conflict, same user.\n" );
			$this->isConflict = false;
		}

		if ( $this->isConflict ) {
			wfDebug( "EditPage::editForm conflict! getting section '$this->section' for time '$this->edittime' (article time '" .
				$this->mArticle->getTimestamp() . "')\n" );
			$text = $this->mArticle->replaceSection( $this->section, $this->textbox1, $this->summary, $this->edittime );
		} else {
			wfDebug( "EditPage::editForm getting section '$this->section'\n" );
			$text = $this->mArticle->replaceSection( $this->section, $this->textbox1, $this->summary );
		}
		if ( is_null( $text ) ) {
			wfDebug( "EditPage::editForm activating conflict; section replace failed.\n" );
			$this->isConflict = true;
			$text = $this->textbox1; // do not try to merge here!
		} else if ( $this->isConflict ) {
			# Attempt merge
			if ( $this->mergeChangesInto( $text ) ) {
				// Successful merge! Maybe we should tell the user the good news?
				$this->isConflict = false;
				wfDebug( "EditPage::editForm Suppressing edit conflict, successful merge.\n" );
			} else {
				$this->section = '';
				$this->textbox1 = $text;
				wfDebug( "EditPage::editForm Keeping edit conflict, failed merge.\n" );
			}
		}

		if ( $this->isConflict ) {
			wfProfileOut( $fname );
			return self::AS_CONFLICT_DETECTED;
		}

		$oldtext = $this->mArticle->getContent();

		// Run post-section-merge edit filter
		if ( !wfRunHooks( 'EditFilterMerged', array( $this, $text, &$this->hookError, $this->summary ) ) ) {
			# Error messages etc. could be handled within the hook...
			wfProfileOut( $fname );
			return self::AS_HOOK_ERROR;
		}

		# Handle the user preference to force summaries here, but not for null edits
		if ( $this->section != 'new' && !$this->allowBlankSummary && 0 != strcmp($oldtext,$text) 
			&& !Title::newFromRedirect( $text ) ) # check if it's not a redirect
		{
			if ( md5( $this->summary ) == $this->autoSumm ) {
				$this->missingSummary = true;
				wfProfileOut( $fname );
				return self::AS_SUMMARY_NEEDED;
			}
		}

		# And a similar thing for new sections
		if ( $this->section == 'new' && !$this->allowBlankSummary ) {
			if (trim($this->summary) == '') {
				$this->missingSummary = true;
				wfProfileOut( $fname );
				return self::AS_SUMMARY_NEEDED;
			}
		}

		# All's well
		wfProfileIn( "$fname-sectionanchor" );
		$sectionanchor = '';
		if ( $this->section == 'new' ) {
			if ( $this->textbox1 == '' ) {
				$this->missingComment = true;
				return self::AS_TEXTBOX_EMPTY;
			}
			if ( $this->summary != '' ) {
				$sectionanchor = $wgParser->guessSectionNameFromWikiText( $this->summary );
				# This is a new section, so create a link to the new section
				# in the revision summary.
				$cleanSummary = $wgParser->stripSectionName( $this->summary );
				$this->summary = wfMsgForContent( 'newsectionsummary', $cleanSummary );
			}
		} elseif ( $this->section != '' ) {
			# Try to get a section anchor from the section source, redirect to edited section if header found
			# XXX: might be better to integrate this into Article::replaceSection
			# for duplicate heading checking and maybe parsing
			$hasmatch = preg_match( "/^ *([=]{1,6})(.*?)(\\1) *\\n/i", $this->textbox1, $matches );
			# we can't deal with anchors, includes, html etc in the header for now,
			# headline would need to be parsed to improve this
			if ( $hasmatch and strlen($matches[2]) > 0 ) {
				$sectionanchor = $wgParser->guessSectionNameFromWikiText( $matches[2] );
			}
		}
		wfProfileOut( "$fname-sectionanchor" );

		// Save errors may fall down to the edit form, but we've now
		// merged the section into full text. Clear the section field
		// so that later submission of conflict forms won't try to
		// replace that into a duplicated mess.
		$this->textbox1 = $text;
		$this->section = '';

		// Check for length errors again now that the section is merged in
		$this->kblength = (int)(strlen( $text ) / 1024);
		if ( $this->kblength > $wgMaxArticleSize ) {
			$this->tooBig = true;
			wfProfileOut( $fname );
			return self::AS_MAX_ARTICLE_SIZE_EXCEEDED;
		}

		# update the article here
		if ( $this->mArticle->updateArticle( $text, $this->summary, $this->minoredit,
			$this->watchthis, $bot, $sectionanchor ) ) 
		{
			wfProfileOut( $fname );
			return self::AS_SUCCESS_UPDATE;
		} else {
			$this->isConflict = true;
		}
		wfProfileOut( $fname );
		return self::AS_END;
	}
	
	/**
	 * Check if no edits were made by other users since
	 * the time a user started editing the page. Limit to
	 * 50 revisions for the sake of performance.
	 */
	protected function userWasLastToEdit( $id, $edittime ) {
		if( !$id ) return false;
		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->select( 'revision',
			'rev_user',
			array( 
				'rev_page' => $this->mArticle->getId(),
				'rev_timestamp > '.$dbw->addQuotes( $dbw->timestamp($edittime) )
			),
			__METHOD__,
			array( 'ORDER BY' => 'rev_timestamp ASC', 'LIMIT' => 50 ) );
		while( $row = $res->fetchObject() ) {
			if( $row->rev_user != $id ) {
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Check given input text against $wgSpamRegex, and return the text of the first match.
	 * @return mixed -- matching string or false
	 */
	public static function matchSpamRegex( $text ) {
		global $wgSpamRegex;
		if ( $wgSpamRegex ) {
			// For back compatibility, $wgSpamRegex may be a single string or an array of regexes.
			$regexes = (array)$wgSpamRegex;
			foreach( $regexes as $regex ) {
				$matches = array();
				if ( preg_match( $regex, $text, $matches ) ) {
					return $matches[0];
				}
			}
		}
		return false;
	}

	/**
	 * Initialise form fields in the object
	 * Called on the first invocation, e.g. when a user clicks an edit link
	 */
	function initialiseForm() {
		$this->edittime = $this->mArticle->getTimestamp();
		$this->textbox1 = $this->getContent( false );
		if ( $this->textbox1 === false ) return false;
		wfProxyCheck();
		return true;
	}

	function setHeaders() {
		global $wgOut, $wgTitle;
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		if ( $this->formtype == 'preview' ) {
			$wgOut->setPageTitleActionText( wfMsg( 'preview' ) );
		}
		if ( $this->isConflict ) {
			$wgOut->setPageTitle( wfMsg( 'editconflict', $wgTitle->getPrefixedText() ) );
		} elseif ( $this->section != '' ) {
			$msg = $this->section == 'new' ? 'editingcomment' : 'editingsection';
			$wgOut->setPageTitle( wfMsg( $msg, $wgTitle->getPrefixedText() ) );
		} else {
			# Use the title defined by DISPLAYTITLE magic word when present
			if ( isset($this->mParserOutput)
			 && ( $dt = $this->mParserOutput->getDisplayTitle() ) !== false ) {
				$title = $dt;
			} else {
				$title = $wgTitle->getPrefixedText();
			}
			$wgOut->setPageTitle( wfMsg( 'editing', $title ) );
		}
	}

	/**
	 * Send the edit form and related headers to $wgOut
	 * @param $formCallback Optional callable that takes an OutputPage
	 *                      parameter; will be called during form output
	 *                      near the top, for captchas and the like.
	 */
	function showEditForm( $formCallback=null ) {
		global $wgOut, $wgUser, $wgLang, $wgContLang, $wgMaxArticleSize, $wgTitle, $wgRequest;

		# If $wgTitle is null, that means we're in API mode.
		# Some hook probably called this function  without checking
		# for is_null($wgTitle) first. Bail out right here so we don't
		# do lots of work just to discard it right after.
		if (is_null($wgTitle))
			return;

		$fname = 'EditPage::showEditForm';
		wfProfileIn( $fname );

		$sk = $wgUser->getSkin();

		wfRunHooks( 'EditPage::showEditForm:initial', array( &$this ) ) ;

		#need to parse the preview early so that we know which templates are used,
		#otherwise users with "show preview after edit box" will get a blank list
		#we parse this near the beginning so that setHeaders can do the title
		#setting work instead of leaving it in getPreviewText
		$previewOutput = '';
		if ( $this->formtype == 'preview' ) {
			$previewOutput = $this->getPreviewText();
		}

		$this->setHeaders();

		# Enabled article-related sidebar, toplinks, etc.
		$wgOut->setArticleRelated( true );

		if ( $this->isConflict ) {
			$wgOut->addWikiMsg( 'explainconflict' );

			$this->textbox2 = $this->textbox1;
			$this->textbox1 = $this->getContent();
			$this->edittime = $this->mArticle->getTimestamp();
		} else {
			if ( $this->section != '' && $this->section != 'new' ) {
				$matches = array();
				if ( !$this->summary && !$this->preview && !$this->diff ) {
					preg_match( "/^(=+)(.+)\\1/mi", $this->textbox1, $matches );
					if ( !empty( $matches[2] ) ) {
						global $wgParser;
						$this->summary = "/* " .
							$wgParser->stripSectionName(trim($matches[2])) .
							" */ ";
					}
				}
			}

			if ( $this->missingComment ) {
				$wgOut->wrapWikiMsg( '<div id="mw-missingcommenttext">$1</div>', 'missingcommenttext' );
			}

			if ( $this->missingSummary && $this->section != 'new' ) {
				$wgOut->wrapWikiMsg( '<div id="mw-missingsummary">$1</div>', 'missingsummary' );
			}

			if ( $this->missingSummary && $this->section == 'new' ) {
				$wgOut->wrapWikiMsg( '<div id="mw-missingcommentheader">$1</div>', 'missingcommentheader' );
			}

			if ( $this->hookError !== '' ) {
				$wgOut->addWikiText( $this->hookError );
			}

			if ( !$this->checkUnicodeCompliantBrowser() ) {
				$wgOut->addWikiMsg( 'nonunicodebrowser' );
			}
			if ( isset( $this->mArticle ) && isset( $this->mArticle->mRevision ) ) {
			// Let sysop know that this will make private content public if saved

				if ( !$this->mArticle->mRevision->userCan( Revision::DELETED_TEXT ) ) {
					$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1</div>\n", 'rev-deleted-text-permission' );
				} else if ( $this->mArticle->mRevision->isDeleted( Revision::DELETED_TEXT ) ) {
					$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1</div>\n", 'rev-deleted-text-view' );
				}

				if ( !$this->mArticle->mRevision->isCurrent() ) {
					$this->mArticle->setOldSubtitle( $this->mArticle->mRevision->getId() );
					$wgOut->addWikiMsg( 'editingold' );
				}
			}
		}

		if ( wfReadOnly() ) {
			$wgOut->wrapWikiMsg( "<div id=\"mw-read-only-warning\">\n$1\n</div>", array( 'readonlywarning', wfReadOnlyReason() ) );
		} elseif ( $wgUser->isAnon() && $this->formtype != 'preview' ) {
			$wgOut->wrapWikiMsg( '<div id="mw-anon-edit-warning">$1</div>', 'anoneditwarning' );
		} else {
			if ( $this->isCssJsSubpage ) {
				# Check the skin exists
				if ( $this->isValidCssJsSubpage ) {
					if ( $this->formtype !== 'preview' ) {
						$wgOut->addWikiMsg( 'usercssjsyoucanpreview' );
					}
				} else {
					$wgOut->addWikiMsg( 'userinvalidcssjstitle', $wgTitle->getSkinFromCssJsSubpage() );
				}
			}
		}

		$classes = array(); // Textarea CSS
		if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
		} elseif ( $this->mTitle->isProtected( 'edit' ) ) {
			# Is the title semi-protected?
			if ( $this->mTitle->isSemiProtected() ) {
				$noticeMsg = 'semiprotectedpagewarning';
				$classes[] = 'mw-textarea-sprotected';
			} else {
				# Then it must be protected based on static groups (regular)
				$noticeMsg = 'protectedpagewarning';
				$classes[] = 'mw-textarea-protected';
			}
			$wgOut->addHTML( "<div class='mw-warning-with-logexcerpt'>\n" );
			$wgOut->addWikiMsg( $noticeMsg );
			LogEventsList::showLogExtract( $wgOut, 'protect', $this->mTitle->getPrefixedText(), '', 1 );
			$wgOut->addHTML( "</div>\n" );
		}
		if ( $this->mTitle->isCascadeProtected() ) {
			# Is this page under cascading protection from some source pages?
			list($cascadeSources, /* $restrictions */) = $this->mTitle->getCascadeProtectionSources();
			$notice = "<div class='mw-cascadeprotectedwarning'>$1\n";
			$cascadeSourcesCount = count( $cascadeSources );
			if ( $cascadeSourcesCount > 0 ) {
				# Explain, and list the titles responsible
				foreach( $cascadeSources as $page ) {
					$notice .= '* [[:' . $page->getPrefixedText() . "]]\n";
				}
			}
			$notice .= '</div>';
			$wgOut->wrapWikiMsg( $notice, array( 'cascadeprotectedwarning', $cascadeSourcesCount ) );
		}
		if ( !$this->mTitle->exists() && $this->mTitle->getRestrictions( 'create' ) ) {
			$wgOut->wrapWikiMsg( '<div class="mw-titleprotectedwarning">$1</div>', 'titleprotectedwarning' );
		}

		if ( $this->kblength === false ) {
			$this->kblength = (int)(strlen( $this->textbox1 ) / 1024);
		}
		if ( $this->tooBig || $this->kblength > $wgMaxArticleSize ) {
			$wgOut->addHTML( "<div class='error' id='mw-edit-longpageerror'>\n" );
			$wgOut->addWikiMsg( 'longpageerror', $wgLang->formatNum( $this->kblength ), $wgLang->formatNum( $wgMaxArticleSize ) );
			$wgOut->addHTML( "</div>\n" );
		} elseif ( $this->kblength > 29 ) {
			$wgOut->addHTML( "<div id='mw-edit-longpagewarning'>\n" );
			$wgOut->addWikiMsg( 'longpagewarning', $wgLang->formatNum( $this->kblength ) );
			$wgOut->addHTML( "</div>\n" );
		}

		$q = 'action='.$this->action;
		#if ( "no" == $redirect ) { $q .= "&redirect=no"; }
		$action = $wgTitle->escapeLocalURL( $q );

		$summary = wfMsg( 'summary' );
		$subject = wfMsg( 'subject' );

		$cancel = $sk->makeKnownLink( $wgTitle->getPrefixedText(),
				wfMsgExt('cancel', array('parseinline')) );
		$separator = wfMsgExt( 'pipe-separator' , 'escapenoentities' );
		$edithelpurl = Skin::makeInternalOrExternalUrl( wfMsgForContent( 'edithelppage' ));
		$edithelp = '<a target="helpwindow" href="'.$edithelpurl.'">'.
			htmlspecialchars( wfMsg( 'edithelp' ) ).'</a> '.
			htmlspecialchars( wfMsg( 'newwindow' ) );

		global $wgRightsText;
		if ( $wgRightsText ) {
			$copywarnMsg = array( 'copyrightwarning',
				'[[' . wfMsgForContent( 'copyrightpage' ) . ']]',
				$wgRightsText );
		} else {
			$copywarnMsg = array( 'copyrightwarning2',
				'[[' . wfMsgForContent( 'copyrightpage' ) . ']]' );
		}

		if ( $wgUser->getOption('showtoolbar') and !$this->isCssJsSubpage ) {
			# prepare toolbar for edit buttons
			$toolbar = EditPage::getEditToolbar();
		} else {
			$toolbar = '';
		}

		// activate checkboxes if user wants them to be always active
		if ( !$this->preview && !$this->diff ) {
			# Sort out the "watch" checkbox
			if ( $wgUser->getOption( 'watchdefault' ) ) {
				# Watch all edits
				$this->watchthis = true;
			} elseif ( $wgUser->getOption( 'watchcreations' ) && !$this->mTitle->exists() ) {
				# Watch creations
				$this->watchthis = true;
			} elseif ( $this->mTitle->userIsWatching() ) {
				# Already watched
				$this->watchthis = true;
			}
			
			# May be overriden by request parameters
			if( $wgRequest->getBool( 'watchthis' ) ) {
				$this->watchthis = true;
			}

			if ( $wgUser->getOption( 'minordefault' ) ) $this->minoredit = true;
		}

		$wgOut->addHTML( $this->editFormPageTop );

		if ( $wgUser->getOption( 'previewontop' ) ) {
			$this->displayPreviewArea( $previewOutput, true );
		}


		$wgOut->addHTML( $this->editFormTextTop );

		# if this is a comment, show a subject line at the top, which is also the edit summary.
		# Otherwise, show a summary field at the bottom
		$summarytext = $wgContLang->recodeForEdit( $this->summary );

		# If a blank edit summary was previously provided, and the appropriate
		# user preference is active, pass a hidden tag as wpIgnoreBlankSummary. This will stop the
		# user being bounced back more than once in the event that a summary
		# is not required.
		#####
		# For a bit more sophisticated detection of blank summaries, hash the
		# automatic one and pass that in the hidden field wpAutoSummary.
		$summaryhiddens =  '';
		if ( $this->missingSummary ) $summaryhiddens .= Xml::hidden( 'wpIgnoreBlankSummary', true );
		$autosumm = $this->autoSumm ? $this->autoSumm : md5( $this->summary );
		$summaryhiddens .= Xml::hidden( 'wpAutoSummary', $autosumm );
		if ( $this->section == 'new' ) {
			$commentsubject = '';
			if ( !$wgRequest->getBool( 'nosummary' ) ) {
				# Add an ID if 'missingsummary' is triggered to allow styling of the summary line
				$summaryMissedID = $this->missingSummary ? ' mw-summarymissed' : '';

				$commentsubject =
					Xml::tags( 'label', array( 'for' => 'wpSummary' ), $subject );
				$commentsubject =
					Xml::tags( 'span', array( 'id' => "wpSummaryLabel$summaryMissedID" ), $commentsubject );
				$commentsubject .= '&nbsp;';
				$commentsubject .= Xml::input( 'wpSummary',
									60,
									$summarytext,
									array(
										'id' => 'wpSummary',
										'maxlength' => '200',
										'tabindex' => '1'
									) );
			}
			$editsummary = "<div class='editOptions'>\n";
			global $wgParser;
			$formattedSummary = wfMsgForContent( 'newsectionsummary', $wgParser->stripSectionName( $this->summary ) );
			$subjectpreview = $summarytext && $this->preview ? "<div class=\"mw-summary-preview\">". wfMsg('subject-preview') . $sk->commentBlock( $formattedSummary, $this->mTitle, true )."</div>\n" : '';
			$summarypreview = '';
		} else {
			$commentsubject = '';

			# Add an ID if 'missingsummary' is triggered to allow styling of the summary line
			$summaryMissedID = $this->missingSummary ? ' mw-summarymissed' : '';

			$editsummary = Xml::tags( 'label', array( 'for' => 'wpSummary' ), $summary );
			$editsummary = Xml::tags( 'span', array( 'id' => "wpSummaryLabel$summaryMissedID" ), $editsummary ) . ' ';

			$editsummary .= Xml::input( 'wpSummary',
				60,
				$summarytext,
				array(
					'id' => 'wpSummary',
					'maxlength' => '200',
					'tabindex' => '1'
				) );

			// No idea where this is closed.
			$editsummary = Xml::openElement( 'div', array( 'class' => 'editOptions' ) )
							. $editsummary . '<br/>';

			$summarypreview = '';
			if ( $summarytext && $this->preview ) {
				$summarypreview =
					Xml::tags( 'div',
						array( 'class' => 'mw-summary-preview' ),
						wfMsg( 'summary-preview' ) .
							$sk->commentBlock( $this->summary, $this->mTitle )
					);
			}
			$subjectpreview = '';
		}
		$commentsubject .= $summaryhiddens;

		# Set focus to the edit box on load, except on preview or diff, where it would interfere with the display
		if ( !$this->preview && !$this->diff ) {
			$wgOut->setOnloadHandler( 'document.editform.wpTextbox1.focus()' );
		}
		$templates = $this->getTemplates();
		$formattedtemplates = $sk->formatTemplates( $templates, $this->preview, $this->section != '');

		$hiddencats = $this->mArticle->getHiddenCategories();
		$formattedhiddencats = $sk->formatHiddenCategories( $hiddencats );

		global $wgUseMetadataEdit ;
		if ( $wgUseMetadataEdit ) {
			$metadata = $this->mMetaData ;
			$metadata = htmlspecialchars( $wgContLang->recodeForEdit( $metadata ) ) ;
			$top = wfMsgWikiHtml( 'metadata_help' );
			/* ToDo: Replace with clean code */
			$ew = $wgUser->getOption( 'editwidth' );
			if ( $ew ) $ew = " style=\"width:100%\"";
			else $ew = '';
			$cols = $wgUser->getIntOption( 'cols' );
			/* /ToDo */
			$metadata = $top . "<textarea name='metadata' rows='3' cols='{$cols}'{$ew}>{$metadata}</textarea>" ;
		}
		else $metadata = "" ;

		$recreate = '';
		if ( $this->wasDeletedSinceLastEdit() ) {
			if ( 'save' != $this->formtype ) {
				$wgOut->wrapWikiMsg(
					'<div class="error mw-deleted-while-editing">$1</div>',
					'deletedwhileediting' );
			} else {
				// Hide the toolbar and edit area, user can click preview to get it back
				// Add an confirmation checkbox and explanation.
				$toolbar = '';
				$recreate = '<div class="mw-confirm-recreate">' .
						$wgOut->parse( wfMsg( 'confirmrecreate',  $this->lastDelete->user_name , $this->lastDelete->log_comment ) ) .
						Xml::checkLabel( wfMsg( 'recreate' ), 'wpRecreate', 'wpRecreate', false,
							array( 'title' => $sk->titleAttrib( 'recreate' ), 'tabindex' => 1, 'id' => 'wpRecreate' )
						) . '</div>';
			}
		}

		$tabindex = 2;

		$checkboxes = $this->getCheckboxes( $tabindex, $sk,
			array( 'minor' => $this->minoredit, 'watch' => $this->watchthis ) );

		$checkboxhtml = implode( $checkboxes, "\n" );

		$buttons = $this->getEditButtons( $tabindex );
		$buttonshtml = implode( $buttons, "\n" );

		$safemodehtml = $this->checkUnicodeCompliantBrowser()
			? '' : Xml::hidden( 'safemode', '1' );

		$wgOut->addHTML( <<<END
{$toolbar}
<form id="editform" name="editform" method="post" action="$action" enctype="multipart/form-data">
END
);

		if ( is_callable( $formCallback ) ) {
			call_user_func_array( $formCallback, array( &$wgOut ) );
		}

		wfRunHooks( 'EditPage::showEditForm:fields', array( &$this, &$wgOut ) );

		// Put these up at the top to ensure they aren't lost on early form submission
		$this->showFormBeforeText();

		$wgOut->addHTML( <<<END
{$recreate}
{$commentsubject}
{$subjectpreview}
{$this->editFormTextBeforeContent}
END
);
		$this->showTextbox1( $classes );

		$wgOut->wrapWikiMsg( "<div id=\"editpage-copywarn\">\n$1\n</div>", $copywarnMsg );
		$wgOut->addHTML( <<<END
{$this->editFormTextAfterWarn}
{$metadata}
{$editsummary}
{$summarypreview}
{$checkboxhtml}
{$safemodehtml}
END
);

		$wgOut->addHTML(
"<div class='editButtons'>
{$buttonshtml}
	<span class='editHelp'>{$cancel}{$separator}{$edithelp}</span>
</div><!-- editButtons -->
</div><!-- editOptions -->");

		/**
		 * To make it harder for someone to slip a user a page
		 * which submits an edit form to the wiki without their
		 * knowledge, a random token is associated with the login
		 * session. If it's not passed back with the submission,
		 * we won't save the page, or render user JavaScript and
		 * CSS previews.
		 *
		 * For anon editors, who may not have a session, we just
		 * include the constant suffix to prevent editing from
		 * broken text-mangling proxies.
		 */
		$token = htmlspecialchars( $wgUser->editToken() );
		$wgOut->addHTML( "\n<input type='hidden' value=\"$token\" name=\"wpEditToken\" />\n" );

		$this->showEditTools();

		$wgOut->addHTML( <<<END
{$this->editFormTextAfterTools}
<div class='templatesUsed'>
{$formattedtemplates}
</div>
<div class='hiddencats'>
{$formattedhiddencats}
</div>
END
);

		if ( $this->isConflict && wfRunHooks( 'EditPageBeforeConflictDiff', array( &$this, &$wgOut ) ) ) {
			$wgOut->wrapWikiMsg( '==$1==', "yourdiff" );

			$de = new DifferenceEngine( $this->mTitle );
			$de->setText( $this->textbox2, $this->textbox1 );
			$de->showDiff( wfMsg( "yourtext" ), wfMsg( "storedversion" ) );

			$wgOut->wrapWikiMsg( '==$1==', "yourtext" );
			$this->showTextbox2();
		}
		$wgOut->addHTML( $this->editFormTextBottom );
		$wgOut->addHTML( "</form>\n" );
		if ( !$wgUser->getOption( 'previewontop' ) ) {
			$this->displayPreviewArea( $previewOutput, false );
		}

		wfProfileOut( $fname );
	}

	protected function showFormBeforeText() {
		global $wgOut;
		$wgOut->addHTML( "
<input type='hidden' value=\"" . htmlspecialchars( $this->section ) . "\" name=\"wpSection\" />
<input type='hidden' value=\"{$this->starttime}\" name=\"wpStarttime\" />\n
<input type='hidden' value=\"{$this->edittime}\" name=\"wpEdittime\" />\n
<input type='hidden' value=\"{$this->scrolltop}\" name=\"wpScrolltop\" id=\"wpScrolltop\" />\n" );
	}
	
	protected function showTextbox1( $classes ) {
		$attribs = array( 'tabindex' => 1 );
		
		if ( $this->wasDeletedSinceLastEdit() )
			$attribs['type'] = 'hidden';
		if ( !empty($classes) )
			$attribs['class'] = implode(' ',$classes);
		
		$this->showTextbox( $this->textbox1, 'wpTextbox1', $attribs );
	}
	
	protected function showTextbox2() {
		$this->showTextbox( $this->textbox2, 'wpTextbox2', array( 'tabindex' => 6 ) );
	}
	
	protected function showTextbox( $content, $name, $attribs = array() ) {
		global $wgOut, $wgUser;
		
		$wikitext = $this->safeUnicodeOutput( $content );
		if ( $wikitext !== '' ) {
			// Ensure there's a newline at the end, otherwise adding lines
			// is awkward.
			// But don't add a newline if the ext is empty, or Firefox in XHTML
			// mode will show an extra newline. A bit annoying.
			$wikitext .= "\n";
		}
		
		$attribs['accesskey'] = ',';
		$attribs['id'] = $name;
		
		if ( $wgUser->getOption( 'editwidth' ) )
			$attribs['style'] = 'width: 100%';
		
		$wgOut->addHTML( Xml::textarea(
			$name,
			$wikitext,
			$wgUser->getIntOption( 'cols' ), $wgUser->getIntOption( 'rows' ),
			$attribs ) );
	}

	protected function displayPreviewArea( $previewOutput, $isOnTop = false ) {
		global $wgOut;
		$classes = array();
		if ( $isOnTop )
			$classes[] = 'ontop';

		$attribs = array( 'id' => 'wikiPreview', 'class' => implode( ' ', $classes ) );

		if ( $this->formtype != 'preview' )
			$attribs['style'] = 'display: none;';

		$wgOut->addHTML( Xml::openElement( 'div', $attribs ) );

		if ( $this->formtype == 'preview' ) {
			$this->showPreview( $previewOutput );
		}

		$wgOut->addHTML( '</div>' );

		if ( $this->formtype == 'diff') {
			$this->showDiff();
		}
	}

	/**
	 * Append preview output to $wgOut.
	 * Includes category rendering if this is a category page.
	 *
	 * @param string $text The HTML to be output for the preview.
	 */
	protected function showPreview( $text ) {
		global $wgOut;
		if ( $this->mTitle->getNamespace() == NS_CATEGORY) {
			$this->mArticle->openShowCategory();
		}
		# This hook seems slightly odd here, but makes things more
		# consistent for extensions.
		wfRunHooks( 'OutputPageBeforeHTML',array( &$wgOut, &$text ) );
		$wgOut->addHTML( $text );
		if ( $this->mTitle->getNamespace() == NS_CATEGORY ) {
			$this->mArticle->closeShowCategory();
		}
	}

	/**
	 * Live Preview lets us fetch rendered preview page content and
	 * add it to the page without refreshing the whole page.
	 * If not supported by the browser it will fall through to the normal form
	 * submission method.
	 *
	 * This function outputs a script tag to support live preview, and
	 * returns an onclick handler which should be added to the attributes
	 * of the preview button
	 */
	function doLivePreviewScript() {
		global $wgOut, $wgTitle;
		$wgOut->addScriptFile( 'preview.js' );
		$liveAction = $wgTitle->getLocalUrl( "action={$this->action}&wpPreview=true&live=true" );
		return "return !lpDoPreview(" .
			"editform.wpTextbox1.value," .
			'"' . $liveAction . '"' . ")";
	}

	protected function showEditTools() {
		global $wgOut;
		$wgOut->addHTML( '<div class="mw-editTools">' );
		$wgOut->addWikiMsgArray( 'edittools', array(), array( 'content' ) );
		$wgOut->addHTML( '</div>' );
	}

	function getLastDelete() {
		$dbr = wfGetDB( DB_SLAVE );
		$data = $dbr->selectRow(
			array( 'logging', 'user' ),
			array( 'log_type',
			       'log_action',
			       'log_timestamp',
			       'log_user',
			       'log_namespace',
			       'log_title',
			       'log_comment',
			       'log_params',
			       'user_name', ),
			array( 'log_namespace' => $this->mTitle->getNamespace(),
			       'log_title' => $this->mTitle->getDBkey(),
			       'log_type' => 'delete',
			       'log_action' => 'delete',
			       'user_id=log_user' ),
			__METHOD__,
			array( 'LIMIT' => 1, 'ORDER BY' => 'log_timestamp DESC' ) );

		return $data;
	}

	/**
	 * Get the rendered text for previewing.
	 * @return string
	 */
	function getPreviewText() {
		global $wgOut, $wgUser, $wgTitle, $wgParser, $wgLang, $wgContLang, $wgMessageCache;

		wfProfileIn( __METHOD__ );

		if ( $this->mTriedSave && !$this->mTokenOk ) {
			if ( $this->mTokenOkExceptSuffix ) {
				$note = wfMsg( 'token_suffix_mismatch' );
			} else {
				$note = wfMsg( 'session_fail_preview' );
			}
		} else {
			$note = wfMsg( 'previewnote' );
		}

		$parserOptions = ParserOptions::newFromUser( $wgUser );
		$parserOptions->setEditSection( false );
		$parserOptions->setIsPreview( true );
		$parserOptions->setIsSectionPreview( !is_null($this->section) && $this->section !== '' );

		global $wgRawHtml;
		if ( $wgRawHtml && !$this->mTokenOk ) {
			// Could be an offsite preview attempt. This is very unsafe if
			// HTML is enabled, as it could be an attack.
			return $wgOut->parse( "<div class='previewnote'>" .
				wfMsg( 'session_fail_preview_html' ) . "</div>" );
		}

		# don't parse user css/js, show message about preview
		# XXX: stupid php bug won't let us use $wgTitle->isCssJsSubpage() here

		if ( $this->isCssJsSubpage ) {
			if (preg_match("/\\.css$/", $this->mTitle->getText() ) ) {
				$previewtext = wfMsg('usercsspreview');
			} else if (preg_match("/\\.js$/", $this->mTitle->getText() ) ) {
				$previewtext = wfMsg('userjspreview');
			}
			$parserOptions->setTidy(true);
			$parserOutput = $wgParser->parse( $previewtext, $this->mTitle, $parserOptions );
			$previewHTML = $parserOutput->mText;
		} elseif ( $rt = Title::newFromRedirectArray( $this->textbox1 ) ) {
			$previewHTML = $this->mArticle->viewRedirect( $rt, false );
		} else {
			$toparse = $this->textbox1;

			# If we're adding a comment, we need to show the
			# summary as the headline
			if ( $this->section=="new" && $this->summary!="" ) {
				$toparse="== {$this->summary} ==\n\n".$toparse;
			}

			if ( $this->mMetaData != "" ) $toparse .= "\n" . $this->mMetaData;

			// Parse mediawiki messages with correct target language
			if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
				list( /* $unused */, $lang ) = $wgMessageCache->figureMessage( $this->mTitle->getText() );
				$obj = wfGetLangObj( $lang );
				$parserOptions->setTargetLanguage( $obj );
			}


			$parserOptions->setTidy(true);
			$parserOptions->enableLimitReport();
			$parserOutput = $wgParser->parse( $this->mArticle->preSaveTransform( $toparse ),
					$this->mTitle, $parserOptions );

			$previewHTML = $parserOutput->getText();
			$this->mParserOutput = $parserOutput;
			$wgOut->addParserOutputNoText( $parserOutput );

			if ( count( $parserOutput->getWarnings() ) ) {
				$note .= "\n\n" . implode( "\n\n", $parserOutput->getWarnings() );
			}
		}

		$previewhead = '<h2>' . htmlspecialchars( wfMsg( 'preview' ) ) . "</h2>\n" .
			"<div class='previewnote'>" . $wgOut->parse( $note ) . "</div>\n";
		if ( $this->isConflict ) {
			$previewhead .='<h2>' . htmlspecialchars( wfMsg( 'previewconflict' ) ) . "</h2>\n";
		}

		wfProfileOut( __METHOD__ );
		return $previewhead . $previewHTML;
	}
	
	function getTemplates() {
		if ( $this->preview || $this->section != '' ) {
			$templates = array();
			if ( !isset($this->mParserOutput) ) return $templates;
			foreach( $this->mParserOutput->getTemplates() as $ns => $template) {
				foreach( array_keys( $template ) as $dbk ) {
					$templates[] = Title::makeTitle($ns, $dbk);
				}
			}
			return $templates;
		} else {
			return $this->mArticle->getUsedTemplates();
		}
	}

	/**
	 * Call the stock "user is blocked" page
	 */
	function blockedPage() {
		global $wgOut, $wgUser;
		$wgOut->blockedPage( false ); # Standard block notice on the top, don't 'return'

		# If the user made changes, preserve them when showing the markup
		# (This happens when a user is blocked during edit, for instance)
		$first = $this->firsttime || ( !$this->save && $this->textbox1 == '' );
		if ( $first ) {
			$source = $this->mTitle->exists() ? $this->getContent() : false;
		} else {
			$source = $this->textbox1;
		}

		# Spit out the source or the user's modified version
		if ( $source !== false ) {
			$rows = $wgUser->getIntOption( 'rows' );
			$cols = $wgUser->getIntOption( 'cols' );
			$attribs = array( 'id' => 'wpTextbox1', 'name' => 'wpTextbox1', 'cols' => $cols, 'rows' => $rows, 'readonly' => 'readonly' );
			$wgOut->addHTML( '<hr />' );
			$wgOut->addWikiMsg( $first ? 'blockedoriginalsource' : 'blockededitsource', $this->mTitle->getPrefixedText() );
			# Why we don't use Xml::element here?
			# Is it because if $source is '', it returns <textarea />?
			$wgOut->addHTML( Xml::openElement( 'textarea', $attribs ) . htmlspecialchars( $source ) . Xml::closeElement( 'textarea' ) );
		}
	}

	/**
	 * Produce the stock "please login to edit pages" page
	 */
	function userNotLoggedInPage() {
		global $wgUser, $wgOut, $wgTitle;
		$skin = $wgUser->getSkin();

		$loginTitle = SpecialPage::getTitleFor( 'Userlogin' );
		$loginLink = $skin->makeKnownLinkObj( $loginTitle, wfMsgHtml( 'loginreqlink' ), 'returnto=' . $wgTitle->getPrefixedUrl() );

		$wgOut->setPageTitle( wfMsg( 'whitelistedittitle' ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );

		$wgOut->addHTML( wfMsgWikiHtml( 'whitelistedittext', $loginLink ) );
		$wgOut->returnToMain( false, $wgTitle );
	}

	/**
	 * Creates a basic error page which informs the user that
	 * they have attempted to edit a nonexistent section.
	 */
	function noSuchSectionPage() {
		global $wgOut, $wgTitle;

		$wgOut->setPageTitle( wfMsg( 'nosuchsectiontitle' ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );

		$wgOut->addWikiMsg( 'nosuchsectiontext', $this->section );
		$wgOut->returnToMain( false, $wgTitle );
	}

	/**
	 * Produce the stock "your edit contains spam" page
	 *
	 * @param $match Text which triggered one or more filters
	 */
	function spamPage( $match = false ) {
		global $wgOut, $wgTitle;

		$wgOut->setPageTitle( wfMsg( 'spamprotectiontitle' ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );

		$wgOut->addHTML( '<div id="spamprotected">' );
		$wgOut->addWikiMsg( 'spamprotectiontext' );
		if ( $match )
			$wgOut->addWikiMsg( 'spamprotectionmatch', wfEscapeWikiText( $match ) );
		$wgOut->addHTML( '</div>' );

		$wgOut->returnToMain( false, $wgTitle );
	}

	/**
	 * @private
	 * @todo document
	 */
	function mergeChangesInto( &$editText ){
		$fname = 'EditPage::mergeChangesInto';
		wfProfileIn( $fname );

		$db = wfGetDB( DB_MASTER );

		// This is the revision the editor started from
		$baseRevision = $this->getBaseRevision();
		if ( is_null( $baseRevision ) ) {
			wfProfileOut( $fname );
			return false;
		}
		$baseText = $baseRevision->getText();

		// The current state, we want to merge updates into it
		$currentRevision = Revision::loadFromTitle( $db, $this->mTitle );
		if ( is_null( $currentRevision ) ) {
			wfProfileOut( $fname );
			return false;
		}
		$currentText = $currentRevision->getText();

		$result = '';
		if ( wfMerge( $baseText, $editText, $currentText, $result ) ) {
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
	 * @private
	 */
	function checkUnicodeCompliantBrowser() {
		global $wgBrowserBlackList;
		if ( empty( $_SERVER["HTTP_USER_AGENT"] ) ) {
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
	 * @deprecated use $wgParser->stripSectionName()
	 */
	function pseudoParseSectionAnchor( $text ) {
		global $wgParser;
		return $wgParser->stripSectionName( $text );
	}

	/**
	 * Format an anchor fragment as it would appear for a given section name
	 * @param string $text
	 * @return string
	 * @private
	 */
	function sectionAnchor( $text ) {
		global $wgParser;
		return $wgParser->guessSectionNameFromWikiText( $text );
	}

	/**
	 * Shows a bulletin board style toolbar for common editing functions.
	 * It can be disabled in the user preferences.
	 * The necessary JavaScript code can be found in skins/common/edit.js.
	 * 
	 * @return string
	 */
	static function getEditToolbar() {
		global $wgStylePath, $wgContLang, $wgLang, $wgJsMimeType;

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
		$toolarray = array(
			array(
				'image'  => $wgLang->getImageFile('button-bold'),
				'id'     => 'mw-editbutton-bold',
				'open'   => '\'\'\'',
				'close'  => '\'\'\'',
				'sample' => wfMsg('bold_sample'),
				'tip'    => wfMsg('bold_tip'),
				'key'    => 'B'
			),
			array(
				'image'  => $wgLang->getImageFile('button-italic'),
				'id'     => 'mw-editbutton-italic',
				'open'   => '\'\'',
				'close'  => '\'\'',
				'sample' => wfMsg('italic_sample'),
				'tip'    => wfMsg('italic_tip'),
				'key'    => 'I'
			),
			array(
				'image'  => $wgLang->getImageFile('button-link'),
				'id'     => 'mw-editbutton-link',
				'open'   => '[[',
				'close'  => ']]',
				'sample' => wfMsg('link_sample'),
				'tip'    => wfMsg('link_tip'),
				'key'    => 'L'
			),
			array(
				'image'  => $wgLang->getImageFile('button-extlink'),
				'id'     => 'mw-editbutton-extlink',
				'open'   => '[',
				'close'  => ']',
				'sample' => wfMsg('extlink_sample'),
				'tip'    => wfMsg('extlink_tip'),
				'key'    => 'X'
			),
			array(
				'image'  => $wgLang->getImageFile('button-headline'),
				'id'     => 'mw-editbutton-headline',
				'open'   => "\n== ",
				'close'  => " ==\n",
				'sample' => wfMsg('headline_sample'),
				'tip'    => wfMsg('headline_tip'),
				'key'    => 'H'
			),
			array(
				'image'  => $wgLang->getImageFile('button-image'),
				'id'     => 'mw-editbutton-image',
				'open'   => '[['.$wgContLang->getNsText(NS_FILE).':',
				'close'  => ']]',
				'sample' => wfMsg('image_sample'),
				'tip'    => wfMsg('image_tip'),
				'key'    => 'D'
			),
			array(
				'image'  => $wgLang->getImageFile('button-media'),
				'id'     => 'mw-editbutton-media',
				'open'   => '[['.$wgContLang->getNsText(NS_MEDIA).':',
				'close'  => ']]',
				'sample' => wfMsg('media_sample'),
				'tip'    => wfMsg('media_tip'),
				'key'    => 'M'
			),
			array(
				'image'  => $wgLang->getImageFile('button-math'),
				'id'     => 'mw-editbutton-math',
				'open'   => "<math>",
				'close'  => "</math>",
				'sample' => wfMsg('math_sample'),
				'tip'    => wfMsg('math_tip'),
				'key'    => 'C'
			),
			array(
				'image'  => $wgLang->getImageFile('button-nowiki'),
				'id'     => 'mw-editbutton-nowiki',
				'open'   => "<nowiki>",
				'close'  => "</nowiki>",
				'sample' => wfMsg('nowiki_sample'),
				'tip'    => wfMsg('nowiki_tip'),
				'key'    => 'N'
			),
			array(
				'image'  => $wgLang->getImageFile('button-sig'),
				'id'     => 'mw-editbutton-signature',
				'open'   => '--~~~~',
				'close'  => '',
				'sample' => '',
				'tip'    => wfMsg('sig_tip'),
				'key'    => 'Y'
			),
			array(
				'image'  => $wgLang->getImageFile('button-hr'),
				'id'     => 'mw-editbutton-hr',
				'open'   => "\n----\n",
				'close'  => '',
				'sample' => '',
				'tip'    => wfMsg('hr_tip'),
				'key'    => 'R'
			)
		);
		$toolbar = "<div id='toolbar'>\n";
		$toolbar.="<script type='$wgJsMimeType'>\n/*<![CDATA[*/\n";

		foreach($toolarray as $tool) {
			$params = array(
				$image = $wgStylePath.'/common/images/'.$tool['image'],
				// Note that we use the tip both for the ALT tag and the TITLE tag of the image.
				// Older browsers show a "speedtip" type message only for ALT.
				// Ideally these should be different, realistically they
				// probably don't need to be.
				$tip = $tool['tip'],
				$open = $tool['open'],
				$close = $tool['close'],
				$sample = $tool['sample'],
				$cssId = $tool['id'],
			);

			$paramList = implode( ',',
				array_map( array( 'Xml', 'encodeJsVar' ), $params ) );
			$toolbar.="addButton($paramList);\n";
		}

		$toolbar.="/*]]>*/\n</script>";
		$toolbar.="\n</div>";
		return $toolbar;
	}

	/**
	 * Returns an array of html code of the following checkboxes:
	 * minor and watch
	 *
	 * @param $tabindex Current tabindex
	 * @param $skin Skin object
	 * @param $checked Array of checkbox => bool, where bool indicates the checked
	 *                 status of the checkbox
	 *
	 * @return array
	 */
	public function getCheckboxes( &$tabindex, $skin, $checked ) {
		global $wgUser;

		$checkboxes = array();

		$checkboxes['minor'] = '';
		$minorLabel = wfMsgExt('minoredit', array('parseinline'));
		if ( $wgUser->isAllowed('minoredit') ) {
			$attribs = array(
				'tabindex'  => ++$tabindex,
				'accesskey' => wfMsg( 'accesskey-minoredit' ),
				'id'        => 'wpMinoredit',
			);
			$checkboxes['minor'] =
				Xml::check( 'wpMinoredit', $checked['minor'], $attribs ) .
				"&nbsp;<label for='wpMinoredit'".$skin->tooltip('minoredit', 'withaccess').">{$minorLabel}</label>";
		}

		$watchLabel = wfMsgExt('watchthis', array('parseinline'));
		$checkboxes['watch'] = '';
		if ( $wgUser->isLoggedIn() ) {
			$attribs = array(
				'tabindex'  => ++$tabindex,
				'accesskey' => wfMsg( 'accesskey-watch' ),
				'id'        => 'wpWatchthis',
			);
			$checkboxes['watch'] =
				Xml::check( 'wpWatchthis', $checked['watch'], $attribs ) .
				"&nbsp;<label for='wpWatchthis'".$skin->tooltip('watch', 'withaccess').">{$watchLabel}</label>";
		}
		wfRunHooks( 'EditPageBeforeEditChecks', array( &$this, &$checkboxes, &$tabindex ) );
		return $checkboxes;
	}

	/**
	 * Returns an array of html code of the following buttons:
	 * save, diff, preview and live
	 *
	 * @param $tabindex Current tabindex
	 *
	 * @return array
	 */
	public function getEditButtons(&$tabindex) {
		global $wgLivePreview, $wgUser;

		$buttons = array();

		$temp = array(
			'id'        => 'wpSave',
			'name'      => 'wpSave',
			'type'      => 'submit',
			'tabindex'  => ++$tabindex,
			'value'     => wfMsg('savearticle'),
			'accesskey' => wfMsg('accesskey-save'),
			'title'     => wfMsg( 'tooltip-save' ).' ['.wfMsg( 'accesskey-save' ).']',
		);
		$buttons['save'] = Xml::element('input', $temp, '');

		++$tabindex; // use the same for preview and live preview
		if ( $wgLivePreview && $wgUser->getOption( 'uselivepreview' ) ) {
			$temp = array(
				'id'        => 'wpPreview',
				'name'      => 'wpPreview',
				'type'      => 'submit',
				'tabindex'  => $tabindex,
				'value'     => wfMsg('showpreview'),
				'accesskey' => '',
				'title'     => wfMsg( 'tooltip-preview' ).' ['.wfMsg( 'accesskey-preview' ).']',
				'style'     => 'display: none;',
			);
			$buttons['preview'] = Xml::element('input', $temp, '');

			$temp = array(
				'id'        => 'wpLivePreview',
				'name'      => 'wpLivePreview',
				'type'      => 'submit',
				'tabindex'  => $tabindex,
				'value'     => wfMsg('showlivepreview'),
				'accesskey' => wfMsg('accesskey-preview'),
				'title'     => '',
				'onclick'   => $this->doLivePreviewScript(),
			);
			$buttons['live'] = Xml::element('input', $temp, '');
		} else {
			$temp = array(
				'id'        => 'wpPreview',
				'name'      => 'wpPreview',
				'type'      => 'submit',
				'tabindex'  => $tabindex,
				'value'     => wfMsg('showpreview'),
				'accesskey' => wfMsg('accesskey-preview'),
				'title'     => wfMsg( 'tooltip-preview' ).' ['.wfMsg( 'accesskey-preview' ).']',
			);
			$buttons['preview'] = Xml::element('input', $temp, '');
			$buttons['live'] = '';
		}

		$temp = array(
			'id'        => 'wpDiff',
			'name'      => 'wpDiff',
			'type'      => 'submit',
			'tabindex'  => ++$tabindex,
			'value'     => wfMsg('showdiff'),
			'accesskey' => wfMsg('accesskey-diff'),
			'title'     => wfMsg( 'tooltip-diff' ).' ['.wfMsg( 'accesskey-diff' ).']',
		);
		$buttons['diff'] = Xml::element('input', $temp, '');

		wfRunHooks( 'EditPageBeforeEditButtons', array( &$this, &$buttons, &$tabindex ) );
		return $buttons;
	}

	/**
	 * Output preview text only. This can be sucked into the edit page
	 * via JavaScript, and saves the server time rendering the skin as
	 * well as theoretically being more robust on the client (doesn't
	 * disturb the edit box's undo history, won't eat your text on
	 * failure, etc).
	 *
	 * @todo This doesn't include category or interlanguage links.
	 *       Would need to enhance it a bit, <s>maybe wrap them in XML
	 *       or something...</s> that might also require more skin
	 *       initialization, so check whether that's a problem.
	 */
	function livePreview() {
		global $wgOut;
		$wgOut->disable();
		header( 'Content-type: text/xml; charset=utf-8' );
		header( 'Cache-control: no-cache' );

		$previewText = $this->getPreviewText();
		#$categories = $skin->getCategoryLinks();

		$s =
		'<?xml version="1.0" encoding="UTF-8" ?>' . "\n" .
		Xml::tags( 'livepreview', null,
			Xml::element( 'preview', null, $previewText )
			#.	Xml::element( 'category', null, $categories )
		);
		echo $s;
	}


	/**
	 * Get a diff between the current contents of the edit box and the
	 * version of the page we're editing from.
	 *
	 * If this is a section edit, we'll replace the section as for final
	 * save and then make a comparison.
	 */
	function showDiff() {
		$oldtext = $this->mArticle->fetchContent();
		$newtext = $this->mArticle->replaceSection(
			$this->section, $this->textbox1, $this->summary, $this->edittime );
		$newtext = $this->mArticle->preSaveTransform( $newtext );
		$oldtitle = wfMsgExt( 'currentrev', array('parseinline') );
		$newtitle = wfMsgExt( 'yourtext', array('parseinline') );
		if ( $oldtext !== false  || $newtext != '' ) {
			$de = new DifferenceEngine( $this->mTitle );
			$de->setText( $oldtext, $newtext );
			$difftext = $de->getDiff( $oldtitle, $newtitle );
			$de->showDiffStyle();
		} else {
			$difftext = '';
		}

		global $wgOut;
		$wgOut->addHTML( '<div id="wikiDiff">' . $difftext . '</div>' );
	}

	/**
	 * Filter an input field through a Unicode de-armoring process if it
	 * came from an old browser with known broken Unicode editing issues.
	 *
	 * @param WebRequest $request
	 * @param string $field
	 * @return string
	 * @private
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
	 * @private
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
	 * @private
	 */
	function makesafe( $invalue ) {
		// Armor existing references for reversability.
		$invalue = strtr( $invalue, array( "&#x" => "&#x0" ) );

		$bytesleft = 0;
		$result = "";
		$working = 0;
		for( $i = 0; $i < strlen( $invalue ); $i++ ) {
			$bytevalue = ord( $invalue{$i} );
			if ( $bytevalue <= 0x7F ) { //0xxx xxxx
				$result .= chr( $bytevalue );
				$bytesleft = 0;
			} elseif ( $bytevalue <= 0xBF ) { //10xx xxxx
				$working = $working << 6;
				$working += ($bytevalue & 0x3F);
				$bytesleft--;
				if ( $bytesleft <= 0 ) {
					$result .= "&#x" . strtoupper( dechex( $working ) ) . ";";
				}
			} elseif ( $bytevalue <= 0xDF ) { //110x xxxx
				$working = $bytevalue & 0x1F;
				$bytesleft = 1;
			} elseif ( $bytevalue <= 0xEF ) { //1110 xxxx
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
	 * @private
	 */
	function unmakesafe( $invalue ) {
		$result = "";
		for( $i = 0; $i < strlen( $invalue ); $i++ ) {
			if ( ( substr( $invalue, $i, 3 ) == "&#x" ) && ( $invalue{$i+3} != '0' ) ) {
				$i += 3;
				$hexstring = "";
				do {
					$hexstring .= $invalue{$i};
					$i++;
				} while( ctype_xdigit( $invalue{$i} ) && ( $i < strlen( $invalue ) ) );

				// Do some sanity checks. These aren't needed for reversability,
				// but should help keep the breakage down if the editor
				// breaks one of the entities whilst editing.
				if ( (substr($invalue,$i,1)==";") and (strlen($hexstring) <= 6) ) {
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

	function noCreatePermission() {
		global $wgOut;
		$wgOut->setPageTitle( wfMsg( 'nocreatetitle' ) );
		$wgOut->addWikiMsg( 'nocreatetext' );
	}

	/**
	 * If there are rows in the deletion log for this page, show them,
	 * along with a nice little note for the user
	 *
	 * @param OutputPage $out
	 */
	protected function showDeletionLog( $out ) {
		global $wgUser;
		$loglist = new LogEventsList( $wgUser->getSkin(), $out );
		$pager = new LogPager( $loglist, 'delete', false, $this->mTitle->getPrefixedText() );
		$count = $pager->getNumRows();
		if ( $count > 0 ) {
			$pager->mLimit = 10;
			$out->addHTML( '<div class="mw-warning-with-logexcerpt">' );
			$out->addWikiMsg( 'recreate-deleted-warn' );
			$out->addHTML(
				$loglist->beginLogEventsList() .
				$pager->getBody() .
				$loglist->endLogEventsList()
			);
			if($count > 10){
				$out->addHTML( $wgUser->getSkin()->link(
					SpecialPage::getTitleFor( 'Log' ),
					wfMsgHtml( 'deletelog-fulllog' ),
					array(),
					array(
						'type' => 'delete',
						'page' => $this->mTitle->getPrefixedText() ) ) );
			}
			$out->addHTML( '</div>' );
			return true;
		}
		
		return false;
	}

	/**
	 * Attempt submission
	 * @return bool false if output is done, true if the rest of the form should be displayed
	 */
	function attemptSave() {
		global $wgUser, $wgOut, $wgTitle, $wgRequest;

		$resultDetails = false;
		# Allow bots to exempt some edits from bot flagging
		$bot = $wgUser->isAllowed('bot') && $wgRequest->getBool('bot',true);
		$value = $this->internalAttemptSave( $resultDetails, $bot );

		if ( $value == self::AS_SUCCESS_UPDATE || $value == self::AS_SUCCESS_NEW_ARTICLE ) {
			$this->didSave = true;
		}

		switch ($value) {
			case self::AS_HOOK_ERROR_EXPECTED:
			case self::AS_CONTENT_TOO_BIG:
		 	case self::AS_ARTICLE_WAS_DELETED:
			case self::AS_CONFLICT_DETECTED:
			case self::AS_SUMMARY_NEEDED:
			case self::AS_TEXTBOX_EMPTY:
			case self::AS_MAX_ARTICLE_SIZE_EXCEEDED:
			case self::AS_END:
				return true;

			case self::AS_HOOK_ERROR:
			case self::AS_FILTERING:
			case self::AS_SUCCESS_NEW_ARTICLE:
			case self::AS_SUCCESS_UPDATE:
				return false;

			case self::AS_SPAM_ERROR:
				$this->spamPage ( $resultDetails['spam'] );
				return false;

			case self::AS_BLOCKED_PAGE_FOR_USER:
				$this->blockedPage();
				return false;

			case self::AS_IMAGE_REDIRECT_ANON:
				$wgOut->showErrorPage( 'uploadnologin', 'uploadnologintext' );
				return false;

			case self::AS_READ_ONLY_PAGE_ANON:
				$this->userNotLoggedInPage();
				return false;

		 	case self::AS_READ_ONLY_PAGE_LOGGED:
		 	case self::AS_READ_ONLY_PAGE:
		 		$wgOut->readOnlyPage();
		 		return false;

		 	case self::AS_RATE_LIMITED:
		 		$wgOut->rateLimited();
		 		return false;

		 	case self::AS_NO_CREATE_PERMISSION;
		 		$this->noCreatePermission();
		 		return;

			case self::AS_BLANK_ARTICLE:
		 		$wgOut->redirect( $wgTitle->getFullURL() );
		 		return false;

			case self::AS_IMAGE_REDIRECT_LOGGED:
				$wgOut->permissionRequired( 'upload' );
				return false;
		}
	}
	
	function getBaseRevision() {
		if ( $this->mBaseRevision == false ) {
			$db = wfGetDB( DB_MASTER );
			$baseRevision = Revision::loadFromTimestamp(
				$db, $this->mTitle, $this->edittime );
			return $this->mBaseRevision = $baseRevision;
		} else {
			return $this->mBaseRevision;
		}
	}
}
