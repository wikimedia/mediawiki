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

class DeleteAction extends Action {

	public function getName(){
		return 'delete';
	}

	public function getRestriction(){
		return 'delete';
	}

	protected function getDescription(){
		return wfMsg( 'delete-confirm', $this->getTitle()->getPrefixedText() );
	}

	/**
	 * Check that the deletion can be executed.  In addition to checking the user permissions,
	 * check that the page is not too big and has not already been deleted.
	 * @throws ErrorPageError
	 * @see Action::checkCanExecute
	 */
	protected function checkCanExecute( User $user ){

		// Check that the article hasn't already been deleted
		$dbw = wfGetDB( DB_MASTER );
		$conds = $this->getTitle()->pageCond();
		$latest = $dbw->selectField( 'page', 'page_latest', $conds, __METHOD__ );
		if ( $latest === false ) {
			// Get the deletion log
			$log = '';
			LogEventsList::showLogExtract(
				$log,
				'delete',
				$this->getTitle()->getPrefixedText()
			);

			$msg = new Message( 'cannotdelete' );
			$msg->params( $this->getTitle()->getPrefixedText() ); // This parameter is parsed
			$msg->rawParams( $log ); // This is not

			throw new ErrorPageError( 'internalerror', $msg );
		}

		// Limit deletions of big pages
		$bigHistory = $this->isBigDeletion();
		if ( $bigHistory && !$user->isAllowed( 'bigdelete' ) ) {
			global $wgDeleteRevisionsLimit;
			throw new ErrorPageError(
				'internalerror',
				'delete-toobig',
				$this->getContext()->lang->formatNum( $wgDeleteRevisionsLimit )
			);
		}

		return parent::checkCanExecute( $user );
	}

	protected function getFormFields(){
		// TODO: add more useful things here?
		$infoText = Html::rawElement(
			'strong',
			array(),
			Linker::link( $this->getTitle(), $this->getTitle()->getText() )
		);

		$arr = array(
			'Page' => array(
				'type' => 'info',
				'raw' => true,
				'default' => $infoText,
			),
			'Reason' => array(
				'type' => 'selectandother',
				'label-message' => 'deletecomment',
				'options-message' => 'deletereason-dropdown',
				'size' => '60',
				'maxlength' => '255',
				'default' => self::getAutoReason( $this->page),
			),
		);

		if( $this->getUser()->isLoggedIn() ){
			$arr['Watch'] = array(
				'type' => 'check',
				'label-message' => 'watchthis',
				'default' => $this->getUser()->getBoolOption( 'watchdeletion' ) || $this->getTitle()->userIsWatching()
			);
		}

		if( $this->getUser()->isAllowed( 'suppressrevision' ) ){
			$arr['Suppress'] = array(
				'type' => 'check',
				'label-message' => 'revdelete-suppress',
				'default' => false,
			);
		}

		return $arr;
	}

	/**
	 * Text to go at the top of the form, before the opening fieldset
	 * @see Action::preText()
	 * @return String
	 */
	protected function preText() {

		// If the page has a history, insert a warning
		if ( $this->page->estimateRevisionCount() ) {
			global $wgLang;

			$link = Linker::link(
				$this->getTitle(),
				wfMsgHtml( 'history' ),
				array( 'rel' => 'archives' ),
				array( 'action' => 'history' )
			);

			return Html::rawElement(
				'strong',
				array( 'class' => 'mw-delete-warning-revisions' ),
				wfMessage(
					'historywarning',
					$wgLang->formatNum( $this->page->estimateRevisionCount() )
				)->rawParams( $link )->parse()
			);
		}
	}

	/**
	 * Text to go at the bottom of the form, below the closing fieldset
	 * @see Action::postText()
	 * @return string
	 */
	protected function postText(){
		$s = '';
		LogEventsList::showLogExtract(
			$s,
			'delete',
			$this->getTitle()->getPrefixedText()
		);
		return Html::element( 'h2', array(), LogPage::logName( 'delete' ) ) . $s;
	}

	protected function alterForm( HTMLForm &$form ){
		$form->setWrapperLegend( wfMsgExt( 'delete-legend', array( 'parsemag', 'escapenoentities' ) ) );

		if ( $this->getUser()->isAllowed( 'editinterface' ) ) {
			$link = Linker::link(
				Title::makeTitle( NS_MEDIAWIKI, 'Deletereason-dropdown' ),
				wfMsgHtml( 'delete-edit-reasonlist' ),
				array(),
				array( 'action' => 'edit' )
			);
			$form->addHeaderText( '<p class="mw-delete-editreasons">' . $link . '</p>' );
		}
	}

	/**
	 * Function called on form submission.  Privilege checks and validation have already been
	 * completed by this point; we just need to jump out to the heavy-lifting function,
	 * which is implemented as a static method so it can be called from other places
	 * TODO: make those other places call $action->execute() properly
	 * @see Action::onSubmit()
	 * @param  $data Array
	 * @return Array|Bool
	 */
	public function onSubmit( $data ){
		$status = self::doDeleteArticle( $this->page, $this->getContext(), $data, true );
		return $status;
	}

