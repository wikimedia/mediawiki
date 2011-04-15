<?php
/**
 * Formats credits for articles
 *
 * Copyright 2004, Evan Prodromou <evan@wikitravel.org>.
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
 * @author <evan@wikitravel.org>
 */

class PurgeAction extends FormAction {

	public function getName() {
		return 'purge';
	}

	public function getRestriction() {
		return null;
	}

	public function requiresUnblock() {
		return false;
	}

	public function getDescription() {
		return '';
	}

	/**
	 * Just get an empty form with a single submit button
	 * @return array
	 */
	protected function getFormFields() {
		return array();
	}

	public function onSubmit( $data ) {
		$this->page->doPurge();
		return true;
	}

	/**
	 * purge is slightly wierd because it can be either formed or formless depending
	 * on user permissions
	 */
	public function show() {
		$this->setHeaders();

		// This will throw exceptions if there's a problem
		$this->checkCanExecute( $this->getUser() );

		if ( $this->getUser()->isAllowed( 'purge' ) ) {
			$this->onSubmit( array() );
			$this->onSuccess();
		} else {
			$form = $this->getForm();
			if ( $form->show() ) {
				$this->onSuccess();
			}
		}
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setSubmitText( wfMsg( 'confirm_purge_button' ) );
	}

	protected function preText() {
		return wfMessage( 'confirm-purge-top' )->parse();
	}

	protected function postText() {
		return wfMessage( 'confirm-purge-bottom' )->parse();
	}

	public function onSuccess() {
		$this->getOutput()->redirect( $this->getTitle()->getFullUrl( $this->getRequest()->getVal( 'redirectparams', '' ) ) );
	}
}
