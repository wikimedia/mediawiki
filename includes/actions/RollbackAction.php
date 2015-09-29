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

	protected function preText() {
		return $this->msg( 'confirm-rollback-top' )->parse();
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setSubmitTextMsg( 'confirm-rollback-button' );
		$form->setTokenSalt( 'rollback' );

		// Copy parameters from GET to confirmation form
		foreach ( [ 'from', 'bot', 'hidediff', 'summary' ] as $param ) {
			if ( $this->getRequest()->getVal( $param ) ) {
				$form->addHiddenField( $param, $this->getRequest()->getVal( $param ) );
			}
		}
	}

	/**
	 * This must return true so that HTMLForm::show() will not display the form again after
	 * submission. For rollback, display either the form or the result (success/error)
	 * not both.
	 *
	 * @return bool
	 * @throws ErrorPageError
	 */
	public function onSubmit( $data ) {
		$this->useTransactionalTimeLimit();

		$request = $this->getRequest();
		$user = $this->getUser();
		$from = $request->getVal( 'from' );
		$rev = Revision::newFromTitle( $this->getTitle() );
		if ( $from === null || $from === '' ) {
			throw new ErrorPageError( 'rollbackfailed', 'sessionfailure' );
		}
		if ( $from !== $rev->getUserText() ) {
			throw new ErrorPageError( 'rollbackfailed', 'alreadyrolled', [
				$this->getTitle()->getPrefixedText(),
				$from,
				$rev->getUserText()
			] );
		}

		$data = null;
		$errors = $this->page->doRollback(
			$from,
			$request->getText( 'summary' ),
			// Provided by HTMLForm
			$request->getVal( 'wpEditToken' ),
			$request->getBool( 'bot' ),
			$data,
			$this->getUser()
		);

		if ( in_array( [ 'actionthrottledtext' ], $errors ) ) {
			throw new ThrottledError;
		}

		if ( isset( $errors[0][0] ) &&
			( $errors[0][0] == 'alreadyrolled' || $errors[0][0] == 'cantrollback' )
		) {
			$this->getOutput()->setPageTitle( $this->msg( 'rollbackfailed' ) );
			$errArray = $errors[0];
			$errMsg = array_shift( $errArray );
			$this->getOutput()->addWikiMsgArray( $errMsg, $errArray );

			if ( isset( $data['current'] ) ) {
				/** @var Revision $current */
				$current = $data['current'];

				if ( $current->getComment() != '' ) {
					$this->getOutput()->addHTML( $this->msg( 'editcomment' )->rawParams(
						Linker::formatComment( $current->getComment() ) )->parse() );
				}
			}

			return true;
		}

		# NOTE: Permission errors already handled by Action::checkExecute.
		if ( $errors == [ [ 'readonlytext' ] ] ) {
			throw new ReadOnlyError;
		}

		# XXX: Would be nice if ErrorPageError could take multiple errors, and/or a status object.
		#      Right now, we only show the first error
		foreach ( $errors as $error ) {
			throw new ErrorPageError( 'rollbackfailed', $error[0], array_slice( $error, 1 ) );
		}

		/** @var Revision $current */
		$current = $data['current'];
		$target = $data['target'];
		$newId = $data['newid'];
		$this->getOutput()->setPageTitle( $this->msg( 'actioncomplete' ) );
		$this->getOutput()->setRobotPolicy( 'noindex,nofollow' );

		$old = Linker::revUserTools( $current );
		$new = Linker::revUserTools( $target );
		$this->getOutput()->addHTML( $this->msg( 'rollback-success' )->rawParams( $old, $new )
			->parseAsBlock() );

		if ( $user->getBoolOption( 'watchrollback' ) ) {
			$user->addWatch( $this->page->getTitle(), User::IGNORE_USER_RIGHTS );
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
		return true;
	}

	public function onSuccess() {
		// Required by parent class, but redundant because onSubmit already shows
		// the success message when needed.
	}

	protected function getDescription() {
		return '';
	}

	public function doesWrites() {
		return true;
	}
}
