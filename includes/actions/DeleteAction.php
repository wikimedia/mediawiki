<?php
/**
 * Handle page deletion
 *
 * Copyright © 2012 TTyler Romeo
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
 * @author Tyler Romeo
 */

class DeleteAction extends FormAction {
	public function getName() {
		return 'delete';
	}

	public function getDescription() {
		return '';
	}

	public function getPageTitle() {
		$title = parent::getPageTitle();
		return wfMessage( 'delete-confirm', $title );
	}

	public function getRestriction() {
		return 'delete';
	}

	protected function checkCanExecute( User $user ) {
		parent::checkCanExecute( $user );

		// Make sure page exists.
		$title = $this->getTitle();
		$dbw = wfGetDB( DB_MASTER );
		$conds = $title->pageCond();
		$latest = $dbw->selectField( 'page', 'page_latest', $conds, __METHOD__ );
		if ( $latest === false ) {
			$msg = wfMessage( 'cannotdelete', wfEscapeWikiText( $title->getPrefixedText() ) );
			throw new BadTitleError( $msg );
		}
	}

	protected function getFormFields() {
		$user = $this->getUser();

		$hasHistory = false;
		$reason = $this->getReason( $hasHistory );

		$formDescriptor['DeleteReasonList'] = array(
			'section' => 'delete',
			'type' => 'selectandother',
			'label-message' => 'deletecomment',
			'options-message' => 'deletereason-dropdown',
			'default' => $reason,
			'size' => 60,
			'maxlength' => 255,
		);

		if( $user->isLoggedIn() ) {
			$formDescriptor['Watch'] = array(
				'section' => 'delete',
				'type' => 'check',
				'label-message' => 'watchthis',
				'default' => $user->getBoolOption( 'watchdeletion' ) || $this->getTitle()->userIsWatching()
			);
		}

		$formDescriptor['DeleteTalk'] = array(
			'section' => 'delete',
			'type' => 'check',
			'label-message' => 'delete-talk'
		);

		if( $user->isAllowed( 'suppressrevision' ) ) {
			$formDescriptor['Suppress'] = array(
				'section' => 'delete',
				'type' => 'check',
				'label-message' => 'revdelete-suppress',
			);
		}

		$formDescriptor['DeleteToken'] = array(
			'type' => 'hidden',
			'raw' => true,
			'default' => $user->getEditToken( array( 'delete', $this->getTitle()->getPrefixedText() ) )
		);

		return $formDescriptor;
	}

	protected function preText() {
		$warning = '';

		$hasHistory = false;
		$reason = $this->getReason( $hasHistory );
		// If the page has a history, insert a warning
		if ( $hasHistory ) {
			$title = $this->getTitle();
			$revisions = $title->estimateRevisionCount();
			// @todo FIXME: i18n issue/patchwork message
			$warning .= '<strong class="mw-delete-warning-revisions">' .
				wfMsgExt( 'historywarning', array( 'parseinline' ), $this->getContext()->getLanguage()->formatNum( $revisions ) ) .
				wfMsgHtml( 'word-separator' ) . Linker::link( $title,
					wfMsgHtml( 'history' ),
					array( 'rel' => 'archives' ),
					array( 'action' => 'history' ) ) .
				'</strong>';


			if ( $title->isBigDeletion() ) {
				global $wgDeleteRevisionsLimit;
				$warning .= "<div class='error'>\n" .
					wfMessage( 'delete-warning-toobig', $this->getContext()->getLanguage()->formatNum( $wgDeleteRevisionsLimit ) ) .
					"\n<div>\n";
			}
		}

		$warning .= Html::rawElement( 'p', array(), $this->msg( 'confirmdeletetext' )->parse() );

		return $warning;
	}

	protected function postText() {
		// Add link to edit deletion reasons if allowed.
		if( $this->getUser()->isAllowed( 'editinterface' ) ) {
			$title = Title::makeTitle( NS_MEDIAWIKI, 'Deletereason-dropdown' );
			$link = Linker::link(
				$title,
				wfMsgHtml( 'delete-edit-reasonlist' ),
				array(),
				array( 'action' => 'edit' )
			);
			return Html::rawElement( 'p', array( 'class' => 'mw-delete-editreasons' ), $link );
		} else {
			return '';
		}
	}

