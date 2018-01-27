<?php
/**
 * Content object for wiki text pages.
 *
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
 *
 * @author Daniel Kinzler
 */

namespace MediaWiki\Storage;

use ApiStashEdit;
use CategoryMembershipChangeJob;
use Content;
use ContentHandler;
use DataUpdate;
use DeferredUpdates;
use Hooks;
use IDBAccessObject;
use InvalidArgumentException;
use JobQueueGroup;
use Language;
use LinksUpdate;
use LogicException;
use MediaWiki\Edit\PreparedEdit;
use MessageCache;
use MWException;
use ParserCache;
use ParserOptions;
use ParserOutput;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RecentChangesUpdateJob;
use ResourceLoaderWikiModule;
use Revision;
use RuntimeException;
use SearchUpdate;
use SiteStatsUpdate;
use Title;
use User;
use Wikimedia\Rdbms\LoadBalancer;
use WikiPage;

/**
 * A handle for managing updates for page meta-data on edit, import, purge, etc.
 *
 * MCR migration note: this replaces the relevant methods in WikiPage, and covers the use cases
 * of PreparedEdit.
 *
 * @since 1.31
 * @ingroup Page
 */
class PageMetaDataUpdater implements IDBAccessObject {

	/**
	 * @var User
	 */
	private $user;

	/**
	 * @var WikiPage
	 */
	private $wikiPage;

	/**
	 * @var ParserCache
	 */
	private $parserCache;

	/**
	 * @var LoadBalancer
	 */
	private $loadBalancer;

	/**
	 * @var RevisionStore
	 */
	private $revisionStore;

	/**
	 * @var Language
	 */
	private $contentLanguage;

	/**
	 * @var LoggerInterface
	 */
	private $saveParseLogger;

	/**
	 * @var JobQueueGroup
	 */
	private $jobQueueGroup;

	/**
	 * @var MessageCache
	 */
	private $messageCache;

	/**
	 * @var boolean see $wgAjaxEditStash
	 */
	private $ajaxEditStash = true;

	/**
	 * @var string see $wgArticleCountMethod
	 */
	private $articleCountMethod;

	/**
	 * @var boolean see $wgRCWatchCategoryMembership
	 */
	private $rcWatchCategoryMembership = false;

	/**
	 * FIXME doc!
	 */
	private $options = [
		'changed' => true,
		'created' => false,
		'moved' => false,
		'restored' => false,
		'oldrevision' => null, // FIXME: use $oldPageState instead
		'oldcountable' => null // FIXME: use $oldPageState instead
	];

	/**
	 * @var array
	 */
	private $oldPageState = null;

	/**
	 * @var MutableRevisionSlots|null
	 */
	private $pstContentSlots = null;

	/**
	 * @var object[] anonymous objects with two fields, using slot roles as keys:
	 *  - hasHtml: whether the output contains HTML
	 *  - ParserOutput: the slot's parser output
	 */
	private $slotsOutput = [];

	/**
	 * @var ParserOutput|null
	 */
	private $canonicalParserOutput = null;

	/**
	 * @var ParserOptions|null
	 */
	private $canonicalParserOptions = null;

	/**
	 * @var RevisionRecord
	 */
	private $revision = null;

	/**
	 * @param User $user
	 * @param WikiPage $wikiPage ,
	 * @param LoadBalancer $loadBalancer
	 * @param RevisionStore $revisionStore
	 * @param ParserCache $parserCache
	 * @param JobQueueGroup $jobQueueGroup
	 * @param MessageCache $messageCache
	 * @param Language $contentLanguage
	 * @param LoggerInterface $saveParseLogger
	 */
	public function __construct(
		User $user,
		WikiPage $wikiPage,
		LoadBalancer $loadBalancer,
		RevisionStore $revisionStore,
		ParserCache $parserCache,
		JobQueueGroup $jobQueueGroup,
		MessageCache $messageCache,
		Language $contentLanguage,
		LoggerInterface $saveParseLogger = null
	) {
		$this->user = $user;
		$this->wikiPage = $wikiPage;

		$this->loadBalancer = $loadBalancer;
		$this->parserCache = $parserCache;
		$this->revisionStore = $revisionStore;
		$this->jobQueueGroup = $jobQueueGroup;
		$this->messageCache = $messageCache;
		$this->contentLanguage = $contentLanguage;

		// XXX: replace all wfDebug calls with a Logger. Do we nede more than one logger here?
		$this->saveParseLogger = $saveParseLogger ?: new NullLogger();

		$this->newContentSlots = new MutableRevisionSlots();
	}

