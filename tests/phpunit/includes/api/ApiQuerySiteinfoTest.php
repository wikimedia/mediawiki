<?php

use MediaWiki\MediaWikiServices;

/**
 * @group API
 * @group medium
 * @group Database
 *
 * @covers ApiQuerySiteinfo
 */
class ApiQuerySiteinfoTest extends ApiTestCase {
	// We don't try to test every single thing for every category, just a sample

	protected function doQuery( $siprop = null, $extraParams = [] ) {
		$params = [ 'action' => 'query', 'meta' => 'siteinfo' ];
		if ( $siprop !== null ) {
			$params['siprop'] = $siprop;
		}
		$params = array_merge( $params, $extraParams );

		$res = $this->doApiRequest( $params );

		$this->assertArrayNotHasKey( 'warnings', $res[0] );
		$this->assertCount( 1, $res[0]['query'] );

		return $res[0]['query'][$siprop === null ? 'general' : $siprop];
	}

	public function testGeneral() {
		$this->setMwGlobals( [
			'wgAllowExternalImagesFrom' => '//localhost/',
		] );

		$data = $this->doQuery();

		$this->assertSame( Title::newMainPage()->getPrefixedText(), $data['mainpage'] );
		$this->assertSame( PHP_VERSION, $data['phpversion'] );
		$this->assertSame( [ '//localhost/' ], $data['externalimages'] );
	}

	public function testLinkPrefixCharset() {
		$contLang = Language::factory( 'ar' );
		$this->setContentLang( $contLang );
		$this->assertTrue( $contLang->linkPrefixExtension(), 'Sanity check' );

		$data = $this->doQuery();

		$this->assertSame( $contLang->linkPrefixCharset(), $data['linkprefixcharset'] );
	}

	public function testVariants() {
		$contLang = Language::factory( 'zh' );
		$this->setContentLang( $contLang );
		$this->assertTrue( $contLang->hasVariants(), 'Sanity check' );

		$data = $this->doQuery();

		$expected = array_map(
			function ( $code ) use ( $contLang ) {
				return [ 'code' => $code, 'name' => $contLang->getVariantname( $code ) ];
			},
			$contLang->getVariants()
		);

		$this->assertSame( $expected, $data['variants'] );
	}

	public function testReadOnly() {
		$svc = MediaWikiServices::getInstance()->getReadOnlyMode();
		$svc->setReason( 'Need more donations' );
		try {
			$data = $this->doQuery();
		} finally {
			$svc->setReason( false );
		}

		$this->assertTrue( $data['readonly'] );
		$this->assertSame( 'Need more donations', $data['readonlyreason'] );
	}

	public function testNamespaces() {
		$this->setMwGlobals( 'wgExtraNamespaces', [ '138' => 'Testing' ] );

		$this->assertSame(
			array_keys( MediaWikiServices::getInstance()->getContentLanguage()->getFormattedNamespaces() ),
			array_keys( $this->doQuery( 'namespaces' ) )
		);
	}

	public function testNamespaceAliases() {
		global $wgNamespaceAliases;

		$expected = array_merge(
			$wgNamespaceAliases,
			MediaWikiServices::getInstance()->getContentLanguage()->getNamespaceAliases()
		);
		$expected = array_map(
			function ( $key, $val ) {
				return [ 'id' => $val, 'alias' => strtr( $key, '_', ' ' ) ];
			},
			array_keys( $expected ),
			$expected
		);

		// Test that we don't list duplicates
		$this->mergeMwGlobalArrayValue( 'wgNamespaceAliases', [ 'Talk' => NS_TALK ] );

		$this->assertSame( $expected, $this->doQuery( 'namespacealiases' ) );
	}

	public function testSpecialPageAliases() {
		$this->assertCount(
			count( MediaWikiServices::getInstance()->getSpecialPageFactory()->getNames() ),
			$this->doQuery( 'specialpagealiases' )
		);
	}

	public function testMagicWords() {
		$this->assertCount(
			count( MediaWikiServices::getInstance()->getContentLanguage()->getMagicWords() ),
			$this->doQuery( 'magicwords' )
		);
	}

