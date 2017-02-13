<?php
/**
 * Interface for page editing
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
 * Abstract class for an edit interface
 *
 * This class fetches the content to edit, makes permission checks and handles the
 * status returned by AbstractEditController::attemptSave.
 * Subclasses should implement the edit() method.
 */
abstract class Editor {

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
	 * Must be overriden by subclasses
	 */

	protected $viewClass = 'EditView';

	/**
	 * Can be overriden by subclasses
	 */

	protected $action = 'submit';

	protected $supportsSectionEditing = true;

	protected $contentModelOverride = false;

	/**
	 * Constructor-set
	 */

	protected $controller;

	protected $page;

	protected $title;

	protected $user;

	protected $data;

	protected $model;

	protected $article;

	protected $context;

	protected $output;

	protected $view;

	/**
	 * Populated from edit status
	 */

	/** @var bool */
	protected $missingComment = false;

	/** @var bool */
	protected $blankArticle = false;

	/** @var bool */
	protected $selfRedirect = false;

	/** @var bool */
	protected $tooBig = false;

	/** @var bool */
	protected $isConflict = false;

	/** @var bool */
	protected $missingSummary = false;

	/** @var Status|null */
	protected $hookStatus = null;

	/** @var bool */
	protected $didSave = false;

	/** @var int */
	protected $oldid = 0;

	/** @var int */
	protected $parentRevId = 0;

	/**
	 * @param Article $article Article being edited
	 * @param User $article User performing the edit
	 * @param Title $contextTitle Title from which the user requested the edit
	 */
	final public function __construct( Article $article, User $user, Title $contextTitle ) {
		$this->controller = new WebEditController( $article->getPage(), $user );
		$this->dataWrapper = $this->controller->getDataWrapper(); // shortcut
		$this->data = $this->dataWrapper->getData(); // shortcut

		$this->user = $user;

		$this->article = $article;
		$this->page = $article->getPage(); // shortcut
		$this->title = $this->page->getTitle(); // shortcut
		$this->context = $this->article->getContext(); // shortcut
		$this->output = $this->context->getOutput(); // shortcut

		// edit view
		$dataSource = new EditFormDataSource( $this->data );
		$this->view = new $this->viewClass( $this->context, $dataSource, $contextTitle,
			$this->action );
	}

	final public function getArticle() {
		return $this->article;
	}

	final public function getUser() {
		return $this->user;
	}

	final public function getController() {
		return $this->controller;
	}

	final public function getView() {
		return $this->view;
	}

	abstract public function edit();

