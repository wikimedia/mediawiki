<?php

namespace MediaWiki\Tests\Integration\CommentFormatter;

use MediaWiki\CommentFormatter\CommentParser;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Tests\Unit\CommentFormatter\CommentFormatterTestUtils;
use MediaWiki\Tests\Unit\DummyServicesTrait;

/**
 * @covers \MediaWiki\CommentFormatter\RowCommentFormatter
 * @covers \MediaWiki\CommentFormatter\RowCommentIterator
 */
class RowCommentFormatterTest extends \MediaWikiIntegrationTestCase {
	use DummyServicesTrait;

	private function getParser() {
		return new class extends CommentParser {
			public function __construct() {
			}

			public function preprocess(
				string $comment, ?LinkTarget $selfLinkTarget = null, $samePage = false,
				$wikiId = null, $enableSectionLinks = true
			) {
				if ( $comment === '' || $comment === '*' ) {
					return $comment; // Hack to make it work more like the real parser
				}
				return CommentFormatterTestUtils::dumpArray( [
					'comment' => $comment,
					'selfLinkTarget' => $selfLinkTarget,
					'samePage' => $samePage,
					'wikiId' => $wikiId,
					'enableSectionLinks' => $enableSectionLinks
				] );
			}
		};
	}

	private function newCommentFormatter() {
		return new RowCommentFormatter(
			$this->getDummyCommentParserFactory( $this->getParser() ),
			$this->getServiceContainer()->getCommentStore()
		);
	}

	public function testFormatRows() {
		$rows = [
			(object)[
				'comment_text' => 'hello',
				'comment_data' => null,
				'namespace' => '0',
				'title' => 'Page',
				'id' => 1
			]
		];
		$commentFormatter = $this->newCommentFormatter();
		$result = $commentFormatter->formatRows(
			$rows,
			'comment',
			'namespace',
			'title',
			'id'
		);
		$this->assertSame(
			[
				1 => 'comment=hello, selfLinkTarget=0:Page, !samePage, !wikiId, enableSectionLinks'
			],
			$result
		);
	}

	public function testRowsWithFormatItems() {
		$rows = [
			(object)[
				'comment_text' => 'hello',
				'comment_data' => null,
				'namespace' => '0',
				'title' => 'Page',
				'id' => 1
			]
		];
		$commentFormatter = $this->newCommentFormatter();
		$result = $commentFormatter->formatItems(
			$commentFormatter->rows( $rows )
				->commentKey( 'comment' )
				->namespaceField( 'namespace' )
				->titleField( 'title' )
				->indexField( 'id' )
		);
		$this->assertSame(
			[
				1 => 'comment=hello, selfLinkTarget=0:Page, !samePage, !wikiId, enableSectionLinks'
			],
			$result
		);
	}

	public function testRowsWithCreateBatch() {
		$rows = [
			(object)[
				'comment_text' => 'hello',
				'comment_data' => null,
				'namespace' => '0',
				'title' => 'Page',
				'id' => 1
			]
		];
		$commentFormatter = $this->newCommentFormatter();
		$result = $commentFormatter->createBatch()
			->comments(
				$commentFormatter->rows( $rows )
					->commentKey( 'comment' )
					->namespaceField( 'namespace' )
					->titleField( 'title' )
					->indexField( 'id' )
			)
			->samePage( true )
			->execute();
		$this->assertSame(
			[
				1 => 'comment=hello, selfLinkTarget=0:Page, samePage, !wikiId, enableSectionLinks'
			],
			$result
		);
	}
}
