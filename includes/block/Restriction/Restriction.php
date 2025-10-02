<?php
/**
 * Block restriction interface.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block\Restriction;

use MediaWiki\Title\Title;

interface Restriction {

	/**
	 * Get the ID of the block.
	 *
	 * @since 1.33
	 * @return int
	 */
	public function getBlockId();

	/**
	 * Set the ID of the block.
	 *
	 * @since 1.33
	 * @param int $blockId
	 * @return self
	 */
	public function setBlockId( $blockId );

	/**
	 * Get the value of the restriction.
	 *
	 * @since 1.33
	 * @return int
	 */
	public function getValue();

	/**
	 * Get the type of restriction
	 *
	 * @since 1.33
	 * @return string
	 */
	public static function getType();

	/**
	 * Get the ID of the type of restriction. This ID is used in the database.
	 *
	 * @since 1.33
	 * @return int
	 */
	public static function getTypeId();

	/**
	 * Create a new Restriction from a database row.
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
	 * @param Title $title
	 * @return bool
	 */
	public function matches( Title $title );

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
