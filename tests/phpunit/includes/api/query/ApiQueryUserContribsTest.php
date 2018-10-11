<?php

/**
 * @group API
 * @group Database
 * @group medium
 * @covers ApiQueryUserContribs
 */
class ApiQueryUserContribsTest extends ApiTestCase {
	public function addDBDataOnce() {
		global $wgActorTableSchemaMigrationStage;

		$reset = new \Wikimedia\ScopedCallback( function ( $v ) {
			global $wgActorTableSchemaMigrationStage;
			$wgActorTableSchemaMigrationStage = $v;
			$this->overrideMwServices();
		}, [ $wgActorTableSchemaMigrationStage ] );
		$wgActorTableSchemaMigrationStage = SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD;
		$this->overrideMwServices();

		$users = [
			User::newFromName( '192.168.2.2', false ),
			User::newFromName( '192.168.2.1', false ),
			User::newFromName( '192.168.2.3', false ),
			User::createNew( __CLASS__ . ' B' ),
			User::createNew( __CLASS__ . ' A' ),
			User::createNew( __CLASS__ . ' C' ),
			User::newFromName( 'IW>' . __CLASS__, false ),
		];

		$title = Title::newFromText( __CLASS__ );
		$page = WikiPage::factory( $title );
		for ( $i = 0; $i < 3; $i++ ) {
			foreach ( array_reverse( $users ) as $user ) {
				$status = $page->doEditContent(
					ContentHandler::makeContent( "Test revision $user #$i", $title ), 'Test edit', 0, false, $user
				);
				if ( !$status->isOK() ) {
					$this->fail( "Failed to edit $title: " . $status->getWikiText( false, false, 'en' ) );
				}
			}
		}
	}

	/**
	 * @dataProvider provideSorting
	 * @param int $stage SCHEMA_COMPAT contants for $wgActorTableSchemaMigrationStage
	 * @param array $params Extra parameters for the query
	 * @param bool $reverse Reverse order?
	 * @param int $revs Number of revisions to expect
	 */
	public function testSorting( $stage, $params, $reverse, $revs ) {
		// FIXME: fails under sqlite
		$this->markTestSkippedIfDbType( 'sqlite' );

		$this->setMwGlobals( 'wgActorTableSchemaMigrationStage', $stage );
		$this->overrideMwServices();

		if ( isset( $params['ucuserids'] ) ) {
			$params['ucuserids'] = implode( '|', array_map( 'User::idFromName', $params['ucuserids'] ) );
		}
		if ( isset( $params['ucuser'] ) ) {
			$params['ucuser'] = implode( '|', $params['ucuser'] );
		}

		$sort = 'rsort';
		if ( $reverse ) {
			$params['ucdir'] = 'newer';
			$sort = 'sort';
		}

		$params += [
			'action' => 'query',
			'list' => 'usercontribs',
			'ucprop' => 'ids',
		];

		$apiResult = $this->doApiRequest( $params + [ 'uclimit' => 500 ] );
		$this->assertArrayNotHasKey( 'continue', $apiResult[0] );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'usercontribs', $apiResult[0]['query'] );

		$count = 0;
		$ids = [];
		foreach ( $apiResult[0]['query']['usercontribs'] as $page ) {
			$count++;
			$ids[$page['user']][] = $page['revid'];
		}
		$this->assertSame( $revs, $count, 'Expected number of revisions' );
		foreach ( $ids as $user => $revids ) {
			$sorted = $revids;
			call_user_func_array( $sort, [ &$sorted ] );
			$this->assertSame( $sorted, $revids, "IDs for $user are sorted" );
		}