	/**
	 * @dataProvider interwikiMapProvider
	 */
	public function testInterwikiMap( $filter ) {
		global $wgServer, $wgScriptPath;

		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert(
			'interwiki',
			[
				[
					'iw_prefix' => 'self',
					'iw_url' => "$wgServer$wgScriptPath/index.php?title=$1",
					'iw_api' => "$wgServer$wgScriptPath/api.php",
					'iw_wikiid' => 'somedbname',
					'iw_local' => true,
					'iw_trans' => true,
				],
				[
					'iw_prefix' => 'foreign',
					'iw_url' => '//foreign.example/wiki/$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => false,
					'iw_trans' => false,
				],
			],
			__METHOD__,
			'IGNORE'
		);
		$this->tablesUsed[] = 'interwiki';

		$this->setMwGlobals( [
			'wgLocalInterwikis' => [ 'self' ],
			'wgExtraInterlanguageLinkPrefixes' => [ 'self' ],
			'wgExtraLanguageNames' => [ 'self' => 'Recursion' ],
		] );

		MessageCache::singleton()->enable();

		$this->editPage( 'MediaWiki:Interlanguage-link-self', 'Self!' );
		$this->editPage( 'MediaWiki:Interlanguage-link-sitename-self', 'Circular logic' );

		$expected = [];

		if ( $filter === null || $filter === '!local' ) {
			$expected[] = [
				'prefix' => 'foreign',
				'url' => wfExpandUrl( '//foreign.example/wiki/$1', PROTO_CURRENT ),
				'protorel' => true,
			];
		}
		if ( $filter === null || $filter === 'local' ) {
			$expected[] = [
				'prefix' => 'self',
				'local' => true,
				'trans' => true,
				'language' => 'Recursion',
				'localinterwiki' => true,
				'extralanglink' => true,
				'linktext' => 'Self!',
				'sitename' => 'Circular logic',
				'url' => "$wgServer$wgScriptPath/index.php?title=$1",
				'protorel' => false,
				'wikiid' => 'somedbname',
				'api' => "$wgServer$wgScriptPath/api.php",
			];
		}

		$data = $this->doQuery( 'interwikimap',
			$filter === null ? [] : [ 'sifilteriw' => $filter ] );

		$this->assertSame( $expected, $data );
	}

	public function interwikiMapProvider() {
		return [ [ 'local' ], [ '!local' ], [ null ] ];
	}

	/**
	 * @dataProvider dbReplLagProvider
	 */
	public function testDbReplLagInfo( $showHostnames, $includeAll ) {
		if ( !$showHostnames && $includeAll ) {
			$this->setExpectedApiException( 'apierror-siteinfo-includealldenied' );
		}

		$mockLB = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getMaxLag', 'getLagTimes', 'getServerName', '__destruct' ] )
			->getMock();
		$mockLB->method( 'getMaxLag' )->willReturn( [ null, 7, 1 ] );
		$mockLB->method( 'getLagTimes' )->willReturn( [ 5, 7 ] );
		$mockLB->method( 'getServerName' )->will( $this->returnValueMap( [
			[ 0, 'apple' ], [ 1, 'carrot' ]
		] ) );
		$this->setService( 'DBLoadBalancer', $mockLB );

		$this->setMwGlobals( 'wgShowHostnames', $showHostnames );

		$expected = [];
		if ( $includeAll ) {
			$expected[] = [ 'host' => $showHostnames ? 'apple' : '', 'lag' => 5 ];
		}
		$expected[] = [ 'host' => $showHostnames ? 'carrot' : '', 'lag' => 7 ];

		$data = $this->doQuery( 'dbrepllag', $includeAll ? [ 'sishowalldb' => '' ] : [] );

		$this->assertSame( $expected, $data );
	}

	public function dbReplLagProvider() {
		return [
			'no hostnames, no showalldb' => [ false, false ],
			'no hostnames, showalldb' => [ false, true ],
			'hostnames, no showalldb' => [ true, false ],
			'hostnames, showalldb' => [ true, true ]
		];
	}

	public function testStatistics() {
		$this->setTemporaryHook( 'APIQuerySiteInfoStatisticsInfo',
			function ( &$data ) {
				$data['addedstats'] = 42;
			}
		);

		$expected = [
			'pages' => intval( SiteStats::pages() ),
			'articles' => intval( SiteStats::articles() ),
			'edits' => intval( SiteStats::edits() ),
			'images' => intval( SiteStats::images() ),
			'users' => intval( SiteStats::users() ),
			'activeusers' => intval( SiteStats::activeUsers() ),
			'admins' => intval( SiteStats::numberingroup( 'sysop' ) ),
			'jobs' => intval( SiteStats::jobs() ),
			'addedstats' => 42,
		];

		$this->assertSame( $expected, $this->doQuery( 'statistics' ) );
	}