	public function canReuse( User $user, $revision ) {
		// FIXME...
		// TODO: MCR: add inherited slots from base revision to $newContentSlots!
		$sig = PreparedEdit::makeSignature( $this->getTitle(),
		                                    $newContentSlots,
		                                    $user,
		                                    $sigMod
		);

	}

	/**
	 * @param string $articleCountMethod
	 */
	public function setArticleCountMethod( $articleCountMethod ) {
		$this->articleCountMethod = $articleCountMethod;
	}

	/**
	 * @param bool $rcWatchCategoryMembership
	 */
	public function setRcWatchCategoryMembership( $rcWatchCategoryMembership ) {
		$this->rcWatchCategoryMembership = $rcWatchCategoryMembership;
	}

	/**
	 * @return Title
	 */
	private function getTitle() {
		// NOTE: eventually, we won't get a WikiPage passed into the constructor any more
		return $this->wikiPage->getTitle();
	}

	/**
	 * @return WikiPage
	 */
	private function getWikiPage() {
		// NOTE: eventually, we won't get a WikiPage passed into the constructor any more
		return $this->wikiPage;
	}

	/**
	 * Determines whether the page being edited already exists.
	 * Only defined after calling grabParentRevision()!
	 *
	 * @return boolean
	 * @throws LogicException if called before grabParentRevision() was called.
	 */
	private function pageExists() {
		if ( !$this->hasPageState() ) {
			throw new LogicException( 'Existence is not known before grabParentRevision() has been called' );
		}

		return $this->oldPageState['oldId'] > 0;
	}

	/**
	 * Returns the immediate parent revision of the edit, which is the current revision of the page
	 * at the time when grabParentRevision() was called. Not to be confused with the logical
	 * base revision. The base revision is specified by the client, the parent revision is
	 * determined by grabParentRevision(). If base revision and parent revision are not the same,
	 * the edit is considered to require edit conflict resolution.
	 *
	 * Application may use this method (after calling grabParentRevision()) to perform edit conflict
	 * resolution by performing a 3-way merge, using the revision returned by this method as
	 * the conflicting revision and the revision indicated by getBaseRevisionId() as the
	 * common base.
	 *
	 * @note The parent revision effective for this PageUpdater is fixed the the page's current
	 * revision by the first call to this method on a given PageUpdater instance.
	 * It acts as a check-and-set (CAS) token in that it is guaranteed that commitEdit() will fail
	 * with the edit-conflict status if the current revision of the page changes after
	 * grabParentRevision() was called and before commitEdit() could insert a new revision.
	 *
	 * @return RevisionRecord|null the parent revision, or null of the page does not yet exist.
	 */
	public function grabParentRevision() {
		if ( $this->hasPageState() ) {
			return $this->oldPageState['oldRevision'];
		}

		// NOTE: eventually, we won't get a WikiPage passed into the constructor any more
		$wikiPage = $this->getWikiPage();

		// The caller may already loaded it from the master or even loaded it using
		// SELECT FOR UPDATE, so do not override that using clear().
		$wikiPage->loadPageData( self::READ_LATEST );
		$rev = $wikiPage->getRevision();
		$parent = $rev ? $rev->getRevisionRecord() : null;

		$this->oldPageState = [
			'oldRevision' => $parent,
			'oldId' => $rev ? $rev->getId() : 0,
			'oldIsRedirect' => $wikiPage->isRedirect(),
			'oldCountable' => $wikiPage->isCountable(),
		];

		return $this->oldPageState['oldRevision'];
	}

	private function hasPageState() {
		return $this->oldPageState !== null;
	}

	/**
	 * @return int
	 */
	private function getPageId() {
		// NOTE: eventually, we won't get a WikiPage passed into the constructor any more
		return $this->wikiPage->getId();
	}

	/**
	 * @return string
	 */
	private function getTimestampNow() {
		// TODO: allow an override to be injected for testing
		return wfTimestampNow();
	}

