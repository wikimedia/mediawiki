<?php

namespace MediaWiki\Tests\Unit\ChangeTags;

use MediaWiki\ChangeTags\ChangeTagsFormatter;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Language\MessageLocalizer;
use MediaWiki\Message\Message;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\ChangeTags\ChangeTagsFormatter
 */
class ChangeTagsFormatterTest extends MediaWikiUnitTestCase {

	private function getObjectUnderTest( ChangeTagsStore $changeTagsStore ): ChangeTagsFormatter {
		return new ChangeTagsFormatter( $changeTagsStore );
	}

	/** @dataProvider provideFormatTagsAsSummaryListForNoTags */
	public function testFormatTagsAsSummaryListForNoTags( ?string $tags ): void {
		$mockChangeTagsStore = $this->createMock( ChangeTagsStore::class );
		$mockChangeTagsStore->method( 'listDefinedTags' )
			->willReturn( [ 'mw-reverted' ] );

		$this->assertSame(
			[ '', [] ],
			$this->getObjectUnderTest( $mockChangeTagsStore )->formatTagsAsSummaryList(
				$tags,
				$this->createMock( MessageLocalizer::class )
			)
		);
	}

	public static function provideFormatTagsAsSummaryListForNoTags(): array {
		return [
			'Tags argument is null' => [ null ],
			'Tags argument is empty string' => [ '' ],
			'Tags argument is a single comma' => [ ',' ],
		];
	}

	public function testFormatTagsAsSummaryListForMissingShortDescriptionMessage(): void {
		$mockMessage = $this->createMock( Message::class );
		$mockMessage->method( 'isDisabled' )
			->willReturn( true );
		$mockMessage->method( 'exists' )
			->willReturn( false );
		$mockMessage->method( 'inContentLanguage' )
			->willReturnSelf();

		$localizer = $this->createMock( MessageLocalizer::class );
		$localizer->method( 'msg' )
			->willReturnCallback( static fn () => $mockMessage );

		$mockChangeTagsStore = $this->createMock( ChangeTagsStore::class );
		$mockChangeTagsStore->method( 'listDefinedTags' )
			->willReturn( [ 'test' ] );

		$this->assertSame(
			[
				'',
				[ 'mw-tag-test' ]
			],
			$this->getObjectUnderTest( $mockChangeTagsStore )->formatTagsAsSummaryList( 'test', $localizer )
		);
	}
}
