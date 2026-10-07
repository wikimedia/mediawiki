<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Title\Title;

/**
 * @group API
 * @group Database
 * @covers MediaWiki\Api\ApiQueryAllDeletedRevisions
 */
class ApiQueryAllDeletedRevisionsTest extends ApiTestCase {

	public function testFromToPrefixParameter() {
		$this->overrideConfigValues( [
			MainConfigNames::CapitalLinks => false,
		] );
		$performer = $this->getTestSysop()->getAuthority();

		$title = Title::makeTitle( NS_MAIN, 'pageM' );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$this->editPage( $page, 'Some text', 'Create', NS_MAIN, $performer );
		$this->deletePage( $page, 'Delete', $performer );

		$userTitle = Title::makeTitle( NS_USER, 'PageU' );
		$userPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $userTitle );
		$this->editPage( $userPage, 'Some text', 'Create', NS_MAIN, $performer );
		$this->deletePage( $userPage, 'Delete', $performer );

		$expectedResult0 = [ 'ns' => $title->getNamespace(), 'title' => $title->getPrefixedDbKey() ];
		$expectedResult1 = [ 'ns' => $userTitle->getNamespace(), 'title' => $userTitle->getPrefixedDbKey() ];

		// Search the page with prefix
		[ $result ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'alldeletedrevisions',
			'adrdir' => 'newer',
			'adrnamespace' => '0|2',
			'adrprefix' => 'page',
		], null, false, $performer );

		$this->assertArrayHasKey( 'query', $result );
		$this->assertArrayHasKey( 'alldeletedrevisions', $result['query'] );
		$this->assertArrayContains( $expectedResult0, $result['query']['alldeletedrevisions'][0] );
		$this->assertArrayContains( $expectedResult1, $result['query']['alldeletedrevisions'][1] );

		// Search the page with from
		[ $result ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'alldeletedrevisions',
			'adrdir' => 'newer',
			'adrnamespace' => '0|2',
			'adrfrom' => 'pageA',
		], null, false, $performer );

		$this->assertArrayHasKey( 'query', $result );
		$this->assertArrayHasKey( 'alldeletedrevisions', $result['query'] );
		$this->assertArrayContains( $expectedResult0, $result['query']['alldeletedrevisions'][0] );
		$this->assertArrayContains( $expectedResult1, $result['query']['alldeletedrevisions'][1] );

		// Search the page with to
		[ $result ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'alldeletedrevisions',
			'adrdir' => 'newer',
			'adrnamespace' => '0|2',
			'adrto' => 'pageZ',
		], null, false, $performer );

		$this->assertArrayHasKey( 'query', $result );
		$this->assertArrayHasKey( 'alldeletedrevisions', $result['query'] );
		$this->assertArrayContains( $expectedResult0, $result['query']['alldeletedrevisions'][0] );
		$this->assertArrayContains( $expectedResult1, $result['query']['alldeletedrevisions'][1] );
	}

	/**
	 * Deleted revisions all report page ID 0, so this also covers the read check
	 * not being shared between different deleted pages.
	 *
	 * @covers \MediaWiki\Api\ApiQueryRevisionsBase
	 */
	public function testRevisionDetailsOfUnreadableDeletedPageAreHidden() {
		$readableTitle = Title::makeTitle( NS_HELP, 'ReadablePage' );
		$restrictedTitle = Title::makeTitle( NS_HELP, 'RestrictedPage' );
		$sysop = $this->getTestSysop()->getAuthority();
		$this->editPage( $readableTitle, 'Readable text', 'readable summary', NS_MAIN, $sysop );
		$this->editPage( $restrictedTitle, 'Restricted text', 'restricted summary', NS_MAIN, $sysop );
		$this->deletePage( $readableTitle->toPageIdentity(), 'Delete', $sysop );
		$this->deletePage( $restrictedTitle->toPageIdentity(), 'Delete', $sysop );

		$this->setTemporaryHook( 'getUserPermissionsErrors',
			static function ( Title $page, &$user, $action, &$result ) use ( $restrictedTitle ) {
				if ( $page->equals( $restrictedTitle ) && $action === 'read' ) {
					$result = false;
					return false;
				}
			} );

		[ $result ] = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'alldeletedrevisions',
			'adrprop' => 'comment',
		], null, false, $sysop );
		$json = json_encode( $result );
		$this->assertStringContainsString( 'readable summary', $json );
		$this->assertStringNotContainsString( 'restricted summary', $json );
	}
}
