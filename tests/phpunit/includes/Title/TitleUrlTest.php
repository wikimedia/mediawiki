<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Title\Title;

/**
 * @group Database
 *
 * @covers \MediaWiki\Title\Title::getLocalURL
 * @covers \MediaWiki\Title\Title::getLinkURL
 * @covers \MediaWiki\Title\Title::getFullURL
 * @covers \MediaWiki\Title\Title::getFullUrlForRedirect
 * @covers \MediaWiki\Title\Title::getCanonicalURL
 * @covers \MediaWiki\Title\Title::getInternalURL
 */
class TitleUrlTest extends MediaWikiLangTestCase {
	use DummyServicesTrait;

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValues( [
			MainConfigNames::Server => '//m.xx.wiki.test',
			MainConfigNames::CanonicalServer => 'https://xx.wiki.test',
			MainConfigNames::InternalServer => 'http://app23.internal',
			MainConfigNames::ArticlePath => '/wiki/$1',
			MainConfigNames::ExternalInterwikiFragmentMode => 'html5',
			MainConfigNames::FragmentMode => [ 'legacy', 'html5' ],
			MainConfigNames::MainPageIsDomainRoot => true,
			MainConfigNames::ActionPaths => [ 'edit' => '/m/edit/$1' ],
			MainConfigNames::VariantArticlePath => '/$2/$1',
			MainConfigNames::ScriptPath => '/m',
			MainConfigNames::Script => '/m/index.php',
			MainConfigNames::UsePigLatinVariant => true,
		] );

		// Some tests use interwikis - define valid prefixes and their configuration
		$interwikiLookup = $this->getDummyInterwikiLookup( [
			[ 'iw_prefix' => 'acme', 'iw_url' => 'https://acme.test/$1' ],
			[ 'iw_prefix' => 'yy', 'iw_url' => '//yy.wiki.test/wiki/$1', 'iw_local' => true ]
		] );
		$this->setService( 'InterwikiLookup', $interwikiLookup );

