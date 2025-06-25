<?php

namespace MediaWiki\Tests\Page;

use MediaWiki\Config\HashConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\SitemapGenerator;
use TestUser;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Page\SitemapGenerator
 * @group Database
 */
class SitemapGeneratorTest extends \MediaWikiIntegrationTestCase {
	/** @var int|null */
	private static $baseId;

	public function setUp(): void {
		$this->overrideConfigValues( [
			MainConfigNames::ExtraGenderNamespaces => [
				NS_USER => [
					'male' => 'UserM',
					'female' => 'UserF'
				]
			],
			MainConfigNames::CanonicalServer => 'https://mediawiki.test',
			MainConfigNames::VariantArticlePath => '/$2/$1',
			MainConfigNames::UsePigLatinVariant => false,
		] );
	}

	public function addDBDataOnce() {
		ConvertibleTimestamp::setFakeTime( '2025-01-01T00:00:00Z' );
		$userM = ( new TestUser( 'M' ) )->getUser();
		$userF = ( new TestUser( 'F' ) )->getUser();
		new TestUser( 'N' );

		$uom = $this->getServiceContainer()->getUserOptionsManager();
		$uom->setOption( $userM, 'gender', 'male' );
		$uom->saveOptions( $userM );
		$uom->setOption( $userF, 'gender', 'female' );
		$uom->saveOptions( $userF );

		$status = $this->editPage( 'SG1', '.' );
		self::$baseId = $status->getNewRevision()->getPageId();

		$this->editPage( 'SG2', '.' );
		$this->editPage( 'SG3', '#REDIRECT [[SG1]]' );
		$this->editPage( 'User:M', '.' );
		$this->editPage( 'User:F', '.' );
		$this->editPage( 'User:N', '.' );
	}

	private function getSitemapGenerator() {
		ConvertibleTimestamp::setFakeTime( '2025-01-01T00:00:00Z' );
		$services = $this->getServiceContainer();
		return new SitemapGenerator(
			$services->getContentLanguage(),
			$services->getLanguageConverterFactory(),
			$services->getGenderCache()
		);
	}

	private function assertSitemap( $expectedInner, $result ) {
		if ( $expectedInner !== '' ) {
			$expectedInner .= "\n";
		}
		$outer = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
$expectedInner</urlset>

XML;
		$this->assertSame( $outer, $result );
	}

	public function testAll() {
		$sg = $this->getSitemapGenerator();
		$result = $sg->getXml( $this->getDb() );
		$expected = <<<XML
<url><loc>https://mediawiki.test/wiki/SG1</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/SG2</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/SG3</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/UserM:M</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/UserF:F</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/User:N</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
XML;
		$this->assertSitemap( $expected, $result );
	}

	public function testNamespaces() {
		$sg = $this->getSitemapGenerator();
		$result = $sg->namespaces( [ 0 ] )->getXml( $this->getDb() );
		$expected = <<<XML
<url><loc>https://mediawiki.test/wiki/SG1</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/SG2</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/SG3</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
XML;
		$this->assertSitemap( $expected, $result );
	}

	public static function provideNamespacesFromConfig() {
		$main = <<<XML
<url><loc>https://mediawiki.test/wiki/SG1</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/SG2</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/SG3</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
XML;
		$user = <<<XML
<url><loc>https://mediawiki.test/wiki/UserM:M</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/UserF:F</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/User:N</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
XML;

		$both = "$main\n$user";

		return [
			'defaults' => [
				[
					MainConfigNames::SitemapNamespaces => false,
					MainConfigNames::DefaultRobotPolicy => 'index',
					MainConfigNames::NamespaceRobotPolicies => [],
				],
				$both
			],
			'global noindex' => [
				[
					MainConfigNames::SitemapNamespaces => false,
					MainConfigNames::DefaultRobotPolicy => 'noindex',
					MainConfigNames::NamespaceRobotPolicies => [],
				],
				''
			],
			'main only' => [
				[
					MainConfigNames::SitemapNamespaces => false,
					MainConfigNames::DefaultRobotPolicy => 'noindex',
					MainConfigNames::NamespaceRobotPolicies => [ NS_MAIN => 'index' ],
				],
				$main
			],
			'user noindex (like production)' => [
				[
					MainConfigNames::SitemapNamespaces => false,
					MainConfigNames::DefaultRobotPolicy => 'index',
					MainConfigNames::NamespaceRobotPolicies => [ NS_USER => 'noindex' ],
				],
				$main
			],
		];
	}

