<?php

namespace MediaWiki\Deferred\LinksUpdate;

use InvalidArgumentException;
use MediaWiki\Linker\LinkTargetLookup;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Revision\RevisionRecord;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\LBFactory;

/**
 * The base class for classes which update a single link table.
 *
 * A LinksTable object is a container for new and existing link sets outbound
 * from a single page, and an abstraction of the associated DB schema. The
 * object stores state related to an update of the outbound links of a page.
 *
 * Explanation of link ID concept
 * ------------------------------
 *
 * Link IDs identify a link in the new or old state, or in the change arrays.
 * They are opaque to the base class and are type-hinted here as mixed.
 *
 * Conventionally, the link ID is string|string[] and contains the link target
 * fields.
 *
 * The link ID should contain enough information so that the base class can
 * tell whether an existing link is in the new set, or vice versa, for the
 * purposes of incremental updates. If a change to a field would cause a DB
 * update, the field should be in the link ID.
 *
 * For example, a change to cl_timestamp does not trigger an update, so
 * cl_timestamp is not in the link ID.
 *
 * @stable to extend
 * @since 1.38
 */
abstract class LinksTable {
	/** Link type: Inserted (added) links */
	public const INSERTED = 1;

	/** Link type: Deleted (removed) links */
	public const DELETED = 2;

	/** Link type: Changed (inserted or removed) links */
	public const CHANGED = 3;

	/** Link type: existing/old links */
	public const OLD = 4;

	/** Link type: new links (from the ParserOutput) */
	public const NEW = 5;

	/**
	 * Rows to delete. An array of associative arrays, each associative array
	 * being the conditions for a delete query. Common conditions should be
	 * leftmost in the associative array so that they can be factored out.
	 *
	 * @var array
	 */
	protected $rowsToDelete = [];

	/**
	 * Rows to insert. An array of associative arrays, each associative array
	 * mapping field names to values.
	 *
	 * @var array
	 */
	protected $rowsToInsert = [];

	/** @var array Link IDs for inserted links */
	protected $insertedLinks = [];

	/** @var array Link IDs for deleted links */
	protected $deletedLinks = [];

	/** @var LBFactory */
	private $lbFactory;

	/** @var LinkTargetLookup */
	protected $linkTargetLookup;

	/** @var IDatabase */
	private $db;

	/** @var PageIdentity */
	private $sourcePage;

	/** @var PageReference|null */
	private $movedPage;

	/** @var int */
	private $batchSize;

	/** @var mixed */
	private $ticket;

	/** @var RevisionRecord */
	private $revision;

	/** @var bool */
	protected $strictTestMode;

	/**
	 * This is called by the factory to inject dependencies for the base class.
	 * This is used instead of the constructor so that changes can be made to
	 * the injected parameters without breaking the subclass constructors.
	 *
	 * @param LBFactory $lbFactory
	 * @param LinkTargetLookup $linkTargetLookup
	 * @param PageIdentity $sourcePage
	 * @param int $batchSize
	 */
	final public function injectBaseDependencies(
		LBFactory $lbFactory,
		LinkTargetLookup $linkTargetLookup,
		PageIdentity $sourcePage,
		$batchSize
	) {
		$this->lbFactory = $lbFactory;
		$this->db = $this->lbFactory->getPrimaryDatabase( $this->virtualDomain() );
		$this->sourcePage = $sourcePage;
		$this->batchSize = $batchSize;
		$this->linkTargetLookup = $linkTargetLookup;
	}

	/**
	 * Set the empty transaction ticket
	 *
	 * @param mixed $ticket
	 */
	public function setTransactionTicket( $ticket ) {
		$this->ticket = $ticket;
	}

	/**
	 * Set the revision associated with the edit.
	 */
	public function setRevision( RevisionRecord $revision ) {
		$this->revision = $revision;
	}

	/**
	 * Notify the object that the operation is a page move, and set the
	 * original title.
	 */
	public function setMoveDetails( PageReference $movedPage ) {
		$this->movedPage = $movedPage;
	}

	/**
	 * Subclasses should implement this to extract the data they need from the
	 * ParserOutput.
	 *
	 * To support a future refactor of LinksDeletionUpdate, if this method is
	 * not called, the subclass should assume that the new state is empty.
	 */
	abstract public function setParserOutput( ParserOutput $parserOutput );

	/**
	 * Get the table name.
	 *
	 * @return string
	 */
	abstract protected function getTableName();

	/**
	 * Get the name of the field which links to page_id.
	 *
	 * @return string
	 */
	abstract protected function getFromField();

