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
 * @ingroup Action
 */

/**
 * User interface for the rollback action
 *
 * @ingroup Action
 */
class RollbackAction extends FormlessAction {

	public function getName() {
		return 'rollback';
	}

	public function getRestriction() {
		return 'rollback';
	}

	public function onView() {
		$details = null;

		$request = $this->getRequest();

		$result = $this->page->doRollback(
			$request->getVal( 'from' ),
			$request->getText( 'summary' ),
			$request->getVal( 'token' ),
			$request->getBool( 'bot' ),
			$details,
			$this->getUser()
		);

		if ( in_array( array( 'actionthrottledtext' ), $result ) ) {
			throw new ThrottledError;
		}

		if ( isset( $result[0][0] ) && ( $result[0][0] == 'alreadyrolled' || $result[0][0] == 'cantrollback' ) ) {
			$this->getOutput()->setPageTitle( wfMsg( 'rollbackfailed' ) );
			$errArray = $result[0];
			$errMsg = array_shift( $errArray );
			$this->getOutput()->addWikiMsgArray( $errMsg, $errArray );

			if ( isset( $details['current'] ) ) {
				$current = $details['current'];

				if ( $current->getComment() != '' ) {
					$this->getOutput()->addHTML( wfMessage( 'editcomment' )->rawParams(
						Linker::formatComment( $current->getComment() ) )->parse() );
				}
			}

			return;
		}

		# Display permissions errors before read-only message -- there's no
		# point in misleading the user into thinking the inability to rollback
		# is only temporary.
		if ( !empty( $result ) && $result !== array( array( 'readonlytext' ) ) ) {
			# array_diff is completely broken for arrays of arrays, sigh.
			# Remove any 'readonlytext' error manually.
			$out = array();
			foreach ( $result as $error ) {
				if ( $error != array( 'readonlytext' ) ) {
					$out [] = $error;
				}
			}
			$this->getOutput()->showPermissionsErrorPage( $out );

			return;
		}

		if ( $result == array( array( 'readonlytext' ) ) ) {
			throw new ReadOnlyError;
		}

		$current = $details['current'];
		$target = $details['target'];
		$newId = $details['newid'];
		$this->getOutput()->setPageTitle( wfMsg( 'actioncomplete' ) );
		$this->getOutput()->setRobotPolicy( 'noindex,nofollow' );

		if ( $current->getUserText() === '' ) {
			$old = wfMsg( 'rev-deleted-user' );
		} else {
			$old = Linker::userLink( $current->getUser(), $current->getUserText() )
				. Linker::userToolLinks( $current->getUser(), $current->getUserText() );
		}

		$new = Linker::userLink( $target->getUser(), $target->getUserText() )
			. Linker::userToolLinks( $target->getUser(), $target->getUserText() );
		$this->getOutput()->addHTML( wfMsgExt( 'rollback-success', array( 'parse', 'replaceafter' ), $old, $new ) );
		$this->getOutput()->returnToMain( false, $this->getTitle() );

		if ( !$request->getBool( 'hidediff', false ) && !$this->getUser()->getBoolOption( 'norollbackdiff', false ) ) {
			$de = new DifferenceEngine( $this->getTitle(), $current->getId(), $newId, false, true );
			$de->showDiff( '', '' );
		}
	}

	protected function getDescription() {
		return '';
	}
}
