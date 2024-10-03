<?php

namespace MediaWiki\Tests\Parser;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Json\JsonCodec;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserCache;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Parser\RevisionOutputCache;
use MediaWiki\Title\TitleFactory;
use MediaWikiUnitTestCase;
use Psr\Log\NullLogger;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * @covers \MediaWiki\Parser\ParserCacheFactory
 */
class ParserCacheFactoryTest extends MediaWikiUnitTestCase {

	/**
	 * @return ParserCacheFactory
	 */
	private function newParserCacheFactory() {
		$options = new ServiceOptions( ParserCacheFactory::CONSTRUCTOR_OPTIONS, [
			MainConfigNames::CacheEpoch => '20200202112233',
			MainConfigNames::OldRevisionParserCacheExpireTime => 60,
			MainConfigNames::ParserCacheFilterConfig
				=> MainConfigSchema::getDefaultValue( MainConfigNames::ParserCacheFilterConfig ),
		] );

		return new ParserCacheFactory(
			new HashBagOStuff(),
			new WANObjectCache( [ 'cache' => new HashBagOStuff() ] ),
			$this->createHookContainer(),
			new JsonCodec(),
			StatsFactory::newNull(),
			new NullLogger(),
			$options,
			$this->createNoOpMock( TitleFactory::class ),
			$this->createNoOpMock( WikiPageFactory::class ),
			$this->createNoOpMock( GlobalIdGenerator::class )
		);
	}

	public function testGetParserCache() {
		$factory = $this->newParserCacheFactory();

		$a = $factory->getParserCache( 'test' );
		$this->assertInstanceOf( ParserCache::class, $a );

		$b = $factory->getParserCache( 'test' );
		$this->assertSame( $a, $b );

		$c = $factory->getParserCache( 'xyzzy' );
		$this->assertNotSame( $a, $c );
	}

	public function testGetRevisionOutputCache() {
		$factory = $this->newParserCacheFactory();

		$a = $factory->getRevisionOutputCache( 'test' );
		$this->assertInstanceOf( RevisionOutputCache::class, $a );

		$b = $factory->getRevisionOutputCache( 'test' );
		$this->assertSame( $a, $b );

		$c = $factory->getRevisionOutputCache( 'xyzzy' );
		$this->assertNotSame( $a, $c );
	}

}
