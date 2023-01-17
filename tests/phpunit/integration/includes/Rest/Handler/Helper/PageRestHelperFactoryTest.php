<?php

namespace MediaWiki\Tests\Rest\Handler\Helper;

use MediaWiki\Rest\Handler\Helper\HtmlMessageOutputHelper;
use MediaWiki\Rest\Handler\Helper\HtmlOutputHelper;
use MediaWiki\Rest\Handler\Helper\HtmlOutputRendererHelper;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Rest\Handler\Helper\PageRestHelperFactory
 */
class PageRestHelperFactoryTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers \MediaWiki\Rest\Handler\Helper\PageRestHelperFactory::newHtmlMessageOutputHelper
	 * @covers \MediaWiki\Rest\Handler\Helper\PageRestHelperFactory::newHtmlOutputRendererHelper
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
