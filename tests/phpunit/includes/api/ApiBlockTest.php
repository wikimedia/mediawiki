<?php

require_once dirname( __FILE__ ) . '/ApiSetup.php';

/**
 * @group Database
 * @group Destructive
 */
class ApiBlockTest extends ApiTestSetup {

	function setUp() {
		parent::setUp();
		$this->doLogin();
	}
	
	function getTokens() {
		return $this->getTokenList( $this->sysopUser );
	}

	function addDBData() {
		$user = User::newFromName( 'UTBlockee' );
		
		if ( $user->getId() == 0 ) {
			$user->addToDatabase();
			$user->setPassword( 'UTBlockeePassword' );

			$user->saveSettings();
		}
	}
	

	
	function testMakeNormalBlock() {
		
		$data = $this->getTokens();
		
		$user = User::newFromName( 'UTBlockee' );
		
		if ( !$user->getId() ) {
			$this->markTestIncomplete( "The user UTBlockee does not exist" );
		}
		
		if( !isset( $data[0]['query']['pages'] ) ) {
			$this->markTestIncomplete( "No block token found" );
		}
		
		$keys = array_keys( $data[0]['query']['pages'] );
		$key = array_pop( $keys );
		$pageinfo = $data[0]['query']['pages'][$key];
		
		$data = $this->doApiRequest( array(
			'action' => 'block',
			'user' => 'UTBlockee',
			'reason' => 'Some reason',
			'token' => $pageinfo['blocktoken'] ), $data );

		$block = Block::newFromTarget('UTBlockee');
		
		$this->assertTrue( !is_null( $block ), 'Block is valid' );

		$this->assertEquals( 'UTBlockee', (string)$block->getTarget() );
		$this->assertEquals( 'Some reason', $block->mReason );
		$this->assertEquals( 'infinity', $block->mExpiry );
		
	}

}
