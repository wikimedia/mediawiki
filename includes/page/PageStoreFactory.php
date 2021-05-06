<?php

namespace MediaWiki\Page;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\DAO\WikiAwareEntity;
use NamespaceInfo;
use TitleParser;
use Wikimedia\Rdbms\ILBFactory;

/**
 * @since 1.36
 */
class PageStoreFactory {

	/**
	 * @internal for use by service wiring
	 */
	public const CONSTRUCTOR_OPTIONS = PageStore::CONSTRUCTOR_OPTIONS;

	/** @var ServiceOptions */
	private $options;

	/** @var ILBFactory */
	private $dbLoadBalancerFactory;

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/** @var TitleParser */
	private $titleParser;

	/**
	 * @param ServiceOptions $options
	 * @param ILBFactory $dbLoadBalancerFactory
	 * @param NamespaceInfo $namespaceInfo
	 * @param TitleParser $titleParser
	 */
	public function __construct(
		ServiceOptions $options,
		ILBFactory $dbLoadBalancerFactory,
		NamespaceInfo $namespaceInfo,
		TitleParser $titleParser
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->dbLoadBalancerFactory = $dbLoadBalancerFactory;
		$this->namespaceInfo = $namespaceInfo;
		$this->titleParser = $titleParser;
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
			$wikiId
		);
	}

}
