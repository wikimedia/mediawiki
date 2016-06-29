<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 * @ingroup Actions
 */

/**
 * @ingroup Actions
 */
class EditsummaryAction extends FormAction {
	/** @var Revision */
	protected $rev;

	/** @var int */
	protected $revId;

	/** @var RevisionList */
	protected $revsList;

	public function getName() {
		return 'editsummary';
	}

	public function getRestriction() {
		return 'editsummary';
	}

	public function getDescription() {
		return Linker::linkKnown(
			SpecialPage::getTitleFor( 'Log', 'editsummary' ),
			$this->msg( 'viewpagelogs' )->escaped()
		);
	}

	public function show() {
		$this->checkCanExecute( $this->getUser() );

		$revId = $this->getRequest()->getInt( 'id', -1 );
		$rev = Revision::newFromId( $revId );
		if ( $rev === null ) {
			throw new ErrorPageError( 'Actionfailed', 'editsummary-error-invalidtarget' );
		}

		$revsList = new RevisionList( $this->getContext(), $rev->getTitle() );

		if ( $rev->getUserText( Revision::RAW ) !== $this->getUser()->getName() ||
			// FIXME: Revision::getId() should return integer but it isn't.
			intval( $revsList->reset()->getId() ) !== $revId ||
			$rev->isDeleted( Revision::DELETED_COMMENT )
		) {
			throw new ErrorPageError( 'Actionfailed', 'editsummary-error-revision' );
		}

		$this->rev = $rev;
		$this->revId = $revId;
		$this->revsList = $revsList;

		parent::show();
	}

	public function getFormFields() {
		return [
			'summary' => [
				'type' => 'text',
				'label-message' => 'summary',
				'default' => $this->rev->getComment(),
				'maxlength' => 255,
			],
			'id' => [
				'type' => 'hidden',
				'default' => $this->revId,
			]
		];
	}

	public function alterForm( $form ) {
		$title = $this->rev->getTitle();
		$diff = $this->msg( 'editsummary-diff-text' )
			->rawParams( Linker::linkKnown( $title, $title->getPrefixedText() ) )
			->parseAsBlock() .
			'<ul>' . $this->revsList->current()->getHTML() . '</ul>';

		$form->addPreText( $diff );
		$form->setAction( $this->getTitle()->getLocalURL( [
			'action' => $this->getName(),
			'id' => $this->revId,
		] ) );
	}

	public function onSubmit( $data ) {
		$newSummary = $data['summary'];
		$oldSummary = $this->rev->getComment();

		if ( $newSummary === $oldSummary ) {
			return Status::newFatal( 'editsummary-error-same' );
		}

		$dbr = wfGetDB( DB_MASTER );
		$dbr->update(
			'revision', [ 'rev_comment' => $newSummary ],
			[ 'rev_id' => $this->revId ], __METHOD__ );

		$logEntry = new ManualLogEntry( 'editsummary', 'edit' );
		$logEntry->setTarget( $this->rev->getTitle() );
		$logEntry->setPerformer( $this->getUser() );
		$logEntry->setParameters( [
			'4::oldSummary' => $oldSummary,
			'5::newSummary' => $newSummary,
			'6::revisionId' => $this->revId,
		] );
		$logid = $logEntry->insert();
		$logEntry->publish( $logid );

		return true;
	}

	public function onSuccess() {
		$this->getOutput()->addWikiMsg( 'editsummary-success' );
		$this->getOutput()->returnToMain( false, $this->getTitle() );
	}

	public function doesWrites() {
		return true;
	}
}
