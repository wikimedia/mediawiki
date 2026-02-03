<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler\SitemapPageHandler;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Rest\Handler\SitemapPageHandler
 * @group Database
 */
class SitemapPageHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerIntegrationTestTrait;

	private function createHandler(): SitemapPageHandler {
		return new SitemapPageHandler(
			$this->getServiceContainer()->getMainConfig(),
			$this->getServiceContainer()->getLanguageConverterFactory(),
			$this->getServiceContainer()->getContentLanguage(),
			$this->getServiceContainer()->getPermissionManager(),
			$this->getServiceContainer()->getConnectionProvider(),
			$this->getServiceContainer()->getGenderCache(),
			$this->getServiceContainer()->getMainWANObjectCache()
		);
	}

	public function addDBDataOnce() {
		ConvertibleTimestamp::setFakeTime( '2025-01-01T00:00:00' );
		$this->editPage( 'Page', '.' );
	}

	public function testGetResponseExample() {
		$handler = $this->createHandler();
		$wrapper = TestingAccessWrapper::newFromObject( $handler );
		$example = $wrapper->getResponseExample();
		$this->assertStringContainsString( '<?xml version="1.0"', $example );
		$this->assertStringContainsString( '<urlset', $example );
		$this->assertStringContainsString( '</urlset>', $example );
	}

	public function testGenerateResponseSpec() {
		$handler = $this->createHandler();
		$wrapper = TestingAccessWrapper::newFromObject( $handler );
		$spec = $wrapper->generateResponseSpec( 'GET' );
		$this->assertArrayHasKey( '200', $spec );
		$this->assertArrayHasKey( 'application/xml', $spec['200']['content'] );
		$this->assertArrayNotHasKey( 'application/json', $spec['200']['content'] );
	}

	public function testExecute() {
		ConvertibleTimestamp::setFakeTime( '2025-01-01T00:00:00' );
		$this->overrideConfigValues( [
			MainConfigNames::SitemapApiConfig => [
				'enabled' => true,
				'sitemapsPerIndex' => 1000,
				'pagesPerSitemap' => 1000,
				'expiry' => 3600,
			],
			MainConfigNames::CanonicalServer => 'https://mediawiki.test',
			MainConfigNames::UsePigLatinVariant => false,
		] );
		$response = $this->execute( [ 'path' => '/rest.php/site/v1/sitemap/0/page/0' ] );
		$expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<url><loc>https://mediawiki.test/wiki/Page</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
</urlset>

XML;

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