	/**
	 * Get the fields to be used in fetchExistingRows(). Note that
	 * fetchExistingRows() is just a helper for subclasses. The value returned
	 * here is effectively private to the subclass.
	 *
	 * @return array
	 */
	abstract protected function getExistingFields();

	/**
	 * Get an array (or iterator) of link IDs for the new state.
	 *
	 * See the LinksTable doc comment for an explanation of link IDs.
	 *
	 * @return iterable<mixed>
	 */
	abstract protected function getNewLinkIDs();

	/**
	 * Get an array (or iterator) of link IDs for the existing state. The
	 * subclass should load the data from the database. There is
	 * fetchExistingRows() to make this easier but the subclass is responsible
	 * for caching.
	 *
	 * See the LinksTable doc comment for an explanation of link IDs.
	 *
	 * @return iterable<mixed>
	 */
	abstract protected function getExistingLinkIDs();

	/**
	 * Determine whether a link (from the new set) is in the existing set.
	 *
	 * @param mixed $linkId
	 * @return bool
	 */
	abstract protected function isExisting( $linkId );

	/**
	 * Determine whether a link (from the existing set) is in the new set.
	 *
	 * @param mixed $linkId
	 * @return bool
	 */
	abstract protected function isInNewSet( $linkId );

	/**
	 * Insert a link identified by ID. The subclass is expected to queue the
	 * insertion by calling insertRow().
	 *
	 * @param mixed $linkId
	 */
	abstract protected function insertLink( $linkId );

	/**
	 * Delete a link identified by ID. The subclass is expected to queue the
	 * deletion by calling deleteRow().
	 *
	 * @param mixed $linkId
	 */
	abstract protected function deleteLink( $linkId );

	/**
	 * Subclasses can override this to return true in order to force
	 * reinsertion of all the links due to some property of the link
	 * changing for reasons not represented by the link ID.
	 *
	 * @return bool
	 */
	protected function needForcedLinkRefresh() {
		return false;
	}

	/**
	 * @stable to override
	 * @return IDatabase
	 */
	protected function getDB(): IDatabase {
		return $this->db;
	}

	protected function getLBFactory(): LBFactory {
		return $this->lbFactory;
	}

	/**
	 * Get the page_id of the source page
	 */
	protected function getSourcePageId(): int {
		return $this->sourcePage->getId();
	}

	/**
	 * Get the source page, i.e. the page which is being updated and is the
	 * source of links.
	 */
	protected function getSourcePage(): PageIdentity {
		return $this->sourcePage;
	}

	/**
	 * Determine whether the page was moved
	 *
	 * @return bool
	 */
	protected function isMove() {
		return $this->movedPage !== null;
	}

	/**
	 * Determine whether the page was moved to a different namespace.
	 *
	 * @return bool
	 */
	protected function isCrossNamespaceMove() {
		return $this->movedPage !== null
			&& $this->sourcePage->getNamespace() !== $this->movedPage->getNamespace();
	}

	/**
	 * Assuming the page was moved, get the original page title before the move.
	 * This will throw an exception if the page wasn't moved.
	 */
	protected function getMovedPage(): PageReference {
		return $this->movedPage;
	}

	/**
	 * Get the maximum number of rows to update in a batch.
	 */
	protected function getBatchSize(): int {
		return $this->batchSize;
	}

	/**
	 * Get the empty transaction ticket, or null if there is none.
	 *
	 * @return mixed
	 */
	protected function getTransactionTicket() {
		return $this->ticket;
	}

	/**
	 * Get the RevisionRecord of the new revision, if the LinksUpdate caller
	 * injected one.
	 *
	 * @return RevisionRecord|null
	 */
	protected function getRevision(): ?RevisionRecord {
		return $this->revision;
	}

	/**
	 * Get field=>value associative array for the from field(s)
	 *
	 * @stable to override
	 * @return array
	 */
	protected function getFromConds() {
		return [ $this->getFromField() => $this->getSourcePageId() ];
	}

	/**
	 * Do a select query to fetch the existing rows. This is a helper for
	 * subclasses.
	 */
	protected function fetchExistingRows(): IResultWrapper {
		return $this->getDB()->newSelectQueryBuilder()
			->select( $this->getExistingFields() )
			->from( $this->getTableName() )
			->where( $this->getFromConds() )
			->caller( __METHOD__ )
			->fetchResultSet();
	}

