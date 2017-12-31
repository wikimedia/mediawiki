<?php

use MediaWiki\Session\Token;

/**
 * @group API
 * @group medium
 * @covers ApiCheckToken
 */
class ApiCheckTokenTest extends ApiTestCase {

	/**
	 * Test result of checking previously queried token (should be valid)
	 */
	public function testCheckTokenValid() {
		// Query token which will be checked later
		$tokens = $this->doApiRequest( [
			'action' => 'query',
			'meta' => 'tokens',
		] );

		$data = $this->doApiRequest( [
			'action' => 'checktoken',
			'type' => 'csrf',
			'token' => $tokens[0]['query']['tokens']['csrftoken'],
		], $tokens[1]->getSessionArray() );

		$this->assertEquals( 'valid', $data[0]['checktoken']['result'] );
		$this->assertArrayHasKey( 'generated', $data[0]['checktoken'] );
	}

	/**
	 * Test result of checking invalid token
	 */
	public function testCheckTokenInvalid() {
		$session = [];
		$data = $this->doApiRequest( [
			'action' => 'checktoken',
			'type' => 'csrf',
			'token' => 'invalid_token',
		], $session );

		$this->assertEquals( 'invalid', $data[0]['checktoken']['result'] );
	}

	/**
	 * Test result of checking token with negative max age (should be expired)
	 */
	public function testCheckTokenExpired() {
		// Query token which will be checked later
		$tokens = $this->doApiRequest( [
			'action' => 'query',
			'meta' => 'tokens',
		] );

		$data = $this->doApiRequest( [
			'action' => 'checktoken',
			'type' => 'csrf',
			'token' => $tokens[0]['query']['tokens']['csrftoken'],
			'maxtokenage' => -1,
		], $tokens[1]->getSessionArray() );

		$this->assertEquals( 'expired', $data[0]['checktoken']['result'] );
		$this->assertArrayHasKey( 'generated', $data[0]['checktoken'] );
	}

	/**
	 * Test if using token with incorrect suffix will produce a warning
	 */
	public function testCheckTokenSuffixWarning() {
		// Query token which will be checked later
		$tokens = $this->doApiRequest( [
			'action' => 'query',
			'meta' => 'tokens',
		] );

		// Get token and change the suffix
		$token = $tokens[0]['query']['tokens']['csrftoken'];
		$token = substr( $token, 0, -strlen( Token::SUFFIX ) ) . urldecode( Token::SUFFIX );

		$data = $this->doApiRequest( [
			'action' => 'checktoken',
			'type' => 'csrf',
			'token' => $token,
			'errorformat' => 'raw',
		], $tokens[1]->getSessionArray() );

		$this->assertEquals( 'invalid', $data[0]['checktoken']['result'] );
		$this->assertArrayHasKey( 'warnings', $data[0] );
		$this->assertCount( 1, $data[0]['warnings'] );
		$this->assertEquals( 'checktoken', $data[0]['warnings'][0]['module'] );
		$this->assertEquals( 'checktoken-percentencoding', $data[0]['warnings'][0]['code'] );
	}

}
