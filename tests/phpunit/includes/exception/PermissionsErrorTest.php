<?php

use MediaWiki\Permissions\PermissionStatus;

/**
 * @covers PermissionsError
 */
class PermissionsErrorTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		$this->setGroupPermissions( '*', 'testpermission', true );
	}

	public function provideConstruction() {
		$status = new PermissionStatus();
		$status->error( 'cat', 1, 2 );
		$status->warning( 'dog', 3, 4 );
		$expected = [ [ 'cat', 1, 2 ], [ 'dog', 3, 4 ] ];
		yield [ null, $status, $expected ];
		yield [ 'testpermission', $status, $expected ];

		yield [ 'testpermission', [], [ [ 'badaccess-groups', Message::listParam( [ '*' ], 'comma' ), 1 ] ] ];
	}

	/**
	 * @dataProvider provideConstruction
	 */
	public function testConstruction( $permission, $errors, $expected ) {
		$e = new PermissionsError( $permission, $errors );
		$this->assertEquals( $permission, $e->permission );
		$this->assertArrayEquals( $expected, $e->errors );
	}

	public function provideInvalidConstruction() {
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
