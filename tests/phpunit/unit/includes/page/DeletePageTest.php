<?php

namespace MediaWiki\Tests\Unit\Page;

use BadMethodCallException;
use Generator;
use MediaWiki\Cache\BacklinkCacheFactory;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\DeletePage;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Page\RedirectStore;
use MediaWiki\Page\WikiPage;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\UserFactory;
use MediaWikiUnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\SelectQueryBuilder;

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
		$ret->method( 'getRevisionRecord' )->willReturn(
			$this->createMock( RevisionRecord::class )
		);

		$title = $this->createMock( Title::class );
		$title->method( 'getPrefixedText' )->willReturn( 'Foo' );
		$title->method( 'getText' )->willReturn( 'Foo' );
		$title->method( 'getDBkey' )->willReturn( 'Foo' );
		$title->method( 'getNamespace' )->willReturn( 0 );
		$title->method( 'getId' )->willReturn( 123 );

		$ret->method( 'getTitle' )->willReturn( $title );
		$ret->method( 'getNamespace' )->willReturn( $title->getNamespace() );
		$ret->method( 'getDBkey' )->willReturn( $title->getDBkey() );

		$rec = $this->createMock( ExistingPageRecord::class );
		$rec->method( 'exists' )->willReturn( true );
		$rec->method( 'getId' )->willReturn( 123 );
		$rec->method( 'getNamespace' )->willReturn( $title->getNamespace() );
		$rec->method( 'getDBkey' )->willReturn( $title->getDBkey() );

		$ret->method( 'toPageRecord' )->willReturn( $rec );
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
		?Authority $deleter = null,
		?ServiceOptions $options = null,
		?RevisionStore $revStore = null,
		?WikiPageFactory $wpFactory = null,
		?NamespaceInfo $nsInfo = null
	): DeletePage {
		if ( !$wpFactory ) {
			$wpFactory = $this->createMock( WikiPageFactory::class );
			$wpFactory->method( 'newFromTitle' )->willReturn( $this->getMockPage() );
		}

		// NOTE: The following could be avoided if the relevant methods were return-typehinted
		$db = $this->createMock( IDatabase::class );
		$db->method( 'select' )->willReturn( $this->createMock( IResultWrapper::class ) );
		$db->method( 'selectRowCount' )->willReturn( 42 );
		$db->method( 'newSelectQueryBuilder' )->willReturn( new SelectQueryBuilder( $db ) );

		$lbFactory = $this->createMock( LBFactory::class );
		$lbFactory->method( 'getPrimaryDatabase' )->willReturn( $db );

		$ret = new DeletePage(
			$this->createHookContainer(),
			$this->createEventDispatcher(),
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
			$this->createMock( RedirectStore::class ),
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
		$authoritySpec,
		bool $expectedGood,
		?string $expectedMessage = null,
		?int $revDeleteLimit = null
	) {
		$authority = $authoritySpec === 'ultimate'
			? $this->mockRegisteredUltimateAuthority()
			: $this->mockAnonAuthority( $authoritySpec );
		if ( $revDeleteLimit !== null ) {
			$options = $this->getServiceOptions( $revDeleteLimit );
			$revStore = $this->getMockRevisionStore();
			$revStore->expects( $this->atLeastOnce() )
				->method( 'countRevisionsByPageId' )
				->willReturn( $revDeleteLimit + 42 );
		} else {
			$options = null;
			$revStore = null;
		}
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

	public static function providePermissions(): iterable {
		$cannotDeleteMsg = "You shall not delete!";
		$cannotDeleteAuthoritySpec = static function (
			string $permission,
			?PageIdentity $page = null,
			?PermissionStatus $status = null
		) use ( $cannotDeleteMsg ): bool {
			if ( $permission === 'delete' ) {
				if ( $status ) {
					$status->fatal( $cannotDeleteMsg );
				}
				return false;
			}
			return true;
		};
		yield 'Cannot delete' => [ $cannotDeleteAuthoritySpec, false, $cannotDeleteMsg ];

		$cannotBigDeleteMsg = 'delete-toomanyrevisions';
		$cannotBigDeleteAuthoritySpec = static function (
			string $permission,
			?PageIdentity $page = null,
			?PermissionStatus $status = null
		) use ( $cannotBigDeleteMsg ): bool {
			if ( $permission === 'bigdelete' ) {
				if ( $status ) {
					$status->fatal( $cannotBigDeleteMsg );
				}
				return false;
			}
			return true;
		};
		$revDeleteLimit = -1;
		yield 'Cannot bigdelete' => [
			$cannotBigDeleteAuthoritySpec,
			false,
			$cannotBigDeleteMsg,
			$revDeleteLimit
		];

		yield 'Successful' => [ 'ultimate', true ];
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
	 * @covers ::canProbablyDeleteAssociatedTalk
	 * @dataProvider provideAssociatedTalk
	 */
	public function testCanProbablyDeleteAssociatedTalk(
		ProperPageIdentity $page,
		$talkExists,
		?string $expectedMsg
	): void {
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

		$nsInfo = new NamespaceInfo( $this->createMock( ServiceOptions::class ), $this->createHookContainer(), [], [] );
		$delPage = $this->getDeletePage( $page, null, null, null, $wpFactory, $nsInfo );

		$res = $delPage->canProbablyDeleteAssociatedTalk();
		if ( $expectedMsg === null ) {
			$this->assertStatusGood( $res );
		} else {
			$this->assertStatusError( $expectedMsg, $res );
		}
	}

	public static function provideAssociatedTalk(): Generator {
		$talkPage = new PageIdentityValue( 42, NS_TALK, 'Test talk page', PageIdentity::LOCAL );
		yield 'Talk page' => [ $talkPage, false, 'delete-error-associated-alreadytalk' ];

		$nonTalkPage = new PageIdentityValue( 44, NS_MAIN, 'Test article', PageIdentity::LOCAL );
		yield 'Article without talk page' => [ $nonTalkPage, false, 'delete-error-associated-doesnotexist' ];

		yield 'Article with talk page' => [ $nonTalkPage, true, null ];
	}
}
