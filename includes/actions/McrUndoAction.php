<?php
/**
 * Temporary action for MCR undos
 * @file
 * @ingroup Actions
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;

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
 * @deprecated since 1.32
 */
class McrUndoAction extends FormAction {

	protected $undo = 0, $undoafter = 0, $cur = 0;

	/** @var RevisionRecord|null */
	protected $curRev = null;

	public function getName() {
		return 'mcrundo';
	}

	public function getDescription() {
		return '';
	}

	public function show() {
		// Send a cookie so anons get talk message notifications
		// (copied from SubmitAction)
		MediaWiki\Session\SessionManager::getGlobalSession()->persist();

		// Some stuff copied from EditAction
		$this->useTransactionalTimeLimit();

		$out = $this->getOutput();
		$out->setRobotPolicy( 'noindex,nofollow' );
		if ( $this->getContext()->getConfig()->get( 'UseMediaWikiUIEverywhere' ) ) {
			$out->addModuleStyles( [
				'mediawiki.ui.input',
				'mediawiki.ui.checkbox',
			] );
		}

		// IP warning headers copied from EditPage
		// (should more be copied?)
		if ( wfReadOnly() ) {
			$out->wrapWikiMsg(
				"<div id=\"mw-read-only-warning\">\n$1\n</div>",
				[ 'readonlywarning', wfReadOnlyReason() ]
			);
		} elseif ( $this->context->getUser()->isAnon() ) {
			if ( !$this->getRequest()->getCheck( 'wpPreview' ) ) {
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
		}

		parent::show();
	}

	protected function initFromParameters() {
		$this->undoafter = $this->getRequest()->getInt( 'undoafter' );
		$this->undo = $this->getRequest()->getInt( 'undo' );

		if ( $this->undo == 0 || $this->undoafter == 0 ) {
			throw new ErrorPageError( 'mcrundofailed', 'mcrundo-missingparam' );
		}

		$curRev = $this->page->getRevision();
		if ( !$curRev ) {
			throw new ErrorPageError( 'mcrundofailed', 'nopagetext' );
		}
		$this->curRev = $curRev->getRevisionRecord();
		$this->cur = $this->getRequest()->getInt( 'cur', $this->curRev->getId() );
	}

	protected function checkCanExecute( User $user ) {
		parent::checkCanExecute( $user );

		$this->initFromParameters();

		$revisionLookup = MediaWikiServices::getInstance()->getRevisionLookup();

		$undoRev = $revisionLookup->getRevisionById( $this->undo );
		$oldRev = $revisionLookup->getRevisionById( $this->undoafter );

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
		$revisionLookup = MediaWikiServices::getInstance()->getRevisionLookup();

		$undoRev = $revisionLookup->getRevisionById( $this->undo );
		$oldRev = $revisionLookup->getRevisionById( $this->undoafter );
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

	private function generateDiffOrPreview() {
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
			$previewHTML = '';

			# provide a anchor link to the form
			$continueEditing = '<span class="mw-continue-editing">' .
				'[[#mw-mcrundo-form|' .
				$this->context->getLanguage()->getArrow() . ' ' .
				$this->context->msg( 'continue-editing' )->text() . ']]</span>';

			$note = $this->context->msg( 'previewnote' )->plain() . ' ' . $continueEditing;

			$parserOptions = $this->page->makeParserOptions( $this->context );
			$parserOptions->setIsPreview( true );
			$parserOptions->setIsSectionPreview( false );
			$parserOptions->enableLimitReport();

			$parserOutput = MediaWikiServices::getInstance()->getRevisionRenderer()
				->getRenderedRevision( $rev, $parserOptions, $this->context->getUser() )
				->getRevisionParserOutput();
			$previewHTML = $parserOutput->getText( [ 'enableSectionEditLinks' => false ] );

			$out->addParserOutputMetadata( $parserOutput );
			if ( count( $parserOutput->getWarnings() ) ) {
				$note .= "\n\n" . implode( "\n\n", $parserOutput->getWarnings() );
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
			Html::rawElement( 'div', [ 'class' => 'warningbox' ],
				$out->parseAsInterface( $note )
			)
		);

		$pageViewLang = $this->getTitle()->getPageViewLanguage();
		$attribs = [ 'lang' => $pageViewLang->getHtmlCode(), 'dir' => $pageViewLang->getDir(),
			'class' => 'mw-content-' . $pageViewLang->getDir() ];
		$previewHTML = Html::rawElement( 'div', $attribs, $previewHTML );

		$out->addHTML( $previewhead . $previewHTML );
	}

	public function onSubmit( $data ) {
		global $wgUseRCPatrol;

		if ( !$this->getRequest()->getCheck( 'wpSave' ) ) {
			// Diff or preview
			return false;
		}

		$updater = $this->page->getPage()->newPageUpdater( $this->context->getUser() );
		$curRev = $updater->grabParentRevision();
		if ( !$curRev ) {
			throw new ErrorPageError( 'mcrundofailed', 'nopagetext' );
		}

		if ( $this->cur !== $curRev->getId() ) {
			return Status::newFatal( 'mcrundo-changed' );
		}

		$newRev = $this->getNewRevision();
		if ( !$newRev->hasSameContent( $curRev ) ) {
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

			$updater->setOriginalRevisionId( false );
			$updater->setUndidRevisionId( $this->undo );

			$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

			// TODO: Ugh.
			if ( $wgUseRCPatrol && $permissionManager->userCan(
				'autopatrol',
				$this->getUser(),
				$this->getTitle() )
			) {
				$updater->setRcPatrolStatus( RecentChange::PRC_AUTOPATROLLED );
			}

			$updater->saveRevision(
				CommentStoreComment::newUnsavedComment( trim( $this->getRequest()->getVal( 'wpSummary' ) ) ),
				EDIT_AUTOSUMMARY | EDIT_UPDATE
			);

			return $updater->getStatus();
		}

		return Status::newGood();
	}

	protected function usesOOUI() {
		return true;
	}

	protected function getFormFields() {
		$request = $this->getRequest();
		$ret = [
			'diff' => [
				'type' => 'info',
				'vertical-label' => true,
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
			$ret['summarypreview']['default'] = Xml::tags( 'div', [ 'class' => 'mw-summary-preview' ],
				Linker::commentBlock( trim( $request->getVal( 'wpSummary' ) ), $this->getTitle(), false )
			);
		} else {
			unset( $ret['summarypreview'] );
		}

		return $ret;
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setWrapperLegendMsg( 'confirm-mcrundo-title' );

		$labelAsPublish = $this->context->getConfig()->get( 'EditSubmitButtonLabelPublish' );

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

	public function onSuccess() {
		$this->getOutput()->redirect( $this->getTitle()->getFullURL() );
	}

	protected function preText() {
		return '<div style="clear:both"></div>';
	}
}
