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
	const AS_SUCCESS_UPDATE            = 200;
	const AS_SUCCESS_NEW_ARTICLE       = 201;
	const AS_HOOK_ERROR                = 210;
	const AS_FILTERING                 = 211;
	const AS_HOOK_ERROR_EXPECTED       = 212;
	const AS_BLOCKED_PAGE_FOR_USER     = 215;
	const AS_CONTENT_TOO_BIG           = 216;
	const AS_USER_CANNOT_EDIT          = 217;
	const AS_READ_ONLY_PAGE_ANON       = 218;
	const AS_READ_ONLY_PAGE_LOGGED     = 219;
	const AS_READ_ONLY_PAGE            = 220;
	const AS_RATE_LIMITED              = 221;
	const AS_ARTICLE_WAS_DELETED       = 222;
	const AS_NO_CREATE_PERMISSION      = 223;
	const AS_BLANK_ARTICLE             = 224;
	const AS_CONFLICT_DETECTED         = 225;
	const AS_SUMMARY_NEEDED            = 226;
	const AS_TEXTBOX_EMPTY             = 228;
	const AS_MAX_ARTICLE_SIZE_EXCEEDED = 229;
	const AS_OK                        = 230;
	const AS_END                       = 231;
	const AS_SPAM_ERROR                = 232;
	const AS_IMAGE_REDIRECT_ANON       = 233;
	const AS_IMAGE_REDIRECT_LOGGED     = 234;

	var $mArticle;
	var $mTitle;
	var $action;
	var $isConflict = false;
	var $isCssJsSubpage = false;
	var $isCssSubpage = false;
	var $isJsSubpage = false;
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
	var $mShowSummaryField = true;

	# Form values
	var $save = false, $preview = false, $diff = false;
	var $minoredit = false, $watchthis = false, $recreate = false;
	var $textbox1 = '', $textbox2 = '', $summary = '', $nosummary = false;
	var $edittime = '', $section = '', $starttime = '';
	var $oldid = 0, $editintro = '', $scrolltop = null, $bot = true;

	# Placeholders for text injection by hooks (must be HTML)
	# extensions should take care to _append_ to the present value
	public $editFormPageTop; // Before even the preview
	public $editFormTextTop;
	public $editFormTextBeforeContent;
	public $editFormTextAfterWarn;
	public $editFormTextAfterTools;
	public $editFormTextBottom;
	public $editFormTextAfterContent;
	public $previewTextAfterContent;

	/* $didSave should be set to true whenever an article was succesfully altered. */
	public $didSave = false;
	public $undidRev = 0;

	public $suppressIntro = false;

	/**
	 * @todo document
	 * @param $article
	 */
	function __construct( $article ) {
		$this->mArticle =& $article;
		$this->mTitle = $article->getTitle();
		$this->action = 'submit';

		# Placeholders for text injection by hooks (empty per default)
		$this->editFormPageTop =
		$this->editFormTextTop =
		$this->editFormTextBeforeContent =
		$this->editFormTextAfterWarn =
		$this->editFormTextAfterTools =
		$this->editFormTextBottom =
		$this->editFormTextAfterContent =
		$this->previewTextAfterContent =
		$this->mPreloadText = "";
	}

	function getArticle() {
		return $this->mArticle;
	}


	/**
	 * Fetch initial editing page content.
	 * @returns mixed string on success, $def_text for invalid sections
	 * @private
	 */
	function getContent( $def_text = '' ) {
		global $wgOut, $wgRequest, $wgParser, $wgContLang, $wgMessageCache;

		wfProfileIn( __METHOD__ );
		# Get variables from query string :P
		$section = $wgRequest->getVal( 'section' );

		$preload = $wgRequest->getVal( 'preload',
			// Custom preload text for new sections
			$section === 'new' ? 'MediaWiki:addsection-preload' : '' );
		$undoafter = $wgRequest->getVal( 'undoafter' );
		$undo = $wgRequest->getVal( 'undo' );

		// For message page not locally set, use the i18n message.
		// For other non-existent articles, use preload text if any.
		if ( !$this->mTitle->exists() ) {
			if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
				# If this is a system message, get the default text.
				list( $message, $lang ) = $wgMessageCache->figureMessage( $wgContLang->lcfirst( $this->mTitle->getText() ) );
				$text = wfMsgGetKey( $message, false, $lang, false );
				if( wfEmptyMsg( $message, $text ) )
					$text = $this->getPreloadedText( $preload );
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
					$undorev = Revision::newFromId( $undo );
					$oldrev = Revision::newFromId( $undoafter );
				} else {
					$undorev = Revision::newFromId( $undo );
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
					// Get section edit text (returns $def_text for invalid sections)
					$text = $wgParser->getSection( $text, $section, $def_text );
				}
			}
		}

		wfProfileOut( __METHOD__ );
		return $text;
	}

	/** Use this method before edit() to preload some text into the edit box */
	public function setPreloadedText( $text ) {
		$this->mPreloadText = $text;
	}

	/**
	 * Get the contents to be preloaded into the box, either set by
	 * an earlier setPreloadText() or by loading the given page.
	 *
	 * @param $preload String: representing the title to preload from.
	 * @return String
	 */
	protected function getPreloadedText( $preload ) {
		global $wgUser, $wgParser;
		if ( !empty( $this->mPreloadText ) ) {
			return $this->mPreloadText;
		} elseif ( $preload !== '' ) {
			$title = Title::newFromText( $preload );
			# Check for existence to avoid getting MediaWiki:Noarticletext
			if ( isset( $title ) && $title->exists() && $title->userCanRead() ) {
				$article = new Article( $title );

				if ( $article->isRedirect() ) {
					$title = Title::newFromRedirectRecurse( $article->getContent() );
					# Redirects to missing titles are displayed, to hidden pages are followed
					# Copying observed behaviour from ?action=view
					if ( $title->exists() ) {
						if ($title->userCanRead() ) {
							$article = new Article( $title );
						} else {
							return "";
						}
					}
				}
				$parserOptions = ParserOptions::newFromUser( $wgUser );
				return $wgParser->getPreloadText( $article->getContent(), $title, $parserOptions );
			}
		}
		return '';
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
		if ( $this->mTitle->isDeletedQuick() ) {
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
		global $wgOut, $wgRequest, $wgUser;
		// Allow extensions to modify/prevent this form or submission
		if ( !wfRunHooks( 'AlternateEdit', array( $this ) ) ) {
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

		$wgOut->addModules( 'mediawiki.legacy.edit' );

		if ( $wgUser->getOption( 'uselivepreview', false ) ) {
			$wgOut->addModules( 'mediawiki.legacy.preview' );
		}

		$permErrors = $this->getEditPermissionErrors();
		if ( $permErrors ) {
			wfDebug( __METHOD__ . ": User can't edit\n" );
			$content = $this->getContent( null );
			$content = $content === '' ? null : $content;
			$this->readOnlyPage( $content, true, $permErrors, 'edit' );
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
					$this->formtype = 'initial';
				}
			}
		}

		// If they used redlink=1 and the page exists, redirect to the main article
		if ( $wgRequest->getBool( 'redlink' ) && $this->mTitle->exists() ) {
			$wgOut->redirect( $this->mTitle->getFullURL() );
		}

		wfProfileIn( __METHOD__."-business-end" );

		$this->isConflict = false;
		// css / js subpages of user pages get a special treatment
		$this->isCssJsSubpage      = $this->mTitle->isCssJsSubpage();
		$this->isCssSubpage        = $this->mTitle->isCssSubpage();
		$this->isJsSubpage         = $this->mTitle->isJsSubpage();
		$this->isValidCssJsSubpage = $this->mTitle->isValidCssJsSubpage();

		# Show applicable editing introductions
		if ( $this->formtype == 'initial' || $this->firsttime )
			$this->showIntro();

		if ( $this->mTitle->isTalkPage() ) {
			$wgOut->addWikiMsg( 'talkpagetext' );
		}

		# Optional notices on a per-namespace and per-page basis
		$editnotice_ns   = 'editnotice-'.$this->mTitle->getNamespace();
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
			if ( $this->initialiseForm() === false ) {
				$this->noSuchSectionPage();
				wfProfileOut( __METHOD__."-business-end" );
				wfProfileOut( __METHOD__ );
				return;
			}
			if ( !$this->mTitle->getArticleId() )
				wfRunHooks( 'EditFormPreloadText', array( &$this->textbox1, &$this->mTitle ) );
			else
				wfRunHooks( 'EditFormInitialText', array( $this ) );
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
			if ( ( $this->preview || $this->diff ) &&
				( $error[0] == 'blockedtext' || $error[0] == 'autoblockedtext' ) )
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
		global $wgRequest, $wgUser, $wgPreviewOnOpenNamespaces;
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
		} elseif ( !$this->mTitle->exists() &&
		  isset($wgPreviewOnOpenNamespaces[$this->mTitle->getNamespace()]) &&
		  $wgPreviewOnOpenNamespaces[$this->mTitle->getNamespace()] )
		{
			// Categories are special
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Does this EditPage class support section editing?
	 * This is used by EditPage subclasses to indicate their ui cannot handle section edits
	 *
	 * @return bool
	 */
	protected function isSectionEditSupported() {
		return true;
	}

	/**
	 * Returns the URL to use in the form's action attribute.
	 * This is used by EditPage subclasses when simply customizing the action
	 * variable in the constructor is not enough. This can be used when the
	 * EditPage lives inside of a Special page rather than a custom page action.
	 *
	 * @param $title Title object for which is being edited (where we go to for &action= links)
	 * @return string
	 */
	protected function getActionURL( Title $title ) {
		return $title->getLocalURL( array( 'action' => $this->action ) );
	}

	/**
	 * @todo document
	 * @param $request
	 */
	function importFormData( &$request ) {
		global $wgLang, $wgUser;

		wfProfileIn( __METHOD__ );

		# Section edit can come from either the form or a link
		$this->section = $request->getVal( 'wpSection', $request->getVal( 'section' ) );

		if ( $request->wasPosted() ) {
			# These fields need to be checked for encoding.
			# Also remove trailing whitespace, but don't remove _initial_
			# whitespace from the text boxes. This may be significant formatting.
			$this->textbox1 = $this->safeUnicodeInput( $request, 'wpTextbox1' );
			if ( !$request->getCheck('wpTextbox2') ) {
				// Skip this if wpTextbox2 has input, it indicates that we came
				// from a conflict page with raw page text, not a custom form
				// modified by subclasses
				wfProfileIn( get_class($this)."::importContentFormData" );
				$textbox1 = $this->importContentFormData( $request );
				if ( isset($textbox1) )
					$this->textbox1 = $textbox1;
				wfProfileOut( get_class($this)."::importContentFormData" );
			}

			# Truncate for whole multibyte characters. +5 bytes for ellipsis
			$this->summary = $wgLang->truncate( $request->getText( 'wpSummary' ), 250, '' );

			# Remove extra headings from summaries and new sections.
			$this->summary = preg_replace('/^\s*=+\s*(.*?)\s*=+\s*$/', '$1', $this->summary);

			$this->edittime = $request->getVal( 'wpEdittime' );
			$this->starttime = $request->getVal( 'wpStarttime' );

			$this->scrolltop = $request->getIntOrNull( 'wpScrolltop' );

			if ( is_null( $this->edittime ) ) {
				# If the form is incomplete, force to preview.
				wfDebug( __METHOD__ . ": Form data appears to be incomplete\n" );
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
					wfDebug( __METHOD__ . ": Passed token check.\n" );
				} else if ( $this->diff ) {
					# Failed token check, but only requested "Show Changes".
					wfDebug( __METHOD__ . ": Failed token check; Show Changes requested.\n" );
				} else {
					# Page might be a hack attempt posted from
					# an external site. Preview instead of saving.
					wfDebug( __METHOD__ . ": Failed token check; forcing preview\n" );
					$this->preview = true;
				}
			}
			$this->save = !$this->preview && !$this->diff;
			if ( !preg_match( '/^\d{14}$/', $this->edittime ) ) {
				$this->edittime = null;
			}

			if ( !preg_match( '/^\d{14}$/', $this->starttime ) ) {
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
			wfDebug( __METHOD__ . ": Not a posted form.\n" );
			$this->textbox1  = '';
			$this->summary   = '';
			$this->edittime  = '';
			$this->starttime = wfTimestampNow();
			$this->edit      = false;
			$this->preview   = false;
			$this->save      = false;
			$this->diff      = false;
			$this->minoredit = false;
			$this->watchthis = $request->getBool( 'watchthis', false ); // Watch may be overriden by request parameters
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

		$this->bot = $request->getBool( 'bot', true );
		$this->nosummary = $request->getBool( 'nosummary' );

		// FIXME: unused variable?
		$this->oldid = $request->getInt( 'oldid' );

		$this->live = $request->getCheck( 'live' );
		$this->editintro = $request->getText( 'editintro',
			// Custom edit intro for new sections
			$this->section === 'new' ? 'MediaWiki:addsection-editintro' : '' );

		wfProfileOut( __METHOD__ );

		// Allow extensions to modify form data
		wfRunHooks( 'EditPage::importFormData', array( $this, $request ) );
	}

	/**
	 * Subpage overridable method for extracting the page content data from the
	 * posted form to be placed in $this->textbox1, if using customized input
	 * this method should be overrided and return the page text that will be used
	 * for saving, preview parsing and so on...
	 *
	 * @param $request WebRequest
	 */
	protected function importContentFormData( &$request ) {
		return; // Don't do anything, EditPage already extracted wpTextbox1
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
			$wgOut->wrapWikiMsg( "<div class='mw-editinginterface'>\n$1\n</div>", 'editinginterface' );
		}

		# Show a warning message when someone creates/edits a user (talk) page but the user does not exist
		# Show log extract when the user is currently blocked
		if ( $namespace == NS_USER || $namespace == NS_USER_TALK ) {
			$parts = explode( '/', $this->mTitle->getText(), 2 );
			$username = $parts[0];
			$user = User::newFromName( $username, false /* allow IP users*/ );
			$ip = User::isIP( $username );
			if ( !$user->isLoggedIn() && !$ip ) { # User does not exist
				$wgOut->wrapWikiMsg( "<div class=\"mw-userpage-userdoesnotexist error\">\n$1\n</div>",
					array( 'userpage-userdoesnotexist', $username ) );
			} else if ( $user->isBlocked() ) { # Show log extract if the user is currently blocked
				LogEventsList::showLogExtract(
					$wgOut,
					'block',
					$user->getUserPage()->getPrefixedText(),
					'',
					array(
						'lim' => 1,
						'showIfEmpty' => false,
						'msgKey' => array(
							'blocked-notice-logextract',
							$user->getName() # Support GENDER in notice
						)
					)
				);
			}
		}
		# Try to add a custom edit intro, or use the standard one if this is not possible.
		if ( !$this->showCustomIntro() && !$this->mTitle->exists() ) {
			if ( $wgUser->isLoggedIn() ) {
				$wgOut->wrapWikiMsg( "<div class=\"mw-newarticletext\">\n$1\n</div>", 'newarticletext' );
			} else {
				$wgOut->wrapWikiMsg( "<div class=\"mw-newarticletextanon\">\n$1\n</div>", 'newarticletextanon' );
			}
		}
		# Give a notice if the user is editing a deleted/moved page...
		if ( !$this->mTitle->exists() ) {
			LogEventsList::showLogExtract( $wgOut, array( 'delete', 'move' ), $this->mTitle->getPrefixedText(),
				'', array( 'lim' => 10,
					   'conds' => array( "log_action != 'revision'" ),
					   'showIfEmpty' => false,
					   'msgKey' => array( 'recreate-moveddeleted-warn') )
			);
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
		global $wgFilterCallback, $wgUser, $wgParser;
		global $wgMaxArticleSize;

		wfProfileIn( __METHOD__  );
		wfProfileIn( __METHOD__ . '-checks' );

		if ( !wfRunHooks( 'EditPage::attemptSave', array( $this ) ) ) {
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

		# Check for spam
		$match = self::matchSummarySpamRegex( $this->summary );
		if ( $match === false ) {
			$match = self::matchSpamRegex( $this->textbox1 );
		}
		if ( $match !== false ) {
			$result['spam'] = $match;
			$ip = wfGetIP();
			$pdbk = $this->mTitle->getPrefixedDBkey();
			$match = str_replace( "\n", '', $match );
			wfDebugLog( 'SpamRegex', "$ip spam regex hit [[$pdbk]]: \"$match\"" );
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return self::AS_SPAM_ERROR;
		}
		if ( $wgFilterCallback && $wgFilterCallback( $this->mTitle, $this->textbox1, $this->section, $this->hookError, $this->summary ) ) {
			# Error messages or other handling should be performed by the filter function
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return self::AS_FILTERING;
		}
		if ( !wfRunHooks( 'EditFilter', array( $this, $this->textbox1, $this->section, &$this->hookError, $this->summary ) ) ) {
			# Error messages etc. could be handled within the hook...
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return self::AS_HOOK_ERROR;
		} elseif ( $this->hookError != '' ) {
			# ...or the hook could be expecting us to produce an error
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return self::AS_HOOK_ERROR_EXPECTED;
		}
		if ( $wgUser->isBlockedFrom( $this->mTitle, false ) ) {
			# Check block state against master, thus 'false'.
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return self::AS_BLOCKED_PAGE_FOR_USER;
		}
		$this->kblength = (int)( strlen( $this->textbox1 ) / 1024 );
		if ( $this->kblength > $wgMaxArticleSize ) {
			// Error will be displayed by showEditForm()
			$this->tooBig = true;
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return self::AS_CONTENT_TOO_BIG;
		}

		if ( !$wgUser->isAllowed( 'edit' ) ) {
			if ( $wgUser->isAnon() ) {
				wfProfileOut( __METHOD__ . '-checks' );
				wfProfileOut( __METHOD__ );
				return self::AS_READ_ONLY_PAGE_ANON;
			} else {
				wfProfileOut( __METHOD__ . '-checks' );
				wfProfileOut( __METHOD__ );
				return self::AS_READ_ONLY_PAGE_LOGGED;
			}
		}

		if ( wfReadOnly() ) {
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return self::AS_READ_ONLY_PAGE;
		}
		if ( $wgUser->pingLimiter() ) {
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return self::AS_RATE_LIMITED;
		}

		# If the article has been deleted while editing, don't save it without
		# confirmation
		if ( $this->wasDeletedSinceLastEdit() && !$this->recreate ) {
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return self::AS_ARTICLE_WAS_DELETED;
		}

		wfProfileOut( __METHOD__ . '-checks' );

		# If article is new, insert it.
		$aid = $this->mTitle->getArticleID( Title::GAID_FOR_UPDATE );
		if ( 0 == $aid ) {
			// Late check for create permission, just in case *PARANOIA*
			if ( !$this->mTitle->userCan( 'create' ) ) {
				wfDebug( __METHOD__ . ": no create permission\n" );
				wfProfileOut( __METHOD__ );
				return self::AS_NO_CREATE_PERMISSION;
			}

			# Don't save a new article if it's blank.
			if ( $this->textbox1 == '' ) {
				wfProfileOut( __METHOD__ );
				return self::AS_BLANK_ARTICLE;
			}

			// Run post-section-merge edit filter
			if ( !wfRunHooks( 'EditFilterMerged', array( $this, $this->textbox1, &$this->hookError, $this->summary ) ) ) {
				# Error messages etc. could be handled within the hook...
				wfProfileOut( __METHOD__ );
				return self::AS_HOOK_ERROR;
			} elseif ( $this->hookError != '' ) {
				# ...or the hook could be expecting us to produce an error
				wfProfileOut( __METHOD__ );
				return self::AS_HOOK_ERROR_EXPECTED;
			}

			# Handle the user preference to force summaries here. Check if it's not a redirect.
			if ( !$this->allowBlankSummary && !Title::newFromRedirect( $this->textbox1 ) ) {
				if ( md5( $this->summary ) == $this->autoSumm ) {
					$this->missingSummary = true;
					wfProfileOut( __METHOD__ );
					return self::AS_SUMMARY_NEEDED;
				}
			}

			$isComment = ( $this->section == 'new' );

			$this->mArticle->insertNewArticle( $this->textbox1, $this->summary,
				$this->minoredit, $this->watchthis, false, $isComment, $bot );

			wfProfileOut( __METHOD__ );
			return self::AS_SUCCESS_NEW_ARTICLE;
		}

		# Article exists. Check for edit conflict.

		$this->mArticle->clear(); # Force reload of dates, etc.
		$this->mArticle->forUpdate( true ); # Lock the article

		wfDebug( "timestamp: {$this->mArticle->getTimestamp()}, edittime: {$this->edittime}\n" );

		if ( $this->mArticle->getTimestamp() != $this->edittime ) {
			$this->isConflict = true;
			if ( $this->section == 'new' ) {
				if ( $this->mArticle->getUserText() == $wgUser->getName() &&
					$this->mArticle->getComment() == $this->summary ) {
					// Probably a duplicate submission of a new comment.
					// This can happen when squid resends a request after
					// a timeout but the first one actually went through.
					wfDebug( __METHOD__ . ": duplicate new section submission; trigger edit conflict!\n" );
				} else {
					// New comment; suppress conflict.
					$this->isConflict = false;
					wfDebug( __METHOD__ .": conflict suppressed; new section\n" );
				}
			}
		}
		$userid = $wgUser->getId();

		# Suppress edit conflict with self, except for section edits where merging is required.
		if ( $this->isConflict && $this->section == '' && $this->userWasLastToEdit( $userid, $this->edittime ) ) {
			wfDebug( __METHOD__ . ": Suppressing edit conflict, same user.\n" );
			$this->isConflict = false;
		}

		if ( $this->isConflict ) {
			wfDebug( __METHOD__ . ": conflict! getting section '$this->section' for time '$this->edittime' (article time '" .
				$this->mArticle->getTimestamp() . "')\n" );
			$text = $this->mArticle->replaceSection( $this->section, $this->textbox1, $this->summary, $this->edittime );
		} else {
			wfDebug( __METHOD__ . ": getting section '$this->section'\n" );
			$text = $this->mArticle->replaceSection( $this->section, $this->textbox1, $this->summary );
		}
		if ( is_null( $text ) ) {
			wfDebug( __METHOD__ . ": activating conflict; section replace failed.\n" );
			$this->isConflict = true;
			$text = $this->textbox1; // do not try to merge here!
		} else if ( $this->isConflict ) {
			# Attempt merge
			if ( $this->mergeChangesInto( $text ) ) {
				// Successful merge! Maybe we should tell the user the good news?
				$this->isConflict = false;
				wfDebug( __METHOD__ . ": Suppressing edit conflict, successful merge.\n" );
			} else {
				$this->section = '';
				$this->textbox1 = $text;
				wfDebug( __METHOD__ . ": Keeping edit conflict, failed merge.\n" );
			}
		}

		if ( $this->isConflict ) {
			wfProfileOut( __METHOD__ );
			return self::AS_CONFLICT_DETECTED;
		}

		$oldtext = $this->mArticle->getContent();

		// Run post-section-merge edit filter
		if ( !wfRunHooks( 'EditFilterMerged', array( $this, $text, &$this->hookError, $this->summary ) ) ) {
			# Error messages etc. could be handled within the hook...
			wfProfileOut( __METHOD__ );
			return self::AS_HOOK_ERROR;
		} elseif ( $this->hookError != '' ) {
			# ...or the hook could be expecting us to produce an error
			wfProfileOut( __METHOD__ );
			return self::AS_HOOK_ERROR_EXPECTED;
		}

		# Handle the user preference to force summaries here, but not for null edits
		if ( $this->section != 'new' && !$this->allowBlankSummary && 0 != strcmp( $oldtext, $text )
			&& !Title::newFromRedirect( $text ) ) # check if it's not a redirect
		{
			if ( md5( $this->summary ) == $this->autoSumm ) {
				$this->missingSummary = true;
				wfProfileOut( __METHOD__ );
				return self::AS_SUMMARY_NEEDED;
			}
		}

		# And a similar thing for new sections
		if ( $this->section == 'new' && !$this->allowBlankSummary ) {
			if ( trim( $this->summary ) == '' ) {
				$this->missingSummary = true;
				wfProfileOut( __METHOD__ );
				return self::AS_SUMMARY_NEEDED;
			}
		}

		# All's well
		wfProfileIn( __METHOD__ . '-sectionanchor' );
		$sectionanchor = '';
		if ( $this->section == 'new' ) {
			if ( $this->textbox1 == '' ) {
				$this->missingComment = true;
				wfProfileOut( __METHOD__ . '-sectionanchor' );
				wfProfileOut( __METHOD__ );
				return self::AS_TEXTBOX_EMPTY;
			}
			if ( $this->summary != '' ) {
				$sectionanchor = $wgParser->guessLegacySectionNameFromWikiText( $this->summary );
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
			if ( $hasmatch and strlen( $matches[2] ) > 0 ) {
				$sectionanchor = $wgParser->guessLegacySectionNameFromWikiText( $matches[2] );
			}
		}
		wfProfileOut( __METHOD__ . '-sectionanchor' );

		// Save errors may fall down to the edit form, but we've now
		// merged the section into full text. Clear the section field
		// so that later submission of conflict forms won't try to
		// replace that into a duplicated mess.
		$this->textbox1 = $text;
		$this->section = '';

		// Check for length errors again now that the section is merged in
		$this->kblength = (int)( strlen( $text ) / 1024 );
		if ( $this->kblength > $wgMaxArticleSize ) {
			$this->tooBig = true;
			wfProfileOut( __METHOD__ );
			return self::AS_MAX_ARTICLE_SIZE_EXCEEDED;
		}

		# update the article here
		if ( $this->mArticle->updateArticle( $text, $this->summary, $this->minoredit,
			$this->watchthis, $bot, $sectionanchor ) )
		{
			wfProfileOut( __METHOD__ );
			return self::AS_SUCCESS_UPDATE;
		} else {
			$this->isConflict = true;
		}
		wfProfileOut( __METHOD__ );
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
		foreach ( $res as $row ) {
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
		// For back compatibility, $wgSpamRegex may be a single string or an array of regexes.
		$regexes = (array)$wgSpamRegex;
		return self::matchSpamRegexInternal( $text, $regexes );
	}

	/**
	 * Check given input text against $wgSpamRegex, and return the text of the first match.
	 * @return mixed -- matching string or false
	 */
	public static function matchSummarySpamRegex( $text ) {
		global $wgSummarySpamRegex;
		$regexes = (array)$wgSummarySpamRegex;
		return self::matchSpamRegexInternal( $text, $regexes );
	}

	protected static function matchSpamRegexInternal( $text, $regexes ) {
		foreach( $regexes as $regex ) {
			$matches = array();
			if( preg_match( $regex, $text, $matches ) ) {
				return $matches[0];
			}
		}
		return false;
	}

	/**
	 * Initialise form fields in the object
	 * Called on the first invocation, e.g. when a user clicks an edit link
	 * @returns bool -- if the requested section is valid
	 */
	function initialiseForm() {
		global $wgUser;
		$this->edittime = $this->mArticle->getTimestamp();
		$this->textbox1 = $this->getContent( false );
		// activate checkboxes if user wants them to be always active
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
		if ( $wgUser->getOption( 'minordefault' ) ) $this->minoredit = true;
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
			if ( isset( $this->mParserOutput )
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
	function showEditForm( $formCallback = null ) {
		global $wgOut, $wgUser, $wgTitle;

		# If $wgTitle is null, that means we're in API mode.
		# Some hook probably called this function  without checking
		# for is_null($wgTitle) first. Bail out right here so we don't
		# do lots of work just to discard it right after.
		if ( is_null( $wgTitle ) )
			return;

		wfProfileIn( __METHOD__ );

		$sk = $wgUser->getSkin();

		#need to parse the preview early so that we know which templates are used,
		#otherwise users with "show preview after edit box" will get a blank list
		#we parse this near the beginning so that setHeaders can do the title
		#setting work instead of leaving it in getPreviewText
		$previewOutput = '';
		if ( $this->formtype == 'preview' ) {
			$previewOutput = $this->getPreviewText();
		}

		wfRunHooks( 'EditPage::showEditForm:initial', array( &$this ) );

		$this->setHeaders();

		# Enabled article-related sidebar, toplinks, etc.
		$wgOut->setArticleRelated( true );

		if ( $this->showHeader() === false )
			return;

		$action = htmlspecialchars($this->getActionURL($wgTitle));

		if ( $wgUser->getOption( 'showtoolbar' ) and !$this->isCssJsSubpage ) {
			# prepare toolbar for edit buttons
			$toolbar = EditPage::getEditToolbar();
		} else {
			$toolbar = '';
		}


		$wgOut->addHTML( $this->editFormPageTop );

		if ( $wgUser->getOption( 'previewontop' ) ) {
			$this->displayPreviewArea( $previewOutput, true );
		}

		$wgOut->addHTML( $this->editFormTextTop );

		$templates = $this->getTemplates();
		$formattedtemplates = $sk->formatTemplates( $templates, $this->preview, $this->section != '');

		$hiddencats = $this->mArticle->getHiddenCategories();
		$formattedhiddencats = $sk->formatHiddenCategories( $hiddencats );

		if ( $this->wasDeletedSinceLastEdit() && 'save' != $this->formtype ) {
			$wgOut->wrapWikiMsg(
				"<div class='error mw-deleted-while-editing'>\n$1\n</div>",
				'deletedwhileediting' );
		} elseif ( $this->wasDeletedSinceLastEdit() ) {
			// Hide the toolbar and edit area, user can click preview to get it back
			// Add an confirmation checkbox and explanation.
			$toolbar = '';
			// @todo move this to a cleaner conditional instead of blanking a variable
		}
		$wgOut->addHTML( <<<HTML
{$toolbar}
<form id="editform" name="editform" method="post" action="$action" enctype="multipart/form-data">
HTML
);

		if ( is_callable( $formCallback ) ) {
			call_user_func_array( $formCallback, array( &$wgOut ) );
		}

		wfRunHooks( 'EditPage::showEditForm:fields', array( &$this, &$wgOut ) );

		// Put these up at the top to ensure they aren't lost on early form submission
		$this->showFormBeforeText();

		if ( $this->wasDeletedSinceLastEdit() && 'save' == $this->formtype ) {
			$wgOut->addHTML(
				'<div class="mw-confirm-recreate">' .
				$wgOut->parse( wfMsg( 'confirmrecreate',  $this->lastDelete->user_name , $this->lastDelete->log_comment ) ) .
				Xml::checkLabel( wfMsg( 'recreate' ), 'wpRecreate', 'wpRecreate', false,
					array( 'title' => $sk->titleAttrib( 'recreate' ), 'tabindex' => 1, 'id' => 'wpRecreate' )
				) .
				'</div>'
			);
		}

		# If a blank edit summary was previously provided, and the appropriate
		# user preference is active, pass a hidden tag as wpIgnoreBlankSummary. This will stop the
		# user being bounced back more than once in the event that a summary
		# is not required.
		#####
		# For a bit more sophisticated detection of blank summaries, hash the
		# automatic one and pass that in the hidden field wpAutoSummary.
		if ( $this->missingSummary ||
			( $this->section == 'new' && $this->nosummary ) )
				$wgOut->addHTML( Html::hidden( 'wpIgnoreBlankSummary', true ) );
		$autosumm = $this->autoSumm ? $this->autoSumm : md5( $this->summary );
		$wgOut->addHTML( Html::hidden( 'wpAutoSummary', $autosumm ) );

		$wgOut->addHTML( Html::hidden( 'oldid', $this->mArticle->getOldID() ) );

		if ( $this->section == 'new' ) {
			$this->showSummaryInput( true, $this->summary );
			$wgOut->addHTML( $this->getSummaryPreview( true, $this->summary ) );
		}

		$wgOut->addHTML( $this->editFormTextBeforeContent );

		if ( $this->isConflict ) {
			// In an edit conflict bypass the overrideable content form method
			// and fallback to the raw wpTextbox1 since editconflicts can't be
			// resolved between page source edits and custom ui edits using the
			// custom edit ui.
			$this->showTextbox1( null, $this->getContent() );
		} else {
			$this->showContentForm();
		}

		$wgOut->addHTML( $this->editFormTextAfterContent );

		$wgOut->addWikiText( $this->getCopywarn() );
		if ( isset($this->editFormTextAfterWarn) && $this->editFormTextAfterWarn !== '' )
			$wgOut->addHTML( $this->editFormTextAfterWarn );

		$this->showStandardInputs();

		$this->showFormAfterText();

		$this->showTosSummary();
		$this->showEditTools();

		$wgOut->addHTML( <<<HTML
{$this->editFormTextAfterTools}
<div class='templatesUsed'>
{$formattedtemplates}
</div>
<div class='hiddencats'>
{$formattedhiddencats}
</div>
HTML
);

		if ( $this->isConflict )
			$this->showConflict();

		$wgOut->addHTML( $this->editFormTextBottom );
		$wgOut->addHTML( "</form>\n" );
		if ( !$wgUser->getOption( 'previewontop' ) ) {
			$this->displayPreviewArea( $previewOutput, false );
		}

		wfProfileOut( __METHOD__ );
	}

	protected function showHeader() {
		global $wgOut, $wgUser, $wgTitle, $wgMaxArticleSize, $wgLang;
		if ( $this->isConflict ) {
			$wgOut->wrapWikiMsg( "<div class='mw-explainconflict'>\n$1\n</div>", 'explainconflict' );
			$this->edittime = $this->mArticle->getTimestamp();
		} else {
			if ( $this->section != '' && !$this->isSectionEditSupported() ) {
				// We use $this->section to much before this and getVal('wgSection') directly in other places
				// at this point we can't reset $this->section to '' to fallback to non-section editing.
				// Someone is welcome to try refactoring though
				$wgOut->showErrorPage( 'sectioneditnotsupported-title', 'sectioneditnotsupported-text' );
				return false;
			}

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
				$wgOut->wrapWikiMsg( "<div id='mw-missingcommenttext'>\n$1\n</div>", 'missingcommenttext' );
			}

			if ( $this->missingSummary && $this->section != 'new' ) {
				$wgOut->wrapWikiMsg( "<div id='mw-missingsummary'>\n$1\n</div>", 'missingsummary' );
			}

			if ( $this->missingSummary && $this->section == 'new' ) {
				$wgOut->wrapWikiMsg( "<div id='mw-missingcommentheader'>\n$1\n</div>", 'missingcommentheader' );
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
					$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n", 'rev-deleted-text-permission' );
				} else if ( $this->mArticle->mRevision->isDeleted( Revision::DELETED_TEXT ) ) {
					$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n", 'rev-deleted-text-view' );
				}

				if ( !$this->mArticle->mRevision->isCurrent() ) {
					$this->mArticle->setOldSubtitle( $this->mArticle->mRevision->getId() );
					$wgOut->addWikiMsg( 'editingold' );
				}
			}
		}

		if ( wfReadOnly() ) {
			$wgOut->wrapWikiMsg( "<div id=\"mw-read-only-warning\">\n$1\n</div>", array( 'readonlywarning', wfReadOnlyReason() ) );
		} elseif ( $wgUser->isAnon() ) {
			if ( $this->formtype != 'preview' ) {
				$wgOut->wrapWikiMsg( "<div id=\"mw-anon-edit-warning\">\n$1</div>", 'anoneditwarning' );
			} else {
				$wgOut->wrapWikiMsg( "<div id=\"mw-anon-preview-warning\">\n$1</div>", 'anonpreviewwarning' );
			}
		} else {
			if ( $this->isCssJsSubpage ) {
				# Check the skin exists
				if ( !$this->isValidCssJsSubpage ) {
					$wgOut->wrapWikiMsg( "<div class='error' id='mw-userinvalidcssjstitle'>\n$1\n</div>", array( 'userinvalidcssjstitle', $wgTitle->getSkinFromCssJsSubpage() ) );
				}
				if ( $this->formtype !== 'preview' ) {
					if ( $this->isCssSubpage )
						$wgOut->wrapWikiMsg( "<div id='mw-usercssyoucanpreview'>\n$1\n</div>", array( 'usercssyoucanpreview' ) );
					if ( $this->isJsSubpage )
						$wgOut->wrapWikiMsg( "<div id='mw-userjsyoucanpreview'>\n$1\n</div>", array( 'userjsyoucanpreview' ) );
				}
			}
		}

		if ( $this->mTitle->getNamespace() != NS_MEDIAWIKI && $this->mTitle->isProtected( 'edit' ) ) {
			# Is the title semi-protected?
			if ( $this->mTitle->isSemiProtected() ) {
				$noticeMsg = 'semiprotectedpagewarning';
			} else {
				# Then it must be protected based on static groups (regular)
				$noticeMsg = 'protectedpagewarning';
			}
			LogEventsList::showLogExtract( $wgOut, 'protect', $this->mTitle->getPrefixedText(), '',
				array( 'lim' => 1, 'msgKey' => array( $noticeMsg ) ) );
		}
		if ( $this->mTitle->isCascadeProtected() ) {
			# Is this page under cascading protection from some source pages?
			list($cascadeSources, /* $restrictions */) = $this->mTitle->getCascadeProtectionSources();
			$notice = "<div class='mw-cascadeprotectedwarning'>\n$1\n";
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
			LogEventsList::showLogExtract( $wgOut, 'protect', $this->mTitle->getPrefixedText(), '',
				array(  'lim' => 1,
					'showIfEmpty' => false,
					'msgKey' => array( 'titleprotectedwarning' ),
					'wrap' => "<div class=\"mw-titleprotectedwarning\">\n$1</div>" ) );
		}

		if ( $this->kblength === false ) {
			$this->kblength = (int)( strlen( $this->textbox1 ) / 1024 );
		}

		if ( $this->tooBig || $this->kblength > $wgMaxArticleSize ) {
			$wgOut->wrapWikiMsg( "<div class='error' id='mw-edit-longpageerror'>\n$1\n</div>",
				array( 'longpageerror', $wgLang->formatNum( $this->kblength ), $wgLang->formatNum( $wgMaxArticleSize ) ) );
		} else {
			$msg = 'longpage-hint';
			$text = wfMsg( $msg );
			if( !wfEmptyMsg( $msg, $text ) && $text !== '-' ) {
				$wgOut->wrapWikiMsg( "<div id='mw-edit-longpage-hint'>\n$1\n</div>",
					array( 'longpage-hint', $wgLang->formatSize( strlen( $this->textbox1 ) ), strlen( $this->textbox1 ) )
				);
			}
		}
	}

	/**
	 * Standard summary input and label (wgSummary), abstracted so EditPage
	 * subclasses may reorganize the form.
	 * Note that you do not need to worry about the label's for=, it will be
	 * inferred by the id given to the input. You can remove them both by
	 * passing array( 'id' => false ) to $userInputAttrs.
	 *
	 * @param $summary The value of the summary input
	 * @param $labelText The html to place inside the label
	 * @param $inputAttrs An array of attrs to use on the input
	 * @param $spanLabelAttrs An array of attrs to use on the span inside the label
	 *
	 * @return array An array in the format array( $label, $input )
	 */
	function getSummaryInput($summary = "", $labelText = null, $inputAttrs = null, $spanLabelAttrs = null) {
		global $wgUser;
		//Note: the maxlength is overriden in JS to 250 and to make it use UTF-8 bytes, not characters.
		$inputAttrs = ( is_array($inputAttrs) ? $inputAttrs : array() ) + array(
			'id' => 'wpSummary',
			'maxlength' => '200',
			'tabindex' => '1',
			'size' => 60,
			'spellcheck' => 'true',
		) + $wgUser->getSkin()->tooltipAndAccessKeyAttribs( 'summary' );

		$spanLabelAttrs = ( is_array($spanLabelAttrs) ? $spanLabelAttrs : array() ) + array(
			'class' => $this->missingSummary ? 'mw-summarymissed' : 'mw-summary',
			'id' => "wpSummaryLabel"
		);

		$label = null;
		if ( $labelText ) {
			$label = Xml::tags( 'label', $inputAttrs['id'] ? array( 'for' => $inputAttrs['id'] ) : null, $labelText );
			$label = Xml::tags( 'span', $spanLabelAttrs, $label );
		}

		$input = Html::input( 'wpSummary', $summary, 'text', $inputAttrs );

		return array( $label, $input );
	}

	/**
	 * @param $isSubjectPreview Boolean: true if this is the section subject/title
	 *                          up top, or false if this is the comment summary
	 *                          down below the textarea
	 * @param $summary String: The text of the summary to display
	 * @return String
	 */
	protected function showSummaryInput( $isSubjectPreview, $summary = "" ) {
		global $wgOut, $wgContLang;
		# Add a class if 'missingsummary' is triggered to allow styling of the summary line
		$summaryClass = $this->missingSummary ? 'mw-summarymissed' : 'mw-summary';
		if ( $isSubjectPreview ) {
			if ( $this->nosummary )
				return;
		} else {
			if ( !$this->mShowSummaryField )
				return;
		}
		$summary = $wgContLang->recodeForEdit( $summary );
		$labelText = wfMsgExt( $isSubjectPreview ? 'subject' : 'summary', 'parseinline' );
		list($label, $input) = $this->getSummaryInput($summary, $labelText, array( 'class' => $summaryClass ), array());
		$wgOut->addHTML("{$label} {$input}");
	}

	/**
	 * @param $isSubjectPreview Boolean: true if this is the section subject/title
	 *                          up top, or false if this is the comment summary
	 *                          down below the textarea
	 * @param $summary String: the text of the summary to display
	 * @return String
	 */
	protected function getSummaryPreview( $isSubjectPreview, $summary = "" ) {
		if ( !$summary || ( !$this->preview && !$this->diff ) )
			return "";

		global $wgParser, $wgUser;
		$sk = $wgUser->getSkin();

		if ( $isSubjectPreview )
			$summary = wfMsgForContent( 'newsectionsummary', $wgParser->stripSectionName( $summary ) );

		$message = $isSubjectPreview ? 'subject-preview' : 'summary-preview';

		$summary = wfMsgExt( $message, 'parseinline' ) . $sk->commentBlock( $summary, $this->mTitle, $isSubjectPreview );
		return Xml::tags( 'div', array( 'class' => 'mw-summary-preview' ), $summary );
	}

	protected function showFormBeforeText() {
		global $wgOut;
		$section = htmlspecialchars( $this->section );
		$wgOut->addHTML( <<<HTML
<input type='hidden' value="{$section}" name="wpSection" />
<input type='hidden' value="{$this->starttime}" name="wpStarttime" />
<input type='hidden' value="{$this->edittime}" name="wpEdittime" />
<input type='hidden' value="{$this->scrolltop}" name="wpScrolltop" id="wpScrolltop" />

HTML
		);
		if ( !$this->checkUnicodeCompliantBrowser() )
			$wgOut->addHTML(Html::hidden( 'safemode', '1' ));
	}

	protected function showFormAfterText() {
		global $wgOut, $wgUser;
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
		$wgOut->addHTML( "\n" . Html::hidden( "wpEditToken", $wgUser->editToken() ) . "\n" );
	}

	/**
	 * Subpage overridable method for printing the form for page content editing
	 * By default this simply outputs wpTextbox1
	 * Subclasses can override this to provide a custom UI for editing;
	 * be it a form, or simply wpTextbox1 with a modified content that will be
	 * reverse modified when extracted from the post data.
	 * Note that this is basically the inverse for importContentFormData
	 */
	protected function showContentForm() {
		$this->showTextbox1();
	}

	/**
	 * Method to output wpTextbox1
	 * The $textoverride method can be used by subclasses overriding showContentForm
	 * to pass back to this method.
	 *
	 * @param $customAttribs An array of html attributes to use in the textarea
	 * @param $textoverride String: optional text to override $this->textarea1 with
	 */
	protected function showTextbox1($customAttribs = null, $textoverride = null) {
		$classes = array(); // Textarea CSS
		if ( $this->mTitle->getNamespace() != NS_MEDIAWIKI && $this->mTitle->isProtected( 'edit' ) ) {
			# Is the title semi-protected?
			if ( $this->mTitle->isSemiProtected() ) {
				$classes[] = 'mw-textarea-sprotected';
			} else {
				# Then it must be protected based on static groups (regular)
				$classes[] = 'mw-textarea-protected';
			}
		}
		$attribs = array( 'tabindex' => 1 );
		if ( is_array($customAttribs) )
			$attribs += $customAttribs;

		if ( $this->wasDeletedSinceLastEdit() )
			$attribs['type'] = 'hidden';
		if ( !empty( $classes ) ) {
			if ( isset($attribs['class']) )
				$classes[] = $attribs['class'];
			$attribs['class'] = implode( ' ', $classes );
		}

		$this->showTextbox( isset($textoverride) ? $textoverride : $this->textbox1, 'wpTextbox1', $attribs );
	}

	protected function showTextbox2() {
		$this->showTextbox( $this->textbox2, 'wpTextbox2', array( 'tabindex' => 6 ) );
	}

	protected function showTextbox( $content, $name, $customAttribs = array() ) {
		global $wgOut, $wgUser;

		$wikitext = $this->safeUnicodeOutput( $content );
		if ( $wikitext !== '' ) {
			// Ensure there's a newline at the end, otherwise adding lines
			// is awkward.
			// But don't add a newline if the ext is empty, or Firefox in XHTML
			// mode will show an extra newline. A bit annoying.
			$wikitext .= "\n";
		}

		$attribs = $customAttribs + array(
			'accesskey' => ',',
			'id'   => $name,
			'cols' => $wgUser->getIntOption( 'cols' ),
			'rows' => $wgUser->getIntOption( 'rows' ),
			'style' => '' // avoid php notices when appending preferences (appending allows customAttribs['style'] to still work
		);

		$wgOut->addHTML( Html::textarea( $name, $wikitext, $attribs ) );
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
	 * @param $text String: the HTML to be output for the preview.
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
	 * Give a chance for site and per-namespace customizations of
	 * terms of service summary link that might exist separately
	 * from the copyright notice.
	 *
	 * This will display between the save button and the edit tools,
	 * so should remain short!
	 */
	protected function showTosSummary() {
		$msg = 'editpage-tos-summary';
		wfRunHooks( 'EditPageTosSummary', array( $this->mTitle, &$msg ) );
		$text = wfMsg( $msg );
		if( !wfEmptyMsg( $msg, $text ) && $text !== '-' ) {
			global $wgOut;
			$wgOut->addHTML( '<div class="mw-tos-summary">' );
			$wgOut->addWikiMsgArray( $msg, array() );
			$wgOut->addHTML( '</div>' );
		}
	}

	protected function showEditTools() {
		global $wgOut;
		$wgOut->addHTML( '<div class="mw-editTools">' );
		$wgOut->addWikiMsgArray( 'edittools', array(), array( 'content' ) );
		$wgOut->addHTML( '</div>' );
	}

	protected function getCopywarn() {
		global $wgRightsText;
		if ( $wgRightsText ) {
			$copywarnMsg = array( 'copyrightwarning',
				'[[' . wfMsgForContent( 'copyrightpage' ) . ']]',
				$wgRightsText );
		} else {
			$copywarnMsg = array( 'copyrightwarning2',
				'[[' . wfMsgForContent( 'copyrightpage' ) . ']]' );
		}
		// Allow for site and per-namespace customization of contribution/copyright notice.
		wfRunHooks( 'EditPageCopyrightWarning', array( $this->mTitle, &$copywarnMsg ) );

		return "<div id=\"editpage-copywarn\">\n" . call_user_func_array("wfMsgNoTrans", $copywarnMsg) . "\n</div>";
	}

	protected function showStandardInputs( &$tabindex = 2 ) {
		global $wgOut, $wgUser;
		$wgOut->addHTML( "<div class='editOptions'>\n" );

		if ( $this->section != 'new' ) {
			$this->showSummaryInput( false, $this->summary );
			$wgOut->addHTML( $this->getSummaryPreview( false, $this->summary ) );
		}

		$checkboxes = $this->getCheckboxes( $tabindex, $wgUser->getSkin(),
			array( 'minor' => $this->minoredit, 'watch' => $this->watchthis ) );
		$wgOut->addHTML( "<div class='editCheckboxes'>" . implode( $checkboxes, "\n" ) . "</div>\n" );
		$wgOut->addHTML( "<div class='editButtons'>\n" );
		$wgOut->addHTML( implode( $this->getEditButtons( $tabindex ), "\n" ) . "\n" );

		$cancel = $this->getCancelLink();
		$separator = wfMsgExt( 'pipe-separator' , 'escapenoentities' );
		$edithelpurl = Skin::makeInternalOrExternalUrl( wfMsgForContent( 'edithelppage' ) );
		$edithelp = '<a target="helpwindow" href="'.$edithelpurl.'">'.
			htmlspecialchars( wfMsg( 'edithelp' ) ).'</a> '.
			htmlspecialchars( wfMsg( 'newwindow' ) );
		$wgOut->addHTML( "	<span class='editHelp'>{$cancel}{$separator}{$edithelp}</span>\n" );
		$wgOut->addHTML( "</div><!-- editButtons -->\n</div><!-- editOptions -->\n" );
	}

	/*
	 * Show an edit conflict. textbox1 is already shown in showEditForm().
	 * If you want to use another entry point to this function, be careful.
	 */
	protected function showConflict() {
		global $wgOut;
		$this->textbox2 = $this->textbox1;
		$this->textbox1 = $this->getContent();
		if ( wfRunHooks( 'EditPageBeforeConflictDiff', array( &$this, &$wgOut ) ) ) {
			$wgOut->wrapWikiMsg( '<h2>$1</h2>', "yourdiff" );

			$de = new DifferenceEngine( $this->mTitle );
			$de->setText( $this->textbox2, $this->textbox1 );
			$de->showDiff( wfMsg( "yourtext" ), wfMsg( "storedversion" ) );

			$wgOut->wrapWikiMsg( '<h2>$1</h2>', "yourtext" );
			$this->showTextbox2();
		}
	}

	protected function getLastDelete() {
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
			       'log_deleted',
			       'user_name' ),
			array( 'log_namespace' => $this->mTitle->getNamespace(),
			       'log_title' => $this->mTitle->getDBkey(),
			       'log_type' => 'delete',
			       'log_action' => 'delete',
			       'user_id=log_user' ),
			__METHOD__,
			array( 'LIMIT' => 1, 'ORDER BY' => 'log_timestamp DESC' )
		);
		// Quick paranoid permission checks...
		if( is_object( $data ) ) {
			if( $data->log_deleted & LogPage::DELETED_USER )
				$data->user_name = wfMsgHtml( 'rev-deleted-user' );
			if( $data->log_deleted & LogPage::DELETED_COMMENT )
				$data->log_comment = wfMsgHtml( 'rev-deleted-comment' );
		}
		return $data;
	}

	/**
	 * Get the rendered text for previewing.
	 * @return string
	 */
	function getPreviewText() {
		global $wgOut, $wgUser, $wgParser, $wgMessageCache;

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
			if (preg_match( "/\\.css$/", $this->mTitle->getText() ) ) {
				$previewtext = "<div id='mw-usercsspreview'>\n" . wfMsg( 'usercsspreview' ) . "\n</div>";
			} else if (preg_match( "/\\.js$/", $this->mTitle->getText() ) ) {
				$previewtext = "<div id='mw-userjspreview'>\n" . wfMsg( 'userjspreview' ) . "\n</div>";
			}
			$parserOptions->setTidy( true );
			$parserOutput = $wgParser->parse( $previewtext, $this->mTitle, $parserOptions );
			$previewHTML = $parserOutput->mText;
		} else {
			$rt = Title::newFromRedirectArray( $this->textbox1 );
			if ( $rt ) {
				$previewHTML = $this->mArticle->viewRedirect( $rt, false );
			} else {
				$toparse = $this->textbox1;

				# If we're adding a comment, we need to show the
				# summary as the headline
				if ( $this->section == "new" && $this->summary != "" ) {
					$toparse = "== {$this->summary} ==\n\n" . $toparse;
				}

				wfRunHooks( 'EditPageGetPreviewText', array( $this, &$toparse ) );

				// Parse mediawiki messages with correct target language
				if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
					list( /* $unused */, $lang ) = $wgMessageCache->figureMessage( $this->mTitle->getText() );
					$obj = wfGetLangObj( $lang );
					$parserOptions->setTargetLanguage( $obj );
				}

				$parserOptions->setTidy( true );
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
		}

		if( $this->isConflict ) {
			$conflict = '<h2 id="mw-previewconflict">' . htmlspecialchars( wfMsg( 'previewconflict' ) ) . "</h2>\n";
		} else {
			$conflict = '<hr />';
		}

		$previewhead = "<div class='previewnote'>\n" .
			'<h2 id="mw-previewheader">' . htmlspecialchars( wfMsg( 'preview' ) ) . "</h2>" .
			$wgOut->parse( $note ) . $conflict . "</div>\n";

		wfProfileOut( __METHOD__ );
		return $previewhead . $previewHTML . $this->previewTextAfterContent;
	}

	function getTemplates() {
		if ( $this->preview || $this->section != '' ) {
			$templates = array();
			if ( !isset( $this->mParserOutput ) ) return $templates;
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
		global $wgOut;
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
			$wgOut->addHTML( '<hr />' );
			$wgOut->addWikiMsg( $first ? 'blockedoriginalsource' : 'blockededitsource', $this->mTitle->getPrefixedText() );
			$this->showTextbox1( array( 'readonly' ), $source );
		}
	}

	/**
	 * Produce the stock "please login to edit pages" page
	 */
	function userNotLoggedInPage() {
		global $wgUser, $wgOut, $wgTitle;
		$skin = $wgUser->getSkin();

		$loginTitle = SpecialPage::getTitleFor( 'Userlogin' );
		$loginLink = $skin->link(
			$loginTitle,
			wfMsgHtml( 'loginreqlink' ),
			array(),
			array( 'returnto' => $wgTitle->getPrefixedText() ),
			array( 'known', 'noclasses' )
		);

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
		global $wgOut;

		$wgOut->setPageTitle( wfMsg( 'nosuchsectiontitle' ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );

		$res = wfMsgExt( 'nosuchsectiontext', 'parse', $this->section );
		wfRunHooks( 'EditPageNoSuchSection', array( &$this, &$res ) );
		$wgOut->addHTML( $res );

		$wgOut->returnToMain( false, $this->mTitle );
	}

	/**
	 * Produce the stock "your edit contains spam" page
	 *
	 * @param $match Text which triggered one or more filters
	 */
	static function spamPage( $match = false ) {
		global $wgOut, $wgTitle;

		$wgOut->setPageTitle( wfMsg( 'spamprotectiontitle' ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );

		$wgOut->addHTML( '<div id="spamprotected">' );
		$wgOut->addWikiMsg( 'spamprotectiontext' );
		if ( $match ) {
			$wgOut->addWikiMsg( 'spamprotectionmatch', wfEscapeWikiText( $match ) );
		}
		$wgOut->addHTML( '</div>' );

		$wgOut->returnToMain( false, $wgTitle );
	}

	/**
	 * @private
	 * @todo document
	 */
	function mergeChangesInto( &$editText ){
		wfProfileIn( __METHOD__ );

		$db = wfGetDB( DB_MASTER );

		// This is the revision the editor started from
		$baseRevision = $this->getBaseRevision();
		if ( is_null( $baseRevision ) ) {
			wfProfileOut( __METHOD__ );
			return false;
		}
		$baseText = $baseRevision->getText();

		// The current state, we want to merge updates into it
		$currentRevision = Revision::loadFromTitle( $db, $this->mTitle );
		if ( is_null( $currentRevision ) ) {
			wfProfileOut( __METHOD__ );
			return false;
		}
		$currentText = $currentRevision->getText();

		$result = '';
		if ( wfMerge( $baseText, $editText, $currentText, $result ) ) {
			$editText = $result;
			wfProfileOut( __METHOD__ );
			return true;
		} else {
			wfProfileOut( __METHOD__ );
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
	 * @param $text String
	 * @return String
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
		global $wgStylePath, $wgContLang, $wgLang, $wgOut;
		global $wgUseTeX, $wgEnableUploads, $wgForeignFileRepos;

		$imagesAvailable = $wgEnableUploads || count( $wgForeignFileRepos );

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
				'image'  => $wgLang->getImageFile( 'button-bold' ),
				'id'     => 'mw-editbutton-bold',
				'open'   => '\'\'\'',
				'close'  => '\'\'\'',
				'sample' => wfMsg( 'bold_sample' ),
				'tip'    => wfMsg( 'bold_tip' ),
				'key'    => 'B'
			),
			array(
				'image'  => $wgLang->getImageFile( 'button-italic' ),
				'id'     => 'mw-editbutton-italic',
				'open'   => '\'\'',
				'close'  => '\'\'',
				'sample' => wfMsg( 'italic_sample' ),
				'tip'    => wfMsg( 'italic_tip' ),
				'key'    => 'I'
			),
			array(
				'image'  => $wgLang->getImageFile( 'button-link' ),
				'id'     => 'mw-editbutton-link',
				'open'   => '[[',
				'close'  => ']]',
				'sample' => wfMsg( 'link_sample' ),
				'tip'    => wfMsg( 'link_tip' ),
				'key'    => 'L'
			),
			array(
				'image'  => $wgLang->getImageFile( 'button-extlink' ),
				'id'     => 'mw-editbutton-extlink',
				'open'   => '[',
				'close'  => ']',
				'sample' => wfMsg( 'extlink_sample' ),
				'tip'    => wfMsg( 'extlink_tip' ),
				'key'    => 'X'
			),
			array(
				'image'  => $wgLang->getImageFile( 'button-headline' ),
				'id'     => 'mw-editbutton-headline',
				'open'   => "\n== ",
				'close'  => " ==\n",
				'sample' => wfMsg( 'headline_sample' ),
				'tip'    => wfMsg( 'headline_tip' ),
				'key'    => 'H'
			),
			$imagesAvailable ? array(
				'image'  => $wgLang->getImageFile( 'button-image' ),
				'id'     => 'mw-editbutton-image',
				'open'   => '[[' . $wgContLang->getNsText( NS_FILE ) . ':',
				'close'  => ']]',
				'sample' => wfMsg( 'image_sample' ),
				'tip'    => wfMsg( 'image_tip' ),
				'key'    => 'D'
			) : false,
			$imagesAvailable ? array(
				'image'  => $wgLang->getImageFile( 'button-media' ),
				'id'     => 'mw-editbutton-media',
				'open'   => '[[' . $wgContLang->getNsText( NS_MEDIA ) . ':',
				'close'  => ']]',
				'sample' => wfMsg( 'media_sample' ),
				'tip'    => wfMsg( 'media_tip' ),
				'key'    => 'M'
			) : false,
			$wgUseTeX ?	array(
				'image'  => $wgLang->getImageFile( 'button-math' ),
				'id'     => 'mw-editbutton-math',
				'open'   => "<math>",
				'close'  => "</math>",
				'sample' => wfMsg( 'math_sample' ),
				'tip'    => wfMsg( 'math_tip' ),
				'key'    => 'C'
			) : false,
			array(
				'image'  => $wgLang->getImageFile( 'button-nowiki' ),
				'id'     => 'mw-editbutton-nowiki',
				'open'   => "<nowiki>",
				'close'  => "</nowiki>",
				'sample' => wfMsg( 'nowiki_sample' ),
				'tip'    => wfMsg( 'nowiki_tip' ),
				'key'    => 'N'
			),
			array(
				'image'  => $wgLang->getImageFile( 'button-sig' ),
				'id'     => 'mw-editbutton-signature',
				'open'   => '--~~~~',
				'close'  => '',
				'sample' => '',
				'tip'    => wfMsg( 'sig_tip' ),
				'key'    => 'Y'
			),
			array(
				'image'  => $wgLang->getImageFile( 'button-hr' ),
				'id'     => 'mw-editbutton-hr',
				'open'   => "\n----\n",
				'close'  => '',
				'sample' => '',
				'tip'    => wfMsg( 'hr_tip' ),
				'key'    => 'R'
			)
		);
		$toolbar = "<div id='toolbar'>\n";

		$script = '';
		foreach ( $toolarray as $tool ) {
			if ( !$tool ) {
				continue;
			}

			$params = array(
				$image = $wgStylePath . '/common/images/' . $tool['image'],
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
			$script .= "addButton($paramList);\n";
		}
		
		$wgOut->addScript( Html::inlineScript(
			"if ( window.mediaWiki ) { $script }"
		) );
		
		$toolbar .= "\n</div>";

		wfRunHooks( 'EditPageBeforeEditToolbar', array( &$toolbar ) );

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
		$minorLabel = wfMsgExt( 'minoredit', array( 'parseinline' ) );
		if ( $wgUser->isAllowed( 'minoredit' ) ) {
			$attribs = array(
				'tabindex'  => ++$tabindex,
				'accesskey' => wfMsg( 'accesskey-minoredit' ),
				'id'        => 'wpMinoredit',
			);
			$checkboxes['minor'] =
				Xml::check( 'wpMinoredit', $checked['minor'], $attribs ) .
				"&#160;<label for='wpMinoredit' id='mw-editpage-minoredit'" . $skin->titleAttrib( 'minoredit', 'withaccess' ) . ">{$minorLabel}</label>";
		}

		$watchLabel = wfMsgExt( 'watchthis', array( 'parseinline' ) );
		$checkboxes['watch'] = '';
		if ( $wgUser->isLoggedIn() ) {
			$attribs = array(
				'tabindex'  => ++$tabindex,
				'accesskey' => wfMsg( 'accesskey-watch' ),
				'id'        => 'wpWatchthis',
			);
			$checkboxes['watch'] =
				Xml::check( 'wpWatchthis', $checked['watch'], $attribs ) .
				"&#160;<label for='wpWatchthis' id='mw-editpage-watch'" . $skin->titleAttrib( 'watch', 'withaccess' ) . ">{$watchLabel}</label>";
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
	public function getEditButtons( &$tabindex ) {
		$buttons = array();

		$temp = array(
			'id'        => 'wpSave',
			'name'      => 'wpSave',
			'type'      => 'submit',
			'tabindex'  => ++$tabindex,
			'value'     => wfMsg( 'savearticle' ),
			'accesskey' => wfMsg( 'accesskey-save' ),
			'title'     => wfMsg( 'tooltip-save' ).' ['.wfMsg( 'accesskey-save' ).']',
		);
		$buttons['save'] = Xml::element('input', $temp, '');

		++$tabindex; // use the same for preview and live preview
		$temp = array(
			'id'        => 'wpPreview',
			'name'      => 'wpPreview',
			'type'      => 'submit',
			'tabindex'  => $tabindex,
			'value'     => wfMsg( 'showpreview' ),
			'accesskey' => wfMsg( 'accesskey-preview' ),
			'title'     => wfMsg( 'tooltip-preview' ) . ' [' . wfMsg( 'accesskey-preview' ) . ']',
		);
		$buttons['preview'] = Xml::element( 'input', $temp, '' );
		$buttons['live'] = '';

		$temp = array(
			'id'        => 'wpDiff',
			'name'      => 'wpDiff',
			'type'      => 'submit',
			'tabindex'  => ++$tabindex,
			'value'     => wfMsg( 'showdiff' ),
			'accesskey' => wfMsg( 'accesskey-diff' ),
			'title'     => wfMsg( 'tooltip-diff' ) . ' [' . wfMsg( 'accesskey-diff' ) . ']',
		);
		$buttons['diff'] = Xml::element( 'input', $temp, '' );

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


	public function getCancelLink() {
		global $wgUser, $wgTitle;

		$cancelParams = array();
		if ( !$this->isConflict && $this->mArticle->getOldID() > 0 ) {
			$cancelParams['oldid'] = $this->mArticle->getOldID();
		}

		return $wgUser->getSkin()->link(
			$wgTitle,
			wfMsgExt( 'cancel', array( 'parseinline' ) ),
			array( 'id' => 'mw-editform-cancel' ),
			$cancelParams,
			array( 'known', 'noclasses' )
		);
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

		wfRunHooks( 'EditPageGetDiffText', array( $this, &$newtext ) );

		$newtext = $this->mArticle->preSaveTransform( $newtext );
		$oldtitle = wfMsgExt( 'currentrev', array( 'parseinline' ) );
		$newtitle = wfMsgExt( 'yourtext', array( 'parseinline' ) );
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
	 * @param $request WebRequest
	 * @param $field String
	 * @return String
	 * @private
	 */
	function safeUnicodeInput( $request, $field ) {
		$text = rtrim( $request->getText( $field ) );
		return $request->getBool( 'safemode' )
			? $this->unmakesafe( $text )
			: $text;
	}

	function safeUnicodeText( $request, $text ) {
		$text = rtrim( $text );
		return $request->getBool( 'safemode' )
			? $this->unmakesafe( $text )
			: $text;
	}

	/**
	 * Filter an output field through a Unicode armoring process if it is
	 * going to an old browser with known broken Unicode editing issues.
	 *
	 * @param $text String
	 * @return String
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
	 * @param $invalue String
	 * @return String
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
	 * @param $invalue String
	 * @return String
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
	 * Attempt submission
	 * @return bool false if output is done, true if the rest of the form should be displayed
	 */
	function attemptSave() {
		global $wgUser, $wgOut, $wgTitle;

		$resultDetails = false;
		# Allow bots to exempt some edits from bot flagging
		$bot = $wgUser->isAllowed( 'bot' ) && $this->bot;
		$value = $this->internalAttemptSave( $resultDetails, $bot );

		if ( $value == self::AS_SUCCESS_UPDATE || $value == self::AS_SUCCESS_NEW_ARTICLE ) {
			$this->didSave = true;
		}

		switch ( $value ) {
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
				self::spamPage( $resultDetails['spam'] );
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

		 	case self::AS_NO_CREATE_PERMISSION:
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
		if ( !$this->mBaseRevision ) {
			$db = wfGetDB( DB_MASTER );
			$baseRevision = Revision::loadFromTimestamp(
				$db, $this->mTitle, $this->edittime );
			return $this->mBaseRevision = $baseRevision;
		} else {
			return $this->mBaseRevision;
		}
	}
}
