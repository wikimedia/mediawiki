<?php
namespace MediaWiki\Storage\Sql;

use LoadBalancer;
use MediaWiki\Storage\NameInternalizer;
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
	 * @var NameInternalizer
	 */
	private $roleInternalizer;
	/**
	 * @var NameInternalizer
	 */
	private $modelInternalizer;
	/**
	 * @var NameInternalizer
	 */
	private $formatlInternalizer;

	/**
	 * RevisionContentStore constructor.
	 *
	 * @param LoadBalancer $dbLoadBalancer
	 * @param NameInternalizer $roleInternalizer
	 * @param NameInternalizer $modelInternalizer
	 * @param NameInternalizer $formatlInternalizer
	 */
	public function __construct(
		LoadBalancer $dbLoadBalancer,
		NameInternalizer $roleInternalizer,
		NameInternalizer $modelInternalizer,
		NameInternalizer $formatlInternalizer
	) {
		$this->dbLoadBalancer = $dbLoadBalancer;
		$this->roleInternalizer = $roleInternalizer;
		$this->modelInternalizer = $modelInternalizer;
		$this->formatlInternalizer = $formatlInternalizer;
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
		foreach ( $slotRecords as $rec ) {
			$row = $this->rowFromRecord( $rec );
			$db->insert( 'content', $row, __METHOD__, $options );

			$contentId = $db->insertId();
			$revcRows[] = [
				'revc_content' => $contentId,
				'revc_revision' => $revisionId
			];
		}

		$db->insert( 'revision' )

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
			$role = $this->roleInternalizer->getName( $row->cnt_role );
			$records[$role] = $this->recordFromRow( $row );
		}

		$this->dbLoadBalancer->reuseConnection( $db );

		return $records;
	}

}
