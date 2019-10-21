<?php
/**
 * Contains a class for dealing with individual log entries
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
 * @author Niklas Laxström
 * @license GPL-2.0-or-later
 * @since 1.19
 */

/**
 * Interface for log entries. Every log entry has these methods.
 *
 * @since 1.19
 */
interface LogEntry {

	/**
	 * The main log type.
	 *
	 * @return string
	 */
	public function getType();

	/**
	 * The log subtype.
	 *
	 * @return string
	 */
	public function getSubtype();

	/**
	 * The full logtype in format maintype/subtype.
	 *
	 * @return string
	 */
	public function getFullType();

	/**
	 * Get the extra parameters stored for this message.
	 *
	 * @return array
	 */
	public function getParameters();

	/**
	 * Get the user who performed this action.
	 *
	 * @return User
	 */
	public function getPerformer();

	/**
	 * Get the target page of this action.
	 *
	 * @return Title
	 */
	public function getTarget();

	/**
	 * Get the timestamp when the action was executed.
	 *
	 * @return string
	 */
	public function getTimestamp();

	/**
	 * Get the user provided comment.
	 *
	 * @return string
	 */
	public function getComment();

	/**
	 * Get the access restriction.
	 *
	 * @return int
	 */
	public function getDeleted();

	/**
	 * @param int $field One of LogPage::DELETED_* bitfield constants
	 * @return bool
	 */
	public function isDeleted( $field );
}
