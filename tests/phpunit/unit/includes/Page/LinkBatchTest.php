<?php

namespace MediaWiki\Tests\Unit;

use MediaWiki\Cache\GenderCache;
use MediaWiki\Language\Language;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Page\LinkBatch;
use MediaWiki\Page\LinkCache;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\TempUser\TempUserDetailsLookup;
use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Split from LinkBatchTest integration tests
 *
 * @group Cache
 * @covers \MediaWiki\Page\LinkBatch
 */
class LinkBatchTest extends MediaWikiUnitTestCase {

	public function testConstructEmptyWithServices() {
		$batch = new LinkBatch(
			[],
			$this->createMock( LinkCache::class ),
			$this->createMock( TitleFormatter::class ),
			$this->createMock( Language::class ),
			$this->createMock( GenderCache::class ),
			$this->createMock( IConnectionProvider::class ),
			$this->createMock( LinksMigration::class ),
			$this->createNoOpMock( TempUserDetailsLookup::class ),
			LoggerFactory::getInstance( 'LinkBatch' )
		);

		$this->assertTrue( $batch->isEmpty() );
		$this->assertSame( 0, $batch->getSize() );
	}

	public function testConstructWithServices() {
		$batch = new LinkBatch(
			[
				new TitleValue( NS_MAIN, 'Foo' ),
				new TitleValue( NS_TALK, 'Bar' ),
			],
			$this->createMock( LinkCache::class ),
			$this->createMock( TitleFormatter::class ),
			$this->createMock( Language::class ),
			$this->createMock( GenderCache::class ),
			$this->createMock( IConnectionProvider::class ),
			$this->createMock( LinksMigration::class ),
			$this->createNoOpMock( TempUserDetailsLookup::class ),
			LoggerFactory::getInstance( 'LinkBatch' )
		);

		$this->assertFalse( $batch->isEmpty() );
		$this->assertSame( 2, $batch->getSize() );
	}

	public function testDoGenderQueryWithEmptyLinkBatch() {
		$batch = new LinkBatch(
			[],
			$this->createMock( LinkCache::class ),
			$this->createMock( TitleFormatter::class ),
			$this->createNoOpMock( Language::class ),
			$this->createNoOpMock( GenderCache::class ),
			$this->createMock( IConnectionProvider::class ),
			$this->createMock( LinksMigration::class ),
			$this->createNoOpMock( TempUserDetailsLookup::class ),
			LoggerFactory::getInstance( 'LinkBatch' )
		);

		$this->assertFalse( $batch->doGenderQuery() );
	}

	public function testDoGenderQueryWithLanguageWithoutGenderDistinction() {
		$language = $this->createMock( Language::class );
		$language->method( 'needsGenderDistinction' )->willReturn( false );

		$batch = new LinkBatch(
			[],
			$this->createMock( LinkCache::class ),
			$this->createMock( TitleFormatter::class ),
			$language,
			$this->createNoOpMock( GenderCache::class ),
			$this->createMock( IConnectionProvider::class ),
			$this->createMock( LinksMigration::class ),
			$this->createNoOpMock( TempUserDetailsLookup::class ),
			LoggerFactory::getInstance( 'LinkBatch' )
		);
		$batch->addObj(
			new TitleValue( NS_MAIN, 'Foo' )
		);

		$this->assertFalse( $batch->doGenderQuery() );
	}

	public function testDoGenderQueryWithLanguageWithGenderDistinction() {
		$language = $this->createMock( Language::class );
		$language->method( 'needsGenderDistinction' )->willReturn( true );

		$genderCache = $this->createMock( GenderCache::class );
		$genderCache->expects( $this->once() )->method( 'doLinkBatch' );

		$batch = new LinkBatch(
			[],
			$this->createMock( LinkCache::class ),
			$this->createMock( TitleFormatter::class ),
			$language,
			$genderCache,
			$this->createMock( IConnectionProvider::class ),
			$this->createMock( LinksMigration::class ),
			$this->createNoOpMock( TempUserDetailsLookup::class ),
			LoggerFactory::getInstance( 'LinkBatch' )
		);
		$batch->addObj(
			new TitleValue( NS_MAIN, 'Foo' )
		);

		$this->assertTrue( $batch->doGenderQuery() );
	}
}
