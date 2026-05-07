<?php

namespace MediaWiki\Tests\Unit\EditPage;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\EditPage\PageEditingHelper;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Revision\RevisionStore;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\EditPage\PageEditingHelper
 */
class PageEditingHelperUnitTest extends MediaWikiUnitTestCase {

	private function createPageEditingHelper(
		?IContentHandlerFactory $contentHandlerFactory = null,
		array $configOverrides = [],
	): PageEditingHelper {
		$contentHandlerFactory ??= $this->createMock( IContentHandlerFactory::class );
		return new PageEditingHelper(
			new ServiceOptions( PageEditingHelper::CONSTRUCTOR_OPTIONS, $configOverrides + [
				MainConfigNames::EditSubmitButtonLabelPublish => false,
			] ),
			$contentHandlerFactory,
			$this->createMock( ParserFactory::class ),
			$this->createMock( RevisionStore::class ),
		);
	}

	public function testIsSupportedContentModel() {
		$contentHandlerFactory = $this->createMock( IContentHandlerFactory::class );

		$supportedContentHandler = $this->createMock( ContentHandler::class );
		$supportedContentHandler->method( 'supportsDirectEditing' )->willReturn( true );

		$unsupportedContentHandler = $this->createMock( ContentHandler::class );
		$unsupportedContentHandler->method( 'supportsDirectEditing' )->willReturn( false );

		$contentHandlerFactory->method( 'getContentHandler' )->willReturnCallback(
			static fn ( $id ) => $id === 'supported' ? $supportedContentHandler : $unsupportedContentHandler
		);

		$pageEditingHelper = $this->createPageEditingHelper(
			$contentHandlerFactory,
		);
		$this->assertTrue(
			$pageEditingHelper->isSupportedContentModel( 'supported', false ),
			'Should return true if the content model is supported and the API edit override is not enabled.'
		);
		$this->assertTrue(
			$pageEditingHelper->isSupportedContentModel( 'supported', true ),
			'Should return true if the content model is supported and the API edit override is enabled.'
		);
		$this->assertFalse(
			$pageEditingHelper->isSupportedContentModel( 'unsupported', false ),
			'Should return false if the content model is not supported and the API edit override is not enabled.'
		);
		$this->assertTrue(
			$pageEditingHelper->isSupportedContentModel( 'unsupported', true ),
			'Should return true if the content model is not supported but the API edit override is enabled.'
		);
	}

	/**
	 * @dataProvider provideGetSubmitButtonLabel
	 */
	public function testGetSubmitButtonLabel(
		bool $usePublishLabels,
		bool $pageExists,
		string $expectedLabel,
	) {
		$page = $this->createMock( PageIdentity::class );
		$page->method( 'exists' )->willReturn( $pageExists );

		$pageEditingHelper = $this->createPageEditingHelper( configOverrides: [
			MainConfigNames::EditSubmitButtonLabelPublish => $usePublishLabels,
		] );
		$this->assertEquals( $expectedLabel, $pageEditingHelper->getSubmitButtonLabel( $page ) );
	}

	public static function provideGetSubmitButtonLabel(): array {
		return [
			[
				false,
				false,
				'savearticle',
			],
			[
				false,
				true,
				'savechanges',
			],
			[
				true,
				false,
				'publishpage',
			],
			[
				true,
				true,
				'publishchanges',
			],
		];
	}

}
