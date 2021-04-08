<?php

namespace MediaWiki\Page;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\DAO\WikiAwareEntity;
use NamespaceInfo;
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

	/**
	 * @param ServiceOptions $options
	 * @param ILBFactory $dbLoadBalancerFactory
	 * @param NamespaceInfo $namespaceInfo
	 */
	public function __construct(
		ServiceOptions $options,
		ILBFactory $dbLoadBalancerFactory,
		NamespaceInfo $namespaceInfo
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->dbLoadBalancerFactory = $dbLoadBalancerFactory;
		$this->namespaceInfo = $namespaceInfo;
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
			$wikiId
		);
	}

}
