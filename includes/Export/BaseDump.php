<?php
/**
 * Helper class for the --prefetch option of dumpTextPass.php
 *
 * Copyright © 2005 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
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
	/** @var XMLReader|null */
	protected $reader = null;
	/** @var bool */
	protected $atEnd = false;
	/** @var bool */
	protected $atPageEnd = false;
	/** @var int */
	protected $lastPage = 0;
	/** @var int */
	protected $lastRev = 0;
	/** @var string[]|null */
	protected $infiles = null;

	/**
	 * @param string $infile
	 */
	public function __construct( $infile ) {
		$this->infiles = explode( ';', $infile );
		$this->reader = new XMLReader();
		$infile = array_shift( $this->infiles );
		if ( !$this->reader->open( $infile, null, LIBXML_PARSEHUGE ) ) {
			$this->debug( __METHOD__ . ' was unable to open xml' );
			$this->atEnd = true;
		}
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
			$this->debug( "BaseDump::prefetch already past page $page or failed to open/read input file, "
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
					if ( !$this->skipTo( 'content', 'revision' ) ||
						!$this->skipTo( 'role', 'revision' )
					) {
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

	/**
	 * @param string $str
	 */
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
				if ( !$this->reader->open( $infile, null, LIBXML_PARSEHUGE ) ) {
					$this->debug( __METHOD__ . ' was unable to open xml' );
					$this->atEnd = true;
				} else {
					$this->atEnd = false;
				}
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
	 * @return string|null
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
	 * @return string|null
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
