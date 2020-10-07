<?php
/**
 * User-requested page cache purging.
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
 * User-requested page cache purging
 *
 * @ingroup Actions
 */
class PurgeAction extends FormAction {

	private $redirectParams;

	public function getName() {
		return 'purge';
	}

	public function requiresUnblock() {
		return false;
	}

	public function getDescription() {
		return '';
	}

	public function onSubmit( $data ) {
		return $this->getWikiPage()->doPurge();
	}

	public function show() {
		$this->setHeaders();

		// This will throw exceptions if there's a problem
		$this->checkCanExecute( $this->getUser() );

		$user = $this->getUser();

		if ( $user->pingLimiter( 'purge' ) ) {
			// TODO: Display actionthrottledtext
			return;
		}

		if ( $this->getRequest()->wasPosted() ) {
			$this->redirectParams = wfArrayToCgi( array_diff_key(
				$this->getRequest()->getQueryValues(),
				[ 'title' => null, 'action' => null ]
			) );
			if ( $this->onSubmit( [] ) ) {
				$this->onSuccess();
			}
		} else {
			$this->redirectParams = $this->getRequest()->getVal( 'redirectparams', '' );
			$form = $this->getForm();
			if ( $form->show() ) {
				$this->onSuccess();
			}
		}
	}

	protected function usesOOUI() {
		return true;
	}

	protected function getFormFields() {
		return [
			'intro' => [
				'type' => 'info',
				'vertical-label' => true,
				'raw' => true,
				'default' => $this->msg( 'confirm-purge-top' )->parse()
			]
		];
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setWrapperLegendMsg( 'confirm-purge-title' );
		$form->setSubmitTextMsg( 'confirm_purge_button' );
	}

	protected function postText() {
		return $this->msg( 'confirm-purge-bottom' )->parse();
	}

	public function onSuccess() {
		$this->getOutput()->redirect( $this->getTitle()->getFullURL( $this->redirectParams ) );
	}

	public function doesWrites() {
		return true;
	}
}
