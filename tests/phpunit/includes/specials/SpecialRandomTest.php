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

		return array(
			array( null, $wgContentNamespaces ),
			array( '', array( NS_MAIN ) ),
			array( 'main', array( NS_MAIN ) ),
			array( 'article', array( NS_MAIN ) ),
			array( '0', array( NS_MAIN ) ),
			array( 'File', array( NS_FILE ) ),
			array( 'file_talk', array( NS_FILE_TALK ) ),
			array( 'hElP', array( NS_HELP ) ),
			array( 'Project,', array( NS_PROJECT, NS_MAIN ) ),
			array( 'Project,Help,User_talk', array( NS_PROJECT, NS_HELP, NS_USER_TALK ) ),
		);
	}
}
