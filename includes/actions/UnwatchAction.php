<?php
/**
 * Performs the unwatch actions on a page
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
 * Page removal from a user's watchlist
 *
 * @ingroup Actions
 */
class UnwatchAction extends WatchAction {

	public function getName() {
		return 'unwatch';
	}

	protected function getDescription() {
		return $this->msg( 'removewatch' )->escaped();
	}

	public function onSubmit( $data ) {
		self::doUnwatch( $this->getTitle(), $this->getUser() );

		return true;
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setSubmitTextMsg( 'confirm-unwatch-button' );
	}

	protected function preText() {
		return $this->msg( 'confirm-unwatch-top' )->parse();
	}

	public function onSuccess() {
		$this->getOutput()->addWikiMsg( 'removedwatchtext', $this->getTitle()->getPrefixedText() );
	}

	public function doesWrites() {
		return true;
	}
}
