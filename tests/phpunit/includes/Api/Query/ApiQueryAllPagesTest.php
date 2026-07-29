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

	public function testBasicAllPages() {
		$this->editPage(
			Title::makeTitle( NS_MAIN, 'TestPage1' ),
			'Content 1',
			'test',
			NS_MAIN,
			$this->getTestSysop()->getAuthority()
		);

		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allpages',
			'apnamespace' => NS_MAIN,
		] );

		$this->assertArrayHasKey( 'query', $result[0] );
		$this->assertArrayHasKey( 'allpages', $result[0]['query'] );
		$this->assertNotEmpty( $result[0]['query']['allpages'] );
	}

	public function testFilterRedirects() {
		$this->editPage(
			Title::makeTitle( NS_MAIN, 'RedirectPage' ),
			'#REDIRECT [[TargetPage]]',
			'test redirect',
			NS_MAIN,
			$this->getTestSysop()->getAuthority()
		);
		$this->editPage(
			Title::makeTitle( NS_MAIN, 'NormalPage' ),
			'Normal content',
			'test normal',
			NS_MAIN,
			$this->getTestSysop()->getAuthority()
		);

		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allpages',
			'apnamespace' => NS_MAIN,
			'apfilterredir' => 'redirects',
		] );

		$this->assertArrayHasKey( 'allpages', $result[0]['query'] );
		$pageTitles = array_column( $result[0]['query']['allpages'], 'title' );
		$this->assertContains( 'RedirectPage', $pageTitles );
		$this->assertNotContains( 'NormalPage', $pageTitles );

		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allpages',
			'apnamespace' => NS_MAIN,
			'apfilterredir' => 'nonredirects',
		] );

		$this->assertArrayHasKey( 'allpages', $result[0]['query'] );
		$pageTitles = array_column( $result[0]['query']['allpages'], 'title' );
		$this->assertNotContains( 'RedirectPage', $pageTitles );
		$this->assertContains( 'NormalPage', $pageTitles );
	}

	public function testPagination() {
		$this->editPage(
			Title::makeTitle( NS_MAIN, 'PageA' ),
			'Content A',
			'test A',
			NS_MAIN,
			$this->getTestSysop()->getAuthority()
		);
		$this->editPage(
			Title::makeTitle( NS_MAIN, 'PageB' ),
			'Content B',
			'test B',
			NS_MAIN,
			$this->getTestSysop()->getAuthority()
		);
		$this->editPage(
			Title::makeTitle( NS_MAIN, 'PageC' ),
			'Content C',
			'test C',
			NS_MAIN,
			$this->getTestSysop()->getAuthority()
		);

		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allpages',
			'apnamespace' => NS_MAIN,
			'aplimit' => 2,
		] );

		$this->assertArrayHasKey( 'allpages', $result[0]['query'] );
		$this->assertCount( 2, $result[0]['query']['allpages'] );

		if ( isset( $result[0]['query']['continue'] ) ) {
			$continue = $result[0]['query']['continue']['apcontinue'];
			$result2 = $this->doApiRequest( [
				'action' => 'query',
				'list' => 'allpages',
				'apnamespace' => NS_MAIN,
				'aplimit' => 2,
				'apcontinue' => $continue,
			] );

			$this->assertArrayHasKey( 'allpages', $result2[0]['query'] );
		}
	}

	public function testPrefixFilter() {
		$this->editPage(
			Title::makeTitle( NS_MAIN, 'TestPageAlpha' ),
			'Content',
			'test',
			NS_MAIN,
			$this->getTestSysop()->getAuthority()
		);
		$this->editPage(
			Title::makeTitle( NS_MAIN, 'TestPageBeta' ),
			'Content',
			'test',
			NS_MAIN,
			$this->getTestSysop()->getAuthority()
		);
		$this->editPage(
			Title::makeTitle( NS_MAIN, 'OtherPage' ),
			'Content',
			'test',
			NS_MAIN,
			$this->getTestSysop()->getAuthority()
		);

		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allpages',
			'apnamespace' => NS_MAIN,
			'apprefix' => 'TestPage',
		] );

		$this->assertArrayHasKey( 'allpages', $result[0]['query'] );
		$pageTitles = array_column( $result[0]['query']['allpages'], 'title' );
		$this->assertContains( 'TestPageAlpha', $pageTitles );
		$this->assertContains( 'TestPageBeta', $pageTitles );
		$this->assertNotContains( 'OtherPage', $pageTitles );
	}

	public function testDirection() {
		$this->editPage(
			Title::makeTitle( NS_MAIN, 'PageA' ),
			'Content',
			'test',
			NS_MAIN,
			$this->getTestSysop()->getAuthority()
		);
		$this->editPage(
			Title::makeTitle( NS_MAIN, 'PageB' ),
			'Content',
			'test',
			NS_MAIN,
			$this->getTestSysop()->getAuthority()
		);

		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allpages',
			'apnamespace' => NS_MAIN,
			'apdir' => 'ascending',
		] );

		$this->assertArrayHasKey( 'allpages', $result[0]['query'] );
		$pageTitles = array_column( $result[0]['query']['allpages'], 'title' );
		$firstTitle = $pageTitles[0] ?? null;
		$this->assertNotNull( $firstTitle );

		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'allpages',
			'apnamespace' => NS_MAIN,
			'apdir' => 'descending',
		] );

		$this->assertArrayHasKey( 'allpages', $result[0]['query'] );
		$pageTitlesDesc = array_column( $result[0]['query']['allpages'], 'title' );
		$lastTitle = end( $pageTitlesDesc );
		$this->assertNotNull( $lastTitle );
	}
}
