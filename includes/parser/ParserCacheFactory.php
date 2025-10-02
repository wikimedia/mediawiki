<?php
/**
 * @license GPL-2.0-or-later
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
use Psr\Log\LoggerInterface;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
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

	/** @var ParserCache[] */
	private $parserCaches = [];

	/** @var RevisionOutputCache[] */
	private $revisionOutputCaches = [];

	/**
	 * @internal
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::CacheEpoch,
		MainConfigNames::ParserCacheFilterConfig,
		MainConfigNames::OldRevisionParserCacheExpireTime,
	];

	public function __construct(
		private readonly BagOStuff $parserCacheBackend,
		private readonly WANObjectCache $revisionOutputCacheBackend,
		private readonly HookContainer $hookContainer,
		private readonly JsonCodec $jsonCodec,
		private readonly StatsFactory $stats,
		private readonly LoggerInterface $logger,
		private readonly ServiceOptions $options,
		private readonly TitleFactory $titleFactory,
		private readonly WikiPageFactory $wikiPageFactory,
		private readonly GlobalIdGenerator $globalIdGenerator,
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
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
