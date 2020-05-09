<?php
/**
 * Source interface for XML import.
 *
 * Copyright © 2003,2005 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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
}
