<?php
/**
 * Controller for page editing via the basic UI.
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
 * A basic MediaWiki Web editor
 *
 * It allows to:
 *  - save an edit.
 */
class BasicEditor extends Editor {

	protected $viewClass = 'BasicEditView';

	/** @var string Effective form type */
	protected $formType = '';

	/** @var int|null */
	protected $scrollTop = null;

	/**
	 * Internal
	 */

	 /** @var bool */
	private $isCssJsSubpage = false;

	/** @var bool */
	private $isCssSubpage = false;

	/** @var bool */
	private $isJsSubpage = false;

	/** @var bool|int */
	private $contentLength = false;

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
	final public function edit() {
		// Allow extensions to modify/prevent this form or submission
		if ( !Hooks::run( 'EditorAlternateEdit', [ $this ] ) ) {
			return;
		}

		wfDebug( __METHOD__ . ": enter\n" );

		$request = $this->user->getRequest();

		// If they used redlink=1 and the page exists, redirect to the main article
		if ( $request->getBool( 'redlink' ) && $this->title->exists() ) {
			$this->output->redirect( $this->title->getFullURL() );
			return;
		}

		$this->facilitator->importFormData( $request );

		$requestType = $this->facilitator->getRequestType();

		$this->importRequestData( $request );

		if ( wfReadOnly() && $requestType === 'save' ) {
			$this->handleReadOnly( $requestType );
		}

		$this->parseFormType( $request, $requestType );
		$this->view->setFormType( $this->formType );

		$revision = $this->article->getRevisionFetched();

		$permErrors = $this->getEditPermissionErrors( $this->formType === 'save' ? 'secure' : 'full' );
		if ( $permErrors ) {
			wfDebug( __METHOD__ . ": User can't edit\n" );
			// Auto-block user's IP if the account was "hard" blocked
			if ( !wfReadOnly() ) {
				$user = $this->user;
				DeferredUpdates::addCallableUpdate( function () use ( $user ) {
					$user->spreadAnyEditBlock();
				} );
			}
			$this->displayPermissionsError( $permErrors, $revision );

			return;
		}

		// Disallow editing revisions with content models different from the current one
		// Undo edits being an exception in order to allow reverting content model changes.
		if ( $revision
			&& $revision->getContentModel() !== $this->edit->getContentModel()
		) {
			$prevRev = null;
			$undidRevId = $this->facilitator->getUndidRevId();
			if ( $undidRevId ) {
				$undidRevObj = Revision::newFromId( $undidRevId );
				$prevRev = $undidRevObj ? $undidRevObj->getPrevious() : null;
			}
			if ( !$undidRevId
				|| !$prevRev
				|| $prevRev->getContentModel() !== $this->edit->getContentModel()
			) {
				$this->disallowDifferentContentModel( $revision );
				return;
			}
		}

		// css / js subpages of user pages get a special treatment
		$this->isCssJsSubpage = $this->title->isCssJsSubpage();
		$this->isCssSubpage = $this->title->isCssSubpage();
		$this->isJsSubpage = $this->title->isJsSubpage();

		# Show applicable editing introductions
		if ( $requestType === 'initial' ) {
			$customIntro = $request->getText( 'editintro',
				// Custom edit intro for new sections
				$this->edit->getSection() === 'new' ? 'MediaWiki:addsection-editintro' : '' );
			$this->view->showIntro( $this->user->isLoggedIn(), $customIntro );
		}

		# Attempt submission here.  This will check for edit conflicts,
		# and redundantly check for locked database, blocked IPs, etc.
		# that edit() already checked just in case someone tries to sneak
		# in the back door with a hand-edited submission URL.

		if ( $this->formType === 'save' ) {
			$status = $this->facilitator->attemptSave();
			if ( !$this->handleStatus( $status ) ) {
				return;
			}
			$this->view->setIsConflict( $this->isConflict );
			$this->view->setMissingSummary( $this->missingSummary );
		}

		if ( $revision !== null && !$revision->isCurrent() ) {
			// add the old revision subtitle / navigation bar
			$this->article->setOldSubtitle( $revision->getId() );
		}

		$hookArgs = [ $this->article, $this->user, $this->edit ];
		Hooks::run( 'EditorBeforeInitialiseForm', $hookArgs );

		# First time through: get contents, set time for conflict
		# checking, etc.
		if ( $requestType === 'initial' ) {
			if ( $this->initialiseForm( $revision ) === false ) {
				$this->view->noSuchSectionPage();
				return;
			}

			if ( !$this->title->getArticleID() ) {
				$text = $this->edit->getText();
				Hooks::run( 'EditFormPreloadText', [ &$text, $this->title ] );
				$this->edit->setText( $text );
			} else {
				Hooks::run( 'EditorEditFormInitialText', [ $this->page ] );
			}

		} else {
			$this->view->setMinorEdit( $this->facilitator->getMinorEdit() );
			$this->view->setWatchThis( $this->facilitator->getWatchThis() );
		}

		// populate needed view fields
		$this->view->setScrollTop( $this->scrollTop );
		$this->view->setOldid( $this->oldid );

		$options = [
			'showtoolbar' => $this->user->getOption( 'showtoolbar' ),
			'uselivepreview' => $this->user->getOption( 'uselivepreview' ),
			'useeditwarning' => $this->user->getOption( 'useeditwarning' ),
		];

		$this->beforeStartEditForm();

		$this->view->startEditForm( $options );

		if ( !$this->isConflict &&
			$this->edit->getSection() !== '' &&
			!$this->isSectionEditSupported() ) {
			// We use $this->edit->getSection() too much before this and getVal('wgSection') directly
			// in other places at this point we can't reset $this->edit->getSection() to '' to fallback
			// to non-section editing.
			// Someone is welcome to try refactoring though
			$this->output->showErrorPage( 'sectioneditnotsupported-title',
				'sectioneditnotsupported-text' );
			return;
		}

		$isUnicodeCompliant = $this->checkUnicodeCompliantBrowser();

		$this->showHeader( $revision, $isUnicodeCompliant );

		$lastDelete = null;
		$wasDeleted = EditUtilities::wasDeletedSince( $this->title, $this->edit->getStartTime(),
			$lastDelete );

		$showToolbar = true;
		if ( $wasDeleted ) {
			if ( $this->formType === 'save' ) {
				// Hide the toolbar and edit area, user can click preview to get it back
				// Add a confirmation checkbox and explanation.
				$showToolbar = false;
			} else {
				$this->output->wrapWikiMsg( "<div class='error mw-deleted-while-editing'>\n$1\n</div>",
					'deletedwhileediting' );
			}
		}

		$this->view->showEditFormTop();

		$this->beforeEditForm();

		$this->view->showBeforeEditForm( $isUnicodeCompliant, $request->getBool( 'nosummary' ) );

		if ( $wasDeleted && $this->formType === 'save' ) {
			// Quick paranoid permission checks...
			if ( $lastDelete->log_deleted & LogPage::DELETED_USER ) {
				$username = $this->context->msg( 'rev-deleted-user' )->escaped();
			} else {
				$username = $lastDelete->user_name;
			}

			if ( $lastDelete->log_deleted & LogPage::DELETED_COMMENT ) {
				$comment = $this->context->msg( 'rev-deleted-comment' )->escaped();
			} else {
				$comment = $lastDelete->log_comment;
			}

			// It is better to not parse the comment at all than to have templates expanded in the middle
			// TODO: can the checkLabel be moved outside of the div so that wrapWikiMsg could be used?
			$key = $comment === ''
				? 'confirmrecreate-noreason'
				: 'confirmrecreate';
			$this->output->addHTML(
				'<div class="mw-confirm-recreate">' .
					$this->context->msg( $key, $username, "<nowiki>$comment</nowiki>" )->parse() .
				Xml::checkLabel( $this->context->msg( 'recreate' )->text(), 'wpRecreate', 'wpRecreate', false,
					[ 'title' => Linker::titleAttrib( 'recreate' ), 'tabindex' => 1, 'id' => 'wpRecreate' ]
				) .
				'</div>'
			);
		}

		$this->addHiddenInputFields();

		if ( !$this->isCssJsSubpage && $showToolbar && $this->user->getOption( 'showtoolbar' ) ) {
			$this->output->addHTML( EditUtilities::getEditToolbar( $this->output, $this->title ) );
		}

		$displayNone = $wasDeleted && $this->formType === 'save';
		$isOldRev = $revision !== null && !$revision->isCurrent();
		$font = $this->user->getOption( 'editfont' );
		if ( $this->isConflict ) {
			// In an edit conflict bypass the overridable content form method
			// and fallback to the raw wpTextbox1 since editconflicts can't be
			// resolved between page source edits and custom ui edits using the
			// custom edit ui.
			$this->onBeforeShowMainTextboxOnConflict();

			$content = $this->facilitator->getCurrentContent();
			$this->edit->setText( $this->toEditText( $content ) );

			$this->view->showMainTextbox( $displayNone, $isUnicodeCompliant, $isOldRev, $font );
		} else {
			$this->view->showContentForm( $displayNone, $isUnicodeCompliant, $isOldRev, $font );
		}

		$this->view->showMinorCheck( $this->user->isAllowed( 'minoredit' ) );
		$this->view->showWatchCheck( $this->user->isLoggedIn() );

		$this->view->showAfterEditForm();

		$this->insertToken();

		$this->afterEditForm( $isUnicodeCompliant );

		$this->view->showBeforeEditEnd();

		$this->endEdit();
	}

