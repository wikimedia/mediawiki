<?php
/**
 * Created by PhpStorm.
 * User: daki
 * Date: 05.07.16
 * Time: 17:32
 */

namespace MediaWiki\Storage\Sql;

use Content;
use ContentHandler;
use LogicException;
use MediaWiki\Storage\ContentBlobInfo;
use MediaWiki\Storage\PageUpdateController;
use MediaWiki\Storage\PageUpdateException;
use MediaWiki\Storage\RevisionContentLookup;
use MediaWiki\Storage\RevisionContentStore;
use MediaWiki\Storage\StorageException;
use Revision;
use User;
use WikiPage;

/**
 * A PageUpdateController based on the WikiPage class.
 *
 * @note There is currently a strong circular dependency between WikiPageUpdateController
 * and WikiPage. This is intended as a first step in the refactoring of WikiPage. Eventually,
 * most if not all of the database related code should be moved out of WikiPage, into a
 * PageUpdateController, a RevisionContentStore, or similar.
 *
 * @package MediaWiki\Storage\Sql
 */
class WikiPageUpdateController implements PageUpdateController {


	/**
	 * @var RevisionContentLookup
	 */
	private $revisionContentLookup;

	/**
	 * @var RevisionContentStore
	 */
	private $revisionContentStore;

	/**
	 * @var WikiPage
	 */
	private $wikiPage;

	/**
	 * @var bool
	 */
	private $isNew = false;

	/**
	 * @var Revision
	 */
	private $currentRevision = null;

	/**
	 * @var Revision
	 */
	private $newRevision = null;

	/**
	 * @var ContentBlobInfo[]
	 */
	private $oldSlots = null;

	/**
	 * @var Content[]
	 */
	private $newContent = [];

	/**
	 * @var string
	 */
	private $state = 'start';

	/**
	 * WikiPageUpdateController constructor.
	 *
	 * @param RevisionContentLookup $revisionContentLookup
	 * @param RevisionContentStore $revisionContentStore
	 * @param WikiPage $wikiPage
	 */
	public function __construct(
		RevisionContentLookup $revisionContentLookup,
		RevisionContentStore $revisionContentStore,
		WikiPage $wikiPage
	) {
		$this->revisionContentLookup = $revisionContentLookup;
		$this->revisionContentStore = $revisionContentStore;
		$this->wikiPage = $wikiPage;
	}

	public function loadBaseRevision( $baseRevId = false ) {

		// Load the data from the master database if needed.
		// The caller may already loaded it from the master or even loaded it using
		// SELECT FOR UPDATE, so do not override that using clear().
		$this->loadPageData( 'fromdbmaster' );

		$this->currentRevision = $this->wikiPage->getRevision();

		$this->isNew = ( $this->currentRevision === null );

		if ( $baseRevId !== false ) {
			if ( $this->isNew ) {
				throw new PageUpdateException( 'edit-gone-missing' );
			} else if ( $baseRevId !== $this->currentRevision->getId() ) {
				throw new PageUpdateException( 'edit-conflict' );
			}
		}

		if ( $this->currentRevision ) {
			// TODO: load only primary slots!
			$this->oldSlots = $this->revisionContentLookup->getRevisionSlots( $baseRevId );
		}

		$this->state = 'loaded';
	}

	public function getBaseRevisionId() {
		return $this->currentRevision ? $this->currentRevision->getId() : 0;
	}

	private function writeSlots( $revisionId, $timestamp, array $prepatedContent ) {
		if ( $this->state !== 'saving' ) {
			throw new LogicException( 'Slots can only be written during the saving phase' );
		}

		// FIXME: apply $prepatedContent here!

		// TODO: detect whether slot Content is different from the old content, using hash
		// TODO: actually remove unwanted slots (which are set to false in $this->newContent)

		$this->revisionContentStore->storeRevisionContent(
			$this->wikiPage->getTitle(),
			$revisionId,
			$this->newContent,
			$timestamp,
			$this->getBaseRevisionId()
		);
	}

