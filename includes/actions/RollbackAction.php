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
		$user = $this->getUser();

		$result = $this->page->doRollback(
			$request->getVal( 'from' ),
			$request->getText( 'summary' ),
			$request->getVal( 'token' ),
			$request->getBool( 'bot' ),
			$details,
			$user
		);

		if ( in_array( array( 'actionthrottledtext' ), $result ) ) {
			throw new ThrottledError;
		}

		if ( isset( $result[0][0] ) && ( $result[0][0] == 'alreadyrolled' || $result[0][0] == 'cantrollback' ) ) {
			$this->getOutput()->setPageTitle( $this->msg( 'rollbackfailed' ) );
			$errArray = $result[0];
			$errMsg = array_shift( $errArray );
			$this->getOutput()->addWikiMsgArray( $errMsg, $errArray );

			if ( isset( $details['current'] ) ) {
				$current = $details['current'];

				if ( $current->getComment() != '' ) {
					$this->getOutput()->addHTML( $this->msg( 'editcomment' )->rawParams(
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
			throw new PermissionsError( 'rollback', $out );
		}

		if ( $result == array( array( 'readonlytext' ) ) ) {
			throw new ReadOnlyError;
		}

		$current = $details['current'];
		$target = $details['target'];
		$newId = $details['newid'];
		$this->getOutput()->setPageTitle( $this->msg( 'actioncomplete' ) );
		$this->getOutput()->setRobotPolicy( 'noindex,nofollow' );

		$old = Linker::revUserTools( $current, false, $user );
		$new = Linker::revUserTools( $target, false, $user );
		$this->getOutput()->addHTML( $this->msg( 'rollback-success' )->rawParams( $old, $new )->parseAsBlock() );
		$this->getOutput()->returnToMain( false, $this->getTitle() );

		if ( !$request->getBool( 'hidediff', false ) && !$user->getBoolOption( 'norollbackdiff', false ) ) {
			$de = new DifferenceEngine( $this->getContext(), $current->getId(), $newId, false, true );
			$de->showDiff( '', '' );
		}
	}

	protected function getDescription() {
		return '';
	}
}
