<?php
/**
 * Created by PhpStorm.
 * User: daki
 * Date: 30.08.17
 * Time: 17:38
 */
namespace MediaWiki\Storage;

/**
 * Service for storing and loading Content objects.
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in Revision.
 */
interface BlobLookup {

	/**
	 * Retrieve a blob, given an address.
	 *
	 * MCR migration note: this replaces Revision::loadText
	 *
	 * @note Passing an int as $blobAddress is deprecated. For referencing blobs in the text table,
	 *       use the "tt:" prefix, as in "tt:12345".
	 *
	 * @param string|int $blobAddress The blob address. If given as an int, this is interpreted
	 *        to refer to the old_id field in the text table.
	 * @param int $queryFlags
	 * @return string|false
	 */
	public function getBlob( $blobAddress, $queryFlags = 0 );

}