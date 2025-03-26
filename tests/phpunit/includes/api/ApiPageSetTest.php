<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiPageSet;
use MediaWiki\Api\ApiResult;
use MediaWiki\Context\RequestContext;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use RuntimeException;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @group medium
 * @group Database
 * @covers \MediaWiki\Api\ApiPageSet
 */
class ApiPageSetTest extends ApiTestCase {
	use DummyServicesTrait;

	public static function provideRedirectMergePolicy() {
		return [
			'By default nothing is merged' => [
				null,
				[]
			],

			'A simple merge policy adds the redirect data in' => [
				static function ( $current, $new ) {
					if ( !isset( $current['index'] ) || $new['index'] < $current['index'] ) {
						$current['index'] = $new['index'];
					}
					return $current;
				},
				[ 'index' => 1 ],
			],
		];
	}

	/**
	 * @dataProvider provideRedirectMergePolicy
	 */
	public function testRedirectMergePolicyWithArrayResult( $mergePolicy, $expect ) {
		[ $target, $pageSet ] = $this->createPageSetWithRedirect();
		$pageSet->setRedirectMergePolicy( $mergePolicy );
		$result = [
			$target->getArticleID() => []
		];
		$pageSet->populateGeneratorData( $result );
		$this->assertEquals( $expect, $result[$target->getArticleID()] );
	}

	/**
	 * @dataProvider provideRedirectMergePolicy
	 */
	public function testRedirectMergePolicyWithApiResult( $mergePolicy, $expect ) {
		[ $target, $pageSet ] = $this->createPageSetWithRedirect();
		$pageSet->setRedirectMergePolicy( $mergePolicy );
		$result = new ApiResult( false );
		$result->addValue( null, 'pages', [
			$target->getArticleID() => []
		] );
		$pageSet->populateGeneratorData( $result, [ 'pages' ] );
		$this->assertEquals(
			$expect,
			$result->getResultData( [ 'pages', $target->getArticleID() ] )
		);
	}

	private function newApiPageSet( $reqParams = [] ) {
		$request = new FauxRequest( $reqParams );
		$context = new RequestContext();
		$context->setRequest( $request );

		$main = new ApiMain( $context );
		$pageSet = new ApiPageSet( $main );

		return $pageSet;
	}

	protected function createPageSetWithRedirect( $targetContent = 'api page set test' ) {
		$target = Title::makeTitle( NS_MAIN, 'UTRedirectTarget' );
		$sourceA = Title::makeTitle( NS_MAIN, 'UTRedirectSourceA' );
		$sourceB = Title::makeTitle( NS_MAIN, 'UTRedirectSourceB' );
		$this->editPage( 'UTRedirectTarget', $targetContent );
		$this->editPage( 'UTRedirectSourceA', '#REDIRECT [[UTRedirectTarget]]' );
		$this->editPage( 'UTRedirectSourceB', '#REDIRECT [[UTRedirectTarget]]' );

		$pageSet = $this->newApiPageSet( [ 'redirects' => 1 ] );

		$pageSet->setGeneratorData( $sourceA, [ 'index' => 1 ] );
		$pageSet->setGeneratorData( $sourceB, [ 'index' => 3 ] );
		$pageSet->populateFromTitles( [ $sourceA, $sourceB ] );

		return [ $target, $pageSet ];
	}

	public function testRedirectMergePolicyRedirectLoop() {
		$redirectOneTitle = 'ApiPageSetTestRedirectOne';
		$redirectTwoTitle = 'ApiPageSetTestRedirectTwo';
		$this->editPage( $redirectOneTitle, "#REDIRECT [[$redirectTwoTitle]]" );
		$this->editPage( $redirectTwoTitle, "#REDIRECT [[$redirectOneTitle]]" );
		[ $target, $pageSet ] = $this->createPageSetWithRedirect(
			"#REDIRECT [[$redirectOneTitle]]"
		);
		$pageSet->setRedirectMergePolicy( static function ( $cur, $new ) {
			throw new RuntimeException( 'unreachable, no merge when target is redirect loop' );
		} );
		// This could infinite loop in a bugged impl, but php doesn't offer
		// a great way to time constrain this.
		$result = new ApiResult( false );
		$pageSet->populateGeneratorData( $result );
		// Assert something, mostly we care that the above didn't infinite loop.
		// This verifies the page set followed our redirect chain and saw the loop.
		$this->assertEqualsCanonicalizing(
			[
				'UTRedirectSourceA', 'UTRedirectSourceB', 'UTRedirectTarget',
				$redirectOneTitle, $redirectTwoTitle,
			],
			array_map( static function ( $x ) {
				return $x->getDBkey();
			}, $pageSet->getPages() )
		);
	}

