<?php

class BlockTest extends MediaWikiTestCase {
	
	private $block;
	
	function setUp() {
		global $wgContLang;
		$wgContLang = Language::factory( 'en' );
	}

	function tearDown() {
	}
	
	function addDBData() {
		$user = User::newFromName( 'UTBlockee' );
		$user->addToDatabase();
		$user->setPassword( 'UTBlockeePassword' );
			
		$user->saveSettings();
		
		$this->block = new Block( 'UTBlockee', 1, 0,
			'Parce que', wfTimestampNow()
		);

		$this->block->insert();
	}
	
	function testInitializerFunctionsReturnCorrectBlock() {
		
		$this->assertTrue( $this->block->equals( Block::newFromDB('UTBlockee') ), "newFromDB() returns the same block as the one that was made");
		
		$this->assertTrue( $this->block->equals( Block::newFromID( 1 ) ), "newFromID() returns the same block as the one that was made");
		
	}

}