	/**
	 * Returns the slot, new or inherited, with no audience checks applied.
	 *
	 * @param string $role slot role name
	 * @return SlotRecord
	 *
	 * @throws PageUpdateException If the slot is neither set for update nor inherited from the
	 *         parent revision.
	 */
	private function getRawSlot( $role ) {
		if ( $this->newContentSlots->hasSlot( $role ) ) {
			return $this->newContentSlots->getSlot( $role );
		}

		$parent = $this->grabParentRevision();

		if ( $parent && $parent->hasSlot( $role ) ) {
			return $parent->getSlot( $role, RevisionRecord::RAW );
		}

		throw new PageUpdateException( 'Slot not set for update and not inherited: ' . $role );
	}

	/**
	 * Returns the content of the given slot of the parent revision, with no audience checks applied.
	 * If there is no parent revision or the slot is not defined, this returns null.
	 *
	 * @param string $role slot role name
	 * @return Content
	 */
	private function getParentContent( $role ) {
		$parent = $this->grabParentRevision();

		if ( $parent && $parent->hasSlot( $role ) ) {
			return $parent->getContent( $role, RevisionRecord::RAW );
		}

		return null;
	}

	private function useMaster() {
		// TODO: can we just set a flag to true in prepareEdit()?
		return $this->wikiPage->wasLoadedFrom( self::READ_LATEST );
	}

	/**
	 * @param PreparedEdit $editInfo
	 *
	 * @return bool
	 */
	private function isCountable() {
		if ( $this->articleCountMethod  === 'any' ) {
			return true;
		}

		// NOTE: main slot determines redirect status
		$mainContent = $this->getPublicContent( 'main' );

		if ( !$mainContent || $mainContent->isRedirect() ) {
			return false;
		}

		$hasLinks = null;

		if ( $this->articleCountMethod  === 'link' ) {
			$hasLinks = (bool)count( $this->getCanonicalParserOutput()->getLinks() );
		}

		// TODO: MCR: ask all slots, based on whether *they* have links.
		return $mainContent->isCountable( $hasLinks );
	}

	/**
	 * Returns the content model of the given slot
	 *
	 * @param string $role slot role name
	 * @return string
	 */
	private function getContentModel( $role ) {
		return $this->getRawSlot( $role )->getModel();
	}

	/**
	 * @param string $role slot role name
	 * @return ContentHandler
	 */
	private function getContentHandler( $role ) {
		// TODO: inject something like a ContentHandlerRegistry
		return ContentHandler::getForModelID( $this->getContentModel( $role ) );
	}

	/**
	 * Prepare content which is about to be saved.
	 *
	 * MCR migration note: this replaces WikiPage::prepareContentForEdit.
	 *
	 * @param RevisionSlots $newContentSlots The new content of the slots to be updated
	 *        by this edit. Typically given before PST. If PST has already been applied,
	 *        $options['do-transform'] must be set to false to avoid double transformation.
	 *
	 * @throws MWException
	 */
	public function prepareEdit( RevisionSlots $newContentSlots ) {
		if ( $this->revision ) {
			throw new LogicException( 'Can\'t call prepareEdit() after prepareUpdate() has been called' );
		}

		if ( $this->pstContentSlots ) {
			throw new LogicException( 'Can\'t call prepareEdit() more than once!' );
		}

		$wikiPage = $this->getWikiPage(); // TODO: use only for legacy hooks!
		$title = $this->getTitle();

		$parentRevision = $this->grabParentRevision();

		$this->slotsOutput = [];
		$this->canonicalParserOutput = null;
		$this->canonicalParserOptions = null;

		// The edit may have already been prepared via api.php?action=stashedit
		$stashedEdit = false;

		// TODO: MCR: allow output for all slots to be stashed.
		if ( $this->ajaxEditStash && $newContentSlots->hasSlot( 'main' ) ) {
			$mainContent = $newContentSlots->getContent( 'main' );
			$stashedEdit = ApiStashEdit::checkCache( $title, $mainContent, $this->user );
		}

		if ( $stashedEdit ) {
			/** @var ParserOutput $output */
			$output = $stashedEdit->output;
			$output->setCacheTime( $stashedEdit->timestamp );

			// TODO: MCR: allow output for all slots to be stashed.
			$this->slotsOutput['main'] = (object)[
				'hasHtml' => true,
				'output' => $output,
			];
		}

		$userPopts = ParserOptions::newFromUserAndLang( $this->user, $this->contentLanguage );
		Hooks::run( 'ArticlePrepareTextForEdit', [ $wikiPage, $userPopts ] );

		$this->pstContentSlots = MutableRevisionSlots::newFromParentRevisionSlots(
			$parentRevision->getSlots()->getSlots()
		);

		foreach ( $newContentSlots->getSlots() as $role => $slot ) {
			// TODO: MCR: allow output for all slots to be stashed.
			if ( $role === 'main' && $stashedEdit ) {
				$pstContent = $stashedEdit->pstContent;
			} else {
				$content = $slot->getContent();
				$pstContent = $content->preSaveTransform( $title, $this->user, $userPopts );
			}

			$this->pstContentSlots->setContent( $role, $pstContent );
		}

		$this->options['oldrevision'] = $this->oldPageState['oldRevision'];
		$this->options['oldcountable'] = $this->oldPageState['oldCountable'];
		$this->options['created'] = $this->options['created']
			|| ( !$this->oldPageState['oldrevision'] );
		$this->options['changed'] = $this->options['created']
			|| self::sameSlots(
				$this->pstContentSlots,
				$parentRevision->getSlots()
			);
	}

