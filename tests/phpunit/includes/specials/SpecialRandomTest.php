<?php
class SpecialRandomTest extends MediaWikiTestCase {

	/** @var RandomPage */
	private $page;

	function setUp() {
		$this->page = new RandomPage;
		parent::setUp();
	}

	/**
	 * @dataProvider providerParsePar
	 * @param String $par
	 * @param Array $expectedNS Array of integer namespace ids
	 */
	function testParsePar( $par, $expectedNS ) {
		$reflectionClass = new ReflectionClass( $this->page );
		$reflectionMethod = $reflectionClass->getMethod( 'parsePar' );
		$reflectionMethod->setAccessible( true );

		$reflectionMethod->invoke( $this->page, $par );
		$this->assertEquals( $this->page->getNamespaces(), $expectedNS );
	}

	function providerParsePar() {
		global $wgContentNamespaces;

		return [
			[ null, $wgContentNamespaces ],
			[ '', array( NS_MAIN ) ],
			[ 'main', array( NS_MAIN ) ],
			[ 'article', array( NS_MAIN ) ],
			[ '0', array( NS_MAIN ) ],
			[ 'File', array( NS_FILE ) ],
			[ 'file_talk', array( NS_FILE_TALK ) ],
			[ 'hElP', array( NS_HELP ) ],
			[ 'Project,', array( NS_PROJECT, NS_MAIN ) ],
			[ 'Project,Help,User_talk', array( NS_PROJECT, NS_HELP, NS_USER_TALK ) ],
		];
	}
}
