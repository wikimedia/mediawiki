<?php
/**
 * User interface for page editing.
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

use MediaWiki\EditPage\TextboxBuilder;
use MediaWiki\EditPage\TextConflictHelper;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use Wikimedia\ScopedCallback;

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
	 * Used for Unicode support checks
	 */
	const UNICODE_CHECK = 'ℳ𝒲♥𝓊𝓃𝒾𝒸ℴ𝒹ℯ';

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
	 * Status: user tried to create a blank page and wpIgnoreBlankArticle == false
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
	 * Status: user tried to modify the content model, but is not allowed to do that
	 * ( User::isAllowed('editcontentmodel') == false )
	 */
	const AS_NO_CHANGE_CONTENT_MODEL = 235;

	/**
	 * Status: user tried to create self-redirect (redirect to the same article) and
	 * wpIgnoreSelfRedirect == false
	 */
	const AS_SELF_REDIRECT = 236;

	/**
	 * Status: an error relating to change tagging. Look at the message key for
	 * more details
	 */
	const AS_CHANGE_TAG_ERROR = 237;

	/**
	 * Status: can't parse content
	 */
	const AS_PARSE_ERROR = 240;

	/**
	 * Status: when changing the content model is disallowed due to
	 * $wgContentHandlerUseDB being false
	 */
	const AS_CANNOT_USE_CUSTOM_MODEL = 241;

	/**
	 * Status: edit rejected because browser doesn't support Unicode.
	 */
	const AS_UNICODE_NOT_SUPPORTED = 242;

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
	 * RFC 2109 ( https://www.ietf.org/rfc/rfc2109.txt ) specifies a possible
	 * limit of only 20 cookies per domain. This still applies at least to some
	 * versions of IE without full updates:
	 * https://blogs.msdn.com/b/ieinternals/archive/2009/08/20/wininet-ie-cookie-internals-faq.aspx
	 *
	 * A value of 20 minutes should be enough to take into account slow loads and minor
	 * clock skew while still avoiding cookie accumulation when JavaScript is turned off.
	 */
	const POST_EDIT_COOKIE_DURATION = 1200;

	/**
	 * @deprecated for public usage since 1.30 use EditPage::getArticle()
	 * @var Article
	 */
	public $mArticle;
	/** @var WikiPage */
	private $page;

	/**
	 * @deprecated for public usage since 1.30 use EditPage::getTitle()
	 * @var Title
	 */
	public $mTitle;

	/** @var null|Title */
	private $mContextTitle = null;

	/** @var string */
	public $action = 'submit';

	/** @var bool */
	public $isConflict = false;

	/** @var bool New page or new section */
	public $isNew = false;

	/** @var bool */
	public $deletedSinceEdit;

	/** @var string */
	public $formtype;

	/** @var bool */
	public $firsttime;

	/** @var bool|stdClass */
	public $lastDelete;

	/** @var bool */
	public $mTokenOk = false;

	/** @var bool */
	public $mTokenOkExceptSuffix = false;

	/** @var bool */
	public $mTriedSave = false;

	/** @var bool */
	public $incompleteForm = false;

	/** @var bool */
	public $tooBig = false;

	/** @var bool */
	public $missingComment = false;

	/** @var bool */
	public $missingSummary = false;

	/** @var bool */
	public $allowBlankSummary = false;

	/** @var bool */
	protected $blankArticle = false;

	/** @var bool */
	protected $allowBlankArticle = false;

	/** @var bool */
	protected $selfRedirect = false;

	/** @var bool */
	protected $allowSelfRedirect = false;

	/** @var string */
	public $autoSumm = '';

	/** @var string */
	public $hookError = '';

	/** @var ParserOutput */
	public $mParserOutput;

	/** @var bool Has a summary been preset using GET parameter &summary= ? */
	public $hasPresetSummary = false;

	/** @var Revision|bool|null */
	public $mBaseRevision = false;

	/** @var bool */
	public $mShowSummaryField = true;

	# Form values

	/** @var bool */
	public $save = false;

	/** @var bool */
	public $preview = false;

	/** @var bool */
	public $diff = false;

	/** @var bool */
	public $minoredit = false;

	/** @var bool */
	public $watchthis = false;

	/** @var bool */
	public $recreate = false;

	/** @var string */
	public $textbox1 = '';

	/** @var string */
	public $textbox2 = '';

	/** @var string */
	public $summary = '';

	/** @var bool */
	public $nosummary = false;

	/** @var string */
	public $edittime = '';

	/** @var int */
	private $editRevId = null;

	/** @var string */
	public $section = '';

	/** @var string */
	public $sectiontitle = '';

	/** @var string */
	public $starttime = '';

	/** @var int */
	public $oldid = 0;

	/** @var int */
	public $parentRevId = 0;

	/** @var string */
	public $editintro = '';

	/** @var null */
	public $scrolltop = null;

	/** @var bool */
	public $bot = true;

	/** @var string */
	public $contentModel;

	/** @var null|string */
	public $contentFormat = null;

	/** @var null|array */
	private $changeTags = null;

	# Placeholders for text injection by hooks (must be HTML)
	# extensions should take care to _append_ to the present value

	/** @var string Before even the preview */
	public $editFormPageTop = '';
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

	/** @var bool */
	protected $edit;

	/** @var bool|int */
	protected $contentLength = false;

	/**
	 * @var bool Set in ApiEditPage, based on ContentHandler::allowsDirectApiEditing
	 */
	private $enableApiEditOverride = false;

	/**
	 * @var IContextSource
	 */
	protected $context;

	/**
	 * @var bool Whether an old revision is edited
	 */
	private $isOldRev = false;

	/**
	 * @var string|null What the user submitted in the 'wpUnicodeCheck' field
	 */
	private $unicodeCheck;

	/**
	 * Factory function to create an edit conflict helper
	 *
	 * @var callable
	 */
	private $editConflictHelperFactory;

	/**
	 * @var TextConflictHelper|null
	 */
	private $editConflictHelper;

	/**
	 * @param Article $article
	 */
	public function __construct( Article $article ) {
		$this->mArticle = $article;
		$this->page = $article->getPage(); // model object
		$this->mTitle = $article->getTitle();
		$this->context = $article->getContext();

		$this->contentModel = $this->mTitle->getContentModel();

		$handler = ContentHandler::getForModelID( $this->contentModel );
		$this->contentFormat = $handler->getDefaultFormat();
		$this->editConflictHelperFactory = [ $this, 'newTextConflictHelper' ];
	}

	/**
	 * @return Article
	 */
	public function getArticle() {
		return $this->mArticle;
	}

	/**
	 * @since 1.28
	 * @return IContextSource
	 */
	public function getContext() {
		return $this->context;
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
	 * @param Title|null $title Title object or null
	 */
	public function setContextTitle( $title ) {
		$this->mContextTitle = $title;
	}

	/**
	 * Get the context title object.
	 * If not set, $wgTitle will be returned. This behavior might change in
	 * the future to return $this->mTitle instead.
	 *
	 * @return Title
	 */
	public function getContextTitle() {
		if ( is_null( $this->mContextTitle ) ) {
			wfDebugLog(
				'GlobalTitleFail',
				__METHOD__ . ' called by ' . wfGetAllCallers( 5 ) . ' with no title set.'
			);
			global $wgTitle;
			return $wgTitle;
		} else {
			return $this->mContextTitle;
		}
	}

	/**
	 * Check if the edit page is using OOUI controls
	 * @return bool Always true
	 * @deprecated since 1.30
	 */
	public function isOouiEnabled() {
		wfDeprecated( __METHOD__, '1.30' );
		return true;
	}

	/**
	 * Returns if the given content model is editable.
	 *
	 * @param string $modelId The ID of the content model to test. Use CONTENT_MODEL_XXX constants.
	 * @return bool
	 * @throws MWException If $modelId has no known handler
	 */
	public function isSupportedContentModel( $modelId ) {
		return $this->enableApiEditOverride === true ||
			ContentHandler::getForModelID( $modelId )->supportsDirectEditing();
	}

	/**
	 * Allow editing of content that supports API direct editing, but not general
	 * direct editing. Set to false by default.
	 *
	 * @param bool $enableOverride
	 */
	public function setApiEditOverride( $enableOverride ) {
		$this->enableApiEditOverride = $enableOverride;
	}

	/**
	 * @deprecated since 1.29, call edit directly
	 */
	public function submit() {
		wfDeprecated( __METHOD__, '1.29' );
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
	public function edit() {
		// Allow extensions to modify/prevent this form or submission
		if ( !Hooks::run( 'AlternateEdit', [ $this ] ) ) {
			return;
		}

		wfDebug( __METHOD__ . ": enter\n" );

		$request = $this->context->getRequest();
		// If they used redlink=1 and the page exists, redirect to the main article
		if ( $request->getBool( 'redlink' ) && $this->mTitle->exists() ) {
			$this->context->getOutput()->redirect( $this->mTitle->getFullURL() );
			return;
		}

		$this->importFormData( $request );
		$this->firsttime = false;

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

		$permErrors = $this->getEditPermissionErrors( $this->save ? 'secure' : 'full' );
		if ( $permErrors ) {
			wfDebug( __METHOD__ . ": User can't edit\n" );
			// Auto-block user's IP if the account was "hard" blocked
			if ( !wfReadOnly() ) {
				DeferredUpdates::addCallableUpdate( function () {
					$this->context->getUser()->spreadAnyEditBlock();
				} );
			}
			$this->displayPermissionsError( $permErrors );

			return;
		}

		$revision = $this->mArticle->getRevisionFetched();
		// Disallow editing revisions with content models different from the current one
		// Undo edits being an exception in order to allow reverting content model changes.
		if ( $revision
			&& $revision->getContentModel() !== $this->contentModel
		) {
			$prevRev = null;
			if ( $this->undidRev ) {
				$undidRevObj = Revision::newFromId( $this->undidRev );
				$prevRev = $undidRevObj ? $undidRevObj->getPrevious() : null;
			}
			if ( !$this->undidRev
				|| !$prevRev
				|| $prevRev->getContentModel() !== $this->contentModel
			) {
				$this->displayViewSourcePage(
					$this->getContentObject(),
					$this->context->msg(
						'contentmodelediterror',
						$revision->getContentModel(),
						$this->contentModel
					)->plain()
				);
				return;
			}
		}

		$this->isConflict = false;

		# Show applicable editing introductions
		if ( $this->formtype == 'initial' || $this->firsttime ) {
			$this->showIntro();
		}

		# Attempt submission here.  This will check for edit conflicts,
		# and redundantly check for locked database, blocked IPs, etc.
		# that edit() already checked just in case someone tries to sneak
		# in the back door with a hand-edited submission URL.

		if ( 'save' == $this->formtype ) {
			$resultDetails = null;
			$status = $this->attemptSave( $resultDetails );
			if ( !$this->handleStatus( $status, $resultDetails ) ) {
				return;
			}
		}

		# First time through: get contents, set time for conflict
		# checking, etc.
		if ( 'initial' == $this->formtype || $this->firsttime ) {
			if ( $this->initialiseForm() === false ) {
				$this->noSuchSectionPage();
				return;
			}

			if ( !$this->mTitle->getArticleID() ) {
				Hooks::run( 'EditFormPreloadText', [ &$this->textbox1, &$this->mTitle ] );
			} else {
				Hooks::run( 'EditFormInitialText', [ $this ] );
			}

		}

		$this->showEditForm();
	}

	/**
	 * @param string $rigor Same format as Title::getUserPermissionErrors()
	 * @return array
	 */
	protected function getEditPermissionErrors( $rigor = 'secure' ) {
		$user = $this->context->getUser();
		$permErrors = $this->mTitle->getUserPermissionsErrors( 'edit', $user, $rigor );
		# Can this title be created?
		if ( !$this->mTitle->exists() ) {
			$permErrors = array_merge(
				$permErrors,
				wfArrayDiff2(
					$this->mTitle->getUserPermissionsErrors( 'create', $user, $rigor ),
					$permErrors
				)
			);
		}
		# Ignore some permissions errors when a user is just previewing/viewing diffs
		$remove = [];
		foreach ( $permErrors as $error ) {
			if ( ( $this->preview || $this->diff )
				&& (
					$error[0] == 'blockedtext' ||
					$error[0] == 'autoblockedtext' ||
					$error[0] == 'systemblockedtext'
				)
			) {
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
	 * @param array $permErrors Array of permissions errors, as returned by
	 *    Title::getUserPermissionsErrors().
	 * @throws PermissionsError
	 */
	protected function displayPermissionsError( array $permErrors ) {
		$out = $this->context->getOutput();
		if ( $this->context->getRequest()->getBool( 'redlink' ) ) {
			// The edit page was reached via a red link.
			// Redirect to the article page and let them click the edit tab if
			// they really want a permission error.
			$out->redirect( $this->mTitle->getFullURL() );
			return;
		}

		$content = $this->getContentObject();

		# Use the normal message if there's nothing to display
		if ( $this->firsttime && ( !$content || $content->isEmpty() ) ) {
			$action = $this->mTitle->exists() ? 'edit' :
				( $this->mTitle->isTalkPage() ? 'createtalk' : 'createpage' );
			throw new PermissionsError( $action, $permErrors );
		}

		$this->displayViewSourcePage(
			$content,
			$out->formatPermissionsErrorMessage( $permErrors, 'edit' )
		);
	}

	/**
	 * Display a read-only View Source page
	 * @param Content $content
	 * @param string $errorMessage additional wikitext error message to display
	 */
	protected function displayViewSourcePage( Content $content, $errorMessage = '' ) {
		$out = $this->context->getOutput();
		Hooks::run( 'EditPage::showReadOnlyForm:initial', [ $this, &$out ] );

		$out->setRobotPolicy( 'noindex,nofollow' );
		$out->setPageTitle( $this->context->msg(
			'viewsource-title',
			$this->getContextTitle()->getPrefixedText()
		) );
		$out->addBacklinkSubtitle( $this->getContextTitle() );
		$out->addHTML( $this->editFormPageTop );
		$out->addHTML( $this->editFormTextTop );

		if ( $errorMessage !== '' ) {
			$out->addWikiText( $errorMessage );
			$out->addHTML( "<hr />\n" );
		}

		# If the user made changes, preserve them when showing the markup
		# (This happens when a user is blocked during edit, for instance)
		if ( !$this->firsttime ) {
			$text = $this->textbox1;
			$out->addWikiMsg( 'viewyourtext' );
		} else {
			try {
				$text = $this->toEditText( $content );
			} catch ( MWException $e ) {
				# Serialize using the default format if the content model is not supported
				# (e.g. for an old revision with a different model)
				$text = $content->serialize();
			}
			$out->addWikiMsg( 'viewsourcetext' );
		}

		$out->addHTML( $this->editFormTextBeforeContent );
		$this->showTextbox( $text, 'wpTextbox1', [ 'readonly' ] );
		$out->addHTML( $this->editFormTextAfterContent );

		$out->addHTML( $this->makeTemplatesOnThisPageList( $this->getTemplates() ) );

		$out->addModules( 'mediawiki.action.edit.collapsibleFooter' );

		$out->addHTML( $this->editFormTextBottom );
		if ( $this->mTitle->exists() ) {
			$out->returnToMain( null, $this->mTitle );
		}
	}

	/**
	 * Should we show a preview when the edit form is first shown?
	 *
	 * @return bool
	 */
	protected function previewOnOpen() {
		$config = $this->context->getConfig();
		$previewOnOpenNamespaces = $config->get( 'PreviewOnOpenNamespaces' );
		$request = $this->context->getRequest();
		if ( $config->get( 'RawHtml' ) ) {
			// If raw HTML is enabled, disable preview on open
			// since it has to be posted with a token for
			// security reasons
			return false;
		}
		if ( $request->getVal( 'preview' ) == 'yes' ) {
			// Explicit override from request
			return true;
		} elseif ( $request->getVal( 'preview' ) == 'no' ) {
			// Explicit override from request
			return false;
		} elseif ( $this->section == 'new' ) {
			// Nothing *to* preview for new sections
			return false;
		} elseif ( ( $request->getVal( 'preload' ) !== null || $this->mTitle->exists() )
			&& $this->context->getUser()->getOption( 'previewonfirst' )
		) {
			// Standard preference behavior
			return true;
		} elseif ( !$this->mTitle->exists()
			&& isset( $previewOnOpenNamespaces[$this->mTitle->getNamespace()] )
			&& $previewOnOpenNamespaces[$this->mTitle->getNamespace()]
		) {
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
	protected function isWrongCaseUserConfigPage() {
		if ( $this->mTitle->isUserConfigPage() ) {
			$name = $this->mTitle->getSkinFromConfigSubpage();
			$skins = array_merge(
				array_keys( Skin::getSkinNames() ),
				[ 'common' ]
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
	 * @return bool True if this edit page supports sections, false otherwise.
	 */
	protected function isSectionEditSupported() {
		$contentHandler = ContentHandler::getForTitle( $this->mTitle );
		return $contentHandler->supportsSections();
	}

	/**
	 * This function collects the form data and uses it to populate various member variables.
	 * @param WebRequest &$request
	 * @throws ErrorPageError
	 */
	public function importFormData( &$request ) {
		# Section edit can come from either the form or a link
		$this->section = $request->getVal( 'wpSection', $request->getVal( 'section' ) );

		if ( $this->section !== null && $this->section !== '' && !$this->isSectionEditSupported() ) {
			throw new ErrorPageError( 'sectioneditnotsupported-title', 'sectioneditnotsupported-text' );
		}

		$this->isNew = !$this->mTitle->exists() || $this->section == 'new';

		if ( $request->wasPosted() ) {
			# These fields need to be checked for encoding.
			# Also remove trailing whitespace, but don't remove _initial_
			# whitespace from the text boxes. This may be significant formatting.
			$this->textbox1 = rtrim( $request->getText( 'wpTextbox1' ) );
			if ( !$request->getCheck( 'wpTextbox2' ) ) {
				// Skip this if wpTextbox2 has input, it indicates that we came
				// from a conflict page with raw page text, not a custom form
				// modified by subclasses
				$textbox1 = $this->importContentFormData( $request );
				if ( $textbox1 !== null ) {
					$this->textbox1 = $textbox1;
				}
			}

			$this->unicodeCheck = $request->getText( 'wpUnicodeCheck' );

			$this->summary = $request->getText( 'wpSummary' );

			# If the summary consists of a heading, e.g. '==Foobar==', extract the title from the
			# header syntax, e.g. 'Foobar'. This is mainly an issue when we are using wpSummary for
			# section titles.
			$this->summary = preg_replace( '/^\s*=+\s*(.*?)\s*=+\s*$/', '$1', $this->summary );

			# Treat sectiontitle the same way as summary.
			# Note that wpSectionTitle is not yet a part of the actual edit form, as wpSummary is
			# currently doing double duty as both edit summary and section title. Right now this
			# is just to allow API edits to work around this limitation, but this should be
			# incorporated into the actual edit form when EditPage is rewritten (Bugs 18654, 26312).
			$this->sectiontitle = $request->getText( 'wpSectionTitle' );
			$this->sectiontitle = preg_replace( '/^\s*=+\s*(.*?)\s*=+\s*$/', '$1', $this->sectiontitle );

			$this->edittime = $request->getVal( 'wpEdittime' );
			$this->editRevId = $request->getIntOrNull( 'editRevId' );
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
				// If we receive the last parameter of the request, we can fairly
				// claim the POST request has not been truncated.

				// TODO: softened the check for cutover.  Once we determine
				// that it is safe, we should complete the transition by
				// removing the "edittime" clause.
				$this->incompleteForm = ( !$request->getVal( 'wpUltimateParam' )
					&& is_null( $this->edittime ) );
			}
			if ( $this->incompleteForm ) {
				# If the form is incomplete, force to preview.
				wfDebug( __METHOD__ . ": Form data appears to be incomplete\n" );
				wfDebug( "POST DATA: " . var_export( $_POST, true ) . "\n" );
				$this->preview = true;
			} else {
				$this->preview = $request->getCheck( 'wpPreview' );
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

			$user = $this->context->getUser();
			# Don't force edit summaries when a user is editing their own user or talk page
			if ( ( $this->mTitle->mNamespace == NS_USER || $this->mTitle->mNamespace == NS_USER_TALK )
				&& $this->mTitle->getText() == $user->getName()
			) {
				$this->allowBlankSummary = true;
			} else {
				$this->allowBlankSummary = $request->getBool( 'wpIgnoreBlankSummary' )
					|| !$user->getOption( 'forceeditsummary' );
			}

			$this->autoSumm = $request->getText( 'wpAutoSummary' );

			$this->allowBlankArticle = $request->getBool( 'wpIgnoreBlankArticle' );
			$this->allowSelfRedirect = $request->getBool( 'wpIgnoreSelfRedirect' );

			$changeTags = $request->getVal( 'wpChangeTags' );
			if ( is_null( $changeTags ) || $changeTags === '' ) {
				$this->changeTags = [];
			} else {
				$this->changeTags = array_filter( array_map( 'trim', explode( ',',
					$changeTags ) ) );
			}
		} else {
			# Not a posted form? Start with nothing.
			wfDebug( __METHOD__ . ": Not a posted form.\n" );
			$this->textbox1 = '';
			$this->summary = '';
			$this->sectiontitle = '';
			$this->edittime = '';
			$this->editRevId = null;
			$this->starttime = wfTimestampNow();
			$this->edit = false;
			$this->preview = false;
			$this->save = false;
			$this->diff = false;
			$this->minoredit = false;
			// Watch may be overridden by request parameters
			$this->watchthis = $request->getBool( 'watchthis', false );
			$this->recreate = false;

			// When creating a new section, we can preload a section title by passing it as the
			// preloadtitle parameter in the URL (T15100)
			if ( $this->section == 'new' && $request->getVal( 'preloadtitle' ) ) {
				$this->sectiontitle = $request->getVal( 'preloadtitle' );
				// Once wpSummary isn't being use for setting section titles, we should delete this.
				$this->summary = $request->getVal( 'preloadtitle' );
			} elseif ( $this->section != 'new' && $request->getVal( 'summary' ) ) {
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
		$this->parentRevId = $request->getInt( 'parentRevId' );

		$this->bot = $request->getBool( 'bot', true );
		$this->nosummary = $request->getBool( 'nosummary' );

		// May be overridden by revision.
		$this->contentModel = $request->getText( 'model', $this->contentModel );
		// May be overridden by revision.
		$this->contentFormat = $request->getText( 'format', $this->contentFormat );

		try {
			$handler = ContentHandler::getForModelID( $this->contentModel );
		} catch ( MWUnknownContentModelException $e ) {
			throw new ErrorPageError(
				'editpage-invalidcontentmodel-title',
				'editpage-invalidcontentmodel-text',
				[ wfEscapeWikiText( $this->contentModel ) ]
			);
		}

		if ( !$handler->isSupportedFormat( $this->contentFormat ) ) {
			throw new ErrorPageError(
				'editpage-notsupportedcontentformat-title',
				'editpage-notsupportedcontentformat-text',
				[
					wfEscapeWikiText( $this->contentFormat ),
					wfEscapeWikiText( ContentHandler::getLocalizedName( $this->contentModel ) )
				]
			);
		}

		/**
		 * @todo Check if the desired model is allowed in this namespace, and if
		 *   a transition from the page's current model to the new model is
		 *   allowed.
		 */

		$this->editintro = $request->getText( 'editintro',
			// Custom edit intro for new sections
			$this->section === 'new' ? 'MediaWiki:addsection-editintro' : '' );

		// Allow extensions to modify form data
		Hooks::run( 'EditPage::importFormData', [ $this, $request ] );
	}

	/**
	 * Subpage overridable method for extracting the page content data from the
	 * posted form to be placed in $this->textbox1, if using customized input
	 * this method should be overridden and return the page text that will be used
	 * for saving, preview parsing and so on...
	 *
	 * @param WebRequest &$request
	 * @return string|null
	 */
	protected function importContentFormData( &$request ) {
		return; // Don't do anything, EditPage already extracted wpTextbox1
	}

	/**
	 * Initialise form fields in the object
	 * Called on the first invocation, e.g. when a user clicks an edit link
	 * @return bool If the requested section is valid
	 */
	public function initialiseForm() {
		$this->edittime = $this->page->getTimestamp();
		$this->editRevId = $this->page->getLatest();

		$content = $this->getContentObject( false ); # TODO: track content object?!
		if ( $content === false ) {
			return false;
		}
		$this->textbox1 = $this->toEditText( $content );

		$user = $this->context->getUser();
		// activate checkboxes if user wants them to be always active
		# Sort out the "watch" checkbox
		if ( $user->getOption( 'watchdefault' ) ) {
			# Watch all edits
			$this->watchthis = true;
		} elseif ( $user->getOption( 'watchcreations' ) && !$this->mTitle->exists() ) {
			# Watch creations
			$this->watchthis = true;
		} elseif ( $user->isWatched( $this->mTitle ) ) {
			# Already watched
			$this->watchthis = true;
		}
		if ( $user->getOption( 'minordefault' ) && !$this->isNew ) {
			$this->minoredit = true;
		}
		if ( $this->textbox1 === false ) {
			return false;
		}
		return true;
	}

	/**
	 * @param Content|null $def_content The default value to return
	 *
	 * @return Content|null Content on success, $def_content for invalid sections
	 *
	 * @since 1.21
	 */
	protected function getContentObject( $def_content = null ) {
		global $wgContLang;

		$content = false;

		$user = $this->context->getUser();
		$request = $this->context->getRequest();
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
				$preload = $request->getVal( 'preload',
					// Custom preload text for new sections
					$this->section === 'new' ? 'MediaWiki:addsection-preload' : '' );
				$params = $request->getArray( 'preloadparams', [] );

				$content = $this->getPreloadedContent( $preload, $params );
			}
		// For existing pages, get text based on "undo" or section parameters.
		} else {
			if ( $this->section != '' ) {
				// Get section edit text (returns $def_text for invalid sections)
				$orig = $this->getOriginalContent( $user );
				$content = $orig ? $orig->getSection( $this->section ) : null;

				if ( !$content ) {
					$content = $def_content;
				}
			} else {
				$undoafter = $request->getInt( 'undoafter' );
				$undo = $request->getInt( 'undo' );

				if ( $undo > 0 && $undoafter > 0 ) {
					$undorev = Revision::newFromId( $undo );
					$oldrev = Revision::newFromId( $undoafter );

					# Sanity check, make sure it's the right page,
					# the revisions exist and they were not deleted.
					# Otherwise, $content will be left as-is.
					if ( !is_null( $undorev ) && !is_null( $oldrev ) &&
						!$undorev->isDeleted( Revision::DELETED_TEXT ) &&
						!$oldrev->isDeleted( Revision::DELETED_TEXT )
					) {
						$content = $this->page->getUndoContent( $undorev, $oldrev );

						if ( $content === false ) {
							# Warn the user that something went wrong
							$undoMsg = 'failure';
						} else {
							$oldContent = $this->page->getContent( Revision::RAW );
							$popts = ParserOptions::newFromUserAndLang( $user, $wgContLang );
							$newContent = $content->preSaveTransform( $this->mTitle, $user, $popts );
							if ( $newContent->getModel() !== $oldContent->getModel() ) {
								// The undo may change content
								// model if its reverting the top
								// edit. This can result in
								// mismatched content model/format.
								$this->contentModel = $newContent->getModel();
								$this->contentFormat = $oldrev->getContentFormat();
							}

							if ( $newContent->equals( $oldContent ) ) {
								# Tell the user that the undo results in no change,
								# i.e. the revisions were already undone.
								$undoMsg = 'nochange';
								$content = false;
							} else {
								# Inform the user of our success and set an automatic edit summary
								$undoMsg = 'success';

								# If we just undid one rev, use an autosummary
								$firstrev = $oldrev->getNext();
								if ( $firstrev && $firstrev->getId() == $undo ) {
									$userText = $undorev->getUserText();
									if ( $userText === '' ) {
										$undoSummary = $this->context->msg(
											'undo-summary-username-hidden',
											$undo
										)->inContentLanguage()->text();
									} else {
										$undoSummary = $this->context->msg(
											'undo-summary',
											$undo,
											$userText
										)->inContentLanguage()->text();
									}
									if ( $this->summary === '' ) {
										$this->summary = $undoSummary;
									} else {
										$this->summary = $undoSummary . $this->context->msg( 'colon-separator' )
											->inContentLanguage()->text() . $this->summary;
									}
									$this->undidRev = $undo;
								}
								$this->formtype = 'diff';
							}
						}
					} else {
						// Failed basic sanity checks.
						// Older revisions may have been removed since the link
						// was created, or we may simply have got bogus input.
						$undoMsg = 'norev';
					}

					$out = $this->context->getOutput();
					// Messages: undo-success, undo-failure, undo-norev, undo-nochange
					$class = ( $undoMsg == 'success' ? '' : 'error ' ) . "mw-undo-{$undoMsg}";
					$this->editFormPageTop .= $out->parse( "<div class=\"{$class}\">" .
						$this->context->msg( 'undo-' . $undoMsg )->plain() . '</div>', true, /* interface */true );
				}

				if ( $content === false ) {
					$content = $this->getOriginalContent( $user );
				}
			}
		}

		return $content;
	}

	/**
	 * Get the content of the wanted revision, without section extraction.
	 *
	 * The result of this function can be used to compare user's input with
	 * section replaced in its context (using WikiPage::replaceSectionAtRev())
	 * to the original text of the edit.
	 *
	 * This differs from Article::getContent() that when a missing revision is
	 * encountered the result will be null and not the
	 * 'missing-revision' message.
	 *
	 * @since 1.19
	 * @param User $user The user to get the revision for
	 * @return Content|null
	 */
	private function getOriginalContent( User $user ) {
		if ( $this->section == 'new' ) {
			return $this->getCurrentContent();
		}
		$revision = $this->mArticle->getRevisionFetched();
		if ( $revision === null ) {
			$handler = ContentHandler::getForModelID( $this->contentModel );
			return $handler->makeEmptyContent();
		}
		$content = $revision->getContent( Revision::FOR_THIS_USER, $user );
		return $content;
	}

	/**
	 * Get the edit's parent revision ID
	 *
	 * The "parent" revision is the ancestor that should be recorded in this
	 * page's revision history.  It is either the revision ID of the in-memory
	 * article content, or in the case of a 3-way merge in order to rebase
	 * across a recoverable edit conflict, the ID of the newer revision to
	 * which we have rebased this page.
	 *
	 * @since 1.27
	 * @return int Revision ID
	 */
	public function getParentRevId() {
		if ( $this->parentRevId ) {
			return $this->parentRevId;
		} else {
			return $this->mArticle->getRevIdFetched();
		}
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
		$rev = $this->page->getRevision();
		$content = $rev ? $rev->getContent( Revision::RAW ) : null;

		if ( $content === false || $content === null ) {
			$handler = ContentHandler::getForModelID( $this->contentModel );
			return $handler->makeEmptyContent();
		} elseif ( !$this->undidRev ) {
			// Content models should always be the same since we error
			// out if they are different before this point (in ->edit()).
			// The exception being, during an undo, the current revision might
			// differ from the prior revision.
			$logger = LoggerFactory::getInstance( 'editpage' );
			if ( $this->contentModel !== $rev->getContentModel() ) {
				$logger->warning( "Overriding content model from current edit {prev} to {new}", [
					'prev' => $this->contentModel,
					'new' => $rev->getContentModel(),
					'title' => $this->getTitle()->getPrefixedDBkey(),
					'method' => __METHOD__
				] );
				$this->contentModel = $rev->getContentModel();
			}

			// Given that the content models should match, the current selected
			// format should be supported.
			if ( !$content->isSupportedFormat( $this->contentFormat ) ) {
				$logger->warning( "Current revision content format unsupported. Overriding {prev} to {new}", [

					'prev' => $this->contentFormat,
					'new' => $rev->getContentFormat(),
					'title' => $this->getTitle()->getPrefixedDBkey(),
					'method' => __METHOD__
				] );
				$this->contentFormat = $rev->getContentFormat();
			}
		}
		return $content;
	}

	/**
	 * Use this method before edit() to preload some content into the edit box
	 *
	 * @param Content $content
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
	 * @param string $preload Representing the title to preload from.
	 * @param array $params Parameters to use (interface-message style) in the preloaded text
	 *
	 * @return Content
	 *
	 * @since 1.21
	 */
	protected function getPreloadedContent( $preload, $params = [] ) {
		if ( !empty( $this->mPreloadContent ) ) {
			return $this->mPreloadContent;
		}

		$handler = ContentHandler::getForModelID( $this->contentModel );

		if ( $preload === '' ) {
			return $handler->makeEmptyContent();
		}

		$user = $this->context->getUser();
		$title = Title::newFromText( $preload );
		# Check for existence to avoid getting MediaWiki:Noarticletext
		if ( $title === null || !$title->exists() || !$title->userCan( 'read', $user ) ) {
			// TODO: somehow show a warning to the user!
			return $handler->makeEmptyContent();
		}

		$page = WikiPage::factory( $title );
		if ( $page->isRedirect() ) {
			$title = $page->getRedirectTarget();
			# Same as before
			if ( $title === null || !$title->exists() || !$title->userCan( 'read', $user ) ) {
				// TODO: somehow show a warning to the user!
				return $handler->makeEmptyContent();
			}
			$page = WikiPage::factory( $title );
		}

		$parserOptions = ParserOptions::newFromUser( $user );
		$content = $page->getContent( Revision::RAW );

		if ( !$content ) {
			// TODO: somehow show a warning to the user!
			return $handler->makeEmptyContent();
		}

		if ( $content->getModel() !== $handler->getModelID() ) {
			$converted = $content->convert( $handler->getModelID() );

			if ( !$converted ) {
				// TODO: somehow show a warning to the user!
				wfDebug( "Attempt to preload incompatible content: " .
					"can't convert " . $content->getModel() .
					" to " . $handler->getModelID() );

				return $handler->makeEmptyContent();
			}

			$content = $converted;
		}

		return $content->preloadTransform( $title, $parserOptions, $params );
	}

	/**
	 * Make sure the form isn't faking a user's credentials.
	 *
	 * @param WebRequest &$request
	 * @return bool
	 * @private
	 */
	public function tokenOk( &$request ) {
		$token = $request->getVal( 'wpEditToken' );
		$user = $this->context->getUser();
		$this->mTokenOk = $user->matchEditToken( $token );
		$this->mTokenOkExceptSuffix = $user->matchEditTokenNoSuffix( $token );
		return $this->mTokenOk;
	}

	/**
	 * Sets post-edit cookie indicating the user just saved a particular revision.
	 *
	 * This uses a temporary cookie for each revision ID so separate saves will never
	 * interfere with each other.
	 *
	 * Article::view deletes the cookie on server-side after the redirect and
	 * converts the value to the global JavaScript variable wgPostEdit.
	 *
	 * If the variable were set on the server, it would be cached, which is unwanted
	 * since the post-edit state should only apply to the load right after the save.
	 *
	 * @param int $statusValue The status value (to check for new article status)
	 */
	protected function setPostEditCookie( $statusValue ) {
		$revisionId = $this->page->getLatest();
		$postEditKey = self::POST_EDIT_COOKIE_KEY_PREFIX . $revisionId;

		$val = 'saved';
		if ( $statusValue == self::AS_SUCCESS_NEW_ARTICLE ) {
			$val = 'created';
		} elseif ( $this->oldid ) {
			$val = 'restored';
		}

		$response = $this->context->getRequest()->response();
		$response->setCookie( $postEditKey, $val, time() + self::POST_EDIT_COOKIE_DURATION );
	}

	/**
	 * Attempt submission
	 * @param array|bool &$resultDetails See docs for $result in internalAttemptSave
	 * @throws UserBlockedError|ReadOnlyError|ThrottledError|PermissionsError
	 * @return Status The resulting status object.
	 */
	public function attemptSave( &$resultDetails = false ) {
		# Allow bots to exempt some edits from bot flagging
		$bot = $this->context->getUser()->isAllowed( 'bot' ) && $this->bot;
		$status = $this->internalAttemptSave( $resultDetails, $bot );

		Hooks::run( 'EditPage::attemptSave:after', [ $this, $status, $resultDetails ] );

		return $status;
	}

	/**
	 * Log when a page was successfully saved after the edit conflict view
	 */
	private function incrementResolvedConflicts() {
		if ( $this->context->getRequest()->getText( 'mode' ) !== 'conflict' ) {
			return;
		}

		$this->getEditConflictHelper()->incrementResolvedStats();
	}

	/**
	 * Handle status, such as after attempt save
	 *
	 * @param Status $status
	 * @param array|bool $resultDetails
	 *
	 * @throws ErrorPageError
	 * @return bool False, if output is done, true if rest of the form should be displayed
	 */
	private function handleStatus( Status $status, $resultDetails ) {
		/**
		 * @todo FIXME: once the interface for internalAttemptSave() is made
		 *   nicer, this should use the message in $status
		 */
		if ( $status->value == self::AS_SUCCESS_UPDATE
			|| $status->value == self::AS_SUCCESS_NEW_ARTICLE
		) {
			$this->incrementResolvedConflicts();

			$this->didSave = true;
			if ( !$resultDetails['nullEdit'] ) {
				$this->setPostEditCookie( $status->value );
			}
		}

		$out = $this->context->getOutput();

		// "wpExtraQueryRedirect" is a hidden input to modify
		// after save URL and is not used by actual edit form
		$request = $this->context->getRequest();
		$extraQueryRedirect = $request->getVal( 'wpExtraQueryRedirect' );

		switch ( $status->value ) {
			case self::AS_HOOK_ERROR_EXPECTED:
			case self::AS_CONTENT_TOO_BIG:
			case self::AS_ARTICLE_WAS_DELETED:
			case self::AS_CONFLICT_DETECTED:
			case self::AS_SUMMARY_NEEDED:
			case self::AS_TEXTBOX_EMPTY:
			case self::AS_MAX_ARTICLE_SIZE_EXCEEDED:
			case self::AS_END:
			case self::AS_BLANK_ARTICLE:
			case self::AS_SELF_REDIRECT:
				return true;

			case self::AS_HOOK_ERROR:
				return false;

			case self::AS_CANNOT_USE_CUSTOM_MODEL:
			case self::AS_PARSE_ERROR:
			case self::AS_UNICODE_NOT_SUPPORTED:
				$out->addWikiText( '<div class="error">' . "\n" . $status->getWikiText() . '</div>' );
				return true;

			case self::AS_SUCCESS_NEW_ARTICLE:
				$query = $resultDetails['redirect'] ? 'redirect=no' : '';
				if ( $extraQueryRedirect ) {
					if ( $query === '' ) {
						$query = $extraQueryRedirect;
					} else {
						$query = $query . '&' . $extraQueryRedirect;
					}
				}
				$anchor = isset( $resultDetails['sectionanchor'] ) ? $resultDetails['sectionanchor'] : '';
				$out->redirect( $this->mTitle->getFullURL( $query ) . $anchor );
				return false;

			case self::AS_SUCCESS_UPDATE:
				$extraQuery = '';
				$sectionanchor = $resultDetails['sectionanchor'];

				// Give extensions a chance to modify URL query on update
				Hooks::run(
					'ArticleUpdateBeforeRedirect',
					[ $this->mArticle, &$sectionanchor, &$extraQuery ]
				);

				if ( $resultDetails['redirect'] ) {
					if ( $extraQuery == '' ) {
						$extraQuery = 'redirect=no';
					} else {
						$extraQuery = 'redirect=no&' . $extraQuery;
					}
				}
				if ( $extraQueryRedirect ) {
					if ( $extraQuery === '' ) {
						$extraQuery = $extraQueryRedirect;
					} else {
						$extraQuery = $extraQuery . '&' . $extraQueryRedirect;
					}
				}

				$out->redirect( $this->mTitle->getFullURL( $extraQuery ) . $sectionanchor );
				return false;

			case self::AS_SPAM_ERROR:
				$this->spamPageWithContent( $resultDetails['spam'] );
				return false;

			case self::AS_BLOCKED_PAGE_FOR_USER:
				throw new UserBlockedError( $this->context->getUser()->getBlock() );

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

			case self::AS_NO_CHANGE_CONTENT_MODEL:
				throw new PermissionsError( 'editcontentmodel' );

			default:
				// We don't recognize $status->value. The only way that can happen
				// is if an extension hook aborted from inside ArticleSave.
				// Render the status object into $this->hookError
				// FIXME this sucks, we should just use the Status object throughout
				$this->hookError = '<div class="error">' ."\n" . $status->getWikiText() .
					'</div>';
				return true;
		}
	}

	/**
	 * Run hooks that can filter edits just before they get saved.
	 *
	 * @param Content $content The Content to filter.
	 * @param Status $status For reporting the outcome to the caller
	 * @param User $user The user performing the edit
	 *
	 * @return bool
	 */
	protected function runPostMergeFilters( Content $content, Status $status, User $user ) {
		// Run old style post-section-merge edit filter
		if ( $this->hookError != '' ) {
			# ...or the hook could be expecting us to produce an error
			$status->fatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR_EXPECTED;
			return false;
		}

		// Run new style post-section-merge edit filter
		if ( !Hooks::run( 'EditFilterMergedContent',
				[ $this->context, $content, $status, $this->summary,
				$user, $this->minoredit ] )
		) {
			# Error messages etc. could be handled within the hook...
			if ( $status->isGood() ) {
				$status->fatal( 'hookaborted' );
				// Not setting $this->hookError here is a hack to allow the hook
				// to cause a return to the edit page without $this->hookError
				// being set. This is used by ConfirmEdit to display a captcha
				// without any error message cruft.
			} else {
				$this->hookError = $this->formatStatusErrors( $status );
			}
			// Use the existing $status->value if the hook set it
			if ( !$status->value ) {
				$status->value = self::AS_HOOK_ERROR;
			}
			return false;
		} elseif ( !$status->isOK() ) {
			# ...or the hook could be expecting us to produce an error
			// FIXME this sucks, we should just use the Status object throughout
			$this->hookError = $this->formatStatusErrors( $status );
			$status->fatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR_EXPECTED;
			return false;
		}

		return true;
	}

	/**
	 * Wrap status errors in an errorbox for increased visibility
	 *
	 * @param Status $status
	 * @return string Wikitext
	 */
	private function formatStatusErrors( Status $status ) {
		$errmsg = $status->getWikiText(
			'edit-error-short',
			'edit-error-long',
			$this->context->getLanguage()
		);
		return <<<ERROR
<div class="errorbox">
{$errmsg}
</div>
<br clear="all" />
ERROR;
	}

	/**
	 * Return the summary to be used for a new section.
	 *
	 * @param string $sectionanchor Set to the section anchor text
	 * @return string
	 */
	private function newSectionSummary( &$sectionanchor = null ) {
		global $wgParser;

		if ( $this->sectiontitle !== '' ) {
			$sectionanchor = $this->guessSectionName( $this->sectiontitle );
			// If no edit summary was specified, create one automatically from the section
			// title and have it link to the new section. Otherwise, respect the summary as
			// passed.
			if ( $this->summary === '' ) {
				$cleanSectionTitle = $wgParser->stripSectionName( $this->sectiontitle );
				return $this->context->msg( 'newsectionsummary' )
					->rawParams( $cleanSectionTitle )->inContentLanguage()->text();
			}
		} elseif ( $this->summary !== '' ) {
			$sectionanchor = $this->guessSectionName( $this->summary );
			# This is a new section, so create a link to the new section
			# in the revision summary.
			$cleanSummary = $wgParser->stripSectionName( $this->summary );
			return $this->context->msg( 'newsectionsummary' )
				->rawParams( $cleanSummary )->inContentLanguage()->text();
		}
		return $this->summary;
	}

	/**
	 * Attempt submission (no UI)
	 *
	 * @param array &$result Array to add statuses to, currently with the
	 *   possible keys:
	 *   - spam (string): Spam string from content if any spam is detected by
	 *     matchSpamRegex.
	 *   - sectionanchor (string): Section anchor for a section save.
	 *   - nullEdit (bool): Set if doEditContent is OK.  True if null edit,
	 *     false otherwise.
	 *   - redirect (bool): Set if doEditContent is OK. True if resulting
	 *     revision is a redirect.
	 * @param bool $bot True if edit is being made under the bot right.
	 *
	 * @return Status Status object, possibly with a message, but always with
	 *   one of the AS_* constants in $status->value,
	 *
	 * @todo FIXME: This interface is TERRIBLE, but hard to get rid of due to
	 *   various error display idiosyncrasies. There are also lots of cases
	 *   where error metadata is set in the object and retrieved later instead
	 *   of being returned, e.g. AS_CONTENT_TOO_BIG and
	 *   AS_BLOCKED_PAGE_FOR_USER. All that stuff needs to be cleaned up some
	 * time.
	 */
	public function internalAttemptSave( &$result, $bot = false ) {
		$status = Status::newGood();
		$user = $this->context->getUser();

		if ( !Hooks::run( 'EditPage::attemptSave', [ $this ] ) ) {
			wfDebug( "Hook 'EditPage::attemptSave' aborted article saving\n" );
			$status->fatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR;
			return $status;
		}

		if ( $this->unicodeCheck !== self::UNICODE_CHECK ) {
			$status->fatal( 'unicode-support-fail' );
			$status->value = self::AS_UNICODE_NOT_SUPPORTED;
			return $status;
		}

		$request = $this->context->getRequest();
		$spam = $request->getText( 'wpAntispam' );
		if ( $spam !== '' ) {
			wfDebugLog(
				'SimpleAntiSpam',
				$user->getName() .
				' editing "' .
				$this->mTitle->getPrefixedText() .
				'" submitted bogus field "' .
				$spam .
				'"'
			);
			$status->fatal( 'spamprotectionmatch', false );
			$status->value = self::AS_SPAM_ERROR;
			return $status;
		}

		try {
			# Construct Content object
			$textbox_content = $this->toEditContent( $this->textbox1 );
		} catch ( MWContentSerializationException $ex ) {
			$status->fatal(
				'content-failed-to-parse',
				$this->contentModel,
				$this->contentFormat,
				$ex->getMessage()
			);
			$status->value = self::AS_PARSE_ERROR;
			return $status;
		}

		# Check image redirect
		if ( $this->mTitle->getNamespace() == NS_FILE &&
			$textbox_content->isRedirect() &&
			!$user->isAllowed( 'upload' )
		) {
				$code = $user->isAnon() ? self::AS_IMAGE_REDIRECT_ANON : self::AS_IMAGE_REDIRECT_LOGGED;
				$status->setResult( false, $code );

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
			$ip = $request->getIP();
			$pdbk = $this->mTitle->getPrefixedDBkey();
			$match = str_replace( "\n", '', $match );
			wfDebugLog( 'SpamRegex', "$ip spam regex hit [[$pdbk]]: \"$match\"" );
			$status->fatal( 'spamprotectionmatch', $match );
			$status->value = self::AS_SPAM_ERROR;
			return $status;
		}
		if ( !Hooks::run(
			'EditFilter',
			[ $this, $this->textbox1, $this->section, &$this->hookError, $this->summary ] )
		) {
			# Error messages etc. could be handled within the hook...
			$status->fatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR;
			return $status;
		} elseif ( $this->hookError != '' ) {
			# ...or the hook could be expecting us to produce an error
			$status->fatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR_EXPECTED;
			return $status;
		}

		if ( $user->isBlockedFrom( $this->mTitle, false ) ) {
			// Auto-block user's IP if the account was "hard" blocked
			if ( !wfReadOnly() ) {
				$user->spreadAnyEditBlock();
			}
			# Check block state against master, thus 'false'.
			$status->setResult( false, self::AS_BLOCKED_PAGE_FOR_USER );
			return $status;
		}

		$this->contentLength = strlen( $this->textbox1 );
		$config = $this->context->getConfig();
		$maxArticleSize = $config->get( 'MaxArticleSize' );
		if ( $this->contentLength > $maxArticleSize * 1024 ) {
			// Error will be displayed by showEditForm()
			$this->tooBig = true;
			$status->setResult( false, self::AS_CONTENT_TOO_BIG );
			return $status;
		}

		if ( !$user->isAllowed( 'edit' ) ) {
			if ( $user->isAnon() ) {
				$status->setResult( false, self::AS_READ_ONLY_PAGE_ANON );
				return $status;
			} else {
				$status->fatal( 'readonlytext' );
				$status->value = self::AS_READ_ONLY_PAGE_LOGGED;
				return $status;
			}
		}

		$changingContentModel = false;
		if ( $this->contentModel !== $this->mTitle->getContentModel() ) {
			if ( !$config->get( 'ContentHandlerUseDB' ) ) {
				$status->fatal( 'editpage-cannot-use-custom-model' );
				$status->value = self::AS_CANNOT_USE_CUSTOM_MODEL;
				return $status;
			} elseif ( !$user->isAllowed( 'editcontentmodel' ) ) {
				$status->setResult( false, self::AS_NO_CHANGE_CONTENT_MODEL );
				return $status;
			}
			// Make sure the user can edit the page under the new content model too
			$titleWithNewContentModel = clone $this->mTitle;
			$titleWithNewContentModel->setContentModel( $this->contentModel );
			if ( !$titleWithNewContentModel->userCan( 'editcontentmodel', $user )
				|| !$titleWithNewContentModel->userCan( 'edit', $user )
			) {
				$status->setResult( false, self::AS_NO_CHANGE_CONTENT_MODEL );
				return $status;
			}

			$changingContentModel = true;
			$oldContentModel = $this->mTitle->getContentModel();
		}

		if ( $this->changeTags ) {
			$changeTagsStatus = ChangeTags::canAddTagsAccompanyingChange(
				$this->changeTags, $user );
			if ( !$changeTagsStatus->isOK() ) {
				$changeTagsStatus->value = self::AS_CHANGE_TAG_ERROR;
				return $changeTagsStatus;
			}
		}

		if ( wfReadOnly() ) {
			$status->fatal( 'readonlytext' );
			$status->value = self::AS_READ_ONLY_PAGE;
			return $status;
		}
		if ( $user->pingLimiter() || $user->pingLimiter( 'linkpurge', 0 )
			|| ( $changingContentModel && $user->pingLimiter( 'editcontentmodel' ) )
		) {
			$status->fatal( 'actionthrottledtext' );
			$status->value = self::AS_RATE_LIMITED;
			return $status;
		}

		# If the article has been deleted while editing, don't save it without
		# confirmation
		if ( $this->wasDeletedSinceLastEdit() && !$this->recreate ) {
			$status->setResult( false, self::AS_ARTICLE_WAS_DELETED );
			return $status;
		}

		# Load the page data from the master. If anything changes in the meantime,
		# we detect it by using page_latest like a token in a 1 try compare-and-swap.
		$this->page->loadPageData( 'fromdbmaster' );
		$new = !$this->page->exists();

		if ( $new ) {
			// Late check for create permission, just in case *PARANOIA*
			if ( !$this->mTitle->userCan( 'create', $user ) ) {
				$status->fatal( 'nocreatetext' );
				$status->value = self::AS_NO_CREATE_PERMISSION;
				wfDebug( __METHOD__ . ": no create permission\n" );
				return $status;
			}

			// Don't save a new page if it's blank or if it's a MediaWiki:
			// message with content equivalent to default (allow empty pages
			// in this case to disable messages, see T52124)
			$defaultMessageText = $this->mTitle->getDefaultMessageText();
			if ( $this->mTitle->getNamespace() === NS_MEDIAWIKI && $defaultMessageText !== false ) {
				$defaultText = $defaultMessageText;
			} else {
				$defaultText = '';
			}

			if ( !$this->allowBlankArticle && $this->textbox1 === $defaultText ) {
				$this->blankArticle = true;
				$status->fatal( 'blankarticle' );
				$status->setResult( false, self::AS_BLANK_ARTICLE );
				return $status;
			}

			if ( !$this->runPostMergeFilters( $textbox_content, $status, $user ) ) {
				return $status;
			}

			$content = $textbox_content;

			$result['sectionanchor'] = '';
			if ( $this->section == 'new' ) {
				if ( $this->sectiontitle !== '' ) {
					// Insert the section title above the content.
					$content = $content->addSectionHeader( $this->sectiontitle );
				} elseif ( $this->summary !== '' ) {
					// Insert the section title above the content.
					$content = $content->addSectionHeader( $this->summary );
				}
				$this->summary = $this->newSectionSummary( $result['sectionanchor'] );
			}

			$status->value = self::AS_SUCCESS_NEW_ARTICLE;

		} else { # not $new

			# Article exists. Check for edit conflict.

			$this->page->clear(); # Force reload of dates, etc.
			$timestamp = $this->page->getTimestamp();
			$latest = $this->page->getLatest();

			wfDebug( "timestamp: {$timestamp}, edittime: {$this->edittime}\n" );

			// Check editRevId if set, which handles same-second timestamp collisions
			if ( $timestamp != $this->edittime
				|| ( $this->editRevId !== null && $this->editRevId != $latest )
			) {
				$this->isConflict = true;
				if ( $this->section == 'new' ) {
					if ( $this->page->getUserText() == $user->getName() &&
						$this->page->getComment() == $this->newSectionSummary()
					) {
						// Probably a duplicate submission of a new comment.
						// This can happen when CDN resends a request after
						// a timeout but the first one actually went through.
						wfDebug( __METHOD__
							. ": duplicate new section submission; trigger edit conflict!\n" );
					} else {
						// New comment; suppress conflict.
						$this->isConflict = false;
						wfDebug( __METHOD__ . ": conflict suppressed; new section\n" );
					}
				} elseif ( $this->section == ''
					&& Revision::userWasLastToEdit(
						DB_MASTER, $this->mTitle->getArticleID(),
						$user->getId(), $this->edittime
					)
				) {
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
				wfDebug( __METHOD__
					. ": conflict! getting section '{$this->section}' for time '{$this->edittime}'"
					. " (id '{$this->editRevId}') (article time '{$timestamp}')\n" );
				// @TODO: replaceSectionAtRev() with base ID (not prior current) for ?oldid=X case
				// ...or disable section editing for non-current revisions (not exposed anyway).
				if ( $this->editRevId !== null ) {
					$content = $this->page->replaceSectionAtRev(
						$this->section,
						$textbox_content,
						$sectionTitle,
						$this->editRevId
					);
				} else {
					$content = $this->page->replaceSectionContent(
						$this->section,
						$textbox_content,
						$sectionTitle,
						$this->edittime
					);
				}
			} else {
				wfDebug( __METHOD__ . ": getting section '{$this->section}'\n" );
				$content = $this->page->replaceSectionContent(
					$this->section,
					$textbox_content,
					$sectionTitle
				);
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
				return $status;
			}

			if ( !$this->runPostMergeFilters( $content, $status, $user ) ) {
				return $status;
			}

			if ( $this->section == 'new' ) {
				// Handle the user preference to force summaries here
				if ( !$this->allowBlankSummary && trim( $this->summary ) == '' ) {
					$this->missingSummary = true;
					$status->fatal( 'missingsummary' ); // or 'missingcommentheader' if $section == 'new'. Blegh
					$status->value = self::AS_SUMMARY_NEEDED;
					return $status;
				}

				// Do not allow the user to post an empty comment
				if ( $this->textbox1 == '' ) {
					$this->missingComment = true;
					$status->fatal( 'missingcommenttext' );
					$status->value = self::AS_TEXTBOX_EMPTY;
					return $status;
				}
			} elseif ( !$this->allowBlankSummary
				&& !$content->equals( $this->getOriginalContent( $user ) )
				&& !$content->isRedirect()
				&& md5( $this->summary ) == $this->autoSumm
			) {
				$this->missingSummary = true;
				$status->fatal( 'missingsummary' );
				$status->value = self::AS_SUMMARY_NEEDED;
				return $status;
			}

			# All's well
			$sectionanchor = '';
			if ( $this->section == 'new' ) {
				$this->summary = $this->newSectionSummary( $sectionanchor );
			} elseif ( $this->section != '' ) {
				# Try to get a section anchor from the section source, redirect
				# to edited section if header found.
				# XXX: Might be better to integrate this into Article::replaceSectionAtRev
				# for duplicate heading checking and maybe parsing.
				$hasmatch = preg_match( "/^ *([=]{1,6})(.*?)(\\1) *\\n/i", $this->textbox1, $matches );
				# We can't deal with anchors, includes, html etc in the header for now,
				# headline would need to be parsed to improve this.
				if ( $hasmatch && strlen( $matches[2] ) > 0 ) {
					$sectionanchor = $this->guessSectionName( $matches[2] );
				}
			}
			$result['sectionanchor'] = $sectionanchor;

			// Save errors may fall down to the edit form, but we've now
			// merged the section into full text. Clear the section field
			// so that later submission of conflict forms won't try to
			// replace that into a duplicated mess.
			$this->textbox1 = $this->toEditText( $content );
			$this->section = '';

			$status->value = self::AS_SUCCESS_UPDATE;
		}

		if ( !$this->allowSelfRedirect
			&& $content->isRedirect()
			&& $content->getRedirectTarget()->equals( $this->getTitle() )
		) {
			// If the page already redirects to itself, don't warn.
			$currentTarget = $this->getCurrentContent()->getRedirectTarget();
			if ( !$currentTarget || !$currentTarget->equals( $this->getTitle() ) ) {
				$this->selfRedirect = true;
				$status->fatal( 'selfredirect' );
				$status->value = self::AS_SELF_REDIRECT;
				return $status;
			}
		}

		// Check for length errors again now that the section is merged in
		$this->contentLength = strlen( $this->toEditText( $content ) );
		if ( $this->contentLength > $maxArticleSize * 1024 ) {
			$this->tooBig = true;
			$status->setResult( false, self::AS_MAX_ARTICLE_SIZE_EXCEEDED );
			return $status;
		}

		$flags = EDIT_AUTOSUMMARY |
			( $new ? EDIT_NEW : EDIT_UPDATE ) |
			( ( $this->minoredit && !$this->isNew ) ? EDIT_MINOR : 0 ) |
			( $bot ? EDIT_FORCE_BOT : 0 );

		$doEditStatus = $this->page->doEditContent(
			$content,
			$this->summary,
			$flags,
			false,
			$user,
			$content->getDefaultFormat(),
			$this->changeTags,
			$this->undidRev
		);

		if ( !$doEditStatus->isOK() ) {
			// Failure from doEdit()
			// Show the edit conflict page for certain recognized errors from doEdit(),
			// but don't show it for errors from extension hooks
			$errors = $doEditStatus->getErrorsArray();
			if ( in_array( $errors[0][0],
					[ 'edit-gone-missing', 'edit-conflict', 'edit-already-exists' ] )
			) {
				$this->isConflict = true;
				// Destroys data doEdit() put in $status->value but who cares
				$doEditStatus->value = self::AS_END;
			}
			return $doEditStatus;
		}

		$result['nullEdit'] = $doEditStatus->hasMessage( 'edit-no-change' );
		if ( $result['nullEdit'] ) {
			// We don't know if it was a null edit until now, so increment here
			$user->pingLimiter( 'linkpurge' );
		}
		$result['redirect'] = $content->isRedirect();

		$this->updateWatchlist();

		// If the content model changed, add a log entry
		if ( $changingContentModel ) {
			$this->addContentModelChangeLogEntry(
				$user,
				$new ? false : $oldContentModel,
				$this->contentModel,
				$this->summary
			);
		}

		return $status;
	}

	/**
	 * @param User $user
	 * @param string|false $oldModel false if the page is being newly created
	 * @param string $newModel
	 * @param string $reason
	 */
	protected function addContentModelChangeLogEntry( User $user, $oldModel, $newModel, $reason ) {
		$new = $oldModel === false;
		$log = new ManualLogEntry( 'contentmodel', $new ? 'new' : 'change' );
		$log->setPerformer( $user );
		$log->setTarget( $this->mTitle );
		$log->setComment( $reason );
		$log->setParameters( [
			'4::oldmodel' => $oldModel,
			'5::newmodel' => $newModel
		] );
		$logid = $log->insert();
		$log->publish( $logid );
	}

	/**
	 * Register the change of watch status
	 */
	protected function updateWatchlist() {
		$user = $this->context->getUser();
		if ( !$user->isLoggedIn() ) {
			return;
		}

		$title = $this->mTitle;
		$watch = $this->watchthis;
		// Do this in its own transaction to reduce contention...
		DeferredUpdates::addCallableUpdate( function () use ( $user, $title, $watch ) {
			if ( $watch == $user->isWatched( $title, User::IGNORE_USER_RIGHTS ) ) {
				return; // nothing to change
			}
			WatchAction::doWatchOrUnwatch( $watch, $title, $user );
		} );
	}

	/**
	 * Attempts to do 3-way merge of edit content with a base revision
	 * and current content, in case of edit conflict, in whichever way appropriate
	 * for the content type.
	 *
	 * @since 1.21
	 *
	 * @param Content $editContent
	 *
	 * @return bool
	 */
	private function mergeChangesIntoContent( &$editContent ) {
		$db = wfGetDB( DB_MASTER );

		// This is the revision the editor started from
		$baseRevision = $this->getBaseRevision();
		$baseContent = $baseRevision ? $baseRevision->getContent() : null;

		if ( is_null( $baseContent ) ) {
			return false;
		}

		// The current state, we want to merge updates into it
		$currentRevision = Revision::loadFromTitle( $db, $this->mTitle );
		$currentContent = $currentRevision ? $currentRevision->getContent() : null;

		if ( is_null( $currentContent ) ) {
			return false;
		}

		$handler = ContentHandler::getForModelID( $baseContent->getModel() );

		$result = $handler->merge3( $baseContent, $editContent, $currentContent );

		if ( $result ) {
			$editContent = $result;
			// Update parentRevId to what we just merged.
			$this->parentRevId = $currentRevision->getId();
			return true;
		}

		return false;
	}

	/**
	 * @note: this method is very poorly named. If the user opened the form with ?oldid=X,
	 *        one might think of X as the "base revision", which is NOT what this returns.
	 * @return Revision|null Current version when the edit was started
	 */
	public function getBaseRevision() {
		if ( !$this->mBaseRevision ) {
			$db = wfGetDB( DB_MASTER );
			$this->mBaseRevision = $this->editRevId
				? Revision::newFromId( $this->editRevId, Revision::READ_LATEST )
				: Revision::loadFromTimestamp( $db, $this->mTitle, $this->edittime );
		}
		return $this->mBaseRevision;
	}

	/**
	 * Check given input text against $wgSpamRegex, and return the text of the first match.
	 *
	 * @param string $text
	 *
	 * @return string|bool Matching string or false
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
	 * @param string $text
	 *
	 * @return string|bool Matching string or false
	 */
	public static function matchSummarySpamRegex( $text ) {
		global $wgSummarySpamRegex;
		$regexes = (array)$wgSummarySpamRegex;
		return self::matchSpamRegexInternal( $text, $regexes );
	}

	/**
	 * @param string $text
	 * @param array $regexes
	 * @return bool|string
	 */
	protected static function matchSpamRegexInternal( $text, $regexes ) {
		foreach ( $regexes as $regex ) {
			$matches = [];
			if ( preg_match( $regex, $text, $matches ) ) {
				return $matches[0];
			}
		}
		return false;
	}

	public function setHeaders() {
		$out = $this->context->getOutput();

		$out->addModules( 'mediawiki.action.edit' );
		$out->addModuleStyles( 'mediawiki.action.edit.styles' );
		$out->addModuleStyles( 'mediawiki.editfont.styles' );

		$user = $this->context->getUser();
		if ( $user->getOption( 'showtoolbar' ) ) {
			// The addition of default buttons is handled by getEditToolbar() which
			// has its own dependency on this module. The call here ensures the module
			// is loaded in time (it has position "top") for other modules to register
			// buttons (e.g. extensions, gadgets, user scripts).
			$out->addModules( 'mediawiki.toolbar' );
		}

		if ( $user->getOption( 'uselivepreview' ) ) {
			$out->addModules( 'mediawiki.action.edit.preview' );
		}

		if ( $user->getOption( 'useeditwarning' ) ) {
			$out->addModules( 'mediawiki.action.edit.editWarning' );
		}

		# Enabled article-related sidebar, toplinks, etc.
		$out->setArticleRelated( true );

		$contextTitle = $this->getContextTitle();
		if ( $this->isConflict ) {
			$msg = 'editconflict';
		} elseif ( $contextTitle->exists() && $this->section != '' ) {
			$msg = $this->section == 'new' ? 'editingcomment' : 'editingsection';
		} else {
			$msg = $contextTitle->exists()
				|| ( $contextTitle->getNamespace() == NS_MEDIAWIKI
					&& $contextTitle->getDefaultMessageText() !== false
				)
				? 'editing'
				: 'creating';
		}

		# Use the title defined by DISPLAYTITLE magic word when present
		# NOTE: getDisplayTitle() returns HTML while getPrefixedText() returns plain text.
		#       setPageTitle() treats the input as wikitext, which should be safe in either case.
		$displayTitle = isset( $this->mParserOutput ) ? $this->mParserOutput->getDisplayTitle() : false;
		if ( $displayTitle === false ) {
			$displayTitle = $contextTitle->getPrefixedText();
		}
		$out->setPageTitle( $this->context->msg( $msg, $displayTitle ) );

		$config = $this->context->getConfig();

		# Transmit the name of the message to JavaScript for live preview
		# Keep Resources.php/mediawiki.action.edit.preview in sync with the possible keys
		$out->addJsConfigVars( [
			'wgEditMessage' => $msg,
			'wgAjaxEditStash' => $config->get( 'AjaxEditStash' ),
		] );

		// Add whether to use 'save' or 'publish' messages to JavaScript for post-edit, other
		// editors, etc.
		$out->addJsConfigVars(
			'wgEditSubmitButtonLabelPublish',
			$config->get( 'EditSubmitButtonLabelPublish' )
		);
	}

	/**
	 * Show all applicable editing introductions
	 */
	protected function showIntro() {
		if ( $this->suppressIntro ) {
			return;
		}

		$out = $this->context->getOutput();
		$namespace = $this->mTitle->getNamespace();

		if ( $namespace == NS_MEDIAWIKI ) {
			# Show a warning if editing an interface message
			$out->wrapWikiMsg( "<div class='mw-editinginterface'>\n$1\n</div>", 'editinginterface' );
			# If this is a default message (but not css, json, or js),
			# show a hint that it is translatable on translatewiki.net
			if (
				!$this->mTitle->hasContentModel( CONTENT_MODEL_CSS )
				&& !$this->mTitle->hasContentModel( CONTENT_MODEL_JSON )
				&& !$this->mTitle->hasContentModel( CONTENT_MODEL_JAVASCRIPT )
			) {
				$defaultMessageText = $this->mTitle->getDefaultMessageText();
				if ( $defaultMessageText !== false ) {
					$out->wrapWikiMsg( "<div class='mw-translateinterface'>\n$1\n</div>",
						'translateinterface' );
				}
			}
		} elseif ( $namespace == NS_FILE ) {
			# Show a hint to shared repo
			$file = wfFindFile( $this->mTitle );
			if ( $file && !$file->isLocal() ) {
				$descUrl = $file->getDescriptionUrl();
				# there must be a description url to show a hint to shared repo
				if ( $descUrl ) {
					if ( !$this->mTitle->exists() ) {
						$out->wrapWikiMsg( "<div class=\"mw-sharedupload-desc-create\">\n$1\n</div>", [
									'sharedupload-desc-create', $file->getRepo()->getDisplayName(), $descUrl
						] );
					} else {
						$out->wrapWikiMsg( "<div class=\"mw-sharedupload-desc-edit\">\n$1\n</div>", [
									'sharedupload-desc-edit', $file->getRepo()->getDisplayName(), $descUrl
						] );
					}
				}
			}
		}

		# Show a warning message when someone creates/edits a user (talk) page but the user does not exist
		# Show log extract when the user is currently blocked
		if ( $namespace == NS_USER || $namespace == NS_USER_TALK ) {
			$username = explode( '/', $this->mTitle->getText(), 2 )[0];
			$user = User::newFromName( $username, false /* allow IP users */ );
			$ip = User::isIP( $username );
			$block = Block::newFromTarget( $user, $user );
			if ( !( $user && $user->isLoggedIn() ) && !$ip ) { # User does not exist
				$out->wrapWikiMsg( "<div class=\"mw-userpage-userdoesnotexist error\">\n$1\n</div>",
					[ 'userpage-userdoesnotexist', wfEscapeWikiText( $username ) ] );
			} elseif ( !is_null( $block ) && $block->getType() != Block::TYPE_AUTO ) {
				# Show log extract if the user is currently blocked
				LogEventsList::showLogExtract(
					$out,
					'block',
					MWNamespace::getCanonicalName( NS_USER ) . ':' . $block->getTarget(),
					'',
					[
						'lim' => 1,
						'showIfEmpty' => false,
						'msgKey' => [
							'blocked-notice-logextract',
							$user->getName() # Support GENDER in notice
						]
					]
				);
			}
		}
		# Try to add a custom edit intro, or use the standard one if this is not possible.
		if ( !$this->showCustomIntro() && !$this->mTitle->exists() ) {
			$helpLink = wfExpandUrl( Skin::makeInternalOrExternalUrl(
				$this->context->msg( 'helppage' )->inContentLanguage()->text()
			) );
			if ( $this->context->getUser()->isLoggedIn() ) {
				$out->wrapWikiMsg(
					// Suppress the external link icon, consider the help url an internal one
					"<div class=\"mw-newarticletext plainlinks\">\n$1\n</div>",
					[
						'newarticletext',
						$helpLink
					]
				);
			} else {
				$out->wrapWikiMsg(
					// Suppress the external link icon, consider the help url an internal one
					"<div class=\"mw-newarticletextanon plainlinks\">\n$1\n</div>",
					[
						'newarticletextanon',
						$helpLink
					]
				);
			}
		}
		# Give a notice if the user is editing a deleted/moved page...
		if ( !$this->mTitle->exists() ) {
			$dbr = wfGetDB( DB_REPLICA );

			LogEventsList::showLogExtract( $out, [ 'delete', 'move' ], $this->mTitle,
				'',
				[
					'lim' => 10,
					'conds' => [ 'log_action != ' . $dbr->addQuotes( 'revision' ) ],
					'showIfEmpty' => false,
					'msgKey' => [ 'recreate-moveddeleted-warn' ]
				]
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
				// Added using template syntax, to take <noinclude>'s into account.
				$this->context->getOutput()->addWikiTextTitleTidy(
					'<div class="mw-editintro">{{:' . $title->getFullText() . '}}</div>',
					$this->mTitle
				);
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
	 * If the given Content object is not of a type that can be edited using
	 * the text base EditPage, an exception will be raised. Set
	 * $this->allowNonTextContent to true to allow editing of non-textual
	 * content.
	 *
	 * @param Content|null|bool|string $content
	 * @return string The editable text form of the content.
	 *
	 * @throws MWException If $content is not an instance of TextContent and
	 *   $this->allowNonTextContent is not true.
	 */
	protected function toEditText( $content ) {
		if ( $content === null || $content === false || is_string( $content ) ) {
			return $content;
		}

		if ( !$this->isSupportedContentModel( $content->getModel() ) ) {
			throw new MWException( 'This content model is not supported: ' . $content->getModel() );
		}

		return $content->serialize( $this->contentFormat );
	}

	/**
	 * Turns the given text into a Content object by unserializing it.
	 *
	 * If the resulting Content object is not of a type that can be edited using
	 * the text base EditPage, an exception will be raised. Set
	 * $this->allowNonTextContent to true to allow editing of non-textual
	 * content.
	 *
	 * @param string|null|bool $text Text to unserialize
	 * @return Content|bool|null The content object created from $text. If $text was false
	 *   or null, false resp. null will be  returned instead.
	 *
	 * @throws MWException If unserializing the text results in a Content
	 *   object that is not an instance of TextContent and
	 *   $this->allowNonTextContent is not true.
	 */
	protected function toEditContent( $text ) {
		if ( $text === false || $text === null ) {
			return $text;
		}

		$content = ContentHandler::makeContent( $text, $this->getTitle(),
			$this->contentModel, $this->contentFormat );

		if ( !$this->isSupportedContentModel( $content->getModel() ) ) {
			throw new MWException( 'This content model is not supported: ' . $content->getModel() );
		}

		return $content;
	}

	/**
	 * Send the edit form and related headers to OutputPage
	 * @param callable|null $formCallback That takes an OutputPage parameter; will be called
	 *     during form output near the top, for captchas and the like.
	 *
	 * The $formCallback parameter is deprecated since MediaWiki 1.25. Please
	 * use the EditPage::showEditForm:fields hook instead.
	 */
	public function showEditForm( $formCallback = null ) {
		# need to parse the preview early so that we know which templates are used,
		# otherwise users with "show preview after edit box" will get a blank list
		# we parse this near the beginning so that setHeaders can do the title
		# setting work instead of leaving it in getPreviewText
		$previewOutput = '';
		if ( $this->formtype == 'preview' ) {
			$previewOutput = $this->getPreviewText();
		}

		$out = $this->context->getOutput();

		// Avoid PHP 7.1 warning of passing $this by reference
		$editPage = $this;
		Hooks::run( 'EditPage::showEditForm:initial', [ &$editPage, &$out ] );

		$this->setHeaders();

		$this->addTalkPageText();
		$this->addEditNotices();

		if ( !$this->isConflict &&
			$this->section != '' &&
			!$this->isSectionEditSupported() ) {
			// We use $this->section to much before this and getVal('wgSection') directly in other places
			// at this point we can't reset $this->section to '' to fallback to non-section editing.
			// Someone is welcome to try refactoring though
			$out->showErrorPage( 'sectioneditnotsupported-title', 'sectioneditnotsupported-text' );
			return;
		}

		$this->showHeader();

		$out->addHTML( $this->editFormPageTop );

		$user = $this->context->getUser();
		if ( $user->getOption( 'previewontop' ) ) {
			$this->displayPreviewArea( $previewOutput, true );
		}

		$out->addHTML( $this->editFormTextTop );

		$showToolbar = true;
		if ( $this->wasDeletedSinceLastEdit() ) {
			if ( $this->formtype == 'save' ) {
				// Hide the toolbar and edit area, user can click preview to get it back
				// Add an confirmation checkbox and explanation.
				$showToolbar = false;
			} else {
				$out->wrapWikiMsg( "<div class='error mw-deleted-while-editing'>\n$1\n</div>",
					'deletedwhileediting' );
			}
		}

		// @todo add EditForm plugin interface and use it here!
		//       search for textarea1 and textarea2, and allow EditForm to override all uses.
		$out->addHTML( Html::openElement(
			'form',
			[
				'class' => 'mw-editform',
				'id' => self::EDITFORM_ID,
				'name' => self::EDITFORM_ID,
				'method' => 'post',
				'action' => $this->getActionURL( $this->getContextTitle() ),
				'enctype' => 'multipart/form-data'
			]
		) );

		if ( is_callable( $formCallback ) ) {
			wfWarn( 'The $formCallback parameter to ' . __METHOD__ . 'is deprecated' );
			call_user_func_array( $formCallback, [ &$out ] );
		}

		// Add a check for Unicode support
		$out->addHTML( Html::hidden( 'wpUnicodeCheck', self::UNICODE_CHECK ) );

		// Add an empty field to trip up spambots
		$out->addHTML(
			Xml::openElement( 'div', [ 'id' => 'antispam-container', 'style' => 'display: none;' ] )
			. Html::rawElement(
				'label',
				[ 'for' => 'wpAntispam' ],
				$this->context->msg( 'simpleantispam-label' )->parse()
			)
			. Xml::element(
				'input',
				[
					'type' => 'text',
					'name' => 'wpAntispam',
					'id' => 'wpAntispam',
					'value' => ''
				]
			)
			. Xml::closeElement( 'div' )
		);

		// Avoid PHP 7.1 warning of passing $this by reference
		$editPage = $this;
		Hooks::run( 'EditPage::showEditForm:fields', [ &$editPage, &$out ] );

		// Put these up at the top to ensure they aren't lost on early form submission
		$this->showFormBeforeText();

		if ( $this->wasDeletedSinceLastEdit() && 'save' == $this->formtype ) {
			$username = $this->lastDelete->user_name;
			$comment = CommentStore::getStore()
				->getComment( 'log_comment', $this->lastDelete )->text;

			// It is better to not parse the comment at all than to have templates expanded in the middle
			// TODO: can the checkLabel be moved outside of the div so that wrapWikiMsg could be used?
			$key = $comment === ''
				? 'confirmrecreate-noreason'
				: 'confirmrecreate';
			$out->addHTML(
				'<div class="mw-confirm-recreate">' .
					$this->context->msg( $key, $username, "<nowiki>$comment</nowiki>" )->parse() .
				Xml::checkLabel( $this->context->msg( 'recreate' )->text(), 'wpRecreate', 'wpRecreate', false,
					[ 'title' => Linker::titleAttrib( 'recreate' ), 'tabindex' => 1, 'id' => 'wpRecreate' ]
				) .
				'</div>'
			);
		}

		# When the summary is hidden, also hide them on preview/show changes
		if ( $this->nosummary ) {
			$out->addHTML( Html::hidden( 'nosummary', true ) );
		}

		# If a blank edit summary was previously provided, and the appropriate
		# user preference is active, pass a hidden tag as wpIgnoreBlankSummary. This will stop the
		# user being bounced back more than once in the event that a summary
		# is not required.
		# ####
		# For a bit more sophisticated detection of blank summaries, hash the
		# automatic one and pass that in the hidden field wpAutoSummary.
		if ( $this->missingSummary || ( $this->section == 'new' && $this->nosummary ) ) {
			$out->addHTML( Html::hidden( 'wpIgnoreBlankSummary', true ) );
		}

		if ( $this->undidRev ) {
			$out->addHTML( Html::hidden( 'wpUndidRevision', $this->undidRev ) );
		}

		if ( $this->selfRedirect ) {
			$out->addHTML( Html::hidden( 'wpIgnoreSelfRedirect', true ) );
		}

		if ( $this->hasPresetSummary ) {
			// If a summary has been preset using &summary= we don't want to prompt for
			// a different summary. Only prompt for a summary if the summary is blanked.
			// (T19416)
			$this->autoSumm = md5( '' );
		}

		$autosumm = $this->autoSumm ? $this->autoSumm : md5( $this->summary );
		$out->addHTML( Html::hidden( 'wpAutoSummary', $autosumm ) );

		$out->addHTML( Html::hidden( 'oldid', $this->oldid ) );
		$out->addHTML( Html::hidden( 'parentRevId', $this->getParentRevId() ) );

		$out->addHTML( Html::hidden( 'format', $this->contentFormat ) );
		$out->addHTML( Html::hidden( 'model', $this->contentModel ) );

		$out->enableOOUI();

		if ( $this->section == 'new' ) {
			$this->showSummaryInput( true, $this->summary );
			$out->addHTML( $this->getSummaryPreview( true, $this->summary ) );
		}

		$out->addHTML( $this->editFormTextBeforeContent );
		if ( $this->isConflict ) {
			// In an edit conflict, we turn textbox2 into the user's text,
			// and textbox1 into the stored version
			$this->textbox2 = $this->textbox1;

			$content = $this->getCurrentContent();
			$this->textbox1 = $this->toEditText( $content );

			$editConflictHelper = $this->getEditConflictHelper();
			$editConflictHelper->setTextboxes( $this->textbox2, $this->textbox1 );
			$editConflictHelper->setContentModel( $this->contentModel );
			$editConflictHelper->setContentFormat( $this->contentFormat );
			$out->addHTML( $editConflictHelper->getEditFormHtmlBeforeContent() );
		}

		if ( !$this->mTitle->isUserConfigPage() && $showToolbar && $user->getOption( 'showtoolbar' ) ) {
			$out->addHTML( self::getEditToolbar( $this->mTitle ) );
		}

		if ( $this->blankArticle ) {
			$out->addHTML( Html::hidden( 'wpIgnoreBlankArticle', true ) );
		}

		if ( $this->isConflict ) {
			// In an edit conflict bypass the overridable content form method
			// and fallback to the raw wpTextbox1 since editconflicts can't be
			// resolved between page source edits and custom ui edits using the
			// custom edit ui.
			$conflictTextBoxAttribs = [];
			if ( $this->wasDeletedSinceLastEdit() ) {
				$conflictTextBoxAttribs['style'] = 'display:none;';
			} elseif ( $this->isOldRev ) {
				$conflictTextBoxAttribs['class'] = 'mw-textarea-oldrev';
			}

			$out->addHTML( $editConflictHelper->getEditConflictMainTextBox( $conflictTextBoxAttribs ) );
			$out->addHTML( $editConflictHelper->getEditFormHtmlAfterContent() );
		} else {
			$this->showContentForm();
		}

		$out->addHTML( $this->editFormTextAfterContent );

		$this->showStandardInputs();

		$this->showFormAfterText();

		$this->showTosSummary();

		$this->showEditTools();

		$out->addHTML( $this->editFormTextAfterTools . "\n" );

		$out->addHTML( $this->makeTemplatesOnThisPageList( $this->getTemplates() ) );

		$out->addHTML( Html::rawElement( 'div', [ 'class' => 'hiddencats' ],
			Linker::formatHiddenCategories( $this->page->getHiddenCategories() ) ) );

		$out->addHTML( Html::rawElement( 'div', [ 'class' => 'limitreport' ],
			self::getPreviewLimitReport( $this->mParserOutput ) ) );

		$out->addModules( 'mediawiki.action.edit.collapsibleFooter' );

		if ( $this->isConflict ) {
			try {
				$this->showConflict();
			} catch ( MWContentSerializationException $ex ) {
				// this can't really happen, but be nice if it does.
				$msg = $this->context->msg(
					'content-failed-to-parse',
					$this->contentModel,
					$this->contentFormat,
					$ex->getMessage()
				);
				$out->addWikiText( '<div class="error">' . $msg->text() . '</div>' );
			}
		}

		// Set a hidden field so JS knows what edit form mode we are in
		if ( $this->isConflict ) {
			$mode = 'conflict';
		} elseif ( $this->preview ) {
			$mode = 'preview';
		} elseif ( $this->diff ) {
			$mode = 'diff';
		} else {
			$mode = 'text';
		}
		$out->addHTML( Html::hidden( 'mode', $mode, [ 'id' => 'mw-edit-mode' ] ) );

		// Marker for detecting truncated form data.  This must be the last
		// parameter sent in order to be of use, so do not move me.
		$out->addHTML( Html::hidden( 'wpUltimateParam', true ) );
		$out->addHTML( $this->editFormTextBottom . "\n</form>\n" );

		if ( !$user->getOption( 'previewontop' ) ) {
			$this->displayPreviewArea( $previewOutput, false );
		}
	}

	/**
	 * Wrapper around TemplatesOnThisPageFormatter to make
	 * a "templates on this page" list.
	 *
	 * @param Title[] $templates
	 * @return string HTML
	 */
	public function makeTemplatesOnThisPageList( array $templates ) {
		$templateListFormatter = new TemplatesOnThisPageFormatter(
			$this->context, MediaWikiServices::getInstance()->getLinkRenderer()
		);

		// preview if preview, else section if section, else false
		$type = false;
		if ( $this->preview ) {
			$type = 'preview';
		} elseif ( $this->section != '' ) {
			$type = 'section';
		}

		return Html::rawElement( 'div', [ 'class' => 'templatesUsed' ],
			$templateListFormatter->format( $templates, $type )
		);
	}

	/**
	 * Extract the section title from current section text, if any.
	 *
	 * @param string $text
	 * @return string|bool String or false
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
		$out = $this->context->getOutput();
		$user = $this->context->getUser();
		if ( $this->isConflict ) {
			$this->addExplainConflictHeader( $out );
			$this->editRevId = $this->page->getLatest();
		} else {
			if ( $this->section != '' && $this->section != 'new' ) {
				if ( !$this->summary && !$this->preview && !$this->diff ) {
					$sectionTitle = self::extractSectionTitle( $this->textbox1 ); // FIXME: use Content object
					if ( $sectionTitle !== false ) {
						$this->summary = "/* $sectionTitle */ ";
					}
				}
			}

			$buttonLabel = $this->context->msg( $this->getSubmitButtonLabel() )->text();

			if ( $this->missingComment ) {
				$out->wrapWikiMsg( "<div id='mw-missingcommenttext'>\n$1\n</div>", 'missingcommenttext' );
			}

			if ( $this->missingSummary && $this->section != 'new' ) {
				$out->wrapWikiMsg(
					"<div id='mw-missingsummary'>\n$1\n</div>",
					[ 'missingsummary', $buttonLabel ]
				);
			}

			if ( $this->missingSummary && $this->section == 'new' ) {
				$out->wrapWikiMsg(
					"<div id='mw-missingcommentheader'>\n$1\n</div>",
					[ 'missingcommentheader', $buttonLabel ]
				);
			}

			if ( $this->blankArticle ) {
				$out->wrapWikiMsg(
					"<div id='mw-blankarticle'>\n$1\n</div>",
					[ 'blankarticle', $buttonLabel ]
				);
			}

			if ( $this->selfRedirect ) {
				$out->wrapWikiMsg(
					"<div id='mw-selfredirect'>\n$1\n</div>",
					[ 'selfredirect', $buttonLabel ]
				);
			}

			if ( $this->hookError !== '' ) {
				$out->addWikiText( $this->hookError );
			}

			if ( $this->section != 'new' ) {
				$revision = $this->mArticle->getRevisionFetched();
				if ( $revision ) {
					// Let sysop know that this will make private content public if saved

					if ( !$revision->userCan( Revision::DELETED_TEXT, $user ) ) {
						$out->wrapWikiMsg(
							"<div class='mw-warning plainlinks'>\n$1\n</div>\n",
							'rev-deleted-text-permission'
						);
					} elseif ( $revision->isDeleted( Revision::DELETED_TEXT ) ) {
						$out->wrapWikiMsg(
							"<div class='mw-warning plainlinks'>\n$1\n</div>\n",
							'rev-deleted-text-view'
						);
					}

					if ( !$revision->isCurrent() ) {
						$this->mArticle->setOldSubtitle( $revision->getId() );
						$out->addWikiMsg( 'editingold' );
						$this->isOldRev = true;
					}
				} elseif ( $this->mTitle->exists() ) {
					// Something went wrong

					$out->wrapWikiMsg( "<div class='errorbox'>\n$1\n</div>\n",
						[ 'missing-revision', $this->oldid ] );
				}
			}
		}

		if ( wfReadOnly() ) {
			$out->wrapWikiMsg(
				"<div id=\"mw-read-only-warning\">\n$1\n</div>",
				[ 'readonlywarning', wfReadOnlyReason() ]
			);
		} elseif ( $user->isAnon() ) {
			if ( $this->formtype != 'preview' ) {
				$out->wrapWikiMsg(
					"<div id='mw-anon-edit-warning' class='warningbox'>\n$1\n</div>",
					[ 'anoneditwarning',
						// Log-in link
						SpecialPage::getTitleFor( 'Userlogin' )->getFullURL( [
							'returnto' => $this->getTitle()->getPrefixedDBkey()
						] ),
						// Sign-up link
						SpecialPage::getTitleFor( 'CreateAccount' )->getFullURL( [
							'returnto' => $this->getTitle()->getPrefixedDBkey()
						] )
					]
				);
			} else {
				$out->wrapWikiMsg( "<div id=\"mw-anon-preview-warning\" class=\"warningbox\">\n$1</div>",
					'anonpreviewwarning'
				);
			}
		} else {
			if ( $this->mTitle->isUserConfigPage() ) {
				# Check the skin exists
				if ( $this->isWrongCaseUserConfigPage() ) {
					$out->wrapWikiMsg(
						"<div class='error' id='mw-userinvalidconfigtitle'>\n$1\n</div>",
						[ 'userinvalidconfigtitle', $this->mTitle->getSkinFromConfigSubpage() ]
					);
				}
				if ( $this->getTitle()->isSubpageOf( $user->getUserPage() ) ) {
					$isUserCssConfig = $this->mTitle->isUserCssConfigPage();
					$isUserJsonConfig = $this->mTitle->isUserJsonConfigPage();
					$isUserJsConfig = $this->mTitle->isUserJsConfigPage();

					$warning = $isUserCssConfig
						? 'usercssispublic'
						: ( $isUserJsonConfig ? 'userjsonispublic' : 'userjsispublic' );

					$out->wrapWikiMsg( '<div class="mw-userconfigpublic">$1</div>', $warning );

					if ( $this->formtype !== 'preview' ) {
						$config = $this->context->getConfig();
						if ( $isUserCssConfig && $config->get( 'AllowUserCss' ) ) {
							$out->wrapWikiMsg(
								"<div id='mw-usercssyoucanpreview'>\n$1\n</div>",
								[ 'usercssyoucanpreview' ]
							);
						} elseif ( $isUserJsonConfig /* No comparable 'AllowUserJson' */ ) {
							$out->wrapWikiMsg(
								"<div id='mw-userjsonyoucanpreview'>\n$1\n</div>",
								[ 'userjsonyoucanpreview' ]
							);
						} elseif ( $isUserJsConfig && $config->get( 'AllowUserJs' ) ) {
							$out->wrapWikiMsg(
								"<div id='mw-userjsyoucanpreview'>\n$1\n</div>",
								[ 'userjsyoucanpreview' ]
							);
						}
					}
				}
			}
		}

		$this->addPageProtectionWarningHeaders();

		$this->addLongPageWarningHeader();

		# Add header copyright warning
		$this->showHeaderCopyrightWarning();
	}

	/**
	 * Helper function for summary input functions, which returns the neccessary
	 * attributes for the input.
	 *
	 * @param array|null $inputAttrs Array of attrs to use on the input
	 * @return array
	 */
	private function getSummaryInputAttributes( array $inputAttrs = null ) {
		$conf = $this->context->getConfig();
		$oldCommentSchema = $conf->get( 'CommentTableSchemaMigrationStage' ) === MIGRATION_OLD;
		// HTML maxlength uses "UTF-16 code units", which means that characters outside BMP
		// (e.g. emojis) count for two each. This limit is overridden in JS to instead count
		// Unicode codepoints (or 255 UTF-8 bytes for old schema).
		return ( is_array( $inputAttrs ) ? $inputAttrs : [] ) + [
			'id' => 'wpSummary',
			'name' => 'wpSummary',
			'maxlength' => $oldCommentSchema ? 200 : CommentStore::COMMENT_CHARACTER_LIMIT,
			'tabindex' => 1,
			'size' => 60,
			'spellcheck' => 'true',
		];
	}

	/**
	 * Builds a standard summary input with a label.
	 *
	 * @param string $summary The value of the summary input
	 * @param string $labelText The html to place inside the label
	 * @param array $inputAttrs Array of attrs to use on the input
	 *
	 * @return OOUI\FieldLayout OOUI FieldLayout with Label and Input
	 */
	function getSummaryInputWidget( $summary = "", $labelText = null, $inputAttrs = null ) {
		$inputAttrs = OOUI\Element::configFromHtmlAttributes(
			$this->getSummaryInputAttributes( $inputAttrs )
		);
		$inputAttrs += [
			'title' => Linker::titleAttrib( 'summary' ),
			'accessKey' => Linker::accesskey( 'summary' ),
		];

		// For compatibility with old scripts and extensions, we want the legacy 'id' on the `<input>`
		$inputAttrs['inputId'] = $inputAttrs['id'];
		$inputAttrs['id'] = 'wpSummaryWidget';

		return new OOUI\FieldLayout(
			new OOUI\TextInputWidget( [
				'value' => $summary,
				'infusable' => true,
			] + $inputAttrs ),
			[
				'label' => new OOUI\HtmlSnippet( $labelText ),
				'align' => 'top',
				'id' => 'wpSummaryLabel',
				'classes' => [ $this->missingSummary ? 'mw-summarymissed' : 'mw-summary' ],
			]
		);
	}

	/**
	 * @param bool $isSubjectPreview True if this is the section subject/title
	 *   up top, or false if this is the comment summary
	 *   down below the textarea
	 * @param string $summary The text of the summary to display
	 */
	protected function showSummaryInput( $isSubjectPreview, $summary = "" ) {
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

		$labelText = $this->context->msg( $isSubjectPreview ? 'subject' : 'summary' )->parse();
		$this->context->getOutput()->addHTML( $this->getSummaryInputWidget(
				$summary,
				$labelText,
				[ 'class' => $summaryClass ]
			) );
	}

	/**
	 * @param bool $isSubjectPreview True if this is the section subject/title
	 *   up top, or false if this is the comment summary
	 *   down below the textarea
	 * @param string $summary The text of the summary to display
	 * @return string
	 */
	protected function getSummaryPreview( $isSubjectPreview, $summary = "" ) {
		// avoid spaces in preview, gets always trimmed on save
		$summary = trim( $summary );
		if ( !$summary || ( !$this->preview && !$this->diff ) ) {
			return "";
		}

		global $wgParser;

		if ( $isSubjectPreview ) {
			$summary = $this->context->msg( 'newsectionsummary' )
				->rawParams( $wgParser->stripSectionName( $summary ) )
				->inContentLanguage()->text();
		}

		$message = $isSubjectPreview ? 'subject-preview' : 'summary-preview';

		$summary = $this->context->msg( $message )->parse()
			. Linker::commentBlock( $summary, $this->mTitle, $isSubjectPreview );
		return Xml::tags( 'div', [ 'class' => 'mw-summary-preview' ], $summary );
	}

	protected function showFormBeforeText() {
		$out = $this->context->getOutput();
		$out->addHTML( Html::hidden( 'wpSection', $this->section ) );
		$out->addHTML( Html::hidden( 'wpStarttime', $this->starttime ) );
		$out->addHTML( Html::hidden( 'wpEdittime', $this->edittime ) );
		$out->addHTML( Html::hidden( 'editRevId', $this->editRevId ) );
		$out->addHTML( Html::hidden( 'wpScrolltop', $this->scrolltop, [ 'id' => 'wpScrolltop' ] ) );
	}

	protected function showFormAfterText() {
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
		$this->context->getOutput()->addHTML(
			"\n" .
			Html::hidden( "wpEditToken", $this->context->getUser()->getEditToken() ) .
			"\n"
		);
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
	 * @param array $customAttribs Array of html attributes to use in the textarea
	 * @param string $textoverride Optional text to override $this->textarea1 with
	 */
	protected function showTextbox1( $customAttribs = null, $textoverride = null ) {
		if ( $this->wasDeletedSinceLastEdit() && $this->formtype == 'save' ) {
			$attribs = [ 'style' => 'display:none;' ];
		} else {
			$builder = new TextboxBuilder();
			$classes = $builder->getTextboxProtectionCSSClasses( $this->getTitle() );

			# Is an old revision being edited?
			if ( $this->isOldRev ) {
				$classes[] = 'mw-textarea-oldrev';
			}

			$attribs = [ 'tabindex' => 1 ];

			if ( is_array( $customAttribs ) ) {
				$attribs += $customAttribs;
			}

			$attribs = $builder->mergeClassesIntoAttributes( $classes, $attribs );
		}

		$this->showTextbox(
			$textoverride !== null ? $textoverride : $this->textbox1,
			'wpTextbox1',
			$attribs
		);
	}

	protected function showTextbox2() {
		$this->showTextbox( $this->textbox2, 'wpTextbox2', [ 'tabindex' => 6, 'readonly' ] );
	}

	protected function showTextbox( $text, $name, $customAttribs = [] ) {
		$builder = new TextboxBuilder();
		$attribs = $builder->buildTextboxAttribs(
			$name,
			$customAttribs,
			$this->context->getUser(),
			$this->mTitle
		);

		$this->context->getOutput()->addHTML(
			Html::textarea( $name, $builder->addNewLineAtEnd( $text ), $attribs )
		);
	}

	protected function displayPreviewArea( $previewOutput, $isOnTop = false ) {
		$classes = [];
		if ( $isOnTop ) {
			$classes[] = 'ontop';
		}

		$attribs = [ 'id' => 'wikiPreview', 'class' => implode( ' ', $classes ) ];

		if ( $this->formtype != 'preview' ) {
			$attribs['style'] = 'display: none;';
		}

		$out = $this->context->getOutput();
		$out->addHTML( Xml::openElement( 'div', $attribs ) );

		if ( $this->formtype == 'preview' ) {
			$this->showPreview( $previewOutput );
		} else {
			// Empty content container for LivePreview
			$pageViewLang = $this->mTitle->getPageViewLanguage();
			$attribs = [ 'lang' => $pageViewLang->getHtmlCode(), 'dir' => $pageViewLang->getDir(),
				'class' => 'mw-content-' . $pageViewLang->getDir() ];
			$out->addHTML( Html::rawElement( 'div', $attribs ) );
		}

		$out->addHTML( '</div>' );

		if ( $this->formtype == 'diff' ) {
			try {
				$this->showDiff();
			} catch ( MWContentSerializationException $ex ) {
				$msg = $this->context->msg(
					'content-failed-to-parse',
					$this->contentModel,
					$this->contentFormat,
					$ex->getMessage()
				);
				$out->addWikiText( '<div class="error">' . $msg->text() . '</div>' );
			}
		}
	}

	/**
	 * Append preview output to OutputPage.
	 * Includes category rendering if this is a category page.
	 *
	 * @param string $text The HTML to be output for the preview.
	 */
	protected function showPreview( $text ) {
		if ( $this->mArticle instanceof CategoryPage ) {
			$this->mArticle->openShowCategory();
		}
		# This hook seems slightly odd here, but makes things more
		# consistent for extensions.
		$out = $this->context->getOutput();
		Hooks::run( 'OutputPageBeforeHTML', [ &$out, &$text ] );
		$out->addHTML( $text );
		if ( $this->mArticle instanceof CategoryPage ) {
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
	public function showDiff() {
		global $wgContLang;

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
		if ( $this->editRevId !== null ) {
			$newContent = $this->page->replaceSectionAtRev(
				$this->section, $textboxContent, $this->summary, $this->editRevId
			);
		} else {
			$newContent = $this->page->replaceSectionContent(
				$this->section, $textboxContent, $this->summary, $this->edittime
			);
		}

		if ( $newContent ) {
			Hooks::run( 'EditPageGetDiffContent', [ $this, &$newContent ] );

			$user = $this->context->getUser();
			$popts = ParserOptions::newFromUserAndLang( $user, $wgContLang );
			$newContent = $newContent->preSaveTransform( $this->mTitle, $user, $popts );
		}

		if ( ( $oldContent && !$oldContent->isEmpty() ) || ( $newContent && !$newContent->isEmpty() ) ) {
			$oldtitle = $this->context->msg( $oldtitlemsg )->parse();
			$newtitle = $this->context->msg( 'yourtext' )->parse();

			if ( !$oldContent ) {
				$oldContent = $newContent->getContentHandler()->makeEmptyContent();
			}

			if ( !$newContent ) {
				$newContent = $oldContent->getContentHandler()->makeEmptyContent();
			}

			$de = $oldContent->getContentHandler()->createDifferenceEngine( $this->context );
			$de->setContent( $oldContent, $newContent );

			$difftext = $de->getDiff( $oldtitle, $newtitle );
			$de->showDiffStyle();
		} else {
			$difftext = '';
		}

		$this->context->getOutput()->addHTML( '<div id="wikiDiff">' . $difftext . '</div>' );
	}

	/**
	 * Show the header copyright warning.
	 */
	protected function showHeaderCopyrightWarning() {
		$msg = 'editpage-head-copy-warn';
		if ( !$this->context->msg( $msg )->isDisabled() ) {
			$this->context->getOutput()->wrapWikiMsg( "<div class='editpage-head-copywarn'>\n$1\n</div>",
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
		Hooks::run( 'EditPageTosSummary', [ $this->mTitle, &$msg ] );
		if ( !$this->context->msg( $msg )->isDisabled() ) {
			$out = $this->context->getOutput();
			$out->addHTML( '<div class="mw-tos-summary">' );
			$out->addWikiMsg( $msg );
			$out->addHTML( '</div>' );
		}
	}

	/**
	 * Inserts optional text shown below edit and upload forms. Can be used to offer special
	 * characters not present on most keyboards for copying/pasting.
	 */
	protected function showEditTools() {
		$this->context->getOutput()->addHTML( '<div class="mw-editTools">' .
			$this->context->msg( 'edittools' )->inContentLanguage()->parse() .
			'</div>' );
	}

	/**
	 * Get the copyright warning
	 *
	 * Renamed to getCopyrightWarning(), old name kept around for backwards compatibility
	 * @return string
	 */
	protected function getCopywarn() {
		return self::getCopyrightWarning( $this->mTitle );
	}

	/**
	 * Get the copyright warning, by default returns wikitext
	 *
	 * @param Title $title
	 * @param string $format Output format, valid values are any function of a Message object
	 * @param Language|string|null $langcode Language code or Language object.
	 * @return string
	 */
	public static function getCopyrightWarning( $title, $format = 'plain', $langcode = null ) {
		global $wgRightsText;
		if ( $wgRightsText ) {
			$copywarnMsg = [ 'copyrightwarning',
				'[[' . wfMessage( 'copyrightpage' )->inContentLanguage()->text() . ']]',
				$wgRightsText ];
		} else {
			$copywarnMsg = [ 'copyrightwarning2',
				'[[' . wfMessage( 'copyrightpage' )->inContentLanguage()->text() . ']]' ];
		}
		// Allow for site and per-namespace customization of contribution/copyright notice.
		Hooks::run( 'EditPageCopyrightWarning', [ $title, &$copywarnMsg ] );

		$msg = call_user_func_array( 'wfMessage', $copywarnMsg )->title( $title );
		if ( $langcode ) {
			$msg->inLanguage( $langcode );
		}
		return "<div id=\"editpage-copywarn\">\n" .
			$msg->$format() . "\n</div>";
	}

	/**
	 * Get the Limit report for page previews
	 *
	 * @since 1.22
	 * @param ParserOutput $output ParserOutput object from the parse
	 * @return string HTML
	 */
	public static function getPreviewLimitReport( $output ) {
		global $wgLang;

		if ( !$output || !$output->getLimitReportData() ) {
			return '';
		}

		$limitReport = Html::rawElement( 'div', [ 'class' => 'mw-limitReportExplanation' ],
			wfMessage( 'limitreport-title' )->parseAsBlock()
		);

		// Show/hide animation doesn't work correctly on a table, so wrap it in a div.
		$limitReport .= Html::openElement( 'div', [ 'class' => 'preview-limit-report-wrapper' ] );

		$limitReport .= Html::openElement( 'table', [
			'class' => 'preview-limit-report wikitable'
		] ) .
			Html::openElement( 'tbody' );

		foreach ( $output->getLimitReportData() as $key => $value ) {
			if ( Hooks::run( 'ParserLimitReportFormat',
				[ $key, &$value, &$limitReport, true, true ]
			) ) {
				$keyMsg = wfMessage( $key );
				$valueMsg = wfMessage( [ "$key-value-html", "$key-value" ] );
				if ( !$valueMsg->exists() ) {
					$valueMsg = new RawMessage( '$1' );
				}
				if ( !$keyMsg->isDisabled() && !$valueMsg->isDisabled() ) {
					$limitReport .= Html::openElement( 'tr' ) .
						Html::rawElement( 'th', null, $keyMsg->parse() ) .
						Html::rawElement( 'td', null,
							$wgLang->formatNum( $valueMsg->params( $value )->parse() )
						) .
						Html::closeElement( 'tr' );
				}
			}
		}

		$limitReport .= Html::closeElement( 'tbody' ) .
			Html::closeElement( 'table' ) .
			Html::closeElement( 'div' );

		return $limitReport;
	}

	protected function showStandardInputs( &$tabindex = 2 ) {
		$out = $this->context->getOutput();
		$out->addHTML( "<div class='editOptions'>\n" );

		if ( $this->section != 'new' ) {
			$this->showSummaryInput( false, $this->summary );
			$out->addHTML( $this->getSummaryPreview( false, $this->summary ) );
		}

		$checkboxes = $this->getCheckboxesWidget(
			$tabindex,
			[ 'minor' => $this->minoredit, 'watch' => $this->watchthis ]
		);
		$checkboxesHTML = new OOUI\HorizontalLayout( [ 'items' => $checkboxes ] );

		$out->addHTML( "<div class='editCheckboxes'>" . $checkboxesHTML . "</div>\n" );

		// Show copyright warning.
		$out->addWikiText( $this->getCopywarn() );
		$out->addHTML( $this->editFormTextAfterWarn );

		$out->addHTML( "<div class='editButtons'>\n" );
		$out->addHTML( implode( "\n", $this->getEditButtons( $tabindex ) ) . "\n" );

		$cancel = $this->getCancelLink();

		$message = $this->context->msg( 'edithelppage' )->inContentLanguage()->text();
		$edithelpurl = Skin::makeInternalOrExternalUrl( $message );
		$edithelp =
			Html::linkButton(
				$this->context->msg( 'edithelp' )->text(),
				[ 'target' => 'helpwindow', 'href' => $edithelpurl ],
				[ 'mw-ui-quiet' ]
			) .
			$this->context->msg( 'word-separator' )->escaped() .
			$this->context->msg( 'newwindow' )->parse();

		$out->addHTML( "	<span class='cancelLink'>{$cancel}</span>\n" );
		$out->addHTML( "	<span class='editHelp'>{$edithelp}</span>\n" );
		$out->addHTML( "</div><!-- editButtons -->\n" );

		Hooks::run( 'EditPage::showStandardInputs:options', [ $this, $out, &$tabindex ] );

		$out->addHTML( "</div><!-- editOptions -->\n" );
	}

	/**
	 * Show an edit conflict. textbox1 is already shown in showEditForm().
	 * If you want to use another entry point to this function, be careful.
	 */
	protected function showConflict() {
		$out = $this->context->getOutput();
		// Avoid PHP 7.1 warning of passing $this by reference
		$editPage = $this;
		if ( Hooks::run( 'EditPageBeforeConflictDiff', [ &$editPage, &$out ] ) ) {
			$this->incrementConflictStats();

			$this->getEditConflictHelper()->showEditFormTextAfterFooters();
		}
	}

	protected function incrementConflictStats() {
		$this->getEditConflictHelper()->incrementConflictStats();
	}

	/**
	 * @return string
	 */
	public function getCancelLink() {
		$cancelParams = [];
		if ( !$this->isConflict && $this->oldid > 0 ) {
			$cancelParams['oldid'] = $this->oldid;
		} elseif ( $this->getContextTitle()->isRedirect() ) {
			$cancelParams['redirect'] = 'no';
		}

		return new OOUI\ButtonWidget( [
			'id' => 'mw-editform-cancel',
			'href' => $this->getContextTitle()->getLinkURL( $cancelParams ),
			'label' => new OOUI\HtmlSnippet( $this->context->msg( 'cancel' )->parse() ),
			'framed' => false,
			'infusable' => true,
			'flags' => 'destructive',
		] );
	}

	/**
	 * Returns the URL to use in the form's action attribute.
	 * This is used by EditPage subclasses when simply customizing the action
	 * variable in the constructor is not enough. This can be used when the
	 * EditPage lives inside of a Special page rather than a custom page action.
	 *
	 * @param Title $title Title object for which is being edited (where we go to for &action= links)
	 * @return string
	 */
	protected function getActionURL( Title $title ) {
		return $title->getLocalURL( [ 'action' => $this->action ] );
	}

	/**
	 * Check if a page was deleted while the user was editing it, before submit.
	 * Note that we rely on the logging table, which hasn't been always there,
	 * but that doesn't matter, because this only applies to brand new
	 * deletes.
	 * @return bool
	 */
	protected function wasDeletedSinceLastEdit() {
		if ( $this->deletedSinceEdit !== null ) {
			return $this->deletedSinceEdit;
		}

		$this->deletedSinceEdit = false;

		if ( !$this->mTitle->exists() && $this->mTitle->isDeletedQuick() ) {
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

	/**
	 * @return bool|stdClass
	 */
	protected function getLastDelete() {
		$dbr = wfGetDB( DB_REPLICA );
		$commentQuery = CommentStore::getStore()->getJoin( 'log_comment' );
		$actorQuery = ActorMigration::newMigration()->getJoin( 'log_user' );
		$data = $dbr->selectRow(
			array_merge( [ 'logging' ], $commentQuery['tables'], $actorQuery['tables'], [ 'user' ] ),
			[
				'log_type',
				'log_action',
				'log_timestamp',
				'log_namespace',
				'log_title',
				'log_params',
				'log_deleted',
				'user_name'
			] + $commentQuery['fields'] + $actorQuery['fields'],
			[
				'log_namespace' => $this->mTitle->getNamespace(),
				'log_title' => $this->mTitle->getDBkey(),
				'log_type' => 'delete',
				'log_action' => 'delete',
			],
			__METHOD__,
			[ 'LIMIT' => 1, 'ORDER BY' => 'log_timestamp DESC' ],
			[
				'user' => [ 'JOIN', 'user_id=' . $actorQuery['fields']['log_user'] ],
			] + $commentQuery['joins'] + $actorQuery['joins']
		);
		// Quick paranoid permission checks...
		if ( is_object( $data ) ) {
			if ( $data->log_deleted & LogPage::DELETED_USER ) {
				$data->user_name = $this->context->msg( 'rev-deleted-user' )->escaped();
			}

			if ( $data->log_deleted & LogPage::DELETED_COMMENT ) {
				$data->log_comment_text = $this->context->msg( 'rev-deleted-comment' )->escaped();
				$data->log_comment_data = null;
			}
		}

		return $data;
	}

	/**
	 * Get the rendered text for previewing.
	 * @throws MWException
	 * @return string
	 */
	public function getPreviewText() {
		$out = $this->context->getOutput();
		$config = $this->context->getConfig();

		if ( $config->get( 'RawHtml' ) && !$this->mTokenOk ) {
			// Could be an offsite preview attempt. This is very unsafe if
			// HTML is enabled, as it could be an attack.
			$parsedNote = '';
			if ( $this->textbox1 !== '' ) {
				// Do not put big scary notice, if previewing the empty
				// string, which happens when you initially edit
				// a category page, due to automatic preview-on-open.
				$parsedNote = $out->parse( "<div class='previewnote'>" .
					$this->context->msg( 'session_fail_preview_html' )->text() . "</div>",
					true, /* interface */true );
			}
			$this->incrementEditFailureStats( 'session_loss' );
			return $parsedNote;
		}

		$note = '';

		try {
			$content = $this->toEditContent( $this->textbox1 );

			$previewHTML = '';
			if ( !Hooks::run(
				'AlternateEditPreview',
				[ $this, &$content, &$previewHTML, &$this->mParserOutput ] )
			) {
				return $previewHTML;
			}

			# provide a anchor link to the editform
			$continueEditing = '<span class="mw-continue-editing">' .
				'[[#' . self::EDITFORM_ID . '|' .
				$this->context->getLanguage()->getArrow() . ' ' .
				$this->context->msg( 'continue-editing' )->text() . ']]</span>';
			if ( $this->mTriedSave && !$this->mTokenOk ) {
				if ( $this->mTokenOkExceptSuffix ) {
					$note = $this->context->msg( 'token_suffix_mismatch' )->plain();
					$this->incrementEditFailureStats( 'bad_token' );
				} else {
					$note = $this->context->msg( 'session_fail_preview' )->plain();
					$this->incrementEditFailureStats( 'session_loss' );
				}
			} elseif ( $this->incompleteForm ) {
				$note = $this->context->msg( 'edit_form_incomplete' )->plain();
				if ( $this->mTriedSave ) {
					$this->incrementEditFailureStats( 'incomplete_form' );
				}
			} else {
				$note = $this->context->msg( 'previewnote' )->plain() . ' ' . $continueEditing;
			}

			# don't parse non-wikitext pages, show message about preview
			if ( $this->mTitle->isUserConfigPage() || $this->mTitle->isSiteConfigPage() ) {
				if ( $this->mTitle->isUserConfigPage() ) {
					$level = 'user';
				} elseif ( $this->mTitle->isSiteConfigPage() ) {
					$level = 'site';
				} else {
					$level = false;
				}

				if ( $content->getModel() == CONTENT_MODEL_CSS ) {
					$format = 'css';
					if ( $level === 'user' && !$config->get( 'AllowUserCss' ) ) {
						$format = false;
					}
				} elseif ( $content->getModel() == CONTENT_MODEL_JSON ) {
					$format = 'json';
					if ( $level === 'user' /* No comparable 'AllowUserJson' */ ) {
						$format = false;
					}
				} elseif ( $content->getModel() == CONTENT_MODEL_JAVASCRIPT ) {
					$format = 'js';
					if ( $level === 'user' && !$config->get( 'AllowUserJs' ) ) {
						$format = false;
					}
				} else {
					$format = false;
				}

				# Used messages to make sure grep find them:
				# Messages: usercsspreview, userjsonpreview, userjspreview,
				#   sitecsspreview, sitejsonpreview, sitejspreview
				if ( $level && $format ) {
					$note = "<div id='mw-{$level}{$format}preview'>" .
						$this->context->msg( "{$level}{$format}preview" )->text() .
						' ' . $continueEditing . "</div>";
				}
			}

			# If we're adding a comment, we need to show the
			# summary as the headline
			if ( $this->section === "new" && $this->summary !== "" ) {
				$content = $content->addSectionHeader( $this->summary );
			}

			$hook_args = [ $this, &$content ];
			Hooks::run( 'EditPageGetPreviewContent', $hook_args );

			$parserResult = $this->doPreviewParse( $content );
			$parserOutput = $parserResult['parserOutput'];
			$previewHTML = $parserResult['html'];
			$this->mParserOutput = $parserOutput;
			$out->addParserOutputMetadata( $parserOutput );

			if ( count( $parserOutput->getWarnings() ) ) {
				$note .= "\n\n" . implode( "\n\n", $parserOutput->getWarnings() );
			}

		} catch ( MWContentSerializationException $ex ) {
			$m = $this->context->msg(
				'content-failed-to-parse',
				$this->contentModel,
				$this->contentFormat,
				$ex->getMessage()
			);
			$note .= "\n\n" . $m->parse();
			$previewHTML = '';
		}

		if ( $this->isConflict ) {
			$conflict = '<h2 id="mw-previewconflict">'
				. $this->context->msg( 'previewconflict' )->escaped() . "</h2>\n";
		} else {
			$conflict = '<hr />';
		}

		$previewhead = "<div class='previewnote'>\n" .
			'<h2 id="mw-previewheader">' . $this->context->msg( 'preview' )->escaped() . "</h2>" .
			$out->parse( $note, true, /* interface */true ) . $conflict . "</div>\n";

		$pageViewLang = $this->mTitle->getPageViewLanguage();
		$attribs = [ 'lang' => $pageViewLang->getHtmlCode(), 'dir' => $pageViewLang->getDir(),
			'class' => 'mw-content-' . $pageViewLang->getDir() ];
		$previewHTML = Html::rawElement( 'div', $attribs, $previewHTML );

		return $previewhead . $previewHTML . $this->previewTextAfterContent;
	}

	private function incrementEditFailureStats( $failureType ) {
		$stats = MediaWikiServices::getInstance()->getStatsdDataFactory();
		$stats->increment( 'edit.failures.' . $failureType );
	}

	/**
	 * Get parser options for a preview
	 * @return ParserOptions
	 */
	protected function getPreviewParserOptions() {
		$parserOptions = $this->page->makeParserOptions( $this->context );
		$parserOptions->setIsPreview( true );
		$parserOptions->setIsSectionPreview( !is_null( $this->section ) && $this->section !== '' );
		$parserOptions->enableLimitReport();
		return $parserOptions;
	}

	/**
	 * Parse the page for a preview. Subclasses may override this class, in order
	 * to parse with different options, or to otherwise modify the preview HTML.
	 *
	 * @param Content $content The page content
	 * @return array with keys:
	 *   - parserOutput: The ParserOutput object
	 *   - html: The HTML to be displayed
	 */
	protected function doPreviewParse( Content $content ) {
		$user = $this->context->getUser();
		$parserOptions = $this->getPreviewParserOptions();
		$pstContent = $content->preSaveTransform( $this->mTitle, $user, $parserOptions );
		$scopedCallback = $parserOptions->setupFakeRevision(
			$this->mTitle, $pstContent, $user );
		$parserOutput = $pstContent->getParserOutput( $this->mTitle, null, $parserOptions );
		ScopedCallback::consume( $scopedCallback );
		return [
			'parserOutput' => $parserOutput,
			'html' => $parserOutput->getText( [
				'enableSectionEditLinks' => false
			] )
		];
	}

	/**
	 * @return array
	 */
	public function getTemplates() {
		if ( $this->preview || $this->section != '' ) {
			$templates = [];
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
	 *
	 * @param Title $title Title object for the page being edited (optional)
	 * @return string
	 */
	public static function getEditToolbar( $title = null ) {
		global $wgContLang, $wgOut;
		global $wgEnableUploads, $wgForeignFileRepos;

		$imagesAvailable = $wgEnableUploads || count( $wgForeignFileRepos );
		$showSignature = true;
		if ( $title ) {
			$showSignature = MWNamespace::wantSignatures( $title->getNamespace() );
		}

		/**
		 * $toolarray is an array of arrays each of which includes the
		 * opening tag, the closing tag, optionally a sample text that is
		 * inserted between the two when no selection is highlighted
		 * and.  The tip text is shown when the user moves the mouse
		 * over the button.
		 *
		 * Images are defined in ResourceLoaderEditToolbarModule.
		 */
		$toolarray = [
			[
				'id'     => 'mw-editbutton-bold',
				'open'   => '\'\'\'',
				'close'  => '\'\'\'',
				'sample' => wfMessage( 'bold_sample' )->text(),
				'tip'    => wfMessage( 'bold_tip' )->text(),
			],
			[
				'id'     => 'mw-editbutton-italic',
				'open'   => '\'\'',
				'close'  => '\'\'',
				'sample' => wfMessage( 'italic_sample' )->text(),
				'tip'    => wfMessage( 'italic_tip' )->text(),
			],
			[
				'id'     => 'mw-editbutton-link',
				'open'   => '[[',
				'close'  => ']]',
				'sample' => wfMessage( 'link_sample' )->text(),
				'tip'    => wfMessage( 'link_tip' )->text(),
			],
			[
				'id'     => 'mw-editbutton-extlink',
				'open'   => '[',
				'close'  => ']',
				'sample' => wfMessage( 'extlink_sample' )->text(),
				'tip'    => wfMessage( 'extlink_tip' )->text(),
			],
			[
				'id'     => 'mw-editbutton-headline',
				'open'   => "\n== ",
				'close'  => " ==\n",
				'sample' => wfMessage( 'headline_sample' )->text(),
				'tip'    => wfMessage( 'headline_tip' )->text(),
			],
			$imagesAvailable ? [
				'id'     => 'mw-editbutton-image',
				'open'   => '[[' . $wgContLang->getNsText( NS_FILE ) . ':',
				'close'  => ']]',
				'sample' => wfMessage( 'image_sample' )->text(),
				'tip'    => wfMessage( 'image_tip' )->text(),
			] : false,
			$imagesAvailable ? [
				'id'     => 'mw-editbutton-media',
				'open'   => '[[' . $wgContLang->getNsText( NS_MEDIA ) . ':',
				'close'  => ']]',
				'sample' => wfMessage( 'media_sample' )->text(),
				'tip'    => wfMessage( 'media_tip' )->text(),
			] : false,
			[
				'id'     => 'mw-editbutton-nowiki',
				'open'   => "<nowiki>",
				'close'  => "</nowiki>",
				'sample' => wfMessage( 'nowiki_sample' )->text(),
				'tip'    => wfMessage( 'nowiki_tip' )->text(),
			],
			$showSignature ? [
				'id'     => 'mw-editbutton-signature',
				'open'   => wfMessage( 'sig-text', '~~~~' )->inContentLanguage()->text(),
				'close'  => '',
				'sample' => '',
				'tip'    => wfMessage( 'sig_tip' )->text(),
			] : false,
			[
				'id'     => 'mw-editbutton-hr',
				'open'   => "\n----\n",
				'close'  => '',
				'sample' => '',
				'tip'    => wfMessage( 'hr_tip' )->text(),
			]
		];

		$script = 'mw.loader.using("mediawiki.toolbar", function () {';
		foreach ( $toolarray as $tool ) {
			if ( !$tool ) {
				continue;
			}

			$params = [
				// Images are defined in ResourceLoaderEditToolbarModule
				false,
				// Note that we use the tip both for the ALT tag and the TITLE tag of the image.
				// Older browsers show a "speedtip" type message only for ALT.
				// Ideally these should be different, realistically they
				// probably don't need to be.
				$tool['tip'],
				$tool['open'],
				$tool['close'],
				$tool['sample'],
				$tool['id'],
			];

			$script .= Xml::encodeJsCall(
				'mw.toolbar.addButton',
				$params,
				ResourceLoader::inDebugMode()
			);
		}

		$script .= '});';

		$toolbar = '<div id="toolbar"></div>';

		if ( Hooks::run( 'EditPageBeforeEditToolbar', [ &$toolbar ] ) ) {
			// Only add the old toolbar cruft to the page payload if the toolbar has not
			// been over-written by a hook caller
			$wgOut->addScript( ResourceLoader::makeInlineScript( $script ) );
		};

		return $toolbar;
	}

	/**
	 * Return an array of checkbox definitions.
	 *
	 * Array keys correspond to the `<input>` 'name' attribute to use for each checkbox.
	 *
	 * Array values are associative arrays with the following keys:
	 *  - 'label-message' (required): message for label text
	 *  - 'id' (required): 'id' attribute for the `<input>`
	 *  - 'default' (required): default checkedness (true or false)
	 *  - 'title-message' (optional): used to generate 'title' attribute for the `<label>`
	 *  - 'tooltip' (optional): used to generate 'title' and 'accesskey' attributes
	 *    from messages like 'tooltip-foo', 'accesskey-foo'
	 *  - 'label-id' (optional): 'id' attribute for the `<label>`
	 *  - 'legacy-name' (optional): short name for backwards-compatibility
	 * @param array $checked Array of checkbox name (matching the 'legacy-name') => bool,
	 *   where bool indicates the checked status of the checkbox
	 * @return array
	 */
	public function getCheckboxesDefinition( $checked ) {
		$checkboxes = [];

		$user = $this->context->getUser();
		// don't show the minor edit checkbox if it's a new page or section
		if ( !$this->isNew && $user->isAllowed( 'minoredit' ) ) {
			$checkboxes['wpMinoredit'] = [
				'id' => 'wpMinoredit',
				'label-message' => 'minoredit',
				// Uses messages: tooltip-minoredit, accesskey-minoredit
				'tooltip' => 'minoredit',
				'label-id' => 'mw-editpage-minoredit',
				'legacy-name' => 'minor',
				'default' => $checked['minor'],
			];
		}

		if ( $user->isLoggedIn() ) {
			$checkboxes['wpWatchthis'] = [
				'id' => 'wpWatchthis',
				'label-message' => 'watchthis',
				// Uses messages: tooltip-watch, accesskey-watch
				'tooltip' => 'watch',
				'label-id' => 'mw-editpage-watch',
				'legacy-name' => 'watch',
				'default' => $checked['watch'],
			];
		}

		$editPage = $this;
		Hooks::run( 'EditPageGetCheckboxesDefinition', [ $editPage, &$checkboxes ] );

		return $checkboxes;
	}

	/**
	 * Returns an array of checkboxes for the edit form, including 'minor' and 'watch' checkboxes and
	 * any other added by extensions.
	 *
	 * @param int &$tabindex Current tabindex
	 * @param array $checked Array of checkbox => bool, where bool indicates the checked
	 *                 status of the checkbox
	 *
	 * @return array Associative array of string keys to OOUI\FieldLayout instances
	 */
	public function getCheckboxesWidget( &$tabindex, $checked ) {
		$checkboxes = [];
		$checkboxesDef = $this->getCheckboxesDefinition( $checked );

		foreach ( $checkboxesDef as $name => $options ) {
			$legacyName = isset( $options['legacy-name'] ) ? $options['legacy-name'] : $name;

			$title = null;
			$accesskey = null;
			if ( isset( $options['tooltip'] ) ) {
				$accesskey = $this->context->msg( "accesskey-{$options['tooltip']}" )->text();
				$title = Linker::titleAttrib( $options['tooltip'] );
			}
			if ( isset( $options['title-message'] ) ) {
				$title = $this->context->msg( $options['title-message'] )->text();
			}

			$checkboxes[ $legacyName ] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( [
					'tabIndex' => ++$tabindex,
					'accessKey' => $accesskey,
					'id' => $options['id'] . 'Widget',
					'inputId' => $options['id'],
					'name' => $name,
					'selected' => $options['default'],
					'infusable' => true,
				] ),
				[
					'align' => 'inline',
					'label' => new OOUI\HtmlSnippet( $this->context->msg( $options['label-message'] )->parse() ),
					'title' => $title,
					'id' => isset( $options['label-id'] ) ? $options['label-id'] : null,
				]
			);
		}

		// Backwards-compatibility hack to run the EditPageBeforeEditChecks hook. It's important,
		// people have used it for the weirdest things completely unrelated to checkboxes...
		// And if we're gonna run it, might as well allow its legacy checkboxes to be shown.
		$legacyCheckboxes = [];
		if ( !$this->isNew ) {
			$legacyCheckboxes['minor'] = '';
		}
		$legacyCheckboxes['watch'] = '';
		// Copy new-style checkboxes into an old-style structure
		foreach ( $checkboxes as $name => $oouiLayout ) {
			$legacyCheckboxes[$name] = (string)$oouiLayout;
		}
		// Avoid PHP 7.1 warning of passing $this by reference
		$ep = $this;
		Hooks::run( 'EditPageBeforeEditChecks', [ &$ep, &$legacyCheckboxes, &$tabindex ], '1.29' );
		// Copy back any additional old-style checkboxes into the new-style structure
		foreach ( $legacyCheckboxes as $name => $html ) {
			if ( $html && !isset( $checkboxes[$name] ) ) {
				$checkboxes[$name] = new OOUI\Widget( [ 'content' => new OOUI\HtmlSnippet( $html ) ] );
			}
		}

		return $checkboxes;
	}

	/**
	 * Get the message key of the label for the button to save the page
	 *
	 * @since 1.30
	 * @return string
	 */
	protected function getSubmitButtonLabel() {
		$labelAsPublish =
			$this->context->getConfig()->get( 'EditSubmitButtonLabelPublish' );

		// Can't use $this->isNew as that's also true if we're adding a new section to an extant page
		$newPage = !$this->mTitle->exists();

		if ( $labelAsPublish ) {
			$buttonLabelKey = $newPage ? 'publishpage' : 'publishchanges';
		} else {
			$buttonLabelKey = $newPage ? 'savearticle' : 'savechanges';
		}

		return $buttonLabelKey;
	}

	/**
	 * Returns an array of html code of the following buttons:
	 * save, diff and preview
	 *
	 * @param int &$tabindex Current tabindex
	 *
	 * @return array
	 */
	public function getEditButtons( &$tabindex ) {
		$buttons = [];

		$labelAsPublish =
			$this->context->getConfig()->get( 'EditSubmitButtonLabelPublish' );

		$buttonLabel = $this->context->msg( $this->getSubmitButtonLabel() )->text();
		$buttonTooltip = $labelAsPublish ? 'publish' : 'save';

		$buttons['save'] = new OOUI\ButtonInputWidget( [
			'name' => 'wpSave',
			'tabIndex' => ++$tabindex,
			'id' => 'wpSaveWidget',
			'inputId' => 'wpSave',
			// Support: IE 6 – Use <input>, otherwise it can't distinguish which button was clicked
			'useInputTag' => true,
			'flags' => [ 'progressive', 'primary' ],
			'label' => $buttonLabel,
			'infusable' => true,
			'type' => 'submit',
			// Messages used: tooltip-save, tooltip-publish
			'title' => Linker::titleAttrib( $buttonTooltip ),
			// Messages used: accesskey-save, accesskey-publish
			'accessKey' => Linker::accesskey( $buttonTooltip ),
		] );

		$buttons['preview'] = new OOUI\ButtonInputWidget( [
			'name' => 'wpPreview',
			'tabIndex' => ++$tabindex,
			'id' => 'wpPreviewWidget',
			'inputId' => 'wpPreview',
			// Support: IE 6 – Use <input>, otherwise it can't distinguish which button was clicked
			'useInputTag' => true,
			'label' => $this->context->msg( 'showpreview' )->text(),
			'infusable' => true,
			'type' => 'submit',
			// Message used: tooltip-preview
			'title' => Linker::titleAttrib( 'preview' ),
			// Message used: accesskey-preview
			'accessKey' => Linker::accesskey( 'preview' ),
		] );

		$buttons['diff'] = new OOUI\ButtonInputWidget( [
			'name' => 'wpDiff',
			'tabIndex' => ++$tabindex,
			'id' => 'wpDiffWidget',
			'inputId' => 'wpDiff',
			// Support: IE 6 – Use <input>, otherwise it can't distinguish which button was clicked
			'useInputTag' => true,
			'label' => $this->context->msg( 'showdiff' )->text(),
			'infusable' => true,
			'type' => 'submit',
			// Message used: tooltip-diff
			'title' => Linker::titleAttrib( 'diff' ),
			// Message used: accesskey-diff
			'accessKey' => Linker::accesskey( 'diff' ),
		] );

		// Avoid PHP 7.1 warning of passing $this by reference
		$editPage = $this;
		Hooks::run( 'EditPageBeforeEditButtons', [ &$editPage, &$buttons, &$tabindex ] );

		return $buttons;
	}

	/**
	 * Creates a basic error page which informs the user that
	 * they have attempted to edit a nonexistent section.
	 */
	public function noSuchSectionPage() {
		$out = $this->context->getOutput();
		$out->prepareErrorPage( $this->context->msg( 'nosuchsectiontitle' ) );

		$res = $this->context->msg( 'nosuchsectiontext', $this->section )->parseAsBlock();

		// Avoid PHP 7.1 warning of passing $this by reference
		$editPage = $this;
		Hooks::run( 'EditPageNoSuchSection', [ &$editPage, &$res ] );
		$out->addHTML( $res );

		$out->returnToMain( false, $this->mTitle );
	}

	/**
	 * Show "your edit contains spam" page with your diff and text
	 *
	 * @param string|array|bool $match Text (or array of texts) which triggered one or more filters
	 */
	public function spamPageWithContent( $match = false ) {
		$this->textbox2 = $this->textbox1;

		if ( is_array( $match ) ) {
			$match = $this->context->getLanguage()->listToText( $match );
		}
		$out = $this->context->getOutput();
		$out->prepareErrorPage( $this->context->msg( 'spamprotectiontitle' ) );

		$out->addHTML( '<div id="spamprotected">' );
		$out->addWikiMsg( 'spamprotectiontext' );
		if ( $match ) {
			$out->addWikiMsg( 'spamprotectionmatch', wfEscapeWikiText( $match ) );
		}
		$out->addHTML( '</div>' );

		$out->wrapWikiMsg( '<h2>$1</h2>', "yourdiff" );
		$this->showDiff();

		$out->wrapWikiMsg( '<h2>$1</h2>', "yourtext" );
		$this->showTextbox2();

		$out->addReturnTo( $this->getContextTitle(), [ 'action' => 'edit' ] );
	}

	/**
	 * Filter an input field through a Unicode de-armoring process if it
	 * came from an old browser with known broken Unicode editing issues.
	 *
	 * @deprecated since 1.30, does nothing
	 *
	 * @param WebRequest $request
	 * @param string $field
	 * @return string
	 */
	protected function safeUnicodeInput( $request, $field ) {
		return rtrim( $request->getText( $field ) );
	}

	/**
	 * Filter an output field through a Unicode armoring process if it is
	 * going to an old browser with known broken Unicode editing issues.
	 *
	 * @deprecated since 1.30, does nothing
	 *
	 * @param string $text
	 * @return string
	 */
	protected function safeUnicodeOutput( $text ) {
		return $text;
	}

	/**
	 * @since 1.29
	 */
	protected function addEditNotices() {
		$out = $this->context->getOutput();
		$editNotices = $this->mTitle->getEditNotices( $this->oldid );
		if ( count( $editNotices ) ) {
			$out->addHTML( implode( "\n", $editNotices ) );
		} else {
			$msg = $this->context->msg( 'editnotice-notext' );
			if ( !$msg->isDisabled() ) {
				$out->addHTML(
					'<div class="mw-editnotice-notext">'
					. $msg->parseAsBlock()
					. '</div>'
				);
			}
		}
	}

	/**
	 * @since 1.29
	 */
	protected function addTalkPageText() {
		if ( $this->mTitle->isTalkPage() ) {
			$this->context->getOutput()->addWikiMsg( 'talkpagetext' );
		}
	}

	/**
	 * @since 1.29
	 */
	protected function addLongPageWarningHeader() {
		if ( $this->contentLength === false ) {
			$this->contentLength = strlen( $this->textbox1 );
		}

		$out = $this->context->getOutput();
		$lang = $this->context->getLanguage();
		$maxArticleSize = $this->context->getConfig()->get( 'MaxArticleSize' );
		if ( $this->tooBig || $this->contentLength > $maxArticleSize * 1024 ) {
			$out->wrapWikiMsg( "<div class='error' id='mw-edit-longpageerror'>\n$1\n</div>",
				[
					'longpageerror',
					$lang->formatNum( round( $this->contentLength / 1024, 3 ) ),
					$lang->formatNum( $maxArticleSize )
				]
			);
		} else {
			if ( !$this->context->msg( 'longpage-hint' )->isDisabled() ) {
				$out->wrapWikiMsg( "<div id='mw-edit-longpage-hint'>\n$1\n</div>",
					[
						'longpage-hint',
						$lang->formatSize( strlen( $this->textbox1 ) ),
						strlen( $this->textbox1 )
					]
				);
			}
		}
	}

	/**
	 * @since 1.29
	 */
	protected function addPageProtectionWarningHeaders() {
		$out = $this->context->getOutput();
		if ( $this->mTitle->isProtected( 'edit' ) &&
			MWNamespace::getRestrictionLevels( $this->mTitle->getNamespace() ) !== [ '' ]
		) {
			# Is the title semi-protected?
			if ( $this->mTitle->isSemiProtected() ) {
				$noticeMsg = 'semiprotectedpagewarning';
			} else {
				# Then it must be protected based on static groups (regular)
				$noticeMsg = 'protectedpagewarning';
			}
			LogEventsList::showLogExtract( $out, 'protect', $this->mTitle, '',
				[ 'lim' => 1, 'msgKey' => [ $noticeMsg ] ] );
		}
		if ( $this->mTitle->isCascadeProtected() ) {
			# Is this page under cascading protection from some source pages?
			/** @var Title[] $cascadeSources */
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
			$out->wrapWikiMsg( $notice, [ 'cascadeprotectedwarning', $cascadeSourcesCount ] );
		}
		if ( !$this->mTitle->exists() && $this->mTitle->getRestrictions( 'create' ) ) {
			LogEventsList::showLogExtract( $out, 'protect', $this->mTitle, '',
				[ 'lim' => 1,
					'showIfEmpty' => false,
					'msgKey' => [ 'titleprotectedwarning' ],
					'wrap' => "<div class=\"mw-titleprotectedwarning\">\n$1</div>" ] );
		}
	}

	/**
	 * @param OutputPage $out
	 * @since 1.29
	 */
	protected function addExplainConflictHeader( OutputPage $out ) {
		$out->addHTML(
			$this->getEditConflictHelper()->getExplainHeader()
		);
	}

	/**
	 * @param string $name
	 * @param mixed[] $customAttribs
	 * @param User $user
	 * @return mixed[]
	 * @since 1.29
	 */
	protected function buildTextboxAttribs( $name, array $customAttribs, User $user ) {
		return ( new TextboxBuilder() )->buildTextboxAttribs(
			$name, $customAttribs, $user, $this->mTitle
		);
	}

	/**
	 * @param string $wikitext
	 * @return string
	 * @since 1.29
	 */
	protected function addNewLineAtEnd( $wikitext ) {
		return ( new TextboxBuilder() )->addNewLineAtEnd( $wikitext );
	}

	/**
	 * Turns section name wikitext into anchors for use in HTTP redirects. Various
	 * versions of Microsoft browsers misinterpret fragment encoding of Location: headers
	 * resulting in mojibake in address bar. Redirect them to legacy section IDs,
	 * if possible. All the other browsers get HTML5 if the wiki is configured for it, to
	 * spread the new style links more efficiently.
	 *
	 * @param string $text
	 * @return string
	 */
	private function guessSectionName( $text ) {
		global $wgParser;

		// Detect Microsoft browsers
		$userAgent = $this->context->getRequest()->getHeader( 'User-Agent' );
		if ( $userAgent && preg_match( '/MSIE|Edge/', $userAgent ) ) {
			// ...and redirect them to legacy encoding, if available
			return $wgParser->guessLegacySectionNameFromWikiText( $text );
		}
		// Meanwhile, real browsers get real anchors
		$name = $wgParser->guessSectionNameFromWikiText( $text );
		// With one little caveat: per T216029, fragments in HTTP redirects need to be urlencoded,
		// otherwise Chrome double-escapes the rest of the URL.
		return '#' . urlencode( mb_substr( $name, 1 ) );
	}

	/**
	 * Set a factory function to create an EditConflictHelper
	 *
	 * @param callable $factory Factory function
	 * @since 1.31
	 */
	public function setEditConflictHelperFactory( callable $factory ) {
		$this->editConflictHelperFactory = $factory;
		$this->editConflictHelper = null;
	}

	/**
	 * @return TextConflictHelper
	 */
	private function getEditConflictHelper() {
		if ( !$this->editConflictHelper ) {
			$this->editConflictHelper = call_user_func(
				$this->editConflictHelperFactory,
				$this->getSubmitButtonLabel()
			);
		}

		return $this->editConflictHelper;
	}

	/**
	 * @param string $submitButtonLabel
	 * @return TextConflictHelper
	 */
	private function newTextConflictHelper( $submitButtonLabel ) {
		return new TextConflictHelper(
			$this->getTitle(),
			$this->getContext()->getOutput(),
			MediaWikiServices::getInstance()->getStatsdDataFactory(),
			$submitButtonLabel
		);
	}
}
