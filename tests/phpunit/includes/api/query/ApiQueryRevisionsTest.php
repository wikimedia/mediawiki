<?php

namespace MediaWiki\Tests\Api\Query;

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Api\ApiTestCase;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\UserIdentityValue;

/**
 * @group API
 * @group Database
 * @group medium
 * @covers \MediaWiki\Api\ApiQueryRevisions
 */
class ApiQueryRevisionsTest extends ApiTestCase {
	use TempUserTestTrait;

	/**
	 * @group medium
	 */
	public function testContentComesWithContentModelAndFormat() {
		$pageName = 'Help:' . __METHOD__;
		$page = $this->getExistingTestPage( $pageName );
		$user = $this->getTestUser()->getUser();
		$page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new WikitextContent( 'Some text' ) )
			->saveRevision( CommentStoreComment::newUnsavedComment( 'inserting content' ) );

		$apiResult = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => $pageName,
			'rvprop' => 'content',
			'rvslots' => 'main',
		] );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'pages', $apiResult[0]['query'] );
		foreach ( $apiResult[0]['query']['pages'] as $page ) {
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

	/**
	 * @group Database
	 * @group medium
	 */
	public function testRevisionMadeByTempUser() {
		$this->enableAutoCreateTempUser();
		$tempUser = new UserIdentityValue( 1236764321, '~1' );

		$title = $this->getNonexistingTestPage( 'TestPage1' )->getTitle();
		$this->editPage(
			$title,
			'Some Content',
			'Create Page',
			NS_MAIN,
			new UltimateAuthority( $tempUser )
		);

		$apiResult = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => 'TestPage1'
		] );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'pages', $apiResult[0]['query'] );
		$this->assertArrayHasKey( 'temp', $apiResult[0]['query']['pages'][1]['revisions'][0] );
		$this->assertTrue( $apiResult[0]['query']['pages'][1]['revisions'][0]['temp'] );
	}

	/**
	 * @group medium
	 */
	public function testResolvesPrevNextInDiffto() {
		$pageName = 'Help:' . __METHOD__;
		$page = $this->getExistingTestPage( $pageName );
		$user = $this->getTestUser()->getUser();

		$revRecord = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new WikitextContent( 'Some text' ) )
			->saveRevision( CommentStoreComment::newUnsavedComment( 'inserting more content' ) );

		[ $rvDiffToPrev ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => $pageName,
			'rvdiffto' => 'prev',
		] );

		$this->assertSame(
			$revRecord->getId(),
			$rvDiffToPrev['query']['pages'][$page->getId()]['revisions'][0]['revid']
		);
		$this->assertSame(
			$revRecord->getId(),
			$rvDiffToPrev['query']['pages'][$page->getId()]['revisions'][0]['diff']['to']
		);
		$this->assertSame(
			$revRecord->getParentId(),
			$rvDiffToPrev['query']['pages'][$page->getId()]['revisions'][0]['diff']['from']
		);

		[ $rvDiffToNext ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => $pageName,
			'rvdiffto' => 'next',
			'rvdir' => 'newer'
		] );

		$this->assertSame(
			$revRecord->getParentId(),
			$rvDiffToNext['query']['pages'][$page->getId()]['revisions'][0]['revid']
		);
		$this->assertSame(
			$revRecord->getId(),
			$rvDiffToNext['query']['pages'][$page->getId()]['revisions'][0]['diff']['to']
		);
		$this->assertSame(
			$revRecord->getParentId(),
			$rvDiffToNext['query']['pages'][$page->getId()]['revisions'][0]['diff']['from']
		);
	}

	/**
	 * @dataProvider provideSectionNewTestCases
	 * @param string $pageContent
	 * @param string $expectedSectionContent
	 * @group medium
	 */
	public function testSectionNewReturnsEmptyContentForPageWithSection(
		$pageContent,
		$expectedSectionContent
	) {
		$pageName = 'Help:' . __METHOD__;
		$page = $this->getExistingTestPage( $pageName );
		$user = $this->getTestUser()->getUser();
		$revRecord = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new WikitextContent( $pageContent ) )
			->saveRevision( CommentStoreComment::newUnsavedComment( 'inserting content' ) );

		[ $response ] = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'revisions',
			'revids' => $revRecord->getId(),
			'rvprop' => 'content|ids',
			'rvslots' => 'main',
			'rvsection' => 'new'
		] );

		$this->assertSame(
			$expectedSectionContent,
			$response['query']['pages'][$page->getId()]['revisions'][0]['slots']['main']['content']
		);
	}

	public static function provideSectionNewTestCases() {
		yield 'page with existing section' => [
			"==A section==\ntext",
			''
		];
		yield 'page with no sections' => [
			'This page has no sections',
			'This page has no sections'
		];
	}
}
