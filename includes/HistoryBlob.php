<?php
/**
 *
 * @package MediaWiki
 */

/**
 * Pure virtual parent
 * @package MediaWiki
 */
class HistoryBlob
{
	# setMeta and getMeta currently aren't used for anything, I just thought they might be useful in the future
	# The meta value is a single string
	function setMeta( $meta ) {}

	# Gets the meta-value
	function getMeta() {}

	# Adds an item of text, returns a stub object which points to the item
	# You must call setLocation() on the stub object before storing it to the database
	function addItem() {}

	# Get item by hash
	function getItem( $hash ) {}
	
	# Set the "default text"
	# This concept is an odd property of the current DB schema, whereby each text item has a revision
	# associated with it. The default text is the text of the associated revision. There may, however, 
	# be other revisions in the same object
	function setText() {}

	# Get default text. This is called from Article::getRevisionText()
	function getText() {}
}

/**
 * The real object
 * @package MediaWiki
 */
class ConcatenatedGzipHistoryBlob extends HistoryBlob
{
	/* private */ var $mVersion = 0, $mCompressed = false, $mItems = array(), $mDefaultHash = '';
	/* private */ var $mFast = 0, $mSize = 0;

	function ConcatenatedGzipHistoryBlob() {
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
		$hash = md5( $text );
		$this->mItems[$hash] = $text;
		$this->mSize += strlen( $text );

		$stub = new HistoryBlobStub( $hash );
		return $stub;
	}

	function getItem( $hash ) {
		$this->uncompress();
		if ( array_key_exists( $hash, $this->mItems ) ) {
			return $this->mItems[$hash];
		} else {
			return false;
		}
	}

	function removeItem( $hash ) {
		$this->mSize -= strlen( $this->mItems[$hash] );
		unset( $this->mItems[$hash] );
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
			$this->mCompressed = false;
		}
	}

	function getText() {
		$this->uncompress();
		return $this->getItem( $this->mDefaultHash );
	}
	
	function setText( $text ) {
		$this->uncompress();
		$stub = $this->addItem( $text );
		$this->mDefaultHash = $stub->mHash;
	}

	function __sleep() {
		$this->compress();
		return array( 'mVersion', 'mCompressed', 'mItems', 'mDefaultHash' );
	}

	function __wakeup() {
		$this->uncompress();
	}

	# Determines if this object is happy
	function isHappy( $maxFactor, $factorThreshold ) {
		if ( count( $this->mItems ) == 0 ) {
			return true;
		}
		if ( $this->mFast ) {
			$this->uncompress();
			$record = serialize( $this->mItems );
			$size = strlen( $record );
			$avgUncompressed = $size / count( $this->mItems );
			$compressed = strlen( gzdeflate( $record ) );

			if ( $compressed < $factorThreshold * 1024 ) {
				return true;
			} else {
				return $avgUncompressed * $maxFactor < $compressed;
			}
		} else {
			return count( $this->mItems ) <= 10;
		}
	}
}

class HistoryBlobStub
{
	var $mOldId, $mHash;

	function HistoryBlobStub( $hash = '', $oldid = 0 ) {
		$this->mHash = $hash;
	}
	
	# Sets the location (old_id) of the main object to which this object points
	function setLocation( $id ) {
		$this->mOldId = $id;
	}
	
	function getText() {
		$dbr =& wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'old', array( 'old_flags', 'old_text' ), array( 'old_id' => $this->mOldId ) );
		if ( !$row || $row->old_flags != 'object' ) {
			return false;
		}
		$obj = unserialize( $row->old_text );
		if ( !is_object( $obj ) ) {
			$obj = unserialize( $obj );
		}
		return $obj->getItem( $this->mHash );
	}

	function getHash() {
		return $this->mHash;
	}
}
?>
