<?php

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers ApiQueryBlockInfoTrait
 */
class ApiQueryBlockInfoTraitTest extends MediaWikiTestCase {

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
		$mock->method( 'getDB' )->willReturn( wfGetDB( DB_REPLICA ) );
		$mock->method( 'getPermissionManager' )
			->willReturn( MediaWikiServices::getInstance()->getPermissionManager() );
		$mock->method( 'getUser' )
			->willReturn( $this->getMutableTestUser()->getUser() );
		$mock->method( 'addTables' )->willReturnCallback( function ( $v ) use ( &$data ) {
			$data['tables'] = array_merge( $data['tables'] ?? [], (array)$v );
		} );
		$mock->method( 'addFields' )->willReturnCallback( function ( $v ) use ( &$data ) {
			$data['fields'] = array_merge( $data['fields'] ?? [], (array)$v );
		} );
		$mock->method( 'addWhere' )->willReturnCallback( function ( $v ) use ( &$data ) {
			$data['where'] = array_merge( $data['where'] ?? [], (array)$v );
		} );
		$mock->method( 'addJoinConds' )->willReturnCallback( function ( $v ) use ( &$data ) {
			$data['joins'] = array_merge( $data['joins'] ?? [], (array)$v );
		} );

		TestingAccessWrapper::newFromObject( $mock )->addBlockInfoToQuery( ...$args );
		$this->assertEquals( $expect, $data );
	}

	public function provideAddBlockInfoToQuery() {
		$queryInfo = DatabaseBlock::getQueryInfo();

		$db = wfGetDB( DB_REPLICA );
		$ts = $db->addQuotes( $db->timestamp( '20190101000000' ) );

		return [
			[ [ false ], [
				'tables' => [ 'blk' => [ 'ipblocks' ] ],
				'fields' => [ 'ipb_deleted' ],
				'where' => [ 'ipb_deleted = 0 OR ipb_deleted IS NULL' ],
				'joins' => [
					'blk' => [ 'LEFT JOIN', [ 'ipb_user=user_id', "ipb_expiry > $ts" ] ]
				],
			] ],

			[ [ true ], [
				'tables' => [ 'blk' => $queryInfo['tables'] ],
				'fields' => $queryInfo['fields'],
				'where' => [ 'ipb_deleted = 0 OR ipb_deleted IS NULL' ],
				'joins' => $queryInfo['joins'] + [
					'blk' => [ 'LEFT JOIN', [ 'ipb_user=user_id', "ipb_expiry > $ts" ] ]
				],
			] ],
		];
	}

}
