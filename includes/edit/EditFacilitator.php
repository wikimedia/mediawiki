<?php
/**
 * Transforms user input into an edit form data and attempt save
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

use MediaWiki\Logger\LoggerFactory;

/**
 * This class transforms user input (such as from a WebRequest) into an Edit object.
 * It then allows to make a save attempt via the EditAttempt class.
 * It furthermore provides access to the relevant content objects for the edit.
 * Subclasses should fill $this->edit based on user input.
 */
abstract class EditFacilitator {

	/**
	 * Constructor-set
	 */

	protected $page;

	protected $title;

	protected $user;

	protected $edit;

	/**
	 * Editing options from request
	 */

	/** @var bool */
	protected $allowBlankSummary = false;

	/** @var bool */
	protected $allowBlankArticle = false;

	/** @var bool */
	protected $allowSelfRedirect = false;

	/** @var bool */
	protected $recreate = false;

	/** @var bool */
	protected $bot = true;

	/** @var bool */
	protected $minorEdit = false;

	/** @var bool */
	protected $watchThis = false;

	/**
	 * Needed to attempt save
	 */

	/** @var null|array */
	protected $changeTags = null;

	/** @var string */
	protected $autoSumm = '';

	/** @var int */
	protected $undidRevId = 0;

	/**
	 * @param WikiPage $page
	 * @param User $user
	 */
	final public function __construct( WikiPage $page, User $user ) {
		$this->page = $page;
		$this->title = $this->page->getTitle(); // shortcut
		$this->user = $user;
		$this->edit = new Edit();
	}

	final public function getPage() {
		return $this->page;
	}

	final public function getUser() {
		return $this->user;
	}

	final public function getEdit() {
		return $this->edit;
	}

	/**
	 * Attempt submission
	 * @param array|bool $resultDetails See docs for $result in internalAttemptSave
	 * @throws UserBlockedError|ReadOnlyError|ThrottledError|PermissionsError
	 * @return Status The resulting status object.
	 */
	final public function attemptSave() {
		$options = [
			'allowBlankArticle' => $this->allowBlankArticle,
			'allowBlankSummary' => $this->allowBlankSummary,
			'allowSelfRedirect' => $this->allowSelfRedirect,
			'bot' => $this->bot,
			'recreate' => $this->recreate,
			'minorEdit' => $this->minorEdit,
			'watchThis' => $this->watchThis,
		];
		$info = [
			'changeTags' => $this->changeTags,
			'autoSumm' => $this->autoSumm,
			'undidRevId' => $this->undidRevId,
		];

		$editAttempt = new EditAttempt( $this->page, $this->user, $this->edit );

		$status = $editAttempt->performSave( $info, $options );

		$hookArgs = [ $this->page, $this->user, $this->edit, $status ];
		Hooks::run( 'EditFacilitatorAfterAttemptSave', $hookArgs );

		return $status;
	}

	final public function getAutoSummary() {
		return $this->autoSumm;
	}

	final public function getUndidRevId() {
		return $this->undidRevId;
	}

	final public function getMinorEdit() {
		return $this->minorEdit;
	}

	final public function getWatchThis() {
		return $this->watchThis;
	}

	/**
	 * @param Content|null $def_content The default value to return
	 *
	 * @return Content|null Content on success, $def_content for invalid sections
	 *
	 * @since 1.21
	 */
	public function getContentObject( $revision, &$undoMsg = null, $def_content = null ) {
		global $wgContLang;

		$content = false;
		$section = $this->edit->getSection();

		// For message page not locally set, use the i18n message.
		// For other non-existent articles, use preload text if any.
		if ( !$this->title->exists() || $section === 'new' ) {
			if ( $this->title->getNamespace() === NS_MEDIAWIKI && $section !== 'new' ) {
				# If this is a system message, get the default text.
				$msg = $this->title->getDefaultMessageText();

				$content = $this->toEditContent( $msg );

				if ( !$this->isSupportedContentModel( $content->getModel() ) ) {
					throw new MWException( 'This content model is not supported: ' . $content->getModel() );
				}
			}
			if ( $content === false ) {
				# If requested, preload some text.
				$preload = $this->user->getRequest()->getVal( 'preload',
					// Custom preload text for new sections
					$section === 'new' ? 'MediaWiki:addsection-preload' : '' );
				$params = $this->user->getRequest()->getArray( 'preloadparams', [] );

				$content = $this->getPreloadedContent( $preload, $params );
			}
		// For existing pages, get text based on "undo" or section parameters.
		} else {
			if ( $section !== '' ) {
				// Get section edit text (returns $def_text for invalid sections)
				$orig = $this->getOriginalContent( $revision );
				$content = $orig ? $orig->getSection( $section ) : null;

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
								// model if it's reverting the top
								// edit. This can result in
								// mismatched content model/format.
								$this->edit->setContentModelAndFormat( $newContent->getModel(),
									$oldrev->getContentFormat() );
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
									if ( $this->edit->getSummary() === '' ) {
										$this->edit->setSummary( $undoSummary );
									} else {
										$this->edit->setSummary( $undoSummary .
											wfMessage( 'colon-separator' )->title( $this->title )
												->inContentLanguage()->text() .
											$this->edit->getSummary() );
									}
									$this->undidRevId = $undo;
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
		if ( $this->edit->getSection() === 'new' ) {
			return $this->getCurrentContent();
		}
		if ( $revision === null ) {
			// $this->edit->getContentModel is set at this point
			$handler = ContentHandler::getForModelID( $this->edit->getContentModel() );

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
		$handler = ContentHandler::getForModelID( $this->edit->getContentModel() );

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
	 * Get the current content of the page. This is basically similar to
	 * WikiPage::getContent( Revision::RAW ) except that when the page doesn't exist an empty
	 * content object is returned instead of null.
	 *
	 * @since 1.21
	 * @return Content
	 */
	final public function getCurrentContent() {
		$rev = $this->page->getRevision();
		$content = $rev ? $rev->getContent( Revision::RAW ) : null;

		if ( $content === false || $content === null ) {
			// $this->edit->getContentModel() is set at this point
			$handler = ContentHandler::getForModelID( $this->edit->getContentModel() );

			return $handler->makeEmptyContent();
		} elseif ( !$this->undidRevId ) {
			// Content models should always be the same since we error
			// out if they are different before this point (in ->edit()).
			// The exception being, during an undo, the current revision might
			// differ from the prior revision.
			if ( $this->edit->getContentModel() !== $rev->getContentModel() ) {
				$logger = LoggerFactory::getInstance( 'editcontroller' );
				$logger->warning( "Overriding content model from current edit {prev} to {new}", [
					'prev' => $this->edit->getContentModel(),
					'new' => $rev->getContentModel(),
					'title' => $this->title->getPrefixedDBkey(),
					'method' => __METHOD__
				] );
				// Given that the content models should match, the current selected
				// format should be supported.
				if ( !$content->isSupportedFormat( $this->edit->getContentFormat() ) ) {
					$logger->warning( "Current revision content format unsupported. " .
						"Overriding {prev} to {new}", [

						'prev' => $this->data->contentFormat,
						'new' => $rev->getContentFormat(),
						'title' => $this->title->getPrefixedDBkey(),
						'method' => __METHOD__
					] );
				}
				$this->edit->setContentModelAndFormat( $rev->getContentModel(),
					$rev->getContentFormat() );
			}
		}
		return $content;
	}

}
