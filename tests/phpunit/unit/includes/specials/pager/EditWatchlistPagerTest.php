<?php

use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Pager\EditWatchlistPager;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\User\UserIdentity;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * @covers \MediaWiki\Pager\EditWatchlistPager
 */
class EditWatchlistPagerTest extends MediaWikiUnitTestCase {

	private static array $SUBJECT_NAMESPACES = [ 777, 888, 999 ];
	private static int $USER_ID = 111;
	private static int $ARBITRARY_TIMESTAMP = 197203021530;

	private function instantiatePager( array $options = [] ): EditWatchlistPager {
		$namespaceInfo = $this->createMock( NamespaceInfo::class );
		$namespaceInfo->method( 'getSubjectNamespaces' )
			->willReturn( self::$SUBJECT_NAMESPACES );
		$user = $this->createMock( UserIdentity::class );
		$user->method( 'getId' )->willReturn( self::$USER_ID );
		$defaultOptions = [
			'watchedItemStore' => $this->createMock( WatchedItemStore::class ),
			'namespaceInfo' => $namespaceInfo,
			'user' => $user,
			'request' => [ 'method' => 'GET', 'data' => [] ],
		];
		$options = array_merge( $defaultOptions, $options );

		$context = $this->createMock( RequestContext::class );
		$context->method( 'getUser' )
			->willReturn( $options['user'] );
		// need to mock the request because FauxRequest uses MediaWikiServices
		$request = new class(
			$options['request']['data'],
			$options['request']['method'] === "POST"
		) extends FauxRequest {
			public function getText( $name, $default = '' ) {
				return $this->data[ $name ] ?? $default;
			}

			public function getIntOrNull( $name ): ?int {
				$val = $this->data[ $name ] ?? null;
				return is_numeric( $val ) ? intval( $val ) : null;
			}
		};
		$context->method( 'getRequest' )
			->willReturn( $request );

		$db = $this->createMock( IReadableDatabase::class );
		$db->method( 'buildComparison' )
			->willReturnCallback( static function ( $op, $conds ) {
				$sql = '';
				foreach ( array_reverse( $conds ) as $field => $value ) {
					// copied from SQLPlatform::buildComparison
					if ( (int)$value == $value ) {
						$encValue = $value;
					} else {
						$encValue = "'" . $value . "'";
					}
					if ( $sql === '' ) {
						$sql = "$field $op $encValue";
						// Change '>=' to '>' etc. for remaining fields, as the equality is handled separately
						$op = rtrim( $op, '=' );
					} else {
						$sql = "$field $op $encValue OR ($field = $encValue AND ($sql))";
					}
				}
				return $sql;
			} );

		// Parent class constructor uses MediaWikiServices singleton which is not available here, so work around it
		$pager = new class(
			$context, $options['watchedItemStore'], $options['namespaceInfo']
		) extends EditWatchlistPager {
			public function __construct(
				IContextSource $context, protected WatchedItemStoreInterface $wis,
				protected NamespaceInfo $namespaceInfo
			) {
				// copied from the parent, but without referring to MediawikiServices
				$this->setContext( $context );
				$this->mRequest = $this->getRequest();
				$this->mOffset = $this->mRequest->getText( 'offset' );
				$this->mDefaultLimit = 50;
				$this->mLimit = $this->mRequest->getText( 'limit', $this->mDefaultLimit );
				$this->mIsBackwards = ( $this->mRequest->getRawVal( 'dir' ) === 'prev' );
				$this->mIndexField = $this->getIndexField()['title'];
				$this->mExtraSortFields = [];
				$this->mDefaultDirection = self::QUERY_ASCENDING;
			}
		};
		$pager->mDb = $db;
		return $pager;
	}

	public function testFormatRow() {
		$pager = $this->instantiatePager();
		$this->assertSame( '', $pager->formatRow( [] ) );
	}

	public function testGetQueryInfo() {
		$pager = $this->instantiatePager();
		$this->assertSame( [], $pager->getQueryInfo() );
	}

	public function testGetIndexField() {
		$pager = $this->instantiatePager();
		$expected = [
			'title' => [ 'wl_namespace', 'wl_title' ],
		];
		$this->assertSame( $expected, $pager->getIndexField() );
	}

	private function createMockWatchedItem( int $namespace, string $title, ?int $expiry ): WatchedItem {
		$target = $this->createMock( PageIdentity::class );
		$target->method( 'getNamespace' )->willReturn( $namespace );
		$target->method( 'getDBkey' )->willReturn( $title );
		$watchedItem = $this->createMock( WatchedItem::class );
		$watchedItem->method( 'getTarget' )->willReturn( $target );
		$watchedItem->method( 'getExpiry' )->willReturn( $expiry );
		$watchedItem->method( 'getExpiryInDaysText' )->willReturn( $expiry ? $expiry . ' days' : '' );
		return $watchedItem;
	}

