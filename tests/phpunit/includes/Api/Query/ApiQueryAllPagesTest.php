<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Title\Title;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiQueryAllPages
 */
class ApiQueryAllPagesTest extends ApiTestCase {
	/**
	 * Test T27702
	 * Prefixes of API search requests are not handled with case sensitivity and may result
	 * in wrong search results
	 */
	public function testPrefixNormalizationSearchBug() {
		$title = Title::makeTitle( NS_CATEGORY, 'Template:xyz' );
		$this->editPage(
			$title,
			'Some text',
			'inserting content',
			NS_MAIN,
			$this->getTestSysop()->getAuthority()
		);

		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allpages',
			'apnamespace' => NS_CATEGORY,
			'apprefix' => 'Template:x' ] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allpages', $result[0]['query'] );
		$this->assertContains( 'Category:Template:xyz', $result[0]['query']['allpages'][0] );
	}
}
