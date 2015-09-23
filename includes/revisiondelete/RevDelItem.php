<?php
/**
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
 * @ingroup RevisionDelete
 */

/**
 * Abstract base class for deletable items
 */
abstract class RevDelItem extends RevisionItemBase {
	/**
	 * Returns true if the item is "current", and the operation to set the given
	 * bits can't be executed for that reason
	 * STUB
	 * @param int $newBits
	 * @return bool
	 */
	public function isHideCurrentOp( $newBits ) {
		return false;
	}

	/**
	 * Get the current deletion bitfield value
	 *
	 * @return integer
	 */
	abstract public function getBits();

	/**
	 * Set the visibility of the item. This should do any necessary DB queries.
	 *
	 * The DB update query should have a condition which forces it to only update
	 * if the value in the DB matches the value fetched earlier with the SELECT.
	 * If the update fails because it did not match, the function should return
	 * false. This prevents concurrency problems.
	 *
	 * @param int $newBits
	 * @return bool Success
	 */
	abstract public function setBits( $newBits );

	/**
	 * Get the return information about the revision for the API
	 * @since 1.23
	 * @param ApiResult $result API result object
	 * @return array Data for the API result
	 */
	abstract public function getApiData( ApiResult $result );
}
