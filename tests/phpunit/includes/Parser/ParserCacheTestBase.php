<?php

namespace MediaWiki\Tests\Parser;

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Json\JsonCodec;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserCache;
use MediaWiki\Title\TitleFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Stats\UnitTestingHelper;
use Wikimedia\UUID\GlobalIdGenerator;

abstract class ParserCacheTestBase extends \MediaWikiIntegrationTestCase {

	protected UnitTestingHelper $statsHelper;

	protected function setUp(): void {
		$this->statsHelper = new UnitTestingHelper();
		parent::setUp();
	}

	/**
	 * @stable named arguments
	 */
	protected function getParserCache(
		string $cacheName,
		?BagOStuff $storage = null,
		?LoggerInterface $logger = null,
		?TitleFactory $titleFactory = null,
		?HookContainer $hookContainer = null,
		?WikiPageFactory $wikiPageFactory = null,
		?GlobalIdGenerator $globalIdGenerator = null,
	): ParserCache {
		return $this->createParserCache(
			$cacheName,
			$storage ?? new HashBagOStuff(),
			'19900220000000',
			$hookContainer ?? $this->getServiceContainer()->getHookContainer(),
			new JsonCodec( $this->getServiceContainer() ),
			$this->statsHelper->getStatsFactory(),
			$logger ?? new NullLogger(),
			$titleFactory ?? $this->getServiceContainer()->getTitleFactory(),
			$wikiPageFactory ?? $this->getServiceContainer()->getWikiPageFactory(),
			$globalIdGenerator ?? $this->getServiceContainer()->getGlobalIdGenerator()
		);
	}

	protected function createParserCache( ...$args ): ParserCache {
		return new ParserCache( ...$args );
	}
}
