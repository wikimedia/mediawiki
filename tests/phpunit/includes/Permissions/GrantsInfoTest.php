<?php

use MediaWiki\Permissions\GrantsInfo;

class GrantsInfoTest extends MediaWikiIntegrationTestCase {

	/** @var GrantsInfo */
	private $grantsInfo;

	protected function setUp() : void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgGrantPermissions' => [
				'hidden1' => [ 'read' => true, 'autoconfirmed' => false ],
				'hidden2' => [ 'autoconfirmed' => true ],
				'normal' => [ 'edit' => true ],
				'normal2' => [ 'edit' => true, 'create' => true ],
				'admin' => [ 'protect' => true, 'delete' => true ],
			],
			'wgGrantPermissionGroups' => [
				'hidden1' => 'hidden',
				'hidden2' => 'hidden',
				'normal' => 'normal-group',
				'admin' => 'admin',
			],
		] );

		$this->grantsInfo = $this->getServiceContainer()->getGrantsInfo();
	}

	/**
	 * @covers MediaWiki\Permissions\GrantsInfo::getValidGrants
	 */
	public function testGetValidGrants() {
		$this->assertSame(
			[ 'hidden1', 'hidden2', 'normal', 'normal2', 'admin' ],
			$this->grantsInfo->getValidGrants()
		);
	}

	/**
	 * @covers MediaWiki\Permissions\GrantsInfo::getRightsByGrant
	 */
	public function testGetRightsByGrant() {
		$this->assertSame(
			[
				'hidden1' => [ 'read' ],
				'hidden2' => [ 'autoconfirmed' ],
				'normal' => [ 'edit' ],
				'normal2' => [ 'edit', 'create' ],
				'admin' => [ 'protect', 'delete' ],
			],
			$this->grantsInfo->getRightsByGrant()
		);
	}

	/**
	 * @dataProvider provideGetGrantRights
	 * @covers MediaWiki\Permissions\GrantsInfo::getGrantRights
	 * @param array|string $grants
	 * @param array $rights
	 */
	public function testGetGrantRights( $grants, $rights ) {
		$this->assertSame( $rights, $this->grantsInfo->getGrantRights( $grants ) );
	}

	public static function provideGetGrantRights() {
		return [
			[ 'hidden1', [ 'read' ] ],
			[ [ 'hidden1', 'hidden2', 'hidden3' ], [ 'read', 'autoconfirmed' ] ],
			[ [ 'normal1', 'normal2' ], [ 'edit', 'create' ] ],
		];
	}

	/**
	 * @dataProvider provideGrantsAreValid
	 * @covers MediaWiki\Permissions\GrantsInfo::grantsAreValid
	 * @param array $grants
	 * @param bool $valid
	 */
	public function testGrantsAreValid( $grants, $valid ) {
		$this->assertSame( $valid, $this->grantsInfo->grantsAreValid( $grants ) );
	}

	public static function provideGrantsAreValid() {
		return [
			[ [ 'hidden1', 'hidden2' ], true ],
			[ [ 'hidden1', 'hidden3' ], false ],
		];
	}

	/**
	 * @dataProvider provideGetGrantGroups
	 * @covers MediaWiki\Permissions\GrantsInfo::getGrantGroups
	 * @param array|null $grants
	 * @param array $expect
	 */
	public function testGetGrantGroups( $grants, $expect ) {
		$this->assertSame( $expect, $this->grantsInfo->getGrantGroups( $grants ) );
	}

	public static function provideGetGrantGroups() {
		return [
			[ null, [
				'hidden' => [ 'hidden1', 'hidden2' ],
				'normal-group' => [ 'normal' ],
				'other' => [ 'normal2' ],
				'admin' => [ 'admin' ],
			] ],
			[ [ 'hidden1', 'normal' ], [
				'hidden' => [ 'hidden1' ],
				'normal-group' => [ 'normal' ],
			] ],
		];
	}

	/**
	 * @covers MediaWiki\Permissions\GrantsInfo::getHiddenGrants
	 */
	public function testGetHiddenGrants() {
		$this->assertSame(
			[ 'hidden1', 'hidden2' ],
			$this->grantsInfo->getHiddenGrants()
		);
	}

}
