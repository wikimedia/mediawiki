<?php

use MediaWiki\Specials\SpecialRandomPage;

/**
 * @covers \MediaWiki\Specials\SpecialRandomPage
 */
class SpecialRandomPageTest extends MediaWikiIntegrationTestCase {

	/** @var SpecialRandomPage */
	private $page;

	public function setUp(): void {
		$services = $this->getServiceContainer();
		$this->page = new SpecialRandomPage(
			$services->getConnectionProvider(),
			$services->getNamespaceInfo()
		);
		parent::setUp();
	}

	/**
	 * @dataProvider providerParsePar
	 * @param string $par
	 * @param array $expectedNS Array of integer namespace ids
	 */
	public function testParsePar( $par, $expectedNS ) {
		$reflectionClass = new ReflectionClass( $this->page );
		$reflectionMethod = $reflectionClass->getMethod( 'parsePar' );
		$reflectionMethod->setAccessible( true );

		$reflectionMethod->invoke( $this->page, $par );
		$this->assertEquals( $expectedNS, $this->page->getNamespaces() );
	}

	public static function providerParsePar() {
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
			[ '|invalid|,|namespaces|', $wgContentNamespaces ],
		];
	}
}