	/**
	 * @dataProvider groupsProvider
	 */
	public function testUserGroups( $numInGroup ) {
		global $wgGroupPermissions, $wgAutopromote;

		$this->setGroupPermissions( 'viscount', 'perambulate', 'yes' );
		$this->setGroupPermissions( 'viscount', 'legislate', '0' );
		$this->setMwGlobals( [
			'wgAddGroups' => [ 'viscount' => true, 'bot' => [] ],
			'wgRemoveGroups' => [ 'viscount' => [ 'sysop' ], 'bot' => [ '*', 'earl' ] ],
			'wgGroupsAddToSelf' => [ 'bot' => [ 'bureaucrat', 'sysop' ] ],
			'wgGroupsRemoveFromSelf' => [ 'bot' => [ 'bot' ] ],
		] );

		$data = $this->doQuery( 'usergroups', $numInGroup ? [ 'sinumberingroup' => '' ] : [] );

		$names = array_map(
			function ( $val ) {
				return $val['name'];
			},
			$data
		);

		$this->assertSame( array_keys( $wgGroupPermissions ), $names );

		foreach ( $data as $val ) {
			if ( !$numInGroup ) {
				$expectedSize = null;
			} elseif ( $val['name'] === 'user' ) {
				$expectedSize = SiteStats::users();
			} elseif ( $val['name'] === '*' || isset( $wgAutopromote[$val['name']] ) ) {
				$expectedSize = null;
			} else {
				$expectedSize = SiteStats::numberingroup( $val['name'] );
			}

			if ( $expectedSize === null ) {
				$this->assertArrayNotHasKey( 'number', $val );
			} else {
				$this->assertSame( $expectedSize, $val['number'] );
			}

			if ( $val['name'] === 'viscount' ) {
				$viscountFound = true;
				$this->assertSame( [ 'perambulate' ], $val['rights'] );
				$this->assertSame( User::getAllGroups(), $val['add'] );
			} elseif ( $val['name'] === 'bot' ) {
				$this->assertArrayNotHasKey( 'add', $val );
				$this->assertArrayNotHasKey( 'remove', $val );
				$this->assertSame( [ 'bureaucrat', 'sysop' ], $val['add-self'] );
				$this->assertSame( [ 'bot' ], $val['remove-self'] );
			}
		}
	}

	public function testFileExtensions() {
		global $wgFileExtensions;

		// Add duplicate
		$this->setMwGlobals( 'wgFileExtensions', array_merge( $wgFileExtensions, [ 'png' ] ) );

		$expected = array_map(
			function ( $val ) {
				return [ 'ext' => $val ];
			},
			array_unique( $wgFileExtensions )
		);

		$this->assertSame( $expected, $this->doQuery( 'fileextensions' ) );
	}

	public function groupsProvider() {
		return [
			'numingroup' => [ true ],
			'nonumingroup' => [ false ],
		];
	}

	public function testInstalledLibraries() {
		// @todo Test no installed.json?  Moving installed.json to a different name temporarily
		// seems a bit scary, but I don't see any other way to do it.
		//
		// @todo Install extensions/skins somehow so that we can test they're filtered out
		global $IP;

		$path = "$IP/vendor/composer/installed.json";
		if ( !file_exists( $path ) ) {
			$this->markTestSkipped( 'No installed libraries' );
		}

		$expected = ( new ComposerInstalled( $path ) )->getInstalledDependencies();

		$expected = array_filter( $expected,
			function ( $info ) {
				return strpos( $info['type'], 'mediawiki-' ) !== 0;
			}
		);

		$expected = array_map(
			function ( $name, $info ) {
				return [ 'name' => $name, 'version' => $info['version'] ];
			},
			array_keys( $expected ),
			array_values( $expected )
		);

		$this->assertSame( $expected, $this->doQuery( 'libraries' ) );
	}

