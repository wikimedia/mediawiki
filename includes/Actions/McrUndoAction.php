<?php
/**
 * Temporary action for MCR undos
 * @file
 * @ingroup Actions
 */

namespace MediaWiki\Actions;

use DifferenceEngine;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Config\Config;
use MediaWiki\Context\IContextSource;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Exception\MWContentSerializationException;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Linker\Linker;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\Article;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\PageUpdateCauses;
use MediaWiki\User\User;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * Temporary action for MCR undos
 *
 * This is intended to go away when real MCR support is added to EditPage and
 * the standard undo-with-edit behavior can be implemented there instead.
 *
 * If this were going to be kept, we'd probably want to figure out a good way
 * to reuse the same code for generating the headers, summary box, and buttons
 * on EditPage and here, and to better share the diffing and preview logic
 * between the two. But doing that now would require much of the rewriting of
 * EditPage that we're trying to put off by doing this instead.
 *
 * @ingroup Actions
 * @since 1.32
 */
class McrUndoAction extends FormAction {

	protected int $undo = 0;
	protected int $undoafter = 0;
	protected int $cur = 0;

	/** @var RevisionRecord|null */
	protected $curRev = null;

	private ReadOnlyMode $readOnlyMode;
	private RevisionLookup $revisionLookup;
	private RevisionRenderer $revisionRenderer;
	private CommentFormatter $commentFormatter;
	private bool $useRCPatrol;

	/**
	 * @param Article $article
	 * @param IContextSource $context
	 * @param ReadOnlyMode $readOnlyMode
	 * @param RevisionLookup $revisionLookup
	 * @param RevisionRenderer $revisionRenderer
	 * @param CommentFormatter $commentFormatter
	 * @param Config $config
	 */
	public function __construct(
		Article $article,
		IContextSource $context,
		ReadOnlyMode $readOnlyMode,
		RevisionLookup $revisionLookup,
		RevisionRenderer $revisionRenderer,
		CommentFormatter $commentFormatter,
		Config $config
	) {
		parent::__construct( $article, $context );
		$this->readOnlyMode = $readOnlyMode;
		$this->revisionLookup = $revisionLookup;
		$this->revisionRenderer = $revisionRenderer;
		$this->commentFormatter = $commentFormatter;
		$this->useRCPatrol = $config->get( MainConfigNames::UseRCPatrol );
	}

	/** @inheritDoc */
	public function getName() {
		return 'mcrundo';
	}

	/** @inheritDoc */
	public function getDescription() {
		return '';
	}

	/** @inheritDoc */
	public function getRestriction() {
		// Require 'edit' permission to even see this action (T297322)
		return 'edit';
	}

	/** @inheritDoc */
	public function show() {
		// Send a cookie so anons get talk message notifications
		// (copied from SubmitAction)
		$this->getRequest()->getSession()->persist();

		// Some stuff copied from EditAction
		$this->useTransactionalTimeLimit();

		$out = $this->getOutput();
		$out->setRobotPolicy( 'noindex,nofollow' );

		// IP warning headers copied from EditPage
		// (should more be copied?)
		if ( $this->readOnlyMode->isReadOnly() ) {
			$out->wrapWikiMsg(
				"<div id=\"mw-read-only-warning\">\n$1\n</div>",
				[ 'readonlywarning', $this->readOnlyMode->getReason() ]
			);
		} elseif ( $this->context->getUser()->isAnon() ) {
			// Note: EditPage has a special message for temp user creation intent here.
			// But McrUndoAction doesn't support user creation.
			if ( !$this->getRequest()->getCheck( 'wpPreview' ) ) {
				$out->addHTML(
					Html::warningBox(
						$out->msg(
							'anoneditwarning',
							// Log-in link
							SpecialPage::getTitleFor( 'Userlogin' )->getFullURL( [
								'returnto' => $this->getTitle()->getPrefixedDBkey()
							] ),
							// Sign-up link
							SpecialPage::getTitleFor( 'CreateAccount' )->getFullURL( [
								'returnto' => $this->getTitle()->getPrefixedDBkey()
							] )
						)->parse(),
						'mw-anon-edit-warning'
					)
				);
			} else {
				$out->addHTML(
					Html::warningBox(
						$out->msg( 'anonpreviewwarning' )->parse(),
						'mw-anon-preview-warning'
					)
				);
			}
		}

		parent::show();
	}

	protected function initFromParameters() {
		$this->undoafter = $this->getRequest()->getInt( 'undoafter' );
		$this->undo = $this->getRequest()->getInt( 'undo' );

		if ( $this->undo == 0 || $this->undoafter == 0 ) {
			throw new ErrorPageError( 'mcrundofailed', 'mcrundo-missingparam' );
		}

		$curRev = $this->getWikiPage()->getRevisionRecord();
		if ( !$curRev ) {
			throw new ErrorPageError( 'mcrundofailed', 'nopagetext' );
		}
		$this->curRev = $curRev;
		$this->cur = $this->getRequest()->getInt( 'cur', $this->curRev->getId() );
	}

