<?php
/**
 * Block restriction interface.
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
 */

namespace MediaWiki\Block\Restriction;

interface Restriction {

	/**
	 * Gets the id of the block.
	 *
	 * @since 1.33
	 * @return int
	 */
	public function getBlockId();

	/**
	 * Sets the id of the block.
	 *
	 * @since 1.33
	 * @param int $blockId
	 * @return self
	 */
	public function setBlockId( $blockId );

	/**
	 * Gets the value of the restriction.
	 *
	 * @since 1.33
	 * @return int
	 */
	public function getValue();

	/**
	 * Gets the type of restriction
	 *
	 * @since 1.33
	 * @return string
	 */
	public static function getType();

	/**
	 * Gets the id of the type of restriction. This id is used in the database.
	 *
	 * @since 1.33
	 * @return int
	 */
	public static function getTypeId();

	/**
	 * Creates a new Restriction from a database row.
	 *
	 * @since 1.33
	 * @param \stdClass $row
	 * @return static
	 */
	public static function newFromRow( \stdClass $row );

	/**
	 * Convert a restriction object into a row array for insertion.
	 *
	 * @since 1.33
	 * @return array
	 */
	public function toRow();

	/**
	 * Determine if a restriction matches a given title.
	 *
	 * @since 1.33
	 * @param \Title $title
	 * @return bool
	 */
	public function matches( \Title $title );

	/**
	 * Determine if a restriction equals another restriction.
	 *
	 * @since 1.33
	 * @param Restriction $other
	 * @return bool
	 */
	public function equals( Restriction $other );

	/**
	 * Create a unique hash of the block restriction based on the type and value.
	 *
	 * @since 1.33
	 * @return string
	 */
	public function getHash();

}
