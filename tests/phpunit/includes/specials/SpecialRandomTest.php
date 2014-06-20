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
			[ '', [ NS_MAIN ] ],
			[ 'main', [ NS_MAIN ] ],
			[ 'article', [ NS_MAIN ] ],
			[ '0', [ NS_MAIN ] ],
			[ 'File', [ NS_FILE ] ],
			[ 'file_talk', [ NS_FILE_TALK ] ],
			[ 'hElP', [ NS_HELP ] ],
			[ 'Project,', [ NS_PROJECT, NS_MAIN ] ],
			[ 'Project,Help,User_talk', [ NS_PROJECT, NS_HELP, NS_USER_TALK ] ],
		];
	}
}
