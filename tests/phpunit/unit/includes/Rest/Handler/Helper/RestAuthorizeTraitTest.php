<?php

namespace MediaWiki\Tests\Rest\Handler\Helper;

use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Permissions\SimpleAuthority;
use MediaWiki\Rest\Handler\Helper\RestAuthorizeTrait;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Rest\Handler\Helper\RestAuthorizeTrait
 */
class RestAuthorizeTraitTest extends MediaWikiUnitTestCase {

	private function getTraitInstance(): object {
		return new class () {
			use RestAuthorizeTrait {
				authorizeActionOrThrow as public;
				authorizeReadOrThrow as public;
				authorizeWriteOrThrow as public;
			}
		};
	}

	public function testPass() {
		$user = new UserIdentityValue( 1, 'Test' );
		$authority = new SimpleAuthority( $user, [ 'test' ] );
		$target = PageIdentityValue::localIdentity( 1, NS_MAIN, 'Test' );

		$trait = $this->getTraitInstance();

		$trait->authorizeActionOrThrow( $authority, 'test' );
		$trait->authorizeReadOrThrow( $authority, 'test', $target );
		$trait->authorizeWriteOrThrow( $authority, 'test', $target );

		$this->addToAssertionCount( 3 );
	}

	public function testSimplePermissionFailure() {
		$user = new UserIdentityValue( 1, 'Test' );
		$authority = new SimpleAuthority( $user, [] );
		$target = PageIdentityValue::localIdentity( 1, NS_MAIN, 'Test' );

		$trait = $this->getTraitInstance();

		try {
			$trait->authorizeActionOrThrow( $authority, 'test' );
			$this->fail( 'Exception expected' );
		} catch ( LocalizedHttpException $ex ) {
			$this->assertSame( 403, $ex->getCode() );
		}

		try {
			$trait->authorizeReadOrThrow( $authority, 'test', $target );
			$this->fail( 'Exception expected' );
		} catch ( LocalizedHttpException $ex ) {
			$this->assertSame( 403, $ex->getCode() );
		}

		try {
			$trait->authorizeWriteOrThrow( $authority, 'test', $target );
			$this->fail( 'Exception expected' );
		} catch ( LocalizedHttpException $ex ) {
			$this->assertSame( 403, $ex->getCode() );
		}
	}

	public function testPermissionFailureWithoutStatusMessage() {
		$target = PageIdentityValue::localIdentity( 1, NS_MAIN, 'Test' );

		$authority = $this->createNoOpMock(
			Authority::class,
			[ 'authorizeAction', 'authorizeRead', 'authorizeWrite', ]
		);

		$authority->method( 'authorizeAction' )->willReturn( false );
		$authority->method( 'authorizeRead' )->willReturn( false );
		$authority->method( 'authorizeWrite' )->willReturn( false );

		$trait = $this->getTraitInstance();

		try {
			$trait->authorizeActionOrThrow( $authority, 'test' );
			$this->fail( 'Exception expected' );
		} catch ( LocalizedHttpException $ex ) {
			$this->assertSame( 403, $ex->getCode() );
		}

		try {
			$trait->authorizeReadOrThrow( $authority, 'test', $target );
			$this->fail( 'Exception expected' );
		} catch ( LocalizedHttpException $ex ) {
			$this->assertSame( 403, $ex->getCode() );
		}

		try {
			$trait->authorizeWriteOrThrow( $authority, 'test', $target );
			$this->fail( 'Exception expected' );
		} catch ( LocalizedHttpException $ex ) {
			$this->assertSame( 403, $ex->getCode() );
		}
	}

	public function testRateLimitFailure() {
		$target = PageIdentityValue::localIdentity( 1, NS_MAIN, 'Test' );

		$authority = $this->createNoOpMock(
			Authority::class,
			[ 'authorizeAction', 'authorizeRead', 'authorizeWrite', ]
		);

		$rateLimitCallback = static function ( $permission, $b = null, $c = null ) {
			if ( $b instanceof PermissionStatus ) {
				$status = $b;
			} else {
				$status = $c;
			}

			$status->setRateLimitExceeded();
			$status->setPermission( $permission );
			return false;
		};

		$authority->method( 'authorizeAction' )->willReturnCallback( $rateLimitCallback );
		$authority->method( 'authorizeRead' )->willReturnCallback( $rateLimitCallback );
		$authority->method( 'authorizeWrite' )->willReturnCallback( $rateLimitCallback );

		$trait = $this->getTraitInstance();

		try {
			$trait->authorizeActionOrThrow( $authority, 'test' );
			$this->fail( 'Exception expected' );
		} catch ( LocalizedHttpException $ex ) {
			$this->assertSame( 429, $ex->getCode() );
		}

		try {
			$trait->authorizeReadOrThrow( $authority, 'test', $target );
			$this->fail( 'Exception expected' );
		} catch ( LocalizedHttpException $ex ) {
			$this->assertSame( 429, $ex->getCode() );
		}

		try {
			$trait->authorizeWriteOrThrow( $authority, 'test', $target );
			$this->fail( 'Exception expected' );
		} catch ( LocalizedHttpException $ex ) {
			$this->assertSame( 429, $ex->getCode() );
		}
	}

}
