<?php
use MediaWiki\MainConfigNames;
use MediaWiki\Skin\SkinComponentSearch;

/**
 * @covers \MediaWiki\Skin\SkinComponentSearch
 *
 * @group Output
 *
 */
class SkinComponentSearchTest extends MediaWikiUnitTestCase {
	use MockTitleTrait;

	/**
	 * @covers \MediaWiki\Skin\SkinComponentSearch::getTemplateData
	 */
	public function testGetTemplateData() {
		$config = new HashConfig( [
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::CapitalLinks => true,
			MainConfigNames::WatchlistExpiry => false,
		] );
		$user = new User();
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
		$relevantTitle = $this->makeMockTitle( 'RelevantTitle' );
		$component = new SkinComponentSearch(
			$config, $user, $localizer, $searchTitle, $relevantTitle
		);

		$data = $component->getTemplateData();
		$expectedKeys = [
			'page-title', 'html-button-search-fallback', 'html-button-search',
			'html-input', 'array-button-go-attributes',
			'html-button-fulltext-attributes', 'array-input-attributes',
			'html-input-attributes'
		];
		foreach ( $expectedKeys as $key ) {
			$this->assertArrayHasKey( $key, $data,  $key . ' is in array' );
		}
		$this->assertEquals( 'Search', $data['search-special-page-title'] );
		$this->assertEquals( '/w/index.php', $data['form-action'] );
	}
}