	public function testHandleNormalization() {
		$pageSet = $this->newApiPageSet( [ 'titles' => "a|B|a\xcc\x8a" ] );
		$pageSet->execute();

		$this->assertSame(
			[ 0 => [ 'A' => -1, 'B' => -2, 'Å' => -3 ] ],
			$pageSet->getAllTitlesByNamespace()
		);
		$this->assertSame(
			[
				[ 'fromencoded' => true, 'from' => 'a%CC%8A', 'to' => 'å' ],
				[ 'fromencoded' => false, 'from' => 'a', 'to' => 'A' ],
				[ 'fromencoded' => false, 'from' => 'å', 'to' => 'Å' ],
			],
			$pageSet->getNormalizedTitlesAsResult()
		);
	}

	public static function provideConversionWithRedirects() {
		return [
			'convert, redirect, convert' => [
				[
					'Esttay 1' => '#REDIRECT [[Test 2]]',
					'Esttay 2' => '',
				],
				[ 'titles' => 'Test 1', 'converttitles' => 1, 'redirects' => 1 ],
				[
					[ 'from' => 'Test 1', 'to' => 'Esttay 1' ],
					[ 'from' => 'Test 2', 'to' => 'Esttay 2' ]
				],
				[ [ 'from' => 'Esttay 1', 'to' => 'Test 2' ] ],
			],

			'redirect, convert, redirect' => [
				[
					'Esttay 1' => '#REDIRECT [[Test 2]]',
					'Esttay 2' => '#REDIRECT [[Esttay 3]]',
				],
				[ 'titles' => 'Esttay 1', 'converttitles' => 1, 'redirects' => 1 ],
				[ [ 'from' => 'Test 2', 'to' => 'Esttay 2' ] ],
				[
					[ 'from' => 'Esttay 1', 'to' => 'Test 2' ],
					[ 'from' => 'Esttay 2', 'to' => 'Esttay 3' ]
				],
			],

			'self-redirect to variant, with converttitles' => [
				[ 'Esttay' => '#REDIRECT [[Test]]' ],
				[ 'titles' => 'Esttay', 'converttitles' => 1, 'redirects' => 1 ],
				[ [ 'from' => 'Test', 'to' => 'Esttay' ] ],
				[ [ 'from' => 'Esttay', 'to' => 'Test' ] ],
			],

			'self-redirect to variant, without converttitles' => [
				[ 'Esttay' => '#REDIRECT [[Test]]' ],
				[ 'titles' => 'Esttay', 'redirects' => 1 ],
				[],
				[ [ 'from' => 'Esttay', 'to' => 'Test' ] ],
			],
		];
	}

	/**
	 * @dataProvider provideConversionWithRedirects
	 */
	public function testHandleConversionWithRedirects( $pages, $params, $expectedConversion, $expectedRedirects ) {
		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, true );
		foreach ( $pages as $title => $content ) {
			$this->editPage( $title, $content );
		}

		$pageSet = $this->newApiPageSet( $params );
		$pageSet->execute();

