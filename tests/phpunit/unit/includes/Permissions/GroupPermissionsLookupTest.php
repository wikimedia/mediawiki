<?php

namespace MediaWiki\Tests\Unit\Permissions;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWikiUnitTestCase;

class GroupPermissionsLookupTest extends MediaWikiUnitTestCase {

	/**
	 * @return GroupPermissionsLookup
	 */
	private function createGroupPermissionsLookup(): GroupPermissionsLookup {
		return new GroupPermissionsLookup(
			new ServiceOptions( GroupPermissionsLookup::CONSTRUCTOR_OPTIONS, [
				'GroupPermissions' => [
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
				'RevokePermissions' => [
					'unittesters' => [
						'nukeworld' => true,
					],
					'formertesters' => [
						'runtest' => true,
					],
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

	/**
	 * @covers  \MediaWiki\Permissions\GroupPermissionsLookup::getGroupsWithAnyPermissions
	 */
	public function testGetGroupsWithAnyPermissions() {
		$this->assertArrayEquals(
			[ 'unittesters', 'testwriters' ],
			$this->createGroupPermissionsLookup()->getGroupsWithAnyPermissions( 'runtest', 'modifytest' )
		);
	}

	/**
	 * @covers \MediaWiki\Permissions\GroupPermissionsLookup::getGroupsWithAllPermissions
	 */
	public function testGetGroupsWithAllPermissions() {
		$this->assertArrayEquals(
			[],
			$this->createGroupPermissionsLookup()->getGroupsWithAllPermissions( 'runtest', 'modifytest' )
		);
		$this->assertArrayEquals(
			[ 'unittesters' ],
			$this->createGroupPermissionsLookup()->getGroupsWithAllPermissions( 'test', 'runtest' )
		);
	}

	public static function provideGetGroupsWithPermission() {
		return [
			[
				[ 'unittesters', 'testwriters' ],
				'test'
			],
			[
				[ 'unittesters' ],
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
		$rights = $this->createGroupPermissionsLookup()
			->getGroupPermissions( 'unittesters' );
		$this->assertContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );

		$rights = $this->createGroupPermissionsLookup()
			->getGroupPermissions( 'unittesters', 'testwriters' );
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
			->getGroupPermissions( 'unittesters', 'formertesters' );
		$this->assertNotContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );
	}

	/**
	 * @covers \MediaWiki\Permissions\GroupPermissionsLookup::groupHasPermission
	 */
	public function testGroupHasPermission() {
		$this->assertTrue( $this->createGroupPermissionsLookup()
			->groupHasPermission( 'unittesters', 'test' ) );
		$this->assertFalse( $this->createGroupPermissionsLookup()
			->groupHasPermission( 'formertesters', 'runtest' ) );
	}

	/**
	 * @covers \MediaWiki\Permissions\GroupPermissionsLookup::groupHasAnyPermission
	 */
	public function testGroupHasAnyPermission() {
		$this->assertTrue( $this->createGroupPermissionsLookup()
			->groupHasAnyPermission( 'unittesters', 'test', 'runtest' ) );
		$this->assertTrue( $this->createGroupPermissionsLookup()
			->groupHasAnyPermission( 'unittesters', 'nukeworld', 'runtest' ) );
		$this->assertFalse( $this->createGroupPermissionsLookup()
			->groupHasAnyPermission( 'formertesters', 'nukeworld', 'runtest' ) );
	}

	/**
	 * @covers \MediaWiki\Permissions\GroupPermissionsLookup::groupHasAllPermissions
	 */
	public function testGroupHasAllPermission() {
		$this->assertTrue( $this->createGroupPermissionsLookup()
			->groupHasAllPermissions( 'unittesters', 'test', 'runtest' ) );
		$this->assertFalse( $this->createGroupPermissionsLookup()
			->groupHasAllPermissions( 'unittesters', 'nukeworld', 'runtest' ) );
		$this->assertFalse( $this->createGroupPermissionsLookup()
			->groupHasAllPermissions( 'formertesters', 'nukeworld', 'runtest' ) );
	}
}
