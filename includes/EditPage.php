<?php
/**
 * Page edition user interface.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * The edit page/HTML interface (split from Article)
 * The actual database and text munging is still in Article,
 * but it should get easier to call those from alternate
 * interfaces.
 *
 * EditPage cares about two distinct titles:
 * $this->mContextTitle is the page that forms submit to, links point to,
 * redirects go to, etc. $this->mTitle (as well as $mArticle) is the
 * page in the database that is actually being edited. These are
 * usually the same, but they are now allowed to be different.
 *
 * Surgeon General's Warning: prolonged exposure to this class is known to cause
 * headaches, which may be fatal.
 */
class EditPage {

	/**
	 * Status: Article successfully updated
	 */
	const AS_SUCCESS_UPDATE = 200;

	/**
	 * Status: Article successfully created
	 */
	const AS_SUCCESS_NEW_ARTICLE = 201;

	/**
	 * Status: Article update aborted by a hook function
	 */
	const AS_HOOK_ERROR = 210;

	/**
	 * Status: A hook function returned an error
	 */
	const AS_HOOK_ERROR_EXPECTED = 212;

	/**
	 * Status: User is blocked from editing this page
	 */
	const AS_BLOCKED_PAGE_FOR_USER = 215;

	/**
	 * Status: Content too big (> $wgMaxArticleSize)
	 */
	const AS_CONTENT_TOO_BIG = 216;

	/**
	 * Status: User cannot edit? (not used)
	 */
	const AS_USER_CANNOT_EDIT = 217;

	/**
	 * Status: this anonymous user is not allowed to edit this page
	 */
	const AS_READ_ONLY_PAGE_ANON = 218;

	/**
	 * Status: this logged in user is not allowed to edit this page
	 */
	const AS_READ_ONLY_PAGE_LOGGED = 219;

	/**
	 * Status: wiki is in readonly mode (wfReadOnly() == true)
	 */
	const AS_READ_ONLY_PAGE = 220;

	/**
	 * Status: rate limiter for action 'edit' was tripped
	 */
	const AS_RATE_LIMITED = 221;

	/**
	 * Status: article was deleted while editing and param wpRecreate == false or form
	 * was not posted
	 */
	const AS_ARTICLE_WAS_DELETED = 222;

	/**
	 * Status: user tried to create this page, but is not allowed to do that
	 * ( Title->userCan('create') == false )
	 */
	const AS_NO_CREATE_PERMISSION = 223;

	/**
	 * Status: user tried to create a blank page
	 */
	const AS_BLANK_ARTICLE = 224;

	/**
	 * Status: (non-resolvable) edit conflict
	 */
	const AS_CONFLICT_DETECTED = 225;

	/**
	 * Status: no edit summary given and the user has forceeditsummary set and the user is not
	 * editing in his own userspace or talkspace and wpIgnoreBlankSummary == false
	 */
	const AS_SUMMARY_NEEDED = 226;

	/**
	 * Status: user tried to create a new section without content
	 */
	const AS_TEXTBOX_EMPTY = 228;

	/**
	 * Status: article is too big (> $wgMaxArticleSize), after merging in the new section
	 */
	const AS_MAX_ARTICLE_SIZE_EXCEEDED = 229;

	/**
	 * not used
	 */
	const AS_OK = 230;

	/**
	 * Status: WikiPage::doEdit() was unsuccessful
	 */
	const AS_END = 231;

	/**
	 * Status: summary contained spam according to one of the regexes in $wgSummarySpamRegex
	 */
	const AS_SPAM_ERROR = 232;

	/**
	 * Status: anonymous user is not allowed to upload (User::isAllowed('upload') == false)
	 */
	const AS_IMAGE_REDIRECT_ANON = 233;

	/**
	 * Status: logged in user is not allowed to upload (User::isAllowed('upload') == false)
	 */
	const AS_IMAGE_REDIRECT_LOGGED = 234;

	/**
	 * Status: can't parse content
	 */
	const AS_PARSE_ERROR = 240;

	/**
	 * HTML id and name for the beginning of the edit form.
	 */
	const EDITFORM_ID = 'editform';

	/**
	 * Prefix of key for cookie used to pass post-edit state.
	 * The revision id edited is added after this
	 */
	const POST_EDIT_COOKIE_KEY_PREFIX = 'PostEditRevision';

	/**
	 * Duration of PostEdit cookie, in seconds.
	 * The cookie will be removed instantly if the JavaScript runs.
	 *
	 * Otherwise, though, we don't want the cookies to accumulate.
	 * RFC 2109 ( https://www.ietf.org/rfc/rfc2109.txt ) specifies a possible limit of only 20 cookies per domain.
	 * This still applies at least to some versions of IE without full updates:
	 * https://blogs.msdn.com/b/ieinternals/archive/2009/08/20/wininet-ie-cookie-internals-faq.aspx
	 *
	 * A value of 20 minutes should be enough to take into account slow loads and minor
	 * clock skew while still avoiding cookie accumulation when JavaScript is turned off.
	 */
	const POST_EDIT_COOKIE_DURATION = 1200;

	/**
	 * @var Article
	 */
	var $mArticle;

	/**
	 * @var Title
	 */
	var $mTitle;
	private $mContextTitle = null;
	var $action = 'submit';
	var $isConflict = false;
	var $isCssJsSubpage = false;
	var $isCssSubpage = false;
	var $isJsSubpage = false;
	var $isWrongCaseCssJsPage = false;
	var $isNew = false; // new page or new section
	var $deletedSinceEdit;
	var $formtype;
	var $firsttime;
	var $lastDelete;
	var $mTokenOk = false;
	var $mTokenOkExceptSuffix = false;
	var $mTriedSave = false;
	var $incompleteForm = false;
	var $tooBig = false;
	var $kblength = false;
	var $missingComment = false;
	var $missingSummary = false;
	var $allowBlankSummary = false;
	var $autoSumm = '';
	var $hookError = '';
	#var $mPreviewTemplates;

	/**
	 * @var ParserOutput
	 */
	var $mParserOutput;

	/**
	 * Has a summary been preset using GET parameter &summary= ?
	 * @var Bool
	 */
	var $hasPresetSummary = false;

	var $mBaseRevision = false;
	var $mShowSummaryField = true;

	# Form values
	var $save = false, $preview = false, $diff = false;
	var $minoredit = false, $watchthis = false, $recreate = false;
	var $textbox1 = '', $textbox2 = '', $summary = '', $nosummary = false;
	var $edittime = '', $section = '', $sectiontitle = '', $starttime = '';
	var $oldid = 0, $editintro = '', $scrolltop = null, $bot = true;
	var $contentModel = null, $contentFormat = null;

	# Placeholders for text injection by hooks (must be HTML)
	# extensions should take care to _append_ to the present value
	public $editFormPageTop = ''; // Before even the preview
	public $editFormTextTop = '';
	public $editFormTextBeforeContent = '';
	public $editFormTextAfterWarn = '';
	public $editFormTextAfterTools = '';
	public $editFormTextBottom = '';
	public $editFormTextAfterContent = '';
	public $previewTextAfterContent = '';
	public $mPreloadContent = null;

	/* $didSave should be set to true whenever an article was successfully altered. */
	public $didSave = false;
	public $undidRev = 0;

	public $suppressIntro = false;

	/**
	 * Set to true to allow editing of non-text content types.
	 *
	 * @var bool
	 */
	public $allowNonTextContent = false;

	/**
	 * @param $article Article
	 */
	public function __construct( Article $article ) {
		$this->mArticle = $article;
		$this->mTitle = $article->getTitle();

		$this->contentModel = $this->mTitle->getContentModel();

		$handler = ContentHandler::getForModelID( $this->contentModel );
		$this->contentFormat = $handler->getDefaultFormat();
	}

	/**
	 * @return Article
	 */
	public function getArticle() {
		return $this->mArticle;
	}

	/**
	 * @since 1.19
	 * @return Title
	 */
	public function getTitle() {
		return $this->mTitle;
	}

	/**
	 * Set the context Title object
	 *
	 * @param $title Title object or null
	 */
	public function setContextTitle( $title ) {
		$this->mContextTitle = $title;
	}

	/**
	 * Get the context title object.
	 * If not set, $wgTitle will be returned. This behavior might change in
	 * the future to return $this->mTitle instead.
	 *
	 * @return Title object
	 */
	public function getContextTitle() {
		if ( is_null( $this->mContextTitle ) ) {
			global $wgTitle;
			return $wgTitle;
		} else {
			return $this->mContextTitle;
		}
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
		wfDebug( __METHOD__ . ": enter\n" );

		// If they used redlink=1 and the page exists, redirect to the main article
		if ( $wgRequest->getBool( 'redlink' ) && $this->mTitle->exists() ) {
			$wgOut->redirect( $this->mTitle->getFullURL() );
			wfProfileOut( __METHOD__ );
			return;
		}

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

		if ( $this->save ) {
			$this->formtype = 'save';
		} elseif ( $this->preview ) {
			$this->formtype = 'preview';
		} elseif ( $this->diff ) {
			$this->formtype = 'diff';
		} else { # First time through
			$this->firsttime = true;
			if ( $this->previewOnOpen() ) {
				$this->formtype = 'preview';
			} else {
				$this->formtype = 'initial';
			}
		}

		$permErrors = $this->getEditPermissionErrors();
		if ( $permErrors ) {
			wfDebug( __METHOD__ . ": User can't edit\n" );
			// Auto-block user's IP if the account was "hard" blocked
			$wgUser->spreadAnyEditBlock();

			$this->displayPermissionsError( $permErrors );

			wfProfileOut( __METHOD__ );
			return;
		}

		wfProfileIn( __METHOD__ . "-business-end" );

		$this->isConflict = false;
		// css / js subpages of user pages get a special treatment
		$this->isCssJsSubpage = $this->mTitle->isCssJsSubpage();
		$this->isCssSubpage = $this->mTitle->isCssSubpage();
		$this->isJsSubpage = $this->mTitle->isJsSubpage();
		$this->isWrongCaseCssJsPage = $this->isWrongCaseCssJsPage();

		# Show applicable editing introductions
		if ( $this->formtype == 'initial' || $this->firsttime ) {
			$this->showIntro();
		}

		# Attempt submission here.  This will check for edit conflicts,
		# and redundantly check for locked database, blocked IPs, etc.
		# that edit() already checked just in case someone tries to sneak
		# in the back door with a hand-edited submission URL.

		if ( 'save' == $this->formtype ) {
			if ( !$this->attemptSave() ) {
				wfProfileOut( __METHOD__ . "-business-end" );
				wfProfileOut( __METHOD__ );
				return;
			}
		}

		# First time through: get contents, set time for conflict
		# checking, etc.
		if ( 'initial' == $this->formtype || $this->firsttime ) {
			if ( $this->initialiseForm() === false ) {
				$this->noSuchSectionPage();
				wfProfileOut( __METHOD__ . "-business-end" );
				wfProfileOut( __METHOD__ );
				return;
			}

			if ( !$this->mTitle->getArticleID() ) {
				wfRunHooks( 'EditFormPreloadText', array( &$this->textbox1, &$this->mTitle ) );
			} else {
				wfRunHooks( 'EditFormInitialText', array( $this ) );
			}

		}

		$this->showEditForm();
		wfProfileOut( __METHOD__ . "-business-end" );
		wfProfileOut( __METHOD__ );
	}

