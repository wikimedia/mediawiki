<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler\SitemapIndexHandler;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Rest\Handler\SitemapIndexHandler
 * @group Database
 */
class SitemapIndexHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerIntegrationTestTrait;

	private function createHandler(): SitemapIndexHandler {
		return new SitemapIndexHandler(
			$this->getServiceContainer()->getMainConfig(),
			$this->getServiceContainer()->getLanguageConverterFactory(),
			$this->getServiceContainer()->getContentLanguage(),
			$this->getServiceContainer()->getPermissionManager(),
			$this->getServiceContainer()->getConnectionProvider()
		);
	}

	public function addDBDataOnce() {
		$this->editPage( 'Page', '.' );
		$this->editPage( 'Page2', '.' );
		$this->editPage( 'Page3', '.' );
	}

	public function testGetResponseExample() {
		$handler = $this->createHandler();
		$wrapper = TestingAccessWrapper::newFromObject( $handler );
		$example = $wrapper->getResponseExample();
		$this->assertStringContainsString( '<?xml version="1.0"', $example );
		$this->assertStringContainsString( '<sitemapindex', $example );
		$this->assertStringContainsString( '</sitemapindex>', $example );
	}

	public function testGenerateResponseSpec() {
		$handler = $this->createHandler();
		$this->initHandler( $handler, null );
		$wrapper = TestingAccessWrapper::newFromObject( $handler );
		$spec = $wrapper->generateResponseSpec( 'GET' );
		$this->assertArrayHasKey( '200', $spec );
		$this->assertArrayHasKey( 'application/xml', $spec['200']['content'] );
		$this->assertArrayNotHasKey( 'application/json', $spec['200']['content'] );
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

	public function testExecuteMaxIndexSizeExceeded() {
		ConvertibleTimestamp::setFakeTime( '2025-01-01T00:00:00' );
		$this->overrideConfigValues( [
			MainConfigNames::SitemapApiConfig => [
				'enabled' => true,
				'sitemapsPerIndex' => 1,
				'pagesPerSitemap' => 2,
				'expiry' => 3600,
			]
		] );
		$response = $this->execute( [ 'path' => '/rest.php/site/v1/sitemap/0' ] );
		$body = $response->getBody()->getContents();
		$this->assertStringContainsString( 'maximum index size exceeded', $body );
		$this->assertStringContainsString( 'sitemap/1', $body );
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
