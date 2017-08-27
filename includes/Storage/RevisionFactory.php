<?php
/**
 * Created by PhpStorm.
 * User: daki
 * Date: 29.08.17
 * Time: 19:19
 */
namespace MediaWiki\Storage;

use MediaWiki\Linker\LinkTarget;
use MWException;
use User;
use Wikimedia\Rdbms\IDatabase;

/**
 * Service for looking up page revisions.
 *
 * @since 1.30
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in Revision.
 */
interface RevisionFactory {

	/**
	 * Constructs a RevisionRecord based on the MW1.29 database schema.
	 *
	 * MCR migration note: this replaces Revision::newFromRow for rows based on the
	 * revision and text tables using the MW1.29 schema.
	 *
	 * @deprecated since 1.30, use newRevisionFromRow() instead.
	 *
	 * @param object $row A database row from the revision table and possible the text table
	 *        as defined in the MW1.29 schema, as a raw object.
	 *
	 * @return RevisionRecord
	 */
	public function newRevisionFromRow_1_29( $row );

	/**
	 * Constructs a RevisionRecord based on the MW1.29 convention for the
	 * Revision constructor.
	 *
	 * MCR migration note: this replaces Revision::newFromRow for arrays based on the
	 * MW1.29 convention for representing revision data in an array.
	 *
	 * @deprecated since 1.30, use newRevisionFromArray() instead.
	 *
	 * @param array $fields
	 *
	 * @return RevisionRecord
	 */
	public function newRevisionFromArray_1_29( array $fields );

	/**
	 * Constructs a RevisionRecord given a database row and content slots.
	 *
	 * MCR migration note: this replaces Revision::newFromRow for rows based on the
	 * revision, slot, and content tables defined for MCR since MW1.30.
	 *
	 * @param object $row A database row from the revision table, as a raw object
	 *
	 * @param RevisionSlots $slots The slots to be contained in the RevisionRecord.
	 *
	 * @return RevisionRecord
	 */
	public function newRevisionFromRow( $row, RevisionSlots $slots );

	/**
	 * Constructs a RevisionRecord given an array.
	 *
	 * MCR migration note: this replaces Revision::newFromRow for programmatic definition of a
	 * new revision using an associative array.
	 *
	 * @param array $fields An associative array defining a revision. The structure of this array
	 *        remains similar to the MW1.29 convention for representing revision data in an array,
	 *        with any information related to the revision content removed and instead represented
	 *
	 * @param RevisionSlots $slots The slots to be contained in the RevisionRecord.
	 *
	 * @return RevisionRecord
	 */
	public function newRevisionFromArray( array $fields, RevisionSlots $slots );

}