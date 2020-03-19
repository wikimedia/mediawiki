<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MediaWikiServices;
use MediaWiki\Rest\Handler\LanguageLinksHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use Title;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Rest\Handler\LanguageLinksHandler
 *
 * @group Database
 */
class LanguageLinksHandlerTest extends \MediaWikiIntegrationTestCase {

	use HandlerTestTrait;

	public function addDBDataOnce() {
		$defaults = [
			'iw_local' => 0,
			'iw_api' => '/w/api.php',
			'iw_url' => ''
		];

		$base = 'https://wiki.test/';

		$this->db->insert(
			'interwiki',
			[
				[ 'iw_prefix' => 'de', 'iw_url' => $base . '/de', 'iw_wikiid' => 'dewiki' ] + $defaults,
				[ 'iw_prefix' => 'en', 'iw_url' => $base . '/en', 'iw_wikiid' => 'enwiki' ] + $defaults,
				[ 'iw_prefix' => 'fr', 'iw_url' => $base . '/fr', 'iw_wikiid' => 'frwiki' ] + $defaults,
			],
			__METHOD__,
			[ 'IGNORE' ]
		);

		$this->editPage( __CLASS__ . '_Foo', 'Foo [[fr:Fou baux]] [[de:Füh bär]]' );
	}

	private function newHandler() {
		$services = MediaWikiServices::getInstance();

		$languageNameUtils = new LanguageNameUtils(
			new ServiceOptions(
				LanguageNameUtils::CONSTRUCTOR_OPTIONS,
				[ 'ExtraLanguageNames' => [], 'UsePigLatinVariant' => false ]
			),
			$services->getHookContainer()
		);

		$titleCodec = $this->makeMockTitleCodec();

		return new LanguageLinksHandler(
			$services->getDBLoadBalancer(),
			$languageNameUtils,
			$this->makeMockPermissionManager(),
			$titleCodec,
			$titleCodec
		);
	}

	private function assertLink( $expected, $actual ) {
		foreach ( $expected as $key => $value ) {
			$this->assertArrayHasKey( $key, $actual );
			$this->assertSame( $value, $actual[$key], $key );
		}
	}

	public function testExecute() {
		$title = __CLASS__ . '_Foo';
		$request = new RequestData( [ 'pathParams' => [ 'title' => $title ] ] );

		$handler = $this->newHandler();
		$data = $this->executeHandlerAndGetBodyData( $handler, $request );

		$this->assertCount( 2, $data );

		$links = [];
		foreach ( $data as $row ) {
			$links[$row['code']] = $row;
		}

		$this->assertArrayHasKey( 'de', $links );
		$this->assertArrayHasKey( 'fr', $links );

		$this->assertLink( [
			'code' => 'de',
			'name' => 'Deutsch',
			'title' => 'Füh bär',
			'key' => 'Füh_bär',
		], $links['de'] );

		$this->assertLink( [
			'code' => 'fr',
			'name' => 'français',
			'title' => 'Fou baux',
			'key' => 'Fou_baux',
		], $links['fr'] );
	}

	public function testCacheControl() {
		$title = Title::newFromText( __METHOD__ );
		$this->editPage( $title->getPrefixedDBkey(), 'First' );

		$request = new RequestData( [ 'pathParams' => [ 'title' => $title->getPrefixedDBkey() ] ] );

		$handler = $this->newHandler();
		$response = $this->executeHandler( $handler, $request );

		$firstETag = $response->getHeaderLine( 'ETag' );
		$this->assertSame(
			wfTimestamp( TS_RFC2822, $title->getTouched() ),
			$response->getHeaderLine( 'Last-Modified' )
		);

		$this->editPage( $title->getPrefixedDBkey(), 'Second' );

		Title::clearCaches();
		$handler = $this->newHandler();
		$response = $this->executeHandler( $handler, $request );

		$this->assertNotEquals( $response->getHeaderLine( 'ETag' ), $firstETag );
		$this->assertSame(
			wfTimestamp( TS_RFC2822, $title->getTouched() ),
			$response->getHeaderLine( 'Last-Modified' )
		);
	}

	public function testExecute_notFound() {
		$title = __CLASS__ . '_Xyzzy';
		$request = new RequestData( [ 'pathParams' => [ 'title' => $title ] ] );

		$handler = $this->newHandler();

		$this->expectExceptionObject(
			new LocalizedHttpException( new MessageValue( 'rest-nonexistent-title' ), 404 )
		);
		$this->executeHandler( $handler, $request );
	}

	public function testExecute_forbidden() {
		// The mock PermissionHandler forbids access to pages that have "Forbidden" in the name
		$title = __CLASS__ . '_Forbidden';
		$this->editPage( $title, 'Forbidden text' );
		$request = new RequestData( [ 'pathParams' => [ 'title' => $title ] ] );

		$handler = $this->newHandler();

		$this->expectExceptionObject(
			new LocalizedHttpException( new MessageValue( 'rest-permission-denied-title' ), 403 )
		);
		$this->executeHandler( $handler, $request, [ 'userCan' => false ] );
	}

}
