<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Language\LanguageCode;
use MediaWiki\Language\LanguageConverter;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Message\Message;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\SiteStats\SiteStats;
use MediaWiki\Skin\Skin;
use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use Wikimedia\Composer\ComposerInstalled;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @group medium
 * @group Database
 *
 * @covers \MediaWiki\Api\ApiQuerySiteinfo
 */
class ApiQuerySiteinfoTest extends ApiTestCase {
	use TempUserTestTrait;

	/** @var array[]|null */
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
		$converter = $this->getServiceContainer()->getLanguageConverterFactory()->getLanguageConverter( $contLang );
		$this->setContentLang( $contLang );
		$this->assertTrue( $converter->hasVariants() );

		$data = $this->doQuery();

		$expected = array_map(
			static function ( $code ) use ( $contLang ) {
				return [ 'code' => $code, 'name' => $contLang->getVariantname( $code ) ];
			},
			$converter->getVariants()
		);

		$this->assertSame( $expected, $data['variants'] );
	}

	public function testReadOnly() {
		// Create the test user before making the DB readonly
		$this->getTestSysop()->getUser();
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
		// XXX: why does this fail when the en-x-piglatin variant is enabled?
		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, false );

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
		$this->assertSameSize(
			$this->getServiceContainer()->getSpecialPageFactory()->getNames(),
			$this->doQuery( 'specialpagealiases' )
		);
	}

	public function testMagicWords() {
		$this->assertSameSize(
			$this->getServiceContainer()->getContentLanguage()->getMagicWords(),
			$this->doQuery( 'magicwords' )
		);
	}

	/**
	 * @dataProvider interwikiMapProvider
	 */
	public function testInterwikiMap( $filter ) {
		$this->overrideConfigValues( [
			MainConfigNames::ExtraInterlanguageLinkPrefixes => [ 'self' ],
			MainConfigNames::ExtraLanguageNames => [ 'self' => 'Recursion' ],
			MainConfigNames::LocalInterwikis => [ 'self' ],
			MainConfigNames::Server => 'https://local.example',
			MainConfigNames::ScriptPath => '/w',
		] );

		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'interwiki' )
			->ignore()
			->row( [
				'iw_prefix' => 'self',
				'iw_url' => 'https://local.example/w/index.php?title=$1',
				'iw_api' => 'https://local.example/w/api.php',
				'iw_wikiid' => 'somedbname',
				'iw_local' => true,
				'iw_trans' => true,
			] )
			->row( [
				'iw_prefix' => 'foreign',
				'iw_url' => '//foreign.example/wiki/$1',
				'iw_api' => '',
				'iw_wikiid' => '',
				'iw_local' => false,
				'iw_trans' => false,
			] )
			->caller( __METHOD__ )
			->execute();

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
				'bcp47' => 'self',
				'localinterwiki' => true,
				'extralanglink' => true,
				'code' => 'self',
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

	public static function interwikiMapProvider() {
		return [ [ 'local' ], [ '!local' ], [ null ] ];
	}

	/**
	 * @dataProvider dbReplLagProvider
	 */
	public function testDbReplLagInfo( $showHostnames, $includeAll ) {
		if ( !$showHostnames && $includeAll ) {
			$this->expectApiErrorCode( 'includeAllDenied' );
		}

		// Force creation of the test user before mocking the database.
		$this->getTestSysop()->getUser();

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

	public static function dbReplLagProvider() {
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
		global $wgAutopromote;

		$this->setGroupPermissions( [
			'viscount' => [
				'perambulate' => true,
				'legislate' => false,
			],
		] );
		$this->overrideConfigValues( [
			MainConfigNames::AddGroups => [ 'viscount' => true, 'bot' => [] ],
			MainConfigNames::RemoveGroups => [ 'viscount' => [ 'sysop' ], 'bot' => [ '*', 'earl' ] ],
			MainConfigNames::GroupsAddToSelf => [ 'bot' => [ 'bureaucrat', 'sysop' ] ],
			MainConfigNames::GroupsRemoveFromSelf => [ 'bot' => [ 'bot' ] ],
			MainConfigNames::GroupInheritsPermissions => [ 'viscountess' => 'viscount' ],
		] );

		$data = $this->doQuery( 'usergroups', $numInGroup ? [ 'sinumberingroup' => '' ] : [] );

		$names = array_column( $data, 'name' );

		$userAllGroups = $this->getServiceContainer()->getUserGroupManager()->listAllGroups();
		$userAllImplicitGroups = $this->getServiceContainer()->getUserGroupManager()->listAllImplicitGroups();
		$this->assertSame( array_merge( $userAllImplicitGroups, $userAllGroups ), $names );

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
				$this->assertSame( [ 'perambulate' ], $val['rights'] );
				$this->assertSame( $userAllGroups, $val['add'] );
			} elseif ( $val['name'] === 'viscountess' ) {
				$this->assertSame( [ 'perambulate' ], $val['rights'] );
				$this->assertArrayNotHasKey( 'add', $val );
			} elseif ( $val['name'] === 'bot' ) {
				$this->assertArrayNotHasKey( 'add', $val );
				$this->assertArrayNotHasKey( 'remove', $val );
				$this->assertSame( [ 'bureaucrat', 'sysop' ], $val['add-self'] );
				$this->assertSame( [ 'bot' ], $val['remove-self'] );
			}
		}
	}

	public function testAutoCreateTempUser() {
		$this->disableAutoCreateTempUser( [ 'reservedPattern' => null ] );
		$this->assertSame(
			[ 'enabled' => false ],
			$this->doQuery( 'autocreatetempuser' ),
			'When disabled, no other properties are present'
		);

		$this->enableAutoCreateTempUser( [
			'reservedPattern' => null,
		] );
		$this->assertArrayEquals(
			[
				'enabled' => true,
				'matchPatterns' => [ '~$1' ],
			],
			$this->doQuery( 'autocreatetempuser' ),
			false,
			true,
			'When enabled, some properties are filled in or cleaned up'
		);
	}

	public function testFileExtensions() {
		// Add duplicate
		$this->overrideConfigValue( MainConfigNames::FileExtensions, [ 'png', 'gif', 'jpg', 'png' ] );

		$expected = [ [ 'ext' => 'png' ], [ 'ext' => 'gif' ], [ 'ext' => 'jpg' ] ];

		$this->assertSame( $expected, $this->doQuery( 'fileextensions' ) );
	}

	public static function groupsProvider() {
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
				return !str_starts_with( $info['type'], 'mediawiki-' );
			}
		);

		$expected = array_map(
			static function ( $name, $info ) {
				return [ 'name' => $name, 'version' => $info['version'] ];
			},
			array_keys( $expected ),
			array_values( $expected )
		);

		// We don't care about the arrays being equal, just that actual contains
		// expected values...
		$this->assertArrayContains( $expected, $this->doQuery( 'libraries' ) );
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
			Title::makeTitle( NS_SPECIAL, 'Version/License/Ersatz Extension' )->getLinkURL(),
			$data[0]['license']
		);

		$this->assertSame(
			Title::makeTitle( NS_SPECIAL, 'Version/Credits/Ersatz Extension' )->getLinkURL(),
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
		$this->overrideConfigValues( [
			MainConfigNames::Server => 'https://local.example',
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

	public static function rightsInfoProvider() {
		$licenseTitleUrl = 'https://local.example/wiki/License';
		$licenseUrl = 'http://license.example/';

		return [
			'No rights info' => [ null, null, null, '', '' ],
			'Only page' => [ 'License', null, null, $licenseTitleUrl, 'License' ],
			'Only URL' => [ null, $licenseUrl, null, $licenseUrl, '' ],
			'Only text' => [ null, null, '!!!', '', '!!!' ],
			// URL is ignored if page is specified
			'Page and URL' => [ 'License', $licenseUrl, null, $licenseTitleUrl, 'License' ],
			'URL and text' => [ null, $licenseUrl, '!!!', $licenseUrl, '!!!' ],
			'Page and text' => [ 'License', null, '!!!', $licenseTitleUrl, '!!!' ],
			'Page and URL and text' => [ 'License', $licenseUrl, '!!!', $licenseTitleUrl, '!!!' ],
			'Pagename "0"' => [ '0', null, null, 'https://local.example/wiki/0', '0' ],
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

	public static function languagesProvider() {
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

	public static function skinsProvider() {
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
		// Make sure there's something to report on
		$this->setTemporaryHook( 'somehook',
			static function () {
			}
		);

		$hookContainer = $this->getServiceContainer()->getHookContainer();
		$expectedNames = $hookContainer->getHookNames();
		$actualNames = array_column( $this->doQuery( 'showhooks' ), 'name' );

		$this->assertArrayEquals( $expectedNames, $actualNames );
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

	/**
	 * @dataProvider provideAutopromote
	 */
	public function testAutopromote( $config, $expected ) {
		$this->overrideConfigValues( [
			MainConfigNames::Autopromote => $config,
			MainConfigNames::AutoConfirmCount => 10,
			MainConfigNames::AutoConfirmAge => 345600,
		] );
		$this->assertSame( $expected, $this->doQuery( 'autopromote' ) );
	}

	public static function provideAutopromote() {
		yield 'simple' => [
			[
				// edit count >= 10 and age >= 4 days
				'simple' => [ '&',
					[ APCOND_EDITCOUNT, 10 ],
					[ APCOND_AGE, 345600 ],
				],
			],
			[
				'simple' => [
					'operand' => '&',
					0 => [
						'condname' => 'APCOND_EDITCOUNT',
						'params' => [ 10 ]
					],
					1 => [
						'condname' => 'APCOND_AGE',
						'params' => [ 345600 ]
					]
				]
			]
		];

		// Test case to check default of null is replaced with value of appropriate $wg
		yield 'simple-use-wg' => [
			[
				'simple-use-wg' => [ '&',
					[ APCOND_EDITCOUNT, null ],
					[ APCOND_AGE, null ],
				],
			],
			[
				'simple-use-wg' => [
					'operand' => '&',
					0 => [
						'condname' => 'APCOND_EDITCOUNT',
						'params' => [ 10 ]
					],
					1 => [
						'condname' => 'APCOND_AGE',
						'params' => [ 345600 ]
					]
				]
			]
		];

		yield 'trivial' => [
			[
				'trivial' => APCOND_EMAILCONFIRMED,
			],
			[
				'trivial' => [
					0 => [
						'condname' => 'APCOND_EMAILCONFIRMED',
						'params' => []
					]
				],
			]
		];

		yield 'multiple-copies-of-condition' => [
			[
				// In both groups 'foo' and 'bar', or in group 'baz'
				'multiple-copies-of-condition' => [ '|',
					[ APCOND_INGROUPS, 'foo', 'bar' ],
					[ APCOND_INGROUPS, 'baz' ],
				],
			],
			[
				'multiple-copies-of-condition' => [
					'operand' => '|',
					0 => [
						'condname' => 'APCOND_INGROUPS',
						'params' => [ 'foo', 'bar' ]
					],
					1 => [
						'condname' => 'APCOND_INGROUPS',
						'params' => [ 'baz' ]
					]
				]
			]
		];

		yield 'complicated' => [
			[
				// confirmed email, or their edit count >= 100 and either they
				// created their account a year ago or it has been 10 days since
				// their first edit, or if they're in both groups 'group1' and
				// 'group2'. Except for users in the 'bad' group.
				'complicated' => [ '&',
					[ '|',
						APCOND_EMAILCONFIRMED,
						[ '&',
							[ APCOND_EDITCOUNT, 100 ],
							[ '|',
								[ APCOND_AGE, 525600 * 60 ],
								[ APCOND_AGE_FROM_EDIT, 864000 ],
							],
						],
						[ APCOND_INGROUPS, 'group1', 'group2' ],
					],
					[ '!', [ APCOND_INGROUPS, 'bad' ] ],
				],
			],
			[
				'complicated' => [
					'operand' => '&',
					0 => [
						'operand' => '|',
						0 => [
							'condname' => 'APCOND_EMAILCONFIRMED',
							'params' => []
						],
						1 => [
							'operand' => '&',
							0 => [
								'condname' => 'APCOND_EDITCOUNT',
								'params' => [ 100 ]
							],
							1 => [
								'operand' => '|',
								0 => [
									'condname' => 'APCOND_AGE',
									'params' => [ 31536000 ]
								],
								1 => [
									'condname' => 'APCOND_AGE_FROM_EDIT',
									'params' => [ 864000 ]
								]
							]
						],
						2 => [
							'condname' => 'APCOND_INGROUPS',
							'params' => [ 'group1', 'group2' ]
						]
					],
					1 => [
						'operand' => '!',
						0 => [
							'condname' => 'APCOND_INGROUPS',
							'params' => [ 'bad' ]
						]
					]
				]
			]
		];

		// Find an undefined APCOND integer
		$constants = [];
		foreach ( get_defined_constants() as $k => $v ) {
			if ( strpos( $k, 'APCOND_' ) !== false ) {
				$constants[$v] = $k;
			}
		}
		$bogusCond = 9000;
		while ( isset( $constants[$bogusCond] ) ) {
			$bogusCond++;
		}
		$bogusCond2 = $bogusCond + 1;
		while ( isset( $constants[$bogusCond2] ) ) {
			$bogusCond2++;
		}

		yield 'bad-cond-1' => [
			[
				// unknown APCOND constant. Might be handled by an extension that
				// didn't define a constant with the expected name.
				'bad-cond' => 'bogus',
			],
			[
				'bad-cond' => [
					'bogus'
				],
			]
		];
		yield 'bad-cond-2' => [
			[
				'bad-cond' => $bogusCond,
			],
			[
				'bad-cond' => [
					0 => [
						'condname' => false,
						'params' => []
					]
				],
			]
		];
		yield 'bad-cond-3' => [
			[
				'bad-cond' => [ 'bogus', 'bogus?', APCOND_EMAILCONFIRMED, $bogusCond, $bogusCond2 ],
			],
			[
				'bad-cond' => [
					'bogus',
					'bogus?',
					APCOND_EMAILCONFIRMED,
					$bogusCond,
					$bogusCond2
				],
			]
		];
		yield 'bad-cond-4' => [
			[
				'bad-cond' => [ '&',
					'bogus1',
					'bogus2',
					APCOND_EMAILCONFIRMED,
					$bogusCond,
					$bogusCond2,
				],
			],
			[
				'bad-cond' => [
					'operand' => '&',
					0 => 'bogus1',
					1 => 'bogus2',
					2 => [
						'condname' => 'APCOND_EMAILCONFIRMED',
						'params' => []
					],
					3 => [
						'condname' => false,
						'params' => []
					],
					4 => [
						'condname' => false,
						'params' => []
					]
				]
			]
		];
	}

	public function testAutopromoteOnceDefault() {
		// PHP doesn't like empty nested arrays nested in arrays...
		$value = [
			'onEdit' => [],
			'onView' => [],
		];
		$this->testAutopromoteOnce( $value, $value );
	}

	/**
	 * @dataProvider provideAutopromoteOnce
	 */
	public function testAutopromoteOnce( $config, $expected ) {
		$this->overrideConfigValues( [
			MainConfigNames::AutopromoteOnce => $config,
			MainConfigNames::AutoConfirmCount => 10,
			MainConfigNames::AutoConfirmAge => 345600,
		] );
		$this->assertSame( $expected, $this->doQuery( 'autopromoteonce' ) );
	}

	public static function provideAutopromoteOnce() {
		yield 'simple' => [
			[
				'onEdit' => [
					// edit count >= 10 and age >= 4 days
					'simple' => [ '&',
						[ APCOND_EDITCOUNT, 10 ],
						[ APCOND_AGE, 345600 ],
					],
				],
			],
			[
				'onEdit' => [
					'simple' => [
						'operand' => '&',
						0 => [
							'condname' => 'APCOND_EDITCOUNT',
							'params' => [ 10 ]
						],
						1 => [
							'condname' => 'APCOND_AGE',
							'params' => [ 345600 ]
						]
					]
				]
			]
		];
	}
}
