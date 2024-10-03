<?php
/**
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
