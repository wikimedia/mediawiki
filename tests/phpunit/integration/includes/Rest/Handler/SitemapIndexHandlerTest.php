<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\MainConfigNames;
use MediaWikiIntegrationTestCase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Rest\Handler\SitemapIndexHandler
 * @group Database
 */
class SitemapIndexHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerIntegrationTestTrait;

	public function addDBDataOnce() {
		$this->editPage( 'Page', '.' );
	}

	public static function provideExecute() {
		return [
			'non-empty' => [
				'0',
				<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<sitemap><loc>https://mediawiki.test/rest.php/site/v1/sitemap/0/page/0</loc></sitemap>
</sitemapindex>

XML
			],
			'empty' => [
				'1',
				<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
</sitemapindex>

XML
			],
		];
	}

	/**
	 * @dataProvider provideExecute
	 *
	 * @param string $id
	 * @param string $expected
	 * @return void
	 */
	public function testExecute( $id, $expected ) {
		ConvertibleTimestamp::setFakeTime( '2025-01-01T00:00:00' );
		$this->overrideConfigValues( [
			MainConfigNames::SitemapApiConfig => [
				'enabled' => true,
				'sitemapsPerIndex' => 1000,
				'pagesPerSitemap' => 1000,
				'expiry' => 3600,
			],
			MainConfigNames::CanonicalServer => 'https://mediawiki.test',
		] );
		$response = $this->execute( [ 'path' => "/rest.php/site/v1/sitemap/$id" ] );
		$this->assertSame( $expected, $response->getBody()->getContents() );
		$this->assertSame(
			'application/xml; charset=utf-8',
			$response->getHeaderLine( 'Content-Type' )
		);
		$this->assertSame(
			'public',
			$response->getHeaderLine( 'Cache-Control' )
		);
		$this->assertSame(
			'Wed, 01 Jan 2025 01:00:00 GMT',
			$response->getHeaderLine( 'Expires' )
		);
	}

}
