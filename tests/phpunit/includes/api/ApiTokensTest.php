<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiTokens
 */
class ApiTokensTest extends ApiTestCase {

	public function testGettingToken() {
		foreach ( self::$users as $user ) {
			$this->runTokenTest( $user );
		}
	}

	protected function runTokenTest( $user ) {
		$tokens = $this->getTokenList( $user );

		$rights = $user->user->getRights();

		$this->assertArrayHasKey( 'edittoken', $tokens );
		$this->assertArrayHasKey( 'movetoken', $tokens );

		if ( isset( $rights['delete'] ) ) {
			$this->assertArrayHasKey( 'deletetoken', $tokens );
		}

		if ( isset( $rights['block'] ) ) {
			$this->assertArrayHasKey( 'blocktoken', $tokens );
			$this->assertArrayHasKey( 'unblocktoken', $tokens );
		}

		if ( isset( $rights['protect'] ) ) {
			$this->assertArrayHasKey( 'protecttoken', $tokens );
		}
	}

}