	private function assertPrepared( $method ) {
		if ( !$this->pstContentSlots ) {
			throw new LogicException( 'Must call prepareEdit() or prepareUpdate() before calling ' . $method );
		}
	}

	public function isNew() {
		$this->assertPrepared( __METHOD__ );
		return $this->options['created'];
	}

	public function isChanged() {
		$this->assertPrepared( __METHOD__ );
		return $this->options['changed'];
	}

	/**
	 * @return RevisionSlots
	 */
	public function getSlots() {
		$this->assertPrepared( __METHOD__ );
		return $this->pstContentSlots;
	}

	/**
	 * Prepare the update targeting the given Revision.
	 *
	 * Calling this method indicates that the given revision is already in the database.
	 *
	 * @param RevisionRecord $revision
	 * @param array $options Array of options, following indexes are used:
	 * - changed: bool, whether the revision changed the content (default true)
	 * - created: bool, whether the revision created the page (default false)
	 * - moved: bool, whether the page was moved (default false)
	 * - restored: bool, whether the page was undeleted (default false)
	 * - oldrevision: Revision object for the pre-update revision (default null)
	 * - oldcountable: bool, null, or string 'no-change' (default null):
	 *    - bool: whether the page was counted as an article before that
	 *      revision, only used in changed is true and created is false
	 *    - null: if created is false, don't update the article count; if created
	 *      is true, do update the article count
	 *    - 'no-change': don't update the article count, ever
	 *
	 */
	public function prepareUpdate( RevisionRecord $revision, array $options = [] ) {
		if ( $this->user !== null ) {
			$user = $revision->getUser();
			if ( $this->user->getActorId() !== $user->getActorId() ) {
				throw new LogicException(
					'The Revision provided has a mismatching actor: expected '
					.$this->user->getActorId()
					. ', got '
					. $user->getActorId()
				);
			}
		}

		if ( $this->pstContentSlots
			&& !self::sameSlots(
				$this->pstContentSlots,
				$revision->getSlots()
			)
		) {
			throw new LogicException(
				'The Revision provided has mismatching content!'
			);
		}

		if ( $this->oldPageState !== null ) {
			if ( $this->oldPageState['oldId'] !== $revision->getParentId() ) {
				throw new LogicException(
					'The Revision provided has a mismatching parent ID: expected '
					. $this->oldPageState['oldId']
					. ', got '
					. $revision->getParentId()
				);
			}
			$parentRevision = $this->oldPageState['oldRevision'];
		} else {
			$parentId = $revision->getParentId();
			$flags = $this->useMaster() ? RevisionStore::READ_LATEST : 0;
			$parentRevision = $parentId ? $this->revisionStore->getRevisionById( $parentId, $flags ) : null;
			$this->oldPageState = [
				'oldRevision' => $parentRevision,
				'oldId' => $parentId,
				'oldIsRedirect' => $this->isRevisionRedirect( $parentRevision ),
				'oldCountable' => $this->isRevisionCountable( $parentRevision ),
			];
		}

		$this->options['oldrevision'] = $this->oldPageState['oldRevision'];
		$this->options['oldcountable'] = $this->options['oldcountable']
										|| $this->oldPageState['oldCountable'];
		$this->options['created'] = $this->options['created']
										|| ( !$this->oldPageState['oldrevision'] );
		$this->options['changed'] = $this->options['changed'] || $this->options['created']
										|| ( $parentRevision->getSha1() != $revision->getSha1() );

		$this->options = array_merge( $this->options, $options );

		$this->revision = $revision;
		$this->pstContentSlots = $revision->getSlots();

		// Prune any output that depends on the revision ID.
		if ( $this->canonicalParserOutput && !$this->slotsOutput ) {
			$this->saveParseLogger->debug( __METHOD__ . ": No prepared output...\n" );
		} else {
			foreach ( $this->slotsOutput as $role => $prep ) {
				$keep = false;
				if ( $prep->output->getFlag( 'vary-revision' ) ) {
					$this->saveParseLogger->info(
						__METHOD__ . ": Prepared output has vary-revision...\n"
					);
				} elseif ( $prep->output->getFlag( 'vary-revision-id' )
					&& $prep->output->getSpeculativeRevIdUsed() !== $this->revision->getId()
				) {
					$this->saveParseLogger->info(
						__METHOD__ . ": Prepared output has vary-revision-id with wrong ID...\n"
					);
				} elseif ( $prep->output->getFlag( 'vary-user' )
					&& !$this->options['changed']
				) {
					// TODO: verify this logic, it seems strange [dk 2018-03]
					$this->saveParseLogger->info(
						__METHOD__ . ": Prepared output has vary-user and is null...\n"
					);
				} else {
					wfDebug( __METHOD__ . ": Keeping prepared output...\n" );
					$keep = true;
				}

				if ( !$keep ) {
					unset( $this->slotsOutput[$role] );

					// We don't know which slots the canonical output depends on, so reset it.
					$this->canonicalParserOutput = null;
				}
			}
		}

		// reset ParserOptions, to the actual revision ID is used in future ParserOutput generation
		$this->canonicalParserOptions = null;
	}

