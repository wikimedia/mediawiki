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
 */

namespace MediaWiki\Edit;

use Content;
use ParserOptions;
use ParserOutput;
use RuntimeException;

/**
 * Represents information returned by WikiPage::prepareContentForEdit()
 *
 * @deprecated since 1.32, use DerivedPageDataUpdater instead.
 *
 * @since 1.30
 */
class PreparedEdit {
	/**
	 * Time this prepared edit was made
	 *
	 * @var string
	 */
	public $timestamp;

	/**
	 * Revision ID
	 *
	 * @var int|null
	 */
	public $revid;

	/**
	 * Content after going through pre-save transform
	 *
	 * @var Content|null
	 */
	public $pstContent;

	/**
	 * Content format
	 *
	 * @var string
	 */
	public $format;

	/**
	 * Parser options used to get parser output
	 *
	 * @var ParserOptions
	 */
	public $popts;

	/**
	 * Parser output
	 *
	 * @var ParserOutput|null
	 */
	private $canonicalOutput;

	/**
	 * Content that is being saved (before PST)
	 *
	 * @var Content
	 */
	public $newContent;

	/**
	 * Current content of the page, if any
	 *
	 * @var Content|null
	 */
	public $oldContent;

	/**
	 * Lazy-loading callback to get canonical ParserOutput object
	 *
	 * @var callable
	 */
	public $parserOutputCallback;

	/**
	 * @return ParserOutput Canonical parser output
	 */
	public function getOutput() {
		if ( !$this->canonicalOutput ) {
			$this->canonicalOutput = call_user_func( $this->parserOutputCallback );
		}

		return $this->canonicalOutput;
	}

	/**
	 * Fetch the ParserOutput via a lazy-loaded callback (for backwards compatibility).
	 *
	 * @deprecated since 1.33
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name ) {
		if ( $name === 'output' ) {
			return $this->getOutput();
		}

		throw new RuntimeException( "Undefined field $name." );
	}
}
