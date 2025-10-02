<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Cache Parser
 */

namespace MediaWiki\Parser;

use MediaWiki\Page\PageRecord;

/**
 * Filter that decides whether a ParserOutput object should be stored in the
 * ParserCache or not. Can be used tweak tradeoffs between storage space and
 * response latency.
 *
 * @since 1.42
 * @package MediaWiki\Parser
 */
class ParserCacheFilter {

	/**
	 * @see MainConfigSchema::ParserCacheFilterConfig
	 */
	private array $config;

	/**
	 * @param array[] $config See MainConfigSchema::ParserCacheFilterConfig.
	 */
	public function __construct( array $config ) {
		$this->config = $config;
	}

	public function shouldCache(
		ParserOutput $output,
		PageRecord $page,
		ParserOptions $options
	): bool {
		$ns = $page->getNamespace();
		$cpuMin = $this->config[ $ns ]['minCpuTime']
			?? ( $this->config['default']['minCpuTime'] ?? 0 );

		$cpuTime = $output->getTimeProfile( 'cpu' );

		if ( $cpuTime !== null && $cpuTime < $cpuMin ) {
			return false;
		}

		return true;
	}

}