	public function testExtensions() {
		$tmpdir = $this->getNewTempDirectory();
		touch( "$tmpdir/ErsatzExtension.php" );
		touch( "$tmpdir/LICENSE" );
		touch( "$tmpdir/AUTHORS.txt" );

		$val = [
			'path' => "$tmpdir/ErsatzExtension.php",
			'name' => 'Ersatz Extension',
			'namemsg' => 'ersatz-extension-name',
			'author' => 'John Smith',
			'version' => '0.0.2',
			'url' => 'https://www.example.com/software/ersatz-extension',
			'description' => 'An extension that is not what it seems.',
			'descriptionmsg' => 'ersatz-extension-desc',
			'license-name' => 'PD',
		];

		$this->setMwGlobals( 'wgExtensionCredits', [ 'api' => [
			$val,
			[
				'author' => [ 'John Smith', 'John Smith Jr.', '...' ],
				'descriptionmsg' => [ 'another-extension-desc', 'param' ] ],
		] ] );

		$data = $this->doQuery( 'extensions' );

		$this->assertCount( 2, $data );

		$this->assertSame( 'api', $data[0]['type'] );

		$sharedKeys = [ 'name', 'namemsg', 'description', 'descriptionmsg', 'author', 'url',
			'version', 'license-name' ];
		foreach ( $sharedKeys as $key ) {
			$this->assertSame( $val[$key], $data[0][$key] );
		}

		// @todo Test git info

		$this->assertSame(
			Title::newFromText( 'Special:Version/License/Ersatz Extension' )->getLinkURL(),
			$data[0]['license']
		);

		$this->assertSame(
			Title::newFromText( 'Special:Version/Credits/Ersatz Extension' )->getLinkURL(),
			$data[0]['credits']
		);

		$this->assertSame( 'another-extension-desc', $data[1]['descriptionmsg'] );
		$this->assertSame( [ 'param' ], $data[1]['descriptionmsgparams'] );
		$this->assertSame( 'John Smith, John Smith Jr., ...', $data[1]['author'] );
	}

	/**
	 * @dataProvider rightsInfoProvider
	 */
	public function testRightsInfo( $page, $url, $text, $expectedUrl, $expectedText ) {
		$this->setMwGlobals( [
			'wgRightsPage' => $page,
			'wgRightsUrl' => $url,
			'wgRightsText' => $text,
		] );

		$this->assertSame(
			[ 'url' => $expectedUrl, 'text' => $expectedText ],
			$this->doQuery( 'rightsinfo' )
		);
	}

	public function rightsInfoProvider() {
		$textUrl = wfExpandUrl( Title::newFromText( 'License' ), PROTO_CURRENT );
		$url = 'http://license.example/';

		return [
			'No rights info' => [ null, null, null, '', '' ],
			'Only page' => [ 'License', null, null, $textUrl, 'License' ],
			'Only URL' => [ null, $url, null, $url, '' ],
			'Only text' => [ null, null, '!!!', '', '!!!' ],
			// URL is ignored if page is specified
			'Page and URL' => [ 'License', $url, null, $textUrl, 'License' ],
			'URL and text' => [ null, $url, '!!!', $url, '!!!' ],
			'Page and text' => [ 'License', null, '!!!', $textUrl, '!!!' ],
			'Page and URL and text' => [ 'License', $url, '!!!', $textUrl, '!!!' ],
			'Pagename "0"' => [ '0', null, null,
				wfExpandUrl( Title::newFromText( '0' ), PROTO_CURRENT ), '0' ],
			'URL "0"' => [ null, '0', null, '0', '' ],
			'Text "0"' => [ null, null, '0', '', '0' ],
		];
	}

	public function testRestrictions() {
		global $wgRestrictionTypes, $wgRestrictionLevels, $wgCascadingRestrictionLevels,
			$wgSemiprotectedRestrictionLevels;

		$this->assertSame( [
			'types' => $wgRestrictionTypes,
			'levels' => $wgRestrictionLevels,
			'cascadinglevels' => $wgCascadingRestrictionLevels,
			'semiprotectedlevels' => $wgSemiprotectedRestrictionLevels,
		], $this->doQuery( 'restrictions' ) );
	}

	/**
	 * @dataProvider languagesProvider
	 */
	public function testLanguages( $langCode ) {
		$expected = Language::fetchLanguageNames( (string)$langCode );

		$expected = array_map(
			function ( $code, $name ) {
				return [
					'code' => $code,
					'bcp47' => LanguageCode::bcp47( $code ),
					'name' => $name
				];
			},
			array_keys( $expected ),
			array_values( $expected )
		);

		$data = $this->doQuery( 'languages',
			$langCode !== null ? [ 'siinlanguagecode' => $langCode ] : [] );

		$this->assertSame( $expected, $data );
	}

	public function languagesProvider() {
		return [ [ null ], [ 'fr' ] ];
	}

	public function testLanguageVariants() {
		$expectedKeys = array_filter( LanguageConverter::$languagesWithVariants,
			function ( $langCode ) {
				return !Language::factory( $langCode )->getConverter() instanceof FakeConverter;
			}
		);
		sort( $expectedKeys );

		$this->assertSame( $expectedKeys, array_keys( $this->doQuery( 'languagevariants' ) ) );
	}

