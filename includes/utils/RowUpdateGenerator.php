<?php
/**
 * Interface for generating updates to single rows in the database.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Maintenance
 */
interface RowUpdateGenerator {
	/**
	 * Given a database row, generates an array mapping column names to
	 * updated value within the database row.
	 *
	 * Sample Response:
	 *   return array(
	 *       'some_col' => 'new value',
	 *       'other_col' => 99,
	 *   );
	 *
	 * @param stdClass $row A row from the database
	 * @return array Map of column names to updated value within the
	 *  database row. When no update is required returns an empty array.
	 */
	public function update( $row );
}
