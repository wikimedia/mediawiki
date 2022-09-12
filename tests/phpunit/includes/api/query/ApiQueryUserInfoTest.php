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

	public function testCanCreateAccount() {
		$params = [
			'action' => 'query',
			'meta' => 'userinfo',
			'uiprop' => 'cancreateaccount',
		];
		$user = $this->getTestUser()->getUser();
		$apiResult = $this->doApiRequest( $params, null, false, $user );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'userinfo', $apiResult[0]['query'] );
		$this->assertArrayHasKey( 'cancreateaccount', $apiResult[0]['query']['userinfo'] );
		$this->assertTrue( $apiResult[0]['query']['userinfo']['cancreateaccount'] );
		$this->assertArrayNotHasKey( 'cancreateaccounterror', $apiResult[0]['query']['userinfo'] );

		$user = $this->getMutableTestUser()->getUser();
		$status = $this->getServiceContainer()->getBlockUserFactory()->newBlockUser(
			$user,
			$this->getTestSysop()->getUser(),
			'infinity',
			'',
			[ 'isCreateAccountBlocked' => true ]
		)->placeBlock();
		if ( !$status->isGood() ) {
			$this->fail( $status->getWikiText( false, false, 'en' ) );
		}
		$apiResult = $this->doApiRequest( $params, null, false, $user );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'userinfo', $apiResult[0]['query'] );
		$this->assertArrayHasKey( 'cancreateaccount', $apiResult[0]['query']['userinfo'] );
		$this->assertFalse( $apiResult[0]['query']['userinfo']['cancreateaccount'] );
		$this->assertArrayHasKey( 'cancreateaccounterror', $apiResult[0]['query']['userinfo'] );
	}
}
