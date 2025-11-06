<?php
/**
 * User-requested page cache purging.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Actions
 */

namespace MediaWiki\Actions;

use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Status\Status;

/**
 * User-requested page cache purging
 *
 * @ingroup Actions
 */
class PurgeAction extends FormAction {

	/** @var string */
	private $redirectParams;

	/** @inheritDoc */
	public function getName() {
		return 'purge';
	}

	/** @inheritDoc */
	public function getDescription() {
		return '';
	}

	/** @inheritDoc */
	public function onSubmit( $data ) {
		$authority = $this->getAuthority();
		$page = $this->getWikiPage();

		$status = PermissionStatus::newEmpty();
		if ( !$authority->authorizeAction( 'purge', $status ) ) {
			return Status::wrap( $status );
		}

		return $page->doPurge();
	}

	/** @inheritDoc */
	public function show() {
		$this->setHeaders();

		// This will throw exceptions if there's a problem
		$this->checkCanExecute( $this->getUser() );

		if ( $this->getRequest()->wasPosted() ) {
			$this->redirectParams = wfArrayToCgi( array_diff_key(
				$this->getRequest()->getQueryValues(),
				[ 'title' => null, 'action' => null ]
			) );

			$result = $this->onSubmit( [] );
			if ( $result === true ) {
				$this->onSuccess();
			} elseif ( $result instanceof Status ) {
				if ( $result->isOK() ) {
					$this->onSuccess();
				} else {
					$this->getOutput()->addHTML( $result->getHTML() );
				}
			}
		} else {
			$this->redirectParams = $this->getRequest()->getVal( 'redirectparams', '' );
			$form = $this->getForm();
			if ( $form->show() ) {
				$this->onSuccess();
			}
		}
	}

	/** @inheritDoc */
	protected function usesOOUI() {
		return true;
	}

	/** @inheritDoc */
	protected function getFormFields() {
		return [
			'intro' => [
				'type' => 'info',
				'raw' => true,
				'default' => $this->msg( 'confirm-purge-top' )->parse()
			]
		];
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setWrapperLegendMsg( 'confirm-purge-title' );
		$form->setSubmitTextMsg( 'confirm_purge_button' );
	}

	/** @inheritDoc */
	protected function postText() {
		return $this->msg( 'confirm-purge-bottom' )->parse();
	}

	public function onSuccess() {
		$this->getOutput()->redirect( $this->getTitle()->getFullURL( $this->redirectParams ) );
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( PurgeAction::class, 'PurgeAction' );
