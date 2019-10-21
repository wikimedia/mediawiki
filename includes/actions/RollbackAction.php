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

use MediaWiki\Revision\RevisionRecord;

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

	protected function usesOOUI() {
		return true;
	}

	protected function getDescription() {
		return '';
	}

	public function doesWrites() {
		return true;
	}

	public function onSuccess() {
		return false;
	}

	public function onSubmit( $data ) {
		return false;
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setWrapperLegendMsg( 'confirm-rollback-top' );
		$form->setSubmitTextMsg( 'confirm-rollback-button' );
		$form->setTokenSalt( 'rollback' );

		$from = $this->getRequest()->getVal( 'from' );
		if ( $from === null ) {
			throw new BadRequestError( 'rollbackfailed', 'rollback-missingparam' );
		}
		foreach ( [ 'from', 'bot', 'hidediff', 'summary', 'token' ] as $param ) {
			$val = $this->getRequest()->getVal( $param );
			if ( $val !== null ) {
				$form->addHiddenField( $param, $val );
			}
		}
	}

	/**
	 * @throws ErrorPageError
	 * @throws ReadOnlyError
	 * @throws ThrottledError
	 */
	public function show() {
		if ( $this->getUser()->getOption( 'showrollbackconfirmation' ) == false ||
			 $this->getRequest()->wasPosted() ) {
			$this->handleRollbackRequest();
		} else {
			$this->showRollbackConfirmationForm();
		}
	}

	public function handleRollbackRequest() {
		$this->enableTransactionalTimelimit();

		$request = $this->getRequest();
		$user = $this->getUser();
		$from = $request->getVal( 'from' );
		$rev = $this->page->getRevision();
		if ( $from === null ) {
			throw new ErrorPageError( 'rollbackfailed', 'rollback-missingparam' );
		}
		if ( !$rev ) {
			throw new ErrorPageError( 'rollbackfailed', 'rollback-missingrevision' );
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
			$request->getVal( 'token' ),
			$request->getBool( 'bot' ),
			$data,
			$this->getUser()
		);

		if ( in_array( [ 'actionthrottledtext' ], $errors ) ) {
			throw new ThrottledError;
		}

		if ( $this->hasRollbackRelatedErrors( $errors ) ) {
			$this->getOutput()->setPageTitle( $this->msg( 'rollbackfailed' ) );
			$errArray = $errors[0];
			$errMsg = array_shift( $errArray );
			$this->getOutput()->addWikiMsgArray( $errMsg, $errArray );

			if ( isset( $data['current'] ) ) {
				/** @var Revision $current */
				$current = $data['current'];

				if ( $current->getComment() != '' ) {
					$this->getOutput()->addWikiMsg(
						'editcomment',
						Message::rawParam(
							Linker::formatComment( $current->getComment() )
						)
					);
				}
			}

			return;
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
		$this->getOutput()->addHTML(
			$this->msg( 'rollback-success' )
				->rawParams( $old, $new )
				->params( $current->getUserText( RevisionRecord::FOR_THIS_USER, $user ) )
				->params( $target->getUserText( RevisionRecord::FOR_THIS_USER, $user ) )
				->parseAsBlock()
		);

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
	}

	/**
	 * Enables transactional time limit for POST and GET requests to RollbackAction
	 * @throws ConfigException
	 */
	private function enableTransactionalTimelimit() {
		// If Rollbacks are made POST-only, use $this->useTransactionalTimeLimit()
		wfTransactionalTimeLimit();
		if ( !$this->getRequest()->wasPosted() ) {
			/**
			 * We apply the higher POST limits on GET requests
			 * to prevent logstash.wikimedia.org from being spammed
			 */
			$fname = __METHOD__;
			$trxLimits = $this->context->getConfig()->get( 'TrxProfilerLimits' );
			$trxProfiler = Profiler::instance()->getTransactionProfiler();
			$trxProfiler->redefineExpectations( $trxLimits['POST'], $fname );
			DeferredUpdates::addCallableUpdate( function () use ( $trxProfiler, $trxLimits, $fname
			) {
				$trxProfiler->redefineExpectations( $trxLimits['PostSend-POST'], $fname );
			} );
		}
	}

	private function showRollbackConfirmationForm() {
		$form = $this->getForm();
		if ( $form->show() ) {
			$this->onSuccess();
		}
	}

	protected function getFormFields() {
		return [
			'intro' => [
				'type' => 'info',
				'vertical-label' => true,
				'raw' => true,
				'default' => $this->msg( 'confirm-rollback-bottom' )->parse()
			]
		];
	}

	private function hasRollbackRelatedErrors( array $errors ) {
		return isset( $errors[0][0] ) &&
			( $errors[0][0] == 'alreadyrolled' ||
				$errors[0][0] == 'cantrollback'
			);
	}
}