	/**
	 * @param string $slotName
	 * @param Content $content
	 */
	public function setPrimarySlotContent( $slotName, Content $content ) {
		if ( $this->state !== 'loaded' ) {
			throw new LogicException( 'Slots can only be set after the update has been loaded' );
		}

		// FIXME: check slot type
		$this->newContent[$slotName] = $content;
	}

	/**
	 * @param string $slotName
	 */
	public function removeSlot( $slotName ) {
		if ( $this->state !== 'loaded' ) {
			throw new LogicException( 'Slots can only be set after the update has been loaded' );
		}

		$this->newContent[$slotName] = false;
	}

	/**
	 * Aborts the page update.
	 */
	public function abort() {
		$this->state = 'aborted';
	}

	/**
	 * Aborts the page update if save() was not yet called.
	 *
	 * Implementations should call this from the destructor.
	 */
	public function cleanup() {
		if ( $this->state !== 'done' && $this->state !== 'failed' && $this->state !== 'aborted' ) {
			$this->abort();
		}
	}

	/**
	 * Whether this page update is a page creation.
	 *
	 * When called before save(), this indicates whether calling save()
	 * will attempt to create the page. When called after save(), this
	 * indicates whether save actually created a new page.
	 *
	 * @return bool
	 */
	public function isNew() {
		return $this->isNew;
	}

	/**
	 * Whether this page update actually changes the page.
	 *
	 * This indicates whether any of the Content objects set via setSlotContent() are different
	 * from the previous Content of the respective slots. This does not reliably indicate whether
	 * a new entry in the revision table was (or will be) created. Use isEdit() for that.
	 *
	 * @see isEdit
	 *
	 * @return bool
	 */
	public function isContentChange() {
		// FIXME: compare if Content is the same as in the old revision, using hash.
		// FIXME: handle slot removal as a relevant change
		return $this->isNew || !empty( $this->newContent );
	}

	/**
	 * Whether this page update creates a new revision.
	 *
	 * When called before save(), this is the same as isContentChange(). When called after save(),
	 * this indicates whether a new entry in the revision table was created. Depending on the
	 * $flags parameter passed to save(), a new revision entry may be created even if the content
	 * was not changed (i.e. a "null revision" was created, as opposed to performing a "null edit",
	 * which updates secondary information, but does not create a revision.
	 *
	 * @return bool
	 */
	public function isEdit() {
		if ( $this->isNew ) {
			return true;
		} elseif ( $this->newRevision ) {
			return $this->newRevision->getId() !== $this->currentRevision->getId();
		} else {
			return $this->isContentChange();
		}
	}

	/**
	 * @return array
	 */
	private function computeRevisionMetaData() {
		$extraSlots = array_diff_key( $this->oldSlots, $this->newContent );

		$size = Revision::calculateRevisionSize( $this->newContent );
		$size = Revision::calculateRevisionSize( $extraSlots, $size );

		$hash = Revision::calculateRevisionHash( $this->newContent );
		$hash = Revision::calculateRevisionHash( $extraSlots, $hash );

		return [
			'size' => $size,
			'sha1' => $hash,
		];
	}

