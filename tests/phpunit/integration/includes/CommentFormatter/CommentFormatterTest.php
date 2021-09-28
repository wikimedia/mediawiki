<?php

namespace MediaWiki\Tests\Integration\CommentFormatter;

use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\CommentFormatter\CommentItem;
use MediaWiki\CommentFormatter\CommentParser;
use MediaWiki\CommentFormatter\CommentParserFactory;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Permissions\SimpleAuthority;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Tests\Unit\CommentFormatter\CommentFormatterTestUtils;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use TitleValue;

/**
 * Trivial comment formatting with a mocked parser. Can't be a unit test because
 * of the wfMessage() calls.
 *
 * @covers \MediaWiki\CommentFormatter\CommentFormatter
 */
class CommentFormatterTest extends MediaWikiIntegrationTestCase {
	private function getParser() {
		return new class extends CommentParser {
			public function __construct() {
			}

			public function preprocess(
				string $comment, LinkTarget $selfLinkTarget = null, $samePage = false,
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

			public function preprocessUnsafe(
				$comment, LinkTarget $selfLinkTarget = null, $samePage = false, $wikiId = null,
				$enableSectionLinks = true
			) {
				return CommentFormatterTestUtils::dumpArray( [
					'comment' => $comment,
					'selfLinkTarget' => $selfLinkTarget,
					'samePage' => $samePage,
					'wikiId' => $wikiId,
					'enableSectionLinks' => $enableSectionLinks,
					'unsafe' => true
				] );
			}

			public function finalize( $comments ) {
				return $comments;
			}
		};
	}

	private function getParserFactory() {
		$parser = $this->getParser();
		return new class( $parser ) extends CommentParserFactory {
			private $parser;

			public function __construct( $parser ) {
				$this->parser = $parser;
			}

			public function create() {
				return $this->parser;
			}
		};
	}

	private function newCommentFormatter() {
		return new CommentFormatter( $this->getParserFactory() );
	}

	public function testCreateBatch() {
		$formatter = $this->newCommentFormatter();
		$result = $formatter->createBatch()
			->strings( [ 'key' => 'c' ] )
			->useBlock()
			->useParentheses()
			->samePage()
			->execute();
		$this->assertSame(
			[
				'key' =>
				// parentheses have to come after something so I guess it
				// makes sense that there is a space here
					' ' .
					'<span class="comment">(comment=c, samePage, !wikiId, enableSectionLinks)</span>'
			],
			$result
		);
	}

	public function testFormatItems() {
		$formatter = $this->newCommentFormatter();
		$result = $formatter->formatItems( [
			'key' => new CommentItem( 'c' )
		] );
		$this->assertSame(
			[ 'key' => 'comment=c, !samePage, !wikiId, enableSectionLinks' ],
			$result
		);
	}

	public function testFormat() {
		$formatter = $this->newCommentFormatter();
		$result = $formatter->format(
			'c',
			new TitleValue( 0, 'Page' ),
			true,
			'enwiki'
		);
		$this->assertSame(
			'comment=c, selfLinkTarget=0:Page, samePage, wikiId=enwiki, enableSectionLinks',
			$result
		);
	}

	public function testFormatBlock() {
		$formatter = $this->newCommentFormatter();
		$result = $formatter->formatBlock(
			'c',
			new TitleValue( 0, 'Page' ),
			true,
			'enwiki',
			true
		);
		$this->assertSame(
			' <span class="comment">' .
			'(comment=c, selfLinkTarget=0:Page, samePage, wikiId=enwiki, enableSectionLinks)' .
			'</span>',
			$result
		);
	}

	public function testFormatLinksUnsafe() {
		$formatter = $this->newCommentFormatter();
		$result = $formatter->formatLinksUnsafe(
			'c',
			new TitleValue( 0, 'Page' ),
			true,
			'enwiki'
		);
		$this->assertSame(
			'comment=c, selfLinkTarget=0:Page, samePage, wikiId=enwiki, !enableSectionLinks, unsafe',
			$result
		);
	}

	public function testFormatLinks() {
		$formatter = $this->newCommentFormatter();
		$result = $formatter->formatLinks(
			'c',
			new TitleValue( 0, 'Page' ),
			true,
			'enwiki'
		);
		$this->assertSame(
			'comment=c, selfLinkTarget=0:Page, samePage, wikiId=enwiki, !enableSectionLinks',
			$result
		);
	}

	public function testFormatStrings() {
		$formatter = $this->newCommentFormatter();
		$result = $formatter->formatStrings(
			[
				'a' => 'A',
				'b' => 'B'
			],
			new TitleValue( 0, 'Page' ),
			true,
			'enwiki'
		);
		$this->assertSame(
			[
				'a' => 'comment=A, selfLinkTarget=0:Page, samePage, wikiId=enwiki, enableSectionLinks',
				'b' => 'comment=B, selfLinkTarget=0:Page, samePage, wikiId=enwiki, enableSectionLinks'
			],
			$result
		);
	}

	public function testFormatStringsAsBlock() {
		$formatter = $this->newCommentFormatter();
		$result = $formatter->formatStringsAsBlock(
			[
				'a' => 'A',
				'b' => 'B'
			],
			new TitleValue( 0, 'Page' ),
			true,
			'enwiki',
			true
		);
		$this->assertSame(
			[
				'a' => ' <span class="comment">(' .
					'comment=A, selfLinkTarget=0:Page, samePage, wikiId=enwiki, enableSectionLinks' .
					')</span>',
				'b' => ' <span class="comment">(' .
					'comment=B, selfLinkTarget=0:Page, samePage, wikiId=enwiki, enableSectionLinks' .
					')</span>'
			],
			$result
		);
	}

	public function provideFormatRevision() {
		$normal = ' <span class="comment">(' .
			'comment=hello, selfLinkTarget=Page, !samePage, enableSectionLinks' .
			')</span>';
		$deleted = ' <span class="history-deleted comment"> ' .
			'<span class="comment">(edit summary removed)</span></span>';
		$deletedAllowed = ' <span class="history-deleted comment"> ' .
			'<span class="comment">(' .
			'comment=hello, selfLinkTarget=Page, !samePage, enableSectionLinks' .
			')</span></span>';

		return [
			'not deleted' => [
				'hello', false, false, false, true,
				$normal,
			],
			'deleted, for public, not allowed' => [
				'hello', true, true, false, true,
				$deleted
			],
			'deleted, for public, allowed' => [
				'hello', true, true, true, true,
				$deleted
			],
			'deleted, for private, not allowed' => [
				'hello', false, true, false, true,
				$deleted
			],
			'deleted, for private, allowed' => [
				'hello', false, true, true, true,
				$deletedAllowed,
			],
			'empty' => [
				'', false, false, false, true,
				''
			],
			'asterisk' => [
				'*', false, false, false, true,
				''
			]
		];
	}

	/**
	 * @param string $text
	 * @param bool $isDeleted
	 * @param bool $isAllowed
	 * @return array{RevisionRecord,Authority}
	 * @throws \MWException
	 */
	private function makeRevisionAndAuthority( $text, $isDeleted, $isAllowed ) {
		$page = new PageIdentityValue( 1, 0, 'Page', false );
		$rev = new MutableRevisionRecord( $page );
		$comment = new \CommentStoreComment( 1, $text );
		$rev->setId( 100 );
		$rev->setComment( $comment );
		$rev->setVisibility( $isDeleted ? RevisionRecord::DELETED_COMMENT : 0 );
		$user = new UserIdentityValue( 1, 'Sysop' );
		$rights = $isAllowed ? [ 'deletedhistory' ] : [];
		$authority = new SimpleAuthority( $user, $rights );
		return [ $rev, $authority ];
	}

	/** @dataProvider provideFormatRevision */
	public function testFormatRevision( $comment, $isPublic, $isDeleted, $isAllowed, $useParentheses,
		$expected
	) {
		list( $rev, $authority ) = $this->makeRevisionAndAuthority(
			$comment, $isDeleted, $isAllowed );
		$formatter = $this->newCommentFormatter();
		$result = $formatter->formatRevision(
			$rev,
			$authority,
			false,
			$isPublic
		);
		$this->assertSame(
			$expected,
			$result
		);
	}

	/** @dataProvider provideFormatRevision */
	public function testFormatRevisions( $comment, $isPublic, $isDeleted, $isAllowed, $useParentheses,
		$expected
	) {
		list( $rev, $authority ) = $this->makeRevisionAndAuthority(
			$comment, $isDeleted, $isAllowed );
		$formatter = $this->newCommentFormatter();
		$result = $formatter->formatRevisions(
			[ 'key' => $rev ],
			$authority,
			false,
			$isPublic
		);
		$this->assertSame(
			[ 'key' => $expected ],
			$result
		);
	}

	public function testFormatRevisionsById() {
		list( $rev, $authority ) = $this->makeRevisionAndAuthority(
			'hello', false, false );
		$formatter = $this->newCommentFormatter();
		$result = $formatter->formatRevisions(
			[ 'key' => $rev ],
			$authority,
			false,
			false,
			true,
			true
		);
		$this->assertSame(
			[ 100 => ' <span class="comment">(' .
				'comment=hello, selfLinkTarget=Page, !samePage, enableSectionLinks' .
				')</span>'
			],
			$result
		);
	}

	/** @dataProvider provideFormatRevision */
	public function testCreateRevisionBatch( $comment, $isPublic, $isDeleted, $isAllowed, $useParentheses,
		$expected
	) {
		list( $rev, $authority ) = $this->makeRevisionAndAuthority(
			$comment, $isDeleted, $isAllowed );
		$formatter = $this->newCommentFormatter();
		$result = $formatter->createRevisionBatch()
			->authority( $authority )
			->hideIfDeleted( $isPublic )
			->useParentheses()
			->revisions( [ 'key' => $rev ] )
			->execute();
		$this->assertSame(
			[ 'key' => $expected ],
			$result
		);
	}

	public function testCreateRevisionBatchById() {
		list( $rev, $authority ) = $this->makeRevisionAndAuthority(
			'hello', false, false );
		$formatter = $this->newCommentFormatter();
		$result = $formatter->createRevisionBatch()
			->authority( $authority )
			->useParentheses()
			->indexById()
			->revisions( [ 'key' => $rev ] )
			->execute();
		$this->assertSame(
			[ 100 => ' <span class="comment">(' .
				'comment=hello, selfLinkTarget=Page, !samePage, enableSectionLinks' .
				')</span>'
			],
			$result
		);
	}
}
