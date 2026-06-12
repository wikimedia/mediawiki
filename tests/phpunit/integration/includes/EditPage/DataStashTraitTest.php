<?php

namespace MediaWiki\Tests\Integration\EditPage;

use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\EditPage\DataStashTrait;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Session\Session;
use MediaWiki\Session\SessionManager;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\EditPage\DataStashTrait
 * @group Database
 */
class DataStashTraitTest extends MediaWikiIntegrationTestCase {

	private const TITLE = 'MediaWiki:Common.js';

	private const KEY_PREFIX = 'editform:' . self::TITLE;

	private function newSession(): Session {
		return SessionManager::singleton()->getEmptySession();
	}

	private function newContext( FauxRequest $request ): DerivativeContext {
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setRequest( $request );
		$context->setUser( $this->getTestUser()->getUser() );
		return $context;
	}

	/**
	 * @return array{0:object,1:TestingAccessWrapper} The object (for reading the
	 *   captured payload) and a wrapper (for the protected/private methods).
	 */
	private function newStasher( IContextSource $context, ?string $keyPrefix = self::KEY_PREFIX ): array {
		$obj = new class( Title::makeTitle( NS_MAIN, self::TITLE ), $context ) {
			use DataStashTrait;

			public ?array $received = null;

			public function __construct(
				private Title $title,
				private IContextSource $ctx
			) {
			}

			protected function getTitle() {
				return $this->title;
			}

			protected function getContext() {
				return $this->ctx;
			}

			protected function handleRetrievedData( array $data ): void {
				$this->received = $data;
			}
		};

		$wrapper = TestingAccessWrapper::newFromObject( $obj );
		if ( $keyPrefix !== null ) {
			$wrapper->setStashKey( $keyPrefix );
		}
		return [ $obj, $wrapper ];
	}

	public static function provideStashDataOnPost(): iterable {
		yield 'POST stashes the body and returns a unique id' => [
			'query' => [ 'title' => self::TITLE, 'action' => 'submit' ],
			'post' => [
				'wpTextbox1' => '<script>alert(1)</script>',
				'wpSummary' => 's',
				'wpEditToken' => 'stale',
			],
			'wasPosted' => true,
			'expectStashed' => true,
		];
		yield 'GET stashes nothing' => [
			'query' => [ 'title' => self::TITLE, 'action' => 'edit' ],
			'post' => [],
			'wasPosted' => false,
			'expectStashed' => false,
		];
		yield 'POST with no body beyond the query stashes nothing' => [
			'query' => [ 'title' => self::TITLE ],
			'post' => [],
			'wasPosted' => true,
			'expectStashed' => false,
		];
	}

	/**
	 * @dataProvider provideStashDataOnPost
	 */
	public function testStashDataOnPost(
		array $query,
		array $post,
		bool $wasPosted,
		bool $expectStashed
	) {
		$session = $this->newSession();
		$request = new FauxRequest( [], $wasPosted, $session );
		$request->setParams( $query, $post );

		[ , $wrapper ] = $this->newStasher( $this->newContext( $request ) );
		$params = $wrapper->stashDataOnPost();

		if ( !$expectStashed ) {
			$this->assertArrayNotHasKey( 'requestUniqueId', $params );
			return;
		}

		$this->assertArrayHasKey( 'requestUniqueId', $params );
		// The body must not leak back into the (query) params returned for the URL.
		$this->assertArrayNotHasKey( 'wpTextbox1', $params );

		$key = self::KEY_PREFIX . ':' . $params['requestUniqueId'];
		$stash = $session->getSecret( $key );
		$this->assertIsArray( $stash );
		$this->assertSame( $post, $stash['data'] );
		$this->assertArrayHasKey( 'ts', $stash, 'timestamp recorded for TTL enforcement' );
	}

	public function testRetrieveDeliversStashedDataToOverride() {
		$session = $this->newSession();

		$postRequest = new FauxRequest( [], true, $session );
		$postRequest->setParams(
			[ 'title' => self::TITLE, 'action' => 'submit' ],
			[ 'wpTextbox1' => 'restored body', 'wpSummary' => 'sum', 'wpMinoredit' => '1' ]
		);
		[ , $postWrapper ] = $this->newStasher( $this->newContext( $postRequest ) );
		$params = $postWrapper->stashDataOnPost();

		$getContext = $this->newContext( new FauxRequest( $params, false, $session ) );
		[ $getObj, $getWrapper ] = $this->newStasher( $getContext );
		$ok = $getWrapper->retrieveStashedData();

		$this->assertTrue( $ok );
		$this->assertSame(
			[ 'wpTextbox1' => 'restored body', 'wpSummary' => 'sum', 'wpMinoredit' => '1' ],
			$getObj->received,
			'override receives the original POST body verbatim'
		);
	}

