<?php
namespace MediaWiki\Storage\Sql;

use Elastica\Exception\NotFoundException;
use LoadBalancer;
use MediaWiki\Storage\ContentRecord;
use MediaWiki\Storage\NameInterner;
use MediaWiki\Storage\SlotRecord;

/**
 * DAO for storing information about revision content "slots".
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class SlotRecordStore {

	private static $selectFields = [
		'cont_id',
		'cont_origin',
		'cont_address',
		'cont_model',
		'cont_format',
		'cont_hash',
		'cont_deleted',
		'slot_content',
		'slot_revision',
		'slot_role'
	];

	/**
	 * @var LoadBalancer
	 */
	private $dbLoadBalancer;
	/**
	 * @var NameInterner
	 */
	private $roleInterner;
	/**
	 * @var NameInterner
	 */
	private $modelInterner;
	/**
	 * @var NameInterner
	 */
	private $formatInterner;

	/**
	 * SlotRecordStore constructor.
	 *
	 * @param LoadBalancer $dbLoadBalancer
	 * @param NameInterner $roleInterner
	 * @param NameInterner $modelInterner
	 * @param NameInterner $formatInterner
	 */
	public function __construct(
		LoadBalancer $dbLoadBalancer,
		NameInterner $roleInterner,
		NameInterner $modelInterner,
		NameInterner $formatInterner
	) {
		$this->dbLoadBalancer = $dbLoadBalancer;
		$this->roleInterner = $roleInterner;
		$this->modelInterner = $modelInterner;
		$this->formatInterner = $formatInterner;
	}

	/**
	 * Copies slots of one revision to another. Used to maintain continuity of
	 * content "streams" across revisions.
	 *
	 * @param int $fromRevision
	 * @param int $toRevision
	 * @param array $omitSlots
	 */
	private function carrySlots( $fromRevision, $toRevision, array $omitSlots = [] ) {
		// TODO: the below can probably be done with a single INSERT IGNORE SELECT

		$contentIds = $this->selectContentIds( $fromRevision, [ 'FOR UPDATE' ] );
		$contentIds = array_diff_key( $contentIds, array_flip( $omitSlots ) );

		if ( !empty( $contentIds ) ) {
			$this->insertSlotRows( $toRevision, $contentIds, [ 'IGNORE' ] );
		}
	}

	/**
	 * Stores records representing the blobs used to store the slot content.
	 *
	 * @param int $revisionId
	 * @param ContentRecord[] $contentRecords New records keyed by slot name.
	 */
	public function storeContentRecords( $revisionId, $contentRecords ) {
		// TODO: trx context?!
		// XXX the caller already knows the parent ID!
		$parentRevisionId = $this->findParentRevision( $revisionId, [ 'FOR UPDATE' ] );
		$this->insertContentRecords( $revisionId, $contentRecords );

		if ( $parentRevisionId ) {
			$this->carrySlots( $parentRevisionId, $revisionId, array_keys( $contentRecords ) );
		}
	}

	/**
	 * @param $revisionId
	 * @param ContentRecord[] $slotRecords
	 * @param array $options
	 *
	 * @throws \MWException
	 */
	private function insertContentRecords( $revisionId, $slotRecords, $options = [] ) {
		$db = $this->dbLoadBalancer->getConnection( DB_MASTER );

		$contentIds = [];
		foreach ( $slotRecords as $role => $rec ) {
			$row = $this->contentRowFromRecord( $rec );
			$db->insert( 'content', $row, __METHOD__, $options );

			$contentIds[$role] = $db->insertId();
		}

		$this->dbLoadBalancer->reuseConnection( $db );

		$this->insertSlotRows( $revisionId, $contentIds, [ 'IGNORE' ] );
	}

	private function insertSlotRows( $revisionId, array $contentIds, array $options = [] ) {
		$db = $this->dbLoadBalancer->getConnection( DB_MASTER );
		$revcRows = [];

		foreach ( $contentIds as $role => $cid ) {
			$revcRows[] = [
				'slot_content' => $cid,
				'slot_role' => $this->roleInterner->getInternalId( $role ),
				'slot_revision' => $revisionId
			];
		}

		$db->insert( 'slots', $revcRows, __METHOD__ );
	}

	/**
	 * Returns raw records about the data blob associated with the given slot of the given revision.
	 *
	 * @param int $revisionId
	 *
	 * @throws NotFoundException if no such revision is found
	 *
	 * @return SlotRecord[]
	 */
	public function loadSlotRecords( $revisionId ) {
		// TODO: for update??
		$this->selectSlotRecords( $revisionId );
	}

	/**
	 * @param int $revisionId
	 *
	 * @return SlotRecord[]
	 */
	private function selectSlotRecords( $revisionId, $options = [] ) {
		$dbIndex = in_array( 'FOR UPDATE', $options ) ? DB_MASTER : DB_SLAVE;

		$db = $this->dbLoadBalancer->getConnection( $dbIndex );
		$rows = $db->select(
			[ 'content', 'slots' ],
			self::$selectFields,
			[ 'slot_revision' => $revisionId ],
			__METHOD__,
			$options,
			[ 'slots' => [ 'slot_content = cont_id' ] ]
		);

		$records = [];
		foreach ( $rows as $row ) {
			$role = $this->roleInterner->getName( $row->cont_role );
			$records[$role] = $this->recordFromRow( $row );
		}

		$this->dbLoadBalancer->reuseConnection( $db );

		return $records;
	}

	/**
	 * @param int $revisionId
	 *
	 * @return SlotRecord[]
	 */
	private function selectContentIds( $revisionId, $options = [] ) {
		$dbIndex = in_array( 'FOR UPDATE', $options ) ? DB_MASTER : DB_SLAVE;

		$db = $this->dbLoadBalancer->getConnection( $dbIndex );
		$rows = $db->select(
			[ 'content', 'slots' ],
			[ 'cont_role', 'slot_content' ], // XXX: perhaps cont_role should be in slots, not content!
			[ 'slot_revision' => $revisionId ],
			__METHOD__,
			$options,
			[ 'slots' => [ 'slot_content = cont_id' ] ]
		);

		$contentIds = [];
		foreach ( $rows as $row ) {
			$role = $this->roleInterner->getName( $row->cont_role );
			$contentIds[$role] = $row->slot_content;
		}

		$this->dbLoadBalancer->reuseConnection( $db );

		return $contentIds;
	}

	/**
	 * @param int $revisionId
	 * @return int the parent revision's id
	 */
	private function findParentRevision( $revisionId, $options = [] ) {
		$dbIndex = in_array( 'FOR UPDATE', $options ) ? DB_MASTER : DB_SLAVE;

		$db = $this->dbLoadBalancer->getConnection( $dbIndex );
		$parent = $db->selectField(
			'revision',
			[ 'rev_parent' ],
			[ 'rev_id' => $revisionId ],
			__METHOD__,
			$options
		);

		$this->dbLoadBalancer->reuseConnection( $db );

		return $parent ?: 0;
	}

	/**
	 * @param object $row
	 *
	 * @return SlotRecord
	 */
	private function recordFromRow( $row ) {
		return new SlotRecord(
			$row->slot_revision,
			$row->slot_content,
			$this->modelInterner->getName( $row->slot_role ),
			$row->cont_address,
			$this->modelInterner->getName( $row->cont_model ),
			$row->cont_logical_size,
			$this->formatInterner->getName( $row->cont_format ),
			$row->cont_hash,
			$row->cont_origin
		);
	}

	/**
	 * @param ContentRecord $rec
	 *
	 * @return array
	 */
	private function contentRowFromRecord( ContentRecord $rec ) {
		return [
			'cont_origin' => $rec->getOrigin(),
			'cont_address' => $rec->getBlobAddress(),
			'cont_model' => $this->modelInterner->acquireInternalId( $rec->getContentModel() ),
			'cont_logical_size' => $rec->getLogicalContentSize(),
			'cont_format' => $this->formatInterner->acquireInternalId( $rec->getSerializationFormat() ),
			'cont_hash' => $rec->getHash(),
		];
	}

}