		$this->clearHooks( [
			'GetFullURL',
			'GetLocalURL__Article',
			'GetLocalURL__Internal',
			'GetLocalURL',
			'GetInternalURL',
			'GetCanonicalURL',
		] );
	}

	public function testUrlsForSimpleTitle() {
		$title = Title::makeTitle( NS_USER, 'Göatee' );
		$name = $title->getPrefixedURL();

		$query = [ 'x' => 'one two', 'y' => '#' ];
		$queryString = 'x=one+two&y=%23';

		// Test getLocalURL()
		$this->assertSame(
			'/wiki/' . $name,
			$title->getLocalURL(),
			'getLocalURL()'
		);
		$this->assertSame(
			'/m/index.php?title=' . $name . '&' . $queryString,
			$title->getLocalURL( $query ),
			'getLocalURL( array )'
		);
		$this->assertSame(
			'/m/index.php?title=' . $name . '&' . $queryString,
			$title->getLocalURL( $queryString ),
			'getLocalURL( string )'
		);

		// Test getFullURL()
		$this->assertSame(
			'//m.xx.wiki.test/wiki/' . $name,
			$title->getFullURL(),
			'getFullURL()'
		);
		$this->assertSame(
			'//m.xx.wiki.test/m/index.php?title=' . $name . '&' . $queryString,
			$title->getFullURL( $query ),
			'getFullURL( array )'
		);
		$this->assertSame(
			'https://xx.wiki.test/wiki/' . $name,
			$title->getFullURL( [], false, PROTO_CANONICAL ),
			'getFullURL() with PROTO_CANONICAL'
		);
		$this->assertSame(
			'http://m.xx.wiki.test/wiki/' . $name,
			$title->getFullURL( [], false, PROTO_HTTP ),
			'getFullURL() with PROTO_HTTP'
		);
		$this->assertSame(
			'http://app23.internal/wiki/' . $name,
			$title->getFullURL( [], false, PROTO_INTERNAL ),
			'getFullURL() with PROTO_INTERNAL'
		);

		// Test getFullUrlForRedirect()
		// Note that it uses PROTO_CURRENT by default, which is 'http' for tests
		$this->assertSame(
			'http://m.xx.wiki.test/wiki/' . $name,
			$title->getFullUrlForRedirect(),
			'getFullUrlForRedirect()'
		);
		$this->assertSame(
			'http://m.xx.wiki.test/m/index.php?title=' . $name . '&' . $queryString,
			$title->getFullUrlForRedirect( $query ),
			'getFullUrlForRedirect( array )'
		);
		$this->assertSame(
			'//m.xx.wiki.test/wiki/' . $name,
			$title->getFullUrlForRedirect( [], PROTO_RELATIVE ),
			'getFullUrlForRedirect() with PROTO_RELATIVE'
		);
		$this->assertSame(
			'https://xx.wiki.test/wiki/' . $name,
			$title->getFullUrlForRedirect( [], PROTO_CANONICAL ),
			'getFullUrlForRedirect() with PROTO_CANONICAL'
		);
		$this->assertSame(
			'http://app23.internal/wiki/' . $name,
			$title->getFullUrlForRedirect( [], PROTO_INTERNAL ),
			'getFullUrlForRedirect() with PROTO_INTERNAL'
		);

		// Test getLinkURL()
		$this->assertSame(
			'/wiki/' . $name,
			$title->getLinkURL(),
			'getLinkURL()'
		);
		$this->assertSame(
			'/m/index.php?title=' . $name . '&' . $queryString,
			$title->getLinkURL( $query ),
			'getLinkURL( array )'
		);
		$this->assertSame(
			'/m/index.php?title=' . $name . '&' . $queryString,
			$title->getLinkURL( $queryString ),
			'getLinkURL( string )'
		);
		$this->assertSame(
			'//m.xx.wiki.test/wiki/' . $name,
			$title->getLinkURL( [], false, PROTO_RELATIVE ),
			'getLinkURL() with PROTO_RELATIVE'
		);
		$this->assertSame(
			'http://m.xx.wiki.test/wiki/' . $name,
			$title->getLinkURL( [], false, PROTO_CURRENT ),
			'getLinkURL() with PROTO_CURRENT'
		);
		$this->assertSame(
			'https://m.xx.wiki.test/wiki/' . $name,
			$title->getLinkURL( [], false, PROTO_HTTPS ),
			'getLinkURL() with PROTO_HTTPS'
		);
		$this->assertSame(
			'https://xx.wiki.test/wiki/' . $name,
			$title->getLinkURL( [], false, PROTO_CANONICAL ),
			'getLinkURL() with PROTO_CANONICAL'
		);
		$this->assertSame(
			'http://app23.internal/wiki/' . $name,
			$title->getLinkURL( [], false, PROTO_INTERNAL ),
			'getLinkURL() with PROTO_INTERNAL'
		);
		$this->assertSame(
			'http://app23.internal/m/index.php?title=' . $name . '&' . $queryString,
			$title->getLinkURL( $query, false, PROTO_INTERNAL ),
			'getLinkURL( array ) with PROTO_INTERNAL'
		);

		// Test getInternalURL()
		$this->assertSame(
			'http://app23.internal/wiki/' . $name,
			$title->getInternalURL(),
			'getInternalURL()'
		);
		$this->assertSame(
			'http://app23.internal/m/index.php?title=' . $name . '&' . $queryString,
			$title->getInternalURL( $query ),
			'getInternalURL( array )'
		);
		$this->assertSame(
			'http://app23.internal/m/index.php?title=' . $name . '&' . $queryString,
			$title->getInternalURL( $queryString ),
			'getInternalURL( string )'
		);

		// Test getCanonicalURL()
		$this->assertSame(
			'https://xx.wiki.test/wiki/' . $name,
			$title->getCanonicalURL(),
			'getCanonicalURL()'
		);
		$this->assertSame(
			'https://xx.wiki.test/m/index.php?title=' . $name . '&' . $queryString,
			$title->getCanonicalURL( $query ),
			'getCanonicalURL( array )'
		);
		$this->assertSame(
			'https://xx.wiki.test/m/index.php?title=' . $name . '&' . $queryString,
			$title->getCanonicalURL( $queryString ),
			'getCanonicalURL( string )'
		);
	}

	public function testUrlsWithActionPath() {
		$title = Title::makeTitle( NS_USER, 'Göatee' );
		$name = $title->getPrefixedURL();

		$query1 = [ 'action' => 'edit' ];
		$query2 = [ 'x' => 'one two', 'y' => '#' ];
		$queryString = 'x=one+two&y=%23';

		// Test getLocalURL()
		$this->assertSame(
			'/m/edit/' . $name,
			$title->getLocalURL( $query1 ),
			'getLocalURL( array )'
		);
		$this->assertSame(
			'/m/edit/' . $name . '?' . $queryString,
			$title->getLocalURL( $query1 + $query2 ),
			'getLocalURL( array + array )'
		);

		// Test getFullURL()
		$this->assertSame(
			'//m.xx.wiki.test/m/edit/' . $name . '?' . $queryString,
			$title->getFullURL( $query1 + $query2 ),
			'getFullURL( array )'
		);

		// Test getFullUrlForRedirect()
		// Note that it uses PROTO_CURRENT by default, which is 'http' for tests
		$this->assertSame(
			'http://m.xx.wiki.test/m/edit/' . $name . '?' . $queryString,
			$title->getFullUrlForRedirect( $query1 + $query2 ),
			'getFullUrlForRedirect( array )'
		);

		// Test getLinkURL()
		$this->assertSame(
			'/m/edit/' . $name . '?' . $queryString,
			$title->getLinkURL( $query1 + $query2 ),
			'getLinkURL( array + array )'
		);

		// Test getInternalURL()
		$this->assertSame(
			'http://app23.internal/m/edit/' . $name . '?' . $queryString,
			$title->getInternalURL( $query1 + $query2 ),
			'getInternalURL( array )'
		);

		// Test getCanonicalURL()
		$this->assertSame(
			'https://xx.wiki.test/m/edit/' . $name . '?' . $queryString,
			$title->getCanonicalURL( $query1 + $query2 ),
			'getCanonicalURL( array )'
		);
	}

	public function testUrlsWithVariantPath() {
		$title = Title::makeTitle( NS_USER, 'Göatee' );
		$name = $title->getPrefixedURL();

		$query1 = [ 'variant' => 'en-x-piglatin' ];
		$query2 = [ 'x' => 'one two', 'y' => '#' ];
		$queryString = 'x=one+two&y=%23';

		// Test getLocalURL()
		$this->assertSame(
			'/en-x-piglatin/' . $name,
			$title->getLocalURL( $query1 ),
			'getLocalURL( array )'
		);
		$this->assertSame( // NOTE: this could as well apply the variant path
			'/m/index.php?title=' . $name . '&variant=en-x-piglatin&' . $queryString,
			$title->getLocalURL( $query1 + $query2 ),
			'getLocalURL( array + array )'
		);

		// Test getFullURL()
		$this->assertSame(
			'//m.xx.wiki.test/en-x-piglatin/' . $name,
			$title->getFullURL( $query1 ),
			'getFullURL( array )'
		);

		// Test getFullUrlForRedirect()
		// Note that it uses PROTO_CURRENT by default, which is 'http' for tests
		$this->assertSame(
			'http://m.xx.wiki.test/en-x-piglatin/' . $name,
			$title->getFullUrlForRedirect( $query1 ),
			'getFullUrlForRedirect( array )'
		);

		// Test getLinkURL()
		$this->assertSame(
			'/en-x-piglatin/' . $name,
			$title->getLinkURL( $query1 ),
			'getLinkURL( array + array )'
		);

		// Test getInternalURL()
		$this->assertSame(
			'http://app23.internal/en-x-piglatin/' . $name,
			$title->getInternalURL( $query1 ),
			'getInternalURL( array )'
		);

		// Test getCanonicalURL()
		$this->assertSame(
			'https://xx.wiki.test/en-x-piglatin/' . $name,
			$title->getCanonicalURL( $query1 ),
			'getCanonicalURL( array )'
		);
	}

	public function testUrlsForMainPage() {
		$title = Title::newMainPage();
		$name = $title->getPrefixedURL();

		$query = [ 'x' => 'one two', 'y' => '#' ];
		$queryString = 'x=one+two&y=%23';

		// Test getLocalURL()
		$this->assertSame(
			'/',
			$title->getLocalURL(),
			'getLocalURL()'
		);
		$this->assertSame(
			'/m/index.php?title=' . $name . '&' . $queryString,
			$title->getLocalURL( $query ),
			'getLocalURL( array )'
		);

		// Test getFullURL()
		$this->assertSame(
			'//m.xx.wiki.test/',
			$title->getFullURL(),
			'getFullURL()'
		);
		$this->assertSame(
			'//m.xx.wiki.test/m/index.php?title=' . $name . '&' . $queryString,
			$title->getFullURL( $query ),
			'getFullURL( array )'
		);

		// Test getFullUrlForRedirect()
		// Note that it uses PROTO_CURRENT by default, which is 'http' for tests
		$this->assertSame(
			'http://m.xx.wiki.test/',
			$title->getFullUrlForRedirect(),
			'getFullUrlForRedirect()'
		);
		$this->assertSame(
			'http://m.xx.wiki.test/m/index.php?title=' . $name . '&' . $queryString,
			$title->getFullUrlForRedirect( $query ),
			'getFullUrlForRedirect( array )'
		);

		// Test getLinkURL()
		$this->assertSame(
			'/',
			$title->getLinkURL(),
			'getLinkURL()'
		);
		$this->assertSame(
			'/m/index.php?title=' . $name . '&' . $queryString,
			$title->getLinkURL( $query ),
			'getLinkURL( array )'
		);

		// Test getInternalURL()
		$this->assertSame(
			'http://app23.internal/',
			$title->getInternalURL(),
			'getInternalURL()'
		);
		$this->assertSame(
			'http://app23.internal/m/index.php?title=' . $name . '&' . $queryString,
			$title->getInternalURL( $query ),
			'getInternalURL( array )'
		);

		// Test getCanonicalURL()
		$this->assertSame(
			'https://xx.wiki.test/',
			$title->getCanonicalURL(),
			'getCanonicalURL()'
		);
		$this->assertSame(
			'https://xx.wiki.test/m/index.php?title=' . $name . '&' . $queryString,
			$title->getCanonicalURL( $query ),
			'getCanonicalURL( array )'
		);
	}

	public function testUrlsForTitleWithFragment() {
		$title = Title::makeTitle( NS_USER, 'Göatee', 'Sectiön' );
		$name = $title->getPrefixedURL();
		$fragment = 'Secti.C3.B6n';

		$query = [ 'x' => 'one two', 'y' => '#' ];
		$queryString = 'x=one+two&y=%23';

		// Test getLocalURL() ignores fragment
		$this->assertSame(
			'/wiki/' . $name,
			$title->getLocalURL(),
			'getLocalURL()'
		);
		$this->assertSame(
			'/m/index.php?title=' . $name . '&' . $queryString,
			$title->getLocalURL( $query ),
			'getLocalURL( array )'
		);

		// Test getFullURL() includes fragment
		$this->assertSame(
			'//m.xx.wiki.test/wiki/' . $name . '#' . $fragment,
			$title->getFullURL(),
			'getFullURL()'
		);
		$this->assertSame(
			'//m.xx.wiki.test/m/index.php?title=' . $name . '&' . $queryString . '#' . $fragment,
			$title->getFullURL( $query ),
			'getFullURL( array )'
		);
		$this->assertSame(
			'https://xx.wiki.test/wiki/' . $name . '#' . $fragment,
			$title->getFullURL( [], false, PROTO_CANONICAL ),
			'getFullURL() with PROTO_CANONICAL'
		);

		// Test getFullUrlForRedirect() includes fragment
		$this->assertSame(
			'http://m.xx.wiki.test/wiki/' . $name . '#' . $fragment,
			$title->getFullUrlForRedirect(),
			'getFullUrlForRedirect()'
		);
		$this->assertSame(
			'http://m.xx.wiki.test/m/index.php?title=' . $name . '&' . $queryString . '#' . $fragment,
			$title->getFullUrlForRedirect( $query ),
			'getFullUrlForRedirect( array )'
		);
		$this->assertSame(
			'//m.xx.wiki.test/wiki/' . $name . '#' . $fragment,
			$title->getFullUrlForRedirect( [], PROTO_RELATIVE ),
			'getFullUrlForRedirect() with PROTO_RELATIVE'
		);

		// Test getLinkURL() includes fragment
		$this->assertSame(
			'/wiki/' . $name . '#' . $fragment,
			$title->getLinkURL(),
			'getLinkURL()'
		);
		$this->assertSame(
			'/m/index.php?title=' . $name . '&' . $queryString . '#' . $fragment,
			$title->getLinkURL( $query ),
			'getLinkURL( array )'
		);
		$this->assertSame(
			'//m.xx.wiki.test/wiki/' . $name . '#' . $fragment,
			$title->getLinkURL( [], false, PROTO_RELATIVE ),
			'getLinkURL() with PROTO_RELATIVE'
		);

		// Test getInternalURL() ignores fragment
		$this->assertSame(
			'http://app23.internal/wiki/' . $name,
			$title->getInternalURL(),
			'getInternalURL()'
		);
		$this->assertSame(
			'http://app23.internal/m/index.php?title=' . $name . '&' . $queryString,
			$title->getInternalURL( $query ),
			'getInternalURL( array )'
		);

		// Test getCanonicalURL() includes fragment
		$this->assertSame(
			'https://xx.wiki.test/wiki/' . $name . '#' . $fragment,
			$title->getCanonicalURL(),
			'getCanonicalURL()'
		);
		$this->assertSame(
			'https://xx.wiki.test/m/index.php?title=' . $name . '&' . $queryString . '#' . $fragment,
			$title->getCanonicalURL( $query ),
			'getCanonicalURL( array )'
		);

		// Test $wgFragmentMode
		$this->overrideConfigValue( MainConfigNames::FragmentMode, [ 'html5', 'legacy' ] );
		$fragment = 'Sectiön';

		$this->assertSame(
			'//m.xx.wiki.test/wiki/' . $name . '#' . $fragment,
			$title->getFullURL(),
			'getFullURL()'
		);
		$this->assertSame(
			'/wiki/' . $name . '#' . $fragment,
			$title->getLinkURL(),
			'getLinkURL()'
		);
	}

	public function testUrlsWithFragmentOnly() {
		$title = Title::makeTitle( NS_MAIN, '', 'Jümp' );
		$fragment = Sanitizer::escapeIdForLink( 'Jümp' );

		$query = [ 'x' => 'one two', 'y' => '#' ];
		$queryString = 'x=one+two&y=%23';

		// Test getLocalURL() ignores fragment
		$this->assertSame( // NOTE: not useful, may change!
			'/wiki/',
			$title->getLocalURL(),
			'getLocalURL()'
		);
		$this->assertSame( // NOTE: not useful, may change!
			'/m/index.php?title=&' . $queryString,
			$title->getLocalURL( $query ),
			'getLocalURL( array )'
		);

		// Test getFullURL() includes fragment
		$this->assertSame( // NOTE: not useful, may change!
			'//m.xx.wiki.test/wiki/#' . $fragment,
			$title->getFullURL(),
			'getFullURL()'
		);
		$this->assertSame( // NOTE: not useful, may change!
			'//m.xx.wiki.test/m/index.php?title=&' . $queryString . '#' . $fragment,
			$title->getFullURL( $query ),
			'getFullURL( array )'
		);
		$this->assertSame( // NOTE: not useful, may change!
			'https://xx.wiki.test/wiki/#' . $fragment,
			$title->getFullURL( [], false, PROTO_CANONICAL ),
			'getFullURL() with PROTO_CANONICAL'
		);

		// Test getFullUrlForRedirect() includes fragment
		$this->assertSame( // NOTE: not useful, may change!
			'http://m.xx.wiki.test/wiki/#' . $fragment,
			$title->getFullUrlForRedirect(),
			'getFullUrlForRedirect()'
		);
		$this->assertSame( // NOTE: not useful, may change!
			'http://m.xx.wiki.test/m/index.php?title=&' . $queryString . '#' . $fragment,
			$title->getFullUrlForRedirect( $query ),
			'getFullUrlForRedirect( array )'
		);
		$this->assertSame( // NOTE: not useful, may change!
			'//m.xx.wiki.test/wiki/#' . $fragment,
			$title->getFullUrlForRedirect( [], PROTO_RELATIVE ),
			'getFullUrlForRedirect() with PROTO_RELATIVE'
		);

		// Test getLinkURL()
		$this->assertSame( // NOTE: this is the one useful way to handle a fragment jump
			'#' . $fragment,
			$title->getLinkURL(),
			'getLinkURL()'
		);
		$this->assertSame(
			'#' . $fragment, // NOTE: not useful, may change!
			$title->getLinkURL( $query ),
			'getLinkURL( array )'
		);
		$this->assertSame( // NOTE: not useful, may change!
			'//m.xx.wiki.test/wiki/#' . $fragment,
			$title->getLinkURL( [], false, PROTO_RELATIVE ),
			'getLinkURL() with PROTO_RELATIVE'
		);
		$this->assertSame( // NOTE: not useful, may change!
			'https://xx.wiki.test/m/index.php?title=&' . $queryString . '#' . $fragment,
			$title->getLinkURL( $query, false, PROTO_CANONICAL ),
			'getLinkURL() with PROTO_RELATIVE'
		);

		// Test getInternalURL() ignores fragment
		$this->assertSame( // NOTE: not useful, may change!
			'http://app23.internal/wiki/',
			$title->getInternalURL(),
			'getInternalURL()'
		);
		$this->assertSame( // NOTE: not useful, may change!
			'http://app23.internal/m/index.php?title=&' . $queryString,
			$title->getInternalURL( $query ),
			'getInternalURL( array )'
		);

		// Test getCanonicalURL() includes fragment
		$this->assertSame( // NOTE: not useful, may change!
			'https://xx.wiki.test/wiki/#' . $fragment,
			$title->getCanonicalURL(),
			'getCanonicalURL()'
		);
		$this->assertSame( // NOTE: not useful, may change!
			'https://xx.wiki.test/m/index.php?title=&' . $queryString . '#' . $fragment,
			$title->getCanonicalURL( $query ),
			'getCanonicalURL( array )'
		);
	}

	public function testUrlsForUnknownInterwiki() {
		// the "xyzzy" prefix is not known
		$title = Title::makeTitle( NS_MAIN, 'Foobär', '', 'xyzzy' );
		$name = $title->getPrefixedURL();

		$query = [ 'x' => 'one two', 'y' => '#' ];
		$queryString = 'x=one+two&y=%23';

		// Test getLocalURL()
		$this->assertSame(
			'/wiki/' . $name,
			$title->getLocalURL(),
			'getLocalURL()'
		);
		$this->assertSame(
			'/m/index.php?title=' . $name . '&' . $queryString,
			$title->getLocalURL( $query ),
			'getLocalURL( array )'
		);

		// Test getFullURL()
		$this->assertSame(
			'//m.xx.wiki.test/wiki/' . $name,
			$title->getFullURL(),
			'getFullURL()'
		);
		$this->assertSame(
			'//m.xx.wiki.test/m/index.php?title=' . $name . '&' . $queryString,
			$title->getFullURL( $query ),
			'getFullURL( array )'
		);
		$this->assertSame(
			'https://m.xx.wiki.test/wiki/' . $name,
			$title->getFullURL( [], false, PROTO_HTTPS ),
			'getFullURL( array ) with PROTO_HTTPS'
		);

		// Test getFullUrlForRedirect()
		// Note that it uses PROTO_CURRENT by default, which is 'http' for tests
		$this->assertSame(
			'http://m.xx.wiki.test/wiki/Special:GoToInterwiki/' . $name,
			$title->getFullUrlForRedirect(),
			'getFullUrlForRedirect()'
		);
		$this->assertSame(
			'http://m.xx.wiki.test/m/index.php?title=Special:GoToInterwiki/'
				. $name . '&' . $queryString,
			$title->getFullUrlForRedirect( $query ),
			'getFullUrlForRedirect( array )'
		);

		// Test getLinkURL()
		$this->assertSame( // NOTE: could also just be '/wiki/...'
			'//m.xx.wiki.test/wiki/' . $name,
			$title->getLinkURL(),
			'getLinkURL()'
		);
		$this->assertSame( // NOTE: could also just be '/m/...'
			'//m.xx.wiki.test/m/index.php?title=' . $name . '&' . $queryString,
			$title->getLinkURL( $query ),
			'getLinkURL( array )'
		);

		// Test getInternalURL()
		$this->assertSame(
			'http://app23.internal/wiki/' . $name,
			$title->getInternalURL(),
			'getInternalURL()'
		);
		$this->assertSame(
			'http://app23.internal/m/index.php?title=' . $name . '&' . $queryString,
			$title->getInternalURL( $query ),
			'getInternalURL( array )'
		);

		// Test getCanonicalURL()
		$this->assertSame(
			'https://xx.wiki.test/wiki/' . $name,
			$title->getCanonicalURL(),
			'getCanonicalURL()'
		);
		$this->assertSame(
			'https://xx.wiki.test/m/index.php?title=' . $name . '&' . $queryString,
			$title->getCanonicalURL( $query ),
			'getCanonicalURL( array )'
		);
	}

	public function testUrlsForExternalInterwikiWithFragment() {
		// the "acme" prefix is a known external interwiki prefix
		$title = Title::makeTitle( NS_MAIN, 'fröbnitz/foo+bar', 'Sectiön', 'acme' );
		$section = 'Sectiön';

		// NOTE: The "/" should remain unencoded even in interwiki links
		$name = wfUrlencode( $title->getDBkey() );

		$query = [ 'x' => 'one two+three', 'y' => '#' ];
		$queryString = 'x=one+two%2Bthree&y=%23';

		// Test getLocalURL()
		$this->assertSame(
			'https://acme.test/' . $name,
			$title->getLocalURL(),
			'getLocalURL()'
		);
		$this->assertSame(
			'https://acme.test/' . $name . '?' . $queryString,
			$title->getLocalURL( $query ),
			'getLocalURL( array )'
		);

		// Test getFullURL()
		$this->assertSame(
			'https://acme.test/' . $name . '#' . $section,
			$title->getFullURL(),
			'getFullURL()'
		);
		$this->assertSame(
			'https://acme.test/' . $name . '?' . $queryString . '#' . $section,
			$title->getFullURL( $query ),
			'getFullURL( array )'
		);
		$this->assertSame( // should not trigger action path
			'https://acme.test/' . $name . '?action=edit#' . $section,
			$title->getFullURL( [ 'action' => 'edit' ] ),
			'getFullURL( array )'
		);
		$this->assertSame( // should not trigger variant path
			'https://acme.test/' . $name . '?variant=en-x-piglatin#' . $section,
			$title->getFullURL( [ 'variant' => 'en-x-piglatin' ] ),
			'getFullURL( array )'
		);

		// Test getFullUrlForRedirect()
		// Note that it uses PROTO_CURRENT by default, which is 'http' for tests
		$this->assertSame(
			'http://m.xx.wiki.test/wiki/Special:GoToInterwiki/acme:' . $name,
			$title->getFullUrlForRedirect(),
			'getFullUrlForRedirect()'
		);
		$this->assertSame(
			'http://m.xx.wiki.test/m/index.php?title=Special:GoToInterwiki/acme:'
				. $name . '&' . $queryString,
			$title->getFullUrlForRedirect( $query ),
			'getFullUrlForRedirect( array )'
		);

		// Test getLinkURL() includes fragment
		$this->assertSame(
			'https://acme.test/' . $name . '#' . $section,
			$title->getLinkURL(),
			'getLinkURL()'
		);
		$this->assertSame(
			'https://acme.test/' . $name . '?' . $queryString . '#' . $section,
			$title->getLinkURL( $query ),
			'getLinkURL( array )'
		);

		// Test getInternalURL() ignores fragment
		$this->assertSame( // NOTE: the current behavior is just wrong.
			'http://app23.internalhttps//acme.test/' . $name,
			$title->getInternalURL(),
			'getInternalURL()'
		);
		$this->assertSame( // NOTE: the current behavior is just wrong.
			'http://app23.internalhttps//acme.test/' . $name . '?' . $queryString,
			$title->getInternalURL( $query ),
			'getInternalURL( array )'
		);

		// Test getCanonicalURL() includes fragment
		$this->assertSame(
			'https://acme.test/' . $name . '#' . $section,
			$title->getCanonicalURL(),
			'getCanonicalURL()'
		);
		$this->assertSame(
			'https://acme.test/' . $name . '?' . $queryString . '#' . $section,
			$title->getCanonicalURL( $query ),
			'getCanonicalURL( array )'
		);

		// Test $wgFragmentMode
		$this->overrideConfigValues( [
			MainConfigNames::FragmentMode => [ 'html5', 'legacy' ],
			MainConfigNames::ExternalInterwikiFragmentMode => 'legacy',
		] );
		$section = 'Secti.C3.B6n';

		$this->assertSame(
			'https://acme.test/' . $name . '#' . $section,
			$title->getFullURL(),
			'getFullURL()'
		);
		$this->assertSame(
			'https://acme.test/' . $name . '#' . $section,
			$title->getLinkURL(),
			'getLinkURL()'
		);
	}

	public function testUrlsForLocalInterwikiWithFragment() {
		// the "yy" prefix is a known local interwiki prefix
		$title = Title::makeTitle( NS_MAIN, 'fröbnitz', 'Sectiön', 'yy' );
		$name = wfUrlencode( $title->getDBkey() );

		// local interwikis use $wgFragmentMode, not $wgExternalInterwikiFragmentMode
		$section = 'Secti.C3.B6n';

		$query = [ 'x' => 'one two', 'y' => '#' ];
		$queryString = 'x=one+two&y=%23';

		// Test getLocalURL()
		$this->assertSame(
			'//yy.wiki.test/wiki/' . $name,
			$title->getLocalURL(),
			'getLocalURL()'
		);
		$this->assertSame(
			'//yy.wiki.test/wiki/' . $name . '?' . $queryString,
			$title->getLocalURL( $query ),
			'getLocalURL( array )'
		);

		// Test getFullURL()
		$this->assertSame(
			'//yy.wiki.test/wiki/' . $name . '#' . $section,
			$title->getFullURL(),
			'getFullURL()'
		);
		$this->assertSame(
			'//yy.wiki.test/wiki/' . $name . '?' . $queryString . '#' . $section,
			$title->getFullURL( $query ),
			'getFullURL( array )'
		);
		$this->assertSame(
			'https://yy.wiki.test/wiki/' . $name . '#' . $section,
			$title->getFullURL( [], false, PROTO_HTTPS ),
			'getFullURL( array ) with PROTO_HTTPS'
		);
		$this->assertSame(
			'https://yy.wiki.test/wiki/' . $name . '#' . $section,
			$title->getFullURL( [], false, PROTO_CANONICAL ),
			'getFullURL( array ) with PROTO_CANONICAL'
		);
		$this->assertSame(
			'http://yy.wiki.test/wiki/' . $name . '#' . $section,
			$title->getFullURL( [], false, PROTO_INTERNAL ),
			'getFullURL( array ) with PROTO_INTERNAL'
		);
		$this->assertSame( // NOTE: could as well use action path
			'//yy.wiki.test/wiki/' . $name . '?action=edit#' . $section,
			$title->getFullURL( [ 'action' => 'edit' ] ),
			'getFullURL( array )'
		);
		$this->assertSame( // NOTE: could as well use variant path
			'//yy.wiki.test/wiki/' . $name . '?variant=en-x-piglatin#' . $section,
			$title->getFullURL( [ 'variant' => 'en-x-piglatin' ] ),
			'getFullURL( array )'
		);

		// Test getFullUrlForRedirect()
		// Note that it uses PROTO_CURRENT by default, which is 'http' for tests
		$this->assertSame(
			'http://m.xx.wiki.test/wiki/Special:GoToInterwiki/yy:' . $name,
			$title->getFullUrlForRedirect(),
			'getFullUrlForRedirect()'
		);
		$this->assertSame(
			'http://m.xx.wiki.test/m/index.php?title=Special:GoToInterwiki/yy:'
			. $name . '&' . $queryString,
			$title->getFullUrlForRedirect( $query ),
			'getFullUrlForRedirect( array )'
		);

		// Test getLinkURL() includes fragment
		$this->assertSame(
			'//yy.wiki.test/wiki/' . $name . '#' . $section,
			$title->getLinkURL(),
			'getLinkURL()'
		);
		$this->assertSame(
			'//yy.wiki.test/wiki/' . $name . '?' . $queryString . '#' . $section,
			$title->getLinkURL( $query ),
			'getLinkURL( array )'
		);
		$this->assertSame(
			'https://yy.wiki.test/wiki/' . $name . '?' . $queryString . '#' . $section,
			$title->getLinkURL( $query, false, PROTO_CANONICAL ),
			'getLinkURL( array ) with PROTO_CANONICAL'
		);
		$this->assertSame(
			'http://yy.wiki.test/wiki/' . $name . '?' . $queryString . '#' . $section,
			$title->getLinkURL( $query, false, PROTO_INTERNAL ),
			'getLinkURL( array ) with PROTO_INTERNAL'
		);
		$this->assertSame(
			'https://yy.wiki.test/wiki/' . $name . '?' . $queryString . '#' . $section,
			$title->getLinkURL( $query, false, PROTO_HTTPS ),
			'getLinkURL( array ) with PROTO_HTTPS'
		);

		// Test getInternalURL() ignores fragment
		$this->assertSame( // NOTE: the current behavior is just wrong.
			'http://app23.internal//yy.wiki.test/wiki/' . $name,
			$title->getInternalURL(),
			'getInternalURL()'
		);
		$this->assertSame( // NOTE: the current behavior is just wrong.
			'http://app23.internal//yy.wiki.test/wiki/' . $name . '?' . $queryString,
			$title->getInternalURL( $query ),
			'getInternalURL( array )'
		);

		// Test getCanonicalURL() includes fragment
		$this->assertSame(
			'https://yy.wiki.test/wiki/' . $name . '#' . $section,
			$title->getCanonicalURL(),
			'getCanonicalURL()'
		);
		$this->assertSame(
			'https://yy.wiki.test/wiki/' . $name . '?' . $queryString . '#' . $section,
			$title->getCanonicalURL( $query ),
			'getCanonicalURL( array )'
		);

		// Test $wgFragmentMode
		$this->overrideConfigValues( [
			MainConfigNames::FragmentMode => [ 'html5', 'legacy' ],
			MainConfigNames::ExternalInterwikiFragmentMode => 'legacy',
		] );
		$section = 'Sectiön';

		$this->assertSame(
			'//yy.wiki.test/wiki/' . $name . '#' . $section,
			$title->getFullURL(),
			'getFullURL()'
		);
		$this->assertSame(
			'//yy.wiki.test/wiki/' . $name . '#' . $section,
			$title->getLinkURL(),
			'getLinkURL()'
		);
	}

	public function testUrlsWithHttpsPort() {
		$title = Title::makeTitle( NS_USER, 'Göatee' );
		$name = $title->getPrefixedURL();

		// NOTE: $wgHttpsPort is only supported if $wgCanonicalServer does not use HTTPS
		$this->overrideConfigValues( [
			MainConfigNames::CanonicalServer => 'http://xx.wiki.test',
			MainConfigNames::HttpsPort => '4444',
		] );

		// Test getFullURL()
		$this->assertSame(
			'//m.xx.wiki.test/wiki/' . $name,
			$title->getFullURL( [], false, PROTO_RELATIVE ),
			'getFullURL() with PROTO_RELATIVE'
		);
		$this->assertSame(
			'http://xx.wiki.test/wiki/' . $name,
			$title->getFullURL( [], false, PROTO_CANONICAL ),
			'getFullURL() with PROTO_CANONICAL'
		);
		$this->assertSame(
			'http://m.xx.wiki.test/wiki/' . $name,
			$title->getFullURL( [], false, PROTO_HTTP ),
			'getFullURL() with PROTO_HTTP'
		);
		$this->assertSame(
			'https://m.xx.wiki.test:4444/wiki/' . $name,
			$title->getFullURL( [], false, PROTO_HTTPS ),
			'getFullURL() with PROTO_HTTP'
		);
		$this->assertSame(
			'http://app23.internal/wiki/' . $name,
			$title->getFullURL( [], false, PROTO_INTERNAL ),
			'getFullURL() with PROTO_INTERNAL'
		);

		// Test getFullUrlForRedirect()
		$this->assertSame(
			'//m.xx.wiki.test/wiki/' . $name,
			$title->getFullUrlForRedirect( [], PROTO_RELATIVE ),
			'getFullUrlForRedirect() with PROTO_RELATIVE'
		);
		$this->assertSame(
			'https://m.xx.wiki.test:4444/wiki/' . $name,
			$title->getFullUrlForRedirect( [], PROTO_HTTPS ),
			'getFullUrlForRedirect() with PROTO_HTTPS'
		);

		// Test getLinkURL()
		$this->assertSame(
			'//m.xx.wiki.test/wiki/' . $name,
			$title->getLinkURL( [], false, PROTO_RELATIVE ),
			'getLinkURL() with PROTO_RELATIVE'
		);
		$this->assertSame(
			'https://m.xx.wiki.test:4444/wiki/' . $name,
			$title->getLinkURL( [], false, PROTO_HTTPS ),
			'getLinkURL() with PROTO_HTTPS'
		);
		$this->assertSame(
			'http://xx.wiki.test/wiki/' . $name,
			$title->getLinkURL( [], false, PROTO_CANONICAL ),
			'getLinkURL() with PROTO_CANONICAL'
		);
		$this->assertSame(
			'http://app23.internal/wiki/' . $name,
			$title->getLinkURL( [], false, PROTO_INTERNAL ),
			'getLinkURL() with PROTO_INTERNAL'
		);

		// Test getInternalURL()
		$this->assertSame(
			'http://app23.internal/wiki/' . $name,
			$title->getInternalURL(),
			'getInternalURL()'
		);

		// Test getCanonicalURL()
		$this->assertSame(
			'http://xx.wiki.test/wiki/' . $name,
			$title->getCanonicalURL(),
			'getCanonicalURL()'
		);
	}

}
