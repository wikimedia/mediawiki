<?php

namespace MediaWiki\Storage;

/**
 * FIXME: document!
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class UpdaterStore {

	/**
	 * Determine whether a page would be suitable for being counted as an
	 * article in the site_stats table based on the title & its content
	 *
	 * @param PreparedEdit|bool $editInfo (false): object returned by prepareTextForEdit(),
	 *   if false, the current database state will be used
	 * @return bool
	 */
	public function isCountable( $editInfo = false ) {
		global $wgArticleCountMethod;

		if ( !$this->mTitle->isContentPage() ) {
			return false;
		}

		if ( $editInfo ) {
			$content = $editInfo->pstContent;
		} else {
			$content = $this->getContent();
		}

		if ( !$content || $content->isRedirect() ) {
			return false;
		}

		$hasLinks = null;

		if ( $wgArticleCountMethod === 'link' ) {
			// nasty special case to avoid re-parsing to detect links

			if ( $editInfo ) {
				// ParserOutput::getLinks() is a 2D array of page links, so
				// to be really correct we would need to recurse in the array
				// but the main array should only have items in it if there are
				// links.
				$hasLinks = (bool)count( $editInfo->output->getLinks() );
			} else {
				$hasLinks = (bool)wfGetDB( DB_REPLICA )->selectField( 'pagelinks', 1,
					[ 'pl_from' => $this->getId() ], __METHOD__ );
			}
		}

		return $content->isCountable( $hasLinks );
	}

	/**
	 * Get a ParserOutput for the given ParserOptions and revision ID.
	 *
	 * The parser cache will be used if possible. Cache misses that result
	 * in parser runs are debounced with PoolCounter.
	 *
	 * @since 1.19
	 * @param ParserOptions $parserOptions ParserOptions to use for the parse operation
	 * @param null|int $oldid Revision ID to get the text from, passing null or 0 will
	 *   get the current revision (default value)
	 * @param bool $forceParse Force reindexing, regardless of cache settings
	 * @return bool|ParserOutput ParserOutput or false if the revision was not found
	 */
	public function getParserOutput(
		ParserOptions $parserOptions, $oldid = null, $forceParse = false
	) {
		$useParserCache =
			( !$forceParse ) && $this->shouldCheckParserCache( $parserOptions, $oldid );

		if ( $useParserCache && !$parserOptions->isSafeToCache() ) {
			throw new InvalidArgumentException(
				'The supplied ParserOptions are not safe to cache. Fix the options or set $forceParse = true.'
			);
		}

		wfDebug( __METHOD__ .
			': using parser cache: ' . ( $useParserCache ? 'yes' : 'no' ) . "\n" );
		if ( $parserOptions->getStubThreshold() ) {
			wfIncrStats( 'pcache.miss.stub' );
		}

		if ( $useParserCache ) {
			$parserOutput = MediaWikiServices::getInstance()->getParserCache()
				->get( $this, $parserOptions );
			if ( $parserOutput !== false ) {
				return $parserOutput;
			}
		}

		if ( $oldid === null || $oldid === 0 ) {
			$oldid = $this->getLatest();
		}

		$pool = new PoolWorkArticleView( $this, $parserOptions, $oldid, $useParserCache );
		$pool->execute();

		return $pool->getParserOutput();
	}

	/**
	 * Insert a new empty page record for this article.
	 * This *must* be followed up by creating a revision
	 * and running $this->updateRevisionOn( ... );
	 * or else the record will be left in a funky state.
	 * Best if all done inside a transaction.
	 *
	 * @param IDatabase $dbw
	 * @param int|null $pageId Custom page ID that will be used for the insert statement
	 *
	 * @return bool|int The newly created page_id key; false if the row was not
	 *   inserted, e.g. because the title already existed or because the specified
	 *   page ID is already in use.
	 */
	public function insertOn( $dbw, $pageId = null ) {
		$pageIdForInsert = $pageId ? [ 'page_id' => $pageId ] : [];
		$dbw->insert(
			'page',
			[
				'page_namespace'    => $this->mTitle->getNamespace(),
				'page_title'        => $this->mTitle->getDBkey(),
				'page_restrictions' => '',
				'page_is_redirect'  => 0, // Will set this shortly...
				'page_is_new'       => 1,
				'page_random'       => wfRandom(),
				'page_touched'      => $dbw->timestamp(),
				'page_latest'       => 0, // Fill this in shortly...
				'page_len'          => 0, // Fill this in shortly...
			] + $pageIdForInsert,
			__METHOD__,
			'IGNORE'
		);

		if ( $dbw->affectedRows() > 0 ) {
			$newid = $pageId ? (int)$pageId : $dbw->insertId();
			$this->mId = $newid;
			$this->mTitle->resetArticleID( $newid );

			return $newid;
		} else {
			return false; // nothing changed
		}
	}

	/**
	 * Update the page record to point to a newly saved revision.
	 *
	 * @param IDatabase $dbw
	 * @param Revision $revision For ID number, and text used to set
	 *   length and redirect status fields
	 * @param int $lastRevision If given, will not overwrite the page field
	 *   when different from the currently set value.
	 *   Giving 0 indicates the new page flag should be set on.
	 * @param bool $lastRevIsRedirect If given, will optimize adding and
	 *   removing rows in redirect table.
	 * @return bool Success; false if the page row was missing or page_latest changed
	 */
	public function updateRevisionOn( $dbw, $revision, $lastRevision = null,
		$lastRevIsRedirect = null
	) {
		global $wgContentHandlerUseDB;

		// Assertion to try to catch T92046
		if ( (int)$revision->getId() === 0 ) {
			throw new InvalidArgumentException(
				__METHOD__ . ': Revision has ID ' . var_export( $revision->getId(), 1 )
			);
		}

		$content = $revision->getContent();
		$len = $content ? $content->getSize() : 0;
		$rt = $content ? $content->getUltimateRedirectTarget() : null;

		$conditions = [ 'page_id' => $this->getId() ];

		if ( !is_null( $lastRevision ) ) {
			// An extra check against threads stepping on each other
			$conditions['page_latest'] = $lastRevision;
		}

		$revId = $revision->getId();
		Assert::parameter( $revId > 0, '$revision->getId()', 'must be > 0' );

		$row = [ /* SET */
			'page_latest'      => $revId,
			'page_touched'     => $dbw->timestamp( $revision->getTimestamp() ),
			'page_is_new'      => ( $lastRevision === 0 ) ? 1 : 0,
			'page_is_redirect' => $rt !== null ? 1 : 0,
			'page_len'         => $len,
		];

		if ( $wgContentHandlerUseDB ) {
			$row['page_content_model'] = $revision->getContentModel();
		}

		$dbw->update( 'page',
			$row,
			$conditions,
			__METHOD__ );

		$result = $dbw->affectedRows() > 0;
		if ( $result ) {
			$this->updateRedirectOn( $dbw, $rt, $lastRevIsRedirect );
			$this->setLastEdit( $revision );
			$this->mLatest = $revision->getId();
			$this->mIsRedirect = (bool)$rt;
			// Update the LinkCache.
			LinkCache::singleton()->addGoodLinkObj(
				$this->getId(),
				$this->mTitle,
				$len,
				$this->mIsRedirect,
				$this->mLatest,
				$revision->getContentModel()
			);
		}

		return $result;
	}

	/**
	 * Add row to the redirect table if this is a redirect, remove otherwise.
	 *
	 * @param IDatabase $dbw
	 * @param Title $redirectTitle Title object pointing to the redirect target,
	 *   or NULL if this is not a redirect
	 * @param null|bool $lastRevIsRedirect If given, will optimize adding and
	 *   removing rows in redirect table.
	 * @return bool True on success, false on failure
	 * @private
	 */
	public function updateRedirectOn( $dbw, $redirectTitle, $lastRevIsRedirect = null ) {
		// Always update redirects (target link might have changed)
		// Update/Insert if we don't know if the last revision was a redirect or not
		// Delete if changing from redirect to non-redirect
		$isRedirect = !is_null( $redirectTitle );

		if ( !$isRedirect && $lastRevIsRedirect === false ) {
			return true;
		}

		if ( $isRedirect ) {
			$this->insertRedirectEntry( $redirectTitle );
		} else {
			// This is not a redirect, remove row from redirect table
			$where = [ 'rd_from' => $this->getId() ];
			$dbw->delete( 'redirect', $where, __METHOD__ );
		}

		if ( $this->getTitle()->getNamespace() == NS_FILE ) {
			RepoGroup::singleton()->getLocalRepo()->invalidateImageRedirect( $this->getTitle() );
		}

		return ( $dbw->affectedRows() != 0 );
	}

	/**
	 * If the given revision is newer than the currently set page_latest,
	 * update the page record. Otherwise, do nothing.
	 *
	 * @deprecated since 1.24, use updateRevisionOn instead
	 *
	 * @param IDatabase $dbw
	 * @param Revision $revision
	 * @return bool
	 */
	public function updateIfNewerOn( $dbw, $revision ) {
		$row = $dbw->selectRow(
			[ 'revision', 'page' ],
			[ 'rev_id', 'rev_timestamp', 'page_is_redirect' ],
			[
				'page_id' => $this->getId(),
				'page_latest=rev_id' ],
			__METHOD__ );

		if ( $row ) {
			if ( wfTimestamp( TS_MW, $row->rev_timestamp ) >= $revision->getTimestamp() ) {
				return false;
			}
			$prev = $row->rev_id;
			$lastRevIsRedirect = (bool)$row->page_is_redirect;
		} else {
			// No or missing previous revision; mark the page as new
			$prev = 0;
			$lastRevIsRedirect = null;
		}

		$ret = $this->updateRevisionOn( $dbw, $revision, $prev, $lastRevIsRedirect );

		return $ret;
	}

	/**
	 * @param string|int|null|bool $sectionId Section identifier as a number or string
	 * (e.g. 0, 1 or 'T-1'), null/false or an empty string for the whole page
	 * or 'new' for a new section.
	 * @param Content $sectionContent New content of the section.
	 * @param string $sectionTitle New section's subject, only if $section is "new".
	 * @param string $edittime Revision timestamp or null to use the current revision.
	 *
	 * @throws MWException
	 * @return Content|null New complete article content, or null if error.
	 *
	 * @since 1.21
	 * @deprecated since 1.24, use replaceSectionAtRev instead
	 */
	public function replaceSectionContent(
		$sectionId, Content $sectionContent, $sectionTitle = '', $edittime = null
	) {
		$baseRevId = null;
		if ( $edittime && $sectionId !== 'new' ) {
			$dbr = wfGetDB( DB_REPLICA );
			$rev = Revision::loadFromTimestamp( $dbr, $this->mTitle, $edittime );
			// Try the master if this thread may have just added it.
			// This could be abstracted into a Revision method, but we don't want
			// to encourage loading of revisions by timestamp.
			if ( !$rev
				&& wfGetLB()->getServerCount() > 1
				&& wfGetLB()->hasOrMadeRecentMasterChanges()
			) {
				$dbw = wfGetDB( DB_MASTER );
				$rev = Revision::loadFromTimestamp( $dbw, $this->mTitle, $edittime );
			}
			if ( $rev ) {
				$baseRevId = $rev->getId();
			}
		}

		return $this->replaceSectionAtRev( $sectionId, $sectionContent, $sectionTitle, $baseRevId );
	}

	/**
	 * @param string|int|null|bool $sectionId Section identifier as a number or string
	 * (e.g. 0, 1 or 'T-1'), null/false or an empty string for the whole page
	 * or 'new' for a new section.
	 * @param Content $sectionContent New content of the section.
	 * @param string $sectionTitle New section's subject, only if $section is "new".
	 * @param int|null $baseRevId
	 *
	 * @throws MWException
	 * @return Content|null New complete article content, or null if error.
	 *
	 * @since 1.24
	 */
	public function replaceSectionAtRev( $sectionId, Content $sectionContent,
		$sectionTitle = '', $baseRevId = null
	) {
		if ( strval( $sectionId ) === '' ) {
			// Whole-page edit; let the whole text through
			$newContent = $sectionContent;
		} else {
			if ( !$this->supportsSections() ) {
				throw new MWException( "sections not supported for content model " .
					$this->getContentHandler()->getModelID() );
			}

			// T32711: always use current version when adding a new section
			if ( is_null( $baseRevId ) || $sectionId === 'new' ) {
				$oldContent = $this->getContent();
			} else {
				$rev = Revision::newFromId( $baseRevId );
				if ( !$rev ) {
					wfDebug( __METHOD__ . " asked for bogus section (page: " .
						$this->getId() . "; section: $sectionId)\n" );
					return null;
				}

				$oldContent = $rev->getContent();
			}

			if ( !$oldContent ) {
				wfDebug( __METHOD__ . ": no page text\n" );
				return null;
			}

			$newContent = $oldContent->replaceSection( $sectionId, $sectionContent, $sectionTitle );
		}

		return $newContent;
	}

	/**
	 * Check flags and add EDIT_NEW or EDIT_UPDATE to them as needed.
	 * @param int $flags
	 * @return int Updated $flags
	 */
	public function checkFlags( $flags ) {
		if ( !( $flags & EDIT_NEW ) && !( $flags & EDIT_UPDATE ) ) {
			if ( $this->exists() ) {
				$flags |= EDIT_UPDATE;
			} else {
				$flags |= EDIT_NEW;
			}
		}

		return $flags;
	}
	/**
	 * Change an existing article or create a new article. Updates RC and all necessary caches,
	 * optionally via the deferred update array.
	 *
	 * @param Content $content New content
	 * @param string $summary Edit summary
	 * @param int $flags Bitfield:
	 *      EDIT_NEW
	 *          Article is known or assumed to be non-existent, create a new one
	 *      EDIT_UPDATE
	 *          Article is known or assumed to be pre-existing, update it
	 *      EDIT_MINOR
	 *          Mark this edit minor, if the user is allowed to do so
	 *      EDIT_SUPPRESS_RC
	 *          Do not log the change in recentchanges
	 *      EDIT_FORCE_BOT
	 *          Mark the edit a "bot" edit regardless of user rights
	 *      EDIT_AUTOSUMMARY
	 *          Fill in blank summaries with generated text where possible
	 *      EDIT_INTERNAL
	 *          Signal that the page retrieve/save cycle happened entirely in this request.
	 *
	 * If neither EDIT_NEW nor EDIT_UPDATE is specified, the status of the
	 * article will be detected. If EDIT_UPDATE is specified and the article
	 * doesn't exist, the function will return an edit-gone-missing error. If
	 * EDIT_NEW is specified and the article does exist, an edit-already-exists
	 * error will be returned. These two conditions are also possible with
	 * auto-detection due to MediaWiki's performance-optimised locking strategy.
	 *
	 * @param bool|int $baseRevId The revision ID this edit was based off, if any.
	 *   This is not the parent revision ID, rather the revision ID for older
	 *   content used as the source for a rollback, for example.
	 * @param User $user The user doing the edit
	 * @param string $serialFormat Format for storing the content in the
	 *   database.
	 * @param array|null $tags Change tags to apply to this edit
	 * Callers are responsible for permission checks
	 * (with ChangeTags::canAddTagsAccompanyingChange)
	 * @param Int $undidRevId Id of revision that was undone or 0
	 *
	 * @throws MWException
	 * @return Status Possible errors:
	 *     edit-hook-aborted: The ArticleSave hook aborted the edit but didn't
	 *       set the fatal flag of $status.
	 *     edit-gone-missing: In update mode, but the article didn't exist.
	 *     edit-conflict: In update mode, the article changed unexpectedly.
	 *     edit-no-change: Warning that the text was the same as before.
	 *     edit-already-exists: In creation mode, but the article already exists.
	 *
	 *  Extensions may define additional errors.
	 *
	 *  $return->value will contain an associative array with members as follows:
	 *     new: Boolean indicating if the function attempted to create a new article.
	 *     revision: The revision object for the inserted revision, or null.
	 *
	 * @since 1.21
	 * @throws MWException
	 */
	public function doEditContent(
		Content $content, $summary, $flags = 0, $baseRevId = false,
		User $user = null, $serialFormat = null, $tags = [], $undidRevId = 0
	) {
		global $wgUser, $wgUseAutomaticEditSummaries;

		// Old default parameter for $tags was null
		if ( $tags === null ) {
			$tags = [];
		}

		// Low-level sanity check
		if ( $this->mTitle->getText() === '' ) {
			throw new MWException( 'Something is trying to edit an article with an empty title' );
		}
		// Make sure the given content type is allowed for this page
		if ( !$content->getContentHandler()->canBeUsedOn( $this->mTitle ) ) {
			return Status::newFatal( 'content-not-allowed-here',
				ContentHandler::getLocalizedName( $content->getModel() ),
				$this->mTitle->getPrefixedText()
			);
		}

		// Load the data from the master database if needed.
		// The caller may already loaded it from the master or even loaded it using
		// SELECT FOR UPDATE, so do not override that using clear().
		$this->loadPageData( 'fromdbmaster' );

		$user = $user ?: $wgUser;
		$flags = $this->checkFlags( $flags );

		// Avoid PHP 7.1 warning of passing $this by reference
		$wikiPage = $this;

		// Trigger pre-save hook (using provided edit summary)
		$hookStatus = Status::newGood( [] );
		$hook_args = [ &$wikiPage, &$user, &$content, &$summary,
			$flags & EDIT_MINOR, null, null, &$flags, &$hookStatus ];
		// Check if the hook rejected the attempted save
		if ( !Hooks::run( 'PageContentSave', $hook_args ) ) {
			if ( $hookStatus->isOK() ) {
				// Hook returned false but didn't call fatal(); use generic message
				$hookStatus->fatal( 'edit-hook-aborted' );
			}

			return $hookStatus;
		}

		$old_revision = $this->getRevision(); // current revision
		$old_content = $this->getContent( Revision::RAW ); // current revision's content

		if ( $old_content && $old_content->getModel() !== $content->getModel() ) {
			$tags[] = 'mw-contentmodelchange';
		}

		// Provide autosummaries if one is not provided and autosummaries are enabled
		if ( $wgUseAutomaticEditSummaries && ( $flags & EDIT_AUTOSUMMARY ) && $summary == '' ) {
			$handler = $content->getContentHandler();
			$summary = $handler->getAutosummary( $old_content, $content, $flags );
		}

		// Avoid statsd noise and wasted cycles check the edit stash (T136678)
		if ( ( $flags & EDIT_INTERNAL ) || ( $flags & EDIT_FORCE_BOT ) ) {
			$useCache = false;
		} else {
			$useCache = true;
		}

		// Get the pre-save transform content and final parser output
		$editInfo = $this->prepareContentForEdit( $content, null, $user, $serialFormat, $useCache );
		$pstContent = $editInfo->pstContent; // Content object
		$meta = [
			'bot' => ( $flags & EDIT_FORCE_BOT ),
			'minor' => ( $flags & EDIT_MINOR ) && $user->isAllowed( 'minoredit' ),
			'serialized' => $pstContent->serialize( $serialFormat ),
			'serialFormat' => $serialFormat,
			'baseRevId' => $baseRevId,
			'oldRevision' => $old_revision,
			'oldContent' => $old_content,
			'oldId' => $this->getLatest(),
			'oldIsRedirect' => $this->isRedirect(),
			'oldCountable' => $this->isCountable(),
			'tags' => ( $tags !== null ) ? (array)$tags : [],
			'undidRevId' => $undidRevId
		];

		// Actually create the revision and create/update the page
		if ( $flags & EDIT_UPDATE ) {
			$status = $this->doModify( $pstContent, $flags, $user, $summary, $meta );
		} else {
			$status = $this->doCreate( $pstContent, $flags, $user, $summary, $meta );
		}

		// Promote user to any groups they meet the criteria for
		DeferredUpdates::addCallableUpdate( function () use ( $user ) {
			$user->addAutopromoteOnceGroups( 'onEdit' );
			$user->addAutopromoteOnceGroups( 'onView' ); // b/c
		} );

		return $status;
	}

	/**
	 * @param Content $content Pre-save transform content
	 * @param int $flags
	 * @param User $user
	 * @param string $summary
	 * @param array $meta
	 * @return Status
	 * @throws DBUnexpectedError
	 * @throws Exception
	 * @throws FatalError
	 * @throws MWException
	 */
	private function doModify(
		Content $content, $flags, User $user, $summary, array $meta
	) {
		global $wgUseRCPatrol;

		// Update article, but only if changed.
		$status = Status::newGood( [ 'new' => false, 'revision' => null ] );

		// Convenience variables
		$now = wfTimestampNow();
		$oldid = $meta['oldId'];
		/** @var Content|null $oldContent */
		$oldContent = $meta['oldContent'];
		$newsize = $content->getSize();

		if ( !$oldid ) {
			// Article gone missing
			$status->fatal( 'edit-gone-missing' );

			return $status;
		} elseif ( !$oldContent ) {
			// Sanity check for T39225
			throw new MWException( "Could not find text for current revision {$oldid}." );
		}

		$changed = !$content->equals( $oldContent );

		$dbw = wfGetDB( DB_MASTER );

		if ( $changed ) {
			// @TODO: pass content object?!
			$revision = new Revision( [
				'page'       => $this->getId(),
				'title'      => $this->mTitle, // for determining the default content model
				'comment'    => $summary,
				'minor_edit' => $meta['minor'],
				'text'       => $meta['serialized'],
				'len'        => $newsize,
				'parent_id'  => $oldid,
				'user'       => $user->getId(),
				'user_text'  => $user->getName(),
				'timestamp'  => $now,
				'content_model' => $content->getModel(),
				'content_format' => $meta['serialFormat'],
			] );

			$prepStatus = $content->prepareSave( $this, $flags, $oldid, $user );
			$status->merge( $prepStatus );
			if ( !$status->isOK() ) {
				return $status;
			}

			$dbw->startAtomic( __METHOD__ );
			// Get the latest page_latest value while locking it.
			// Do a CAS style check to see if it's the same as when this method
			// started. If it changed then bail out before touching the DB.
			$latestNow = $this->lockAndGetLatest();
			if ( $latestNow != $oldid ) {
				$dbw->endAtomic( __METHOD__ );
				// Page updated or deleted in the mean time
				$status->fatal( 'edit-conflict' );

				return $status;
			}

			// At this point we are now comitted to returning an OK
			// status unless some DB query error or other exception comes up.
			// This way callers don't have to call rollback() if $status is bad
			// unless they actually try to catch exceptions (which is rare).

			// Save the revision text
			$revisionId = $revision->insertOn( $dbw );
			// Update page_latest and friends to reflect the new revision
			if ( !$this->updateRevisionOn( $dbw, $revision, null, $meta['oldIsRedirect'] ) ) {
				throw new MWException( "Failed to update page row to use new revision." );
			}

			Hooks::run( 'NewRevisionFromEditComplete',
				[ $this, $revision, $meta['baseRevId'], $user ] );

			// Update recentchanges
			if ( !( $flags & EDIT_SUPPRESS_RC ) ) {
				// Mark as patrolled if the user can do so
				$patrolled = $wgUseRCPatrol && !count(
						$this->mTitle->getUserPermissionsErrors( 'autopatrol', $user ) );
				// Add RC row to the DB
				RecentChange::notifyEdit(
					$now,
					$this->mTitle,
					$revision->isMinor(),
					$user,
					$summary,
					$oldid,
					$this->getTimestamp(),
					$meta['bot'],
					'',
					$oldContent ? $oldContent->getSize() : 0,
					$newsize,
					$revisionId,
					$patrolled,
					$meta['tags']
				);
			}

			$user->incEditCount();

			$dbw->endAtomic( __METHOD__ );
			$this->mTimestamp = $now;
		} else {
			// T34948: revision ID must be set to page {{REVISIONID}} and
			// related variables correctly. Likewise for {{REVISIONUSER}} (T135261).
			// Since we don't insert a new revision into the database, the least
			// error-prone way is to reuse given old revision.
			$revision = $meta['oldRevision'];
		}

		if ( $changed ) {
			// Return the new revision to the caller
			$status->value['revision'] = $revision;
		} else {
			$status->warning( 'edit-no-change' );
			// Update page_touched as updateRevisionOn() was not called.
			// Other cache updates are managed in onArticleEdit() via doEditUpdates().
			$this->mTitle->invalidateCache( $now );
		}

		// Do secondary updates once the main changes have been committed...
		DeferredUpdates::addUpdate(
			new AtomicSectionUpdate(
				$dbw,
				__METHOD__,
				function () use (
					$revision, &$user, $content, $summary, &$flags,
					$changed, $meta, &$status
				) {
					// Update links tables, site stats, etc.
					$this->doEditUpdates(
						$revision,
						$user,
						[
							'changed' => $changed,
							'oldcountable' => $meta['oldCountable'],
							'oldrevision' => $meta['oldRevision']
						]
					);
					// Avoid PHP 7.1 warning of passing $this by reference
					$wikiPage = $this;
					// Trigger post-save hook
					$params = [ &$wikiPage, &$user, $content, $summary, $flags & EDIT_MINOR,
						null, null, &$flags, $revision, &$status, $meta['baseRevId'],
						$meta['undidRevId'] ];
					Hooks::run( 'PageContentSaveComplete', $params );
				}
			),
			DeferredUpdates::PRESEND
		);

		return $status;
	}

	/**
	 * @param Content $content Pre-save transform content
	 * @param int $flags
	 * @param User $user
	 * @param string $summary
	 * @param array $meta
	 * @return Status
	 * @throws DBUnexpectedError
	 * @throws Exception
	 * @throws FatalError
	 * @throws MWException
	 */
	private function doCreate(
		Content $content, $flags, User $user, $summary, array $meta
	) {
		global $wgUseRCPatrol, $wgUseNPPatrol;

		$status = Status::newGood( [ 'new' => true, 'revision' => null ] );

		$now = wfTimestampNow();
		$newsize = $content->getSize();
		$prepStatus = $content->prepareSave( $this, $flags, $meta['oldId'], $user );
		$status->merge( $prepStatus );
		if ( !$status->isOK() ) {
			return $status;
		}

		$dbw = wfGetDB( DB_MASTER );
		$dbw->startAtomic( __METHOD__ );

		// Add the page record unless one already exists for the title
		$newid = $this->insertOn( $dbw );
		if ( $newid === false ) {
			$dbw->endAtomic( __METHOD__ ); // nothing inserted
			$status->fatal( 'edit-already-exists' );

			return $status; // nothing done
		}

		// At this point we are now comitted to returning an OK
		// status unless some DB query error or other exception comes up.
		// This way callers don't have to call rollback() if $status is bad
		// unless they actually try to catch exceptions (which is rare).

		// @TODO: pass content object?!
		$revision = new Revision( [
			'page'       => $newid,
			'title'      => $this->mTitle, // for determining the default content model
			'comment'    => $summary,
			'minor_edit' => $meta['minor'],
			'text'       => $meta['serialized'],
			'len'        => $newsize,
			'user'       => $user->getId(),
			'user_text'  => $user->getName(),
			'timestamp'  => $now,
			'content_model' => $content->getModel(),
			'content_format' => $meta['serialFormat'],
		] );

		// Save the revision text...
		$revisionId = $revision->insertOn( $dbw );
		// Update the page record with revision data
		if ( !$this->updateRevisionOn( $dbw, $revision, 0 ) ) {
			throw new MWException( "Failed to update page row to use new revision." );
		}

		Hooks::run( 'NewRevisionFromEditComplete', [ $this, $revision, false, $user ] );

		// Update recentchanges
		if ( !( $flags & EDIT_SUPPRESS_RC ) ) {
			// Mark as patrolled if the user can do so
			$patrolled = ( $wgUseRCPatrol || $wgUseNPPatrol ) &&
				!count( $this->mTitle->getUserPermissionsErrors( 'autopatrol', $user ) );
			// Add RC row to the DB
			RecentChange::notifyNew(
				$now,
				$this->mTitle,
				$revision->isMinor(),
				$user,
				$summary,
				$meta['bot'],
				'',
				$newsize,
				$revisionId,
				$patrolled,
				$meta['tags']
			);
		}

		$user->incEditCount();

		$dbw->endAtomic( __METHOD__ );
		$this->mTimestamp = $now;

		// Return the new revision to the caller
		$status->value['revision'] = $revision;

		// Do secondary updates once the main changes have been committed...
		DeferredUpdates::addUpdate(
			new AtomicSectionUpdate(
				$dbw,
				__METHOD__,
				function () use (
					$revision, &$user, $content, $summary, &$flags, $meta, &$status
				) {
					// Update links, etc.
					$this->doEditUpdates( $revision, $user, [ 'created' => true ] );
					// Avoid PHP 7.1 warning of passing $this by reference
					$wikiPage = $this;
					// Trigger post-create hook
					$params = [ &$wikiPage, &$user, $content, $summary,
						$flags & EDIT_MINOR, null, null, &$flags, $revision ];
					Hooks::run( 'PageContentInsertComplete', $params );
					// Trigger post-save hook
					$params = array_merge( $params, [ &$status, $meta['baseRevId'], 0 ] );
					Hooks::run( 'PageContentSaveComplete', $params );
				}
			),
			DeferredUpdates::PRESEND
		);

		return $status;
	}

	/**
	 * Get parser options suitable for rendering the primary article wikitext
	 *
	 * @see ContentHandler::makeParserOptions
	 *
	 * @param IContextSource|User|string $context One of the following:
	 *        - IContextSource: Use the User and the Language of the provided
	 *          context
	 *        - User: Use the provided User object and $wgLang for the language,
	 *          so use an IContextSource object if possible.
	 *        - 'canonical': Canonical options (anonymous user with default
	 *          preferences and content language).
	 * @return ParserOptions
	 */
	public function makeParserOptions( $context ) {
		$options = $this->getContentHandler()->makeParserOptions( $context );

		if ( $this->getTitle()->isConversionTable() ) {
			// @todo ConversionTable should become a separate content model, so
			// we don't need special cases like this one.
			$options->disableContentConversion();
		}

		return $options;
	}

	/**
	 * Prepare content which is about to be saved.
	 *
	 * Prior to 1.30, this returned a stdClass object with the same class
	 * members.
	 *
	 * @param Content $content
	 * @param Revision|int|null $revision Revision object. For backwards compatibility, a
	 *        revision ID is also accepted, but this is deprecated.
	 * @param User|null $user
	 * @param string|null $serialFormat
	 * @param bool $useCache Check shared prepared edit cache
	 *
	 * @return PreparedEdit
	 *
	 * @since 1.21
	 */
	public function prepareContentForEdit(
		Content $content, $revision = null, User $user = null,
		$serialFormat = null, $useCache = true
	) {
		global $wgContLang, $wgUser, $wgAjaxEditStash;

		if ( is_object( $revision ) ) {
			$revid = $revision->getId();
		} else {
			$revid = $revision;
			// This code path is deprecated, and nothing is known to
			// use it, so performance here shouldn't be a worry.
			if ( $revid !== null ) {
				wfDeprecated( __METHOD__ . ' with $revision = revision ID', '1.25' );
				$revision = Revision::newFromId( $revid, Revision::READ_LATEST );
			} else {
				$revision = null;
			}
		}

		$user = is_null( $user ) ? $wgUser : $user;
		// XXX: check $user->getId() here???

		// Use a sane default for $serialFormat, see T59026
		if ( $serialFormat === null ) {
			$serialFormat = $content->getContentHandler()->getDefaultFormat();
		}

		if ( $this->mPreparedEdit
			&& isset( $this->mPreparedEdit->newContent )
			&& $this->mPreparedEdit->newContent->equals( $content )
			&& $this->mPreparedEdit->revid == $revid
			&& $this->mPreparedEdit->format == $serialFormat
			// XXX: also check $user here?
		) {
			// Already prepared
			return $this->mPreparedEdit;
		}

		// The edit may have already been prepared via api.php?action=stashedit
		$cachedEdit = $useCache && $wgAjaxEditStash
			? ApiStashEdit::checkCache( $this->getTitle(), $content, $user )
			: false;

		$popts = ParserOptions::newFromUserAndLang( $user, $wgContLang );
		Hooks::run( 'ArticlePrepareTextForEdit', [ $this, $popts ] );

		$edit = new PreparedEdit();
		if ( $cachedEdit ) {
			$edit->timestamp = $cachedEdit->timestamp;
		} else {
			$edit->timestamp = wfTimestampNow();
		}
		// @note: $cachedEdit is safely not used if the rev ID was referenced in the text
		$edit->revid = $revid;

		if ( $cachedEdit ) {
			$edit->pstContent = $cachedEdit->pstContent;
		} else {
			$edit->pstContent = $content
				? $content->preSaveTransform( $this->mTitle, $user, $popts )
				: null;
		}

		$edit->format = $serialFormat;
		$edit->popts = $this->makeParserOptions( 'canonical' );
		if ( $cachedEdit ) {
			$edit->output = $cachedEdit->output;
		} else {
			if ( $revision ) {
				// We get here if vary-revision is set. This means that this page references
				// itself (such as via self-transclusion). In this case, we need to make sure
				// that any such self-references refer to the newly-saved revision, and not
				// to the previous one, which could otherwise happen due to replica DB lag.
				$oldCallback = $edit->popts->getCurrentRevisionCallback();
				$edit->popts->setCurrentRevisionCallback(
					function ( Title $title, $parser = false ) use ( $revision, &$oldCallback ) {
						if ( $title->equals( $revision->getTitle() ) ) {
							return $revision;
						} else {
							return call_user_func( $oldCallback, $title, $parser );
						}
					}
				);
			} else {
				// Try to avoid a second parse if {{REVISIONID}} is used
				$dbIndex = ( $this->mDataLoadedFrom & self::READ_LATEST ) === self::READ_LATEST
					? DB_MASTER // use the best possible guess
					: DB_REPLICA; // T154554

				$edit->popts->setSpeculativeRevIdCallback( function () use ( $dbIndex ) {
					return 1 + (int)wfGetDB( $dbIndex )->selectField(
						'revision',
						'MAX(rev_id)',
						[],
						__METHOD__
					);
				} );
			}
			$edit->output = $edit->pstContent
				? $edit->pstContent->getParserOutput( $this->mTitle, $revid, $edit->popts )
				: null;
		}

		$edit->newContent = $content;
		$edit->oldContent = $this->getContent( Revision::RAW );

		// NOTE: B/C for hooks! don't use these fields!
		$edit->newText = $edit->newContent
			? ContentHandler::getContentText( $edit->newContent )
			: '';
		$edit->oldText = $edit->oldContent
			? ContentHandler::getContentText( $edit->oldContent )
			: '';
		$edit->pst = $edit->pstContent ? $edit->pstContent->serialize( $serialFormat ) : '';

		if ( $edit->output ) {
			$edit->output->setCacheTime( wfTimestampNow() );
		}

		// Process cache the result
		$this->mPreparedEdit = $edit;

		return $edit;
	}
	/**
	 * Do standard deferred updates after page edit.
	 * Update links tables, site stats, search index and message cache.
	 * Purges pages that include this page if the text was changed here.
	 * Every 100th edit, prune the recent changes table.
	 *
	 * @param Revision $revision
	 * @param User $user User object that did the revision
	 * @param array $options Array of options, following indexes are used:
	 * - changed: bool, whether the revision changed the content (default true)
	 * - created: bool, whether the revision created the page (default false)
	 * - moved: bool, whether the page was moved (default false)
	 * - restored: bool, whether the page was undeleted (default false)
	 * - oldrevision: Revision object for the pre-update revision (default null)
	 * - oldcountable: bool, null, or string 'no-change' (default null):
	 *   - bool: whether the page was counted as an article before that
	 *     revision, only used in changed is true and created is false
	 *   - null: if created is false, don't update the article count; if created
	 *     is true, do update the article count
	 *   - 'no-change': don't update the article count, ever
	 */
	public function doEditUpdates( Revision $revision, User $user, array $options = [] ) {
		global $wgRCWatchCategoryMembership;

		$options += [
			'changed' => true,
			'created' => false,
			'moved' => false,
			'restored' => false,
			'oldrevision' => null,
			'oldcountable' => null
		];
		$content = $revision->getContent();

		$logger = LoggerFactory::getInstance( 'SaveParse' );

		// See if the parser output before $revision was inserted is still valid
		$editInfo = false;
		if ( !$this->mPreparedEdit ) {
			$logger->debug( __METHOD__ . ": No prepared edit...\n" );
		} elseif ( $this->mPreparedEdit->output->getFlag( 'vary-revision' ) ) {
			$logger->info( __METHOD__ . ": Prepared edit has vary-revision...\n" );
		} elseif ( $this->mPreparedEdit->output->getFlag( 'vary-revision-id' )
			&& $this->mPreparedEdit->output->getSpeculativeRevIdUsed() !== $revision->getId()
		) {
			$logger->info( __METHOD__ . ": Prepared edit has vary-revision-id with wrong ID...\n" );
		} elseif ( $this->mPreparedEdit->output->getFlag( 'vary-user' ) && !$options['changed'] ) {
			$logger->info( __METHOD__ . ": Prepared edit has vary-user and is null...\n" );
		} else {
			wfDebug( __METHOD__ . ": Using prepared edit...\n" );
			$editInfo = $this->mPreparedEdit;
		}

		if ( !$editInfo ) {
			// Parse the text again if needed. Be careful not to do pre-save transform twice:
			// $text is usually already pre-save transformed once. Avoid using the edit stash
			// as any prepared content from there or in doEditContent() was already rejected.
			$editInfo = $this->prepareContentForEdit( $content, $revision, $user, null, false );
		}

		// Save it to the parser cache.
		// Make sure the cache time matches page_touched to avoid double parsing.
		MediaWikiServices::getInstance()->getParserCache()->save(
			$editInfo->output, $this, $editInfo->popts,
			$revision->getTimestamp(), $editInfo->revid
		);

		// Update the links tables and other secondary data
		if ( $content ) {
			$recursive = $options['changed']; // T52785
			$updates = $content->getSecondaryDataUpdates(
				$this->getTitle(), null, $recursive, $editInfo->output
			);
			foreach ( $updates as $update ) {
				$update->setCause( 'edit-page', $user->getName() );
				if ( $update instanceof LinksUpdate ) {
					$update->setRevision( $revision );
					$update->setTriggeringUser( $user );
				}
				DeferredUpdates::addUpdate( $update );
			}
			if ( $wgRCWatchCategoryMembership
				&& $this->getContentHandler()->supportsCategories() === true
				&& ( $options['changed'] || $options['created'] )
				&& !$options['restored']
			) {
				// Note: jobs are pushed after deferred updates, so the job should be able to see
				// the recent change entry (also done via deferred updates) and carry over any
				// bot/deletion/IP flags, ect.
				JobQueueGroup::singleton()->lazyPush( new CategoryMembershipChangeJob(
					$this->getTitle(),
					[
						'pageId' => $this->getId(),
						'revTimestamp' => $revision->getTimestamp()
					]
				) );
			}
		}

		// Avoid PHP 7.1 warning of passing $this by reference
		$wikiPage = $this;

		Hooks::run( 'ArticleEditUpdates', [ &$wikiPage, &$editInfo, $options['changed'] ] );

		if ( Hooks::run( 'ArticleEditUpdatesDeleteFromRecentchanges', [ &$wikiPage ] ) ) {
			// Flush old entries from the `recentchanges` table
			if ( mt_rand( 0, 9 ) == 0 ) {
				JobQueueGroup::singleton()->lazyPush( RecentChangesUpdateJob::newPurgeJob() );
			}
		}

		if ( !$this->exists() ) {
			return;
		}

		$id = $this->getId();
		$title = $this->mTitle->getPrefixedDBkey();
		$shortTitle = $this->mTitle->getDBkey();

		if ( $options['oldcountable'] === 'no-change' ||
			( !$options['changed'] && !$options['moved'] )
		) {
			$good = 0;
		} elseif ( $options['created'] ) {
			$good = (int)$this->isCountable( $editInfo );
		} elseif ( $options['oldcountable'] !== null ) {
			$good = (int)$this->isCountable( $editInfo ) - (int)$options['oldcountable'];
		} else {
			$good = 0;
		}
		$edits = $options['changed'] ? 1 : 0;
		$total = $options['created'] ? 1 : 0;

		DeferredUpdates::addUpdate( new SiteStatsUpdate( 0, $edits, $good, $total ) );
		DeferredUpdates::addUpdate( new SearchUpdate( $id, $title, $content ) );

		// If this is another user's talk page, update newtalk.
		// Don't do this if $options['changed'] = false (null-edits) nor if
		// it's a minor edit and the user doesn't want notifications for those.
		if ( $options['changed']
			&& $this->mTitle->getNamespace() == NS_USER_TALK
			&& $shortTitle != $user->getTitleKey()
			&& !( $revision->isMinor() && $user->isAllowed( 'nominornewtalk' ) )
		) {
			$recipient = User::newFromName( $shortTitle, false );
			if ( !$recipient ) {
				wfDebug( __METHOD__ . ": invalid username\n" );
			} else {
				// Avoid PHP 7.1 warning of passing $this by reference
				$wikiPage = $this;

				// Allow extensions to prevent user notification
				// when a new message is added to their talk page
				if ( Hooks::run( 'ArticleEditUpdateNewTalk', [ &$wikiPage, $recipient ] ) ) {
					if ( User::isIP( $shortTitle ) ) {
						// An anonymous user
						$recipient->setNewtalk( true, $revision );
					} elseif ( $recipient->isLoggedIn() ) {
						$recipient->setNewtalk( true, $revision );
					} else {
						wfDebug( __METHOD__ . ": don't need to notify a nonexistent user\n" );
					}
				}
			}
		}

		if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
			MessageCache::singleton()->updateMessageOverride( $this->mTitle, $content );
		}

		if ( $options['created'] ) {
			self::onArticleCreate( $this->mTitle );
		} elseif ( $options['changed'] ) { // T52785
			self::onArticleEdit( $this->mTitle, $revision );
		}

		ResourceLoaderWikiModule::invalidateModuleCache(
			$this->mTitle, $options['oldrevision'], $revision, wfWikiID()
		);
	}

}
