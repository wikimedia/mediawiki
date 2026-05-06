<?php

namespace MediaWiki\PageEdit;

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\Content;
use MediaWiki\Content\ContentSerializationException;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\TextContent;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\EditPage\Constraint\AuthorizationConstraint;
use MediaWiki\EditPage\Constraint\ChangeTagsConstraint;
use MediaWiki\EditPage\Constraint\ContentModelChangeConstraint;
use MediaWiki\EditPage\Constraint\DefaultTextConstraint;
use MediaWiki\EditPage\Constraint\EditConstraintFactory;
use MediaWiki\EditPage\Constraint\EditConstraintRunner;
use MediaWiki\EditPage\Constraint\ExistingSectionEditConstraint;
use MediaWiki\EditPage\Constraint\ImageRedirectConstraint;
use MediaWiki\EditPage\Constraint\MissingCommentConstraint;
use MediaWiki\EditPage\Constraint\NewSectionMissingSubjectConstraint;
use MediaWiki\EditPage\Constraint\PageSizeConstraint;
use MediaWiki\EditPage\Constraint\RevisionDeletedConstraint;
use MediaWiki\EditPage\IEditObject;
use MediaWiki\EditPage\NotDirectlyEditableException;
use MediaWiki\EditPage\PageEditingHelper;
use MediaWiki\Language\Language;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\WikiPage;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Permissions\RateLimiter;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\ShadowPage\ShadowPageLoader;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\PageUpdateCauses;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use MediaWiki\Watchlist\WatchlistManager;
use Wikimedia\Message\MessageValue;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @internal
 * @since 1.47
 */
