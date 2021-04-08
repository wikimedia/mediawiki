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
			->getGroupPermissions( [ 'unittesters' ] );
		$this->assertContains( 'runtest', $rights );
		$this->assertNotContains( 'writetest', $rights );
		$this->assertNotContains( 'modifytest', $rights );
		$this->assertNotContains( 'nukeworld', $rights );

		$rights = $this->createGroupPermissionsLookup()
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
		$result = $this->createGroupPermissionsLookup()
			->groupHasPermission( 'unittesters', 'test' );
		$this->assertTrue( $result );

		$result = $this->createGroupPermissionsLookup()
			->groupHasPermission( 'formertesters', 'runtest' );
		$this->assertFalse( $result );
	}
}
