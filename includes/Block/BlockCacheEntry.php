<?php

namespace MediaWiki\Block;

/**
 * @internal For use by BlockManager
 */
class BlockCacheEntry {
	public BlockCacheKey $key;
	/** @var AbstractBlock|false */
	public $block;

	/**
	 * @param BlockCacheKey $key
	 * @param AbstractBlock|false $block
	 */
	public function __construct( BlockCacheKey $key, $block ) {
		$this->key = $key;
		$this->block = $block;
	}
}
