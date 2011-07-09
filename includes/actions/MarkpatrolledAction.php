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

class MarkpatrolledAction extends FormlessAction {

	public function getName() {
		return 'markpatrolled';
	}

	public function getRestriction() {
		return 'read';
	}

	protected function getDescription() {
		return '';
	}

	protected function checkCanExecute( User $user ) {
		if ( !$user->matchEditToken( $this->getRequest()->getVal( 'token' ), $this->getRequest()->getInt( 'rcid' ) ) ) {
			throw new ErrorPageError( 'sessionfailure-title', 'sessionfailure' );
		}

		return parent::checkCanExecute( $user );
	}

	public function onView() {
		$rc = RecentChange::newFromId( $this->getRequest()->getInt( 'rcid' ) );

		if ( is_null( $rc ) ) {
			throw new ErrorPageError( 'markedaspatrollederror', 'markedaspatrollederrortext' );
		}

		$errors = $rc->doMarkPatrolled( $this->getUser() );

		if ( in_array( array( 'rcpatroldisabled' ), $errors ) ) {
			throw new ErrorPageError( 'rcpatroldisabled', 'rcpatroldisabledtext' );
		}

		if ( in_array( array( 'hookaborted' ), $errors ) ) {
			// The hook itself has handled any output
			return;
		}

		# It would be nice to see where the user had actually come from, but for now just guess
		$returnto = $rc->getAttribute( 'rc_type' ) == RC_NEW ? 'Newpages' : 'Recentchanges';
		$return = SpecialPage::getTitleFor( $returnto );

		if ( in_array( array( 'markedaspatrollederror-noautopatrol' ), $errors ) ) {
			$this->getOutput()->setPageTitle( wfMsg( 'markedaspatrollederror' ) );
			$this->getOutput()->addWikiMsg( 'markedaspatrollederror-noautopatrol' );
			$this->getOutput()->returnToMain( null, $return );
			return;
		}

		if ( !empty( $errors ) ) {
			$this->getOutput()->showPermissionsErrorPage( $errors );
			return;
		}

		# Inform the user
		$this->getOutput()->setPageTitle( wfMsg( 'markedaspatrolled' ) );
		$this->getOutput()->addWikiMsg( 'markedaspatrolledtext', $rc->getTitle()->getPrefixedText() );
		$this->getOutput()->returnToMain( null, $return );
	}
}
