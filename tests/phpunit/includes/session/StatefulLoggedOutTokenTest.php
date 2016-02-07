<?php

use MediaWiki\Session\StatefulLoggedOutToken;
use MediaWiki\Session\StatelessLoggedOutToken;
use MediaWiki\Session\Token;

/**
 * @group Session
 * @covers MediaWiki\Session\StatefulLoggedOutTokenTest
 */
class StatefulLoggedOutTokenTest extends MediaWikiTestCase {

	/** @var StatefulLoggedOutToken */
	protected $token;

	protected function setUp() {
		$ts = wfTimestampNow();
		$this->token = TestingAccessWrapper::newFromObject(
			new StatefulLoggedOutToken(
				'127.0.0.1', 'secret', 'secret2', $ts, 'salt', false
			)
		);
		parent::setUp();
	}

	public function testMatchSelf() {
		$atCurTime = $this->token->toString();
		$ts = wfTimestampNow();
		$this->assertTrue( $this->token->match( $atCurTime ), "match self" );
		$this->assertTrue( $this->token->match( $atCurTime, null ), "match self null expire" );
		// This assumes the test takes < 60 seconds to run.
		$this->assertTrue( $this->token->match( $atCurTime, 60 ), "match self 1 min exp" );
		$token2 = new StatefulLoggedOutToken(
			'127.0.0.1', 'secret', 'secret2', $ts, 'salt', false
		);
		$this->assertTrue( $token2->match( $atCurTime ), "separate instance" );

		$token3 = new StatefulLoggedOutToken(
			'127.0.0.1', 'diff secret', 'secret2', $ts, 'salt', true
		);
		$this->assertTrue( $token3->match( $atCurTime ), "fallback token ignored" );
		$token4 = new StatefulLoggedOutToken(
			'192', 'secret', 'secret2', $ts, 'salt', false
		);
		$this->assertTrue( $token4->match( $atCurTime ), 'stateful diff ip ignored' );
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
	 * Unlike a stateless token, this should be allowed for stateful tokens
	 */
	public function testMatch5HourAgo() {
		$oldToken = $this->token->toStringAtTimestamp(
			wfTimestamp( TS_UNIX, wfTimestamp() - 60*60*5 )
		);

		$this->assertTrue( $this->token->match( $oldToken ), "no exp" );
		$this->assertTrue( $this->token->match( $oldToken, null ), "null exp" );
		$this->assertFalse( $this->token->match( $oldToken, 60*60 ), "1 min exp" );
		$this->assertTrue( $this->token->match( $oldToken, 60*60*6 ), "six hour exp" );
	}

	public function testMatchStateless() {
		$stateless = TestingAccessWrapper::newFromObject(
			new StatelessLoggedOutToken( '127.0.0.1', 'secret', 'salt' )
		);

		$this->assertTrue( $this->token->match( $stateless->toString() ), "cur ts" );
		$this->assertFalse( $this->token->match(
			$stateless->toStringAtTimestamp( wfTimestamp( TS_UNIX ) - 61*60 )
		), "11 min ago" );
		$this->assertTrue( $this->token->match(
			$stateless->toStringAtTimestamp( wfTimestamp( TS_UNIX ) - 4*60 )
		), "4 min ago" );
	}

	public function testWasNew() {
		$this->assertFalse( $this->token->wasNew() );
		$token2 = new StatefulLoggedOutToken(
			'127.0.0.1', 'secret', 'secret2', wfTimestampNow(), 'salt', true
		);
		$this->assertTrue( $token2->wasNew() );
	}

	public function testLongSession() {
		$ts = wfTimestamp( TS_UNIX ) - 65*60;
		$token2 = new StatefulLoggedOutToken(
			'127.0.0.1', 'secret', 'secret2', $ts, 'salt', false
		);
		$stateless = new StatelessLoggedOutToken( '127.0.0.1', 'secret', 'salt' );
		$this->assertFalse( $token2->match( (string)$stateless ) );

		// Make sure "new" tokens are always compatible with the stateless tokens,
		// as "new" means the session is new in this request, so any submitted tokens
		// will be of the stateless style.
		$token2 = new StatefulLoggedOutToken(
			'127.0.0.1', 'secret', 'secret2', $ts, 'salt', true /* new */
		);
		$this->assertTrue( $token2->match( (string)$stateless ) );
	}

	/**
	 * @dataProvider provideDoesNotMatchOtherTokens
	 * @param $token \MediaWiki\Session\Token Token to test
	 * @param $reason string
	 */
	public function testDoesNotMatchOtherTokens( $token, $reason ) {
		$this->assertFalse( $this->token->match( (string)$token ), $reason );
	}

	public function provideDoesNotMatchOtherTokens() {
		$ip = '127.0.0.1';
		$s1 = 'secret';
		$s2 = 'secret2';
		$ts = wfTimestampNow();
		$salt = 'salt';
		$new = false;

		return [
			[ new StatelessLoggedOutToken( '::1', $s1, $salt ), "diff ip" ],
			[ new StatelessLoggedOutToken( $ip, 'top-secret', $salt ), "diff secret" ],
			[ new StatelessLoggedOutToken( $ip, $s1, 'organic sea-salt' ), "diff salt" ],
			[ new StatefulLoggedOutToken( $ip, $s1, 'secret', $ts, $salt, $new ), 'stateful diff secret' ],
			[ new StatefulLoggedOutToken( $ip, $s1, $s2, $ts, 'salty', $new ), 'stateful diff salt' ],
		];
	}

	/**
	 * A user without cookies, may have a "new" stateful token output, but the
	 * session won't persist, so next request they might have a "new" stateful
	 * token with a different secret. This would be the case if a user without
	 * cookies hit preview page, and then hit save page.
	 */
	public function testCookielessUser() {
		$ts = wfTimestampNow();
		$token = new StatefulLoggedOutToken(
			'127.0.0.1', 'secret', 'secret2', $ts, 'salt', true
		);
		$statelessToken = new StatelessLoggedOutToken( '127.0.0.1', 'secret', 'salt' );
		$token2 = new StatefulLoggedOutToken(
			'127.0.0.1', 'secret', 'OTHER SECRET', $ts, 'salt', true
		);
		$this->assertTrue( $token2->match( (string)$token ) );
		$this->assertTrue( $token2->match( (string)$statelessToken ) );
	}
}