	/**
	 * @return array
	 */
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
		foreach ( $permErrors as $error ) {
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
	 * Display a permissions error page, like OutputPage::showPermissionsErrorPage(),
	 * but with the following differences:
	 * - If redlink=1, the user will be redirected to the page
	 * - If there is content to display or the error occurs while either saving,
	 *   previewing or showing the difference, it will be a
	 *   "View source for ..." page displaying the source code after the error message.
	 *
	 * @since 1.19
	 * @param array $permErrors of permissions errors, as returned by
	 *                    Title::getUserPermissionsErrors().
	 * @throws PermissionsError
	 */
	protected function displayPermissionsError( array $permErrors ) {
		global $wgRequest, $wgOut;

		if ( $wgRequest->getBool( 'redlink' ) ) {
			// The edit page was reached via a red link.
			// Redirect to the article page and let them click the edit tab if
			// they really want a permission error.
			$wgOut->redirect( $this->mTitle->getFullURL() );
			return;
		}

		$content = $this->getContentObject();

		# Use the normal message if there's nothing to display
		if ( $this->firsttime && ( !$content || $content->isEmpty() ) ) {
			$action = $this->mTitle->exists() ? 'edit' :
				( $this->mTitle->isTalkPage() ? 'createtalk' : 'createpage' );
			throw new PermissionsError( $action, $permErrors );
		}

		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setPageTitle( wfMessage( 'viewsource-title', $this->getContextTitle()->getPrefixedText() ) );
		$wgOut->addBacklinkSubtitle( $this->getContextTitle() );
		$wgOut->addWikiText( $wgOut->formatPermissionsErrorMessage( $permErrors, 'edit' ) );
		$wgOut->addHTML( "<hr />\n" );

		# If the user made changes, preserve them when showing the markup
		# (This happens when a user is blocked during edit, for instance)
		if ( !$this->firsttime ) {
			$text = $this->textbox1;
			$wgOut->addWikiMsg( 'viewyourtext' );
		} else {
			$text = $this->toEditText( $content );
			$wgOut->addWikiMsg( 'viewsourcetext' );
		}

		$this->showTextbox( $text, 'wpTextbox1', array( 'readonly' ) );

		$wgOut->addHTML( Html::rawElement( 'div', array( 'class' => 'templatesUsed' ),
			Linker::formatTemplates( $this->getTemplates() ) ) );

		$wgOut->addModules( 'mediawiki.action.edit.collapsibleFooter' );

		if ( $this->mTitle->exists() ) {
			$wgOut->returnToMain( null, $this->mTitle );
		}
	}

	/**
	 * Show a read-only error
	 * Parameters are the same as OutputPage:readOnlyPage()
	 * Redirect to the article page if redlink=1
	 * @deprecated in 1.19; use displayPermissionsError() instead
	 */
	function readOnlyPage( $source = null, $protected = false, $reasons = array(), $action = null ) {
		wfDeprecated( __METHOD__, '1.19' );

		global $wgRequest, $wgOut;
		if ( $wgRequest->getBool( 'redlink' ) ) {
			// The edit page was reached via a red link.
			// Redirect to the article page and let them click the edit tab if
			// they really want a permission error.
			$wgOut->redirect( $this->mTitle->getFullURL() );
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
			// Standard preference behavior
			return true;
		} elseif ( !$this->mTitle->exists() &&
			isset( $wgPreviewOnOpenNamespaces[$this->mTitle->getNamespace()] ) &&
			$wgPreviewOnOpenNamespaces[$this->mTitle->getNamespace()] )
		{
			// Categories are special
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Checks whether the user entered a skin name in uppercase,
	 * e.g. "User:Example/Monobook.css" instead of "monobook.css"
	 *
	 * @return bool
	 */
	protected function isWrongCaseCssJsPage() {
		if ( $this->mTitle->isCssJsSubpage() ) {
			$name = $this->mTitle->getSkinFromCssJsSubpage();
			$skins = array_merge(
				array_keys( Skin::getSkinNames() ),
				array( 'common' )
			);
			return !in_array( $name, $skins )
				&& in_array( strtolower( $name ), $skins );
		} else {
			return false;
		}
	}

	/**
	 * Returns whether section editing is supported for the current page.
	 * Subclasses may override this to replace the default behavior, which is
	 * to check ContentHandler::supportsSections.
	 *
	 * @return bool true if this edit page supports sections, false otherwise.
	 */
	protected function isSectionEditSupported() {
		$contentHandler = ContentHandler::getForTitle( $this->mTitle );
		return $contentHandler->supportsSections();
	}

	/**
	 * This function collects the form data and uses it to populate various member variables.
	 * @param $request WebRequest
	 */
	function importFormData( &$request ) {
		global $wgContLang, $wgUser;

		wfProfileIn( __METHOD__ );

		# Section edit can come from either the form or a link
		$this->section = $request->getVal( 'wpSection', $request->getVal( 'section' ) );

		if ( $this->section !== null && $this->section !== '' && !$this->isSectionEditSupported() ) {
			wfProfileOut( __METHOD__ );
			throw new ErrorPageError( 'sectioneditnotsupported-title', 'sectioneditnotsupported-text' );
		}

		$this->isNew = !$this->mTitle->exists() || $this->section == 'new';

		if ( $request->wasPosted() ) {
			# These fields need to be checked for encoding.
			# Also remove trailing whitespace, but don't remove _initial_
			# whitespace from the text boxes. This may be significant formatting.
			$this->textbox1 = $this->safeUnicodeInput( $request, 'wpTextbox1' );
			if ( !$request->getCheck( 'wpTextbox2' ) ) {
				// Skip this if wpTextbox2 has input, it indicates that we came
				// from a conflict page with raw page text, not a custom form
				// modified by subclasses
				wfProfileIn( get_class( $this ) . "::importContentFormData" );
				$textbox1 = $this->importContentFormData( $request );
				if ( $textbox1 !== null ) {
					$this->textbox1 = $textbox1;
				}

				wfProfileOut( get_class( $this ) . "::importContentFormData" );
			}

			# Truncate for whole multibyte characters
			$this->summary = $wgContLang->truncate( $request->getText( 'wpSummary' ), 255 );

			# If the summary consists of a heading, e.g. '==Foobar==', extract the title from the
			# header syntax, e.g. 'Foobar'. This is mainly an issue when we are using wpSummary for
			# section titles.
			$this->summary = preg_replace( '/^\s*=+\s*(.*?)\s*=+\s*$/', '$1', $this->summary );

			# Treat sectiontitle the same way as summary.
			# Note that wpSectionTitle is not yet a part of the actual edit form, as wpSummary is
			# currently doing double duty as both edit summary and section title. Right now this
			# is just to allow API edits to work around this limitation, but this should be
			# incorporated into the actual edit form when EditPage is rewritten (Bugs 18654, 26312).
			$this->sectiontitle = $wgContLang->truncate( $request->getText( 'wpSectionTitle' ), 255 );
			$this->sectiontitle = preg_replace( '/^\s*=+\s*(.*?)\s*=+\s*$/', '$1', $this->sectiontitle );

			$this->edittime = $request->getVal( 'wpEdittime' );
			$this->starttime = $request->getVal( 'wpStarttime' );

			$undidRev = $request->getInt( 'wpUndidRevision' );
			if ( $undidRev ) {
				$this->undidRev = $undidRev;
			}

			$this->scrolltop = $request->getIntOrNull( 'wpScrolltop' );

			if ( $this->textbox1 === '' && $request->getVal( 'wpTextbox1' ) === null ) {
				// wpTextbox1 field is missing, possibly due to being "too big"
				// according to some filter rules such as Suhosin's setting for
				// suhosin.request.max_value_length (d'oh)
				$this->incompleteForm = true;
			} else {
				// edittime should be one of our last fields; if it's missing,
				// the submission probably broke somewhere in the middle.
				$this->incompleteForm = is_null( $this->edittime );
			}
			if ( $this->incompleteForm ) {
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
				} elseif ( $this->diff ) {
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

			$this->recreate = $request->getCheck( 'wpRecreate' );

			$this->minoredit = $request->getCheck( 'wpMinoredit' );
			$this->watchthis = $request->getCheck( 'wpWatchthis' );

			# Don't force edit summaries when a user is editing their own user or talk page
			if ( ( $this->mTitle->mNamespace == NS_USER || $this->mTitle->mNamespace == NS_USER_TALK ) &&
				$this->mTitle->getText() == $wgUser->getName() )
			{
				$this->allowBlankSummary = true;
			} else {
				$this->allowBlankSummary = $request->getBool( 'wpIgnoreBlankSummary' ) || !$wgUser->getOption( 'forceeditsummary' );
			}

			$this->autoSumm = $request->getText( 'wpAutoSummary' );
		} else {
			# Not a posted form? Start with nothing.
			wfDebug( __METHOD__ . ": Not a posted form.\n" );
			$this->textbox1 = '';
			$this->summary = '';
			$this->sectiontitle = '';
			$this->edittime = '';
			$this->starttime = wfTimestampNow();
			$this->edit = false;
			$this->preview = false;
			$this->save = false;
			$this->diff = false;
			$this->minoredit = false;
			$this->watchthis = $request->getBool( 'watchthis', false ); // Watch may be overridden by request parameters
			$this->recreate = false;

			// When creating a new section, we can preload a section title by passing it as the
			// preloadtitle parameter in the URL (Bug 13100)
			if ( $this->section == 'new' && $request->getVal( 'preloadtitle' ) ) {
				$this->sectiontitle = $request->getVal( 'preloadtitle' );
				// Once wpSummary isn't being use for setting section titles, we should delete this.
				$this->summary = $request->getVal( 'preloadtitle' );
			}
			elseif ( $this->section != 'new' && $request->getVal( 'summary' ) ) {
				$this->summary = $request->getText( 'summary' );
				if ( $this->summary !== '' ) {
					$this->hasPresetSummary = true;
				}
			}

			if ( $request->getVal( 'minor' ) ) {
				$this->minoredit = true;
			}
		}

		$this->oldid = $request->getInt( 'oldid' );

		$this->bot = $request->getBool( 'bot', true );
		$this->nosummary = $request->getBool( 'nosummary' );

		$content_handler = ContentHandler::getForTitle( $this->mTitle );
		$this->contentModel = $request->getText( 'model', $content_handler->getModelID() ); #may be overridden by revision
		$this->contentFormat = $request->getText( 'format', $content_handler->getDefaultFormat() ); #may be overridden by revision

		#TODO: check if the desired model is allowed in this namespace, and if a transition from the page's current model to the new model is allowed
		#TODO: check if the desired content model supports the given content format!

		$this->live = $request->getCheck( 'live' );
		$this->editintro = $request->getText( 'editintro',
			// Custom edit intro for new sections
			$this->section === 'new' ? 'MediaWiki:addsection-editintro' : '' );

		// Allow extensions to modify form data
		wfRunHooks( 'EditPage::importFormData', array( $this, $request ) );

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Subpage overridable method for extracting the page content data from the
	 * posted form to be placed in $this->textbox1, if using customized input
	 * this method should be overridden and return the page text that will be used
	 * for saving, preview parsing and so on...
	 *
	 * @param $request WebRequest
	 */
	protected function importContentFormData( &$request ) {
		return; // Don't do anything, EditPage already extracted wpTextbox1
	}

	/**
	 * Initialise form fields in the object
	 * Called on the first invocation, e.g. when a user clicks an edit link
	 * @return bool -- if the requested section is valid
	 */
	function initialiseForm() {
		global $wgUser;
		$this->edittime = $this->mArticle->getTimestamp();

		$content = $this->getContentObject( false ); #TODO: track content object?!
		if ( $content === false ) {
			return false;
		}
		$this->textbox1 = $this->toEditText( $content );

		// activate checkboxes if user wants them to be always active
		# Sort out the "watch" checkbox
		if ( $wgUser->getOption( 'watchdefault' ) ) {
			# Watch all edits
			$this->watchthis = true;
		} elseif ( $wgUser->getOption( 'watchcreations' ) && !$this->mTitle->exists() ) {
			# Watch creations
			$this->watchthis = true;
		} elseif ( $wgUser->isWatched( $this->mTitle ) ) {
			# Already watched
			$this->watchthis = true;
		}
		if ( $wgUser->getOption( 'minordefault' ) && !$this->isNew ) {
			$this->minoredit = true;
		}
		if ( $this->textbox1 === false ) {
			return false;
		}
		return true;
	}

	/**
	 * Fetch initial editing page content.
	 *
	 * @param $def_text string|bool
	 * @return mixed string on success, $def_text for invalid sections
	 * @private
	 * @deprecated since 1.21, get WikiPage::getContent() instead.
	 */
	function getContent( $def_text = false ) {
		ContentHandler::deprecated( __METHOD__, '1.21' );

		if ( $def_text !== null && $def_text !== false && $def_text !== '' ) {
			$def_content = $this->toEditContent( $def_text );
		} else {
			$def_content = false;
		}

		$content = $this->getContentObject( $def_content );

		// Note: EditPage should only be used with text based content anyway.
		return $this->toEditText( $content );
	}

	/**
	 * @param Content|null $def_content The default value to return
	 *
	 * @return mixed Content on success, $def_content for invalid sections
	 *
	 * @since 1.21
	 */
	protected function getContentObject( $def_content = null ) {
		global $wgOut, $wgRequest;

		wfProfileIn( __METHOD__ );

		$content = false;

		// For message page not locally set, use the i18n message.
		// For other non-existent articles, use preload text if any.
		if ( !$this->mTitle->exists() || $this->section == 'new' ) {
			if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI && $this->section != 'new' ) {
				# If this is a system message, get the default text.
				$msg = $this->mTitle->getDefaultMessageText();

				$content = $this->toEditContent( $msg );
			}
			if ( $content === false ) {
				# If requested, preload some text.
				$preload = $wgRequest->getVal( 'preload',
					// Custom preload text for new sections
					$this->section === 'new' ? 'MediaWiki:addsection-preload' : '' );

				$content = $this->getPreloadedContent( $preload );
			}
		// For existing pages, get text based on "undo" or section parameters.
		} else {
			if ( $this->section != '' ) {
				// Get section edit text (returns $def_text for invalid sections)
				$orig = $this->getOriginalContent();
				$content = $orig ? $orig->getSection( $this->section ) : null;

				if ( !$content ) {
					$content = $def_content;
				}
			} else {
				$undoafter = $wgRequest->getInt( 'undoafter' );
				$undo = $wgRequest->getInt( 'undo' );

				if ( $undo > 0 && $undoafter > 0 ) {
					if ( $undo < $undoafter ) {
						# If they got undoafter and undo round the wrong way, switch them
						list( $undo, $undoafter ) = array( $undoafter, $undo );
					}

					$undorev = Revision::newFromId( $undo );
					$oldrev = Revision::newFromId( $undoafter );

					# Sanity check, make sure it's the right page,
					# the revisions exist and they were not deleted.
					# Otherwise, $content will be left as-is.
					if ( !is_null( $undorev ) && !is_null( $oldrev ) &&
						$undorev->getPage() == $oldrev->getPage() &&
						$undorev->getPage() == $this->mTitle->getArticleID() &&
						!$undorev->isDeleted( Revision::DELETED_TEXT ) &&
						!$oldrev->isDeleted( Revision::DELETED_TEXT ) ) {

						$content = $this->mArticle->getUndoContent( $undorev, $oldrev );

						if ( $content === false ) {
							# Warn the user that something went wrong
							$undoMsg = 'failure';
						} else {
							# Inform the user of our success and set an automatic edit summary
							$undoMsg = 'success';

							# If we just undid one rev, use an autosummary
							$firstrev = $oldrev->getNext();
							if ( $firstrev && $firstrev->getId() == $undo ) {
								$userText = $undorev->getUserText();
								if ( $userText === '' ) {
									$undoSummary = wfMessage(
										'undo-summary-username-hidden',
										$undo
									)->inContentLanguage()->text();
								} else {
									$undoSummary = wfMessage(
										'undo-summary',
										$undo,
										$userText
									)->inContentLanguage()->text();
								}
								if ( $this->summary === '' ) {
									$this->summary = $undoSummary;
								} else {
									$this->summary = $undoSummary . wfMessage( 'colon-separator' )
										->inContentLanguage()->text() . $this->summary;
								}
								$this->undidRev = $undo;
							}
							$this->formtype = 'diff';
						}
					} else {
						// Failed basic sanity checks.
						// Older revisions may have been removed since the link
						// was created, or we may simply have got bogus input.
						$undoMsg = 'norev';
					}

					// Messages: undo-success, undo-failure, undo-norev
					$class = ( $undoMsg == 'success' ? '' : 'error ' ) . "mw-undo-{$undoMsg}";
					$this->editFormPageTop .= $wgOut->parse( "<div class=\"{$class}\">" .
						wfMessage( 'undo-' . $undoMsg )->plain() . '</div>', true, /* interface */true );
				}

				if ( $content === false ) {
					$content = $this->getOriginalContent();
				}
			}
		}

		wfProfileOut( __METHOD__ );
		return $content;
	}

	/**
	 * Get the content of the wanted revision, without section extraction.
	 *
	 * The result of this function can be used to compare user's input with
	 * section replaced in its context (using WikiPage::replaceSection())
	 * to the original text of the edit.
	 *
	 * This differs from Article::getContent() that when a missing revision is
	 * encountered the result will be null and not the
	 * 'missing-revision' message.
	 *
	 * @since 1.19
	 * @return Content|null
	 */
	private function getOriginalContent() {
		if ( $this->section == 'new' ) {
			return $this->getCurrentContent();
		}
		$revision = $this->mArticle->getRevisionFetched();
		if ( $revision === null ) {
			if ( !$this->contentModel ) {
				$this->contentModel = $this->getTitle()->getContentModel();
			}
			$handler = ContentHandler::getForModelID( $this->contentModel );

			return $handler->makeEmptyContent();
		}
		$content = $revision->getContent();
		return $content;
	}

	/**
	 * Get the current content of the page. This is basically similar to
	 * WikiPage::getContent( Revision::RAW ) except that when the page doesn't exist an empty
	 * content object is returned instead of null.
	 *
	 * @since 1.21
	 * @return Content
	 */
	protected function getCurrentContent() {
		$rev = $this->mArticle->getRevision();
		$content = $rev ? $rev->getContent( Revision::RAW ) : null;

		if ( $content === false || $content === null ) {
			if ( !$this->contentModel ) {
				$this->contentModel = $this->getTitle()->getContentModel();
			}
			$handler = ContentHandler::getForModelID( $this->contentModel );

			return $handler->makeEmptyContent();
		} else {
			# nasty side-effect, but needed for consistency
			$this->contentModel = $rev->getContentModel();
			$this->contentFormat = $rev->getContentFormat();

			return $content;
		}
	}

	/**
	 * Use this method before edit() to preload some text into the edit box
	 *
	 * @param $text string
	 * @deprecated since 1.21, use setPreloadedContent() instead.
	 */
	public function setPreloadedText( $text ) {
		ContentHandler::deprecated( __METHOD__, "1.21" );

		$content = $this->toEditContent( $text );

		$this->setPreloadedContent( $content );
	}

	/**
	 * Use this method before edit() to preload some content into the edit box
	 *
	 * @param $content Content
	 *
	 * @since 1.21
	 */
	public function setPreloadedContent( Content $content ) {
		$this->mPreloadContent = $content;
	}

	/**
	 * Get the contents to be preloaded into the box, either set by
	 * an earlier setPreloadText() or by loading the given page.
	 *
	 * @param string $preload representing the title to preload from.
	 *
	 * @return String
	 *
	 * @deprecated since 1.21, use getPreloadedContent() instead
	 */
	protected function getPreloadedText( $preload ) {
		ContentHandler::deprecated( __METHOD__, "1.21" );

		$content = $this->getPreloadedContent( $preload );
		$text = $this->toEditText( $content );

		return $text;
	}

	/**
	 * Get the contents to be preloaded into the box, either set by
	 * an earlier setPreloadText() or by loading the given page.
	 *
	 * @param string $preload representing the title to preload from.
	 *
	 * @return Content
	 *
	 * @since 1.21
	 */
	protected function getPreloadedContent( $preload ) {
		global $wgUser;

		if ( !empty( $this->mPreloadContent ) ) {
			return $this->mPreloadContent;
		}

		$handler = ContentHandler::getForTitle( $this->getTitle() );

		if ( $preload === '' ) {
			return $handler->makeEmptyContent();
		}

		$title = Title::newFromText( $preload );
		# Check for existence to avoid getting MediaWiki:Noarticletext
		if ( $title === null || !$title->exists() || !$title->userCan( 'read', $wgUser ) ) {
			//TODO: somehow show a warning to the user!
			return $handler->makeEmptyContent();
		}

		$page = WikiPage::factory( $title );
		if ( $page->isRedirect() ) {
			$title = $page->getRedirectTarget();
			# Same as before
			if ( $title === null || !$title->exists() || !$title->userCan( 'read', $wgUser ) ) {
				//TODO: somehow show a warning to the user!
				return $handler->makeEmptyContent();
			}
			$page = WikiPage::factory( $title );
		}

		$parserOptions = ParserOptions::newFromUser( $wgUser );
		$content = $page->getContent( Revision::RAW );

		if ( !$content ) {
			//TODO: somehow show a warning to the user!
			return $handler->makeEmptyContent();
		}

		if ( $content->getModel() !== $handler->getModelID() ) {
			$converted = $content->convert( $handler->getModelID() );

			if ( !$converted ) {
				//TODO: somehow show a warning to the user!
				wfDebug( "Attempt to preload incompatible content: "
						. "can't convert " . $content->getModel()
						. " to " . $handler->getModelID() );

				return $handler->makeEmptyContent();
			}

			$content = $converted;
		}

		return $content->preloadTransform( $title, $parserOptions );
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
	 * Sets post-edit cookie indicating the user just saved a particular revision.
	 *
	 * This uses a temporary cookie for each revision ID so separate saves will never
	 * interfere with each other.
	 *
	 * The cookie is deleted in the mediawiki.action.view.postEdit JS module after
	 * the redirect.  It must be clearable by JavaScript code, so it must not be
	 * marked HttpOnly. The JavaScript code converts the cookie to a wgPostEdit config
	 * variable.
	 *
	 * We use a path of '/' since wgCookiePath is not exposed to JS
	 *
	 * If the variable were set on the server, it would be cached, which is unwanted
	 * since the post-edit state should only apply to the load right after the save.
	 */
	protected function setPostEditCookie() {
		$revisionId = $this->mArticle->getLatest();
		$postEditKey = self::POST_EDIT_COOKIE_KEY_PREFIX . $revisionId;

		$response = RequestContext::getMain()->getRequest()->response();
		$response->setcookie( $postEditKey, '1', time() + self::POST_EDIT_COOKIE_DURATION, array(
			'path' => '/',
			'httpOnly' => false,
		) );
	}

	/**
	 * Attempt submission
	 * @throws UserBlockedError|ReadOnlyError|ThrottledError|PermissionsError
	 * @return bool false if output is done, true if the rest of the form should be displayed
	 */
	function attemptSave() {
		global $wgUser, $wgOut;

		$resultDetails = false;
		# Allow bots to exempt some edits from bot flagging
		$bot = $wgUser->isAllowed( 'bot' ) && $this->bot;
		$status = $this->internalAttemptSave( $resultDetails, $bot );
		// FIXME: once the interface for internalAttemptSave() is made nicer, this should use the message in $status
		if ( $status->value == self::AS_SUCCESS_UPDATE || $status->value == self::AS_SUCCESS_NEW_ARTICLE ) {
			$this->didSave = true;
			if ( !$resultDetails['nullEdit'] ) {
				$this->setPostEditCookie();
			}
		}

		switch ( $status->value ) {
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
				return false;

			case self::AS_PARSE_ERROR:
				$wgOut->addWikiText( '<div class="error">' . $status->getWikiText() . '</div>' );
				return true;

			case self::AS_SUCCESS_NEW_ARTICLE:
				$query = $resultDetails['redirect'] ? 'redirect=no' : '';
				$anchor = isset( $resultDetails['sectionanchor'] ) ? $resultDetails['sectionanchor'] : '';
				$wgOut->redirect( $this->mTitle->getFullURL( $query ) . $anchor );
				return false;

			case self::AS_SUCCESS_UPDATE:
				$extraQuery = '';
				$sectionanchor = $resultDetails['sectionanchor'];

				// Give extensions a chance to modify URL query on update
				wfRunHooks( 'ArticleUpdateBeforeRedirect', array( $this->mArticle, &$sectionanchor, &$extraQuery ) );

				if ( $resultDetails['redirect'] ) {
					if ( $extraQuery == '' ) {
						$extraQuery = 'redirect=no';
					} else {
						$extraQuery = 'redirect=no&' . $extraQuery;
					}
				}
				$wgOut->redirect( $this->mTitle->getFullURL( $extraQuery ) . $sectionanchor );
				return false;

			case self::AS_BLANK_ARTICLE:
				$wgOut->redirect( $this->getContextTitle()->getFullURL() );
				return false;

			case self::AS_SPAM_ERROR:
				$this->spamPageWithContent( $resultDetails['spam'] );
				return false;

			case self::AS_BLOCKED_PAGE_FOR_USER:
				throw new UserBlockedError( $wgUser->getBlock() );

			case self::AS_IMAGE_REDIRECT_ANON:
			case self::AS_IMAGE_REDIRECT_LOGGED:
				throw new PermissionsError( 'upload' );

			case self::AS_READ_ONLY_PAGE_ANON:
			case self::AS_READ_ONLY_PAGE_LOGGED:
				throw new PermissionsError( 'edit' );

			case self::AS_READ_ONLY_PAGE:
				throw new ReadOnlyError;

			case self::AS_RATE_LIMITED:
				throw new ThrottledError();

			case self::AS_NO_CREATE_PERMISSION:
				$permission = $this->mTitle->isTalkPage() ? 'createtalk' : 'createpage';
				throw new PermissionsError( $permission );

			default:
				// We don't recognize $status->value. The only way that can happen
				// is if an extension hook aborted from inside ArticleSave.
				// Render the status object into $this->hookError
				// FIXME this sucks, we should just use the Status object throughout
				$this->hookError = '<div class="error">' . $status->getWikitext() .
					'</div>';
				return true;
		}
	}

	/**
	 * Run hooks that can filter edits just before they get saved.
	 *
	 * @param Content $content the Content to filter.
	 * @param Status  $status for reporting the outcome to the caller
	 * @param User    $user the user performing the edit
	 *
	 * @return bool
	 */
	protected function runPostMergeFilters( Content $content, Status $status, User $user ) {
		// Run old style post-section-merge edit filter
		if ( !ContentHandler::runLegacyHooks( 'EditFilterMerged',
			array( $this, $content, &$this->hookError, $this->summary ) ) ) {

			# Error messages etc. could be handled within the hook...
			$status->fatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR;
			return false;
		} elseif ( $this->hookError != '' ) {
			# ...or the hook could be expecting us to produce an error
			$status->fatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR_EXPECTED;
			return false;
		}

		// Run new style post-section-merge edit filter
		if ( !wfRunHooks( 'EditFilterMergedContent',
			array( $this->mArticle->getContext(), $content, $status, $this->summary,
				$user, $this->minoredit ) ) ) {

			# Error messages etc. could be handled within the hook...
			// XXX: $status->value may already be something informative...
			$this->hookError = $status->getWikiText();
			$status->fatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR;
			return false;
		} elseif ( !$status->isOK() ) {
			# ...or the hook could be expecting us to produce an error
			// FIXME this sucks, we should just use the Status object throughout
			$this->hookError = $status->getWikiText();
			$status->fatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR_EXPECTED;
			return false;
		}

		return true;
	}

	/**
	 * Attempt submission (no UI)
	 *
	 * @param array $result array to add statuses to, currently with the possible keys:
	 *  spam - string - Spam string from content if any spam is detected by matchSpamRegex
	 *  sectionanchor - string - Section anchor for a section save
	 *  nullEdit - boolean - Set if doEditContent is OK.  True if null edit, false otherwise.
	 *  redirect - boolean -  Set if doEditContent is OK.  True if resulting revision is a redirect
	 * @param bool $bot True if edit is being made under the bot right.
	 *
	 * @return Status object, possibly with a message, but always with one of the AS_* constants in $status->value,
	 *
	 * FIXME: This interface is TERRIBLE, but hard to get rid of due to various error display idiosyncrasies. There are
	 * also lots of cases where error metadata is set in the object and retrieved later instead of being returned, e.g.
	 * AS_CONTENT_TOO_BIG and AS_BLOCKED_PAGE_FOR_USER. All that stuff needs to be cleaned up some time.
	 */
	function internalAttemptSave( &$result, $bot = false ) {
		global $wgUser, $wgRequest, $wgParser, $wgMaxArticleSize;

		$status = Status::newGood();

		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-checks' );

		if ( !wfRunHooks( 'EditPage::attemptSave', array( $this ) ) ) {
			wfDebug( "Hook 'EditPage::attemptSave' aborted article saving\n" );
			$status->fatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR;
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return $status;
		}

		$spam = $wgRequest->getText( 'wpAntispam' );
		if ( $spam !== '' ) {
			wfDebugLog(
				'SimpleAntiSpam',
				$wgUser->getName() .
				' editing "' .
				$this->mTitle->getPrefixedText() .
				'" submitted bogus field "' .
				$spam .
				'"'
			);
			$status->fatal( 'spamprotectionmatch', false );
			$status->value = self::AS_SPAM_ERROR;
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return $status;
		}

		try {
			# Construct Content object
			$textbox_content = $this->toEditContent( $this->textbox1 );
		} catch ( MWContentSerializationException $ex ) {
			$status->fatal( 'content-failed-to-parse', $this->contentModel, $this->contentFormat, $ex->getMessage() );
			$status->value = self::AS_PARSE_ERROR;
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return $status;
		}

		# Check image redirect
		if ( $this->mTitle->getNamespace() == NS_FILE &&
			$textbox_content->isRedirect() &&
			!$wgUser->isAllowed( 'upload' ) ) {
				$code = $wgUser->isAnon() ? self::AS_IMAGE_REDIRECT_ANON : self::AS_IMAGE_REDIRECT_LOGGED;
				$status->setResult( false, $code );

				wfProfileOut( __METHOD__ . '-checks' );
				wfProfileOut( __METHOD__ );

				return $status;
		}

		# Check for spam
		$match = self::matchSummarySpamRegex( $this->summary );
		if ( $match === false && $this->section == 'new' ) {
			# $wgSpamRegex is enforced on this new heading/summary because, unlike
			# regular summaries, it is added to the actual wikitext.
			if ( $this->sectiontitle !== '' ) {
				# This branch is taken when the API is used with the 'sectiontitle' parameter.
				$match = self::matchSpamRegex( $this->sectiontitle );
			} else {
				# This branch is taken when the "Add Topic" user interface is used, or the API
				# is used with the 'summary' parameter.
				$match = self::matchSpamRegex( $this->summary );
			}
		}
		if ( $match === false ) {
			$match = self::matchSpamRegex( $this->textbox1 );
		}
		if ( $match !== false ) {
			$result['spam'] = $match;
			$ip = $wgRequest->getIP();
			$pdbk = $this->mTitle->getPrefixedDBkey();
			$match = str_replace( "\n", '', $match );
			wfDebugLog( 'SpamRegex', "$ip spam regex hit [[$pdbk]]: \"$match\"" );
			$status->fatal( 'spamprotectionmatch', $match );
			$status->value = self::AS_SPAM_ERROR;
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return $status;
		}
		if ( !wfRunHooks( 'EditFilter', array( $this, $this->textbox1, $this->section, &$this->hookError, $this->summary ) ) ) {
			# Error messages etc. could be handled within the hook...
			$status->fatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR;
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return $status;
		} elseif ( $this->hookError != '' ) {
			# ...or the hook could be expecting us to produce an error
			$status->fatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR_EXPECTED;
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return $status;
		}

		if ( $wgUser->isBlockedFrom( $this->mTitle, false ) ) {
			// Auto-block user's IP if the account was "hard" blocked
			$wgUser->spreadAnyEditBlock();
			# Check block state against master, thus 'false'.
			$status->setResult( false, self::AS_BLOCKED_PAGE_FOR_USER );
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return $status;
		}

		$this->kblength = (int)( strlen( $this->textbox1 ) / 1024 );
		if ( $this->kblength > $wgMaxArticleSize ) {
			// Error will be displayed by showEditForm()
			$this->tooBig = true;
			$status->setResult( false, self::AS_CONTENT_TOO_BIG );
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return $status;
		}

		if ( !$wgUser->isAllowed( 'edit' ) ) {
			if ( $wgUser->isAnon() ) {
				$status->setResult( false, self::AS_READ_ONLY_PAGE_ANON );
				wfProfileOut( __METHOD__ . '-checks' );
				wfProfileOut( __METHOD__ );
				return $status;
			} else {
				$status->fatal( 'readonlytext' );
				$status->value = self::AS_READ_ONLY_PAGE_LOGGED;
				wfProfileOut( __METHOD__ . '-checks' );
				wfProfileOut( __METHOD__ );
				return $status;
			}
		}

		if ( wfReadOnly() ) {
			$status->fatal( 'readonlytext' );
			$status->value = self::AS_READ_ONLY_PAGE;
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return $status;
		}
		if ( $wgUser->pingLimiter() || $wgUser->pingLimiter( 'linkpurge', 0 ) ) {
			$status->fatal( 'actionthrottledtext' );
			$status->value = self::AS_RATE_LIMITED;
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return $status;
		}

		# If the article has been deleted while editing, don't save it without
		# confirmation
		if ( $this->wasDeletedSinceLastEdit() && !$this->recreate ) {
			$status->setResult( false, self::AS_ARTICLE_WAS_DELETED );
			wfProfileOut( __METHOD__ . '-checks' );
			wfProfileOut( __METHOD__ );
			return $status;
		}

		wfProfileOut( __METHOD__ . '-checks' );

		# Load the page data from the master. If anything changes in the meantime,
		# we detect it by using page_latest like a token in a 1 try compare-and-swap.
		$this->mArticle->loadPageData( 'fromdbmaster' );
		$new = !$this->mArticle->exists();

		if ( $new ) {
			// Late check for create permission, just in case *PARANOIA*
			if ( !$this->mTitle->userCan( 'create', $wgUser ) ) {
				$status->fatal( 'nocreatetext' );
				$status->value = self::AS_NO_CREATE_PERMISSION;
				wfDebug( __METHOD__ . ": no create permission\n" );
				wfProfileOut( __METHOD__ );
				return $status;
			}

			// Don't save a new page if it's blank or if it's a MediaWiki:
			// message with content equivalent to default (allow empty pages
			// in this case to disable messages, see bug 50124)
			$defaultMessageText = $this->mTitle->getDefaultMessageText();
			if ( $this->mTitle->getNamespace() === NS_MEDIAWIKI && $defaultMessageText !== false ) {
				$defaultText = $defaultMessageText;
			} else {
				$defaultText = '';
			}

			if ( $this->textbox1 === $defaultText ) {
				$status->setResult( false, self::AS_BLANK_ARTICLE );
				wfProfileOut( __METHOD__ );
				return $status;
			}

			if ( !$this->runPostMergeFilters( $textbox_content, $status, $wgUser ) ) {
				wfProfileOut( __METHOD__ );
				return $status;
			}

			$content = $textbox_content;

			$result['sectionanchor'] = '';
			if ( $this->section == 'new' ) {
				if ( $this->sectiontitle !== '' ) {
					// Insert the section title above the content.
					$content = $content->addSectionHeader( $this->sectiontitle );

					// Jump to the new section
					$result['sectionanchor'] = $wgParser->guessLegacySectionNameFromWikiText( $this->sectiontitle );

					// If no edit summary was specified, create one automatically from the section
					// title and have it link to the new section. Otherwise, respect the summary as
					// passed.
					if ( $this->summary === '' ) {
						$cleanSectionTitle = $wgParser->stripSectionName( $this->sectiontitle );
						$this->summary = wfMessage( 'newsectionsummary' )
							->rawParams( $cleanSectionTitle )->inContentLanguage()->text();
					}
				} elseif ( $this->summary !== '' ) {
					// Insert the section title above the content.
					$content = $content->addSectionHeader( $this->summary );

					// Jump to the new section
					$result['sectionanchor'] = $wgParser->guessLegacySectionNameFromWikiText( $this->summary );

					// Create a link to the new section from the edit summary.
					$cleanSummary = $wgParser->stripSectionName( $this->summary );
					$this->summary = wfMessage( 'newsectionsummary' )
						->rawParams( $cleanSummary )->inContentLanguage()->text();
				}
			}

			$status->value = self::AS_SUCCESS_NEW_ARTICLE;

		} else { # not $new

			# Article exists. Check for edit conflict.

			$this->mArticle->clear(); # Force reload of dates, etc.
			$timestamp = $this->mArticle->getTimestamp();

			wfDebug( "timestamp: {$timestamp}, edittime: {$this->edittime}\n" );

			if ( $timestamp != $this->edittime ) {
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
						wfDebug( __METHOD__ . ": conflict suppressed; new section\n" );
					}
				} elseif ( $this->section == '' && Revision::userWasLastToEdit( DB_MASTER, $this->mTitle->getArticleID(),
							$wgUser->getId(), $this->edittime ) ) {
					# Suppress edit conflict with self, except for section edits where merging is required.
					wfDebug( __METHOD__ . ": Suppressing edit conflict, same user.\n" );
					$this->isConflict = false;
				}
			}

			// If sectiontitle is set, use it, otherwise use the summary as the section title.
			if ( $this->sectiontitle !== '' ) {
				$sectionTitle = $this->sectiontitle;
			} else {
				$sectionTitle = $this->summary;
			}

			$content = null;

			if ( $this->isConflict ) {
				wfDebug( __METHOD__ . ": conflict! getting section '{$this->section}' for time '{$this->edittime}'"
						. " (article time '{$timestamp}')\n" );

				$content = $this->mArticle->replaceSectionContent( $this->section, $textbox_content, $sectionTitle, $this->edittime );
			} else {
				wfDebug( __METHOD__ . ": getting section '{$this->section}'\n" );
				$content = $this->mArticle->replaceSectionContent( $this->section, $textbox_content, $sectionTitle );
			}

			if ( is_null( $content ) ) {
				wfDebug( __METHOD__ . ": activating conflict; section replace failed.\n" );
				$this->isConflict = true;
				$content = $textbox_content; // do not try to merge here!
			} elseif ( $this->isConflict ) {
				# Attempt merge
				if ( $this->mergeChangesIntoContent( $content ) ) {
					// Successful merge! Maybe we should tell the user the good news?
					$this->isConflict = false;
					wfDebug( __METHOD__ . ": Suppressing edit conflict, successful merge.\n" );
				} else {
					$this->section = '';
					$this->textbox1 = ContentHandler::getContentText( $content );
					wfDebug( __METHOD__ . ": Keeping edit conflict, failed merge.\n" );
				}
			}

			if ( $this->isConflict ) {
				$status->setResult( false, self::AS_CONFLICT_DETECTED );
				wfProfileOut( __METHOD__ );
				return $status;
			}

			if ( !$this->runPostMergeFilters( $content, $status, $wgUser ) ) {
				wfProfileOut( __METHOD__ );
				return $status;
			}

			if ( $this->section == 'new' ) {
				// Handle the user preference to force summaries here
				if ( !$this->allowBlankSummary && trim( $this->summary ) == '' ) {
					$this->missingSummary = true;
					$status->fatal( 'missingsummary' ); // or 'missingcommentheader' if $section == 'new'. Blegh
					$status->value = self::AS_SUMMARY_NEEDED;
					wfProfileOut( __METHOD__ );
					return $status;
				}

				// Do not allow the user to post an empty comment
				if ( $this->textbox1 == '' ) {
					$this->missingComment = true;
					$status->fatal( 'missingcommenttext' );
					$status->value = self::AS_TEXTBOX_EMPTY;
					wfProfileOut( __METHOD__ );
					return $status;
				}
			} elseif ( !$this->allowBlankSummary
				&& !$content->equals( $this->getOriginalContent() )
				&& !$content->isRedirect()
				&& md5( $this->summary ) == $this->autoSumm
			) {
				$this->missingSummary = true;
				$status->fatal( 'missingsummary' );
				$status->value = self::AS_SUMMARY_NEEDED;
				wfProfileOut( __METHOD__ );
				return $status;
			}

			# All's well
			wfProfileIn( __METHOD__ . '-sectionanchor' );
			$sectionanchor = '';
			if ( $this->section == 'new' ) {
				if ( $this->sectiontitle !== '' ) {
					$sectionanchor = $wgParser->guessLegacySectionNameFromWikiText( $this->sectiontitle );
					// If no edit summary was specified, create one automatically from the section
					// title and have it link to the new section. Otherwise, respect the summary as
					// passed.
					if ( $this->summary === '' ) {
						$cleanSectionTitle = $wgParser->stripSectionName( $this->sectiontitle );
						$this->summary = wfMessage( 'newsectionsummary' )
							->rawParams( $cleanSectionTitle )->inContentLanguage()->text();
					}
				} elseif ( $this->summary !== '' ) {
					$sectionanchor = $wgParser->guessLegacySectionNameFromWikiText( $this->summary );
					# This is a new section, so create a link to the new section
					# in the revision summary.
					$cleanSummary = $wgParser->stripSectionName( $this->summary );
					$this->summary = wfMessage( 'newsectionsummary' )
						->rawParams( $cleanSummary )->inContentLanguage()->text();
				}
			} elseif ( $this->section != '' ) {
				# Try to get a section anchor from the section source, redirect to edited section if header found
				# XXX: might be better to integrate this into Article::replaceSection
				# for duplicate heading checking and maybe parsing
				$hasmatch = preg_match( "/^ *([=]{1,6})(.*?)(\\1) *\\n/i", $this->textbox1, $matches );
				# we can't deal with anchors, includes, html etc in the header for now,
				# headline would need to be parsed to improve this
				if ( $hasmatch && strlen( $matches[2] ) > 0 ) {
					$sectionanchor = $wgParser->guessLegacySectionNameFromWikiText( $matches[2] );
				}
			}
			$result['sectionanchor'] = $sectionanchor;
			wfProfileOut( __METHOD__ . '-sectionanchor' );

			// Save errors may fall down to the edit form, but we've now
			// merged the section into full text. Clear the section field
			// so that later submission of conflict forms won't try to
			// replace that into a duplicated mess.
			$this->textbox1 = $this->toEditText( $content );
			$this->section = '';

			$status->value = self::AS_SUCCESS_UPDATE;
		}

		// Check for length errors again now that the section is merged in
			$this->kblength = (int)( strlen( $this->toEditText( $content ) ) / 1024 );
		if ( $this->kblength > $wgMaxArticleSize ) {
			$this->tooBig = true;
			$status->setResult( false, self::AS_MAX_ARTICLE_SIZE_EXCEEDED );
			wfProfileOut( __METHOD__ );
			return $status;
		}

		$flags = EDIT_DEFER_UPDATES | EDIT_AUTOSUMMARY |
			( $new ? EDIT_NEW : EDIT_UPDATE ) |
			( ( $this->minoredit && !$this->isNew ) ? EDIT_MINOR : 0 ) |
			( $bot ? EDIT_FORCE_BOT : 0 );

			$doEditStatus = $this->mArticle->doEditContent( $content, $this->summary, $flags,
															false, null, $this->contentFormat );

		if ( !$doEditStatus->isOK() ) {
			// Failure from doEdit()
			// Show the edit conflict page for certain recognized errors from doEdit(),
			// but don't show it for errors from extension hooks
			$errors = $doEditStatus->getErrorsArray();
			if ( in_array( $errors[0][0], array( 'edit-gone-missing', 'edit-conflict',
				'edit-already-exists' ) ) )
			{
				$this->isConflict = true;
				// Destroys data doEdit() put in $status->value but who cares
				$doEditStatus->value = self::AS_END;
			}
			wfProfileOut( __METHOD__ );
			return $doEditStatus;
		}

		$result['nullEdit'] = $doEditStatus->hasMessage( 'edit-no-change' );
		if ( $result['nullEdit'] ) {
			// We don't know if it was a null edit until now, so increment here
			$wgUser->pingLimiter( 'linkpurge' );
		}
		$result['redirect'] = $content->isRedirect();
		$this->updateWatchlist();
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * Register the change of watch status
	 */
	protected function updateWatchlist() {
		global $wgUser;

		if ( $wgUser->isLoggedIn()
			&& $this->watchthis != $wgUser->isWatched( $this->mTitle, WatchedItem::IGNORE_USER_RIGHTS )
		) {
			$fname = __METHOD__;
			$title = $this->mTitle;
			$watch = $this->watchthis;

			// Do this in its own transaction to reduce contention...
			$dbw = wfGetDB( DB_MASTER );
			$dbw->onTransactionIdle( function() use ( $dbw, $title, $watch, $wgUser, $fname ) {
				$dbw->begin( $fname );
				WatchAction::doWatchOrUnwatch( $watch, $title, $wgUser );
				$dbw->commit( $fname );
			} );
		}
	}

	/**
	 * Attempts to merge text content with base and current revisions
	 *
	 * @param $editText string
	 *
	 * @return bool
	 * @deprecated since 1.21, use mergeChangesIntoContent() instead
	 */
	function mergeChangesInto( &$editText ) {
		ContentHandler::deprecated( __METHOD__, "1.21" );

		$editContent = $this->toEditContent( $editText );

		$ok = $this->mergeChangesIntoContent( $editContent );

		if ( $ok ) {
			$editText = $this->toEditText( $editContent );
			return true;
		}
		return false;
	}

	/**
	 * Attempts to do 3-way merge of edit content with a base revision
	 * and current content, in case of edit conflict, in whichever way appropriate
	 * for the content type.
	 *
	 * @since 1.21
	 *
	 * @param $editContent
	 *
	 * @return bool
	 */
	private function mergeChangesIntoContent( &$editContent ) {
		wfProfileIn( __METHOD__ );

		$db = wfGetDB( DB_MASTER );

		// This is the revision the editor started from
		$baseRevision = $this->getBaseRevision();
		$baseContent = $baseRevision ? $baseRevision->getContent() : null;

		if ( is_null( $baseContent ) ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		// The current state, we want to merge updates into it
		$currentRevision = Revision::loadFromTitle( $db, $this->mTitle );
		$currentContent = $currentRevision ? $currentRevision->getContent() : null;

		if ( is_null( $currentContent ) ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		$handler = ContentHandler::getForModelID( $baseContent->getModel() );

		$result = $handler->merge3( $baseContent, $editContent, $currentContent );

		if ( $result ) {
			$editContent = $result;
			wfProfileOut( __METHOD__ );
			return true;
		}

		wfProfileOut( __METHOD__ );
		return false;
	}

	/**
	 * @return Revision
	 */
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

	/**
	 * Check given input text against $wgSpamRegex, and return the text of the first match.
	 *
	 * @param $text string
	 *
	 * @return string|bool matching string or false
	 */
	public static function matchSpamRegex( $text ) {
		global $wgSpamRegex;
		// For back compatibility, $wgSpamRegex may be a single string or an array of regexes.
		$regexes = (array)$wgSpamRegex;
		return self::matchSpamRegexInternal( $text, $regexes );
	}

	/**
	 * Check given input text against $wgSummarySpamRegex, and return the text of the first match.
	 *
	 * @param $text string
	 *
	 * @return string|bool matching string or false
	 */
	public static function matchSummarySpamRegex( $text ) {
		global $wgSummarySpamRegex;
		$regexes = (array)$wgSummarySpamRegex;
		return self::matchSpamRegexInternal( $text, $regexes );
	}

	/**
	 * @param $text string
	 * @param $regexes array
	 * @return bool|string
	 */
	protected static function matchSpamRegexInternal( $text, $regexes ) {
		foreach ( $regexes as $regex ) {
			$matches = array();
			if ( preg_match( $regex, $text, $matches ) ) {
				return $matches[0];
			}
		}
		return false;
	}

	function setHeaders() {
		global $wgOut, $wgUser;

		$wgOut->addModules( 'mediawiki.action.edit' );
		$wgOut->addModuleStyles( 'mediawiki.action.edit.styles' );

		if ( $wgUser->getOption( 'uselivepreview', false ) ) {
			$wgOut->addModules( 'mediawiki.action.edit.preview' );
		}

		if ( $wgUser->getOption( 'useeditwarning', false ) ) {
			$wgOut->addModules( 'mediawiki.action.edit.editWarning' );
		}

		// Bug #19334: textarea jumps when editing articles in IE8
		$wgOut->addStyle( 'common/IE80Fixes.css', 'screen', 'IE 8' );

		$wgOut->setRobotPolicy( 'noindex,nofollow' );

		# Enabled article-related sidebar, toplinks, etc.
		$wgOut->setArticleRelated( true );

		$contextTitle = $this->getContextTitle();
		if ( $this->isConflict ) {
			$msg = 'editconflict';
		} elseif ( $contextTitle->exists() && $this->section != '' ) {
			$msg = $this->section == 'new' ? 'editingcomment' : 'editingsection';
		} else {
			$msg = $contextTitle->exists() || ( $contextTitle->getNamespace() == NS_MEDIAWIKI && $contextTitle->getDefaultMessageText() !== false ) ?
				'editing' : 'creating';
		}
		# Use the title defined by DISPLAYTITLE magic word when present
		$displayTitle = isset( $this->mParserOutput ) ? $this->mParserOutput->getDisplayTitle() : false;
		if ( $displayTitle === false ) {
			$displayTitle = $contextTitle->getPrefixedText();
		}
		$wgOut->setPageTitle( wfMessage( $msg, $displayTitle ) );
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
		} elseif ( $namespace == NS_FILE ) {
			# Show a hint to shared repo
			$file = wfFindFile( $this->mTitle );
			if ( $file && !$file->isLocal() ) {
				$descUrl = $file->getDescriptionUrl();
				# there must be a description url to show a hint to shared repo
				if ( $descUrl ) {
					if ( !$this->mTitle->exists() ) {
						$wgOut->wrapWikiMsg( "<div class=\"mw-sharedupload-desc-create\">\n$1\n</div>", array(
									'sharedupload-desc-create', $file->getRepo()->getDisplayName(), $descUrl
						) );
					} else {
						$wgOut->wrapWikiMsg( "<div class=\"mw-sharedupload-desc-edit\">\n$1\n</div>", array(
									'sharedupload-desc-edit', $file->getRepo()->getDisplayName(), $descUrl
						) );
					}
				}
			}
		}

		# Show a warning message when someone creates/edits a user (talk) page but the user does not exist
		# Show log extract when the user is currently blocked
		if ( $namespace == NS_USER || $namespace == NS_USER_TALK ) {
			$parts = explode( '/', $this->mTitle->getText(), 2 );
			$username = $parts[0];
			$user = User::newFromName( $username, false /* allow IP users*/ );
			$ip = User::isIP( $username );
			if ( !( $user && $user->isLoggedIn() ) && !$ip ) { # User does not exist
				$wgOut->wrapWikiMsg( "<div class=\"mw-userpage-userdoesnotexist error\">\n$1\n</div>",
					array( 'userpage-userdoesnotexist', wfEscapeWikiText( $username ) ) );
			} elseif ( $user->isBlocked() ) { # Show log extract if the user is currently blocked
				LogEventsList::showLogExtract(
					$wgOut,
					'block',
					$user->getUserPage(),
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
			LogEventsList::showLogExtract( $wgOut, array( 'delete', 'move' ), $this->mTitle,
				'',
				array(
					'lim' => 10,
					'conds' => array( "log_action != 'revision'" ),
					'showIfEmpty' => false,
					'msgKey' => array( 'recreate-moveddeleted-warn' )
				)
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
			if ( $title instanceof Title && $title->exists() && $title->userCan( 'read' ) ) {
				global $wgOut;
				// Added using template syntax, to take <noinclude>'s into account.
				$wgOut->addWikiTextTitleTidy( '{{:' . $title->getFullText() . '}}', $this->mTitle );
				return true;
			}
		}
		return false;
	}

	/**
	 * Gets an editable textual representation of $content.
	 * The textual representation can be turned by into a Content object by the
	 * toEditContent() method.
	 *
	 * If $content is null or false or a string, $content is returned unchanged.
	 *
	 * If the given Content object is not of a type that can be edited using the text base EditPage,
	 * an exception will be raised. Set $this->allowNonTextContent to true to allow editing of non-textual
	 * content.
	 *
	 * @param Content|null|bool|string $content
	 * @return String the editable text form of the content.
	 *
	 * @throws MWException if $content is not an instance of TextContent and $this->allowNonTextContent is not true.
	 */
	protected function toEditText( $content ) {
		if ( $content === null || $content === false ) {
			return $content;
		}

		if ( is_string( $content ) ) {
			return $content;
		}

		if ( !$this->allowNonTextContent && !( $content instanceof TextContent ) ) {
			throw new MWException( "This content model can not be edited as text: "
								. ContentHandler::getLocalizedName( $content->getModel() ) );
		}

		return $content->serialize( $this->contentFormat );
	}

	/**
	 * Turns the given text into a Content object by unserializing it.
	 *
	 * If the resulting Content object is not of a type that can be edited using the text base EditPage,
	 * an exception will be raised. Set $this->allowNonTextContent to true to allow editing of non-textual
	 * content.
	 *
	 * @param string|null|bool $text Text to unserialize
	 * @return Content The content object created from $text. If $text was false or null, false resp. null will be
	 *                 returned instead.
	 *
	 * @throws MWException if unserializing the text results in a Content object that is not an instance of TextContent
	 *          and $this->allowNonTextContent is not true.
	 */
	protected function toEditContent( $text ) {
		if ( $text === false || $text === null ) {
			return $text;
		}

		$content = ContentHandler::makeContent( $text, $this->getTitle(),
			$this->contentModel, $this->contentFormat );

		if ( !$this->allowNonTextContent && !( $content instanceof TextContent ) ) {
			throw new MWException( "This content model can not be edited as text: "
				. ContentHandler::getLocalizedName( $content->getModel() ) );
		}

		return $content;
	}

	/**
	 * Send the edit form and related headers to $wgOut
	 * @param $formCallback Callback|null that takes an OutputPage parameter; will be called
	 *     during form output near the top, for captchas and the like.
	 */
	function showEditForm( $formCallback = null ) {
		global $wgOut, $wgUser;

		wfProfileIn( __METHOD__ );

		# need to parse the preview early so that we know which templates are used,
		# otherwise users with "show preview after edit box" will get a blank list
		# we parse this near the beginning so that setHeaders can do the title
		# setting work instead of leaving it in getPreviewText
		$previewOutput = '';
		if ( $this->formtype == 'preview' ) {
			$previewOutput = $this->getPreviewText();
		}

		wfRunHooks( 'EditPage::showEditForm:initial', array( &$this, &$wgOut ) );

		$this->setHeaders();

		if ( $this->showHeader() === false ) {
			wfProfileOut( __METHOD__ );
			return;
		}

		$wgOut->addHTML( $this->editFormPageTop );

		if ( $wgUser->getOption( 'previewontop' ) ) {
			$this->displayPreviewArea( $previewOutput, true );
		}

		$wgOut->addHTML( $this->editFormTextTop );

		$showToolbar = true;
		if ( $this->wasDeletedSinceLastEdit() ) {
			if ( $this->formtype == 'save' ) {
				// Hide the toolbar and edit area, user can click preview to get it back
				// Add an confirmation checkbox and explanation.
				$showToolbar = false;
			} else {
				$wgOut->wrapWikiMsg( "<div class='error mw-deleted-while-editing'>\n$1\n</div>",
					'deletedwhileediting' );
			}
		}

		// @todo add EditForm plugin interface and use it here!
		//       search for textarea1 and textares2, and allow EditForm to override all uses.
		$wgOut->addHTML( Html::openElement( 'form', array( 'id' => self::EDITFORM_ID, 'name' => self::EDITFORM_ID,
			'method' => 'post', 'action' => $this->getActionURL( $this->getContextTitle() ),
			'enctype' => 'multipart/form-data' ) ) );

		if ( is_callable( $formCallback ) ) {
			call_user_func_array( $formCallback, array( &$wgOut ) );
		}

		// Add an empty field to trip up spambots
		$wgOut->addHTML(
			Xml::openElement( 'div', array( 'id' => 'antispam-container', 'style' => 'display: none;' ) )
			. Html::rawElement( 'label', array( 'for' => 'wpAntiSpam' ), wfMessage( 'simpleantispam-label' )->parse() )
			. Xml::element( 'input', array( 'type' => 'text', 'name' => 'wpAntispam', 'id' => 'wpAntispam', 'value' => '' ) )
			. Xml::closeElement( 'div' )
		);

		wfRunHooks( 'EditPage::showEditForm:fields', array( &$this, &$wgOut ) );

		// Put these up at the top to ensure they aren't lost on early form submission
		$this->showFormBeforeText();

		if ( $this->wasDeletedSinceLastEdit() && 'save' == $this->formtype ) {
			$username = $this->lastDelete->user_name;
			$comment = $this->lastDelete->log_comment;

			// It is better to not parse the comment at all than to have templates expanded in the middle
			// TODO: can the checkLabel be moved outside of the div so that wrapWikiMsg could be used?
			$key = $comment === ''
				? 'confirmrecreate-noreason'
				: 'confirmrecreate';
			$wgOut->addHTML(
				'<div class="mw-confirm-recreate">' .
					wfMessage( $key, $username, "<nowiki>$comment</nowiki>" )->parse() .
				Xml::checkLabel( wfMessage( 'recreate' )->text(), 'wpRecreate', 'wpRecreate', false,
					array( 'title' => Linker::titleAttrib( 'recreate' ), 'tabindex' => 1, 'id' => 'wpRecreate' )
				) .
				'</div>'
			);
		}

		# When the summary is hidden, also hide them on preview/show changes
		if ( $this->nosummary ) {
			$wgOut->addHTML( Html::hidden( 'nosummary', true ) );
		}

		# If a blank edit summary was previously provided, and the appropriate
		# user preference is active, pass a hidden tag as wpIgnoreBlankSummary. This will stop the
		# user being bounced back more than once in the event that a summary
		# is not required.
		#####
		# For a bit more sophisticated detection of blank summaries, hash the
		# automatic one and pass that in the hidden field wpAutoSummary.
		if ( $this->missingSummary || ( $this->section == 'new' && $this->nosummary ) ) {
			$wgOut->addHTML( Html::hidden( 'wpIgnoreBlankSummary', true ) );
		}

		if ( $this->undidRev ) {
			$wgOut->addHTML( Html::hidden( 'wpUndidRevision', $this->undidRev ) );
		}

		if ( $this->hasPresetSummary ) {
			// If a summary has been preset using &summary= we don't want to prompt for
			// a different summary. Only prompt for a summary if the summary is blanked.
			// (Bug 17416)
			$this->autoSumm = md5( '' );
		}

		$autosumm = $this->autoSumm ? $this->autoSumm : md5( $this->summary );
		$wgOut->addHTML( Html::hidden( 'wpAutoSummary', $autosumm ) );

		$wgOut->addHTML( Html::hidden( 'oldid', $this->oldid ) );

		$wgOut->addHTML( Html::hidden( 'format', $this->contentFormat ) );
		$wgOut->addHTML( Html::hidden( 'model', $this->contentModel ) );

		if ( $this->section == 'new' ) {
			$this->showSummaryInput( true, $this->summary );
			$wgOut->addHTML( $this->getSummaryPreview( true, $this->summary ) );
		}

		$wgOut->addHTML( $this->editFormTextBeforeContent );

		if ( !$this->isCssJsSubpage && $showToolbar && $wgUser->getOption( 'showtoolbar' ) ) {
			$wgOut->addHTML( EditPage::getEditToolbar() );
		}

		if ( $this->isConflict ) {
			// In an edit conflict bypass the overridable content form method
			// and fallback to the raw wpTextbox1 since editconflicts can't be
			// resolved between page source edits and custom ui edits using the
			// custom edit ui.
			$this->textbox2 = $this->textbox1;

			$content = $this->getCurrentContent();
			$this->textbox1 = $this->toEditText( $content );

			$this->showTextbox1();
		} else {
			$this->showContentForm();
		}

		$wgOut->addHTML( $this->editFormTextAfterContent );

		$this->showStandardInputs();

		$this->showFormAfterText();

		$this->showTosSummary();

		$this->showEditTools();

		$wgOut->addHTML( $this->editFormTextAfterTools . "\n" );

		$wgOut->addHTML( Html::rawElement( 'div', array( 'class' => 'templatesUsed' ),
			Linker::formatTemplates( $this->getTemplates(), $this->preview, $this->section != '' ) ) );

		$wgOut->addHTML( Html::rawElement( 'div', array( 'class' => 'hiddencats' ),
			Linker::formatHiddenCategories( $this->mArticle->getHiddenCategories() ) ) );

		$wgOut->addHTML( Html::rawElement( 'div', array( 'class' => 'limitreport' ),
			self::getPreviewLimitReport( $this->mParserOutput ) ) );

		$wgOut->addModules( 'mediawiki.action.edit.collapsibleFooter' );

		if ( $this->isConflict ) {
			try {
				$this->showConflict();
			} catch ( MWContentSerializationException $ex ) {
				// this can't really happen, but be nice if it does.
				$msg = wfMessage( 'content-failed-to-parse', $this->contentModel, $this->contentFormat, $ex->getMessage() );
				$wgOut->addWikiText( '<div class="error">' . $msg->text() . '</div>' );
			}
		}

		$wgOut->addHTML( $this->editFormTextBottom . "\n</form>\n" );

		if ( !$wgUser->getOption( 'previewontop' ) ) {
			$this->displayPreviewArea( $previewOutput, false );
		}

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Extract the section title from current section text, if any.
	 *
	 * @param string $text
	 * @return Mixed|string or false
	 */
	public static function extractSectionTitle( $text ) {
		preg_match( "/^(=+)(.+)\\1\\s*(\n|$)/i", $text, $matches );
		if ( !empty( $matches[2] ) ) {
			global $wgParser;
			return $wgParser->stripSectionName( trim( $matches[2] ) );
		} else {
			return false;
		}
	}

	protected function showHeader() {
		global $wgOut, $wgUser, $wgMaxArticleSize, $wgLang;

		if ( $this->mTitle->isTalkPage() ) {
			$wgOut->addWikiMsg( 'talkpagetext' );
		}

		// Add edit notices
		$wgOut->addHTML( implode( "\n", $this->mTitle->getEditNotices( $this->oldid ) ) );

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
				if ( !$this->summary && !$this->preview && !$this->diff ) {
					$sectionTitle = self::extractSectionTitle( $this->textbox1 ); //FIXME: use Content object
					if ( $sectionTitle !== false ) {
						$this->summary = "/* $sectionTitle */ ";
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

			if ( $this->section != 'new' ) {
				$revision = $this->mArticle->getRevisionFetched();
				if ( $revision ) {
					// Let sysop know that this will make private content public if saved

					if ( !$revision->userCan( Revision::DELETED_TEXT, $wgUser ) ) {
						$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n", 'rev-deleted-text-permission' );
					} elseif ( $revision->isDeleted( Revision::DELETED_TEXT ) ) {
						$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n", 'rev-deleted-text-view' );
					}

					if ( !$revision->isCurrent() ) {
						$this->mArticle->setOldSubtitle( $revision->getId() );
						$wgOut->addWikiMsg( 'editingold' );
					}
				} elseif ( $this->mTitle->exists() ) {
					// Something went wrong

					$wgOut->wrapWikiMsg( "<div class='errorbox'>\n$1\n</div>\n",
						array( 'missing-revision', $this->oldid ) );
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
				if ( $this->isWrongCaseCssJsPage ) {
					$wgOut->wrapWikiMsg( "<div class='error' id='mw-userinvalidcssjstitle'>\n$1\n</div>", array( 'userinvalidcssjstitle', $this->mTitle->getSkinFromCssJsSubpage() ) );
				}
				if ( $this->formtype !== 'preview' ) {
					if ( $this->isCssSubpage ) {
						$wgOut->wrapWikiMsg( "<div id='mw-usercssyoucanpreview'>\n$1\n</div>", array( 'usercssyoucanpreview' ) );
					}

					if ( $this->isJsSubpage ) {
						$wgOut->wrapWikiMsg( "<div id='mw-userjsyoucanpreview'>\n$1\n</div>", array( 'userjsyoucanpreview' ) );
					}
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
			LogEventsList::showLogExtract( $wgOut, 'protect', $this->mTitle, '',
				array( 'lim' => 1, 'msgKey' => array( $noticeMsg ) ) );
		}
		if ( $this->mTitle->isCascadeProtected() ) {
			# Is this page under cascading protection from some source pages?
			list( $cascadeSources, /* $restrictions */ ) = $this->mTitle->getCascadeProtectionSources();
			$notice = "<div class='mw-cascadeprotectedwarning'>\n$1\n";
			$cascadeSourcesCount = count( $cascadeSources );
			if ( $cascadeSourcesCount > 0 ) {
				# Explain, and list the titles responsible
				foreach ( $cascadeSources as $page ) {
					$notice .= '* [[:' . $page->getPrefixedText() . "]]\n";
				}
			}
			$notice .= '</div>';
			$wgOut->wrapWikiMsg( $notice, array( 'cascadeprotectedwarning', $cascadeSourcesCount ) );
		}
		if ( !$this->mTitle->exists() && $this->mTitle->getRestrictions( 'create' ) ) {
			LogEventsList::showLogExtract( $wgOut, 'protect', $this->mTitle, '',
				array( 'lim' => 1,
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
			if ( !wfMessage( 'longpage-hint' )->isDisabled() ) {
				$wgOut->wrapWikiMsg( "<div id='mw-edit-longpage-hint'>\n$1\n</div>",
					array( 'longpage-hint', $wgLang->formatSize( strlen( $this->textbox1 ) ), strlen( $this->textbox1 ) )
				);
			}
		}
		# Add header copyright warning
		$this->showHeaderCopyrightWarning();
	}

	/**
	 * Standard summary input and label (wgSummary), abstracted so EditPage
	 * subclasses may reorganize the form.
	 * Note that you do not need to worry about the label's for=, it will be
	 * inferred by the id given to the input. You can remove them both by
	 * passing array( 'id' => false ) to $userInputAttrs.
	 *
	 * @param string $summary The value of the summary input
	 * @param string $labelText The html to place inside the label
	 * @param array $inputAttrs of attrs to use on the input
	 * @param array $spanLabelAttrs of attrs to use on the span inside the label
	 *
	 * @return array An array in the format array( $label, $input )
	 */
	function getSummaryInput( $summary = "", $labelText = null, $inputAttrs = null, $spanLabelAttrs = null ) {
		// Note: the maxlength is overridden in JS to 255 and to make it use UTF-8 bytes, not characters.
		$inputAttrs = ( is_array( $inputAttrs ) ? $inputAttrs : array() ) + array(
			'id' => 'wpSummary',
			'maxlength' => '200',
			'tabindex' => '1',
			'size' => 60,
			'spellcheck' => 'true',
		) + Linker::tooltipAndAccesskeyAttribs( 'summary' );

		$spanLabelAttrs = ( is_array( $spanLabelAttrs ) ? $spanLabelAttrs : array() ) + array(
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
	 * @param string $summary The text of the summary to display
	 * @return String
	 */
	protected function showSummaryInput( $isSubjectPreview, $summary = "" ) {
		global $wgOut, $wgContLang;
		# Add a class if 'missingsummary' is triggered to allow styling of the summary line
		$summaryClass = $this->missingSummary ? 'mw-summarymissed' : 'mw-summary';
		if ( $isSubjectPreview ) {
			if ( $this->nosummary ) {
				return;
			}
		} else {
			if ( !$this->mShowSummaryField ) {
				return;
			}
		}
		$summary = $wgContLang->recodeForEdit( $summary );
		$labelText = wfMessage( $isSubjectPreview ? 'subject' : 'summary' )->parse();
		list( $label, $input ) = $this->getSummaryInput( $summary, $labelText, array( 'class' => $summaryClass ), array() );
		$wgOut->addHTML( "{$label} {$input}" );
	}

	/**
	 * @param $isSubjectPreview Boolean: true if this is the section subject/title
	 *                          up top, or false if this is the comment summary
	 *                          down below the textarea
	 * @param string $summary the text of the summary to display
	 * @return String
	 */
	protected function getSummaryPreview( $isSubjectPreview, $summary = "" ) {
		// avoid spaces in preview, gets always trimmed on save
		$summary = trim( $summary );
		if ( !$summary || ( !$this->preview && !$this->diff ) ) {
			return "";
		}

		global $wgParser;

		if ( $isSubjectPreview ) {
			$summary = wfMessage( 'newsectionsummary' )->rawParams( $wgParser->stripSectionName( $summary ) )
				->inContentLanguage()->text();
		}

		$message = $isSubjectPreview ? 'subject-preview' : 'summary-preview';

		$summary = wfMessage( $message )->parse() . Linker::commentBlock( $summary, $this->mTitle, $isSubjectPreview );
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
		if ( !$this->checkUnicodeCompliantBrowser() ) {
			$wgOut->addHTML( Html::hidden( 'safemode', '1' ) );
		}
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
		$wgOut->addHTML( "\n" . Html::hidden( "wpEditToken", $wgUser->getEditToken() ) . "\n" );
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
	 * @param array $customAttribs of html attributes to use in the textarea
	 * @param string $textoverride optional text to override $this->textarea1 with
	 */
	protected function showTextbox1( $customAttribs = null, $textoverride = null ) {
		if ( $this->wasDeletedSinceLastEdit() && $this->formtype == 'save' ) {
			$attribs = array( 'style' => 'display:none;' );
		} else {
			$classes = array(); // Textarea CSS
			if ( $this->mTitle->getNamespace() != NS_MEDIAWIKI && $this->mTitle->isProtected( 'edit' ) ) {
				# Is the title semi-protected?
				if ( $this->mTitle->isSemiProtected() ) {
					$classes[] = 'mw-textarea-sprotected';
				} else {
					# Then it must be protected based on static groups (regular)
					$classes[] = 'mw-textarea-protected';
				}
				# Is the title cascade-protected?
				if ( $this->mTitle->isCascadeProtected() ) {
					$classes[] = 'mw-textarea-cprotected';
				}
			}

			$attribs = array( 'tabindex' => 1 );

			if ( is_array( $customAttribs ) ) {
				$attribs += $customAttribs;
			}

			if ( count( $classes ) ) {
				if ( isset( $attribs['class'] ) ) {
					$classes[] = $attribs['class'];
				}
				$attribs['class'] = implode( ' ', $classes );
			}
		}

		$this->showTextbox( $textoverride !== null ? $textoverride : $this->textbox1, 'wpTextbox1', $attribs );
	}

	protected function showTextbox2() {
		$this->showTextbox( $this->textbox2, 'wpTextbox2', array( 'tabindex' => 6, 'readonly' ) );
	}

	protected function showTextbox( $text, $name, $customAttribs = array() ) {
		global $wgOut, $wgUser;

		$wikitext = $this->safeUnicodeOutput( $text );
		if ( strval( $wikitext ) !== '' ) {
			// Ensure there's a newline at the end, otherwise adding lines
			// is awkward.
			// But don't add a newline if the ext is empty, or Firefox in XHTML
			// mode will show an extra newline. A bit annoying.
			$wikitext .= "\n";
		}

		$attribs = $customAttribs + array(
			'accesskey' => ',',
			'id' => $name,
			'cols' => $wgUser->getIntOption( 'cols' ),
			'rows' => $wgUser->getIntOption( 'rows' ),
			'style' => '' // avoid php notices when appending preferences (appending allows customAttribs['style'] to still work
		);

		$pageLang = $this->mTitle->getPageLanguage();
		$attribs['lang'] = $pageLang->getCode();
		$attribs['dir'] = $pageLang->getDir();

		$wgOut->addHTML( Html::textarea( $name, $wikitext, $attribs ) );
	}

	protected function displayPreviewArea( $previewOutput, $isOnTop = false ) {
		global $wgOut;
		$classes = array();
		if ( $isOnTop ) {
			$classes[] = 'ontop';
		}

		$attribs = array( 'id' => 'wikiPreview', 'class' => implode( ' ', $classes ) );

		if ( $this->formtype != 'preview' ) {
			$attribs['style'] = 'display: none;';
		}

		$wgOut->addHTML( Xml::openElement( 'div', $attribs ) );

		if ( $this->formtype == 'preview' ) {
			$this->showPreview( $previewOutput );
		}

		$wgOut->addHTML( '</div>' );

		if ( $this->formtype == 'diff' ) {
			try {
				$this->showDiff();
			} catch ( MWContentSerializationException $ex ) {
				$msg = wfMessage( 'content-failed-to-parse', $this->contentModel, $this->contentFormat, $ex->getMessage() );
				$wgOut->addWikiText( '<div class="error">' . $msg->text() . '</div>' );
			}
		}
	}

	/**
	 * Append preview output to $wgOut.
	 * Includes category rendering if this is a category page.
	 *
	 * @param string $text the HTML to be output for the preview.
	 */
	protected function showPreview( $text ) {
		global $wgOut;
		if ( $this->mTitle->getNamespace() == NS_CATEGORY ) {
			$this->mArticle->openShowCategory();
		}
		# This hook seems slightly odd here, but makes things more
		# consistent for extensions.
		wfRunHooks( 'OutputPageBeforeHTML', array( &$wgOut, &$text ) );
		$wgOut->addHTML( $text );
		if ( $this->mTitle->getNamespace() == NS_CATEGORY ) {
			$this->mArticle->closeShowCategory();
		}
	}

	/**
	 * Get a diff between the current contents of the edit box and the
	 * version of the page we're editing from.
	 *
	 * If this is a section edit, we'll replace the section as for final
	 * save and then make a comparison.
	 */
	function showDiff() {
		global $wgUser, $wgContLang, $wgOut;

		$oldtitlemsg = 'currentrev';
		# if message does not exist, show diff against the preloaded default
		if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI && !$this->mTitle->exists() ) {
			$oldtext = $this->mTitle->getDefaultMessageText();
			if ( $oldtext !== false ) {
				$oldtitlemsg = 'defaultmessagetext';
				$oldContent = $this->toEditContent( $oldtext );
			} else {
				$oldContent = null;
			}
		} else {
			$oldContent = $this->getCurrentContent();
		}

		$textboxContent = $this->toEditContent( $this->textbox1 );

		$newContent = $this->mArticle->replaceSectionContent(
							$this->section, $textboxContent,
							$this->summary, $this->edittime );

		if ( $newContent ) {
			ContentHandler::runLegacyHooks( 'EditPageGetDiffText', array( $this, &$newContent ) );
			wfRunHooks( 'EditPageGetDiffContent', array( $this, &$newContent ) );

			$popts = ParserOptions::newFromUserAndLang( $wgUser, $wgContLang );
			$newContent = $newContent->preSaveTransform( $this->mTitle, $wgUser, $popts );
		}

		if ( ( $oldContent && !$oldContent->isEmpty() ) || ( $newContent && !$newContent->isEmpty() ) ) {
			$oldtitle = wfMessage( $oldtitlemsg )->parse();
			$newtitle = wfMessage( 'yourtext' )->parse();

			if ( !$oldContent ) {
				$oldContent = $newContent->getContentHandler()->makeEmptyContent();
			}

			if ( !$newContent ) {
				$newContent = $oldContent->getContentHandler()->makeEmptyContent();
			}

			$de = $oldContent->getContentHandler()->createDifferenceEngine( $this->mArticle->getContext() );
			$de->setContent( $oldContent, $newContent );

			$difftext = $de->getDiff( $oldtitle, $newtitle );
			$de->showDiffStyle();
		} else {
			$difftext = '';
		}

		$wgOut->addHTML( '<div id="wikiDiff">' . $difftext . '</div>' );
	}

	/**
	 * Show the header copyright warning.
	 */
	protected function showHeaderCopyrightWarning() {
		$msg = 'editpage-head-copy-warn';
		if ( !wfMessage( $msg )->isDisabled() ) {
			global $wgOut;
			$wgOut->wrapWikiMsg( "<div class='editpage-head-copywarn'>\n$1\n</div>",
				'editpage-head-copy-warn' );
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
		if ( !wfMessage( $msg )->isDisabled() ) {
			global $wgOut;
			$wgOut->addHTML( '<div class="mw-tos-summary">' );
			$wgOut->addWikiMsg( $msg );
			$wgOut->addHTML( '</div>' );
		}
	}

	protected function showEditTools() {
		global $wgOut;
		$wgOut->addHTML( '<div class="mw-editTools">' .
			wfMessage( 'edittools' )->inContentLanguage()->parse() .
			'</div>' );
	}

	/**
	 * Get the copyright warning
	 *
	 * Renamed to getCopyrightWarning(), old name kept around for backwards compatibility
	 */
	protected function getCopywarn() {
		return self::getCopyrightWarning( $this->mTitle );
	}

	/**
	 * Get the copyright warning, by default returns wikitext
	 *
	 * @param Title $title
	 * @param string $format output format, valid values are any function of
	 *                       a Message object
	 * @return string
	 */
	public static function getCopyrightWarning( $title, $format = 'plain' ) {
		global $wgRightsText;
		if ( $wgRightsText ) {
			$copywarnMsg = array( 'copyrightwarning',
				'[[' . wfMessage( 'copyrightpage' )->inContentLanguage()->text() . ']]',
				$wgRightsText );
		} else {
			$copywarnMsg = array( 'copyrightwarning2',
				'[[' . wfMessage( 'copyrightpage' )->inContentLanguage()->text() . ']]' );
		}
		// Allow for site and per-namespace customization of contribution/copyright notice.
		wfRunHooks( 'EditPageCopyrightWarning', array( $title, &$copywarnMsg ) );

		return "<div id=\"editpage-copywarn\">\n" .
			call_user_func_array( 'wfMessage', $copywarnMsg )->$format() . "\n</div>";
	}

	/**
	 * Get the Limit report for page previews
	 *
	 * @since 1.22
	 * @param ParserOutput $output ParserOutput object from the parse
	 * @return string HTML
	 */
	public static function getPreviewLimitReport( $output ) {
		if ( !$output || !$output->getLimitReportData() ) {
			return '';
		}

		wfProfileIn( __METHOD__ );

		$limitReport = Html::rawElement( 'div', array( 'class' => 'mw-limitReportExplanation' ),
			wfMessage( 'limitreport-title' )->parseAsBlock()
		);

		// Show/hide animation doesn't work correctly on a table, so wrap it in a div.
		$limitReport .= Html::openElement( 'div', array( 'class' => 'preview-limit-report-wrapper' ) );

		$limitReport .= Html::openElement( 'table', array(
			'class' => 'preview-limit-report wikitable'
		) ) .
			Html::openElement( 'tbody' );

		foreach ( $output->getLimitReportData() as $key => $value ) {
			if ( wfRunHooks( 'ParserLimitReportFormat',
				array( $key, $value, &$limitReport, true, true )
			) ) {
				$keyMsg = wfMessage( $key );
				$valueMsg = wfMessage( array( "$key-value-html", "$key-value" ) );
				if ( !$valueMsg->exists() ) {
					$valueMsg = new RawMessage( '$1' );
				}
				if ( !$keyMsg->isDisabled() && !$valueMsg->isDisabled() ) {
					$limitReport .= Html::openElement( 'tr' ) .
						Html::rawElement( 'th', null, $keyMsg->parse() ) .
						Html::rawElement( 'td', null, $valueMsg->params( $value )->parse() ) .
						Html::closeElement( 'tr' );
				}
			}
		}

		$limitReport .= Html::closeElement( 'tbody' ) .
			Html::closeElement( 'table' ) .
			Html::closeElement( 'div' );

		wfProfileOut( __METHOD__ );

		return $limitReport;
	}

	protected function showStandardInputs( &$tabindex = 2 ) {
		global $wgOut;
		$wgOut->addHTML( "<div class='editOptions'>\n" );

		if ( $this->section != 'new' ) {
			$this->showSummaryInput( false, $this->summary );
			$wgOut->addHTML( $this->getSummaryPreview( false, $this->summary ) );
		}

		$checkboxes = $this->getCheckboxes( $tabindex,
			array( 'minor' => $this->minoredit, 'watch' => $this->watchthis ) );
		$wgOut->addHTML( "<div class='editCheckboxes'>" . implode( $checkboxes, "\n" ) . "</div>\n" );

		// Show copyright warning.
		$wgOut->addWikiText( $this->getCopywarn() );
		$wgOut->addHTML( $this->editFormTextAfterWarn );

		$wgOut->addHTML( "<div class='editButtons'>\n" );
		$wgOut->addHTML( implode( $this->getEditButtons( $tabindex ), "\n" ) . "\n" );

		$cancel = $this->getCancelLink();
		if ( $cancel !== '' ) {
			$cancel .= Html::element( 'span',
				array( 'class' => 'mw-editButtons-pipe-separator' ),
				wfMessage( 'pipe-separator' )->text() );
		}
		$edithelpurl = Skin::makeInternalOrExternalUrl( wfMessage( 'edithelppage' )->inContentLanguage()->text() );
		$edithelp = '<a target="helpwindow" href="' . $edithelpurl . '">' .
			wfMessage( 'edithelp' )->escaped() . '</a> ' .
			wfMessage( 'newwindow' )->parse();
		$wgOut->addHTML( "	<span class='cancelLink'>{$cancel}</span>\n" );
		$wgOut->addHTML( "	<span class='editHelp'>{$edithelp}</span>\n" );
		$wgOut->addHTML( "</div><!-- editButtons -->\n" );
		wfRunHooks( 'EditPage::showStandardInputs:options', array( $this, $wgOut, &$tabindex ) );
		$wgOut->addHTML( "</div><!-- editOptions -->\n" );
	}

	/**
	 * Show an edit conflict. textbox1 is already shown in showEditForm().
	 * If you want to use another entry point to this function, be careful.
	 */
	protected function showConflict() {
		global $wgOut;

		if ( wfRunHooks( 'EditPageBeforeConflictDiff', array( &$this, &$wgOut ) ) ) {
			$wgOut->wrapWikiMsg( '<h2>$1</h2>', "yourdiff" );

			$content1 = $this->toEditContent( $this->textbox1 );
			$content2 = $this->toEditContent( $this->textbox2 );

			$handler = ContentHandler::getForModelID( $this->contentModel );
			$de = $handler->createDifferenceEngine( $this->mArticle->getContext() );
			$de->setContent( $content2, $content1 );
			$de->showDiff(
				wfMessage( 'yourtext' )->parse(),
				wfMessage( 'storedversion' )->text()
			);

			$wgOut->wrapWikiMsg( '<h2>$1</h2>', "yourtext" );
			$this->showTextbox2();
		}
	}

	/**
	 * @return string
	 */
	public function getCancelLink() {
		$cancelParams = array();
		if ( !$this->isConflict && $this->oldid > 0 ) {
			$cancelParams['oldid'] = $this->oldid;
		}

		return Linker::linkKnown(
			$this->getContextTitle(),
			wfMessage( 'cancel' )->parse(),
			array( 'id' => 'mw-editform-cancel' ),
			$cancelParams
		);
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
	 * Check if a page was deleted while the user was editing it, before submit.
	 * Note that we rely on the logging table, which hasn't been always there,
	 * but that doesn't matter, because this only applies to brand new
	 * deletes.
	 */
	protected function wasDeletedSinceLastEdit() {
		if ( $this->deletedSinceEdit !== null ) {
			return $this->deletedSinceEdit;
		}

		$this->deletedSinceEdit = false;

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

	protected function getLastDelete() {
		$dbr = wfGetDB( DB_SLAVE );
		$data = $dbr->selectRow(
			array( 'logging', 'user' ),
			array(
				'log_type',
				'log_action',
				'log_timestamp',
				'log_user',
				'log_namespace',
				'log_title',
				'log_comment',
				'log_params',
				'log_deleted',
				'user_name'
			), array(
				'log_namespace' => $this->mTitle->getNamespace(),
				'log_title' => $this->mTitle->getDBkey(),
				'log_type' => 'delete',
				'log_action' => 'delete',
				'user_id=log_user'
			),
			__METHOD__,
			array( 'LIMIT' => 1, 'ORDER BY' => 'log_timestamp DESC' )
		);
		// Quick paranoid permission checks...
		if ( is_object( $data ) ) {
			if ( $data->log_deleted & LogPage::DELETED_USER ) {
				$data->user_name = wfMessage( 'rev-deleted-user' )->escaped();
			}

			if ( $data->log_deleted & LogPage::DELETED_COMMENT ) {
				$data->log_comment = wfMessage( 'rev-deleted-comment' )->escaped();
			}
		}
		return $data;
	}

	/**
	 * Get the rendered text for previewing.
	 * @throws MWException
	 * @return string
	 */
	function getPreviewText() {
		global $wgOut, $wgUser, $wgRawHtml, $wgLang;

		wfProfileIn( __METHOD__ );

		if ( $wgRawHtml && !$this->mTokenOk ) {
			// Could be an offsite preview attempt. This is very unsafe if
			// HTML is enabled, as it could be an attack.
			$parsedNote = '';
			if ( $this->textbox1 !== '' ) {
				// Do not put big scary notice, if previewing the empty
				// string, which happens when you initially edit
				// a category page, due to automatic preview-on-open.
				$parsedNote = $wgOut->parse( "<div class='previewnote'>" .
					wfMessage( 'session_fail_preview_html' )->text() . "</div>", true, /* interface */true );
			}
			wfProfileOut( __METHOD__ );
			return $parsedNote;
		}

		$note = '';

		try {
			$content = $this->toEditContent( $this->textbox1 );

			$previewHTML = '';
			if ( !wfRunHooks( 'AlternateEditPreview', array( $this, &$content, &$previewHTML, &$this->mParserOutput ) ) ) {
				wfProfileOut( __METHOD__ );
				return $previewHTML;
			}

			if ( $this->mTriedSave && !$this->mTokenOk ) {
				if ( $this->mTokenOkExceptSuffix ) {
					$note = wfMessage( 'token_suffix_mismatch' )->plain();

				} else {
					$note = wfMessage( 'session_fail_preview' )->plain();
				}
			} elseif ( $this->incompleteForm ) {
				$note = wfMessage( 'edit_form_incomplete' )->plain();
			} else {
				$note = wfMessage( 'previewnote' )->plain() .
					' [[#' . self::EDITFORM_ID . '|' . $wgLang->getArrow() . ' ' . wfMessage( 'continue-editing' )->text() . ']]';
			}

			$parserOptions = $this->mArticle->makeParserOptions( $this->mArticle->getContext() );
			$parserOptions->setEditSection( false );
			$parserOptions->setIsPreview( true );
			$parserOptions->setIsSectionPreview( !is_null( $this->section ) && $this->section !== '' );

			# don't parse non-wikitext pages, show message about preview
			if ( $this->mTitle->isCssJsSubpage() || $this->mTitle->isCssOrJsPage() ) {
				if ( $this->mTitle->isCssJsSubpage() ) {
					$level = 'user';
				} elseif ( $this->mTitle->isCssOrJsPage() ) {
					$level = 'site';
				} else {
					$level = false;
				}

				if ( $content->getModel() == CONTENT_MODEL_CSS ) {
					$format = 'css';
				} elseif ( $content->getModel() == CONTENT_MODEL_JAVASCRIPT ) {
					$format = 'js';
				} else {
					$format = false;
				}

				# Used messages to make sure grep find them:
				# Messages: usercsspreview, userjspreview, sitecsspreview, sitejspreview
				if ( $level && $format ) {
					$note = "<div id='mw-{$level}{$format}preview'>" . wfMessage( "{$level}{$format}preview" )->text() . "</div>";
				}
			}

			$rt = $content->getRedirectChain();
			if ( $rt ) {
				$previewHTML = $this->mArticle->viewRedirect( $rt, false );
			} else {

				# If we're adding a comment, we need to show the
				# summary as the headline
				if ( $this->section === "new" && $this->summary !== "" ) {
					$content = $content->addSectionHeader( $this->summary );
				}

				$hook_args = array( $this, &$content );
				ContentHandler::runLegacyHooks( 'EditPageGetPreviewText', $hook_args );
				wfRunHooks( 'EditPageGetPreviewContent', $hook_args );

				$parserOptions->enableLimitReport();

				# For CSS/JS pages, we should have called the ShowRawCssJs hook here.
				# But it's now deprecated, so never mind

				$content = $content->preSaveTransform( $this->mTitle, $wgUser, $parserOptions );
				$parserOutput = $content->getParserOutput( $this->getArticle()->getTitle(), null, $parserOptions );

				$previewHTML = $parserOutput->getText();
				$this->mParserOutput = $parserOutput;
				$wgOut->addParserOutputNoText( $parserOutput );

				if ( count( $parserOutput->getWarnings() ) ) {
					$note .= "\n\n" . implode( "\n\n", $parserOutput->getWarnings() );
				}
			}
		} catch ( MWContentSerializationException $ex ) {
			$m = wfMessage( 'content-failed-to-parse', $this->contentModel, $this->contentFormat, $ex->getMessage() );
			$note .= "\n\n" . $m->parse();
			$previewHTML = '';
		}

		if ( $this->isConflict ) {
			$conflict = '<h2 id="mw-previewconflict">' . wfMessage( 'previewconflict' )->escaped() . "</h2>\n";
		} else {
			$conflict = '<hr />';
		}

		$previewhead = "<div class='previewnote'>\n" .
			'<h2 id="mw-previewheader">' . wfMessage( 'preview' )->escaped() . "</h2>" .
			$wgOut->parse( $note, true, /* interface */true ) . $conflict . "</div>\n";

		$pageViewLang = $this->mTitle->getPageViewLanguage();
		$attribs = array( 'lang' => $pageViewLang->getHtmlCode(), 'dir' => $pageViewLang->getDir(),
			'class' => 'mw-content-' . $pageViewLang->getDir() );
		$previewHTML = Html::rawElement( 'div', $attribs, $previewHTML );

		wfProfileOut( __METHOD__ );
		return $previewhead . $previewHTML . $this->previewTextAfterContent;
	}

	/**
	 * @return Array
	 */
	function getTemplates() {
		if ( $this->preview || $this->section != '' ) {
			$templates = array();
			if ( !isset( $this->mParserOutput ) ) {
				return $templates;
			}
			foreach ( $this->mParserOutput->getTemplates() as $ns => $template ) {
				foreach ( array_keys( $template ) as $dbk ) {
					$templates[] = Title::makeTitle( $ns, $dbk );
				}
			}
			return $templates;
		} else {
			return $this->mTitle->getTemplateLinksFrom();
		}
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
		 * $toolarray is an array of arrays each of which includes the
		 * filename of the button image (without path), the opening
		 * tag, the closing tag, optionally a sample text that is
		 * inserted between the two when no selection is highlighted
		 * and.  The tip text is shown when the user moves the mouse
		 * over the button.
		 *
		 * Also here: accesskeys (key), which are not used yet until
		 * someone can figure out a way to make them work in
		 * IE. However, we should make sure these keys are not defined
		 * on the edit page.
		 */
		$toolarray = array(
			array(
				'image'  => $wgLang->getImageFile( 'button-bold' ),
				'id'     => 'mw-editbutton-bold',
				'open'   => '\'\'\'',
				'close'  => '\'\'\'',
				'sample' => wfMessage( 'bold_sample' )->text(),
				'tip'    => wfMessage( 'bold_tip' )->text(),
				'key'    => 'B'
			),
			array(
				'image'  => $wgLang->getImageFile( 'button-italic' ),
				'id'     => 'mw-editbutton-italic',
				'open'   => '\'\'',
				'close'  => '\'\'',
				'sample' => wfMessage( 'italic_sample' )->text(),
				'tip'    => wfMessage( 'italic_tip' )->text(),
				'key'    => 'I'
			),
			array(
				'image'  => $wgLang->getImageFile( 'button-link' ),
				'id'     => 'mw-editbutton-link',
				'open'   => '[[',
				'close'  => ']]',
				'sample' => wfMessage( 'link_sample' )->text(),
				'tip'    => wfMessage( 'link_tip' )->text(),
				'key'    => 'L'
			),
			array(
				'image'  => $wgLang->getImageFile( 'button-extlink' ),
				'id'     => 'mw-editbutton-extlink',
				'open'   => '[',
				'close'  => ']',
				'sample' => wfMessage( 'extlink_sample' )->text(),
				'tip'    => wfMessage( 'extlink_tip' )->text(),
				'key'    => 'X'
			),
			array(
				'image'  => $wgLang->getImageFile( 'button-headline' ),
				'id'     => 'mw-editbutton-headline',
				'open'   => "\n== ",
				'close'  => " ==\n",
				'sample' => wfMessage( 'headline_sample' )->text(),
				'tip'    => wfMessage( 'headline_tip' )->text(),
				'key'    => 'H'
			),
			$imagesAvailable ? array(
				'image'  => $wgLang->getImageFile( 'button-image' ),
				'id'     => 'mw-editbutton-image',
				'open'   => '[[' . $wgContLang->getNsText( NS_FILE ) . ':',
				'close'  => ']]',
				'sample' => wfMessage( 'image_sample' )->text(),
				'tip'    => wfMessage( 'image_tip' )->text(),
				'key'    => 'D',
			) : false,
			$imagesAvailable ? array(
				'image'  => $wgLang->getImageFile( 'button-media' ),
				'id'     => 'mw-editbutton-media',
				'open'   => '[[' . $wgContLang->getNsText( NS_MEDIA ) . ':',
				'close'  => ']]',
				'sample' => wfMessage( 'media_sample' )->text(),
				'tip'    => wfMessage( 'media_tip' )->text(),
				'key'    => 'M'
			) : false,
			$wgUseTeX ? array(
				'image'  => $wgLang->getImageFile( 'button-math' ),
				'id'     => 'mw-editbutton-math',
				'open'   => "<math>",
				'close'  => "</math>",
				'sample' => wfMessage( 'math_sample' )->text(),
				'tip'    => wfMessage( 'math_tip' )->text(),
				'key'    => 'C'
			) : false,
			array(
				'image'  => $wgLang->getImageFile( 'button-nowiki' ),
				'id'     => 'mw-editbutton-nowiki',
				'open'   => "<nowiki>",
				'close'  => "</nowiki>",
				'sample' => wfMessage( 'nowiki_sample' )->text(),
				'tip'    => wfMessage( 'nowiki_tip' )->text(),
				'key'    => 'N'
			),
			array(
				'image'  => $wgLang->getImageFile( 'button-sig' ),
				'id'     => 'mw-editbutton-signature',
				'open'   => '--~~~~',
				'close'  => '',
				'sample' => '',
				'tip'    => wfMessage( 'sig_tip' )->text(),
				'key'    => 'Y'
			),
			array(
				'image'  => $wgLang->getImageFile( 'button-hr' ),
				'id'     => 'mw-editbutton-hr',
				'open'   => "\n----\n",
				'close'  => '',
				'sample' => '',
				'tip'    => wfMessage( 'hr_tip' )->text(),
				'key'    => 'R'
			)
		);

		$script = 'mw.loader.using("mediawiki.action.edit", function() {';
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

			$script .= Xml::encodeJsCall( 'mw.toolbar.addButton', $params );
		}

		// This used to be called on DOMReady from mediawiki.action.edit, which
		// ended up causing race conditions with the setup code above.
		$script .= "\n" .
			"// Create button bar\n" .
			"$(function() { mw.toolbar.init(); } );\n";

		$script .= '});';
		$wgOut->addScript( Html::inlineScript( ResourceLoader::makeLoaderConditionalScript( $script ) ) );

		$toolbar = '<div id="toolbar"></div>';

		wfRunHooks( 'EditPageBeforeEditToolbar', array( &$toolbar ) );

		return $toolbar;
	}

	/**
	 * Returns an array of html code of the following checkboxes:
	 * minor and watch
	 *
	 * @param int $tabindex Current tabindex
	 * @param array $checked of checkbox => bool, where bool indicates the checked
	 *                 status of the checkbox
	 *
	 * @return array
	 */
	public function getCheckboxes( &$tabindex, $checked ) {
		global $wgUser;

		$checkboxes = array();

		// don't show the minor edit checkbox if it's a new page or section
		if ( !$this->isNew ) {
			$checkboxes['minor'] = '';
			$minorLabel = wfMessage( 'minoredit' )->parse();
			if ( $wgUser->isAllowed( 'minoredit' ) ) {
				$attribs = array(
					'tabindex' => ++$tabindex,
					'accesskey' => wfMessage( 'accesskey-minoredit' )->text(),
					'id' => 'wpMinoredit',
				);
				$checkboxes['minor'] =
					Xml::check( 'wpMinoredit', $checked['minor'], $attribs ) .
					"&#160;<label for='wpMinoredit' id='mw-editpage-minoredit'" .
					Xml::expandAttributes( array( 'title' => Linker::titleAttrib( 'minoredit', 'withaccess' ) ) ) .
					">{$minorLabel}</label>";
			}
		}

		$watchLabel = wfMessage( 'watchthis' )->parse();
		$checkboxes['watch'] = '';
		if ( $wgUser->isLoggedIn() ) {
			$attribs = array(
				'tabindex' => ++$tabindex,
				'accesskey' => wfMessage( 'accesskey-watch' )->text(),
				'id' => 'wpWatchthis',
			);
			$checkboxes['watch'] =
				Xml::check( 'wpWatchthis', $checked['watch'], $attribs ) .
				"&#160;<label for='wpWatchthis' id='mw-editpage-watch'" .
				Xml::expandAttributes( array( 'title' => Linker::titleAttrib( 'watch', 'withaccess' ) ) ) .
				">{$watchLabel}</label>";
		}
		wfRunHooks( 'EditPageBeforeEditChecks', array( &$this, &$checkboxes, &$tabindex ) );
		return $checkboxes;
	}

	/**
	 * Returns an array of html code of the following buttons:
	 * save, diff, preview and live
	 *
	 * @param int $tabindex Current tabindex
	 *
	 * @return array
	 */
	public function getEditButtons( &$tabindex ) {
		$buttons = array();

		$temp = array(
			'id' => 'wpSave',
			'name' => 'wpSave',
			'type' => 'submit',
			'tabindex' => ++$tabindex,
			'value' => wfMessage( 'savearticle' )->text(),
			'accesskey' => wfMessage( 'accesskey-save' )->text(),
			'title' => wfMessage( 'tooltip-save' )->text() . ' [' . wfMessage( 'accesskey-save' )->text() . ']',
		);
		$buttons['save'] = Xml::element( 'input', $temp, '' );

		++$tabindex; // use the same for preview and live preview
		$temp = array(
			'id' => 'wpPreview',
			'name' => 'wpPreview',
			'type' => 'submit',
			'tabindex' => $tabindex,
			'value' => wfMessage( 'showpreview' )->text(),
			'accesskey' => wfMessage( 'accesskey-preview' )->text(),
			'title' => wfMessage( 'tooltip-preview' )->text() . ' [' . wfMessage( 'accesskey-preview' )->text() . ']',
		);
		$buttons['preview'] = Xml::element( 'input', $temp, '' );
		$buttons['live'] = '';

		$temp = array(
			'id' => 'wpDiff',
			'name' => 'wpDiff',
			'type' => 'submit',
			'tabindex' => ++$tabindex,
			'value' => wfMessage( 'showdiff' )->text(),
			'accesskey' => wfMessage( 'accesskey-diff' )->text(),
			'title' => wfMessage( 'tooltip-diff' )->text() . ' [' . wfMessage( 'accesskey-diff' )->text() . ']',
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
	 *       Would need to enhance it a bit, "<s>maybe wrap them in XML
	 *       or something...</s>" that might also require more skin
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
	 * Call the stock "user is blocked" page
	 *
	 * @deprecated in 1.19; throw an exception directly instead
	 */
	function blockedPage() {
		wfDeprecated( __METHOD__, '1.19' );
		global $wgUser;

		throw new UserBlockedError( $wgUser->getBlock() );
	}

	/**
	 * Produce the stock "please login to edit pages" page
	 *
	 * @deprecated in 1.19; throw an exception directly instead
	 */
	function userNotLoggedInPage() {
		wfDeprecated( __METHOD__, '1.19' );
		throw new PermissionsError( 'edit' );
	}

	/**
	 * Show an error page saying to the user that he has insufficient permissions
	 * to create a new page
	 *
	 * @deprecated in 1.19; throw an exception directly instead
	 */
	function noCreatePermission() {
		wfDeprecated( __METHOD__, '1.19' );
		$permission = $this->mTitle->isTalkPage() ? 'createtalk' : 'createpage';
		throw new PermissionsError( $permission );
	}

	/**
	 * Creates a basic error page which informs the user that
	 * they have attempted to edit a nonexistent section.
	 */
	function noSuchSectionPage() {
		global $wgOut;

		$wgOut->prepareErrorPage( wfMessage( 'nosuchsectiontitle' ) );

		$res = wfMessage( 'nosuchsectiontext', $this->section )->parseAsBlock();
		wfRunHooks( 'EditPageNoSuchSection', array( &$this, &$res ) );
		$wgOut->addHTML( $res );

		$wgOut->returnToMain( false, $this->mTitle );
	}

	/**
	 * Produce the stock "your edit contains spam" page
	 *
	 * @param string|bool $match Text which triggered one or more filters
	 * @deprecated since 1.17 Use method spamPageWithContent() instead
	 */
	static function spamPage( $match = false ) {
		wfDeprecated( __METHOD__, '1.17' );

		global $wgOut, $wgTitle;

		$wgOut->prepareErrorPage( wfMessage( 'spamprotectiontitle' ) );

		$wgOut->addHTML( '<div id="spamprotected">' );
		$wgOut->addWikiMsg( 'spamprotectiontext' );
		if ( $match ) {
			$wgOut->addWikiMsg( 'spamprotectionmatch', wfEscapeWikiText( $match ) );
		}
		$wgOut->addHTML( '</div>' );

		$wgOut->returnToMain( false, $wgTitle );
	}

	/**
	 * Show "your edit contains spam" page with your diff and text
	 *
	 * @param $match string|Array|bool Text (or array of texts) which triggered one or more filters
	 */
	public function spamPageWithContent( $match = false ) {
		global $wgOut, $wgLang;
		$this->textbox2 = $this->textbox1;

		if ( is_array( $match ) ) {
			$match = $wgLang->listToText( $match );
		}
		$wgOut->prepareErrorPage( wfMessage( 'spamprotectiontitle' ) );

		$wgOut->addHTML( '<div id="spamprotected">' );
		$wgOut->addWikiMsg( 'spamprotectiontext' );
		if ( $match ) {
			$wgOut->addWikiMsg( 'spamprotectionmatch', wfEscapeWikiText( $match ) );
		}
		$wgOut->addHTML( '</div>' );

		$wgOut->wrapWikiMsg( '<h2>$1</h2>', "yourdiff" );
		$this->showDiff();

		$wgOut->wrapWikiMsg( '<h2>$1</h2>', "yourtext" );
		$this->showTextbox2();

		$wgOut->addReturnTo( $this->getContextTitle(), array( 'action' => 'edit' ) );
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
	 * Check if the browser is on a blacklist of user-agents known to
	 * mangle UTF-8 data on form submission. Returns true if Unicode
	 * should make it through, false if it's known to be a problem.
	 * @return bool
	 * @private
	 */
	function checkUnicodeCompliantBrowser() {
		global $wgBrowserBlackList, $wgRequest;

		$currentbrowser = $wgRequest->getHeader( 'User-Agent' );
		if ( $currentbrowser === false ) {
			// No User-Agent header sent? Trust it by default...
			return true;
		}

		foreach ( $wgBrowserBlackList as $browser ) {
			if ( preg_match( $browser, $currentbrowser ) ) {
				return false;
			}
		}
		return true;
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

	/**
	 * @param $request WebRequest
	 * @param $text string
	 * @return string
	 */
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
		// Armor existing references for reversibility.
		$invalue = strtr( $invalue, array( "&#x" => "&#x0" ) );

		$bytesleft = 0;
		$result = "";
		$working = 0;
		for ( $i = 0; $i < strlen( $invalue ); $i++ ) {
			$bytevalue = ord( $invalue[$i] );
			if ( $bytevalue <= 0x7F ) { // 0xxx xxxx
				$result .= chr( $bytevalue );
				$bytesleft = 0;
			} elseif ( $bytevalue <= 0xBF ) { // 10xx xxxx
				$working = $working << 6;
				$working += ( $bytevalue & 0x3F );
				$bytesleft--;
				if ( $bytesleft <= 0 ) {
					$result .= "&#x" . strtoupper( dechex( $working ) ) . ";";
				}
			} elseif ( $bytevalue <= 0xDF ) { // 110x xxxx
				$working = $bytevalue & 0x1F;
				$bytesleft = 1;
			} elseif ( $bytevalue <= 0xEF ) { // 1110 xxxx
				$working = $bytevalue & 0x0F;
				$bytesleft = 2;
			} else { // 1111 0xxx
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
		for ( $i = 0; $i < strlen( $invalue ); $i++ ) {
			if ( ( substr( $invalue, $i, 3 ) == "&#x" ) && ( $invalue[$i + 3] != '0' ) ) {
				$i += 3;
				$hexstring = "";
				do {
					$hexstring .= $invalue[$i];
					$i++;
				} while ( ctype_xdigit( $invalue[$i] ) && ( $i < strlen( $invalue ) ) );

				// Do some sanity checks. These aren't needed for reversibility,
				// but should help keep the breakage down if the editor
				// breaks one of the entities whilst editing.
				if ( ( substr( $invalue, $i, 1 ) == ";" ) and ( strlen( $hexstring ) <= 6 ) ) {
					$codepoint = hexdec( $hexstring );
					$result .= codepointToUtf8( $codepoint );
				} else {
					$result .= "&#x" . $hexstring . substr( $invalue, $i, 1 );
				}
			} else {
				$result .= substr( $invalue, $i, 1 );
			}
		}
		// reverse the transform that we made for reversibility reasons.
		return strtr( $result, array( "&#x0" => "&#x" ) );
	}
}
