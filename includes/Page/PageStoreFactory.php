<?php

namespace MediaWiki\Page;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\TitleParser;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Stats\StatsFactory;

/**
 * @since 1.36
 */
class PageStoreFactory {

	/**
	 * @internal For use by service wiring
	 */
	public const CONSTRUCTOR_OPTIONS = PageStore::CONSTRUCTOR_OPTIONS;

	private ServiceOptions $options;
	private ILBFactory $dbLoadBalancerFactory;
	private NamespaceInfo $namespaceInfo;
	private TitleParser $titleParser;
	private LinkCache $linkCache;
	private StatsFactory $stats;

	public function __construct(
		ServiceOptions $options,
		ILBFactory $dbLoadBalancerFactory,
		NamespaceInfo $namespaceInfo,
		TitleParser $titleParser,
		LinkCache $linkCache,
		StatsFactory $stats
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->dbLoadBalancerFactory = $dbLoadBalancerFactory;
		$this->namespaceInfo = $namespaceInfo;
		$this->titleParser = $titleParser;
		$this->linkCache = $linkCache;
		$this->stats = $stats;
	}

	/**
	 * @param string|false $wikiId
	 *
	 * @return PageStore
	 */
	public function getPageStore( $wikiId = WikiAwareEntity::LOCAL ): PageStore {
		// Optimization: Normalize current wiki ID to ensure LinkCache below (T440732)
		//
		// FIXME: Not in tests, as RevisionStoreDbTest relies on this behaviour to simulate
		// cross-wikiness, in absence of a better way (T261848), and that would produce ID mismatches.
		if ( !defined( 'MW_PHPUNIT_TEST' ) && is_string( $wikiId ) && WikiMap::isCurrentWikiId( $wikiId ) ) {
			$wikiId = WikiAwareEntity::LOCAL;
		}
		return new PageStore(
			$this->options,
			$this->dbLoadBalancerFactory,
			$this->namespaceInfo,
			$this->titleParser,
			$wikiId !== WikiAwareEntity::LOCAL ? null : $this->linkCache,
			$this->stats,
			$wikiId
		);
	}

}