	/**
	 * Saves a new revision of the page.
	 *
	 * @param string $summary
	 * @param User $user
	 * @param int $flags
	 * @param array $tags
	 *
	 * @throws StorageException
	 * @return Revision
	 */
	public function save( $summary, User $user, $flags = 0, $tags = [ ] ) {
		global $wgUseAutomaticEditSummaries; // FIXME: use a setter!

		if ( $this->state !== 'loaded' ) {
			throw new LogicException( 'Update can only be saved after being loaded' );
		}

		$this->checkSlotsForEdit( $flags, $this->getBaseRevisionId(), $user );

		$mainSlotContent = $this->newContent['main']; // FIXME: load it if it's not there. see $old_content below.

		// Provide autosummaries if one is not provided and autosummaries are enabled
		if ( $wgUseAutomaticEditSummaries && ( $flags & EDIT_AUTOSUMMARY ) && $summary == '' ) {
			//FIXME: use main slot content for summary!
			$old_content = $this->wikiPage->getContent( Revision::RAW ); // current revision's content
			$handler = $mainSlotContent->getContentHandler();
			$summary = $handler->getAutosummary( $old_content, $mainSlotContent, $flags );
		}

		$oldRevId = $this->getBaseRevisionId();

		$meta = [
			'now' => wfTimestampNow(),
			'summary' => $summary,
			'bot' => ( $flags & EDIT_FORCE_BOT ),
			'minor' => ( $flags & EDIT_MINOR ) && $user->isAllowed( 'minoredit' ),
			'baseRevId' => $oldRevId, // XXX really? we check in loadCurrent()...
			'oldRevision' => $this->currentRevision,
			'oldId' => $oldRevId,
			'oldIsRedirect' => $this->wikiPage->isRedirect(),
			'oldCountable' => $this->wikiPage->isCountable(),
			'tags' => $tags,
			'flags' => $flags,
			'changed' => $this->isContentChange(),
			'use-stash' => ( $flags & ( EDIT_INTERNAL | EDIT_FORCE_BOT ) ),
			'main_content' => $mainSlotContent,
			'contentModel' => $mainSlotContent->getModel(),
			'serialFormat' => null, // XXX: is this ok?? format should be ignored anyway
		];

		$meta += $this->computeRevisionMetaData();

		// FIXME: enforce transaction bracket
		$this->newRevision = $this->wikiPage->prepareEdit(
			$summary,
			$user,
			$meta
		);

		$prepatedContent = $this->prepareSlotsForEdit(
			$this->newRevision, $user, $meta['use-stash']
		);

		if ( $meta['changed'] ) {
			$this->writeSlots( $this->newRevision->getId(), $this->newRevision->getTimestamp(), $prepatedContent );
		}

		$this->wikiPage->finishEdit( $this->newRevision, $user, $meta, $prepatedContent );

		return $this->newRevision;
	}

	/**
	 * @param Revision $revision
	 * @param User $user
	 * @param bool $useStash
	 * @return object[] // FIXME: make EditInfo a proper class!
	 */
	private function prepareSlotsForEdit(
		Revision $revision, User $user, $useStash = true
	) {
		$prepared = [];

		foreach ( $this->newContent as $slotName => $content ) {
			/*
			// FIXME: Cache, in case we do this more than once?!
			if ( $this->mPreparedEdit
				&& $this->mPreparedEdit->newContent
				&& $this->mPreparedEdit->newContent->equals( $content )
				&& $this->mPreparedEdit->revid == $revid
				&& $this->mPreparedEdit->format == $serialFormat
				// XXX: also check $user here?
			) {
				// Already prepared
				continue
			}
			 */

			if ( !isset( $prepared[$slotName] ) ) {
				$prepared[$slotName] = $this->wikiPage->prepareContentForEdit(
					$content, $revision, $user, null, $useStash
				);
			}
		}

		return $prepared;
	}

	private function checkSlotsForEdit( $flags, $oldid, $user ) {
		// FIXME: make sure we have no dupe content here that is the same as in the previous revision
		foreach ( $this->newContent as $slotName => $content ) {
			// Make sure the given content type is allowed for this page
			$status = $content->prepareSave( $this->wikiPage, $flags, $oldid, $user );
			if ( !$status->isOK() ) {
				throw new PageUpdateException( /* FIXME */ );
			}

			if ( !$content->getContentHandler()->canBeUsedOn( $this->mTitle ) ) {
				return new PageUpdateException(
					wfMessage( 'content-not-allowed-here',
				        ContentHandler::getLocalizedName( $content->getModel() ),
				        $this->mTitle->getPrefixedText()
					)
				);
			}
		}
	}

}