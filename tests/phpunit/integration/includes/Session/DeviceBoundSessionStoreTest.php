<?php

namespace MediaWiki\Tests\Session;

use MediaWiki\Session\DeviceBoundSessionStore;
use MediaWikiIntegrationTestCase;
use ReportUri\Dbsc\Binding;
use ReportUri\Dbsc\Exception\CorruptStateException;
use ReportUri\Dbsc\PendingRegistration;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @group Database
 * @group Session
 * @covers \MediaWiki\Session\DeviceBoundSessionStore
 */
class DeviceBoundSessionStoreTest extends MediaWikiIntegrationTestCase {

	private const BINDING_TTL = 100;

	private function newStore(): DeviceBoundSessionStore {
		return new DeviceBoundSessionStore(
			$this->getServiceContainer()->getConnectionProvider(),
			new HashBagOStuff(),
			'https://wiki.example.org',
			self::BINDING_TTL,
			900
		);
	}

	private function newBinding( string $subject = 'c:7' ): Binding {
		return new Binding(
			userId: $subject,
			sessionIdentifier: 'dbsc-sid-1',
			cookieValue: 'cookie-1',
			publicKeyPem: "-----BEGIN PUBLIC KEY-----\nMA==\n-----END PUBLIC KEY-----\n",
			algorithm: 'ES256',
			challenge: 'chal-1',
			challengeTime: time(),
			createdAt: time()
		);
	}

	public function testBindingRoundTrip() {
		$store = $this->newStore();
		$store->putBinding( 'mw-sess-1', $this->newBinding() );

		$read = $store->getBinding( 'mw-sess-1' );
		$this->assertNotNull( $read );
		$this->assertSame( 'c:7', $read->userId );
		$this->assertSame( 'dbsc-sid-1', $read->sessionIdentifier );

		$store->putBinding( 'mw-sess-1', $this->newBinding( 'c:8' ) );
		$this->assertSame( 'c:8', $store->getBinding( 'mw-sess-1' )->userId,
			're-registration replaces the row' );

		$store->delete( 'mw-sess-1' );
		$this->assertNull( $store->getBinding( 'mw-sess-1' ) );
	}

	public function testBindingsAreIsolatedByOrigin() {
		$this->newStore()->putBinding( 'mw-sess-1', $this->newBinding() );
		$other = new DeviceBoundSessionStore(
			$this->getServiceContainer()->getConnectionProvider(),
			new HashBagOStuff(),
			'https://other.example.org',
			self::BINDING_TTL,
			900
		);

		$this->assertNull( $other->getBinding( 'mw-sess-1' ) );
		$this->assertNotNull( $this->newStore()->getBinding( 'mw-sess-1' ) );
	}

	public function testCorruptBindingFailsClosed() {
		$store = $this->newStore();
		$store->putBinding( 'mw-sess-1', $this->newBinding() );
		$this->getServiceContainer()->getConnectionProvider()
			->getPrimaryDatabase( DeviceBoundSessionStore::VIRTUAL_DOMAIN )
			->newUpdateQueryBuilder()
			->update( 'device_bound_session' )
			->set( [ 'dbs_binding' => '{not json' ] )
			->where( [ 'dbs_session' => 'mw-sess-1' ] )
			->caller( __METHOD__ )
			->execute();

		$this->expectException( CorruptStateException::class );
		$store->getBinding( 'mw-sess-1' );
	}

	public function testTerminateAllForSubject() {
		$store = $this->newStore();
		$store->putBinding( 'mw-sess-1', $this->newBinding( 'c:7' ) );
		$store->putBinding( 'mw-sess-2', $this->newBinding( 'c:7' ) );
		$store->putBinding( 'mw-sess-3', $this->newBinding( 'c:8' ) );

		$store->terminateAllForSubject( 'c:7' );

		$this->assertNull( $store->getBinding( 'mw-sess-1' ) );
		$this->assertNull( $store->getBinding( 'mw-sess-2' ) );
		$this->assertNotNull( $store->getBinding( 'mw-sess-3' ), 'other subject unaffected' );
	}

	public function testBindingExpires() {
		$start = 1_600_000_000;
		ConvertibleTimestamp::setFakeTime( $start );
		$store = $this->newStore();
		$store->putBinding( 'mw-sess-1', $this->newBinding() );

		ConvertibleTimestamp::setFakeTime( $start + self::BINDING_TTL - 10 );
		$this->assertNotNull( $store->getBinding( 'mw-sess-1' ) );

		ConvertibleTimestamp::setFakeTime( $start + self::BINDING_TTL + 10 );
		$this->assertNull( $store->getBinding( 'mw-sess-1' ), 'hard-expired' );

		ConvertibleTimestamp::setFakeTime( false );
	}

	public function testPendingRegistrationsAreSeparateFromBindings() {
		$store = $this->newStore();
		$store->putPendingRegistration( 'mw-sess-1', new PendingRegistration(
			userId: 'c:7', regChallenge: 'chal-1', regChallengeTime: time() ) );

		$this->assertNull( $store->getBinding( 'mw-sess-1' ),
			'a pending offer must never read as a binding' );
		$this->assertSame( 'chal-1', $store->getPendingRegistration( 'mw-sess-1' )->regChallenge );

		$store->deletePendingRegistration( 'mw-sess-1' );
		$this->assertNull( $store->getPendingRegistration( 'mw-sess-1' ) );
	}
}
