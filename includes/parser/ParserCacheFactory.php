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

use BagOStuff;
use IBufferingStatsdDataFactory;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Json\JsonUnserializer;
use ParserCache;
use Psr\Log\LoggerInterface;

/**
 * Returns an instance of the ParserCache by its name.
 * @since 1.36
 * @package MediaWiki\Parser
 */
class ParserCacheFactory {

	/** @var string name of ParserCache for the default parser */
	public const DEFAULT_NAME = 'pcache';

	/** @var BagOStuff */
	private $cacheBackend;

	/** @var string */
	private $cacheEpoch;

	/** @var HookContainer */
	private $hookContainer;

	/** @var JsonUnserializer */
	private $jsonUnserializer;

	/** @var IBufferingStatsdDataFactory */
	private $stats;

	/** @var LoggerInterface */
	private $logger;

	/** @var array */
	private $instanceCache = [];

	/**
	 * @note Temporary feature flag, remove before 1.36 is released.
	 * @var bool
	 */
	private $useJson = false;

	/**
	 * @param BagOStuff $cacheBackend
	 * @param string $cacheEpoch
	 * @param HookContainer $hookContainer
	 * @param JsonUnserializer $jsonUnserializer
	 * @param IBufferingStatsdDataFactory $stats
	 * @param LoggerInterface $logger
	 * @param bool $useJson Temporary feature flag, remove before 1.36 is released.
	 */
	public function __construct(
		BagOStuff $cacheBackend,
		string $cacheEpoch,
		HookContainer $hookContainer,
		JsonUnserializer $jsonUnserializer,
		IBufferingStatsdDataFactory $stats,
		LoggerInterface $logger,
		$useJson = false
	) {
		$this->cacheBackend = $cacheBackend;
		$this->cacheEpoch = $cacheEpoch;
		$this->hookContainer = $hookContainer;
		$this->jsonUnserializer = $jsonUnserializer;
		$this->stats = $stats;
		$this->logger = $logger;
		$this->useJson = $useJson;
	}

	/**
	 * Get a ParserCache instance by $name.
	 * @param string $name
	 * @return ParserCache
	 */
	public function getInstance( string $name ) : ParserCache {
		if ( !isset( $this->instanceCache[$name] ) ) {
			$this->logger->debug( "Creating ParserCache instance for {$name}" );
			$cache = new ParserCache(
				$name,
				$this->cacheBackend,
				$this->cacheEpoch,
				$this->hookContainer,
				$this->jsonUnserializer,
				$this->stats,
				$this->logger,
				$this->useJson
			);

			$this->instanceCache[$name] = $cache;
		}
		return $this->instanceCache[$name];
	}
}
