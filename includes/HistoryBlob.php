<?php
/**
 *
 */

/**
 * Pure virtual parent
 */
class HistoryBlob
{
	function setMeta() {}
	function getMeta() {}
	function addItem() {}
	function getItem() {}
}

/**
 * The real object
 */
class ConcatenatedGzipHistoryBlob
{
	/* private */ var $mVersion = 0, $mCompressed = false, $mItems = array();

	function HistoryBlob() {
		if ( !function_exists( 'gzdeflate' ) ) {
			die( "Need zlib support to read or write this kind of history object (ConcatenatedGzipHistoryBlob)\n" );
		}
	}
	
	function setMeta( $metaData ) {
		$this->uncompress();
		$this->mItems['meta'] = $metaData;
	}

	function getMeta() {
		$this->uncompress();
		return $this->mItems['meta'];
	}
	
	function addItem( $text ) {
		$this->uncompress();
		$this->mItems[md5($text)] = $text;
	}

	function getItem( $hash ) {
		$this->compress();
		return $this->mItems[$hash];
	}

	function compress() {
		if ( !$this->mCompressed  ) {
			$this->mItems = gzdeflate( serialize( $this->mItems ) );
			$this->mCompressed = true;
		}
	}

	function uncompress() { 
		if ( $this->mCompressed ) {
			$this->mItems = unserialize( gzinflate( $this->mItems ) );
		}
	}

	function __sleep() {
		compress();
	}

	function __wakeup() {
		uncompress();
	}
}
?>
