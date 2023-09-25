<?php

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers ApiQueryBlockInfoTrait
 * @group Database
 */
class ApiQueryBlockInfoTraitTest extends MediaWikiIntegrationTestCase {

	public function testUsesApiBlockInfoTrait() {
		$this->assertTrue( method_exists( ApiQueryBlockInfoTrait::class, 'getBlockDetails' ),
			'ApiQueryBlockInfoTrait::getBlockDetails exists' );
	}

	/**
	 * @dataProvider provideAddBlockInfoToQuery
	 */
	public function testAddBlockInfoToQuery( $args, $expect ) {
		// Fake timestamp to show up in the queries
		$reset = ConvertibleTimestamp::setFakeTime( '20190101000000' );

		$data = [];

		$mock = $this->getMockForTrait( ApiQueryBlockInfoTrait::class );
		$mock->method( 'getDB' )->willReturn( $this->getDb() );
		$mock->method( 'getAuthority' )
			->willReturn( $this->getMutableTestUser()->getUser() );
		$mock->method( 'addTables' )->willReturnCallback( static function ( $v ) use ( &$data ) {
			$data['tables'] = array_merge( $data['tables'] ?? [], (array)$v );
		} );
		$mock->method( 'addFields' )->willReturnCallback( static function ( $v ) use ( &$data ) {
			$data['fields'] = array_merge( $data['fields'] ?? [], (array)$v );
		} );
		$mock->method( 'addWhere' )->willReturnCallback( static function ( $v ) use ( &$data ) {
			$data['where'] = array_merge( $data['where'] ?? [], (array)$v );
		} );
		$mock->method( 'addJoinConds' )->willReturnCallback( static function ( $v ) use ( &$data ) {
			$data['joins'] = array_merge( $data['joins'] ?? [], (array)$v );
		} );

		TestingAccessWrapper::newFromObject( $mock )->addBlockInfoToQuery( ...$args );
		$this->assertEquals( $expect, $data );
	}

	public static function provideAddBlockInfoToQuery() {
		$queryInfo = DatabaseBlock::getQueryInfo();

		$db = MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->getReplicaDatabase();
		$ts = $db->addQuotes( $db->timestamp( '20190101000000' ) );

		return [
			[ [ false ], [
				'tables' => [ 'blk' => [ 'ipblocks' ] ],
				'fields' => [ 'ipb_deleted' ],
				'where' => [ 'ipb_deleted' => [ 0, null ] ],
				'joins' => [
					'blk' => [ 'LEFT JOIN', [ 'ipb_user=user_id', "ipb_expiry > $ts" ] ]
				],
			] ],

			[ [ true ], [
				'tables' => [ 'blk' => $queryInfo['tables'] ],
				'fields' => $queryInfo['fields'],
				'where' => [ 'ipb_deleted' => [ 0, null ] ],
				'joins' => $queryInfo['joins'] + [
					'blk' => [ 'LEFT JOIN', [ 'ipb_user=user_id', "ipb_expiry > $ts" ] ]
				],
			] ],
		];
	}

}
