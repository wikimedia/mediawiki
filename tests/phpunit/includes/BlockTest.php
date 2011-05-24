<?php

/**
 * @group Database
 */
class BlockTest extends MediaWikiLangTestCase {
	
	private $block, $madeAt;
	
	function setUp() {
		global $wgContLang;
		parent::setUp();
		$wgContLang = Language::factory( 'en' );
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

	/**
	 * This is the method previously used to load block info in CheckUser etc
	 * passing an empty value (empty string, null, etc) as the ip parameter bypasses IP lookup checks.
	 *
	 * This stopped working with r84475 and friends: regression being fixed for bug 29116.
	 *
	 * @dataProvider dataBug29116
	 */
	function testBug29116LoadWithEmptyIp( $vagueTarget ) {
		$block = new Block();
		$block->load( $vagueTarget, 'UTBlockee' );
		$this->assertTrue( $this->block->equals( Block::newFromTarget('UTBlockee', $vagueTarget) ), "Block->load() returns the same block as the one that was made when given empty ip param " . var_export( $vagueTarget, true ) );
	}

	/**
	 * CheckUser since being changed to use Block::newFromTarget started failing
	 * because the new function didn't accept empty strings like Block::load()
	 * had. Regression bug 29116.
	 *
	 * @dataProvider dataBug29116
	 */
	function testBug29116NewFromTargetWithEmptyIp( $vagueTarget ) {
		$block = Block::newFromTarget('UTBlockee', $vagueTarget);
		$this->assertTrue( $this->block->equals( $block ), "newFromTarget() returns the same block as the one that was made when given empty vagueTarget param " . var_export( $vagueTarget, true ) );
	}

	function dataBug29116() {
		return array(
			array( null ),
			array( '' ),
			array( false )
		);
	}
}