	protected function getForm() {
		$form = parent::getForm();
		$form->setSubmitTextMsg( 'deletepage' );
		return $form;
	}

	public function show() {
		parent::show();

		$outputPage = $this->getOutput();
		$outputPage->clearSubtitle();
		$outputPage->addBacklinkSubtitle( $this->getTitle() );
		$outputPage->addHTML( Xml::element( 'h2', null, LogPage::logName( 'delete' ) ) );
		LogEventsList::showLogExtract( $outputPage, 'delete',
			$this->getTitle()->getPrefixedText()
		);
	}

	public function onSubmit( $data ) {
		$user = $this->getUser();
		$title = $this->getTitle();

		// Check if edit token matches.
		if( !$user->matchEditToken( $data['DeleteToken'], array( 'delete', $this->getTitle()->getPrefixedText() ) ) ) {
			throw new ErrorPageError( 'sessionfailure-title', 'sessionfailure' );
		}

		// Watch or unwatch the page if requested.
		if( $data['Watch'] && $user->isLoggedIn() ) {
			WatchAction::doWatch( $title, $user );
		} elseif ( $title->userIsWatching() ) {
			WatchAction::doUnwatch( $title, $user );
		}

		// Delete the page.
		$suppress = $data['Suppress'] && $user->isAllowed( 'suppressrevision' );
		$error = $this->page->delete( $data['DeleteReasonList'][0], $suppress );

		if( !$error->isGood() ) {
			// Deletion was unsuccessful.
			$msgTitle = wfMessage( 'cannotdelete-title', $this->getTitle()->getPrefixedText() );
			throw new ErrorPageError( $msgTitle, $error->getMessage() );
		}

		// If set, delete the talk page.
		if( $data['DeleteTalk'] ) {
			$talkTitle = $title->getTalkPage();

	                // Make sure talk page exists. If it doesn't, ignore it.
			$dbw = wfGetDB( DB_MASTER );
			$conds = $talkTitle->pageCond();
			$latest = $dbw->selectField( 'page', 'page_latest', $conds, __METHOD__ );

			if ( $latest !== false ) {
				// Talk page exists, delete it.
				$talkPage = Article::newFromTitle( $talkTitle, $this->getContext() );
				$error = $talkPage->delete( $data['DeleteReasonList'][0] );

				if( !$error->isGood() ) {
					$msgTitle = wfMessage( 'cannotdelete-title', $talkTitle->getPrefixedText() );
					throw new ErrorPageError( $msgTitle, $error->getMessage() );
				}
			}
		}

		return true;
	}

	public function onSuccess() {
		$outputPage = $this->getOutput();
		$outputPage->setPageTitle( wfMessage( 'actioncomplete' ) );

		$deleted = $this->getTitle()->getPrefixedText();
		$loglink = '[[Special:Log/delete|' . wfMsgNoTrans( 'deletionlog' ) . ']]';

		$outputPage->addWikiMsg( 'deletedtext', wfEscapeWikiText( $deleted ), $loglink );
		$outputPage->returnToMain( false );
	}

	protected function getReason( &$hasHistory ) {
		$request = $this->getRequest();
		$deleteReasonList = $request->getText( 'wpDeleteReasonList', 'other' );
		$deleteReason = $request->getText( 'wpReason' );

		if ( $deleteReasonList == 'other' ) {
			$reason = $deleteReason;
		} elseif ( $deleteReason != '' ) {
			// Entry from drop down menu + additional comment
			$reason = $deleteReasonList . wfMsgForContent( 'colon-separator' ) . $deleteReason;
		} else {
			$reason = $deleteReasonList;
		}

		// Generate deletion reason
		if ( !$reason ) {
			$reason = $this->page->generateReason( $hasHistory );
		}

		wfRunHooks( 'ArticleConfirmDelete', array( $this->page, $this->getOutput(), &$reason ) );

		return $reason;
	}
}