	public function testRetrieveReturnsFalseWithoutUniqueId() {
		$session = $this->newSession();
		$context = $this->newContext( new FauxRequest(
			[ 'title' => self::TITLE, 'action' => 'edit' ],
			false,
			$session
		) );

		[ $obj, $wrapper ] = $this->newStasher( $context );

		$this->assertFalse( $wrapper->retrieveStashedData() );
		$this->assertNull( $obj->received, 'override is not invoked without a unique id' );
	}

	public function testExpiredStashIsIgnoredAndRemoved() {
		$session = $this->newSession();
		$uniqueId = '0123ab';
		$key = self::KEY_PREFIX . ':' . $uniqueId;
		$session->setSecret( $key, [
			'data' => [ 'wpTextbox1' => 'should never appear' ],
			'ts' => time() - 100000,
		] );

		$context = $this->newContext( new FauxRequest(
			[ 'title' => self::TITLE, 'requestUniqueId' => $uniqueId ],
			false,
			$session
		) );
		[ $obj, $wrapper ] = $this->newStasher( $context );

		$this->assertFalse( $wrapper->retrieveStashedData() );
		$this->assertNull( $obj->received );
		$this->assertNull( $session->getSecret( $key ), 'expired stash is purged' );
	}

	public function testStashIsNotConsumedOnSuccessfulRetrieve() {
		$session = $this->newSession();
		$postRequest = new FauxRequest( [], true, $session );
		$postRequest->setParams(
			[ 'title' => self::TITLE ],
			[ 'wpTextbox1' => 'survives reload' ]
		);
		[ , $postWrapper ] = $this->newStasher( $this->newContext( $postRequest ) );
		$params = $postWrapper->stashDataOnPost();

		$key = self::KEY_PREFIX . ':' . $params['requestUniqueId'];

		[ $first, $firstWrapper ] = $this->newStasher(
			$this->newContext( new FauxRequest( $params, false, $session ) )
		);
		$this->assertTrue( $firstWrapper->retrieveStashedData() );
		$this->assertSame( 'survives reload', $first->received['wpTextbox1'] );
		$this->assertIsArray( $session->getSecret( $key ), 'stash still present after first use' );

		[ $second, $secondWrapper ] = $this->newStasher(
			$this->newContext( new FauxRequest( $params, false, $session ) )
		);
		$this->assertTrue( $secondWrapper->retrieveStashedData() );
		$this->assertSame( 'survives reload', $second->received['wpTextbox1'] );
	}

	public function testSetStashKeyIgnoresEmptyString() {
		$context = $this->newContext( new FauxRequest( [], false, $this->newSession() ) );
		// Start with no key set
		[ , $wrapper ] = $this->newStasher( $context, null );

		$wrapper->setStashKey( '' );
		$this->assertNull( $wrapper->getStashKey(), 'empty key is rejected, leaving null' );

		$wrapper->setStashKey( self::KEY_PREFIX );
		$this->assertSame( self::KEY_PREFIX, $wrapper->getStashKey() );
	}

	public function testDoReauthRedirectTargetsLoginWithReturnQuery() {
		$context = $this->newContext( new FauxRequest( [], false, $this->newSession() ) );
		[ , $wrapper ] = $this->newStasher( $context );

		$status = $this->createMock( PermissionStatus::class );
		$status->method( 'getReauthOperation' )->willReturn( 'edit' );

		$wrapper->doReauthRedirect( $status, [
			'title' => self::TITLE,
			'action' => 'submit',
			'requestUniqueId' => 'abc123',
		] );

		$url = $context->getOutput()->getRedirect();
		$this->assertNotSame( '', $url, 'a redirect was issued' );
		$this->assertStringContainsString( 'force=edit', $url );
		$this->assertStringContainsString( 'requestUniqueId', $url );
	}
}
