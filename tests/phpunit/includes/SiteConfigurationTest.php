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
			'simple' => [
				'wiki' => 'wiki',
				'tag' => 'tag',
				'enwiki' => 'enwiki',
				'dewiki' => 'dewiki',
				'frwiki' => 'frwiki',
			],

			'fallback' => [
				'default' => 'default',
				'wiki' => 'wiki',
				'tag' => 'tag',
			],

			'params' => [
				'default' => '$lang $site $wiki',
			],

			'+global' => [
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

			'merge' => [
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

		$GLOBALS['global'] = [ 'global' => 'global' ];
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
		$this->assertEquals(
			'enwiki',
			$this->mConf->get( 'simple', 'enwiki', 'wiki' ),
			'get(): simple setting on an existing wiki'
		);
		$this->assertEquals(
			'dewiki',
			$this->mConf->get( 'simple', 'dewiki', 'wiki' ),
			'get(): simple setting on an existing wiki (2)'
		);
		$this->assertEquals(
			'frwiki',
			$this->mConf->get( 'simple', 'frwiki', 'wiki' ),
			'get(): simple setting on an existing wiki (3)'
		);
		$this->assertEquals(
			'wiki',
			$this->mConf->get( 'simple', 'wiki', 'wiki' ),
			'get(): simple setting on an suffix'
		);
		$this->assertEquals(
			'wiki',
			$this->mConf->get( 'simple', 'eswiki', 'wiki' ),
			'get(): simple setting on an non-existing wiki'
		);

		$this->assertEquals(
			'wiki',
			$this->mConf->get( 'fallback', 'enwiki', 'wiki' ),
			'get(): fallback setting on an existing wiki'
		);
		$this->assertEquals(
			'tag',
			$this->mConf->get( 'fallback', 'dewiki', 'wiki', [], [ 'tag' ] ),
			'get(): fallback setting on an existing wiki (with wiki tag)'
		);
		$this->assertEquals(
			'wiki',
			$this->mConf->get( 'fallback', 'wiki', 'wiki' ),
			'get(): fallback setting on an suffix'
		);
		$this->assertEquals(
			'wiki',
			$this->mConf->get( 'fallback', 'wiki', 'wiki', [], [ 'tag' ] ),
			'get(): fallback setting on an suffix (with wiki tag)'
		);
		$this->assertEquals(
			'wiki',
			$this->mConf->get( 'fallback', 'eswiki', 'wiki' ),
			'get(): fallback setting on an non-existing wiki'
		);
		$this->assertEquals(
			'tag',
			$this->mConf->get( 'fallback', 'eswiki', 'wiki', [], [ 'tag' ] ),
			'get(): fallback setting on an non-existing wiki (with wiki tag)'
		);

		$common = [ 'wiki' => 'wiki', 'default' => 'default' ];
		$commonTag = [ 'tag' => 'tag', 'wiki' => 'wiki', 'default' => 'default' ];
		$this->assertEquals(
			[ 'enwiki' => 'enwiki' ] + $common,
			$this->mConf->get( 'merge', 'enwiki', 'wiki' ),
			'get(): merging setting on an existing wiki'
		);
		$this->assertEquals(
			[ 'enwiki' => 'enwiki' ] + $commonTag,
			$this->mConf->get( 'merge', 'enwiki', 'wiki', [], [ 'tag' ] ),
			'get(): merging setting on an existing wiki (with tag)'
		);
		$this->assertEquals(
			[ 'dewiki' => 'dewiki' ] + $common,
			$this->mConf->get( 'merge', 'dewiki', 'wiki' ),
			'get(): merging setting on an existing wiki (2)'
		);
		$this->assertEquals(
			[ 'dewiki' => 'dewiki' ] + $commonTag,
			$this->mConf->get( 'merge', 'dewiki', 'wiki', [], [ 'tag' ] ),
			'get(): merging setting on an existing wiki (2) (with tag)'
		);
		$this->assertEquals(
			[ 'frwiki' => 'frwiki' ] + $common,
			$this->mConf->get( 'merge', 'frwiki', 'wiki' ),
			'get(): merging setting on an existing wiki (3)'
		);
		$this->assertEquals(
			[ 'frwiki' => 'frwiki' ] + $commonTag,
			$this->mConf->get( 'merge', 'frwiki', 'wiki', [], [ 'tag' ] ),
			'get(): merging setting on an existing wiki (3) (with tag)'
		);
		$this->assertEquals(
			[ 'wiki' => 'wiki' ] + $common,
			$this->mConf->get( 'merge', 'wiki', 'wiki' ),
			'get(): merging setting on an suffix'
		);
		$this->assertEquals(
			[ 'wiki' => 'wiki' ] + $commonTag,
			$this->mConf->get( 'merge', 'wiki', 'wiki', [], [ 'tag' ] ),
			'get(): merging setting on an suffix (with tag)'
		);
		$this->assertEquals(
			$common,
			$this->mConf->get( 'merge', 'eswiki', 'wiki' ),
			'get(): merging setting on an non-existing wiki'
		);
		$this->assertEquals(
			$commonTag,
			$this->mConf->get( 'merge', 'eswiki', 'wiki', [], [ 'tag' ] ),
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
			$this->mConf->get( 'params', 'enwiki', 'wiki' ),
			'get(): parameter replacement on an existing wiki'
		);
		$this->assertEquals(
			'de wiki dewiki',
			$this->mConf->get( 'params', 'dewiki', 'wiki' ),
			'get(): parameter replacement on an existing wiki (2)'
		);
		$this->assertEquals(
			'fr wiki frwiki',
			$this->mConf->get( 'params', 'frwiki', 'wiki' ),
			'get(): parameter replacement on an existing wiki (3)'
		);
		$this->assertEquals(
			' wiki wiki',
			$this->mConf->get( 'params', 'wiki', 'wiki' ),
			'get(): parameter replacement on an suffix'
		);
		$this->assertEquals(
			'es wiki eswiki',
			$this->mConf->get( 'params', 'eswiki', 'wiki' ),
			'get(): parameter replacement on an non-existing wiki'
		);
	}

	/**
	 * @covers SiteConfiguration::getAll
	 */
	public function testGetAllGlobals() {
		$this->mConf->siteParamsCallback = 'SiteConfigurationTest::getSiteParamsCallback';

		$getall = [
			'simple' => 'enwiki',
			'fallback' => 'tag',
			'params' => 'en wiki enwiki',
			'global' => [ 'enwiki' => 'enwiki' ] + $GLOBALS['global'],
			'merge' => [
				'enwiki' => 'enwiki',
				'tag' => 'tag',
				'wiki' => 'wiki',
				'default' => 'default'
			],
		];
		$this->assertEquals( $getall, $this->mConf->getAll( 'enwiki' ), 'getAll()' );

		$this->mConf->extractAllGlobals( 'enwiki', 'wiki' );

		$this->assertEquals(
			$getall['simple'],
			$GLOBALS['simple'],
			'extractAllGlobals(): simple setting'
		);
		$this->assertEquals(
			$getall['fallback'],
			$GLOBALS['fallback'],
			'extractAllGlobals(): fallback setting'
		);
		$this->assertEquals(
			$getall['params'],
			$GLOBALS['params'],
			'extractAllGlobals(): parameter replacement'
		);
		$this->assertEquals(
			$getall['global'],
			$GLOBALS['global'],
			'extractAllGlobals(): merging with global'
		);
		$this->assertEquals(
			$getall['merge'],
			$GLOBALS['merge'],
			'extractAllGlobals(): merging setting'
		);
	}
}