	/**
	 * Handle status, such as after attempt save
	 *
	 * @param Status $status
	 * @param array|bool $resultDetails
	 *
	 * @throws ErrorPageError
	 * @return bool False, if output is done, true if rest of the form should be displayed
	 */
	protected function handleStatus( Status $status, $resultDetails ) {
		/**
		 * @todo FIXME: once the interface for internalAttemptSave() is made
		 *   nicer, this should use the message in $status
		 */
		if ( $status->value === EditAttempt::AS_SUCCESS_UPDATE
			|| $status->value === EditAttempt::AS_SUCCESS_NEW_ARTICLE
		) {
			$this->didSave = true;
			if ( !$resultDetails['nullEdit'] ) {
				$this->setPostEditCookie( $status->value );
			}
		}

		// "wpExtraQueryRedirect" is a hidden input to modify
		// after save URL and is not used by actual edit form
		$extraQueryRedirect = $this->user->getRequest()->getVal( 'wpExtraQueryRedirect' );

		switch ( $status->value ) {

			// cases where we can continue

			case EditAttempt::AS_CONTENT_TOO_BIG:
			case EditAttempt::AS_MAX_ARTICLE_SIZE_EXCEEDED:
				$this->tooBig = true;
				return true;

			case EditAttempt::AS_BLANK_ARTICLE:
				$this->blankArticle = true;
				return true;

			case EditAttempt::AS_SUMMARY_NEEDED:
				$this->missingSummary = true;
				return true;

			case EditAttempt::AS_TEXTBOX_EMPTY:
				$this->missingComment = true;
				return true;

			case EditAttempt::AS_CONFLICT_DETECTED:
				$this->isConflict = true;
				return true;

			case EditAttempt::AS_HOOK_ERROR_EXPECTED:
				$this->hookStatus = $status;
				return true;

			case EditAttempt::AS_ARTICLE_WAS_DELETED:
			case EditAttempt::AS_END:
			case EditAttempt::AS_HOOK_ERROR_RESUME:
				return true;

			case EditAttempt::AS_SELF_REDIRECT:
				$this->selfRedirect = true;
				return true;

			case EditAttempt::AS_CANNOT_USE_CUSTOM_MODEL:
			case EditAttempt::AS_PARSE_ERROR:
				$this->output->addWikiText( '<div class="error">' . "\n" . $status->getWikiText() . '</div>' );
				return true;

			// cases where we must abort

			case EditAttempt::AS_HOOK_ERROR:
				return false;

			case EditAttempt::AS_SUCCESS_NEW_ARTICLE:
				$query = $resultDetails['redirect'] ? 'redirect=no' : '';
				if ( $extraQueryRedirect ) {
					if ( $query === '' ) {
						$query = $extraQueryRedirect;
					} else {
						$query = $query . '&' . $extraQueryRedirect;
					}
				}
				$anchor = isset( $resultDetails['sectionanchor'] ) ? $resultDetails['sectionanchor'] : '';
				$this->output->redirect( $this->title->getFullURL( $query ) . $anchor );
				return false;

			case EditAttempt::AS_SUCCESS_UPDATE:
				$extraQuery = '';
				$sectionanchor = $resultDetails['sectionanchor'];
				if ( isset( $resultDetails['newParent'] ) ) {
					$this->parentRevId = $resultDetails['newParent'];
				}

				// Give extensions a chance to modify URL query on update
				Hooks::run(
					'EditorUpdateBeforeRedirect',
					[ $this->page, $this->context, &$sectionanchor, &$extraQuery ]
				);

				if ( $resultDetails['redirect'] ) {
					if ( $extraQuery === '' ) {
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

				$this->output->redirect( $this->title->getFullURL( $extraQuery ) . $sectionanchor );
				return false;

			case EditAttempt::AS_SPAM_ERROR:
				$this->spamPageWithContent( $resultDetails['spam'] );
				return false;

			// cases where we must throw

			case EditAttempt::AS_BLOCKED_PAGE_FOR_USER:
				throw new UserBlockedError( $this->user->getBlock() );

			case EditAttempt::AS_IMAGE_REDIRECT_ANON:
			case EditAttempt::AS_IMAGE_REDIRECT_LOGGED:
				throw new PermissionsError( 'upload' );

			case EditAttempt::AS_READ_ONLY_PAGE_ANON:
			case EditAttempt::AS_READ_ONLY_PAGE_LOGGED:
				throw new PermissionsError( 'edit' );

			case EditAttempt::AS_READ_ONLY_PAGE:
				throw new ReadOnlyError;

			case EditAttempt::AS_RATE_LIMITED:
				throw new ThrottledError();

			case EditAttempt::AS_NO_CREATE_PERMISSION:
				$permission = $this->title->isTalkPage() ? 'createtalk' : 'createpage';
				throw new PermissionsError( $permission );

			case EditAttempt::AS_NO_CHANGE_CONTENT_MODEL:
				throw new PermissionsError( 'editcontentmodel' );

			default:
				// this shouldn't happen
				throw new FatalError( 'Unrecognized edit status' );
		}
	}

	/**
	 * Initialise form fields in the object
	 * Called on the first invocation, e.g. when a user clicks an edit link
	 * @return bool If the requested section is valid
	 */
	protected function initialiseForm( $revision ) {
		$this->data->editTime = $this->page->getTimestamp();
		$this->data->editRevId = $this->page->getLatest();

		$this->parentRevId = $this->page->getLatest();

		if ( $revision !== null && $revision->getId() < $this->page->getLatest() ) {
			$this->oldid = $revision->getId();
		}

		$undoMsg = '';
		$content = $this->controller->getContentObject( $revision, $undoMsg, false ); # TODO: track content object?!

		if ( !$this->isSupportedContentModel( $content->getModel() ) ) {
			throw new MWException( 'This content model is not supported: ' . $content->getModel() );
		}

		if ( $this->data->section !== '' && !$this->supportsSectionEditing ) {
			throw new ErrorPageError( 'sectioneditnotsupported-title',
				'sectioneditnotsupported-text' );
		}

		if ( $undoMsg !== '' ) {
			$this->onGetUndoContent( $undoMsg );
		}

		if ( $content === false ) {
			return false;
		}
		$this->data->textbox1 = $this->toEditText( $content );

		// activate checkboxes if user wants them to be always active
		# Sort out the "watch" checkbox
		$watchThis = $this->controller->getWatchThis();
		if ( $this->user->getOption( 'watchdefault' ) ) {
			# Watch all edits
			$watchThis = true;
		} elseif ( $this->user->getOption( 'watchcreations' ) && !$this->title->exists() ) {
			# Watch creations
			$watchThis = true;
		} elseif ( $this->user->isWatched( $this->title ) ) {
			# Already watched
			$watchThis = true;
		}
		$this->view->setWatchThis( $watchThis );

		$minorEdit = $this->controller->getMinorEdit();
		if ( $this->user->getOption( 'minordefault' ) && $this->title->exists() &&
			$this->data->section !== 'new'
		) {
			$minorEdit = true;
		}
		$this->view->setMinorEdit( $minorEdit );

		if ( $this->data->textbox1 === false ) {
			return false;
		}
		return true;
	}

	protected function onGetUndoContent( $undoMsg ) {
	}

	/**
	 * Check if the browser is on a blacklist of user-agents known to
	 * mangle UTF-8 data on form submission. Returns true if Unicode
	 * should make it through, false if it's known to be a problem.
	 * @return bool
	 */
	protected function checkUnicodeCompliantBrowser() {
		global $wgBrowserBlackList;

		$currentbrowser = $this->user->getRequest()->getHeader( 'User-Agent' );
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
	 * Checks whether the user entered a skin name in uppercase,
	 * e.g. "User:Example/Monobook.css" instead of "monobook.css"
	 *
	 * @return bool
	 */
	protected function isWrongCaseCssJsPage() {
		if ( $this->title->isCssJsSubpage() ) {
			$name = $this->title->getSkinFromCssJsSubpage();
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
	 * @param string $rigor Same format as Title::getUserPermissionErrors()
	 * @return array
	 */
	protected function getEditPermissionErrors( $rigor = 'secure' ) {

		$permErrors = $this->title->getUserPermissionsErrors( 'edit', $this->user, $rigor );
		# Can this title be created?
		if ( !$this->title->exists() ) {
			$permErrors = array_merge(
				$permErrors,
				wfArrayDiff2(
					$this->title->getUserPermissionsErrors( 'create', $this->user, $rigor ),
					$permErrors
				)
			);
		}

		$this->onGetEditPermissionErrors( $permErrors );

		return $permErrors;
	}

	protected function onGetEditPermissionErrors( &$permErrors ) {
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
	 * If the variable were set on the server, it would be cached, which is unwanted
	 * since the post-edit state should only apply to the load right after the save.
	 *
	 * @param int $statusValue The status value (to check for new article status)
	 */
	protected function setPostEditCookie( $statusValue ) {
		$revisionId = $this->page->getLatest();
		$postEditKey = self::POST_EDIT_COOKIE_KEY_PREFIX . $revisionId;

		$val = 'saved';
		if ( $statusValue === EditAttempt::AS_SUCCESS_NEW_ARTICLE ) {
			$val = 'created';
		} elseif ( $this->oldid ) {
			$val = 'restored';
		}

		$response = $this->user->getRequest()->response();
		$response->setCookie( $postEditKey, $val, time() + self::POST_EDIT_COOKIE_DURATION, [
			'httpOnly' => false,
		] );
	}

	abstract public function spamPageWithContent( $match = false );

	/**
	 * Returns whether section editing is supported for the current page.
	 * Subclasses may override this to replace the default behavior, which is
	 * to check ContentHandler::supportsSections.
	 *
	 * @return bool True if this edit page supports sections, false otherwise.
	 */
	protected function isSectionEditSupported() {
		$contentHandler = ContentHandler::getForTitle( $this->title );
		return $contentHandler->supportsSections();
	}

	/**
	 * Returns if the given content model is editable.
	 *
	 * @param string $modelId The ID of the content model to test. Use CONTENT_MODEL_XXX constants.
	 * @return bool
	 * @throws MWException If $modelId has no known handler
	 */
	protected function isSupportedContentModel( $modelId ) {
		return $this->contentModelOverride ||
			ContentHandler::getForModelID( $modelId )->supportsDirectEditing();
	}

	/**
	 * Gets an editable textual representation of $content.
	 * The textual representation can be turned by into a Content object by the
	 * toEditContent() method.
	 *
	 * If $content is null or false or a string, $content is returned unchanged.
	 *
	 * @param Content|null|bool|string $content
	 * @return string The editable text form of the content.
	 */
	final public function toEditText( $content ) {
		if ( $content === null || $content === false || is_string( $content ) ) {
			return $content;
		}

		return $content->serialize( $this->data->contentFormat );
	}

	/**
	 * Turns the given text into a Content object by unserializing it.
	 *
	 * @param string|null|bool $text Text to unserialize
	 * @return Content|bool|null The content object created from $text. If $text was false
	 *   or null, false resp. null will be  returned instead.n
	 */
	final public function toEditContent( $text ) {
		if ( $text === false || $text === null ) {
			return $text;
		}

		$content = ContentHandler::makeContent( $text, $this->title,
			$this->data->contentModel, $this->data->contentFormat );

		return $content;
	}
}
