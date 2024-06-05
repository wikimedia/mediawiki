<?php

namespace MediaWiki\Tests\Rest\Handler\Helper;

use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\Handler\Helper\HtmlInputTransformHelper;
use MediaWiki\Rest\Handler\Helper\HtmlMessageOutputHelper;
use MediaWiki\Rest\Handler\Helper\HtmlOutputHelper;
use MediaWiki\Rest\Handler\Helper\HtmlOutputRendererHelper;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Rest\Handler\Helper\PageRestHelperFactory
 * @group Database
 */
class PageRestHelperFactoryTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers \MediaWiki\Rest\Handler\Helper\PageRestHelperFactory::newHtmlMessageOutputHelper
	 * @covers \MediaWiki\Rest\Handler\Helper\PageRestHelperFactory::newHtmlOutputRendererHelper
	 */
	public function testNewHtmlOutputHelpers() {
		$page = $this->getNonexistingTestPage( __METHOD__ );
		$helperFactory = $this->getServiceContainer()->getPageRestHelperFactory();

		$helper = $helperFactory->newHtmlMessageOutputHelper( $page );

		$this->assertInstanceOf( HtmlMessageOutputHelper::class, $helper );
		$this->assertInstanceOf( HtmlOutputHelper::class, $helper );

		$authority = $this->createNoOpMock( Authority::class );
		$helper = $helperFactory->newHtmlOutputRendererHelper( $page, [], $authority );

		$this->assertInstanceOf( HtmlOutputRendererHelper::class, $helper );
		$this->assertInstanceOf( HtmlOutputHelper::class, $helper );
	}

	/**
	 * @covers \MediaWiki\Rest\Handler\Helper\PageRestHelperFactory::newHtmlInputTransformHelper
	 */
	public function testNewHtmlInputTransformHelper() {
		$page = $this->getNonexistingTestPage( __METHOD__ );
		$helperFactory = $this->getServiceContainer()->getPageRestHelperFactory();

		$helper = $helperFactory->newHtmlInputTransformHelper( [], $page, 'foo', [] );

		$this->assertInstanceOf( HtmlInputTransformHelper::class, $helper );
	}
}