	/**
	 * @deprecated This only exists for B/C, use the getters on PageMetaDataUpdater directly!
	 */
	public function getPreparedEdit() {
		$this->assertPrepared( __METHOD__ );

		// FIXME
		return new PreparedEdit(
			$title,
			$newContentSlots,
			$pstContentSlots,
			$user,
			$canonPopts,
			$output,
			$sigMod
		);
	}

	/**
	 * @return ParserOutput
	 */
	public function getSlotParserOutput( $role, $generateHtml = true ) {
		$this->assertPrepared( __METHOD__ );

		// FIXME
		$output->setCacheTime( $this->getTimestampNow() );
	}

	/**
	 * @return ParserOutput
	 */
	public function getCanonicalParserOutput() {
		if ( $this->canonicalParserOutput ) {
			return $this->canonicalParserOutput;
		}

		// TODO: MCR: logic for combining the output of multiple slot goes here!
		$this->canonicalParserOutput = $this->getSlotParserOutput( 'main' );

		return $this->canonicalParserOutput;
	}

	/**
	 * @return ParserOptions
	 */
	public function getCanonicalParserOptions() {
		if ( $this->canonicalParserOptions ) {
			return $this->canonicalParserOptions;
		}

		// TODO: MCR: combine parser options for all slots?! Include inherited (or use cached output)
		// XXX: Even though this *says* canonical, it may *still* depend on the user language!
		$this->canonicalParserOptions = $this->wikiPage->makeParserOptions( 'canonical' );

		if ( $this->revision ) {
			// Make sure we use the appropriate revision ID when generating output
			$title = $this->getTitle();
			$oldCallback = $this->canonicalParserOptions->getCurrentRevisionCallback();
			$this->canonicalParserOptions->setCurrentRevisionCallback(
				function ( Title $parserTitle, $parser = false )
				use ( $title, &$oldCallback )
				{
					if ( $parserTitle->equals( $title ) ) {
						$legacyRevision = new Revision( $this->revision );
						return $legacyRevision;
					} else {
						return call_user_func( $oldCallback, $parserTitle, $parser );
					}
				}
			);
		} else {
			// NOTE: we only get here without READ_LATEST if called directly by application logic
			$dbIndex = $this->useMaster()
				? DB_MASTER // use the best possible guess
				: DB_REPLICA; // T154554

			$this->canonicalParserOptions->setSpeculativeRevIdCallback(
				function () use ( $dbIndex ) {
					return 1 + (int)wfGetDB( $dbIndex )->selectField(
						'revision',
						'MAX(rev_id)',
						[ ],
						__METHOD__
					);
				}
			);
		}

		return $this->canonicalParserOptions;
	}

