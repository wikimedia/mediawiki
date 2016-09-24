<?php

/**
 * @property \MediaWiki\Interwiki\InterwikiLookupAdapter interwikiLookup
 * @property SiteLookup siteLookup
 * @covers MediaWiki\Interwiki\InterwikiLookupAdapter
 *
 * @group MediaWiki
 * @group Interwiki
 */
class InterwikiLookupAdapterTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$this->siteLookup = $this->getSiteLookup( $this->getSites() );
		$this->interwikiLookup = new \MediaWiki\Interwiki\InterwikiLookupAdapter(
			$this->siteLookup
		);
	}

	public function testIsValidInterwiki() {
		$this->assertTrue(
			$this->interwikiLookup->isValidInterwiki( 'enwt' ),
			'enwt known prefix is valid'
		);
		$this->assertTrue(
			$this->interwikiLookup->isValidInterwiki( 'foo' ),
			'foo site known prefix is valid'
		);
		$this->assertFalse(
			$this->interwikiLookup->isValidInterwiki( 'xyz' ),
			'unknown prefix is valid'
		);
	}

	public function testFetch() {

		$interwiki = $this->interwikiLookup->fetch( 'foo' );
		$this->assertInstanceOf( 'Interwiki', $interwiki );

		$interwiki = $this->interwikiLookup->fetch( 'enwt' );
		$this->assertInstanceOf( 'Interwiki', $interwiki );

		$this->assertSame( 'https://en.wiktionary.org/wiki/$1', $interwiki->getURL(), 'getURL' );
		$this->assertSame( 'https://en.wiktionary.org/w/api.php', $interwiki->getAPI(), 'getAPI' );
		$this->assertSame( 'enwiktionary', $interwiki->getWikiID(), 'getWikiID' );
		$this->assertTrue( $interwiki->isLocal(), 'isLocal' );
	}

	public function testGetAllPrefixes() {
		$this->assertEquals(
			[ 'foo', 'enwt' ],
			$this->interwikiLookup->getAllPrefixes(),
			'getAllPrefixes()'
		);
	}

	private function getSiteLookup( SiteList $sites ) {
		$siteLookup = $this->getMockBuilder( 'SiteLookup' )
			->disableOriginalConstructor()
			->getMock();

		$siteLookup->expects( $this->any() )
			->method( 'getSites' )
			->will( $this->returnValue( $sites ) );

		return $siteLookup;
	}

	private function getSites() {
		$sites = [];

		$site = new Site();
		$site->setGlobalId( 'foobar' );
		$site->addInterwikiId( 'foo' );
		$sites[] = $site;

		$site = new MediaWikiSite();
		$site->setGlobalId( 'enwiktionary' );
		$site->setGroup( 'wiktionary' );
		$site->setLanguageCode( 'en' );
		$site->addNavigationId( 'enwiktionary' );
		$site->addInterwikiId( 'enwt' );
		$site->setPath( MediaWikiSite::PATH_PAGE, "https://en.wiktionary.org/wiki/$1" );
		$site->setPath( MediaWikiSite::PATH_FILE, "https://en.wiktionary.org/w/$1" );
		$sites[] = $site;

		return new SiteList( $sites );
	}

}

