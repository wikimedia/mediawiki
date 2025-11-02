<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace MediaWiki\Linker;

use RuntimeException;
use stdClass;
use Wikimedia\Rdbms\IDatabase;

/**
 * @since 1.38
 */
interface LinkTargetLookup {
	/**
	 * Instantiate a new LinkTarget object based on a $row from the linktarget table.
	 *
	 * Use this method when a linktarget row was already fetched from the DB via a join.
	 * This method just constructs a new instance and does not try fetching missing
	 * values from the DB again.
	 *
	 * @param stdClass $row with the following fields:
	 *  - int lt_id
	 *  - int lt_namespace
	 *  - string lt_title
	 * @return LinkTarget
	 */
	public function newLinkTargetFromRow( stdClass $row ): LinkTarget;

	/**
	 * Find a link target by $id.
	 *
	 * @param int $linkTargetId
	 * @return LinkTarget|null Returns null if no link target with this $linkTargetId exists in the database.
	 */
	public function getLinkTargetById( int $linkTargetId ): ?LinkTarget;

	/**
	 * Attempt to assign an link target ID to the given $linkTarget. If it is already assigned,
	 * return the existing ID.
	 *
	 * @note If called within a transaction, the returned ID might become invalid
	 * if the transaction is rolled back, so it should not be passed outside of the
	 * transaction context.
	 *
	 * @param LinkTarget $linkTarget
	 * @param IDatabase $dbw The database connection to acquire the ID from.
	 * @return int linktarget ID greater then 0
	 * @throws RuntimeException if no linktarget ID has been assigned to this $linkTarget
	 */
	public function acquireLinkTargetId( LinkTarget $linkTarget, IDatabase $dbw ): int;

	/**
	 * Return link target id if exists
	 *
	 * @param LinkTarget $linkTarget
	 * @return int|null linktarget ID greater then 0, null if not found
	 */
	public function getLinkTargetId( LinkTarget $linkTarget ): ?int;

}
