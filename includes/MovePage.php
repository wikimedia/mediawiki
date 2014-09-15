<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * Handles the backend logic of moving a page from one title
 * to another.
 *
 * @since 1.24
 */
class MovePage {

	/**
	 * @var Title
	 */
	protected $oldTitle;

	/**
	 * @var Title
	 */
	protected $newTitle;

	public function __construct( Title $oldTitle, Title $newTitle ) {
		$this->oldTitle = $oldTitle;
		$this->newTitle = $newTitle;
	}

	/**
	 * @param User $user
	 * @param string $reason
	 * @param bool $createRedirect
	 * @return Status
	 */
	public function move( User $user, $reason, $createRedirect ) {
		global $wgCategoryCollation;

		// If it is a file, move it first.
		// It is done before all other moving stuff is done because it's hard to revert.
		$dbw = wfGetDB( DB_MASTER );
		if ( $this->oldTitle->getNamespace() == NS_FILE ) {
			$file = wfLocalFile( $this->oldTitle );
			if ( $file->exists() ) {
				$status = $file->move( $this->newTitle );
				if ( !$status->isOk() ) {
					return $status;
				}
			}
			// Clear RepoGroup process cache
			RepoGroup::singleton()->clearCache( $this->oldTitle );
			RepoGroup::singleton()->clearCache( $this->newTitle ); # clear false negative cache
		}

		$dbw->begin( __METHOD__ ); # If $file was a LocalFile, its transaction would have closed our own.
		$pageid = $this->oldTitle->getArticleID( Title::GAID_FOR_UPDATE );
		$protected = $this->oldTitle->isProtected();

		// Do the actual move
		$this->moveToInternal( $user, $this->newTitle, $reason, $createRedirect );

		// Refresh the sortkey for this row.  Be careful to avoid resetting
		// cl_timestamp, which may disturb time-based lists on some sites.
		// @todo This block should be killed, it's duplicating code
		// from LinksUpdate::getCategoryInsertions() and friends.
		$prefixes = $dbw->select(
			'categorylinks',
			array( 'cl_sortkey_prefix', 'cl_to' ),
			array( 'cl_from' => $pageid ),
			__METHOD__
		);
		if ( $this->newTitle->getNamespace() == NS_CATEGORY ) {
			$type = 'subcat';
		} elseif ( $this->newTitle->getNamespace() == NS_FILE ) {
			$type = 'file';
		} else {
			$type = 'page';
		}
		foreach ( $prefixes as $prefixRow ) {
			$prefix = $prefixRow->cl_sortkey_prefix;
			$catTo = $prefixRow->cl_to;
			$dbw->update( 'categorylinks',
				array(
					'cl_sortkey' => Collation::singleton()->getSortKey(
							$this->newTitle->getCategorySortkey( $prefix ) ),
					'cl_collation' => $wgCategoryCollation,
					'cl_type' => $type,
					'cl_timestamp=cl_timestamp' ),
				array(
					'cl_from' => $pageid,
					'cl_to' => $catTo ),
				__METHOD__
			);
		}

		$redirid = $this->oldTitle->getArticleID();

		if ( $protected ) {
			# Protect the redirect title as the title used to be...
			$dbw->insertSelect( 'page_restrictions', 'page_restrictions',
				array(
					'pr_page' => $redirid,
					'pr_type' => 'pr_type',
					'pr_level' => 'pr_level',
					'pr_cascade' => 'pr_cascade',
					'pr_user' => 'pr_user',
					'pr_expiry' => 'pr_expiry'
				),
				array( 'pr_page' => $pageid ),
				__METHOD__,
				array( 'IGNORE' )
			);
			# Update the protection log
			$log = new LogPage( 'protect' );
			$comment = wfMessage(
				'prot_1movedto2',
				$this->oldTitle->getPrefixedText(),
				$this->newTitle->getPrefixedText()
			)->inContentLanguage()->text();
			if ( $reason ) {
				$comment .= wfMessage( 'colon-separator' )->inContentLanguage()->text() . $reason;
			}
			// @todo FIXME: $params?
			$logId = $log->addEntry(
				'move_prot',
				$this->newTitle,
				$comment,
				array( $this->oldTitle->getPrefixedText() ),
				$user
			);

			// reread inserted pr_ids for log relation
			$insertedPrIds = $dbw->select(
				'page_restrictions',
				'pr_id',
				array( 'pr_page' => $redirid ),
				__METHOD__
			);
			$logRelationsValues = array();
			foreach ( $insertedPrIds as $prid ) {
				$logRelationsValues[] = $prid->pr_id;
			}
			$log->addRelations( 'pr_id', $logRelationsValues, $logId );
		}

		// Update *_from_namespace fields as needed
		if ( $this->oldTitle->getNamespace() != $this->newTitle->getNamespace() ) {
			$dbw->update( 'pagelinks',
				array( 'pl_from_namespace' => $this->newTitle->getNamespace() ),
				array( 'pl_from' => $pageid ),
				__METHOD__
			);
			$dbw->update( 'templatelinks',
				array( 'tl_from_namespace' => $this->newTitle->getNamespace() ),
				array( 'tl_from' => $pageid ),
				__METHOD__
			);
			$dbw->update( 'imagelinks',
				array( 'il_from_namespace' => $this->newTitle->getNamespace() ),
				array( 'il_from' => $pageid ),
				__METHOD__
			);
		}

		# Update watchlists
		$oldtitle = $this->oldTitle->getDBkey();
		$newtitle = $this->newTitle->getDBkey();
		$oldsnamespace = MWNamespace::getSubject( $this->oldTitle->getNamespace() );
		$newsnamespace = MWNamespace::getSubject( $this->newTitle->getNamespace() );
		if ( $oldsnamespace != $newsnamespace || $oldtitle != $newtitle ) {
			WatchedItem::duplicateEntries( $this->oldTitle, $this->newTitle );
		}

		$dbw->commit( __METHOD__ );

		wfRunHooks( 'TitleMoveComplete', array( &$this->oldTitle, &$this->newTitle, &$user, $pageid, $redirid, $reason ) );
		return Status::newGood();

	}

