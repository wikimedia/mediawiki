<?php

use MediaWiki\Interwiki\InterwikiLookupAdapter;

/**
 * @covers MediaWiki\Interwiki\InterwikiLookupAdapter
 *
 * @group MediaWiki
 * @group Interwiki
 */
class InterwikiLookupAdapterTest extends \MediaWikiUnitTestCase {

	/**
	 * @var InterwikiLookupAdapter
	 */
	private $interwikiLookup;

	protected function setUp() : void {
		parent::setUp();

		$this->interwikiLookup = new InterwikiLookupAdapter(
			$this->getSiteLookup( $this->getSites() )
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
			'unknown prefix is not valid'
		);
	}

	public function testFetch() {
		$interwiki = $this->interwikiLookup->fetch( '' );
		$this->assertNull( $interwiki );

		$interwiki = $this->interwikiLookup->fetch( 'xyz' );
		$this->assertFalse( $interwiki );

		$interwiki = $this->interwikiLookup->fetch( 'foo' );
		$this->assertInstanceOf( Interwiki::class, $interwiki );
		$this->assertSame( 'foobar', $interwiki->getWikiID() );

		$interwiki = $this->interwikiLookup->fetch( 'enwt' );
		$this->assertInstanceOf( Interwiki::class, $interwiki );

		$this->assertSame( 'https://en.wiktionary.org/wiki/$1', $interwiki->getURL(), 'getURL' );
		$this->assertSame( 'https://en.wiktionary.org/w/api.php', $interwiki->getAPI(), 'getAPI' );
		$this->assertSame( 'enwiktionary', $interwiki->getWikiID(), 'getWikiID' );
		$this->assertTrue( $interwiki->isLocal(), 'isLocal' );
	}

	public function testGetAllPrefixes() {
		$foo = [
			'iw_prefix' => 'foo',
			'iw_url' => '',
			'iw_api' => '',
			'iw_wikiid' => 'foobar',
			'iw_local' => false,
			'iw_trans' => false,
		];
		$enwt = [
			'iw_prefix' => 'enwt',
			'iw_url' => 'https://en.wiktionary.org/wiki/$1',
			'iw_api' => 'https://en.wiktionary.org/w/api.php',
			'iw_wikiid' => 'enwiktionary',
			'iw_local' => true,
			'iw_trans' => false,
		];

		$this->assertEquals(
			[ $foo, $enwt ],
			$this->interwikiLookup->getAllPrefixes(),
			'getAllPrefixes()'
		);

		$this->assertEquals(
			[ $foo ],
			$this->interwikiLookup->getAllPrefixes( false ),
			'get external prefixes'
		);

		$this->assertEquals(
			[ $enwt ],
			$this->interwikiLookup->getAllPrefixes( true ),
			'get local prefixes'
		);
	}

	private function getSiteLookup( SiteList $sites ) {
		$siteLookup = $this->getMockBuilder( SiteLookup::class )
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
		$site->setSource( 'external' );
		$sites[] = $site;

		$site = new MediaWikiSite();
		$site->setGlobalId( 'enwiktionary' );
		$site->setGroup( 'wiktionary' );
		$site->setLanguageCode( 'en' );
		$site->addNavigationId( 'enwiktionary' );
		$site->addInterwikiId( 'enwt' );
		$site->setSource( 'local' );
		$site->setPath( MediaWikiSite::PATH_PAGE, "https://en.wiktionary.org/wiki/$1" );
		$site->setPath( MediaWikiSite::PATH_FILE, "https://en.wiktionary.org/w/$1" );
		$sites[] = $site;

		return new SiteList( $sites );
	}

}