	/** @inheritDoc */
	protected function checkCanExecute( User $user ) {
		parent::checkCanExecute( $user );

		$this->initFromParameters();

		// We use getRevisionByTitle to verify the revisions belong to this page (T297322)
		$title = $this->getTitle();
		$undoRev = $this->revisionLookup->getRevisionByTitle( $title, $this->undo );
		$oldRev = $this->revisionLookup->getRevisionByTitle( $title, $this->undoafter );

		if ( $undoRev === null || $oldRev === null ||
			$undoRev->isDeleted( RevisionRecord::DELETED_TEXT ) ||
			$oldRev->isDeleted( RevisionRecord::DELETED_TEXT )
		) {
			throw new ErrorPageError( 'mcrundofailed', 'undo-norev' );
		}

		return true;
	}

	/**
	 * @return MutableRevisionRecord
	 */
	private function getNewRevision() {
		$undoRev = $this->revisionLookup->getRevisionById( $this->undo );
		$oldRev = $this->revisionLookup->getRevisionById( $this->undoafter );
		$curRev = $this->curRev;

		$isLatest = $curRev->getId() === $undoRev->getId();

		if ( $undoRev === null || $oldRev === null ||
			$undoRev->isDeleted( RevisionRecord::DELETED_TEXT ) ||
			$oldRev->isDeleted( RevisionRecord::DELETED_TEXT )
		) {
			throw new ErrorPageError( 'mcrundofailed', 'undo-norev' );
		}

		if ( $isLatest ) {
			// Short cut! Undoing the current revision means we just restore the old.
			return MutableRevisionRecord::newFromParentRevision( $oldRev );
		}

		$newRev = MutableRevisionRecord::newFromParentRevision( $curRev );

		// Figure out the roles that need merging by first collecting all roles
		// and then removing the ones that don't.
		$rolesToMerge = array_unique( array_merge(
			$oldRev->getSlotRoles(),
			$undoRev->getSlotRoles(),
			$curRev->getSlotRoles()
		) );

		// Any roles with the same content in $oldRev and $undoRev can be
		// inherited because undo won't change them.
		$rolesToMerge = array_intersect(
			$rolesToMerge, $oldRev->getSlots()->getRolesWithDifferentContent( $undoRev->getSlots() )
		);
		if ( !$rolesToMerge ) {
			throw new ErrorPageError( 'mcrundofailed', 'undo-nochange' );
		}

		// Any roles with the same content in $oldRev and $curRev were already reverted
		// and so can be inherited.
		$rolesToMerge = array_intersect(
			$rolesToMerge, $oldRev->getSlots()->getRolesWithDifferentContent( $curRev->getSlots() )
		);
		if ( !$rolesToMerge ) {
			throw new ErrorPageError( 'mcrundofailed', 'undo-nochange' );
		}

		// Any roles with the same content in $undoRev and $curRev weren't
		// changed since and so can be reverted to $oldRev.
		$diffRoles = array_intersect(
			$rolesToMerge, $undoRev->getSlots()->getRolesWithDifferentContent( $curRev->getSlots() )
		);
		foreach ( array_diff( $rolesToMerge, $diffRoles ) as $role ) {
			if ( $oldRev->hasSlot( $role ) ) {
				$newRev->inheritSlot( $oldRev->getSlot( $role, RevisionRecord::RAW ) );
			} else {
				$newRev->removeSlot( $role );
			}
		}
		$rolesToMerge = $diffRoles;

		// Any slot additions or removals not handled by the above checks can't be undone.
		// There will be only one of the three revisions missing the slot:
		//  - !old means it was added in the undone revisions and modified after.
		//    Should it be removed entirely for the undo, or should the modified version be kept?
		//  - !undo means it was removed in the undone revisions and then readded with different content.
		//    Which content is should be kept, the old or the new?
		//  - !cur means it was changed in the undone revisions and then deleted after.
		//    Did someone delete vandalized content instead of undoing (meaning we should ideally restore
		//    it), or should it stay gone?
		foreach ( $rolesToMerge as $role ) {
			if ( !$oldRev->hasSlot( $role ) || !$undoRev->hasSlot( $role ) || !$curRev->hasSlot( $role ) ) {
				throw new ErrorPageError( 'mcrundofailed', 'undo-failure' );
			}
		}

		// Try to merge anything that's left.
		foreach ( $rolesToMerge as $role ) {
			$oldContent = $oldRev->getSlot( $role, RevisionRecord::RAW )->getContent();
			$undoContent = $undoRev->getSlot( $role, RevisionRecord::RAW )->getContent();
			$curContent = $curRev->getSlot( $role, RevisionRecord::RAW )->getContent();
			$newContent = $undoContent->getContentHandler()
				->getUndoContent( $curContent, $undoContent, $oldContent, $isLatest );
			if ( !$newContent ) {
				throw new ErrorPageError( 'mcrundofailed', 'undo-failure' );
			}
			$newRev->setSlot( SlotRecord::newUnsaved( $role, $newContent ) );
		}

		return $newRev;
	}

