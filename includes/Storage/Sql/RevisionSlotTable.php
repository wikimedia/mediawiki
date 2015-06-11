<?php
namespace MediaWiki\Storage\Sql;
use MediaWiki\Storage\NotFoundException;

/**
 * RevisionSlotTable
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
