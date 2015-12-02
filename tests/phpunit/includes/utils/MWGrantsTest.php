<?php
class MWGrantsTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			'wgGrantPermissions' => array(
				'hidden1' => array( 'read' => true, 'autoconfirmed' => false ),
				'hidden2' => array( 'autoconfirmed' => true ),
				'normal' => array( 'edit' => true ),
				'normal2' => array( 'edit' => true, 'create' => true ),
				'admin' => array( 'protect' => true, 'delete' => true ),
			),
			'wgGrantPermissionGroups' => array(
				'hidden1' => 'hidden',
				'hidden2' => 'hidden',
				'normal' => 'normal-group',
				'admin' => 'admin',
			),
		) );
	}

	/**
	 * @covers MWGrants::getValidGrants
	 */
	public function testGetValidGrants() {
		$this->assertSame(
			array( 'hidden1', 'hidden2', 'normal', 'normal2', 'admin' ),
			MWGrants::getValidGrants()
		);
	}

	/**
	 * @covers MWGrants::getRightsByGrant
	 */
	public function testGetRightsByGrant() {
		$this->assertSame(
			array(
				'hidden1' => array( 'read' ),
				'hidden2' => array( 'autoconfirmed' ),
				'normal' => array( 'edit' ),
				'normal2' => array( 'edit', 'create' ),
				'admin' => array( 'protect', 'delete' ),
			),
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
		$this->assertSame( $rights, MWGrants::getGrantRights( $grants ) );
	}

	public static function provideGetGrantRights() {
		return array(
			array( 'hidden1', array( 'read' ) ),
			array( array( 'hidden1', 'hidden2', 'hidden3' ), array( 'read', 'autoconfirmed' ) ),
			array( array( 'normal1', 'normal2' ), array( 'edit', 'create' ) ),
		);
	}

	/**
	 * @dataProvider provideGrantsAreValid
	 * @covers MWGrants::grantsAreValid
	 * @param array $grants
	 * @param bool $valid
	 */
	public function testGrantsAreValid( $grants, $valid ) {
		$this->assertSame( $valid, MWGrants::grantsAreValid( $grants ) );
	}

	public static function provideGrantsAreValid() {
		return array(
			array( array( 'hidden1', 'hidden2' ), true ),
			array( array( 'hidden1', 'hidden3' ), false ),
		);
	}

	/**
	 * @dataProvider provideGetGrantGroups
	 * @covers MWGrants::getGrantGroups
	 * @param array|null $grants
	 * @param array $expect
	 */
	public function testGetGrantGroups( $grants, $expect ) {
		$this->assertSame( $expect, MWGrants::getGrantGroups( $grants ) );
	}

	public static function provideGetGrantGroups() {
		return array(
			array( null, array(
				'hidden' => array( 'hidden1', 'hidden2' ),
				'normal-group' => array( 'normal' ),
				'other' => array( 'normal2' ),
				'admin' => array( 'admin' ),
			) ),
			array( array( 'hidden1', 'normal' ), array(
				'hidden' => array( 'hidden1' ),
				'normal-group' => array( 'normal' ),
			) ),
		);
	}

	/**
	 * @covers MWGrants::getHiddenGrants
	 */
	public function testGetHiddenGrants() {
		$this->assertSame( array( 'hidden1', 'hidden2' ), MWGrants::getHiddenGrants() );
	}

}
