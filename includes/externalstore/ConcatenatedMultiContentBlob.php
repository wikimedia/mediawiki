<?php

namespace MediaWiki\ExternalStore;

use ConcatenatedGzipHistoryBlob;

/**
 * Concatenated storage
 *
 * Improves compression ratio by concatenating like objects before gzipping
 *
 * @since 1.32
 */
class ConcatenatedMultiContentBlob extends MultiContentBlob {

	/** Content items */
	protected $items = [];

	/** Current size */
	protected $size = 0;

	public function getSize() {
		return $this->size;
	}

	public function getCount() {
		return count( $this->items );
	}

	public function addItem( $text ) {
		$hash = md5( $text );
		if ( !isset( $this->items[$hash] ) ) {
			$this->items[$hash] = $text;
			$this->size += strlen( $text );
		}
		return $hash;
	}

	public function getItem( $hash ) {
		if ( array_key_exists( $hash, $this->items ) ) {
			return $this->items[$hash];
		} else {
			return false;
		}
	}

	protected function getData() {
		return [ $this->items, null ];
	}

	protected function setData( array $data, array $metadata = null ) {
		if ( $metadata !== null ) {
			throw new \InvalidArgumentException( __CLASS__ . ' takes no metadata' );
		}

		$this->items = $data;
		$this->size = array_reduce( $data, function ( $a, $v ) {
			return $a + strlen( $v );
		}, 0 );
	}

	/**
	 * For migration, create a ConcatenatedMultiContentBlob from a ConcatenatedGzipHistoryBlob
	 * @param ConcatenatedGzipHistoryBlob $blob
	 * @return ConcatenatedMultiContentBlob
	 */
	public static function newFromConcatenatedGzipHistoryBlob( ConcatenatedGzipHistoryBlob $blob ) {
		$ret = new self;
		$ret->setData( $blob->mItems, null );
		return $ret;
	}

}
