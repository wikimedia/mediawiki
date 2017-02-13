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
 * This fetches the content to edit, makes permission checks and handles the
 * status returned by AbstractEditController::attemptSave.
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
	 * Constructor-set
	 */

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

	/**
	 * @param WebEditController $controller
	 * @param IContextSource $context
	 */
	final public function __construct( WebEditController $controller, IContextSource $context ) {
		$this->controller = $controller;
		$this->page = $controller->getPage(); // shortcut
		$this->title = $this->page->getTitle(); // shortcut
		$this->user = $controller->getUser(); // shortcut
		$this->data = $controller->getData(); // shortcut
		$this->model = $controller->getModel(); // shortcut

		$this->context = $context;
		$this->output = $this->context->getOutput(); // shortcut

		// edit view
		$dataSource = new EditFormDataSource( $this->data );
		$this->view = new $this->viewClass( $this->title, $context, $dataSource );
	}

	final public function getController() {
		return $this->controller;
	}

	final public function getContext() {
		return $this->context;
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

		$undoMsg = '';
		$content = $this->getContentObject( $revision, $undoMsg, false ); # TODO: track content object?!

		if ( $undoMsg !== '' ) {
			$this->onGetUndoContent( $undoMsg );
		}

		if ( $content === false ) {
			return false;
		}
		$this->data->textbox1 = $this->model->toEditText( $content );

		// activate checkboxes if user wants them to be always active
		# Sort out the "watch" checkbox
		if ( $this->user->getOption( 'watchdefault' ) ) {
			# Watch all edits
			$this->data->watchThis = true;
		} elseif ( $this->user->getOption( 'watchcreations' ) && !$this->title->exists() ) {
			# Watch creations
			$this->data->watchThis = true;
		} elseif ( $this->user->isWatched( $this->title ) ) {
			# Already watched
			$this->data->watchThis = true;
		}
		if ( $this->user->getOption( 'minordefault' ) && $this->title->exists() &&
			$this->data->section !== 'new'
		) {
			$this->data->minorEdit = true;
		}
		if ( $this->data->textbox1 === false ) {
			return false;
		}
		return true;
	}

	protected function onGetUndoContent( $undoMsg ) {
	}

	/**
	 * @param Content|null $def_content The default value to return
	 *
	 * @return Content|null Content on success, $def_content for invalid sections
	 *
	 * @since 1.21
	 */
	protected function getContentObject( $revision, &$undoMsg = null, $def_content = null ) {
		global $wgContLang;

		$content = false;

		// For message page not locally set, use the i18n message.
		// For other non-existent articles, use preload text if any.
		if ( !$this->title->exists() || $this->data->section === 'new' ) {
			if ( $this->title->getNamespace() === NS_MEDIAWIKI && $this->data->section !== 'new' ) {
				# If this is a system message, get the default text.
				$msg = $this->title->getDefaultMessageText();

				$content = $this->model->toEditContent( $msg );
			}
			if ( $content === false ) {
				# If requested, preload some text.
				$preload = $this->user->getRequest()->getVal( 'preload',
					// Custom preload text for new sections
					$this->data->section === 'new' ? 'MediaWiki:addsection-preload' : '' );
				$params = $this->user->getRequest()->getArray( 'preloadparams', [] );

				$content = $this->getPreloadedContent( $preload, $params );
			}
		// For existing pages, get text based on "undo" or section parameters.
		} else {
			if ( $this->data->section !== '' ) {
				// Get section edit text (returns $def_text for invalid sections)
				$orig = $this->getOriginalContent( $revision );
				$content = $orig ? $orig->getSection( $this->data->section ) : null;

				if ( !$content ) {
					$content = $def_content;
				}
			} else {
				$undoafter = $this->user->getRequest()->getInt( 'undoafter' );
				$undo = $this->user->getRequest()->getInt( 'undo' );

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
							$popts = ParserOptions::newFromUserAndLang( $this->user, $wgContLang );
							$newContent = $content->preSaveTransform( $this->title, $this->user, $popts );
							if ( $newContent->getModel() !== $oldContent->getModel() ) {
								// The undo may change content
								// model if its reverting the top
								// edit. This can result in
								// mismatched content model/format.
								$this->data->contentModel = $newContent->getModel();
								$this->data->contentFormat = $oldrev->getContentFormat();
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
								if ( $firstrev && $firstrev->getId() === $undo ) {
									$userText = $undorev->getUserText();
									if ( $userText === '' ) {
										$undoSummary = wfMessage(
											'undo-summary-username-hidden',
											$undo
										)->title( $this->title )->inContentLanguage()->text();
									} else {
										$undoSummary = wfMessage(
											'undo-summary',
											$undo,
											$userText
										)->title( $this->title )->inContentLanguage()->text();
									}
									if ( $this->data->summary === '' ) {
										$this->data->summary = $undoSummary;
									} else {
										$this->data->summary = $undoSummary . wfMessage( 'colon-separator' )
											->title( $this->title )->inContentLanguage()->text() .
											$this->data->summary;
									}
									$this->data->undidRevId = $undo;
								}
							}
						}
					} else {
						// Failed basic sanity checks.
						// Older revisions may have been removed since the link
						// was created, or we may simply have got bogus input.
						$undoMsg = 'norev';
					}
				}

				if ( $content === false ) {
					$content = $this->getOriginalContent( $revision );
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
	 * @param Revision|null $revision
	 * @return Content|null
	 */
	private function getOriginalContent( Revision $revision = null ) {
		if ( $this->data->section === 'new' ) {
			return $this->model->getCurrentContent();
		}
		if ( $revision === null ) {
			if ( !$this->data->contentModel ) {
				$this->data->contentModel = $this->title->getContentModel();
			}
			$handler = ContentHandler::getForModelID( $this->data->contentModel );

			return $handler->makeEmptyContent();
		}
		$content = $revision->getContent( Revision::FOR_THIS_USER, $this->user );
		return $content;
	}

	/**
	 * Get the contents to be preloaded into the box, by loading the given page.
	 *
	 * @param string $preload Representing the title to preload from.
	 * @param array $params Parameters to use (interface-message style) in the preloaded text
	 *
	 * @return Content
	 *
	 * @since 1.21
	 */
	private function getPreloadedContent( $preload, $params = [] ) {
		$handler = ContentHandler::getForModelID( $this->data->contentModel );

		if ( $preload === '' ) {
			return $handler->makeEmptyContent();
		}

		$title = Title::newFromText( $preload );
		# Check for existence to avoid getting MediaWiki:Noarticletext
		if ( $title === null || !$title->exists() || !$title->userCan( 'read', $this->user ) ) {
			// TODO: somehow show a warning to the user!
			return $handler->makeEmptyContent();
		}

		$page = WikiPage::factory( $title );
		if ( $page->isRedirect() ) {
			$title = $page->getRedirectTarget();
			# Same as before
			if ( $title === null || !$title->exists() || !$title->userCan( 'read', $this->user ) ) {
				// TODO: somehow show a warning to the user!
				return $handler->makeEmptyContent();
			}
			$page = WikiPage::factory( $title );
		}

		$parserOptions = ParserOptions::newFromUser( $this->user );
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
		} elseif ( $this->data->oldid ) {
			$val = 'restored';
		}

		$response = $this->context->getRequest()->response();
		$response->setCookie( $postEditKey, $val, time() + self::POST_EDIT_COOKIE_DURATION, [
			'httpOnly' => false,
		] );
	}

}
