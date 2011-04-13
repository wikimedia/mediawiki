<?php
/**
 * Performs the watch and unwatch actions on a page
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

class WatchAction extends FormlessAction {

	public function getName(){
		return 'watch';
	}

	public function getRestriction(){
		return 'read';
	}

	protected function getDescription(){
		return wfMsg( 'addedwatch' );
	}

	protected function checkCanExecute( User $user ){
		if ( $user->isAnon() ) {
			throw new ErrorPageError( 'watchnologin', 'watchnologintext' );
		}
		return parent::checkCanExecute( $user );
	}

	public function onView() {
		wfProfileIn( __METHOD__ );

		$user = $this->getUser();
		if ( wfRunHooks( 'WatchArticle', array( &$user, &$this->page ) ) ) {
			$this->getUser()->addWatch( $this->getTitle() );
			wfRunHooks( 'WatchArticleComplete', array( &$user, &$this->page ) );
		}

		wfProfileOut( __METHOD__ );

		return wfMessage( 'addedwatchtext', $this->getTitle()->getPrefixedText() )->parse();
	}
}

class UnwatchAction extends WatchAction {

	public function getName(){
		return 'unwatch';
	}

	protected function getDescription(){
		return wfMsg( 'removedwatch' );
	}

	public function onView() {
		wfProfileIn( __METHOD__ );

		$user = $this->getUser();
		if ( wfRunHooks( 'UnwatchArticle', array( &$user, &$this->page ) ) ) {
			$this->getUser()->removeWatch( $this->getTitle() );
			wfRunHooks( 'UnwatchArticleComplete', array( &$user, &$this->page ) );
		}

		wfProfileOut( __METHOD__ );

		return wfMessage( 'removedwatchtext', $this->getTitle()->getPrefixedText() )->parse();
	}
}