	/**
	 * Move page to a title which is either a redirect to the
	 * source page or nonexistent
	 *
	 * @fixme This was basically directly moved from Title, it should be split into smaller functions
	 * @param User $user the User doing the move
	 * @param Title $nt The page to move to, which should be a redirect or nonexistent
	 * @param string $reason The reason for the move
	 * @param bool $createRedirect Whether to leave a redirect at the old title. Does not check
	 *   if the user has the suppressredirect right
	 * @throws MWException
	 */
	private function moveToInternal( User $user, &$nt, $reason = '', $createRedirect = true ) {
		global $wgContLang;

		if ( $nt->exists() ) {
			$moveOverRedirect = true;
			$logType = 'move_redir';
		} else {
			$moveOverRedirect = false;
			$logType = 'move';
		}

		if ( $createRedirect ) {
			if ( $this->oldTitle->getNamespace() == NS_CATEGORY
				&& !wfMessage( 'category-move-redirect-override' )->inContentLanguage()->isDisabled()
			) {
				$redirectContent = new WikitextContent(
					wfMessage( 'category-move-redirect-override' )
						->params( $nt->getPrefixedText() )->inContentLanguage()->plain() );
			} else {
				$contentHandler = ContentHandler::getForTitle( $this->oldTitle );
				$redirectContent = $contentHandler->makeRedirectContent( $nt,
					wfMessage( 'move-redirect-text' )->inContentLanguage()->plain() );
			}

			// NOTE: If this page's content model does not support redirects, $redirectContent will be null.
		} else {
			$redirectContent = null;
		}

		// bug 57084: log_page should be the ID of the *moved* page
		$oldid = $this->oldTitle->getArticleID();
		$logTitle = clone $this->oldTitle;

		$logEntry = new ManualLogEntry( 'move', $logType );
		$logEntry->setPerformer( $user );
		$logEntry->setTarget( $logTitle );
		$logEntry->setComment( $reason );
		$logEntry->setParameters( array(
			'4::target' => $nt->getPrefixedText(),
			'5::noredir' => $redirectContent ? '0': '1',
		) );

		$formatter = LogFormatter::newFromEntry( $logEntry );
		$formatter->setContext( RequestContext::newExtraneousContext( $this->oldTitle ) );
		$comment = $formatter->getPlainActionText();
		if ( $reason ) {
			$comment .= wfMessage( 'colon-separator' )->inContentLanguage()->text() . $reason;
		}
		# Truncate for whole multibyte characters.
		$comment = $wgContLang->truncate( $comment, 255 );

		$dbw = wfGetDB( DB_MASTER );

		$newpage = WikiPage::factory( $nt );

		if ( $moveOverRedirect ) {
			$newid = $nt->getArticleID();
			$newcontent = $newpage->getContent();

			# Delete the old redirect. We don't save it to history since
			# by definition if we've got here it's rather uninteresting.
			# We have to remove it so that the next step doesn't trigger
			# a conflict on the unique namespace+title index...
			$dbw->delete( 'page', array( 'page_id' => $newid ), __METHOD__ );

			$newpage->doDeleteUpdates( $newid, $newcontent );
		}

		# Save a null revision in the page's history notifying of the move
		$nullRevision = Revision::newNullRevision( $dbw, $oldid, $comment, true, $user );
		if ( !is_object( $nullRevision ) ) {
			throw new MWException( 'No valid null revision produced in ' . __METHOD__ );
		}

		$nullRevision->insertOn( $dbw );

		# Change the name of the target page:
		$dbw->update( 'page',
			/* SET */ array(
				'page_namespace' => $nt->getNamespace(),
				'page_title' => $nt->getDBkey(),
			),
			/* WHERE */ array( 'page_id' => $oldid ),
			__METHOD__
		);

		// clean up the old title before reset article id - bug 45348
		if ( !$redirectContent ) {
			WikiPage::onArticleDelete( $this->oldTitle );
		}

		$this->oldTitle->resetArticleID( 0 ); // 0 == non existing
		$nt->resetArticleID( $oldid );
		$newpage->loadPageData( WikiPage::READ_LOCKING ); // bug 46397

		$newpage->updateRevisionOn( $dbw, $nullRevision );

		wfRunHooks( 'NewRevisionFromEditComplete',
			array( $newpage, $nullRevision, $nullRevision->getParentId(), $user ) );

		$newpage->doEditUpdates( $nullRevision, $user, array( 'changed' => false ) );

		if ( !$moveOverRedirect ) {
			WikiPage::onArticleCreate( $nt );
		}

		# Recreate the redirect, this time in the other direction.
		if ( $redirectContent ) {
			$redirectArticle = WikiPage::factory( $this->oldTitle );
			$redirectArticle->loadFromRow( false, WikiPage::READ_LOCKING ); // bug 46397
			$newid = $redirectArticle->insertOn( $dbw );
			if ( $newid ) { // sanity
				$this->oldTitle->resetArticleID( $newid );
				$redirectRevision = new Revision( array(
					'title' => $this->oldTitle, // for determining the default content model
					'page' => $newid,
					'user_text' => $user->getName(),
					'user' => $user->getId(),
					'comment' => $comment,
					'content' => $redirectContent ) );
				$redirectRevision->insertOn( $dbw );
				$redirectArticle->updateRevisionOn( $dbw, $redirectRevision, 0 );

				wfRunHooks( 'NewRevisionFromEditComplete',
					array( $redirectArticle, $redirectRevision, false, $user ) );

				$redirectArticle->doEditUpdates( $redirectRevision, $user, array( 'created' => true ) );
			}
		}

		# Log the move
		$logid = $logEntry->insert();
		$logEntry->publish( $logid );
	}

}