class PageEdit implements IEditObject {

	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::EnableWatchlistLabels,
		MainConfigNames::UseNPPatrol,
		MainConfigNames::UseRCPatrol,
	];

	private int|false $contentLength = false;
	private bool $isConflict = false;
	private ?bool $nullEdit = null;
	private int $parentRevId;
	private ?bool $redirect = null;
	private string $section;
	private ?string $sectionanchor = null;
	private string $summary;
	private string $textbox1;

	public function __construct(
		private readonly PageEditInputs $inputs,
		private readonly ServiceOptions $options,
		private readonly IContentHandlerFactory $contentHandlerFactory,
		private readonly EditConstraintFactory $constraintFactory,
		private readonly IConnectionProvider $connectionProvider,
		private readonly Language $contentLanguage,
		private readonly ContentTransformer $contentTransformer,
		private readonly PageEditingHelper $pageEditingHelper,
		private readonly RateLimiter $rateLimiter,
		private readonly RevisionStore $revisionStore,
		private readonly ShadowPageLoader $shadowPageLoader,
		private readonly WatchlistManager $watchlistManager,
		private readonly WatchedItemStoreInterface $watchedItemStore,
		private readonly WikiPageFactory $wikiPageFactory,
	) {
		$this->options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->parentRevId = $inputs->getParentRevId();
		$this->section = $inputs->getSection();
		$this->summary = $inputs->getSummary();
		$this->textbox1 = $inputs->getTextbox1();
	}

	/**
	 * Perform checks, perform the edit if they pass, and wrap the resulting data in a PageEditResult object.
	 */
	public function edit(): PageEditResult {
		$status = $this->doEdit();
		return new PageEditResult(
			$status,

			contentLength: $this->contentLength,
			isConflict: $this->isConflict,
			nullEdit: $this->nullEdit,
			parentRevId: $this->parentRevId,
			redirect: $this->redirect,
			section: $this->section,
			sectionanchor: $this->sectionanchor,
			summary: $this->summary,
			textbox1: $this->textbox1,
		);
	}

	/**
	 * Perform checks, and perform the edit if they pass.
	 *
	 * @return PageEditStatus Status object, possibly with a message, but always with one of the AS_* constants as
	 * its value,
	 */
	private function doEdit(): PageEditStatus {
		try {
			# Construct Content object
			$textbox_content = $this->convertTextToContent( $this->textbox1 );
		} catch ( ContentSerializationException $ex ) {
			return PageEditStatus::newFatal(
				'content-failed-to-parse',
				$this->inputs->getContentModel(),
				$this->inputs->getContentFormat() ?? '',
				$ex->getMessage(),
			)->setValue( self::AS_PARSE_ERROR );
		}
		'@phan-var Content $textbox_content';

		$this->contentLength = strlen( $this->textbox1 );

		$requestUser = $this->inputs->getContext()->getUser();
		$pstUser = $this->inputs->getUserForPreview();

		$page = $this->wikiPageFactory->newFromTitle( $this->inputs->getPage() );

		$changingContentModel = false;
		if ( $this->inputs->getContentModel() !== $page->getTitle()->getContentModel() ) {
			$changingContentModel = true;
			$oldContentModel = $page->getTitle()->getContentModel();
		}

		// Load the page data from the primary DB. If anything changes in the meantime,
		// we detect it by using page_latest like a token in a 1 try compare-and-swap.
		$page->loadPageData( IDBAccessObject::READ_LATEST );
		$new = !$page->exists();

		$preliminaryChecksRunner = $this->getPreliminaryChecksRunner(
			$new,
			$textbox_content,
			$requestUser,
			$page->getTitle(),
		);
		$status = $preliminaryChecksRunner->checkConstraints();
		if ( !$status->isOK() ) {
			return $status;
		}

		$flags = EDIT_AUTOSUMMARY |
			( $new ? EDIT_NEW : EDIT_UPDATE ) |
			( $this->inputs->shouldMarkAsMinor() ? EDIT_MINOR : 0 ) |
			( $this->inputs->shouldMarkAsBot() ? EDIT_FORCE_BOT : 0 );

		$sectiontitle = $this->inputs->getSectiontitle();

		if ( $new ) {
			$content = $textbox_content;

			$this->sectionanchor = '';
			if ( $this->section === 'new' ) {
				if ( $sectiontitle !== null ) {
					// Insert the section title above the content.
					$content = $content->addSectionHeader( $sectiontitle );
				}
				$this->sectionanchor = $this->inputs->getNewSectionAnchor();
			}

			$pageUpdater = $page->newPageUpdater( $pstUser )
				->setContent( SlotRecord::MAIN, $content );
			$pageUpdater->prepareUpdate( $flags );

			$newPageChecksRunner = $this->getNewPageChecksRunner( $content, $pstUser );
			$status = $newPageChecksRunner->checkConstraints();
			if ( !$status->isOK() ) {
				return $status;
			}
		} else { # not $new

			# Article exists. Check for edit conflict.

			$timestamp = $page->getTimestamp();
			$latest = $page->getLatest();
			$edittime = $this->inputs->getEdittime();
			$editRevId = $this->inputs->getEditRevId();

			wfDebug( "timestamp: {$timestamp}, edittime: {$edittime}" );
			wfDebug( "revision: {$latest}, editRevId: {$editRevId}" );

			$editConflictLogger = LoggerFactory::getInstance( 'EditConflict' );
			// An edit conflict is detected if the latest revision is different from the
			// revision that was current when editing was initiated on the client.
			// This is checked based on the timestamp and revision ID.
			// TODO: the timestamp based check can probably go away now.
			if ( ( $edittime !== null && $edittime != $timestamp )
				|| ( $editRevId !== null && $editRevId != $latest )
			) {
				$this->isConflict = true;
				if ( $this->section === 'new' ) {
					if ( $page->getUserText() === $requestUser->getName() &&
						$page->getComment() === $this->summary
					) {
						// Probably a duplicate submission of a new comment.
						// This can happen when CDN resends a request after
						// a timeout but the first one actually went through.
						$editConflictLogger->debug(
							'Duplicate new section submission; trigger edit conflict!'
						);
					} else {
						// New comment; suppress conflict.
						$this->isConflict = false;
						$editConflictLogger->debug( 'Conflict suppressed; new section' );
					}
				} elseif ( $this->section === ''
					&& $edittime
					&& $this->revisionStore->userWasLastToEdit(
						$this->connectionProvider->getPrimaryDatabase(),
						$this->inputs->getPage()->getId(),
						$requestUser->getId(),
						$edittime
					)
				) {
					# Suppress edit conflict with self, except for section edits where merging is required.
					$editConflictLogger->debug( 'Suppressing edit conflict, same user.' );
					$this->isConflict = false;
				}
			}

			if ( $this->isConflict ) {
				$editConflictLogger->debug(
					'Conflict! Getting section {section} for time {editTime}'
					. ' (id {editRevId}, article time {timestamp})',
					[
						'section' => $this->section,
						'editTime' => $edittime,
						'editRevId' => $editRevId,
						'timestamp' => $timestamp,
					]
				);
				// @TODO: replaceSectionAtRev() with base ID (not prior current) for ?oldid=X case
				// ...or disable section editing for non-latest revisions (not exposed anyway).
				if ( $editRevId !== null ) {
					$content = $page->replaceSectionAtRev(
						$this->section,
						$textbox_content,
						$sectiontitle ?? '',
						$editRevId
					);
				} else {
					$content = $page->replaceSectionContent(
						$this->section,
						$textbox_content,
						$sectiontitle ?? '',
						$edittime
					);
				}
			} else {
				$editConflictLogger->debug(
					'Getting section {section}',
					[ 'section' => $this->section ]
				);
				$content = $page->replaceSectionAtRev(
					$this->section,
					$textbox_content,
					$sectiontitle ?? ''
				);
			}

			if ( $content === null ) {
				$editConflictLogger->debug( 'Activating conflict; section replace failed.' );
				$this->isConflict = true;
				$content = $textbox_content; // do not try to merge here!
			} elseif ( $this->isConflict ) {
				// Attempt merge
				$mergedChange = $this->mergeChangesIntoContent( $content );
				if ( $mergedChange !== false ) {
					// Successful merge! Maybe we should tell the user the good news?
					$content = $mergedChange[0];
					$this->parentRevId = $mergedChange[1];
					$this->isConflict = false;
					$editConflictLogger->debug( 'Suppressing edit conflict, successful merge.' );
				} else {
					$this->section = '';
					$this->textbox1 = ( $content instanceof TextContent ) ? $content->getText() : '';
					$editConflictLogger->debug( 'Keeping edit conflict, failed merge.' );
				}
			}

			if ( $this->isConflict ) {
				return PageEditStatus::newGood( self::AS_CONFLICT_DETECTED )
					// This message isn't shown, it's just for some logging code (T423754)
					->fatal( 'editconflict', (string)$this->inputs->getContextPage() );
			}

			$pageUpdater = $page->newPageUpdater( $pstUser )
				->setContent( SlotRecord::MAIN, $content );
			$pageUpdater->prepareUpdate( $flags );

			$existingPageChecksRunner = $this->getExistingPageChecksRunner( $content, $pstUser, $page );
			$status = $existingPageChecksRunner->checkConstraints();
			if ( !$status->isOK() ) {
				return $status;
			}

			# All's well
			$sectionAnchor = '';
			if ( $this->section === 'new' ) {
				$sectionAnchor = $this->inputs->getNewSectionAnchor();
			} elseif ( $this->section !== '' ) {
				# Try to get a section anchor from the section source, redirect
				# to edited section if header found.
				# XXX: Might be better to integrate this into WikiPage::replaceSectionAtRev
				# for duplicate heading checking and maybe parsing.
				$hasmatch = preg_match( "/^ *([=]{1,6})(.*?)(\\1) *\\n/i", $this->textbox1, $matches );
				# We can't deal with anchors, includes, html etc in the header for now,
				# headline would need to be parsed to improve this.
				if ( $hasmatch && $matches[2] !== '' ) {
					$sectionAnchor = $this->pageEditingHelper->guessSectionName( $matches[2] );
				}
			}
			$this->sectionanchor = $sectionAnchor;

			// Save errors may fall down to the edit form, but we've now
			// merged the section into full text. Clear the section field
			// so that later submission of conflict forms won't try to
			// replace that into a duplicated mess.
			$this->textbox1 = $this->pageEditingHelper->toEditText(
				$content, $this->inputs->getContentFormat(), $this->inputs->shouldEnableApiEditOverride()
			) ?? '';
			$this->section = '';
		}

		// Check for length errors again now that the section is merged in
		$this->contentLength = strlen( $this->pageEditingHelper->toEditText(
			$content, $this->inputs->getContentFormat(), $this->inputs->shouldEnableApiEditOverride()
		) ?? '' );

		$postMergeChecksRunner = $this->getPostMergeChecksRunner( $content, $page );
		$status = $postMergeChecksRunner->checkConstraints();
		if ( !$status->isOK() ) {
			return $status;
		}

		if ( $this->inputs->getUndidRev() && $this->isUndoClean( $content, $page ) ) {
			// As the user can change the edit's content before saving, we only mark
			// "clean" undos as reverts. This is to avoid abuse by marking irrelevant
			// edits as undos.
			$pageUpdater
				->setOriginalRevisionId( $this->inputs->getUndoAfter() ?: false )
				->setCause( PageUpdateCauses::CAUSE_UNDO )
				->markAsRevert(
					EditResult::REVERT_UNDO,
					$this->inputs->getUndidRev(),
					$this->inputs->getUndoAfter() ?: null
				);
		}

		$needsPatrol = $this->options->get( MainConfigNames::UseRCPatrol )
			|| ( $this->options->get( MainConfigNames::UseNPPatrol ) && !$page->exists() );
		if ( $needsPatrol &&
				$this->inputs->getAuthority()->authorizeWrite( 'autopatrol', $this->inputs->getPage() ) ) {
			$pageUpdater->setRcPatrolStatus( RecentChange::PRC_AUTOPATROLLED );
		}

		$pageUpdater
			->addTags( $this->inputs->getChangeTags() )
			->saveRevision(
				CommentStoreComment::newUnsavedComment( trim( $this->summary ) ),
				$flags
			);
		$doEditStatus = $pageUpdater->getStatus();

		if ( !$doEditStatus->isOK() ) {
			// Failure from doEdit()
			// Show the edit conflict page for certain recognized errors from doEdit(),
			// but don't show it for errors from extension hooks
			if (
				$doEditStatus->failedBecausePageMissing() ||
				$doEditStatus->failedBecausePageExists() ||
				$doEditStatus->failedBecauseOfConflict()
			) {
				$this->isConflict = true;
				return PageEditStatus::cast( $doEditStatus )
					->setValue( self::AS_END );
			}
			return PageEditStatus::cast( $doEditStatus );
		}

		$this->nullEdit = !$doEditStatus->wasRevisionCreated();
		if ( $this->nullEdit ) {
			// We didn't know if it was a null edit until now, so bump the rate limit now
			$limitSubject = $requestUser->toRateLimitSubject();
			$this->rateLimiter->limit( $limitSubject, 'linkpurge' );
		}
		$this->redirect = $content->isRedirect();

		$this->updateWatchlist();

		// If the content model changed, add a log entry
		if ( $changingContentModel ) {
			$this->addContentModelChangeLogEntry(
				$this->inputs->getUserForSave(),
				// @phan-suppress-next-next-line PhanPossiblyUndeclaredVariable
				// $oldContentModel is set when $changingContentModel is true
				$new ? false : $oldContentModel,
				$this->inputs->getContentModel(),
				$this->summary
			);
		}

		// Instead of carrying the same status object throughout, it is created right
		// when it is returned, either at an earlier point due to an error or here
		// due to a successful edit.
		$statusCode = ( $new ? self::AS_SUCCESS_NEW_ARTICLE : self::AS_SUCCESS_UPDATE );
		return PageEditStatus::newGood( $statusCode );
	}

	/**
	 * @param bool $new
	 * @param Content $newContent
	 * @param User $requestUser
	 * @param Title $title
	 */
	private function getPreliminaryChecksRunner(
		bool $new,
		Content $newContent,
		User $requestUser,
		$title,
	): EditConstraintRunner {
		return new EditConstraintRunner(
			// Ensure that the context request does not have `wpAntispam` set
			// Use $user since there is no permissions aspect
			$this->constraintFactory->newSimpleAntiSpamConstraint(
				$this->inputs->getContext()->getRequest()->getText( 'wpAntispam' ),
				$requestUser,
				$this->inputs->getPage(),
			),

			// Ensure that the summary and text don't match the spam regex
			$this->constraintFactory->newSpamRegexConstraint(
				$this->summary,
				$this->inputs->getSectiontitle(),
				$this->textbox1,
				$this->inputs->getContext()->getRequest()->getIP(),
				$this->inputs->getPage(),
			),

			new ImageRedirectConstraint(
				$newContent,
				$this->inputs->getPage(),
				$this->inputs->getAuthority()
			),

			$this->constraintFactory->newReadOnlyConstraint(),

			new AuthorizationConstraint(
				$this->inputs->getAuthority(),
				$this->inputs->getPage(),
				$new
			),

			new ContentModelChangeConstraint(
				$this->inputs->getAuthority(),
				$title,
				$this->inputs->getContentModel()
			),

			$this->constraintFactory->newLinkPurgeRateLimitConstraint( $requestUser->toRateLimitSubject() ),

			// Same constraint is used to check size before and after merging the
			// edits, which use different failure codes
			$this->constraintFactory->newPageSizeConstraint(
				$this->contentLength,
				PageSizeConstraint::BEFORE_MERGE
			),

			new ChangeTagsConstraint( $this->inputs->getAuthority(), $this->inputs->getChangeTags() ),

			// If the article has been deleted while editing, don't save it without confirmation
			$this->constraintFactory->newAccidentalRecreationConstraint(
				$title,
				$this->inputs->shouldRecreate(),
				$this->inputs->getStarttime(),
				$this->inputs->getSubmitButtonLabel(),
			)
		);
	}

	private function getNewPageChecksRunner(
		Content $content,
		UserIdentity $pstUser,
	): EditConstraintRunner {
		return new EditConstraintRunner(
		// Don't save a new page if it's blank or if it's a MediaWiki:
		// message with content equivalent to default (allow empty pages
		// in this case to disable messages, see T52124)
			new DefaultTextConstraint(
				$this->shadowPageLoader,
				$this->inputs->getPage(),
				$this->inputs->shouldAllowBlankArticle(),
				$this->textbox1,
				$this->inputs->getSubmitButtonLabel()
			),

			$this->constraintFactory->newEditFilterMergedContentHookConstraint(
				$content,
				$this->inputs->getContext(),
				$this->summary,
				$this->inputs->shouldMarkAsMinor(),
				$this->inputs->getContext()->getLanguage(),
				$pstUser
			),
		);
	}

	/**
	 * @param Content $content
	 * @param UserIdentity $pstUser
	 * @param WikiPage $page
	 */
	private function getExistingPageChecksRunner(
		Content $content,
		UserIdentity $pstUser,
		$page,
	): EditConstraintRunner {
		$revision = $this->inputs->getOldid()
			? $this->revisionStore->getRevisionById( $this->inputs->getOldid() )
			: $page->getRevisionRecord();
		return new EditConstraintRunner(
			$this->constraintFactory->newEditFilterMergedContentHookConstraint(
				$content,
				$this->inputs->getContext(),
				$this->summary,
				$this->inputs->shouldMarkAsMinor(),
				$this->inputs->getContext()->getLanguage(),
				$pstUser
			),
			new NewSectionMissingSubjectConstraint(
				$this->section,
				$this->inputs->getSectiontitle() ?? '',
				$this->inputs->shouldAllowBlankSummary(),
				$this->inputs->getSubmitButtonLabel()
			),
			new MissingCommentConstraint( $this->section, $this->textbox1 ),
			new ExistingSectionEditConstraint(
				$this->section,
				$this->summary,
				$this->inputs->getAutoSumm(),
				$this->inputs->shouldAllowBlankSummary(),
				$content,
				$this->pageEditingHelper->getOriginalContent(
					$this->inputs->getAuthority(),
					$page,
					$revision,
					$this->inputs->getContentModel(),
					$this->section,
				),
				$this->inputs->getSubmitButtonLabel()
			),
			new RevisionDeletedConstraint(
				$this->inputs->shouldIgnoreRevisionDeletedWarning(),
				$this->inputs->getOldid(),
				$revision,
				$this->section,
				$page->getTitle(),
				$this->inputs->getAuthority(),
				MessageValue::new(
					'edit-constraint-warning-wrapper-save-deleted-revision',
					[ $this->inputs->getSubmitButtonLabel() ],
				),
			),
		);
	}

	/**
	 * @param Content $content
	 * @param WikiPage $page
	 */
	private function getPostMergeChecksRunner(
		Content $content,
		$page,
	): EditConstraintRunner {
		$constraintRunner = new EditConstraintRunner();
		if ( !$this->inputs->shouldIgnoreProblematicRedirects() ) {
			$constraintRunner->addConstraint(
				$this->constraintFactory->newRedirectConstraint(
					$this->inputs->getAllowedProblematicRedirectTarget(),
					$content,
					$this->pageEditingHelper->getCurrentContent(
						$this->inputs->getContentModel(), $page,
					),
					$this->inputs->getPage(),
					MessageValue::new(
						'edit-constraint-warning-wrapper-save',
						[ $this->inputs->getSubmitButtonLabel() ],
					),
					$this->inputs->getContentFormat(),
				)
			);
		}
		$constraintRunner->addConstraint(
		// Same constraint is used to check size before and after merging the
		// edits, which use different failure codes
			$this->constraintFactory->newPageSizeConstraint(
				$this->contentLength,
				PageSizeConstraint::AFTER_MERGE
			)
		);
		return $constraintRunner;
	}

	/**
	 * Unserialize the given text into a Content object.
	 *
	 * @throws NotDirectlyEditableException
	 * @throws ContentSerializationException
	 */
	private function convertTextToContent( string $text ): ?Content {
		$content = $this->contentHandlerFactory
			->getContentHandler( $this->inputs->getContentModel() )
			->unserializeContent( $text, $this->inputs->getContentFormat() );

		if ( !$this->pageEditingHelper->isSupportedContentModel(
			$content->getModel(),
			$this->inputs->shouldEnableApiEditOverride()
		) ) {
			throw new NotDirectlyEditableException( 'This content model is not supported: ' . $content->getModel() );
		}

		return $content;
	}

	/**
	 * Does checks and compares the automatically generated undo content with the
	 * one that was submitted by the user. If they match, the undo is considered "clean".
	 * Otherwise there is no guarantee if anything was reverted at all, as the user could
	 * even swap out entire content.
	 *
	 * @param Content $content
	 * @param WikiPage $page
	 */
	private function isUndoClean( Content $content, $page ): bool {
		// Check whether the undo was "clean", that is the user has not modified
		// the automatically generated content.
		$undoRev = $this->revisionStore->getRevisionById( $this->inputs->getUndidRev() );
		if ( $undoRev === null ) {
			return false;
		}

		if ( $this->inputs->getUndoAfter() ) {
			$oldRev = $this->revisionStore->getRevisionById( $this->inputs->getUndoAfter() );
		} else {
			$oldRev = $this->revisionStore->getPreviousRevision( $undoRev );
		}

		if ( $oldRev === null ||
			$undoRev->isDeleted( RevisionRecord::DELETED_TEXT ) ||
			$oldRev->isDeleted( RevisionRecord::DELETED_TEXT )
		) {
			return false;
		}

		$undoContent = $this->pageEditingHelper->getUndoContent( $page, $undoRev, $oldRev, $undoError );
		if ( !$undoContent ) {
			return false;
		}

		// Do a pre-save transform on the retrieved undo content
		$user = $this->inputs->getUserForPreview();
		$parserOptions = ParserOptions::newFromUserAndLang( $user, $this->contentLanguage );
		$undoContent = $this->contentTransformer->preSaveTransform(
			$undoContent, $this->inputs->getPage(), $user, $parserOptions
		);

		if ( $undoContent->equals( $content ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Attempts to do 3-way merge of edit content with a base revision
	 * and current content, in case of edit conflict, in whichever way appropriate
	 * for the content type.
	 *
	 * @return array{0:Content,1:int}|false either `false` or an array of the new Content and the
	 *   updated parent revision id
	 */
	private function mergeChangesIntoContent( Content $editContent ): array|false {
		// This is the revision that was current at the time editing was initiated on the client,
		// even if the edit was based on an old revision.
		$baseRevRecord = $this->pageEditingHelper->getExpectedParentRevision(
			$this->inputs->getEditRevId(),
			$this->inputs->getEdittime(),
			$this->inputs->getPage(),
		);
		$baseContent = $baseRevRecord?->getContent( SlotRecord::MAIN );

		if ( $baseContent === null ) {
			return false;
		} elseif ( $baseRevRecord->isCurrent() ) {
			// Impossible to have a conflict when the user just edited the latest revision. This can
			// happen e.g. when $wgDiff3 is badly configured.
			return [ $editContent, $baseRevRecord->getId() ];
		}

		// The current state, we want to merge updates into it
		$currentRevisionRecord = $this->revisionStore->getRevisionByTitle(
			$this->inputs->getPage(),
			0,
			IDBAccessObject::READ_LATEST
		);
		$currentContent = $currentRevisionRecord?->getContent( SlotRecord::MAIN );

		if ( $currentContent === null ) {
			return false;
		}

		$mergedContent = $this->contentHandlerFactory
			->getContentHandler( $baseContent->getModel() )
			->merge3( $baseContent, $editContent, $currentContent );

		if ( $mergedContent ) {
			// Also need to update parentRevId to what we just merged.
			return [ $mergedContent, $currentRevisionRecord->getId() ];
		}

		return false;
	}

	/**
	 * @param UserIdentity $user
	 * @param string|false $oldModel false if the page is being newly created
	 * @param string $newModel
	 * @param string $reason
	 */
	private function addContentModelChangeLogEntry(
		UserIdentity $user,
		string|false $oldModel,
		string $newModel,
		string $reason,
	): void {
		$new = $oldModel === false;
		$log = new ManualLogEntry( 'contentmodel', $new ? 'new' : 'change' );
		$log->setPerformer( $user );
		$log->setTarget( $this->inputs->getPage() );
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
	private function updateWatchlist(): void {
		$authority = $this->inputs->getAuthority();
		if ( !$authority->isNamed() ) {
			return;
		}

		$watch = $this->inputs->shouldWatchthis();
		$watchlistExpiry = $this->inputs->getWatchlistExpiry();

		// This can't run as a DeferredUpdate due to a possible race condition
		// when the post-edit redirect happens if the pendingUpdates queue is
		// too large to finish in time (T259564)
		$this->watchlistManager->setWatch(
			$watch,
			$authority,
			$this->inputs->getPage(),
			$watchlistExpiry,
			$this->inputs->getWatchlistLabels()
		);

		$this->watchedItemStore->maybeEnqueueWatchlistExpiryJob();
	}

}
