<?php
namespace MediaWiki\Storage\Sql;
use LoadBalancer;
use MediaWiki\Storage\NotFoundException;

/**
 * DAO for the revisionslot table. The table has the following fields:
 *
 *   slot_row_id       INT AUTOINCREMENT PRIMARY KEY,
 *   slot_revision_id  INT,
 *   slot_role         INT, -- aka slot name, -> slot_roles
 *   slot_role_aspect  VARCHAR(255), -- aka subslot. concat with resolved slot_role
 *   slot_timestamp    CHAR(14), -- only relevant for derived data
 *   slot_address      VARCHAR(255), -- factor out common prefixes?
 *   slot_blob_length  INT,
 *   slot_model        INT, -- -> content_models
 *   slot_format       INT, -- -> content_format
 *   slot_hash         VARCHAR(32), from Content::getHash.
 *   slot_logical_size INT, -- from Content::getSize
 *   slot_updated_in   INT, -- id of the last rev that changed this slot
 *   slot_deleted      INT, -- for per-slot suppression, if we need that
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class RevisionSlotTable {

	/**
	 * @var LoadBalancer
	 */
	private $dbLoadBalancer;

	/**
	 * RevisionSlotTable constructor.
	 *
	 * @param LoadBalancer $dbLoadBalancer
	 */
	public function __construct( LoadBalancer $dbLoadBalancer ) {
		$this->dbLoadBalancer = $dbLoadBalancer;
	}

	/**
	 * Returns the addresses of the slot blobs.
	 *
	 * @param int $revisionId
	 *
	 * @return string[] Addresses, keyed by slot name
	 */
	public function getContentAddresses( $revisionId ) {
		//TODO: read from revisionslots table
		//TODO: read from legacy fields in revision table
	}

	/**
	 * Copies slots of one revision to another. Used to maintain continuity of
	 * content "streams" across revisions.
	 *
	 * @param int $fromRevision
	 * @param int $toRevision
	 * @param string[] $slots
	 */
	public function copySlots( $fromRevision, $toRevision, array $slots ) {
		//TODO: copy entries in the revisionslots table
		//TODO: copy legacy fields in the revision table
		//TODO: sync info between revisionslots and legacy firelds
	}

	/**
	 * Stores records representing the blobs used to store the slot content.
	 *
	 * @param int $revisionId
	 * @param array[] $slotRecords New records keyed by slot name.
	 */
	public function putContentRecords( $revisionId, $slotRecords ) {
		//TODO: write entries into the revisionslots table
		//TODO: write legacy fields into the revision table
	}

	/**
	 * Returns raw records about the data blob associated with the given slot of the given revision.
	 *
	 * @param int $revisionId
	 *
	 * @throws NotFoundException if no such revision is found
	 *
	 * @todo Shouldn't this return ContentBlobInfo objects?
	 *
	 * @return array An array of records, keyed by slot name. Each record of the following form:
	 *	[
	 *		'blob_address' => string,
	 *		'content_model' => string,
	 *		'content_format' => string,
	 *	];
	 */
	public function getContentRecords( $revisionId ) {
		//TODO: read from revisionslots table
		//TODO: read from legacy fields in revision table
	}

}