	protected function importRequestData( $request ) {
		if ( $request->wasPosted() ) {
			$this->scrollTop = $request->getIntOrNull( 'wpScrolltop' );
		}
		$this->oldid = $request->getInt( 'oldid' );
		$this->parentRevId = $request->getInt( 'parentRevId' );
	}

	protected function handleReadOnly( &$requestType ) {
		$requestType = 'initial';
	}

	protected function parseFormType( $request, $requestType ) {
		if ( $requestType === 'save' ) {
			$this->formType = 'save';
		} else {
			$this->formType = 'initial';
		}
	}

	protected function beforeStartEditForm() {
	}

	protected function beforeEditForm() {
	}

	protected function afterEditForm( $isUnicodeCompliant ) {
	}

	protected function onBeforeShowMainTextboxOnConflict() {
	}

	protected function endEdit() {
	}

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
	private function insertToken() {
		$this->output->addHTML( "\n" .
			Html::hidden( "wpEditToken", $this->user->getEditToken() ) . "\n" );
	}

	public function showHeader( $revision, $isUnicodeCompliant ) {
		global $wgAllowUserCss, $wgAllowUserJs;

		if ( $this->isConflict ) {
			$this->addExplainConflictHeader();
			$this->edit->setLastRevisionTime( $this->page->getLatest() );
		} else {
			if ( $this->edit->getSection() !== '' && $this->edit->getSection() !== 'new' ) {
				if ( !$this->edit->getSummary() && $this->formType !== 'preview' &&
					!$this->formType !== 'diff'
				) {
					// FIXME: use Content object
					$sectionTitle = EditUtilities::extractSectionTitle( $this->edit->getText() );
					if ( $sectionTitle !== false ) {
						$this->edit->setSummary( "/* $sectionTitle */ " );
					}
				}
			}

			if ( $this->missingComment ) {
				$this->output->wrapWikiMsg( "<div id='mw-missingcommenttext'>\n$1\n</div>",
					'missingcommenttext' );
			}

			if ( $this->missingSummary && $this->edit->getSection() !== 'new' ) {
				$this->output->wrapWikiMsg( "<div id='mw-missingsummary'>\n$1\n</div>",
					'missingsummary' );
			}

			if ( $this->missingSummary && $this->edit->getSection() === 'new' ) {
				$this->output->wrapWikiMsg( "<div id='mw-missingcommentheader'>\n$1\n</div>",
					'missingcommentheader' );
			}

			if ( $this->blankArticle ) {
				$this->output->wrapWikiMsg( "<div id='mw-blankarticle'>\n$1\n</div>",
					'blankarticle' );
			}

			if ( $this->selfRedirect ) {
				$this->output->wrapWikiMsg( "<div id='mw-selfredirect'>\n$1\n</div>",
					'selfredirect' );
			}

			if ( $this->hookStatus !== null ) {
				$this->output->addWikiText( '<div class="error">' ."\n" .
					$this->hookStatus->getWikiText( false, false, $this->context->getLanguage() )
				. '</div>' );
			}

			if ( !$isUnicodeCompliant ) {
				$this->output->addWikiMsg( 'nonunicodebrowser' );
			}

			if ( $this->edit->getSection() !== 'new' ) {
				if ( $revision ) {
					// Let sysop know that this will make private content public if saved

					if ( !$revision->userCan( Revision::DELETED_TEXT, $this->user ) ) {
						$this->output->wrapWikiMsg(
							"<div class='mw-warning plainlinks'>\n$1\n</div>\n",
							'rev-deleted-text-permission'
						);
					} elseif ( $revision->isDeleted( Revision::DELETED_TEXT ) ) {
						$this->output->wrapWikiMsg(
							"<div class='mw-warning plainlinks'>\n$1\n</div>\n",
							'rev-deleted-text-view'
						);
					}

					if ( !$revision->isCurrent() ) {
						$this->output->addWikiMsg( 'editingold' );
					}
				} elseif ( $this->title->exists() ) {
					// Something went wrong

					$this->output->wrapWikiMsg( "<div class='errorbox'>\n$1\n</div>\n",
						[ 'missing-revision', $this->oldid ] );
				}
			}
		}

		if ( wfReadOnly() ) {
			$this->output->wrapWikiMsg(
				"<div id=\"mw-read-only-warning\">\n$1\n</div>",
				[ 'readonlywarning', wfReadOnlyReason() ]
			);
		} elseif ( $this->user->isAnon() ) {
			$this->view->outputAnonWarning();
		} else {
			if ( $this->isCssJsSubpage ) {
				# Check the skin exists
				if ( $this->isWrongCaseCssJsPage() ) {
					$this->output->wrapWikiMsg(
						"<div class='error' id='mw-userinvalidcssjstitle'>\n$1\n</div>",
						[ 'userinvalidcssjstitle', $this->title->getSkinFromCssJsSubpage() ]
					);
				}
				if ( $this->title->isSubpageOf( $this->user->getUserPage() ) ) {
					$this->output->wrapWikiMsg( '<div class="mw-usercssjspublic">$1</div>',
						$this->isCssSubpage ? 'usercssispublic' : 'userjsispublic'
					);
					if ( $this->showCssJsWarnings() ) {
						if ( $this->isCssSubpage && $wgAllowUserCss ) {
							$this->output->wrapWikiMsg(
								"<div id='mw-usercssyoucanpreview'>\n$1\n</div>",
								[ 'usercssyoucanpreview' ]
							);
						}

						if ( $this->isJsSubpage && $wgAllowUserJs ) {
							$this->output->wrapWikiMsg(
								"<div id='mw-userjsyoucanpreview'>\n$1\n</div>",
								[ 'userjsyoucanpreview' ]
							);
						}
					}
				}
			}
		}

		$this->view->addPageProtectionWarningHeaders();

		if ( $this->contentLength === false ) {
			$this->contentLength = strlen( $this->edit->getText() );
		}
		$this->view->addLongPageWarningHeader( $this->contentLength, $this->tooBig );

		# Add header copyright warning
		$this->view->showHeaderCopyrightWarning();
	}

