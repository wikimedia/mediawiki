<?php

function getSiteParams( $conf, $wiki ) {
	$site = null;
	$lang = null;
	foreach ( $conf->suffixes as $suffix ) {
		if ( substr( $wiki, -strlen( $suffix ) ) == $suffix ) {
			$site = $suffix;
			$lang = substr( $wiki, 0, -strlen( $suffix ) );
			break;
		}
	}
	return array(
		'suffix' => $site,
		'lang' => $lang,
		'params' => array(
			'lang' => $lang,
			'site' => $site,
			'wiki' => $wiki,
		),
		'tags' => array( 'tag' ),
	);
}

class SiteConfigurationTest extends MediaWikiTestCase {
	var $mConf;

	function setUp() {
		$this->mConf = new SiteConfiguration;

		$this->mConf->suffixes = array( 'wiki' );
		$this->mConf->wikis = array( 'enwiki', 'dewiki', 'frwiki' );
		$this->mConf->settings = array(
			'simple' => array(
				'wiki' => 'wiki',
				'tag' => 'tag',
				'enwiki' => 'enwiki',
				'dewiki' => 'dewiki',
				'frwiki' => 'frwiki',
			),

			'fallback' => array(
				'default' => 'default',
				'wiki' => 'wiki',
				'tag' => 'tag',
			),

			'params' => array(
				'default' => '$lang $site $wiki',
			),

			'+global' => array(
				'wiki' => array(
					'wiki' => 'wiki',
				),
				'tag' => array(
					'tag' => 'tag',
				),
				'enwiki' => array(
					'enwiki' => 'enwiki',
				),
				'dewiki' => array(
					'dewiki' => 'dewiki',
				),
				'frwiki' => array(
					'frwiki' => 'frwiki',
				),
			),

			'merge' => array(
				'+wiki' => array(
					'wiki' => 'wiki',
				),
				'+tag' => array(
					'tag' => 'tag',
				),
				'default' => array(
					'default' => 'default',
				),
				'+enwiki' => array(
					'enwiki' => 'enwiki',
				),
				'+dewiki' => array(
					'dewiki' => 'dewiki',
				),
				'+frwiki' => array(
					'frwiki' => 'frwiki',
				),
			),
		);

		$GLOBALS['global'] = array( 'global' => 'global' );
	}


	function testSiteFromDb() {
		$this->assertEquals(
			array( 'wikipedia', 'en' ),
			$this->mConf->siteFromDB( 'enwiki' ),
			'siteFromDB()'
		);
		$this->assertEquals(
			array( 'wikipedia', '' ),
			$this->mConf->siteFromDB( 'wiki' ),
			'siteFromDB() on a suffix'
		);
		$this->assertEquals(
			array( null, null ),
			$this->mConf->siteFromDB( 'wikien' ),
			'siteFromDB() on a non-existing wiki'
		);

		$this->mConf->suffixes = array( 'wiki', '' );
		$this->assertEquals(
			array( '', 'wikien' ),
			$this->mConf->siteFromDB( 'wikien' ),
			'siteFromDB() on a non-existing wiki (2)'
		);
	}

	function testGetLocalDatabases() {
		$this->assertEquals(
			array( 'enwiki', 'dewiki', 'frwiki' ),
			$this->mConf->getLocalDatabases(),
			'getLocalDatabases()'
		);
	}

	function testGetConfVariables() {
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
			$this->mConf->get( 'fallback', 'dewiki', 'wiki', array(), array( 'tag' ) ),
			'get(): fallback setting on an existing wiki (with wiki tag)'
		);
		$this->assertEquals(
			'wiki',
			$this->mConf->get( 'fallback', 'wiki', 'wiki' ),
			'get(): fallback setting on an suffix'
		);
		$this->assertEquals(
			'wiki',
			$this->mConf->get( 'fallback', 'wiki', 'wiki', array(), array( 'tag' ) ),
			'get(): fallback setting on an suffix (with wiki tag)'
		);
		$this->assertEquals(
			'wiki',
			$this->mConf->get( 'fallback', 'eswiki', 'wiki' ),
			'get(): fallback setting on an non-existing wiki'
		);
		$this->assertEquals(
			'tag',
			$this->mConf->get( 'fallback', 'eswiki', 'wiki', array(), array( 'tag' ) ),
			'get(): fallback setting on an non-existing wiki (with wiki tag)'
		);

