<?php
/**
 * Source interface for XML import.
 *
 * Copyright © 2003,2005 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup SpecialPage
 */

/**
 * Source interface for XML import.
 *
 * @ingroup SpecialPage
 */
interface ImportSource {

	/**
	 * Indicates whether the end of the input has been reached.
	 * Will return true after a finite number of calls to readChunk.
	 *
	 * @return bool true if there is no more input, false otherwise.
	 */
	public function atEnd();

	/**
	 * Return a chunk of the input, as a (possibly empty) string.
	 * When the end of input is reached, readChunk() returns false.
	 * If atEnd() returns false, readChunk() will return a string.
	 * If atEnd() returns true, readChunk() will return false.
	 *
	 * @return bool|string
	 */
	public function readChunk();

	/**
	 * Check if the source is seekable and a call to self::seek is valid
	 *
	 * @return bool
	 */
	public function isSeekable();

	/**
	 * Seek the input to the given offset.
	 *
	 * @param int $offset
	 * @return int
	 */
	public function seek( int $offset );
}
