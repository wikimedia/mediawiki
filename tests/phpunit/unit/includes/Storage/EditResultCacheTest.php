<?php

namespace MediaWiki\Tests\Storage;

use BagOStuff;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Json\FormatJson;
use MediaWiki\MainConfigNames;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\EditResultCache;
use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @covers \MediaWiki\Storage\EditResultCache
 */
class EditResultCacheTest extends MediaWikiUnitTestCase {

	private const DUMMY_KEY = 'wiki:dummy:key';

	/**
	 * Returns an EditResult for testing.
	 *
	 * @return EditResult
	 */
	private function getEditResult(): EditResult {
		return new EditResult(
			false,
			100,
			EditResult::REVERT_ROLLBACK,
			123,
			125,
			true,
			false,
			[ 'mw-rollback' ]
		);
	}

	/**
	 * @covers \MediaWiki\Storage\EditResultCache::set
	 */
	public function testSet() {
		$editResult = $this->getEditResult();

		$mainStash = $this->createMock( BagOStuff::class );
		$mainStash->expects( $this->once() )
			->method( 'set' )
			->with(
				self::DUMMY_KEY,
				FormatJson::encode( $editResult )
			)
			->willReturn( true );
		$mainStash->expects( $this->once() )
			->method( 'makeKey' )
			->willReturn( self::DUMMY_KEY );

		$erCache = new EditResultCache(
			$mainStash,
			$this->createNoOpMock( IConnectionProvider::class ),
			new ServiceOptions(
				EditResultCache::CONSTRUCTOR_OPTIONS,
				[ MainConfigNames::RCMaxAge => BagOStuff::TTL_MONTH ]
			)
		);

		$result = $erCache->set( 123, $editResult );
		$this->assertTrue( $result, 'EditResultCache::set()' );
	}

	/**
	 * @covers \MediaWiki\Storage\EditResultCache::get
	 */
	public function testGetFromStash() {
		$editResult = $this->getEditResult();

		$mainStash = $this->createMock( BagOStuff::class );
		$mainStash->expects( $this->once() )
			->method( 'get' )
			->with( self::DUMMY_KEY )
			->willReturn( FormatJson::encode( $editResult ) );
		$mainStash->expects( $this->once() )
			->method( 'makeKey' )
			->willReturn( self::DUMMY_KEY );

		$erCache = new EditResultCache(
			$mainStash,
			$this->createNoOpMock( IConnectionProvider::class ),
			new ServiceOptions(
				EditResultCache::CONSTRUCTOR_OPTIONS,
				[ MainConfigNames::RCMaxAge => BagOStuff::TTL_MONTH ]
			)
		);
		$res = $erCache->get( 126 );

		$this->assertEquals( $editResult, $res, 'Correct EditResult is returned' );
	}

	/**
	 * @covers \MediaWiki\Storage\EditResultCache::get
	 */
	public function testGetFromRevertTag() {
		$editResult = $this->getEditResult();

		$mainStash = $this->createMock( BagOStuff::class );
		$mainStash->expects( $this->once() )
			->method( 'get' )
			->with( self::DUMMY_KEY )
			->willReturn( false );
		$mainStash->expects( $this->once() )
			->method( 'makeKey' )
			->willReturn( self::DUMMY_KEY );

		$dbr = $this->createMock( IDatabase::class );
		$dbr->expects( $this->once() )
			->method( 'selectField' )
			->willReturn( FormatJson::encode( $editResult ) );
		$dbr->method( 'newSelectQueryBuilder' )->willReturnCallback( static fn () => new SelectQueryBuilder( $dbr ) );
		$dbProvider = $this->createMock( IConnectionProvider::class );
		$dbProvider->expects( $this->once() )
			->method( 'getReplicaDatabase' )
			->willReturn( $dbr );

		$erCache = new EditResultCache(
			$mainStash,
			$dbProvider,
			new ServiceOptions(
				EditResultCache::CONSTRUCTOR_OPTIONS,
				[ MainConfigNames::RCMaxAge => BagOStuff::TTL_MONTH ]
			)
		);
		$res = $erCache->get( 126 );

		$this->assertEquals( $editResult, $res, 'Correct EditResult is returned' );
	}

	/**
	 * @covers \MediaWiki\Storage\EditResultCache::get
	 */
	public function testGetMissingEntry() {
		$mainStash = $this->createMock( BagOStuff::class );
		$mainStash->expects( $this->once() )
			->method( 'get' )
			->with( self::DUMMY_KEY )
			->willReturn( false );
		$mainStash->expects( $this->once() )
			->method( 'makeKey' )
			->willReturn( self::DUMMY_KEY );

		$dbr = $this->createMock( IDatabase::class );
		$dbr->expects( $this->once() )
			->method( 'selectField' )
			->willReturn( false );
		$dbr->method( 'newSelectQueryBuilder' )->willReturnCallback( static fn () => new SelectQueryBuilder( $dbr ) );
		$dbProvider = $this->createMock( IConnectionProvider::class );
		$dbProvider->expects( $this->once() )
			->method( 'getReplicaDatabase' )
			->willReturn( $dbr );

		$erCache = new EditResultCache(
			$mainStash,
			$dbProvider,
			new ServiceOptions(
				EditResultCache::CONSTRUCTOR_OPTIONS,
				[ MainConfigNames::RCMaxAge => BagOStuff::TTL_MONTH ]
			)
		);
		$res = $erCache->get( 126 );

		$this->assertNull( $res, 'Null is returned' );
	}
}
