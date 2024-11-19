<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Title\Title;

/**
 * @group API
 * @group Database
 * @group medium
 * @covers \MediaWiki\Api\ApiQueryAllRevisions
 */
class ApiQueryAllRevisionsTest extends ApiTestCase {

	/**
	 * @group medium
	 */
	public function testContentComesWithContentModelAndFormat() {
		$title = Title::makeTitle( NS_HELP, 'TestContentComesWithContentModelAndFormat' );
		$this->editPage(
			$title,
			'Some text',
			'inserting content',
			NS_MAIN,
			$this->getTestSysop()->getAuthority()
		);
		$this->editPage(
			$title,
			'Some other text',
			'adding revision',
			NS_MAIN,
			$this->getTestSysop()->getAuthority()
		);

		$apiResult = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allrevisions',
			'arvprop' => 'content',
			'arvslots' => 'main',
			'arvdir' => 'older',
		] );

		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'allrevisions', $apiResult[0]['query'] );
		$this->assertArrayHasKey( 0, $apiResult[0]['query']['allrevisions'] );
		$this->assertArrayHasKey( 'title', $apiResult[0]['query']['allrevisions'][0] );
		$this->assertSame( $title->getPrefixedText(), $apiResult[0]['query']['allrevisions'][0]['title'] );
		$this->assertArrayHasKey( 'revisions', $apiResult[0]['query']['allrevisions'][0] );
		$this->assertCount( 2, $apiResult[0]['query']['allrevisions'][0]['revisions'] );

		foreach ( $apiResult[0]['query']['allrevisions'] as $page ) {
			$this->assertArrayHasKey( 'revisions', $page );
			foreach ( $page['revisions'] as $revision ) {
				$this->assertArrayHasKey( 'slots', $revision );
				$this->assertArrayHasKey( 'main', $revision['slots'] );
				$this->assertArrayHasKey( 'contentformat', $revision['slots']['main'],
					'contentformat should be included when asking content so client knows how to interpret it'
				);
				$this->assertArrayHasKey( 'contentmodel', $revision['slots']['main'],
					'contentmodel should be included when asking content so client knows how to interpret it'
				);
			}
		}
	}
}
