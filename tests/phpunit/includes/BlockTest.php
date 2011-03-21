<?php

/**
 * @group Database
 */
class BlockTest extends MediaWikiTestCase {
	
	private $block, $madeAt;
	
	function setUp() {
		global $wgContLang;
		$wgContLang = Language::factory( 'en' );
	}

	function tearDown() {
	}
	
	function addDBData() {
		
		$user = User::newFromName( 'UTBlockee' );
		if( $user->getID() == 0 ) {
			$user->addToDatabase();
			$user->setPassword( 'UTBlockeePassword' );
			
			$user->saveSettings();
		}
		
		$this->block = new Block( 'UTBlockee', 1, 0,
			'Parce que'
		);
		$this->madeAt = wfTimestamp( TS_MW );

		$this->block->insert();
	}
	
	function testInitializerFunctionsReturnCorrectBlock() {
		
		$this->assertTrue( $this->block->equals( Block::newFromTarget('UTBlockee') ), "newFromTarget() returns the same block as the one that was made");
		
		$this->assertTrue( $this->block->equals( Block::newFromID( 1 ) ), "newFromID() returns the same block as the one that was made");
		
	}
	
	/**
	 * per bug 26425
	 */
	function testBug26425BlockTimestampDefaultsToTime() {
		
		$this->assertEquals( $this->madeAt, $this->block->mTimestamp, "If no timestamp is specified, the block is recorded as time()");
		
	}

}

