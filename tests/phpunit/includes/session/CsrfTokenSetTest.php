<?php

namespace MediaWiki\Tests\Session;

use MediaWiki\Request\WebRequest;
use MediaWiki\Session\CsrfTokenSet;
use MediaWiki\Session\SessionManager;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Session\CsrfTokenSet
 * @group Database
 */
class CsrfTokenSetTest extends MediaWikiIntegrationTestCase {

	private function makeRequest( bool $userRegistered ): WebRequest {
		$webRequest = new WebRequest();
		$session1 = SessionManager::singleton()->getEmptySession( $webRequest );
		$session1->setUser( $userRegistered ? $this->getTestUser()->getUser() : new User() );
		return $webRequest;
	}

	public function testCSRFTokens_anon() {
		$webRequest1 = $this->makeRequest( false );
		$tokenRepo1 = new CsrfTokenSet( $webRequest1 );
		$token = $tokenRepo1->getToken()->toString();
		$webRequest2 = $this->makeRequest( false );
		$tokenRepo2 = new CsrfTokenSet( $webRequest2 );
		$this->assertTrue( $tokenRepo2->matchToken( $token ) );
		$webRequest2->setVal( 'wpBlabla', $token );
		$this->assertTrue( $tokenRepo2->matchTokenField( 'wpBlabla' ) );
	}

	public function testCSRFTokens_registered() {
		$webRequest1 = $this->makeRequest( true );
		$tokenRepo1 = new CsrfTokenSet( $webRequest1 );
		$token = $tokenRepo1->getToken()->toString();
		$this->assertTrue( $tokenRepo1->matchToken( $token ) );
		$this->assertFalse( $tokenRepo1->matchTokenField( 'wpBlabla' ) );
		$webRequest1->setVal( 'wpBlabla', $token );
		$this->assertTrue( $tokenRepo1->matchTokenField( 'wpBlabla' ) );
		$webRequest2 = $this->makeRequest( true );
		$webRequest2->setVal( 'wpBlabla', $token );
		$tokenRepo2 = new CsrfTokenSet( $webRequest2 );
		$this->assertFalse( $tokenRepo2->matchTokenField( 'wpBlabla' ) );
		$this->assertFalse( $tokenRepo2->matchToken( $token ) );
	}
}
