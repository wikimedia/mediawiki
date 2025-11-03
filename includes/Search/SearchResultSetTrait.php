<?php

// @phan-file-suppress PhanUndeclaredMethod

/**
 * Trait useful for SearchResultSet implementations.
 * It holds the functions that are rarely needed to be overridden.
 *
 * This trait can be used directly by extensions providing a SearchEngine.
 *
 * @ingroup Search
 */
trait SearchResultSetTrait {
	/**
	 * Set of result's extra data, indexed per result id
	 * and then per data item name.
	 * The structure is:
	 * PAGE_ID => [ augmentor name => data, ... ]
	 * @var array[]
	 */
	private $extraData = [];

	/**
	 * Sets augmented data for result set.
	 * @param string $name Extra data item name
	 * @param array[] $data Extra data as PAGEID => data
	 */
	public function setAugmentedData( $name, $data ) {
		foreach ( $data as $id => $resultData ) {
			$this->extraData[$id][$name] = $resultData;
		}
	}

	/**
	 * Returns extra data for specific result and store it in SearchResult object.
	 */
	public function augmentResult( SearchResult $result ) {
		$id = $result->getTitle()->getArticleID();
		if ( $id === -1 ) {
			return;
		}
		$result->setExtensionData( function () use ( $id ) {
			return $this->extraData[$id] ?? [];
		} );
	}

	/**
	 * @return int|null The offset the current page starts at. Typically
	 *  this should be null to allow the UI to decide on its own, but in
	 *  special cases like interleaved AB tests specifying explicitly is
	 *  necessary.
	 */
	public function getOffset() {
		return null;
	}

	final public function getIterator(): ArrayIterator {
		return new ArrayIterator( $this->extractResults() );
	}
}
