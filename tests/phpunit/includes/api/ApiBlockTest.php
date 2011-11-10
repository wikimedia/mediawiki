<?php

/**
 * @group Database
 */
class ApiBlockTest extends ApiTestCase {

	function setUp() {
		parent::setUp();
		$this->doLogin();
	}

	function getTokens() {
		return $this->getTokenList( self::$users['sysop'] );
	}

	function addDBData() {
		$user = User::newFromName( 'UTApiBlockee' );

		if ( $user->getId() == 0 ) {
			$user->addToDatabase();
			$user->setPassword( 'UTApiBlockeePassword' );

			$user->saveSettings();
		}
	}

	function testMakeNormalBlock() {

		$data = $this->getTokens();

		$user = User::newFromName( 'UTApiBlockee' );

		if ( !$user->getId() ) {
			$this->markTestIncomplete( "The user UTApiBlockee does not exist" );
		}

		if( !isset( $data[0]['query']['pages'] ) ) {
			$this->markTestIncomplete( "No block token found" );
		}

		$keys = array_keys( $data[0]['query']['pages'] );
		$key = array_pop( $keys );
		$pageinfo = $data[0]['query']['pages'][$key];

		$data = $this->doApiRequest( array(
			'action' => 'block',
			'user' => 'UTApiBlockee',
			'reason' => 'Some reason',
			'token' => $pageinfo['blocktoken'] ), $data, false, self::$users['sysop']->user );

		$block = Block::newFromTarget('UTApiBlockee');

		$this->assertTrue( !is_null( $block ), 'Block is valid' );

		$this->assertEquals( 'UTApiBlockee', (string)$block->getTarget() );
		$this->assertEquals( 'Some reason', $block->mReason );
		$this->assertEquals( $this->db->getInfinity(), $block->mExpiry );

	}

}
