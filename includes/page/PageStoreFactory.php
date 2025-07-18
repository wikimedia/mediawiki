<?php

namespace MediaWiki\Page;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\TitleParser;
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
		return new PageStore(
			$this->options,
			$this->dbLoadBalancerFactory->getMainLB( $wikiId ),
			$this->namespaceInfo,
			$this->titleParser,
			$wikiId !== WikiAwareEntity::LOCAL ? null : $this->linkCache,
			$this->stats,
			$wikiId
		);
	}

}
