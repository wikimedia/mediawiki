<?php
namespace MediaWiki\Storage\Sql;
use MediaWiki\Storage\NotFoundException;

/**
 * DAO for the revisionslot table. The table has the following fields:
 *
 *   slot_revision_id INT,
 *   slot_name        VARCHAR(32),
 *   slot_timestamp   CHAR(14),
 *   slot_address     VARCHAR(255),
 *   slot_model       VARCHAR(32),
 *   slot_format      VARCHAR(64),
 *
 * @todo consider the following additions:
 *   slot_sha1        VARCHAR(32),,
 *   slot_len         INT,
 *   slot_copied      TINYINT,
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class RevisionSlotTable {

	/**
	 * Returns the addresses of the slot blobs.
	 *
	 * @param int $revisionId
	 *
	 * @return string[] Addresses, keyed by slot name
	 */
	public function getContentAddresses( $revisionId ) {
		//TODO...
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
		//TODO...
	}

	/**
	 * Stores records representing the blobs used to store the slot content.
	 *
	 * @param int $revisionId
	 * @param array[] $slotRecords New records keyed by slot name.
	 */
	public function putContentRecords( $revisionId, $slotRecords ) {
		//TODO...
	}

	/**
	 * Returns the record about the data blob associated with the given slot of the given revision.
	 *
	 * @param int $revisionId
	 * @param string $slot
	 *
	 * @throws NotFoundException if no such slot or revision is found
	 *
	 * @return array A record of the following form:
	 *	array(
	 *		'blob_address' => string,
	 *		'content_model' => string,
	 *		'content_format' => string,
	 *		'timestamp' => string
	 *	);
	 */
	public function getContentRecord( $revisionId, $slot ) {
		//TODO...
	}

}
