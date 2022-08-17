<?php

namespace MediaWiki\Tests\Unit\Permissions;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWikiUnitTestCase;

class GroupPermissionsLookupTest extends MediaWikiUnitTestCase {

	/**
	 * @return GroupPermissionsLookup
	 */
	private function createGroupPermissionsLookup(): GroupPermissionsLookup {
		return new GroupPermissionsLookup(
			new ServiceOptions( GroupPermissionsLookup::CONSTRUCTOR_OPTIONS, [
				MainConfigNames::GroupPermissions => [
					'unittesters' => [
						'test' => true,
						'runtest' => true,
						'nukeworld' => true
					],
					'testwriters' => [
						'test' => true,
						'writetest' => true,
						'modifytest' => true,
					],
				],
				MainConfigNames::RevokePermissions => [
					'unittesters' => [
						'nukeworld' => true,
					],
					'formertesters' => [
						'runtest' => true,
					],
				],
				MainConfigNames::GroupInheritsPermissions => [
					'inheritedtesters' => 'unittesters',
				],
			] )
		);
	}

	/**
	 * @dataProvider provideGetGroupsWithPermission
	 * @covers \MediaWiki\Permissions\GroupPermissionsLookup::getGroupsWithPermission
	 */
	public function testGetGroupsWithPermission( $expected, $right ) {
		$result = $this->createGroupPermissionsLookup()->getGroupsWithPermission( $right );
		sort( $result );
		sort( $expected );

		$this->assertEquals( $expected, $result, "Groups with permission $right" );
	}

	public static function provideGetGroupsWithPermission() {
		return [
			[
				[ 'unittesters', 'testwriters', 'inheritedtesters' ],
				'test'
			],
			[
				[ 'unittesters', 'inheritedtesters' ],
				'runtest'
			],
			[
				[ 'testwriters' ],
				'writetest'
			],
			[
				[ 'testwriters' ],
				'modifytest'
			],
		];
	}

	/**
	 * @covers \MediaWiki\Permissions\GroupPermissionsLookup::getGroupPermissions
	 */
	public function testGroupPermissions() {
		$lookup = $this->createGroupPermissionsLookup();
		$rights = $lookup
			->getGroupPermissions( [ 'unittesters' ] );
		$this->assertContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );

		$this->assertEquals(
			$lookup->getGroupPermissions( [ 'unittesters' ] ),
			$lookup->getGroupPermissions( [ 'inheritedtesters' ] )
		);

		$rights = $lookup
			->getGroupPermissions( [ 'unittesters', 'testwriters' ] );
		$this->assertContains( 'runtest', $rights );
		$this->assertContains( 'writetest', $rights );
		$this->assertContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );
	}

	/**
	 * @covers \MediaWiki\Permissions\GroupPermissionsLookup::getGroupPermissions
	 */
	public function testRevokePermissions() {
		$rights = $this->createGroupPermissionsLookup()
			->getGroupPermissions( [ 'unittesters', 'formertesters' ] );
		$this->assertNotContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );
	}

	/**
	 * @covers \MediaWiki\Permissions\GroupPermissionsLookup::groupHasPermission
	 */
	public function testGroupHasPermission() {
		$lookup = $this->createGroupPermissionsLookup();
		$this->assertTrue( $lookup->groupHasPermission( 'unittesters', 'test' ) );
		$this->assertTrue( $lookup->groupHasPermission( 'inheritedtesters', 'test' ) );

		$this->assertFalse( $lookup->groupHasPermission( 'formertesters', 'runtest' ) );
	}

	/**
	 * @covers \MediaWiki\Permissions\GroupPermissionsLookup::getGrantedPermissions
	 */
	public function testGetGrantedPermissions() {
		$lookup = $this->createGroupPermissionsLookup();
		$this->assertSame(
			$lookup->getGrantedPermissions( 'unittesters' ),
			[ 'test', 'runtest', 'nukeworld' ]
		);
		$this->assertSame(
			$lookup->getGrantedPermissions( 'inheritedtesters' ),
			[ 'test', 'runtest', 'nukeworld' ]
		);
	}

	/**
	 * @covers \MediaWiki\Permissions\GroupPermissionsLookup::getRevokedPermissions
	 */
	public function testGetRevokedPermissions() {
		$lookup = $this->createGroupPermissionsLookup();
		$this->assertSame(
			$lookup->getRevokedPermissions( 'unittesters' ),
			[ 'nukeworld' ]
		);
		$this->assertSame(
			$lookup->getRevokedPermissions( 'inheritedtesters' ),
			[ 'nukeworld' ]
		);
	}
}
