<?php

namespace MediaWiki\Tests\Unit\CommentFormatter;

use MediaWiki\CommentFormatter\CommentBatch;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\CommentFormatter\CommentItem;
use MediaWiki\Title\TitleValue;
use MediaWikiUnitTestCase;

/**
 * Trivial unit test with the universe mocked.
 *
 * @covers \MediaWiki\CommentFormatter\CommentBatch
 * @covers \MediaWiki\CommentFormatter\CommentItem
 * @covers \MediaWiki\CommentFormatter\StringCommentIterator
 */
class CommentBatchTest extends MediaWikiUnitTestCase {
	/** @var array */
	private $calls;

	private function getFormatter() {
		return new class( $this->calls ) extends CommentFormatter {
			private $calls;

			public function __construct( &$calls ) {
				$this->calls =& $calls;
			}

			public function formatItemsInternal(
				$items, $selfLinkTarget = null, $samePage = null, $wikiId = null,
				$enableSectionLinks = null, $useBlock = null, $useParentheses = null
			) {
				$paramDump = CommentFormatterTestUtils::dumpArray( [
					'selfLinkTarget' => $selfLinkTarget,
					'samePage' => $samePage,
					'wikiId' => $wikiId,
					'enableSectionLinks' => $enableSectionLinks,
					'useBlock' => $useBlock,
					'useParentheses' => $useParentheses,
				] );
				if ( $paramDump !== '' ) {
					$lines = [ $paramDump ];
				}
				/** @var CommentItem $item */
				foreach ( $items as $i => $item ) {
					$lines[] = "$i. " . CommentFormatterTestUtils::dumpArray( [
						'comment' => $item->comment,
						'selfLinkTarget' => $item->selfLinkTarget,
						'samePage' => $item->samePage,
						'wikiId' => $item->wikiId,
					] );
				}
				$this->calls[] = $lines;
				return [];
			}
		};
	}

	private function newBatch() {
		return new CommentBatch( $this->getFormatter() );
	}

	public function testComments() {
		$this->newBatch()
			->comments( [ new CommentItem( 'c' ) ] )
			->execute();
		$this->assertSame(
			[
				[ '0. comment=c' ],
			],
			$this->calls
		);
	}

	public function testStrings() {
		$this->newBatch()
			->strings( [
				1 => 'one',
				3 => 'three',
				5 => 'five',
			] )
			->execute();
		$this->assertSame(
			[
				[
					'1. comment=one',
					'3. comment=three',
					'5. comment=five'
				],
			],
			$this->calls
		);
	}

	public static function provideUseBlock() {
		return [
			[
				null,
				[ '0. comment=c' ]
			],
			[
				false,
				[ '!useBlock', '0. comment=c' ]
			],
			[
				true,
				[ 'useBlock', '0. comment=c' ]
			],
		];
	}

	/** @dataProvider provideUseBlock */
	public function testUseBlock( $useBlock, $expected ) {
		$batch = $this->newBatch()
			->strings( [ 'c' ] );
		if ( $useBlock !== null ) {
			$batch->useBlock( $useBlock );
		}
		$batch->execute();
		$this->assertSame( [ $expected ], $this->calls );
	}

	public static function provideUseParentheses() {
		return [
			[
				null,
				[ '0. comment=c' ]
			],
			[
				false,
				[ '!useParentheses', '0. comment=c' ]
			],
			[
				true,
				[ 'useParentheses', '0. comment=c' ]
			],
		];
	}

	/** @dataProvider provideUseParentheses */
	public function testUseParentheses( $useParentheses, $expected ) {
		$batch = $this->newBatch()
			->strings( [ 'c' ] );
		if ( $useParentheses !== null ) {
			$batch->useParentheses( $useParentheses );
		}
		$batch->execute();
		$this->assertSame( [ $expected ], $this->calls );
	}

	public static function provideSelfLinkTarget() {
		return [
			[
				null,
				[ '0. comment=c' ]
			],
			[
				[ 0, 'Page' ],
				[ 'selfLinkTarget=0:Page', '0. comment=c' ]
			],
		];
	}

