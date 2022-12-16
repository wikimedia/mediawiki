<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Rest\Handler\HtmlMessageOutputHelper;
use MediaWiki\Rest\Handler\HtmlOutputHelper;
use MediaWiki\Rest\Handler\HtmlOutputRendererHelper;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Rest\Handler\PageRestHelperFactory
 */
class PageRestHelperFactoryTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers \MediaWiki\Rest\Handler\PageRestHelperFactory::newHtmlMessageOutputHelper
	 * @covers \MediaWiki\Rest\Handler\PageRestHelperFactory::newHtmlOutputRendererHelper
	 */
	public function testNewHtmlOutputHelpers() {
		$helperFactory = $this->getServiceContainer()->getPageRestHelperFactory();

		$helper = $helperFactory->newHtmlMessageOutputHelper();

		$this->assertInstanceOf( HtmlMessageOutputHelper::class, $helper );
		$this->assertInstanceOf( HtmlOutputHelper::class, $helper );

		$helper = $helperFactory->newHtmlOutputRendererHelper();

		$this->assertInstanceOf( HtmlOutputRendererHelper::class, $helper );
		$this->assertInstanceOf( HtmlOutputHelper::class, $helper );
	}

}