		$this->assertSame( $expectedConversion, $pageSet->getConvertedTitlesAsResult() );
		$this->assertSame( $expectedRedirects, $pageSet->getRedirectTitlesAsResult() );
	}

	public function testSpecialRedirects() {
		$id1 = $this->editPage( 'UTApiPageSet', 'UTApiPageSet in the default language' )
			->getNewRevision()->getPageId();
		$id2 = $this->editPage( 'UTApiPageSet/de', 'UTApiPageSet in German' )
			->getNewRevision()->getPageId();

		$user = $this->getTestUser()->getUser();
		$userName = $user->getName();
		$userDbkey = str_replace( ' ', '_', $userName );
		$request = new FauxRequest( [
			'titles' => implode( '|', [
				'Special:MyContributions',
				'Special:MyPage',
				'Special:MyTalk/subpage',
				'Special:MyLanguage/UTApiPageSet',
			] ),
		] );
		$context = new RequestContext();
		$context->setRequest( $request );
		$context->setUser( $user );

		$main = new ApiMain( $context );
		$pageSet = new ApiPageSet( $main );
		$pageSet->execute();

		$this->assertEquals( [
		], $pageSet->getRedirectTitlesAsResult() );
		$this->assertEquals( [
			[ 'ns' => NS_SPECIAL, 'title' => 'Special:MyContributions', 'special' => true ],
			[ 'ns' => NS_SPECIAL, 'title' => 'Special:MyPage', 'special' => true ],
			[ 'ns' => NS_SPECIAL, 'title' => 'Special:MyTalk/subpage', 'special' => true ],
			[ 'ns' => NS_SPECIAL, 'title' => 'Special:MyLanguage/UTApiPageSet', 'special' => true ],
		], $pageSet->getInvalidTitlesAndRevisions() );
		$this->assertEquals( [
		], $pageSet->getAllTitlesByNamespace() );

		$request->setVal( 'redirects', 1 );
		$main = new ApiMain( $context );
		$pageSet = new ApiPageSet( $main );
		$pageSet->execute();

		$this->assertEquals( [
			[ 'from' => 'Special:MyPage', 'to' => "User:$userName" ],
			[ 'from' => 'Special:MyTalk/subpage', 'to' => "User talk:$userName/subpage" ],
			[ 'from' => 'Special:MyLanguage/UTApiPageSet', 'to' => 'UTApiPageSet' ],
		], $pageSet->getRedirectTitlesAsResult() );
		$this->assertEquals( [
			[ 'ns' => NS_SPECIAL, 'title' => 'Special:MyContributions', 'special' => true ],
			[ 'ns' => NS_USER, 'title' => "User:$userName", 'missing' => true ],
			[ 'ns' => NS_USER_TALK, 'title' => "User talk:$userName/subpage", 'missing' => true ],
		], $pageSet->getInvalidTitlesAndRevisions() );
		$this->assertEquals( [
			NS_MAIN => [ 'UTApiPageSet' => $id1 ],
			NS_USER => [ $userDbkey => -2 ],
			NS_USER_TALK => [ "$userDbkey/subpage" => -3 ],
		], $pageSet->getAllTitlesByNamespace() );

		$context->setLanguage( 'de' );
		$main = new ApiMain( $context );
		$pageSet = new ApiPageSet( $main );
		$pageSet->execute();

		$this->assertEquals( [
			[ 'from' => 'Special:MyPage', 'to' => "User:$userName" ],
			[ 'from' => 'Special:MyTalk/subpage', 'to' => "User talk:$userName/subpage" ],
			[ 'from' => 'Special:MyLanguage/UTApiPageSet', 'to' => 'UTApiPageSet/de' ],
		], $pageSet->getRedirectTitlesAsResult() );
		$this->assertEquals( [
			[ 'ns' => NS_SPECIAL, 'title' => 'Special:MyContributions', 'special' => true ],
			[ 'ns' => NS_USER, 'title' => "User:$userName", 'missing' => true ],
			[ 'ns' => NS_USER_TALK, 'title' => "User talk:$userName/subpage", 'missing' => true ],
		], $pageSet->getInvalidTitlesAndRevisions() );
		$this->assertEquals( [
			NS_MAIN => [ 'UTApiPageSet/de' => $id2 ],
			NS_USER => [ $userDbkey => -2 ],
			NS_USER_TALK => [ "$userDbkey/subpage" => -3 ],
		], $pageSet->getAllTitlesByNamespace() );
	}

	/**
	 * Test that ApiPageSet is calling GenderCache for provided user names to prefill the
	 * GenderCache and avoid a performance issue when loading each users' gender on its own.
	 * The test is setting the "missLimit" to 0 on the GenderCache to trigger misses logic.
	 * When the "misses" property is no longer 0 at the end of the test,
	 * something was requested which is not part of the cache. Than the test is failing.
	 */
	public function testGenderCaching() {
		// Create the test user now so that the cache will be empty later
		$this->getTestSysop()->getUser();
		// Set up the user namespace to have gender aliases to trigger the gender cache
		$this->overrideConfigValue(
			MainConfigNames::ExtraGenderNamespaces,
			[ NS_USER => [ 'male' => 'Male', 'female' => 'Female' ] ]
		);
		$this->overrideMwServices();

		// User names to test with - it is not needed that the user exists in the database
		// to trigger gender cache
		$userNames = [
			'Female',
			'Unknown',
			'Male',
		];

		// Prepare the gender cache for testing - this is a fresh instance due to service override
		$genderCache = TestingAccessWrapper::newFromObject(
			$this->getServiceContainer()->getGenderCache()
		);
		$genderCache->missLimit = 0;

		// Do an api request to trigger ApiPageSet code
		$this->doApiRequest( [
			'action' => 'query',
			'titles' => 'User:' . implode( '|User:', $userNames ),
		] );

		$this->assertSame( 0, $genderCache->misses,
			'ApiPageSet does not prefill the gender cache correctly' );
		$this->assertEquals( $userNames, array_keys( $genderCache->cache ),
			'ApiPageSet does not prefill all users into the gender cache' );
	}

	public function testPopulateFromTitles() {
		$interwikiLookup = $this->getDummyInterwikiLookup( [ 'acme' ] );
		$this->setService( 'InterwikiLookup', $interwikiLookup );

		$this->getExistingTestPage( 'ApiPageSetTest_existing' )->getTitle();
		$this->getExistingTestPage( 'ApiPageSetTest_redirect_target' )->getTitle();
		$this->getNonexistingTestPage( 'ApiPageSetTest_missing' )->getTitle();
		$redirectTitle = $this->getExistingTestPage( 'ApiPageSetTest_redirect' )->getTitle();
		$this->editPage( $redirectTitle, '#REDIRECT [[ApiPageSetTest_redirect_target]]' );

		$input = [
			'existing' => 'ApiPageSetTest_existing',
			'missing' => 'ApiPageSetTest_missing',
			'invalid' => 'ApiPageSetTest|invalid',
			'redirect' => 'ApiPageSetTest_redirect',
			'special' => 'Special:BlankPage',
			'interwiki' => 'acme:ApiPageSetTest',
		];

		$pageSet = $this->newApiPageSet( [ 'redirects' => 1 ] );
		$pageSet->populateFromTitles( $input );

		$expectedPages = [
			new TitleValue( NS_MAIN, 'ApiPageSetTest_existing' ),
			new TitleValue( NS_MAIN, 'ApiPageSetTest_redirect' ),
			new TitleValue( NS_MAIN, 'ApiPageSetTest_missing' ),

			// the redirect page and the target are included!
			new TitleValue( NS_MAIN, 'ApiPageSetTest_redirect_target' ),
		];
		$this->assertLinkTargets( PageIdentity::class, $expectedPages, $pageSet->getPages() );

		$expectedGood = [
			new TitleValue( NS_MAIN, 'ApiPageSetTest_existing' ),
			new TitleValue( NS_MAIN, 'ApiPageSetTest_redirect_target' )
		];
		$this->assertLinkTargets( PageIdentity::class, $expectedGood, $pageSet->getGoodPages() );

		$expectedMissing = [ new TitleValue( NS_MAIN, 'ApiPageSetTest_missing' ) ];
		$this->assertLinkTargets(
			PageIdentity::class,
			$expectedMissing,
			$pageSet->getMissingPages()
		);
		$this->assertSame(
			[ NS_MAIN => [ 'ApiPageSetTest_missing' => -3 ] ],
			$pageSet->getMissingTitlesByNamespace()
		);

		$expectedGoodAndMissing = array_merge( $expectedGood, $expectedMissing );
		$this->assertLinkTargets(
			PageIdentity::class,
			$expectedGoodAndMissing,
			$pageSet->getGoodAndMissingPages()
		);

		$expectedSpecial = [ new TitleValue( NS_SPECIAL, 'BlankPage' ) ];
		$this->assertLinkTargets( PageReference::class, $expectedSpecial, $pageSet->getSpecialPages() );

		$expectedRedirects = [
			'ApiPageSetTest redirect' => new TitleValue(
				NS_MAIN, 'ApiPageSetTest_redirect_target'
			)
		];
		$this->assertLinkTargets( LinkTarget::class, $expectedRedirects, $pageSet->getRedirectTargets() );

		$this->assertSame( [ 'acme:ApiPageSetTest' => 'acme' ], $pageSet->getInterwikiTitles() );
		$this->assertSame(
			[ [ 'title' => 'acme:ApiPageSetTest', 'iw' => 'acme' ] ],
			$pageSet->getInterwikiTitlesAsResult()
		);

		$this->assertSame(
			[ -1 => [
					'title' => 'ApiPageSetTest|invalid',
					'invalidreason' => 'The requested page title contains invalid characters: "|".'
			] ],
			$pageSet->getInvalidTitlesAndReasons()
		);
	}

	/**
	 * @param string $type
	 * @param LinkTarget[] $expected
	 * @param LinkTarget[]|PageReference[] $actual
	 */
	private function assertLinkTargets( $type, $expected, $actual ) {
		reset( $actual );
		foreach ( $expected as $expKey => $exp ) {
			$act = current( $actual );
			$this->assertNotFalse( $act, 'missing entry at key $expKey: ' . $exp );

			$actKey = key( $actual );
			next( $actual );

			if ( !is_int( $expKey ) ) {
				$this->assertSame( $expKey, $actKey );
			}
			$this->assertSame( $exp->getNamespace(), $act->getNamespace() );
			$this->assertSame( $exp->getDBkey(), $act->getDBkey() );

			$this->assertInstanceOf( $type, $act );

			if ( $actual instanceof LinkTarget ) {
				$this->assertSame( $exp->getFragment(), $act->getFragment() );
				$this->assertSame( $exp->getInterwiki(), $act->getInterwiki() );
			}
		}

		$act = current( $actual );
		$this->assertFalse( $act, 'extra entry: ' . $act );
	}
}