		for ( $limit = 1; $limit < $revs; $limit++ ) {
			$continue = [];
			$count = 0;
			$batchedIds = [];
			while ( $continue !== null ) {
				$apiResult = $this->doApiRequest( $params + [ 'uclimit' => $limit ] + $continue );
				$this->assertArrayHasKey( 'query', $apiResult[0], "Batching with limit $limit" );
				$this->assertArrayHasKey( 'usercontribs', $apiResult[0]['query'],
					"Batching with limit $limit" );
				$continue = $apiResult[0]['continue'] ?? null;
				foreach ( $apiResult[0]['query']['usercontribs'] as $page ) {
					$count++;
					$batchedIds[$page['user']][] = $page['revid'];
				}
				$this->assertLessThanOrEqual( $revs, $count, "Batching with limit $limit" );
			}
			$this->assertSame( $ids, $batchedIds, "Result set is the same when batching with limit $limit" );
		}
	}

	public static function provideSorting() {
		$users = [ __CLASS__ . ' A', __CLASS__ . ' B', __CLASS__ . ' C' ];
		$users2 = [ __CLASS__ . ' A', __CLASS__ . ' B', __CLASS__ . ' D' ];
		$ips = [ '192.168.2.1', '192.168.2.2', '192.168.2.3', '192.168.2.4' ];

		foreach (
			[
				'old' => SCHEMA_COMPAT_OLD,
				'read old' => SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD,
				'read new' => SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW,
				'new' => SCHEMA_COMPAT_NEW,
			] as $stageName => $stage
		) {
			foreach ( [ false, true ] as $reverse ) {
				$name = $stageName . ( $reverse ? ', reverse' : '' );
				yield "Named users, $name" => [ $stage, [ 'ucuser' => $users ], $reverse, 9 ];
				yield "Named users including a no-edit user, $name" => [
					$stage, [ 'ucuser' => $users2 ], $reverse, 6
				];
				yield "IP users, $name" => [ $stage, [ 'ucuser' => $ips ], $reverse, 9 ];
				yield "All users, $name" => [
					$stage, [ 'ucuser' => array_merge( $users, $ips ) ], $reverse, 18
				];
				yield "User IDs, $name" => [ $stage, [ 'ucuserids' => $users ], $reverse, 9 ];
				yield "Users by prefix, $name" => [ $stage, [ 'ucuserprefix' => __CLASS__ ], $reverse, 9 ];
				yield "IPs by prefix, $name" => [ $stage, [ 'ucuserprefix' => '192.168.2.' ], $reverse, 9 ];
			}
		}
	}

	/**
	 * @dataProvider provideInterwikiUser
	 * @param int $stage SCHEMA_COMPAT constants for $wgActorTableSchemaMigrationStage
	 */
	public function testInterwikiUser( $stage ) {
		$this->setMwGlobals( 'wgActorTableSchemaMigrationStage', $stage );
		$this->overrideMwServices();

		$params = [
			'action' => 'query',
			'list' => 'usercontribs',
			'ucuser' => 'IW>' . __CLASS__,
			'ucprop' => 'ids',
			'uclimit' => 'max',
		];

		$apiResult = $this->doApiRequest( $params );
		$this->assertArrayNotHasKey( 'continue', $apiResult[0] );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'usercontribs', $apiResult[0]['query'] );

		$count = 0;
		$ids = [];
		foreach ( $apiResult[0]['query']['usercontribs'] as $page ) {
			$count++;
			$this->assertSame( 'IW>' . __CLASS__, $page['user'], 'Correct user returned' );
			$ids[] = $page['revid'];
		}
		$this->assertSame( 3, $count, 'Expected number of revisions' );
		$sorted = $ids;
		rsort( $sorted );
		$this->assertSame( $sorted, $ids, "IDs are sorted" );
	}

	public static function provideInterwikiUser() {
		return [
			'old' => [ SCHEMA_COMPAT_OLD ],
			'read old' => [ SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_OLD ],
			'read new' => [ SCHEMA_COMPAT_WRITE_BOTH | SCHEMA_COMPAT_READ_NEW ],
			'new' => [ SCHEMA_COMPAT_NEW ],
		];
	}

}
