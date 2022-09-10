<?php

use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @group medium
 * @group Database
 *
 * @covers ApiQuerySiteinfo
 */
class ApiQuerySiteinfoTest extends ApiTestCase {
	private $originalRegistryLoaded = null;

	protected function tearDown(): void {
		if ( $this->originalRegistryLoaded !== null ) {
			$reg = TestingAccessWrapper::newFromObject( ExtensionRegistry::getInstance() );
			$reg->loaded = $this->originalRegistryLoaded;
			$this->originalRegistryLoaded = null;
		}
		parent::tearDown();
	}

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
		$this->overrideConfigValues( [
			MainConfigNames::AllowExternalImagesFrom => '//localhost/',
			MainConfigNames::MainPageIsDomainRoot => true,
		] );

		$data = $this->doQuery();

		$this->assertSame( Title::newMainPage()->getPrefixedText(), $data['mainpage'] );
		$this->assertSame( PHP_VERSION, $data['phpversion'] );
		$this->assertSame( [ '//localhost/' ], $data['externalimages'] );
		$this->assertTrue( $data['mainpageisdomainroot'] );
	}

	public function testLinkPrefixCharset() {
		$contLang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'ar' );
		$this->setContentLang( $contLang );
		$this->assertTrue( $contLang->linkPrefixExtension() );

		$data = $this->doQuery();

		$this->assertSame( $contLang->linkPrefixCharset(), $data['linkprefixcharset'] );
	}

	public function testVariants() {
		$contLang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'zh' );
		$this->setContentLang( $contLang );
		$this->assertTrue( $contLang->hasVariants() );

		$data = $this->doQuery();

		$expected = array_map(
			static function ( $code ) use ( $contLang ) {
				return [ 'code' => $code, 'name' => $contLang->getVariantname( $code ) ];
			},
			$contLang->getVariants()
		);

		$this->assertSame( $expected, $data['variants'] );
	}

	public function testReadOnly() {
		$svc = $this->getServiceContainer()->getReadOnlyMode();
		$svc->setReason( 'Need more donations' );
		try {
			$data = $this->doQuery();
		} finally {
			$svc->setReason( false );
		}

		$this->assertTrue( $data['readonly'] );
		$this->assertSame( 'Need more donations', $data['readonlyreason'] );
	}

	public function testNamespacesBasic() {
		$this->assertSame(
			array_keys( $this->getServiceContainer()->getContentLanguage()->getFormattedNamespaces() ),
			array_keys( $this->doQuery( 'namespaces' ) )
		);
	}

	public function testNamespacesExtraNS() {
		$this->overrideConfigValue( MainConfigNames::ExtraNamespaces, [ '138' => 'Testing' ] );
		$this->assertSame(
			array_keys( $this->getServiceContainer()->getContentLanguage()->getFormattedNamespaces() ),
			array_keys( $this->doQuery( 'namespaces' ) )
		);
	}

	public function testNamespacesProtection() {
		$this->overrideConfigValue(
			MainConfigNames::NamespaceProtection,
			[
				'0' => '',
				'2' => [ '' ],
				'4' => 'editsemiprotected',
				'8' => [
					'editinterface',
					'noratelimit'
				],
				'14' => [
					'move-categorypages',
					''
				]
			]
		);
		$data = $this->doQuery( 'namespaces' );
		$this->assertArrayNotHasKey( 'namespaceprotection', $data['0'] );
		$this->assertArrayNotHasKey( 'namespaceprotection', $data['2'] );
		$this->assertSame( 'editsemiprotected', $data['4']['namespaceprotection'] );
		$this->assertSame( 'editinterface|noratelimit', $data['8']['namespaceprotection'] );
		$this->assertSame( 'move-categorypages', $data['14']['namespaceprotection'] );
	}

	public function testNamespaceAliases() {
		$expected = $this->getServiceContainer()->getContentLanguage()->getNamespaceAliases();
		$expected = array_map(
			static function ( $key, $val ) {
				return [ 'id' => $val, 'alias' => strtr( $key, '_', ' ' ) ];
			},
			array_keys( $expected ),
			$expected
		);

		$this->assertSame( $expected, $this->doQuery( 'namespacealiases' ) );
	}

	public function testSpecialPageAliases() {
		$this->assertCount(
			count( $this->getServiceContainer()->getSpecialPageFactory()->getNames() ),
			$this->doQuery( 'specialpagealiases' )
		);
	}

	public function testMagicWords() {
		$this->assertCount(
			count( $this->getServiceContainer()->getContentLanguage()->getMagicWords() ),
			$this->doQuery( 'magicwords' )
		);
	}

	/**
	 * @dataProvider interwikiMapProvider
	 */
	public function testInterwikiMap( $filter ) {
		$this->overrideConfigValues( [
			MainConfigNames::Server => 'https://local.example',
			MainConfigNames::ScriptPath => '/w',
		] );

		$dbw = wfGetDB( DB_PRIMARY );
		$dbw->insert(
			'interwiki',
			[
				[
					'iw_prefix' => 'self',
					'iw_url' => 'https://local.example/w/index.php?title=$1',
					'iw_api' => 'https://local.example/w/api.php',
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

		$this->overrideConfigValues( [
			MainConfigNames::LocalInterwikis => [ 'self' ],
			MainConfigNames::ExtraInterlanguageLinkPrefixes => [ 'self' ],
			MainConfigNames::ExtraLanguageNames => [ 'self' => 'Recursion' ],
		] );

		$this->getServiceContainer()->getMessageCache()->enable();

		$this->editPage( 'MediaWiki:Interlanguage-link-self', 'Self!' );
		$this->editPage( 'MediaWiki:Interlanguage-link-sitename-self', 'Circular logic' );

		$expected = [];

		if ( $filter === null || $filter === '!local' ) {
			$expected[] = [
				'prefix' => 'foreign',
				'url' => 'http://foreign.example/wiki/$1',
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
				'url' => 'https://local.example/w/index.php?title=$1',
				'protorel' => false,
				'wikiid' => 'somedbname',
				'api' => 'https://local.example/w/api.php',
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

		$mockLB = $this->createNoOpMock( LoadBalancer::class, [ 'getMaxLag', 'getLagTimes',
			'getServerName', 'getLocalDomainID' ] );
		$mockLB->method( 'getMaxLag' )->willReturn( [ null, 7, 1 ] );
		$mockLB->method( 'getLagTimes' )->willReturn( [ 5, 7 ] );
		$mockLB->method( 'getServerName' )->willReturnMap( [
			[ 0, 'apple' ],
			[ 1, 'carrot' ]
		] );
		$mockLB->method( 'getLocalDomainID' )->willReturn( 'testdomain' );
		$this->setService( 'DBLoadBalancer', $mockLB );

		$this->overrideConfigValue( MainConfigNames::ShowHostnames, $showHostnames );

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
			static function ( &$data ) {
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
		$this->overrideConfigValues( [
			MainConfigNames::AddGroups => [ 'viscount' => true, 'bot' => [] ],
			MainConfigNames::RemoveGroups => [ 'viscount' => [ 'sysop' ], 'bot' => [ '*', 'earl' ] ],
			MainConfigNames::GroupsAddToSelf => [ 'bot' => [ 'bureaucrat', 'sysop' ] ],
			MainConfigNames::GroupsRemoveFromSelf => [ 'bot' => [ 'bot' ] ],
		] );

		$data = $this->doQuery( 'usergroups', $numInGroup ? [ 'sinumberingroup' => '' ] : [] );

		$names = array_column( $data, 'name' );

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
		// Add duplicate
		$this->overrideConfigValue( MainConfigNames::FileExtensions, [ 'png', 'gif', 'jpg', 'png' ] );

		$expected = [ [ 'ext' => 'png' ], [ 'ext' => 'gif' ], [ 'ext' => 'jpg' ] ];

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
		if ( !is_file( $path ) ) {
			$this->markTestSkipped( 'No installed libraries' );
		}

		$expected = ( new ComposerInstalled( $path ) )->getInstalledDependencies();

		$expected = array_filter( $expected,
			static function ( $info ) {
				return strpos( $info['type'], 'mediawiki-' ) !== 0;
			}
		);

		$expected = array_map(
			static function ( $name, $info ) {
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

		$this->overrideConfigValue( MainConfigNames::ExtensionCredits, [ 'api' => [
			$val,
			[
				'author' => [ 'John Smith', 'John Smith Jr.', '...' ],
				'descriptionmsg' => [ 'another-extension-desc', 'param' ] ],
		] ] );
		// Make the main registry empty
		// TODO: Make ExtensionRegistry an injected service?
		$reg = TestingAccessWrapper::newFromObject( ExtensionRegistry::getInstance() );
		$this->originalRegistryLoaded = $reg->loaded;
		$reg->loaded = [];

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
	public function testRightsInfo( $page, $url, $text, $expectedUrlOrTitle, $expectedText ) {
		$expectedUrl = ( $expectedUrlOrTitle instanceof Title )
			? wfExpandUrl( $expectedUrlOrTitle->getLinkURL(), PROTO_CURRENT )
			: $expectedUrlOrTitle;

		$this->overrideConfigValues( [
			MainConfigNames::RightsPage => $page,
			MainConfigNames::RightsUrl => $url,
			MainConfigNames::RightsText => $text,
		] );
		$this->assertSame(
			[ 'url' => $expectedUrl, 'text' => $expectedText ],
			$this->doQuery( 'rightsinfo' )
		);

		// The installer sets these options to empty string if not specified otherwise,
		// test that this behaves the same as null.
		$this->overrideConfigValues( [
			MainConfigNames::RightsPage => $page ?? '',
			MainConfigNames::RightsUrl => $url ?? '',
			MainConfigNames::RightsText => $text ?? '',
		] );
		$this->assertSame(
			[ 'url' => $expectedUrl, 'text' => $expectedText ],
			$this->doQuery( 'rightsinfo' ),
			'empty string behaves the same as null'
		);
	}

	public function rightsInfoProvider() {
		$licenseTitle = Title::makeTitle( 0, 'License' );
		$licenseUrl = 'http://license.example/';

		return [
			'No rights info' => [ null, null, null, '', '' ],
			'Only page' => [ 'License', null, null, $licenseTitle, 'License' ],
			'Only URL' => [ null, $licenseUrl, null, $licenseUrl, '' ],
			'Only text' => [ null, null, '!!!', '', '!!!' ],
			// URL is ignored if page is specified
			'Page and URL' => [ 'License', $licenseUrl, null, $licenseTitle, 'License' ],
			'URL and text' => [ null, $licenseUrl, '!!!', $licenseUrl, '!!!' ],
			'Page and text' => [ 'License', null, '!!!', $licenseTitle, '!!!' ],
			'Page and URL and text' => [ 'License', $licenseUrl, '!!!', $licenseTitle, '!!!' ],
			'Pagename "0"' => [ '0', null, null, Title::makeTitle( 0, '0' ), '0' ],
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
		$expected = $this->getServiceContainer()
			->getLanguageNameUtils()
			->getLanguageNames( (string)$langCode );

		$expected = array_map(
			static function ( $code, $name ) {
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
				$lang = $this->getServiceContainer()->getLanguageFactory()
					->getLanguage( $langCode );
				$converter = $this->getServiceContainer()->getLanguageConverterFactory()
					->getLanguageConverter( $lang );
					return $converter->hasVariants();
			}
		);
		sort( $expectedKeys );

		$this->assertSame( $expectedKeys, array_keys( $this->doQuery( 'languagevariants' ) ) );
	}

	public function testLanguageVariantsDisabled() {
		$this->overrideConfigValue( MainConfigNames::DisableLangConversion, true );

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
		$services = $this->getServiceContainer();
		$skinFactory = $services->getSkinFactory();
		$skinNames = $skinFactory->getInstalledSkins();
		$expectedAllowed = $skinFactory->getAllowedSkins();
		$expectedDefault = Skin::normalizeKey( 'default' );
		$languageNameUtils = $services->getLanguageNameUtils();

		$i = 0;
		foreach ( $skinNames as $name => $displayName ) {
			$this->assertSame( $name, $data[$i]['code'] );

			$msg = wfMessage( "skinname-$name" );
			if ( $code && $languageNameUtils->isValidCode( $code ) ) {
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
		$expected = array_map(
			static function ( $tag ) {
				return "<$tag>";
			},
			$this->getServiceContainer()->getParser()->getTags()
		);

		$this->assertSame( $expected, $this->doQuery( 'extensiontags' ) );
	}

	public function testFunctionHooks() {
		$this->assertSame( $this->getServiceContainer()->getParser()->getFunctionHooks(),
			$this->doQuery( 'functionhooks' ) );
	}

	public function testVariables() {
		$this->assertSame(
			$this->getServiceContainer()->getMagicWordFactory()->getVariableIDs(),
			$this->doQuery( 'variables' )
		);
	}

	public function testProtocols() {
		$urlProtocol = MainConfigSchema::getDefaultValue(
			MainConfigNames::UrlProtocols
		);
		$this->assertSame( $urlProtocol, $this->doQuery( 'protocols' ) );
	}

	public function testDefaultOptions() {
		$this->assertSame(
			$this->getServiceContainer()->getUserOptionsLookup()->getDefaultOptions(),
			$this->doQuery( 'defaultoptions' )
		);
	}

	public function testUploadDialog() {
		global $wgUploadDialog;

		$this->assertSame( $wgUploadDialog, $this->doQuery( 'uploaddialog' ) );
	}

	public function testGetHooks() {
		global $wgHooks;

		// Make sure there's something to report on
		$this->setTemporaryHook( 'somehook',
			static function () {
				return;
			}
		);

		$expectedNames = $wgHooks;
		ksort( $expectedNames );

		$actualNames = array_column( $this->doQuery( 'showhooks' ), 'name' );

		$this->assertSame( array_keys( $expectedNames ), $actualNames );
	}

	public function testContinuation() {
		// Use $wgUrlProtocols as easy example for forging the
		// size of the API response
		$protocol = 'foo://';
		$size = strlen( $protocol );
		$protocols = [ $protocol ];

		$this->overrideConfigValues( [
			MainConfigNames::UrlProtocols => [ $protocol ],
			MainConfigNames::APIMaxResultSize => $size,
		] );

		$res = $this->doApiRequest( [
			'action' => 'query',
			'meta' => 'siteinfo',
			'siprop' => 'protocols|languages',
		] );

		$this->assertSame(
			wfMessage( 'apiwarn-truncatedresult', Message::numParam( $size ) )
				->text(),
			$res[0]['warnings']['result']['warnings']
		);

		$this->assertSame( $protocols, $res[0]['query']['protocols'] );
		$this->assertArrayNotHasKey( 'languages', $res[0] );
		$this->assertTrue( $res[0]['batchcomplete'], 'batchcomplete should be true' );
		$this->assertSame( [ 'siprop' => 'languages', 'continue' => '-||' ], $res[0]['continue'] );
	}
}
