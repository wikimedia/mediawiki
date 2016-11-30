<?php
/**
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

use MediaWiki\MediaWikiServices;

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
		// Disable default header "subtitle"
		return '';
	}

	public function getRestriction() {
		return 'patrol';
	}

	protected function getRecentChange( $data = null ) {
		$rc = null;
		// Note: This works both on initial GET url and after submitting the form
		$rcId = $data ? intval( $data['rcid'] ) : $this->getRequest()->getInt( 'rcid' );
		if ( $rcId ) {
			$rc = RecentChange::newFromId( $rcId );
		}
		if ( !$rc ) {
			throw new ErrorPageError( 'markedaspatrollederror', 'markedaspatrollederrortext' );
		}
		return $rc;
	}

	protected function preText() {
		$rc = $this->getRecentChange();
		$title = $rc->getTitle();
		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();

		// Based on logentry-patrol-patrol (see PatrolLogFormatter)
		$revId = $rc->getAttribute( 'rc_this_oldid' );
		$query = [
			'curid' => $rc->getAttribute( 'rc_cur_id' ),
			'diff' => $revId,
			'oldid' => $rc->getAttribute( 'rc_last_oldid' )
		];
		$revlink = $linkRenderer->makeLink( $title, $revId, [], $query );
		$pagelink = $linkRenderer->makeLink( $title, $title->getPrefixedText() );

		return $this->msg( 'confirm-markpatrolled-top' )->params(
			$title->getPrefixedText(),
			// Provide pre-rendered link as parser would render [[:$1]] as bold non-link
			Message::rawParam( $pagelink ),
			Message::rawParam( $revlink )
		)->parse();
	}

	protected function alterForm( HTMLForm $form ) {
		$form->addHiddenField( 'rcid', $this->getRequest()->getInt( 'rcid' ) );
		$form->setTokenSalt( 'patrol' );
		$form->setSubmitTextMsg( 'confirm-markpatrolled-button' );
	}

	/**
	 * @return bool|array True for success, false for didn't-try, array of errors on failure
	 */
	public function onSubmit( $data ) {
		$user = $this->getUser();
		$rc = $this->getRecentChange( $data );
		$errors = $rc->doMarkPatrolled( $user );

		if ( in_array( [ 'rcpatroldisabled' ], $errors ) ) {
			throw new ErrorPageError( 'rcpatroldisabled', 'rcpatroldisabledtext' );
		}

		// Guess where the user came from
		// TODO: Would be nice to see where the user actually came from
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
			return true;
		}

		if ( $errors ) {
			if ( !in_array( [ 'hookaborted' ], $errors ) ) {
				throw new PermissionsError( 'patrol', $errors );
			}
			// The hook itself has handled any output
			return $errors;
		}

		$this->getOutput()->setPageTitle( $this->msg( 'markedaspatrolled' ) );
		$this->getOutput()->addWikiMsg( 'markedaspatrolledtext', $rc->getTitle()->getPrefixedText() );
		$this->getOutput()->returnToMain( null, $return );
		return true;
	}

	public function onSuccess() {
		// Required by parent class. Redundant as our onSubmit handles output already.
	}

	public function doesWrites() {
		return true;
	}
}
