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

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Json\JsonCodec;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Title\TitleFactory;
use ParserCache;
use Psr\Log\LoggerInterface;
use WANObjectCache;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * Returns an instance of the ParserCache by its name.
 * @since 1.36
 * @package MediaWiki\Parser
 */
class ParserCacheFactory {

	/** @var string name of ParserCache for the default parser */
	public const DEFAULT_NAME = 'pcache';

	/** @var string name of RevisionOutputCache for the default parser */
	public const DEFAULT_RCACHE_NAME = 'rcache';

	/** @var BagOStuff */
	private $parserCacheBackend;

	/** @var WANObjectCache */
	private $revisionOutputCacheBackend;

	/** @var HookContainer */
	private $hookContainer;

	/** @var JsonCodec */
	private $jsonCodec;

	/** @var StatsFactory */
	private $stats;

	/** @var LoggerInterface */
	private $logger;

	/** @var TitleFactory */
	private $titleFactory;

	/** @var WikiPageFactory */
	private $wikiPageFactory;

	private GlobalIdGenerator $globalIdGenerator;

	/** @var ParserCache[] */
	private $parserCaches = [];

	/** @var RevisionOutputCache[] */
	private $revisionOutputCaches = [];

	/** @var ServiceOptions */
	private $options;

	/**
	 * @internal
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::CacheEpoch,
		MainConfigNames::ParserCacheFilterConfig,
		MainConfigNames::OldRevisionParserCacheExpireTime,
	];

	/**
	 * @param BagOStuff $parserCacheBackend
	 * @param WANObjectCache $revisionOutputCacheBackend
	 * @param HookContainer $hookContainer
	 * @param JsonCodec $jsonCodec
	 * @param StatsFactory $stats
	 * @param LoggerInterface $logger
	 * @param ServiceOptions $options
	 * @param TitleFactory $titleFactory
	 * @param WikiPageFactory $wikiPageFactory
	 * @param GlobalIdGenerator $globalIdGenerator
	 */
	public function __construct(
		BagOStuff $parserCacheBackend,
		WANObjectCache $revisionOutputCacheBackend,
		HookContainer $hookContainer,
		JsonCodec $jsonCodec,
		StatsFactory $stats,
		LoggerInterface $logger,
		ServiceOptions $options,
		TitleFactory $titleFactory,
		WikiPageFactory $wikiPageFactory,
		GlobalIdGenerator $globalIdGenerator
	) {
		$this->parserCacheBackend = $parserCacheBackend;
		$this->revisionOutputCacheBackend = $revisionOutputCacheBackend;
		$this->hookContainer = $hookContainer;
		$this->jsonCodec = $jsonCodec;
		$this->stats = $stats;
		$this->logger = $logger;

		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->titleFactory = $titleFactory;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->globalIdGenerator = $globalIdGenerator;
	}

	/**
	 * Get a ParserCache instance by $name.
	 * @param string $name
	 * @return ParserCache
	 */
	public function getParserCache( string $name ): ParserCache {
		if ( !isset( $this->parserCaches[$name] ) ) {
			$this->logger->debug( "Creating ParserCache instance for {$name}" );
			$cache = new ParserCache(
				$name,
				$this->parserCacheBackend,
				$this->options->get( MainConfigNames::CacheEpoch ),
				$this->hookContainer,
				$this->jsonCodec,
				$this->stats,
				$this->logger,
				$this->titleFactory,
				$this->wikiPageFactory,
				$this->globalIdGenerator
			);

			$filterConfig = $this->options->get( MainConfigNames::ParserCacheFilterConfig );

			if ( isset( $filterConfig[$name] ) ) {
				$filter = new ParserCacheFilter( $filterConfig[$name] );
				$cache->setFilter( $filter );
			}

			$this->parserCaches[$name] = $cache;
		}
		return $this->parserCaches[$name];
	}

	/**
	 * Get a RevisionOutputCache instance by $name.
	 * @param string $name
	 * @return RevisionOutputCache
	 */
	public function getRevisionOutputCache( string $name ): RevisionOutputCache {
		if ( !isset( $this->revisionOutputCaches[$name] ) ) {
			$this->logger->debug( "Creating RevisionOutputCache instance for {$name}" );
			$cache = new RevisionOutputCache(
				$name,
				$this->revisionOutputCacheBackend,
				$this->options->get( MainConfigNames::OldRevisionParserCacheExpireTime ),
				$this->options->get( MainConfigNames::CacheEpoch ),
				$this->jsonCodec,
				$this->stats,
				$this->logger,
				$this->globalIdGenerator
			);

			$this->revisionOutputCaches[$name] = $cache;
		}
		return $this->revisionOutputCaches[$name];
	}
}
