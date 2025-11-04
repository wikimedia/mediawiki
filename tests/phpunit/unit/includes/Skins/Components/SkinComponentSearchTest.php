<?php

use MediaWiki\Config\HashConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Skin\SkinComponentSearch;
use MediaWiki\Skin\SkinTemplate;

/**
 * @covers \MediaWiki\Skin\SkinComponentSearch
 * @group Skin
 */
class SkinComponentSearchTest extends MediaWikiUnitTestCase {
	use MockTitleTrait;

	public function testGetTemplateData() {
		$config = new HashConfig( [
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::CapitalLinks => true,
			MainConfigNames::WatchlistExpiry => false,
		] );
		$msg = $this->createMock( Message::class );
		$msg->method( 'isDisabled' )->willReturn( false );
		$msg->method( 'text' )->willReturn( 'text' );

		$skin = $this->createMock( SkinTemplate::class );
		$skin->method( 'getConfig' )->willReturn( $config );
		$skin->method( 'msg' )->willReturn( $msg );
		$localizer = $this->createMock( MessageLocalizer::class );
		$localizer->method( 'msg' )->willReturn(
			$msg
		);
		$searchTitle = $this->makeMockTitle( 'Special:Search' );
		$component = new SkinComponentSearch(
			$config, $localizer, $searchTitle
		);

		$data = $component->getTemplateData();
		$expectedKeys = [
			'page-title', 'html-button-search-fallback', 'html-button-search',
			'html-input', 'array-button-go-attributes',
			'html-button-fulltext-attributes', 'array-input-attributes',
			'html-input-attributes'
		];
		foreach ( $expectedKeys as $key ) {
			$this->assertArrayHasKey( $key, $data, $key . ' is in array' );
		}
		$this->assertEquals( 'Search', $data['search-special-page-title'] );
		$this->assertEquals( '/w/index.php', $data['form-action'] );
	}
}
