<?php
namespace MediaWiki\Storage\Sql;

use Elastica\Exception\NotFoundException;
use LoadBalancer;
use MediaWiki\Storage\NameInterner;
use MediaWiki\Storage\RevisionContentInfo;

/**
 * DAO for storing information about revision content "slots".
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class RevisionContentStore {

	private static $selectFields = [
		'cnt_id',
		'cnt_revision',
		'cnt_role',
		'cnt_address',
		'cnt_timestamp',
		'cnt_blob_length',
		'cnt_model',
		'cnt_format',
		'cnt_hash',
		'cnt_logical_size',
		'cnt_deleted',
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
	 * RevisionContentStore constructor.
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
	 * @param string[] $slots = null
	 */
	public function copySlots( $fromRevision, $toRevision ) {
		$slots = $this->selectContentRecords( $fromRevision, [ 'FOR UPDATE' ] );
		$this->insertContentRecords( $toRevision, $slots, [ 'IGNORE' ] );
	}

	/**
	 * Stores records representing the blobs used to store the slot content.
	 *
	 * @param int $revisionId
	 * @param RevisionContentInfo[] $slotRecords New records keyed by slot name.
	 */
	public function storeContentRecords( $revisionId, $slotRecords ) {
		// TODO: trx context?!
		// TODO: allow update derived, instead of insert!
		$this->insertContentRecords( $revisionId, $slotRecords );
	}

	/**
	 * @param $revisionId
	 * @param RevisionContentInfo[] $slotRecords
	 * @param array $options
	 *
	 * @throws \MWException
	 */
	private function insertContentRecords( $revisionId, $slotRecords, $options = [] ) {
		$db = $this->dbLoadBalancer->getConnection( DB_MASTER );

		$revcRows = [];
		foreach ( $slotRecords as $role => $rec ) {
			$row = $this->rowFromRecord( $revisionId, $role, $rec );
			$db->insert( 'content', $row, __METHOD__, $options );

			$contentId = $db->insertId();
			$revcRows[] = [
				'revc_content' => $contentId,
				'revc_revision' => $revisionId
			];
		}

		$db->insert( 'revision_content', $revcRows, __METHOD__ );

		$this->dbLoadBalancer->reuseConnection( $db );
	}

	/**
	 * Returns raw records about the data blob associated with the given slot of the given revision.
	 *
	 * @param int $revisionId
	 *
	 * @throws NotFoundException if no such revision is found
	 *
	 * @return RevisionContentInfo[]
	 */
	public function loadContentRecords( $revisionId ) {
		// TODO: for update??
		$this->selectContentRecords( $revisionId );
	}

	/**
	 * @param int $revisionId
	 * @return RevisionContentInfo[]
	 */
	private function selectContentRecords( $revisionId, $options = [] ) {
		$dbIndex = in_array( 'FOR UPDATE', $options ) ? DB_MASTER : DB_SLAVE;

		$db = $this->dbLoadBalancer->getConnection( $dbIndex );
		$rows = $db->select(
			'content',
			self::$selectFields,
			[ 'revc_revision' => $revisionId ],
			__METHOD__,
			$options,
			[ 'revision_content' => [ 'revc_content = cnt_id' ] ]
		);

		$records = [];
		foreach ( $rows as $row ) {
			$role = $this->roleInterner->getName( $row->cnt_role );
			$records[$role] = $this->recordFromRow( $row );
		}

		$this->dbLoadBalancer->reuseConnection( $db );

		return $records;
	}

	/**
	 * @param object $row
	 *
	 * @return RevisionContentInfo
	 */
	private function recordFromRow( $row ) {
		return new RevisionContentInfo(
			$row->cont_address,
			$this->modelInterner->getName( $row->cont_model ),
			$row->cont_logical_size,
			$this->formatInterner->getName( $row->cont_format ),
			$row->cont_blob_size, // XXX
			$row->cont_timestamp, // XXX
			$row->cont_hash
			// XXX role!
			// XXX revision!
		);
	}

	/**
	 * @param int $revisionId
	 * @param string $role
	 * @param RevisionContentInfo $rec
	 *
	 * @return array
	 */
	private function rowFromRecord( $revisionId, $role, RevisionContentInfo $rec ) {
		return [
			'cont_address' => $rec->getBlobAddress(),
			'cont_model' => $this->modelInterner->acquireInternalId( $rec->getContentModel() ),
			'cont_logical_size' => $rec->getLogicalContentSize(),
			'cont_format' => $this->formatInterner->acquireInternalId( $rec->getSerializationFormat() ),
			'cont_blob_size' => $rec->getBlobSize(),
			'cont_timestamp' => wfTimestamp( TS_MW, $rec->getTimestamp() ),
			'cont_hash' => $rec->getHash(),
			'cont_revision' => $revisionId,
			'cont_role' => $this->roleInterner->acquireInternalId( $role ),
		];
	}

}
