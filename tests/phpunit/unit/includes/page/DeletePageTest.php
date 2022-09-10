<?php

namespace MediaWiki\Tests\Unit\Page;

use BadMethodCallException;
use BagOStuff;
use CommentStore;
use Generator;
use JobQueueGroup;
use MediaWiki\Cache\BacklinkCacheFactory;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\DeletePage;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
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
use NamespaceInfo;
use PHPUnit\Framework\MockObject\MockObject;
use Title;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Rdbms\IDatabase;
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

		$title = $this->createMock( Title::class );
		$title->method( 'getPrefixedText' )->willReturn( 'Foo' );
		$title->method( 'getText' )->willReturn( 'Foo' );
		$title->method( 'getDBkey' )->willReturn( 'Foo' );
		$title->method( 'getNamespace' )->willReturn( 0 );
		$ret->method( 'getTitle' )->willReturn( $title );
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
				MainConfigNames::DeleteRevisionsBatchSize => 100,
				MainConfigNames::DeleteRevisionsLimit => $deleteLimit
			]
		);
	}

	private function getDeletePage(
		ProperPageIdentity $page,
		Authority $deleter = null,
		ServiceOptions $options = null,
		RevisionStore $revStore = null,
		WikiPageFactory $wpFactory = null,
		NamespaceInfo $nsInfo = null
	): DeletePage {
		if ( !$wpFactory ) {
			$wpFactory = $this->createMock( WikiPageFactory::class );
			$wpFactory->method( 'newFromTitle' )->willReturn( $this->getMockPage() );
		}

		// NOTE: The following could be avoided if the relevant methods were return-typehinted
		$db = $this->createMock( IDatabase::class );
		$db->method( 'select' )->willReturn( $this->createMock( IResultWrapper::class ) );
		$db->method( 'selectRowCount' )->willReturn( 42 );
		$lb = $this->createMock( ILoadBalancer::class );
		$lb->method( 'getConnectionRef' )->willReturn( $db );
		$lbFactory = $this->createMock( LBFactory::class );
		$lbFactory->method( 'getMainLB' )->willReturn( $lb );

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
			$this->createMock( BacklinkCacheFactory::class ),
			$nsInfo ?? $this->createMock( NamespaceInfo::class ),
			$this->createMock( ITextFormatter::class ),
			$page,
			$deleter ?? $this->createMock( Authority::class )
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
			$this->assertStatusError( $expectedMessage, $status );
		}
	}

	public function providePermissions(): iterable {
		$cannotDeleteMsg = "You shall not delete!";
		$cannotDeleteAuthority = $this->mockAnonAuthority(
			static function (
				string $permission,
				PageIdentity $page = null,
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

		$cannotBigDeleteMsg = 'delete-toomanyrevisions';
		$cannotBigDeleteAuthority = $this->mockAnonAuthority(
			static function (
				string $permission,
				PageIdentity $page = null,
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
		$this->assertArrayHasKey( DeletePage::PAGE_BASE, $delPage->getSuccessfulDeletionsIDs() );
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
	 * @covers ::deletionsWereScheduled
	 */
	public function testDeletionsWereScheduled(): void {
		$delPage = $this->getDeletePage(
			$this->createMock( ProperPageIdentity::class ),
			$this->createMock( Authority::class )
		);
		$delPage->deleteUnsafe( 'foo' );
		$this->assertFalse( $delPage->deletionsWereScheduled()[DeletePage::PAGE_BASE] );
	}

	/**
	 * @covers ::deletionsWereScheduled
	 */
	public function testDeletionsWereScheduled__notAttempted(): void {
		$delPage = $this->getDeletePage(
			$this->createMock( ProperPageIdentity::class ),
			$this->createMock( Authority::class )
		);
		$this->expectException( BadMethodCallException::class );
		$delPage->deletionsWereScheduled();
	}

	/**
	 * @param ProperPageIdentity $page
	 * @param WikiPageFactory $wpFactory
	 * @param NamespaceInfo|null $nsInfo
	 * @param string|null $expectedMsg
	 * @covers ::canProbablyDeleteAssociatedTalk
	 * @dataProvider provideAssociatedTalk
	 */
	public function testCanProbablyDeleteAssociatedTalk(
		ProperPageIdentity $page,
		WikiPageFactory $wpFactory,
		?NamespaceInfo $nsInfo,
		?string $expectedMsg
	): void {
		$delPage = $this->getDeletePage( $page, null, null, null, $wpFactory, $nsInfo );

		$res = $delPage->canProbablyDeleteAssociatedTalk();
		if ( $expectedMsg === null ) {
			$this->assertStatusGood( $res );
		} else {
			$this->assertStatusError( $expectedMsg, $res );
		}
	}

	public function provideAssociatedTalk(): Generator {
		$getWpFactory = function ( bool $talkExists ): WikiPageFactory {
			$wpFactory = $this->createMock( WikiPageFactory::class );
			$wpFactory->method( 'newFromTitle' )->willReturnCallback( function ( $t ) {
				$title = Title::castFromPageReference( $t );
				$wikiPage = $this->createMock( WikiPage::class );
				$wikiPage->method( 'getTitle' )->willReturn( $title );
				$wikiPage->method( 'getNamespace' )->willReturn( $title->getNamespace() );
				return $wikiPage;
			} );
			$wpFactory->method( 'newFromLinkTarget' )->willReturnCallback(
				function ( LinkTarget $t ) use ( $talkExists ) {
					$existingTalk = $this->createMock( WikiPage::class );
					$existingTalk->expects( $this->atLeastOnce() )->method( 'exists' )->willReturn( $talkExists );
					return $existingTalk;
				}
			);
			return $wpFactory;
		};
		$nsInfo = new NamespaceInfo( $this->createMock( ServiceOptions::class ), $this->createHookContainer() );

		$talkPage = new PageIdentityValue( 42, NS_TALK, 'Test talk page', PageIdentity::LOCAL );
		yield 'Talk page' => [ $talkPage, $getWpFactory( false ), $nsInfo, 'delete-error-associated-alreadytalk' ];

		$nonTalkPage = new PageIdentityValue( 44, NS_MAIN, 'Test article', PageIdentity::LOCAL );
		yield 'Article without talk page' =>
		[ $nonTalkPage, $getWpFactory( false ), $nsInfo, 'delete-error-associated-doesnotexist' ];

		yield 'Article with talk page' => [ $nonTalkPage, $getWpFactory( true ), $nsInfo, null ];
	}
}