	public function testLanguageVariantsDisabled() {
		$this->setMwGlobals( 'wgDisableLangConversion', true );

		$this->assertSame( [], $this->doQuery( 'languagevariants' ) );
	}

	/**
	 * @todo Test a skin with a description that's known to be different in a different language.
	 *   Vector will do, but it's not installed by default.
	 *
	 * @todo Test that an invalid language code doesn't actually try reading any messages
	 *
	 * @dataProvider skinsProvider
	 */
	public function testSkins( $code ) {
		$data = $this->doQuery( 'skins', $code !== null ? [ 'siinlanguagecode' => $code ] : [] );

		$expectedAllowed = Skin::getAllowedSkins();
		$expectedDefault = Skin::normalizeKey( 'default' );

		$i = 0;
		foreach ( Skin::getSkinNames() as $name => $displayName ) {
			$this->assertSame( $name, $data[$i]['code'] );

			$msg = wfMessage( "skinname-$name" );
			if ( $code && Language::isValidCode( $code ) ) {
				$msg->inLanguage( $code );
			} else {
				$msg->inContentLanguage();
			}
			if ( $msg->exists() ) {
				$displayName = $msg->text();
			}
			$this->assertSame( $displayName, $data[$i]['name'] );

			if ( !isset( $expectedAllowed[$name] ) ) {
				$this->assertTrue( $data[$i]['unusable'], "$name must be unusable" );
			}
			if ( $name === $expectedDefault ) {
				$this->assertTrue( $data[$i]['default'], "$expectedDefault must be default" );
			}
			$i++;
		}
	}

	public function skinsProvider() {
		return [
			'No language specified' => [ null ],
			'Czech' => [ 'cs' ],
			'Invalid language' => [ '/invalid/' ],
		];
	}

	public function testExtensionTags() {
		global $wgParser;

		$expected = array_map(
			function ( $tag ) {
				return "<$tag>";
			},
			$wgParser->getTags()
		);

		$this->assertSame( $expected, $this->doQuery( 'extensiontags' ) );
	}

	public function testFunctionHooks() {
		global $wgParser;

		$this->assertSame( $wgParser->getFunctionHooks(), $this->doQuery( 'functionhooks' ) );
	}

	public function testVariables() {
		$this->assertSame(
			MediaWikiServices::getInstance()->getMagicWordFactory()->getVariableIDs(),
			$this->doQuery( 'variables' )
		);
	}

	public function testProtocols() {
		global $wgUrlProtocols;

		$this->assertSame( $wgUrlProtocols, $this->doQuery( 'protocols' ) );
	}

	public function testDefaultOptions() {
		$this->assertSame( User::getDefaultOptions(), $this->doQuery( 'defaultoptions' ) );
	}

	public function testUploadDialog() {
		global $wgUploadDialog;

		$this->assertSame( $wgUploadDialog, $this->doQuery( 'uploaddialog' ) );
	}

	public function testGetHooks() {
		global $wgHooks;

		// Make sure there's something to report on
		$this->setTemporaryHook( 'somehook',
			function () {
				return;
			}
		);

		$expectedNames = $wgHooks;
		ksort( $expectedNames );

		$actualNames = array_map(
			function ( $val ) {
				return $val['name'];
			},
			$this->doQuery( 'showhooks' )
		);

		$this->assertSame( array_keys( $expectedNames ), $actualNames );
	}

	public function testContinuation() {
		// We make lots and lots of URL protocols that are each 100 bytes
		global $wgAPIMaxResultSize, $wgUrlProtocols;

		$this->setMwGlobals( 'wgUrlProtocols', [] );

		// Just under the limit
		$chunks = $wgAPIMaxResultSize / 100 - 1;

		for ( $i = 0; $i < $chunks; $i++ ) {
			$wgUrlProtocols[] = substr( str_repeat( "$i ", 50 ), 0, 100 );
		}

		$res = $this->doApiRequest( [
			'action' => 'query',
			'meta' => 'siteinfo',
			'siprop' => 'protocols|languages',
		] );

		$this->assertSame(
			wfMessage( 'apiwarn-truncatedresult', Message::numParam( $wgAPIMaxResultSize ) )
				->text(),
			$res[0]['warnings']['result']['warnings']
		);

		$this->assertSame( $wgUrlProtocols, $res[0]['query']['protocols'] );
		$this->assertArrayNotHasKey( 'languages', $res[0] );
		$this->assertTrue( $res[0]['batchcomplete'], 'batchcomplete should be true' );
		$this->assertSame( [ 'siprop' => 'languages', 'continue' => '-||' ], $res[0]['continue'] );
	}
}
