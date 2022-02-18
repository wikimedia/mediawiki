<?php

namespace MediaWiki\Tests\Unit\CommentFormatter;

use ArrayIterator;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\CommentFormatter\RevisionCommentBatch;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\SimpleAuthority;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;

/**
 * Trivial unit test with the universe mocked.
 *
 * @covers \MediaWiki\CommentFormatter\RevisionCommentBatch
 */
class RevisionCommentBatchTest extends MediaWikiUnitTestCase {
	private function getFormatter( $callback ) {
		return new class( $callback ) extends CommentFormatter {
			private $callback;

			public function __construct( $callback ) {
				$this->callback = $callback;
			}

			public function formatRevisions(
				$revisions, Authority $authority, $samePage = false, $isPublic = false,
				$useParentheses = true, $indexById = false
			) {
				( $this->callback )( get_defined_vars() );
			}
		};
	}

	private function newBatch( $callback ) {
		return new RevisionCommentBatch(
			$this->getFormatter( $callback )
		);
	}

	private function getAuthority() {
		return new SimpleAuthority(
			new UserIdentityValue( 1, 'Sysop' ),
			[]
		);
	}

	public function testAuthority() {
		$authority = $this->getAuthority();
		$batch = $this->newBatch(
			function ( $params ) use ( $authority ) {
				$this->assertSame( $authority, $params['authority'] );
			}
		);
		$batch->authority( $authority )->execute();
	}

	public function testNoAuthority() {
		$this->expectException( \TypeError::class );
		$batch = $this->newBatch(
			static function ( $params ) {
			}
		);
		$batch
			->revisions( [] )
			->execute();
	}

	public function testRevisions() {
		$revisions = new ArrayIterator( [] );
		$batch = $this->newBatch(
			function ( $params ) use ( $revisions ) {
				$this->assertSame( $revisions, $params['revisions'] );
				// Check default booleans while we have them
				$this->assertFalse( $params['samePage'] );
				$this->assertFalse( $params['isPublic'] );
				$this->assertFalse( $params['useParentheses'] );
				$this->assertFalse( $params['indexById'] );
			}
		);
		$batch
			->authority( $this->getAuthority() )
			->revisions( $revisions )
			->execute();
	}

	public function testSamePage() {
		$batch = $this->newBatch(
			function ( $params ) {
				$this->assertTrue( $params['samePage'] );
			}
		);
		$batch
			->authority( $this->getAuthority() )
			->samePage()
			->execute();
	}

	public function testUseParentheses() {
		$batch = $this->newBatch(
			function ( $params ) {
				$this->assertTrue( $params['useParentheses'] );
			}
		);
		$batch
			->authority( $this->getAuthority() )
			->useParentheses()
			->execute();
	}

	public function hideIfPrivate() {
		$batch = $this->newBatch(
			function ( $params ) {
				$this->assertTrue( $params['isPublic'] );
			}
		);
		$batch
			->authority( $this->getAuthority() )
			->hideIfDeleted()
			->execute();
	}

	public function indexById() {
		$batch = $this->newBatch(
			function ( $params ) {
				$this->assertTrue( $params['indexById'] );
			}
		);
		$batch
			->authority( $this->getAuthority() )
			->indexById()
			->execute();
	}
}
