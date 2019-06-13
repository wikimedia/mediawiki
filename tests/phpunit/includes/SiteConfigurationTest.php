<?php

class SiteConfigurationTest extends MediaWikiTestCase {

	/**
	 * @var SiteConfiguration
	 */
	protected $mConf;

	protected function setUp() {
		parent::setUp();

		$this->mConf = new SiteConfiguration;

		$this->mConf->suffixes = [ 'wikipedia' => 'wiki' ];
		$this->mConf->wikis = [ 'enwiki', 'dewiki', 'frwiki' ];
		$this->mConf->settings = [
			'SimpleKey' => [
				'wiki' => 'wiki',
				'tag' => 'tag',
				'enwiki' => 'enwiki',
				'dewiki' => 'dewiki',
				'frwiki' => 'frwiki',
			],

			'Fallback' => [
				'default' => 'default',
				'wiki' => 'wiki',
				'tag' => 'tag',
				'frwiki' => 'frwiki',
				'null_wiki' => null,
			],

			'WithParams' => [
				'default' => '$lang $site $wiki',
			],

			'+SomeGlobal' => [
				'wiki' => [
					'wiki' => 'wiki',
				],
				'tag' => [
					'tag' => 'tag',
				],
				'enwiki' => [
					'enwiki' => 'enwiki',
				],
				'dewiki' => [
					'dewiki' => 'dewiki',
				],
				'frwiki' => [
					'frwiki' => 'frwiki',
				],
			],

			'MergeIt' => [
				'+wiki' => [
					'wiki' => 'wiki',
				],
				'+tag' => [
					'tag' => 'tag',
				],
				'default' => [
					'default' => 'default',
				],
				'+enwiki' => [
					'enwiki' => 'enwiki',
				],
				'+dewiki' => [
					'dewiki' => 'dewiki',
				],
				'+frwiki' => [
					'frwiki' => 'frwiki',
				],
			],
		];

		$GLOBALS['SomeGlobal'] = [ 'SomeGlobal' => 'SomeGlobal' ];
	}

	/**
	 * This function is used as a callback within the tests below
	 */
	public static function getSiteParamsCallback( $conf, $wiki ) {
		$site = null;
		$lang = null;
		foreach ( $conf->suffixes as $suffix ) {
			if ( substr( $wiki, -strlen( $suffix ) ) == $suffix ) {
				$site = $suffix;
				$lang = substr( $wiki, 0, -strlen( $suffix ) );
				break;
			}
		}

		return [
			'suffix' => $site,
			'lang' => $lang,
			'params' => [
				'lang' => $lang,
				'site' => $site,
				'wiki' => $wiki,
			],
			'tags' => [ 'tag' ],
		];
	}

	/**
	 * @covers SiteConfiguration::siteFromDB
	 */
	public function testSiteFromDb() {
		$this->assertEquals(
			[ 'wikipedia', 'en' ],
			$this->mConf->siteFromDB( 'enwiki' ),
			'siteFromDB()'
		);
		$this->assertEquals(
			[ 'wikipedia', '' ],
			$this->mConf->siteFromDB( 'wiki' ),
			'siteFromDB() on a suffix'
		);
		$this->assertEquals(
			[ null, null ],
			$this->mConf->siteFromDB( 'wikien' ),
			'siteFromDB() on a non-existing wiki'
		);

		$this->mConf->suffixes = [ 'wiki', '' ];
		$this->assertEquals(
			[ '', 'wikien' ],
			$this->mConf->siteFromDB( 'wikien' ),
			'siteFromDB() on a non-existing wiki (2)'
		);
	}

	/**
	 * @covers SiteConfiguration::getLocalDatabases
	 */
	public function testGetLocalDatabases() {
		$this->assertEquals(
			[ 'enwiki', 'dewiki', 'frwiki' ],
			$this->mConf->getLocalDatabases(),
			'getLocalDatabases()'
		);
	}

