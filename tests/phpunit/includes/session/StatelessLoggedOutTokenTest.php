<?php

use MediaWiki\Session\StatelessLoggedOutToken;

/**
 * @group Session
 * @covers MediaWiki\Session\StatelessLoggedOutTokenTest
 */
class StatelessLoggedOutTokenTest extends MediaWikiTestCase {

	/** @var StatelessLoggedOutToken */
	protected $token;

	protected function setUp() {
		$this->setMwGlobals( 'wgLoggedOutTokenTestingMode', false );
		$this->token = TestingAccessWrapper::newFromObject(
			new StatelessLoggedOutToken( '127.0.0.1', 'secret', 'salt' )
		);
		parent::setUp();
	}

	public function testMatchSelf() {
		$atCurTime = $this->token->toString();
		$this->assertTrue( $this->token->match( $atCurTime ), "match self" );
		$this->assertTrue( $this->token->match( $atCurTime, null ), "match self null expire" );
		// This assumes the test takes < 60 seconds to run.
		$this->assertTrue( $this->token->match( $atCurTime, 60 ), "match self 1 min exp" );
		$token2 = new StatelessLoggedOutToken( '127.0.0.1', 'secret', 'salt' );
		$this->assertTrue( $token2->match( $atCurTime ), "separate instance" );
	}

	public function testMatchTenMinutesAgo() {
		$tenMinuteAgo = $this->token->toStringAtTimestamp(
			wfTimestamp( TS_UNIX, wfTimestamp() - 60*10 )
		);

		$this->assertTrue( $this->token->match( $tenMinuteAgo ), "no exp" );
		$this->assertTrue( $this->token->match( $tenMinuteAgo, null ), "null exp" );
		$this->assertTrue( $this->token->match( $tenMinuteAgo, 11*60 ), "11 min exp" );
		$this->assertFalse( $this->token->match( $tenMinuteAgo, 9*60 ), "9 min exp" );
	}

	/**
	 * This should be more than the max allowed for a stateless token
	 */
	public function testMatch5HourAgo() {
		$oldToken = $this->token->toStringAtTimestamp(
			wfTimestamp( TS_UNIX, wfTimestamp() - 60*60*5 )
		);

		$this->assertFalse( $this->token->match( $oldToken ), "no exp" );
		$this->assertFalse( $this->token->match( $oldToken, null ), "null exp" );
		$this->assertFalse( $this->token->match( $oldToken, 60*60 ), "1 min exp" );
		$this->assertFalse( $this->token->match( $oldToken, 60*60*6 ), "six hour exp" );
	}

	public function testDoesNotMatchOtherTokens() {
		$otherIp = new StatelessLoggedOutToken( '::1', 'secret', 'salt' );
		$otherSecret = new StatelessLoggedOutToken( '127.0.0.1', 'top-secret', 'salt' );
		$otherSalt = new StatelessLoggedOutToken( '127.0.0.1', 'secret', 'organic sea-salt' );
		$loggedIn = new \MediaWiki\Session\Token( 'randsecret', 'salt' );
		$stateful = new \MediaWiki\Session\StatefulLoggedOutToken(
			'127.0.0.1', 'secret', 'secret2', wfTimestampNow(), 'salt', false
		);

		$this->assertFalse( $this->token->match( $otherIp->toString() ), "diff ip" );
		$this->assertFalse( $this->token->match( $otherSecret->toString() ), "diff secret" );
		$this->assertFalse( $this->token->match( $otherSalt->toString() ), "diff salt" );
		$this->assertFalse( $this->token->match( $loggedIn->toString() ), "logged-in" );
		$this->assertFalse( $this->token->match( $stateful->toString() ), "stateful" );
	}

	public function testWasNew() {
		$this->assertTrue( $this->token->wasNew() );
	}
}