	/** @dataProvider provideSelfLinkTarget */
	public function testSelfLinkTarget( $selfLinkTarget, $expected ) {
		$batch = $this->newBatch()
			->strings( [ 'c' ] );
		if ( $selfLinkTarget !== null ) {
			$batch->selfLinkTarget( new TitleValue( $selfLinkTarget[0], $selfLinkTarget[1] ) );
		}
		$batch->execute();
		$this->assertSame( [ $expected ], $this->calls );
	}

	public static function provideEnableSectionLinks() {
		return [
			[
				null,
				[ '0. comment=c' ]
			],
			[
				false,
				[ '!enableSectionLinks', '0. comment=c' ]
			],
			[
				true,
				[ 'enableSectionLinks', '0. comment=c' ]
			],
		];
	}

	/** @dataProvider provideEnableSectionLinks */
	public function testEnableSectionLinks( $enableSectionLinks, $expected ) {
		$batch = $this->newBatch()
			->strings( [ 'c' ] );
		if ( $enableSectionLinks !== null ) {
			$batch->enableSectionLinks( $enableSectionLinks );
		}
		$batch->execute();
		$this->assertSame( [ $expected ], $this->calls );
	}

	public static function provideDisableSectionLinks() {
		return [
			[
				null,
				[ '0. comment=c' ]
			],
			[
				true,
				[ '!enableSectionLinks', '0. comment=c' ]
			],
		];
	}

	/** @dataProvider provideDisableSectionLinks */
	public function testDisableSectionLinks( $disableSectionLinks, $expected ) {
		$batch = $this->newBatch()
			->strings( [ 'c' ] );
		if ( $disableSectionLinks !== null ) {
			$batch->disableSectionLinks();
		}
		$batch->execute();
		$this->assertSame( [ $expected ], $this->calls );
	}

	public static function provideSamePage() {
		return [
			[
				null,
				[ '0. comment=c' ]
			],
			[
				false,
				[ '!samePage', '0. comment=c' ]
			],
			[
				true,
				[ 'samePage', '0. comment=c' ]
			],
		];
	}

	/** @dataProvider provideSamePage */
	public function testSamePage( $samePage, $expected ) {
		$batch = $this->newBatch()
			->strings( [ 'c' ] );
		if ( $samePage !== null ) {
			$batch->samePage( $samePage );
		}
		$batch->execute();
		$this->assertSame( [ $expected ], $this->calls );
	}

	public static function provideWikiId() {
		return [
			[
				null,
				[ '0. comment=c' ]
			],
			[
				'enwiki',
				[ 'wikiId=enwiki', '0. comment=c' ]
			],
		];
	}

	/** @dataProvider provideWikiId */
	public function testWikiId( $wikiId, $expected ) {
		$batch = $this->newBatch()
			->strings( [ 'c' ] );
		if ( $wikiId !== null ) {
			$batch->wikiId( $wikiId );
		}
		$batch->execute();
		$this->assertSame( [ $expected ], $this->calls );
	}

	public function testItemSelfLinkTarget() {
		$item = ( new CommentItem( 'c' ) )
			->selfLinkTarget( new TitleValue( 0, 'Page' ) );
		$this->newBatch()
			->comments( [ $item ] )
			->execute();
		$this->assertSame(
			[
				[ '0. comment=c, selfLinkTarget=0:Page' ],
			],
			$this->calls
		);
	}

	public function testItemSamePage() {
		$item = ( new CommentItem( 'c' ) )
			->samePage();
		$this->newBatch()
			->comments( [ $item ] )
			->execute();
		$this->assertSame(
			[
				[ '0. comment=c, samePage' ],
			],
			$this->calls
		);
	}

	public function testItemWikiId() {
		$item = ( new CommentItem( 'c' ) )
			->wikiId( 'enwiki' );
		$this->newBatch()
			->comments( [ $item ] )
			->execute();
		$this->assertSame(
			[
				[ '0. comment=c, wikiId=enwiki' ],
			],
			$this->calls
		);
	}

}