	protected function showCssJsWarnings() {
		return true;
	}

	/**
	 * @since 1.29
	 */
	protected function addExplainConflictHeader() {
	}

	protected function addHiddenInputFields() {
		# When the summary is hidden, also hide it on subsequent post requests (e.g. preview)
		$request = $this->context->getRequest();
		$noSummary = $request->getBool( 'nosummary' );
		if ( $noSummary ) {
			$this->output->addHTML( Html::hidden( 'nosummary', true ) );
		}

		# If a blank edit summary was previously provided, and the appropriate
		# user preference is active, pass a hidden tag as wpIgnoreBlankSummary. This will stop the
		# user being bounced back more than once in the event that a summary
		# is not required.
		# ####
		# For a bit more sophisticated detection of blank summaries, hash the
		# automatic one and pass that in the hidden field wpAutoSummary.
		if ( $this->missingSummary || ( $this->edit->getSection() === 'new' && $noSummary ) ) {
			$this->output->addHTML( Html::hidden( 'wpIgnoreBlankSummary', true ) );
		}

		$undidRevId = $this->facilitator->getUndidRevId();
		if ( $undidRevId ) {
			$this->output->addHTML( Html::hidden( 'wpUndidRevision', $undidRevId ) );
		}

		if ( $this->selfRedirect ) {
			$this->output->addHTML( Html::hidden( 'wpIgnoreSelfRedirect', true ) );
		}

		if ( $this->facilitator->hasPresetSummary() ) {
			// If a summary has been preset using &summary= we don't want to prompt for
			// a different summary. Only prompt for a summary if the summary is blanked.
			// (Bug 17416)
			$autosumm = md5( '' );
		} elseif ( $this->facilitator->getAutoSummary() ) {
			$autosumm = $this->facilitator->getAutoSummary();
		} else {
			$autosumm = md5( $this->edit->getSummary() );
		}

		$this->output->addHTML( Html::hidden( 'wpAutoSummary', $autosumm ) );

		$this->output->addHTML( Html::hidden( 'oldid', $this->oldid ) );
		$this->output->addHTML( Html::hidden( 'parentRevId', $this->parentRevId ) );

		$this->output->addHTML( Html::hidden( 'format', $this->edit->getContentFormat() ) );
		$this->output->addHTML( Html::hidden( 'model', $this->edit->getContentModel() ) );

		if ( $this->blankArticle ) {
			$this->output->addHTML( Html::hidden( 'wpIgnoreBlankArticle', true ) );
		}
	}

	/**
	 * Display a permissions error page, like OutputPage::showPermissionsErrorPage()
	 *
	 * @since 1.19
	 * @param array $permErrors Array of permissions errors, as returned by
	 *    Title::getUserPermissionsErrors().
	 * @throws PermissionsError
	 */
	protected function displayPermissionsError( array $permErrors, $revision ) {
		$action = $this->title->exists() ? 'edit' :
			( $this->title->isTalkPage() ? 'createtalk' : 'createpage' );
		throw new PermissionsError( $action, $permErrors );
	}

	protected function disallowDifferentContentModel( $revision ) {
		throw new FatalError(
			$this->context->msg(
				'contentmodelediterror',
				$revision->getContentModel(),
				$this->edit->getContentModel()
			)->plain()
		);
	}

	/**
	 * Show "your edit contains spam" page with your diff and text
	 *
	 * @param string|array|bool $match Text (or array of texts) which triggered one or more filters
	 */
	public function spamPageWithContent( $match = false ) {
		throw new FatalError( $this->context->msg( 'spamprotectiontext' )->parse() );
	}

}
