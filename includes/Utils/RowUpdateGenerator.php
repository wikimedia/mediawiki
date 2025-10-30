<?php
/**
 * @license GPL-2.0-or-later
 */

/**
 * Interface for generating updates to single rows in the database.
 *
 * @stable to implement
 * @ingroup Maintenance
 */
interface RowUpdateGenerator {
	/**
	 * Given a database row, generates an array mapping column names to
	 * updated value within the database row.
	 *
	 * Sample Response:
	 *   return [
	 *       'some_col' => 'new value',
	 *       'other_col' => 99,
	 *   ];
	 *
	 * @param stdClass $row A row from the database
	 * @return array Map of column names to updated value within the
	 *  database row. When no update is required returns an empty array.
	 */
	public function update( $row );
}
