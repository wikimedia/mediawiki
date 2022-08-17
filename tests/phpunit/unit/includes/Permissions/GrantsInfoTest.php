<?php

namespace MediaWiki\Tests\Unit\Permissions;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\GrantsInfo;
use MediaWikiUnitTestCase;

/**
 * @coversDefaultClass \MediaWiki\Permissions\GrantsInfo
 */
class GrantsInfoTest extends MediaWikiUnitTestCase {

	/** @var GrantsInfo */
	private $grantsInfo;

	protected function setUp(): void {
		parent::setUp();

		$config = [
			MainConfigNames::GrantPermissions => [
				'hidden1' => [ 'read' => true, 'autoconfirmed' => false ],
				'hidden2' => [ 'autoconfirmed' => true ],
				'normal' => [ 'edit' => true ],
				'normal2' => [ 'edit' => true, 'create' => true ],
				'admin' => [ 'protect' => true, 'delete' => true ],
			],
			MainConfigNames::GrantPermissionGroups => [
				'hidden1' => 'hidden',
				'hidden2' => 'hidden',
				'normal' => 'normal-group',
				'admin' => 'admin',
			],
		];

		$this->grantsInfo = new GrantsInfo(
			new ServiceOptions(
				GrantsInfo::CONSTRUCTOR_OPTIONS,
				$config
			)
		);
	}

	/**
	 * @covers ::getValidGrants
	 */
	public function testGetValidGrants() {
		$this->assertSame(
			[ 'hidden1', 'hidden2', 'normal', 'normal2', 'admin' ],
			$this->grantsInfo->getValidGrants()
		);
	}

	/**
	 * @covers ::getRightsByGrant
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
	 * @covers ::getGrantRights
	 * @param array|string $grants
	 * @param array $rights
	 */
	public function testGetGrantRights( $grants, $rights ) {
		$this->assertSame( $rights, $this->grantsInfo->getGrantRights( $grants ) );
	}

	public function provideGetGrantRights() {
		return [
			'anon' => [ 'hidden1', [ 'read' ] ],
			'newbie' => [ [ 'hidden1', 'hidden2', 'hidden3' ], [ 'read', 'autoconfirmed' ] ],
			'basic' => [ [ 'normal1', 'normal2' ], [ 'edit', 'create' ] ],
		];
	}

	/**
	 * @dataProvider provideGrantsAreValid
	 * @covers ::grantsAreValid
	 * @param array $grants
	 * @param bool $valid
	 */
	public function testGrantsAreValid( $grants, $valid ) {
		$this->assertSame( $valid, $this->grantsInfo->grantsAreValid( $grants ) );
	}

	public function provideGrantsAreValid() {
		return [
			[ [ 'hidden1', 'hidden2' ], true ],
			[ [ 'hidden1', 'hidden3' ], false ],
		];
	}

	/**
	 * @dataProvider provideGetGrantGroups
	 * @covers ::getGrantGroups
	 * @param array|null $grants
	 * @param array $expect
	 */
	public function testGetGrantGroups( $grants, $expect ) {
		$this->assertSame( $expect, $this->grantsInfo->getGrantGroups( $grants ) );
	}

	public function provideGetGrantGroups() {
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
	 * @covers ::getHiddenGrants
	 */
	public function testGetHiddenGrants() {
		$this->assertSame(
			[ 'hidden1', 'hidden2' ],
			$this->grantsInfo->getHiddenGrants()
		);
	}

}