	/**
	 * @param PreparedEdit $editInfo
	 * @param bool $recursive
	 *
	 * @return DataUpdate[]
	 */
	public function getSecondaryDataUpdates( $recursive = false ) {
		// TODO: MCR: getSecondaryDataUpdates() needs a complete overhaul to avoid DataUpdates
		// from different slots overwriting each other in the database. Plan:
		// * replace direct calls to Content::getSecondaryLinksUpdates() with calls to this method
		// * Construct LinksUpdate here, on the combined ParserOutput, instead of in AbstractContent
		//   for each slot.
		// * Pass $slot into getSecondaryLinksUpdates() - probably be introducing a new duplicate
		//   version of this function in ContentHandler.
		// * The new method gets the PreparedEdit, but no $recursive flag (that's for LinksUpdate)
		// * Hack: call both the old and the new getSecondaryLinksUpdates method here; Pass
		//   the per-slot ParserOutput to the old method, for B/C.
		// * Hack: If there is moore than one slot, filter LinksUpdate from the DataUpdates
		//   returned by getSecondaryLinksUpdates, and use a LinksUpdated for the combined output
		//   instead.
		// * Call the SecondaryDataUpdates hook here (or kill it - its signature doesn't make sense)

		$content = $this->getSlots()->getContent( 'main' );

		// NOTE: $output is the combined output, to be shown in the default view.
		$output = $this->getCanonicalParserOutput();

		$updates = $content->getSecondaryDataUpdates(
			$this->getTitle(), null, $recursive, $output
		);

		return $updates;
	}

