<?php
/**
 * MediaWiki page data importer.
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
 * Used for importing XML dumps where the content of the dump is in a string.
 * This class is ineffecient, and should only be used for small dumps.
 * For larger dumps, ImportStreamSource should be used instead.
 *
 * @ingroup SpecialPage
 */
class ImportStringSource implements ImportSource {
	/** @var string */
	private $mString;

	/** @var bool */
	private $mRead;

	/**
	 * @param string $string
	 */
	public function __construct( $string ) {
		$this->mString = $string;
		$this->mRead = false;
	}

	/**
	 * @return bool
	 */
	public function atEnd() {
		return $this->mRead;
	}

	/**
	 * @return bool|string
	 */
	public function readChunk() {
		if ( $this->atEnd() ) {
			return false;
		}
		$this->mRead = true;
		return $this->mString;
	}
}