	/**
	 * * @dataProvider provideTestReallyDoQuery
	 */
	public function testReallyDoQuery(
		array $request, array $watchedItemsCallAndResponse, FakeResultWrapper $expectedResult
	) {
		$mockUser = $this->createMock( UserIdentity::class );
		$mockUser->method( 'getId' )->willReturn( self::$USER_ID );
		$watchedItemStore = $this->createMock( WatchedItemStore::class );
		$watchedItemStore->expects( $this->once() )
			->method( 'getWatchedItemsForUser' )
			->willReturnCallback(
				function (
					UserIdentity $user, array $options
				) use ( $watchedItemsCallAndResponse, $mockUser, $request ) {
					$this->assertEquals( $user, $mockUser );
					$this->assertEquals( $watchedItemsCallAndResponse['call'], $options );
					return $watchedItemsCallAndResponse['response'];
				}
			);
		$pager = $this->instantiatePager( [
			'request' => $request, 'watchedItemStore' => $watchedItemStore, 'user' => $mockUser
		] );
		[ $offset, $limit, $order ] = $this->getReallyDoQueryArgsFromRequest( $pager->getRequest() );
		$this->assertEquals( $expectedResult, $pager->reallyDoQuery(
				$offset,
				$limit,
				$order,
		) );
	}

	private function getReallyDoQueryArgsFromRequest( WebRequest $request ): array {
		$offset = $request->getText( 'offset' ) ?? '';
		$limit = $request->getIntOrNull( 'limit' ) ?? 50;
		$order = $request->getText( 'dir' ) == 'prev'
			? EditWatchlistPager::QUERY_DESCENDING
			: EditWatchlistPager::QUERY_ASCENDING;
		return [ $offset, $limit, $order ];
	}

	public function provideTestReallyDoQuery(): array {
		return [
			// data set 0: request empty
			[
				'request' => [ 'method' => 'GET', 'data' => [] ],
				'watchedItemsCallAndResponse' => [
					'call' => [
						'limit' => 50,
						'sort' => WatchedItemStoreInterface::SORT_ASC,
						'namespaces' => self::$SUBJECT_NAMESPACES,
						'offsetConds' => [],
					],
					'response' => [
						$this->createMockWatchedItem( 777, 'Page_1', 20250909180500 ),
						$this->createMockWatchedItem( 999, 'Page_2', 20250909180530 ),
						$this->createMockWatchedItem( 666, 'Page_X', null ),
					]
				],
				'expectedResult' => new FakeResultWrapper( [
					[ 'wl_namespace' => 777, 'wl_title' => 'Page_1', 'we_expiry' => 20250909180500,
						'expiryInDaysText' => '20250909180500 days' ],
					[ 'wl_namespace' => 999, 'wl_title' => 'Page_2', 'we_expiry' => 20250909180530,
						'expiryInDaysText' => '20250909180530 days' ],
					[ 'wl_namespace' => 666, 'wl_title' => 'Page_X', 'we_expiry' => null,
						'expiryInDaysText' => '' ],
				] ),
			],
			// data set 1: sort, offset, limit specified in request data
			[
				'request' => [
					'method' => 'GET',
					'data' => [
						'dir' => 'prev',
						'limit' => 20,
						'offset' => '999|Page_2'
					]
				],
				'watchedItems' => [
					'call' => [
						'limit' => 20,
						'sort' => WatchedItemStoreInterface::SORT_DESC,
						'namespaces' => self::$SUBJECT_NAMESPACES,
						'offsetConds' => [ "wl_namespace < 999 OR (wl_namespace = 999 AND (wl_title < 'Page_2'))" ]
					],
					'response' => [
						$this->createMockWatchedItem( 999, 'Page_2', 20250909180530 ),
						$this->createMockWatchedItem( 777, 'Page_1', 20250909180500 ),
					]
				],
				'expectedResult' => new FakeResultWrapper( [
					[ 'wl_namespace' => 999, 'wl_title' => 'Page_2', 'we_expiry' => 20250909180530,
						'expiryInDaysText' => '20250909180530 days' ],
					[ 'wl_namespace' => 777, 'wl_title' => 'Page_1', 'we_expiry' => 20250909180500,
						'expiryInDaysText' => '20250909180500 days' ],
				] ),
			],
		];
	}
}
