<?php

namespace MediaWiki\Tests\Unit\Page;

use BacklinkCache;
use BadMethodCallException;
use BagOStuff;
use CommentStore;
use IDatabase;
use JobQueueGroup;
use MediaWiki\Cache\BacklinkCacheFactory;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Page\DeletePage;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\LBFactory;
use WikiPage;

/**
 * @coversDefaultClass \MediaWiki\Page\DeletePage
 */
class DeletePageTest extends MediaWikiUnitTestCase {
	use MockAuthorityTrait;

	private function getMockPage(): WikiPage {
		$ret = $this->createMock( WikiPage::class );
		$ret->method( 'canExist' )->willReturn( true );
		$ret->method( 'exists' )->willReturn( true );
		$ret->method( 'getId' )->willReturn( 123 );
		$ret->method( 'getRevisionRecord' )->willReturn( $this->createMock( RevisionRecord::class ) );
		return $ret;
	}

	/**
	 * @return RevisionStore|MockObject
	 */
	private function getMockRevisionStore(): RevisionStore {
		$ret = $this->createMock( RevisionStore::class );
		$ret->method( 'getQueryInfo' )->willReturn( [ 'tables' => [], 'fields' => [], 'joins' => [] ] );
		return $ret;
	}

	private function getServiceOptions( int $deleteLimit = 100 ): ServiceOptions {
		return new ServiceOptions(
			DeletePage::CONSTRUCTOR_OPTIONS,
			[
				'DeleteRevisionsBatchSize' => 100,
				'ActorTableSchemaMigrationStage' => SCHEMA_COMPAT_NEW,
				'DeleteRevisionsLimit' => $deleteLimit
			]
		);
	}

	private function getDeletePage(
		ProperPageIdentity $page,
		Authority $deleter,
		ServiceOptions $options = null,
		RevisionStore $revStore = null
	): DeletePage {
		$wpFactory = $this->createMock( WikiPageFactory::class );
		$wpFactory->method( 'newFromTitle' )->willReturn( $this->getMockPage() );

		// NOTE: The following could be avoided if the relevant methods were return-typehinted
		$db = $this->createMock( IDatabase::class );
		$db->method( 'select' )->willReturn( $this->createMock( IResultWrapper::class ) );
		$db->method( 'selectRowCount' )->willReturn( 42 );
		$lb = $this->createMock( ILoadBalancer::class );
		$lb->method( 'getConnectionRef' )->willReturn( $db );
		$lbFactory = $this->createMock( LBFactory::class );
		$lbFactory->method( 'getMainLB' )->willReturn( $lb );
		$blc = $this->createMock( BacklinkCache::class );
		$blcFactory = $this->createMock( BacklinkCacheFactory::class );
		$blcFactory->method( 'getBacklinkCache' )->willReturn( $blc );

		$ret = new DeletePage(
			$this->createHookContainer(),
			$revStore ?? $this->getMockRevisionStore(),
			$lbFactory,
			$this->createMock( JobQueueGroup::class ),
			$this->createMock( CommentStore::class ),
			$options ?? $this->getServiceOptions(),
			$this->createMock( BagOStuff::class ),
			'wiki-id',
			'req-foo-bar',
			$wpFactory,
			$this->createMock( UserFactory::class ),
			$page,
			$deleter,
			$blcFactory
		);
		$ret->setIsDeletePageUnitTest( true );
		return $ret;
	}

	/**
	 * @covers ::deleteIfAllowed
	 * @covers ::authorizeDeletion
	 * @covers ::isBigDeletion
	 * @dataProvider providePermissions
	 */
	public function testPermissions(
		Authority $authority,
		bool $expectedGood,
		string $expectedMessage = null,
		ServiceOptions $options = null,
		RevisionStore $revStore = null
	) {
		$dp = $this->getDeletePage(
			$this->getMockPage(),
			$authority,
			$options,
			$revStore
		);
		$status = $dp->deleteIfAllowed( 'foobar' );
		$this->assertSame( $expectedGood, $status->isGood() );
		if ( $expectedMessage !== null ) {
			$this->assertTrue( $status->hasMessage( $expectedMessage ) );
		}
	}

	public function providePermissions(): iterable {
		$cannotDeleteMsg = "You shall not delete!";
		$cannotDeleteAuthority = $this->mockAnonAuthority(
			static function (
				string $permission,
				?PageIdentity $page,
				PermissionStatus $status = null
			) use ( $cannotDeleteMsg ): bool {
				if ( $permission === 'delete' ) {
					if ( $status ) {
						$status->fatal( $cannotDeleteMsg );
					}
					return false;
				}
				return true;
			}
		);
		yield 'Cannot delete' => [ $cannotDeleteAuthority, false, $cannotDeleteMsg ];

		$cannotBigDeleteMsg = 'delete-toobig';
		$cannotBigDeleteAuthority = $this->mockAnonAuthority(
			static function (
				string $permission,
				?PageIdentity $page,
				PermissionStatus $status = null
			) use ( $cannotBigDeleteMsg ): bool {
				if ( $permission === 'bigdelete' ) {
					if ( $status ) {
						$status->fatal( $cannotBigDeleteMsg );
					}
					return false;
				}
				return true;
			}
		);
		$revDeleteLimit = -1;
		$cannotBigDeleteOptions = $this->getServiceOptions( $revDeleteLimit );
		$revStore = $this->getMockRevisionStore();
		$revStore->expects( $this->atLeastOnce() )
			->method( 'countRevisionsByPageId' )
			->willReturn( $revDeleteLimit + 42 );
		yield 'Cannot bigdelete' => [
			$cannotBigDeleteAuthority,
			false,
			$cannotBigDeleteMsg,
			$cannotBigDeleteOptions,
			$revStore
		];

		$successAuthority = new UltimateAuthority( new UserIdentityValue( 42, 'Deleter' ) );
		yield 'Successful' => [ $successAuthority, true ];
	}

	/**
	 * @covers ::getSuccessfulDeletionsIDs
	 */
	public function testGetSuccessfulDeletionsIDs(): void {
		$delPage = $this->getDeletePage(
			$this->createMock( ProperPageIdentity::class ),
			$this->createMock( Authority::class )
		);
		$delPage->deleteUnsafe( 'foo' );
		$this->assertIsArray( $delPage->getSuccessfulDeletionsIDs() );
	}

	/**
	 * @covers ::getSuccessfulDeletionsIDs
	 */
	public function testGetSuccessfulDeletionsIDs__notAttempted(): void {
		$delPage = $this->getDeletePage(
			$this->createMock( ProperPageIdentity::class ),
			$this->createMock( Authority::class )
		);
		$this->expectException( BadMethodCallException::class );
		$delPage->getSuccessfulDeletionsIDs();
	}

	/**
	 * @covers ::deletionWasScheduled
	 */
	public function testDeletionWasScheduled(): void {
		$delPage = $this->getDeletePage(
			$this->createMock( ProperPageIdentity::class ),
			$this->createMock( Authority::class )
		);
		$delPage->deleteUnsafe( 'foo' );
		$this->assertIsBool( $delPage->deletionWasScheduled() );
	}

	/**
	 * @covers ::deletionWasScheduled
	 */
	public function testDeletionWasScheduled__notAttempted(): void {
		$delPage = $this->getDeletePage(
			$this->createMock( ProperPageIdentity::class ),
			$this->createMock( Authority::class )
		);
		$this->expectException( BadMethodCallException::class );
		$delPage->deletionWasScheduled();
	}
}
