<?php
/**
 * Helper class for the --prefetch option of dumpTextPass.php
 *
 * @file
 * @ingrouo Maintenance
 */

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
 * Requires the XMLReader PECL extension.
 * @ingroup Maintenance
 */
class BaseDump {
	var $reader = null;
	var $atEnd = false;
	var $atPageEnd = false;
	var $lastPage = 0;
	var $lastRev = 0;

	function BaseDump( $infile ) {
		$this->reader = new XMLReader();
		$this->reader->open( $infile );
	}

	/**
	 * Attempts to fetch the text of a particular page revision
	 * from the dump stream. May return null if the page is
	 * unavailable.
	 *
	 * @param $page Integer: ID number of page to read
	 * @param $rev Integer: ID number of revision to read
	 * @return string or null
	 */
	function prefetch( $page, $rev ) {
		$page = intval( $page );
		$rev = intval( $rev );
		while ( $this->lastPage < $page && !$this->atEnd ) {
			$this->debug( "BaseDump::prefetch at page $this->lastPage, looking for $page" );
			$this->nextPage();
		}
		if ( $this->lastPage > $page || $this->atEnd ) {
			$this->debug( "BaseDump::prefetch already past page $page looking for rev $rev  [$this->lastPage, $this->lastRev]" );
			return null;
		}
		while ( $this->lastRev < $rev && !$this->atEnd && !$this->atPageEnd ) {
			$this->debug( "BaseDump::prefetch at page $this->lastPage, rev $this->lastRev, looking for $page, $rev" );
			$this->nextRev();
		}
		if ( $this->lastRev == $rev && !$this->atEnd ) {
			$this->debug( "BaseDump::prefetch hit on $page, $rev [$this->lastPage, $this->lastRev]" );
			return $this->nextText();
		} else {
			$this->debug( "BaseDump::prefetch already past rev $rev on page $page  [$this->lastPage, $this->lastRev]" );
			return null;
		}
	}

	function debug( $str ) {
		wfDebug( $str . "\n" );
		// global $dumper;
		// $dumper->progress( $str );
	}

	/**
	 * @access private
	 */
	function nextPage() {
		if ( $this->skipTo( 'page', 'mediawiki' ) ) {
			if ( $this->skipTo( 'id' ) ) {
				$this->lastPage = intval( $this->nodeContents() );
				$this->lastRev = 0;
				$this->atPageEnd = false;
			}
		} else {
			$this->atEnd = true;
		}
	}

	/**
	 * @access private
	 */
	function nextRev() {
		if ( $this->skipTo( 'revision' ) ) {
			if ( $this->skipTo( 'id' ) ) {
				$this->lastRev = intval( $this->nodeContents() );
			}
		} else {
			$this->atPageEnd = true;
		}
	}

	/**
	 * @access private
	 */
	function nextText() {
		$this->skipTo( 'text' );
		return strval( $this->nodeContents() );
	}

	/**
	 * @access private
	 */
	function skipTo( $name, $parent = 'page' ) {
		if ( $this->atEnd ) {
			return false;
		}
		while ( $this->reader->read() ) {
			if ( $this->reader->nodeType == XMLReader::ELEMENT &&
				$this->reader->name == $name ) {
				return true;
			}
			if ( $this->reader->nodeType == XMLReader::END_ELEMENT &&
				$this->reader->name == $parent ) {
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
	 * @return String
	 * @access private
	 */
	function nodeContents() {
		if ( $this->atEnd ) {
			return null;
		}
		if ( $this->reader->isEmptyElement ) {
			return "";
		}
		$buffer = "";
		while ( $this->reader->read() ) {
			switch( $this->reader->nodeType ) {
			case XMLReader::TEXT:
//			case XMLReader::WHITESPACE:
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
	 * @access private
	 */
	function close() {
		$this->reader->close();
		$this->atEnd = true;
		return null;
	}
}