	private function generateDiffOrPreview(): string {
		$newRev = $this->getNewRevision();
		if ( $newRev->hasSameContent( $this->curRev ) ) {
			throw new ErrorPageError( 'mcrundofailed', 'undo-nochange' );
		}

		$diffEngine = new DifferenceEngine( $this->context );
		$diffEngine->setRevisions( $this->curRev, $newRev );

		$oldtitle = $this->context->msg( 'currentrev' )->parse();
		$newtitle = $this->context->msg( 'yourtext' )->parse();

		if ( $this->getRequest()->getCheck( 'wpPreview' ) ) {
			$this->showPreview( $newRev );
			return '';
		} else {
			$diffText = $diffEngine->getDiff( $oldtitle, $newtitle );
			$diffEngine->showDiffStyle();
			return '<div id="wikiDiff">' . $diffText . '</div>';
		}
	}

	private function showPreview( RevisionRecord $rev ) {
		// Mostly copied from EditPage::getPreviewText()
		$out = $this->getOutput();

		try {
			# provide a anchor link to the form
			$continueEditing = '<span class="mw-continue-editing">' .
				'[[#mw-mcrundo-form|' .
				$this->context->getLanguage()->getArrow() . ' ' .
				$this->context->msg( 'continue-editing' )->text() . ']]</span>';

			$note = $this->context->msg( 'previewnote' )->plain() . ' ' . $continueEditing;

			$parserOptions = $this->getWikiPage()->makeParserOptions( $this->context );
			$parserOptions->setRenderReason( 'page-preview' );
			$parserOptions->setIsPreview( true );
			$parserOptions->setIsSectionPreview( false );

			$parserOutput = $this->revisionRenderer
				->getRenderedRevision( $rev, $parserOptions, $this->getAuthority() )
				->getRevisionParserOutput();
			// TODO T371004 move runOutputPipeline out of $parserOutput
			$previewHTML = $parserOutput->runOutputPipeline( $parserOptions, [
				'enableSectionEditLinks' => false,
				'includeDebugInfo' => true,
			] )->getContentHolderText();

			$out->addParserOutputMetadata( $parserOutput );
			foreach ( $parserOutput->getWarningMsgs() as $mv ) {
				$note .= "\n\n" . $this->context->msg( $mv )->text();
			}
		} catch ( MWContentSerializationException $ex ) {
			$m = $this->context->msg(
				'content-failed-to-parse',
				$ex->getMessage()
			);
			$note .= "\n\n" . $m->parse();
			$previewHTML = '';
		}

		$previewhead = Html::rawElement(
			'div', [ 'class' => 'previewnote' ],
			Html::element(
				'h2', [ 'id' => 'mw-previewheader' ],
				$this->context->msg( 'preview' )->text()
			) .
			Html::warningBox(
				$out->parseAsInterface( $note )
			)
		);

		$out->addHTML( $previewhead . $previewHTML );
	}