	/**
	 * Do standard updates after page edit, purge, or import.
	 * Update links tables, site stats, search index and message cache.
	 * Purges pages that include this page if the text was changed here.
	 * Every 100th edit, prune the recent changes table.
	 *
	 * @note prepareUpdate() must be called before calling this method!
	 *
	 * MCR migration note: this replaces WikiPage::doEditUpdates.
	 */
	public function doUpdates() {
		if ( !$this->revision ) {
			throw new LogicException( 'Must call prepareUpdate() before calling ' . __METHOD__ );
		}

		$wikiPage = $this->getWikiPage(); // TODO: use only for legacy hooks!

		// FIXME: audience check!
		$touchedSlots = $this->revision->getTouchedSlots();

		$output = $this->getCanonicalParserOutput();

		// Save it to the parser cache.
		// Make sure the cache time matches page_touched to avoid double parsing.
		$this->parserCache->save(
			$output, $wikiPage, $this->getCanonicalParserOptions(),
			$this->revision->getTimestamp(),  $this->revision->getId()
		);

		$legacyRevision = new Revision( $this->revision );

		// Update the links tables and other secondary data
		$recursive = $this->options['changed']; // T52785
		$updates = $this->getSecondaryDataUpdates(
			$recursive
		);
		foreach ( $updates as $update ) {
			$update->setCause( 'edit-page', $this->user->getName() );
			if ( $update instanceof LinksUpdate ) {
				$update->setRevision( $legacyRevision );
				$update->setTriggeringUser( $this->user );
			}
			DeferredUpdates::addUpdate( $update );
		}

		// TODO: MCR: check if *any* changed slot supports categories!
		if ( $this->rcWatchCategoryMembership
			&& $this->getContentHandler( 'main' )->supportsCategories() === true
			&& ( $this->options['changed'] || $this->options['created'] )
			&& !$this->options['restored']
		) {
			// Note: jobs are pushed after deferred updates, so the job should be able to see
			// the recent change entry (also done via deferred updates) and carry over any
			// bot/deletion/IP flags, ect.
			$this->jobQueueGroup->lazyPush(
				new CategoryMembershipChangeJob(
					$this->getTitle(),
					[
						'pageId' => $this->getPageId(),
						'revTimestamp' => $this->revision->getTimestamp(),
					]
				)
			);
		}

		// TODO: replace legacy hook!
		$editInfo = $this->getPreparedEdit();
		Hooks::run( 'ArticleEditUpdates', [ &$wikiPage, &$editInfo, $this->options['changed'] ] );

		// TODO: replace legacy hook!
		if ( Hooks::run( 'ArticleEditUpdatesDeleteFromRecentchanges', [ &$wikiPage ] ) ) {
			// Flush old entries from the `recentchanges` table
			if ( mt_rand( 0, 9 ) == 0 ) {
				$this->jobQueueGroup->lazyPush( RecentChangesUpdateJob::newPurgeJob() );
			}
		}

		$id = $this->getPageId();
		$title = $this->getTitle();
		$dbKey = $title->getPrefixedDBkey();
		$shortTitle = $title->getDBkey();

		if ( !$title->exists() ) {
			wfDebug( __METHOD__ . ": Page doesn't exist any more, bailing out\n" );
			return;
		}

		if ( $this->options['oldcountable'] === 'no-change' ||
			( !$this->options['changed'] && !$this->options['moved'] )
		) {
			$good = 0;
		} elseif ( $this->options['created'] ) {
			$good = (int)$this->isCountable();
		} elseif ( $this->options['oldcountable'] !== null ) {
			$good = (int)$this->isCountable() - (int)$this->options['oldcountable'];
		} else {
			$good = 0;
		}
		$edits = $this->options['changed'] ? 1 : 0;
		$pages = $this->options['created'] ? 1 : 0;

		DeferredUpdates::addUpdate( SiteStatsUpdate::factory(
			[ 'edits' => $edits, 'articles' => $good, 'pages' => $pages ]
		) );

		// TODO: make search infrastructure aware of slots!
		// FIXME: audience check
		$mainSlot = $this->revision->getSlot( 'main' );
		if ( !$mainSlot->isInherited() ) {
			DeferredUpdates::addUpdate( new SearchUpdate( $id, $dbKey, $mainSlot->getContent() ) );
		}

		// If this is another user's talk page, update newtalk.
		// Don't do this if $this->options['changed'] = false (null-edits) nor if
		// it's a minor edit and the user doesn't want notifications for those.
		if ( $this->options['changed']
			&& $title->getNamespace() == NS_USER_TALK
			&& $shortTitle != $this->user->getTitleKey()
			&& !( $this->revision->isMinor() && $this->user->isAllowed( 'nominornewtalk' ) )
		) {
			$recipient = User::newFromName( $shortTitle, false );
			if ( !$recipient ) {
				wfDebug( __METHOD__ . ": invalid username\n" );
			} else {
				// Allow extensions to prevent user notification
				// when a new message is added to their talk page
				// TODO: replace legacy hook!
				if ( Hooks::run( 'ArticleEditUpdateNewTalk', [ &$wikiPage, $recipient ] ) ) {
					if ( User::isIP( $shortTitle ) ) {
						// An anonymous user
						$recipient->setNewtalk( true, $legacyRevision );
					} elseif ( $recipient->isLoggedIn() ) {
						$recipient->setNewtalk( true, $legacyRevision );
					} else {
						wfDebug( __METHOD__ . ": don't need to notify a nonexistent user\n" );
					}
				}
			}
		}

		if ( $title->getNamespace() == NS_MEDIAWIKI && $touchedSlots->hasSlot( 'main' ) ) {
			$mainContent = $touchedSlots->getContent( 'main' );
			$this->messageCache->updateMessageOverride( $title, $mainContent );
		}

		if ( $this->options['created'] ) {
			// XXX: move method here?
			WikiPage::onArticleCreate( $title );
		} elseif ( $this->options['changed'] ) { // T52785
			// XXX: move method here?
			WikiPage::onArticleEdit( $title, $legacyRevision );
		}

		$oldLegacyRevision = $this->options['oldrevision']
			? new Revision( $this->options['oldrevision'] )
			: null;

		ResourceLoaderWikiModule::invalidateModuleCache(
			$title, $oldLegacyRevision, $legacyRevision, wfWikiID()
		);
	}

}
