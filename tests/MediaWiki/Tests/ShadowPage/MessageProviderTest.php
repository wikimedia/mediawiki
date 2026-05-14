<?php

namespace MediaWiki\Tests\ShadowPage;

use MediaWiki\Content\WikitextContent;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\ShadowPage\MessagePage;

/**
 * @group Database
 * @covers \MediaWiki\ShadowPage\MessageProvider
 * @covers \MediaWiki\ShadowPage\MessagePage
 * @covers \MediaWiki\ShadowPage\ShadowPageLoader::getMessageProvider
 * @covers \MediaWiki\ShadowPage\ParseHelper
 */
class MessageProviderTest extends \MediaWikiIntegrationTestCase {
	public function testGetShadowed() {
		$this->getServiceContainer()->getLocalisationCache()
			->setSubitemForTesting( 'en', 'messages', 'test-message-provider', '{{Test}}' );

		$loader = $this->getServiceContainer()->getShadowPageLoader();

		$page = $loader->get( new PageReferenceValue(
			NS_MEDIAWIKI, 'Test-message-provider', WikiAwareEntity::LOCAL ) );
		$this->assertInstanceOf( MessagePage::class, $page );

		$content = $page->getPreloadContent();
		$this->assertInstanceOf( WikitextContent::class, $content );
		$this->assertSame( '{{Test}}', $content->serialize() );

		$content = $page->getContentForTransclusion();
		$this->assertInstanceOf( WikitextContent::class, $content );
		$this->assertSame( '{{Test}}', $content->serialize() );

		$this->assertTrue( $page->existsForEdit() );

		$this->assertNotNull( $page->getDiffTitleMessage() );

		$view = $page->getView();
		$this->assertNotNull( $view );

		$out = $view->getParserOutput();
		$this->assertNotNull( $out );

		$opt = $view->getParserOptions();
		$this->assertNotNull( $opt );

		$this->assertStringStartsWith( '<p><a href=', $out->getContentHolderText() );
		$this->assertStringContainsString( 'Template:Test', $out->getContentHolderText() );
	}

	public function testGetUnshadowed() {
		$loader = $this->getServiceContainer()->getShadowPageLoader();

		$page = $loader->get( new PageReferenceValue(
			NS_MEDIAWIKI, 'Test-message-provider', WikiAwareEntity::LOCAL ) );
		$this->assertNull( $page );
	}

	public function testExistsForLink() {
		$this->getServiceContainer()->getLocalisationCache()
			->setSubitemForTesting( 'en', 'messages', 'test-message-provider', '{{Test}}' );

		$provider = $this->getServiceContainer()->getShadowPageLoader()->getMessageProvider();
		$this->assertTrue( $provider->existsForLink(
			new PageReferenceValue( NS_MEDIAWIKI, 'Test-message-provider', WikiAwareEntity::LOCAL )
		) );
	}

	public function testExistsForLinkNot() {
		$provider = $this->getServiceContainer()->getShadowPageLoader()->getMessageProvider();
		$this->assertFalse( $provider->existsForLink(
			new PageReferenceValue( NS_MEDIAWIKI, 'Test-message-provider', WikiAwareEntity::LOCAL )
		) );
	}
}