	public function onSuccess(){
		// Watch or unwatch, if requested
		if( $this->getRequest()->getCheck( 'wpWatch' ) && $this->getUser()->isLoggedIn() ) {
			Action::factory( 'watch', $this->page )->execute();
		} elseif ( $this->getTitle()->userIsWatching() ) {
			Action::factory( 'unwatch', $this->page )->execute();
		}

		$this->getOutput()->setPagetitle( wfMsg( 'actioncomplete' ) );
		$this->getOutput()->addWikiMsg(
			'deletedtext',
			$this->getTitle()->getPrefixedText(),
			'[[Special:Log/delete|' . wfMsgNoTrans( 'deletionlog' ) . ']]'
		);
		$this->getOutput()->returnToMain( false );
	}

	/**
	 * @return bool whether or not the page surpasses $wgDeleteRevisionsLimit revisions
	 */
	protected function isBigDeletion() {
		global $wgDeleteRevisionsLimit;
		return $wgDeleteRevisionsLimit && $this->page->estimateRevisionCount() > $wgDeleteRevisionsLimit;
	}

	/**
	 * Back-end article deletion
	 * Deletes the article with database consistency, writes logs, purges caches
	 *
	 * @param $commit boolean defaults to true, triggers transaction end
	 * @return Bool|Array true if successful, error array on failure
	 */
	public static function doDeleteArticle( Article $page, RequestContext $context, array $data, $commit = true ) {
		global $wgDeferredUpdateList, $wgUseTrackbacks;

		wfDebug( __METHOD__ . "\n" );

		// The normal syntax from HTMLSelectAndOtherField is for the reason to be in the form
		// 'Reason' => array( <full reason>, <dropdown>, <custom> ), but it's reasonable for other
		// functions to just pass 'Reason' => <reason>
		$data['Reason'] = (array)$data['Reason'];

		$error = null;
		if ( !wfRunHooks( 'ArticleDelete', array( &$page, &$context->user, &$data['Reason'][0], &$error ) ) ) {
			return $error;
		}

		$title = $page->getTitle();
		$id = $page->getID( Title::GAID_FOR_UPDATE );

		if ( $title->getDBkey() === '' || $id == 0 ) {
			return false;
		}

		$updates = new SiteStatsUpdate( 0, 1, - (int)$page->isCountable(), -1 );
		array_push( $wgDeferredUpdateList, $updates );

		// Bitfields to further suppress the content
		if ( isset( $data['Suppress'] ) && $data['Suppress'] ) {
			$bitfield = 0;
			// This should be 15...
			$bitfield |= Revision::DELETED_TEXT;
			$bitfield |= Revision::DELETED_COMMENT;
			$bitfield |= Revision::DELETED_USER;
			$bitfield |= Revision::DELETED_RESTRICTED;

			$logtype = 'suppress';
		} else {
			// Otherwise, leave it unchanged
			$bitfield = 'rev_deleted';
			$logtype = 'delete';
		}

		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();
		// For now, shunt the revision data into the archive table.
		// Text is *not* removed from the text table; bulk storage
		// is left intact to avoid breaking block-compression or
		// immutable storage schemes.
		//
		// For backwards compatibility, note that some older archive
		// table entries will have ar_text and ar_flags fields still.
		//
		// In the future, we may keep revisions and mark them with
		// the rev_deleted field, which is reserved for this purpose.
		$dbw->insertSelect(
			'archive',
			array( 'page', 'revision' ),
			array(
				'ar_namespace'  => 'page_namespace',
				'ar_title'      => 'page_title',
				'ar_comment'    => 'rev_comment',
				'ar_user'       => 'rev_user',
				'ar_user_text'  => 'rev_user_text',
				'ar_timestamp'  => 'rev_timestamp',
				'ar_minor_edit' => 'rev_minor_edit',
				'ar_rev_id'     => 'rev_id',
				'ar_text_id'    => 'rev_text_id',
				'ar_text'       => "''", // Be explicit to appease
				'ar_flags'      => "''", // MySQL's "strict mode"...
				'ar_len'        => 'rev_len',
				'ar_page_id'    => 'page_id',
				'ar_deleted'    => $bitfield
			),
			array(
				'page_id' => $id,
				'page_id = rev_page'
			),
			__METHOD__
		);

		// Delete restrictions for it
		$dbw->delete( 'page_restrictions', array ( 'pr_page' => $id ), __METHOD__ );

		// Now that it's safely backed up, delete it
		$dbw->delete( 'page', array( 'page_id' => $id ), __METHOD__ );

		// getArticleId() uses slave, could be laggy
		if ( $dbw->affectedRows() == 0 ) {
			$dbw->rollback();
			return false;
		}

		// Fix category table counts
		$res = $dbw->select( 'categorylinks', 'cl_to', array( 'cl_from' => $id ), __METHOD__ );
		$cats = array();
		foreach ( $res as $row ) {
			$cats[] = $row->cl_to;
		}
		$page->updateCategoryCounts( array(), $cats );

		// If using cascading deletes, we can skip some explicit deletes
		if ( !$dbw->cascadingDeletes() ) {
			$dbw->delete( 'revision', array( 'rev_page' => $id ), __METHOD__ );

			if ( $wgUseTrackbacks ){
				$dbw->delete( 'trackbacks', array( 'tb_page' => $id ), __METHOD__ );
			}

			// Delete outgoing links
			$dbw->delete( 'pagelinks', array( 'pl_from' => $id ) );
			$dbw->delete( 'imagelinks', array( 'il_from' => $id ) );
			$dbw->delete( 'categorylinks', array( 'cl_from' => $id ) );
			$dbw->delete( 'templatelinks', array( 'tl_from' => $id ) );
			$dbw->delete( 'externallinks', array( 'el_from' => $id ) );
			$dbw->delete( 'langlinks', array( 'll_from' => $id ) );
			$dbw->delete( 'redirect', array( 'rd_from' => $id ) );
		}

		// If using cleanup triggers, we can skip some manual deletes
		if ( !$dbw->cleanupTriggers() ) {
			// Clean up recentchanges entries...
			$dbw->delete( 'recentchanges',
				array(
					'rc_type != ' . RC_LOG,
					'rc_namespace' => $title->getNamespace(),
					'rc_title' => $title->getDBkey() ),
				__METHOD__
			);
			$dbw->delete(
				'recentchanges',
				array( 'rc_type != ' . RC_LOG, 'rc_cur_id' => $id ),
				__METHOD__
			);
		}

		// Clear caches
		// TODO: should this be in here or left in Article?
		Article::onArticleDelete( $title );

		// Clear the cached article id so the interface doesn't act like we exist
		$title->resetArticleID( 0 );

		// Log the deletion, if the page was suppressed, log it at Oversight instead
		$log = new LogPage( $logtype );

		// Make sure logging got through
		$log->addEntry( 'delete', $title, $data['Reason'][0], array() );

		if ( $commit ) {
			$dbw->commit();
		}

		wfRunHooks( 'ArticleDeleteComplete', array( &$page, &$context->user, $data['Reason'][0], $id ) );
		return true;
	}