		$common = array( 'wiki' => 'wiki', 'default' => 'default' );
		$commonTag = array( 'tag' => 'tag', 'wiki' => 'wiki', 'default' => 'default' );
		$this->assertEquals(
			array( 'enwiki' => 'enwiki' ) + $common,
			$this->mConf->get( 'merge', 'enwiki', 'wiki' ),
			'get(): merging setting on an existing wiki'
		);
		$this->assertEquals(
			array( 'enwiki' => 'enwiki' ) + $commonTag,
			$this->mConf->get( 'merge', 'enwiki', 'wiki', array(), array( 'tag' ) ),
			'get(): merging setting on an existing wiki (with tag)'
		);
		$this->assertEquals(
			array( 'dewiki' => 'dewiki' ) + $common,
			$this->mConf->get( 'merge', 'dewiki', 'wiki' ),
			'get(): merging setting on an existing wiki (2)'
		);
		$this->assertEquals(
			array( 'dewiki' => 'dewiki' ) + $commonTag,
			$this->mConf->get( 'merge', 'dewiki', 'wiki', array(), array( 'tag' ) ),
			'get(): merging setting on an existing wiki (2) (with tag)'
		);
		$this->assertEquals(
			array( 'frwiki' => 'frwiki' ) + $common,
			$this->mConf->get( 'merge', 'frwiki', 'wiki' ),
			'get(): merging setting on an existing wiki (3)'
		);
		$this->assertEquals(
			array( 'frwiki' => 'frwiki' ) + $commonTag,
			$this->mConf->get( 'merge', 'frwiki', 'wiki', array(), array( 'tag' ) ),
			'get(): merging setting on an existing wiki (3) (with tag)'
		);
		$this->assertEquals(
			array( 'wiki' => 'wiki' ) + $common,
			$this->mConf->get( 'merge', 'wiki', 'wiki' ),
			'get(): merging setting on an suffix'
		);
		$this->assertEquals(
			array( 'wiki' => 'wiki' ) + $commonTag,
			$this->mConf->get( 'merge', 'wiki', 'wiki', array(), array( 'tag' ) ),
			'get(): merging setting on an suffix (with tag)'
		);
		$this->assertEquals(
			$common,
			$this->mConf->get( 'merge', 'eswiki', 'wiki' ),
			'get(): merging setting on an non-existing wiki'
		);
		$this->assertEquals(
			$commonTag,
			$this->mConf->get( 'merge', 'eswiki', 'wiki', array(), array( 'tag' ) ),
			'get(): merging setting on an non-existing wiki (with tag)'
		);
	}

	function testSiteFromDbWithCallback() {
		$this->mConf->siteParamsCallback = 'getSiteParams';

		$this->assertEquals(
			array( 'wiki', 'en' ),
			$this->mConf->siteFromDB( 'enwiki' ),
			'siteFromDB() with callback'
		);
		$this->assertEquals(
			array( 'wiki', '' ),
			$this->mConf->siteFromDB( 'wiki' ),
			'siteFromDB() with callback on a suffix'
		);
		$this->assertEquals(
			array( null, null ),
			$this->mConf->siteFromDB( 'wikien' ),
			'siteFromDB() with callback on a non-existing wiki'
		);
	}

	function testParameterReplacement() {
		$this->mConf->siteParamsCallback = 'getSiteParams';

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

	function testGetAllGlobals() {
		$this->mConf->siteParamsCallback = 'getSiteParams';

		$getall = array(
			'simple' => 'enwiki',
			'fallback' => 'tag',
			'params' => 'en wiki enwiki',
			'global' => array( 'enwiki' => 'enwiki' ) + $GLOBALS['global'],
			'merge' => array( 'enwiki' => 'enwiki', 'tag' => 'tag', 'wiki' => 'wiki', 'default' => 'default' ),
		);
		$this->assertEquals( $getall, $this->mConf->getAll( 'enwiki' ), 'getAll()' );

		$this->mConf->extractAllGlobals( 'enwiki', 'wiki' );

		$this->assertEquals( $getall['simple'], $GLOBALS['simple'], 'extractAllGlobals(): simple setting' );
		$this->assertEquals( $getall['fallback'], $GLOBALS['fallback'], 'extractAllGlobals(): fallback setting' );
		$this->assertEquals( $getall['params'], $GLOBALS['params'], 'extractAllGlobals(): parameter replacement' );
		$this->assertEquals( $getall['global'], $GLOBALS['global'],  'extractAllGlobals(): merging with global' );
		$this->assertEquals( $getall['merge'], $GLOBALS['merge'],  'extractAllGlobals(): merging setting' );
	}
}
