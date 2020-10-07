<?php
/**
 * Helper class for the --prefetch option of dumpTextPass.php
 *
 * Copyright © 2005 Brion Vibber <brion@pobox.com>
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
 * @ingroup Maintenance
 */

use MediaWiki\Revision\SlotRecord;

/**
 * Readahead helper for making large MediaWiki data dumps;
 * reads in a previous XML dump to sequentially prefetch text
 * records already normalized and decompressed.
 *
 * This can save load on the external database servers, hopefully.
 *
 * Assumes that dumps will be recorded in the canonical order:
 * - ascending by page_id
 * - ascending by rev_id within each page
 * - text contents are immutable and should not change once
 *   recorded, so the previous dump is a reliable source
 *
 * @ingroup Maintenance
 */
class BaseDump {
	/** @var XMLReader */
	protected $reader = null;
	protected $atEnd = false;
	protected $atPageEnd = false;
	protected $lastPage = 0;
	protected $lastRev = 0;
	protected $infiles = null;

	public function __construct( $infile ) {
		$this->infiles = explode( ';', $infile );
		$this->reader = new XMLReader();
		$infile = array_shift( $this->infiles );
		$this->reader->open( $infile, null, LIBXML_PARSEHUGE );
	}

	/**
	 * Attempts to fetch the text of a particular page revision
	 * from the dump stream. May return null if the page is
	 * unavailable.
	 *
	 * @param int $page ID number of page to read
	 * @param int $rev ID number of revision to read
	 * @param string $slot Role name of the slot to read
	 * @return string|null
	 */
	public function prefetch( $page, $rev, $slot = SlotRecord::MAIN ) {
		$page = intval( $page );
		$rev = intval( $rev );
		while ( $this->lastPage < $page && !$this->atEnd ) {
			$this->debug( "BaseDump::prefetch at page $this->lastPage, looking for $page" );
			$this->nextPage();
		}
		if ( $this->lastPage > $page || $this->atEnd ) {
			$this->debug( "BaseDump::prefetch already past page $page "
				. "looking for rev $rev  [$this->lastPage, $this->lastRev]" );

			return null;
		}
		while ( $this->lastRev < $rev && !$this->atEnd && !$this->atPageEnd ) {
			$this->debug( "BaseDump::prefetch at page $this->lastPage, rev $this->lastRev, "
				. "looking for $page, $rev" );
			$this->nextRev();
		}
		if ( $this->lastRev == $rev && !$this->atEnd ) {
			$this->debug( "BaseDump::prefetch hit on $page, $rev [$this->lastPage, $this->lastRev]" );

			if ( $slot !== SlotRecord::MAIN ) {
				$lastSlot = SlotRecord::MAIN;
				while ( $lastSlot !== $slot ) {
					if ( !$this->skipTo( 'content', 'revision' ) ) {
						return null;
					}
					if ( !$this->skipTo( 'role', 'revision' ) ) {
						return null;
					}
					$lastSlot = $this->nodeContents();
				}
			}

			return $this->nextText();
		} else {
			$this->debug( "BaseDump::prefetch already past rev $rev on page $page "
				. "[$this->lastPage, $this->lastRev]" );

			return null;
		}
	}

	protected function debug( $str ) {
		wfDebug( $str );
		// global $dumper;
		// $dumper->progress( $str );
	}

	private function nextPage() {
		if ( $this->skipTo( 'page', 'mediawiki' ) ) {
			if ( $this->skipTo( 'id' ) ) {
				$this->lastPage = intval( $this->nodeContents() );
				$this->lastRev = 0;
				$this->atPageEnd = false;
			}
		} else {
			$this->close();
			if ( count( $this->infiles ) ) {
				$infile = array_shift( $this->infiles );
				$this->reader->open( $infile, null, LIBXML_PARSEHUGE );
				$this->atEnd = false;
			}
		}
	}

	private function nextRev() {
		if ( $this->skipTo( 'revision' ) ) {
			if ( $this->skipTo( 'id' ) ) {
				$this->lastRev = intval( $this->nodeContents() );
			}
		} else {
			$this->atPageEnd = true;
		}
	}

	/**
	 * @return string
	 */
	private function nextText() {
		if ( !$this->skipTo( 'text', 'revision' ) ) {
			return null;
		}

		return strval( $this->nodeContents() );
	}

	/**
	 * @param string $name
	 * @param string $parent
	 * @return bool|null
	 */
	private function skipTo( $name, $parent = 'page' ) {
		if ( $this->atEnd ) {
			return false;
		}
		while ( $this->reader->read() ) {
			if ( $this->reader->nodeType == XMLReader::ELEMENT
				&& $this->reader->name == $name
			) {
				return true;
			}
			if ( $this->reader->nodeType == XMLReader::END_ELEMENT
				&& $this->reader->name == $parent
			) {
				$this->debug( "BaseDump::skipTo found </$parent> searching for <$name>" );

				return false;
			}
		}

		return $this->close();
	}

	/**
	 * Shouldn't something like this be built-in to XMLReader?
	 * Fetches text contents of the current element, assuming
	 * no sub-elements or such scary things.
	 *
	 * @return string
	 */
	private function nodeContents() {
		if ( $this->atEnd ) {
			return null;
		}
		if ( $this->reader->isEmptyElement ) {
			return "";
		}
		$buffer = "";
		while ( $this->reader->read() ) {
			switch ( $this->reader->nodeType ) {
				case XMLReader::TEXT:
				// case XMLReader::WHITESPACE:
				case XMLReader::SIGNIFICANT_WHITESPACE:
					$buffer .= $this->reader->value;
					break;
				case XMLReader::END_ELEMENT:
					return $buffer;
			}
		}

		return $this->close();
	}

	/**
	 * @return null
	 */
	public function close() {
		$this->reader->close();
		$this->atEnd = true;

		return null;
	}
}