	/**
	 * @covers SiteConfiguration::get
	 */
	public function testGetConfVariables() {
		// Simple
		$this->assertEquals(
			'enwiki',
			$this->mConf->get( 'SimpleKey', 'enwiki', 'wiki' ),
			'get(): simple setting on an existing wiki'
		);
		$this->assertEquals(
			'dewiki',
			$this->mConf->get( 'SimpleKey', 'dewiki', 'wiki' ),
			'get(): simple setting on an existing wiki (2)'
		);
		$this->assertEquals(
			'frwiki',
			$this->mConf->get( 'SimpleKey', 'frwiki', 'wiki' ),
			'get(): simple setting on an existing wiki (3)'
		);
		$this->assertEquals(
			'wiki',
			$this->mConf->get( 'SimpleKey', 'wiki', 'wiki' ),
			'get(): simple setting on an suffix'
		);
		$this->assertEquals(
			'wiki',
			$this->mConf->get( 'SimpleKey', 'eswiki', 'wiki' ),
			'get(): simple setting on an non-existing wiki'
		);

		// Fallback
		$this->assertEquals(
			'wiki',
			$this->mConf->get( 'Fallback', 'enwiki', 'wiki' ),
			'get(): fallback setting on an existing wiki'
		);
		$this->assertEquals(
			'tag',
			$this->mConf->get( 'Fallback', 'dewiki', 'wiki', [], [ 'tag' ] ),
			'get(): fallback setting on an existing wiki (with wiki tag)'
		);
		$this->assertEquals(
			'frwiki',
			$this->mConf->get( 'Fallback', 'frwiki', 'wiki', [], [ 'tag' ] ),
			'get(): no fallback if wiki has its own setting (matching tag)'
		);
		$this->assertSame(
			// Potential regression test for T192855
			null,
			$this->mConf->get( 'Fallback', 'null_wiki', 'wiki', [], [ 'tag' ] ),
			'get(): no fallback if wiki has its own setting (matching tag and uses null)'
		);
		$this->assertEquals(
			'wiki',
			$this->mConf->get( 'Fallback', 'wiki', 'wiki' ),
			'get(): fallback setting on an suffix'
		);
		$this->assertEquals(
			'wiki',
			$this->mConf->get( 'Fallback', 'wiki', 'wiki', [], [ 'tag' ] ),
			'get(): fallback setting on an suffix (with wiki tag)'
		);
		$this->assertEquals(
			'wiki',
			$this->mConf->get( 'Fallback', 'eswiki', 'wiki' ),
			'get(): fallback setting on an non-existing wiki'
		);
		$this->assertEquals(
			'tag',
			$this->mConf->get( 'Fallback', 'eswiki', 'wiki', [], [ 'tag' ] ),
			'get(): fallback setting on an non-existing wiki (with wiki tag)'
		);

		// Merging
		$common = [ 'wiki' => 'wiki', 'default' => 'default' ];
		$commonTag = [ 'tag' => 'tag', 'wiki' => 'wiki', 'default' => 'default' ];
		$this->assertEquals(
			[ 'enwiki' => 'enwiki' ] + $common,
			$this->mConf->get( 'MergeIt', 'enwiki', 'wiki' ),
			'get(): merging setting on an existing wiki'
		);
		$this->assertEquals(
			[ 'enwiki' => 'enwiki' ] + $commonTag,
			$this->mConf->get( 'MergeIt', 'enwiki', 'wiki', [], [ 'tag' ] ),
			'get(): merging setting on an existing wiki (with tag)'
		);
		$this->assertEquals(
			[ 'dewiki' => 'dewiki' ] + $common,
			$this->mConf->get( 'MergeIt', 'dewiki', 'wiki' ),
			'get(): merging setting on an existing wiki (2)'
		);
		$this->assertEquals(
			[ 'dewiki' => 'dewiki' ] + $commonTag,
			$this->mConf->get( 'MergeIt', 'dewiki', 'wiki', [], [ 'tag' ] ),
			'get(): merging setting on an existing wiki (2) (with tag)'
		);
		$this->assertEquals(
			[ 'frwiki' => 'frwiki' ] + $common,
			$this->mConf->get( 'MergeIt', 'frwiki', 'wiki' ),
			'get(): merging setting on an existing wiki (3)'
		);
		$this->assertEquals(
			[ 'frwiki' => 'frwiki' ] + $commonTag,
			$this->mConf->get( 'MergeIt', 'frwiki', 'wiki', [], [ 'tag' ] ),
			'get(): merging setting on an existing wiki (3) (with tag)'
		);
		$this->assertEquals(
			[ 'wiki' => 'wiki' ] + $common,
			$this->mConf->get( 'MergeIt', 'wiki', 'wiki' ),
			'get(): merging setting on an suffix'
		);
		$this->assertEquals(
			[ 'wiki' => 'wiki' ] + $commonTag,
			$this->mConf->get( 'MergeIt', 'wiki', 'wiki', [], [ 'tag' ] ),
			'get(): merging setting on an suffix (with tag)'
		);
		$this->assertEquals(
			$common,
			$this->mConf->get( 'MergeIt', 'eswiki', 'wiki' ),
			'get(): merging setting on an non-existing wiki'
		);
		$this->assertEquals(
			$commonTag,
			$this->mConf->get( 'MergeIt', 'eswiki', 'wiki', [], [ 'tag' ] ),
			'get(): merging setting on an non-existing wiki (with tag)'
		);
	}

