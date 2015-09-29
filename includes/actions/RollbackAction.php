<?php
/**
 * Edit rollback user interface
 *
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
 * User interface for the rollback action
 *
 * @ingroup Actions
 */
class RollbackAction extends FormAction {

	public function getName() {
		return 'rollback';
	}

	public function getRestriction() {
		return 'rollback';
	}

	protected function getDescription() {
		return '';
	}

	protected function preText() {
		return $this->msg( 'confirm-rollback-top' )->parse();
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setSubmitTextMsg( 'confirm-rollback-button' );
		$form->setTokenSalt( 'rollback' );

		// Copy parameters from GET to confirmation form
		foreach ( array( 'from', 'bot', 'hidediff', 'summary' ) as $param ) {
			if ( $this->getRequest()->getVal( $param ) ) {
				$form->addHiddenField( $param, $this->getRequest()->getVal( $param ) );
			}
		}
	}

	public function onSubmit( $data ) {
		$this->useTransactionalTimeLimit();

		$request = $this->getRequest();

		$user = $request->getVal( 'from' );
		$rev = Revision::newFromTitle( $this->getTitle() );
		if ( $user === null || $user === '' ) {
			throw new ErrorPageError( 'rollbackfailed', 'sessionfailure' );
		}
		if ( $user !== $rev->getUserText() ) {
			throw new ErrorPageError( 'rollbackfailed', 'alreadyrolled', array(
				$this->getTitle()->getPrefixedText(),
				$user,
				$rev->getUserText()
			) );
		}

		$details = null;
		$user = $this->getUser();

		$result = $this->page->doRollback(
			$user,
			$request->getText( 'summary' ),
			$request->getVal( 'token' ),
			$request->getBool( 'bot' ),
			$details,
			$this->getUser()
		);

		if ( in_array( array( 'actionthrottledtext' ), $result ) ) {
			throw new ThrottledError;
		}

		if ( isset( $result[0][0] ) &&
			( $result[0][0] == 'alreadyrolled' || $result[0][0] == 'cantrollback' )
		) {
			$this->getOutput()->setPageTitle( $this->msg( 'rollbackfailed' ) );
			$errArray = $result[0];
			$errMsg = array_shift( $errArray );
			$this->getOutput()->addWikiMsgArray( $errMsg, $errArray );

			if ( isset( $details['current'] ) ) {
				/** @var Revision $current */
				$current = $details['current'];

				if ( $current->getComment() != '' ) {
					$this->getOutput()->addHTML( $this->msg( 'editcomment' )->rawParams(
						Linker::formatComment( $current->getComment() ) )->parse() );
				}
			}

			return false;
		}

		# NOTE: Permission errors already handled by Action::checkExecute.

		if ( $result == array( array( 'readonlytext' ) ) ) {
			throw new ReadOnlyError;
		}

		# XXX: Would be nice if ErrorPageError could take multiple errors, and/or a status object.
		#     Right now, we only show the first error
		foreach ( $result as $error ) {
			throw new ErrorPageError( 'rollbackfailed', $error[0], array_slice( $error, 1 ) );
		}

		/** @var Revision $current */
		$current = $details['current'];
		$target = $details['target'];
		$newId = $details['newid'];
		$this->getOutput()->setPageTitle( $this->msg( 'actioncomplete' ) );
		$this->getOutput()->setRobotPolicy( 'noindex,nofollow' );

		$old = Linker::revUserTools( $current );
		$new = Linker::revUserTools( $target );
		$this->getOutput()->addHTML( $this->msg( 'rollback-success' )->rawParams( $old, $new )
			->parseAsBlock() );

		if ( $user->getBoolOption( 'watchrollback' ) ) {
			$user->addWatch( $this->page->getTitle(), WatchedItem::IGNORE_USER_RIGHTS );
		}

		$this->getOutput()->returnToMain( false, $this->getTitle() );

		if ( !$request->getBool( 'hidediff', false ) &&
			!$this->getUser()->getBoolOption( 'norollbackdiff' )
		) {
			$contentHandler = $current->getContentHandler();
			$de = $contentHandler->createDifferenceEngine(
				$this->getContext(),
				$current->getId(),
				$newId,
				false,
				true
			);
			$de->showDiff( '', '' );
		}
	}

	public function onSuccess() {
	}
}