	/**
	 * Execute an edit/delete update
	 */
	final public function update() {
		$this->startUpdate();
		$force = $this->needForcedLinkRefresh();
		foreach ( $this->getNewLinkIDs() as $link ) {
			if ( $force || !$this->isExisting( $link ) ) {
				$this->insertLink( $link );
				$this->insertedLinks[] = $link;
			}
		}

		foreach ( $this->getExistingLinkIDs() as $link ) {
			if ( $force || !$this->isInNewSet( $link ) ) {
				$this->deleteLink( $link );
				$this->deletedLinks[] = $link;
			}
		}
		$this->doWrites();
		$this->finishUpdate();
	}

	/**
	 * Queue a row for insertion. Subclasses are expected to call this from
	 * insertLink(). The "from" field should not be included in the row.
	 *
	 * @param array $row Associative array mapping fields to values.
	 */
	protected function insertRow( $row ) {
		$row += $this->getFromConds();
		$this->rowsToInsert[] = $row;
	}

	/**
	 * Queue a deletion operation. Subclasses are expected to call this from
	 * deleteLink(). The "from" field does not need to be included in the
	 * conditions.
	 *
	 * Most often, the conditions match a single row, but this is not required.
	 *
	 * @param array $conds Associative array mapping fields to values,
	 *   specifying the conditions for a delete query.
	 */
	protected function deleteRow( $conds ) {
		// Put the "from" field leftmost, so it can be factored out
		$conds = $this->getFromConds() + $conds;
		$this->rowsToDelete[] = $conds;
	}

	/**
	 * Subclasses can override this to do any necessary setup before the lock
	 * is acquired.
	 *
	 * @stable to override
	 */
	public function beforeLock() {
	}

	/**
	 * Subclasses can override this to do any necessary setup before individual
	 * write operations begin.
	 *
	 * @stable to override
	 */
	protected function startUpdate() {
	}

	/**
	 * Subclasses can override this to do any updates associated with their
	 * link data, for example dispatching HTML update jobs.
	 *
	 * @stable to override
	 */
	protected function finishUpdate() {
	}

	/**
	 * Do the common DB operations
	 */
	protected function doWrites() {
		$db = $this->getDB();
		$table = $this->getTableName();
		$batchSize = $this->getBatchSize();
		$ticket = $this->getTransactionTicket();

		$deleteBatches = array_chunk( $this->rowsToDelete, $batchSize );
		foreach ( $deleteBatches as $chunk ) {
			$db->newDeleteQueryBuilder()
				->deleteFrom( $table )
				->where( $db->factorConds( $chunk ) )
				->caller( __METHOD__ )->execute();
			if ( count( $deleteBatches ) > 1 ) {
				$this->lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
			}
		}

		$insertBatches = array_chunk( $this->rowsToInsert, $batchSize );
		foreach ( $insertBatches as $insertBatch ) {
			$db->newInsertQueryBuilder()
				->options( $this->getInsertOptions() )
				->insertInto( $table )
				->rows( $insertBatch )
				->caller( __METHOD__ )->execute();
			if ( count( $insertBatches ) > 1 ) {
				$this->lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
			}
		}
	}

	/**
	 * Omit conflict resolution options from the insert query so that testing
	 * can confirm that the incremental update logic was correct.
	 *
	 * @param bool $mode
	 */
	public function setStrictTestMode( $mode = true ) {
		$this->strictTestMode = $mode;
	}

	/**
	 * Get the options for the insert queries
	 *
	 * @return array
	 */
	protected function getInsertOptions() {
		if ( $this->strictTestMode ) {
			return [];
		} else {
			return [ 'IGNORE' ];
		}
	}

	/**
	 * Get an array or iterator of link IDs of a given type. Some subclasses
	 * use this to provide typed data to callers. This is not public because
	 * link IDs are a private concept.
	 *
	 * @param int $setType One of the class constants: self::INSERTED, self::DELETED,
	 *   self::CHANGED, self::OLD or self::NEW.
	 * @return iterable<mixed>
	 */
	protected function getLinkIDs( $setType ) {
		switch ( $setType ) {
			case self::INSERTED:
				return $this->insertedLinks;

			case self::DELETED:
				return $this->deletedLinks;

			case self::CHANGED:
				return array_merge( $this->insertedLinks, $this->deletedLinks );

			case self::OLD:
				return $this->getExistingLinkIDs();

			case self::NEW:
				return $this->getNewLinkIDs();

			default:
				throw new InvalidArgumentException( __METHOD__ . ": Unknown link type" );
		}
	}

	/**
	 * Normalization stage of the links table (see T222224)
	 */
	protected function linksTargetNormalizationStage(): int {
		return SCHEMA_COMPAT_OLD;
	}

	/**
	 * What virtual domain should be used to read/write from the table
	 * @return string|bool
	 */
	protected function virtualDomain() {
		return false;
	}
}
