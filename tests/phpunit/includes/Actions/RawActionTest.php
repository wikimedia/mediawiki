<?php

use MediaWiki\Actions\ActionEntryPoint;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\MockEnvironment;
use MediaWiki\Title\Title;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Actions\RawAction
 * @group Database
 */
class RawActionTest extends MediaWikiIntegrationTestCase {

	/** @before */
	public function fixtureSetUp(): void {
		ConvertibleTimestamp::setFakeTime( '20110401090000' );
		$this->editPage( 'MediaWiki:Foo.js', 'console.log("Hello world");' );
		ConvertibleTimestamp::setFakeTime( '20110402090000' );
	}

	public static function provideCacheControl() {
		yield 'Import JS canonical (CDN on)' => [
			// Enable full CDN caching for canonical URLs (T279120).
			'/w/index.php?title=MediaWiki:Foo.js&action=raw&ctype=text/javascript',
			[],
			true,
			200,
			'public, s-maxage=604800, max-age=300'
		];
		yield 'Import JS canonical (If-Modified-Since matches, CDN on)' => [
			'/w/index.php?title=MediaWiki:Foo.js&action=raw&ctype=text/javascript',
			[ 'If-Modified-Since' => '2011-04-01T09:00:00Z' ],
			true,
			304,
			's-maxage=604800, must-revalidate, max-age=0'
		];
		yield 'Import JS canonical (If-Modified-Since matches, CDN off)' => [
			'/w/index.php?title=MediaWiki:Foo.js&action=raw&ctype=text/javascript',
			[ 'If-Modified-Since' => '2011-04-01T09:00:00Z' ],
			false,
			304,
			'private, must-revalidate, max-age=0'
		];
		yield 'Import JS canonical (If-Modified-Since too old, CDN on)' => [
			'/w/index.php?title=MediaWiki:Foo.js&action=raw&ctype=text/javascript',
			[ 'If-Modified-Since' => '2011-04-01T08:00:00Z' ],
			true,
			200,
			'public, s-maxage=604800, max-age=300'
		];
		yield 'Import JS canonical (If-Modified-Since too old, CDN off)' => [
			'/w/index.php?title=MediaWiki:Foo.js&action=raw&ctype=text/javascript',
			[ 'If-Modified-Since' => '2011-04-01T08:00:00Z' ],
			false,
			200,
			'public, s-maxage=3600, max-age=300'
		];
		yield 'Import JS canonical (CDN off)' => [
			'/w/index.php?title=MediaWiki:Foo.js&action=raw&ctype=text/javascript',
			[],
			false,
			200,
			'public, s-maxage=3600, max-age=300'
		];
		yield 'Import JS oldid (CDN on)' => [
			'/w/index.php?title=MediaWiki:Foo.js&action=raw&ctype=text/javascript&oldid=1',
			[],
			true,
			200,
			'public, s-maxage=3600, max-age=300'
		];
		yield 'Import JS oldid (CDN off)' => [
			'/w/index.php?title=MediaWiki:Foo.js&action=raw&ctype=text/javascript&oldid=1',
			[],
			false,
			200,
			'public, s-maxage=3600, max-age=300'
		];
		yield 'Import JS whatever (CDN on)' => [
			'/w/index.php?title=MediaWiki:Foo.js&action=raw&ctype=text/javascript&whatever=1',
			[],
			true,
			200,
			'public, s-maxage=3600, max-age=300'
		];
		yield 'Import JS whatever (CDN off)' => [
			'/w/index.php?title=MediaWiki:Foo.js&action=raw&ctype=text/javascript&whatever=1',
			[],
			false,
			200,
			'public, s-maxage=3600, max-age=300'
		];
		yield 'Import JS without ctype (CDN on)' => [
			'/w/index.php?title=MediaWiki:Foo.js&action=raw',
			[],
			true,
			200,
			'public, s-maxage=3600, max-age=300'
		];
		yield 'Import JS without ctype (CDN off)' => [
			'/w/index.php?title=MediaWiki:Foo.js&action=raw',
			[],
			false,
			200,
			'public, s-maxage=3600, max-age=300'
		];
		yield 'Regular page (CDN on)' => [
			'/w/index.php?title=Help:RawActionTest&action=raw',
			[],
			true,
			404,
			'public, s-maxage=3600, max-age=300'
		];
		yield 'Regular page (CDN off)' => [
			'/w/index.php?title=Help:RawActionTest&action=raw',
			[],
			false,
			404,
			'public, s-maxage=3600, max-age=300'
		];
	}

	/**
	 * @dataProvider provideCacheControl
	 */
	public function testCacheControl( string $url, array $headers, bool $cdn, int $status, string $cc ) {
		$this->overrideConfigValues( [
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::ArticlePath => '/wiki/$1',
			MainConfigNames::UseCdn => $cdn,
			MainConfigNames::CdnMaxAge => 604800,
			MainConfigNames::CacheEpoch => '20030516000000',
		] );

		$query = wfCgiToArray( parse_url( $url, PHP_URL_QUERY ) );
		$title = Title::newFromText( $query['title'] );
		$context = RequestContext::newExtraneousContext( $title, $query );
		$context->getRequest()->setRequestURL( $url );
		$context->getRequest()->setHeaders( $headers );

		// Simulate via ActionEntryPoint instead of ActionFactory/Action::show,
		// because we ActionEntryPoint::performAction influences the behavior
		// via its setCdnMaxAge call.
		$entryPoint = TestingAccessWrapper::newFromObject( new ActionEntryPoint(
			$context,
			new MockEnvironment(),
			$this->getServiceContainer()
		) );
		$entryPoint->enableOutputCapture();
		$entryPoint->performRequest();
		$entryPoint->getCapturedOutput();

		$this->assertSame(
			$status,
			$context->getRequest()->response()->getStatusCode() ?? 200
		);
		$this->assertSame(
			$cc,
			$context->getRequest()->response()->getHeader( 'Cache-Control' )
		);
	}
}
