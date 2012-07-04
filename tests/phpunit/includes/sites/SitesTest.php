<?php

/**
 * Tests for the Sites class.
 *
 * @file
 * @since 1.20
 *
 * @ingroup Sites
 * @ingroup Test
 *
 * @group Sites
 *
 * @group Database
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SitesTest extends \MediaWikiTestCase {

	/**
	 * Returns a set of sites that can be used for testing purposes.
	 * These are the same tests as those getting inserted into the db
	 * when calling @see SitesTest::insertSitesForTests.
	 *
	 * @since 1.20
	 *
	 * @return array of Site
	 */
	public static function getSitesForTests() {
		$sites = array();

		$sites[] = Sites::newSite( array(
			'global_key' => 'enwiki',
			'type' => Site::TYPE_MEDIAWIKI,
			'group' => Site::GROUP_WIKIPEDIA,
			'url' => 'https://en.wikipedia.org',
			'page_path' => '/wiki/$1',
			'file_path' => '/w/$1',
			'local_key' => 'en',
			'language' => 'en',
		) );

		$sites[] = Sites::newSite( array(
			'global_key' => 'dewiki',
			'type' => Site::TYPE_MEDIAWIKI,
			'group' => Site::GROUP_WIKIPEDIA,
			'url' => 'https://de.wikipedia.org',
			'page_path' => '/wiki/$1',
			'file_path' => '/w/$1',
			'local_key' => 'de',
			'language' => 'de',
		) );

		$sites[] = Sites::newSite( array(
			'global_key' => 'nlwiki',
			'type' => Site::TYPE_MEDIAWIKI,
			'group' => Site::GROUP_WIKIPEDIA,
			'url' => 'https://nl.wikipedia.org',
			'page_path' => '/wiki/$1',
			'file_path' => '/w/$1',
			'local_key' => 'nl',
			'link_inline' => true,
			'link_navigation' => true,
			'forward' => true,
			'language' => 'nl',
		) );

		$sites[] = Sites::newSite( array(
			'global_key' => 'svwiki',
			'url' => 'https://sv.wikipedia.org',
			'page_path' => '/wiki/$1',
			'file_path' => '/w/$1',
			'local_key' => 'sv',
			'language' => 'sv',
		) );

		$sites[] = Sites::newSite( array(
			'global_key' => 'srwiki',
			'type' => 0,
			'group' => 0,
			'url' => 'https://sr.wikipedia.org',
			'page_path' => '/wiki/$1',
			'file_path' => '/w/$1',
			'local_key' => 'sr',
			'link_inline' => true,
			'link_navigation' => true,
			'forward' => true,
		) );

		$sites[] = Sites::newSite( array(
			'global_key' => 'nowiki',
			'type' => 0,
			'group' => 0,
			'url' => 'https://no.wikipedia.org',
			'page_path' => '/wiki/$1',
			'file_path' => '/w/$1',
			'local_key' => 'no',
			'link_inline' => true,
			'link_navigation' => true,
			'forward' => true,
		) );

		$sites[] = Sites::newSite( array(
			'global_key' => 'nnwiki',
			'url' => 'https://nn.wikipedia.org',
			'page_path' => '/wiki/$1',
			'file_path' => '/w/$1',
			'local_key' => 'nn',
			'language' => 'nn',
		) );

		$sites[] = Sites::newSite( array(
			'global_key' => 'enwiktionary',
			'url' => 'https://en.wiktionary.org',
			'page_path' => '/wiki/$1',
			'file_path' => '/w/$1',
			'local_key' => 'enwiktionary',
			'language' => 'en',
		) );

		return $sites;
	}

	/**
	 * Inserts sites into the database for the unit tests that need them.
	 *
	 * @since 1.20
	 */
	public static function insertSitesForTests() {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->begin();

		if ( $GLOBALS['wgDBtype'] == 'sqlite' ) {
			$dbw->query( 'DELETE FROM ' . $dbw->tableName( 'sites' ) );
		}
		else {
			$dbw->query( 'TRUNCATE TABLE ' . $dbw->tableName( 'sites' ) );
		}

		foreach ( self::getSitesForTests() as $site ) {
			$site->save();
		}

		$dbw->commit();
	}

	public function setUp() {
		parent::setUp();
		self::insertSitesForTests();
	}

	public function testSingleton() {
		$this->assertInstanceOf( 'Sites', Sites::singleton() );
		$this->assertTrue( Sites::singleton() === Sites::singleton() );
	}

	public function testGetGlobalIdentifiers() {
		$this->assertTrue( is_array( Sites::singleton()->getGlobalIdentifiers() ) );
		$ids = Sites::singleton()->getGlobalIdentifiers();

		if ( $ids !== array() ) {
			$this->assertTrue( in_array( reset( $ids ), Sites::singleton()->getGlobalIdentifiers() ) );
		}

		$this->assertFalse( in_array( '4241413541354135435435413', Sites::singleton()->getGlobalIdentifiers() ) );
	}

	public function testGetGroup() {
		$allSites = Sites::singleton()->getAllSites();
		$count = 0;

		foreach ( $allSites->getGroupNames() as $groupName ) {
			$group = Sites::singleton()->getGroup( $groupName );
			$this->assertInstanceOf( '\SiteList', $group );
			$count += $group->count();

			if ( !$group->isEmpty() ) {
				$sites = iterator_to_array( $group );

				foreach ( array_slice( $sites, 0, 5 ) as $site ) {
					$this->assertInstanceOf( '\Site', $site );
					$this->assertEquals( $groupName, $site->getGroup() );
				}
			}
		}

		$this->assertEquals( $allSites->count(), $count );
	}

	public function testGetSite() {
		$count = 0;
		$sites = Sites::singleton()->getAllSites();

		foreach ( $sites as $site ) {
			$this->assertInstanceOf( '\Site', $site );

			$this->assertEquals(
				$site,
				Sites::singleton()->getSiteByGlobalId( $site->getGlobalId() )
			);

			if ( ++$count > 100 ) {
				break;
			}
		}
	}

	public function loadConditionsProvider() {
		return array(
			array( array( 'global_key' => 'enwiki' ) ),
			array( array( 'local_key' => 'en' ) ),
			array( array( 'global_key' => 'zsdfszdrtgsdftyg' ) ),
			array( array( 'local_key' => 'sdrfsdatddertd' ) ),
			array( array( 'global_key' => 'enwiki', 'local_key' => 'en' ) ),
			array( array() ),
		);
	}

	/**
	 * @dataProvider loadConditionsProvider
	 * @param array $conditions
	 */
	public function testLoadSites( array $conditions ) {
		Sites::singleton()->loadSites( $conditions );

		$this->assertTrue( true, 'Loading sites with these conditions: ' . json_encode( $conditions ) );
	}

	public function testGetSiteByLocalId() {
		$site = Sites::singleton()->getSiteByLocalId( "en" );
		$this->assertFalse( $site === false, "site not found" );
		$this->assertEquals( "en", $site->getConfig()->getLocalId() );
		$this->assertFalse( Sites::singleton()->getSiteByLocalId( 'dxzfzxdegxdrfyxsdty' ) );
	}

	public function testGetSiteByGlobalId() {
		$site = Sites::singleton()->getSiteByGlobalId( "enwiki" );
		$this->assertFalse( $site === false, "site not found" );
		$this->assertEquals( "enwiki", $site->getGlobalId() );
		$this->assertFalse( Sites::singleton()->getSiteByGlobalId( 'dxzfzxdegxdrfyxsdty' ) );
	}

	public function testGetLoadedSites() {
		$this->assertInstanceOf( '\SiteList', Sites::singleton()->getLoadedSites() );

		$this->assertEquals(
			Sites::singleton()->getAllSites(),
			Sites::singleton()->getLoadedSites()
		);
	}

	public function testNewSite() {
		$this->assertInstanceOf( 'Site', Sites::newSite() );
		$this->assertInstanceOf( 'Site', Sites::newSite( array() ) );
		$this->assertInstanceOf( 'Site', Sites::newSite( array( 'type' => Site::TYPE_UNKNOWN ) ) );
	}

}