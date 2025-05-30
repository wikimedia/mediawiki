<?php

use MediaWiki\Exception\PermissionsError;
use MediaWiki\Message\Message;
use MediaWiki\Permissions\PermissionStatus;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Exception\PermissionsError
 */
class PermissionsErrorTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		$this->setGroupPermissions( '*', 'testpermission', true );
	}

	public static function provideConstruction() {
		$status = new PermissionStatus();
		$status->error( 'cat', 1, 2 );
		$status->error( 'dog', 3, 4 );
		$array = [ [ 'cat', 1, 2 ], [ 'dog', 3, 4 ] ];
		yield [ null, $status, $status ];
		yield [ null, $array, $status ];
		yield [ 'testpermission', $status, $status ];
		yield [ 'testpermission', $array, $status ];

		yield [ 'testpermission', [],
			PermissionStatus::newEmpty()->fatal( 'badaccess-groups', Message::listParam( [ '*' ], 'comma' ), 1 ) ];
	}

	/**
	 * @dataProvider provideConstruction
	 */
	public function testConstruction( $permission, $errors, $expected ) {
		$e = new PermissionsError( $permission, $errors );
		$et = TestingAccessWrapper::newFromObject( $e );

		$this->expectDeprecationAndContinue( '/PermissionsError::\\$permission/' );
		$this->assertEquals( $permission, $e->permission );

		$this->assertStatusMessagesExactly( $expected, $et->status );
	}

	public static function provideInvalidConstruction() {
		yield [ null, null ];
		yield [ null, [] ];
		yield [ null, new PermissionStatus() ];
	}

	/**
	 * @dataProvider provideInvalidConstruction
	 */
	public function testInvalidConstruction( $permission, $errors ) {
		$this->expectException( InvalidArgumentException::class );
		$e = new PermissionsError( $permission, $errors );
	}
}
