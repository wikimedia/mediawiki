<?php
/**
 * Mark a revision as patrolled on a page
 *
 * Copyright © 2011 Alexandre Emsenhuber
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
 * Mark a revision as patrolled on a page
 *
 * @ingroup Actions
 */
class MarkpatrolledAction extends FormAction {

	public function getName() {
		return 'markpatrolled';
	}

	protected function getDescription() {
		return '';
	}

	public function onSubmit( $data ) {
	}

	/**
	 * FIXME: Convert to put rcid and token into form as hidden fields.
	 * Similar to proposed RollbackAction patch. Consider not overriding show(),
	 * but using smaller methods provided by parent class.
	 */
	public function show() {
		$this->setHeaders();
		$this->checkCanExecute( $this->getUser() );

		$request = $this->getRequest();

		$rcId = $request->getInt( 'rcid' );
		$rc = RecentChange::newFromId( $rcId );
		if ( is_null( $rc ) ) {
			throw new ErrorPageError( 'markedaspatrollederror', 'markedaspatrollederrortext' );
		}

		$user = $this->getUser();
		if ( !$user->matchEditToken( $request->getVal( 'token' ), $rcId ) ) {
			throw new ErrorPageError( 'sessionfailure-title', 'sessionfailure' );
		}

		$errors = $rc->doMarkPatrolled( $user );

		if ( in_array( [ 'rcpatroldisabled' ], $errors ) ) {
			throw new ErrorPageError( 'rcpatroldisabled', 'rcpatroldisabledtext' );
		}

		if ( in_array( [ 'hookaborted' ], $errors ) ) {
			// The hook itself has handled any output
			return;
		}

		# It would be nice to see where the user had actually come from, but for now just guess
		if ( $rc->getAttribute( 'rc_type' ) == RC_NEW ) {
			$returnTo = 'Newpages';
		} elseif ( $rc->getAttribute( 'rc_log_type' ) == 'upload' ) {
			$returnTo = 'Newfiles';
		} else {
			$returnTo = 'Recentchanges';
		}
		$return = SpecialPage::getTitleFor( $returnTo );

		if ( in_array( [ 'markedaspatrollederror-noautopatrol' ], $errors ) ) {
			$this->getOutput()->setPageTitle( $this->msg( 'markedaspatrollederror' ) );
			$this->getOutput()->addWikiMsg( 'markedaspatrollederror-noautopatrol' );
			$this->getOutput()->returnToMain( null, $return );

			return;
		}

		if ( count( $errors ) ) {
			throw new PermissionsError( 'patrol', $errors );
		}

		# Inform the user
		$this->getOutput()->setPageTitle( $this->msg( 'markedaspatrolled' ) );
		$this->getOutput()->addWikiMsg( 'markedaspatrolledtext', $rc->getTitle()->getPrefixedText() );
		$this->getOutput()->returnToMain( null, $return );
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setSubmitTextMsg( 'confirm-markpatrolled-button' );
	}

	protected function preText() {
		// TODO: Get rc object. Get rc->getTitle(). and rc_type (edit or new)
		// Use message: Mark the selected revision as patrolled (like markedaspatrollednotify)
		// or
		// Use message: Mark this page as patrolled (like markaspatrolledtext)
		return $this->msg( 'confirm-markpatrolled-top' )->parse();
	}

	public function onSuccess() {
		// ..
	}

	public function doesWrites() {
		return true;
	}
}
