<?php

/**
 * @covers ApiQuerySites
 *
 * @group API
 * @group Database
 * @group medium
 *
 * @licence GNU GPL v2+
 * @author Adam Shorland
 */
class ApiQuerySitesTest extends ApiTestCase {

	public function setUp() {
		parent::setUp();
		TestSites::insertIntoDb();
	}

	public function testApiRequest() {
		$params = array( 'action' => 'query', 'meta' => 'sites' );
		$expectedProps = array( 'globalId', 'domain', 'group', 'language' );

		list( $result,, ) = $this->doApiRequest( $params );

		$this->assertArrayHasKey( 'sites', $result );
		$this->assertArrayHasSites( $result['sites'], $expectedProps );
	}

	public function testApiRequestWithSpecificProp() {
		$params = array( 'action' => 'query', 'meta' => 'sites', 'stprops' => 'globalId|group' );
		$expectedProps = array( 'globalId', 'group' );

		list( $result,, ) = $this->doApiRequest( $params );

		$this->assertArrayHasKey( 'sites', $result );
		$this->assertArrayHasSites( $result['sites'], $expectedProps );
	}

	/**
	 * @param array $sites
	 * @param array $expectedProps
	 */
	private function assertArrayHasSites( $sites, $expectedProps ) {
		/** @var Site $site */
		foreach( TestSites::getSites() as $site ) {
			$this->assertSiteInArray( $site, $sites );
			foreach( $expectedProps as $prop ) {
				$this->assertSiteHasProp( $sites[$site->getGlobalId()], $site, $prop );
			}
		}
	}

	/**
	 * @param Site $site
	 * @param array $array
	 */
	private function assertSiteInArray( $site, $array ) {
		$this->assertArrayHasKey( $site->getGlobalId(), $array );
	}

	/**
	 * @param array $resultSite result from the api
	 * @param Site $actualSite site from TestSites
	 * @param string $prop
	 */
	private function assertSiteHasProp( $resultSite, $actualSite, $prop ) {
		switch ( $prop ) {
			case 'globalId':
				$this->assertArrayHasKey( 'globalId', $resultSite );
				$this->assertEquals( $actualSite->getGlobalId(), $resultSite['globalId'] );
				break;
			case 'domain':
				$this->assertArrayHasKey( 'domain', $resultSite );
				$this->assertEquals( $actualSite->getDomain(), $resultSite['domain'] );
				break;
			case 'group':
				$this->assertArrayHasKey( 'group', $resultSite );
				$this->assertEquals( $actualSite->getGroup(), $resultSite['group'] );
				break;
			case 'language':
				$this->assertArrayHasKey( 'language', $resultSite );
				$this->assertEquals( $actualSite->getLanguageCode(), $resultSite['language'] );
				break;
		}
	}

} 