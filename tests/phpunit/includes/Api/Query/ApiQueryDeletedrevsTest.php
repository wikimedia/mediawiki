<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use WikitextContent;

/**
 * @group API
 * @group Database
 * @group medium
 * @covers \MediaWiki\Api\ApiQueryDeletedrevs
 */
class ApiQueryDeletedrevsTest extends ApiTestCase {

	private Title $testTitle;
	private User $user;

	protected function setUp(): void {
		parent::setUp();

		$this->user = $this->getTestUser( [ 'sysop', 'deletedhistory' ] )->getUser();
		$this->setGroupPermissions( 'sysop', 'deleterevision', true );

		$page = $this->getNonexistingTestPage( 'TestPage' );
		$page->doUserEditContent(
			new WikitextContent( 'Test content for SHA1 test' ),
			$this->user,
			'Test edit'
		);

		$this->testTitle = $page->getTitle();

		$this->deletePage( $page, 'Testing', $this->user );
	}

	public function testDeletedRevisionsWithSha1() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'deletedrevisions',
			'titles' => $this->testTitle->getPrefixedText(),
			'drvprop' => 'sha1|ids|content',
			'drvlimit' => '1',
			'drvslots' => 'main',
		], null, false, $this->user );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'pages', $result[0]['query'] );
		$page = reset( $result[0]['query']['pages'] );

		$this->assertArrayHasKey( 'deletedrevisions', $page );
		$rev = reset( $page['deletedrevisions'] );

		$this->assertArrayHasKey( 'sha1', $rev );
		$this->assertSame( '9a7af48d6f38ebdaa3fef9cb192d3f76e16a995f', $rev['sha1'] );
	}

	public function testDeletedRevisionsWithoutSha1() {
		$result = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'deletedrevisions',
			'titles' => $this->testTitle->getPrefixedText(),
			'drvprop' => 'ids',
			'drvlimit' => '1',
		], null, false, $this->user );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'pages', $result[0]['query'] );
		$page = reset( $result[0]['query']['pages'] );

		$this->assertArrayHasKey( 'deletedrevisions', $page );
		$rev = reset( $page['deletedrevisions'] );

		$this->assertArrayNotHasKey( 'sha1', $rev );
	}
}
