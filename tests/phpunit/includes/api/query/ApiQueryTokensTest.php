<?php

/**
 * @group API
 * @group medium
 * @covers ApiQueryTokens
 */
class ApiQueryTokensTest extends ApiTestCase {

	public function testGetCsrfToken() {
		$params = [
			'action' => 'query',
			'meta' => 'tokens',
			'type' => 'csrf',
		];

		$user = $this->getTestUser()->getUser();

		$apiResult = $this->doApiRequest( $params, null, false, $user );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'tokens', $apiResult[0]['query'] );
		$this->assertArrayHasKey( 'csrftoken', $apiResult[0]['query']['tokens'] );
		$this->assertStringEndsWith( '+\\', $apiResult[0]['query']['tokens']['csrftoken'] );
	}

	public function testGetAllTokens() {
		$params = [
			'action' => 'query',
			'meta' => 'tokens',
			'type' => '*',
		];

		$user = $this->getTestUser()->getUser();

		$apiResult = $this->doApiRequest( $params, null, false, $user );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'tokens', $apiResult[0]['query'] );

		// MW core has 7 token types (createaccount, csrf, login, patrol, rollback, userrights, watch)
		$this->assertGreaterThanOrEqual( 7, count( $apiResult[0]['query']['tokens'] ) );
	}
}