	/**
	 * @dataProvider provideNamespacesFromConfig
	 * @param array $configArray
	 * @param string $expected
	 */
	public function testNamespacesFromConfig( $configArray, $expected ) {
		$sg = $this->getSitemapGenerator();
		$result = $sg
			->namespacesFromConfig( new HashConfig( $configArray ) )
			->getXml( $this->getDb() );
		$this->assertSitemap( $expected, $result );
	}

	public function testIdRange() {
		$sg = $this->getSitemapGenerator();
		$result = $sg->idRange( self::$baseId, self::$baseId + 2 )->getXml( $this->getDb() );
		$expected = <<<XML
<url><loc>https://mediawiki.test/wiki/SG1</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/SG2</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
XML;
		$this->assertSitemap( $expected, $result );
	}

	public function testSkipRedirects() {
		$sg = $this->getSitemapGenerator();
		$result = $sg->skipRedirects()->getXml( $this->getDb() );
		$expected = <<<XML
<url><loc>https://mediawiki.test/wiki/SG1</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/SG2</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/UserM:M</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/UserF:F</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/User:N</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
XML;
		$this->assertSitemap( $expected, $result );
	}

	public function testLimitIteration() {
		$sg = $this->getSitemapGenerator();
		$result = $sg->limit( 4 )->getXml( $this->getDb() );
		$expected = <<<XML
<url><loc>https://mediawiki.test/wiki/SG1</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/SG2</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/SG3</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/UserM:M</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
XML;
		$this->assertSitemap( $expected, $result );

		$this->assertTrue( $sg->nextBatch() );
		$result = $sg->getXml( $this->getDb() );
		$expected = <<<XML
<url><loc>https://mediawiki.test/wiki/UserF:F</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/User:N</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
XML;
		$this->assertSitemap( $expected, $result );
		$this->assertFalse( $sg->nextBatch() );
	}

	public function testVariants() {
		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, true );
		$sg = $this->getSitemapGenerator();
		$result = $sg->getXml( $this->getDb() );
		$expected = <<<XML
<url><loc>https://mediawiki.test/wiki/SG1</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/en-x-piglatin/SG1</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/SG2</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/en-x-piglatin/SG2</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/SG3</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/en-x-piglatin/SG3</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/UserM:M</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/en-x-piglatin/UserM:M</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/UserF:F</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/en-x-piglatin/UserF:F</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/User:N</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/en-x-piglatin/User:N</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
XML;
		$this->assertSitemap( $expected, $result );
	}

	public function testVariantsLimit() {
		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, true );
		$sg = $this->getSitemapGenerator();
		$result = $sg->limit( 8 )->getXml( $this->getDb() );
		$expected = <<<XML
<url><loc>https://mediawiki.test/wiki/SG1</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/en-x-piglatin/SG1</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/SG2</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/en-x-piglatin/SG2</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/SG3</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/en-x-piglatin/SG3</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/UserM:M</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/en-x-piglatin/UserM:M</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
XML;
		$this->assertSitemap( $expected, $result );
		$this->assertTrue( $sg->nextBatch() );
		$result = $sg->getXml( $this->getDb() );
		$expected = <<<XML
<url><loc>https://mediawiki.test/wiki/UserF:F</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/en-x-piglatin/UserF:F</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/wiki/User:N</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
<url><loc>https://mediawiki.test/en-x-piglatin/User:N</loc><lastmod>2025-01-01T00:00:00Z</lastmod></url>
XML;
		$this->assertSitemap( $expected, $result );
	}
}
