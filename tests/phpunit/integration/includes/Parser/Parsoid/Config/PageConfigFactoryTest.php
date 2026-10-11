<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\Parser\Parsoid\Config;

use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use MediaWikiIntegrationTestCase;
use Wikimedia\Bcp47Code\Bcp47CodeValue;

/**
 * @covers \MediaWiki\Parser\Parsoid\Config\PageConfigFactory
 * @group Database
 */
class PageConfigFactoryTest extends MediaWikiIntegrationTestCase {

	private function getFactory(): PageConfigFactory {
		return $this->getServiceContainer()->getParsoidPageConfigFactory();
	}

	public function testCreateFromParserOptions() {
		$page = $this->getExistingTestPage();
		$opts = ParserOptions::newFromAnon();
		$opts->setUseParsoid();

		$pageConfig = $this->getFactory()->createFromParserOptions( $opts, $page );

		$this->assertSame( $opts, $pageConfig->getParserOptions() );
		$this->assertSame( 'en', $pageConfig->getPageLanguageBcp47()->toBcp47Code() );
		$this->assertNull( $opts->getTargetLanguage() );
		$this->assertTrue( $opts->isSafeToCache() );
	}

	public function testCreateFromParserOptionsWithTargetLanguage() {
		$page = $this->getExistingTestPage();
		$opts = ParserOptions::newFromAnon();
		$opts->setUseParsoid();
		$opts->setTargetLanguage(
			$this->getServiceContainer()->getLanguageFactory()->getLanguage( 'de' )
		);

		$pageConfig = $this->getFactory()->createFromParserOptions( $opts, $page );

		$this->assertSame( $opts, $pageConfig->getParserOptions() );
		$this->assertSame( 'de', $pageConfig->getPageLanguageBcp47()->toBcp47Code() );
	}

	public function testCreateFromParserOptionsWithoutUseParsoid() {
		$this->expectDeprecationAndContinue( '/without useParsoid set/' );
		$page = $this->getExistingTestPage();
		$opts = ParserOptions::newFromAnon();
		$opts->setUseParsoid( false );

		$pageConfig = $this->getFactory()->createFromParserOptions( $opts, $page );

		// The options of the caller do not change
		$this->assertFalse( $opts->getUseParsoid() );
		$this->assertTrue( $pageConfig->getParserOptions()->getUseParsoid() );
	}

	public function testCreateFromParserOptionsWithPageLanguageOverride() {
		$this->expectDeprecationAndContinue( '/with \$pageLanguageOverride/' );
		$page = $this->getExistingTestPage();
		$opts = ParserOptions::newFromAnon();
		$opts->setUseParsoid();

		$pageConfig = $this->getFactory()->createFromParserOptions(
			$opts, $page, null, new Bcp47CodeValue( 'de' )
		);

		$this->assertSame( 'de', $pageConfig->getPageLanguageBcp47()->toBcp47Code() );
		// The options of the caller do not change
		$this->assertNull( $opts->getTargetLanguage() );
		$this->assertTrue( $opts->isSafeToCache() );
	}
}
