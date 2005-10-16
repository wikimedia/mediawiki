<?php

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
 * Requires PHP 5 and the XMLReader PECL extension.
 */
class BaseDump {
	var $reader = null;
	var $atEnd = false;
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
	 * @param int $page ID number of page to read
	 * @param int $rev ID number of revision to read
	 * @return string or null
	 */
	function prefetch( $page, $rev ) {
		while( $this->lastPage < $page && !$this->atEnd ) {
			$this->nextPage();
		}
		if( $this->lastPage > $page || $this->atEnd ) {
			$this->debug( "BaseDump::prefetch already past page $page looking for rev $rev\n" );
			return null;
		}
		while( $this->lastRev < $rev && !$this->atEnd ) {
			$this->nextRev();
		}
		if( $this->lastRev == $rev ) {
			$this->debug( "BaseDump::prefetch hit on $page, $rev\n" );
			return $this->nextText();
		} else {
			$this->debug( "BaseDump::prefetch already past rev $rev on page $page\n" );
			return null;
		}
	}
	
	function debug( $str ) {
		wfDebug( $str );
		//global $dumper;
		//$dumper->progress( $str );
	}
	
	/**
	 * @access private
	 */
	function nextPage() {
		$this->skipTo( 'page' );
		$this->skipTo( 'id' );
		$this->lastPage = intval( $this->nodeContents() );
		$this->lastRev = 0;
	}
	
	/**
	 * @access private
	 */
	function nextRev() {
		$this->skipTo( 'revision' );
		$this->skipTo( 'id' );
		$this->lastRev = intval( $this->nodeContents() );
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
	function skipTo( $name ) {
		if( $this->atEnd ) {
			return false;
		}
		while( $this->reader->read() ) {
			if( $this->reader->nodeType == XMLREADER_ELEMENT &&
				$this->reader->name == $name ) {
				return true;
			}
		}
		return $this->close();
	}
	
	/**
	 * Shouldn't something like this be built-in to XMLReader?
	 * Fetches text contents of the current element, assuming
	 * no sub-elements or such scary things.
	 * @return string
	 * @access private
	 */
	function nodeContents() {
		if( $this->atEnd ) {
			return false;
		}
		if( $this->reader->isEmptyElement ) {
			return "";
		}
		$buffer = "";
		while( $this->reader->read() ) {
			switch( $this->reader->nodeType ) {
			case XMLREADER_TEXT:
//			case XMLREADER_WHITESPACE:
			case XMLREADER_SIGNIFICANT_WHITESPACE:
				$buffer .= $this->reader->value;
				break;
			case XMLREADER_END_ELEMENT:
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
		return false;
	}
}

?>