	/**
	 * Auto-generates a deletion reason.  Also sets $this->hasHistory if the page has old
	 * revisions.
	 *
	 * @return mixed String containing default reason or empty string, or boolean false
	 *    if no revision was found
	 */
	public static function getAutoReason( Article $page ) {
		global $wgContLang;

		$dbw = wfGetDB( DB_MASTER );
		// Get the last revision
		$rev = Revision::newFromTitle( $page->getTitle() );

		if ( is_null( $rev ) ) {
			return false;
		}

		// Get the article's contents
		$contents = $rev->getText();
		$blank = false;

		// If the page is blank, use the text from the previous revision,
		// which can only be blank if there's a move/import/protect dummy revision involved
		if ( $contents == '' ) {
			$prev = $rev->getPrevious();

			if ( $prev )	{
				$contents = $prev->getText();
				$blank = true;
			}
		}

		// Find out if there was only one contributor
		// Only scan the last 20 revisions
		$res = $dbw->select( 'revision', 'rev_user_text',
			array(
				'rev_page' => $page->getID(),
				$dbw->bitAnd( 'rev_deleted', Revision::DELETED_USER ) . ' = 0'
			),
			__METHOD__,
			array( 'LIMIT' => 20 )
		);

		if ( $res === false ) {
			// This page has no revisions, which is very weird
			return false;
		}

		$row = $dbw->fetchObject( $res );

		if ( $row ) { // $row is false if the only contributor is hidden
			$onlyAuthor = $row->rev_user_text;
			// Try to find a second contributor
			foreach ( $res as $row ) {
				if ( $row->rev_user_text != $onlyAuthor ) { // Bug 22999
					$onlyAuthor = false;
					break;
				}
			}
		} else {
			$onlyAuthor = false;
		}

		// Generate the summary with a '$1' placeholder
		if ( $blank ) {
			// The current revision is blank and the one before is also
			// blank. It's just not our lucky day
			$reason = wfMessage( 'exbeforeblank', '$1' )->inContentLanguage()->text();
		} else {
			if ( $onlyAuthor ) {
				$reason = wfMessage( 'excontentauthor', '$1', $onlyAuthor )->inContentLanguage()->text();
			} else {
				$reason = wfMessage( 'excontent', '$1' )->inContentLanguage()->text();
			}
		}

		if ( $reason == '-' ) {
			// Allow these UI messages to be blanked out cleanly
			return '';
		}

		// Replace newlines with spaces to prevent uglyness
		$contents = preg_replace( "/[\n\r]/", ' ', $contents );
		// Calculate the maximum number of chars to get
		// Max content length = max comment length - length of the comment (excl. $1)
		$maxLength = 255 - ( strlen( $reason ) - 2 );
		$contents = $wgContLang->truncate( $contents, $maxLength );
		// Remove possible unfinished links
		$contents = preg_replace( '/\[\[([^\]]*)\]?$/', '$1', $contents );
		// Now replace the '$1' placeholder
		$reason = str_replace( '$1', $contents, $reason );

		return $reason;
	}

	public function show() {
	}

	public function execute(){

	}
}
