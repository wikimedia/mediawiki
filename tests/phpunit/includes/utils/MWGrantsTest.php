<?php

use MediaWiki\MainConfigNames;

class MWGrantsTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
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
		] );
	}

	/**
	 * @covers MWGrants::getValidGrants
	 */
	public function testGetValidGrants() {
		$this->hideDeprecated( 'MWGrants::getValidGrants' );
		$this->assertSame(
			[ 'hidden1', 'hidden2', 'normal', 'normal2', 'admin' ],
			MWGrants::getValidGrants()
		);
	}

	/**
	 * @covers MWGrants::getRightsByGrant
	 */
	public function testGetRightsByGrant() {
		$this->hideDeprecated( 'MWGrants::getRightsByGrant' );
		$this->assertSame(
			[
				'hidden1' => [ 'read' ],
				'hidden2' => [ 'autoconfirmed' ],
				'normal' => [ 'edit' ],
				'normal2' => [ 'edit', 'create' ],
				'admin' => [ 'protect', 'delete' ],
			],
			MWGrants::getRightsByGrant()
		);
	}

	/**
	 * @dataProvider provideGetGrantRights
	 * @covers MWGrants::getGrantRights
	 * @param array|string $grants
	 * @param array $rights
	 */
	public function testGetGrantRights( $grants, $rights ) {
		$this->hideDeprecated( 'MWGrants::getGrantRights' );
		$this->assertSame( $rights, MWGrants::getGrantRights( $grants ) );
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
	 * @covers MWGrants::grantsAreValid
	 * @param array $grants
	 * @param bool $valid
	 */
	public function testGrantsAreValid( $grants, $valid ) {
		$this->hideDeprecated( 'MWGrants::grantsAreValid' );
		$this->assertSame( $valid, MWGrants::grantsAreValid( $grants ) );
	}

	public static function provideGrantsAreValid() {
		return [
			[ [ 'hidden1', 'hidden2' ], true ],
			[ [ 'hidden1', 'hidden3' ], false ],
		];
	}

	/**
	 * @dataProvider provideGetGrantGroups
	 * @covers MWGrants::getGrantGroups
	 * @param array|null $grants
	 * @param array $expect
	 */
	public function testGetGrantGroups( $grants, $expect ) {
		$this->hideDeprecated( 'MWGrants::getGrantGroups' );
		$this->assertSame( $expect, MWGrants::getGrantGroups( $grants ) );
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
	 * @covers MWGrants::getHiddenGrants
	 */
	public function testGetHiddenGrants() {
		$this->hideDeprecated( 'MWGrants::getHiddenGrants' );
		$this->assertSame( [ 'hidden1', 'hidden2' ], MWGrants::getHiddenGrants() );
	}

}
