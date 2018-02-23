<?php

/**
 * @covers MediaWiki\Interwiki\InterwikiLookupAdapter
 *
 * @group MediaWiki
 * @group Interwiki
 */

use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Interwiki\SiteLookupAdapter;

class SiteLookupAdapterTest extends MediaWikiTestCase {

	/**
	 * @var SiteLookupAdapter
	 */
	private $siteLookupAdapter;

	protected function setUp() {
		parent::setUp();

		$this->siteLookupAdapter = new SiteLookupAdapter(
			$this->getInterwikiLookup( $this->getInterwikis() )
		);
	}

	public function testFetch() {
		$site = $this->siteLookupAdapter->getSite( '' );
		$this->assertNull( $site );

		$site = $this->siteLookupAdapter->getSite( 'xyz' );
		$this->assertNull( $site );

		$site = $this->siteLookupAdapter->getSite( 'enwt' );
		$this->assertInstanceOf( MediaWikiSite::class, $site );
		$this->assertSame( 'https://en.wiktionary.org/wiki/$1', $site->getPageUrl(), 'getPageUrl' );
		$this->assertSame(
			'https://en.wiktionary.org/w/api.php',
			$site->getFileUrl( 'api.php' ),
			'getPath'
		);
		$this->assertSame( 'enwiktionary', $site->getGlobalId(), 'GlobalId' );
		$this->assertSame( 'local', $site->getSource(), 'getSource' );
		$this->assertSame( [ 'enwt' ], $site->getInterwikiIds(), 'getInterwikiIds' );

		$site = $this->siteLookupAdapter->getSite( 'fa' );
		$this->assertInstanceOf( MediaWikiSite::class, $site );
		$this->assertSame( 'https://fa.wikipedia.org/wiki/$1', $site->getPageUrl(), 'getPageUrl' );
		$this->assertSame(
			'https://fa.wikipedia.org/w/api.php',
			$site->getFileUrl( 'api.php' ),
			'getPath'
		);
		$this->assertSame( 'fawiki', $site->getGlobalId(), 'GlobalId' );
		$this->assertSame( 'local', $site->getSource(), 'getSource' );
		$this->assertSame( [ 'fa' ], $site->getInterwikiIds(), 'getInterwikiIds' );
	}

	public function testGetSites() {
		$fawiki = new MediaWikiSite();
		$fawiki->setGlobalId( 'fawiki' );
		$fawiki->setSource( 'local' );
		$fawiki->setPagePath( 'https://fa.wikipedia.org/wiki/$1' );
		$fawiki->setFilePath( 'https://fa.wikipedia.org/w/$1' );
		$fawiki->addInterwikiId( 'fa' );

		$enwiktionary = new MediaWikiSite();
		$enwiktionary->setGlobalId( 'enwiktionary' );
		$enwiktionary->setSource( 'local' );
		$enwiktionary->setPagePath( 'https://en.wiktionary.org/wiki/$1' );
		$enwiktionary->setFilePath( 'https://en.wiktionary.org/w/$1' );
		$enwiktionary->addInterwikiId( 'enwt' );

		$this->assertEquals(
			new SiteList( [ $fawiki, $enwiktionary ] ),
			$this->siteLookupAdapter->getSites(),
			'getSites()'
		);
	}

	private function getInterwikiLookup( array $interwikis ) {
		$interwikiLookup = $this->getMockBuilder( InterwikiLookup::class )
			->disableOriginalConstructor()
			->getMock();

		$interwikiLookup->expects( $this->any() )
			->method( 'getAllPrefixes' )
			->will( $this->returnValue( array_keys( $interwikis ) ) );

		$interwikiLookup->expects( $this->any() )
			->method( 'fetch' )
			->will( $this->returnCallback( function ( $interwiki ) use ( $interwikis ) {
				return $interwikis[$interwiki];
			} ) );

		return $interwikiLookup;
	}

	private function getInterwikis() {
		$interwikis = [];

		$interwiki = new Interwiki(
			'fa',
			'https://fa.wikipedia.org/wiki/$1',
			'https://fa.wikipedia.org/w/api.php',
			'fawiki',
			true
		);
		$interwikis['fa'] = $interwiki;

		$interwiki = new Interwiki(
			'enwt',
			'https://en.wiktionary.org/wiki/$1',
			'https://en.wiktionary.org/w/api.php',
			'enwiktionary',
			true
		);
		$interwikis['enwt'] = $interwiki;

		return $interwikis;
	}

}
