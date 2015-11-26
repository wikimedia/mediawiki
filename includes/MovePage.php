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

	public function checkPermissions( User $user, $reason ) {
		$status = new Status();

		$errors = wfMergeErrorArrays(
			$this->oldTitle->getUserPermissionsErrors( 'move', $user ),
			$this->oldTitle->getUserPermissionsErrors( 'edit', $user ),
			$this->newTitle->getUserPermissionsErrors( 'move-target', $user ),
			$this->newTitle->getUserPermissionsErrors( 'edit', $user )
		);

		// Convert into a Status object
		if ( $errors ) {
			foreach ( $errors as $error ) {
				call_user_func_array( array( $status, 'fatal' ), $error );
			}
		}

		if ( EditPage::matchSummarySpamRegex( $reason ) !== false ) {
			// This is kind of lame, won't display nice
			$status->fatal( 'spamprotectiontext' );
		}

		$tp = $this->newTitle->getTitleProtection();
		if ( $tp !== false && !$user->isAllowed( $tp['permission'] ) ) {
				$status->fatal( 'cantmove-titleprotected' );
		}

		Hooks::run( 'MovePageCheckPermissions',
			array( $this->oldTitle, $this->newTitle, $user, $reason, $status )
		);

		return $status;
	}

	/**
	 * Does various sanity checks that the move is
	 * valid. Only things based on the two titles
	 * should be checked here.
	 *
	 * @return Status
	 */
	public function isValidMove() {
		global $wgContentHandlerUseDB;
		$status = new Status();

		if ( $this->oldTitle->equals( $this->newTitle ) ) {
			$status->fatal( 'selfmove' );
		}
		if ( !$this->oldTitle->isMovable() ) {
			$status->fatal( 'immobile-source-namespace', $this->oldTitle->getNsText() );
		}
		if ( $this->newTitle->isExternal() ) {
			$status->fatal( 'immobile-target-namespace-iw' );
		}
		if ( !$this->newTitle->isMovable() ) {
			$status->fatal( 'immobile-target-namespace', $this->newTitle->getNsText() );
		}

		$oldid = $this->oldTitle->getArticleID();

		if ( strlen( $this->newTitle->getDBkey() ) < 1 ) {
			$status->fatal( 'articleexists' );
		}
		if (
			( $this->oldTitle->getDBkey() == '' ) ||
			( !$oldid ) ||
			( $this->newTitle->getDBkey() == '' )
		) {
			$status->fatal( 'badarticleerror' );
		}

		# The move is allowed only if (1) the target doesn't exist, or
		# (2) the target is a redirect to the source, and has no history
		# (so we can undo bad moves right after they're done).
		if ( $this->newTitle->getArticleID() && !$this->isValidMoveTarget() ) {
			$status->fatal( 'articleexists' );
		}

		// Content model checks
		if ( !$wgContentHandlerUseDB &&
			$this->oldTitle->getContentModel() !== $this->newTitle->getContentModel() ) {
			// can't move a page if that would change the page's content model
			$status->fatal(
				'bad-target-model',
				ContentHandler::getLocalizedName( $this->oldTitle->getContentModel() ),
				ContentHandler::getLocalizedName( $this->newTitle->getContentModel() )
			);
		}

		// Image-specific checks
		if ( $this->oldTitle->inNamespace( NS_FILE ) ) {
			$status->merge( $this->isValidFileMove() );
		}

		if ( $this->newTitle->inNamespace( NS_FILE ) && !$this->oldTitle->inNamespace( NS_FILE ) ) {
			$status->fatal( 'nonfile-cannot-move-to-file' );
		}

		// Hook for extensions to say a title can't be moved for technical reasons
		Hooks::run( 'MovePageIsValidMove', array( $this->oldTitle, $this->newTitle, $status ) );

		return $status;
	}

	/**
	 * Sanity checks for when a file is being moved
	 *
	 * @return Status
	 */
	protected function isValidFileMove() {
		$status = new Status();
		$file = wfLocalFile( $this->oldTitle );
		$file->load( File::READ_LATEST );
		if ( $file->exists() ) {
			if ( $this->newTitle->getText() != wfStripIllegalFilenameChars( $this->newTitle->getText() ) ) {
				$status->fatal( 'imageinvalidfilename' );
			}
			if ( !File::checkExtensionCompatibility( $file, $this->newTitle->getDBkey() ) ) {
				$status->fatal( 'imagetypemismatch' );
			}
		}

		if ( !$this->newTitle->inNamespace( NS_FILE ) ) {
			$status->fatal( 'imagenocrossnamespace' );
		}

		return $status;
	}

	/**
	 * Checks if $this can be moved to a given Title
	 * - Selects for update, so don't call it unless you mean business
	 *
	 * @since 1.25
	 * @return bool
	 */
	protected function isValidMoveTarget() {
		# Is it an existing file?
		if ( $this->newTitle->inNamespace( NS_FILE ) ) {
			$file = wfLocalFile( $this->newTitle );
			$file->load( File::READ_LATEST );
			if ( $file->exists() ) {
				wfDebug( __METHOD__ . ": file exists\n" );
				return false;
			}
		}
		# Is it a redirect with no history?
		if ( !$this->newTitle->isSingleRevRedirect() ) {
			wfDebug( __METHOD__ . ": not a one-rev redirect\n" );
			return false;
		}
		# Get the article text
		$rev = Revision::newFromTitle( $this->newTitle, false, Revision::READ_LATEST );
		if ( !is_object( $rev ) ) {
			return false;
		}
		$content = $rev->getContent();
		# Does the redirect point to the source?
		# Or is it a broken self-redirect, usually caused by namespace collisions?
		$redirTitle = $content ? $content->getRedirectTarget() : null;

		if ( $redirTitle ) {
			if ( $redirTitle->getPrefixedDBkey() !== $this->oldTitle->getPrefixedDBkey() &&
				$redirTitle->getPrefixedDBkey() !== $this->newTitle->getPrefixedDBkey() ) {
				wfDebug( __METHOD__ . ": redirect points to other page\n" );
				return false;
			} else {
				return true;
			}
		} else {
			# Fail safe (not a redirect after all. strange.)
			wfDebug( __METHOD__ . ": failsafe: database says " . $this->newTitle->getPrefixedDBkey() .
				" is a redirect, but it doesn't contain a valid redirect.\n" );
			return false;
		}
	}

	/**
	 * @param User $user
	 * @param string $reason
	 * @param bool $createRedirect
	 * @return Status
	 */
	public function move( User $user, $reason, $createRedirect ) {
		global $wgCategoryCollation;

		Hooks::run( 'TitleMove', array( $this->oldTitle, $this->newTitle, $user ) );

		// If it is a file, move it first.
		// It is done before all other moving stuff is done because it's hard to revert.
		$dbw = wfGetDB( DB_MASTER );
		if ( $this->oldTitle->getNamespace() == NS_FILE ) {
			$file = wfLocalFile( $this->oldTitle );
			$file->load( File::READ_LATEST );
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

			// Build comment for log
			$comment = wfMessage(
				'prot_1movedto2',
				$this->oldTitle->getPrefixedText(),
				$this->newTitle->getPrefixedText()
			)->inContentLanguage()->text();
			if ( $reason ) {
				$comment .= wfMessage( 'colon-separator' )->inContentLanguage()->text() . $reason;
			}

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

			// Update the protection log
			$logEntry = new ManualLogEntry( 'protect', 'move_prot' );
			$logEntry->setTarget( $this->newTitle );
			$logEntry->setComment( $comment );
			$logEntry->setPerformer( $user );
			$logEntry->setParameters( array(
				'4::oldtitle' => $this->oldTitle->getPrefixedText(),
			) );
			$logEntry->setRelations( array( 'pr_id' => $logRelationsValues ) );
			$logId = $logEntry->insert();
			$logEntry->publish( $logId );
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

		Hooks::run(
			'TitleMoveComplete',
			array( &$this->oldTitle, &$this->newTitle, &$user, $pageid, $redirid, $reason )
		);
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

		// Figure out whether the content model is no longer the default
		$oldDefault = ContentHandler::getDefaultModelFor( $this->oldTitle );
		$contentModel = $this->oldTitle->getContentModel();
		$newDefault = ContentHandler::getDefaultModelFor( $nt );
		$defaultContentModelChanging = ( $oldDefault !== $newDefault
			&& $oldDefault === $contentModel );

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

		$oldpage = WikiPage::factory( $this->oldTitle );
		$oldcountable = $oldpage->isCountable();

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

		Hooks::run( 'NewRevisionFromEditComplete',
			array( $newpage, $nullRevision, $nullRevision->getParentId(), $user ) );

		$newpage->doEditUpdates( $nullRevision, $user,
			array( 'changed' => false, 'moved' => true, 'oldcountable' => $oldcountable ) );

		// If the default content model changes, we need to populate rev_content_model
		if ( $defaultContentModelChanging ) {
			$dbw->update(
				'revision',
				array( 'rev_content_model' => $contentModel ),
				array( 'rev_page' => $nt->getArticleID(), 'rev_content_model IS NULL' ),
				__METHOD__
			);
		}

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

				Hooks::run( 'NewRevisionFromEditComplete',
					array( $redirectArticle, $redirectRevision, false, $user ) );

				$redirectArticle->doEditUpdates( $redirectRevision, $user, array( 'created' => true ) );
			}
		}

		# Log the move
		$logid = $logEntry->insert();
		$logEntry->publish( $logid );
	}
}