	/** @inheritDoc */
	public function onSubmit( $data ) {
		if ( !$this->getRequest()->getCheck( 'wpSave' ) ) {
			// Diff or preview
			return false;
		}

		$updater = $this->getWikiPage()->newPageUpdater( $this->context->getUser() );
		$curRev = $updater->grabParentRevision();
		if ( !$curRev ) {
			throw new ErrorPageError( 'mcrundofailed', 'nopagetext' );
		}

		if ( $this->cur !== $curRev->getId() ) {
			return Status::newFatal( 'mcrundo-changed' );
		}

		$status = new PermissionStatus();
		$this->getAuthority()->authorizeWrite( 'edit', $this->getTitle(), $status );
		if ( !$status->isOK() ) {
			throw new PermissionsError( 'edit', $status );
		}

		$newRev = $this->getNewRevision();
		if ( !$newRev->hasSameContent( $curRev ) ) {
			$hookRunner = $this->getHookRunner();
			foreach ( $newRev->getSlotRoles() as $slotRole ) {
				$slot = $newRev->getSlot( $slotRole, RevisionRecord::RAW );

				$status = new Status();
				$hookResult = $hookRunner->onEditFilterMergedContent(
					$this->getContext(),
					$slot->getContent(),
					$status,
					trim( $this->getRequest()->getVal( 'wpSummary' ) ?? '' ),
					$this->getUser(),
					false
				);

				if ( !$hookResult ) {
					if ( $status->isGood() ) {
						$status->error( 'hookaborted' );
					}

					return $status;
				} elseif ( !$status->isOK() ) {
					if ( !$status->getMessages() ) {
						$status->error( 'hookaborted' );
					}
					return $status;
				}
			}

			// Copy new slots into the PageUpdater, and remove any removed slots.
			// TODO: This interface is awful, there should be a way to just pass $newRev.
			// TODO: MCR: test this once we can store multiple slots
			foreach ( $newRev->getSlots()->getSlots() as $slot ) {
				$updater->setSlot( $slot );
			}
			foreach ( $curRev->getSlotRoles() as $role ) {
				if ( !$newRev->hasSlot( $role ) ) {
					$updater->removeSlot( $role );
				}
			}

			$updater->setCause( PageUpdateCauses::CAUSE_UNDO );
			$updater->markAsRevert( EditResult::REVERT_UNDO, $this->undo, $this->undoafter );

			if ( $this->useRCPatrol && $this->getAuthority()
					->authorizeWrite( 'autopatrol', $this->getTitle() )
			) {
				$updater->setRcPatrolStatus( RecentChange::PRC_AUTOPATROLLED );
			}

			$updater->saveRevision(
				CommentStoreComment::newUnsavedComment(
					trim( $this->getRequest()->getVal( 'wpSummary' ) ?? '' ) ),
				EDIT_AUTOSUMMARY | EDIT_UPDATE
			);

			return $updater->getStatus();
		}

		return Status::newGood();
	}

	/** @inheritDoc */
	protected function usesOOUI() {
		return true;
	}

	/** @inheritDoc */
	protected function getFormFields() {
		$request = $this->getRequest();
		$ret = [
			'diff' => [
				'type' => 'info',
				'raw' => true,
				'default' => function () {
					return $this->generateDiffOrPreview();
				}
			],
			'summary' => [
				'type' => 'text',
				'id' => 'wpSummary',
				'name' => 'wpSummary',
				'cssclass' => 'mw-summary',
				'label-message' => 'summary',
				'maxlength' => CommentStore::COMMENT_CHARACTER_LIMIT,
				'value' => $request->getVal( 'wpSummary', '' ),
				'size' => 60,
				'spellcheck' => 'true',
			],
			'summarypreview' => [
				'type' => 'info',
				'label-message' => 'summary-preview',
				'raw' => true,
			],
		];

		if ( $request->getCheck( 'wpSummary' ) ) {
			$ret['summarypreview']['default'] = Html::rawElement(
				'div',
				[ 'class' => 'mw-summary-preview' ],
				$this->commentFormatter->formatBlock(
					trim( $request->getVal( 'wpSummary' ) ),
					$this->getTitle(),
					false
				)
			);
		} else {
			unset( $ret['summarypreview'] );
		}

		return $ret;
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setWrapperLegendMsg( 'confirm-mcrundo-title' );

		$labelAsPublish = $this->context->getConfig()->get( MainConfigNames::EditSubmitButtonLabelPublish );

		$form->setId( 'mw-mcrundo-form' );
		$form->setSubmitName( 'wpSave' );
		$form->setSubmitTooltip( $labelAsPublish ? 'publish' : 'save' );
		$form->setSubmitTextMsg( $labelAsPublish ? 'publishchanges' : 'savechanges' );
		$form->showCancel( true );
		$form->setCancelTarget( $this->getTitle() );
		$form->addButton( [
			'name' => 'wpPreview',
			'value' => '1',
			'label-message' => 'showpreview',
			'attribs' => Linker::tooltipAndAccesskeyAttribs( 'preview' ),
		] );
		$form->addButton( [
			'name' => 'wpDiff',
			'value' => '1',
			'label-message' => 'showdiff',
			'attribs' => Linker::tooltipAndAccesskeyAttribs( 'diff' ),
		] );

		$this->addStatePropagationFields( $form );
	}

	protected function addStatePropagationFields( HTMLForm $form ) {
		$form->addHiddenField( 'undo', $this->undo );
		$form->addHiddenField( 'undoafter', $this->undoafter );
		$form->addHiddenField( 'cur', $this->curRev->getId() );
	}

	/** @inheritDoc */
	public function onSuccess() {
		$this->getOutput()->redirect( $this->getTitle()->getFullURL() );
	}

	/** @inheritDoc */
	protected function preText() {
		return '<div style="clear:both"></div>';
	}
}

/** @deprecated class alias since 1.44 */
class_alias( McrUndoAction::class, 'McrUndoAction' );
