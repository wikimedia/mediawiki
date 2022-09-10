<?php

/**
 * @group API
 * @group Database
 * @group medium
 * @covers ApiQueryUserInfo
 */
class ApiQueryUserInfoTest extends ApiTestCase {

	/**
	 * @throws MWContentSerializationException
	 * @throws MWException
	 * @covers ApiQueryUserInfo::getLatestContributionTime
	 */
	public function testTimestamp() {
		$clock = MWTimestamp::convert( TS_UNIX, '20100101000000' );
		MWTimestamp::setFakeTime( static function () use ( &$clock ) {
			return $clock += 1000;
		} );

		$params = [
			'action' => 'query',
			'meta' => 'userinfo',
			'uiprop' => 'latestcontrib',
		];

		$page = $this->getNonexistingTestPage();
		$performer = $this->getTestUser()->getAuthority();

		$apiResult = $this->doApiRequest( $params, null, false, $performer );
		$this->assertArrayNotHasKey( 'continue', $apiResult[0] );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'userinfo', $apiResult[0]['query'] );
		$this->assertArrayNotHasKey( 'latestcontrib', $apiResult[0]['query']['userinfo'] );

		$status = $this->editPage( $page, 'one' );
		$this->assertStatusOK( $status );
		$status = $this->editPage( $page, 'two' );
		$this->assertStatusOK( $status );

		$revisionTimestamp = MWTimestamp::convert( TS_ISO_8601, $page->getTimestamp() );

		$apiResult = $this->doApiRequest( $params, null, false, $performer );
		$this->assertArrayNotHasKey( 'continue', $apiResult[0] );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'userinfo', $apiResult[0]['query'] );
		$this->assertArrayHasKey( 'latestcontrib', $apiResult[0]['query']['userinfo'] );
		$queryTimestamp = $apiResult[0]['query']['userinfo']['latestcontrib'];
		$this->assertSame( $revisionTimestamp, $queryTimestamp );
	}
}