	/**
	 * @covers SiteConfiguration::siteFromDB
	 */
	public function testSiteFromDbWithCallback() {
		$this->mConf->siteParamsCallback = 'SiteConfigurationTest::getSiteParamsCallback';

		$this->assertEquals(
			[ 'wiki', 'en' ],
			$this->mConf->siteFromDB( 'enwiki' ),
			'siteFromDB() with callback'
		);
		$this->assertEquals(
			[ 'wiki', '' ],
			$this->mConf->siteFromDB( 'wiki' ),
			'siteFromDB() with callback on a suffix'
		);
		$this->assertEquals(
			[ null, null ],
			$this->mConf->siteFromDB( 'wikien' ),
			'siteFromDB() with callback on a non-existing wiki'
		);
	}

	/**
	 * @covers SiteConfiguration::get
	 */
	public function testParameterReplacement() {
		$this->mConf->siteParamsCallback = 'SiteConfigurationTest::getSiteParamsCallback';

		$this->assertEquals(
			'en wiki enwiki',
			$this->mConf->get( 'WithParams', 'enwiki', 'wiki' ),
			'get(): parameter replacement on an existing wiki'
		);
		$this->assertEquals(
			'de wiki dewiki',
			$this->mConf->get( 'WithParams', 'dewiki', 'wiki' ),
			'get(): parameter replacement on an existing wiki (2)'
		);
		$this->assertEquals(
			'fr wiki frwiki',
			$this->mConf->get( 'WithParams', 'frwiki', 'wiki' ),
			'get(): parameter replacement on an existing wiki (3)'
		);
		$this->assertEquals(
			' wiki wiki',
			$this->mConf->get( 'WithParams', 'wiki', 'wiki' ),
			'get(): parameter replacement on an suffix'
		);
		$this->assertEquals(
			'es wiki eswiki',
			$this->mConf->get( 'WithParams', 'eswiki', 'wiki' ),
			'get(): parameter replacement on an non-existing wiki'
		);
	}

	/**
	 * @covers SiteConfiguration::getAll
	 */
	public function testGetAllGlobals() {
		$this->mConf->siteParamsCallback = 'SiteConfigurationTest::getSiteParamsCallback';

		$getall = [
			'SimpleKey' => 'enwiki',
			'Fallback' => 'tag',
			'WithParams' => 'en wiki enwiki',
			'SomeGlobal' => [ 'enwiki' => 'enwiki' ] + $GLOBALS['SomeGlobal'],
			'MergeIt' => [
				'enwiki' => 'enwiki',
				'tag' => 'tag',
				'wiki' => 'wiki',
				'default' => 'default'
			],
		];
		$this->assertEquals( $getall, $this->mConf->getAll( 'enwiki' ), 'getAll()' );

		$this->mConf->extractAllGlobals( 'enwiki', 'wiki' );

		$this->assertEquals(
			$getall['SimpleKey'],
			$GLOBALS['SimpleKey'],
			'extractAllGlobals(): simple setting'
		);
		$this->assertEquals(
			$getall['Fallback'],
			$GLOBALS['Fallback'],
			'extractAllGlobals(): fallback setting'
		);
		$this->assertEquals(
			$getall['WithParams'],
			$GLOBALS['WithParams'],
			'extractAllGlobals(): parameter replacement'
		);
		$this->assertEquals(
			$getall['SomeGlobal'],
			$GLOBALS['SomeGlobal'],
			'extractAllGlobals(): merging with global'
		);
		$this->assertEquals(
			$getall['MergeIt'],
			$GLOBALS['MergeIt'],
			'extractAllGlobals(): merging setting'
		);
	}
}
